@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.address.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.addresses.update", [$address->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label>{{ trans('cruds.address.fields.address_type') }}</label>
                            <select class="form-control" name="address_type" id="address_type">
                                <option value disabled {{ old('address_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Address::ADDRESS_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('address_type', $address->address_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('address_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.address.fields.address_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="name_id">{{ trans('cruds.address.fields.name') }}</label>
                            <select class="form-control select2" name="name_id" id="name_id">
                                @foreach($names as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('name_id') ? old('name_id') : $address->name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.address.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="room_no_and_street">{{ trans('cruds.address.fields.room_no_and_street') }}</label>
                            <input class="form-control" type="text" name="room_no_and_street" id="room_no_and_street" value="{{ old('room_no_and_street', $address->room_no_and_street) }}">
                            @if($errors->has('room_no_and_street'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('room_no_and_street') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.address.fields.room_no_and_street_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="area_name">{{ trans('cruds.address.fields.area_name') }}</label>
                            <input class="form-control" type="text" name="area_name" id="area_name" value="{{ old('area_name', $address->area_name) }}">
                            @if($errors->has('area_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('area_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.address.fields.area_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="district">{{ trans('cruds.address.fields.district') }}</label>
                            <input class="form-control" type="text" name="district" id="district" value="{{ old('district', $address->district) }}">
                            @if($errors->has('district'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('district') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.address.fields.district_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="pincode">{{ trans('cruds.address.fields.pincode') }}</label>
                            <input class="form-control" type="text" name="pincode" id="pincode" value="{{ old('pincode', $address->pincode) }}">
                            @if($errors->has('pincode'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pincode') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.address.fields.pincode_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="state">{{ trans('cruds.address.fields.state') }}</label>
                            <input class="form-control" type="text" name="state" id="state" value="{{ old('state', $address->state) }}">
                            @if($errors->has('state'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('state') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.address.fields.state_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="country">{{ trans('cruds.address.fields.country') }}</label>
                            <input class="form-control" type="text" name="country" id="country" value="{{ old('country', $address->country) }}">
                            @if($errors->has('country'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('country') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.address.fields.country_helper') }}</span>
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