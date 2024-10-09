<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\BankAccountDetail;
use App\Models\NonTeachingStaff;
use App\Models\Staffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BankAccountDetailsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('bank_account_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BankAccountDetail::query()->select(sprintf('%s.*', (new BankAccountDetail)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'bank_account_detail_show';
                $editGate = 'bank_account_detail_edit';
                $deleteGate = 'bank_account_detail_delete';
                $crudRoutePart = 'bank-account-details';

                return view(
                    'partials.datatablesActions',
                    compact(
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
            $table->editColumn('account_type', function ($row) {
                return $row->account_type ? $row->account_type : '';
            });
            $table->editColumn('account_no', function ($row) {
                return $row->account_no ? $row->account_no : '';
            });
            $table->editColumn('ifsc_code', function ($row) {
                return $row->ifsc_code ? $row->ifsc_code : '';
            });
            $table->editColumn('bank_name', function ($row) {
                return $row->bank_name ? $row->bank_name : '';
            });
            $table->editColumn('branch_name', function ($row) {
                return $row->branch_name ? $row->branch_name : '';
            });
            $table->editColumn('bank_location', function ($row) {
                return $row->bank_location ? $row->bank_location : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.bankAccountDetails.index');
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('bank_account_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->accept)) {
            BankAccountDetail::where('id', $request->id)->update(['status' => 1]);
        }

        if (!$request->updater) {
            $query = BankAccountDetail::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->account_type = '';
                $query->account_no = '';
                $query->ifsc_code = '';
                $query->bank_name = '';
                $query->branch_name = '';
                $query->bank_location = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new BankAccountDetail;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->account_type = '';
                $staff_edit->account_no = '';
                $staff_edit->ifsc_code = '';
                $staff_edit->bank_name = '';
                $staff_edit->branch_name = '';
                $staff_edit->bank_location = '';

            }

        } else {

            $query_one = BankAccountDetail::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = BankAccountDetail::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                $list = $query_one;
                $staff_edit = $query_two[0];
            } else {
                return back();
            }
        }

        $check = 'bank_account_details';
        $check_staff_1 = Staffs::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check', 'list', 'staff_edit'));
            }
        }
    }

    public function staff_update(Request $request, BankAccountDetail $bankAccountDetail)
    {

        abort_if(Gate::denies('bank_account_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$request->id == 0 || $request->id != '') {

            $bankAccount = $bankAccountDetail->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $bankAccount = false;
        }

        if ($bankAccount) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_bankAccount = new BankAccountDetail;

            $staff_bankAccount->account_type = $request->account_type;
            $staff_bankAccount->account_no = $request->account_no;
            $staff_bankAccount->ifsc_code = $request->ifsc_code;
            $staff_bankAccount->bank_name = $request->bank_name;
            $staff_bankAccount->branch_name = $request->branch_name;
            $staff_bankAccount->bank_location = $request->bank_location;
            $staff_bankAccount->user_name_id = $request->user_name_id;
            $staff_bankAccount->status = '0';
            $staff_bankAccount->save();

            $user = auth()->user();

            if ($staff_bankAccount) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                return back();
            }
        }
        return redirect()->route('admin.bank-account-details.staff_index', $staff);
    }

    public function create()
    {
        // abort_if(Gate::denies('bank_account_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bankAccountDetails.create');
    }

    public function store(Request $request)
    {
        $bankAccountDetail = BankAccountDetail::create($request->all());

        return redirect()->route('admin.bank-account-details.index');
    }

    public function edit(BankAccountDetail $bankAccountDetail)
    {
        abort_if(Gate::denies('bank_account_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bankAccountDetails.edit', compact('bankAccountDetail'));
    }

    public function update(Request $request, BankAccountDetail $bankAccountDetail)
    {
        $bankAccountDetail->update($request->all());

        return redirect()->route('admin.bank-account-details.index');
    }

    public function show(BankAccountDetail $bankAccountDetail)
    {
        abort_if(Gate::denies('bank_account_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bankAccountDetails.show', compact('bankAccountDetail'));
    }

    public function destroy(BankAccountDetail $bankAccountDetail)
    {
        abort_if(Gate::denies('bank_account_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bankAccountDetail->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        $bankAccountDetails = BankAccountDetail::find(request('ids'));

        foreach ($bankAccountDetails as $bankAccountDetail) {
            $bankAccountDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
