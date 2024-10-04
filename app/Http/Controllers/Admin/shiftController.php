<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolsDegreeTypeRequest;
use App\Http\Requests\MassDestroyToolsDepartmentRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreToolsDegreeTypeRequest;
use App\Http\Requests\UpdateToolsDegreeTypeRequest;
use App\Models\ToolsDegreeType;
use App\Models\ShiftModel;
use App\Models\ToolsDepartment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class shiftController extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('shift')->select('*');
        if(request()->ajax()) {
            $dataTable = datatables()->of($data);

            $dataTable->addColumn('placeholder', '&nbsp;');
            $dataTable->addColumn('actions', '&nbsp;');

            $dataTable->editColumn('actions', function ($row) {
                $viewGate      = 'tools_department_show';
                $editGate      = 'tools_department_edit';
                $deleteGate    = 'tools_department_delete';
                $crudRoutePart = 'Shift';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $dataTable->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $dataTable->editColumn('Name', function ($row) {
                return $row->Name ? $row->Name : '';
            });
            $dataTable->editColumn('time', function ($row) {
                return $row->time ? $row->time : '';
            });
            $dataTable->editColumn('endTime', function ($row) {
                return $row->endTime ? $row->endTime : '';
            });
           $dataTable->rawColumns(['actions', 'placeholder']);

            return $dataTable->make(true);
        }

        return view('admin.Shift.shiftindex')->with('data', $data->get());

    }
    public function show()
    {

        return view('admin.Shift.shiftindex');
    }
    public function edit($id)
    {
        $request = DB::table('shift')
         ->select('*')
         ->where('id', $id)
         ->first();
        return view('admin.Shift.edit',compact('request'));
    }

    public function create()
    {


        return view('admin.Shift.create');
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request->except('_token');
        ShiftModel::create($data);


        return redirect()->route('admin.Shift.index');
    }


    public function update(Request $request,$id)
    {   
        $data = $request->except('_token', '_method', 'id');
   DB::table('shift')
    ->where('id', $id)
    ->update($data);
        return redirect()->route('admin.Shift.index');
    }

    public function massDestroy(MassDestroyToolsDepartmentRequest $request)
    {
        $toolsDepartments = ToolsDepartment::find(request('ids'));

        foreach ($toolsDepartments as $toolsDepartment) {
            $toolsDepartment->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
