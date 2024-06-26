<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySubjectRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\CourseEnrollMaster;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\SubjectCategory;
use App\Models\SubjectRegistration;
use App\Models\SubjectType;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\ToolssyllabusYear;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        // dd($request);
        abort_if(Gate::denies('subject_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Subject::with(['regulation', 'department', 'semester', 'course', 'subject_type', 'subject_category'])->select(sprintf('%s.*', (new Subject)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'subject_show';
                $editGate = 'subject_edit';
                $deleteGate = 'subject_delete';
                $crudRoutePart = 'subjects';

                return view(
                    'partials.datatablesActions',
                    compact(
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
            $table->addColumn('syllabus_year', function ($row) {
                return $row->syllabus ? $row->syllabus->year : '';
            });

            $table->addColumn('regulation', function ($row) {
                return $row->regulation ? $row->regulation->name : '';
            });

            $table->addColumn('subject_type', function ($row) {
                return $row->subject_type ? $row->subject_type->name : '';
            });

            $table->addColumn('subject_category', function ($row) {
                return $row->subject_category ? $row->subject_category->name : '';
            });

            $table->addColumn('department', function ($row) {
                return $row->department ? $row->department->name : '';
            });

            $table->addColumn('course', function ($row) {
                return $row->course ? $row->course->short_form : '';
            });

            $table->addColumn('semester', function ($row) {
                return $row->semester ? $row->semester->semester : '';
            });

            $table->editColumn('subject_code', function ($row) {
                return $row->subject_code ? $row->subject_code : '';
            });

            $table->editColumn('lecture', function ($row) {
                return $row->lecture ? $row->lecture : '';
            });
            $table->editColumn('tutorial', function ($row) {
                return $row->tutorial ? $row->tutorial : '';
            });
            $table->editColumn('practical', function ($row) {
                return $row->practical ? $row->practical : '';
            });
            $table->editColumn('credits', function ($row) {
                return $row->credits ? $row->credits : '';
            });
            $table->editColumn('contact_periods', function ($row) {
                return $row->contact_periods ? $row->contact_periods : '';
            });
            // $table->editColumn('status', function ($row) {
            //     // dd($row->status);
            //     // return $row->status == '0' ? '0' : '1';
            //     if ($row->status == '0') {
            //         return '0';
            //     } elseif ($row->status == '1') {
            //         return '1';
            //     } elseif ($row->status == '2') {
            //         return '2';
            //     }

            // });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'syllabus']);

            return $table->make(true);
        }

        if ($request->input('department') != '' || $request->input('course') != '' || $request->input('semester') != '') {
            $subject = Subject::where(['department_id' => $request->input('department'), 'course_id' => $request->input('course'), 'semester_id' => $request->input('semester')])->get();

            return response()->json(['data' => $subject]);

        }

        $dept = ToolsDepartment::pluck('name', 'id');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::pluck('name', 'id');
        $regulation = ToolssyllabusYear::pluck('name', 'id');

        return view('admin.subjects.index', compact('dept', 'courses', 'semester', 'Subjects', 'regulation'));
    }

    public function search(Request $request)
    {
        // dd($request);
        if ($request->input('department') != '' && $request->input('course') != '' && $request->input('regulation') != '') {
            if ($request->input('semester') != null) {
                $subjectQuery = Subject::where([
                    'department_id' => $request->input('department'),
                    'regulation_id' => $request->input('regulation'),
                    'course_id' => $request->input('course'),
                    'semester_id' => $request->input('semester'),
                ])->with(['regulation', 'department', 'semester', 'course', 'subject_type', 'subject_category']);
            } else {
                $subjectQuery = Subject::where([
                    'department_id' => $request->input('department'),
                    'course_id' => $request->input('course'),
                    'regulation_id' => $request->input('regulation'),
                ])->with(['regulation', 'department', 'semester', 'course', 'subject_type', 'subject_category']);
            }
            $subject = $subjectQuery->get();
            // dd($subject);

            $table = DataTables::of($subject);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'subject_show';
                $editGate = 'subject_edit';
                $deleteGate = 'subject_delete';
                $crudRoutePart = 'subjects';

                return view(
                    'partials.datatablesActions',
                    compact(
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
            $table->addColumn('syllabus_year', function ($row) {
                return $row->syllabus ? $row->syllabus->year : '';
            });

            $table->addColumn('regulation', function ($row) {
                return $row->regulation ? $row->regulation->name : '';
            });

            $table->addColumn('subject_type', function ($row) {
                return $row->subject_type ? $row->subject_type->name : '';
            });

            $table->addColumn('subject_category', function ($row) {
                return $row->subject_category ? $row->subject_category->name : '';
            });

            $table->addColumn('department', function ($row) {
                return $row->department ? $row->department->name : '';
            });

            $table->addColumn('course', function ($row) {
                return $row->course ? $row->course->short_form : '';
            });

            $table->addColumn('semester', function ($row) {
                return $row->semester ? $row->semester->semester : '';
            });

            $table->editColumn('subject_code', function ($row) {
                return $row->subject_code ? $row->subject_code : '';
            });

            $table->editColumn('lecture', function ($row) {
                return $row->lecture ? $row->lecture : '';
            });
            $table->editColumn('tutorial', function ($row) {
                return $row->tutorial ? $row->tutorial : '';
            });
            $table->editColumn('practical', function ($row) {
                return $row->practical ? $row->practical : '';
            });
            $table->editColumn('credits', function ($row) {
                return $row->credits ? $row->credits : '';
            });
            $table->editColumn('contact_periods', function ($row) {
                return $row->contact_periods ? $row->contact_periods : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
    }

    public function create()
    {
        abort_if(Gate::denies('subject_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $get_dept = ToolsDepartment::get();

        $get_course = ToolsCourse::get();
        $depts = [];
        foreach ($get_dept as $data) {
            $depts[$data->id] = [];
            foreach ($get_course as $course) {
                //   dd($course);
                if ($data->id != 5) {
                    if ($data->id == $course->department_id) {
                        array_push($depts[$data->id], $course);
                    }
                } else {

                    array_push($depts[$data->id], $course);

                }

            }
        }

        $department = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');

        $course = ToolsCourse::pluck('name', 'id')->prepend('Select Course', '');

        $regulation = ToolssyllabusYear::pluck('name', 'id')->prepend('Select Regulation', '');

        $semester = Semester::pluck('semester', 'id')->prepend('Select Semester', '');

        $subject_type = SubjectType::pluck('name', 'id')->prepend('Select Subject Type', '');

        $subject_cat = SubjectCategory::pluck('name', 'id')->prepend('Select Subject Category', '');
        // if (auth()->user()->roles[0]->id == 14) {
        //     $User_deptment = TeachingStaff::where('user_name_id', auth()->user()->id)->first();
        //     if ($User_deptment) {
        //         // $User_dept =
        //         $User_deptment->Dept;
        //         $department->name = $User_deptment->Dept;
        // $department = ToolsDepartment::where('name',$User_deptment->Dept)->pluck('name', 'id')->prepend('Select Department', '');

        //     } else {
        //         // $User_dept='';
        //     }
        // } else {
        //     // $User_dept = '';

        // }
        // dd($department);

        return view('admin.subjects.create', compact('regulation', 'department', 'semester', 'course', 'depts', 'subject_type', 'subject_cat'));
    }

    public function store(StoreSubjectRequest $request)
    {
        // dd($request);
        if ($request->semester_id == 1 || $request->semester_id == 2) {
            // 5 means S & H department
            if ($request->department_id != 5) {
                return back()->withErrors(['department' => 'You Can\'t Choose this Department for the selected Semester.'])->withInput();
            }
        }

        $get_sub_code = Subject::where(['regulation_id' => $request->regulation_id, 'department_id' => $request->department_id, 'course_id' => $request->course_id, 'subject_code' => $request->subject_code])->get();

        if (count($get_sub_code) > 0) {
            return back()->withErrors(['subject_code' => 'The Subject Code Already Exist.'])->withInput();

        }

        $subject = Subject::create([
            'regulation_id' => $request->regulation_id,
            'department_id' => $request->department_id,
            "course_id" => $request->course_id,
            "semester_id" => $request->semester_id,
            "subject_type_id" => $request->subject_type_id,
            "subject_cat_id" => $request->subject_cat_id,
            "lecture" => $request->lecture,
            "tutorial" => $request->tutorial,
            "practical" => $request->practical,
            "contact_periods" => $request->contact_periods,
            "credits" => $request->credits,
            "subject_code" => $request->subject_code,
            "name" => $request->name,
            "honors_degree" => isset($request->honors_degree) ? 1 : 0,
        ]);

        return redirect()->route('admin.subjects.index');
    }

    public function edit(Subject $subject)
    {
        abort_if(Gate::denies('subject_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $get_dept = ToolsDepartment::get();

        $get_course = ToolsCourse::get();
        $depts = [];
        foreach ($get_dept as $data) {
            $depts[$data->id] = [];
            foreach ($get_course as $course) {
                //   dd($course);
                if ($data->id != 5) {
                    if ($data->id == $course->department_id) {
                        array_push($depts[$data->id], $course);
                    }
                } else {

                    array_push($depts[$data->id], $course);

                }

            }
        }

        $department = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');

        $course = ToolsCourse::pluck('name', 'id')->prepend('Select Course', '');

        $regulation = ToolssyllabusYear::pluck('name', 'id')->prepend('Select Regulation', '');

        $semester = Semester::pluck('semester', 'id')->prepend('Select Semester', '');

        $subject_type = SubjectType::pluck('name', 'id')->prepend('Select Subject Type', '');

        $subject_cat = SubjectCategory::pluck('name', 'id')->prepend('Select Subject Category', '');

        $subject->load('regulation', 'department', 'semester', 'course', 'subject_type', 'subject_category');

        return view('admin.subjects.edit', compact('subject', 'regulation', 'department', 'semester', 'course', 'depts', 'subject_type', 'subject_cat'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {

        if ($request->semester_id == 1 || $request->semester_id == 2) {
            // 5 means S & H department
            if ($request->department_id != 5) {
                return back()->withErrors(['department' => 'You Can\'t Choose this Department for the selected Semester.'])->withInput();
            }
        }
        // dd($subject);
        $get_sub_code = Subject::where(['regulation_id' => $request->regulation_id, 'department_id' => $request->department_id, 'course_id' => $request->course_id, 'subject_code' => $request->subject_code])->where('id', '!=', $subject->id)->get();
// dd($get_sub_code);
        if (count($get_sub_code) > 0) {
            return back()->withErrors(['subject_code' => 'The Subject Code Already Exist.'])->withInput();

        }
        if (isset($subject->id)) {
            $subject = Subject::where(['id' => $subject->id])->update([
                'regulation_id' => $request->regulation_id,
                'department_id' => $request->department_id,
                "course_id" => $request->course_id,
                "semester_id" => $request->semester_id,
                "subject_type_id" => $request->subject_type_id,
                "subject_cat_id" => $request->subject_cat_id,
                "lecture" => $request->lecture,
                "tutorial" => $request->tutorial,
                "practical" => $request->practical,
                "contact_periods" => $request->contact_periods,
                "credits" => $request->credits,
                "subject_code" => $request->subject_code,
                "name" => $request->name,
                "honors_degree" => isset($request->honors_degree) ? 1 : 0,
            ]);
        }

        return redirect()->route('admin.subjects.index');
    }

    public function show(Subject $subject)
    {
        abort_if(Gate::denies('subject_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subject->load('regulation', 'department', 'semester', 'course', 'subject_type', 'subject_category');

        return view('admin.subjects.show', compact('subject'));
    }
    public function statusUpdate(UpdateSubjectRequest $request, Subject $subject)
    {

        if ($request) {
            subject::where('id', $request->data['id'])->update(['status' => $request->data['status'], 'rejected_reason' => $request->data['status'] != '' ? $request->data['status'] : '']);

            return response()->json(['status' => 'ok']);

        }

    }
    public function destroy(Subject $subject)
    {
        abort_if(Gate::denies('subject_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subject->delete();

        return back();
    }

    public function massDestroy(MassDestroySubjectRequest $request)
    {
        $subjects = Subject::find(request('ids'));

        foreach ($subjects as $subject) {
            $subject->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function get_sub_categories(Request $request)
    {
        if ($request->data != '') {
            $get_cat = SubjectCategory::where(['regulation_id' => $request->data])->get();
            $get_type = SubjectType::where(['regulation_id' => $request->data])->get();
            return response()->json(['cat' => $get_cat, 'type' => $get_type]);
        }
    }

    public function get_course(Request $request)
    {
        if ($request->dept != '') {
            if ($request->dept == 5) {
                $get_course = ToolsCourse::get();
            } else {
                $get_course = ToolsCourse::where(['department_id' => $request->dept])->get();
            }
            return response()->json(['course' => $get_course]);
        }
    }

    // public function honorsSubject(Request $request)
    // {

    //     // abort_if(Gate::denies('subject_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     if ($request->ajax()) {
    //         $query = Subject::with(['regulation', 'department', 'semester', 'course', 'subject_type', 'subject_category'])->select(sprintf('%s.*', (new Subject)->table));
    //         $table = Datatables::of($query);

    //         $table->addColumn('actions', '&nbsp;');

    //         $table->editColumn('actions', function ($row) {
    //             $viewGate = '';
    //             $editGate = '';
    //             $deleteGate = 'subject_delete';
    //             $crudRoutePart = 'subjects';

    //             return view(
    //                 'partials.datatablesActions',
    //                 compact(
    //                     'viewGate',
    //                     'editGate',
    //                     'deleteGate',
    //                     'crudRoutePart',
    //                     'row'
    //                 )
    //             );
    //         });
    //         $i = 0;
    //         $table->editColumn('id', function ($row) use (&$i) {
    //             $i++;
    //             return $i;
    //         });
    //         $table->addColumn('syllabus_year', function ($row) {
    //             return $row->syllabus ? $row->syllabus->year : '';
    //         });

    //         $table->addColumn('regulation', function ($row) {
    //             return $row->regulation ? $row->regulation->name : '';
    //         });

    //         $table->addColumn('subject_type', function ($row) {
    //             return $row->subject_type ? $row->subject_type->name : '';
    //         });

    //         $table->addColumn('subject_category', function ($row) {
    //             return $row->subject_category ? $row->subject_category->name : '';
    //         });

    //         $table->addColumn('department', function ($row) {
    //             return $row->department ? $row->department->name : '';
    //         });

    //         $table->addColumn('course', function ($row) {
    //             return $row->course ? $row->course->short_form : '';
    //         });

    //         $table->addColumn('semester', function ($row) {
    //             return $row->semester ? $row->semester->semester : '';
    //         });

    //         $table->editColumn('subject_code', function ($row) {
    //             return $row->subject_code ? $row->subject_code : '';
    //         });

    //         $table->editColumn('lecture', function ($row) {
    //             return $row->lecture ? $row->lecture : '';
    //         });
    //         $table->editColumn('tutorial', function ($row) {
    //             return $row->tutorial ? $row->tutorial : '';
    //         });
    //         $table->editColumn('practical', function ($row) {
    //             return $row->practical ? $row->practical : '';
    //         });
    //         $table->editColumn('credits', function ($row) {
    //             return $row->credits ? $row->credits : '';
    //         });
    //         $table->editColumn('contact_periods', function ($row) {
    //             return $row->contact_periods ? $row->contact_periods : '';
    //         });

    //         $table->editColumn('name', function ($row) {
    //             return $row->name ? $row->name : '';
    //         });

    //         $table->rawColumns(['actions']);

    //         return $table->make(true);
    //     }

    //     if ($request->input('department') != '' || $request->input('course') != '' || $request->input('semester') != '') {
    //         $subject = Subject::where(['department_id' => $request->input('department'), 'course_id' => $request->input('course'), 'semester_id' => $request->input('semester')])->get();

    //         return response()->json(['data' => $subject]);

    //     }

    //     $departments = ToolsDepartment::pluck('name', 'id');
    //     $courses = ToolsCourse::pluck('short_form', 'id');
    //     $semester = Semester::pluck('semester', 'id');
    //     $subjects = Subject::pluck('name', 'id');
    //     $regulation = ToolssyllabusYear::pluck('name', 'id');

    //     return view('admin.honorsDegree.index', compact('departments', 'courses', 'semester', 'subjects', 'regulation'));
    // }

    // public function honorSearch(Request $request)
    // {
    //     if ($request->input('department') != '' && $request->input('course') != '' && $request->input('regulation') != '') {
    //         if ($request->input('semester') != null) {
    //             $subjectQuery = Subject::where([
    //                 'department_id' => $request->input('department'),
    //                 'regulation_id' => $request->input('regulation'),
    //                 'course_id' => $request->input('course'),
    //                 'semester_id' => $request->input('semester'),
    //                 'honors_degree' => 1,
    //             ])->with(['regulation', 'department', 'semester', 'course', 'subject_type', 'subject_category']);
    //         } else {
    //             $subjectQuery = Subject::where([
    //                 'department_id' => $request->input('department'),
    //                 'course_id' => $request->input('course'),
    //                 'regulation_id' => $request->input('regulation'),
    //                 'honors_degree' => 1,
    //             ])->with(['regulation', 'department', 'semester', 'course', 'subject_type', 'subject_category']);
    //         }
    //         $subject = $subjectQuery->get();

    //         $table = DataTables::of($subject);

    //         $table->addColumn('actions', '&nbsp;');

    //         $table->editColumn('actions', function ($row) {
    //             $viewGate = '';
    //             $editGate = '';
    //             $deleteGate = 'subject_delete';
    //             $crudRoutePart = 'subjects';

    //             return view(
    //                 'partials.datatablesActions',
    //                 compact(
    //                     'viewGate',
    //                     'editGate',
    //                     'deleteGate',
    //                     'crudRoutePart',
    //                     'row'
    //                 )
    //             );
    //         });

    //         $i = 0;
    //         $table->editColumn('id', function ($row) use (&$i) {
    //             $i++;
    //             return $i;
    //         });
    //         $table->addColumn('syllabus_year', function ($row) {
    //             return $row->syllabus ? $row->syllabus->year : '';
    //         });

    //         $table->addColumn('regulation', function ($row) {
    //             return $row->regulation ? $row->regulation->name : '';
    //         });

    //         $table->addColumn('subject_type', function ($row) {
    //             return $row->subject_type ? $row->subject_type->name : '';
    //         });

    //         $table->addColumn('subject_category', function ($row) {
    //             return $row->subject_category ? $row->subject_category->name : '';
    //         });

    //         $table->addColumn('department', function ($row) {
    //             return $row->department ? $row->department->name : '';
    //         });

    //         $table->addColumn('course', function ($row) {
    //             return $row->course ? $row->course->short_form : '';
    //         });

    //         $table->addColumn('semester', function ($row) {
    //             return $row->semester ? $row->semester->semester : '';
    //         });

    //         $table->editColumn('subject_code', function ($row) {
    //             return $row->subject_code ? $row->subject_code : '';
    //         });

    //         $table->editColumn('lecture', function ($row) {
    //             return $row->lecture ? $row->lecture : '';
    //         });
    //         $table->editColumn('tutorial', function ($row) {
    //             return $row->tutorial ? $row->tutorial : '';
    //         });
    //         $table->editColumn('practical', function ($row) {
    //             return $row->practical ? $row->practical : '';
    //         });
    //         $table->editColumn('credits', function ($row) {
    //             return $row->credits ? $row->credits : '';
    //         });
    //         $table->editColumn('contact_periods', function ($row) {
    //             return $row->contact_periods ? $row->contact_periods : '';
    //         });
    //         $table->editColumn('name', function ($row) {
    //             return $row->name ? $row->name : '';
    //         });

    //         $table->rawColumns(['actions']);

    //         return $table->make(true);
    //     }
    // }

    public function honorsSubjectReport(Request $request)
    {

        if ($request->ajax()) {
            $currentClasses = Session::get('currentClasses');

            $getData = SubjectRegistration::where(['category' => 'Honors Degree', 'status' => 2])->whereIn('enroll_master', $currentClasses)->selectRaw('enroll_master, COUNT(user_name_id) as students')->groupBy('enroll_master')->get();

            if (count($getData) > 0) {
                foreach ($getData as $data) {
                    $getEnroll = CourseEnrollMaster::where(['id' => $data->enroll_master])->select('enroll_master_number')->first();
                    if ($getEnroll != '') {
                        $explode = explode('/', $getEnroll->enroll_master_number);
                        $getCourse = ToolsCourse::where('name', 'LIKE', $explode[1])->select('short_form')->first();
                        $data->class = $getCourse->short_form . ' / ' . $explode[3] . ' / ' . $explode[4];
                    } else {
                        $data->class = '';
                    }
                    $data->id = $data->enroll_master;
                }
            }

            $table = Datatables::of($getData);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'honor_subjects_view';
                $editGate = '';
                $deleteGate = '';
                $crudRoutePart = 'honor-subjects';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });

            $table->addColumn('class', function ($row) {
                return $row->class ? $row->class : '';
            });
            $table->editColumn('students', function ($row) {
                return $row->students ? $row->students : '';
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }
        $courses = ToolsCourse::pluck('short_form', 'id');
        $semesters = Semester::pluck('semester', 'id');

        return view('admin.honorsDegree.index', compact('courses', 'semesters'));
    }

    public function honorReportSearch(Request $request)
    {
        if ($request->input('course') != '') {

            $currentClasses = Session::get('currentClasses');

            $getData = SubjectRegistration::where(['status' => 2, 'category' => 'Honors Degree'])->whereIn('enroll_master', $currentClasses)->selectRaw('enroll_master, COUNT(user_name_id) as students')->groupBy('enroll_master')->get();

            if (count($getData) > 0) {
                foreach ($getData as $data) {
                    $data->class = null;
                    $data->id = null;
                    $getEnroll = CourseEnrollMaster::where(['id' => $data->enroll_master])->select('enroll_master_number')->first();
                    if ($getEnroll != '') {
                        $explode = explode('/', $getEnroll->enroll_master_number);
                        if (isset($request->semester) && $request->semester != '') {
                            if ($explode[3] == $request->semester) {
                                $getCourse = ToolsCourse::where('name', 'LIKE', $explode[1])->where(['id' => $request->input('course')])->select('short_form')->first();
                                if ($getCourse != '') {
                                    $data->class = $getCourse->short_form . ' / ' . $explode[3] . ' / ' . $explode[4];
                                    $data->id = $data->enroll_master;
                                }
                            }
                        } else {
                            $getCourse = ToolsCourse::where('name', 'LIKE', $explode[1])->where(['id' => $request->input('course')])->select('short_form')->first();
                            if ($getCourse != '') {
                                $data->class = $getCourse->short_form . ' / ' . $explode[3] . ' / ' . $explode[4];
                                $data->id = $data->enroll_master;
                            }
                        }

                    }

                }
            }

            $table = Datatables::of($getData);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'honor_subjects_view';
                $editGate = '';
                $deleteGate = '';
                $crudRoutePart = 'honor-subjects';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });

            $table->addColumn('class', function ($row) {
                return $row->class ? $row->class : '';
            });

            $table->editColumn('students', function ($row) {
                return $row->students ? $row->students : '';
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }
    }
    public function honorStudentsList(Request $request)
    {
        $class = null;
        $currentClasses = Session::get('currentClasses');
        $getData = SubjectRegistration::with('students', 'subjects')->where(['enroll_master' => $request->class, 'category' => 'Honors Degree', 'status' => 2])->select('subject_id', 'user_name_id')->groupBy('subject_id', 'user_name_id')->get();
        $getEnroll = CourseEnrollMaster::where(['id' => $request->class])->select('enroll_master_number')->first();
        if ($getEnroll != '') {
            $explode = explode('/', $getEnroll->enroll_master_number);
            $getCourse = ToolsCourse::where('name', 'LIKE', $explode[1])->select('short_form')->first();
            $class = $getCourse->short_form . ' / ' . $explode[3] . ' / ' . $explode[4];
        } else {
            return back()->with('error', 'Class Not Found');
        }

        return view('admin.honorsDegree.viewStudents', compact('class', 'getData'));
    }

}
