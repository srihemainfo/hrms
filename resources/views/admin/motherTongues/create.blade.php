@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.motherTongue.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.mother-tongues.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="mother_tongue">{{ trans('cruds.motherTongue.fields.mother_tongue') }}</label>
                <input class="form-control {{ $errors->has('mother_tongue') ? 'is-invalid' : '' }}" type="text" name="mother_tongue" id="mother_tongue" value="{{ old('mother_tongue', '') }}">
                @if($errors->has('mother_tongue'))
                    <span class="text-danger">{{ $errors->first('mother_tongue') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.motherTongue.fields.mother_tongue_helper') }}</span>
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