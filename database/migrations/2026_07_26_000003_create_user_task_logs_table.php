<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_task_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('custom_task_id')->constrained('custom_tasks')->cascadeOnDelete();
            $table->boolean('visited')->default(false);
            $table->boolean('claimed')->default(false);
            $table->decimal('coins_awarded', 12, 4)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'custom_task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_task_logs');
    }
};
