<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CpxPostbackController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $ip = $request->ip();

        $whitelist = Config::get('services.cpx.whitelist_ips');
        if (! empty($whitelist) && ! in_array($ip, $whitelist)) {
            Log::warning('CPX Postback: Unauthorized IP', ['ip' => $ip]);

            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 200);
        }

        $transId = $request->query('trans_id');
        $userId = $request->query('user_id');
        $secureHash = $request->query('secure_hash');
        $amountLocal = $request->query('amount_local');
        $status = $request->query('status');

        if (! $transId || ! $userId || ! $secureHash || ! $amountLocal || ! $status) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters'], 200);
        }

        $expectedHash = md5($transId.'-'.Config::get('services.cpx.secure_hash'));
        if ($secureHash !== $expectedHash) {
            Log::warning('CPX Postback: Invalid hash', ['trans_id' => $transId, 'user_id' => $userId]);

            return response()->json(['status' => 'error', 'message' => 'Invalid hash'], 200);
        }

        $user = User::find($userId);
        if (! $user) {
            Log::warning('CPX Postback: User not found', ['user_id' => $userId]);

            return response()->json(['status' => 'error', 'message' => 'User not found'], 200);
        }

        $amount = (float) $amountLocal;

        if ((int) $status === 1) {
            $existing = Transaction::where('source_ref', 'CPX_'.$transId)->first();
            if ($existing) {
                return response()->json(['status' => 'ok', 'message' => 'Already processed'], 200);
            }

            DB::transaction(function () use ($user, $amount, $transId) {
                $user->increment('coin_balance', $amount);

                $user->transactions()->create([
                    'source_ref' => 'CPX_'.$transId,
                    'provider' => 'cpx',
                    'amount' => $amount,
                    'type' => 'offerwall',
                    'status' => 'approved',
                    'metadata' => json_encode([
                        'provider' => 'cpx',
                        'trans_id' => $transId,
                    ]),
                ]);
            });

            Log::info('CPX Postback: Credited', ['user_id' => $userId, 'amount' => $amount, 'trans_id' => $transId]);
        } elseif ((int) $status === 2) {
            DB::transaction(function () use ($user, $amount, $transId) {
                $existingCredit = Transaction::where('source_ref', 'CPX_'.$transId)
                    ->where('status', 'approved')
                    ->first();

                if ($existingCredit) {
                    $user->decrement('coin_balance', $amount);
                    $existingCredit->update(['status' => 'rejected']);
                } else {
                    $alreadyReversed = Transaction::where('source_ref', 'CPX_'.$transId)
                        ->where('status', 'rejected')
                        ->exists();

                    if (! $alreadyReversed) {
                        $user->transactions()->create([
                            'source_ref' => 'CPX_'.$transId,
                            'provider' => 'cpx',
                            'amount' => $amount,
                            'type' => 'offerwall',
                            'status' => 'rejected',
                            'metadata' => json_encode([
                                'provider' => 'cpx',
                                'trans_id' => $transId,
                                'note' => 'Reversed without prior credit',
                            ]),
                        ]);
                    }
                }
            });

            Log::info('CPX Postback: Reversed', ['user_id' => $userId, 'amount' => $amount, 'trans_id' => $transId]);
        }

        return response()->json(['status' => 'ok']);
    }
}
