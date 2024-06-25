@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Staff leave Report
        </div>
        <script>
            var leaveTypes = [];
        </script>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data" id="search_form">
                @csrf
                <div class="row gutters">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="leave_type">Leave Type</label>
                            <select class="form-control select2" name="leave_type" id="leave_type" required>
                                <option value="">Please Select</option>
                                @foreach ($leave_type as $id => $value)
                                    <option value="{{ $id }}">{{ $value }}</option>
                                    <script>
                                        leaveTypes[{{ $id }}] = "{{ $value }}";
                                    </script>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if (auth()->user()->roles[0]->id != 42)
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <label class="required" for="department">Department</label>
                            <select class="form-control select2" name="department" id="department" required>
                                <option value="">Please select</option>
                                @foreach ($departments as $id => $entry)
                                    <option value="{{ $entry }}">{{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="department" id="department" value="">
                    @endif
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="" for="fromtime">From Date</label>
                            <input type="text" class=" form-control date" placeholder="Enter The From Date"
                                id="start_date" name="start_date">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="" for="totime">To Date</label>
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
    <div class="loader" id="loader" style="display:none;left:53%;">
        <div class="spinner-border text-primary"></div>
    </div>
    <div class="card" id="report_card" style="display:none;">
        <div class="card-header">
            <div>
                <div>Staff Leave Reports</div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-attend_rep"
                id='report_table'>
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Staff Name
                        </th>
                        <th>
                            Staff Code
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Casual Leaves
                        </th>
                        <th>
                            Compensation Leaves
                        </th>
                        <th>
                            Training OD
                        </th>
                        <th>
                            Exam OD
                        </th>
                        <th>
                            Admin OD
                        </th>
                        <th>
                            Half Day Leave
                        </th>

                    </tr>
                </thead>

            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent

    <script>
        $("#submit").click(function(e) {

            e.preventDefault();

            let data;
            let leave = $("#leave_type").val();
            let department = $("#department").val();
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();
            data = {
                'leave_type': leave,
                'start_date': start_date,
                'end_date': end_date,
                'department': department,
            }

            if ((data.start_date != '' && data.end_date === '') || (data.start_date === '' && data.end_date !=
                    '')) {
                Swal.fire({
                    title: 'Error',
                    text: 'Please fill  the Date  fields',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else {
                $("#report_card").hide();
                $("#loader").show();

                let table = $('.datatable-attend_rep').DataTable();
                table.clear().draw();


                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('admin.staff_leave_report.index_rep') }}',
                    data: {
                        data: data,
                    },
                    success: function(response) {

                        $("#loader").hide();
                        $("#report_card").show();

                        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


                        let print = {
                            extend: 'print',
                            title: '<div style="display:flex;justify-content:space-around;"><h3>Leave Type : </h3></div>',
                            className: 'btn-default',
                            text: 'Print',
                            customize: function(win) {
                                $(win.document.body).prepend(
                                    '<h1 style="text-align:center;">Demo Collage Of Engineering & Technology</h1></br><h2 style="text-align:center;">Staff Leave Report</h2>'
                                );
                            },
                            exportOptions: {
                                columns: ':visible'
                            }
                        };

                        dtButtons.splice(6, 1, print);
                        // console.log(dtButtons)

                        let dtOverrideGlobals = {
                            buttons: dtButtons,
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
                                    data: 'name',
                                    name: 'name'
                                },
                                {
                                    data: 'StaffCode',
                                    name: 'StaffCode'
                                },
                                {
                                    data: 'Designation',
                                    name: 'Designation'
                                },
                                {
                                    data: 'Dept',
                                    name: 'Dept'
                                },
                                {
                                    data: 'casual_leave_taken',
                                    name: 'casual_leave_taken'
                                },
                                {
                                    data: 'compensation',
                                    name: 'compensation'
                                },
                                {
                                    data: 'training_od',
                                    name: 'training_od'
                                },
                                {
                                    data: 'exam_od',
                                    name: 'exam_od'
                                },
                                {
                                    data: 'admin_od',
                                    name: 'admin_od'
                                },
                                {
                                    data: 'half_day_leave',
                                    name: 'half_day_leave'
                                }

                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };

                        let table = $('.datatable-attend_rep').DataTable();
                        table.destroy();
                        table = $('.datatable-attend_rep').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }

                });
            }
        });
    </script>
@endsection
