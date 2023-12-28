<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('merchant_id'); // ID of merchant
            $table->integer('payment_id'); // Merchant's payment ID
            $table->string('status'); // Payment status
            $table->integer('amount'); // Payment amount
            $table->integer('amount_paid'); // Actually paid amount (in merchant's currency)
            $table->string('signature'); // Signature
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
