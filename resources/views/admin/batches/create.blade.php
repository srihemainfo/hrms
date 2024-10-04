@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.batch.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.batches.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">

                <label class="required" for="from">{{ trans('cruds.batch.fields.from') }}</label>
                <select class="form-control select2 {{ $errors->has('from') ? 'is-invalid' : '' }}" name="from" id="from" required>
                    <option value="">Selct From Year</option>
                    @foreach ($year as $entry)
                    <option value="{{ $entry }}" {{ old('from') == $entry ? 'selected' : '' }}>
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('from'))
                <span class="text-danger">{{ $errors->first('from') }}</span>
                @endif

            </div>

            <div class="form-group">
                <label class="required" for="to">{{ trans('cruds.batch.fields.to') }}</label>
                <select class="form-control select2 {{ $errors->has('to') ? 'is-invalid' : '' }}" name="to" id="to" required>
                    <option value="">Selct To Year</option>
                    @foreach ($year as $entry)
                    <option value="{{ $entry }}" {{ old('to') == $entry ? 'selected' : '' }}>
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('to'))
                <span class="text-danger">{{ $errors->first('to') }}</span>
                @endif

            </div>
            {{--
            <div class="form-group">
                <label class="required" for="from">{{ trans('cruds.batch.fields.from') }}</label>
            <input class="form-control {{ $errors->has('from') ? 'is-invalid' : '' }}" type="number" name="from" id="from" value="{{ old('from', '') }}" step="1" required>
            @if($errors->has('from'))
            <span class="text-danger">{{ $errors->first('from') }}</span>
            @endif
            <span class="help-block">{{ trans('cruds.batch.fields.from_helper') }}</span>
    </div>
    <div class="form-group">
        <label class="required" for="to">{{ trans('cruds.batch.fields.to') }}</label>
        <input class="form-control {{ $errors->has('to') ? 'is-invalid' : '' }}" type="number" name="to" id="to" value="{{ old('to', '') }}" step="1" required>
        @if($errors->has('to'))
        <span class="text-danger">{{ $errors->first('to') }}</span>
        @endif
        <span class="help-block">{{ trans('cruds.batch.fields.to_helper') }}</span>
    </div> --}}
    <div class="form-group">
        <label class="required" for="name">{{ trans('cruds.batch.fields.name') }}</label>
        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
        @if($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
        <span class="help-block">{{ trans('cruds.batch.fields.name_helper') }}</span>
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