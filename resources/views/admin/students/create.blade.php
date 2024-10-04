@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.student.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.student.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        style="text-transform:uppercase;" type="text" name="name" id="name"
                        value="{{ old('name', '') }}" required>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif

                </div>
                <div class="form-group">
                    <label class="required" for="email">{{ 'Email' }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                        name="email" id="name" value="{{ old('email', '') }}" required>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif

                </div>
                <div class="form-group">
                    <label class="required" for="phone">{{ 'Phone Number ' }}</label>
                    <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="number"
                        name="phone" id="name" value="{{ old('phone', '') }}" required>
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif

                </div>
                @if ($shift)
                    <div class="form-group">
                        <label class="" for="shift">{{ 'Shift' }}</label>
                        <select class="form-control select2 {{ $errors->has('shift') ? 'is-invalid' : '' }} "
                            name="shift" id="shift">
                            <option value="">Select Shift</option>
                            @foreach ($shift as $id => $entry)
                                <option value="{{ $id }}" {{ old('shift') == $id ? 'selected' : '' }}>
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('shift'))
                            <span class="text-danger">{{ $errors->first('shift') }}</span>
                        @endif
                    </div>
                @endif
                <div class="form-group">
                    <label class="required" for="roll_no">{{ 'Roll Number ' }}</label>
                    <input class="form-control {{ $errors->has('roll_no') ? 'is-invalid' : '' }}" type="number"
                        name="roll_no" id="roll_no" value="{{ old('roll_no', '') }}" required>
                    @if ($errors->has('roll_no'))
                        <span class="text-danger">{{ $errors->first('roll_no') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="required" for="register_no">Register Number</label>
                    <input class="form-control {{ $errors->has('register_no') ? 'is-invalid' : '' }}" type="number"
                        name="register_no" id="register_no" value="{{ old('register_no', '') }}" step="1" required>
                    @if ($errors->has('register_no'))
                        <span class="text-danger">{{ $errors->first('register_no') }}</span>
                    @endif

                </div>
                {{-- <div class="form-group">
                    <label class="" for="enroll_master_id">{{ trans('cruds.student.fields.enroll_master') }}</label>

                    <select class="form-control select2 {{ $errors->has('enroll_master') ? 'is-invalid' : '' }}"
                        name="enroll_master_id" id="enroll_master_id">

                        @foreach ($enroll_masters as $id => $entry)
                            <option value="{{ $id }}" {{ old('enroll_master_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div> --}}
                {{-- new addition --}}
                {{-- <div class="form-group">
                <label class="required" for="roll_no">{{ trans('cruds.student.fields.roll_no') }}</label>
                <input class="form-control {{ $errors->has('roll_no') ? 'is-invalid' : '' }}" type="text" name="roll_no" id="roll_no" value="{{ old('roll_no', '') }}" required>
                @if ($errors->has('roll_no'))
                    <span class="text-danger">{{ $errors->first('roll_no') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.roll_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="student_batch">{{ trans('cruds.student.fields.student_batch') }}</label>
                <input class="form-control {{ $errors->has('student_batch') ? 'is-invalid' : '' }}" type="text" name="student_batch" id="student_batch" value="{{ old('student_batch', '') }}" required>
                @if ($errors->has('student_batch'))
                    <span class="text-danger">{{ $errors->first('student_batch') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.student_batch_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="current_semester">{{ trans('cruds.student.fields.current_semester') }}</label>
                <input class="form-control {{ $errors->has('current_semester') ? 'is-invalid' : '' }}" type="text" name="current_semester" id="current_semester" value="{{ old('current_semester', '') }}" required>
                @if ($errors->has('current_semester'))
                    <span class="text-danger">{{ $errors->first('current_semester') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.current_semester_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="section">{{ trans('cruds.student.fields.section') }}</label>
                <input class="form-control {{ $errors->has('section') ? 'is-invalid' : '' }}" type="text" name="section" id="section" value="{{ old('section', '') }}" required>
                @if ($errors->has('section'))
                    <span class="text-danger">{{ $errors->first('section') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.section_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="student_initial">{{ trans('cruds.student.fields.student_initial') }}</label>
                <input class="form-control {{ $errors->has('student_initial') ? 'is-invalid' : '' }}" type="text" name="student_initial" id="student_initial" value="{{ old('student_initial', '') }}" required>
                @if ($errors->has('student_initial'))
                    <span class="text-danger">{{ $errors->first('student_initial') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.student_initial') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="admitted_course">{{ trans('cruds.student.fields.admitted_course') }}</label>
                <input class="form-control {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}" type="text" name="admitted_course" id="admitted_course" value="{{ old('admitted_course', '') }}" required>
                @if ($errors->has('admitted_course'))
                    <span class="text-danger">{{ $errors->first('admitted_course') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.admitted_course_helper') }}</span>
            </div> --}}
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
