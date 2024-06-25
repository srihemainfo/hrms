<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\SubjectType;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\SubjectCategory;
use App\Models\ToolsDepartment;
use App\Models\SubjectAllotment;
use App\Models\ToolssyllabusYear;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSubjectRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySubjectRequest;

class SubjectAllotmentController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        // dd($request);
        abort_if(Gate::denies('subject_allotment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = SubjectAllotment::with(['academic_years', 'regulations', 'departments', 'semesters', 'courses'])
            ->select('regulation', 'department', 'course', 'academic_year', 'semester_type', 'semester', DB::raw('MIN(created_at) as date'))
            ->groupBy('regulation', 'department', 'course', 'academic_year', 'semester_type', 'semester')
            ->get();


        $departments = ToolsDepartment::pluck('name', 'id');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::pluck('name', 'id');
        $regulation = ToolssyllabusYear::pluck('name', 'id');

        return view('admin.subjectAllotment.index', compact('query', 'departments', 'courses', 'semester', 'Subjects', 'regulation'));
    }

    public function search(Request $request)
    {
        // dd($request);
        if ($request->input('department') != '' && $request->input('course') != '' && $request->input('regulation') != '') {
            if ($request->input('semester') != null) {
                $subjectQuery = SubjectAllotment::where([
                    'department' => $request->input('department'),
                    'regulation' => $request->input('regulation'),
                    'course' => $request->input('course'),
                    'semester' => $request->input('semester'),
                ])->with(['regulations', 'departments', 'semesters', 'courses', 'academic_years']);
            } else {
                $subjectQuery = SubjectAllotment::where([
                    'department' => $request->input('department'),
                    'course' => $request->input('course'),
                    'regulation' => $request->input('regulation'),
                ])->with(['regulations', 'departments', 'semesters', 'courses', 'academic_years']);
            }
            $subjectAllot = $subjectQuery->select('regulation', 'department', 'course', 'academic_year', 'semester_type', 'semester', 'created_at')->groupBy('regulation', 'department', 'course', 'academic_year', 'semester_type', 'semester', 'created_at')->get();

            return response()->json(['data' => $subjectAllot]);

        }
    }

    public function create()
    {
        abort_if(Gate::denies('subject_allotment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $get_dept = ToolsDepartment::get();

        $get_course = ToolsCourse::get();
        $depts = [];
        foreach ($get_dept as $data) {
            $depts[$data->id] = [];
            foreach ($get_course as $course) {
                //   dd($course);
                if ($data->id != 5) {
                    if ($data->id == $course->department_id) {
                        array_push($depts[$data->id], $course);
                    }
                } else {

                    array_push($depts[$data->id], $course);

                }

            }
        }

        $department = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');

        $course = ToolsCourse::pluck('name', 'id')->prepend('Select Course', '');

        $regulation = ToolssyllabusYear::pluck('name', 'id')->prepend('Select Regulation', '');

        $semester = Semester::pluck('semester', 'id')->prepend('Select Semester', '');

        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');

        return view('admin.subjectAllotment.create', compact('regulation', 'department', 'semester', 'course', 'depts', 'academic_years'));
    }

    public function check(Request $request)
    {

        $check = SubjectAllotment::where(['regulation' => $request->reg, 'course' => $request->course, 'department' => $request->dept, 'academic_year' => $request->ay, 'semester' => $request->sem, 'semester_type' => $request->sem_type])->get();

        if (count($check) > 0) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->inputs) && isset($request->regular) && isset($request->professional) && isset($request->open) && isset($request->others)) {
            $regular = $request->regular;
            $professional = $request->professional;
            $open = $request->open;
            $others = $request->others;
            $logistics = $request->logistics;
            $datas = $request->inputs;

            $array = [];
            // dd($logistics);
            // if($data['department'])
            if ($datas['course'] == '13' || $datas['course'] == 13) {
                for ($i = 0; $i < count($regular); $i++) {

                    array_push($array, ['option_limits' => 0, 'category' => 'Regular Subject', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $regular[$i]['value']]);

                }
                if ($professional != '' || $professional != null) {

                    for ($i = 0; $i < count($professional); $i++) {

                        array_push($array, ['option_limits' => 0, 'category' => 'Electives HR', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $professional[$i]['value']]);

                    }
                }
                if ($open != '' || $open != null) {

                    for ($i = 0; $i < count($open); $i++) {

                        array_push($array, ['option_limits' => 0, 'category' => 'Electives Finance', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $open[$i]['value']]);

                    }
                }
                if ($others != '' || $others != null) {

                    for ($i = 0; $i < count($others); $i++) {

                        array_push($array, ['option_limits' => 0, 'category' => 'Electives Operations', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $others[$i]['value']]);

                    }
                }
                if ($logistics != '' || $logistics != null) {

                    for ($i = 0; $i < count($logistics); $i++) {

                        array_push($array, ['option_limits' => 0, 'category' => 'Electives Logistics and supply chain management', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $logistics[$i]['value']]);

                    }
                }
            } else {

                for ($i = 0; $i < count($regular); $i++) {

                    array_push($array, ['option_limits' => 0, 'category' => 'Regular Subject', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $regular[$i]['value']]);
                }
                for ($i = 1; $i < count($professional); $i++) {

                    array_push($array, ['option_limits' => $professional[0]['value'], 'category' => 'Professional Electives', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $professional[$i]['value']]);

                }
                for ($i = 1; $i < count($open); $i++) {

                    array_push($array, ['option_limits' => $open[0]['value'], 'category' => 'Open Electives', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $open[$i]['value']]);

                }
                for ($i = 1; $i < count($others); $i++) {

                    array_push($array, ['option_limits' => $others[0]['value'], 'category' => 'Others', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $others[$i]['value']]);

                }
            }
            // dd($array);
            if (count($array) > 0) {
                foreach ($array as $data) {
                    // dd($data);
                    $check = SubjectAllotment::where(['regulation' => $data['regulation'], 'department' => $data['department'], 'course' => $data['course'], 'academic_year' => $data['ay'], 'semester_type' => $data['sem_type'], 'semester' => $data['semester'], 'subject_id' => $data['subject']])->get();
                    if (count($check) <= 0) {
                        $insert = new SubjectAllotment;
                        $insert->regulation = $data['regulation'];
                        $insert->department = $data['department'];
                        $insert->course = $data['course'];
                        $insert->academic_year = $data['ay'];
                        $insert->semester_type = $data['sem_type'];
                        $insert->semester = $data['semester'];
                        $insert->category = $data['category'];
                        $insert->subject_id = $data['subject'];
                        $insert->option_limits = $data['option_limits'];
                        $insert->created_at = Carbon::now()->toDateString();
                        $insert->save();
                    }
                }
            }
            return response()->json(['status' => true]);
        }
        // return redirect()->route('admin.subjects.index');
    }

    public function edit(Request $request)
    {
        abort_if(Gate::denies('subject_allotment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $regular = $professional = $open = $others = [];
        $dept_id = $reg_id = $course_id = $sem_id = $ay_id = null;

        if (isset($request->regulation) && isset($request->department) && isset($request->semester) && isset($request->course) && isset($request->semester_type) && isset($request->academic_year)) {

            $alloted_subjects = SubjectAllotment::with(['subjects'])->where(['regulation' => $request->regulation, 'department' => $request->department, 'semester' => $request->semester, 'course' => $request->course, 'semester_type' => $request->semester_type, 'academic_year' => $request->academic_year])->get();
            $hr = [];
            $operations = [];
            $finance = [];
            $logistics = [];
            $regular = [];
            $professional = [];
            $open = [];
            $others = [];
            foreach ($alloted_subjects as $subject) {
                // dd($subject);
                $subject->subjects->subject_type_id = null;
                $get_sub = Subject::where(['id' => $subject->subject_id])->first();
                if ($get_sub != '') {
                    $get_sub_type = SubjectType::where(['id' => $get_sub->subject_type_id])->first();
                    if ($get_sub_type != '') {
                        $subject->subjects->subject_type_id = $get_sub_type->name;
                    }
                }
                $check_course = $request->course;
                if ($request->course == 13 || $request->course == '13') {
                    if ($subject->category == 'Regular Subject') {
                        array_push($regular, $subject);
                    }
                    if ($subject->category == 'Electives HR') {
                        array_push($hr, $subject);
                    }
                    if ($subject->category == 'Electives Operations') {
                        array_push($operations, $subject);
                    }
                    if ($subject->category == 'Electives Finance') {
                        array_push($finance, $subject);
                    }
                    if ($subject->category == 'Electives Logistics and supply chain management') {
                        array_push($logistics, $subject);
                    }
                    $professional = [];
                    $open = [];
                    $others = [];
                } else {
                    if ($subject->category == 'Regular Subject') {
                        array_push($regular, $subject);
                    }
                    if ($subject->category == 'Professional Electives') {
                        array_push($professional, $subject);
                    }
                    if ($subject->category == 'Open Electives') {
                        array_push($open, $subject);
                    }
                    if ($subject->category == 'Others') {
                        array_push($others, $subject);
                    }
                    $hr = [];
                    $operations = [];
                    $finance = [];
                    $logistics = [];
                }
            }
            $get_department = ToolsDepartment::where(['id' => $request->department])->first();

            $get_course = ToolsCourse::where(['id' => $request->course])->first();

            $get_regulation = ToolssyllabusYear::where(['id' => $request->regulation])->first();

            $get_semester = Semester::where(['id' => $request->semester])->first();

            $get_academic_year = AcademicYear::where(['id' => $request->academic_year])->first();

            if ($get_department != '') {
                $dept_id = $get_department->id;
            }
            if ($get_course != '') {
                $course_id = $get_course->id;
            }
            if ($get_regulation != '') {
                $reg_id = $get_regulation->id;
            }
            if ($get_semester != '') {
                $sem_id = $get_semester->id;
            }
            if ($get_academic_year != '') {
                $ay_id = $get_academic_year->id;
            }

            $sem_type = $request->semester_type;

            $department = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', null);

            $course = ToolsCourse::pluck('short_form', 'id')->prepend('Select Course', null);

            $regulation = ToolssyllabusYear::pluck('name', 'id')->prepend('Select Regulation', null);

            $semester = Semester::pluck('semester', 'id')->prepend('Select Semester', null);

            $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', null);

        }
        return view('admin.subjectAllotment.edit', compact('check_course', 'hr', 'operations', 'finance', 'logistics', 'academic_years', 'semester', 'regulation', 'course', 'department', 'regular', 'professional', 'open', 'others', 'dept_id', 'reg_id', 'course_id', 'sem_id', 'ay_id', 'sem_type'));

    }

    public function updater(Request $request)
    {

        // dd($request);
        if (isset($request->inputs) && isset($request->regular) || isset($request->professional) || isset($request->open) || isset($request->others)) {
            $regular = $request->regular;
            $professional = $request->professional ?? '';
            $open = $request->open ?? '';
            $others = $request->others ?? '';
            $logistics = $request->logistics ?? '';
            $datas = $request->inputs;
            // dd($professional);
            $array = [];
            $check_box_array = [];

            if ($datas['course'] == '13' || $datas['course'] == 13) {
                // dd('hii');
                for ($i = 0; $i < count($regular); $i++) {
                    if ($regular[$i]['name'] != 'checkbox') {
                        array_push($array, ['option_limits' => 0, 'category' => 'Regular Subject', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $regular[$i]['value']]);
                    }
                    if ($regular[$i]['name'] == 'checkbox') {
                        array_push($check_box_array, $regular[$i]['value']);
                    }
                }
                if ($professional != '' && $open != '' && $others != '' && $logistics != '') {
                    for ($i = 0; $i < count($professional); $i++) {
                        if ($professional[$i]['name'] != 'checkbox') {
                            array_push($array, ['option_limits' => 0, 'category' => 'Electives HR', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $professional[$i]['value']]);
                        }
                        if ($professional[$i]['name'] == 'checkbox') {
                            array_push($check_box_array, $professional[$i]['value']);
                        }
                    }
                    for ($i = 0; $i < count($open); $i++) {
                        if ($open[$i]['name'] != 'checkbox') {
                            array_push($array, ['option_limits' => 0, 'category' => 'Electives Finance', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $open[$i]['value']]);
                        }
                        if ($open[$i]['name'] == 'checkbox') {
                            array_push($check_box_array, $open[$i]['value']);
                        }
                    }
                    for ($i = 0; $i < count($others); $i++) {
                        if ($others[$i]['name'] != 'checkbox') {
                            array_push($array, ['option_limits' => 0, 'category' => 'Electives Operations', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $others[$i]['value']]);
                        }
                        if ($others[$i]['name'] == 'checkbox') {
                            array_push($check_box_array, $others[$i]['value']);
                        }
                    }
                    for ($i = 0; $i < count($logistics); $i++) {
                        if ($logistics[$i]['name'] != 'checkbox') {
                            array_push($array, ['option_limits' => 0, 'category' => 'Electives Logistics and supply chain management', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $logistics[$i]['value']]);
                        }
                        if ($logistics[$i]['name'] == 'checkbox') {
                            array_push($check_box_array, $logistics[$i]['value']);
                        }
                    }
                }

            } else {
                for ($i = 0; $i < count($regular); $i++) {
                    if ($regular[$i]['name'] != 'checkbox') {
                        array_push($array, ['option_limits' => 0, 'category' => 'Regular Subject', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $regular[$i]['value']]);
                    }
                    if ($regular[$i]['name'] == 'checkbox') {
                        array_push($check_box_array, $regular[$i]['value']);
                    }
                }

                // for ($i = 1; $i < count($professional); $i++) {
                //     if ($professional[$i]['name'] != 'checkbox') {
                //         array_push($array, ['option_limits' => $professional[0]['value'], 'category' => 'Professional Electives', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $professional[$i]['value']]);
                //     }
                //     if ($professional[$i]['name'] == 'checkbox') {
                //         array_push($check_box_array, $professional[$i]['value']);
                //     }
                // }
                // for ($i = 1; $i < count($open); $i++) {
                //     if ($open[$i]['name'] != 'checkbox') {
                //         array_push($array, ['option_limits' => $open[0]['value'], 'category' => 'Open Electives', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $open[$i]['value']]);
                //     }
                //     if ($open[$i]['name'] == 'checkbox') {
                //         array_push($check_box_array, $open[$i]['value']);
                //     }
                // }
                // for ($i = 1; $i < count($others); $i++) {
                //     if ($others[$i]['name'] != 'checkbox') {
                //         array_push($array, ['option_limits' => $others[0]['value'], 'category' => 'Others', 'regulation' => $datas['reg'], 'department' => $datas['dept'], 'course' => $datas['course'], 'semester' => $datas['sem'], 'sem_type' => $datas['sem_type'], 'ay' => $datas['ay'], 'subject' => $others[$i]['value']]);
                //     }
                //     if ($others[$i]['name'] == 'checkbox') {
                //         array_push($check_box_array, $others[$i]['value']);
                //     }
                // }
            }
            // dd($array, $check_box_array);
            if (count($array) > 0) {
                foreach ($array as $data) {
                    // dd($data);
                    $check = SubjectAllotment::where(['regulation' => $data['regulation'], 'course' => $data['course'], 'academic_year' => $data['ay'], 'semester_type' => $data['sem_type'], 'semester' => $data['semester'], 'subject_id' => $data['subject']])->get();
                    if (count($check) <= 0) {

                        $insert = new SubjectAllotment;
                        $insert->regulation = $data['regulation'];
                        $insert->department = $data['department'];
                        $insert->course = $data['course'];
                        $insert->academic_year = $data['ay'];
                        $insert->semester_type = $data['sem_type'];
                        $insert->semester = $data['semester'];
                        $insert->category = $data['category'];
                        $insert->subject_id = $data['subject'];
                        $insert->option_limits = $data['option_limits'];
                        $insert->created_at = Carbon::now()->toDateString();
                        $insert->save();
                    } else {
                        $update = SubjectAllotment::where(['regulation' => $data['regulation'], 'course' => $data['course'], 'academic_year' => $data['ay'], 'semester_type' => $data['sem_type'], 'semester' => $data['semester'], 'subject_id' => $data['subject']])->update([
                            'option_limits' => $data['option_limits'],
                            'department' => $data['department'],
                            'category' => $data['category'],
                            'updated_at' => Carbon::now()->toDateString(),
                        ]);

                    }
                }
            }

            if (count($check_box_array) > 0) {
                // dd($check_box_array);
                foreach ($check_box_array as $delete) {

                    $update = SubjectAllotment::find($delete);
                    $update->delete();
                }
            }
            return response()->json(['status' => true, 'data'=> 'Subject Allotement Updated.']);
        }
        // return redirect()->route('admin.subjects.index');
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('subject_allotment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->regulation);
        $regular = $professional = $open = $others = [];
        $dept = $reg = $course = $sem = $ay = $sem_type = null;

        if (isset($request->regulation) && isset($request->department) && isset($request->semester) && isset($request->course) && isset($request->semester_type) && isset($request->academic_year)) {

            $alloted_subjects = SubjectAllotment::with(['subjects'])->where(['regulation' => $request->regulation, 'department' => $request->department, 'semester' => $request->semester, 'course' => $request->course, 'semester_type' => $request->semester_type, 'academic_year' => $request->academic_year])->get();
            $hr = [];
            $operations = [];
            $finance = [];
            $logistics = [];
            $regular = [];
            $professional = [];
            $open = [];
            $others = [];
            // dd($alloted_subjects);
            foreach ($alloted_subjects as $subject) {
                $subject->subjects->subject_type_id = null;
                $get_sub = Subject::where(['id' => $subject->subject_id])->first();
                if ($get_sub != '') {
                    $get_sub_type = SubjectType::where(['id' => $get_sub->subject_type_id])->first();
                    // dd($subject->category);
                    if ($get_sub_type != '') {
                        $subject->subjects->subject_type_id = $get_sub_type->name;
                    }
                }

                $check_course = $request->course;
                if ($request->course == 13 || $request->course == '13') {
                    if ($subject->category == 'Regular Subject') {
                        array_push($regular, $subject);
                    }
                    if ($subject->category == 'Electives HR') {
                        array_push($hr, $subject);
                    }
                    if ($subject->category == 'Electives Operations') {
                        array_push($operations, $subject);
                    }
                    if ($subject->category == 'Electives Finance') {
                        array_push($finance, $subject);
                    }
                    if ($subject->category == 'Electives Logistics and supply chain management') {
                        array_push($logistics, $subject);
                    }
                    $professional = [];
                    $open = [];
                    $others = [];
                } else {
                    if ($subject->category == 'Regular Subject') {
                        array_push($regular, $subject);
                    }
                    if ($subject->category == 'Professional Electives') {
                        array_push($professional, $subject);
                    }
                    if ($subject->category == 'Open Electives') {
                        array_push($open, $subject);
                    }
                    if ($subject->category == 'Others') {
                        array_push($others, $subject);
                    }
                    $hr = [];
                    $operations = [];
                    $finance = [];
                    $logistics = [];
                }

            }
            // dd($regular);
            $get_department = ToolsDepartment::where(['id' => $request->department])->first();

            $get_course = ToolsCourse::where(['id' => $request->course])->first();

            $get_regulation = ToolssyllabusYear::where(['id' => $request->regulation])->first();

            $get_semester = Semester::where(['id' => $request->semester])->first();

            $get_academic_year = AcademicYear::where(['id' => $request->academic_year])->first();

            if ($get_department != '') {
                $dept = $get_department->name;
            }
            if ($get_course != '') {
                $course = $get_course->short_form;
            }
            if ($get_regulation != '') {
                $reg = $get_regulation->name;
            }
            if ($get_semester != '') {
                $sem = $get_semester->semester;
            }
            if ($get_academic_year != '') {
                $ay = $get_academic_year->name;
            }

            $sem_type = $request->semester_type;
        }
        return view('admin.subjectAllotment.show', compact('check_course', 'hr', 'operations', 'finance', 'logistics', 'regular', 'professional', 'open', 'others', 'dept', 'reg', 'course', 'sem', 'ay', 'sem_type'));
    }
    public function statusUpdate(UpdateSubjectRequest $request, Subject $subject)
    {

        if ($request) {
            // dd($request->data['id']);
            // $subject->update(['status'=>$request->data['status']]);
            subject::where('id', $request->data['id'])->update(['status' => $request->data['status'], 'rejected_reason' => $request->data['status'] != '' ? $request->data['status'] : '']);

            return response()->json(['status' => 'ok']);

        }

    }
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('subject_allotment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $alloted_subjects = SubjectAllotment::where(['regulation' => $request->regulation, 'department' => $request->department, 'semester' => $request->semester, 'course' => $request->course, 'semester_type' => $request->semester_type, 'academic_year' => $request->academic_year])->get();
        foreach ($alloted_subjects as $allot) {
            $allot->delete();
        }
        return back();
    }

    public function massDestroy(MassDestroySubjectRequest $request)
    {
        $subjects = Subject::find(request('ids'));

        foreach ($subjects as $subject) {
            $subject->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function get_sub_categories(Request $request)
    {
        // dd($request->data);
        if ($request->data != '') {
            $get_cat = SubjectCategory::where(['regulation_id' => $request->data])->get();
            $get_type = SubjectType::where(['regulation_id' => $request->data])->get();
            return response()->json(['cat' => $get_cat, 'type' => $get_type]);
        }
    }

    public function get_subjects(Request $request)
    {
        $get_subjects = [];
        $get_subjects_1 = Subject::with(['subject_type'])->where(['regulation_id' => $request->reg, 'course_id' => $request->course, 'department_id' => $request->dept, 'semester_id' => $request->sem])->get();
        if (count($get_subjects_1) > 0) {
            foreach ($get_subjects_1 as $subjects) {
                array_push($get_subjects, $subjects);
            }
        }
        // dd($get_subjects);
        // $subjects = [];
        // $get_subjects = SubjectAllotment::where(['regulation' => $request->reg, 'semester' => $request->sem, 'course' => $request->course])->get();
        // if (count($get_subjects) > 0) {
        //     foreach ($get_subjects as $subject) {
        //         $got_subject = Subject::where(['id' => $subject->subject_id])->first();
        //         if ($got_subject != '') {
        //             array_push($subjects, $got_subject);
        //         }
        //     }
        // }

        // $get_subjects_2 = Subject::with(['subject_type'])->where(['regulation_id' => $request->reg, 'semester_id' => null])->get();
        // if (count($get_subjects_2) > 0) {
        //     foreach ($get_subjects_2 as $subjects) {
        //         array_push($get_subjects, $subjects);
        //     }
        // }
        // dd($subjects);
        return response()->json(['subjects' => $get_subjects]);
    }
}
