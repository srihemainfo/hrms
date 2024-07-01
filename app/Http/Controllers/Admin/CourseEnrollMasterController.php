<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\UpdateCourseEnrollMasterRequest;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\Section;
use App\Models\Semester;
use App\Models\ToolsCourse;
use App\Models\ToolsDegreeType;
use App\Models\ToolsDepartment;
use App\Models\Year;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CourseEnrollMasterController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('course_enroll_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $years = Year::select('id', 'year')->get();

        if ($request->ajax()) {
            $query = CourseEnrollMaster::with(['degreetype', 'batch', 'academic', 'course', 'department', 'semester', 'section'])->select(sprintf('%s.*', (new CourseEnrollMaster)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {

                $deleteFunct = 'deleteEnroll';
                $deleteGate = 'course_enroll_master_delete';
                $crudRoutePart = 'course-enroll-masters';

                return view('partials.ajaxTableActions', compact(

                    'deleteGate',
                    'crudRoutePart',
                    'deleteFunct',
                    'row'
                )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('enroll_master_number', function ($row) {
                return $row->enroll_master_number ? $row->enroll_master_number : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $degreeTypes = ToolsDegreeType::pluck('name', 'id');
        $years = Year::select('year')->get();

        return view('admin.courseEnrollMasters.index', compact('years', 'degreeTypes'));
    }

    public function store(Request $request)
    {
        // dd($request->degree_type == 'UG', $request);
        if (isset($request->from) && isset($request->to)) {
            $academy = [];
            $academic_year = [];

            $batch = $request->from . '-' . $request->to;
            $batchId = Batch::where('name', $batch)->select('id')->first();
            $academy = range($request->from, $request->to);
            // dd($academy);
            $semester = Semester::pluck('semester', 'id');

            for ($b = 0; $b < count($academy) - 1; $b++) {
                array_push($academic_year, $academy[$b] . '-' . $academy[$b + 1]);
                array_push($academic_year, $academy[$b] . '-' . $academy[$b + 1]);
            }

            $degree_id = ToolsDegreeType::where('name', $request->degree_type)->first();
            $toolCourse = ToolsCourse::where(['degree_type_id' => $degree_id->id])->select('id', 'name', 'short_form', 'shift_id')->get();
            // dd($toolCourse);
            if (count($toolCourse) > 0) {

                foreach ($toolCourse as $courseId => $course) {

                    $section = Section::where('course_id', $course->id)->pluck('section', 'id');
                    if ($request->degree_type == 'UG') {
                        foreach ($semester as $semId => $sem) {
                            foreach ($section as $secId => $sec) {
                                $checky = CourseEnrollMaster::where('enroll_master_number', $batch . '/' . $course->short_form . '/' . $academic_year[$sem - 1] . '/' . $sem . '/' . $sec)->get();
                                if (count($checky) <= 0) {
                                    $ayId = AcademicYear::where(['name' => $academic_year[$sem - 1]])->select('id')->first();
                                    $courseEnrollMaster = CourseEnrollMaster::create(['enroll_master_number' => $batch . '/' . $course->short_form . '/' . $academic_year[$sem - 1] . '/' . $sem . '/' . $sec, 'batch_id' => $batchId->id, 'academic_id' => $ayId->id, 'course_id' => $course->id, 'semester_id' => $sem, 'section_id' => $sec, 'shift_id' => $course->shift_id ?? null]);
                                } else {
                                    $courseEnrollMaster = false;
                                }
                            }
                        }
                    } else {
                        for ($i = 1; $i <= 4; $i++) {
                            foreach ($section as $secId => $sec) {
                                $checky = CourseEnrollMaster::where('enroll_master_number', $batch . '/' . $course->short_form . '/' . $academic_year[$i - 1] . '/' . $i . '/' . $sec)->get();
                                if (count($checky) <= 0) {
                                    $ayId = AcademicYear::where(['name' => $academic_year[$i - 1]])->select('id')->first();
                                    // dd($ayId->id);
                                    $courseEnrollMaster = CourseEnrollMaster::create(['enroll_master_number' => $batch . '/' . $course->short_form . '/' . $academic_year[$i - 1] . '/' . $i . '/' . $sec, 'batch_id' => $batchId->id, 'academic_id' => $ayId->id, 'course_id' => $course->id, 'semester_id' => $i, 'section_id' => $sec, 'shift_id' => $course->shift_id ?? null]);
                                    
                                } else {
                                    $courseEnrollMaster = false;
                                }
                            }
                        }
                    }
                }
                if ($courseEnrollMaster) {
                    return response()->json(['status' => true, 'data' => 'Enroll Master Created']);
                } else {
                    return response()->json(['status' => 'two', 'data' => 'Enroll Master Already Created']);
                }
            } else {
                return response()->json(['status' => 'one', 'data' => 'Courses are Empty']);
            }
        }
    }

    public function edit(CourseEnrollMaster $courseEnrollMaster)
    {
        abort_if(Gate::denies('course_enroll_master_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $degreetypes = ToolsDegreeType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $batches = Batch::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academics = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $courses = ToolsCourse::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $departments = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $semesters = Semester::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sections = Section::pluck('section', 'id')->prepend(trans('global.pleaseSelect'), '');

        $courseEnrollMaster->load('degreetype', 'batch', 'academic', 'course', 'department', 'semester', 'section');

        return view('admin.courseEnrollMasters.edit', compact('academics', 'batches', 'courseEnrollMaster', 'courses', 'degreetypes', 'departments', 'sections', 'semesters'));
    }

    public function update(UpdateCourseEnrollMasterRequest $request, CourseEnrollMaster $courseEnrollMaster)
    {
        $courseEnrollMaster->update($request->all());

        return redirect()->route('admin.course-enroll-masters.index');
    }

    public function show(CourseEnrollMaster $courseEnrollMaster)
    {
        abort_if(Gate::denies('course_enroll_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseEnrollMaster->load('degreetype', 'batch', 'academic', 'course', 'department', 'semester', 'section');

        return view('admin.courseEnrollMasters.show', compact('courseEnrollMaster'));
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = CourseEnrollMaster::where(['id' => $request->id])->select('id', 'enroll_master_number')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = CourseEnrollMaster::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Enrollment Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $enroll = CourseEnrollMaster::find(request('ids'));

        foreach ($enroll as $e) {
            $e->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Enrollment Deleted Successfully']);
    }
}
