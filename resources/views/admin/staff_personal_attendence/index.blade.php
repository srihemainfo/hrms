@php
    $role_id = auth()->user()->roles[0]->id;
    if ($role_id == 1) {
        $key = 'layouts.admin';
    } else {
        $key = 'layouts.staffs';
    }
@endphp
@extends($key)
@section('content')
    <div class="card">
        <div class="card-header">
            Staff Personal Attendance
        </div>
        @php
            $user = auth()->user();

            if ($user) {
                // Get the user's ID
                $userId = $user->id;
                // dd($userId);
                // You can also use the following equivalent syntax:
                // $userId = auth()->id();

                // $userId now contains the ID of the authenticated user
            }
        @endphp
        <style>
            .select2-container {
                width: 100% !important;
            }
        </style>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="row gutters">

                    <input type="hidden" name="staff_code" id="staff_code" value="{{ $userId }}">

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="month">Month</label>
                            <select class="form-control select2" name="month" id="month" required>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="year">Year</label>
                            <select class="form-control select2" name="year" id="year" required>
                                @php
                                    $current_year = date('Y');
                                @endphp
                                @for ($i = 2010; $i <= $current_year; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
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
    <div class="card" id="report" style="display:none;" style="max-width:100%;overflow-x:auto;">
        <div class="card-header" style="min-width:700px;">
            <div class="header_div">
                <div style="text-align:center;font-size:1.5rem;color:#007bff;">SRI HEMA INFOTECH</div>
                <div style="text-align:center;font-size:1.2rem;color:rgb(85, 85, 85);" id="month_label"></div>
                <div style="text-align:center;font-size:1rem;display:flex;justify-content:space-around;"
                    id="employee_details"></div>
            </div>
        </div>
        <div class="card-body" style="min-width:700px;">
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
            let register_table = $("#register_table");
            let month_label = $("#month_label");
            let employee_details = $("#employee_details");

            $("#submit").on('click', function(e) {

                e.preventDefault();
                $("#report").hide();
                $("#loadig_spin").show();

                let staff_code = $("#staff_code").val();
                let month = $("#month").val();
                let year = $("#year").val();

                let month_name = new Date(year, month - 1, 1).toLocaleString('default', {
                    month: 'long'
                });
                let monthName = month_name.toUpperCase();
                month_label.html();
                employee_details.html();


                let data = {
                    'staff_code': staff_code,
                    'month': month,
                    'year': year
                };

                let table = $('.datatable-staff_attend_rep').DataTable();
                table.clear().draw();

                $.ajax({
                    url: '{{ route('admin.Staff-Personal-Attendence.search') }}',
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // console.log(response);
                        let staff = response.staff;

                        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                        console.log(staff);


                        let print = {
                            extend: 'print',
                            title: '<div style="display:flex;justify-content:space-around;"><h5>Employee Name : ' +
                                staff.name + '</h5> <h5>  Biometric : ' + staff.biometric +
                                '</h5> <h5>  Staff Code : ' + staff.employee_id,
                            className: 'btn-default',
                            text: 'Print',
                            customize: function(win) {
                                $(win.document.body).prepend(
                                    '<h1 style="text-align:center;">SRI HEMA INFOTECH</h1></br><h2 style="text-align:center;">Staff Attendance Register For <span style=\"color:black;\">' +
                                    monthName +
                                    '</span> Month</h2>'
                                );
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
                        table = $('.datatable-staff_attend_rep').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });


                        month_label.html(
                            "Staff Attendance Register For <span style=\"color:black;\">" +
                            monthName +
                            "</span> Month");
                        employee_details.html("<div>Employee Name : " + staff.name +
                            "</div> <div>  Biometric ID : " + staff.biometric +
                            " </div> <div>  Staff Code : " + staff.employee_id);


                        $("#report").show();
                        $("#loadig_spin").hide();

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                })

            })
        }
    </script>
@endsection
