@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.bankAccountDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.bank-account-details.update", [$bankAccountDetail->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="account_type">{{ trans('cruds.bankAccountDetail.fields.account_type') }}</label>
                            <input class="form-control" type="text" name="account_type" id="account_type" value="{{ old('account_type', $bankAccountDetail->account_type) }}">
                            @if($errors->has('account_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('account_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.account_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="account_no">{{ trans('cruds.bankAccountDetail.fields.account_no') }}</label>
                            <input class="form-control" type="text" name="account_no" id="account_no" value="{{ old('account_no', $bankAccountDetail->account_no) }}">
                            @if($errors->has('account_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('account_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.account_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="ifsc_code">{{ trans('cruds.bankAccountDetail.fields.ifsc_code') }}</label>
                            <input class="form-control" type="text" name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code', $bankAccountDetail->ifsc_code) }}">
                            @if($errors->has('ifsc_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ifsc_code') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.ifsc_code_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="bank_name">{{ trans('cruds.bankAccountDetail.fields.bank_name') }}</label>
                            <input class="form-control" type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $bankAccountDetail->bank_name) }}">
                            @if($errors->has('bank_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bank_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.bank_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="branch_name">{{ trans('cruds.bankAccountDetail.fields.branch_name') }}</label>
                            <input class="form-control" type="text" name="branch_name" id="branch_name" value="{{ old('branch_name', $bankAccountDetail->branch_name) }}">
                            @if($errors->has('branch_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('branch_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.bankAccountDetail.fields.branch_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="bank_location">{{ trans('cruds.bankAccountDetail.fields.bank_location') }}</label>
                            <input class="form-control" type="text" name="bank_location" id="bank_location" value="{{ old('bank_location', $bankAccountDetail->bank_location) }}">
                            @if($errors->has('bank_location'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bank_location') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection