<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentModeController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = PaymentMode::query()->select(sprintf('%s.*', (new PaymentMode)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'foundation_show';
                $editGate = 'foundation_edit';
                $deleteGate = 'foundation_delete';
                $viewFunct = 'viewPayment';
                $editFunct = 'editPayment';
                $deleteFunct = 'deletePayment';
                $crudRoutePart = 'paymentMode';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.paymentMode.index');
    }

    public function create()
    {

        return view('admin.nationalities.create');
    }

    public function store(Request $request)
    {
        if (isset($request->payment)) {
            if ($request->id == '') {
                $store = PaymentMode::create([
                    'name' => strtoupper($request->payment),
                ]);
                return response()->json(['status' => true, 'data' => 'PaymentMode Created']);
            } else {
                $update = PaymentMode::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->payment),
                ]);
                return response()->json(['status' => true, 'data' => 'PaymentMode Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'PaymentMode Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = PaymentMode::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = PaymentMode::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateNationalityRequest $request, PaymentMode $PaymentMode)
    {
        $PaymentMode->update($request->all());

        return redirect()->route('admin.nationalities.index');
    }

    public function show(PaymentMode $PaymentMode)
    {
        abort_if(Gate::denies('nationality_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.nationalities.show', compact('PaymentMode'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = PaymentMode::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'PaymentMode Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $payment = PaymentMode::find(request('ids'));

        foreach ($payment as $n) {
            $n->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'PaymentMode Deleted Successfully']);
    }
}
