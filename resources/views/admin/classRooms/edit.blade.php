@extends('layouts.admin')
@section('content')
    {{-- {{ dd($teaching_staffs) }} --}}
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.classRoom.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.class-rooms.update', [$classRoom->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <input type="hidden" name="name" value="{{ $classRoom->name }}">
                    <label for="department_id">Department</label>
                    <select class="form-control select2" name="department_id" id="department_id" onchange="dept(this)" disabled>
                        @foreach ($ToolsDepartment as $id => $entry)
                            <option value="{{ $id }}"
                                {{ (old('department_id') ? old('department_id') : $teachingStaff->Dept ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admitted_course" class="required">Admitted Course</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="admitted_course" id="admitted_course" disabled>
                        @foreach ($course as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $teachingStaff->course_1 ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="academicYear" class="required">Academic year</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="academicYear" id="academicYear" disabled>
                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $teachingStaff->accademicYear_1 ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admitted_batch" class="required">Admitted Batch</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="admitted_batch" id="admitted_batch" disabled>
                        @foreach ($Batch as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $teachingStaff->batch_1 ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admitted_sem" class="required">Admitted Semester</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="admitted_sem" id="admitted_sem" disabled>
                        @foreach ($Semester as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $teachingStaff->sem_1 ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admitted_section"class="required">Admitted Section</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="admitted_section" id="admitted_section" disabled>
                        @foreach ($Section as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $teachingStaff->section_1 ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="class_incharge">Class Incharge</label>
                    <select class="form-control select2" name="class_incharge" id="class_incharge">
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->user_name_id }}"
                                {{ (old('class_incharge') ? old('class_incharge') : $classRoom->class_incharge ?? '') == $staff->user_name_id ? 'selected' : '' }}>
                                {{ $staff->name }} ({{ $staff->StaffCode }})</option>
                        @endforeach
                    </select>
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
@section('scripts')
    <script>

    </script>
@endsection
