@php
    if ($who == 'tech') {
        $key = 'layouts.teachingStaffHome';
    } else {
        $key = 'layouts.non_techStaffHome';
    }
@endphp
@extends($key)
@section('content')
    <div class="container">
        <style>
            .table.dataTable tbody td.select-checkbox:before {
                content: none !important;
            }
        </style>
        <div class="row gutters">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-2 text-primary">Permission Request</h5>
                    </div>
                    <div class="card-body">

                        <div class="row gutters">

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
                                style="display:flex;justify-content: flex-end;">

                                <button type="button" class="manual_bn d-block">Available Permission (Personal) :
                                    {{ $staff_edit->personal_permission == '' ? 0 : $staff_edit->personal_permission }}</button>

                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="off_date_div">
                                <div class="form-group">
                                    <label for="off_date">Permission</label>
                                    <input type="hidden" name="id" id="id" value="{{ $staff_edit->id }}">
                                    <input type="hidden" id="pp_availability"
                                        value="{{ $staff_edit->personal_permission == '' ? 0 : $staff_edit->personal_permission }}">
                                    <select class="form-control select2" name="Permission" id="Permission"
                                        onchange="checker(this)" required>
                                        @if ($staff_edit->Permission == 'Personal')
                                            <option value="Personal"
                                                {{ $staff_edit->Permission == 'Personal' ? 'selected' : '' }}>Personal
                                            </option>
                                        @elseif ($staff_edit->Permission == 'On Duty')
                                            <option value="On Duty"
                                                {{ $staff_edit->Permission == 'On Duty' ? 'selected' : '' }}>On Duty
                                            </option>
                                        @elseif ($staff_edit->Permission == '')
                                            <option value="">Please Select</option>
                                            <option value="Personal">Personal</option>
                                            <option value="On Duty">On Duty</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="off_date_div">
                                <div class="form-group">
                                    <label for="off_date"> Date</label>
                                    <input type="text" class="form-control date" name="date" id="date"
                                        placeholder="Enter  Date" value="{{ $staff_edit->date }}" required>
                                </div>
                            </div>
                            @if ($staff_edit->Permission == 'Personal')
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="from_time_div_one">
                                    <div class="form-group">
                                        <label for="from_date" style="display:block;">From Time</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('from_time') ? 'is-invalid' : '' }}"
                                            name="from_time" id="from_time">
                                            <option value="" {{ $staff_edit->from_time == '' ? 'selected' : '' }}>
                                                Please Select</option>
                                            <option value="08:00:00"
                                                {{ $staff_edit->from_time == '08:00:00' ? 'selected' : '' }}>08:00:00 AM
                                            </option>
                                            <option value="15:00:00"
                                                {{ $staff_edit->from_time == '15:00:00' ? 'selected' : '' }}>03:00:00 PM
                                            </option>
                                            <option value="16:00:00"
                                                {{ $staff_edit->from_time == '16:00:00' ? 'selected' : '' }}>04:00:00
                                                PM
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="to_time_div_one">
                                    <div class="form-group">
                                        <label for="to_time" style="display:block;">To Time</label>
                                        {{-- <input type="text" class="form-control" name="to_time" id="to_time"
                                        value="{{ $staff_edit->to_time }}" readonly> --}}
                                        <select
                                            class="form-control select2 {{ $errors->has('from_time') ? 'is-invalid' : '' }}"
                                            name="to_time" id="to_time">
                                            <option value="{{ $staff_edit->to_time }}" selected>
                                                {{ $staff_edit->to_time }}</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif ($staff_edit->Permission == 'On Duty')
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="from_time_div_two">
                                    <div class="form-group">
                                        <label for="from_time_od">From Time</label>
                                        <input type="time" class="form-control" name="from_time_od" id="from_time_od"
                                            placeholder="Enter From Time" value="{{ $staff_edit->from_time }}"
                                            onchange="time_checker(this)">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="to_time_div_two">
                                    <div class="form-group">
                                        <label for="to_time_od">To Time</label>
                                        <input type="time" class="form-control" name="to_time_od" id="to_time_od"
                                            placeholder="Enter To Time" value="{{ $staff_edit->to_time }}" readonly>
                                    </div>
                                </div>
                            @elseif ($staff_edit->Permission == '')
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="insert_from_time_one">
                                    <div class="form-group">
                                        <label for="from_date" style="display:block;">From Time</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('from_time') ? 'is-invalid' : '' }}"
                                            name="from_time" id="from_time">
                                            <option value="" selected>Please Select</option>
                                            <option value="08:00:00">08:00:00 AM</option>
                                            <option value="15:00:00">03:00:00 PM</option>
                                            <option value="16:00:00">04:00:00 PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="insert_to_time_one">
                                    <div class="form-group">
                                        <label for="to_time" style="display:block;">To Time</label>
                                        {{-- <input type="text" class="form-control" name="to_time" id="to_time"
                                    value="{{ $staff_edit->to_time }}" readonly> --}}
                                        <select
                                            class="form-control select2 {{ $errors->has('from_time') ? 'is-invalid' : '' }}"
                                            name="to_time" id="to_time">
                                            <option value="" selected></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="display:none;"
                                    id="insert_from_time_two">
                                    <div class="form-group">
                                        <label for="from_time_od">From Time</label>
                                        <input type="time" class="form-control" name="from_time_od" id="from_time_od"
                                            placeholder="Enter From Time" value="{{ $staff_edit->from_time }}"
                                            onchange="time_checker(this)">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="display:none;"
                                    id="insert_to_time_two">
                                    <div class="form-group">
                                        <label for="to_time_od">To Time</label>
                                        <input type="time" class="form-control" name="to_time_od" id="to_time_od"
                                            placeholder="Enter To Time" value="{{ $staff_edit->to_time }}" readonly>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="alter_date_div">
                                <div class="form-group">
                                    <label for="alter_date">Reason</label>
                                    <textarea type="text" class="form-control" id="reason" name="reason" placeholder="Enter Reason"
                                        value="{{ $staff_edit->reason }}" required>{{ $staff_edit->reason }}</textarea>
                                </div>
                            </div>


                        </div>
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    <button type="submit" id="submit" name="submit" onclick="checkDatas()"
                                        class="btn btn-primary Edit">{{ $staff_edit->add }}</button>
                                </div>
                                <div class="text-right text-primary" id="loading_div" style="display:none;">
                                    <b>Processing...</b>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @if (count($list) > 0)
            <div class="row gutters mt-3 mb-3">
                <div class="col" style="padding:0;">
                    <div class="card h-100">

                        <div class="card-body table-responsive">
                            <h5 class="mb-3 text-primary">Requested Permission List</h5>
                            <table
                                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-leaveList"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Permission Type
                                        </th>
                                        <th>
                                            From Time
                                        </th>
                                        <th>
                                            To Time
                                        </th>
                                        <th>
                                            Date
                                        </th>
                                        <th>
                                            Reason
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
                                        <tr>
                                            <td>{{ $list[$i]->Permission }}</td>
                                            <td>{{ $list[$i]->from_time }}</td>
                                            <td>{{ $list[$i]->to_time }}</td>
                                            <td>{{ $list[$i]->date }}</td>
                                            <td>{{ $list[$i]->reason }}</td>
                                            <td>
                                                @if ($list[$i]->status == '0')
                                                    <div class="p-2 Pending">
                                                        Pending
                                                    </div>
                                                @elseif ($list[$i]->status == '1')
                                                    <div class="p-2 Pending">
                                                        Waiting for HR Approval
                                                    </div>
                                                @elseif ($list[$i]->status == '2')
                                                    <div class="p-2 Approved">
                                                        Approved
                                                    </div>
                                                @elseif ($list[$i]->status == '3')
                                                    <div class="btn mt-2 btn-danger">
                                                        Rejected
                                                    </div>
                                                @elseif ($list[$i]->status == '4')
                                                    <div class="btn mt-2 btn-primary">
                                                        NeedClarification
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($list[$i]->status == '0')
                                                    <form method="POST"
                                                        action="{{ route('admin.staff-permissionsreq.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <button type="submit" id="updater" name="updater"
                                                            value="updater" class="btn btn-xs btn-info">Edit</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('admin.staff-permissionsreq.destroy', $list[$i]->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token"
                                                            value="{{ csrf_token() }}">
                                                        <input type="submit" class="btn btn-xs btn-danger mt-2"
                                                            value="{{ trans('global.delete') }}">
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
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
        $(document).ready(function() {
            $('#from_time').on('change', function() {
                if ($(this).val() == '08:00:00') {
                    $('#to_time').html(`<option value="09:00:00" selected>09:00:00 AM </option>`);
                } else if ($(this).val() == '15:00:00') {
                    $('#to_time').html(`<option value="16:00:00" selected>04:00:00 PM </option>`);
                } else if ($(this).val() == '16:00:00') {
                    $('#to_time').html(`<option value="17:00:00" selected>05:00:00 PM </option>`);
                } else {
                    $('#to_time').html(`<option value="" selected></option>`);
                }
            });
        });

        window.onload = function() {
            $("#loading_div").hide();
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

        function checker(element) {

            if (element.value == 'On Duty') {

                $("#insert_from_time_one").hide();
                $("#insert_to_time_one").hide();
                $("#from_time").val('');
                $("#to_time").val('');
                $("#insert_from_time_two").show();
                $("#insert_to_time_two").show();
                $("#from_time_od").attr('required', true);
                $("#to_time_od").attr('required', true);
                $("#from_time").attr('required', false);
                $("#to_time").attr('required', false);

            } else if (element.value == 'Personal') {

                let availability = $("#pp_availability").val();


                if (availability <= 0) {
                    Swal.fire('', 'The Permission Exhausted For this Month', 'info');
                    location.reload();
                }


                $("#insert_from_time_one").show();
                $("#insert_to_time_one").show();
                $("#insert_from_time_two").hide();
                $("#insert_to_time_two").hide();
                $("#from_time_od").val('');
                $('#from_time').html(`<option value="" selected>Please Select</option>
                                        <option value="08:00:00">08:00:00 AM</option>
                                        <option value="15:00:00">03:00:00 PM</option>
                                        <option value="16:00:00">04:00:00 PM</option>`);
                $("#to_time_od").val('');
                $('#to_time').html(`<option value="" selected></option>`);
                $("#from_time").attr('required', true);
                $("#to_time").attr('required', true);
                $("#from_time_od").attr('required', false);
                $("#to_time_od").attr('required', false);
            }

        }

        function time_checker(element) {
            // console.log(element.value)
            let from_time = element.value;

            let inputHour = from_time.split(":")[0];
            let inputMin = from_time.split(":")[1];

            let add = parseInt(inputHour) + 2;
            let to_time;
            if (add < 10) {
                to_time = '0' + add + ':' + inputMin;
            } else {
                to_time = add + ':' + inputMin;
            }
            $("#to_time_od").val(to_time);
            // console.log(to_time,inputHour,inputMin);
        }

        function checkDatas() {
            event.preventDefault();
            let from_time = '';
            let to_time = '';
            let theDate = $("#date").val();
            let currentStatus = false;

            if ($("#Permission").val() == 'On Duty') {
                if ($("#date").val() == '') {
                    Swal.fire('', 'Please Select The Date', 'error');
                    return false;
                }
                if ($("#from_time_od").val() == '') {
                    Swal.fire('', 'Please Select The From Time', 'error');
                    return false;
                }
                if ($("#to_time_od").val() == '') {
                    Swal.fire('', 'Please Select The To Time', 'error');
                    return false;
                }
                if ($("#reason").val() == '') {
                    Swal.fire('', 'Please Select The Date', 'error');
                    return false;
                }

                $("#loading_div").show();
                $("#submit").hide();

                $.ajax({
                    url: '{{ route('admin.staff-permissionsreq.staff_update') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $("#id").val(),
                        'from_time': $("#from_time_od").val(),
                        'to_time': $("#to_time_od").val(),
                        'date': $("#date").val(),
                        'Permission': 'On Duty',
                        'reason': $("#reason").val()
                    },
                    success: function(response) {
                        if (response.status == true) {
                            Swal.fire('', response.data, 'success');
                            location.reload();
                        } else {
                            Swal.fire('', response.data, 'error');
                            $("#loading_div").hide();
                            $("#submit").show();
                        }
                    }
                })
            }

            if ($("#Permission").val() == 'Personal') {
                if ($("#date").val() == '') {
                    Swal.fire('', 'Please Select The Date', 'error');
                    return false;
                }
                if ($("#from_time").val() == '') {
                    Swal.fire('', 'Please Select The From Time', 'error');
                    return false;
                }
                if ($("#to_time").val() == '') {
                    Swal.fire('', 'Please Select The To Time', 'error');
                    return false;
                }
                if ($("#reason").val() == '') {
                    Swal.fire('', 'Please Select The Date', 'error');
                    return false;
                }

                from_time = $("#from_time").val();
                to_time = $("#to_time").val();

                if (from_time != '' && to_time != '' && theDate != '') {
                    $("#loading_div").show();
                    $("#submit").hide();
                    $.ajax({
                        url: '{{ route('admin.staff-permissionsreq.checkDate') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'from_time': from_time,
                            'to_time': to_time,
                            'date': theDate
                        },
                        success: function(response) {

                            if (response.status == false) {
                                Swal.fire('', response.data, 'error');
                                $("#loading_div").hide();
                                $("#submit").show();

                            } else {
                                $.ajax({
                                    url: '{{ route('admin.staff-permissionsreq.staff_update') }}',
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        'id': $("#id").val(),
                                        'from_time': $("#from_time").val(),
                                        'to_time': $("#to_time").val(),
                                        'date': $("#date").val(),
                                        'Permission': 'Personal',
                                        'reason': $("#reason").val()
                                    },
                                    success: function(response) {
                                        if (response.status == true) {
                                            Swal.fire('', response.data, 'success');
                                            location.reload();
                                        } else {
                                            Swal.fire('', response.data, 'error');
                                            $("#loading_div").hide();
                                            $("#submit").show();
                                        }
                                    }
                                })
                            }
                        }
                    })

                } else {
                    $("#loading_div").hide();
                    $("#submit").show();
                    return false;
                }
            }

        }
    </script>
@endsection
