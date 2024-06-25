<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollegeBlock;
use App\Models\RoomCreation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('rooms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RoomCreation::with(['block'])->select(sprintf('%s.*', (new RoomCreation)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'rooms_show';
                $editGate = 'rooms_edit';
                $deleteGate = 'rooms_delete';
                $crudRoutePart = 'rooms';

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

            $table->addColumn('block_name', function ($row) {
                return $row->block ? $row->block->name : '';
            });

            $table->editColumn('room_no', function ($row) {
                return $row->room_no ? $row->room_no : '';
            });

            $table->editColumn('no_of_class_seats', function ($row) {
                return $row->no_of_class_seats ? $row->no_of_class_seats : '';
            });

            $table->editColumn('no_of_exam_seats', function ($row) {
                return $row->no_of_exam_seats ? $row->no_of_exam_seats : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'block']);

            return $table->make(true);
        }

        return view('admin.rooms.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('rooms_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $blocks = CollegeBlock::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.rooms.create', compact('blocks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        if ($request) {
            $check = RoomCreation::where(['block_id' => $request->block_id, 'room_no' => $request->room_no])->get();

            if (count($check) <= 0) {

                $store = new RoomCreation;
                $store->block_id = $request->block_id;
                $store->room_no = $request->room_no;
                $store->no_of_class_seats = $request->no_of_class_seats;
                $store->no_of_exam_seats = $request->no_of_exam_seats;
                $store->save();
            }
        }
        return view('admin.rooms.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RoomCreation $room)
    {
        abort_if(Gate::denies('rooms_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $room->load('block');

        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RoomCreation $room)
    {
        abort_if(Gate::denies('rooms_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blocks = CollegeBlock::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $room->load('block');

        return view('admin.rooms.edit', compact('blocks', 'room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {

        return view('admin.rooms.index');
    }
    public function updater(Request $request)
    {
        if (isset($request->id)) {
            $update = RoomCreation::where(['id' => $request->id])->update([
                'block_id' => $request->block_id,
                'room_no' => $request->room_no,
                'no_of_class_seats' => $request->no_of_class_seats,
                'no_of_exam_seats' => $request->no_of_exam_seats,
            ]);
        }
        // $update = RoomCreation::where(['id' =>])
        return view('admin.rooms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = RoomCreation::find($id);

        $room->delete();

        return view('admin.rooms.index');
    }

    public function massDestroy($request)
    {
        $rooms = RoomCreation::find(request('ids'));

        foreach ($rooms as $room) {
            $room->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
