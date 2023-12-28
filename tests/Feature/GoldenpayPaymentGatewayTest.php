<?php

namespace Tests\Feature;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoldenpayPaymentGatewayTest extends TestCase
{
    use RefreshDatabase;


    public function test_gateway_can_process_payment()
    {
        $data = [
            "merchant_id" => 6,
            "payment_id" => 13,
            "status" => "completed",
            "amount" => 500,
            "amount_paid" => 500,
            "timestamp" => 1654103837,
            "sign" => "f027612e0e6cb321ca161de060237eeb97e46000da39d3add08d09074f931728"
        ];

        $response = $this->postJson(
            route('payment.process', ['gateway' => 'goldenpay']),
            $data,
        );

        $response->assertStatus(200);

        $model = Payment::query()
            ->where('merchant_id', $data['merchant_id'])
            ->where('payment_id', $data['payment_id'])
            ->first();
        $this->assertModelExists($model);
    }

    public function test_gateway_cannot_process_payment_with_invalid_sign()
    {
        $data = [
            "merchant_id" => 6,
            "payment_id" => 13,
            "status" => "completed",
            "amount" => 500,
            "amount_paid" => 5000,
            "timestamp" => 1654103837,
            "sign" => "f027612e0e6cb321ca161de060237eeb97e46000da39d3add08d09074f931728"
        ];

        $response = $this->postJson(
            route('payment.process', ['gateway' => 'goldenpay']),
            $data,
        );

        $response->assertStatus(403);
    }


}
