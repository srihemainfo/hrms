<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySectionRequest;
use App\Models\Section;
use App\Models\ToolsCourse;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('section_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $query = Section::with('course')->select(sprintf('%s.*', (new Section)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'section_show';
                $editGate = 'section_edit';
                $deleteGate = 'section_delete';
                $editFunct = 'editSection';
                $viewFunct = 'viewSection';
                $deleteFunct = 'deleteSection';
                $crudRoutePart = 'sections';

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
            $table->editColumn('section', function ($row) {
                return $row->section ? $row->section : '';
            });
            $table->addColumn('course', function ($row) {
                return $row->course ? $row->course->short_form : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $courses = ToolsCourse::select('id', 'short_form')->get();

        return view('admin.sections.index', compact('courses'));
    }
    public function store(Request $request)
    {

        if (isset($request->course)) {
            if ($request->id == '') {
                $count = Section::where(['course_id' => $request->course, 'section' => strtoupper($request->section)])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Section Already Exist.']);
                } else {
                    $store = Section::create([
                        'course_id' => $request->course,
                        'section' => strtoupper($request->section),
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Section Created']);
            } else {
                $count = Section::whereNotIn('id', [$request->id])->where(['course_id' => $request->course, 'section' => strtoupper($request->section)])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Section Already Exist.']);
                } else {
                    $update = Section::where(['id' => $request->id])->update([
                        'course_id' => $request->course,
                        'section' => strtoupper($request->section),
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Section Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Section Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Section::with('course')->where(['id' => $request->id])->select('id', 'section', 'course_id')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Section::with('course')->where(['id' => $request->id])->select('id', 'section', 'course_id')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Section::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Section Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(MassDestroySectionRequest $request)
    {
        $sections = Section::find(request('ids'));

        foreach ($sections as $section) {
            $section->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Section Deleted Successfully']);
    }
}
