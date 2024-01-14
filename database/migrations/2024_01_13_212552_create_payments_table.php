<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Payment;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create('payments', function(Blueprint $table) {
			$table->id();
            $table->unsignedBigInteger("merchant_invoice_id");
            $table->unsignedTinyInteger('status_id')->default(Payment::STATUS_NEW);
            $table->unsignedBigInteger("payment_gateway_id");
            $table->unsignedDecimal("amount");
            $table->unsignedDecimal("amount_paid");

            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('payments');
	}
};
