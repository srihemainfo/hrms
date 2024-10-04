@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <div class="text-center">Class Attendance Report</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
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
                <div class="col-md-4 col-sm-6 col-12">
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
                <div class="col-md-4 col-sm-6 col-12">
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
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="section" class="required">Section</label>
                        <select class="form-control select2" name="section" id="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="from_date" class="required">From Date</label>
                        <input type="text" class="form-control date" name="from_date" id="from_date" value="">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="to_date" class="required">To Date</label>
                        <input type="text" class="form-control date" name="to_date" id="to_date" value="">
                    </div>
                </div>
                <div class="col-md-10 col-sm-9 col-12"></div>
                <div class="col-md-2 col-sm-3 col-12" style="text-align:center;">
                    <div class="form-group" style="padding-top: 32px;">
                        <button type="button" style="width:100%;" class="enroll_generate_bn"
                            onclick="submit()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="report" style="display:none;text-align:center;">
        <div class="card-header text-right" id="card_header" style="display:none;">
            <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>
        </div>
        <div class="card-body" id="card_body" style="max-width:100%;overflow-x:auto;">

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        window.onload = function() {
            let user_name_id = '';
            let main_class = '';

        }

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('ClassAttendanceReport.' + (type || 'xlsx')));
        }

        function course(element) {
            let course = element.value;
            if (course == '') {
                Swal.fire('', 'Please Choose the Course', 'warning');
            }
            $("#ay").val('')
            $("#semester").val('')
            $("#section").val('')
            $("#from_date").val('')
            $("#to_date").val('')
            $("select").select2()
        }

        function semester(element) {
            let course = $("#course").val();
            let ay = $("#ay").val();
            let semester = element.value;

            if (course == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Course', 'warning');
            } else if (ay == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Academic Year', 'warning');
            } else if (semester == '') {
                $(element).val('');
                $("select").select2();
                Swal.fire('', 'Please Choose the Semester', 'warning');
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

                        let sections = response.sections;
                        let sec_len = sections.length;
                        let got_sections = `<option value=''>Select Section</option>`;

                        if (sec_len > 0) {
                            for (let a = 0; a < sec_len; a++) {
                                got_sections +=
                                    `<option value='${sections[a].id}'>${sections[a].section}</option>`;
                            }
                            $("select").select2();
                            $("#section").html(got_sections);
                        } else {

                            Swal.fire('', 'No Classes Found', 'warning');
                        }
                    }
                })
            }

        }

        function submit() {
            // alert();
            let course = $("#course").val();
            let ay = $("#ay").val();
            let semester = $("#semester").val();
            let section = $("#section").val();
            let from_date = $("#from_date").val();
            let to_date = $("#to_date").val();

            if (course == '') {
                Swal.fire('', 'Please Choose the Course', 'warning');
            } else if (ay == '') {

                Swal.fire('', 'Please Choose the Academic Year', 'warning');
            } else if (semester == '') {

                Swal.fire('', 'Please Choose the Semester', 'warning');
            } else if (section == '') {

                Swal.fire('', 'Please Choose the Section', 'warning');
            } else if (from_date == '') {

                Swal.fire('', 'Please Select the  From Date', 'warning');
            } else if (to_date == '') {

                Swal.fire('', 'Please Select the  To Date', 'warning');
            } else {
                let first_show = `<span class="text-primary">LOADING....</span>`
                $("#card_body").html(first_show);
                $("#card_header").hide();
                $("#report").show();
                $.ajax({
                    url: '{{ route('admin.class-attendance-report.get_report') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': course,
                        'ay': ay,
                        'semester': semester,
                        'section': section,
                        'from_date': from_date,
                        'to_date': to_date
                    },
                    success: function(response) {

                        let status = response.status;
                        let data = response.data;
                        let data_len = data.length;
                        let subject_len;
                        let subjects = '';
                        let students = '';
                        let sub_list = '';
                        let column_list = '';
                        let make_table = '';
                        let allAttend;
                        let allTotal;
                        let attendArray;
                        let totalArray;
                        let overAllAttend = 0;
                        let overAllTotal = 0;
                        let averageAttend;
                        let averageTotal;
                        let averageAttendSum = 0;
                        let averageTotalSum = 0;
                        let averagepercentage;
                        let averagepercentageTd;
                        let percentage;

                        if (status == true) {
                            let got_subjects = response.subject_list;

                            let sub_len = got_subjects.length;

                            for (let a = 0; a < sub_len; a++) {

                                subjects += `<th colspan="3">${got_subjects[a].subject_name}</th>`;

                                column_list +=
                                    `<th>Attend Periods</th><th>Total Periods</th><th>Percentage</th>`;
                            }

                            if (data_len > 0) {
                                attendArray = [];
                                totalArray = [];
                                for (let i = 0; i < data_len; i++) {
                                    var subjects_len = data[i].length;
                                    for (let j = 0; j < subjects_len; j++) {



                                        if (data[i][j].registration == true) {
                                            if (data[i][j].percentage > 74) {

                                                percentage =
                                                    `<td>${data[i][j].percentage}</td>`;

                                            } else if (data[i][j].percentage > 100) {

                                                percentage =
                                                    `<td style="background-color:#1fff00;color:black;">${data[i][j].percentage}</td>`;

                                            } else {

                                                percentage =
                                                    `<td style="background-color:#ffccc7;color:black;">${data[i][j].percentage}</td>`;
                                            }
                                            sub_list += `<td> ${data[i][j].attend_hours}</td>
                                                         <td>${data[i][j].total_hours}</td>
                                                         ${percentage}`;
                                        } else if (data[i][j].registration == false) {
                                            sub_list += `<td colspan="3">Not Registered</td>`;
                                        } else {

                                        }

                                        if (data[i][j].attend_hours > 0 || data[i][j].total_hours > 0) {
                                            attendArray.push(parseInt(data[i][j].attend_hours));
                                            totalArray.push(parseInt(data[i][j].total_hours));
                                        }

                                    }

                                    allAttend = attendArray.length;
                                    allTotal = totalArray.length;
                                    if (allAttend > 0) {
                                        for (let m = 0; m < allAttend; m++) {
                                            overAllAttend += attendArray[m];
                                        }
                                    }
                                    if (allTotal > 0) {
                                        for (let m = 0; m < allTotal; m++) {

                                            overAllTotal += totalArray[m];
                                        }
                                    }

                                    if (overAllAttend > 0) {
                                        averagAttend = overAllAttend / allAttend;
                                        averageAttendSum = averagAttend.toFixed(2);
                                    }
                                    // console.log(allAttend)
                                    if (overAllTotal > 0) {
                                        averageTotal = overAllTotal / allTotal;
                                        averageTotalSum = averageTotal.toFixed(2);
                                    }
                                    // if (i == 0) {
                                    //     console.log(attendArray)
                                    //     console.log(totalArray)
                                    //     console.log(overAllAttend)
                                    //     console.log(overAllTotal)
                                    // }
                                    // console.log(overAllTotal)
                                    if (averageAttendSum != 0 && averageTotalSum != 0) {
                                        averagepercentage = Math.round((averageAttendSum / averageTotalSum) *
                                            100);
                                    } else {
                                        averagepercentage = 0;
                                    }
                                    if (averagepercentage > 74) {

                                        averagepercentageTd =
                                            `<td>${averagepercentage}</td>`;

                                    } else if (averagepercentage > 100) {

                                        averagepercentageTd =
                                            `<td style="background-color:#1fff00;color:black;">${averagepercentage}</td>`;

                                    } else {

                                        averagepercentageTd =
                                            `<td style="background-color:#ffccc7;color:black;">${averagepercentage}</td>`;
                                    }
                                    students += `<tr>
                                                  <td>${i + 1}</td>
                                                  <td>${data[i][0].register_no}</td>
                                                  <td>${data[i][0].name}</td>
                                                  ${sub_list}
                                                  <td>${averageAttendSum}</td>
                                                  <td>${averageTotalSum}</td>
                                                 ${averagepercentageTd}
                                                </tr>`;

                                    sub_list = '';
                                    overAllAttend = 0;
                                    overAllTotal = 0;
                                    averageTotal = 0;
                                    averageAttend = 0;
                                    averageAttendSum = 0;
                                    averageTotalSum = 0;
                                    averagepercentage = 0;
                                    totalArray.length = 0;
                                    attendArray.length = 0;
                                    averageAttend.length = 0;
                                    averageTotal.length = 0;
                                }
                                make_table = `<table class="table table-bordered table-striped table-hover text-center" id="tbl_exporttable_to_xls">
                                                      <thead>
                                                         <tr>
                                                             <th rowspan="2">S.No</th>
                                                             <th rowspan="2">Register No</th>
                                                             <th rowspan="2">Name</th>
                                                             ${subjects}
                                                             <th rowspan="2">Average Attended Periods</th>
                                                             <th rowspan="2">Average Handled Periods</th>
                                                             <th rowspan="2">Average Attendance Percentage</th>
                                                         </tr>
                                                         <tr>${column_list}</tr>
                                                      </thead>
                                                      <tbody id="tbody">
                                                             ${students}
                                                      </tbody>

                                                  </table>`;
                                $("#card_body").html(make_table);
                                $("#card_header").show();
                            }
                        } else {
                            Swal.fire('', data, 'error');
                            $("#card_body").html('');
                            $("#report").hide();
                        }

                    }
                })
            }
        }
    </script>
@endsection
