@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} Driver
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.driver.update", [$driver->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $driver->name) }}">
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.nonTeachingStaff.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="working_as_id">Working As</label>
                <select class="form-control select2 {{ $errors->has('working_as') ? 'is-invalid' : '' }}" name="working_as_id" id="working_as_id">
                    @foreach($working_as as $id => $entry)
                        <option value="{{ $id }}" {{ (old('working_as_id') ? old('working_as_id') : $driver->working_as->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('working_as'))
                    <span class="text-danger">{{ $errors->first('working_as') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.nonTeachingStaff.fields.working_as_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection