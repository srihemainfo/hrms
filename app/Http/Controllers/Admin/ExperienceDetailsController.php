<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyExperienceDetailRequest;
use App\Http\Requests\StoreExperienceDetailRequest;
use App\Http\Requests\UpdateExperienceDetailRequest;
use App\Models\ExperienceDetail;
use App\Models\NonTeachingStaff;
use App\Models\TeachingStaff;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ExperienceDetailsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('experience_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ExperienceDetail::with(['name'])->select(sprintf('%s.*', (new ExperienceDetail)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'experience_detail_show';
                $editGate = 'experience_detail_edit';
                $deleteGate = 'experience_detail_delete';
                $crudRoutePart = 'experience-details';

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
            $table->editColumn('designation', function ($row) {
                return $row->designation ? $row->designation : '';
            });
            $table->editColumn('years_of_experience', function ($row) {
                return $row->years_of_experience ? $row->years_of_experience : '';
            });
            $table->editColumn('worked_place', function ($row) {
                return $row->worked_place ? $row->worked_place : '';
            });
            $table->editColumn('taken_subjects', function ($row) {
                return $row->taken_subjects ? $row->taken_subjects : '';
            });

            $table->addColumn('name_name', function ($row) {
                return $row->name ? $row->name->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name']);

            return $table->make(true);
        }

        return view('admin.experienceDetails.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('experience_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$request->updater) {

            $query = ExperienceDetail::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->id = '';
                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->designation = '';
                $query->department = '';
                $query->name_of_organisation = '';
                $query->taken_subjects = '';
                $query->doj = '';
                $query->dor = '';
                $query->last_drawn_salary = '';
                $query->responsibilities = '';
                $query->leaving_reason = '';
                $query->address = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];
                // dd($student);
            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $staff_edit = new ExperienceDetail;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->designation = '';
                $staff_edit->department = '';
                $staff_edit->name_of_organisation = '';
                $staff_edit->taken_subjects = '';
                $staff_edit->doj = '';
                $staff_edit->dor = '';
                $staff_edit->last_drawn_salary = '';
                $staff_edit->responsibilities = '';
                $staff_edit->leaving_reason = '';
                $staff_edit->address = '';

                // $staff = $query[0];
                $list = $query;

            }

        } else {
            $query_one = ExperienceDetail::where(['user_name_id' => $request->user_name_id])->get();

            $query_two = ExperienceDetail::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                $list = $query_one;

                // dd($staff);
                $staff_edit = $query_two[0];
                //  dd($staff_edit);
            } else {
                dd('Error');
            }
        }

        $check = 'experience_details';

        $check_staff_1 = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check', 'list', 'staff_edit'));
            }
        }
    }

    public function staff_update(UpdateExperienceDetailRequest $request, ExperienceDetail $experienceDetail)
    {
// dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $experienceDetails = $experienceDetail
                ->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])
                ->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $experienceDetails = false;
        }

        if ($experienceDetails) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_education = new ExperienceDetail;

            $staff_education->user_name_id = $request->user_name_id;
            $staff_education->designation = $request->designation;
            $staff_education->department = $request->department;
            $staff_education->name_of_organisation = $request->name_of_organisation;
            $staff_education->taken_subjects = $request->taken_subjects;
            $staff_education->doj = $request->doj;
            $staff_education->dor = $request->dor;
            $staff_education->last_drawn_salary = $request->last_drawn_salary;
            $staff_education->responsibilities = $request->responsibilities;
            $staff_education->leaving_reason = $request->leaving_reason;
            $staff_education->address = $request->address;
            $staff_education->save();

            if ($staff_education) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        return redirect()->route('admin.experience-details.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('experience_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.experienceDetails.create', compact('names'));
    }

    public function store(StoreExperienceDetailRequest $request)
    {
        $experienceDetail = ExperienceDetail::create($request->all());

        return redirect()->route('admin.experience-details.index');
    }

    public function edit(ExperienceDetail $experienceDetail)
    {
        abort_if(Gate::denies('experience_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $experienceDetail->load('name');

        return view('admin.experienceDetails.edit', compact('experienceDetail', 'names'));
    }

    public function update(UpdateExperienceDetailRequest $request, ExperienceDetail $experienceDetail)
    {
        $experienceDetail->update($request->all());

        return redirect()->route('admin.experience-details.index');
    }

    public function show(ExperienceDetail $experienceDetail)
    {
        abort_if(Gate::denies('experience_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $experienceDetail->load('name');

        return view('admin.experienceDetails.show', compact('experienceDetail'));
    }

    public function destroy(ExperienceDetail $experienceDetail)
    {
        abort_if(Gate::denies('experience_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $experienceDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyExperienceDetailRequest $request)
    {
        $experienceDetails = ExperienceDetail::find(request('ids'));

        foreach ($experienceDetails as $experienceDetail) {
            $experienceDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
