<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\GatewayCollection;
use App\Http\Resources\V1\GatewayResource;
use App\Models\Gateway;
use App\Utils\GatewayServiceById;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    /**
     * @throws \Exception
     */
    public function updatePaymentStatus(int $gateway_id, Request $request)
    {
        $gatewayService = GatewayServiceById::get($gateway_id);
        return $gatewayService->updatePaymentStatus($request, $gateway_id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return GatewayCollection
     */
    public function index()
    {
        return new GatewayCollection(Gateway::all());
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Gateway $gateway
     * @return GatewayResource
     */
    public function show(Gateway $gateway)
    {
        return new GatewayResource($gateway);
    }
}
