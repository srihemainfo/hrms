<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\BookDataModal;
use App\Models\BookIssueModel;
use App\Models\BookModel;
use App\Models\GenreModel;
use PDF;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    use CsvImportTrait;
    public function index(Request $request)
    {


        abort_if(Gate::denies('library_book_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BookModel::select(sprintf('%s.*', (new BookModel)->table))->get();
            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewBook';
                $editFunct = 'editBook';
                $deleteFunct = 'deleteBook';
                $viewGate = 'library_book_show';
                $editGate = 'library_book_edit';
                $deleteGate = 'library_book_delete';
                $crudRoutePart = 'book';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('book_code', function ($row) {
                return $row->isbn ? $row->isbn : '';
            });
            $table->editColumn('genre', function ($row) {
                $decode = json_decode($row->genre);
                $data = '';
                foreach ($decode as $key => $value) {
                    $get_data = GenreModel::where('id', (int) $value)->select('genre')->first();
                    $data .= $get_data->genre . ', ';
                }
                return $data ? $data : '';
            });
            $table->editColumn('author', function ($row) {
                return $row->author ? $row->author : '';
            });
            $table->editColumn('publication', function ($row) {
                return $row->publication ? $row->publication : '';
            });
            $table->editColumn('book_count', function ($row) {
                return $row->book_count ? $row->book_count : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        // $book = QrCode::size(200)->format('png')->merge('/storage/app/B.png')->generate('https://www.google.com/');
        // File::put(public_path('uploads/' . 'B.png'), $book);

        // $data = QrCode::size(512)
        //     ->format('png')
        //     ->errorCorrection('M')
        //     ->generate(
        //         'https://twitter.com/HarryKir',
        //     );
        // https://svcet.kalvierp.com/admin/book-issue

        // File::put(public_path('qrcodes/' . 'Bwwww.png'), $data);

        $genre = GenreModel::pluck('genre', 'id');
        return view('admin.book.index', compact('genre'));


    }
    public function store(Request $request)
    {

        abort_if(Gate::denies('library_book_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if ($request->id == '') {
            $check = BookModel::where(['isbn' => $request->book_code])->get();
            if (count($check) > 0) {
                return response()->json(['status' => false, 'data' => "Book ISBN Already Exists (" . $check[0]->name . ")"]);
            } else {
                $originalName = '';
                if ($request->hasFile('image') != null) {
                    $originalName = $request->file('image')->getClientOriginalName();
                    $request->file('image')->move(public_path('uploads'), $originalName);
                }

                $array = str_getcsv($request->genre);
                $array = array_map('intval', $array);

                if (($request->book) != null) {
                    $check = BookModel::where(['name' => $request->book])->count();
                    if ($check > 0) {
                        return response()->json(['status' => false, 'data' => 'Book Already Exixts']);
                    } else {

                        $book = BookModel::create([
                            'name' => strtoupper($request->book),
                            'isbn' => $request->book_code,
                            'genre' => json_encode($array),
                            'author' => strtoupper($request->author),
                            'publication' => strtoupper($request->publication),
                            'book_count' => $request->input('book_count'),
                            'image' => $originalName,
                        ]);

                        $count = BookDataModal::where('book_id', $book->id)->count();
                        // dd($book->id, $count);

                        if ($request->input('book_count') != '' || $request->input('book_count') != 0) {
                            for ($i = 1; $i <= $request->input('book_count'); $i++) {
                                //Barcode...
                                // $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                // $image = $generator->getBarcode('https://svcet.kalvierp.com/admin/book-issue/get-book-info/' . $request->book_code . '-' . $count += 1, $generator::TYPE_CODE_128);
                                // $image = $generator->getBarcode('https://127.0.0.1:8001/admin/book-issue/get-book-info/' . $request->book_code . '-' . $count += 1, $generator::TYPE_CODE_128);
                                // File::put(public_path('barcodes/' . $request->book_code . '-' . $count . '.png'), $image);

                                // $data = QrCode::size(300)
                                //     ->format('png')
                                //     ->style('dot')
                                //     ->eye('circle')
                                //     ->margin(1)
                                //     ->errorCorrection('M')
                                //     ->generate(
                                //         'https://svcet.kalvierp.com/admin/book-issue/get-book-info/' . $request->book_code . '-' . $count += 1,
                                //         // 'http://127.0.0.1:8001/admin/book-issue/get-book-info/' . $request->book_code . '-' . $count
                                //     );

                                // File::put(public_path('qrcodes/' . $request->book_code . '-' . $count . '.png'), $data);

                                $books = BookDataModal::create([
                                    'book_id' => $book->id,
                                    'book_code' => $request->book_code . '-' . $count += 1,
                                    'status' => 'Available',
                                    'availability' => 'Yes',
                                    // 'barcode_image' => $request->book_code . '-' . $count . '.png',
                                    // 'qrcode_image' => $request->book_code . '-' . $count . '.png'
                                ]);

                            }
                        }
                    }
                }

                return response()->json(['status' => true, 'data' => "Book Created Successfully"]);
            }
        } else {
            $count = BookModel::where(['id' => $request->id])->count();
            if ($count > 0) {
                $originalName = '';
                if ($request->hasFile('image') != null) {
                    $originalName = $request->file('image')->getClientOriginalName();
                    $request->file('image')->move(public_path('uploads'), $originalName);
                }
                $array = str_getcsv($request->genre);
                $array = array_map('intval', $array);
                $check = BookModel::whereNotIn('id', [$request->id])->where(['name' => $request->book])->exists();
                if ($check) {

                    return response()->json(['status' => false, 'data' => 'Book Already Exixts']);
                } else {

                    $books = BookDataModal::where('book_id', $request->id)->get();
                    $code_count = count($books);
                    $loop = $code_count;
                    if ($code_count < $request->input('book_count')) {
                        if ($request->input('book_count') != '' || $request->input('book_count') != 0) {
                            for ($i = 1; $i <= $request->input('book_count'); $i++) {
                                $update_check = BookDataModal::where('book_code', $request->book_code . '-' . $i)->exists();
                                if ($update_check) {
                                    continue;
                                } else {
                                    // dd('hii', $code_count);
                                    $update = BookModel::where('id', $request->id)->update([
                                        'name' => strtoupper($request->book),
                                        'isbn' => $request->book_code,
                                        'genre' => json_encode($array),
                                        'author' => strtoupper($request->author),
                                        'publication' => strtoupper($request->publication),
                                        'image' => $originalName,
                                        'book_count' => $request->input('book_count'),

                                    ]);
                                    // $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                    // $image = $generator->getBarcode('https://svcet.kalvierp.com/admin/book-issue/get-book-info/' . $request->book_code . '-' . $i, $generator::TYPE_CODE_128);
                                    // $image = $generator->getBarcode('https://127.0.0.1:8001/admin/book-issue/get-book-info/' . $request->book_code . '-' . $i, $generator::TYPE_CODE_128);
                                    // File::put(public_path('barcodes/' . $request->book_code . '-' . $i . '.png'), $image);

                                    // $data = QrCode::size(300)
                                    //     ->format('png')
                                    //     ->style('dot')
                                    //     ->eye('circle')
                                    //     ->margin(1)
                                    //     ->errorCorrection('M')
                                    //     ->generate(
                                    //         'https://svcet.kalvierp.com/admin/book-issue/get-book-info/' . $request->book_code . '-' . $i,
                                    //         // 'http://127.0.0.1:8001/admin/book-issue/get-book-info/' . $request->book_code . '-' . $i
                                    //     );

                                    // File::put(public_path('qrcodes/' . $request->book_code . '-' . $i . '.png'), $data);

                                    $books = BookDataModal::create([
                                        'book_id' => $request->id,
                                        'book_code' => $request->book_code . '-' . $i,
                                        'status' => 'Available',
                                        'availability' => 'Yes',
                                        // 'barcode_image' => $request->book_code . '-' . $i . '.png',
                                        // 'qrcode_image' => $request->book_code . '-' . $i . '.png'
                                    ]);
                                }
                            }
                        }
                    } elseif ($code_count > $request->input('book_count')) {
                        if ($request->input('book_count') != '' || $request->input('book_count') != 0) {
                            if ($books != null) {
                                $issue_check = BookIssueModel::where('book_id', $request->id)
                                    ->where('status', '!=', 'Return')
                                    ->exists();
                                if ($issue_check) {
                                    return response()->json(['status' => false, 'data' => 'One of the Book Already Issued Cannot be Modified...']);
                                } else {
                                    // dd($loop, $code_count);
                                    $update = BookModel::where('id', $request->id)->update([
                                        'name' => strtoupper($request->book),
                                        'isbn' => $request->book_code,
                                        'genre' => json_encode($array),
                                        'author' => strtoupper($request->author),
                                        'publication' => strtoupper($request->publication),
                                        'image' => $originalName,
                                        'book_count' => $request->input('book_count'),

                                    ]);
                                    for ($i = 1; $i <= $loop - $request->input('book_count'); $i++) {

                                        // $books = BookDataModal::where('book_code', $request->book_code . '-' . $code_count)->first();

                                        // $imagePath1 = public_path('qrcodes/' . $books->qrcode_image);
                                        // // $imagePath2 = public_path('barcodes/' . $books->barcode_image);

                                        // if (File::exists($imagePath1)) {
                                        //     File::delete($imagePath1);
                                        // }
                                        // if (File::exists($imagePath2)) {
                                        //     File::delete($imagePath2);
                                        // }
                                        $books_delete = BookDataModal::where('book_code', $request->book_code . '-' . $code_count)->delete();

                                        $code_count -= 1;
                                    }
                                }
                            }

                        }
                    } elseif ($code_count == $request->input('book_count')) {
                        $update = BookModel::where('id', $request->id)->update([
                            'name' => strtoupper($request->book),
                            'isbn' => $request->book_code,
                            'genre' => json_encode($array),
                            'author' => strtoupper($request->author),
                            'publication' => strtoupper($request->publication),
                            'image' => $originalName,
                            'book_count' => $request->input('book_count'),

                        ]);
                    }

                    return response()->json(['status' => true, 'data' => "Book Details Updated Successfully"]);
                }

            } else {
                return response()->json(['status' => false, 'data' => 'Book is Not Available']);
            }
        }
    }
    public function view(Request $request)
    {
        abort_if(Gate::denies('library_book_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = BookModel::where(['id' => $request->id])->select('id', 'name', 'isbn', 'genre', 'author', 'publication', 'image', 'book_count')->first();
            if (isset($data)) {
                $got_data = [];
                $decode = json_decode($data->genre);
                foreach ($decode as $key => $value) {
                    $get_data = GenreModel::where('id', (int) $value)->select('genre', 'id')->first();
                    $got_data[$get_data->id] = $get_data->genre;
                }
                $data->got_genre = $got_data;
                return response()->json(['status' => true, 'data' => $data]);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function edit(Request $request)
    {
        abort_if(Gate::denies('library_book_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = BookModel::where(['id' => $request->id])->select('id', 'name', 'isbn', 'genre', 'author', 'publication', 'image', 'book_count')->first();
            if (isset($data)) {
                $got_data = [];
                $decode = json_decode($data->genre);
                foreach ($decode as $key => $value) {
                    $get_data = GenreModel::where('id', (int) $value)->select('genre', 'id')->first();
                    $got_data[$get_data->id] = $get_data->genre;
                }
                $data->got_genre = $got_data;
                return response()->json(['status' => true, 'data' => $data]);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('library_book_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $issue_check = BookIssueModel::where('book_id', $request->id)
                ->where('status', '!=', 'Return')
                ->exists();
            if ($issue_check) {
                return response()->json(['status' => false, 'data' => 'One of the Book Already Issued Cannot be Modified...']);
            } else {
                $bookdata = BookDataModal::where('book_id', $request->id)->delete();
                $delete = BookModel::where(['id' => $request->id])->update([
                    'deleted_at' => Carbon::now(),
                ]);
                return response()->json(['status' => 'success', 'data' => "Book Deleted Successfully"]);
            }
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('library_book_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $BookModel = BookModel::find(request('ids'));

        foreach ($BookModel as $r) {
            $issue_check = BookIssueModel::where('book_id', $r->id)
                ->where('status', '!=', 'Return')
                ->exists();
            if ($issue_check) {
                continue;
            } else {
                $r->delete();
                $bookdata = BookDataModal::where('book_id', $r->id)->delete();
            }
        }
        return response()->json(['status' => 'success', 'data' => 'Books Deleted Successfully']);
    }

    public function downloadQr($request)
    {
        $qrcode = [];
        $books = BookDataModal::with(['books'])->where('book_id', $request)->select('book_code', 'book_id')->get();
        foreach ($books as $book) {
            $data = QrCode::size(300)
                ->margin(4)
                ->generate('https://svcet.kalvierp.com/admin/book-issue/get-book-info/' . $book->book_code);
                // ->generate('http://127.0.0.1:8001/admin/book-issue/get-book-info/' . $book->book_code);
            $qrcode[] = array('qrcode' => $data, 'book_code' => $book->book_code);
        }

        // dd($books[0]->books->name, $qrcode);
        $pdf = PDF::loadView('admin.book.qrcodeDownload', ['qrcode' => $qrcode, 'book_name' => $books[0]->books->name, 'isbn' => $books[0]->books->isbn]);
        return $pdf->stream('qrcodeDownload.pdf');

    }

}
