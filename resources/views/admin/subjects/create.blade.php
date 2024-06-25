@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.subject.title_singular') }}
        </div>
        {{-- {{ dd($depts) }} --}}
        <div class="card-body">
            <form method="POST" action="{{ route('admin.subjects.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row gutters">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="regulation_id" class="required">Regulation</label>
                            <select class="form-control select2" name="regulation_id" id="regulation_id" required
                                onchange="check_regulation(this)">
                                @foreach ($regulation as $id => $entry)
                                    <option value="{{ $id }}" {{ old('regulation_id') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="department_id" class="required">Department</label>
                            <select class="form-control select2" name="department_id" id="department_id" required
                                onchange="check_dept(this)">
                                @foreach ($department as $id => $entry)
                                    <option value="{{ $id }}" {{ old('department_id') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="course_id" class="required">Course</label>
                            <select class="form-control select2" name="course_id" id="course_id" required
                                onchange="check_course(this)">

                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="semester_id" class="">Semester</label>
                            <select class="form-control select2" name="semester_id" id="semester_id">

                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="subject_type_id" class="required">Subject Type</label>
                            <select class="form-control select2" name="subject_type_id" id="subject_type_id" required>
                                @foreach ($subject_type as $id => $entry)
                                    <option value="{{ $id }}"
                                        {{ old('subject_type_id') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="subject_cat_id" class="required">Subject Category</label>
                            <select class="form-control select2" name="subject_cat_id" id="subject_cat_id" required>
                                @foreach ($subject_cat as $id => $entry)
                                    <option value="{{ $id }}"
                                        {{ old('subject_cat_id') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="lecture" class="required">Lecture</label>
                            <input class="form-control" type="number" name="lecture" id="lecture"
                                value="{{ old('lecture', '') }}" required>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="tutorial" class="required">Tutorial</label>
                            <input class="form-control" type="number" name="tutorial" id="tutorial"
                                value="{{ old('tutorial', '') }}" required>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="practical" class="required">Practical</label>
                            <input class="form-control" type="number" name="practical" id="practical"
                                value="{{ old('practical', '') }}" required>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="contact_periods" class="required">Total Contact Periods</label>
                            <input class="form-control" type="number" name="contact_periods" id="contact_periods"
                                value="{{ old('contact_periods', '') }}" required>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="credits" class="required">Credits</label>
                            <input class="form-control" type="text" name="credits" id="credits"
                                value="{{ old('credits', '') }}" required onchange="checkCredit(this)">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="name" class="required">Subject Code</label>
                            <input class="form-control" type="text" name="subject_code" id="subject_code"
                                value="{{ old('subject_code', '') }}" required>

                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        {{-- <input type="hidden" name="status" id="status" value="0"> --}}
                        <div class="form-group">
                            <label for="name" class="required">{{ trans('cruds.subject.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name"
                                value="{{ old('name', '') }}" required>
                        </div>
                    </div>
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
        window.onload = function() {
            $("#regulation_id").select2();
            $("#department_id").select2();
            $("#course_id").select2();
            $("#semester_id").select2();
            $("#subject_type_id").select2();
            $("#subject_cat_id").select2();
        }

        function check_dept(element) {
            if (element.value != '') {
                let dept = element.value;

                $.ajax({
                    url: '{{ route('admin.subjects.get_course') }}',
                    type: 'POST',
                    data: {
                        'dept': dept
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response.course != '') {
                            let course = response.course;
                            let course_len = course.length;

                            let got_course = `<option value="">Select Course</option>`;
                            for (let a = 0; a < course_len; a++) {
                                got_course +=
                                    `<option value="${course[a].id}">${course[a].name}</option>`;
                            }
                            $("#course_id").html(got_course);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
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
                });




            }
        }

        function check_course(element) {
            if (element.value != '') {
                // console.log(element.value, );
                let course = element.value;
                let dept = $("#department_id").val();
                if (dept != '' && course != '') {
                    let semester = `<option value =''>Select Semester</option>
                @foreach ($semester as $id => $entry)
                @if ($id > 2)
                            <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                                @endif
                        @endforeach`;


                    if (dept == 5) {
                        semester = `
                        @foreach ($semester as $id => $entry)
                @if ($id < 3)
                            <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                                @endif
                        @endforeach`;
                    }

                    $("#semester_id").html(semester);
                }
            }
        }

        function check_regulation(element) {
            if (element.value != '') {
                let value = element.value;
                $.ajax({
                    url: '{{ route('admin.subjects.get_sub_categories') }}',
                    type: 'POST',
                    data: {
                        'data': value
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.cat != '' && response.cat != null) {
                            let data = response.cat;
                            var regulation_cat = `<option value="">Select Subject Category</option>`;
                            for (let i = 0; i < data.length; i++) {
                                regulation_cat += `<option value="${data[i].id}">${data[i].name}</option>`;
                            }

                        } else {
                            var regulation_cat = null;
                        }

                        if (response.type != '' && response.type != null) {
                            let got_data = response.type;
                            var regulation_type = `<option value="">Select Subject Type</option>`;
                            for (let i = 0; i < got_data.length; i++) {
                                regulation_type +=
                                    `<option value="${got_data[i].id}">${got_data[i].name}</option>`;
                            }

                        } else {
                            var regulation_type = null;
                        }

                        $("#subject_cat_id").html(regulation_cat);
                        $("#subject_type_id").html(regulation_type);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
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
                });
            }
        }

        function checkCredit(element) {
            var inputValue = $(element).val();


            var numberPattern = /^[0-9]+(\.[0-9]+)?$/;

            if (numberPattern.test(inputValue)) {
                return true;
            } else {
                Swal.fire('', "Invalid input. Please enter a valid number.", 'error');
                $(element).val('');
            }
        }
    </script>
@endsection
