<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBatchRequest;
use App\Http\Requests\UpdateBatchRequest;
use App\Models\Batch;
use App\Models\ToolsDegreeType;
use App\Models\Year;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('batch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Batch::query()->select(sprintf('%s.*', (new Batch)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'batch_show';
                $editGate = 'batch_edit';
                $deleteGate = 'batch_delete';
                $crudRoutePart = 'batches';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->editColumn('actions', function ($row) {
                $viewGate = 'batch_show';
                $editGate = 'batch_edit';
                $deleteGate = 'batch_delete';
                $crudRoutePart = 'batches';
                $viewFunct = 'viewBatch';
                $editFunct = 'editBatch';
                $deleteFunct = 'deleteBatch';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'viewFunct',
                    'editGate',
                    'editFunct',
                    'deleteGate',
                    'deleteFunct',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('degree_type', function ($row) {
                return $row->degree_type ? $row->degree_type : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $years = Year::select('year')->get();

        $degreeTypes = ToolsDegreeType::pluck('name','id');

        return view('admin.batches.index', compact('years','degreeTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('batch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $year = Year::pluck('year');

        return view('admin.batches.create', compact('year'));
    }

    public function store(Request $request)
    {
        if (isset($request->from) && isset($request->to)) {
            if ($request->id == '') {
                $count = Batch::where(['from' => $request->from, 'to' => $request->to])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Batch Already Exist.']);
                } else {
                    $store = Batch::create([
                        'from' => $request->from,
                        'to' => $request->to,
                        'name' => $request->from . '-' . $request->to,
                        'degree_type' => $request->degree_type
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Batch Created']);
            } else {
                $count = Batch::whereNotIn('id', [$request->id])->where(['from' => $request->from, 'to' => $request->to])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Batch Already Exist.']);
                } else {

                    $update = Batch::where(['id' => $request->id])->update([
                        'from' => $request->from,
                        'to' => $request->to,
                        'name' => $request->from . '-' . $request->to,
                        'degree_type' => $request->degree_type
                    ]);

                }
                return response()->json(['status' => true, 'data' => 'Batch Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Batch Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Batch::where(['id' => $request->id])->select('id', 'from', 'to','degree_type')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Batch::where(['id' => $request->id])->select('id', 'from', 'to','degree_type')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Batch::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Batch Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(MassDestroyBatchRequest $request)
    {
        $batches = Batch::find(request('ids'));
        foreach ($batches as $batch) {
            $batch->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Batch Deleted Successfully']);
    }

    public function update(UpdateBatchRequest $request, Batch $batch)
    {
        $batch->update($request->all());

        return redirect()->route('admin.batches.index');
    }

    public function show(Batch $batch)
    {
        abort_if(Gate::denies('batch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.batches.show', compact('batch'));
    }

}
