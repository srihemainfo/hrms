@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.takeAttentanceStudent.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.take-attentance-students.update", [$takeAttentanceStudent->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="enroll_master_id">{{ trans('cruds.takeAttentanceStudent.fields.enroll_master') }}</label>
                            <select class="form-control select2" name="enroll_master_id" id="enroll_master_id" required>
                                @foreach($enroll_masters as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('enroll_master_id') ? old('enroll_master_id') : $takeAttentanceStudent->enroll_master->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('enroll_master'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('enroll_master') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.takeAttentanceStudent.fields.enroll_master_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="period">{{ trans('cruds.takeAttentanceStudent.fields.period') }}</label>
                            <input class="form-control" type="text" name="period" id="period" value="{{ old('period', $takeAttentanceStudent->period) }}">
                            @if($errors->has('period'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('period') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.takeAttentanceStudent.fields.period_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="taken_from">{{ trans('cruds.takeAttentanceStudent.fields.taken_from') }}</label>
                            <input class="form-control" type="text" name="taken_from" id="taken_from" value="{{ old('taken_from', $takeAttentanceStudent->taken_from) }}">
                            @if($errors->has('taken_from'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('taken_from') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.takeAttentanceStudent.fields.taken_from_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="approved_by">{{ trans('cruds.takeAttentanceStudent.fields.approved_by') }}</label>
                            <input class="form-control" type="text" name="approved_by" id="approved_by" value="{{ old('approved_by', $takeAttentanceStudent->approved_by) }}" required>
                            @if($errors->has('approved_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('approved_by') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection