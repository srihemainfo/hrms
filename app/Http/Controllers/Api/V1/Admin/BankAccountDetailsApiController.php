<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankAccountDetailRequest;
use App\Http\Requests\UpdateBankAccountDetailRequest;
use App\Http\Resources\Admin\BankAccountDetailResource;
use App\Models\BankAccountDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BankAccountDetailsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('bank_account_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BankAccountDetailResource(BankAccountDetail::all());
    }

    public function store(StoreBankAccountDetailRequest $request)
    {
        $bankAccountDetail = BankAccountDetail::create($request->all());

        return (new BankAccountDetailResource($bankAccountDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BankAccountDetail $bankAccountDetail)
    {
        abort_if(Gate::denies('bank_account_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BankAccountDetailResource($bankAccountDetail);
    }

    public function update(UpdateBankAccountDetailRequest $request, BankAccountDetail $bankAccountDetail)
    {
        $bankAccountDetail->update($request->all());

        return (new BankAccountDetailResource($bankAccountDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(BankAccountDetail $bankAccountDetail)
    {
        abort_if(Gate::denies('bank_account_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bankAccountDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
