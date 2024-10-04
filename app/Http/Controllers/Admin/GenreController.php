<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GenreModel;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class GenreController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('genre_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GenreModel::select(sprintf('%s.*', (new GenreModel)->table))->get();
            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewGenre';
                $editFunct = 'editGenre';
                $deleteFunct = 'deleteGenre';
                $viewGate = 'genre_show';
                $editGate = 'genre_edit';
                $deleteGate = 'genre_delete';
                $crudRoutePart = 'genre';

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
            $table->editColumn('genre', function ($row) {
                return $row->genre ? $row->genre : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.genre.index');


    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('genre_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->id == '') {
            $genre = GenreModel::where('genre', $request->genre)->get();
            if (count($genre) > 0) {
                return response()->json(['status' => false, 'data' => 'Genre Already Exists']);
            } else {
                $create = GenreModel::create(['genre' => strtoupper($request->genre)]);
                return response()->json(['status' => true, 'data' => 'Genre Created Successfully']);
            }
        } else {
            $count = GenreModel::where(['id' => $request->id])->count();
            if ($count > 0) {
                $update = GenreModel::where('id', '=', $request->id)->update([
                    'genre' => strtoupper($request->genre)
                ]);
                return response()->json(['status' => true, 'data' => "Genre Updated Successfully"]);
            } else {
                return response()->json(['status' => false, 'data' => 'Genre is Not Available']);
            }
        }

    }
    public function view(Request $request)
    {
        abort_if(Gate::denies('genre_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = GenreModel::where(['id' => $request->id])->select('id', 'genre')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function edit(Request $request)
    {
        abort_if(Gate::denies('genre_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = GenreModel::where(['id' => $request->id])->select('id', 'genre')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('genre_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = GenreModel::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => "Genre deleted Successfully"]);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('genre_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $GenreModel = GenreModel::find(request('ids'));

        foreach ($GenreModel as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Genres Deleted Successfully']);
    }

}
