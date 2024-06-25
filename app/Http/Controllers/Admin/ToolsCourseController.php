<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolsCourseRequest;
use App\Models\ToolsCourse;
use App\Models\ToolsDegreeType;
use App\Models\ToolsDepartment;
use Carbon\Carbon;
use FontLib\Table\Type\name;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ToolsCourseController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('tools_course_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ToolsCourse::with('degree', 'department', 'shift')->get();
            // dd($query);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate = 'tools_course_show';
                $editGate = 'tools_course_edit';
                $deleteGate = 'tools_course_delete';
                $crudRoutePart = 'tools-courses';
                $editFunct = 'editCourse';
                $viewFunct = 'viewCourse';
                $deleteFunct = 'deleteCourse';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'editFunct',
                    'viewFunct',
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
            $table->editColumn('degree_type', function ($row) {
                return $row->degree ? $row->degree->name : 'UG';
            });
            $table->editColumn('shift', function ($row) {
                return $row->shift ? $row->shift->name : '';
            });
            $table->editColumn('department', function ($row) {
                return $row->department ? $row->department->name : '';
            });
            $table->editColumn('short_form', function ($row) {
                return $row->short_form ? $row->short_form : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'department']);

            return $table->make(true);
        }
        $degree = ToolsDegreeType::pluck('name', 'id');
        $dept = ToolsDepartment::pluck('name', 'id');

        return view('admin.toolsCourses.index', compact('degree', 'dept'));
    }

    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->course)) {
            if ($request->id == '') {
                $count = ToolsCourse::where(['name' => $request->course, 'degree_type_id'=>$request->degree])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Course Already Exist.']);
                } else {
                    $store = ToolsCourse::create([
                        'name' => strtoupper($request->course),
                        'short_form' => strtoupper($request->short_form),
                        'degree_type_id' => $request->degree,
                        'department_id' => $request->dept,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Course Created']);
            } else {
                $count = ToolsCourse::whereNotIn('id', [$request->id])->where(['name' => $request->course, 'degree_type_id'=>$request->degree, 'short_form'=>$request->short_form])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Course Already Exist.']);
                } else {
                    $update = ToolsCourse::where(['id' => $request->id])->update([
                        'name' => strtoupper($request->course),
                        'short_form' => strtoupper($request->short_form),
                        'degree_type_id' => $request->degree,
                        'department_id' => $request->dept
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Course Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Course Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = ToolsCourse::where(['id' => $request->id])->select('id', 'name', 'short_form', 'degree_type_id', 'department_id')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = ToolsCourse::where(['id' => $request->id])->select('id', 'name', 'short_form', 'degree_type_id', 'department_id')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = ToolsCourse::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Course Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(MassDestroyToolsCourseRequest $request)
    {
        $toolsCourses = ToolsCourse::find(request('ids'));

        foreach ($toolsCourses as $toolsCourse) {
            $toolsCourse->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Course Deleted Successfully']);
    }
}
