<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyIvRequest;
use App\Http\Requests\StoreIvRequest;
use App\Http\Requests\UpdateIvRequest;
use App\Models\Iv;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class IvController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('iv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Iv::with(['name'])->select(sprintf('%s.*', (new Iv)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'iv_show';
                $editGate = 'iv_edit';
                $deleteGate = 'iv_delete';
                $crudRoutePart = 'ivs';

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
            $table->addColumn('name_name', function ($row) {
                return $row->name ? $row->name->name : '';
            });

            $table->editColumn('topic', function ($row) {
                return $row->topic ? $row->topic : '';
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : '';
            });

            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name']);

            return $table->make(true);
        }

        return view('admin.ivs.index');
    }

    public function stu_index(Request $request)
    {

        abort_if(Gate::denies('iv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (isset($request->accept)) {
            // dd($request);
            Iv::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Iv::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->remarks = '';
                $query->location = '';
                $query->from_date = '';
                $query->to_date = '';
                $query->add = 'Add';

                $student = $query;
                $stu_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $student = $query[0];

                $list = $query;

                $stu_edit = new Iv;
                $stu_edit->add = 'Add';
                $stu_edit->id = '';
                $stu_edit->topic = '';
                $stu_edit->remarks = '';
                $stu_edit->location = '';
                $stu_edit->from_date = '';
                $stu_edit->to_date = '';

            }

        } else {

            // dd($request);

            $query_one = Iv::where(['name_id' => $request->user_name_id])->get();
            $query_two = Iv::where(['id' => $request->id])->get();

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

        $check = 'iv_details';

        return view('admin.StudentProfile.student', compact('student', 'check', 'list', 'stu_edit'));
    }

    public function stu_update(UpdateIvRequest $request, Iv $iv)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $ivs = $iv->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $ivs = false;
        }

        if ($ivs) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $student_iv = new Iv;

            $student_iv->topic = $request->topic;
            $student_iv->remarks = $request->remarks;
            $student_iv->location = $request->location;
            $student_iv->from_date = $request->from_date;
            $student_iv->to_date = $request->to_date;
            $student_iv->name_id = $request->user_name_id;
            $student_iv->status='0';
            $student_iv->save();

            if ($student_iv) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.ivs.stu_index', $student);
    }



    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('iv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (isset($request->accept)) {
            // dd($request);
            Iv::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Iv::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->remarks = '';
                $query->location = '';
                $query->from_date = '';
                $query->to_date = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new Iv;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->topic = '';
                $staff_edit->remarks = '';
                $staff_edit->location = '';
                $staff_edit->from_date = '';
                $staff_edit->to_date = '';

            }

        } else {

            // dd($request);

            $query_one = Iv::where(['name_id' => $request->user_name_id])->get();
            $query_two = Iv::where(['id' => $request->id])->get();

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

        $check = 'iv_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateIvRequest $request, Iv $iv)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $ivs = $iv->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $ivs = false;
        }

        if ($ivs) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_iv = new Iv;

            $staff_iv->topic = $request->topic;
            $staff_iv->remarks = $request->remarks;
            $staff_iv->location = $request->location;
            $staff_iv->from_date = $request->from_date;
            $staff_iv->to_date = $request->to_date;
            $staff_iv->name_id = $request->user_name_id;
            $staff_iv->status = '0';
            $staff_iv->save();

            if ($staff_iv) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.ivs.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('iv_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ivs.create', compact('names'));
    }

    public function store(StoreIvRequest $request)
    {
        $iv = Iv::create($request->all());

        return redirect()->route('admin.ivs.index');
    }

    public function edit(Iv $iv)
    {
        abort_if(Gate::denies('iv_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $iv->load('name');

        return view('admin.ivs.edit', compact('iv', 'names'));
    }

    public function update(UpdateIvRequest $request, Iv $iv)
    {
        $iv->update($request->all());

        return redirect()->route('admin.ivs.index');
    }

    public function show(Iv $iv)
    {
        abort_if(Gate::denies('iv_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iv->load('name');

        return view('admin.ivs.show', compact('iv'));
    }

    public function destroy(Iv $iv)
    {
        abort_if(Gate::denies('iv_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iv->delete();

        return back();
    }

    public function massDestroy(MassDestroyIvRequest $request)
    {
        $ivs = Iv::find(request('ids'));

        foreach ($ivs as $iv) {
            $iv->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
