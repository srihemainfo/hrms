@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.staffTransferInfo.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.staff-transfer-infos.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="enroll_master_id">{{ trans('cruds.staffTransferInfo.fields.enroll_master') }}</label>
                            <select class="form-control select2" name="enroll_master_id" id="enroll_master_id" required>
                                @foreach($enroll_masters as $id => $entry)
                                    <option value="{{ $id }}" {{ old('enroll_master_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('enroll_master'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('enroll_master') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.staffTransferInfo.fields.enroll_master_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="period">{{ trans('cruds.staffTransferInfo.fields.period') }}</label>
                            <input class="form-control" type="text" name="period" id="period" value="{{ old('period', '') }}">
                            @if($errors->has('period'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('period') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.staffTransferInfo.fields.period_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from_user">{{ trans('cruds.staffTransferInfo.fields.from_user') }}</label>
                            <input class="form-control" type="text" name="from_user" id="from_user" value="{{ old('from_user', '') }}">
                            @if($errors->has('from_user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from_user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.staffTransferInfo.fields.from_user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="to_user">{{ trans('cruds.staffTransferInfo.fields.to_user') }}</label>
                            <input class="form-control" type="text" name="to_user" id="to_user" value="{{ old('to_user', '') }}">
                            @if($errors->has('to_user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to_user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.staffTransferInfo.fields.to_user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="transfer_date">{{ trans('cruds.staffTransferInfo.fields.transfer_date') }}</label>
                            <input class="form-control" type="text" name="transfer_date" id="transfer_date" value="{{ old('transfer_date', '') }}">
                            @if($errors->has('transfer_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('transfer_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.staffTransferInfo.fields.transfer_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="approved_by_user">{{ trans('cruds.staffTransferInfo.fields.approved_by_user') }}</label>
                            <input class="form-control" type="text" name="approved_by_user" id="approved_by_user" value="{{ old('approved_by_user', '') }}">
                            @if($errors->has('approved_by_user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('approved_by_user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.staffTransferInfo.fields.approved_by_user_helper') }}</span>
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