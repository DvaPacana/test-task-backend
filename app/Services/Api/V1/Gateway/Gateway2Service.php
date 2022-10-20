<?php

namespace App\Services\Api\V1\Gateway;

use App\Events\ChangePayStatusEvent;
use App\Models\Gateway;
use App\Models\Payment;
use App\Models\User;
use App\Utils\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class Gateway2Service extends GatewayCommon
{

    private function createSign(array $data, string $app_key): string
    {
        $array_sort = $data;
        ksort($array_sort);
        return md5(implode('.', array_keys($array_sort)) . $app_key);
    }

    private function collectCallbackData(Payment|Model $payment): array
    {
        $data = [];
        $data['project'] = $payment->merchant_id;
        $data['invoice'] = $payment->id;
        $data['status'] = $payment->status;
        $data['amount'] = Currency::convertCent($payment->amount);
        $data['amount_paid'] = Currency::convertCent($payment->amount_paid);
        $data['rand'] = Str::random(12);
        return $data;
    }

    protected function makeValidations(Gateway $gateway, Request $request)
    {
        // validate input fields
        $this->validate($request->all(), [
            'app_id' => 'integer|required',
            'app_key' => 'string|required',
        ]);
        // validate payment_count daily norm
        if ($gateway->payment_count > $gateway->payment_limit) {
            throw new HttpResponseException(
                response()->json(['success' => false, 'errors' => 'Limit exceeded'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            );
        }
    }

    public function updatePaymentStatus(Request $request, int $gateway_id)
    {
        $currentGateway = Gateway::find($gateway_id);
        // some validation
        $this->makeValidations($currentGateway, $request);
        // payment fetch some logic
        $merchant = User::inRandomOrder()->first();
        $found_payment = Payment::with(['merchant', 'currency'])
            ->where([
                ['merchant_id', '=', $merchant->id],
                ['currency_id', '=', $currentGateway->currency_id],
                ['status', '<>', $currentGateway->payment_status]
            ])
            ->first();
        if ($found_payment?->id) {
            // updates
            $found_payment->status = $currentGateway->payment_status;
            $found_payment->save();

            $currentGateway->payment_count++;
            $currentGateway->save();

            $data = $this->collectCallbackData($found_payment);
            $sign = $this->createSign($data, request()->app_key);
            // event on request callback url
            ChangePayStatusEvent::dispatch($data, $gateway_id, ['Authorization' => $sign]);
        } else return throw new HttpResponseException(
            response()->json(['success' => false, 'errors' => 'Payment not found'], JsonResponse::HTTP_NOT_FOUND)
        );
        return response()->json(['success' => true, 'data' => $data], 200);
    }
}
