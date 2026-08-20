<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimeWallPostbackController extends Controller
{
    public function handle(Request $request)
    {
        $ip = $request->ip();

        $whitelist = Config::get('services.timewall.whitelist_ips');
        if (! empty($whitelist) && ! in_array($ip, $whitelist)) {
            Log::warning('TimeWall Postback: Unauthorized IP', ['ip' => $ip]);

            return response('OK', 200);
        }

        $userId = $request->query('userId');
        $revenue = $request->query('revenue');
        $currencyAmount = $request->query('currencyAmount');
        $transactionId = $request->query('transactionId');
        $type = $request->query('type');
        $hash = $request->query('hash');

        if (! $userId || ! $revenue || ! $currencyAmount || ! $transactionId || ! $type || ! $hash) {
            Log::warning('TimeWall Postback: Missing parameters', $request->query());

            return response('OK', 200);
        }

        $secretKey = Config::get('services.timewall.secret_key');
        $expectedHash = hash('sha256', $userId.$revenue.$secretKey);

        if ($hash !== $expectedHash) {
            Log::warning('TimeWall Postback: Invalid hash', [
                'user_id' => $userId,
                'transaction_id' => $transactionId,
            ]);

            return response('OK', 200);
        }

        $user = User::find($userId);
        if (! $user) {
            Log::warning('TimeWall Postback: User not found', ['user_id' => $userId]);

            return response('OK', 200);
        }

        $amount = (float) $currencyAmount;

        if ($type === 'credit') {
            $existing = Transaction::where('source_ref', 'TW_'.$transactionId)->first();
            if ($existing) {
                return response('OK', 200);
            }

            DB::transaction(function () use ($user, $amount, $transactionId, $revenue) {
                $user->increment('coin_balance', $amount);

                $user->transactions()->create([
                    'source_ref' => 'TW_'.$transactionId,
                    'provider' => 'timewall',
                    'amount' => $amount,
                    'type' => 'offerwall',
                    'status' => 'approved',
                    'metadata' => json_encode([
                        'provider' => 'timewall',
                        'transaction_id' => $transactionId,
                        'revenue_usd' => $revenue,
                    ]),
                ]);
            });

            Log::info('TimeWall Postback: Credited', [
                'user_id' => $userId,
                'amount' => $amount,
                'transaction_id' => $transactionId,
            ]);
        } elseif ($type === 'chargeback') {
            DB::transaction(function () use ($user, $amount, $transactionId, $revenue) {
                $existingCredit = Transaction::where('source_ref', 'TW_'.$transactionId)
                    ->where('status', 'approved')
                    ->first();

                if ($existingCredit) {
                    $user->decrement('coin_balance', $amount);
                    $existingCredit->update(['status' => 'rejected']);
                } else {
                    $alreadyReversed = Transaction::where('source_ref', 'TW_'.$transactionId)
                        ->where('status', 'rejected')
                        ->exists();

                    if (! $alreadyReversed) {
                        $user->transactions()->create([
                            'source_ref' => 'TW_'.$transactionId,
                            'provider' => 'timewall',
                            'amount' => $amount,
                            'type' => 'offerwall',
                            'status' => 'rejected',
                            'metadata' => json_encode([
                                'provider' => 'timewall',
                                'transaction_id' => $transactionId,
                                'revenue_usd' => $revenue,
                                'note' => 'Chargeback without prior credit',
                            ]),
                        ]);
                    }
                }
            });

            Log::info('TimeWall Postback: Chargeback', [
                'user_id' => $userId,
                'amount' => $amount,
                'transaction_id' => $transactionId,
            ]);
        }

        return response('OK', 200);
    }
}
