<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySttpRequest;
use App\Http\Requests\UpdateSttpRequest;
use App\Models\Sttp;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SttpController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('sttp_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Sttp::with(['name'])->select(sprintf('%s.*', (new Sttp)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'sttp_show';
                $editGate = 'sttp_edit';
                $deleteGate = 'sttp_delete';
                $crudRoutePart = 'sttps';

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
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name']);

            return $table->make(true);
        }

        return view('admin.sttps.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('sttp_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (isset($request->accept)) {

            Sttp::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Sttp::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->remarks = '';
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

                $staff_edit = new Sttp;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->topic = '';
                $staff_edit->remarks = '';
                $staff_edit->from = '';
                $staff_edit->to = '';

            }

        } else {

            // dd($request);

            $query_one = Sttp::where(['name_id' => $request->user_name_id])->get();
            $query_two = Sttp::where(['id' => $request->id])->get();

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

        $check = 'sttp_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateSttpRequest $request, Sttp $sttp)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $sttps = $sttp->where(['name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $sttps = false;
        }

        if ($sttps) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_sttp = new Sttp;

            $staff_sttp->topic = $request->topic;
            $staff_sttp->remarks = $request->remarks;
            $staff_sttp->from = $request->from;
            $staff_sttp->to = $request->to;
            $staff_sttp->name_id = $request->user_name_id;
            $staff_sttp->status='0';
            $staff_sttp->save();

            if ($staff_sttp) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.sttps.staff_index', $staff);
    }

    public function update(UpdateSttpRequest $request, Sttp $sttp)
    {
        $sttp->update($request->all());

        return redirect()->route('admin.sttps.index');
    }

    public function show(Sttp $sttp)
    {
        abort_if(Gate::denies('sttp_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sttp->load('name');

        return view('admin.sttps.show', compact('sttp'));
    }

    public function destroy(Sttp $sttp)
    {
        // abort_if(Gate::denies('sttp_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sttp->delete();

        return back();
    }

    public function massDestroy(MassDestroySttpRequest $request)
    {
        $sttps = Sttp::find(request('ids'));

        foreach ($sttps as $sttp) {
            $sttp->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
