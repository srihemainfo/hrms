@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Consolidated Statement Generation
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="regulation">Regulation</label>
                        <select name="regulation" id="regulation" class="form-control select2">
                            <option value="">Select Regulation</option>
                            @foreach ($regulations as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="batch">Batch</label>
                        <select name="batch" id="batch" class="form-control select2">
                            <option value="">Select Batch</option>
                            @foreach ($batches as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="course">Course</label>
                        <select name="course" id="course" class="form-control select2">
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group text-right" id="action_div">
                        <button class="enroll_generate_bn bg-primary" style="margin-top:32px;"
                            onclick="searchStatement()">Search</button>
                        @can('consolidated_statement_generate_access')
                            <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                                onclick="checkGeneration()">Generate</button>
                        @endcan
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                    <div id="loading_div" class="form-group text-right" style="display:none;margin-top:32px;">
                        <b class="text-success">Generating...</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Statement text-center">
                <thead>
                    <tr>
                        <th>
                            S.No
                        </th>
                        <th>
                            Regulation
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>Course</th>
                        <th>Generated Date</th>
                        <th>Preview</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
            dtButtons.splice(0, 8);

            if ($.fn.DataTable.isDataTable('.datatable-Statement')) {
                $('.datatable-Statement').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.consolidated-statement.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'regulation',
                        name: 'regulation'
                    },
                    {
                        data: 'batch',
                        name: 'batch'
                    },
                    {
                        data: 'course',
                        name: 'course'
                    },
                    {
                        data: 'generated_date',
                        name: 'generated_date'
                    },
                    {
                        data: 'preview',
                        name: 'preview',
                        render: function(response) {
                            var data = JSON.parse(response);
                            return `<a class="btn btn-sm btn-success" target="_blank" href="{{ url('admin/consolidated-statement/pdf') }}/${data.batch}/${data.course}/${data.regulation}"> View PDF</a>`;
                        },
                        type: 'html'
                    },
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-Statement').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function reset() {
            $("#course").val($("#target option:first").val());
            $("#batch").val($("#target option:first").val());
            $("#regulation").val($("#target option:first").val());
            $('select').select2();
        }

        function searchStatement() {
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else {
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
                dtButtons.splice(0, 8);

                if ($.fn.DataTable.isDataTable('.datatable-Statement')) {
                    $('.datatable-Statement').DataTable().destroy();
                }
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.consolidated-statement.search') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'regulation': $("#regulation").val(),
                            'batch': $("#batch").val(),
                            'course': $("#course").val()
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'regulation',
                            name: 'regulation'
                        },
                        {
                            data: 'batch',
                            name: 'batch'
                        },
                        {
                            data: 'course',
                            name: 'course'
                        },
                        {
                            data: 'generated_date',
                            name: 'generated_date'
                        },
                        {
                            data: 'preview',
                            name: 'preview',
                            render: function(response) {
                                var data = JSON.parse(response);
                                return `<a class="btn btn-sm btn-success" target="_blank" href="{{ url('admin/consolidated-statement/pdf') }}/${data.batch}/${data.course}/${data.regulation}"> View PDF</a>`;
                            },
                            type: 'html'
                        },
                    ],
                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 10,
                };
                let table = $('.datatable-Statement').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
            }
        }

        function checkGeneration() {
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else {
                $("#loading_div").show();
                $("#action_div").hide();
                $.ajax({
                    url: "{{ route('admin.consolidated-statement.check-generation') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': $("#regulation").val(),
                        'batch': $("#batch").val(),
                        'course': $("#course").val()
                    },
                    success: function(response) {
                        $("#loading_div").hide();
                        $("#action_div").show();
                        if (response.status == true) {
                            generate();
                        } else if (response.data != '') {
                            Swal.fire('', response.data, 'error');
                        } else {
                            reGenerate();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#loading_div").hide();
                        $("#action_div").show();
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
        }

        function reGenerate() {
            Swal.fire({
                title: "Consolidated Statement Already Generated for this Batch !",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Regenerate",
                cancelButtonText: "OK-Exit",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $("#loading_div").show();
                    $("#action_div").hide();
                    $.ajax({
                        url: "{{ route('admin.consolidated-statement.generation') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'regulation': $("#regulation").val(),
                            'batch': $("#batch").val(),
                            'course': $("#course").val(),
                            'action': '2'
                        },
                        success: function(response) {

                            $("#loading_div").hide();
                            $("#action_div").show();
                            if (response.status == true) {
                                Swal.fire('', response.data, 'success');
                                callAjax();

                            } else {
                                Swal.fire('', response.data, 'error');
                            }
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
                                Swal.fire('', 'Request Failed With Status: ' + jqXHR
                                    .statusText,
                                    "error");
                            }
                            $("#loading_div").hide();
                            $("#action_div").show();
                        }
                    })
                }
            })
        }

        function generate() {

            $("#loading_div").show();
            $("#action_div").hide();
            $.ajax({
                url: "{{ route('admin.consolidated-statement.generation') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'regulation': $("#regulation").val(),
                    'batch': $("#batch").val(),
                    'course': $("#course").val(),
                    'action': '1'
                },
                success: function(response) {

                    $("#loading_div").hide();
                    $("#action_div").show();
                    if (response.status == true) {
                        Swal.fire('', response.data, 'success');
                        callAjax();

                    } else {
                        Swal.fire('', response.data, 'error');
                    }
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
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR
                            .statusText,
                            "error");
                    }
                    $("#loading_div").hide();
                    $("#action_div").show();
                }
            })
        }
    </script>
@endsection
