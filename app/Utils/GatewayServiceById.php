<?php

namespace App\Utils;

use App\Services\Api\V1\Gateway\GatewayCommon;
use App\Traits\ConvertVariable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Facade;

class GatewayServiceById extends Facade
{
    use ConvertVariable;

    /**
     * @throws \Exception
     */
    public static function get(int $id): GatewayCommon
    {
        try {
            return (new self)->convertVariableToModelName(
                'Gateway' . $id . 'Service',
                ['App', 'Services', 'Api', 'V1', 'Gateway']
            );
        } catch (\Exception $exception) {
            throw new HttpResponseException(
                response()->json(['success' => false, 'errors' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND)
            );
        }

    }
}
