<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMediumofStudiedRequest;
use App\Http\Requests\StoreMediumofStudiedRequest;
use App\Http\Requests\UpdateMediumofStudiedRequest;
use App\Models\MediumofStudied;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MediumofStudiedController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MediumofStudied::query()->select(sprintf('%s.*', (new MediumofStudied)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewMedium';
                $editFunct = 'editMedium';
                $deleteFunct = 'deleteMedium';
                $viewGate      = 'mediumof_studied_show';
                $editGate      = 'mediumof_studied_edit';
                $deleteGate    = 'mediumof_studied_delete';
                $crudRoutePart = 'mediumof-studieds';

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
            $table->editColumn('medium', function ($row) {
                return $row->medium ? $row->medium : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.mediumofStudieds.index');
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
                $store = MediumofStudied::create([
                    'medium' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Medium Created']);
            } else {
                $update = MediumofStudied::where(['id' => $request->id])->update([
                    'medium' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Medium Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Medium Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = MediumofStudied::where(['id' => $request->id])->select('id', 'medium')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = MediumofStudied::where(['id' => $request->id])->select('id', 'medium')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, MediumofStudied $MediumofStudied)
    {
        $MediumofStudied->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(MediumofStudied $MediumofStudied)
    {

    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = MediumofStudied::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Medium Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $medium = MediumofStudied::find(request('ids'));

        foreach ($medium as $m) {
            $m->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Medium Deleted Successfully']);
    }

}
