@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Staff Attendance Register
        </div>

        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="row gutters">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="user">Staff Name</label>
                            <select class="form-control select2" name="user" id="user" required>
                                <option value="">Select Staff</option>
                                @foreach ($staff as $key)
                                    <option value="{{ $key['user_name_id'] }}">{{ $key['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="fromtime">From Date</label>
                            <input type="text" class=" form-control date" placeholder="Enter The From Date"
                                id="start_date" name="start_date">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="totime">To Date</label>
                            <input type="text" class=" form-control date" id="end_date" placeholder="Enter The To Date"
                                id="totime" name="end_date">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group" style="padding-top: 30px;">
                            <button type="submit" id="submit" name="submit" class="enroll_generate_bn">Get
                                Report</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="loadig_spin" style="display:none;">
        <div class="loader">
            <div class="spinner-border text-primary"></div>
        </div>
    </div>
    <div class="card" id="report" style="display:none;">
        <div class="card-header">
            <div class="header_div">
                <div style="text-align:center;font-size:1.5rem;color:#007bff;">Demo Collage Of Engineering & Technology
                </div>
                <div style="text-align:center;font-size:1.2rem;color:rgb(85, 85, 85);" id="month_label"></div>
                <div style="text-align:center;font-size:1rem;display:flex;justify-content:space-around;"
                    id="employee_details"></div>
            </div>
        </div>
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-staff_attend_rep text-center"
                id="register_table">
                <thead>
                    <tr>
                        <th></th>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Day Punches</th>
                        <th>In Time</th>
                        <th>Out Time</th>
                        <th>Total Hours</th>
                        <th>Attendance</th>
                        <th>Permissions</th>
                        <th>Details</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        window.onload = function() {

            $("#user").select2();
            let register_table = $("#register_table");
            let month_label = $("#month_label");
            let employee_details = $("#employee_details");

            $("#submit").on('click', function(e) {

                e.preventDefault();

                let user = $("#user").val();
                let start_date = $("#start_date").val();
                let end_date = $("#end_date").val();

                if (user != '') {
                    if (start_date != '' || end_date != '') {

                        $("#report").hide();
                        $("#loadig_spin").show();

                        let data = {
                            'user': user,
                            'start_date': start_date,
                            'end_date': end_date,
                        };

                        let table = $('.datatable-staff_attend_rep').DataTable();
                        table.clear().draw();

                        $.ajax({
                            url: '{{ route('admin.staff-attend-register.search') }}',
                            type: 'POST',
                            data: data,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // console.log(response);
                                let staff = response.staff;

                                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                                if (!staff.BiometricID) {
                                    staff.BiometricID = '';
                                }
                                let print = {
                                    extend: 'print',
                                    title: '<div style="display:flex;justify-content:space-around;"><h5>Employee Name : ' +
                                        staff.name +
                                        '</h5> <h5>  Biometric ID : ' + staff.BiometricID +
                                        '</h5> <h5>  Staff Code : ' + staff.StaffCode +
                                        '</h5> <h5>  Department : ' + staff.Dept + '</h5>',
                                    className: 'btn-default',
                                    text: 'Print',
                                    customize: function(win) {
                                        // $(win.document.body).prepend(
                                        //     '<h1 style="text-align:center;">Demo Collage Of Engineering & Technology</h1></br><h2 style="text-align:center;">Staff Attendance Register For <span style=\"color:black;\">' +
                                        //     monthName +
                                        //     '</span> Month</h2>'
                                        // );
                                    },
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                };

                                dtButtons.splice(6, 1, print);

                                let dtOverrideGlobals = {
                                    buttons: dtButtons,
                                    // processing: true,
                                    // serverSide: true,
                                    deferRender: true,
                                    retrieve: true,
                                    aaSorting: [],
                                    data: response.data,
                                    columns: [{
                                            data: 'empty',
                                            name: 'empty',
                                            render: function(data, type, full, meta) {
                                                // Add static data here
                                                return ' ';
                                            }
                                        },
                                        {
                                            data: 'SNo',
                                            name: 'SNo',

                                        },
                                        {
                                            data: 'date',
                                            name: 'date',

                                        },
                                        {
                                            data: 'day',
                                            name: 'day',

                                        },
                                        {
                                            data: 'day_punches',
                                            name: 'day_punches',

                                        },
                                        {
                                            data: 'in_time',
                                            name: 'in_time',

                                        },
                                        {
                                            data: 'out_time',
                                            name: 'out_time',

                                        },
                                        {
                                            data: 'total_hours',
                                            name: 'total_hours',

                                        },
                                        {
                                            data: 'status',
                                            name: 'status',

                                        },
                                        {
                                            data: 'permission',
                                            name: 'permission',

                                        },
                                        {
                                            data: 'details',
                                            name: 'details',

                                        }
                                    ],
                                    orderCellsTop: true,
                                    order: [
                                        [1, 'desc']
                                    ],
                                    pageLength: 35,
                                };


                                let table = $('.datatable-staff_attend_rep').DataTable();
                                table.destroy();
                                table = $('.datatable-staff_attend_rep').DataTable(
                                    dtOverrideGlobals);
                                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                                    $($.fn.dataTable.tables(true)).DataTable()
                                        .columns.adjust();
                                });


                                // month_label.html(
                                //     "Staff Attendance Register For <span style=\"color:black;\">" +
                                //     monthName +
                                //     "</span> Month");
                                employee_details.html("<div>Employee Name : " + staff.name +
                                    "</div> <div>  Biometric ID : " + staff.BiometricID +
                                    " </div> <div>  Staff Code : " + staff.StaffCode +
                                    " </div> <div>  Department : " + staff.Dept + "</div>");


                                $("#report").show();
                                $("#loadig_spin").hide();

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                            }
                        })
                    } else {
                        Swal.fire('', 'Please Choose Dates', 'warning');
                    }
                } else {
                    Swal.fire('', 'Please Choose Staff', 'warning');
                }
            })
        }
    </script>
@endsection
