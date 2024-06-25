<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPaymentGatewayRequest;
use App\Http\Requests\StorePaymentGatewayRequest;
use App\Http\Requests\UpdatePaymentGatewayRequest;
use App\Models\PaymentGateway;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentGatewayController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('payment_gateway_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentGateways = PaymentGateway::all();

        return view('frontend.paymentGateways.index', compact('paymentGateways'));
    }

    public function create()
    {
        abort_if(Gate::denies('payment_gateway_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.paymentGateways.create');
    }

    public function store(StorePaymentGatewayRequest $request)
    {
        $paymentGateway = PaymentGateway::create($request->all());

        return redirect()->route('frontend.payment-gateways.index');
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        abort_if(Gate::denies('payment_gateway_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.paymentGateways.edit', compact('paymentGateway'));
    }

    public function update(UpdatePaymentGatewayRequest $request, PaymentGateway $paymentGateway)
    {
        $paymentGateway->update($request->all());

        return redirect()->route('frontend.payment-gateways.index');
    }

    public function show(PaymentGateway $paymentGateway)
    {
        abort_if(Gate::denies('payment_gateway_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.paymentGateways.show', compact('paymentGateway'));
    }

    public function destroy(PaymentGateway $paymentGateway)
    {
        abort_if(Gate::denies('payment_gateway_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentGateway->delete();

        return back();
    }

    public function massDestroy(MassDestroyPaymentGatewayRequest $request)
    {
        $paymentGateways = PaymentGateway::find(request('ids'));

        foreach ($paymentGateways as $paymentGateway) {
            $paymentGateway->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
