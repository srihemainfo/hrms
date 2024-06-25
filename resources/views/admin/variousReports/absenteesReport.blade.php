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
            <div class="text-center">Absentees Summary Report</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="department" class="required">Department</label>
                        <select class="form-control select2" name="department" id="department" onchange="check_dept(this)">
                            <option value="">Select Department</option>
                            @foreach ($departments as $id => $entry)
                            @if($id != 9 && $id != 10)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="course" class="required">Course</label>
                        <select class="form-control select2" name="course" id="course" required>
                            <option value="">Select Course</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
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
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="sem_type" class="required">Semester Type</label>
                        <select class="form-control select2" id="sem_type" name="sem_type">
                            <option value="">Select Sem Type</option>
                            <option value="ODD">ODD</option>
                            <option value="EVEN">EVEN</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="week" class="required">Date</label>
                        <input type="text" class="form-control date" id="search_date" name="search_date">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group" style="text-align:right;padding-top:2.2rem;">
                        <button class="enroll_generate_bn" onclick="get_data()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="report" style="display:none;max-width:fix-content;overflow-x:auto;z-index:0;">
        <div class="card-header text-right" id="card_header" style="display:none;">

        </div>
        <div class="card-body" id="card-body">
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


                        let got_courses = '<option value="">Select Course</option>';


                        if (courses_len > 0) {
                            for (let i = 0; i < courses_len; i++) {
                                got_courses +=
                                    `<option value="${courses[i].id}">${courses[i].short_form}</option>`;

                            }
                        }

                        $("#course").html(got_courses);

                        $("select").select2();

                    }
                })
            }

        }

        function get_data() {

            let dept = $("#department").val();
            let course = $("#course").val();
            let ay = $("#ay").val();
            let sem_type = $("#sem_type").val();
            let theDate = $("#search_date").val();
            $("#report").hide();
            $("#card_header").hide();
            if (dept == '') {
                Swal.fire('', 'Please Choose the Department', 'warning');
            } else if (course == '') {
                Swal.fire('', 'Please Choose the Course', 'warning');

            } else if (ay == '') {
                Swal.fire('', 'Please Choose the Academic Year', 'warning');

            } else if (sem_type == '') {
                Swal.fire('', 'Please Choose the Sem Type', 'warning');

            } else if (theDate == '') {
                Swal.fire('', 'Please Choose the Date', 'warning');

            } else {
                $("#card-body").html(`<div class="text-primary text-center">Loading...</div>`);
                $("#report").show();
                $.ajax({
                    url: '{{ route('admin.absentees-summary-report.summary-report') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'department': dept,
                        'course': course,
                        'ay': ay,
                        'sem_type': sem_type,
                        'date': theDate
                    },
                    success: function(response) {

                        let status = response.status;
                        let data = response.data;
                        let make_card;
                        let theDatas;
                        let theDatas_len;
                        let theSection;
                        let theCards = '';
                        let absentList = '';
                        let absentList_len;
                        let leaveList = '';
                        let leaveList_len;
                        let odList = '';
                        let odList_len;
                        let absent_students = '';
                        let leave_students = '';
                        let od_students = '';
                        let cumulative = '';
                        let strength = 0;
                        let present = 0;
                        let absent = 0;
                        let leave = 0;
                        let od = 0;
                        let students = 0;
                        let temp_absent = 0;
                        let temp_leave = 0;
                        let temp_od = 0;


                        if (status != false) {

                            let data_len = data.length;
                            for (let a = 0; a < data_len; a++) {
                                make_card =
                                    `<div class="card" style="margin-bottom:1rem;"><div class="card-header bg-primary"><span style="font-size:1.1rem;">${data[a].year}</span></div><div class="card-body"><div class="row">`;
                                theDatas = data[a].data;
                                theDatas_len = theDatas.length;
                                for (let b = 0; b < theDatas_len; b++) {
                                    students += theDatas[b].students;
                                    absentList = theDatas[b].absentlist;
                                    absentList_len = absentList.length;
                                    if (absentList_len > 0) {
                                        for (let c = 0; c < absentList_len; c++) {
                                            absent_students += absentList[c].student + ', ';
                                            absent++;
                                            temp_absent++;
                                        }
                                    }
                                    absent_students = absent_students.slice(0, -2);

                                    leaveList = theDatas[b].leavelist;
                                    leaveList_len = leaveList.length;
                                    if (leaveList_len > 0) {
                                        for (let c = 0; c < leaveList_len; c++) {
                                            leave_students += leaveList[c].student + ', ';
                                            leave++;
                                            temp_leave++;
                                        }
                                    }
                                    leave_students = leave_students.slice(0, -2);

                                    odList = theDatas[b].odlist;
                                    odList_len = odList.length;
                                    if (odList_len > 0) {
                                        for (let c = 0; c < odList_len; c++) {
                                            od_students += odList[c].student + ', ';
                                            od++;
                                            temp_od++;
                                        }
                                    }
                                    od_students = od_students.slice(0, -2);
                                    if (theDatas[b].status == true) {
                                        theSection =
                                            `<div class="col-md-3 table-bordered p-0">
                                             <div class="table-bordered text-center"><strong>${theDatas[b].name}</strong></div>
                                             <div class="table-bordered">
                                             <div style="padding-left:5px;"><strong>Absent</strong></div>
                                             <div class="text-center">${absent_students} </div>
                                             </div>
                                             <div class="table-bordered">
                                             <div style="padding-left:5px;"><strong>Leave</strong></div>
                                             <div class="text-center">${leave_students} </div>
                                             </div>
                                             <div class="table-bordered">
                                             <div style="padding-left:5px;"><strong>OD</strong></div>
                                             <div class="text-center">${od_students} </div>
                                             </div>
                                             <div style="display:flex;">
                                             <div class="table-bordered" style="width:65%;">
                                             <div style="padding-left:5px;"><strong>Total Leave & Absent</strong></div>
                                             <div class="text-center">${temp_absent + temp_leave}</div>
                                             </div>
                                             <div class="table-bordered" style="width:35%;">
                                             <div style="padding-left:5px;"><strong>Total OD</strong></div>
                                             <div class="text-center">${temp_od}</div>
                                             </div>
                                             </div></div>`;
                                    } else {
                                        theSection =
                                            `<div class="col-md-3 table-bordered p-0 text-center"><div class="table-bordered"><strong>${theDatas[b].name}</strong></div><div style="padding-top:1rem;">Not Yet Taken</div></div>`;
                                    }
                                    make_card += theSection;
                                    absent_students = '';
                                    leave_students = '';
                                    od_students = '';
                                    temp_absent = 0;
                                    temp_leave = 0;
                                    temp_od = 0;
                                }
                                strength = students;
                                present = strength - (absent + leave + od);
                                cumulative =
                                    `<div class="col-md-3 table-bordered"><div><strong>Strength : </strong> ${strength}</div><div><strong>Present : </strong>${present}</div><div><strong>Leave : </strong>${leave}</div><div><strong>OD : </strong>${od}</div><div><strong>Absent : </strong>${absent}</div></div>`;

                                make_card += `${cumulative}</div></div></div>`;
                                students = 0;
                                strength = 0;
                                present = 0;
                                absent = 0;
                                leave = 0;
                                od = 0;
                                theCards += make_card;
                            }
                            $("#card_header").html(`<a class="manual_bn bg-success" target="blank" href="{{ URL::to('admin/absentees-summary-report/pdf/${dept}/${course}/${ay}/${sem_type}/${theDate}') }}">
                                Download PDF File
                             </a>`);
                            $("#card-body").html(theCards);
                            $("#card_header").show();
                        } else {
                            Swal.fire('', data, 'error');
                            $("#report").hide();
                        }
                    }
                })
            }
        }
    </script>
@endsection
