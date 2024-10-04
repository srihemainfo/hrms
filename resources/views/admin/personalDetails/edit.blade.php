@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.personalDetail.title_singular') }}
    </div>
{{-- {{ dd($personalDetail); }} --}}
    <div class="card-body">
        <form method="POST" action="{{ route("admin.personal-details.update", [$personalDetail->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="user_name_id">{{ trans('cruds.personalDetail.fields.user_name') }}</label>
                <select class="form-control select2 {{ $errors->has('user_name') ? 'is-invalid' : '' }}" name="user_name_id" id="user_name_id">
                    @foreach($user_names as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_name_id') ? old('user_name_id') : $personalDetail->user_name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_name'))
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.user_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="age">{{ trans('cruds.personalDetail.fields.age') }}</label>
                <input class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }}" type="number" name="age" id="age" value="{{ old('age', $personalDetail->age) }}" step="1">
                @if($errors->has('age'))
                    <span class="text-danger">{{ $errors->first('age') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.age_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dob">{{ trans('cruds.personalDetail.fields.dob') }}</label>
                <input class="form-control date {{ $errors->has('dob') ? 'is-invalid' : '' }}" type="text" name="dob" id="dob" value="{{ old('dob', $personalDetail->dob) }}">
                @if($errors->has('dob'))
                    <span class="text-danger">{{ $errors->first('dob') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.dob_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.personalDetail.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $personalDetail->email) }}">
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mobile_number">{{ trans('cruds.personalDetail.fields.mobile_number') }}</label>
                <input class="form-control {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}" type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $personalDetail->mobile_number) }}">
                @if($errors->has('mobile_number'))
                    <span class="text-danger">{{ $errors->first('mobile_number') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.mobile_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="aadhar_number">{{ trans('cruds.personalDetail.fields.aadhar_number') }}</label>
                <input class="form-control {{ $errors->has('aadhar_number') ? 'is-invalid' : '' }}" type="text" name="aadhar_number" id="aadhar_number" value="{{ old('aadhar_number', $personalDetail->aadhar_number) }}">
                @if($errors->has('aadhar_number'))
                    <span class="text-danger">{{ $errors->first('aadhar_number') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.aadhar_number_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="blood_group_id">{{ trans('cruds.personalDetail.fields.blood_group') }}</label>
                <select class="form-control select2 {{ $errors->has('blood_group') ? 'is-invalid' : '' }}" name="blood_group_id" id="blood_group_id">
                    @foreach($blood_groups as $id => $entry)
                        <option value="{{ $id }}" {{ (old('blood_group_id') ? old('blood_group_id') : $personalDetail->blood_ group->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('blood_group'))
                    <span class="text-danger">{{ $errors->first('blood_group') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.blood_group_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <label for="mother_tongue_id">{{ trans('cruds.personalDetail.fields.mother_tongue') }}</label>
                <select class="form-control select2 {{ $errors->has('mother_tongue') ? 'is-invalid' : '' }}" name="mother_tongue_id" id="mother_tongue_id">
                    @foreach($mother_tongues as $id => $entry)
                        <option value="{{ $id }}" {{ (old('mother_tongue_id') ? old('mother_tongue_id') : $personalDetail->mother_tongue->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('mother_tongue'))
                    <span class="text-danger">{{ $errors->first('mother_tongue') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.mother_tongue_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label for="religion_id">{{ trans('cruds.personalDetail.fields.religion') }}</label>
                <select class="form-control select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion_id" id="religion_id">
                    @foreach($religions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('religion_id') ? old('religion_id') : $personalDetail->religion->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('religion'))
                    <span class="text-danger">{{ $errors->first('religion') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.religion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="community_id">{{ trans('cruds.personalDetail.fields.community') }}</label>
                <select class="form-control select2 {{ $errors->has('community') ? 'is-invalid' : '' }}" name="community_id" id="community_id">
                    @foreach($communities as $id => $entry)
                        <option value="{{ $id }}" {{ (old('community_id') ? old('community_id') : $personalDetail->community->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('community'))
                    <span class="text-danger">{{ $errors->first('community') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.community_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="state">{{ trans('cruds.personalDetail.fields.state') }}</label>
                <input class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" type="text" name="state" id="state" value="{{ old('state', $personalDetail->state) }}">
                @if($errors->has('state'))
                    <span class="text-danger">{{ $errors->first('state') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.state_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="country">{{ trans('cruds.personalDetail.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', $personalDetail->country) }}">
                @if($errors->has('country'))
                    <span class="text-danger">{{ $errors->first('country') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.personalDetail.fields.country_helper') }}</span>
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
