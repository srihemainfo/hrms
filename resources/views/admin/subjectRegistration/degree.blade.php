@extends('layouts.admin')
@section('content')
    <style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <h5 class="text-primary text-center">STUDENT SUBJECT REGISTRATION</h5>
            <h6 class="text-center">Degree Wise Report</h6>
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label class="required" for="department	">Department</label>
                        <select class="form-control select2 " name="department" id="department" required
                            onchange="check_dept(this)">
                            <option value="">Select Department</option>
                            @foreach ($departments as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course" required
                            onchange="get_sections(this)">

                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label for="academic_year" class="">Academic Year</label>
                        <select class="form-control select2" name="academic_year" id="academic_year">
                            @foreach ($academic_years as $id => $entry)
                                <option value="{{ $id }}" {{ old('academic_year') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label for="semester" class="">Semester</label>
                        <select class="form-control select2" name="semester" id="semester">
                            <option value="">Select Semester</option>
                            @foreach ($semester as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-10">
                        <label for="section" class="">Section</label>
                        <select class="form-control select2" name="section" id="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-5 col-sm-5 col-0"></div>
                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-2">
                        <div class="form-group" style="padding-top: 32px;">
                            <button type="button" id="submit" name="submit" onclick="get_data()"
                                class="enroll_generate_bn">Go</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card" id="card">
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover datatable datatable-DegreeWise text-center">
                <thead>
                    <tr>
                        <th>
                            S.No
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Academic Year
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            Section
                        </th>
                        <th>
                            Total No of Students
                        </th>
                        <th>
                            Total No of Students Registered
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="tbody">

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            $("#department").select2();
            $("#course").select2();
            $("#academic_year").select2();
            $("#semester").select2();
            $("#section").select2();
        }

        function check_dept(element) {
            if (element.value != '' && element.value != 9 && element.value != 10) {
                let dept = element.value;

                $.ajax({
                    url: '{{ route('admin.subject-registration.get_course_and_sem') }}',
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
                            $("#course").html(got_course);

                        }
                        if (response.semester != '') {
                            let semester = response.semester;
                            let semester_len = semester.length;

                            let got_semester = `<option value="">Select Semester</option>`;
                            for (let a = 0; a < semester_len; a++) {
                                got_semester +=
                                    `<option value="${semester[a]}">${semester[a]}</option>`;
                            }
                            $("#semester").html(got_semester);
                        }
                    },
                    // error: function(xhr, textStatus, errorThrown) {
                    //     console.log(xhr.responseText);
                    // }
                });

            } else {
                $("#course").val('');
                $("#academic_year").val('');
                $("#semester").val('');
                $("#section").val('');
                $("#course").select2();
                $("#academic_year").select2();
                $("#semester").select2();
                $("#section").select2();
            }
        }

        function get_sections(element) {

            if (element.value != '') {
                let id = element.value;
                $.ajax({
                    url: '{{ route('admin.subject-registration.get_sections') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': id
                    },
                    success: function(response) {

                        if (response.sections) {
                            let sections = response.sections;
                            // console.log(sections)
                            let len = sections.length;
                            let section = '<option value="">Select Section</option>';
                            for (let i = 0; i < len; i++) {
                                section += '<option value="' + sections[i].id + '">' + sections[i].section +
                                    '</option>'
                            }
                            $("#section").html(section);
                        }
                    }
                });

            }
        }

        function get_data() {
            let department = $("#department").val();
            let course = $("#course").val();
            let academic_year = $("#academic_year").val();
            let semester = $("#semester").val();
            let section = $("#section").val();

            if (department == '') {
                alert('Please Select the Department');
                return false;
            }
            if (department == '9' || department == '10') {
                Swal.fire('', 'Choosen Department is Not Valid', 'error');
                return false;
            }

            if (course == '') {
                alert('Please Select the Course');
                return false;
            }

            if (department != '' && department != 9 && department != 10 && course != '') {
                let default_row = `<tr><td colspan="10">Loading...</td></tr>`;
                $("#tbody").html(default_row);

                $.ajax({
                    url: '{{ route('admin.degree-wise-subject-registration.search') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'department': department,
                        'course': course,
                        'academic_year': academic_year,
                        'semester': semester,
                        'section': section
                    },
                    success: function(response) {
                        let row = '';
                        if (response.data) {
                            let list = response.data;
                            let len = list.length;

                            if (len > 0) {
                                for (let a = 0; a < len; a++) {
                                    var id = list[a].enroll;
                                    let url =
                                        "{{ route('admin.degree-wise-subject-registration.show', ['enroll' => ':id']) }}";
                                    url = url.replace(':id', id);
                                    row += `<tr>
                                            <td>${a + 1}</td>
                                            <td>${list[a].dept}</td>
                                            <td>${list[a].course}</td>
                                            <td>${list[a].batch}</td>
                                            <td>${list[a].ay}</td>
                                            <td>${list[a].semester}</td>
                                            <td>${list[a].section}</td>
                                            <td>${list[a].students}</td>
                                            <td>${list[a].registered}</td>
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="` + url + `" target="_blank">
                                                    {{ trans('global.view') }}
                                                </a>
                                            </td>
                                        </tr>`;
                                }
                            }

                            // console.log(response);
                        } else {
                            row += `<tr><td colspan="10">No Data Available...</td></tr>`;
                        }
                        $("#tbody").html(row);
                        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

                        dtButtons.splice(0, 2);
                        dtButtons.splice(5, 1);

                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            deferRender: true,
                            retrieve: true,
                            aaSorting: [],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };

                        let table = $('.datatable-DegreeWise').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });

                    }
                });
            }
        }
    </script>
@endsection
