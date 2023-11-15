<?php

namespace Tests\Unit;

use App\Payment\Concrete\PaymentGatewayOne;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PaymentGatewayOneTest extends TestCase
{
    private function getMethod($name) 
    {
        $class = new ReflectionClass(PaymentGatewayOne::class);
        $method = $class->getMethod($name);
        return $method;
    }      

    /**
     * @test
     */
    public function can_validate_valid_sign(): void
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

        $paymentGatewayOne = new PaymentGatewayOne(6, "KaTf5tZYHx4v7pgZ");
        $sign = $data["sign"];
        unset($data["sign"]);
        $validateSignMethod = $this->getMethod("validateSign");
        $this->assertTrue($validateSignMethod->invokeArgs($paymentGatewayOne, [$data, $sign]));
    }

}
