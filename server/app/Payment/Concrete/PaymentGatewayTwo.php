<?php

namespace App\Payment\Concrete;

use App\Models\Payment;
use App\Payment\Contracts\PaymentGatewayInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;


class PaymentGatewayTwo implements PaymentGatewayInterface
{
    const MATCHES = [
        "created" => Payment::NEW,
        "processing" => Payment::PENDING,
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
        private readonly int              $appId,
        private readonly string           $appKey,
        private readonly ?PaymentRepositoryInterface $paymentRepository = null
    ){}

    public function getMerchantId(): int
    {
        return $this->appId;
    }

    public function getMerchantKey(): string
    {
        return $this->appKey;
    }

    /**
     * @throws \Exception
     */
    public function processPayment(array $data): bool
    {
        $sign = $data['authorization'];
        unset($data["authorization"]);
        if(!$this->validateSign($data, $sign) || $sign === null){
            throw new \Exception("Can't validate sign");
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

        $payment = $this->paymentRepository->findByGatewayAndMerchantIdOrFail(static::getName(), $data["invoice"]);

        return $this->paymentRepository->update($payment->id, [
            'amount' => $data["amount"] / 100,
            'amount_paid' => $data["amount_paid"] / 100,
            'status' => static::MATCHES[$data["status"]]
        ]);
    }

    public static function getValidationRules(): array
    {
        return [
            "project" => "required|integer",
            "invoice" => "required|integer",
            "status" => "required|string|in:created,processing,paid,expired,rejected",
            "amount" => "required|integer",
            "amount_paid" => "required|integer",
            "rand" => "required|string",
        ];
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

}
