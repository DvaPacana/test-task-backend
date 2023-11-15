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
        $paymentStatuses = [
            Payment::NEW,
            Payment::COMPLETED,
            Payment::PENDING,
            Payment::REJECTED,
            Payment::EXPIRED
        ];

        Schema::create('payments', function (Blueprint $table) use ($paymentStatuses){
            $table->id();
            $table->unsignedBigInteger("merchant_invoice_id");
            $table->enum("status", $paymentStatuses);
            $table->string("payment_gateway");
            $table->unsignedDecimal("amount");
            $table->unsignedDecimal("amount_paid");
            $table->char("currency", 3)->default("USD");

            $table->timestamps();

            $table->unique(["merchant_invoice_id", "payment_gateway"]);
            $table->foreign("currency")->references("currency")->on("currencies");
            $table->foreign("payment_gateway")->references("name")->on("payment_gateways");
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
