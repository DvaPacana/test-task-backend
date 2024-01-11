<?php

namespace App\Payment\Concrete;

use App\Models\Payment;
use App\Payment\Contracts\PaymentGatewayInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * TODO
 * 1. Implement database transactions
 * 2. Make PaymentGatewayResponse class and handle exceptions
 */
class PaymentGatewayOne implements PaymentGatewayInterface
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
        private readonly int              $merchantId,
        private readonly string           $merchantKey,
        private readonly ?PaymentRepositoryInterface $paymentRepository = null
    ){}

    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    public function getMerchantKey(): string
    {
        return $this->merchantKey;
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function processPayment(array $data): bool
    {
        $sign = $data['sign'];
        unset($data["sign"]);

        if(!$this->validateSign($data, $sign)){
            throw new \Exception("Sign is invalid");
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

        $payment = $this->paymentRepository->findByGatewayAndMerchantIdOrFail(static::getName(), $data["payment_id"]);

        return $this->paymentRepository->update($payment->id, [
            'amount' => $data["amount"] / 100,
            'amount_paid' => $data["amount_paid"] / 100,
            'status' => static::MATCHES[$data["status"]]
        ]);
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
    public static function extractData(Request $request): array
    {
        return $request->only(
            'merchant_id',
            'payment_id',
            'status',
            'amount',
            'amount_paid',
            'timestamp',
            'sign'
        );
    }


    public static function getName(): string
    {
        return "Gateway 1";
    }


}
