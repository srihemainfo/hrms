<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTeachingStaffRequest;
use App\Http\Requests\StoreTeachingStaffRequest;
use App\Http\Requests\UpdateTeachingStaffRequest;
use App\Models\Address;
use App\Models\Award;
use App\Models\BankAccountDetail;
use App\Models\BloodGroup;
use App\Models\Community;
use App\Models\Document;
use App\Models\EducationalDetail;
use App\Models\EducationType;
use App\Models\EntranceExam;
use App\Models\EventOrganized;
use App\Models\EventParticipation;
use App\Models\Events;
use App\Models\Examstaff;
use App\Models\ExperienceDetail;
use App\Models\HrmRequestLeaf;
use App\Models\IndustrialExperience;
use App\Models\IndustrialTraining;
use App\Models\Intern;
use App\Models\Iv;
use App\Models\LeaveType;
use App\Models\MediumofStudied;
use App\Models\MotherTongue;
use App\Models\NonTeachingStaff;
use App\Models\OnlineCourse;
use App\Models\Patent;
use App\Models\PermissionRequest;
use App\Models\PersonalDetail;
use App\Models\PhdDetail;
use App\Models\PromotionDetails;
use App\Models\PublicationDetail;
use App\Models\Religion;
use App\Models\Role;
use App\Models\Sabotical;
use App\Models\Sponser;
use App\Models\StaffSalary;
use App\Models\Sttp;
use App\Models\TeachingStaff;
use App\Models\ToolsDepartment;
use App\Models\User;
use App\Models\StaffOldCurrentStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Illuminate\Support\Facades\DB;

class StaffStatusController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('teaching_staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->staff_status)) {
            $staff_status = $request->staff_status;
            session(['staff_status' => $staff_status]); 
        } else {
            $staff_status = session('staff_status', 'teaching_staff'); 
        }

        if ($request->ajax()) {
            if ($staff_status === 'teaching_staff') {

                $staff_details = StaffOldCurrentStatus::from('staff_old_current_statuses AS s')
                ->withTrashed()
                ->select(
                    's.user_name_id',
                    's.staff_name',
                    's.status',
                    's.current_status',
                    's.Dept',
                    's.Designation',
                    's.total_days',
                    's.start_time',
                    's.end_time',
                    'pd.StaffCode'
                )
                ->leftJoin('personal_details AS pd', 's.user_name_id', '=', 'pd.user_name_id')
                ->where('s.teach_or_nonteach', 'Teaching')->whereNotNull('end_time')
                ->get();

            } else {

                $staff_details = StaffOldCurrentStatus::from('staff_old_current_statuses AS s')
                ->withTrashed()
                ->select(
                    's.user_name_id',
                    's.staff_name',
                    's.status',
                    's.current_status',
                    's.Dept',
                    's.Designation',
                    's.total_days',
                    's.start_time',
                    's.end_time',
                    'pd.StaffCode'
                )
                ->leftJoin('personal_details AS pd', 's.user_name_id', '=', 'pd.user_name_id')
                ->where('s.teach_or_nonteach', 'NonTeaching')->whereNotNull('end_time')
                ->get();
            }
           

            $table = Datatables::of($staff_details);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('id', function ($row) {
                return $row->user_name_id ? $row->user_name_id : '';
            });

            $table->editColumn('actions', function ($row) {
                $row->id = $row->user_name_id;

                $viewGate = 'teaching_staff_show';
                $crudRoutePart = 'Staff_status';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });

            $table->editColumn('StaffCode', function ($row) {
                return $row->StaffCode ? $row->StaffCode : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->staff_name ? $row->staff_name : '';
            });


            $table->editColumn('Designation', function ($row) {
                return $row->Designation ? $row->Designation : '';
            });

            $table->editColumn('Dept', function ($row) {
                return $row->Dept ? $row->Dept : '';
            });
            $table->editColumn('previous_status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('todate', function ($row) {
                return $row->start_time ? date('d-m-Y', strtotime($row->start_time))   : '';
            });
            $table->editColumn('enddate', function ($row) {

                if($row->start_time != $row->end_time){
                    $endTime = $row->end_time ? date('d-m-Y', strtotime($row->end_time))  : '';

                }else{
                    $endTime = '';
                }

                return $row->end_time ? $endTime : '';
            });
            $table->editColumn('leavedays', function ($row) {

                return $row->total_days ? $row->total_days   : '';
            });

            $table->editColumn('current_status', function ($row) {
                return  $employmentStatus = $row->current_status ? $row->current_status : '';
            });
            $table->rawColumns(['actions', 'placeholder']);
            $request->session()->forget('staff_status');
            return $table->make(true);
        }

        return view('admin.staffOldCurrentStatus.index', compact('staff_status'));
    }

    public function show(Request $request, $id)
    {

        abort_if(Gate::denies('teaching_staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff_status = $id;

        if ($request->ajax()) {
            $staffname = $request->Staff_status;



            $staff_details = StaffOldCurrentStatus::from('staff_old_current_statuses AS s')
                ->withTrashed()
                ->select(
                    's.user_name_id',
                    's.staff_name',
                    's.status',
                    's.current_status',
                    's.Dept',
                    's.Designation',
                    's.total_days',
                    's.start_time',
                    's.end_time',
                    'pd.StaffCode'
                )
                ->leftJoin('personal_details AS pd', 's.user_name_id', '=', 'pd.user_name_id')
                ->where('s.user_name_id', $staffname)->whereNotNull('end_time')
                ->get();
            $table = Datatables::of($staff_details);
            $table->editColumn('id', function ($row) {
                return $row->user_name_id ? $row->user_name_id : '';
            });

            $table->editColumn('StaffCode', function ($row) {
                return $row->StaffCode ? $row->StaffCode : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->staff_name ? $row->staff_name : '';
            });


            $table->editColumn('Designation', function ($row) {
                return $row->Designation ? $row->Designation : '';
            });

            $table->editColumn('Dept', function ($row) {
                return $row->Dept ? $row->Dept : '';
            });
            $table->editColumn('previous_status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('todate', function ($row) {
                return $row->start_time ? date('d-m-Y', strtotime($row->start_time)) : '';
            });
            $table->editColumn('enddate', function ($row) {

                return $row->end_time ? date('d-m-Y', strtotime($row->end_time)) : '';
            });
            $table->editColumn('leavedays', function ($row) {

                return $row->total_days ? $row->total_days   : '';
            });

            $table->editColumn('current_status', function ($row) {
                return  $employmentStatus = $row->current_status ? $row->current_status : '';
            });
            // $table->rawColumns(['actions', 'placeholder']);
            $request->session()->forget('staff_status');
            return $table->make(true);
        }
        return view('admin.staffOldCurrentStatus.view', compact('staff_status'));
    }
}
