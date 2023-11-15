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
class PaymentGatewayTwo implements PaymentGatewayContract
{
    const MATCHES = [
        "created" => Payment::NEW,
        "inprogress" => Payment::PENDING,
        "paid" => Payment::COMPLETED,
        "expired" => Payment::EXPIRED,
        "rejected" => Payment::REJECTED
    ];

    private function validateSign(array $data, string $sign): bool
    {
        ksort($data);
        $validateSign = md5(implode(".", $data) . $this->appKey);

        return $validateSign === $sign;
    }

    public function __construct(
        private int $appId,
        private string $appKey,
        private PaymentRepositoryContract $paymentRepository
    ){}

    public function getPaymentGatewayMerchantId(): int
    {
        return $this->appId;
    }

    public function getPaymentGatewayMerchantKey(): string
    {
        return $this->appKey;
    }

    public function processPayment(array $data): bool
    {    
        $sign = $data['authorization'];
        unset($data["authorization"]);
        if(!$this->validateSign($data, $sign) || $sign === null){
            throw new PaymentGatewayException("Can't validate sign");
        }

        if($data["status"] === "created"){

            $this->paymentRepository->create([
                'payment_gateway' => static::getName(),
                'merchant_invoice_id' => $data["invoice"],
                'amount' => $data["amount"] / 100,
                'amount_paid' => $data["amount_paid"] / 100,
                'status' => static::MATCHES[$data["status"]]
            ]);

            return true;
        }

        $payment = $this->paymentRepository->findByGatewayAndMerchantOrFail(static::getName(), $data["invoice"]);
        $updated = $this->paymentRepository->update($payment->id, [
            'amount' => $data["amount"] / 100,
            'amount_paid' => $data["amount_paid"] / 100,
            'status' => static::MATCHES[$data["status"]]
        ]);
        
        return $updated;
    }

    public static function getName(): string
    {
        return "Gateway 2";
    }

    public static function extractData(Request $request): array
    {
        $authValue = $request->header('Authorization');
        $body = $request->only('project', 'invoice', 'status', 'amount', 'amount_paid', 'rand');
        $body["authorization"] = $authValue;

        return $body;
    }

    public static function getValidationRules(): array
    {
        return [
            "project" => "required|integer",
            "invoice" => "required|integer",
            "status" => "required|string|in:created,inprogress,paid,expired,rejected",
            "amount" => "required|integer",
            "amount_paid" => "required|integer",
            "rand" => "required|string",
        ];
    }
}