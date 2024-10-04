<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuestLectureRequest;
use App\Http\Requests\StoreGuestLectureRequest;
use App\Http\Requests\UpdateGuestLectureRequest;
use App\Models\GuestLecture;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GuestLectureController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('guest_lecture_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GuestLecture::with(['user_name'])->select(sprintf('%s.*', (new GuestLecture)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'guest_lecture_show';
                $editGate = 'guest_lecture_edit';
                $deleteGate = 'guest_lecture_delete';
                $crudRoutePart = 'guest-lectures';

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
            $table->addColumn('user_name_name', function ($row) {
                return $row->user_name ? $row->user_name->name : '';
            });

            $table->editColumn('topic', function ($row) {
                return $row->topic ? $row->topic : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user_name']);

            return $table->make(true);
        }

        return view('admin.guestLectures.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('guest_lecture_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$request->updater) {
            $query = GuestLecture::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->topic = '';
                $query->remarks = '';
                $query->location = '';
                $query->from_date_and_time = '';
                $query->to_date_and_time = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;


                $staff = $query[0];


                $list = $query;

                $staff_edit = new GuestLecture;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->topic = '';
                $staff_edit->remarks = '';
                $staff_edit->location = '';
                $staff_edit->from_date_and_time = '';
                $staff_edit->to_date_and_time = '';


            }

        } else {

            // dd($request);

            $query_one = GuestLecture::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = GuestLecture::where(['id' => $request->id])->get();

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

        $check = 'guest_lecture_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateGuestLectureRequest $request, GuestLecture $guestLecture)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $guestlect = $guestLecture->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        }else{
            $guestlect = false;
        }

        if ($guestlect) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_guest = new GuestLecture;

            $staff_guest->topic = $request->topic;
            $staff_guest->remarks = $request->remarks;
            $staff_guest->location = $request->location;
            $staff_guest->from_date_and_time = $request->from_date_and_time;
            $staff_guest->to_date_and_time = $request->to_date_and_time;
            $staff_guest->user_name_id = $request->user_name_id;
            $staff_guest->save();

            if ($staff_guest) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

// dd($student);
        return redirect()->route('admin.guest-lectures.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('guest_lecture_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.guestLectures.create', compact('user_names'));
    }

    public function store(StoreGuestLectureRequest $request)
    {
        $guestLecture = GuestLecture::create($request->all());

        return redirect()->route('admin.guest-lectures.index');
    }

    public function edit(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $staff->load('user_name');

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list'));
    }

    public function update(UpdateGuestLectureRequest $request, GuestLecture $guestLecture)
    {
        $guestLecture->update($request->all());

        return redirect()->route('admin.guest-lectures.index');
    }

    public function show(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->load('user_name');

        return view('admin.guestLectures.show', compact('guestLecture'));
    }

    public function destroy(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuestLectureRequest $request)
    {
        $guestLectures = GuestLecture::find(request('ids'));

        foreach ($guestLectures as $guestLecture) {
            $guestLecture->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
