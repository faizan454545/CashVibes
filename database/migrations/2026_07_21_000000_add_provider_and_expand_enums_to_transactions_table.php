<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = DB::getSchemaBuilder()->getColumnListing('transactions');
        if (! in_array('provider', $columns)) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('provider')->nullable()->after('source_ref');
            });
        }

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('ALTER TABLE transactions RENAME TO transactions_old');
            DB::statement("
                CREATE TABLE transactions (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                    source_ref VARCHAR NOT NULL,
                    provider VARCHAR NULL,
                    amount DECIMAL(12,4) NOT NULL,
                    type VARCHAR NOT NULL DEFAULT 'credit',
                    status VARCHAR NOT NULL DEFAULT 'pending',
                    metadata VARCHAR NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ");
            DB::statement('CREATE INDEX idx_transactions_user_id_created_at ON transactions(user_id, created_at)');
            DB::statement('CREATE INDEX idx_transactions_source_ref ON transactions(source_ref)');
            DB::statement('INSERT INTO transactions SELECT id, user_id, source_ref, NULL, amount, type, status, metadata, created_at, updated_at FROM transactions_old');
            DB::statement('DROP TABLE transactions_old');
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('provider');
        });
    }
};
