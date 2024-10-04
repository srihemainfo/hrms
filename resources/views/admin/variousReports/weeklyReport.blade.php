@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select-checkbox:before {
            content: none !important;
        }

        .staff_label {
            width: 100%;
            margin: auto;
            margin-bottom: 3px;
            border-radius: 2px;
            padding: 3px;
            box-sizing: border-box;
            box-shadow: 0px 1px 3px grey;
            font-size: 0.75rem;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <div class="text-center">Weekly Class Report</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="department" class="required">Department</label>
                        <select class="form-control select2" name="department" id="department" onchange="check_dept(this)">
                            <option value="">Select Department</option>
                            @foreach ($departments as $id => $entry)
                                @if ($id != 9 && $id != 10)
                                    <option value="{{ $id }}">{{ $entry }}</option>
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
                            <option value="">Select AY</option>
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
                            @foreach ($semesters as $sem)
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
                        <label for="week" class="required">Week</label>
                        <input type="week" class="form-control" id="week" name="week">
                    </div>
                </div>
            </div>
            <div style="text-align:right;">
                <button class="enroll_generate_bn" onclick="get_data()">Submit</button>
            </div>
        </div>
    </div>

    <div class="card" id="lister" style="display:none;max-width:100%;overflow-x:auto;">
        <div class="card-body" style="min-width:1100px;">
            <table id="data_table"
                class="table table-bordered table-hover ajaxTable datatable datatable-WeeklyReport text-center">
                <thead id="thead">
                    <tr>
                        <th>PERIOD / DAY</th>
                        <th>MONDAY</th>
                        <th>TUESDAY</th>
                        <th>WEDNESDAY</th>
                        <th>THURSDAY</th>
                        <th>FRIDAY</th>
                        <th>SATURDAY</th>
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
        function check_dept(element) {
            let dept = element.value;
            let courses;
            $("#course").val('');
            $("#ay").val('');
            $("#semester").val('');
            $("#section").html(`<option value="">Section</option>`);
            $("select").select2();
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


                        let got_courses = '<option value="">Select Course</option>';
                        // let got_sections = '<option value="">Select Section</option>';

                        if (courses_len > 0) {
                            for (let i = 0; i < courses_len; i++) {
                                got_courses +=
                                    `<option value="${courses[i].id}">${courses[i].short_form}</option>`;

                                // let sections = courses[i].sections;
                                // let sections_len = sections.length;

                                // if (sections_len > 0) {
                                //     for (let k = 0; k < sections_len; k++) {
                                //         got_sections +=
                                //             `<option value="${sections[k].id}">${sections[k].section}</option>`;
                                //     }
                                // }
                            }
                        }

                        let semesters = '<option value="">Select Semester</option>'
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
            let week = $("#week").val();

            if (dept == '') {
                Swal.fire('', 'Please Choose the Department', 'warning');
            } else if (course == '') {
                Swal.fire('', 'Please Choose the Course', 'warning');

            } else if (ay == '') {
                Swal.fire('', 'Please Choose the Academic Year', 'warning');

            } else if (sem == '') {
                Swal.fire('', 'Please Choose the Semester', 'warning');

            } else if (section == '') {
                Swal.fire('', 'Please Choose the Section', 'warning');

            } else if (week == '') {
                Swal.fire('', 'Please Choose the Week', 'warning');

            } else {

                let loading = `<tr><td colspan="7"> Loading...</td></tr>`;
                $("#tbody").html(loading);
                $("#lister").show();

                $.ajax({
                    url: '{{ route('admin.weekly-class-report.weekly-report') }}',
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
                        'week': week
                    },
                    success: function(response) {

                        let status = response.status;
                        console.log(response)
                        let period_one = `<tr><td>1</td>`;
                        let period_two = `<tr><td>2</td>`;
                        let period_three = `<tr><td>3</td>`;
                        let period_four = `<tr><td>4</td>`;
                        let period_five = `<tr><td>5</td>`;
                        let period_six = `<tr><td>6</td>`;
                        let period_seven = `<tr><td>7</td>`;

                        if (status == true) {

                            let one = response.one;
                            let two = response.two;
                            let three = response.three;
                            let four = response.four;
                            let five = response.five;
                            let six = response.six;
                            let seven = response.seven;
                            let weekDates = response.dates;

                            let theader = `<tr>
                                              <th>PERIOD / DAY</th>
                                              <th><div>MONDAY    </div> <span style="font-size:0.8rem;">(${weekDates[1]})</span></th>
                                              <th><div>TUESDAY   </div> <span style="font-size:0.8rem;">(${weekDates[2]})</span></th>
                                              <th><div>WEDNESDAY </div> <span style="font-size:0.8rem;">(${weekDates[3]})</span></th>
                                              <th><div>THURSDAY  </div> <span style="font-size:0.8rem;">(${weekDates[4]})</span></th>
                                              <th><div>FRIDAY    </div> <span style="font-size:0.8rem;">(${weekDates[5]})</span></th>
                                              <th><div>SATURDAY  </div> <span style="font-size:0.8rem;">(${weekDates[6]})</span></th>
                                          </tr>`;
                            $("#thead").html(theader);
                            if (one.hasOwnProperty('MONDAY')) {
                                if (one['MONDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < one['MONDAY'].length; c++) {
                                        if (one['MONDAY'][c].exam == false) {
                                            var sub_name = one['MONDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = one['MONDAY'][c].staffs.name + '  ' + '  (' +
                                                one[
                                                    'MONDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = one['MONDAY'][c].subjects.name + '  ' + '  (' +
                                                    one[
                                                        'MONDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
                                                   <div><b>${sub_label}</b></div>
                                                   <b class="text-primary">${subjectDetails}</b>
                                                   <div><b>Staff</b></div>
                                                   <b class="text-primary">${staffDetails}</b>
                                                 </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
                                                   <div><b>CAT EXAM</b></div>
                                                   <b class="text-primary">${one['MONDAY'][c].exam_name}</b>
                                                 </div>`;
                                        }
                                    }
                                    period_one += `<td>${variable}</td>`;
                                } else {
                                    period_one += `<td></td>`;
                                }
                            } else {
                                period_one += `<td></td>`;
                            }
                            if (one.hasOwnProperty('TUESDAY')) {
                                if (one['TUESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < one['TUESDAY'].length; c++) {
                                        if (one['TUESDAY'][c].exam == false) {
                                            var sub_name = one['TUESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = one['TUESDAY'][c].staffs.name + '  ' + '  (' +
                                                one[
                                                    'TUESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = one['TUESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    one[
                                                        'TUESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${one['TUESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }
                                    period_one += `<td>${variable}</td>`;
                                } else {
                                    period_one += `<td></td>`;
                                }
                            } else {
                                period_one += `<td></td>`;
                            }
                            if (one.hasOwnProperty('WEDNESDAY')) {
                                if (one['WEDNESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < one['WEDNESDAY'].length; c++) {
                                        if (one['WEDNESDAY'][c].exam == false) {
                                            var sub_name = one['WEDNESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = one['WEDNESDAY'][c].staffs.name + '  ' + '  (' +
                                                one[
                                                    'WEDNESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = one['WEDNESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    one[
                                                        'WEDNESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${one['WEDNESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_one += `<td>${variable}</td>`;
                                } else {
                                    period_one += `<td></td>`;
                                }
                            } else {
                                period_one += `<td></td>`;
                            }
                            if (one.hasOwnProperty('THURSDAY')) {
                                if (one['THURSDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < one['THURSDAY'].length; c++) {
                                        if (one['THURSDAY'][c].exam == false) {
                                            var sub_name = one['THURSDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = one['THURSDAY'][c].staffs.name + '  ' + '  (' +
                                                one[
                                                    'THURSDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = one['THURSDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    one[
                                                        'THURSDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${one['THURSDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_one += `<td>${variable}</td>`;
                                } else {
                                    period_one += `<td></td>`;
                                }
                            } else {
                                period_one += `<td></td>`;
                            }
                            if (one.hasOwnProperty('FRIDAY')) {
                                if (one['FRIDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < one['FRIDAY'].length; c++) {
                                        if (one['FRIDAY'][c].exam == false) {
                                            var sub_name = one['FRIDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = one['FRIDAY'][c].staffs.name + '  ' + '  (' +
                                                one[
                                                    'FRIDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = one['FRIDAY'][c].subjects.name + '  ' + '  (' +
                                                    one[
                                                        'FRIDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${one['FRIDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_one += `<td>${variable}</td>`;
                                } else {
                                    period_one += `<td></td>`;
                                }
                            } else {
                                period_one += `<td></td>`;
                            }
                            if (one.hasOwnProperty('SATURDAY')) {
                                if (one['SATURDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < one['SATURDAY'].length; c++) {
                                        if (one['SATURDAY'][c].exam == false) {
                                            var sub_name = one['SATURDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = one['SATURDAY'][c].staffs.name + '  ' + '  (' +
                                                one[
                                                    'SATURDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = one['SATURDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    one[
                                                        'SATURDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${one['SATURDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_one += `<td>${variable}</td>`;
                                } else {
                                    period_one += `<td></td>`;
                                }
                            } else {
                                period_one += `<td></td>`;
                            }
                            if (two.hasOwnProperty('MONDAY')) {
                                if (two['MONDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < two['MONDAY'].length; c++) {
                                        if (two['MONDAY'][c].exam == false) {
                                            var sub_name = two['MONDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = two['MONDAY'][c].staffs.name + '  ' + '  (' +
                                                two[
                                                    'MONDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = two['MONDAY'][c].subjects.name + '  ' + '  (' +
                                                    two[
                                                        'MONDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${two['MONDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_two += `<td>${variable}</td>`;
                                } else {
                                    period_two += `<td></td>`;
                                }
                            } else {
                                period_two += `<td></td>`;
                            }
                            if (two.hasOwnProperty('TUESDAY')) {
                                if (two['TUESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < two['TUESDAY'].length; c++) {
                                        if (two['TUESDAY'][c].exam == false) {
                                            var sub_name = two['TUESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = two['TUESDAY'][c].staffs.name + '  ' + '  (' +
                                                two[
                                                    'TUESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = two['TUESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    two[
                                                        'TUESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${two['TUESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_two += `<td>${variable}</td>`;
                                } else {
                                    period_two += `<td></td>`;
                                }
                            } else {
                                period_two += `<td></td>`;
                            }
                            if (two.hasOwnProperty('WEDNESDAY')) {
                                if (two['WEDNESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < two['WEDNESDAY'].length; c++) {
                                        if (two['WEDNESDAY'][c].exam == false) {
                                            var sub_name = two['WEDNESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = two['WEDNESDAY'][c].staffs.name + '  ' + '  (' +
                                                two[
                                                    'WEDNESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = two['WEDNESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    two[
                                                        'WEDNESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${two['WEDNESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_two += `<td>${variable}</td>`;
                                } else {
                                    period_two += `<td></td>`;
                                }
                            } else {
                                period_two += `<td></td>`;
                            }
                            if (two.hasOwnProperty('THURSDAY')) {
                                if (two['THURSDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < two['THURSDAY'].length; c++) {
                                        if (two['THURSDAY'][c].exam == false) {
                                            var sub_name = two['THURSDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = two['THURSDAY'][c].staffs.name + '  ' + '  (' +
                                                two[
                                                    'THURSDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = two['THURSDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    two[
                                                        'THURSDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${two['THURSDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_two += `<td>${variable}</td>`;
                                } else {
                                    period_two += `<td></td>`;
                                }
                            } else {
                                period_two += `<td></td>`;
                            }
                            if (two.hasOwnProperty('FRIDAY')) {
                                if (two['FRIDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < two['FRIDAY'].length; c++) {
                                        if (two['FRIDAY'][c].exam == false) {
                                            var sub_name = two['FRIDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = two['FRIDAY'][c].staffs.name + '  ' + '  (' +
                                                two[
                                                    'FRIDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = two['FRIDAY'][c].subjects.name + '  ' + '  (' +
                                                    two[
                                                        'FRIDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${two['FRIDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_two += `<td>${variable}</td>`;
                                } else {
                                    period_two += `<td></td>`;
                                }
                            } else {
                                period_two += `<td></td>`;
                            }
                            if (two.hasOwnProperty('SATURDAY')) {
                                if (two['SATURDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < two['SATURDAY'].length; c++) {
                                        if (two['SATURDAY'][c].exam == false) {
                                            var sub_name = two['SATURDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = two['SATURDAY'][c].staffs.name + '  ' + '  (' +
                                                two[
                                                    'SATURDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = two['SATURDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    two[
                                                        'SATURDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${two['SATURDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_two += `<td>${variable}</td>`;
                                } else {
                                    period_two += `<td></td>`;
                                }
                            } else {
                                period_two += `<td></td>`;
                            }
                            if (three.hasOwnProperty('MONDAY')) {
                                if (three['MONDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < three['MONDAY'].length; c++) {
                                        if (three['MONDAY'][c].exam == false) {
                                            var sub_name = three['MONDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = three['MONDAY'][c].staffs.name + '  ' + '  (' +
                                                three[
                                                    'MONDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = three['MONDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    three[
                                                        'MONDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${three['MONDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_three += `<td>${variable}</td>`;
                                } else {
                                    period_three += `<td></td>`;
                                }
                            } else {
                                period_three += `<td></td>`;
                            }
                            if (three.hasOwnProperty('TUESDAY')) {
                                if (three['TUESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < three['TUESDAY'].length; c++) {
                                        if (three['TUESDAY'][c].exam == false) {
                                            var sub_name = three['TUESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = three['TUESDAY'][c].staffs.name + '  ' + '  (' +
                                                three[
                                                    'TUESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = three['TUESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    three[
                                                        'TUESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${three['TUESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_three += `<td>${variable}</td>`;
                                } else {
                                    period_three += `<td></td>`;
                                }
                            } else {
                                period_three += `<td></td>`;
                            }
                            if (three.hasOwnProperty('WEDNESDAY')) {
                                if (three['WEDNESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < three['WEDNESDAY'].length; c++) {
                                        if (three['WEDNESDAY'][c].exam == false) {
                                            var sub_name = three['WEDNESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = three['WEDNESDAY'][c].staffs.name + '  ' +
                                                '  (' +
                                                three[
                                                    'WEDNESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = three['WEDNESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    three[
                                                        'WEDNESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${three['WEDNESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_three += `<td>${variable}</td>`;
                                } else {
                                    period_three += `<td></td>`;
                                }
                            } else {
                                period_three += `<td></td>`;
                            }
                            if (three.hasOwnProperty('THURSDAY')) {
                                if (three['THURSDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < three['THURSDAY'].length; c++) {
                                        if (three['THURSDAY'][c].exam == false) {
                                            var sub_name = three['THURSDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = three['THURSDAY'][c].staffs.name + '  ' + '  (' +
                                                three[
                                                    'THURSDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = three['THURSDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    three[
                                                        'THURSDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${three['THURSDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_three += `<td>${variable}</td>`;
                                } else {
                                    period_three += `<td></td>`;
                                }
                            } else {
                                period_three += `<td></td>`;
                            }
                            if (three.hasOwnProperty('FRIDAY')) {
                                if (three['FRIDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < three['FRIDAY'].length; c++) {
                                        if (three['FRIDAY'][c].exam == false) {
                                            var sub_name = three['FRIDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = three['FRIDAY'][c].staffs.name + '  ' + '  (' +
                                                three[
                                                    'FRIDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = three['FRIDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    three[
                                                        'FRIDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${three['FRIDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_three += `<td>${variable}</td>`;
                                } else {
                                    period_three += `<td></td>`;
                                }
                            } else {
                                period_three += `<td></td>`;
                            }
                            if (three.hasOwnProperty('SATURDAY')) {
                                if (three['SATURDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < three['SATURDAY'].length; c++) {
                                        if (three['SATURDAY'][c].exam == false) {
                                            var sub_name = three['SATURDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = three['SATURDAY'][c].staffs.name + '  ' + '  (' +
                                                three[
                                                    'SATURDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = three['SATURDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    three[
                                                        'SATURDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${three['SATURDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_three += `<td>${variable}</td>`;
                                } else {
                                    period_three += `<td></td>`;
                                }
                            } else {
                                period_three += `<td></td>`;
                            }
                            if (four.hasOwnProperty('MONDAY')) {
                                if (four['MONDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < four['MONDAY'].length; c++) {
                                        if (four['MONDAY'][c].exam == false) {
                                            var sub_name = four['MONDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = four['MONDAY'][c].staffs.name + '  ' + '  (' +
                                                four[
                                                    'MONDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = four['MONDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    four[
                                                        'MONDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${four['MONDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_four += `<td>${variable}</td>`;
                                } else {
                                    period_four += `<td></td>`;
                                }
                            } else {
                                period_four += `<td></td>`;
                            }
                            if (four.hasOwnProperty('TUESDAY')) {
                                if (four['TUESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < four['TUESDAY'].length; c++) {
                                        if (four['TUESDAY'][c].exam == false) {
                                            var sub_name = four['TUESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = four['TUESDAY'][c].staffs.name + '  ' + '  (' +
                                                four[
                                                    'TUESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = four['TUESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    four[
                                                        'TUESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${four['TUESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_four += `<td>${variable}</td>`;
                                } else {
                                    period_four += `<td></td>`;
                                }
                            } else {
                                period_four += `<td></td>`;
                            }
                            if (four.hasOwnProperty('WEDNESDAY')) {
                                if (four['WEDNESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < four['WEDNESDAY'].length; c++) {
                                        if (four['WEDNESDAY'][c].exam == false) {
                                            var sub_name = four['WEDNESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = four['WEDNESDAY'][c].staffs.name + '  ' + '  (' +
                                                four[
                                                    'WEDNESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = four['WEDNESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    four[
                                                        'WEDNESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${four['WEDNESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_four += `<td>${variable}</td>`;
                                } else {
                                    period_four += `<td></td>`;
                                }
                            } else {
                                period_four += `<td></td>`;
                            }
                            if (four.hasOwnProperty('THURSDAY')) {
                                if (four['THURSDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < four['THURSDAY'].length; c++) {
                                        if (four['THURSDAY'][c].exam == false) {
                                            var sub_name = four['THURSDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = four['THURSDAY'][c].staffs.name + '  ' + '  (' +
                                                four[
                                                    'THURSDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = four['THURSDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    four[
                                                        'THURSDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${four['THURSDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_four += `<td>${variable}</td>`;
                                } else {
                                    period_four += `<td></td>`;
                                }
                            } else {
                                period_four += `<td></td>`;
                            }
                            if (four.hasOwnProperty('FRIDAY')) {
                                if (four['FRIDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < four['FRIDAY'].length; c++) {
                                        if (four['FRIDAY'][c].exam == false) {
                                            var sub_name = four['FRIDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = four['FRIDAY'][c].staffs.name + '  ' + '  (' +
                                                four[
                                                    'FRIDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = four['FRIDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    four[
                                                        'FRIDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${four['FRIDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_four += `<td>${variable}</td>`;
                                } else {
                                    period_four += `<td></td>`;
                                }
                            } else {
                                period_four += `<td></td>`;
                            }
                            if (four.hasOwnProperty('SATURDAY')) {
                                if (four['SATURDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < four['SATURDAY'].length; c++) {
                                        if (four['SATURDAY'][c].exam == false) {
                                            var sub_name = four['SATURDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = four['SATURDAY'][c].staffs.name + '  ' + '  (' +
                                                four[
                                                    'SATURDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = four['SATURDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    four[
                                                        'SATURDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${four['SATURDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_four += `<td>${variable}</td>`;
                                } else {
                                    period_four += `<td></td>`;
                                }
                            } else {
                                period_four += `<td></td>`;
                            }
                            if (five.hasOwnProperty('MONDAY')) {
                                if (five['MONDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < five['MONDAY'].length; c++) {
                                        if (five['MONDAY'][c].exam == false) {
                                            var sub_name = five['MONDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = five['MONDAY'][c].staffs.name + '  ' + '  (' +
                                                five[
                                                    'MONDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = five['MONDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    five[
                                                        'MONDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${five['MONDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_five += `<td>${variable}</td>`;
                                } else {
                                    period_five += `<td></td>`;
                                }
                            } else {
                                period_five += `<td></td>`;
                            }
                            if (five.hasOwnProperty('TUESDAY')) {
                                if (five['TUESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < five['TUESDAY'].length; c++) {
                                        if (five['TUESDAY'][c].exam == false) {
                                            var sub_name = five['TUESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = five['TUESDAY'][c].staffs.name + '  ' + '  (' +
                                                five[
                                                    'TUESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = five['TUESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    five[
                                                        'TUESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${five['TUESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_five += `<td>${variable}</td>`;
                                } else {
                                    period_five += `<td></td>`;
                                }
                            } else {
                                period_five += `<td></td>`;
                            }
                            if (five.hasOwnProperty('WEDNESDAY')) {
                                if (five['WEDNESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < five['WEDNESDAY'].length; c++) {
                                        if (five['WEDNESDAY'][c].exam == false) {
                                            var sub_name = five['WEDNESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = five['WEDNESDAY'][c].staffs.name + '  ' + '  (' +
                                                five[
                                                    'WEDNESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = five['WEDNESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    five[
                                                        'WEDNESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${five['WEDNESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_five += `<td>${variable}</td>`;
                                } else {
                                    period_five += `<td></td>`;
                                }
                            } else {
                                period_five += `<td></td>`;
                            }
                            if (five.hasOwnProperty('THURSDAY')) {
                                if (five['THURSDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < five['THURSDAY'].length; c++) {
                                        if (five['THURSDAY'][c].exam == false) {
                                            var sub_name = five['THURSDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = five['THURSDAY'][c].staffs.name + '  ' + '  (' +
                                                five[
                                                    'THURSDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = five['THURSDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    five[
                                                        'THURSDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${five['THURSDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_five += `<td>${variable}</td>`;
                                } else {
                                    period_five += `<td></td>`;
                                }
                            } else {
                                period_five += `<td></td>`;
                            }
                            if (five.hasOwnProperty('FRIDAY')) {
                                if (five['FRIDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < five['FRIDAY'].length; c++) {
                                        if (five['FRIDAY'][c].exam == false) {
                                            var sub_name = five['FRIDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = five['FRIDAY'][c].staffs.name + '  ' + '  (' +
                                                five[
                                                    'FRIDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = five['FRIDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    five[
                                                        'FRIDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${five['FRIDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_five += `<td>${variable}</td>`;
                                } else {
                                    period_five += `<td></td>`;
                                }
                            } else {
                                period_five += `<td></td>`;
                            }
                            if (five.hasOwnProperty('SATURDAY')) {
                                if (five['SATURDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < five['SATURDAY'].length; c++) {
                                        if (five['SATURDAY'][c].exam == false) {
                                            var sub_name = five['SATURDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = five['SATURDAY'][c].staffs.name + '  ' + '  (' +
                                                five[
                                                    'SATURDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = five['SATURDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    five[
                                                        'SATURDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${five['SATURDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_five += `<td>${variable}</td>`;
                                } else {
                                    period_five += `<td></td>`;
                                }
                            } else {
                                period_five += `<td></td>`;
                            }
                            if (six.hasOwnProperty('MONDAY')) {
                                if (six['MONDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < six['MONDAY'].length; c++) {
                                        if (six['MONDAY'][c].exam == false) {
                                            var sub_name = six['MONDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = six['MONDAY'][c].staffs.name + '  ' + '  (' +
                                                six[
                                                    'MONDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = six['MONDAY'][c].subjects.name + '  ' + '  (' +
                                                    six[
                                                        'MONDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${six['MONDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_six += `<td>${variable}</td>`;
                                } else {
                                    period_six += `<td></td>`;
                                }
                            } else {
                                period_six += `<td></td>`;
                            }
                            if (six.hasOwnProperty('TUESDAY')) {
                                if (six['TUESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < six['TUESDAY'].length; c++) {
                                        if (six['TUESDAY'][c].exam == false) {
                                            var sub_name = six['TUESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = six['TUESDAY'][c].staffs.name + '  ' + '  (' +
                                                six[
                                                    'TUESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = six['TUESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    six[
                                                        'TUESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${six['TUESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_six += `<td>${variable}</td>`;
                                } else {
                                    period_six += `<td></td>`;
                                }
                            } else {
                                period_six += `<td></td>`;
                            }
                            if (six.hasOwnProperty('WEDNESDAY')) {
                                if (six['WEDNESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < six['WEDNESDAY'].length; c++) {
                                        if (six['WEDNESDAY'][c].exam == false) {
                                            var sub_name = six['WEDNESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = six['WEDNESDAY'][c].staffs.name + '  ' + '  (' +
                                                six[
                                                    'WEDNESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = six['WEDNESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    six[
                                                        'WEDNESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${six['WEDNESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_six += `<td>${variable}</td>`;
                                } else {
                                    period_six += `<td></td>`;
                                }
                            } else {
                                period_six += `<td></td>`;
                            }
                            if (six.hasOwnProperty('THURSDAY')) {
                                if (six['THURSDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < six['THURSDAY'].length; c++) {
                                        if (six['THURSDAY'][c].exam == false) {
                                            var sub_name = six['THURSDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = six['THURSDAY'][c].staffs.name + '  ' + '  (' +
                                                six[
                                                    'THURSDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = six['THURSDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    six[
                                                        'THURSDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${six['THURSDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_six += `<td>${variable}</td>`;
                                } else {
                                    period_six += `<td></td>`;
                                }
                            } else {
                                period_six += `<td></td>`;
                            }
                            if (six.hasOwnProperty('FRIDAY')) {
                                if (six['FRIDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < six['FRIDAY'].length; c++) {
                                        if (six['FRIDAY'][c].exam == false) {
                                            var sub_name = six['FRIDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = six['FRIDAY'][c].staffs.name + '  ' + '  (' +
                                                six[
                                                    'FRIDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = six['FRIDAY'][c].subjects.name + '  ' + '  (' +
                                                    six[
                                                        'FRIDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${six['FRIDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_six += `<td>${variable}</td>`;
                                } else {
                                    period_six += `<td></td>`;
                                }
                            } else {
                                period_six += `<td></td>`;
                            }
                            if (six.hasOwnProperty('SATURDAY')) {
                                if (six['SATURDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < six['SATURDAY'].length; c++) {
                                        if (six['SATURDAY'][c].exam == false) {
                                            var sub_name = six['SATURDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = six['SATURDAY'][c].staffs.name + '  ' + '  (' +
                                                six[
                                                    'SATURDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = six['SATURDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    six[
                                                        'SATURDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${six['SATURDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }


                                    period_six += `<td>${variable}</td>`;
                                } else {
                                    period_six += `<td></td>`;
                                }
                            } else {
                                period_six += `<td></td>`;
                            }
                            if (seven.hasOwnProperty('MONDAY')) {
                                if (seven['MONDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < seven['MONDAY'].length; c++) {
                                        if (seven['MONDAY'][c].exam == false) {
                                            var sub_name = seven['MONDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = seven['MONDAY'][c].staffs.name + '  ' + '  (' +
                                                seven[
                                                    'MONDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = seven['MONDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    seven[
                                                        'MONDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${seven['MONDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_seven += `<td>${variable}</td>`;
                                } else {
                                    period_seven += `<td></td>`;
                                }
                            } else {
                                period_seven += `<td></td>`;
                            }
                            if (seven.hasOwnProperty('TUESDAY')) {
                                if (seven['TUESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < seven['TUESDAY'].length; c++) {
                                        if (seven['TUESDAY'][c].exam == false) {
                                            var sub_name = seven['TUESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = seven['TUESDAY'][c].staffs.name + '  ' + '  (' +
                                                seven[
                                                    'TUESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = seven['TUESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    seven[
                                                        'TUESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${seven['TUESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_seven += `<td>${variable}</td>`;
                                } else {
                                    period_seven += `<td></td>`;
                                }
                            } else {
                                period_seven += `<td></td>`;
                            }
                            if (seven.hasOwnProperty('WEDNESDAY')) {
                                if (seven['WEDNESDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < seven['WEDNESDAY'].length; c++) {
                                        if (seven['WEDNESDAY'][c].exam == false) {
                                            var sub_name = seven['WEDNESDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = seven['WEDNESDAY'][c].staffs.name + '  ' +
                                                '  (' +
                                                seven[
                                                    'WEDNESDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = seven['WEDNESDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    seven[
                                                        'WEDNESDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${seven['WEDNESDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_seven += `<td>${variable}</td>`;
                                } else {
                                    period_seven += `<td></td>`;
                                }
                            } else {
                                period_seven += `<td></td>`;
                            }
                            if (seven.hasOwnProperty('THURSDAY')) {
                                if (seven['THURSDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < seven['THURSDAY'].length; c++) {
                                        if (seven['THURSDAY'][c].exam == false) {
                                            var sub_name = seven['THURSDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = seven['THURSDAY'][c].staffs.name + '  ' + '  (' +
                                                seven[
                                                    'THURSDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = seven['THURSDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    seven[
                                                        'THURSDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${seven['THURSDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_seven += `<td>${variable}</td>`;
                                } else {
                                    period_seven += `<td></td>`;
                                }
                            } else {
                                period_seven += `<td></td>`;
                            }
                            if (seven.hasOwnProperty('FRIDAY')) {
                                if (seven['FRIDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < seven['FRIDAY'].length; c++) {
                                        if (seven['FRIDAY'][c].exam == false) {
                                            var sub_name = seven['FRIDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = seven['FRIDAY'][c].staffs.name + '  ' + '  (' +
                                                seven[
                                                    'FRIDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = seven['FRIDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    seven[
                                                        'FRIDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${seven['FRIDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_seven += `<td>${variable}</td>`;
                                } else {
                                    period_seven += `<td></td>`;
                                }
                            } else {
                                period_seven += `<td></td>`;
                            }
                            if (seven.hasOwnProperty('SATURDAY')) {
                                if (seven['SATURDAY'].length > 0) {
                                    var variable = '';
                                    for (let c = 0; c < seven['SATURDAY'].length; c++) {
                                        if (seven['SATURDAY'][c].exam == false) {
                                            var sub_name = seven['SATURDAY'][c].subject;
                                            var sub_label;
                                            var subjectDetails;
                                            var staffDetails = seven['SATURDAY'][c].staffs.name + '  ' + '  (' +
                                                seven[
                                                    'SATURDAY'][c].staffs.employID + ')';
                                            if (sub_name == 'Library') {
                                                sub_label = 'Library';
                                                subjectDetails = '';
                                            } else {
                                                sub_label = 'Subject';
                                                subjectDetails = seven['SATURDAY'][c].subjects.name + '  ' +
                                                    '  (' +
                                                    seven[
                                                        'SATURDAY'][c].subjects.subject_code + ')';
                                            }
                                            variable += `<div class="staff_label">
               <div><b>${sub_label}</b></div>
               <b class="text-primary">${subjectDetails}</b>
               <div><b>Staff</b></div>
               <b class="text-primary">${staffDetails}</b>
             </div>`;
                                        } else {
                                            variable += `<div class="staff_label">
               <div><b>CAT EXAM</b></div>
               <b class="text-primary">${seven['SATURDAY'][c].exam_name}</b>
             </div>`;
                                        }
                                    }

                                    period_seven += `<td>${variable}</td>`;
                                } else {
                                    period_seven += `<td></td>`;
                                }
                            } else {
                                period_seven += `<td></td>`;
                            }

                            let make_table = period_one + period_two + period_three + period_four +
                                period_five + period_six + period_seven;
                            $("#tbody").html(make_table)
                        } else {

                            Swal.fire('', response.data, 'error');
                            $("#tbody").html(`<tr><td colspan="7"> No Data Available.. </td></tr>`);

                        }
                    }
                })
            }
        }
    </script>
@endsection
