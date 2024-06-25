@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.takeAttentanceStudent.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.take-attentance-students.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="enroll_master_id">{{ trans('cruds.takeAttentanceStudent.fields.enroll_master') }}</label>
                <select class="form-control select2 {{ $errors->has('enroll_master') ? 'is-invalid' : '' }}" name="enroll_master_id" id="enroll_master_id" required>
                    @foreach($enroll_masters as $id => $entry)
                        <option value="{{ $id }}" {{ old('enroll_master_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('enroll_master'))
                    <span class="text-danger">{{ $errors->first('enroll_master') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.takeAttentanceStudent.fields.enroll_master_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="period">{{ trans('cruds.takeAttentanceStudent.fields.period') }}</label>
                <input class="form-control {{ $errors->has('period') ? 'is-invalid' : '' }}" type="text" name="period" id="period" value="{{ old('period', '') }}">
                @if($errors->has('period'))
                    <span class="text-danger">{{ $errors->first('period') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.takeAttentanceStudent.fields.period_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="taken_from">{{ trans('cruds.takeAttentanceStudent.fields.taken_from') }}</label>
                <input class="form-control {{ $errors->has('taken_from') ? 'is-invalid' : '' }}" type="text" name="taken_from" id="taken_from" value="{{ old('taken_from', '') }}">
                @if($errors->has('taken_from'))
                    <span class="text-danger">{{ $errors->first('taken_from') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.takeAttentanceStudent.fields.taken_from_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="approved_by">{{ trans('cruds.takeAttentanceStudent.fields.approved_by') }}</label>
                <input class="form-control {{ $errors->has('approved_by') ? 'is-invalid' : '' }}" type="text" name="approved_by" id="approved_by" value="{{ old('approved_by', '') }}" required>
                @if($errors->has('approved_by'))
                    <span class="text-danger">{{ $errors->first('approved_by') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.takeAttentanceStudent.fields.approved_by_helper') }}</span>
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