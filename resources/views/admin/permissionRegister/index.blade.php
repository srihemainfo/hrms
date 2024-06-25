@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Permission Register
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12">
                    <form method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label class="" for="fromtime">From Date</label>
                                    <input type="text" class=" form-control date" id="fromtime"
                                        placeholder="Enter The From Date" name="fromtime">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label class="" for="totime">To Date</label>
                                    <input type="text" class=" form-control date" placeholder="Enter The To Date"
                                        id="totime" name="totime">
                                </div>
                            </div>
                            @if (auth()->user()->roles[0]->id != 42)
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="required" for="department">{{ 'Department' }}</label>
                                        <select class="form-control select2" name="Dept" id="Dept">
                                            @foreach ($department as $id => $entry)
                                                @if (auth()->user()->dept != null && auth()->user()->dept == $entry)
                                                    <option value="{{ $entry }}">{{ $entry }}</option>
                                                @endif
                                                @if (auth()->user()->dept == null)
                                                    <option value="{{ $entry }}">{{ $entry }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="Dept" id="Dept" value="">
                            @endif
                        </div>
                    </form>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <div class="form-group" style="padding-top: 30px;">
                        <button id="submit" class="enroll_generate_bn">Get
                            Reports</button>
                    </div>
                </div>
            </div>
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
                <div style="text-align:center;font-size:1.2rem;color:rgb(85, 85, 85);"> Permission Register (<span
                        id="from"></span> to <span id="to"></span> )</div>
                <div style="text-align:center;font-size:1.2rem;color:rgb(85, 85, 85);" id="employee_details">Department :
                    <span id="dept"></span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-staff_attend_rep text-center"
                id="register_table">
                <thead>
                    <tr>
                        <th></th>
                        {{-- <th>Biometric ID</th> --}}
                        <th>Staff Name</th>
                        <th>Staff Code</th>
                        <th>Date</th>
                        <th>From Time</th>
                        <th>To Time</th>
                        <th>Permissions</th>
                        <th>Remarks</th>
                        <th>Balance Permissions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $('#submit').on('click', function() {
            let fromtime = $("#fromtime").val();
            let totime = $("#totime").val();
            let Dept = $("#Dept").val();
            $("#report").hide();
            $("#loadig_spin").show();
            $("#from").text(fromtime);
            $("#to").text(totime);
            $('#dept').text(Dept);

            let data = {
                'fromtime': fromtime,
                'totime': totime,
                'Dept': Dept,
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.permission-register.search') }}',
                type: 'POST',
                data: data,
                success: function(response) {

                    if ($.fn.DataTable.isDataTable('#register_table')) {
                        $('#register_table').DataTable().destroy();
                    }
                    // Initialize DataTable

                    $('#register_table').DataTable({
                        data: response,
                        columns: [{
                                data: 'empty',
                                name: 'empty',
                                render: function(data, type, full, meta) {
                                    // Add static data here
                                    return '';
                                }
                            },
                            // {
                            //     data: 'biometric_id',
                            //     name: 'biometric_id',

                            // },
                            {
                                data: 'name',
                                name: 'name',
                            },
                            {
                                data: 'staff_code',
                                name: 'staff_code',
                            },
                            {
                                data: 'date',
                                name: 'date',
                            },
                            {
                                data: 'from_time',
                                name: 'from_time',
                            },
                            {
                                data: 'to_time',
                                name: 'to_time',
                            },
                            {
                                data: 'Permission',
                                name: 'Permission',
                            },
                            {
                                data: 'reason',
                                name: 'reason',
                            },
                            {
                                data: 'balance_permission',
                                name: 'balance_permission',
                            },

                        ]
                    });


                    $("#report").show();
                    $("#loadig_spin").hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ', textStatus, errorThrown);
                }
            });
        });
    </script>
@endsection
