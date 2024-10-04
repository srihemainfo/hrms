@extends('layouts.admin')
@section('content')
    {{-- {{ dd($blocks_array) }} --}}
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.classRoom.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.class-rooms.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    {{-- <input type="hidden" name="name" value=""> --}}
                    <label for="department_id" class="required">Department</label>
                    <select class="form-control select2" name="department_id" id="department_id" required
                        onchange="Course(this)">
                        @foreach ($ToolsDepartment as $id => $entry)
                            <option value="{{ $id }}" {{ old('department_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('department_id'))
                        <span class="text-danger">{{ $errors->first('department_id') }}</span>
                    @endif
                    <span style="color:#007bff;">Note : If you want to create classrooms for first and second semester,
                        please choose the department "S & H"</span>
                </div>


                <div class="form-group">
                    <label for="admitted_course" class="required">Admitted Course</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="admitted_course" id="admitted_course">
                        <option value="">Please Select</option>
                        {{-- @foreach ($course as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $student->course ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach --}}
                    </select>
                </div>
                <div class="form-group">
                    <label for="admitted_course" class="required">Admitted Batch</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="admitted_batch" id="admitted_course">
                        @foreach ($Batch as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $student->course ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admitted_course" class="required">Academic year</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="academicYear" id="admitted_course">
                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $student->course ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admitted_sem" class="required">Admitted Semester</label>
                    <select class="form-control select2 {{ $errors->has('admitted_sem') ? 'is-invalid' : '' }}"
                        name="admitted_sem" id="admitted_sem">
                        @foreach ($Semester as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $student->course ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admitted_course"class="required">Admitted Section</label>
                    <select class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                        name="admitted_section" id="admitted_course">
                        @foreach ($Section as $id => $entry)
                            <option value="{{ $entry }}"
                                {{ (old('course') ? old('course') : $student->course ?? '') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="class_incharge" class="required">Class Incharge</label>
                    <select class="form-control select2" name="class_incharge" id="class_incharge" required
                        onchange="checkStaff(this)">
                        <option value="">Select Staff</option>
                        @foreach ($teachingStaff as $staff)
                            <option value="{{ $staff->user_name_id }}"
                                {{ old('class_incharge') == $staff->user_name_id ? 'selected' : '' }}>
                                {{ $staff->name }} ({{ $staff->StaffCode }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-danger" type="submit" id="submitBtn">
                        {{ trans('global.save') }}
                    </button>
                    <span id="staffSpan" class="text-primary" style="display:none;">Checking In Class Incharge...</span>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function Course(element) {
            $('#admitted_sem').empty().append($('<option>', {
                value: '',
                text: 'Please Select'
            }));

            if (element.value === '5') {
                // Show only options 1 and 2
                for (let i = 1; i <= 2; i++) {
                    $('#admitted_sem').append($('<option>', {
                        value: i,
                        text: i
                    }));
                }
            } else {
                // Show options 3 to 8
                for (let i = 3; i <= 8; i++) {
                    $('#admitted_sem').append($('<option>', {
                        value: i,
                        text: i
                    }));
                }
            }
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.check-class-dept.index') }}',
                data: {
                    'dept_id': element.value,

                },
                success: function(response) {

                    console.log(response);
                    $('#admitted_course').empty().append($('<option>', {
                        value: '',
                        text: 'Please Select'
                    }));
                    $.each(response.course, function(index, cours) {
                        console.log(cours);
                        $('#admitted_course').append($('<option>', {
                            value: cours.name,
                            text: cours.name
                        }));

                    });


                },

                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error',
                                'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                            "error");
                    }
                }

            })
        }

        function checkStaff(element) {
            if ($(element).val() != '') {
                $("#submitBtn").hide();
                $("#staffSpan").show();
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('admin.check-class-dept.check-staff') }}',
                    data: {
                        'user_name_id': $(element).val(),

                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            $("#staffSpan").hide();
                            $("#submitBtn").show();
                        } else {
                            Swal.fire('', data, 'error');
                            $("#submitBtn").hide();
                            $("#staffSpan").hide();
                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error',
                                    'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }
    </script>
@endsection
