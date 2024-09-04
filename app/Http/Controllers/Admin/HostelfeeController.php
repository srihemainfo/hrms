<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBatchRequest;
use App\Models\AcademicDetail;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\HostelBlock;
use App\Models\HostelFee;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HostelfeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = HostelFee::query()->select(sprintf('%s.*', (new HostelFee)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'hostel_fee_show';
                $editGate = 'hostel_fee_edit';
                $deleteGate = 'hostel_fee_delete';
                $crudRoutePart = 'hostel_fee';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->editColumn('actions', function ($row) {
                $viewGate = 'batch_show';
                $editGate = 'batch_edit';
                $deleteGate = 'batch_delete';
                $crudRoutePart = 'batches';
                $viewFunct = 'hostel_fee_view';
                $editFunct = 'hostel_fee_edit';
                $deleteFunct = 'hostel_fee_delete';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('register_number', function ($row) {
                return $row->register_number ? $row->register_number : '';
            });

            $table->editColumn('batch', function ($row) {
                return $row->batch ? $row->batch->name : '';
            });

            $table->editColumn('ay', function ($row) {
                return $row->ay ? $row->ay->name : '';
            });

            $table->editColumn('hostel_block_id', function ($row) {
                return $row->hostel_block ? $row->hostel_block->name : '';
            });

            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $batch = Batch::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $hostel_block = HostelBlock::pluck('name', 'id');
        return view('admin.hostel_fee.index', compact('batch', 'ay', 'hostel_block'));
    }

    public function filter_student(Request $request)
    {

        $batch = $request->batch;
        $applied_ay = $request->applied_ay;

        $applied_ay_ids = CourseEnrollMaster::where('academic_id', $applied_ay)->pluck('id')->toArray();

        $batch_name = Batch::where('id', $batch)->pluck('name')->first();

        $studentIds = Student::where('student_batch', $batch_name)
            ->whereIn('enroll_master_id', $applied_ay_ids)
            ->pluck('user_name_id')
            ->toArray();

        $academic_Details = AcademicDetail::whereIn('user_name_id', $studentIds)
            ->where('hosteler', '1')
            ->pluck('user_name_id');

        // dd($academic_Details);

        $hostel_Students = Student::whereIn('user_name_id', $academic_Details)
            ->pluck('name', 'register_no');

        // dd($hostel_Students);

        if ($hostel_Students->isEmpty()) {
            return response()->json(['status' => false, 'data' => 'Students Not Found']);
        } else {
            return response()->json(['status' => true, 'data' => $hostel_Students]);
        }
    }

    public function store(Request $request)
    {

        if (isset($request->hostel_fee_amount)) {
            if ($request->hostel_fee_id == '') {

                $applied_batch = $request->applied_batch;
                $hostel_block = $request->hostel_block;
                $applied_ay = $request->applied_ay;
                $hostel_fee_amount = $request->hostel_fee_amount;

                // dd($hostel_block);

                $batch_filter_std = $request->batch_filter_std;

                $students = Student::whereIn('register_no', $batch_filter_std)
                    ->get(['user_name_id', 'name', 'register_no']);

                foreach ($students as $student) {

                    $user_name_id = $student->user_name_id;
                    $name = $student->name;
                    $reg = $student->register_no;

                    HostelFee::create([
                        'name' => $name,
                        'std_user_name_id' => $user_name_id,
                        'batch_id' => $applied_batch,
                        'hostel_block_id' => $hostel_block,
                        'amount' => $hostel_fee_amount,
                        'academic_year_id' => $applied_ay,
                        'register_number' => $reg,
                    ]);

                }

                return response()->json(['status' => true, 'data' => 'HostelFee Created Successfully']);

            } else {

                $hostel_fee_amount = $request->hostel_fee_amount;
                $update = HostelFee::where(['id' => $request->hostel_fee_id])->update([
                    'amount' => $hostel_fee_amount,
                ]);

                return response()->json(['status' => true, 'data' => 'HostelFee Updated']);

            }

        } else {
            return response()->json(['status' => false, 'data' => 'HostelFee Not Created']);
        }

    }

    public function massDestroy(MassDestroyBatchRequest $request)
    {
        $batches = HostelFee::find(request('ids'));
        foreach ($batches as $batch) {
            $batch->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'HostelFee Deleted Successfully']);
    }

    public function delete(Request $request)
    {
        if (isset($request->id)) {
            $delete = HostelFee::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'HostelFee Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = HostelFee::where('id', $request->id)->first();

            return response()->json(['status' => true, 'data' => $data]);

        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        // dd($request);

        if (isset($request->id)) {
            $data = HostelFee::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

}
