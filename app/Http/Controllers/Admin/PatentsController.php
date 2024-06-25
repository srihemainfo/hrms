<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPatentRequest;
use App\Http\Requests\StorePatentRequest;
use App\Http\Requests\UpdatePatentRequest;
use App\Models\Patent;
use App\Models\User;
use App\Models\UserAlert;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PatentsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('patent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Patent::with(['name'])->select(sprintf('%s.*', (new Patent)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'patent_show';
                $editGate = 'patent_edit';
                $deleteGate = 'patent_delete';
                $crudRoutePart = 'patents';

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

            $table->editColumn('topic', function ($row) {
                return $row->topic ? $row->topic : '';
            });
            $table->editColumn('remark', function ($row) {
                return $row->remark ? $row->remark : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name']);

            return $table->make(true);
        }

        return view('admin.patents.index');
    }

    public function stu_index(Request $request)
    {

        abort_if(Gate::denies('patent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
// dd($request);
        if (isset($request->accept)) {

            Patent::where('id', $request->id)->update(['status' => 1]);
          }

        if (!$request->updater) {
            $query = Patent::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->title = '';
                $query->remark = '';
                $query->add = 'Add';

                $student = $query;
                $stu_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $student = $query[0];

                $list = $query;

                $stu_edit = new Patent;
                $stu_edit->add = 'Add';
                $stu_edit->id = '';
                $stu_edit->title = '';
                $stu_edit->remark = '';

            }

        } else {

            // dd($request);

            $query_one = Patent::where(['name_id' => $request->user_name_id])->get();
            $query_two = Patent::where(['id' => $request->id])->get();

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

        $check = 'patent_details';

        return view('admin.StudentProfile.student', compact('student', 'check', 'list', 'stu_edit'));
    }

    public function stu_update(UpdatePatentRequest $request, Patent $patent)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $patents = $patent->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $patents = false;
        }

        if ($patents) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_patent = new Patent;

            $stu_patent->title = $request->title;
            $stu_patent->remark = $request->remark;
            $stu_patent->name_id = $request->user_name_id;
            $stu_patent->status='0';
            $stu_patent->save();

            if ($stu_patent) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
                $fullUrl = URL::previous();
                $userAlert = new UserAlert;
                $userAlert->alert_text = $request->name.' Requesting For Approvel';
                $userAlert->alert_link=$fullUrl;
                $userAlert->save();
                $userAlert->users()->sync($request->input('users', 1));
            } else {
                dd('Error');
            }
        }

// dd($student);
        return redirect()->route('admin.patents.stu_index', $student);
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('patent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
// dd($request);
if (isset($request->accept)) {

    Patent::where('id', $request->id)->update(['status' => 1]);
  }
        if (!$request->updater) {
            $query = Patent::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->title = '';
                $query->application_no = '';
                $query->application_date = '';
                $query->application_status = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new Patent;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->title = '';
                $staff_edit->application_no = '';
                $staff_edit->application_date = '';
                $staff_edit->application_status = '';

            }

        } else {

            // dd($request);

            $query_one = Patent::where(['name_id' => $request->user_name_id])->get();
            $query_two = Patent::where(['id' => $request->id])->get();

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

        $check = 'patent_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdatePatentRequest $request, Patent $patent)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $patents = $patent->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $patents = false;
        }

        if ($patents) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_patent = new Patent;

            $staff_patent->title = $request->title;
            $staff_patent->application_no = $request->application_no;
            $staff_patent->application_date = $request->application_date;
            $staff_patent->application_status = $request->application_status;
            $staff_patent->name_id = $request->user_name_id;
            $staff_patent->status='0';
            $staff_patent->save();

            if ($staff_patent) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
                $fullUrl = URL::previous();
                $userAlert = new UserAlert;
                $userAlert->alert_text = $request->name.' Requesting For Approvel';
                $userAlert->alert_link=$fullUrl;
                $userAlert->save();
                $userAlert->users()->sync($request->input('users', 1));
            } else {
                dd('Error');
            }
        }

// dd($student);
        return redirect()->route('admin.patents.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('patent_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.patents.create', compact('names'));
    }

    public function store(StorePatentRequest $request)
    {
        $patent = Patent::create($request->all());

        return redirect()->route('admin.patents.index');
    }

    public function edit(Patent $patent)
    {
        abort_if(Gate::denies('patent_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $patent->load('name');

        return view('admin.patents.edit', compact('names', 'patent'));
    }

    public function update(UpdatePatentRequest $request, Patent $patent)
    {
        $patent->update($request->all());

        return redirect()->route('admin.patents.index');
    }

    public function show(Patent $patent)
    {
        abort_if(Gate::denies('patent_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patent->load('name');

        return view('admin.patents.show', compact('patent'));
    }

    public function destroy(Patent $patent)
    {
        abort_if(Gate::denies('patent_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patent->delete();

        return back();
    }

    public function massDestroy(MassDestroyPatentRequest $request)
    {
        $patents = Patent::find(request('ids'));

        foreach ($patents as $patent) {
            $patent->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
