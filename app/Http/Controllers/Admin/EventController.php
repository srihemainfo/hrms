<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEventsRequest;
use App\Http\Requests\StoreEventsRequest;
use App\Http\Requests\UpdateEventsRequest;
use App\Models\Events;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Events::query()->select(sprintf('%s.*', (new Events)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewEvent';
                $editFunct = 'editEvent';
                $deleteFunct = 'deleteEvent';
                $viewGate      = 'events_show';
                $editGate      = 'events_edit';
                $deleteGate    = 'events_delete';
                $crudRoutePart = 'events';

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
            $table->editColumn('name', function ($row) {
                return $row->event ? $row->event : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.events.index');
    }

    public function create()
    {
        abort_if(Gate::denies('religion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.create');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = Events::create([
                    'event' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Events Created']);
            } else {
                $update = Events::where(['id' => $request->id])->update([
                    'event' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Events Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Events Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Events::where(['id' => $request->id])->select('id', 'event')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Events::where(['id' => $request->id])->select('id', 'event')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateReligionRequest $request, Events $Events)
    {
        $Events->update($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function show(Events $Events)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.show', compact('Events'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Events::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Events Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $Events = Events::find(request('ids'));

        foreach ($Events as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Religions Deleted Successfully']);
    }
}
