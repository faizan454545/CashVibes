<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('payout_gateway', ['easypaisa', 'jazzcash', 'binance_pay']);
            $table->integer('requested_coins')->unsigned();
            $table->decimal('fiat_pkr_equivalent', 10, 2);
            $table->string('account_number_or_id');
            $table->string('account_title_receiver')->nullable();
            $table->enum('payout_status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['payout_status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
