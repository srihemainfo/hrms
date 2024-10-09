<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocumentRequest;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\EducationType;
use App\Models\Staffs;
use App\Models\NonTeachingStaff;
use App\Models\TeachingStaff;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocumentsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Document::with(['nameofuser'])->select(sprintf('%s.*', (new Document)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'document_show';
                $editGate = 'document_edit';
                $deleteGate = 'document_delete';
                $crudRoutePart = 'documents';

                return view('partials.datatablesActions', compact(
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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->addColumn('nameofuser_name', function ($row) {
                return $row->nameofuser ? $row->nameofuser->name : '';
            });

            $table->editColumn('file', function ($row) {
                if (!$row->file) {
                    return '';
                }
                $links = [];
                foreach ($row->file as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'nameofuser', 'file']);

            return $table->make(true);
        }

        return view('admin.documents.index');
    }

    public function stu_index(Request $request)
    {

        // abort_if(Gate::denies('document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $education_types = EducationType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if ($request) {
            $query = Document::where(['nameofuser_id' => $request->user_name_id])->get();
        }
        if ($query->count() <= 0) {

            $query->user_name_id = $request->user_name_id;
            $query->name = $request->name;
            $query->fileName = '';
            $query->add = 'Add';

            $student = $query;
        } else {

            $query[0]['user_name_id'] = $request->user_name_id;

            $query[0]['name'] = $request->name;

            $query[0]['add'] = 'Update';

            $student = $query[0];
        }

        $check = 'document_details';
        $document = Document::where('nameofuser_id', $request->user_name_id)->get();

        return view('admin.StudentProfile.student', compact('student', 'check', 'education_types', 'document'));
    }

    public function stu_update(Request $request, Document $document)
    {
        $request->validate([
            'filePath' => 'required|image|mimes:jpg,JPG,jpeg,png,PNG,JPEG|max:2048',
        ]);

        $file = $request->file('filePath');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $destinationPath = public_path('uploads'); // Set the destination path

          // Move the uploaded file to the destination manually
        $file->move($destinationPath, $fileName);

          // Set the storage path for further use if needed
        $path = 'uploads/' . $fileName;

        // Find the document with the same file name and user ID
        $document = Document::where('fileName', $request->fileName)
            ->where('nameofuser_id', $request->user_name_id)
            ->first();

        // If the document exists, update it and delete the old file
        if ($document) {
            $filePath = public_path($document->filePath);

            $document->filePath = $path;
            $document->fileName = $request->fileName;
            $document->status = '0';
            $document->save();

            // Delete the old file from the disk
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
        } else {
            // If the document does not exist, create a new one
            $document = new Document([
                'fileName' => $request->fileName,
                'filePath' => $path,
                'nameofuser_id' => $request->user_name_id,
                'status' => '0',
            ]);
            $document->save();

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
        }

        return redirect()->route('admin.documents.stu_index', $student);
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {
            $query = Document::where(['nameofuser_id' => $request->user_name_id])->get();
        }
        if ($query->count() <= 0) {

            $query->user_name_id = $request->user_name_id;
            $query->name = $request->name;
            $query->fileName = '';
            $query->status = '0';
            $query->add = 'Add';

            $staff = $query;
        } else {

            $query[0]['user_name_id'] = $request->user_name_id;

            $query[0]['name'] = $request->name;

            $query[0]['add'] = 'Update';

            $staff = $query[0];
        }

        $check = 'document_details';
        $document = Document::where('nameofuser_id', $request->user_name_id)->get();
        $education_types = EducationType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $check_staff_1 = Staffs::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check', 'document', 'education_types'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check', 'document', 'education_types'));
            }
        }
    }
    public function staff_update(Request $request, Document $document)
    {
        abort_if(Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'filePath' => 'required|image|mimes:jpg,JPG,jpeg,png,PNG,JPEG|max:2048',
        ]);

        $file = $request->file('filePath');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $destinationPath = public_path('uploads'); // Set the destination path

        // Move the uploaded file to the destination manually
        $file->move($destinationPath, $fileName);

        // Set the storage path for further use if needed
        $path = 'uploads/' . $fileName;

        // Find the document with the same file name and user ID
        $document = Document::where('fileName', $request->fileName)
            ->where('nameofuser_id', $request->user_name_id)
            ->first();

        // If the document exists, update it and delete the old file
        if ($document) {
            $filePath = public_path($document->filePath);

            $document->filePath = $path;
            $document->fileName = $request->fileName;
            $document->status = '0';
            $document->save();

            // Delete the old file from the disk
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
        } else {
            // If the document does not exist, create a new one
            $document = new Document([
                'fileName' => $request->fileName,
                'filePath' => $path,
                'nameofuser_id' => $request->user_name_id,
                'status' => '0',
            ]);
            $document->save();

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
        }

        return redirect()->route('admin.documents.staff_index', $staff);

    }

    public function newapprove(Request $request)
    {
        if (isset($request->accept)) {

            Document::where('id', $request->id)->update(['status' => 1]);

        }
        return back();
    }

    public function create()
    {
        abort_if(Gate::denies('document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nameofusers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.documents.create', compact('nameofusers'));
    }

    public function store(Request $request)
    {
        $document = Document::create($request->all());

        foreach ($request->input('file', []) as $file) {
            $document->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $document->id]);
        }

        return redirect()->route('admin.documents.index');
    }

    public function edit(Document $document)
    {
        abort_if(Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nameofusers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $document->load('nameofuser');

        return view('admin.documents.edit', compact('document', 'nameofusers'));
    }

    public function update(Request $request, Document $document)
    {
        $document->update($request->all());

        if (count($document->file) > 0) {
            foreach ($document->file as $media) {
                if (!in_array($media->file_name, $request->input('file', []))) {
                    $media->delete();
                }
            }
        }
        $media = $document->file->pluck('file_name')->toArray();
        foreach ($request->input('file', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $document->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file');
            }
        }

        return redirect()->route('admin.documents.index');
    }

    public function show(Document $document)
    {
        abort_if(Gate::denies('document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $document->load('nameofuser');

        return view('admin.documents.show', compact('document'));
    }

    public function destroy(Document $document)
    {
        abort_if(Gate::denies('document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $document->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocumentRequest $request)
    {
        $documents = Document::find(request('ids'));

        foreach ($documents as $document) {
            $document->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('document_create') && Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $model = new Document();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
