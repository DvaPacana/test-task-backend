<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OperationHistoryCollection;
use App\Http\Resources\V1\OperationHistoryResource;
use App\Models\OperationHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OperationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return OperationHistoryCollection
     */
    public function index()
    {
        return new OperationHistoryCollection(OperationHistory::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(int $gateway_id, Request $request)
    {
        $operation = new OperationHistory();
        $data = $request->toArray();
        // validate
        if(!$gateway_id || !count($data)) throw new HttpResponseException(
            response()->json(['success' => false], Response::HTTP_BAD_REQUEST)
        );
        if ($request->header('Authorization')) $data['Authorization'] = $request->header('Authorization');
        $operation->data = json_encode($data);
        $operation->gateway_id = $gateway_id;
        $operation->save();
        return response()->json(['success' => true], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param OperationHistory $operationHistory
     * @return OperationHistoryResource
     */
    public function show(OperationHistory $operationHistory)
    {
        return new OperationHistoryResource($operationHistory);
    }
}
