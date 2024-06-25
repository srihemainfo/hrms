<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEntranceExamRequest;
use App\Http\Requests\StoreEntranceExamRequest;
use App\Http\Requests\UpdateEntranceExamRequest;
use App\Models\EntranceExam;
use App\Models\Examstaff;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EntranceExamsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('entrance_exam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EntranceExam::with(['name', 'exam_type'])->select(sprintf('%s.*', (new EntranceExam)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'entrance_exam_show';
                $editGate = 'entrance_exam_edit';
                $deleteGate = 'entrance_exam_delete';
                $crudRoutePart = 'entrance-exams';

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
            $table->addColumn('name_name', function ($row) {
                return $row->name ? $row->name->name : '';
            });

            $table->addColumn('exam_type_name', function ($row) {
                return $row->exam_type ? $row->exam_type->name : '';
            });

            $table->editColumn('scored_mark', function ($row) {
                return $row->scored_mark ? $row->scored_mark : '';
            });
            $table->editColumn('total_mark', function ($row) {
                return $row->total_mark ? $row->total_mark : '';
            });
            $table->editColumn('rank', function ($row) {
                return $row->rank ? $row->rank : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name', 'exam_type']);

            return $table->make(true);
        }

        return view('admin.entranceExams.index');
    }

    public function stu_index(Request $request)
    {

        abort_if(Gate::denies('entrance_exam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {
            $query = EntranceExam::where(['name_id' => $request->user_name_id])->get();
        }

        $exam_types = Examstaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if ($query->count() <= 0) {

            $query->user_name_id = $request->user_name_id;
            $query->exam_type_id = $exam_types;
            $query->exam_types = $exam_types;
            $query->name = $request->name;
            $query->add = 'Add';

            $student = $query;

        } else {

            for ($i = 0; $i < count($query); $i++) {
                $query[$i]->name = $request->name;
                $query[$i]->exam_types = $exam_types;
                $query[$i]->user_name_id = $request->user_name_id;
                $query[$i]->add = 'Update';
            }

            $student = $query[0];
            $list = $query;

        }
        // dd($staff);
        $check = 'entrance_exam_details';

        return view('admin.StudentProfile.student', compact('student', 'check', 'list'));
    }

    public function stu_update(UpdateEntranceExamRequest $request, EntranceExam $entranceExam)
    {
        // dd($request);
        // dd($request->education_type_id);
        $entrance = $entranceExam->where(['name_id' => $request->user_name_id, 'exam_type_id' => $request->exam_type_id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        if ($entrance) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_entrance = new EntranceExam;

            $stu_entrance->passing_year = $request->passing_year;
            $stu_entrance->scored_mark = $request->scored_mark;
            $stu_entrance->total_mark = $request->total_mark;
            $stu_entrance->rank = $request->rank;
            $stu_entrance->exam_type_id = $request->exam_type_id;
            $stu_entrance->name_id = $request->user_name_id;
            $stu_entrance->save();

            if ($stu_entrance) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($education_s);
            } else {
                dd('Error');
            }

        }
// dd($student);
        return redirect()->route('admin.entrance-exams.stu_index', $student);
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('entrance_exam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (isset($request->accept)) {

            EntranceExam::where('id', $request->id)->update(['status' => 1]);
        }
        $exam_types = Examstaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if (!$request->updater) {

            $query = EntranceExam::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->exam_type_id = '';
                $query->exam_types = $exam_types;
                $query->name = $request->name;
                $query->passing_year = $request->passing_year;
                $query->scored_mark = $request->scored_mark;
                $query->total_mark = $request->total_mark;
                $query->rank = $request->rank;
                $query->add = 'Add';

                $staff = $query;
                $list = [];
                $staff_edit = $query;

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $query[0]['exam_types'] = $exam_types;

                $staff = $query[0];

                $staff_edit = new EntranceExam;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->passing_year = '';
                $staff_edit->scored_mark = '';
                $staff_edit->total_mark = '';
                $staff_edit->rank = '';
                $staff_edit->exam_type_id = '';
                $staff_edit->exam_types = $exam_types;
                // $staff_edit->name_id = $request->user_name_id;

                for ($i = 0; $i < count($query); $i++) {
                    $query[$i]->exam_types = $exam_types;

                }
                $list = $query;

            }

        } else {
            $query_one = EntranceExam::with(['exam_type'])->where(['name_id' => $request->user_name_id])->get();

            $query_two = EntranceExam::with(['exam_type'])->where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';
                $query_one[0]['exam_types'] = $exam_types;
                $query_two[0]['exam_types'] = $exam_types;

                $staff = $query_one[0];

                $list = $query_one;

                for ($i = 0; $i < count($query_one); $i++) {
                    $query_one[$i]->exam_types = $exam_types;
                }
                // dd($staff);
                $staff_edit = $query_two[0];
                //  dd($staff_edit);
            } else {
                dd('Error');
            }
        }
        // dd($staff);
        $check = 'entrance_exam_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'staff_edit', 'list'));
    }

    public function staff_update(UpdateEntranceExamRequest $request, EntranceExam $entranceExam)
    {
        // dd($request);
        // dd($request->education_type_id);
        if (!$request->id == 0 || $request->id != '') {
            $entrance = $entranceExam->where(['name_id' => $request->user_name_id, 'exam_type_id' => $request->exam_type_id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));
        } else {
            $entrance = false;
        }

        if ($entrance) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            if ($request->exam_type_id == '') {

                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

            } else {
                $staff_entrance = new EntranceExam;

                $staff_entrance->passing_year = $request->passing_year;
                $staff_entrance->scored_mark = $request->scored_mark;
                $staff_entrance->total_mark = $request->total_mark;
                $staff_entrance->rank = $request->rank;
                $staff_entrance->exam_type_id = $request->exam_type_id;
                $staff_entrance->name_id = $request->user_name_id;
                $staff_entrance->status='0';
                $staff_entrance->save();

                if ($staff_entrance) {
                    $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                    // dd($education_s);
                } else {
                    dd('Error');
                }
            }
        }

// dd($student);
        return redirect()->route('admin.entrance-exams.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('entrance_exam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $exam_types = Examstaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.entranceExams.create', compact('exam_types', 'names'));
    }

    public function store(StoreEntranceExamRequest $request)
    {
        $entranceExam = EntranceExam::create($request->all());

        return redirect()->route('admin.entrance-exams.index');
    }

    public function edit(EntranceExam $entranceExam)
    {
        abort_if(Gate::denies('entrance_exam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $exam_types = Examstaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entranceExam->load('name', 'exam_type');

        return view('admin.entranceExams.edit', compact('entranceExam', 'exam_types', 'names'));
    }

    public function update(UpdateEntranceExamRequest $request, EntranceExam $entranceExam)
    {
        $entranceExam->update($request->all());

        return redirect()->route('admin.entrance-exams.index');
    }

    public function show(EntranceExam $entranceExam)
    {
        abort_if(Gate::denies('entrance_exam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entranceExam->load('name', 'exam_type');

        return view('admin.entranceExams.show', compact('entranceExam'));
    }

    public function destroy(EntranceExam $entranceExam)
    {
        abort_if(Gate::denies('entrance_exam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entranceExam->delete();

        return back();
    }

    public function massDestroy(MassDestroyEntranceExamRequest $request)
    {
        $entranceExams = EntranceExam::find(request('ids'));

        foreach ($entranceExams as $entranceExam) {
            $entranceExam->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
