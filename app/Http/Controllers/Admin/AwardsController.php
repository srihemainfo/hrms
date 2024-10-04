<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAwardRequest;
use App\Http\Requests\StoreAwardRequest;
use App\Http\Requests\UpdateAwardRequest;
use App\Models\Award;
use App\Models\User;
use App\Models\UserAlert;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AwardsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('award_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Award::with(['user_name'])->select(sprintf('%s.*', (new Award)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'award_show';
                $editGate = 'award_edit';
                $deleteGate = 'award_delete';
                $crudRoutePart = 'awards';

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
            $table->addColumn('user_name_name', function ($row) {
                return $row->user_name ? $row->user_name->name : '';
            });

            $table->editColumn('topic', function ($row) {
                return $row->topic ? $row->topic : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user_name']);

            return $table->make(true);
        }

        return view('admin.awards.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('award_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (isset($request->accept)) {

            Award::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Award::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->title = '';
                $query->organizer_name = '';
                $query->awarded_date = '';
                $query->venue = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new Award;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->title = '';
                $staff_edit->organizer_name = '';
                $staff_edit->awarded_date = '';
                $staff_edit->venue = '';
            }

        } else {

            $query_one = Award::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = Award::where(['id' => $request->id])->get();

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

        $check = 'award_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateAwardRequest $request, Award $award)
    {

        if (!$request->id == 0 || $request->id != '') {

            $awards = $award->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $awards = false;
        }

        if ($awards) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_award = new Award;
            $staff_award->title = $request->title;
            $staff_award->organizer_name = $request->organizer_name;
            $staff_award->awarded_date = $request->awarded_date;
            $staff_award->venue = $request->venue;
            $staff_award->user_name_id = $request->user_name_id;
            $staff_award->status = '0';
            $staff_award->save();

            if ($staff_award) {

                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                $fullUrl = URL::previous();
                $userAlert = new UserAlert;
                $userAlert->alert_text = $request->name . ' Requesting For Approvel';
                $userAlert->alert_link = $fullUrl;
                $userAlert->save();
                $userAlert->users()->sync($request->input('users', 1));
            } else {
                dd('Error');
            }
        }
        return redirect()->route('admin.awards.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('award_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.awards.create', compact('user_names'));
    }

    public function store(StoreAwardRequest $request)
    {
        $award = Award::create($request->all());

        return redirect()->route('admin.awards.index');
    }

    public function edit(Award $award)
    {
        abort_if(Gate::denies('award_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $award->load('user_name');

        return view('admin.awards.edit', compact('award', 'user_names'));
    }

    public function update(UpdateAwardRequest $request, Award $award)
    {
        $award->update($request->all());

        return redirect()->route('admin.awards.index');
    }

    public function show(Award $award)
    {
        abort_if(Gate::denies('award_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $award->load('user_name');

        return view('admin.awards.show', compact('award'));
    }

    public function destroy(Award $award)
    {
        abort_if(Gate::denies('award_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $award->delete();

        return back();
    }

    public function massDestroy(MassDestroyAwardRequest $request)
    {
        $awards = Award::find(request('ids'));

        foreach ($awards as $award) {
            $award->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
