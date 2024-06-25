<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\BulkOD;
use App\Models\Student;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\User;
use App\Models\UserAlert;
use Illuminate\Http\Request;

class BulkODController extends Controller
{
    use CsvImportTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $deparments = ToolsDepartment::pluck('name', 'id');
        $organized_by = '';
        $dept_name = '';
        $event_category = '';
        $from_date = '';
        $to_date = '';

        if (!isset($request->organized_by)) {
            $get_data = BulkOD::with(['tech_staff'])->groupBy('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->select('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->get();

            if (count($get_data) > 0) {
                foreach ($get_data as $data) {
                    $get_students = BulkOD::where(['organized_by' => $data->organized_by, 'dept_name' => $data->dept_name, 'incharge' => $data->incharge, 'event_title' => $data->event_title, 'event_category' => $data->event_category, 'from_date' => $data->from_date, 'to_date' => $data->to_date])->get();
                    if (count($get_students) > 0) {
                        $data->id = $get_students[0]->id;
                        $data->count = count($get_students);
                    }
                }
            }
        } else {

            $organized_by = $request->organized_by;
            $dept_name = $request->dept_name;
            $event_category = $request->event_category;
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            if ($organized_by != '' && $dept_name != '' && $event_category != '' && $from_date != '' && $to_date != '') {

                $get_students = BulkOD::where(['organized_by' => $organized_by, 'dept_name' => $dept_name, 'event_category' => $event_category, 'from_date' => $from_date, 'to_date' => $to_date])->groupBy('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->select('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->get();
            } else if ($organized_by != '' && $dept_name != '' && $event_category != '' && $from_date != '' && $to_date == '') {

                $get_students = BulkOD::where(['organized_by' => $organized_by, 'dept_name' => $dept_name, 'event_category' => $event_category, 'from_date' => $from_date])->groupBy('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->select('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->get();
            } else if ($organized_by != '' && $dept_name != '' && $event_category != '' && $from_date == '' && $to_date == '') {

                $get_students = BulkOD::where(['organized_by' => $organized_by, 'dept_name' => $dept_name, 'event_category' => $event_category])->groupBy('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->select('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->get();
            } else if ($organized_by != '' && $dept_name != '' && $event_category == '' && $from_date != '' && $to_date != '') {

                $get_students = BulkOD::where(['organized_by' => $organized_by, 'dept_name' => $dept_name, 'from_date' => $from_date, 'to_date' => $to_date])->groupBy('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->select('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->get();
            } else if ($organized_by != '' && $dept_name != '' && $event_category == '' && $from_date == '' && $to_date != '') {

                $get_students = BulkOD::where(['organized_by' => $organized_by, 'dept_name' => $dept_name, 'to_date' => $to_date])->groupBy('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->select('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->get();
            } else if ($organized_by != '' && $dept_name != '' && $event_category != '' && $from_date == '' && $to_date != '') {

                $get_students = BulkOD::where(['organized_by' => $organized_by, 'dept_name' => $dept_name, 'event_category' => $event_category, 'to_date' => $to_date])->groupBy('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->select('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->get();
            } else if ($organized_by != '' && $dept_name != '' && $event_category == '' && $from_date == '' && $to_date == '') {

                $get_students = BulkOD::where(['organized_by' => $organized_by, 'dept_name' => $dept_name])->groupBy('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->select('organized_by', 'dept_name', 'incharge', 'event_category', 'from_date', 'to_date', 'event_title', 'status')->get();
            }

            if (count($get_students) > 0) {
                foreach ($get_students as $data) {
                    $get_id = BulkOD::with(['tech_staff'])->where(['organized_by' => $data->organized_by, 'dept_name' => $data->dept_name, 'incharge' => $data->incharge, 'event_title' => $data->event_title, 'event_category' => $data->event_category, 'from_date' => $data->from_date, 'to_date' => $data->to_date])->get();
                    if (count($get_id) > 0) {
                        $data->id = $get_id[0]->id;
                        $data->count = count($get_id);
                    }
                }

            }

            $get_data = $get_students;

        }

        return view('admin.bulkOD.index', compact('get_data', 'deparments', 'organized_by', 'dept_name', 'event_category', 'from_date', 'to_date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $getStaff = TeachingStaff::with('personal_details:user_name_id,employment_status')->select('user_name_id', 'name', 'StaffCode')->get();
        $teaching_staff = [];
        if (count($getStaff) > 0) {
            foreach ($getStaff as $staff) {
                if ($staff->personal_details->employment_status == 'Active' || $staff->personal_details->employment_status == '') {
                    array_push($teaching_staff, $staff);
                }
            }
        }
        $students = Student::where('enroll_master_id', '!=', null)->select('name', 'user_name_id', 'register_no')->get();
        $deparments = ToolsDepartment::pluck('name', 'id');
        return view('admin.bulkOD.create', compact('teaching_staff', 'students', 'deparments'));
    }




public function documents(Request $request)
{
    if ($request->hasFile('file')) {
        $file = $request->file('file');

        // Validate file size (5 MB limit)
        if ($file->getSize() <= 5 * 1024 * 1024) {
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'png', 'pdf'];

            if (in_array($extension, $allowedExtensions)) {
                $fileName = time() . '.' . $extension;
                $destinationPath = public_path('uploads');

                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $fileName);
                $path = 'uploads/' . $fileName;

                return response()->json(['path' => $path]);
            } else {
                return response()->json(['error' => 'Only JPG, PNG, and PDF files are allowed.'], 400);
            }
        } else {
            return response()->json(['error' => 'File size exceeds the limit of 5 MB.'], 400);
        }
    } else {
        return response()->json(['error' => 'No file was uploaded.'], 400);
    }
}



    public function save(Request $request)
    {

        $student = '';

        if (isset($request->data)) {
            $student = Student::with(['enroll_master'])->where(['user_name_id' => $request->data])->first();

            if ($student != '' && isset($student->enroll_master)) {
                $split_enroll = explode('/', $student->enroll_master->enroll_master_number);
                $course = $split_enroll[1];
                $semester = $split_enroll[3];
                $section = $split_enroll[4];

                $dept_id = ToolsCourse::where('name', 'like', $course)->select('department_id')->first();
                $dept = '';
                if ($dept_id != '') {
                    $get_dept = ToolsDepartment::where(['id' => $dept_id->department_id])->first();
                    if ($get_dept != '') {
                        $dept = $get_dept->name;
                    }
                }

                $student->course = $course;
                $student->semester = $semester;
                $student->section = $section;
                $student->dept = $dept;
            }
        }
        return response()->json(['student' => $student]);
    }

    public function check(Request $request)
    {
        if (isset($request->search_form)) {
            $organized_by = $request->search_form[0]['value'];
            $dept_name = $request->search_form[1]['value'];
            $incharge = $request->search_form[2]['value'];
            $event_title = $request->search_form[3]['value'];
            $event_category = $request->search_form[4]['value'];
            $from_date = $request->search_form[5]['value'];
            $to_date = $request->search_form[6]['value'];

            $check = BulkOD::where(['organized_by' => $organized_by, 'dept_name' => $dept_name, 'incharge' => $incharge, 'event_title' => $event_title, 'event_category' => $event_category, 'from_date' => $from_date, 'to_date' => $to_date])->get();

            if (count($check) > 0) {
                return response()->json(['status' => true]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(isset($request->document) && $request->document !=''){
            $urls=json_encode($request->document);
        }else{
            $urls='';
        }
        if (isset($request->student_form) && isset($request->search_form)) {
            if (count($request->student_form) > 0 && count($request->search_form) > 0) {

                $organized_by = $request->search_form[0]['value'];
                $dept_name = $request->search_form[1]['value'];
                $incharge = $request->search_form[2]['value'];
                $event_title = $request->search_form[3]['value'];
                $event_category = $request->search_form[4]['value'];
                $ext_event_venue = $request->search_form[5]['value'];
                $duration = $request->search_form[6]['value'];
                $from_period = $request->search_form[7]['value'];
                $to_period = $request->search_form[8]['value'];
                $from_date = $request->search_form[9]['value'];
                $to_date = $request->search_form[10]['value'];

                $all_students = [];
                foreach ($request->student_form as $student) {

                    $get_student = Student::with(['enroll_master'])->where(['user_name_id' => $student['value']])->select('name', 'user_name_id', 'register_no', 'enroll_master_id')->first();
                    if ($get_student != '') {

                        $split_enroll = explode('/', $get_student->enroll_master->enroll_master_number);
                        $course = $split_enroll[1];
                        $ay = $split_enroll[2];
                        $semester = $split_enroll[3];
                        $section = $split_enroll[4];

                        $dept_id = ToolsCourse::where('name', 'like', $course)->select('department_id')->first();
                        $dept = '';
                        if ($dept_id != '') {
                            $get_dept = ToolsDepartment::where(['id' => $dept_id->department_id])->first();
                            if ($get_dept != '') {
                                $dept = $get_dept->name;
                            }
                        }
                        $get_student->course = $course;
                        $get_student->academic_year = $ay;
                        $get_student->semester = $semester;
                        $get_student->section = $section;
                        $get_student->dept = $dept;

                        array_push($all_students, $get_student);
                    }
                }

                if (count($all_students) > 0) {

                    foreach ($all_students as $student) {

                        $bulkOD = BulkOD::create([
                            'organized_by' => $organized_by,
                            'dept_name' => $dept_name,
                            'incharge' => $incharge,
                            'event_title' => $event_title,
                            'event_category' => $event_category,
                            'ext_event_venue' => $ext_event_venue,
                            'duration' => $duration,
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'from_period' => $from_period,
                            'to_period' => $to_period,
                            'register_no' => $student->register_no,
                            'academic_year' => $student->academic_year,
                            'dept' => $student->dept,
                            'course' => $student->course,
                            'semester' => $student->semester,
                            'section' => $student->section,
                            'user_name_id' => $student->user_name_id,
                            'document'=>$urls,
                        ]);

                    }

                    $userAlert = new UserAlert;
                    $userAlert->alert_text = 'Institute OD Application From  ' . $dept_name;
                    $userAlert->alert_link = route('admin.bulk-ods.index');
                    $userAlert->save();

                    $roles = [1, 15];
                    $users = User::whereHas('roles', function ($query) use ($roles) {
                        $query->whereIn('id', $roles);
                    })->get();
                    $ids = [];
                    foreach ($users as $user) {
                        array_push($ids, $user->id);
                    }
                    $userAlert->users()->sync($request->input('users', $ids));

                }
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $students = [];
        $organized_by = '';
        $dept_name = '';
        $incharge = '';
        $event_title = '';
        $event_category = '';
        $ext_event_venue = '';
        $from_date = '';
        $to_date = '';
        $from_period = '';
        $to_period = '';
        $Id = 0;
        $status = 2;
        $document='';

        if ($id != '') {
            $data = BulkOD::where(['id' => $id])->first();
            if ($data != '') {
                $get_students = BulkOD::with(['student', 'tech_staff'])->where(['organized_by' => $data->organized_by, 'dept_name' => $data->dept_name, 'incharge' => $data->incharge, 'event_title' => $data->event_title, 'event_category' => $data->event_category, 'from_date' => $data->from_date, 'to_date' => $data->to_date])->get();

                if (count($get_students) > 0) {
                    $students = $get_students;
                    $Id = $get_students[0]->id;
                    $status = $get_students[0]->status;
                    $organized_by = $get_students[0]->organized_by;
                    $dept_name = $get_students[0]->dept_name;
                    $incharge = $get_students[0]->tech_staff->name . '  (' . $get_students[0]->tech_staff->StaffCode . '  )';
                    $event_title = $get_students[0]->event_title;
                    $event_category = $get_students[0]->event_category;
                    $ext_event_venue = $get_students[0]->ext_event_venue;
                    $from_date = $get_students[0]->from_date;
                    $to_date = $get_students[0]->to_date;
                    $document = $get_students[0]->document != '' ? json_decode($get_students[0]->document) : '' ;
                    $from_period = $get_students[0]->from_period;
                    $to_period = $get_students[0]->to_period;
                }
            }
        }

        return view('admin.bulkOD.show', compact('document','to_period','from_period','ext_event_venue','status', 'students', 'organized_by', 'dept_name', 'incharge', 'event_title', 'event_category', 'from_date', 'to_date', 'Id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {

    }

    public function action(Request $request)
    {
        if (isset($request->id) && isset($request->action)) {
            $id = $request->id;
            $action = $request->action;

            if ($id != '') {
                $data = BulkOD::where(['id' => $id])->first();
                if ($data != '') {
                    if ($action == 'approve') {
                        $get_students = BulkOD::with(['student', 'tech_staff'])->where(['organized_by' => $data->organized_by, 'dept_name' => $data->dept_name, 'incharge' => $data->incharge, 'event_title' => $data->event_title, 'event_category' => $data->event_category, 'from_date' => $data->from_date, 'to_date' => $data->to_date])->update([
                            'status' => 1,
                        ]);
                    } else if ($action == 'reject') {
                        $get_students = BulkOD::with(['student', 'tech_staff'])->where(['organized_by' => $data->organized_by, 'dept_name' => $data->dept_name, 'incharge' => $data->incharge, 'event_title' => $data->event_title, 'event_category' => $data->event_category, 'from_date' => $data->from_date, 'to_date' => $data->to_date])->update([
                            'status' => 2,
                            'rejected_reason' => $request->rejected_reason,
                        ]);
                    }

                }
            }
            return response()->json(['status' => true]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
