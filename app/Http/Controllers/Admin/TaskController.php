<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskTag;
use App\Models\UserAlert;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\MassDestroyTaskRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TaskController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tasks = Task::with(['status', 'tags', 'assigned_to', 'media'])->get();

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        abort_if(Gate::denies('task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = TaskStatus::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tags = TaskTag::pluck('name', 'id');

        $roles = Role::pluck('title', 'id')->prepend('Select Role', '');

        $assigned_tos = [];

        return view('admin.tasks.create', compact('assigned_tos','roles', 'statuses', 'tags'));
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all());
        if($task != ''){
            if($task->assigned_to_id != ''){
                $userAlert = new UserAlert;
                $userAlert->alert_text = auth()->user()->name . ' assigned a task to you';
                $userAlert->alert_link = null;
                $userAlert->save();
                $userAlert->users()->sync($task->assigned_to_id);
            }
        }
        $task->tags()->sync($request->input('tags', []));
        if ($request->input('attachment', false)) {
            $task->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachment'))))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $task->id]);
        }

        return redirect()->route('admin.tasks.index');
    }

    public function edit(Task $task)
    {
        abort_if(Gate::denies('task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = TaskStatus::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tags = TaskTag::pluck('name', 'id');



        $roles = Role::pluck('title', 'id')->prepend('Select Role', '');

        if($task != ''){
            $role = $task->role_id;
            if($role != ''){
                $assigned_tos = DB::table('role_user')->where(['role_id' => $role])->join('users','role_user.user_id','=','users.id')->select('id','name','employID','register_no')->where('users.deleted_at','=', null)->get();
            }else{
                $assigned_tos = [];
            }
        }

        $task->load('status', 'tags', 'assigned_to');

        return view('admin.tasks.edit', compact('assigned_tos','roles', 'statuses', 'tags', 'task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->all());
        $task->tags()->sync($request->input('tags', []));
        if ($request->input('attachment', false)) {
            if (! $task->attachment || $request->input('attachment') !== $task->attachment->file_name) {
                if ($task->attachment) {
                    $task->attachment->delete();
                }
                $task->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachment'))))->toMediaCollection('attachment');
            }
        } elseif ($task->attachment) {
            $task->attachment->delete();
        }

        return redirect()->route('admin.tasks.index');
    }

    public function show(Task $task)
    {
        abort_if(Gate::denies('task_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->load('status', 'tags', 'assigned_to');

        return view('admin.tasks.show', compact('task'));
    }

    public function destroy(Task $task)
    {
        abort_if(Gate::denies('task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->delete();

        return back();
    }

    public function massDestroy(MassDestroyTaskRequest $request)
    {
        $tasks = Task::find(request('ids'));

        foreach ($tasks as $task) {
            $task->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('task_create') && Gate::denies('task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Task();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function getUsers(Request $request){
        if(isset($request->role_id) && $request->role_id != 0) {
            $getUser = DB::table('role_user')->where(['role_id' => $request->role_id])->join('users','role_user.user_id','=','users.id')->select('id','name','employID','register_no')->where('users.deleted_at','=', null)->get();
           return response()->json(['status' => true,'data' => $getUser]);
        }else{
            return response()->json(['status' => false,'data'=> 'Invalid Role']);
        }

    }
}
