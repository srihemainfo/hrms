<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CurrentAySemTrait;
use App\Http\Requests\MassDestroyAcademicYearRequest;
use App\Models\AcademicYear;
use App\Models\Year;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AcademicYearController extends Controller
{
    use CurrentAySemTrait;

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = AcademicYear::query()->select(sprintf('%s.*', (new AcademicYear)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'academic_year_show';
                $editGate = 'academic_year_edit';
                $deleteGate = 'academic_year_delete';
                $crudRoutePart = 'academic-years';
                $viewFunct = 'viewAy';
                $editFunct = 'editAy';
                $deleteFunct = 'deleteAy';

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
            $table->editColumn('status', function ($row) {
                $status = $row->status ? $row->status : 0;
                $data = ['status' => $status, 'id' => $row->id];
                return $data;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $year = Year::pluck('year', 'id')->toArray();
        sort($year);

        return view('admin.academicYears.index', compact('year'));
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = AcademicYear::where(['id' => $request->id])->select('id', 'name', 'from', 'to')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function store(Request $request)
    {
        if (isset($request->from) && isset($request->to)) {
            if ($request->id == '') {
                $count = AcademicYear::where(['from' => $request->from])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'AY Already Exist.']);
                } else {
                    $store = AcademicYear::create([
                        'from' => $request->from,
                        'to' => $request->to,
                        'name' => $request->from . '-' . $request->to,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'AY Created Successfully']);
            } else {
                $count = AcademicYear::whereNotIn('id', [$request->id])->where(['from' => $request->from])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'AY Already Exist.']);
                } else {
                    $update = AcademicYear::where(['id' => $request->id])->update([
                        'from' => $request->from,
                        'to' => $request->to,
                        'name' => $request->from . '-' . $request->to,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'AY Updated Successfully']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'AY Not Created']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = AcademicYear::where(['id' => $request->id])->select('id', 'name', 'from', 'to')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = AcademicYear::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Academic Year Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(MassDestroyAcademicYearRequest $request)
    {
        $academicYears = AcademicYear::find(request('ids'));

        foreach ($academicYears as $academicYear) {
            $academicYear->delete();
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function changeStatus(Request $request)
    {
        if (isset($request->id) && isset($request->status)) {
            $update = AcademicYear::where(['id' => $request->id])->update(['status' => $request->status]);
            $store = $this->getCurrent_Ay_Sem();
            return response()->json(['status' => true, 'data' => 'Academic Year\'s Status Modified']);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
