@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.bankAccountDetail.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bank-account-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.bankAccountDetail.fields.id') }}
                        </th>
                        <td>
                            {{ $bankAccountDetail->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankAccountDetail.fields.account_type') }}
                        </th>
                        <td>
                            {{ $bankAccountDetail->account_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankAccountDetail.fields.account_no') }}
                        </th>
                        <td>
                            {{ $bankAccountDetail->account_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankAccountDetail.fields.ifsc_code') }}
                        </th>
                        <td>
                            {{ $bankAccountDetail->ifsc_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankAccountDetail.fields.bank_name') }}
                        </th>
                        <td>
                            {{ $bankAccountDetail->bank_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankAccountDetail.fields.branch_name') }}
                        </th>
                        <td>
                            {{ $bankAccountDetail->branch_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankAccountDetail.fields.bank_location') }}
                        </th>
                        <td>
                            {{ $bankAccountDetail->bank_location }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bank-account-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection