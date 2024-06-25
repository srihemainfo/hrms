<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentGatewayRequest;
use App\Http\Requests\UpdatePaymentGatewayRequest;
use App\Http\Resources\Admin\PaymentGatewayResource;
use App\Models\PaymentGateway;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentGatewayApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('payment_gateway_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PaymentGatewayResource(PaymentGateway::all());
    }

    public function store(StorePaymentGatewayRequest $request)
    {
        $paymentGateway = PaymentGateway::create($request->all());

        return (new PaymentGatewayResource($paymentGateway))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PaymentGateway $paymentGateway)
    {
        abort_if(Gate::denies('payment_gateway_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PaymentGatewayResource($paymentGateway);
    }

    public function update(UpdatePaymentGatewayRequest $request, PaymentGateway $paymentGateway)
    {
        $paymentGateway->update($request->all());

        return (new PaymentGatewayResource($paymentGateway))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PaymentGateway $paymentGateway)
    {
        abort_if(Gate::denies('payment_gateway_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentGateway->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
