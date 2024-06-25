<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPaymentGatewayRequest;
use App\Http\Requests\StorePaymentGatewayRequest;
use App\Http\Requests\UpdatePaymentGatewayRequest;
use App\Models\PaymentGateway;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PaymentGatewayController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('payment_gateway_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PaymentGateway::query()->select(sprintf('%s.*', (new PaymentGateway)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'payment_gateway_show';
                $editGate      = 'payment_gateway_edit';
                $deleteGate    = 'payment_gateway_delete';
                $crudRoutePart = 'payment-gateways';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('gateway_type', function ($row) {
                return $row->gateway_type ? $row->gateway_type : '';
            });
            $table->editColumn('prefix', function ($row) {
                return $row->prefix ? $row->prefix : '';
            });
            $table->editColumn('url', function ($row) {
                return $row->url ? $row->url : '';
            });
            $table->editColumn('username', function ($row) {
                return $row->username ? $row->username : '';
            });
            $table->editColumn('password', function ($row) {
                return $row->password ? $row->password : '';
            });
            $table->editColumn('merchand', function ($row) {
                return $row->merchand ? $row->merchand : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.paymentGateways.index');
    }

    public function create()
    {
        abort_if(Gate::denies('payment_gateway_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.paymentGateways.create');
    }

    public function store(StorePaymentGatewayRequest $request)
    {
        $paymentGateway = PaymentGateway::create($request->all());

        return redirect()->route('admin.payment-gateways.index');
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        abort_if(Gate::denies('payment_gateway_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.paymentGateways.edit', compact('paymentGateway'));
    }

    public function update(UpdatePaymentGatewayRequest $request, PaymentGateway $paymentGateway)
    {
        $paymentGateway->update($request->all());

        return redirect()->route('admin.payment-gateways.index');
    }

    public function show(PaymentGateway $paymentGateway)
    {
        abort_if(Gate::denies('payment_gateway_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.paymentGateways.show', compact('paymentGateway'));
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
