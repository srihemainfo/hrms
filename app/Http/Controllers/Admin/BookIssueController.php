<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\BookDataModal;
use App\Models\BookIssueModel;
use App\Models\BookModel;
use App\Models\BookReservationModel;
use App\Models\Student;
use App\Models\User;
use App\Models\UserAlert;
use Carbon\Carbon;
use Date;
use DateTime;
use DB;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class BookIssueController extends Controller
{

    public function index(Request $request)
    {

        // Storage::disk('public')->put('uploads/.'.'B-2'.'.png', $image);
        // File::move($image, public_path('uploads/B-1.png'));
        // $image->move(public_path('uploads'), 'B-1'.'.png');
        // dd($image);
        // return view('barcode', compact('barcode'));
        // return response($image)->header('Content-type','image/png');
        abort_if(Gate::denies('book_issue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DB::table('book_issue')
                ->whereNull('book_issue.deleted_at')
                ->leftJoin('users', 'users.id', '=', 'book_issue.user_name_id')
                ->leftJoin('book_data', 'book_data.id', '=', 'book_issue.book_data_id')
                ->leftJoin('book_details', 'book_details.id', '=', 'book_issue.book_id')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'book_issue.user_name_id')
                ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('roles.title', 'book_issue.id', 'users.name as user', 'book_details.name', 'book_data.book_code', 'book_issue.status', 'book_issue.issued_date', 'book_issue.due_date', 'book_issue.return_date')
                ->get();

            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                if ($row->status != 'Return') {
                    $viewFunct = 'viewBookIssue';
                    $deleteFunct = 'deleteBookIssue';
                    $viewGate = 'book_issue_show';
                    $deleteGate = 'book_issue_delete';
                    $crudRoutePart = 'book-issue';

                    return view(
                        'partials.ajaxTableActions',
                        compact(
                            'viewGate',
                            'deleteGate',
                            'crudRoutePart',
                            'viewFunct',
                            'deleteFunct',
                            'row'
                        )
                    );
                } else {
                    $viewFunct = 'viewBookIssue';
                    $viewGate = 'book_issue_show';
                    $crudRoutePart = 'book-issue';
                    return view(
                        'partials.ajaxTableActions',
                        compact(
                            'viewGate',
                            'crudRoutePart',
                            'viewFunct',
                            'row'
                        )
                    );
                }


            });
            $i = 0;
            $table->editColumn('sno', function ($row) use (&$i) {
                return $i += 1;
            });
            $table->editColumn('id', function ($row) {
                return $row->id ?? '';
            });
            $table->editColumn('name', function ($row) {
                return $row->user ? $row->user : '';
            });
            $table->editColumn('book_name', function ($row) {
                return $row->name ? $row->name . '(' . $row->book_code . ')' : '';
            });
            $table->editColumn('issue_date', function ($row) {
                return $row->issued_date ? $row->issued_date : '';
            });
            $table->editColumn('due_date', function ($row) {
                return $row->due_date ? $row->due_date : '';
            });
            $table->editColumn('return_date', function ($row) {
                return $row->return_date != '' ? $row->return_date : '-';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('role', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $data = [];
        $student = DB::table("users")
            ->whereNull("users.deleted_at")
            ->where('users.id', '!=', 1)
            ->pluck('users.name', 'users.id');

        $book = BookModel::pluck("name", "id");
        return view('admin.bookIssue.index', compact('student', 'book'));

    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('book_issue_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // dd($request);
        if ($request->id == '') {
            $check = BookIssueModel::where(['book_data_id' => $request->book_no, 'status' => 'On Loan'])->exists();
            if ($check) {
                return response()->json(['status' => false, 'data' => 'This Book Already Issued.']);
            } else {
                $user = User::with('roles')->find($request->user);
                $create = BookIssueModel::create([
                    'user_name_id' => $request->user,
                    'role_id' => $user->roles[0]->id,
                    'book_id' => $request->book_id,
                    'book_data_id' => $request->book_no,
                    'issued_date' => $request->issue_date,
                    'due_date' => $request->due_date,
                    'status' => 'On Loan',
                    'renew_count' => 0
                ]);

                if ($create) {
                    $update = BookDataModal::where('id', $request->book_no)->update([
                        'availability' => "No",
                        'status' => "Issued",
                    ]);
                }

                $check = BookReservationModel::where(['user_name_id' => $request->user, 'book_id' => $request->book_id, 'status' => 0])->first();
                // dd($check);
                if ($check) {
                    $check->update([
                        'status' => 1
                    ]);

                }
                return response()->json(['status' => true, 'data' => 'Book Issued to ' . $user->name]);
            }
        }
    }

    public function view(Request $request)
    {
        abort_if(Gate::denies('book_issue_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->id != '') {
            $data = DB::table('book_issue')
                ->whereNull('book_issue.deleted_at')
                ->where('book_issue.id', (int) $request->id)
                ->leftJoin('users', 'book_issue.user_name_id', '=', 'users.id')
                ->leftJoin('book_data', 'book_data.id', '=', 'book_issue.book_data_id')
                ->leftJoin('book_details', 'book_details.id', '=', 'book_issue.book_id')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('book_issue.id', 'users.name as user_name', 'roles.title', 'book_details.name as book_name', 'book_data.book_code', 'book_details.isbn', 'book_issue.status', 'book_issue.issued_date', 'book_issue.due_date', 'book_issue.return_date', 'book_issue.fine', 'book_issue.renew_count')
                ->first();
            // dd($data);
            if ($data != null) {
                return response()->json(['status' => true, 'data' => $data]);
            } else {
                return response()->json(['status' => false, 'data' => 'All the Books Are Issued.']);
            }
        }
    }
    public function edit(Request $request)
    {

    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('book_issue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = BookIssueModel::where(['id' => $request->id])->first();
            if ($data) {
                $update = BookDataModal::where('id', $data->book_data_id)->update([
                    'availability' => "Yes",
                    'status' => "Available",
                ]);
            }
            $delete = BookIssueModel::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => "Issued Book Record Is Deleted Successfully"]);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('book_issue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $BookIssue = BookIssueModel::whereIn('id', request('ids'))->get();
        foreach ($BookIssue as $r) {
            if ($r) {
                $update = BookDataModal::where('id', $r->book_data_id)->update([
                    'availability' => "Yes",
                    'status' => "Available",
                ]);
            }
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Issued Book Records Is Deleted Successfully']);
    }
    public function fetchBook(Request $request)
    {
        // dd($request->id);
        $book_image = BookModel::where('id', (int) $request->id)->select('image')->first();
        $books = BookDataModal::where(['book_id' => (int) $request->id, 'availability' => 'Yes'])->pluck('book_code', 'id');
        if (count($books) > 0) {
            return response()->json(['status' => true, 'book_image' => $book_image, 'books' => $books]);
        } else {
            return response()->json(['status' => false, 'book_image' => $book_image, 'data' => 'All the Books Are Issued.']);

        }
    }

    public function checkStudent(Request $request)
    {
        $book_count = BookIssueModel::where('user_name_id', $request->id)
            ->where('status', '!=', 'Return')
            ->count();
        $check = User::where('id', $request->id)->whereNotNull('employID')->exists();
        // dd($check);
        if ($check) {
            if ($book_count >= 0 && $book_count < 4) {
                $overdue = BookIssueModel::where(['user_name_id' => $request->id, 'status' => 'OverDue'])->count();
                if ($overdue > 0) {
                    return response()->json(['status' => false, 'data' => 'You are Already Have a OverDue']);
                } else {
                    return response()->json(['status' => true]);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'This Member Already Have Four Books']);
            }
        } else {

            if ($book_count >= 0 && $book_count < 2) {
                $overdue = BookIssueModel::where(['user_name_id' => $request->id, 'status' => 'OverDue'])->count();
                if ($overdue > 0) {
                    return response()->json(['status' => false, 'data' => 'You are Already Have OverDue']);
                } else {
                    return response()->json(['status' => true]);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'This Member Already Have Two Books']);
            }
        }

    }


    public function get_record($id)
    {
        abort_if(Gate::denies('book_issue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // dd($id);
        if ($id != '') {
            // $data = BookIssueModel::with('books:id,name,image', 'bookData:id,book_code,status', 'users:id,name')->where('id', $id)->select('user_name_id', 'book_id', 'book_data_id', 'issued_date', 'due_date', 'fine', 'return_date', 'status')->first();
            $data = DB::table('book_issue')
                ->whereNull('book_issue.deleted_at')
                ->where('book_issue.id', (int) $id)
                ->leftJoin('users', 'book_issue.user_name_id', '=', 'users.id')
                ->leftJoin('book_data', 'book_data.id', '=', 'book_issue.book_data_id')
                ->leftJoin('book_details', 'book_details.id', '=', 'book_issue.book_id')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('book_issue.id', 'users.name as user_name', 'roles.title', 'book_details.name as book_name', 'book_data.book_code', 'book_details.isbn', 'book_issue.status', 'book_issue.issued_date', 'book_issue.due_date', 'book_issue.return_date', 'book_issue.fine', 'book_issue.renew_count')
                ->first();

            // dd($data);
            if ($data != null) {
                return view('admin.bookIssue.issue_details', compact('data'));
            } else {
                return response()->json(['status' => false, 'data' => 'All the Books Are Issued.']);
            }
        }
    }

    public function updater(Request $request)
    {
        // dd($request);
        if ($request->id != '') {
            $check = BookIssueModel::where('id', (int) $request->id)->exists();
            if ($check) {
                if ($request->action == 'Return') {
                    $curret_date = (new DateTime())->format('Y-m-d');
                    $update = BookIssueModel::where('id', (int) $request->id)->first();
                    if ($update) {
                        $update->update([
                            'fine' => $request->fine,
                            'status' => $request->action,
                            'return_date' => $curret_date,
                            'remark' => $request->remark
                        ]);
                        $book_update = BookDataModal::where('id', $update->book_data_id)->update([
                            'status' => "Available",
                            'availability' => 'Yes'
                        ]);

                        $user_id = DB::table('book_reservation')
                            ->where('book_reservation.status', 0)
                            ->leftJoin('book_data', 'book_reservation.book_id', '=', 'book_data.book_id')
                            ->leftJoin('book_details', 'book_reservation.book_id', '=', 'book_details.id')
                            ->groupBy('book_reservation.user_name_id', 'book_details.name')
                            ->select('book_details.name', 'book_reservation.user_name_id', DB::raw('SUM(CASE WHEN book_data.availability = "Yes" THEN 1 ELSE 0 END) as availability_count'))
                            ->get();


                        foreach ($user_id as $key => $user) {
                            // dd('1' > 0);
                            if ($user->availability_count > 0) {
                                $userAlert = new UserAlert;
                                $userAlert->alert_text = $user->name . ' book is ready for you to pick up';
                                $userAlert->save();
                                $userAlert->users()->sync($user->user_name_id);
                                // $userAlert->alert_link = '#';
                            }
                        }

                        return response()->json(['status' => true, 'data' => 'Book Returned Successfully.']);
                    }
                } else {
                    if ($request->issue_date != '' && $request->due_date != '') {
                        $update = BookIssueModel::where('id', (int) $request->id)->whereNot('status', 'Return')->first();
                        // dd($update);
                        if ($update->renew_count < 2) {
                            $update->update([
                                'fine' => $request->fine,
                                'remark' => $request->remark,
                                'status' => $request->action,
                                'issued_date' => $request->issue_date,
                                'due_date' => $request->due_date,
                                'renew_count' => $update->renew_count + 1
                            ]);
                            return response()->json(['status' => true, 'data' => 'Book Renewed Successfully.']);

                        } else {
                            return response()->json(['status' => false, 'data' => 'This Member Already Renewed Twice.']);
                        }
                    }
                }

            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }


    public function reservation(Request $request)
    {
        if ($request->user != '' || $request->book_id != '') {
            $date = date('Y-m-d');
            $reserve = BookReservationModel::create([
                'user_name_id' => $request->user,
                'book_id' => $request->book_id,
                'reserve_date' => $date
            ]);
            return response()->json(['status' => true, 'data' => 'Book Reserved Successfully']);
        } else {
            return response()->json(['status' => false, 'data' => 'Member Name or Book Name is Empty.']);
        }
    }
    public function reserveReport(Request $request)
    {
        abort_if(Gate::denies('library_reservation_report'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DB::table('book_reservation')
                ->whereNull('book_reservation.deleted_at')
                ->leftJoin('users', 'book_reservation.user_name_id', '=', 'users.id')
                ->leftJoin('book_details', 'book_details.id', '=', 'book_reservation.book_id')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('book_reservation.id', 'book_reservation.status', 'users.name', 'roles.title', 'book_reservation.reserve_date', 'book_details.name as book_name')
                ->get();

            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');

            $i = 0;
            $table->editColumn('sno', function ($row) use (&$i) {
                return $i += 1;
            });
            $table->editColumn('id', function ($row) {
                return $row->id ?? '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('role', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('book_name', function ($row) {
                return $row->book_name ? $row->book_name : '';
            });
            $table->editColumn('reserve_date', function ($row) {
                return $row->reserve_date ? $row->reserve_date : '';
            });
            $table->editColumn('action', function ($row) {
                return $row->status == 0 ? 'Not Received' : 'Received';
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.reservation_report.index');
    }

    public function search(Request $request)
    {
        if ($request->from_date != '' || $request->to_date != '') {

            $query = DB::table('book_reservation')
                ->whereNull('book_reservation.deleted_at')
                ->whereBetween('book_reservation.reserve_date', [$request->from_date, $request->to_date])
                ->leftJoin('users', 'book_reservation.user_name_id', '=', 'users.id')
                ->leftJoin('book_details', 'book_details.id', '=', 'book_reservation.book_id')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('book_reservation.id', 'users.name', 'roles.title', 'book_reservation.reserve_date', 'book_details.name as book_name')
                ->get();
            return response()->json(['status' => true, 'data' => $query]);
        } else {
            return response()->json(['status' => false, 'data' => 'From Date or To Date is Empty.']);

        }
    }

    public function get_book_info($request)
    {
        // dd($request);
        $books = BookDataModal::where('book_code', $request)->first();
        // dd($books);
        if ($books != null) {
            // $data = BookIssueModel::with('books:id,name,image', 'bookData:id,book_code,status', 'users:id,name')->where('id', $id)->select('user_name_id', 'book_id', 'book_data_id', 'issued_date', 'due_date', 'fine', 'return_date', 'status')->first();
            $check = BookIssueModel::where('book_data_id', $books->id)
                ->where('status', '<>', 'Return')
                ->exists();

            if ($check) {
                $data = DB::table('book_issue')
                    ->whereNull('book_issue.deleted_at')
                    ->where('book_issue.book_data_id', $books->id)
                    ->whereNotIn('book_issue.status', ['Return'])
                    ->leftJoin('users', 'book_issue.user_name_id', '=', 'users.id')
                    ->leftJoin('book_data', 'book_data.id', '=', 'book_issue.book_data_id')
                    ->leftJoin('book_details', 'book_details.id', '=', 'book_issue.book_id')
                    ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                    ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->select('book_issue.id', 'users.name as user_name', 'roles.title', 'book_details.name as book_name', 'book_data.book_code', 'book_details.isbn', 'book_issue.status', 'book_issue.issued_date', 'book_issue.due_date', 'book_issue.return_date', 'book_issue.fine', 'book_issue.renew_count')
                    ->first();

                return view('admin.bookIssue.issue_details', compact('data'));
            } else {
                $book = DB::table('book_data')
                    ->whereNull('book_data.deleted_at')
                    ->where('book_data.id', $books->id)
                    ->leftJoin('book_details', 'book_details.id', '=', 'book_data.book_id')
                    ->select('book_details.id as book_id', 'book_data.id as book_data_id', 'book_details.name as book_name', 'book_data.book_code', 'book_details.isbn', 'book_details.image', 'book_data.availability')
                    ->first();

                $student = DB::table("users")
                    ->whereNull("users.deleted_at")
                    ->where('users.id', '!=', 1)
                    ->pluck('users.name', 'users.id');

                // dd($books, $student);


                return view('admin.bookIssue.qr_issue', compact('book', 'student'));
            }
        } else {
            return redirect()->route('admin.book-issue.index');
        }
    }


    public function memberWiseReport(Request $request)
    {
        abort_if(Gate::denies('library_member_wise_report'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DB::table('book_issue')
                ->whereNull('book_issue.deleted_at')
                ->leftJoin('users', 'book_issue.user_name_id', '=', 'users.id')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->groupBy('book_issue.user_name_id', 'users.name', 'roles.title')
                ->select('users.name', 'roles.title', DB::raw('SUM(book_issue.fine) as total_fine'), DB::raw('SUM(CASE WHEN book_issue.status = "OverDue" THEN 1 ELSE 0 END) as overdue'), DB::raw('COUNT(DISTINCT book_issue.book_data_id) as book_count'))
                ->get();
            // dd($query);
            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');

            $i = 0;
            $table->editColumn('sno', function ($row) use (&$i) {
                return $i += 1;
            });
            $table->editColumn('name', function ($row) {
                return $row->name ?? '';
            });
            $table->editColumn('role', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('book_count', function ($row) {
                return $row->book_count ? $row->book_count : 0;
            });
            $table->editColumn('overdue', function ($row) {
                return $row->overdue ? $row->overdue : 0;
            });
            $table->editColumn('total_fine', function ($row) {
                return $row->total_fine ? $row->total_fine : 0;
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $totalFine = DB::table('book_issue')
            ->whereNull('book_issue.deleted_at')
            ->select(DB::raw('SUM(fine) as total_fine'))
            ->first();

        return view('admin.fineReport.memberWise', compact('totalFine'));


    }
    public function departWise(Request $request)
    {
        // dd($request->who);
        abort_if(Gate::denies('library_department_wise_report'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $who = $request->who;
        if ($request->who == 'student') {
            $query = DB::table('book_issue')
                ->whereNull('book_issue.deleted_at')
                ->join('students', 'students.user_name_id', '=', 'book_issue.user_name_id')
                ->whereNull('students.deleted_at')
                ->leftJoin('book_data', 'book_data.id', '=', 'book_issue.book_data_id')
                ->whereNull('book_data.deleted_at')
                ->leftJoin('tools_courses', 'students.admitted_course', '=', 'tools_courses.short_form')
                ->groupBy('tools_courses.name')
                ->select(
                    'tools_courses.name as name',
                    DB::raw('SUM(book_issue.fine) as total_fine'),
                    DB::raw('COUNT(DISTINCT students.user_name_id) as student_count'),
                    DB::raw('SUM(CASE WHEN book_issue.status = "OverDue" THEN 1 ELSE 0 END) as overdue'),
                    DB::raw('COUNT(DISTINCT book_issue.book_data_id) as book_count'),
                    DB::raw('SUM(CASE WHEN book_issue.status = "On Loan" THEN 1 ELSE 0 END) as loaned'),
                    DB::raw('SUM(CASE WHEN book_issue.status = "Return" THEN 1 ELSE 0 END) as available')
                )->get()->toArray();

            // dd($query);
        } elseif ($request->who == 'staff') {
            $query = DB::table('book_issue')
                ->whereNull('book_issue.deleted_at')
                ->join('users', 'book_issue.user_name_id', '=', 'users.id')
                ->whereNotNull('users.dept')
                ->whereNull('users.deleted_at')
                ->leftJoin('book_data', 'book_data.id', '=', 'book_issue.book_data_id')
                ->whereNull('book_data.deleted_at')
                ->groupBy('users.dept')
                ->select(
                    'users.dept as name',
                    DB::raw('SUM(book_issue.fine) as total_fine'),
                    DB::raw('COUNT(DISTINCT users.id) as student_count'),
                    DB::raw('SUM(CASE WHEN book_issue.status = "OverDue" THEN 1 ELSE 0 END) as overdue'),
                    DB::raw('COUNT(DISTINCT book_issue.book_data_id) as book_count'),
                    DB::raw('SUM(CASE WHEN book_issue.status = "On Loan" THEN 1 ELSE 0 END) as loaned'),
                    DB::raw('SUM(CASE WHEN book_issue.status = "Return" THEN 1 ELSE 0 END) as available')
                )->get()->toArray();
        }


        $totalFine = DB::table('book_issue')
            ->whereNull('book_issue.deleted_at')
            ->select(DB::raw('SUM(fine) as total_fine'))
            ->first();

        return view('admin.fineReport.departWise', compact('totalFine', 'who', 'query'));
    }
    public function inventory(Request $request)
    {
        abort_if(Gate::denies('library_inventory_report'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DB::table('book_details')
                ->whereNull('book_details.deleted_at')
                ->leftJoin('book_data', 'book_data.book_id', '=', 'book_details.id')
                ->leftJoin('book_issue', 'book_issue.book_data_id', '=', 'book_data.id')
                ->select(
                    'book_details.id',
                    'book_details.name',
                    'book_details.isbn',
                    'book_details.author',
                    'book_details.publication',
                    'book_details.book_count',
                    DB::raw('SUM(CASE WHEN book_data.availability = "No" THEN 1 ELSE 0 END) as loaned'),
                    DB::raw('SUM(CASE WHEN book_data.availability = "Yes" THEN 1 ELSE 0 END) as available')
                )
                ->groupBy('book_details.id')
                ->get();

            // dd($query);
            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');

            $i = 0;
            $table->editColumn('sno', function ($row) use (&$i) {
                return $i += 1;
            });
            $table->editColumn('name', function ($row) {
                return $row->name != null ? $row->name : '-';
            });
            $table->editColumn('author', function ($row) {
                return $row->author ? $row->author : '-';
            });
            $table->editColumn('isbn', function ($row) {
                return $row->book_count ? $row->isbn : '-';
            });
            $table->editColumn('publication', function ($row) {
                return $row->publication ? $row->publication : '-';
            });
            $table->editColumn('book_count', function ($row) {
                return $row->book_count ? $row->book_count : 0;
            });
            $table->editColumn('available', function ($row) {
                return $row->available ? $row->available : 0;
            });
            $table->editColumn('loaned', function ($row) {
                return $row->loaned ? $row->loaned : 0;
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.fineReport.inventory');
    }

}
