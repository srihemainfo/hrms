<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySponserRequest;
use App\Http\Requests\StoreSponserRequest;
use App\Http\Requests\UpdateSponserRequest;
use App\Models\Sponser;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SponserController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('sponser_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Sponser::with(['user_name'])->select(sprintf('%s.*', (new Sponser)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'sponser_show';
                $editGate = 'sponser_edit';
                $deleteGate = 'sponser_delete';
                $crudRoutePart = 'sponsers';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('user_name_name', function ($row) {
                return $row->user_name ? $row->user_name->name : '';
            });

            $table->editColumn('sponser_type', function ($row) {
                return $row->sponser_type ? $row->sponser_type : '';
            });
            $table->editColumn('sponser_name', function ($row) {
                return $row->sponser_name ? $row->sponser_name : '';
            });
            $table->editColumn('sponsered_items', function ($row) {
                return $row->sponsered_items ? $row->sponsered_items : '';
            });
            $table->editColumn('sponsered_to', function ($row) {
                return $row->sponsered_to ? $row->sponsered_to : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user_name']);

            return $table->make(true);
        }

        return view('admin.sponsers.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('sponser_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
// dd($request);
             if (isset($request->accept)) {

                Sponser::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Sponser::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->sponser_name = '';
                $query->project_title = '';
                $query->project_duration = '';
                $query->application_date = '';
                $query->application_status = '';
                $query->investigator_level = '';
                $query->funding_amount = '';
                $query->received_date = '';
                $query->sanctioned_letter = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new Sponser;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->sponser_name = '';
                $staff_edit->project_title = '';
                $staff_edit->project_duration = '';
                $staff_edit->application_date = '';
                $staff_edit->application_status = '';
                $staff_edit->investigator_level = '';
                $staff_edit->funding_amount = '';
                $staff_edit->received_date = '';
                $staff_edit->sanctioned_letter = '';

            }

        } else {

            // dd($request);

            $query_one = Sponser::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = Sponser::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                $list = $query_one;
                // dd($staff);
                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $check = 'sponser_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(UpdateSponserRequest $request, Sponser $sponser)
    {
        // dd($request);
        if (isset($request->sanctioned_letter)) {

            $request->validate([
                'sanctioned_letter' => 'required|image|mimes:jpg,JPG,jpeg,png,PNG,JPEG|max:2048',
            ]);
            $file = $request->file('sanctioned_letter');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = $file->storeAs('uploads', $fileName);
            $file->move(public_path('uploads'), $fileName);
        } else {
            $path = '';
        }
        if (!$request->id == 0 || $request->id != '') {

            $sponsers = $sponser->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->first();

            if ($sponsers) {

                $file_exist = $sponsers->sanctioned_letter;
                $filePath = public_path($sponsers->sanctioned_letter);

                $sponsers->sponser_name = $request->sponser_name;
                $sponsers->project_title = $request->project_title;
                $sponsers->project_duration = $request->project_duration;
                $sponsers->application_date = $request->application_date;
                $sponsers->application_status = $request->application_status;
                $sponsers->investigator_level = $request->investigator_level;
                $sponsers->funding_amount = $request->funding_amount;
                $sponsers->received_date = $request->received_date;
                $sponsers->status='0';
                if ($path != '') {
                    $sponsers->sanctioned_letter = $path;
                }

                $sponsers->save();

                // Delete the old file from the disk
                if ($path != '') {
                    if ($file_exist != '' || $file_exist != null) {
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }

            } else {
                $sponsers = false;
            }
        } else {
            $sponsers = false;
        }
        if ($sponsers) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_spons = new Sponser;
            $staff_spons->sponser_name = $request->sponser_name;
            $staff_spons->project_title = $request->project_title;
            $staff_spons->project_duration = $request->project_duration;
            $staff_spons->application_date = $request->application_date;
            $staff_spons->application_status = $request->application_status;
            $staff_spons->investigator_level = $request->investigator_level;
            $staff_spons->funding_amount = $request->funding_amount;
            $staff_spons->received_date = $request->received_date;
            $staff_spons->user_name_id = $request->user_name_id;
            $staff_spons->status='0';

            if ($path != '') {
                $staff_spons->sanctioned_letter = $path;
            }

            $staff_spons->save();

            if ($staff_spons) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

// dd($student);
        return redirect()->route('admin.sponsers.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('sponser_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.sponsers.create', compact('user_names'));
    }

    public function store(StoreSponserRequest $request)
    {
        $sponser = Sponser::create($request->all());

        return redirect()->route('admin.sponsers.index');
    }

    public function edit(Sponser $sponser)
    {
        abort_if(Gate::denies('sponser_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sponser->load('user_name');

        return view('admin.sponsers.edit', compact('sponser', 'user_names'));
    }

    public function update(UpdateSponserRequest $request, Sponser $sponser)
    {
        $sponser->update($request->all());

        return redirect()->route('admin.sponsers.index');
    }

    public function show(Sponser $sponser)
    {
        abort_if(Gate::denies('sponser_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponser->load('user_name');

        return view('admin.sponsers.show', compact('sponser'));
    }

    public function destroy(Sponser $sponser)
    {
        abort_if(Gate::denies('sponser_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponser->delete();

        return back();
    }

    public function massDestroy(MassDestroySponserRequest $request)
    {
        $sponsers = Sponser::find(request('ids'));

        foreach ($sponsers as $sponser) {
            $sponser->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
