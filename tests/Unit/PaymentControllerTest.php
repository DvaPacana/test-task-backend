<?php

namespace Tests\Unit;

use App\Http\Controllers\PaymentController;
use App\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;


class PaymentControllerTest extends TestCase
{
    public function testHandleCallback() : void
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('has')->andReturn(true);

        $mockGateway = Mockery::mock(PaymentGatewayInterface::class);
        $mockGateway->shouldReceive('validateCallback')->once()
            ->with($mockRequest)
            ->andReturn(true);
        $mockGateway->shouldReceive('processPayment')->once()
            ->with($mockRequest)
            ->andReturn(new JsonResponse(['success' => 'Payment processed']));

        $instance = Mockery::mock(PaymentController::class)->makePartial();
        $instance->shouldReceive('determineGateway')->once()
            ->with($mockRequest)
            ->andReturn($mockGateway);

        $response = $instance->handleCallback($mockRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"success":"Payment processed"}', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
