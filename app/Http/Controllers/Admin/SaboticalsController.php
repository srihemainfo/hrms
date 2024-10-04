<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySaboticalRequest;
use App\Http\Requests\StoreSaboticalRequest;
use App\Http\Requests\UpdateSaboticalRequest;
use App\Models\Sabotical;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SaboticalsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('sabotical_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Sabotical::with(['name'])->select(sprintf('%s.*', (new Sabotical)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'sabotical_show';
                $editGate = 'sabotical_edit';
                $deleteGate = 'sabotical_delete';
                $crudRoutePart = 'saboticals';

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
            $table->editColumn('eligiblity_approve', function ($row) {
                return $row->eligiblity_approve ? $row->eligiblity_approve : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name']);

            return $table->make(true);
        }

        return view('admin.saboticals.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('sabotical_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (isset($request->accept)) {

            Sabotical::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Sabotical::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                // $query->eligiblity_approve = '';
                $query->from = '';
                $query->to = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new Sabotical;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->topic = '';
                // $staff_edit->eligiblity_approve = '';
                $staff_edit->from = '';
                $staff_edit->to = '';

            }

        } else {

            // dd($request);

            $query_one = Sabotical::where(['name_id' => $request->user_name_id])->get();
            $query_two = Sabotical::where(['id' => $request->id])->get();

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

        $check = 'sabotical_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateSaboticalRequest $request, Sabotical $sabotical)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $saboticals = $sabotical->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $saboticals = false;
        }

        if ($saboticals) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_saboticals = new Sabotical;

            $staff_saboticals->topic = $request->topic;
            // $staff_saboticals->eligiblity_approve = $request->eligiblity_approve;
            $staff_saboticals->from = $request->from;
            $staff_saboticals->to = $request->to;
            $staff_saboticals->name_id = $request->user_name_id;
            $staff_saboticals->status='0';
            $staff_saboticals->save();

            if ($staff_saboticals) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.saboticals.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('sabotical_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.saboticals.create', compact('names'));
    }

    public function store(StoreSaboticalRequest $request)
    {
        $sabotical = Sabotical::create($request->all());

        return redirect()->route('admin.saboticals.index');
    }

    public function edit(Sabotical $sabotical)
    {
        abort_if(Gate::denies('sabotical_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sabotical->load('name');

        return view('admin.saboticals.edit', compact('names', 'sabotical'));
    }

    public function update(UpdateSaboticalRequest $request, Sabotical $sabotical)
    {
        $sabotical->update($request->all());

        return redirect()->route('admin.saboticals.index');
    }

    public function show(Sabotical $sabotical)
    {
        abort_if(Gate::denies('sabotical_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sabotical->load('name');

        return view('admin.saboticals.show', compact('sabotical'));
    }

    public function destroy(Sabotical $sabotical)
    {
        abort_if(Gate::denies('sabotical_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sabotical->delete();

        return back();
    }

    public function massDestroy(MassDestroySaboticalRequest $request)
    {
        $saboticals = Sabotical::find(request('ids'));

        foreach ($saboticals as $sabotical) {
            $sabotical->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
