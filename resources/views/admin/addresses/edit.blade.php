@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.address.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.addresses.update", [$address->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label>{{ trans('cruds.address.fields.address_type') }}</label>
                <select class="form-control {{ $errors->has('address_type') ? 'is-invalid' : '' }}" name="address_type" id="address_type">
                    <option value disabled {{ old('address_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Address::ADDRESS_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('address_type', $address->address_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('address_type'))
                    <span class="text-danger">{{ $errors->first('address_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.address.fields.address_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name_id">{{ trans('cruds.address.fields.name') }}</label>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id">
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ (old('name_id') ? old('name_id') : $address->name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.address.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="room_no_and_street">{{ trans('cruds.address.fields.room_no_and_street') }}</label>
                <input class="form-control {{ $errors->has('room_no_and_street') ? 'is-invalid' : '' }}" type="text" name="room_no_and_street" id="room_no_and_street" value="{{ old('room_no_and_street', $address->room_no_and_street) }}">
                @if($errors->has('room_no_and_street'))
                    <span class="text-danger">{{ $errors->first('room_no_and_street') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.address.fields.room_no_and_street_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="area_name">{{ trans('cruds.address.fields.area_name') }}</label>
                <input class="form-control {{ $errors->has('area_name') ? 'is-invalid' : '' }}" type="text" name="area_name" id="area_name" value="{{ old('area_name', $address->area_name) }}">
                @if($errors->has('area_name'))
                    <span class="text-danger">{{ $errors->first('area_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.address.fields.area_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="district">{{ trans('cruds.address.fields.district') }}</label>
                <input class="form-control {{ $errors->has('district') ? 'is-invalid' : '' }}" type="text" name="district" id="district" value="{{ old('district', $address->district) }}">
                @if($errors->has('district'))
                    <span class="text-danger">{{ $errors->first('district') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.address.fields.district_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pincode">{{ trans('cruds.address.fields.pincode') }}</label>
                <input class="form-control {{ $errors->has('pincode') ? 'is-invalid' : '' }}" type="text" name="pincode" id="pincode" value="{{ old('pincode', $address->pincode) }}">
                @if($errors->has('pincode'))
                    <span class="text-danger">{{ $errors->first('pincode') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.address.fields.pincode_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="state">{{ trans('cruds.address.fields.state') }}</label>
                <input class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" type="text" name="state" id="state" value="{{ old('state', $address->state) }}">
                @if($errors->has('state'))
                    <span class="text-danger">{{ $errors->first('state') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.address.fields.state_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="country">{{ trans('cruds.address.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', $address->country) }}">
                @if($errors->has('country'))
                    <span class="text-danger">{{ $errors->first('country') }}</span>
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



@endsection