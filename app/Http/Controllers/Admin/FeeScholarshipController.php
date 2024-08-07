<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\ScholarStudents;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FeeScholarshipController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = $query = ScholarStudents::query()->select(sprintf('%s.*', (new ScholarStudents)->table))
                ->leftJoin('scholarships', 'scholarship_students.scholar_id', '=', 'scholarships.id')
                ->addSelect('scholarships.foundation_name as name');
            $table = Datatables::of($query);

            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $editFunct = 'editfeeScholarship';
                $viewFunct = 'viewfeeScholarship';
                $deleteFunct = 'deletefeeScholarship';
                $viewGate = 'scholarship_show';
                $editGate = 'scholarship_edit';
                $deleteGate = 'scholarship_delete';
                $crudRoutePart = 'scholarships';

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

            $table->editColumn('stu_reg_no', function ($row) {
                return $row->stu_reg_no ? $row->stu_reg_no : '';
            });

            $table->editColumn('scholar_id', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('scholar_details', function ($row) {
                return $row->scholar_details ? $row->scholar_details : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $getStudents = Student::select('register_no', 'name')->get();
        $getScholarship = Scholarship::where('status', 1)->pluck('name', 'id');
        return view('admin.feeScholarship.index', compact('getStudents', 'getScholarship'));

    }

    public function getScholarship(Request $request)
    {

        $scholarship = $request->scholarship;
        $getscholarshipdata = Scholarship::where('id', $scholarship)->first();

        if ($getscholarshipdata) {

            if (is_null($getscholarshipdata->amount)) {
                $value = $getscholarshipdata->percentage;
            } else {

                $value = !empty($getscholarshipdata->percentage) ? $getscholarshipdata->percentage : $getscholarshipdata->amount;
            }

            return response()->json(['status' => true, 'value' => $value]);

        } else {
            return response()->json(['status' => false, 'data' => 'Unable to Fetch Data']);
        }

    }

    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->student)) {

            // dd($request->student);

            if ($request->id == '') {

                $check = ScholarStudents::where(['stu_reg_no' => $request->student])->count();
                if ($check > 0) {
                    return response()->json(['status' => false, 'data' => 'ScholarShip Already Created']);
                } else {
                    $scholarship = $request->scholarship;
                    $amt_percentage = $request->amt_percentage;
                    $students = $request->student;

                    foreach ($students as $student) {
                        ScholarStudents::create([
                            'scholar_id' => $scholarship,
                            'scholar_details' => $amt_percentage,
                            'stu_reg_no' => $student,

                        ]);
                    }
                    return response()->json(['status' => true, 'data' => 'Scholarship Created Successfully']);

                }

            } else {
                // dd($request);

                $amt_per_edit = $request->amt_per_edit;
                $update  = ScholarStudents::where(['id' => $request->id])->update([
                    'scholar_details' => $amt_per_edit,
                ]);
                return response()->json(['status' => true, 'data' => 'Scholarship Updated Successfully']);


            }

        } else {
            return response()->json(['status' => false, 'data' => 'Scholarship Not Created']);
        }

    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = ScholarStudents::where('id', $request->id)->first();
            if ($data) {
                $foundation_name = Scholarship::where('id', $data->scholar_id)->first();
                $foundation_name1 = $foundation_name ? $foundation_name->foundation_name : 'Scholarship Name Not Found';
            }

            return response()->json(['status' => true, 'data' => $data, 'foundation_name' => $foundation_name]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        // dd($request);
        if (isset($request->id)) {
            $data = ScholarStudents::where('id', $request->id)->first();
            if ($data) {
                $foundation_name = Scholarship::where('id', $data->scholar_id)->first();
                $foundation_name1 = $foundation_name ? $foundation_name->foundation_name : 'Scholarship Name Not Found';
            }

            return response()->json(['status' => true, 'data' => $data, 'foundation_name' => $foundation_name]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = ScholarStudents::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Scholarship Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $feeScholar = ScholarStudents::whereIn('id', request('ids'))->update([
            'deleted_at' => Carbon::now(),
        ]);
        return response()->json(['status' => 'success', 'data' => 'Scholarship Deleted Successfully']);
    }

}
