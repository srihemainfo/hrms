@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.academicYear.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.academic-years.update", [$academicYear->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="from">{{ trans('cruds.academicYear.fields.from') }}</label>
                <input class="form-control {{ $errors->has('from') ? 'is-invalid' : '' }}" type="number" name="from" id="from" value="{{ old('from', $academicYear->from) }}" step="1" required>
                @if($errors->has('from'))
                    <span class="text-danger">{{ $errors->first('from') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicYear.fields.from_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="to">{{ trans('cruds.academicYear.fields.to') }}</label>
                <input class="form-control {{ $errors->has('to') ? 'is-invalid' : '' }}" type="number" name="to" id="to" value="{{ old('to', $academicYear->to) }}" step="1" required>
                @if($errors->has('to'))
                    <span class="text-danger">{{ $errors->first('to') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicYear.fields.to_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.academicYear.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $academicYear->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicYear.fields.name_helper') }}</span>
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