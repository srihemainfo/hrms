<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Events\StaffInsertEvent;
use App\Models\NonTeachingStaff;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAddressRequest;

class AddressController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('address_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Address::with(['name'])->select(sprintf('%s.*', (new Address)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'address_show';
                $editGate = 'address_edit';
                $deleteGate = 'address_delete';
                $crudRoutePart = 'addresses';

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
            $table->editColumn('address_type', function ($row) {
                return $row->address_type ? Address::ADDRESS_TYPE_SELECT[$row->address_type] : '';
            });
            $table->addColumn('name_name', function ($row) {
                return $row->name ? $row->name->name : '';
            });

            $table->editColumn('room_no_and_street', function ($row) {
                return $row->room_no_and_street ? $row->room_no_and_street : '';
            });
            $table->editColumn('area_name', function ($row) {
                return $row->area_name ? $row->area_name : '';
            });
            $table->editColumn('district', function ($row) {
                return $row->district ? $row->district : '';
            });
            $table->editColumn('pincode', function ($row) {
                return $row->pincode ? $row->pincode : '';
            });
            $table->editColumn('state', function ($row) {
                return $row->state ? $row->state : '';
            });
            $table->editColumn('country', function ($row) {
                return $row->country ? $row->country : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'name']);

            return $table->make(true);
        }

        return view('admin.addresses.index');
    }

    public function stu_index(Request $request)
    {
        if (isset($request->accept)) {

            Address::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {
            $query = Address::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->address_type = null;
                $query->room_no_and_street = null;
                $query->area_name = null;
                $query->district = null;
                $query->pincode = null;
                $query->state = null;
                $query->country = null;
                $query->add = 'Add';
                $student = $query;
                $stu_edit = $query;

                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $student = $query[0];

                $stu_edit = new Address;
                if ($query->count() >= 2) {
                    $stu_edit->add = 'Update';
                } else {
                    $stu_edit->add = 'Add';
                }

                $stu_edit->id = null;
                $stu_edit->room_no_and_street = null;
                $stu_edit->area_name = null;
                $stu_edit->address_type = null;
                $stu_edit->district = null;
                $stu_edit->pincode = null;
                $stu_edit->state = null;
                $stu_edit->country = null;

                $list = $query;

            }

        } else {

            $query = Address::where(['name_id' => $request->user_name_id])->get();
            $query_two = Address::where(['name_id' => $request->user_name_id, 'address_type' => $request->updater])->get();

            for ($i = 0; $i < count($query); $i++) {
                $query[$i]->user_name_id = $request->user_name_id;
                $query[$i]->name = $request->name;
                $query[$i]->add = 'Update';
            }

            $student = $query[0];
            $list = $query;
            $query_two[0]['add'] = 'Update';

            $stu_edit = $query_two[0];

            $staff_edit = $query_two[0];
        }

        $check = "address_details";

        return view('admin.StudentProfile.student', compact('student', 'check', 'list', 'stu_edit'));
    }

    public function stu_update(UpdateAddressRequest $request, Address $address)
    {
        $addresses = $address->where(['name_id' => $request->user_name_id, 'address_type' => $request->address_type])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));
        if ($addresses) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_address = new Address;
            $stu_address->address_type = $request->address_type;
            $stu_address->room_no_and_street = $request->room_no_and_street;
            $stu_address->area_name = $request->area_name;
            $stu_address->district = $request->district;
            $stu_address->pincode = $request->pincode;
            $stu_address->state = $request->state;
            $stu_address->country = $request->country;
            $stu_address->name_id = $request->user_name_id;
            $stu_address->save();


            if ($stu_address) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                return back();
            }

        }
        return redirect()->route('admin.addresses.stu_index', $student);
    }

    public function staff_index(Request $request)
    {
        if (isset($request->accept)) {

            Address::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {

            $query = Address::where(['name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->address_type = null;
                $query->room_no_and_street = null;
                $query->area_name = null;
                $query->district = null;
                $query->pincode = null;
                $query->state = null;
                $query->country = null;
                $query->add = 'Add';
                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {
                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $staff_edit = new Address;
                if ($query->count() >= 2) {
                    $staff_edit->add = 'Update';
                } else {
                    $staff_edit->add = 'Add';
                }

                $staff_edit->id = null;
                $staff_edit->room_no_and_street = null;
                $staff_edit->area_name = null;
                $staff_edit->address_type = null;
                $staff_edit->district = null;
                $staff_edit->pincode = null;
                $staff_edit->state = null;
                $staff_edit->country = null;

                $list = $query;

            }

        } else {

            $query = Address::where(['name_id' => $request->user_name_id])->get();
            $query_two = Address::where(['name_id' => $request->user_name_id, 'address_type' => $request->updater])->get();
            for ($i = 0; $i < count($query); $i++) {
                $query[$i]->user_name_id = $request->user_name_id;
                $query[$i]->name = $request->name;
                $query[$i]->add = 'Update';
            }

            $staff = $query[0];

            $list = $query;

            $query_two[0]['add'] = 'Update';
            $staff_edit = $query_two[0];

        }
        $query_two = Address::where(['name_id' => $request->user_name_id,'address_type' => $request->updater])->get();
        $address=Address::get()->pluck('address_type');



        $check = "address_details";
        $check_staff_1 = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check', 'list', 'staff_edit'));
            }
        }
    }
    public function staff_update(UpdateAddressRequest $request, Address $address)
    {
        $addresses = $address->where(['name_id' => $request->user_name_id, 'address_type' => $request->address_type])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        if ($addresses) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_address = new Address;
            $staff_address->address_type = $request->address_type;
            $staff_address->room_no_and_street = $request->room_no_and_street;
            $staff_address->area_name = $request->area_name;
            $staff_address->district = $request->district;
            $staff_address->pincode = $request->pincode;
            $staff_address->state = $request->state;
            $staff_address->country = $request->country;
            $staff_address->name_id = $request->user_name_id;
            $staff_address->save();

            if ($staff_address) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                dd('Error');
            }

        }

        return redirect()->route('admin.addresses.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('address_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.addresses.create', compact('names'));
    }

    public function store(StoreAddressRequest $request)
    {
        $address = Address::create($request->all());

        return redirect()->route('admin.addresses.index');
    }

    public function edit(Address $address)
    {
        abort_if(Gate::denies('address_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $address->load('name');

        return view('admin.addresses.edit', compact('address', 'names'));
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $address->update($request->all());

        return redirect()->route('admin.addresses.index');
    }

    public function show(Address $address)
    {
        abort_if(Gate::denies('address_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $address->load('name');

        return view('admin.addresses.show', compact('address'));
    }

    public function destroy(Address $address)
    {
        abort_if(Gate::denies('address_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $address->delete();

        return back();
    }

    public function massDestroy(MassDestroyAddressRequest $request)
    {
        $addresses = Address::find(request('ids'));

        foreach ($addresses as $address) {
            $address->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
