<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Scholarship;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Scholarship::with(['theAys', 'theBatches'])->select(sprintf('%s.*', (new Scholarship)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $editFunct = 'editScholarship';
                $viewFunct = 'viewScholarship';
                $deleteFunct = 'deleteScholarship';
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
            $table->editColumn('foundation_name', function ($row) {
                return $row->foundation_name ? $row->foundation_name : '';
            });

            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });

            $table->editColumn('percentage', function ($row) {
                return $row->percentage ? $row->percentage : '';
            });


            $table->editColumn('started_ay', function ($row) {
                return $row->theAys ? $row->theAys->name : '';
            });
            $table->editColumn('started_batch', function ($row) {
                return $row->theBatches ? $row->theBatches->name : '';
            });
            $table->editColumn('status', function ($row) {
                if ($row->status == 0) {
                    $status = 'Inactive';
                } else {
                    $status = 'Active';
                }
                return $status;
            });
            // $table->editColumn('inactive_reason', function ($row) {
            //     return $row->inactive_reason ? $row->inactive_reason : '';
            // });
            // $table->editColumn('inactive_date', function ($row) {
            //     return $row->inactive_date ? $row->inactive_date : '';
            // });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $getAys = AcademicYear::pluck('name', 'id');
        $getBatches = Batch::pluck('name', 'id');

        return view('admin.scholarships.index', compact('getAys', 'getBatches'));
    }
    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->name) && isset($request->foundation_name) && isset($request->started_ay) && isset($request->started_batch) && isset($request->status)) {
            if ($request->id == '') {
                $count = Scholarship::where(['name' => $request->name, 'foundation_name' => $request->foundation_name])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Scholarship Already Exist.']);
                } else {
                    $store = Scholarship::create([
                        'name' => strtoupper($request->name),
                        'foundation_name' => strtoupper($request->foundation_name),
                        'started_ay' => $request->started_ay,
                        'started_batch' => $request->started_batch,
                        'remarks' => isset($request->remarks) ? $request->remarks : null,
                        'status' => $request->status == 'Inactive' ? 0 : 1,
                        'amount' => isset($request->amount_input_box) ? $request->amount_input_box : null,
                        'percentage' => isset($request->percentage_input_box) ? $request->percentage_input_box: null,
                        'inactive_reason' => isset($request->inactive_reason) ? $request->inactive_reason : null,
                        'inactive_date' => isset($request->inactive_date) ? $request->inactive_date : null,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Scholarship Created']);
            } else {

                dd($request);
                $count = Scholarship::whereNotIn('id', [$request->id])->where(['name' => $request->name, 'foundation_name' => $request->foundation_name])->count();
                // dd($count);
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Scholarship Already Exist.']);
                } else {
                    $update = Scholarship::where(['id' => $request->id])->update([
                        'name' => strtoupper($request->name),
                        'foundation_name' => strtoupper($request->foundation_name),
                        'amount' => $request->amount_input_box,
                        'started_ay' => $request->started_ay,
                        'started_batch' => $request->started_batch,
                        'remarks' => isset($request->remarks) ? $request->remarks : null,
                        'status' => $request->status == 'Inactive' ? 0 : 1,
                        'inactive_reason' => isset($request->inactive_reason) ? $request->inactive_reason : null,
                        'inactive_date' => isset($request->inactive_date) ? $request->inactive_date : null,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Scholarship Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Scholarship Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Scholarship::where(['id' => $request->id])->select('id', 'name', 'foundation_name', 'started_ay',
            'started_batch',
            'remarks',
            'amount',
            'percentage',
            'status',
            'inactive_reason',
            'inactive_date')->first();
            // dd($data);
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Scholarship::where(['id' => $request->id])->select('id', 'name', 'foundation_name', 'started_ay',
            'started_batch',
            'remarks',
            'amount',
            'percentage',
            'status',
            'inactive_reason',
            'inactive_date')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {

        if (isset($request->id)) {
            $delete = Scholarship::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Scholarships Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $Scholarships = Scholarship::find(request('ids'));

        foreach ($Scholarships as $Scholarship) {
            $Scholarship->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Scholarships Deleted Successfully']);
    }

}
