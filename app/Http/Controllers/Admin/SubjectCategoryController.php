<?php

namespace App\Http\Controllers\Admin;
use Gate;
use Illuminate\Http\Request;
use App\Models\SubjectCategory;
use App\Models\ToolssyllabusYear;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroySubjectCategoryRequest;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubjectCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query= DB::table('subject_category')
                    ->leftJoin('toolssyllabus_years', 'toolssyllabus_years.id','=', 'subject_category.regulation_id')
                    ->whereNull('subject_category.deleted_at')
                    ->select('subject_category.name', 'toolssyllabus_years.name as regulation_id', 'subject_category.id')
                    ->get();
                    // dd($query);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewSub_cat';
                $editFunct = 'editSub_cat';
                $deleteFunct = 'deleteSub_cat';
                $viewGate      = 'subject_category_show';
                $editGate      = 'subject_category_edit';
                $deleteGate    = 'subject_category_delete';
                $crudRoutePart = 'subject_category';

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
            $table->editColumn('regulation', function ($row) {
                return $row->regulation_id ? $row->regulation_id : '';
            });
            $table->editColumn('sub_cat', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $reg= ToolssyllabusYear::pluck('name', 'id');
        return view('admin.subjectCategory.index', compact('reg'));
    }

    public function create()
    {

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = SubjectCategory::create([
                    'regulation_id' => $request->regulation,
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'SubjectCategory Created']);
            } else {
                $update = SubjectCategory::where(['id' => $request->id])->update([
                    'regulation_id' => $request->regulation,
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'SubjectCategory Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'SubjectCategory Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = SubjectCategory::where(['id' => $request->id])->select('id', 'regulation_id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = SubjectCategory::where(['id' => $request->id])->select('id', 'regulation_id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, SubjectCategory $SubjectCategory)
    {
        $SubjectCategory->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(SubjectCategory $SubjectCategory)
    {
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = SubjectCategory::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'SubjectCategory Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $SubjectCategory = SubjectCategory::find(request('ids'));

        foreach ($SubjectCategory as $g) {
            $g->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'SubjectCategories are Deleted Successfully']);
    }
}
