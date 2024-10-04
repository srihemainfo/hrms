<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CurrentAySemTrait;
use App\Http\Requests\MassDestroySemesterRequest;
use App\Models\Semester;
use App\Models\SemType;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SemesterController extends Controller
{
    use CurrentAySemTrait;
    public function index(Request $request)
    {
        abort_if(Gate::denies('semester_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Semester::query()->select(sprintf('%s.*', (new Semester)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'semester_show';
                $editGate = 'semester_edit';
                $deleteGate = 'semester_delete';
                $editFunct = 'editSemester';
                $viewFunct = 'viewSemester';
                $deleteFunct = 'deleteSemester';
                $crudRoutePart = 'semesters';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'viewFunct',
                    'editGate',
                    'editFunct',
                    'deleteGate',
                    'deleteFunct',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('semester', function ($row) {
                return $row->semester ? $row->semester : '';
            });

            $table->editColumn('status', function ($row) {
                $status = $row->status ? $row->status : 0;
                $data = ['status' => $status, 'id' => $row->id];
                return $data;

            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.semesters.index');
    }

    public function store(Request $request)
    {

        if (isset($request->semester)) {
            if ($request->id == '') {
                $count = Semester::where(['semester' => $request->semester])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Semester Already Exist.']);
                } else {
                    $store = Semester::create([
                        'semester' => $request->semester,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Semester Created']);
            } else {
                $count = Semester::whereNotIn('id', [$request->id])->where(['semester' => $request->semester])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Semester Already Exist.']);
                } else {
                    $update = Semester::where(['id' => $request->id])->update([
                        'semester' => $request->semester,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Semester Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Semester Not Created']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Semester::where(['id' => $request->id])->select('id', 'semester')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Semester::where(['id' => $request->id])->select('id', 'semester')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Semester::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Semester Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(MassDestroySemesterRequest $request)
    {
        $semesters = Semester::find(request('ids'));

        foreach ($semesters as $semester) {
            $semester->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Semester Deleted Successfully']);
    }

    public function semType(Request $request)
    {
        $totalData = 1;
        $getData = SemType::select('sem_type', 'status')->get();
        $addedSemTypes = [];
        if (count($getData) > 0) {
            foreach ($getData as $data) {
                array_push($addedSemTypes, $data->sem_type);
            }
        }
        $semTypes = ['ODD', 'EVEN'];
        return view('admin.semesters.semTypeIndex', compact('totalData', 'getData', 'semTypes', 'addedSemTypes'));
    }

    public function semTypeStore(Request $request)
    {
        if (isset($request->sem_type)) {

            $store = SemType::create([
                'sem_type' => strtoupper($request->sem_type),
            ]);
            $getData = SemType::select('sem_type', 'status')->get();
            return response()->json(['status' => true, 'data' => $getData]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Data Not Found']);
        }
    }

    public function changeStatus(Request $request)
    {
        if (isset($request->id) && isset($request->status)) {
            $update = Semester::where(['id' => $request->id])->update(['status' => $request->status]);
            $store = $this->getCurrent_Ay_Sem();
            return response()->json(['status' => true, 'data' => 'Semester\'s Status Modified']);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
