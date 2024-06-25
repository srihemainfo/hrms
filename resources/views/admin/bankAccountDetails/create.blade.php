@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.bankAccountDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.bank-account-details.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="account_type">{{ trans('cruds.bankAccountDetail.fields.account_type') }}</label>
                <input class="form-control {{ $errors->has('account_type') ? 'is-invalid' : '' }}" type="text" name="account_type" id="account_type" value="{{ old('account_type', '') }}">
                @if($errors->has('account_type'))
                    <span class="text-danger">{{ $errors->first('account_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.account_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="account_no">{{ trans('cruds.bankAccountDetail.fields.account_no') }}</label>
                <input class="form-control {{ $errors->has('account_no') ? 'is-invalid' : '' }}" type="text" name="account_no" id="account_no" value="{{ old('account_no', '') }}">
                @if($errors->has('account_no'))
                    <span class="text-danger">{{ $errors->first('account_no') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.account_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ifsc_code">{{ trans('cruds.bankAccountDetail.fields.ifsc_code') }}</label>
                <input class="form-control {{ $errors->has('ifsc_code') ? 'is-invalid' : '' }}" type="text" name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code', '') }}">
                @if($errors->has('ifsc_code'))
                    <span class="text-danger">{{ $errors->first('ifsc_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.ifsc_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bank_name">{{ trans('cruds.bankAccountDetail.fields.bank_name') }}</label>
                <input class="form-control {{ $errors->has('bank_name') ? 'is-invalid' : '' }}" type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', '') }}">
                @if($errors->has('bank_name'))
                    <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.bank_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="branch_name">{{ trans('cruds.bankAccountDetail.fields.branch_name') }}</label>
                <input class="form-control {{ $errors->has('branch_name') ? 'is-invalid' : '' }}" type="text" name="branch_name" id="branch_name" value="{{ old('branch_name', '') }}">
                @if($errors->has('branch_name'))
                    <span class="text-danger">{{ $errors->first('branch_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.branch_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bank_location">{{ trans('cruds.bankAccountDetail.fields.bank_location') }}</label>
                <input class="form-control {{ $errors->has('bank_location') ? 'is-invalid' : '' }}" type="text" name="bank_location" id="bank_location" value="{{ old('bank_location', '') }}">
                @if($errors->has('bank_location'))
                    <span class="text-danger">{{ $errors->first('bank_location') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.bank_location_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection