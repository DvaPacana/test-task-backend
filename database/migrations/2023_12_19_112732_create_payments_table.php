<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID пользователя
            $table->string('payment_id'); // ID платежа от шлюза
            $table->integer('amount'); // Сумма платежа в центах
            $table->string('status'); // Статус платежа
            $table->integer('amount_paid')->nullable(); // Фактически оплаченная сумма
            $table->unsignedBigInteger('timestamp');
            $table->string('gateway'); // Идентификатор шлюза

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
