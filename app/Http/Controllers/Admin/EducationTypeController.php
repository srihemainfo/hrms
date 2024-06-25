<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEducationTypeRequest;
use App\Http\Requests\StoreEducationTypeRequest;
use App\Http\Requests\UpdateEducationTypeRequest;
use App\Models\EducationType;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EducationTypeController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = EducationType::query()->select(sprintf('%s.*', (new EducationType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewEduType';
                $editFunct = 'editEduType';
                $deleteFunct = 'deleteEduType';
                $viewGate      = 'education_type_show';
                $editGate      = 'education_type_edit';
                $deleteGate    = 'education_type_delete';
                $crudRoutePart = 'education-types';

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

        return view('admin.educationTypes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('community_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = EducationType::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'EducationType Created']);
            } else {
                $update = EducationType::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'EducationType Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'EducationType Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = EducationType::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = EducationType::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, EducationType $EducationType)
    {
        $EducationType->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(EducationType $EducationType)
    {

    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = EducationType::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'EducationType Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $eduType = EducationType::find(request('ids'));

        foreach ($eduType as $e) {
            $e->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'EducationType Deleted Successfully']);
    }
}
