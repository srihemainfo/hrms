<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassRoomRequest;
use App\Http\Requests\UpdateClassRoomRequest;
use App\Http\Resources\Admin\ClassRoomResource;
use App\Models\ClassRoom;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassRoomsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('class_room_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClassRoomResource(ClassRoom::with(['block'])->get());
    }

    public function store(StoreClassRoomRequest $request)
    {
        $classRoom = ClassRoom::create($request->all());

        return (new ClassRoomResource($classRoom))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ClassRoom $classRoom)
    {
        abort_if(Gate::denies('class_room_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClassRoomResource($classRoom->load(['block']));
    }

    public function update(UpdateClassRoomRequest $request, ClassRoom $classRoom)
    {
        $classRoom->update($request->all());

        return (new ClassRoomResource($classRoom))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ClassRoom $classRoom)
    {
        abort_if(Gate::denies('class_room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $classRoom->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
