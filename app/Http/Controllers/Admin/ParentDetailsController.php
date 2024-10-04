<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyParentDetailRequest;
use App\Http\Requests\StoreParentDetailRequest;
use App\Http\Requests\UpdateParentDetailRequest;
use App\Models\ParentDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ParentDetailsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('parent_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ParentDetail::query()->select(sprintf('%s.*', (new ParentDetail)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'parent_detail_show';
                $editGate = 'parent_detail_edit';
                $deleteGate = 'parent_detail_delete';
                $crudRoutePart = 'parent-details';

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
            $table->editColumn('father_name', function ($row) {
                return $row->father_name ? $row->father_name : '';
            });
            $table->editColumn('father_mobile_no', function ($row) {
                return $row->father_mobile_no ? $row->father_mobile_no : '';
            });
            $table->editColumn('father_email', function ($row) {
                return $row->father_email ? $row->father_email : '';
            });
            $table->editColumn('fathers_occupation', function ($row) {
                return $row->fathers_occupation ? $row->fathers_occupation : '';
            });
            $table->editColumn('mother_name', function ($row) {
                return $row->mother_name ? $row->mother_name : '';
            });
            $table->editColumn('mother_mobile_no', function ($row) {
                return $row->mother_mobile_no ? $row->mother_mobile_no : '';
            });
            $table->editColumn('mother_email', function ($row) {
                return $row->mother_email ? $row->mother_email : '';
            });
            $table->editColumn('mothers_occupation', function ($row) {
                return $row->mothers_occupation ? $row->mothers_occupation : '';
            });
            $table->editColumn('guardian_name', function ($row) {
                return $row->guardian_name ? $row->guardian_name : '';
            });
            $table->editColumn('guardian_mobile_no', function ($row) {
                return $row->guardian_mobile_no ? $row->guardian_mobile_no : '';
            });
            $table->editColumn('guardian_email', function ($row) {
                return $row->guardian_email ? $row->guardian_email : '';
            });
            $table->editColumn('gaurdian_occupation', function ($row) {
                return $row->gaurdian_occupation ? $row->gaurdian_occupation : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.parentDetails.index');
    }

    public function stu_index(Request $request)
    {

        if ($request) {
            $query = ParentDetail::where(['user_name_id' => $request->user_name_id])->get();
        }
        if ($query->count() <= 0) {

            $query->user_name_id = $request->user_name_id;
            $query->father_name = '';
            $query->father_mobile_no = '';
            $query->father_email = '';
            $query->fathers_occupation = '';
            $query->father_off_address = '';
            $query->mother_name = '';
            $query->mother_mobile_no = '';
            $query->mother_email = '';
            $query->mothers_occupation = '';
            $query->mother_off_address = '';
            $query->guardian_name = '';
            $query->guardian_mobile_no = '';
            $query->guardian_email = '';
            $query->gaurdian_occupation = '';
            $query->guardian_off_address = '';
            $query->name = $request->name;
            $query->add = 'Add';
            $student = $query;
        } else {
            $query[0]->add = 'Update';
            $query[0]->name = $request->name;
            $student = $query[0];
        }

        $check = "parent_details";

        return view('admin.StudentProfile.student', compact('student', 'check'));
    }

    public function stu_update(UpdateParentDetailRequest $request, ParentDetail $parentDetail)
    {

        $parent = $parentDetail->where('user_name_id', $request->user_name_id)->update(request()->except(['_token', 'submit', 'user_name_id', 'id', 'name']));

        if ($parent) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_parent = new ParentDetail;
            $stu_parent->father_name = $request->father_name;
            $stu_parent->mother_name = $request->mother_name;
            $stu_parent->guardian_name = $request->guardian_name;
            $stu_parent->father_mobile_no = $request->father_mobile_no;
            $stu_parent->father_email = $request->father_email;
            $stu_parent->mother_mobile_no = $request->mother_mobile_no;
            $stu_parent->mother_email = $request->mother_email;
            $stu_parent->guardian_mobile_no = $request->guardian_mobile_no;
            $stu_parent->guardian_email = $request->guardian_email;
            $stu_parent->fathers_occupation = $request->fathers_occupation;
            $stu_parent->mothers_occupation = $request->mothers_occupation;
            $stu_parent->father_off_address = $request->father_off_address;
            $stu_parent->mother_off_address = $request->mother_off_address;
            $stu_parent->gaurdian_occupation = $request->gaurdian_occupation;
            $stu_parent->guardian_off_address = $request->guardian_off_address;
            $stu_parent->user_name_id = $request->user_name_id;
            $stu_parent->save();

            if ($stu_parent) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                dd('Error');
            }

        }

        return redirect()->route('admin.parent-details.stu_index', $student);
    }

    public function create()
    {
        abort_if(Gate::denies('parent_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.parentDetails.create');
    }

    public function store(StoreParentDetailRequest $request)
    {
        $parentDetail = ParentDetail::create($request->all());

        return redirect()->route('admin.parent-details.index');
    }

    public function edit(ParentDetail $parentDetail)
    {
        abort_if(Gate::denies('parent_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.parentDetails.edit', compact('parentDetail'));
    }

    public function update(UpdateParentDetailRequest $request, ParentDetail $parentDetail)
    {
        $parentDetail->update($request->all());

        return redirect()->route('admin.parent-details.index');
    }

    public function show(ParentDetail $parentDetail)
    {
        abort_if(Gate::denies('parent_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.parentDetails.show', compact('parentDetail'));
    }

    public function destroy(ParentDetail $parentDetail)
    {
        abort_if(Gate::denies('parent_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parentDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyParentDetailRequest $request)
    {
        $parentDetails = ParentDetail::find(request('ids'));

        foreach ($parentDetails as $parentDetail) {
            $parentDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
