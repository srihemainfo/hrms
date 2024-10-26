<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = Announcement::select(sprintf('%s.*', (new Announcement)->table))
                ->where('status', 0);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'Announcement_show';
                $editGate = 'Announcement_edit';
                $deleteGate = 'Announcement_delete';
                $editFunct = 'editAnnouncement';
                $viewFunct = 'viewAnnouncement';
                $deleteFunct = 'deleteAnnouncement';
                $crudRoutePart = 'Announcement';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'editFunct',
                    'viewFunct',
                    'deleteFunct',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('announcement', function ($row) {
                return $row->announcement ? $row->announcement : '';
            });

            $table->editColumn('created_by', function ($row) {
                return $row->created_by ? $row->created_by : '';
            });

            $table->editColumn('create_date', function ($row) {
                return $row->create_date ? $row->create_date : '';
            });

            $table->editColumn('end_date', function ($row) {
                return $row->end_date ? $row->end_date : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.announcement.index');
    }

    public function store(Request $request)
    {

        if (isset($request->announcement)) {
            if ($request->id == '') {
                $count = Announcement::where(['announcement' => $request->announcement])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Announcement Already Exist.']);
                } else {
                    $store = Announcement::create([
                        'announcement' => $request->announcement,
                        'end_date' => $request->end_date,
                        'create_date' => now()->format('Y-m-d'),
                        'created_by' => auth()->user()->name,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Announcement Created']);
            } else {
                $count = Announcement::whereNotIn('id', [$request->id])->where(['announcement' => $request->announcement])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Announcement Already Exist.']);
                } else {
                    $update = Announcement::where(['id' => $request->id])->update([
                        'announcement' => $request->announcement,
                        'end_date' => $request->end_date,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Announcement Updated Successfully!']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Announcement Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Announcement::where(['id' => $request->id])->select('id', 'announcement', 'end_date')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Announcement::where(['id' => $request->id])->select('id', 'announcement', 'end_date')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Announcement::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Announcement Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $sections = Announcement::find(request('ids'));

        foreach ($sections as $section) {
            $section->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Announcement Deleted Successfully']);
    }

}
