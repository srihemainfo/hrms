<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackTypeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FeedbackTypeController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = FeedbackTypeModel::query()->select(sprintf('%s.*', (new FeedbackTypeModel)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'nationality_show';
                $editGate = 'nationality_edit';
                $deleteGate = 'nationality_delete';
                $crudRoutePart = 'FeedbackTypeModel';
                $viewFunct = 'viewfeedType';
                $editFunct = 'editfeedType';
                $deleteFunct = 'deletefeedType';

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
            $table->editColumn('participant', function ($row) {
                return $row->feedback_participant ? ucwords($row->feedback_participant) : '';
            });
            $table->editColumn('feedback_type', function ($row) {
                return $row->feedback_type ? ucwords($row->feedback_type) : '';
            });
            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.feedbackType.index');
    }


    public function store(Request $request)
    {
        // dd($request);
        if ($request->feedType != '' && $request->participant != '') {
            if ($request->id == '') {
                $store = FeedbackTypeModel::create([
                    'feedback_participant' => strtolower($request->participant),
                    'feedback_type' => strtolower($request->feedType)
                ]);
                return response()->json(['status' => true, 'data' => 'FeedbackType Created']);
            } else {
                $update = FeedbackTypeModel::where(['id' => $request->id])->update([
                    'feedback_participant' => strtolower($request->participant),
                    'feedback_type' => strtolower($request->feedType)
                ]);
                return response()->json(['status' => true, 'data' => 'FeedbackType Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'FeedbackType Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = FeedbackTypeModel::where(['id' => $request->id])->select('id', 'feedback_participant', 'feedback_type')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = FeedbackTypeModel::where(['id' => $request->id])->select('id', 'feedback_participant', 'feedback_type')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = FeedbackTypeModel::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'FeedbackType Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        // dd($request);
        $feed = FeedbackTypeModel::find(request('ids'));
        foreach ($feed as $n) {
            $n->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'FeedbackType Deleted Successfully']);
    }
}
