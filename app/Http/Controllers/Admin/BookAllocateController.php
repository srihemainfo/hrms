<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookAllocateModel;
use App\Models\BookDataModal;
use App\Models\BookModel;
use App\Models\GenreModel;
use App\Models\RackModel;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class BookAllocateController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('book_allote_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BookAllocateModel::with('rack:id,rack_no,row_no')->select('row_id')->groupBy('row_id')->get();

            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewBookAllote';
                $editFunct = 'editBookAllote';
                $deleteFunct = 'deleteBookAllote';
                $viewGate = 'book_allote_show';
                $editGate = 'book_allote_edit';
                $deleteGate = 'book_allote_delete';
                $crudRoutePart = 'book-allocate';

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
            $i = 0;
            $table->editColumn('sno', function ($row) use (&$i) {
                return $i += 1;
            });
            $table->editColumn('id', function ($row) {
                return $row->rack['id'] ?? '';
            });
            $table->editColumn('rack', function ($row) {
                return $row->rack['rack_no'] ? $row->rack['rack_no'] : '';
            });
            $table->editColumn('row', function ($row) {
                return $row->rack['row_no'] ? $row->rack['row_no'] : '';
            });
            $table->editColumn('count', function ($row) {
                $count = BookAllocateModel::where('row_id', (string) $row->rack['id'])->count();
                return $count;
            });
            // $table->editColumn('name', function ($row) {
            //     return $row->book['name'] ? $row->book['name'] : '';
            // });
            // $table->editColumn('genre', function ($row) {
            //     $decode = json_decode($row->book['genre']);
            //     $data = '';
            //     foreach ($decode as $key => $value) {
            //         $get_data = GenreModel::where('id', (int) $value)->select('genre')->first();
            //         $data .= $get_data->genre . ', ';
            //     }
            //     return $data ? $data : '';
            //     // return $row->book['name'] ? $row->book['name'] : '';
            // });
            // $table->editColumn('book_code', function ($row) {
            //     return $row->book['isbn'] ? $row->book['isbn'] : '';
            // });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $rack = RackModel::groupBy('rack_no')->select('rack_no')->get()->toArray();
        $genre = GenreModel::pluck('genre', 'id');
        // dd($genre);
        return view('admin.book_allocation.index', compact('rack', 'genre'));
    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('book_allote_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        // dd($request);
        if ($request->id == '') {
            $books = $request->book;
            $check = BookAllocateModel::where('row_id', $request->rows)->exists();
            if ($check) {
                return response()->json(['status' => false, 'data' => 'This Row Already Allocated.']);
            } else {
                foreach ($books as $key => $value) {
                    $explode = explode(',', $value);
                    // dd($explode[1]);
                    $count = BookAllocateModel::where('book_code', $explode[1])->count();
                    if ($count > 0) {
                        continue;
                    } else {
                        $book_allote = BookAllocateModel::create([
                            'row_id' => $request->rows,
                            'genre_id' => $request->genre,
                            'book_data_id' => (int) $explode[0],
                            'book_code' => $explode[1],
                        ]);
                    }
                }
            }
            return response()->json(['status' => true, 'data' => "Book Alloted Successfully"]);
        } else {

            $count = BookAllocateModel::where(['row_id' => (int) $request->id])->count();
            if ($count > 0) {
                $books = $request->book;
                $BookAllocateModel = new BookAllocateModel();
                // $count = $BookAllocateModel->where('row_id', $request->id)->count();
                if ($count > 0) {
                    $count = $BookAllocateModel->where('row_id', (int) $request->id)->delete();
                    foreach ($books as $key => $value) {
                        $explode = explode(',', $value);
                        $book_allote = BookAllocateModel::create([
                            'row_id' => $request->rows,
                            'genre_id' => $request->genre,
                            'book_data_id' => (int) $explode[0],
                            'book_code' => $explode[1],
                        ]);
                    }
                } else {
                    return response()->json(['status' => false, 'data' => 'Row Not Allocated']);
                }
                return response()->json(['status' => true, 'data' => "Book Allocate Is Updated"]);
                // }
            } else {
                return response()->json(['status' => false, 'data' => 'Book is Not Available']);
            }

            
        }

    }
    public function view(Request $request)
    {
        abort_if(Gate::denies('book_allote_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // dd($request->id);
        if (isset($request->id)) {
            // $data = BookAllocateModel::with('rack:id,rack_no')->where(['row_id' => (int) $request->id])->select('id', 'row_id', 'book_data_id', 'genre_id', 'book_code')->get();
            $data = DB::table('book_allocation')
                ->where(['book_allocation.row_id' => (int) $request->id])
                ->whereNull('book_allocation.deleted_at')
                ->leftJoin('rack', 'rack.id', '=', 'book_allocation.row_id')
                ->leftJoin('book_data', 'book_data.id', '=', 'book_allocation.book_data_id')
                ->leftJoin('book_details', 'book_details.id', '=', 'book_data.book_id')
                ->select('book_details.name', 'book_details.isbn', 'book_data.book_code', 'book_allocation.id', 'book_allocation.row_id', 'book_allocation.genre_id', 'rack.rack_no', 'book_allocation.book_data_id')->get();
            // dd($data);
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function edit(Request $request)
    {
        abort_if(Gate::denies('book_allote_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = DB::table('book_allocation')
                ->where(['book_allocation.row_id' => (int) $request->id])
                ->whereNull('book_allocation.deleted_at')
                ->leftJoin('rack', 'rack.id', '=', 'book_allocation.row_id')
                ->leftJoin('book_data', 'book_data.id', '=', 'book_allocation.book_data_id')
                ->leftJoin('book_details', 'book_details.id', '=', 'book_data.book_id')
                ->select('book_details.name', 'book_details.isbn', 'book_data.book_code', 'book_allocation.id', 'book_allocation.row_id', 'book_allocation.genre_id', 'rack.rack_no', 'book_allocation.book_data_id')->get();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('book_allote_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = BookAllocateModel::where(['row_id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => "Allocated Book Is Deleted Successfully"]);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('book_allote_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $BookAllocateModel = BookAllocateModel::whereIn('row_id', request('ids'))->get();
        // dd($BookAllocateModel);
        foreach ($BookAllocateModel as $r) {
            // dd($r);
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Allocated Books Is Deleted Successfully']);
    }

    public function fetchRow(Request $request)
    {
        if ($request->rack != '') {
            $row = RackModel::where('rack_no', $request->rack)->pluck('row_no', 'id');
            // $row = RackModel::with('bookAllocate')->where('rack_no', $request->rack)->pluck('row_no', 'id');
            // $book_count = BookAllocateModel::where('room_id', $request)->count();
            // dd($book_count);
            if ($row != null) {
                return response()->json(['status' => true, 'data' => $row]);
            } else {
                return response()->json(['status' => false, 'data' => 'Rack Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Rack Name is Empty']);
        }
    }
    public function fetchBook(Request $request)
    {
        // dd($request);
        if ($request->genre != '') {
            // $book = BookAllocateModel::where('genre', $request->genre)->pluck('name', 'isbn');
            // dd();

            $book = [];
            $checkBook = BookAllocateModel::select('book_data_id')->get()->toArray();
            foreach ($checkBook as $key => $value) {
                $book = $value;
                // dd($value);
            }
            // dd($book);
            $genre[] = (int) $request->genre;
            $get_data = DB::table('book_details')
                ->whereJsonContains('genre', $genre)
                ->leftJoin('book_data', 'book_data.book_id', '=', 'book_details.id')
                ->select('book_details.name', 'book_data.id', 'book_data.book_code')
                ->get();
            // dd($get_data);
            if ($get_data != null) {
                // dd($get_data);
                return response()->json(['status' => true, 'data' => $get_data]);
            } else {
                return response()->json(['status' => false, 'data' => 'Genre Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Genre Name is Empty']);
        }
    }
    public function fetchCount(Request $request)
    {
        if ($request->rows != '') {
            $row_count = BookAllocateModel::where('row_id', (string) $request->rows)->count();
            // dd($row_count);
            // if ($row_count) {
            return response()->json(['status' => true, 'data' => $row_count]);
            // } else {
            //     return response()->json(['status' => false, 'data' => 'Rack Not Found']);
            // }
        } else {
            return response()->json(['status' => false, 'data' => 'Rack Name is Empty']);
        }
    }
    public function get_fetchCount($request)
    {
        // dd($request);
        if ($request->rows != '') {
            $row_count = BookAllocateModel::where('row_id', (string) $request->rows)->count();
            // dd($row_count);
            // if ($row_count) {
            return response()->json(['status' => true, 'data' => $row_count]);
            // } else {
            //     return response()->json(['status' => false, 'data' => 'Rack Not Found']);
            // }
        } else {
            return response()->json(['status' => false, 'data' => 'Rack Name is Empty']);
        }
    }

}
