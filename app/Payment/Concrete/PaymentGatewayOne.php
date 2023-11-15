<?php

namespace App\Payment\Concrete;

use App\Models\Payment;
use App\Payment\Contracts\PaymentGatewayContract;
use App\Payment\Exceptions\PaymentGatewayException;
use App\Repositories\Payment\PaymentRepositoryContract;
use Illuminate\Http\Request;

/**
 * TODO
 * 1. Implement database transactions
 * 2. Make PaymentGatewayResponse class and handle exceptions
 */
class PaymentGatewayOne implements PaymentGatewayContract
{
    const MATCHES = [
        "new" => Payment::NEW,
        "pending" => Payment::PENDING,
        "completed" => Payment::COMPLETED,
        "expired" => Payment::EXPIRED,
        "rejected" => Payment::REJECTED
    ];

    private function validateSign(array $data, string $sign): bool
    {
        ksort($data);
        $validateSign = hash("sha256", implode(":", $data) . $this->merchantKey);

        return $validateSign === $sign;
    }

    public function __construct(
        private int $merchantId,
        private string $merchantKey,
        private PaymentRepositoryContract $paymentRepository
    ){}

    public function getPaymentGatewayMerchantId(): int
    {
        return $this->merchantId;
    }

    public function getPaymentGatewayMerchantKey(): string
    {
        return $this->merchantKey;
    }

    public function processPayment(array $data): bool
    {
        $sign = $data['sign'];
        unset($data["sign"]);
        if(!$this->validateSign($data, $sign)){
            throw new PaymentGatewayException("Can't validate sign");
        }

        if($data["status"] === "new"){

            $this->paymentRepository->create([
                'payment_gateway' => static::getName(),
                'merchant_invoice_id' => $data["payment_id"],
                'amount' => $data["amount"] / 100,
                'amount_paid' => $data["amount_paid"] / 100,
                'status' => static::MATCHES[$data["status"]]
            ]);

            return true;
        }

        $payment = $this->paymentRepository->findByGatewayAndMerchantOrFail(static::getName(), $data["payment_id"]);
        $updated = $this->paymentRepository->update($payment->id, [
            'amount' => $data["amount"] / 100,
            'amount_paid' => $data["amount_paid"] / 100,
            'status' => static::MATCHES[$data["status"]]
        ]);
        
        return $updated;
    }

    public static function getName(): string
    {
        return "Gateway 1";
    }

    public static function extractData(Request $request): array
    {
        $body = $request->only('merchant_id', 'payment_id', 'status', 'amount', 'amount_paid', 'timestamp', 'sign');

        return $body;
    }

    public static function getValidationRules(): array
    {
        return [
            "merchant_id" => "required|integer",
            "payment_id" => "required|integer",
            "status" => "required|string|in:new,pending,completed,expired,rejected",
            "amount" => "required|integer",
            "amount_paid" => "required|integer",
            "timestamp" => "required|integer",
            "sign" => "required|string"
        ];
    }
}