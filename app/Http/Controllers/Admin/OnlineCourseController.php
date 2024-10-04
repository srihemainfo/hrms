<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOnlineCourseRequest;
use App\Http\Requests\StoreOnlineCourseRequest;
use App\Http\Requests\UpdateOnlineCourseRequest;
use App\Models\OnlineCourse;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OnlineCourseController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('online_course_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OnlineCourse::with(['user_name'])->select(sprintf('%s.*', (new OnlineCourse)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'online_course_show';
                $editGate = 'online_course_edit';
                $deleteGate = 'online_course_delete';
                $crudRoutePart = 'online-courses';

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
            $table->addColumn('user_name_name', function ($row) {
                return $row->user_name ? $row->user_name->name : '';
            });

            $table->editColumn('course_name', function ($row) {
                return $row->course_name ? $row->course_name : '';
            });
            $table->editColumn('remark', function ($row) {
                return $row->remark ? $row->remark : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user_name']);

            return $table->make(true);
        }

        return view('admin.onlineCourses.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('online_course_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (isset($request->accept)) {
            // dd($request);
            OnlineCourse::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = OnlineCourse::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->course_name = '';
                $query->remark = '';
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

                $staff_edit = new OnlineCourse;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->course_name = '';
                $staff_edit->remark = '';
                $staff_edit->from_date = '';
                $staff_edit->to_date = '';

            }

        } else {

            // dd($request);

            $query_one = OnlineCourse::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = OnlineCourse::where(['id' => $request->id])->get();

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

        $check = 'online_course_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateOnlineCourseRequest $request, OnlineCourse $onlineCourse)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $on_course = $onlineCourse->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $on_course = false;
        }

        if ($on_course) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_guest = new OnlineCourse;

            $staff_guest->course_name = $request->course_name;
            $staff_guest->remark = $request->remark;
            $staff_guest->from_date = $request->from_date;
            $staff_guest->to_date = $request->to_date;
            $staff_guest->user_name_id = $request->user_name_id;
            $staff_guest->status = '0';
            $staff_guest->save();

            if ($staff_guest) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        // dd($student);
        return redirect()->route('admin.online-courses.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('online_course_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.onlineCourses.create', compact('user_names'));
    }

    public function store(StoreOnlineCourseRequest $request)
    {
        $onlineCourse = OnlineCourse::create($request->all());

        return redirect()->route('admin.online-courses.index');
    }

    public function edit(OnlineCourse $onlineCourse)
    {
        abort_if(Gate::denies('online_course_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $onlineCourse->load('user_name');

        return view('admin.onlineCourses.edit', compact('onlineCourse', 'user_names'));
    }

    public function update(UpdateOnlineCourseRequest $request, OnlineCourse $onlineCourse)
    {
        $onlineCourse->update($request->all());

        return redirect()->route('admin.online-courses.index');
    }

    public function show(OnlineCourse $onlineCourse)
    {
        abort_if(Gate::denies('online_course_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onlineCourse->load('user_name');

        return view('admin.onlineCourses.show', compact('onlineCourse'));
    }

    public function destroy(OnlineCourse $onlineCourse)
    {
        abort_if(Gate::denies('online_course_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onlineCourse->delete();

        return back();
    }

    public function massDestroy(MassDestroyOnlineCourseRequest $request)
    {
        $onlineCourses = OnlineCourse::find(request('ids'));

        foreach ($onlineCourses as $onlineCourse) {
            $onlineCourse->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
