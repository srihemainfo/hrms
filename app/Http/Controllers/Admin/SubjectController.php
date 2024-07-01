<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\Subject;
use App\Models\SubjectCategory;
use App\Models\SubjectType;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\ToolssyllabusYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    use CsvImportTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Subject::query()->select(sprintf('%s.*', (new Subject)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewSubject';
                $editFunct = 'editSubject';
                $deleteFunct = 'deleteSubject';
                $viewGate = 'subject_show';
                $editGate = 'subject_edit';
                $deleteGate = 'subject_delete';
                $crudRoutePart = 'subjects';

                return view(
                    'partials.ajaxTableActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'viewFunct',
                        'editFunct',
                        'deleteFunct',
                        'row'
                    )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('regulation', function ($row) {
                $reg = ToolssyllabusYear::where('id', $row->regulation_id)->select('name')->first();
                return $reg->name != 0 ? $reg->name : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->subject_code ? $row->subject_code : '';
            });
            $table->editColumn('subject', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('credit', function ($row) {
                return $row->credits ? $row->credits : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $regulation = ToolssyllabusYear::pluck('name', 'id');
        $dept = ToolsDepartment::pluck('name', 'id');
        $sub_type = SubjectType::pluck('name', 'id');
        $sub_cat = SubjectCategory::pluck('name', 'id');
        // dd($sub_type, $sub_cat);
        return view('admin.subjects.index', compact('regulation', 'dept', 'sub_type', 'sub_cat'));
    }

    public function create()
    {

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->subject)) {
            if ($request->id == '') {

                $store = Subject::create([
                    'regulation_id' => $request->regulation,
                    'subject_code' => strtoupper($request->subject_code),
                    'name' => $request->subject,
                    'department_id' => $request->dept,
                    'course_id' => $request->course,
                    'semester_id' => $request->sem,
                    'subject_type_id' => $request->sub_type,
                    'subject_cat_id' => $request->sub_type,
                    'lecture' => $request->lecture,
                    'tutorial' => $request->tutorial,
                    'practical' => $request->practical,
                    'contact_periods' => $request->contact_periods,
                    'credits' => strtoupper($request->credit),
                ]);
                return response()->json(['status' => true, 'data' => 'Subject Created']);
            } else if (isset($request->subject)) {
                $check = Subject::whereNotIn('id', [$request->id])->where('subject_code', strtoupper($request->subject_code))->exists();
                // dd($check);
                if ($check) {
                    return response()->json(['status' => false, 'data' => 'Subject Code Already Exists.']);
                } else {
                    $update = Subject::where(['id' => $request->id])->update([
                        'regulation_id' => $request->regulation,
                        'subject_code' => strtoupper($request->subject_code),
                        'name' => $request->subject,
                        'department_id' => $request->dept,
                        'course_id' => $request->course,
                        'semester_id' => $request->sem,
                        'subject_type_id' => $request->sub_type,
                        'subject_cat_id' => $request->sub_type,
                        'lecture' => $request->lecture,
                        'tutorial' => $request->tutorial,
                        'practical' => $request->practical,
                        'contact_periods' => $request->contact_periods,
                        'credits' => strtoupper($request->credit),
                    ]);
                    return response()->json(['status' => true, 'data' => 'Subject Updated']);
                }
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Subject Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Subject::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Subject::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, Subject $Subject)
    {
        $Subject->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(Subject $Subject)
    {
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Subject::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Subject Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $Subject = Subject::find(request('ids'));

        foreach ($Subject as $s) {
            $s->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Subject Deleted Successfully']);
    }

    public function get_course(Request $request)
    {
        if ($request->dept != '') {
            if ($request->dept == 5) {
                $get_course = ToolsCourse::get();
            } else {
                $get_course = ToolsCourse::where(['department_id' => $request->dept])->get();
            }
            return response()->json(['status' => true, 'course' => $get_course, 'data' => $get_course]);
        } else {
            return response()->json(['status' => false, 'data' => 'Course Not Available.']);
        }
    }
}
