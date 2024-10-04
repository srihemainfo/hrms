<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Section;
use App\Models\Semester;
use App\Models\ClassRoom;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use App\Models\RoomCreation;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\ToolsDepartment;
use App\Models\CourseEnrollMaster;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreClassRoomRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyClassRoomRequest;
use Google\Service\CloudSearch\Id;
use Illuminate\Support\Facades\DB;

class ClassRoomsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        // if ($request->ajax()) {
        //     $query = ClassRoom::with(['room', 'block', 'enroll_master', 'department', 'teaching_staff'])->select(sprintf('%s.*', (new ClassRoom)->table));
        //     $table = Datatables::of($query);

        //     $table->addColumn('placeholder', '&nbsp;');
        //     $table->addColumn('actions', '&nbsp;');

        //     $table->editColumn('actions', function ($row) {
        //         $viewGate = 'class_room_show';
        //         $editGate = 'class_room_edit';
        //         $deleteGate = 'class_room_delete';
        //         $crudRoutePart = 'class-rooms';

        //         return view('partials.datatablesActions', compact(
        //             'viewGate',
        //             'editGate',
        //             'deleteGate',
        //             'crudRoutePart',
        //             'row'
        //         ));
        //     });

        //     $table->addColumn('enroll_master', function ($row) {
        //         return $row->enroll_master ? $row->enroll_master->enroll_master_number : '';
        //     });

        //     $table->addColumn('department', function ($row) {
        //         return $row->department ? $row->department->name : '';
        //     });

        //     $table->addColumn('teaching_staff', function ($row) {
        //         return $row->teaching_staff ? $row->teaching_staff->name : '';
        //     });

        //     $table->editColumn('short_form', function ($row) {
        //         return $row->short_form ? $row->short_form : '';
        //     });

        //     $table->rawColumns(['actions', 'placeholder', 'block', 'room', 'enroll_master', 'teaching_staff', 'department']);

        //     return $table->make(true);
        // }

        if ($request->ajax()) {
            // $query = ClassRoom::query()->select(sprintf('%s.*', (new ClassRoom)->table));

            $query = DB::table('class_rooms')
                ->whereNull('class_rooms.deleted_at')
                ->leftJoin('course_enroll_masters', 'course_enroll_masters.id', '=', 'class_rooms.name')
                ->leftJoin('teaching_staffs', 'class_rooms.class_incharge', '=', 'teaching_staffs.user_name_id')
                ->select('course_enroll_masters.enroll_master_number', 'teaching_staffs.name', 'class_rooms.id')
                ->get();
            // dd($query);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $editFunct = 'editClass_room';
                $deleteFunct = 'deleteClass_room';
                $editGate = 'class_room_edit';
                $deleteGate = 'class_room_delete';
                $crudRoutePart = 'class-rooms';

                return view(
                    'partials.ajaxTableActions',
                    compact(
                        'editGate',
                        'deleteGate',
                        'editFunct',
                        'deleteFunct',
                        'crudRoutePart',
                        'row'
                    )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->enroll_master_number ? $row->enroll_master_number : '';
            });
            $table->editColumn('incharge', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $course = ToolsCourse::pluck('short_form', 'id');
        $Batch = Batch::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $Semester = Semester::pluck('semester', 'id');
        $Section = Section::pluck('section', 'id')->unique();
        $staffs = TeachingStaff::pluck('name', 'user_name_id');
        return view('admin.classRooms.index', compact('course', 'Batch', 'ay', 'Semester', 'Section', 'staffs'));
    }

    public function create()
    {
        abort_if(Gate::denies('class_room_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $course = ToolsCourse::pluck('short_form', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $ToolsDepartment = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $Batch = Batch::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $AcademicYear = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $Semester = Semester::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');
        $Section = Section::pluck('section', 'id')->prepend(trans('global.pleaseSelect'), '')->unique();

        $getStaff = TeachingStaff::with('personal_details:user_name_id,employment_status')->select('user_name_id', 'name', 'StaffCode')->get();
        $teachingStaff = [];
        if (count($getStaff) > 0) {
            foreach ($getStaff as $staff) {
                if ($staff->personal_details->employment_status == 'Active' || $staff->personal_details->employment_status == '') {
                    array_push($teachingStaff, $staff);
                }
            }
        }

        return view('admin.classRooms.create', compact('teachingStaff', 'course', 'ToolsDepartment', 'Batch', 'AcademicYear', 'Semester', 'Section'));
    }

    public function store(Request $request)
    {
        if ($request->id != '') {
            $check = ClassRoom::where(['id' => $request->id])->exists();
            // dd($request);
            if ($check == true) {
                $update = ClassRoom::where(['id' => $request->id])->update([
                    'class_incharge' => $request->incharge,
                ]);
                return response()->json(['status' => true, 'data' => 'Class Incharge Updated']);
            } else {
                return response()->json(['error' => false, 'data' => 'Tecnical Error']);
            }
        } else {
            $get_enroll = CourseEnrollMaster::where(['batch_id' => $request->batch, 'course_id' => $request->course, 'academic_id' => $request->ay, 'semester_id' => $request->sem, 'section' => $request->sec])->select('id')->first();
            // dd($get_enroll);
            if ($get_enroll != null) {
                $classRoom = new ClassRoom;
                $class = $classRoom->where('name', $get_enroll->id)->orWhere('class_incharge', $request->incharge)->exists();
                if ($class == false) {
                    $classRoom->name = $get_enroll->id;
                    $classRoom->class_incharge = $request->incharge;
                    $classRoom->save();
                    return response()->json(['status' => true, 'data' => 'Class Incharge Created']);
                } else {
                    return response()->json(['status' => false, 'data' => 'Class Incharge Already Created']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Class Enroll Not Available']);
            }
        }
    }

    public function edit(Request $request)
    {

        if (isset($request->id)) {
            $data = DB::table('class_rooms')
                ->where('class_rooms.id', '=', $request->id)
                ->leftJoin('course_enroll_masters', 'course_enroll_masters.id', '=', 'class_rooms.name')
                ->leftJoin('teaching_staffs', 'class_rooms.class_incharge', '=', 'teaching_staffs.user_name_id')
                ->select('course_enroll_masters.enroll_master_number', 'teaching_staffs.name', 'teaching_staffs.user_name_id', 'class_rooms.id')
                ->whereNull('class_rooms.deleted_at')
                ->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }

        // abort_if(Gate::denies('class_room_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $course = ToolsCourse::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $ToolsDepartment = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $Batch = Batch::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $AcademicYear = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $Semester = Semester::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $Section = Section::pluck('section', 'id')->prepend(trans('global.pleaseSelect'), '')->unique();

        // $Department = ToolsDepartment::find($classRoom->department_id);
        // $teachingStaff = TeachingStaff::with('personal_details:user_name_id,employment_status')->select('user_name_id', 'name', 'StaffCode')->get();
        // $staffs = [];
        // if (count($teachingStaff) > 0) {
        //     foreach ($teachingStaff as $staff) {
        //         if ($staff->personal_details->employment_status == 'Active' || $staff->personal_details->employment_status == '') {
        //             array_push($staffs, $staff);
        //         }
        //     }
        // }
        // if ($teachingStaff) {
        //     $enroll_master_numbers = CourseEnrollMaster::find($classRoom->name);
        //     $array = explode('/', $enroll_master_numbers->enroll_master_number);
        // } else {
        //     $enroll_master_numbers = '';
        // }

        // if ($enroll_master_numbers) {
        //     $teachingStaff->Dept = $classRoom->department_id;
        //     $teachingStaff->course_1 = $array[1];
        //     $teachingStaff->batch_1 = $array[0];
        //     $teachingStaff->accademicYear_1 = $array[2];
        //     $teachingStaff->sem_1 = $array[3];
        //     $teachingStaff->section_1 = $array[4];
        // } else {
        //     $teachingStaff->Dept = '';
        //     $teachingStaff->course_1 = '';
        //     $teachingStaff->batch_1 = '';
        //     $teachingStaff->accademicYear_1 = '';
        //     $teachingStaff->sem_1 = '';
        //     $teachingStaff->section_1 = '';
        // }

        // return view('admin.classRooms.edit', compact('Section', 'Semester', 'staffs', 'AcademicYear', 'Batch', 'ToolsDepartment', 'course', 'teachingStaff', 'classRoom'));
    }

    public function update(Request $request)
    {
        $get_enroll = CourseEnrollMaster::where(['id' => $request->name])->first();
        $get_course = explode('/', $get_enroll->enroll_master_number);

        $get_short_form = ToolsCourse::where(['name' => $get_course[1]])->select('short_form')->first();
        $short_form = $get_short_form->short_form . ' / ' . $get_course[3] . ' / ' . $get_course[4];

        $className = ClassRoom::where('class_incharge', $request->class_incharge)->exists();
        if ($className != '') {
            return back()->with('error', 'This Teacher Is Alredy Assigned to Another Class');
        }
        $update = ClassRoom::where(['name' => $request->name])->update([
            'class_incharge' => $request->class_incharge,
            'short_form' => $short_form,
        ]);

        return redirect()->route('admin.class-rooms.index');
    }

    public function show(ClassRoom $classRoom)
    {
        abort_if(Gate::denies('class_room_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $classRoom->load('enroll_master', 'teaching_staff');

        return view('admin.classRooms.show', compact('classRoom'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = ClassRoom::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Class Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $classes = ClassRoom::find(request('ids'));

        foreach ($classes as $c) {
            $c->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Classes Deleted Successfully']);
    }

    public function newer()
    {
        $data = [
            'message' => 'Hello, this is a sample API response.',
        ];

        return response()->json($data);
    }

    public function get_block(Request $request)
    {

        if (isset($request->data)) {
            $rooms = [];
            $get = RoomCreation::where(['block_id' => $request->data])->select('room_no')->get();
            if (count($get) > 0) {

                foreach ($get as $block) {
                    $find = ClassRoom::where(['block_id' => $request->data, 'room_no' => $block->room_no])->select('room_no')->get();
                    if (count($find) <= 0) {

                        array_push($rooms, $block->room_no);
                    }
                }
            }

            return response()->json(['rooms' => $rooms]);
        } else {
            return response()->json(['rooms' => false]);
        }
    }

    public function class_dept(Request $request)
    {

        if ($request->dept_id == '5') {
            $courses = ToolsCourse::get();
        } else {
            $courses = ToolsCourse::where(['department_id' => $request->dept_id])->get();
        }

        if ($courses) {
            $course = $courses;
        } else {
            $course = [];
        }

        return response()->json(['course' => $course]);
    }

    public function checkStaff(Request $request)
    {
        if (isset($request->user_name_id) && $request->user_name_id != '') {
            $classes = Session::get('currentClasses');
            if (count($classes) > 0) {
                $className = ClassRoom::whereIn('name', $classes)->where('class_incharge', $request->user_name_id)->exists();
                if ($className != '') {
                    return response()->json(['status' => false, 'data' => 'Class Incharge Already Assigned']);
                } else {
                    return response()->json(['status' => true, 'data' => '']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Current Classes Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function getBatch(Request $request)
    {
        if ($request->course != '') {
            $course = ToolsCourse::find($request->course)->degree_type_id;
            if ($course != '') {
                $degree = $course == '1' ? 'UG' : ($course == '2' ? 'PG' : '');
                $getBatch = Batch::where('degree_type', $degree)->pluck('name', 'id');
                return response()->json(['status' => true, 'data' => $getBatch]);
            } else {
                return response()->json(['status' => false, 'data' => "Course is Not Available"]);
            }
        }
    }
}
