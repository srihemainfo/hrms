@extends('layouts.admin')
@section('content')
    <style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Grade Sheet Generation
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
                        <label class="required" for="ay">Academic Year</label>
                        <select name="ay" id="ay" class="form-control select2">
                            <option value="">Select AY</option>
                            @foreach ($ays as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="exam_month">Exam Month</label>
                        <select name="exam_month" id="exam_month" class="form-control select2">
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
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="exam_year">Exam Year</label>
                        <select name="exam_year" id="exam_year" class="form-control select2">
                            <option value="">Select Year</option>
                            @foreach ($exam_year as $id => $year)
                                <option value="{{ $year }}">{{ $year }}</option>
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
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="form-group text-right" id="action_div">
                        <button class="enroll_generate_bn bg-primary" style="margin-top:32px;"
                            onclick="searchGradeSheet()">Search</button>
                        @can('grade_sheet_generate_access')
                            <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                                onclick="checkGeneration()">Generate</button>
                        @endcan
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                    <div id="loading_div" class="text-right" style="display:none;margin-top:32px;">
                        <b class="text-success">Generating...</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-GradeSheet text-center">
                <thead>
                    <tr>
                        <th>
                            S.No
                        </th>
                        <th>
                            Exam Month & Year
                        </th>
                        <th>
                            Regulation
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>AY</th>
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

            if ($.fn.DataTable.isDataTable('.datatable-GradeSheet')) {
                $('.datatable-GradeSheet').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.grade-sheet.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'exam_date',
                        name: 'exam_date'
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
                        data: 'academic_year',
                        name: 'academic_year'
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
                            return `<a class="btn btn-sm btn-success" target="_blank" href="{{ url('admin/grade-sheet/pdf') }}/${data.batch}/${data.academic_year}/${data.course}/${data.regulation}/${data.exam_date}"> View PDF</a>`;
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
            let table = $('.datatable-GradeSheet').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function reset() {
            $("#regulation").val($("#target option:first").val());
            $("#batch").val($("#target option:first").val());
            $("#ay").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#exam_month").val($("#target option:first").val());
            $("#exam_year").val($("#target option:first").val());
            $('select').select2();
        }

        function searchGradeSheet() {
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#exam_month").val() == '') {
                Swal.fire('', 'Please Select Exam Month', 'error');
                return false;
            } else if ($("#exam_year").val() == '') {
                Swal.fire('', 'Please Select Exam Year', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else {
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
                dtButtons.splice(0, 8);

                if ($.fn.DataTable.isDataTable('.datatable-GradeSheet')) {
                    $('.datatable-GradeSheet').DataTable().destroy();
                }
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.grade-sheet.search') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'regulation': $("#regulation").val(),
                            'batch': $("#batch").val(),
                            'ay': $("#ay").val(),
                            'course': $("#course").val(),
                            'exam_month': $("#exam_month").val(),
                            'exam_year': $("#exam_year").val()
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'exam_date',
                            name: 'exam_date'
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
                            data: 'academic_year',
                            name: 'academic_year'
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
                                return `<a class="btn btn-sm btn-success" target="_blank" href="{{ url('admin/grade-sheet/pdf') }}/${data.batch}/${data.academic_year}/${data.course}/${data.regulation}/${data.exam_date}"> View PDF</a>`;
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
                let table = $('.datatable-GradeSheet').DataTable(dtOverrideGlobals);
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
            } else if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#exam_month").val() == '') {
                Swal.fire('', 'Please Select Exam Month', 'error');
                return false;
            } else if ($("#exam_year").val() == '') {
                Swal.fire('', 'Please Select Exam Year', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else {
                $("#loading_div").show();
                $("#action_div").hide();
                $.ajax({
                    url: "{{ route('admin.grade-sheet.check-generation') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': $("#regulation").val(),
                        'batch': $("#batch").val(),
                        'ay': $("#ay").val(),
                        'course': $("#course").val(),
                        'exam_month': $("#exam_month").val(),
                        'exam_year': $("#exam_year").val()
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
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#exam_month").val() == '') {
                Swal.fire('', 'Please Select Exam Month', 'error');
                return false;
            } else if ($("#exam_year").val() == '') {
                Swal.fire('', 'Please Select Exam Year', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else {

                Swal.fire({
                    title: "Grade Sheet Already Generated for this Exam !",
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
                            url: "{{ route('admin.grade-sheet.generation') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'regulation': $("#regulation").val(),
                                'batch': $("#batch").val(),
                                'ay': $("#ay").val(),
                                'course': $("#course").val(),
                                'exam_month': $("#exam_month").val(),
                                'exam_year': $("#exam_year").val(),
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
        }

        function generate() {

            $("#loading_div").show();
            $("#action_div").hide();
            $.ajax({
                url: "{{ route('admin.grade-sheet.generation') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'regulation': $("#regulation").val(),
                    'batch': $("#batch").val(),
                    'ay': $("#ay").val(),
                    'course': $("#course").val(),
                    'exam_month': $("#exam_month").val(),
                    'exam_year': $("#exam_year").val(),
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
