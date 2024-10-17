@extends('layouts.staffs')
@section('content')
    <style>
        #shi {
            color: #007bff;
            font-size: 22px;
            font-weight: bold;
        }

        #staff_payslip {
            margin-top: -10px;
            font-size: 16px;
        }

        .error {
            color: red;
        }

        .secondLoader {
            z-index: 999;
        }
    </style>
    @php
        $user_name_id = auth()->user()->id;
    @endphp

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p id="shi">SRI HEMA INFOTECH</P>
                    <p id="staff_payslip">Staff PaySlip</p>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <p><strong>Employee Name : {{ $employeeName }}</strong></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Staff Code : {{ $employeeID }}</strong></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Biometric ID : {{ $biometricId }}</strong></p>
                </div>
            </div>
            {{-- <p id="payslip-note" style="color:red; display:none;">Note : The PaySlip will automatically disappear after 24
                hours</p> --}}
            <table id="my-table"
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Staff-Payslip text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Staff Name
                        </th>
                        <th>
                            Month
                        </th>
                        <th>
                            Year
                        </th>
                        <th>
                            Net Pay
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div>
    <div class="card">
        <div class="card-header text-center">
            <strong>Payslip Request</strong>
        </div>
        <div class="card-body">
            <input type="hidden" name="user_name_id" id="user_name_id" value="{{ $user_name_id }}" readonly>
            <div class="row">
                <div class="col-md-4">
                    <label for="year">Year <span style="color:red;">*</span></label>
                    <select name="year" id="year" class="form-control select2">
                        <option value="">Select Year</option>
                        <option value="2024">2024</option>
                    </select>
                    <span id="yearspan" class="error"></span>
                </div>
                <div class="col-md-4">
                    <label for="month">Month <span style="color:red;">*</span></label>
                    <select name="month" id="month" class="form-control select2" multiple>
                        <option value="">Select Month</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>
                    <span id="monthspan" class="error"></span>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="reason" class="required">Reason</label>
                        <input type="text" id="reason" class="form-control">
                        <span id="reasonspan" class="error"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-outline-success" id="sent_request">Send Request</button>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <table id="my-table-request"
                        class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Staff-Payslip-Request text-center">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Staff Name
                                </th>
                                <th>
                                    Month
                                </th>
                                <th>
                                    Year
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);

            if ($.fn.DataTable.isDataTable('.datatable-Staff-Payslip')) {
                $('.datatable-Staff-Payslip').DataTable().destroy();
            }

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.Staff-Payslip.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'year',
                        name: 'year'
                    },
                    {
                        data: 'netpay',
                        name: 'netpay'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions ') }}',
                        orderable: false,
                        searchable: false
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };

            let table = $('.datatable-Staff-Payslip').DataTable(dtOverrideGlobals);

            table.on('draw', function() {
                let rowCount = table.rows().count();
                if (rowCount > 0) {
                    $('#payslip-note').show();
                } else {
                    $('#payslip-note').hide();
                }
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        }

        $("#sent_request").click(function() {
            let year = $("#year").val();
            let month = $("#month").val();
            let reason = $("#reason").val();
            let user_name_id = $("#user_name_id").val();
            let json_month = JSON.stringify(month);

            if (year == '') {
                $("#yearspan").text("Please Select Year");
                return false;
            } else {
                $("#yearspan").text('');
            }
            if (month == '') {
                $("#monthspan").text("Please Select Month");
                return false;
            } else {
                $("#monthspan").text("");
            }
            if (reason == '') {
                $("#reasonspan").text("Please Enter a reson");
                return false;
            } else {
                $("#reasonspan").text("");
            }


            $(".secondLoader").show();

            $.ajax({
                url: "{{ route('admin.Staff-Payslip.reqs') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'user_name_id': user_name_id,
                    'year': year,
                    'json_month': json_month,
                    'reason': reason
                },
                success: function(response) {
                    $(".secondLoader").hide();

                    let status = response.status;
                    if (status == true) {
                        Swal.fire('', response.data, 'success');
                        $("#reason").val('');
                        $("#year").val('').select2();
                        $("#month").val('').select2();
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                    // callAjax();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $(".secondLoader").hide();

                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                            "error");
                    }
                }
            })
        })
    </script>
@endsection
