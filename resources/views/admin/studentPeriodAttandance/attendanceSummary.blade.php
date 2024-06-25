@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <span class="text-primary" style="font-size:1.2rem">Attendance Summary</span>
        </div>
        @php
            $role_id = auth()->user()->roles[0]->id;
            $userDept = auth()->user()->dept;
            if ($role_id == 14) {
                if ($userDept == 'S & H') {
                    $semester = [1, 2];
                } else {
                    $semester = [3, 4, 5, 6, 7, 8];
                }
            } else {
                $semester = [1, 2, 3, 4, 5, 6, 7, 8];
            }
        @endphp
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="department" class="required">Department</label>
                        <select class="form-control select2" name="department" id="department" onchange="check_dept(this)">
                            @foreach ($departments as $id => $entry)
                                @if ($id != 9 && $id != 10)
                                    @if ($userDept != null)
                                        @if ($userDept == $entry)
                                            <option value="">Select Department</option>
                                            <option value="{{ $id }}">{{ $entry }}</option>
                                        @endif
                                    @else
                                        <option value="{{ $id }}">{{ $entry }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="course" class="required">Course</label>
                        <select class="form-control select2" name="course" id="course" onchange="check_course(this)">
                            <option value="">Select Course</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="ay" class="required">AY</label>
                        <select class="form-control select2" name="ay" id="ay">
                            @foreach ($academic_years as $id => $entry)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="semester" class="required">Semester</label>
                        <select class="form-control select2" name="semester" id="semester" required>
                            <option value="">Select Semester</option>
                            @foreach ($semester as $sem)
                                <option value="{{ $sem }}">{{ $sem }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="section" class="required">Section</label>
                        <select class="form-control select2" name="section" id="section" required>
                            <option value="">Section</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="search_date" class="required">Date</label>
                        <input type="text" class="form-control date" id="search_date" name="search_date">
                    </div>
                </div>
            </div>
            <div style="text-align:right;">
                <button class="enroll_generate_bn" onclick="get_data()">Submit</button>
            </div>
        </div>
    </div>

    <div class="card" id="lister" style="display:none;">
        <div class="card-header">Attendance PeriodWise List</div>
        <div class="card-body">
            <table id="data_table"
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-AttendanceSummary text-center">
                <thead>
                    <tr>
                        <th>Alloted Period</th>
                        <th>Taken Period</th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Staff Name</th>
                        <th>No Of Students</th>
                        <th>Class / Batch Strength</th>
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

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            $("#search_date").val(today);

        }

        function check_dept(element) {
            let dept = element.value;
            let courses;
            if (dept == '') {

                Swal.fire('', 'Please Select the Department', 'warning');
            } else {
                $.ajax({
                    url: '{{ route('admin.student-attendance-summary.get_courses') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'department': dept
                    },
                    success: function(response) {
                        // console.log(response)
                        let courses = response.courses;

                        let courses_len = courses.length;


                        let got_courses = '<option>Select Course</option>';


                        if (courses_len > 0) {
                            for (let i = 0; i < courses_len; i++) {
                                got_courses +=
                                    `<option value="${courses[i].id}">${courses[i].short_form}</option>`;
                            }
                        }

                        let semesters = '<option>Select Semester</option>'
                        if (dept == 5) {
                            semesters += `<option>1</option><option>2</option>`;
                        } else {
                            semesters +=
                                `<option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option>`;
                        }

                        $("#semester").html(semesters);
                        $("#course").html(got_courses);
                        $("select").select2();

                    }
                })
            }

        }

        function check_course(element) {
            let course = element.value;
            let dept = $("#department").val();
            if (department == '') {
                Swal.fire('', 'Please Choose the Department', 'warning');
            } else if (course == '') {
                Swal.fire('', 'Please Choose the Course', 'warning');
            } else {
                $.ajax({
                    url: '{{ route('admin.student-attendance-summary.get_sections') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': course
                    },
                    success: function(response) {
                        // console.log(response)
                        let sections = response.sections;

                        let sections_len = sections.length;

                        let got_sections = '<option>Select Section</option>';

                        if (sections_len > 0) {
                            for (let i = 0; i < sections_len; i++) {
                                got_sections +=
                                    `<option value="${sections[i].id}">${sections[i].section}</option>`;
                            }
                        }
                        $("#section").html(got_sections);
                        $("select").select2();

                    }
                })
            }
        }

        function get_data() {

            let dept = $("#department").val();
            let course = $("#course").val();
            let ay = $("#ay").val();
            let sem = $("#semester").val();
            let section = $("#section").val();
            let search_date = $("#search_date").val();

            if (department == '') {
                Swal.fire('', 'Please Choose the Department', 'warning');
            } else if (course == '') {
                Swal.fire('', 'Please Choose the Course', 'warning');

            } else if (ay == '') {
                Swal.fire('', 'Please Choose the Academic Year', 'warning');

            } else if (sem == '') {
                Swal.fire('', 'Please Choose the Semester', 'warning');

            } else if (section == '') {
                Swal.fire('', 'Please Choose the Section', 'warning');

            } else if (search_date == '') {
                Swal.fire('', 'Please Choose the Date', 'warning');

            } else {

                let loading = `<tr><td colspan="7"> Loading...</td></tr>`;
                $("#tbody").html(loading);
                $("#lister").show();

                $.ajax({
                    url: '{{ route('admin.student-attendance-summary.get_data') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'department': dept,
                        'course': course,
                        'ay': ay,
                        'semester': sem,
                        'section': section,
                        'date': search_date
                    },
                    success: function(response) {
                        // console.log(response)
                        let data = response.status;
                        let check_data_type = typeof data;
                        let status, subject_code;
                        let rows = '';
                        if (check_data_type == 'string') {
                            Swal.fire('', data, 'warning');

                            rows += `<tr><td colspan="7"> No Data Available...</td></tr>`;
                        } else {
                            let data_len = data.length;

                            for (let a = 0; a < data_len; a++) {

                                let checky = typeof data[a]['status'];
                                if (checky == 'string') {

                                    status = '<td style="background-color:#fff88f;color:black;">' + data[a][
                                        'status'
                                    ] + '</td>';

                                } else {
                                    if (data[a]['status'] === false) {
                                        status =
                                            '<td style="background-color:#ffccc7;color:black;">Not Yet Taken</td>';
                                    } else if (data[a]['status'] === true) {
                                        status = `<td>${data[a]['attend_students']}</td>`;
                                    }
                                }
                                if (data[a]['subject_code'] == null) {
                                    subject_code = '';
                                } else {
                                    subject_code = data[a]['subject_code'];
                                }

                                rows += `<tr>
                                           <td>${data[a]['alloted_periods']}</td>
                                           <td>${data[a]['period']}</td>
                                           <td>${subject_code}</td>
                                           <td>${data[a]['subject_name']}</td>
                                           <td>${data[a]['staff_name']}  (${data[a]['staff_code']})</td>
                                           ${status}
                                           <td>${data[a]['total_students']}</td>
                                        </tr>`;
                            }

                        }
                        $("#tbody").html(rows);
                        // let data_table = $('.datatable-AttendanceSummary').DataTable();
                        // data_table.destroy();
                        // let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                        // let dtOverrideGlobals = {
                        //     buttons: dtButtons,
                        //     retrieve: true,
                        //     aaSorting: [],
                        //     orderCellsTop: true,
                        //     order: [
                        //         [1, 'desc']
                        //     ],
                        //     pageLength: 100,
                        // };
                        // let table = $('.datatable-AttendanceSummary').DataTable(dtOverrideGlobals);
                        // $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        //     $($.fn.dataTable.tables(true)).DataTable()
                        //         .columns.adjust();
                        // });

                    }
                })
            }
        }
    </script>
@endsection
