@extends('layouts.non_techStaffHome')
@section('content')
    <style>
        input[type="file"] {
            border: none;
            cursor: pointer;
            font-size: 16px;
        }


        input[type="file"]:focus {
            outline: none;
        }
        .table.dataTable tbody td.select-checkbox:before{
            content: none !important;
        }
    </style>
    <div class="container" style="position:relative;">
        <div class="loader" id="loader" style="display:none;top:20%;">
            <div class="spinner-border text-primary"></div>
        </div>
        <div class="row gutters">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-2 text-primary">Leave Form</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" onsubmit="return Validate(this)"
                            action="{{ route('admin.staff-request-leaves.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">

                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
                                    style="display:flex;justify-content: flex-end;">

                                    <button class="manual_bn d-block">Available CL's :
                                        {{ $staff_edit->avail_cl == '' ? 0 : $staff_edit->avail_cl }}</button>

                                </div>

                                <div class="col-xl-9 col-lg-9 col-md-10 col-sm-8 col-8">
                                    <div class="form-group">
                                        <label for="leave_type" class="required">Leave Types</label>
                                        <select class="form-control select2" name="leave_type" id="leave_type"
                                            onchange="check_leave()">
                                            <option value="">Select Leave Type</option>
                                            @foreach ($staff_edit->leave_types as $id => $data)
                                                <option value="{{ $id }}"
                                                    {{ $id == $staff_edit->leave_type ? 'selected' : '' }}>
                                                    {{ $data }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span style="color:#007bff;">
                                            @if ($staff_edit->avail_cl == '' || $staff_edit->avail_cl == 0)
                                                Note: If you take Casual Leave, It will be in LOP.
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-4 col-4 text-center">
                                    <div class="form-group">
                                        <label for="leave_type" class="required">Half Day</label>
                                        <div style="padding-top:5px;">
                                            <input type="checkbox" name="half_day" id="half_day" value=""
                                                style="width:18px;height:18px;accent-color:#007bff;"
                                                onchange="checkHalf(this)"
                                                {{ $staff_edit->half_day_leave != null ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="off_date_div"
                                    style={{ $staff_edit->off_date == null ? 'display:none;' : '' }}>
                                    <div class="form-group">
                                        <label for="off_date" class="required">Off Date</label>
                                        <input type="text" class="form-control date" name="off_date" id="off_date"
                                            placeholder="Enter Off Date" value="{{ $staff_edit->off_date }}"
                                            onfocusout="check_OffDate(this)">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="alter_date_div"
                                    style={{ $staff_edit->off_date == null ? 'display:none;' : '' }}>
                                    <div class="form-group">
                                        <label for="alter_date" class="required">Alter Working Date</label>
                                        <input type="text" class="form-control date" name="alter_date" id="alter_date"
                                            placeholder="Enter Alter Working Date" value="{{ $staff_edit->alter_date }}"
                                            onfocusout="get_periodsForCompo(this)">

                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="current_date_div"
                                    style={{ $staff_edit->half_day_leave == null ? 'display:none;' : '' }}>
                                    <div class="form-group">
                                        <label for="half_day_leave" class="required"> Date</label>
                                        <input type="text" class="form-control date" name="half_day_leave"
                                            id="half_day_leave" placeholder="Enter Date"
                                            value="{{ $staff_edit->half_day_leave }}"
                                            onfocusout="check_Date_For_Half_Leave(this)">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="noon_div"
                                    style={{ $staff_edit->half_day_leave == null ? 'display:none;' : '' }}>
                                    <div class="form-group">
                                        <label for="noon" class="required"> FN / AN</label>
                                        <select class="form-control select2" name="noon" id="noon"
                                            style="width:100%;">
                                            <option value="" {{ $staff_edit->noon == '' ? 'selected' : '' }}>
                                                Select
                                                FN / AN</option>
                                            <option value="Fore Noon"
                                                {{ $staff_edit->noon == 'Fore Noon' ? 'selected' : '' }}>Fore Noon
                                            </option>
                                            <option value="After Noon"
                                                {{ $staff_edit->noon == 'After Noon' ? 'selected' : '' }}>After Noon
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="from_date_div"
                                    style={{ $staff_edit->from_date == null ? 'display:none;' : '' }}>
                                    <div class="form-group">
                                        <label for="from_date" class="required">From Date</label>
                                        <input type="text" class="form-control date" name="from_date" id="from_date"
                                            placeholder="Enter From Date" value="{{ $staff_edit->from_date }}"
                                            autocomplete="off" onfocusout="check_Date_For_Leave(this)">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="to_date_div"
                                    style={{ $staff_edit->from_date == null ? 'display:none;' : '' }}>
                                    <div class="form-group">
                                        <label for="to_date" class="required">To Date</label>
                                        <input type="text" class="form-control date" name="to_date" id="to_date"
                                            placeholder="Enter To Date" value="{{ $staff_edit->to_date }}"
                                            onfocusout="check_fromDate(this)" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="subject" class="required">Reason</label>
                                        <input type="text" class="form-control" name="subject" id="subject"
                                            placeholder="Enter Reason" value="{{ $staff_edit->subject }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="certificate">Document</label>
                                        @php
                                            $theEvent =
                                                session('appUser') == true ? 'onclick=Android.openFilePicker()' : '';
                                        @endphp
                                        <input type="file" class="form-control" name="certificate" value=""
                                            {{ $theEvent }}>

                                        <span class="text-primary">file should be in 2MB. PNG, JPG ,JPEG Formats
                                            Only</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right" id="submit_div">
                                        <button type="submit" id="submit" name="submit" style="display:none;"
                                            class="btn btn-primary Edit">{{ $staff_edit->add }}</button>
                                    </div>
                                    <div class="text-right text-primary" id="loading_div" style="display:none;">
                                        <b>Processing...</b>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (count($list) > 0)
            <div class="row gutters mt-3 mb-3">
                <div class="col" style="padding:0;">
                    <div class="card h-100">
                        <div class="card-body table-responsive">
                            <h5 class="mb-3 text-primary">Requested Leave Details</h5>
                            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-leaveList" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Leave Type
                                        </th>
                                        <th>
                                            From Date
                                        </th>
                                        <th>
                                            To Date
                                        </th>
                                        <th>
                                            Off Date
                                        </th>
                                        <th>
                                            Alter Date
                                        </th>
                                        <th>
                                            Half Day Leave Date
                                        </th>
                                        <th>
                                            FN / AN
                                        </th>
                                        <th>
                                            Total Days
                                        </th>
                                        <th>
                                            Reason
                                        </th>
                                        <th>
                                            Document
                                        </th>
                                        <th>
                                            Rejected / Clarification <br> (Reasons)

                                        </th>

                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($list); $i++)
                                        @if ($list[$i]->leave_type != '' || $list[$i]->leave_type != null)
                                            <tr>
                                                @foreach ($list[$i]->leave_types as $id => $entry)
                                                    @if ($list[$i]->leave_type == $id)
                                                        <td>{{ $entry }}</td>
                                                    @endif
                                                @endforeach
                                                <td>{{ $list[$i]->from_date }}</td>
                                                <td>{{ $list[$i]->to_date }}</td>
                                                <td>{{ $list[$i]->off_date }}</td>
                                                <td>{{ $list[$i]->alter_date }}</td>
                                                <td>{{ $list[$i]->half_day_leave }}</td>
                                                <td>{{ $list[$i]->noon }}</td>
                                                <td>{{ $list[$i]->total_days + ($list[$i]->total_days_nxt_mn != null ? $list[$i]->total_days_nxt_mn : 0) }}</td>
                                                <td>{{ $list[$i]->subject }}</td>
                                                <td>
                                                    @if ($list[$i]->certificate)
                                                        <img class="uploaded_img"
                                                            src="{{ asset($list[$i]->certificate) }}" alt="image">
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($list[$i]->status == 'Rejected')
                                                        <p>{{ $list[$i]->rejected_reason ?? '' }}</p>
                                                    @elseif ($list[$i]->status == 'NeedClarification')
                                                        <p>{{ $list[$i]->clarification_reason ?? '' }}</p>
                                                    @endif
                                                </td>

                                                <td>
                                                    @switch($list[$i]->status)
                                                        @case('Pending')
                                                            @if ($list[$i]->level == 0)
                                                                @if (
                                                                    $list[$i]->leave_type == 2 ||
                                                                        $list[$i]->leave_type == 3 ||
                                                                        $list[$i]->leave_type == 4 ||
                                                                        $list[$i]->leave_type == 6 ||
                                                                        $list[$i]->leave_type == 7 ||
                                                                        $list[$i]->leave_type == 8)
                                                                    <div class="p-2 Pending">Waiting For Principal Approval
                                                                    </div>
                                                                @else
                                                                    <div class="p-2 Pending">Waiting For HR Approval</div>
                                                                @endif
                                                            @elseif ($list[$i]->level == 1)
                                                                @if (
                                                                    $list[$i]->leave_type == 2 ||
                                                                        $list[$i]->leave_type == 3 ||
                                                                        $list[$i]->leave_type == 4 ||
                                                                        $list[$i]->leave_type == 6 ||
                                                                        $list[$i]->leave_type == 7 ||
                                                                        $list[$i]->leave_type == 8)
                                                                    <div class="p-2 Pending">Waiting For Principal Approval
                                                                    </div>
                                                                @else
                                                                    <div class="p-2 Pending">Waiting For HR Approval</div>
                                                                @endif
                                                            @elseif ($list[$i]->level == 95)
                                                                <div class="p-2 Pending">Principal Approved, Waiting For HR
                                                                    Approval
                                                                </div>
                                                            @else
                                                                <div class="p-2 Pending">Pending</div>
                                                            @endif
                                                        @break

                                                        @case('Approved')
                                                            <div class="p-2 Approved">Approved</div>
                                                        @break

                                                        @case('Rejected')
                                                            <div class="mt-2 btn-danger" style="border-radius:3px;">Rejected</div>
                                                        @break

                                                        @case('NeedClarification')
                                                            <div class="mt-2 btn-info" style="border-radius:3px;">Need Clarification</div>
                                                        @break

                                                        @default
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @if ($list[$i]->status == 'Pending' && $list[$i]->level == 0)
                                                        <form method="POST"
                                                            action="{{ route('admin.staff-request-leaves.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            <button type="submit" id="updater" name="updater"
                                                                value="updater" class="btn btn-xs btn-info">Edit</button>
                                                        </form>
                                                        <form
                                                            action="{{ route('admin.staff-request-leaves.delete', ['id' => $list[$i]->id]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                            style="display: inline-block;">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <input type="submit" class="btn btn-xs btn-danger mt-2"
                                                                value="{{ trans('global.delete') }}">
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            $("select").select2();
            $("#submit").show();
            callAjax();
        }

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(0, 4);
            dtButtons.splice(3, 3);
            if ($.fn.DataTable.isDataTable('.datatable-leaveList')) {
                $('.datatable-leaveList').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-leaveList').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function check_fromDate(element) {
            let toDate = element.value;
            let fromDate = $("#from_date").val();
            let to_date = new Date(toDate);
            let from_date = new Date(fromDate);

            if (from_date != '') {
                if (to_date.getTime() < from_date.getTime()) {
                    Swal.fire('', "It's Not a Valid Date!", "error");

                    element.value = '';
                    return false;
                }

                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();
                if (isValidDate(fromDate) && isValidDate(toDate)) {
                    const leaveType = $('#leave_type').val();

                    $("button, input,select").prop("disabled", true);
                    $("#loader").show();
                    if (toDate != '' && fromDate != '') {
                        $.ajax({
                            url: "{{ route('admin.staff-request-leaves.check') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                leave_type: leaveType,
                                from_date: fromDate,
                                to_date: toDate,
                            },
                            success: function(response) {
                                if (response.data != '') {
                                    if (response.data != 'Error') {

                                    } else {
                                        Swal.fire('', 'You Have Applied a Leave/OD for the Selected Date',
                                            "warning");
                                        $('#from_date').val('');
                                        $('#to_date').val('');
                                    }
                                }
                                $("#loader").hide();
                                $("button, input,select").removeAttr("disabled");
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                if (jqXHR.status) {
                                    if (jqXHR.status == 500) {
                                        Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                                    } else {
                                        Swal.fire('', jqXHR.status, 'error');
                                    }
                                } else if (textStatus) {
                                    Swal.fire('', textStatus, 'error');
                                } else {
                                    Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                                }
                            }
                        });
                    }
                } else {
                    Swal.fire('', "It's Not a Valid Date!", "error");
                }

            } else {
                Swal.fire('', "Please Choose the From Date", "warning");
            }

        }

        function check_OffDate(element) {
            let offDate = $(element).val();
            if (isValidDate(offDate)) {
                $("button, input,select").prop("disabled", true);
                $("#loader").show();
                $.ajax({
                    url: '{{ route('admin.staff-request-leaves.check_for_off') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        'offDate': offDate
                    },
                    success: function(response) {
                        let data = response.data;
                        if (data == false) {
                            Swal.fire('', 'You Have Applied a Leave/OD for the Selected Date', "warning");
                            $(element).val('');
                        }
                        $("#loader").hide();
                        $("button, input,select").removeAttr("disabled");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                        }
                    }
                })
            }
        }

        function check_Date_For_Leave(element) {
            let check = 'check';
            let givenDate = element.value;
            let Date_get = new Date(givenDate);
            Date_get.setHours(0, 0, 0, 0)
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (today > Date_get) {
                $("#loader").show();
                $.ajax({
                    url: '{{ route('admin.Past_Leave_Non_Teaching_Access__check') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'data': check,
                        'date': givenDate,
                    },
                    success: function(response) {

                        if (response.status == false) {
                            if (givenDate != '') {
                                $("#to_date").val('');
                                $("#from_date").val('');
                                $("#loader").hide();
                                Swal.fire('', 'It\'s Not a Valid Date', 'error');
                                givenDate = '';
                                return false;
                            }


                        } else {
                            $("#loader").hide();
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                        }
                    }
                })
            }


        }

        function check_Date_For_Half_Leave(element) {
            let check = 'check';
            let givenDate = element.value;
            if (element.value != '') {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const parsedGivenDate = new Date(givenDate);

                // Compare the dates
                if (parsedGivenDate < today) {
                    $("#loader").show();
                    $.ajax({
                        url: '{{ route('admin.Past_Leave_Non_Teaching_Access__check') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'data': check,
                            'date': givenDate,
                        },
                        success: function(response) {

                            if (response.status == false) {
                                if (givenDate != '') {

                                    $("#half_day_leave").val('');
                                    $("#loader").hide();
                                    Swal.fire('', 'It\'s Not a Valid Date', 'error');
                                    givenDate = '';
                                    return false;
                                }


                            } else {
                                $("#loader").hide();
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status) {
                                if (jqXHR.status == 500) {
                                    Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                                } else {
                                    Swal.fire('', jqXHR.status, 'error');
                                }
                            } else if (textStatus) {
                                Swal.fire('', textStatus, 'error');
                            } else {
                                Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                            }
                        }
                    })
                }
            }
        }

        function checkHalf(element) {
            let leave_type = $("#leave_type").val();
            if (leave_type != '') {
                if ($(element).prop("checked")) {

                    $("#from_date_div").hide()
                    $("#to_date_div").hide()
                    $("#off_date_div").hide()
                    $("#alter_date_div").hide()
                    $("#current_date_div").show()
                    $("#noon_div").show()

                    $("#from_date").val('')
                    $("#to_date").val('')
                    $("#off_date").val('')
                    $("#alter_date").val('')

                } else {
                    check_leave();
                }
            } else {
                $("#half_day").prop('checked', false);
                Swal.fire('', 'Please Choose The Leave Type', 'info');
            }
        }

        function isValidDate(dateString) {
            var date = new Date(dateString);
            return !isNaN(date.getTime());
        }

        function check_leave() {
            let leave_type = $("#leave_type").val();
            $("#half_day").prop('checked', false);
            if (leave_type != '') {
                $("#leave_type").select2()
                $("#noon").select2()
                if (leave_type == '5') {

                    $("#from_date_div").hide()
                    $("#to_date_div").hide()
                    $("#off_date_div").show()
                    $("#alter_date_div").show()
                    $("#current_date_div").hide()
                    $("#noon_div").hide()

                    $("#from_date").val('')
                    $("#to_date").val('')
                    $("#half_day_leave").val('')
                    $("#noon").val('')
                    $("#noon").select2()

                } else {

                    $("#off_date_div").hide()
                    $("#alter_date_div").hide()
                    $("#from_date_div").show()
                    $("#to_date_div").show()
                    $("#current_date_div").hide()
                    $("#noon_div").hide()

                    $("#half_day_leave").val('')
                    $("#noon").val('')
                    $("#off_date").val('')
                    $("#alter_date").val('')
                    $("#from_date").val('')
                    $("#to_date").val('')
                    $('select').select2();

                }
            }
        }

        function Validate(element) {

            let leave_type = $("#leave_type").val();
            let subject = $("#subject").val();
            let from_date = $("#from_date").val();
            let to_date = $("#to_date").val();
            let off_date = $("#off_date").val();
            let alter_date = $("#alter_date").val();
            let half_leave_date = $("#half_day_leave").val();
            let noon = $("#noon").val();

            if (leave_type == '') {
                Swal.fire('', 'Please Select the Leave Type', 'warning');
                return false;
            } else {
                if ($("#half_day").prop('checked')) {
                    if (half_leave_date == '') {
                        Swal.fire('', 'Please Choose the Leave Date', 'warning');
                        return false;
                    } else if (noon == '') {
                        Swal.fire('', 'Please Choose the FN OR AN', 'warning');
                        return false;
                    }
                } else {
                    if (leave_type == '5') {
                        if (off_date == '') {
                            Swal.fire('', 'Please Choose the Off Date', 'warning');
                            return false;
                        } else if (alter_date == '') {
                            Swal.fire('', 'Please Choose the Alter Working Date', 'warning');
                            return false;
                        } else if (off_date == alter_date) {
                            Swal.fire('', 'Off Date & Alter Date Can\'t Be A Same Date !', 'warning');
                            $('#alter_date').val('');
                            return false;

                        }
                    } else {
                        if (from_date == '') {
                            Swal.fire('', 'Please Choose the From Date', 'warning');
                            return false;
                        } else if (to_date == '') {
                            Swal.fire('', 'Please Choose the To Date', 'warning');
                            return false;
                        }
                    }
                }
            }

            if (subject == '') {
                Swal.fire('', 'Please Fill the Reason', 'warning');
                $("#submit_div").show();
                $("#loading_div").hide();
                return false;
            } else {
                $("#submit_div").hide();
                $("#loading_div").show();
                return true;
            }

        }

        function get_periodsForCompo(element) {
            let alter_date = $(element).val();
            let off_date = $("#off_date").val();
            $("#view_staff").html('');
            if (alter_date != '') {
                if (off_date != '') {
                    if (off_date != alter_date) {
                        $("input, select").prop("disabled", true);
                        $("#loader").show();
                        $("#save").hide();
                        $.ajax({
                            url: '{{ route('admin.Non_Teach_staff-request-leaves.check_for_compo') }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'leave_date': off_date,
                                'alter_date': alter_date,
                            },
                            success: function(response) {
                                let status = response.status;
                                let data = response.data;
                                if (status == false) {
                                    Swal.fire('', data, "error");
                                    $('#off_date').val('');
                                    $('#alter_date').val('');
                                }
                                $("#loader").hide();
                                $("input, select").prop("disabled", false);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                if (jqXHR.status) {
                                    if (jqXHR.status == 500) {
                                        Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                                    } else {
                                        Swal.fire('', jqXHR.status, 'error');
                                    }
                                } else if (textStatus) {
                                    Swal.fire('', textStatus, 'error');
                                } else {
                                    Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                                }
                            }
                        })
                    } else {
                        Swal.fire('', 'Off Date & Alter Date Can\'t Be A Same Date !', 'warning');
                        $(element).val('')
                    }
                } else {
                    Swal.fire('', 'Please Choose The Off Date', 'warning');
                    $(element).val('')
                }
            } else {
                Swal.fire('', 'Please Choose The Alter Date', 'warning');
            }
        }
    </script>
@endsection
