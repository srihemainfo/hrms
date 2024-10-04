<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWorkshopRequest;
use App\Http\Requests\StoreWorkshopRequest;
use App\Http\Requests\UpdateWorkshopRequest;
use App\Models\User;
use App\Models\Workshop;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class WorkshopController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('workshop_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Workshop::with(['user_name'])->select(sprintf('%s.*', (new Workshop)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'workshop_show';
                $editGate = 'workshop_edit';
                $deleteGate = 'workshop_delete';
                $crudRoutePart = 'workshops';

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

        return view('admin.workshops.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('workshop_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
// dd($request);
        if (!$request->updater) {
            $query = Workshop::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->remarks = '';
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

                $staff_edit = new Workshop;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->topic = '';
                $staff_edit->remarks = '';
                $staff_edit->from_date = '';
                $staff_edit->to_date = '';

            }

        } else {

            // dd($request);

            $query_one = Workshop::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = Workshop::where(['id' => $request->id])->get();

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

        $check = 'workshop_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateWorkshopRequest $request, Workshop $workshop)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $workshops = $workshop->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $workshops = false;
        }

        if ($workshops) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_work = new Workshop;

            $staff_work->topic = $request->topic;
            $staff_work->remarks = $request->remarks;
            $staff_work->from_date = $request->from_date;
            $staff_work->to_date = $request->to_date;
            $staff_work->user_name_id = $request->user_name_id;
            $staff_work->save();

            if ($staff_work) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

// dd($student);
        return redirect()->route('admin.workshops.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('workshop_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.workshops.create', compact('user_names'));
    }

    public function store(StoreWorkshopRequest $request)
    {
        $workshop = Workshop::create($request->all());

        return redirect()->route('admin.workshops.index');
    }

    public function edit(Workshop $workshop)
    {
        abort_if(Gate::denies('workshop_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $workshop->load('user_name');

        return view('admin.workshops.edit', compact('user_names', 'workshop'));
    }

    public function update(UpdateWorkshopRequest $request, Workshop $workshop)
    {
        $workshop->update($request->all());

        return redirect()->route('admin.workshops.index');
    }

    public function show(Workshop $workshop)
    {
        abort_if(Gate::denies('workshop_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workshop->load('user_name');

        return view('admin.workshops.show', compact('workshop'));
    }

    public function destroy(Workshop $workshop)
    {
        abort_if(Gate::denies('workshop_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workshop->delete();

        return back();
    }

    public function massDestroy(MassDestroyWorkshopRequest $request)
    {
        $workshops = Workshop::find(request('ids'));

        foreach ($workshops as $workshop) {
            $workshop->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
