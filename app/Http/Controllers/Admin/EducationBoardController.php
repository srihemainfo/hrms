<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEducationBoardRequest;
use App\Http\Requests\StoreEducationBoardRequest;
use App\Http\Requests\UpdateEducationBoardRequest;
use App\Models\EducationBoard;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EducationBoardController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = EducationBoard::query()->select(sprintf('%s.*', (new EducationBoard)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewEdu';
                $editFunct = 'editEdu';
                $deleteFunct = 'deleteEdu';
                $viewGate      = 'education_board_show';
                $editGate      = 'education_board_edit';
                $deleteGate    = 'education_board_delete';
                $crudRoutePart = 'education-boards';

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
            $table->editColumn('edu', function ($row) {
                return $row->education_board ? $row->education_board : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.educationBoards.index');
    }

    public function create()
    {
        abort_if(Gate::denies('community_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = EducationBoard::create([
                    'education_board' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'EducationBoard Created']);
            } else {
                $update = EducationBoard::where(['id' => $request->id])->update([
                    'education_board' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'EducationBoard Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'EducationBoard Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = EducationBoard::where(['id' => $request->id])->select('id', 'education_board')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = EducationBoard::where(['id' => $request->id])->select('id', 'education_board')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, EducationBoard $EducationBoard)
    {
        $EducationBoard->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(EducationBoard $EducationBoard)
    {
        abort_if(Gate::denies('community_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.motherTongues.index', compact('EducationBoard'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = EducationBoard::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'EducationBoard Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $education = EducationBoard::find(request('ids'));

        foreach ($education as $edu) {
            $edu->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'EducationBoard Deleted Successfully']);
    }
}
