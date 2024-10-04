<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAcademicDetailRequest;
use App\Http\Requests\StoreAcademicDetailRequest;
use App\Http\Requests\UpdateAcademicDetailRequest;
use App\Models\AcademicDetail;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\NonTeachingStaff;
use App\Models\Scholarship;
use App\Models\Section;
use App\Models\Semester;
use App\Models\ShiftModel;
use App\Models\Student;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class AcademicDetailsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('academic_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AcademicDetail::with(['enroll_master_number'])->select(sprintf('%s.*', (new AcademicDetail)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'academic_detail_show';
                $editGate = 'academic_detail_edit';
                $deleteGate = 'academic_detail_delete';
                $crudRoutePart = 'academic-details';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('enroll_master_number_enroll_master_number', function ($row) {
                return $row->enroll_master_number ? $row->enroll_master_number->enroll_master_number : '';
            });

            $table->editColumn('register_number', function ($row) {
                return $row->register_number ? $row->register_number : '';
            });
            $table->editColumn('emis_number', function ($row) {
                return $row->emis_number ? $row->emis_number : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'enroll_master_number']);

            return $table->make(true);
        }

        return view('admin.academicDetails.index');
    }

    public function stu_index(Request $request)
    {
        abort_if(Gate::denies('academic_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {
            $query = AcademicDetail::with(['enroll_master_number'])->where(['user_name_id' => $request->user_name_id])->get();
        }

        $enroll_master_numbers = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');
        $course = ToolsCourse::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $ToolsDepartment = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $Batch = Batch::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $AcademicYear = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $Semester = Semester::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');
        $shift = ShiftModel::pluck('Name', 'id');
        $Section = Section::pluck('section', 'id')->prepend(trans('global.pleaseSelect'), '')->unique();
        $stu_main = Student::where(['user_name_id' => $request->user_name_id])->first();
        $scholarships = Scholarship::select('name', 'id')->get();

        if ($query->count() <= 0) {

            $query->id = $request->user_name_id;
            $query->name = $request->name;
            $query->register_number = null;
            $query->roll_no = null;
            $query->emis_number = null;
            $query->enroll_master_number_id = $stu_main->enroll_master_id ?? null;
            $query->enroll_master_number = $enroll_master_numbers;
            $query->batch = $Batch;
            $query->accademicYear = $AcademicYear;
            $query->section = $Section;
            $query->semester = $Semester;
            $query->admitted_mode = null;
            $query->admitted_courses = $course;
            $query->first_graduate = '0';
            $query->scholarship = '0';
            $query->gqg = '0';
            $query->late_entry = '0';
            $query->hosteler = '0';
            $query->scholarships = $scholarships;
            $query->student_id = null;
            $query->batch_id = null;
            $query->course_id = null;
            $query->accademicYear_id = null;
            $query->semester_id = null;
            $query->section_id = null;
            $query->user_name_id = $request->user_name_id;
            $query->add = 'Add';

            $student = $query;

        } else {

            if ($query[0]['enroll_master_number_id'] != '') {
                $eroll = CourseEnrollMaster::find($query[0]['enroll_master_number_id']);

                if ($eroll) {
                    $array = explode('/', $eroll->enroll_master_number);

                    if ($array) {
                        $coursE = $array[1];
                        $batcH = $array[0];
                        $accademicyeaR = $array[2];
                        $sectioN = $array[4];
                        $semE = $array[3];
                    } else {
                        $coursE = '';
                        $batcH = '';
                        $accademicyeaR = '';
                        $sectioN = '';
                        $semE = '';

                    }

                }
            } else {
                $coursE = '';
                $batcH = '';
                $accademicyeaR = '';
                $sectioN = '';
                $semE = '';
            }

            $query[0]['id'] = $request->user_name_id;

            $query[0]['name'] = $request->name;

            $query[0]['enroll_master_number'] = $enroll_master_numbers;

            $query[0]['admitted_courses'] = $course;
            $query[0]['batch'] = $Batch;
            $query[0]['accademicYear'] = $AcademicYear;
            $query[0]['section'] = $Section;
            $query[0]['semester'] = $Semester;
            $query[0]['batch_id'] = $batcH;
            $query[0]['course_id'] = $coursE;
            $query[0]['accademicYear_id'] = $accademicyeaR;
            $query[0]['semester_id'] = $semE;
            $query[0]['section_id'] = $sectioN;
            $query[0]['scholarships'] = $scholarships;

            $query[0]['add'] = 'Update';

            $student = $query[0];

        }
        $check = 'academic_details';
        return view('admin.StudentProfile.student', compact('student', 'check', 'shift'));
    }

    public function stu_update(UpdateAcademicDetailRequest $request, AcademicDetail $academicDetail)
    {

        $check_reg_no = User::where(['register_no' => $request->register_number])->where('id', '!=', $request->user_name_id)->get();

        if (count($check_reg_no) > 0) {
            return back()->withErrors(['register_number' => 'The Register Number Already Exist']);
        }
        if ($request->admitted_course != '' && $request->batch != '' && $request->accademicYear != '' && $request->semester != '' && $request->section != '') {
            $enrollMaster = $request->batch . '/' . $request->admitted_course . '/' . $request->accademicYear . '/' . $request->semester . '/' . $request->section;

            $iD = CourseEnrollMaster::where('enroll_master_number', $enrollMaster)->first();

            if ($iD) {
                $enrollMasterId = $iD->id;

            } else {
                $enrollMasterId = null;
            }

        } else {

            $enrollMasterId = null;
        }

        $academy = $academicDetail->where('user_name_id', $request->user_name_id)->update([
            'enroll_master_number_id' => $enrollMasterId,
            'register_number' => $request->register_number,
            'emis_number' => $request->emis_number,
            'shift_id' => $request->shift,
            'admitted_mode' => $request->admitted_mode,
            'first_graduate' => $request->first_graduate ?? '0',
            'scholarship' => $request->scholarship ?? '0',
            'scholarship_name' => isset($request->scholarship_name) ? ($request->scholarship_name != '' ? $request->scholarship_name : null) : null,
            'gqg' => $request->gqg ?? '0',
            'late_entry' => $request->late_entry ?? '0',
            'hosteler' => $request->hosteler ?? '0',
        ]);

        $stu_update = Student::where('user_name_id', $request->user_name_id)->update([
            'enroll_master_id' => $enrollMasterId,
            'register_no' => $request->register_number,
            'shift_id' => $request->shift,
            'roll_no' => $request->roll_no,
            'student_batch' => $request->batch,
            'admitted_course' => $request->admitted_course,
        ]);

        $user_update = User::where(['id' => $request->user_name_id])->update([
            'register_no' => $request->register_number,
        ]);

        if ($academy) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_academic = new AcademicDetail;
            $stu_academic->register_number = $request->register_number;
            $stu_academic->roll_no = $request->roll_no;
            $stu_academic->admitted_mode = $request->admitted_mode;
            $stu_academic->admitted_course = $request->admitted_course;
            $stu_academic->batch = $request->batch;
            $stu_academic->shift_id = $request->shift;
            $stu_academic->emis_number = $request->emis_number;
            $stu_academic->enroll_master_number_id = $enrollMasterId;
            $stu_academic->user_name_id = $request->user_name_id;
            $stu_academic->first_graduate = $request->first_graduate ?? '0';
            $stu_academic->scholarship = $request->scholarship ?? '0';
            $stu_academic->gqg = $request->gqg ?? '0';
            $stu_academic->late_entry = $request->late_entry ?? '0';
            $stu_academic->hosteler = $request->hosteler ?? '0';
            $stu_academic->save();

            if ($stu_academic) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                return back();
            }

        }

        return redirect()->route('admin.academic-details.stu_index', $student);
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('academic_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {
            $query = AcademicDetail::with(['enroll_master_number'])->where(['user_name_id' => $request->user_name_id])->get();
        }

        if ($query->count() <= 0) {

            $query->id = $request->user_name_id;
            $query->name = $request->name;
            $query->user_name_id = $request->user_name_id;
            $query->add = 'Add';

            $staff = $query;

        } else {

            $query[0]['id'] = $request->user_name_id;

            $query[0]['name'] = $request->name;

            $query[0]['add'] = 'Update';

            $staff = $query[0];

        }

        $check = 'academic_details';
        $check_staff_1 = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check'));
            }
        }
    }
    public function staff_update(UpdateAcademicDetailRequest $request, AcademicDetail $academicDetail)
    {
        $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        return redirect()->route('admin.academic-details.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('academic_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_master_numbers = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.academicDetails.create', compact('enroll_master_numbers'));
    }

    public function store(StoreAcademicDetailRequest $request)
    {
        $academicDetail = AcademicDetail::create($request->all());

        return redirect()->route('admin.academic-details.index');
    }

    public function edit(AcademicDetail $academicDetail)
    {
        abort_if(Gate::denies('academic_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_master_numbers = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academicDetail->load('enroll_master_number');

        return view('admin.academicDetails.edit', compact('academicDetail', 'enroll_master_numbers'));
    }

    public function update(UpdateAcademicDetailRequest $request, AcademicDetail $academicDetail)
    {
        $academicDetail->update($request->all());

        return redirect()->route('admin.academic-details.index');
    }

    public function show(AcademicDetail $academicDetail)
    {
        abort_if(Gate::denies('academic_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicDetail->load('enroll_master_number');

        return view('admin.academicDetails.show', compact('academicDetail'));
    }

    public function destroy(AcademicDetail $academicDetail)
    {
        abort_if(Gate::denies('academic_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyAcademicDetailRequest $request)
    {
        $academicDetails = AcademicDetail::find(request('ids'));

        foreach ($academicDetails as $academicDetail) {
            $academicDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
