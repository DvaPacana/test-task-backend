<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Merchants;

use App\Contracts\Merchant;
use App\Services\Merchants\Models\BestPaymentMerchant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\HeaderBag;
use Tests\TestCase;

final class BestPaymentMerchantTest extends TestCase
{
    private Merchant $merchant;
    private array $payload;
    private string $apiKey;
    private string $signature;


    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKey = 'rTaasVHeteGbhwBx';
        $this->signature = 'd84eb9036bfc2fa7f46727f101c73c73';

        $this->payload = [
            'project' => 816,
            'invoice' => 73,
            'status' =>  'completed',
            'amount' => 700,
            'amount_paid' => 700,
            'rand' => 'SNuHufEJ',
        ];

        $request = Request::create(
            uri: 'http://test.com', parameters: $this->payload
        );

        $request->headers = new HeaderBag([
            'Authorization' => 'd84eb9036bfc2fa7f46727f101c73c73'
        ]);

        $this->merchant = new BestPaymentMerchant($request);
    }

    /** @test */
    public function is_valid_signature()
    {

        $this->assertTrue($this->merchant->checkSignature($this->apiKey, $this->payload));
    }

    /** @test  */
    public function is_invalid_signature()
    {
        $this->assertFalse($this->merchant->checkSignature('test', $this->payload));
    }

    /** @test */
    public function make_valid_signature()
    {
        $this->assertEquals($this->signature, $this->merchant->makeSignature($this->apiKey, $this->payload));
    }
}
