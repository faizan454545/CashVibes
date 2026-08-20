<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'access_key')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('access_key', 64)->after('referral_code')->default('');
            });
        }

        if (! Schema::hasColumn('users', 'last_login_ip')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            });
        }

        if (! Schema::hasIndex('users', ['access_key'])) {
            // Backfill empty access keys first
            $users = User::where('access_key', '')->orWhereNull('access_key')->get();
            foreach ($users as $user) {
                $user->update(['access_key' => strtoupper(bin2hex(random_bytes(24)))]);
            }
            Schema::table('users', function (Blueprint $table) {
                $table->unique('access_key');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['access_key']);
            $table->dropColumn(['access_key', 'last_login_ip']);
        });
    }
};
