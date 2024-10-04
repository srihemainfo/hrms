@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <div>payslip</div>
    </div>

    <div class="card-body">
        <div class="row gutters">
            <div class="col-xl-9 col-lg-9 col-md-6 col-sm-6 col-12">
                <form method="POST" action="" enctype="multipart/form-data" id="search-form">
                    @csrf
                    <div class="row gutters">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="" for="dept">Department</label>
                                <select class="form-control select2" name="dept" id="dept" onchange="get_dept(this)">
                                    <option value="null">All Departments</option>
                                    @foreach ($dept as $id => $key)
                                    <option value="{{ $id }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="" for="staff_code">Staff Name</label>
                                <select class="form-control select2" name="staff_code" id="staff_code" onchange="get_code(this)">
                                    <option value="null">All Staffs</option>

                                    @foreach ($staff as $id => $key)
                                    <option value="{{ $id }}">{{ $key . ' (' . $id . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="" for="month">Month</label>
                                <select class="form-control select2" name="month" id="month" onchange="get_month(this)">

                                    <option value="null">All Months</option>
                                    @php
                                        $months = array();
                                        for ($month = 1;
                                        $month <= 12; $month++) { $timestamp=mktime(0, 0, 0, $month, 1);
                                            $monthName=date("F", $timestamp);
                                    @endphp <option value="{{ $monthName }}">{{ $monthName }}</option>
                                    @php
                                    }
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="required" for="year">Year</label>
                                <select class="form-control select2" name="year" id="year" required onchange="get_year(this)">
                                    @php
                                    $current_year = date('Y');
                                    $next_year = $current_year + 1;

                                    @endphp
                                    <option value="{{ $current_year }}" selected>{{ $current_year }}</option>
                                    <option value="{{ $next_year }}">{{ $next_year }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="form-group" style="padding-top: 30px;">
                    <button id="searchButton" class="enroll_generate_bn">Get
                        Report</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-10">Pay Slip List</div>
            <div class="col-2 text-center">
                <a class="enroll_generate_bn" id="bulk_pdf" style="color:white;" target="_blank">Bulk Download</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        {{-- <div class="loader" id="loader" style="position:absolute;">
                <div class="spinner-border text-primary"></div>
            </div> --}}
        <table id="my-table" class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ToolsDepartment text-center">
            <thead>
                <tr>
                    <th width="10">

                    </th>

                    <th>
                        {{ 'ID' }}
                    </th>
                    <th>
                        {{ 'Staff Name' }}
                    </th>
                    <th>
                        {{ 'Staff Code' }}
                    </th>
                    <th>
                        {{ 'Month' }}
                    </th>
                    <th>
                        {{ 'Net pay' }}
                    </th>
                    <th>
                        {{ 'Actions' }}&nbsp;
                    </th>
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function() {

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

        dtButtons.splice(2, 5);
        // dtButtons.push(pdf);
        // dtButtons.push(print);
        // console.log(dtButtons)
        let dtOverrideGlobals = {
            buttons: dtButtons,
            deferRender: true,
            retrieve: true,
            aaSorting: [],
            ajax: function(data, callback, settings) {
                if ($('#searchButton').data('clicked')) {
                    // Build custom request based on search button click
                    // alert('eiuhbwiuh');
                    let details = {
                        dept: $("#dept").val(),
                        staff_code: $('#staff_code').val(),
                        year: $('#year').val(),
                        month: $('#month').val()
                    }

                    $("#tbody").html(`<tr><td colspan="7">Loading...</td></tr>`)
                    let request = {
                        url: "{{ route('admin.payslip.index_rep') }}",
                        type: 'GET',
                        data: {
                            dept: $("#dept").val(),
                            staff_code: $('#staff_code').val(),
                            year: $('#year').val(),
                            month: $('#month').val()
                        },
                        dataType: 'json',
                        success: function(data) {
                            $("#tbody").html();
                            callback(data);
                            // alert('lvbiwb');
                        }
                    };
                    $.ajax(request);
                } else {
                    // Default request
                    $.ajax({
                        url: "{{ route('admin.PaySlip.index') }}",
                        type: 'GET',
                        data: data,
                        dataType: 'json',
                        success: function(data) {
                            callback(data);
                        }
                    });
                }
            },
            columns: [{
                    data: 'placeholder',
                    name: 'placeholder',
                    orderable: false,
                    searchable: false
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
                    data: 'staff_code',
                    name: 'staff_code'
                },
                {
                    data: 'month',
                    name: 'month'
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
        // console.log(dtOverrideGlobals);
        let table = $('.datatable-ToolsDepartment').DataTable(dtOverrideGlobals);

        // Add click event listener to search button
        $('#searchButton').click(function() {
            $('#searchButton').data('clicked', true);
            table.ajax.reload();
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    });
</script>
<script>
    let dept = $("#dept").val();
    let staff_code = $('#staff_code').val();
    let year = $('#year').val();
    let month = $('#month').val();

    let url = "{{ route('admin.payslip.bulk_pdf') }}?dept=" + encodeURIComponent(dept) + "&staff_code=" +
        encodeURIComponent(staff_code) + "&year=" + encodeURIComponent(year) + "&month=" +
        encodeURIComponent(month);
    $("#bulk_pdf").attr('href', url);

    function get_year(element) {
        let dept = $("#dept").val();
        let staff_code = $('#staff_code').val();
        let year = $('#year').val();
        let month = $('#month').val();
        let url = "{{ route('admin.payslip.bulk_pdf') }}?dept=" + encodeURIComponent(dept) + "&staff_code=" +
            encodeURIComponent(staff_code) + "&year=" + encodeURIComponent(year) + "&month=" +
            encodeURIComponent(month);

        $("#bulk_pdf").attr('href', url);
    }

    function get_month(element) {
        let dept = $("#dept").val();
        let staff_code = $('#staff_code').val();
        let year = $('#year').val();
        let month = $('#month').val();
        let url = "{{ route('admin.payslip.bulk_pdf') }}?dept=" + encodeURIComponent(dept) + "&staff_code=" +
            encodeURIComponent(staff_code) + "&year=" + encodeURIComponent(year) + "&month=" +
            encodeURIComponent(month);
        $("#bulk_pdf").attr('href', url);
    }

    function get_code(element) {
        let dept = $("#dept").val();
        let staff_code = $('#staff_code').val();
        let year = $('#year').val();
        let month = $('#month').val();
        let url = "{{ route('admin.payslip.bulk_pdf') }}?dept=" + encodeURIComponent(dept) + "&staff_code=" +
            encodeURIComponent(staff_code) + "&year=" + encodeURIComponent(year) + "&month=" +
            encodeURIComponent(month);

        $("#bulk_pdf").attr('href', url);
    }

    function get_dept(element) {
        let dept = $("#dept").val();
        let staff_code = $('#staff_code').val();
        let year = $('#year').val();
        let month = $('#month').val();
        let url = "{{ route('admin.payslip.bulk_pdf') }}?dept=" + encodeURIComponent(dept) + "&staff_code=" +
            encodeURIComponent(staff_code) + "&year=" + encodeURIComponent(year) + "&month=" +
            encodeURIComponent(month);
        $("#bulk_pdf").attr('href', url);

        if (element.value == 'null') {

            $("#staff_code").html(

                `  <option value = "null">All Staffs </option>@foreach ($staff as $id => $key)
                                            <option value="{{ $id }}">{{ $key . ' (' . $id . ')' }}</option>
                                        @endforeach`
            );
        } else if (element.value == '1') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                    @foreach ($cse as $cs)
                        @foreach ($cs as $id => $key)
                            <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                {{ $key . ' (' . $id . ')' }} </option>
                        @endforeach
                    @endforeach`
            );
        } else if (element.value == '2') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                    @foreach ($ece as $cs)
                        @foreach ($cs as $id => $key)
                            <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                {{ $key . ' (' . $id . ')' }} </option>
                        @endforeach
                    @endforeach`
            );
        } else if (element.value == '3') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                    @foreach ($mech as $cs)
                        @foreach ($cs as $id => $key)
                            <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                {{ $key . ' (' . $id . ')' }} </option>
                        @endforeach
                    @endforeach`
            );
        } else if (element.value == '4') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                    @foreach ($ai as $cs)
                        @foreach ($cs as $id => $key)
                            <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                {{ $key . ' (' . $id . ')' }} </option>
                        @endforeach
                    @endforeach`
            );
        } else if (element.value == '5') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                    @foreach ($sh as $cs)
                        @foreach ($cs as $id => $key)
                            <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                {{ $key . ' (' . $id . ')' }} </option>
                        @endforeach
                    @endforeach`
            );
        } else if (element.value == '6') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                     @foreach ($cce as $cs)
                         @foreach ($cs as $id => $key)
                             <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                 {{ $key . ' (' . $id . ')' }} </option>
                         @endforeach
                     @endforeach`
            );
        } else if (element.value == '7') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                     @foreach ($csbs as $cs)
                         @foreach ($cs as $id => $key)
                             <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                 {{ $key . ' (' . $id . ')' }} </option>
                         @endforeach
                     @endforeach`
            );
        } else if (element.value == '8') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                      @foreach ($aiml as $cs)
                          @foreach ($cs as $id => $key)
                              <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                  {{ $key . ' (' . $id . ')' }} </option>
                          @endforeach
                      @endforeach`
            );
        } else if (element.value == '9') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                      @foreach ($admin as $cs)
                          @foreach ($cs as $id => $key)
                              <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                  {{ $key . ' (' . $id . ')' }} </option>
                          @endforeach
                      @endforeach`
            );
        } else if (element.value == '10') {

            $("#staff_code").html(
                `<option value = "null">All Staffs </option>
                      @foreach ($civil as $cs)
                          @foreach ($cs as $id => $key)
                              <option value = "{{ $id }}" {{ old('staff_code') == $id ? 'selected' : '' }}>
                                  {{ $key . ' (' . $id . ')' }} </option>
                          @endforeach
                      @endforeach`
            );
        }
    }
</script>
@endsection
