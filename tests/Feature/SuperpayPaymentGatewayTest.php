<?php

namespace Tests\Feature;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperpayPaymentGatewayTest extends TestCase
{
    use RefreshDatabase;


    public function test_gateway_can_process_payment()
    {
        $data = [
            "project" => 816,
            "invoice" => 73,
            "status" => "completed",
            "amount" => 700,
            "amount_paid" => 700,
            "rand" => "SNuHufEJ",
        ];

        $response = $this->post(
            route('payment.process', ['gateway' => 'superpay']),
            $data,
            [
                'Authorization' => 'd84eb9036bfc2fa7f46727f101c73c73',
                'Content-Type' => 'multipart/form-data',
            ],
        );

        $response->assertStatus(200);

        $model = Payment::query()
            ->where('merchant_id', $data['project'])
            ->where('payment_id', $data['invoice'])
            ->first();

        $this->assertModelExists($model);
    }

    public function test_gateway_cannot_process_payment_with_invalid_sign()
    {
        $data = [
            "project" => 816,
            "invoice" => 73,
            "status" => "completed",
            "amount" => 700,
            "amount_paid" => 7000,
            "rand" => "SNuHufEJ",
        ];

        $response = $this->post(
            route('payment.process', ['gateway' => 'superpay']),
            $data,
            [
                'Authorization' => 'd84eb9036bfc2fa7f46727f101c73c73',
                'Content-Type' => 'multipart/form-data',
            ],
        );
        $response->assertStatus(403);
    }


}
