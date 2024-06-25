<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyYearRequest;
use App\Http\Requests\StoreYearRequest;
use App\Http\Requests\UpdateYearRequest;
use App\Models\Year;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;

class YearController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('year_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Year::query()->select(sprintf('%s.*', (new Year)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'year_show';
                $viewFunct = 'viewYear';
                $editGate      = 'year_edit';
                $editFunct = 'editYear';
                $deleteGate    = 'year_delete';
                $deleteFunct = 'deleteYear';
                $crudRoutePart = 'year';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('year', function ($row) {
                return $row->year ? $row->year : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.year.index');
    }

    public function create()
    {
        return view('admin.year.create');
    }

    public function store(Request $request)
    {
        if (isset($request->year)) {
            if ($request->id == '') {
                $store = Year::create([
                    'year' => $request->year,
                ]);
                return response()->json(['status' => true, 'data' => 'Year Created']);
            } else {
                $update = Year::where(['id' => $request->id])->update([
                    'year' => $request->year,
                ]);
                return response()->json(['status' => true, 'data' => 'Year Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Year Not Created']);
        }
    }


    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Year::where(['id' => $request->id])->select('id', 'year')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(Request $request, Year $year)
    {

        if ($request->year != '') {
            $id = $year->id;
            $record = Year::findOrFail($id);
            $years = $record->year;
            if ($years == $request->year) {
                return redirect()->route('admin.year.index');
            } else {
                $existingRecord = Year::where('year', $request->year)->where('id', '!=', $id)->first();
                if ($existingRecord) {
                    return back()->withInput()->with('message_error', 'Combination already exists.');
                } else {

                    if (preg_match("/^\d{4}$/", $request->year)) {
                        $year->update($request->all());
                        return redirect()->route('admin.year.index')->with('message', 'Year Updated successfully');
                    } else {
                        return back()->withInput()->with('message_error', 'Year Format is Invalid.');
                    }
                }
            }
        } else {
            return back()->withInput()->with('message_error', 'Year Required');
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Year::where(['id' => $request->id])->select('id', 'year')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function show(Year $year)
    {
        abort_if(Gate::denies('year_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.year.show', compact('year'));
    }

    public function destroy(Request $request)
    {

        if (isset($request->id)) {
            $delete = Year::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Year Deleted Successfully']);
        } else {
            return response()->json(['status' => 'success', 'data' => 'Year Deleted Successfully']);
        }
    }

    public function massDestroy(MassDestroyYearRequest $request)
    {
        $years = Year::find(request('ids'));

        foreach ($years as $year) {
            $year->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Year Deleted Successfully']);
    }
}
