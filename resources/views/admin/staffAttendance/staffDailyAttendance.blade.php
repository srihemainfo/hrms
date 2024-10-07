@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Staff Daily Attendance
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-4 col-8">
                    <div class="form-group">
                        <label for="week" class="required">Date</label>
                        <input type="text" class="form-control date" id="search_date" name="search_date">
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="form-group" style="text-align:right;padding-top:2.2rem;">
                        <button class="enroll_generate_bn" onclick="get_data()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="report" style="display:none;max-width:fix-content;overflow-x:auto;z-index:0;">
        <div class="card-header text-center text-primary" id="card_header" style="display:none;">

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

        function get_data() {
            let dept = $("#department").val();
            let theDate = $("#search_date").val();
            $("#report").hide();
            $("#card_header").hide();
            if (dept == '') {
                Swal.fire('', 'Please Choose the Department', 'warning');
            } else if (theDate == '') {
                Swal.fire('', 'Please Choose the Date', 'warning');
            } else {
                $("#card-body").html(`<div class="text-primary text-center">Loading...</div>`);
                $("#report").show();
                $.ajax({
                    url: '{{ route('admin.staff-daily-attendance.get-data') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'department': dept,
                        'date': theDate
                    },
                    success: function(response) {

                        let status = response.status;
                        let data = response.data;

                        // console.log(response.data)
                        if (status != false) {
                            let department = response.department;
                            let data_len = data.length;
                            let theDatas = '';
                            let currentStatus;
                            let cl = 0;
                            let od = 0;
                            let comp_off = 0;
                            let permission = 0;
                            let absent = 0;
                            let present = 0;

                            let table = `<table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Staff Code</th>
                                        <th>Staff Name</th>
                                        <th>Attendance</th>
                                    </tr>
                                </thead> <tbody>`;
                            for (let v = 0; v < data_len; v++) {
                                if (data[v].leaveType == null) {
                                    if (data[v].permission == null) {
                                        if (data[v].status == null) {
                                            if (data[v].employment_status == null || data[v].employment_status == 'Active') {
                                                currentStatus = `<td>Not Available</td>`;
                                            }else{
                                                currentStatus = `<td>${data[v].employment_status}</td>`;
                                                absent++;
                                            }
                                        } else {
                                            currentStatus = `<td>${data[v].status}</td>`;
                                            if (data[v].status == 'Absent') {
                                                absent++;
                                            }
                                            if (data[v].status == 'Present') {
                                                present++;
                                            }
                                        }
                                    } else {
                                        currentStatus =
                                            `<td style="background-color:#ffccc7;color:black;">${data[v].permission}</td>`;

                                        permission++;
                                    }
                                } else {
                                    // console.log(data[v].leaveType);

                                    currentStatus =
                                        `<td style="background-color:#ffccc7;color:black;">${data[v].leaveType}</td>`;
                                    if (data[v].leaveType == 'CASUAL LEAVE') {
                                        cl++;
                                    }
                                    if (data[v].leaveType == 'Admin OD') {
                                        od++;
                                    }
                                    if (data[v].leaveType == 'Training OD') {
                                        od++;
                                    }
                                    if (data[v].leaveType == 'Exam OD') {
                                        od++;
                                    }
                                    if (data[v].leaveType == 'Compensation Leave(Off)') {
                                        comp_off++;
                                    }
                                }
                                theDatas += `<tr>
                                              <td>${data[v].employee_id}</td>
                                              <td>${data[v].name}</td>
                                              ${currentStatus}
                                            </tr>`;

                                console.log(present, absent, cl);

                            }

                            table += `${theDatas}</tbody></table>`;
                            table += `<div class="row text-center" style="margin-top:15px;">
                                        <div class="col-3"><div><b>Total Faculties </b></div>${data_len}</div>
                                        <div class="col-3"><div><b>Total Present </b></div>${present}</div>
                                        <div class="col-3"><div><b>Total Leave & Absent </b></div>${absent+cl}</div>
                                        <div class="col-3"><div><b>Total OD </b></div>${od}</div>
                                     </div>`;

                            $("#card-body").html(table);
                            $("#card_header").html(`Staff List`);
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
