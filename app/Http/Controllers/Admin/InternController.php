<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyInternRequest;
use App\Http\Requests\StoreInternRequest;
use App\Http\Requests\UpdateInternRequest;
use App\Models\Intern;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class InternController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('intern_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Intern::with(['name'])->select(sprintf('%s.*', (new Intern)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'intern_show';
                $editGate = 'intern_edit';
                $deleteGate = 'intern_delete';
                $crudRoutePart = 'interns';

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

            $table->editColumn('progress_report', function ($row) {
                return $row->progress_report ? $row->progress_report : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name']);

            return $table->make(true);
        }

        return view('admin.interns.index');
    }

    public function stu_index(Request $request)
    {

        abort_if(Gate::denies('intern_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (isset($request->accept)) {
            // dd($request);
            Intern::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Intern::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->progress_report = '';
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

                $stu_edit = new Intern;
                $stu_edit->add = 'Add';
                $stu_edit->id = '';
                $stu_edit->topic = '';
                $stu_edit->progress_report = '';
                $stu_edit->from_date = '';
                $stu_edit->to_date = '';

            }

        } else {

            // dd($request);

            $query_one = Intern::where(['name_id' => $request->user_name_id])->get();
            $query_two = Intern::where(['id' => $request->id])->get();

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

        $check = 'intern_details';

        return view('admin.StudentProfile.student', compact('student', 'check', 'list', 'stu_edit'));
    }

    public function stu_update(UpdateInternRequest $request, Intern $intern)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $stu_intern = $intern->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $stu_intern = false;
        }

        if ($stu_intern) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_int = new Intern;

            $stu_int->topic = $request->topic;
            $stu_int->progress_report = $request->progress_report;
            $stu_int->from_date = $request->from_date;
            $stu_int->to_date = $request->to_date;
            $stu_int->name_id = $request->user_name_id;
            $stu_int->status='0';
            $stu_int->save();

            if ($stu_int) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.interns.stu_index', $student);
    }


    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('intern_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (isset($request->accept)) {
            // dd($request);
            Intern::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Intern::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->progress_report = '';
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

                $staff_edit = new Intern;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->topic = '';
                $staff_edit->progress_report = '';
                $staff_edit->from_date = '';
                $staff_edit->to_date = '';

            }

        } else {

            // dd($request);

            $query_one = Intern::where(['name_id' => $request->user_name_id])->get();
            $query_two = Intern::where(['id' => $request->id])->get();

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

        $check = 'intern_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateInternRequest $request, Intern $intern)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $staff_intern = $intern->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $staff_intern = false;
        }

        if ($staff_intern) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {
            // dd($request->to_date);
            $staff_int = new Intern;

            $staff_int->topic = $request->topic;
            $staff_int->progress_report = $request->progress_report;
            $staff_int->from_date = $request->from_date;
            $staff_int->to_date = $request->to_date;
            $staff_int->name_id = $request->user_name_id;
            $staff_int->to_date = $request->to_date;
            $staff_int->status = '0';
            $staff_int->save();

            if ($staff_int) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.interns.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('intern_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.interns.create', compact('names'));
    }

    public function store(StoreInternRequest $request)
    {
        $intern = Intern::create($request->all());

        return redirect()->route('admin.interns.index');
    }

    public function edit(Intern $intern)
    {
        abort_if(Gate::denies('intern_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $intern->load('name');

        return view('admin.interns.edit', compact('intern', 'names'));
    }

    public function update(UpdateInternRequest $request, Intern $intern)
    {
        $intern->update($request->all());

        return redirect()->route('admin.interns.index');
    }

    public function show(Intern $intern)
    {
        abort_if(Gate::denies('intern_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $intern->load('name');

        return view('admin.interns.show', compact('intern'));
    }

    public function destroy(Intern $intern)
    {
        abort_if(Gate::denies('intern_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $intern->delete();

        return back();
    }

    public function massDestroy(MassDestroyInternRequest $request)
    {
        $interns = Intern::find(request('ids'));

        foreach ($interns as $intern) {
            $intern->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
