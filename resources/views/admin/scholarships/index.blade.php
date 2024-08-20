@extends('layouts.admin')
@section('content')
    @can('scholarship_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Scholarship
                </button>
            </div>
        </div>
    @endcan
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Scholarships List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Scholarship text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Scholarship Name
                        </th>
                        <th>
                            Foundation Name
                        </th>
                        <th>
                            Amount
                        </th>
                        <th>
                            Percentage
                        </th>
                        <th>
                            Started AY
                        </th>
                        <th>
                            Started Batch
                        </th>
                        <th>
                            Status
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

    <div class="modal fade" id="scholarshipModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="scholarship" class="required">Scholarship Name</label>
                            <input type="hidden" name="scholarship_id" id="scholarship_id" value="">
                            <input type="text" value="" name="scholarship" style="text-transform:uppercase"
                                id="scholarship" class="form-control">
                            <span id="scholarship_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="foundation_name" class="required">Sponser / Organization Full Name</label>
                            <input type="text" value="" name="foundation_name" style="text-transform:uppercase"
                                id="foundation_name" class="form-control">
                            <span id="foundation_name_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                            <label for="started_ay" class="required">Started AY</label>
                            <select class="select2 form-control" name="started_ay" id="started_ay">
                                <option value="">Select Ay</option>
                                @foreach ($getAys as $id => $ay)
                                    <option value="{{ $id }}">{{ $ay }}</option>
                                @endforeach
                            </select>
                            <span id="started_ay_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                            <label for="started_batch" class="required">Started Batch</label>
                            <select class="select2 form-control" name="started_batch" id="started_batch">
                                <option value="">Select Batch</option>
                                @foreach ($getBatches as $id => $batch)
                                    <option value="{{ $id }}">{{ $batch }}</option>
                                @endforeach
                            </select>
                            <span id="started_batch_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                            <label for="remarks">Remarks</label>
                            <input type="text" value="" name="remarks" id="remarks" class="form-control">
                            <span id="remarks_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                            <label for="status" class="required">Status</label>
                            <select class="select2 form-control" name="status" id="status" onchange="openDivs(this)">
                                <option value="">Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <span id="status_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="received_in_div">
                            <label for="received_in" class="required">Received In</label>
                            <select class="select2 form-control" name="received_in" id="received_in"
                                onchange="received_in(this)">
                                <option value="">Select Type</option>
                                <option value="amount">Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                            <span id="received_in_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>


                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9 form-group" id="reasonDiv"
                            style="display:none;">
                            <label for="inactive_reason" class="required">Inactive Reason</label>
                            <input type="text" value="" name="inactive_reason" id="inactive_reason"
                                class="form-control">
                            <span id="inactive_reason_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 form-group" id="dateDiv"
                            style="display:none;">
                            <label for="inactive_date" class="required">Inactive Date</label>
                            <input type="text" class="date form-control" value="" name="inactive_date"
                                id="inactive_date">
                            <span id="inactive_date_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group" style="display: none;"
                            id="amount_input">
                            <label for="amount_input_box">Amount</label>
                            <input type="number" value="" name="amount_input_box" id="amount_input_box"
                                placeholder="Enter Amount" class="form-control">
                            <span id="amount_input_box_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group" style="display: none;"
                            id="percentage_input">
                            <label for="percentage_input_box">Percentage</label>
                            <input type="number" value="" name="percentage_input_box" id="percentage_input_box"
                                placeholder="Enter Percentage" class="form-control">
                            <span id="percentage_input_box_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveScholarship()">Save</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader"></span>
                    </div>
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
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('scholarship_delete')
                let deleteButton = {
                    text: 'Delete Selected',
                    className: 'btn-outline-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            Swal.fire('', 'No Rows Selected', 'warning');

                            return
                        }

                        Swal.fire({
                            title: "Are You Sure?",
                            text: "Do You Really Want To Delete !",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            reverseButtons: true
                        }).then(function(result) {
                            if (result.value) {
                                $('.secondLoader').show()

                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: "{{ route('admin.scholarships.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        $('.secondLoader').hide()

                                        Swal.fire('', response.data, response.status);
                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-Scholarship')) {
                $('.datatable-Scholarship').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.scholarships.index') }}",
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
                        data: 'foundation_name',
                        name: 'foundation_name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'percentage',
                        name: 'percentage'
                    },
                    {
                        data: 'started_ay',
                        name: 'started_ay'
                    },
                    {
                        data: 'started_batch',
                        name: 'started_batch'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-Scholarship').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#scholarship_id").val('');
            $("#scholarship").val('');
            $("#foundation_name").val('');
            $("#started_ay").val('');
            $("#started_ay").select2()
            $("#started_batch").val('');
            $("#started_batch").select2()
            $("#remarks").val('');
            $("#received_in_div").show();
            $("#inactive_reason").val('')
            $("#inactive_date").val('')
            $("#status option:nth-child(1)").prop('selected', true);
            $("#received_in option:nth-child(1)").prop('selected', true);
            $("#amount_input").hide();
            $("#percentage_input").hide();
            $("#status").select2()
            $("#received_in").select2()
            $("#scholarship_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#reasonDiv").hide()
            $("#dateDiv").hide()
            $("#scholarshipModal").modal();
        }

        function openDivs(element) {
            $("#inactive_reason").val('')
            $("#inactive_date").val('')
            if ($(element).val() == 'Inactive') {
                $("#reasonDiv").show();
                $("#dateDiv").show();
                $("#inactive_reason").val('').show()
                $("#inactive_date").val('').show()
            } else {
                $("#dateDiv").hide();
                $("#reasonDiv").hide();
            }
        }

        function received_in(element1) {

            $("#amount_input_box").val('');
            $("#percentage_input_box").val('');

            if ($(element1).val() == 'amount') {

                $("#amount_input").show();
                $("#percentage_input").hide();

                $('#amount_input_box').on('input', function() {
                    var value = $(this).val().replace(/[^0-9]/g, '');
                    $(this).val(value);
                });

            } else if ($(element1).val() == 'percentage') {

                $("#amount_input").hide();
                $("#percentage_input").show();

                $('#percentage_input_box').on('input', function() {
                    var number = parseFloat($(this).val());
                    if (isNaN(number)) {
                        number = 0; // Default to 0 if input is not a number
                    }
                    if (number > 100) {
                        number = 100;
                    }

                    // $(this).val(number.toFixed(1));
                    $(this).val(number);

                });

            } else {
                $("#amount_input").hide();
                $("#percentage_input").hide();
            }

        }



        function saveScholarship() {
            $("#loading_div").hide();
            if ($("#scholarship").val() == '') {
                $("#scholarship_span").html(`Scholarship Is Required.`);
                $("#received_in_span").hide();
                $("#scholarship_span").show();
                $("#foundation_name_span").hide();
                $("#started_ay_span").hide();
                $("#started_batch_span").hide();

            } else if ($("#foundation_name").val() == '') {
                $("#foundation_name_span").html(`Foundation Name Is Required.`);
                $("#scholarship_span").hide();
                $("#received_in_span").hide();
                $("#foundation_name_span").show();
                $("#started_ay_span").hide();
                $("#started_batch_span").hide();

            } else if ($("#started_ay").val() == '') {
                $("#started_ay_span").html(`AY Is Required.`);
                $("#scholarship_span").hide();
                $("#received_in_span").hide();
                $("#foundation_name_span").hide();
                $("#started_ay_span").show();
                $("#started_batch_span").hide();

            } else if ($("#started_batch").val() == '') {
                $("#started_batch_span").html(`Batch Is Required.`);
                $("#scholarship_span").hide();
                $("#received_in_span").hide();
                $("#foundation_name_span").hide();
                $("#started_ay_span").hide();
                $("#started_batch_span").show();

            }
            else if ($("#received_in").val() == '') {

                $("#received_in_span").html(`Received Type Is Required.`);
                $("#received_in_span").show();
                $("#scholarship_span").hide();
                $("#foundation_name_span").hide();
                $("#started_ay_span").hide();
                $("#started_batch_span").hide();

            }
            else {
                $("#inactive_reason_span").hide();
                $("#inactive_date_span").hide();
                $("#scholarship_span").hide();
                $("#foundation_name_span").hide();
                $("#started_ay_span").hide();
                $("#started_batch_span").hide();
                $("#received_in_span").hide();


                if ($("#status").val() == 'Inactive') {
                    if ($("#inactive_reason").val() == '') {

                        $("#inactive_reason_span").html(`Inactive Reason Is Required.`);
                        $("#inactive_reason_span").show();
                        $("#inactive_date_span").hide();
                        $("#scholarship_span").hide();
                        $("#foundation_name_span").hide();
                        $("#started_ay_span").hide();
                        $("#started_batch_span").hide();

                    } else if ($("#inactive_date").val() == '') {

                        $("#inactive_date_span").html(`Inactive Date Is Required.`);
                        $("#inactive_reason_span").hide();
                        $("#inactive_date_span").show();
                        $("#scholarship_span").hide();
                        $("#foundation_name_span").hide();
                        $("#started_ay_span").hide();
                        $("#started_batch_span").hide();
                    }
                } else {
                    $("#inactive_reason_span").hide();
                    $("#inactive_date_span").hide();
                }
                $("#save_div").hide();
                $("#loading_div").show();
                let id = $("#scholarship_id").val();
                let name = $("#scholarship").val();
                let foundation_name = $("#foundation_name").val();
                let started_ay = $("#started_ay").val();
                let started_batch = $("#started_batch").val();
                let remarks = $("#remarks").val();
                let status = $("#status").val();
                let inactive_reason = '';
                let inactive_date = '';
                let received_in = $("#received_in").val();
                let amount_input_box = '';
                let percentage_input_box = '';
                if (received_in == 'amount') {
                    amount_input_box = $("#amount_input_box").val();
                    amount_input_box = amount_input_box;
                } else if (received_in == 'percentage') {
                    percentage_input_box = $("#percentage_input_box").val();
                    percentage_input_box = percentage_input_box + '%';
                }
                if (status == 'Inactive') {
                    inactive_reason = $("#inactive_reason").val()
                    inactive_date = $("#inactive_date").val()
                }
                $.ajax({
                    url: "{{ route('admin.scholarships.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'name': name,
                        'foundation_name': foundation_name,
                        'started_ay': started_ay,
                        'started_batch': started_batch,
                        'remarks': remarks,
                        'status': status,
                        'received_in' : received_in,
                        'inactive_reason': inactive_reason,
                        'inactive_date': inactive_date,
                        'amount_input_box': amount_input_box,
                        'percentage_input_box': percentage_input_box,
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#scholarshipModal").modal('hide');
                        callAjax();
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }

        function viewScholarship(id) {
            console.log(id);
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.scholarships.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        $("#inactive_reason").hide()
                        $("#inactive_date").hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            console.log(typeof(data.status));
                            console.log(data.percentage)
                            $("#scholarship_id").val(data.id);
                            $("#scholarship").val(data.name);
                            $("#foundation_name").val(data.foundation_name);
                            // $("#received_in_div").hide();
                            $("#received_in").select2();
                            $("#received_in").val(data.scholarship_type);
                            if (data.percentage != null) {
                                var numericPercentage = data.percentage.replace('%', '');
                                $("#percentage_input").show();
                                $("#amount_input").hide();
                                $("#percentage_input_box").val(numericPercentage);
                            } else if (data.amount != null) {

                                $("#amount_input").show();
                                $("#percentage_input").hide();
                                $("#amount_input_box").val(data.amount);

                            } else {
                                $("#amount_input").hide();
                                $("#percentage_input").hide();

                            }
                            $("#started_ay").val(data.started_ay);
                            $("#started_ay").select2()
                            $("#started_batch").val(data.started_batch);
                            $("#started_batch").select2()
                            $("#remarks").val(data.remarks);
                            if (data.status == '1') {
                                $("#status option:nth-child(2)").prop('selected', true);
                                $("#status").select2()
                                $("#reasonDiv").hide()
                                $("#dateDiv").hide()
                                $("#inactive_reason").val(data.inactive_reason).hide()
                                $("#inactive_date").val(data.inactive_date).hide()
                            } else if (data.status == '0') {
                                $("#status option:nth-child(3)").prop('selected', true);
                                $("#status").select2()
                                $("#inactive_reason").val(data.inactive_reason).show()
                                $("#reasonDiv").show()
                                $("#inactive_date").val(data.inactive_date).show()
                                $("#dateDiv").show()
                            }

                            $("#save_div").hide();
                            $("#scholarship_span").hide();
                            $("#loading_div").hide();
                            $("#scholarshipModal").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }

        function editScholarship(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.scholarships.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            console.log(data);
                            $("#scholarship_id").val(data.id);
                            $("#scholarship").val(data.name);
                            $("#foundation_name").val(data.foundation_name);
                            $("#started_ay").val(data.started_ay);
                            $("#started_ay").select2()
                            $("#started_batch").val(data.started_batch);
                            $("#started_batch").select2()
                            $("#remarks").val(data.remarks);
                            $("#received_in").val(data.scholarship_type);
                            $("#received_in").select2();

                            if (data.percentage != null) {
                                var numericPercentage = data.percentage.replace('%', '');
                                $("#percentage_input").show();
                                $("#amount_input").hide();
                                $("#percentage_input_box").val(numericPercentage);
                            } else if (data.amount != null) {

                                $("#amount_input").show();
                                $("#percentage_input").hide();
                                $("#amount_input_box").val(data.amount);

                            } else {
                                $("#amount_input").hide();
                                $("#percentage_input").hide();

                            }


                            if (data.status == '1') {
                                $("#status option:nth-child(2)").prop('selected', true);
                                $("#status").select2()
                                $("#reasonDiv").hide()
                                $("#dateDiv").hide()
                                $("#inactive_reason").val(data.inactive_reason).hide()
                                $("#inactive_date").val(data.inactive_date).hide()
                            } else if (data.status == '0') {
                                $("#reasonDiv").show()
                                $("#dateDiv").show()
                                $("#status option:nth-child(3)").prop('selected', true);
                                $("#status").select2()
                                $("#inactive_reason").val(data.inactive_reason).show()
                                $("#inactive_date").val(data.inactive_date).show()
                            }
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#scholarship_span").hide();
                            $("#section_span").hide();
                            $("#loading_div").hide();
                            $("#scholarshipModal").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }

        function deleteScholarship(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You Really Want To Delete !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $('.secondLoader').show()

                        $.ajax({
                            url: "{{ route('admin.scholarships.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $('.secondLoader').hide()

                                Swal.fire('', response.data, response.status);
                                callAjax();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                if (jqXHR.status) {
                                    if (jqXHR.status == 500) {
                                        Swal.fire('', 'Request Timeout / Internal Server Error',
                                            'error');
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
                    }
                })
            }
        }
    </script>
@endsection
