@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.odMaster.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.od-masters.update", [$odMaster->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.odMaster.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $odMaster->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.odMaster.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="level_1_role">{{ trans('cruds.odMaster.fields.level_1_role') }}</label>
                <input class="form-control {{ $errors->has('level_1_role') ? 'is-invalid' : '' }}" type="text" name="level_1_role" id="level_1_role" value="{{ old('level_1_role', $odMaster->level_1_role) }}" required>
                @if($errors->has('level_1_role'))
                    <span class="text-danger">{{ $errors->first('level_1_role') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.odMaster.fields.level_1_role_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="level_2_role">{{ trans('cruds.odMaster.fields.level_2_role') }}</label>
                <input class="form-control {{ $errors->has('level_2_role') ? 'is-invalid' : '' }}" type="text" name="level_2_role" id="level_2_role" value="{{ old('level_2_role', $odMaster->level_2_role) }}" required>
                @if($errors->has('level_2_role'))
                    <span class="text-danger">{{ $errors->first('level_2_role') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.odMaster.fields.level_2_role_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="level_3_role">{{ trans('cruds.odMaster.fields.level_3_role') }}</label>
                <input class="form-control {{ $errors->has('level_3_role') ? 'is-invalid' : '' }}" type="text" name="level_3_role" id="level_3_role" value="{{ old('level_3_role', $odMaster->level_3_role) }}" required>
                @if($errors->has('level_3_role'))
                    <span class="text-danger">{{ $errors->first('level_3_role') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.odMaster.fields.level_3_role_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="approved_by">{{ trans('cruds.odMaster.fields.approved_by') }}</label>
                <input class="form-control {{ $errors->has('approved_by') ? 'is-invalid' : '' }}" type="text" name="approved_by" id="approved_by" value="{{ old('approved_by', $odMaster->approved_by) }}" required>
                @if($errors->has('approved_by'))
                    <span class="text-danger">{{ $errors->first('approved_by') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.odMaster.fields.approved_by_helper') }}</span>
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