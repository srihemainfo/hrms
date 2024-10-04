<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySeminarRequest;
use App\Http\Requests\StoreSeminarRequest;
use App\Http\Requests\UpdateSeminarRequest;
use App\Models\Seminar;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SeminarController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Seminar::with(['user_name'])->select(sprintf('%s.*', (new Seminar)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'seminar_show';
                $editGate = 'seminar_edit';
                $deleteGate = 'seminar_delete';
                $crudRoutePart = 'seminars';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('user_name_name', function ($row) {
                return $row->user_name ? $row->user_name->name : '';
            });

            $table->editColumn('topic', function ($row) {
                return $row->topic ? $row->topic : '';
            });
            $table->editColumn('remark', function ($row) {
                return $row->remark ? $row->remark : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user_name']);

            return $table->make(true);
        }

        return view('admin.seminars.index');
    }

    public function stu_index(Request $request)
    {

        abort_if(Gate::denies('seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (isset($request->accept)) {
            // dd($request);
            Seminar::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Seminar::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->remark = '';
                $query->seminar_date = '';
                $query->add = 'Add';

                $student = $query;
                $stu_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $student = $query[0];

                $list = $query;

                $stu_edit = new Seminar;
                $stu_edit->add = 'Add';
                $stu_edit->id = '';
                $stu_edit->topic = '';
                $stu_edit->remark = '';
                $stu_edit->seminar_date = '';

            }

        } else {

            // dd($request);

            $query_one = Seminar::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = Seminar::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $student = $query_one[0];

                $list = $query_one;
                // dd($staff);
                $stu_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $check = 'seminar_details';

        return view('admin.StudentProfile.student', compact('student', 'check', 'list', 'stu_edit'));
    }

    public function stu_update(UpdateSeminarRequest $request, Seminar $seminar)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $seminars = $seminar->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $seminars = false;
        }

        if ($seminars) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_semi = new Seminar;

            $stu_semi->topic = $request->topic;
            $stu_semi->remark = $request->remark;
            $stu_semi->seminar_date = $request->seminar_date;
            $stu_semi->user_name_id = $request->user_name_id;
            $stu_semi->status='0';
            $stu_semi->save();

            if ($stu_semi) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.seminars.stu_index', $student);
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (!$request->updater) {
            $query = Seminar::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->remark = '';
                $query->seminar_date = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new Seminar;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->topic = '';
                $staff_edit->remark = '';
                $staff_edit->seminar_date = '';

            }

        } else {

            // dd($request);

            $query_one = Seminar::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = Seminar::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                $list = $query_one;
                // dd($staff);
                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $check = 'seminar_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateSeminarRequest $request, Seminar $seminar)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $seminars = $seminar->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $seminars = false;
        }

        if ($seminars) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_semi = new Seminar;

            $staff_semi->topic = $request->topic;
            $staff_semi->remark = $request->remark;
            $staff_semi->seminar_date = $request->seminar_date;
            $staff_semi->user_name_id = $request->user_name_id;
            $staff_semi->save();

            if ($staff_semi) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.seminars.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.seminars.create', compact('user_names'));
    }

    public function store(StoreSeminarRequest $request)
    {
        $seminar = Seminar::create($request->all());

        return redirect()->route('admin.seminars.index');
    }

    public function edit(Seminar $seminar)
    {
        abort_if(Gate::denies('seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $seminar->load('user_name');

        return view('admin.seminars.edit', compact('seminar', 'user_names'));
    }

    public function update(UpdateSeminarRequest $request, Seminar $seminar)
    {
        $seminar->update($request->all());

        return redirect()->route('admin.seminars.index');
    }

    public function show(Seminar $seminar)
    {
        abort_if(Gate::denies('seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seminar->load('user_name');

        return view('admin.seminars.show', compact('seminar'));
    }

    public function destroy(Seminar $seminar)
    {
        abort_if(Gate::denies('seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seminar->delete();

        return back();
    }

    public function massDestroy(MassDestroySeminarRequest $request)
    {
        $seminars = Seminar::find(request('ids'));

        foreach ($seminars as $seminar) {
            $seminar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
