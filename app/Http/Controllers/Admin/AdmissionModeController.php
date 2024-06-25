<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionMode;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdmissionModeController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = AdmissionMode::select(sprintf('%s.*', (new AdmissionMode)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $editFunct = 'editAdmissionMode';
                $viewFunct = 'viewAdmissionMode';
                $deleteFunct = 'deleteAdmissionMode';
                $viewGate      = 'admission_mode_show';
                $editGate      = 'admission_mode_edit';
                $deleteGate    = 'admission_mode_delete';
                $crudRoutePart = 'admission-mode';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'editFunct',
                    'viewFunct',
                    'deleteFunct',
                    'crudRoutePart',
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

        return view('admin.admissionMode.index');
    }
    public function store(Request $request)
    {

        if (isset($request->name)) {
            if ($request->id == '') {
                $count = AdmissionMode::where(['name' => $request->name])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Admission Mode Already Exist.']);
                } else {
                    $store = AdmissionMode::create([
                        'name' => strtoupper($request->name),
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Admission Mode Created']);
            } else {
                $count = AdmissionMode::whereNotIn('id', [$request->id])->where(['name' => $request->name])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Admission Mode Already Exist.']);
                } else {
                    $update = AdmissionMode::where(['id' => $request->id])->update([
                        'name' => strtoupper($request->name),
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Admission Mode Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Admission Mode Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = AdmissionMode::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = AdmissionMode::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = AdmissionMode::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Admission Mode Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $AdmissionModes = AdmissionMode::find(request('ids'));

        foreach ($AdmissionModes as $AdmissionMode) {
            $AdmissionMode->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Admission Modes Deleted Successfully']);
    }

}
