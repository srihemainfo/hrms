<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyBankAccountDetailRequest;
use App\Http\Requests\StoreBankAccountDetailRequest;
use App\Http\Requests\UpdateBankAccountDetailRequest;
use App\Models\BankAccountDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BankAccountDetailsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('bank_account_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bankAccountDetails = BankAccountDetail::all();

        return view('frontend.bankAccountDetails.index', compact('bankAccountDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('bank_account_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bankAccountDetails.create');
    }

    public function store(StoreBankAccountDetailRequest $request)
    {
        $bankAccountDetail = BankAccountDetail::create($request->all());

        return redirect()->route('frontend.bank-account-details.index');
    }

    public function edit(BankAccountDetail $bankAccountDetail)
    {
        abort_if(Gate::denies('bank_account_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bankAccountDetails.edit', compact('bankAccountDetail'));
    }

    public function update(UpdateBankAccountDetailRequest $request, BankAccountDetail $bankAccountDetail)
    {
        $bankAccountDetail->update($request->all());

        return redirect()->route('frontend.bank-account-details.index');
    }

    public function show(BankAccountDetail $bankAccountDetail)
    {
        abort_if(Gate::denies('bank_account_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bankAccountDetails.show', compact('bankAccountDetail'));
    }

    public function destroy(BankAccountDetail $bankAccountDetail)
    {
        abort_if(Gate::denies('bank_account_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bankAccountDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyBankAccountDetailRequest $request)
    {
        $bankAccountDetails = BankAccountDetail::find(request('ids'));

        foreach ($bankAccountDetails as $bankAccountDetail) {
            $bankAccountDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
