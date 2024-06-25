<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCollegeCalenderRequest;
use App\Http\Requests\StoreCollegeCalenderRequest;
use App\Http\Requests\UpdateCollegeCalenderRequest;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CollegeCalender;
use App\Models\Semester;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Facades\DataTables;

class CollegeCalenderController extends Controller
{
    use CsvImportTrait;
    public function index(Request $request)
    {
        abort_if(Gate::denies('college_calender_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $getAys = AcademicYear::where(['status' => 1])->select('name')->get();
            $Ays = [];
            if (count($getAys) > 0) {
                foreach ($getAys as $ay) {
                    array_push($Ays, $ay->name);
                }
            }
            $getSem = Semester::where(['status' => 1])->select('semester')->get();
            $Sems = [];
            if (count($getSem) > 0) {
                foreach ($getSem as $sem) {
                    array_push($Sems, $sem->semester);
                }
            }
            $semTypes = [];
            foreach ($Sems as $sem) {
                if ($sem == 1 || $sem == 3 || $sem == 5 || $sem == 7) {
                    if (!in_array('ODD', $semTypes)) {
                        array_push($semTypes, 'ODD');
                    }
                }
                if ($sem == 2 || $sem == 4 || $sem == 6 || $sem == 8) {
                    if (!in_array('EVEN', $semTypes)) {
                        array_push($semTypes, 'EVEN');
                    }
                }
            }

            $query = CollegeCalender::query()->whereIn('academic_year', $Ays)->whereIn('semester_type', $semTypes)->select(sprintf('%s.*', (new CollegeCalender)->table));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate = 'college_calender_show';
                $editGate = 'college_calende_edit';
                $deleteGate = 'college_calender_delete';
                $crudRoutePart = 'college-calenders';
                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row',

                    )
                );
            });
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('academic_year', function ($row) {
                return $row->academic_year ? $row->academic_year : '';
            });
            $table->editColumn('shift', function ($row) {
                return $row->shift ? $row->shift : '';
            });
            $table->editColumn('semester_type', function ($row) {
                return $row->semester_type ? $row->semester_type : '';
            });
            $table->editColumn('batch', function ($row) {
                switch ($row->batch) {
                    case '01':
                        return 'First year';
                    case '02':
                        return 'Second year';
                    case '03':
                        return 'Third year';
                    case '04':
                        return 'Fourth year';
                    default:
                        return '';
                }
            });

            $table->editColumn('from_date', function ($row) {
                return $row->from_date ? $row->from_date : '';
            });
            $table->editColumn('to_date', function ($row) {
                return $row->to_date ? $row->to_date : '';
            });
            $table->editColumn('past_attendance_control', function ($row) {
                $accessGate = 'attendance_access_control';
                return view('partials.controlBtn', compact('accessGate', 'row'));
            });
            $table->rawColumns(['actions', 'placeholder', 'past_attendance_control']);
            return $table->make(true);
        }
        return view('admin.collegeCalenders.index');
    }
    public function create()
    {
        abort_if(Gate::denies('college_calender_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $academics = AcademicYear::pluck('name', 'id');
        $semesters = Semester::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');
        $Batch = Batch::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $currentYear = date('Y');
        $currentMonth = date('n');

        return view('admin.collegeCalenders.create', compact('semesters', 'Batch', 'academics'));
    }
    public function store(StoreCollegeCalenderRequest $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $semester_type = $request->input('semester_type');
        $academic_year = $request->input('academic_year');
        $batch = $request->input('batch');
        $dateFrom = Carbon::createFromFormat('Y-m-d', $from_date);
        $dateTo = Carbon::createFromFormat('Y-m-d', $to_date);
        $startDate = $dateFrom->toDateString();
        $endDate = $dateTo->toDateString();
        $dateRange = CarbonPeriod::create($startDate, $endDate);
        $rules = [
            'batch' => 'required',
            'from_date' => 'required|date|date_format:Y-m-d',
            'to_date' => 'required|date|date_format:Y-m-d|after_or_equal:from_date',
        ];

        $messages = [
            'batch.required' => 'Batch is required.',
            'from_date.required' => 'Start date is required.',
            'from_date.date' => 'Start date must be a valid date.',
            'from_date.date_format' => 'Start date must be in the format "YYYY-MM-DD".',
            'to_date.required' => 'End date is required.',
            'to_date.date' => 'End date must be a valid date.',
            'to_date.date_format' => 'End date must be in the format "YYYY-MM-DD".',
            'to_date.after_or_equal' => 'End date must be greater than or equal to start date.',
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        $validator->after(function ($validator) use ($academic_year, $batch) {
            $existingBatches = CollegeCalender::where('academic_year', $academic_year)
                ->count();

            if ($existingBatches >= 8) {
                $validator->errors()->add('batch', 'Only four batches are allowed for the selected academic year.');
            }

            $existingSemesters = CollegeCalender::where('academic_year', $academic_year)
                ->where('batch', $batch)
                ->whereIn('semester_type', ['ODD', 'EVEN'])
                ->count();

            if ($existingSemesters >= 2) {
                $validator->errors()->add('semester_type', 'Only one ODD and one EVEN semester are allowed for each batch.');
            }
        });

        $validator->validate();
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $record = DB::table('college_calenders_preview')
                ->where('academic_year', $academic_year)
                ->where('semester_type', $semester_type)
                ->where('batch', $batch)
                ->first();
            if (!$record) {

                foreach ($dateRange as $date) {
                    $day = $date->format('l');
                    $isHoliday = 0;

                    if ($request->input('sunday') == 1 && $day == 'Sunday') {
                        $isHoliday = 1;
                    }

                    if ($request->input('saturday') == 1 && $day == 'Saturday') {
                        $isHoliday = 2;
                    }
                    if ($request->input('monday') == 1 && $day == 'Monday') {
                        $isHoliday = 3;
                    }

                    DB::table('college_calenders_preview')->insert([
                        'start_date' => $date->toDateString(),
                        'end_date' => $endDate,
                        'date' => $date,
                        'dayorder' => $isHoliday,
                        'academic_year' => $academic_year,
                        'semester_type' => $semester_type,
                        'batch' => $batch,
                        'created_at' => now(),
                    ]);
                }

                $collegeCalender = CollegeCalender::create($request->all());
                return redirect()->route('admin.college-calenders.index');
            } else {
                session()->flash('message', 'Record already exists.');

                return back()->with('message', 'Record already exists.');
            }
        }

    }
    public function edit(CollegeCalender $collegeCalender)
    {
        abort_if(Gate::denies('college_calender_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $from_date = $collegeCalender->from_date;
        $to_date = $collegeCalender->to_date;
        $batch = $collegeCalender->batch;
        $academic_year = $collegeCalender->academic_year;
        $semester_type = $collegeCalender->semester_type;
        $dateFrom = Carbon::createFromFormat('Y-m-d', $from_date);
        $dateTo = Carbon::createFromFormat('Y-m-d', $to_date);
        $startDate = $dateFrom->toDateString();
        $endDate = $dateTo->toDateString();
        $startDates = DB::table('college_calenders_preview')->whereBetween('start_date', [$from_date, $to_date])
            ->where('academic_year', $academic_year)
            ->where('semester_type', $semester_type)
            ->where('batch', $batch)
            ->get();
        return view('admin.collegeCalenders.edit', compact('collegeCalender', 'startDates'));
    }
    public function update(UpdateCollegeCalenderRequest $request, CollegeCalender $collegeCalender)
    {
        $collegeCalender->update($request->all());

        return redirect()->route('admin.college-calenders.index');
    }
    public function show(CollegeCalender $collegeCalender)
    {
        $from_date = $collegeCalender->from_date;
        $to_date = $collegeCalender->to_date;
        $academic_year = $collegeCalender->academic_year;
        $batch = $collegeCalender->batch;
        $semester_type = $collegeCalender->semester_type;
        $dateFrom = Carbon::createFromFormat('Y-m-d', $from_date);
        $dateTo = Carbon::createFromFormat('Y-m-d', $to_date);
        $startDate = $dateFrom->toDateString();
        $endDate = $dateTo->toDateString();
        $startDates = DB::table('college_calenders_preview')->whereBetween('start_date', [$from_date, $to_date])
            ->where('academic_year', $academic_year)
            ->where('semester_type', $semester_type)
            ->where('batch', $batch)
            ->get();
        return view('admin.collegeCalenders.show', compact('collegeCalender', 'startDates'));
    }
    public function destroy(CollegeCalender $collegeCalender)
    {
        abort_if(Gate::denies('college_calender_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $from_date = $collegeCalender->from_date;
        $to_date = $collegeCalender->to_date;
        $batch = $collegeCalender->batch;
        $academic_year = $collegeCalender->academic_year;
        $semester_type = $collegeCalender->semester_type;
        $dateFrom = Carbon::createFromFormat('Y-m-d', $from_date);
        $dateTo = Carbon::createFromFormat('Y-m-d', $to_date);
        $startDate = $dateFrom->toDateString();
        $endDate = $dateTo->toDateString();
        $ToDelete =
        DB::table('college_calenders_preview')->whereBetween('start_date', [$from_date, $to_date])
            ->where('academic_year', $academic_year)
            ->where('semester_type', $semester_type)
            ->where('batch', $batch)
            ->delete();

        $collegeCalender->delete();

        return back();
    }
    public function updateDay(Request $request)
    {
        $month = $request->input('month');
        $accYear = $request->input('accYear');
        $semType = $request->input('semType');
        $hasError = false;
        if ($month) {

            foreach ($month as $date => $data) {
                $value = $data['value'];
                $batch = $data['batch'];

                // Set the day order based on the selected value
                if ($value == 'Holiday') {
                    $dayOrder = 4;
                } else if ($value == 'No_order_day') {
                    $dayOrder = 5;
                } else if ($value == 'Unit_Test') {
                    $dayOrder = 6;
                } else if ($value == 'Monday') {
                    $dayOrder = 20;  // Modified Code
                } else if ($value == 'Tuesday') {
                    $dayOrder = 7;
                } else if ($value == 'Wednesday') {
                    $dayOrder = 8;
                } else if ($value == 'Thursday') {
                    $dayOrder = 9;
                } else if ($value == 'Friday') {
                    $dayOrder = 10;
                } else if ($value == 'Saturday') {
                    $dayOrder = 11;
                } else if ($value == 'General_Councelling') {
                    $dayOrder = 12;
                } else if ($value == 'Project_review') {
                    $dayOrder = 13;
                } else if ($value == 'model_exam') {
                    $dayOrder = 14;
                } else if ($value == 'internal_assessment_test') {
                    $dayOrder = 15;
                } else if ($value == 'class_committee_meeting') {
                    $dayOrder = 16;
                } else if ($value == 'course_committee_meeting') {
                    $dayOrder = 17;
                } else if ($value == 'Feedback') {
                    $dayOrder = 18;
                } else if ($value == 'Colleage_Day') {
                    $dayOrder = 19;
                } else if ($value == 'Reset') {
                    $dayOrder = 0;
                } else {
                    $dayOrder = '';
                }

                $formattedDate = $date;
                try {
                    DB::table('college_calenders_preview')
                        ->where('date', 'like', '%' . $formattedDate . '%')
                        ->where('batch', $batch)
                        ->where('semester_type', $semType)
                        ->where('academic_year', $accYear)
                        ->update(['dayorder' => $dayOrder, 'batch' => $batch, 'updated_at' => now()]);
                } catch (Exception $e) {
                    $hasError = true;
                }
            }

            if ($hasError) {
                return response()->json(['message' => 'Error: Some updates failed'], 500);
            } else {
                return response()->json(['message' => 'success'], 200);
            }
        }

    }
    public function massDestroy(MassDestroyCollegeCalenderRequest $request)
    {
        $collegeCalenders = CollegeCalender::find(request('ids'));
        foreach ($collegeCalenders as $collegeCalender) {
            $collegeCalender->delete();
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function attAccess(Request $request)
    {
        if (isset($request->id) && $request->id != '' && isset($request->status) && $request->status != '') {
            $update_control = CollegeCalender::where(['id' => $request->id])->update([
                'past_attendance_control' => (int) $request->status,
            ]);

            if ($request->status == '1') {
                $status = 'Enable';
                $update_control = CollegeCalender::select('past_attendance_control')->first();
                $id = $update_control->past_attendance_control;
            } else {
                $status = 'Disable';
                $update_control = CollegeCalender::select('past_attendance_control')->first();
                $id = $update_control->past_attendance_control;
            }
            if ($update_control) {
                return response()->json(['status' => true, 'id' => $id, 'data' => $status . 'd']);
            } else {
                return response()->json(['status' => false, 'id' => $id, 'data' => $status . ' Process Failed']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

}
