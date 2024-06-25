<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyClassRoomRequest;
use App\Http\Requests\StoreClassRoomRequest;
use App\Http\Requests\UpdateClassRoomRequest;
use App\Models\ClassRoom;
use App\Models\CollegeBlock;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassRoomsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('class_room_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $classRooms = ClassRoom::with(['block'])->get();

        return view('frontend.classRooms.index', compact('classRooms'));
    }

    public function create()
    {
        abort_if(Gate::denies('class_room_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blocks = CollegeBlock::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.classRooms.create', compact('blocks'));
    }

    public function store(StoreClassRoomRequest $request)
    {
        $classRoom = ClassRoom::create($request->all());

        return redirect()->route('frontend.class-rooms.index');
    }

    public function edit(ClassRoom $classRoom)
    {
        abort_if(Gate::denies('class_room_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blocks = CollegeBlock::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $classRoom->load('block');

        return view('frontend.classRooms.edit', compact('blocks', 'classRoom'));
    }

    public function update(UpdateClassRoomRequest $request, ClassRoom $classRoom)
    {
        $classRoom->update($request->all());

        return redirect()->route('frontend.class-rooms.index');
    }

    public function show(ClassRoom $classRoom)
    {
        abort_if(Gate::denies('class_room_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $classRoom->load('block');

        return view('frontend.classRooms.show', compact('classRoom'));
    }

    public function destroy(ClassRoom $classRoom)
    {
        abort_if(Gate::denies('class_room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $classRoom->delete();

        return back();
    }

    public function massDestroy(MassDestroyClassRoomRequest $request)
    {
        $classRooms = ClassRoom::find(request('ids'));

        foreach ($classRooms as $classRoom) {
            $classRoom->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
