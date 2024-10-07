@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Staff leave Register
        </div>

        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data" id="search_form">
                @csrf
                <div class="row gutters">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="staff_code">Staff Name</label>
                            <select class="form-control select2" name="staff_code" id="staff_code" required>
                                <option value="">Please select</option>
                                @foreach ($staff as $id => $key)
                                    <option value="{{ $id }}">{{ $key . ' (' . $id . ')' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
    <div class="loader" id="loader" style="display:none; padding-left:100px;">
        <div class="spinner-border text-primary"></div>
    </div>
    <div class="card" id="report_card" style="display:none;">
        <div class="card-header">
            <div>Staff Leave Register</div>
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
                            From Date
                        </th>

                        <th>
                            To date
                        </th>

                        <th>
                            Total Days
                        </th>
                        <th>
                            Leave Type
                        </th>
                        {{-- <th>
                            Balance CL
                        </th> --}}
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
            let staff_code = $("#staff_code").val();
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();

            if (staff_code != '') {
                if (start_date != '' || end_date != '') {
                    $("#report_card").hide();
                    $("#loader").show();

                    let table = $('.datatable-attend_rep').DataTable();
                    table.clear().draw();

                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('admin.staff_leave_register.index_rep') }}',
                        data: {
                            'staff_code': staff_code,
                            'start_date': start_date,
                            'end_date': end_date
                        },
                        success: function(response) {
                            $("#loader").hide();
                            $("#report_card").show();

                            // Destroy existing DataTable instance
                            let table = $('.datatable-attend_rep').DataTable();
                            table.destroy();

                            // Initialize DataTable with the received data
                            table = $('.datatable-attend_rep').DataTable({
                                data: response,
                                columns: [{
                                        data: null,
                                        name: 'empty',
                                        render: function(data, type, full, meta) {
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
                                        data: 'from_date',
                                        name: 'from_date'
                                    },
                                    {
                                        data: 'to_date',
                                        name: 'to_date'
                                    },

                                    {
                                        data: 'total_days',
                                        name: 'total_days'
                                    },

                                    {
                                        data: 'leave_type',
                                        name: 'leave_type'
                                    },
                                    


                                ],
                                orderCellsTop: true,
                                order: [
                                    [1, 'desc']
                                ],
                                pageLength: 10
                            });

                            // Adjust column widths on tab change
                            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                                $('.datatable-attend_rep').DataTable().columns.adjust();
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                        }

                    });
                } else {
                    Swal.fire('', 'Please Choose Dates', 'warning');
                }
            } else {
                Swal.fire('', 'Please Choose Staff', 'warning');
            }

        });
    </script>
@endsection
