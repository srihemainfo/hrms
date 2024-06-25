@extends('layouts.admin')
@section('content')
    {{-- @php
    ini_set('memory_limit', '-1');
@endphp --}}
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <button class="btn btn-warning" onclick="triggerModal()">
                Result Publish Import
            </button>
        </div>
    </div>
    <div class="modal fade" id="csvImportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel">@lang('global.app_csvImport')</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class='row' id="checkDataDiv">
                        <div class="col-md-4 col-12 form-group">
                            <label for="i_batch" class="required">Batch</label>
                            <select class="form-control select2" name="i_batch" id="i_batch">
                                <option value="">Select Batch</option>
                                @foreach ($batches as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <span id="i_batch_span" class="span text-danger text-center"
                                style="display:none;font-size:0.9rem;">Batch Is Required</span>
                        </div>
                        <div class="col-md-4 col-12 form-group">
                            <label for="i_ay" class="required">AY</label>
                            <select class="form-control select2" name="i_ay" id="i_ay">
                                <option value="">Select AY</option>
                                @foreach ($ays as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <span id="i_ay_span" class="span text-danger text-center"
                                style="display:none;font-size:0.9rem;">AY
                                Is Required</span>
                        </div>
                        <div class="col-md-4 col-12 form-group">
                            <label for="i_course" class="required">Course</label>
                            <select class="form-control select2" name="i_course" id="i_course">
                                <option value="">Select Course</option>
                                @foreach ($courses as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <span id="i_course_span" class="span text-danger text-center"
                                style="display:none;font-size:0.9rem;">Course
                                Is Required</span>
                        </div>
                        <div class="col-md-3 col-12 form-group">
                            <label for="i_semester" class="required">Semester</label>
                            <select class="form-control select2" name="i_semester" id="i_semester">
                                <option value="">Select Semester</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                            </select>
                            <span id="i_semester_span" class="span text-danger text-center"
                                style="display:none;font-size:0.9rem;">Semester
                                Is Required</span>
                        </div>
                        <div class="col-md-3 col-12 form-group">
                            <label for="i_exam_month" class="required">Exam Month</label>
                            <select class="form-control select2" name="i_exam_month" id="i_exam_month">
                                <option value="">Select Exam Month</option>
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
                            <span id="i_exam_month_span" class="span text-danger text-center"
                                style="display:none;font-size:0.9rem;">Exam Month Is Required</span>
                        </div>
                        <div class="col-md-3 col-12 form-group">
                            <label for="i_exam_year" class="required">Exam Year</label>
                            <select class="form-control select2" name="i_exam_year" id="i_exam_year">
                                <option value="">Select Exam Year</option>
                                @foreach ($years as $id => $name)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <span id="i_exam_year_span" class="span text-danger text-center"
                                style="display:none;font-size:0.9rem;">Exam Year Is Required</span>
                        </div>
                        <div class="col-md-3 col-12 form-group text-center">
                            <button class="enroll_generate_bn" style="margin-top:32px;"
                                onclick="checkImpRequire(this)">Check</button>
                            <div class="text-success" style="display:none;margin-top:32px;" id="checkBtn">Checking...
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-12' id="importDiv" style="display:none;">

                            <form class="form-horizontal" method="POST"
                                action="{{ route('admin.exam-result-publish.parseCsvImport', ['model' => 'ExamResultPublish']) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                                    <label for="csv_file" class="col-md-4 control-label">@lang('global.app_csv_file_to_import')</label>

                                    <div class="col-md-6">
                                        <input id="csv_file" type="file" class="form-control-file" name="csv_file"
                                            required>

                                        @if ($errors->has('csv_file'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('csv_file') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="header" checked> @lang('global.app_file_contains_header_row')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            @lang('global.app_parse_csv')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .borderNone {
            border: none;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header text-uppercase text-center">
            Search Result Entry
        </div>
        <div class="card-body">
            <div class="row">

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required" for="regulation">Regulation</label>
                    <select class="form-control select2" name="regulation" id="regulation" required>
                        <option value="">Select Regulation</option>
                        @foreach ($regulations as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="academic_year" class="required">Academic Year</label>
                    <select class="form-control select2" name="academic_year" id="academic_year">
                        <option value="">Select AY</option>
                        @foreach ($ays as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required" for="exam_month">Exam Month </label>
                    <select class="form-control select2" name="exam_month" id="exam_month" required>
                        <option value="">Select Exam Month</option>
                        @foreach ($exam_month as $data)
                            <option value="{{ $data->exam_month }}">
                                {{ $data->exam_month }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="exam_year" class="required">Exam Year</label>
                    <select class="form-control select2 " name="exam_year" id="exam_year">
                        <option value="">Select Exam Year</option>
                        @foreach ($exam_year as $data)
                            <option value="{{ $data->exam_year }}">
                                {{ $data->exam_year }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="course" class="required">Course</label>
                    <select class="form-control select2" name="course" id="course" required>
                        <option value="">Select Course</option>
                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="semester" class="required">Semester</label>
                    <select class="form-control select2" name="semester" id="semester" required>
                        <option value="">Select Semester</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required" for="result_type">Result Type</label>
                    <select class="form-control select2" name="result_type" id="result_type" required>
                        <option value="">Select Result Type</option>
                        @foreach ($result_type as $id => $entry)
                            <option value="{{ $entry }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="publish_date" class="required">Published Date</label>
                    <div class="form-group">
                        <select class="form-control select2" name="publish_date" id="publish_date" required>
                            <option value="">Select Publish Date</option>
                            @foreach ($publish_date as $data)
                                <option value="{{ $data->publish_date }}">{{ $data->publish_date }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}

                <div class="col-xl-3 col-lg-3 col-md-8 col-sm-6 col-12">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="search()">Search</button>
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="position: relative;">
            <table class=" table table-bordered table-striped table-hover datatable datatable-publish text-center"
                id="datatablePublish">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            Result Name
                        </th>
                        <th>
                            Regulation
                        </th>
                        <th>
                            Batch
                        </th>

                        <th>
                            Academic Year
                        </th>
                        <th>
                            Published Date
                        </th>
                        <th>
                            Uploaded Date
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
    </div>
@endsection
@section('scripts')
    @parent

    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            let dtOverrideGlobals = {
                buttons: dtButtons,
                deferRender: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.result-publish.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'regulation',
                        name: 'regulations'
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
                        data: 'publish_date',
                        name: 'publish_date'
                    },
                    {
                        data: 'uploaded_date',
                        name: 'uploaded_date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data) {
                            let publishBtn =
                                `<div><button class="btn btn-xs btn-outline-success" style="cursor:default;">Published</button></div>`;
                            if (data.publish == true) {
                                publishBtn =
                                    `@can('download_exam_reg_access')<div><button class="btn btn-xs btn-warning" id="publishBtn" onclick="publish('${data.urlData}')" }}">Publish</button><span class="text-success" style="display:none;" id="processingBtn">Processing...</span></div>@endcan`;
                            }
                            return `@can('download_exam_reg_access')<div><a class="btn btn-xs btn-success" target="_blank" href="{{ url('admin/result-publish/download-excel/${data.urlData}') }}">Download Excel</a></div>@endcan
                                    @can('download_exam_reg_access')<div><a class="btn btn-xs btn-primary" target="_blank" href="{{ url('admin/result-publish/download-pdf/${data.urlData}') }}">Download Pdf</a></div>@endcan
                                    @can('download_exam_reg_access')<div><a class="btn btn-xs btn-danger" target="_blank" href="{{ url('admin/result-publish/delete/${data.urlData}') }}">Delete Result</a></div>@endcan${publishBtn}`;
                        },
                        type: 'html',
                        className: 'text-center'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-publish').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function reset() {
            $("#regulation").val($("#target option:first").val());
            $("#academic_year").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#semester").val($("#target option:first").val());
            $("#result_type").val($("#target option:first").val());
            $("#exam_month").val($("#target option:first").val());
            $("#exam_year").val($("#target option:first").val());
            // $("#publish_date").val($("#target option:first").val());
            $('select').select2();
        }

        function search() {
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else if ($("#academic_year").val() == '') {
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
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else if ($("#result_type").val() == '') {
                Swal.fire('', 'Please Select Result Type', 'error');
                return false;
            } else {
                let regulation = $("#regulation").val();
                let ay = $("#academic_year").val();
                let exam_month = $("#exam_month").val();
                let exam_year = $("#exam_year").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let result_type = $("#result_type").val();
                // let publish_date = $("#publish_date").val();

                if ($.fn.DataTable.isDataTable('#datatablePublish')) {
                    $('#datatablePublish').DataTable().clear().destroy();
                }

                $.ajax({
                    url: "{{ route('admin.result-publish.search') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': regulation,
                        'ay': ay,
                        'exam_month': exam_month,
                        'exam_year': exam_year,
                        'course': course,
                        'semester': semester,
                        'result_type': result_type,
                        // 'publish_date': publish_date,
                    },
                    success: function(response) {
                        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            deferRender: true,
                            retrieve: true,
                            data: response.data,
                            aaSorting: [],
                            columns: [{
                                    data: 'placeholder',
                                    name: 'placeholder'
                                },
                                {
                                    data: 'name',
                                    name: 'name'
                                },
                                {
                                    data: 'regulation',
                                    name: 'regulations'
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
                                    data: 'publish_date',
                                    name: 'publish_date'
                                },
                                {
                                    data: 'uploaded_date',
                                    name: 'uploaded_date'
                                },
                                {
                                    data: 'status',
                                    name: 'status'
                                },
                                {
                                    data: 'actions',
                                    name: 'actions',
                                    render: function(data) {
                                        let publishBtn =
                                            `<div><button class="btn btn-xs btn-outline-success" style="cursor:default;">Published</button></div>`;
                                        if (data.publish == true) {
                                            publishBtn =
                                                `@can('download_exam_reg_access')<div><button class="btn btn-xs btn-warning" id="publishBtn"  onclick="publish('${data.urlData}')" }}">Publish</button><span class="text-success" style="display:none;" id="processingBtn">Processing...</span></div>@endcan`;
                                        }
                                        return `@can('download_exam_reg_access')<div><a class="btn btn-xs btn-success" target="_blank" href="{{ url('admin/result-publish/download-excel/${data.urlData}') }}">Download Excel</a></div>@endcan
                                    @can('download_exam_reg_access')<div><a class="btn btn-xs btn-primary" target="_blank" href="{{ url('admin/result-publish/download-pdf/${data.urlData}') }}">Download Pdf</a></div>@endcan
                                    @can('download_exam_reg_access')<div><a class="btn btn-xs btn-danger" target="_blank" href="{{ url('admin/result-publish/delete/${data.urlData}') }}">Delete Result</a></div>@endcan${publishBtn}`;
                                    },
                                    type: 'html',
                                    className: 'text-center'
                                }
                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };

                        // let table = $('.datatable-publish').DataTable(dtOverrideGlobals);
                        // table.destroy();
                        let table = $('#datatablePublish').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
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

        function publish(data) {

            let datas = data.split('/');
            let ay = datas[0];
            let batch = datas[1];
            let course = datas[2];
            let regulation = datas[3];
            let sem = datas[4];
            let result_type = datas[5];
            // let publish = datas[6];
            let exam_month = datas[7];
            let exam_year = datas[8];
            if (ay != '' && batch != '' && course != '' && regulation != '' && sem != '' && result_type != '' &&
                exam_month != '' && exam_year != '') {
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You Want To Publish The Result ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $("#processingBtn").show();
                        $("#publishBtn").hide();
                        $.ajax({
                            url: '{{ route('admin.result-publish.publish-action') }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'ay': ay,
                                'batch': batch,
                                'course': course,
                                'regulation': regulation,
                                'sem': sem,
                                'result_type': result_type,
                                'exam_month': exam_month,
                                'exam_year': exam_year
                            },
                            success: function(response) {
                                let status = response.status;
                                $("#processingBtn").hide();
                                if (status == true) {
                                    $("#fee_structure").show();
                                    Swal.fire('', 'Result Published Successfully!', 'success');
                                    location.reload();
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
                                    Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                        "error");
                                }
                                $("#processingBtn").hide();
                            }
                        })
                    } else if (result.dismiss == "cancel") {
                        Swal.fire(
                            "Cancelled",
                            "Result Publish Cancelled",
                            "error"
                        )
                    }
                });
            }else{
                Swal.fire(
                            "",
                            "Required Details Not Found",
                            "error"
                        )
            }

        }

        function triggerModal() {
            $("#checkDataDiv").show();
            $("#importDiv").hide();
            $("#i_batch").val($("#target option:first").val())
            $("#i_ay").val($("#target option:first").val())
            $("#i_course").val($("#target option:first").val())
            $("#i_semester").val($("#target option:first").val())
            $("#i_exam_month").val($("#target option:first").val())
            $("#i_exam_year").val($("#target option:first").val())
            $("#i_batch").select2();
            $("#i_ay").select2();
            $("#i_course").select2();
            $("#i_semester").select2();
            $("#i_exam_month").select2();
            $("#i_exam_year").select2();
            $("#csvImportModal").modal()
        }

        function checkImpRequire(element) {
            if ($("#i_batch").val() == '') {
                $(".span").hide();
                $("#i_batch_span").show();
                return false;
            } else if ($("#i_ay").val() == '') {
                $(".span").hide();
                $("#i_ay_span").show();
            } else if ($("#i_course").val() == '') {
                $(".span").hide();
                $("#i_course_span").show();
            } else if ($("#i_semester").val() == '') {
                $(".span").hide();
                $("#i_semester_span").show();
            } else if ($("#i_exam_month").val() == '') {
                $(".span").hide();
                $("#i_exam_month_span").show();
            } else if ($("#i_exam_year").val() == '') {
                $(".span").hide();
                $("#i_exam_year_span").show();
                return false;
            } else {
                $(element).hide()
                $('#checkBtn').show()
                $.ajax({
                    url: '{{ route('admin.result-publish.check-data') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'ay': $("#i_ay").val(),
                        'batch': $("#i_batch").val(),
                        'course': $("#i_course").val(),
                        'sem': $("#i_semester").val(),
                        'exam_month': $("#i_exam_month").val(),
                        'exam_year': $("#i_exam_year").val()
                    },
                    success: function(response) {
                        let status = response.status;
                        $("#checkBtn").hide();
                        $(element).show()
                        if (status == true) {
                            $("#importDiv").show();
                            $("#checkDataDiv").hide();
                        } else {
                            $("#checkDataDiv").show();
                            $("#importDiv").hide();
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
                        $("#checkBtn").hide();
                        $(element).show()
                    }
                })
            }
        }
    </script>
@endsection
