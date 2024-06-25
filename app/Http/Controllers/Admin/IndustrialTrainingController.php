<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyIndustrialTrainingRequest;
use App\Http\Requests\StoreIndustrialTrainingRequest;
use App\Http\Requests\UpdateIndustrialTrainingRequest;
use App\Models\IndustrialTraining;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class IndustrialTrainingController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('industrial_training_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = IndustrialTraining::with(['name'])->select(sprintf('%s.*', (new IndustrialTraining)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'industrial_training_show';
                $editGate = 'industrial_training_edit';
                $deleteGate = 'industrial_training_delete';
                $crudRoutePart = 'industrial-trainings';

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
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name']);

            return $table->make(true);
        }

        return view('admin.industrialTrainings.index');
    }

    public function stu_index(Request $request)
    {

        abort_if(Gate::denies('industrial_training_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
// dd($request);

        if (isset($request->accept)) {
            // dd($request);
            IndustrialTraining::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = IndustrialTraining::where(['name_id' => $request->user_name_id])->get();

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

                $stu_edit = new IndustrialTraining;
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

            $query_one = IndustrialTraining::where(['name_id' => $request->user_name_id])->get();
            $query_two = IndustrialTraining::where(['id' => $request->id])->get();

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

        $check = 'industrial_training_details';

        return view('admin.StudentProfile.student', compact('student', 'check', 'list', 'stu_edit'));
    }

    public function stu_update(UpdateIndustrialTrainingRequest $request, IndustrialTraining $industrialTraining)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $insdustrial = $industrialTraining->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $insdustrial = false;
        }

        if ($insdustrial) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_train = new IndustrialTraining;

            $stu_train->topic = $request->topic;
            $stu_train->remarks = $request->remarks;
            $stu_train->location = $request->location;
            $stu_train->from_date = $request->from_date;
            $stu_train->to_date = $request->to_date;
            $stu_train->name_id = $request->user_name_id;
            $stu_train->status='0';
            $stu_train->save();

            if ($stu_train) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

// dd($student);
        return redirect()->route('admin.industrial-trainings.stu_index', $student);
    }


    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('industrial_training_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
// dd($request);
         if (isset($request->accept)) {
             // dd($request);
             IndustrialTraining::where('id', $request->id)->update(['status' => 1]);
         }
        if (!$request->updater) {
            $query = IndustrialTraining::where(['name_id' => $request->user_name_id])->get();

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

                $staff_edit = new IndustrialTraining;
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

            $query_one = IndustrialTraining::where(['name_id' => $request->user_name_id])->get();
            $query_two = IndustrialTraining::where(['id' => $request->id])->get();

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

        $check = 'industrial_training_details';
        
        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateIndustrialTrainingRequest $request, IndustrialTraining $industrialTraining)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $insdustrial = $industrialTraining->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $insdustrial = false;
        }

        if ($insdustrial) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_train = new IndustrialTraining;

            $staff_train->topic = $request->topic;
            $staff_train->remarks = $request->remarks;
            $staff_train->location = $request->location;
            $staff_train->from_date = $request->from_date;
            $staff_train->to_date = $request->to_date;
            $staff_train->name_id = $request->user_name_id;
            $staff_train->status='0';
            $staff_train->save();

            if ($staff_train) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

// dd($student);
        return redirect()->route('admin.industrial-trainings.staff_index', $staff);
    }
    public function create()
    {
        abort_if(Gate::denies('industrial_training_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.industrialTrainings.create', compact('names'));
    }

    public function store(StoreIndustrialTrainingRequest $request)
    {
        $industrialTraining = IndustrialTraining::create($request->all());

        return redirect()->route('admin.industrial-trainings.index');
    }

    public function edit(IndustrialTraining $industrialTraining)
    {
        abort_if(Gate::denies('industrial_training_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $industrialTraining->load('name');

        return view('admin.industrialTrainings.edit', compact('industrialTraining', 'names'));
    }

    public function update(UpdateIndustrialTrainingRequest $request, IndustrialTraining $industrialTraining)
    {
        $industrialTraining->update($request->all());

        return redirect()->route('admin.industrial-trainings.index');
    }

    public function show(IndustrialTraining $industrialTraining)
    {
        abort_if(Gate::denies('industrial_training_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialTraining->load('name');

        return view('admin.industrialTrainings.show', compact('industrialTraining'));
    }

    public function destroy(IndustrialTraining $industrialTraining)
    {
        abort_if(Gate::denies('industrial_training_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialTraining->delete();

        return back();
    }

    public function massDestroy(MassDestroyIndustrialTrainingRequest $request)
    {
        $industrialTrainings = IndustrialTraining::find(request('ids'));

        foreach ($industrialTrainings as $industrialTraining) {
            $industrialTraining->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
