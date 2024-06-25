@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-center">Subject Attendance Report</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="course" class="required">Course</label>
                        <select class="form-control select2" name="course" id="course" onchange="course(this)">
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="ay" class="required">AY</label>
                        <select class="form-control select2" name="ay" id="ay">
                            <option value="">Select Academic Year</option>
                            @foreach ($academic_years as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="semester" class="required">Semester</label>
                        <select class="form-control select2" name="semester" id="semester" onchange="semester(this)">
                            <option value="">Select Semester</option>
                            @foreach ($semesters as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <div class="form-group">
                        <label for="section" class="required">Section</label>
                        <select class="form-control select2" name="section" id="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-5 col-12">
                    <div class="form-group">
                        <label for="section" class="required">Subject</label>
                        <select class="form-control select2" name="subject" id="subject" onchange="subject(this)">
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="section" class="required">Staff</label>
                        <select class="form-control select2" name="staff" id="staff">
                        </select>
                    </div>
                </div>
                <div class="col-md-1 col-12" style="text-align:right;">
                    <div class="form-group" style="padding-top: 32px;">
                        <button type="button"class="enroll_generate_bn" onclick="submit()">Go</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="report" style="display:none;max-width:100%;overflow-x:auto;z-index:0;">
        <div class="card-body" style="min-width:1100px;">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Register No</th>
                        <th>Student Name</th>
                        <th>No Of Periods Attend</th>
                        <th>Total No Of Periods</th>
                        <th>Attendance Percentage</th>
                        <th>View Attendance</th>
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
            let user_name_id = '';
            let main_class = '';

        }

        function course(element) {
            let course = element.value;
            if (course == '') {
                Swal.fire('', 'Please Choose the Course','warning');
            }

            $("#ay").val('')
            $("#semester").val('')
            $("#section").val('')
            $("#subject").val('')
            $("#staff").val('')

            $('select').select2();
        }

        function semester(element) {
            let course = $("#course").val();
            let ay = $("#ay").val();
            let semester = element.value;

            if (course == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Course','warning');
            } else if (ay == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Academic Year','warning');
            } else if (semester == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Semester','warning');
            } else {
                $.ajax({
                    url: '{{ route('admin.subject-attendance-report.get_details') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': course,
                        'ay': ay,
                        'semester': semester
                    },
                    success: function(response) {
                        // console.log(response)
                        let sections = response.sections;
                        let subjects = response.subjects;
                        let sec_len = sections.length;
                        let sub_len = subjects.length;
                        let got_sections = '<option>Select Section</option>';
                        let got_subjects = '<option>Select Subject</option>';

                        if (sec_len > 0) {
                            for (let a = 0; a < sec_len; a++) {
                                got_sections +=
                                    `<option value='${sections[a].id}'>${sections[a].section}</option>`;
                            }
                            $("select").select2();
                            $("#section").html(got_sections);
                        } else {

                            Swal.fire('', 'No Classes Found','warning');
                        }

                        if (sub_len > 0) {
                            for (let b = 0; b < sub_len; b++) {
                                got_subjects +=
                                    `<option value='${subjects[b].id}'>${subjects[b].name}  (${subjects[b].subject_code})</option>`;
                            }
                            got_subjects += '<option value="Library">Library</option>';
                            $("select").select2();
                            $("#subject").html(got_subjects);
                        } else {
                            $("select").select2();
                            Swal.fire('', 'No Subjects Found','warning');
                        }

                    }
                })
            }

        }

        function subject(element) {
            let course = $("#course").val();
            let ay = $("#ay").val();
            let semester = $("#semester").val();
            let section = $("#section").val();
            let subject = element.value;

            if (course == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Course','warning');
            } else if (ay == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Academic Year','warning');
            } else if (semester == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Semester','warning');
            } else if (section == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Section','warning');
            } else if (subject == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Subject','warning');
            } else {

                $.ajax({
                    url: '{{ route('admin.subject-attendance-report.get_staff') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': course,
                        'ay': ay,
                        'semester': semester,
                        'section': section,
                        'subject': subject
                    },
                    success: function(response) {
                        // console.log(response)
                        let status = response.status;
                        let data = response.data;
                        let data_len = data.length;
                        let staff = ``;
                        if (status == true) {

                            staff +=
                                `<option value="${data.user_name_id}" selected>${data.name}  (${data.StaffCode})</option>`;

                            $("#staff").html(staff)
                            $("select").select2();
                        } else {
                            $("#staff").html(staff);
                            $("select").select2();
                            Swal.fire('', data, 'error');
                        }


                    }
                })
            }
        }

        function submit() {
            let course = $("#course").val();
            let ay = $("#ay").val();
            let semester = $("#semester").val();
            let section = $("#section").val();
            let subject = $("#subject").val();
            let staff = $("#staff").val();

            if (course == '') {
                Swal.fire('', 'Please Choose the Course','warning');
            } else if (ay == '') {

                Swal.fire('', 'Please Choose the Academic Year','warning');
            } else if (semester == '') {

                Swal.fire('', 'Please Choose the Semester','warning');
            } else if (section == '') {

                Swal.fire('', 'Please Choose the Section','warning');
            } else if (subject == '') {

                Swal.fire('', 'Please Choose the Subject','warning');
            } else if (staff == '') {

                Swal.fire('', 'Staff Not Assigned', 'error');
            } else {
                rows = `<tr><td colspan="7">Loading...</td></tr>`;
                $("#tbody").html(rows);
                $("#report").show();
                $.ajax({
                    url: '{{ route('admin.subject-attendance-report.get_report') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': course,
                        'ay': ay,
                        'semester': semester,
                        'section': section,
                        'subject': subject,
                        'staff': staff
                    },
                    success: function(response) {
                        // console.log(response)
                        let status = response.status;
                        let data = response.data;
                        let data_len = data.length;
                        let rows = '';
                        let percentage;

                        if (status == true) {
                            if (data_len > 0) {
                                for (let k = 0; k < data_len; k++) {
                                    user_name_id = data[k]['user_name_id'];
                                    main_class = data[k]['class'];
                                    if (data[k]['percentage'] > 74) {
                                        percentage = `<td style="">${data[k]['percentage']}</td>`;

                                    } else {

                                        percentage =
                                            `<td style="background-color:#ffccc7;color:black;">${data[k]['percentage']}</td>`;
                                    }
                                    if (data[k]['registration'] == true) {

                                        rows += `<tr>
                                            <td>${k + 1}</td>
                                            <td>${data[k]['register_no']}</td>
                                            <td>${data[k]['name']}</td>
                                            <td>${data[k]['attend_hours']}</td>
                                            <td>${data[k]['total_hours']}</td>
                                           ${percentage}
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="{{ url('admin/subject-attendance-report/show/${user_name_id}/${main_class}/${subject}') }}" target="_blank">
                                                view
                                                </a>
                                             </td>
                                        </tr>`;
                                    } else {
                                        rows += `<tr>
                                            <td>${k + 1}</td>
                                            <td>${data[k]['register_no']}</td>
                                            <td>${data[k]['name']}</td>
                                            <td colspan="3">Not Registered</td>
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="{{ url('admin/subject-attendance-report/show/${user_name_id}/${main_class}/${subject}') }}" target="_blank">
                                                view
                                                </a>
                                            </td>
                                        </tr>`;
                                    }
                                }
                            } else {
                                rows = `<tr><td colspan="7">No Data Available...</td></tr>`;
                            }
                        } else {
                            rows = `<tr><td colspan="7">No Data Available...</td></tr>`;
                        }
                        $("#tbody").html(rows);

                    }
                })
            }
        }
    </script>
@endsection
