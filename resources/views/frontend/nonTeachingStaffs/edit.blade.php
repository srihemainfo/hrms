@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.nonTeachingStaff.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.non-teaching-staffs.update", [$nonTeachingStaff->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.nonTeachingStaff.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $nonTeachingStaff->name) }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonTeachingStaff.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="working_as_id">{{ trans('cruds.nonTeachingStaff.fields.working_as') }}</label>
                            <select class="form-control select2" name="working_as_id" id="working_as_id">
                                @foreach($working_as as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('working_as_id') ? old('working_as_id') : $nonTeachingStaff->working_as->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('working_as'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('working_as') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.nonTeachingStaff.fields.working_as_helper') }}</span>
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