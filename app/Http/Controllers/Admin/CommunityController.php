<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCommunityRequest;
use App\Http\Requests\StoreCommunityRequest;
use App\Http\Requests\UpdateCommunityRequest;
use App\Models\Community;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Community::query()->select(sprintf('%s.*', (new Community)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewCommunity';
                $editFunct = 'editCommunity';
                $deleteFunct = 'deleteCommunity';
                $viewGate      = 'community_show';
                $editGate      = 'community_edit';
                $deleteGate    = 'community_delete';
                $crudRoutePart = 'communities';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.communities.index');
    }

    public function create()
    {
        abort_if(Gate::denies('community_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.communities.create');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = Community::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Community Created']);
            } else {
                $update = Community::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Community Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Community Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Community::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Community::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, Community $community)
    {
        $community->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(Community $community)
    {
        abort_if(Gate::denies('community_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.communities.show', compact('community'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Community::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Community Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $religion = Community::find(request('ids'));

        foreach ($religion as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Community Deleted Successfully']);
    }
}
