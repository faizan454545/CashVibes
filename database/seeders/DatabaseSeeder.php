<?php

namespace Database\Seeders;

use App\Models\Referral;
use App\Models\User;
use App\Models\Withdrawal;
use App\Services\ReferralService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    private function generateAccessKey(): string
    {
        return strtoupper(bin2hex(random_bytes(24)));
    }

    public function run(): void
    {
        $referralService = app(ReferralService::class);

        // ========================================
        // ADMIN USER (Main Test Account)
        // ========================================
        $admin = User::where('email', 'admin')->first();

        if ($admin) {
            $admin->update([
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'coin_balance' => 1240.50,
                'name' => 'Admin',
                'ip_address' => '127.0.0.1',
                'last_login_at' => now()->subHours(2),
            ]);
        } else {
            $admin = User::create([
                'google_id' => 'admin-'.uniqid(),
                'name' => 'Admin',
                'email' => 'admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'coin_balance' => 1240.50,
                'referral_code' => strtoupper('CV-ADMIN-'.rand(100, 999)),
                'access_key' => $this->generateAccessKey(),
                'ip_address' => '127.0.0.1',
                'last_login_at' => now()->subHours(2),
            ]);
        }

        // --- Admin Transactions (Realistic History) ---
        $adminTransactions = [
            ['source_ref' => 'DEPOSIT_EXTERNAL', 'amount' => 1000.00, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 14],
            ['source_ref' => 'NODE_STAKE_01', 'amount' => 125.00, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 12],
            ['source_ref' => 'VIBE_REWARD_GEN', 'amount' => 88.90, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 8],
            ['source_ref' => 'POOL_DISTRIBUTION', 'amount' => 12.40, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 7],
            ['source_ref' => 'POOL_DISTRIBUTION', 'amount' => 12.40, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 6],
            ['source_ref' => 'WALLET_WITHDRAWAL', 'amount' => 500.00, 'type' => 'debit', 'status' => 'settled', 'days_ago' => 5],
            ['source_ref' => 'INTERNAL_TRANSFER', 'amount' => 45.20, 'type' => 'debit', 'status' => 'pending', 'days_ago' => 4],
            ['source_ref' => 'NODE_STAKE_01', 'amount' => 125.00, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 3],
            ['source_ref' => 'POOL_DISTRIBUTION', 'amount' => 12.40, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 2],
            ['source_ref' => 'NODE_STAKE_01', 'amount' => 125.00, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 1],
            ['source_ref' => 'VIBE_REWARD_GEN', 'amount' => 88.90, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 0],
            ['source_ref' => 'POOL_DISTRIBUTION', 'amount' => 12.40, 'type' => 'credit', 'status' => 'settled', 'days_ago' => 0],
        ];

        foreach ($adminTransactions as $tx) {
            $admin->transactions()->create([
                'source_ref' => $tx['source_ref'],
                'amount' => $tx['amount'],
                'type' => $tx['type'],
                'status' => $tx['status'],
                'metadata' => $tx['source_ref'] === 'DEPOSIT_EXTERNAL' ? json_encode(['source' => 'initial_deposit']) : null,
                'created_at' => now()->subDays($tx['days_ago'])->addHours(rand(8, 18)),
            ]);
        }

        // --- Admin Pending Withdrawals ---
        Withdrawal::create([
            'user_id' => $admin->id,
            'payout_gateway' => 'easypaisa',
            'requested_coins' => 500,
            'fiat_pkr_equivalent' => 150.00,
            'account_number_or_id' => '03123456789',
            'account_title_receiver' => 'Admin User',
            'payout_status' => 'pending',
        ]);

        Withdrawal::create([
            'user_id' => $admin->id,
            'payout_gateway' => 'binance_pay',
            'requested_coins' => 300,
            'fiat_pkr_equivalent' => 90.00,
            'account_number_or_id' => '123456789',
            'account_title_receiver' => null,
            'payout_status' => 'pending',
        ]);

        Withdrawal::create([
            'user_id' => $admin->id,
            'payout_gateway' => 'jazzcash',
            'requested_coins' => 250,
            'fiat_pkr_equivalent' => 75.00,
            'account_number_or_id' => '03987654321',
            'account_title_receiver' => 'Admin JazzCash',
            'payout_status' => 'pending',
        ]);

        // ========================================
        // REFERRAL TEST USERS
        // ========================================
        $referralUsers = [
            ['name' => 'USER_VIBE_88', 'email' => 'vibe88@example.com', 'days_ago' => 2],
            ['name' => 'CORE_NODE_X', 'email' => 'corenode@example.com', 'days_ago' => 5],
            ['name' => 'NETWORK_USER_42', 'email' => 'network42@example.com', 'days_ago' => 8],
        ];

        foreach ($referralUsers as $userData) {
            $user = User::create([
                'google_id' => 'test-'.uniqid(),
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'coin_balance' => 150.00 + rand(0, 500),
                'referred_by' => $admin->id,
                'referral_code' => strtoupper('CV-'.strtoupper(substr($userData['name'], 0, 5)).'-'.rand(100, 999)),
                'access_key' => $this->generateAccessKey(),
                'ip_address' => '192.168.1.'.rand(1, 254),
                'created_at' => now()->subDays($userData['days_ago']),
                'last_login_at' => now()->subDays(rand(0, 2)),
            ]);

            $user->transactions()->create([
                'source_ref' => 'REGISTRATION_BONUS',
                'amount' => config('app.registration_bonus'),
                'type' => 'credit',
                'status' => 'settled',
                'created_at' => $user->created_at,
            ]);

            $user->transactions()->create([
                'source_ref' => 'NODE_STAKE_01',
                'amount' => 25.00 + rand(0, 50),
                'type' => 'credit',
                'status' => 'settled',
                'created_at' => $user->created_at->addDays(1),
            ]);

            $admin->referralsMade()->create([
                'referee_id' => $user->id,
                'is_first_task_done' => true,
                'reward_triggered_at' => now()->subDays(rand(1, $userData['days_ago'])),
                'created_at' => $user->created_at,
            ]);

            $admin->credit(config('app.referral_bonus_amt'), 'REFERRAL_BONUS', json_encode([
                'referee_id' => $user->id,
                'referee_name' => $user->name,
            ]));
        }

        // ========================================
        // EXTRA STANDALONE USERS (for testing)
        // ========================================
        $extraUsers = [
            ['name' => 'PHANTOM_NODE_7', 'email' => 'phantom7@example.com'],
            ['name' => 'HASH_MINER_X', 'email' => 'hashminer@example.com'],
        ];

        foreach ($extraUsers as $extraData) {
            User::create([
                'google_id' => 'extra-'.uniqid(),
                'name' => $extraData['name'],
                'email' => $extraData['email'],
                'password' => Hash::make('password123'),
                'coin_balance' => 100 + rand(0, 300),
                'referral_code' => strtoupper('CV-'.strtoupper(substr($extraData['name'], 0, 5)).'-'.rand(100, 999)),
                'access_key' => $this->generateAccessKey(),
                'ip_address' => '10.0.0.'.rand(1, 254),
                'created_at' => now()->subDays(rand(3, 10)),
                'last_login_at' => now()->subDays(rand(0, 3)),
            ]);
        }

        echo "Database seeded successfully!\n";
        echo "Admin username: admin\n";
        echo "Admin password: admin123\n";
        echo "Referral Code: CV-ALPHA-092\n";
        echo 'Coin Balance: '.$admin->coin_balance." Coins\n";
        echo "Pending Withdrawals: 3\n";
        echo 'Total Transactions: '.$admin->transactions()->count()."\n";
    }
}
