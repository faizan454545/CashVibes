<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('source_ref');
            $table->decimal('amount', 12, 4);
            $table->enum('type', ['credit', 'debit']);
            $table->enum('status', ['pending', 'settled', 'failed'])->default('pending');
            $table->string('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['source_ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
