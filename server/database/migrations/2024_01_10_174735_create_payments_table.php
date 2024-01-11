<?php

use App\Models\Payment;
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

        Schema::create('payments', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger("merchant_invoice_id");
            $table->unsignedBigInteger('status_id')->default(1);
            $table->unsignedBigInteger("payment_gateway_id");
            $table->unsignedDecimal("amount");
            $table->unsignedDecimal("amount_paid");
            $table->unsignedBigInteger('currency_id')->default(1);


            $table->foreign("currency_id")->references("id")->on("currencies");
            $table->foreign("payment_gateway_id")->references("id")->on("payment_gateways");

            $table->timestamps();
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
