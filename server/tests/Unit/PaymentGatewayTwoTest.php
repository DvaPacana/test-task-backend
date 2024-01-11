<?php

namespace Tests\Unit;

use App\Payment\Concrete\PaymentGatewayOne;
use App\Payment\Concrete\PaymentGatewayTwo;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PaymentGatewayTwoTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    private function getMethod($name): \ReflectionMethod
    {
        $class = new ReflectionClass(PaymentGatewayTwo::class);
        $method = $class->getMethod($name);
        return $method;
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function can_validate_valid_sign(): void
    {
        $body = [
            "project" => 816,
            "invoice" => 73,
            "status" => "completed",
            "amount" => 700,
            "amount_paid" => 700,
            "rand" => "SNuHufEJ",
        ];

        $sign = "d84eb9036bfc2fa7f46727f101c73c73";
        $paymentGatewayTwo = new PaymentGatewayTwo(816, "rTaasVHeteGbhwBx");
        $validateSignMethod = $this->getMethod("validateSign");

        $this->assertTrue($validateSignMethod->invokeArgs($paymentGatewayTwo, [$body, $sign]));
    }

}
