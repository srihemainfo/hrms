<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\EducationType;
use App\Models\TeachingStaff;
use App\Models\MediumofStudied;
use App\Models\Staffs;
use App\Models\NonTeachingStaff;
use App\Models\EducationalDetail;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\StoreEducationalDetailRequest;
use App\Http\Requests\UpdateEducationalDetailRequest;
use App\Http\Requests\MassDestroyEducationalDetailRequest;
use Illuminate\Support\Facades\Gate;

class EducationalDetailsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('educational_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EducationalDetail::with(['education_type', 'medium'])->select(sprintf('%s.*', (new EducationalDetail)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'educational_detail_show';
                $editGate = 'educational_detail_edit';
                $deleteGate = 'educational_detail_delete';
                $crudRoutePart = 'educational-details';

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
                return $row->id ? $row->id : null;
            });
            $table->addColumn('education_type_name', function ($row) {
                return $row->education_type ? $row->education_type->name : null;
            });

            $table->editColumn('institute_name', function ($row) {
                return $row->institute_name ? $row->institute_name : null;
            });
            $table->editColumn('institute_location', function ($row) {
                return $row->institute_location ? $row->institute_location : null;
            });
            $table->addColumn('medium_medium', function ($row) {
                return $row->medium ? $row->medium->medium : null;
            });

            $table->editColumn('board_or_university', function ($row) {
                return $row->board_or_university ? $row->board_or_university : null;
            });
            $table->editColumn('marks', function ($row) {
                return $row->marks ? $row->marks : null;
            });
            $table->editColumn('marks_in_percentage', function ($row) {
                return $row->marks_in_percentage ? $row->marks_in_percentage : null;
            });
            $table->editColumn('subject_1', function ($row) {
                return $row->subject_1 ? $row->subject_1 : null;
            });
            $table->editColumn('mark_1', function ($row) {
                return $row->mark_1 ? $row->mark_1 : null;
            });
            $table->editColumn('subject_2', function ($row) {
                return $row->subject_2 ? $row->subject_2 : null;
            });
            $table->editColumn('mark_2', function ($row) {
                return $row->mark_2 ? $row->mark_2 : null;
            });
            $table->editColumn('subject_3', function ($row) {
                return $row->subject_3 ? $row->subject_3 : null;
            });
            $table->editColumn('mark_3', function ($row) {
                return $row->mark_3 ? $row->mark_3 : null;
            });
            $table->editColumn('subject_4', function ($row) {
                return $row->subject_4 ? $row->subject_4 : null;
            });
            $table->editColumn('mark_4', function ($row) {
                return $row->mark_4 ? $row->mark_4 : null;
            });
            $table->editColumn('subject_5', function ($row) {
                return $row->subject_5 ? $row->subject_5 : null;
            });
            $table->editColumn('mark_5', function ($row) {
                return $row->mark_5 ? $row->mark_5 : null;
            });
            $table->editColumn('subject_6', function ($row) {
                return $row->subject_6 ? $row->subject_6 : null;
            });
            $table->editColumn('mark_6', function ($row) {
                return $row->mark_6 ? $row->mark_6 : null;
            });

            $table->rawColumns(['actions', 'placeholder', 'education_type', 'medium']);

            return $table->make(true);
        }

        return view('admin.educationalDetails.index');
    }


    public function staff_index(Request $request)
    {


        abort_if(Gate::denies('educational_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $education_types = EducationType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $medium = MediumofStudied::pluck('medium', 'id')->prepend(trans('global.pleaseSelect'), '');

        if (!$request->updater) {

            $query = EducationalDetail::with(['education_type', 'medium'])->where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->id = null;
                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->education_type_id = null;
                $query->medium_id = null;
                $query->education_types = $education_types;
                $query->medium = $medium;
                $query->institute_name = null;
                $query->institute_location = null;
                $query->board_or_university = null;
                $query->marks_in_percentage = null;
                $query->month_value = null;
                $query->study_mode = null;
                $query->qualification = null;
                $query->course_duration = null;
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];
            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $query[0]['education_types'] = $education_types;

                $query[0]['medium'] = $medium;

                $staff = $query[0];

                $staff_edit = new EducationalDetail;
                $staff_edit->add = 'Add';
                $staff_edit->id = null;
                $staff_edit->education_type_id = null;
                $staff_edit->medium_id = null;
                $staff_edit->education_types = $education_types;
                $staff_edit->medium = $medium;
                $staff_edit->institute_name = null;
                $staff_edit->institute_location = null;
                $staff_edit->board_or_university = null;
                $staff_edit->marks_in_percentage = null;
                $staff_edit->month_value = null;
                $staff_edit->study_mode = null;
                $staff_edit->qualification = null;
                $staff_edit->course_duration = null;

                for ($i = 0; $i < count($query); $i++) {
                    $query[$i]->education_types = $education_types;

                    $query[$i]->medium = $medium;

                }
                $list = $query;

            }

        } else {
            $query_one = EducationalDetail::with(['education_type', 'medium'])->where(['user_name_id' => $request->user_name_id])->get();

            $query_two = EducationalDetail::with(['education_type', 'medium'])->where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';
                $query_one[0]['medium'] = $medium;
                $query_two[0]['medium'] = $medium;
                $query_one[0]['education_types'] = $education_types;
                $query_two[0]['education_types'] = $education_types;

                $staff = $query_one[0];

                $list = $query_one;

                for ($i = 0; $i < count($query_one); $i++) {
                    $query_one[$i]->education_types = $education_types;

                    $query_one[$i]->medium = $medium;

                }

                $staff_edit = $query_two[0];

            } else {
                dd('Error');
            }
        }

        $check = 'educational_details';
        $check_staff_1 = Staffs::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check', 'list', 'staff_edit'));
            }
        }
    }


    public function staff_update(Request $request, EducationalDetail $educationalDetail)
    {

        abort_if(Gate::denies('educational_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $request->validate([
            'month_value' => 'required|date_format:Y-m',
        ]);
        $date = $request->input('month_value');
        $timestamp = strtotime($date);
        $formattedDate = date('Y-m-d', $timestamp);


        if (!$request->id == 0 || $request->id != '') {

            $educationalDetails = $educationalDetail
                ->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])
                ->update(
                    array_merge(
                        request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']),
                        ['month_value' => $formattedDate]
                    )
                );

        } else {
            $educationalDetails = false;
        }

        if ($educationalDetails) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_education = new EducationalDetail;

            $staff_education->education_type_id = $request->education_type_id;
            $staff_education->user_name_id = $request->user_name_id;
            $staff_education->institute_name = $request->institute_name;
            $staff_education->institute_location = $request->institute_location;
            $staff_education->board_or_university = $request->board_or_university;
            $staff_education->medium_id = $request->medium_id;
            $staff_education->marks_in_percentage = $request->marks_in_percentage;
            $staff_education->month_value = $formattedDate;
            $staff_education->study_mode = $request->study_mode;
            $staff_education->qualification = $request->qualification;
            $staff_education->course_duration = $request->course_duration;
            $staff_education->save();

            if ($staff_education) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                dd('Error');
            }
        }

        return redirect()->route('admin.educational-details.staff_index', $staff);
    }


    public function create()
    {
        // abort_if(Gate::denies('educational_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $education_types = EducationType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $media = MediumofStudied::pluck('medium', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.educationalDetails.create', compact('education_types', 'media'));
    }

    public function store(StoreEducationalDetailRequest $request)
    {
        $educationalDetail = EducationalDetail::create($request->all());

        return redirect()->route('admin.educational-details.index');
    }

    public function edit(EducationalDetail $educationalDetail)
    {
        abort_if(Gate::denies('educational_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $education_types = EducationType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $media = MediumofStudied::pluck('medium', 'id')->prepend(trans('global.pleaseSelect'), '');

        $educationalDetail->load('education_type', 'medium');

        return view('admin.educationalDetails.edit', compact('education_types', 'educationalDetail', 'media'));
    }

    public function update(UpdateEducationalDetailRequest $request, EducationalDetail $educationalDetail)
    {
        $educationalDetail->update($request->all());

        return redirect()->route('admin.educational-details.index');
    }

    public function show(EducationalDetail $educationalDetail)
    {
        // abort_if(Gate::denies('educational_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationalDetail->load('education_type', 'medium');

        return view('admin.educationalDetails.show', compact('educationalDetail'));
    }

    public function destroy(EducationalDetail $educationalDetail)
    {
        abort_if(Gate::denies('educational_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationalDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyEducationalDetailRequest $request)
    {
        $educationalDetails = EducationalDetail::find(request('ids'));

        foreach ($educationalDetails as $educationalDetail) {
            $educationalDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
