<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAddConferenceRequest;
use App\Http\Requests\StoreAddConferenceRequest;
use App\Http\Requests\UpdateAddConferenceRequest;
use App\Models\AddConference;
use App\Models\User;
use App\Models\UserAlert;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AddConferenceController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('add_conference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AddConference::with(['user_name'])->select(sprintf('%s.*', (new AddConference)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'add_conference_show';
                $editGate = 'add_conference_edit';
                $deleteGate = 'add_conference_delete';
                $crudRoutePart = 'add-conferences';

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

            $table->editColumn('topic_name', function ($row) {
                return $row->topic_name ? $row->topic_name : '';
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : '';
            });

            $table->editColumn('contribution_of_conference', function ($row) {
                return $row->contribution_of_conference ? $row->contribution_of_conference : '';
            });
            $table->editColumn('project_name', function ($row) {
                return $row->project_name ? $row->project_name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user_name']);

            return $table->make(true);
        }

        return view('admin.addConferences.index');
    }

    public function stu_index(Request $request)
    {

        abort_if(Gate::denies('add_conference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->accept)) {

            AddConference::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = AddConference::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic_name = '';
                $query->location = '';
                $query->conference_date = '';
                $query->contribution_of_conference = '';
                $query->project_name = '';
                $query->add = 'Add';

                $student = $query;
                $stu_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $student = $query[0];

                $list = $query;

                $stu_edit = new AddConference;
                $stu_edit->add = 'Add';
                $stu_edit->topic_name = '';
                $stu_edit->location = '';
                $stu_edit->conference_date = '';
                $stu_edit->contribution_of_conference = '';
                $stu_edit->project_name = '';
            }

        } else {

            $query_one = AddConference::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = AddConference::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $student = $query_one[0];

                $list = $query_one;

                $stu_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $check = 'conference_details';

        return view('admin.StudentProfile.student', compact('student', 'check', 'list', 'stu_edit'));
    }

    public function stu_update(UpdateAddConferenceRequest $request, AddConference $addConference)
    {

        if (!$request->id == 0 || $request->id != '') {

            $addConferences = $addConference->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $addConferences = false;
        }

        if ($addConferences) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_conference = new AddConference;

            $stu_conference->topic_name = $request->topic_name;
            $stu_conference->location = $request->location;
            $stu_conference->conference_date = $request->conference_date;
            $stu_conference->contribution_of_conference = $request->contribution_of_conference;
            $stu_conference->project_name = $request->project_name;
            $stu_conference->user_name_id = $request->user_name_id;
            $stu_conference->status='0';
            $stu_conference->save();

            if ($stu_conference) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
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

        return redirect()->route('admin.add-conferences.stu_index', $student);
    }




    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('add_conference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$request->updater) {
            $query = AddConference::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic_name = '';
                $query->location = '';
                $query->conference_date = '';
                $query->contribution_of_conference = '';
                $query->project_name = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new AddConference;
                $staff_edit->add = 'Add';
                $staff_edit->topic_name = '';
                $staff_edit->location = '';
                $staff_edit->conference_date = '';
                $staff_edit->contribution_of_conference = '';
                $staff_edit->project_name = '';
            }

        } else {

            $query_one = AddConference::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = AddConference::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                $list = $query_one;

                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $check = 'conference_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateAddConferenceRequest $request, AddConference $addConference)
    {

        if (!$request->id == 0 || $request->id != '') {

            $addConferences = $addConference->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $addConferences = false;
        }

        if ($addConferences) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_conference = new AddConference;

            $staff_conference->topic_name = $request->topic_name;
            $staff_conference->location = $request->location;
            $staff_conference->conference_date = $request->conference_date;
            $staff_conference->contribution_of_conference = $request->contribution_of_conference;
            $staff_conference->project_name = $request->project_name;
            $staff_conference->user_name_id = $request->user_name_id;
            $staff_conference->save();

            if ($staff_conference) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                $fullUrl = URL::previous();
                $userAlert = new UserAlert;
                $userAlert->alert_text = $request->name.' Requesting For Approvel';
                $userAlert->alert_link=$fullUrl;
                $userAlert->save();
                $userAlert->users()->sync($request->input('users', 1));
            } else {
                return back();
            }
        }

        return redirect()->route('admin.add-conferences.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('add_conference_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.addConferences.create', compact('user_names'));
    }

    public function store(StoreAddConferenceRequest $request)
    {
        $addConference = AddConference::create($request->all());

        return redirect()->route('admin.add-conferences.index');
    }

    public function edit(AddConference $addConference)
    {
        abort_if(Gate::denies('add_conference_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addConference->load('user_name');

        return view('admin.addConferences.edit', compact('addConference', 'user_names'));
    }

    public function update(UpdateAddConferenceRequest $request, AddConference $addConference)
    {
        $addConference->update($request->all());

        return redirect()->route('admin.add-conferences.index');
    }

    public function show(AddConference $addConference)
    {
        abort_if(Gate::denies('add_conference_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addConference->load('user_name');

        return view('admin.addConferences.show', compact('addConference'));
    }

    public function destroy(AddConference $addConference)
    {
        abort_if(Gate::denies('add_conference_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addConference->delete();

        return back();
    }

    public function massDestroy(MassDestroyAddConferenceRequest $request)
    {
        $addConferences = AddConference::find(request('ids'));

        foreach ($addConferences as $addConference) {
            $addConference->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
