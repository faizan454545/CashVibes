<?php

namespace App\Http\Controllers;

use App\Models\AdminEarning;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BitLabsPostbackController extends Controller
{
    public function handle(Request $request)
    {
        $userId = $request->query('user_id');
        $amount = $request->query('amount');
        $transactionId = $request->query('transaction_id');
        $hash = $request->query('hash');

        if (! $userId || ! $amount || ! $transactionId || ! $hash) {
            Log::warning('BitLabs Postback: Missing parameters', $request->query());

            return response('200 OK', 200);
        }

        $secretKey = Config::get('services.bitlabs.server_key') ?: Config::get('services.bitlabs.secret_key');
        $expectedHash = md5($userId.$amount.$transactionId.$secretKey);

        if ($hash !== $expectedHash) {
            Log::warning('BitLabs Postback: Invalid hash', [
                'user_id' => $userId,
                'transaction_id' => $transactionId,
            ]);

            return response('200 OK', 200);
        }

        $user = User::find($userId);
        if (! $user) {
            Log::warning('BitLabs Postback: User not found', ['user_id' => $userId]);

            return response('200 OK', 200);
        }

        $existing = Transaction::where('source_ref', 'BITLABS_'.$transactionId)->first();
        if ($existing) {
            return response('200 OK', 200);
        }

        $grossCoins = (float) $amount;
        $adminPct = config('app.admin_profit_pct');
        $userCoins = round($grossCoins * (1 - $adminPct), 4);
        $adminCoins = round($grossCoins * $adminPct, 4);

        DB::transaction(function () use ($user, $grossCoins, $userCoins, $adminCoins, $transactionId) {
            $user->increment('coin_balance', $userCoins);

            $user->transactions()->create([
                'source_ref' => 'BITLABS_'.$transactionId,
                'provider' => 'bitlabs',
                'amount' => $userCoins,
                'type' => 'offerwall',
                'status' => 'approved',
                'metadata' => json_encode([
                    'provider' => 'bitlabs',
                    'transaction_id' => $transactionId,
                    'gross_coins' => $grossCoins,
                    'admin_profit' => $adminCoins,
                ]),
            ]);

            AdminEarning::create([
                'user_id' => $user->id,
                'provider' => 'bitlabs',
                'transaction_id' => $transactionId,
                'gross_coins' => $grossCoins,
                'admin_coins' => $adminCoins,
                'user_coins' => $userCoins,
            ]);
        });

        Log::info('BitLabs Postback: Credited', [
            'user_id' => $userId,
            'gross' => $grossCoins,
            'user_coins' => $userCoins,
            'admin_profit' => $adminCoins,
            'transaction_id' => $transactionId,
        ]);

        return response('200 OK', 200);
    }
}
