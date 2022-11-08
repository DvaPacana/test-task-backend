<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Merchants;

use App\Contracts\Merchant;
use App\Services\Merchants\Models\BeautifulPaymentMerchant;
use Illuminate\Http\Request;
use Tests\TestCase;

final class BeautifulPaymentMerchantTest extends TestCase
{
    private Merchant $merchant;
    private array $payload;
    private string $signature;
    private string $apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKey = 'KaTf5tZYHx4v7pgZ';
        $this->signature = 'f027612e0e6cb321ca161de060237eeb97e46000da39d3add08d09074f931728';

        $this->payload = [
            'merchant_id' => 6,
            'payment_id' => 13,
            'status' => 'completed',
            'amount' => 500,
            'amount_paid' =>  500,
            'timestamp' => 1654103837,
            'sign' =>  $this->signature
        ];

        $this->merchant = new BeautifulPaymentMerchant(
            request: Request::create(
                uri: 'http://test.com', parameters: $this->payload
            )
        );
    }

    /** @test */
    public function is_valid_signature()
    {
        $this->assertTrue($this->merchant->checkSignature($this->apiKey, $this->payload));
    }

    public function is_invalid_signature()
    {
        $this->assertFalse($this->merchant->checkSignature('test', $this->payload));
    }

    /** @test */
    public function make_valid_signature()
    {
        $signature = $this->payload['sign'];
        $this->assertEquals($signature, $this->merchant->makeSignature($this->apiKey, $this->payload));
    }
}
