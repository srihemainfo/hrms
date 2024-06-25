@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .amtInp {
            border: 1px solid #d7d7d7;
            outline: none;
            padding-left: 15px;
            width: 50%;
            margin: auto;
        }
    </style>
    @can('fee_structure_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-md-6 col-12">
                <button class="btn btn-success" onclick="openModal()">
                    Add Fees Structure
                </button>
                <button class="btn btn-warning" onclick="feeGenerateModal('Generate')">
                    Generate Fee
                </button>
                <button class="btn btn-info" onclick="feeGenerateModal('Publish')">
                    Publish Fee
                </button>
            </div>
            <div class="col-md-6 col-12 text-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#feeStructureImp">
                    Import Students Fee
                </button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#paidImp">
                    Import Students Paid Fee
                </button>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Fess Sturucture List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FeeStructure text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            AY
                        </th>
                        <th>
                            Admission Mode
                        </th>
                        <th>
                            Created By
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

    <div class="modal fade" id="feeStructureModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Course</label>
                            <input type="hidden" id="feeStructure_id" value="">
                            <select class="form-control select2" style="text-transform:uppercase" id="course"
                                name="course" value="">
                                <option value="">Select Course</option>
                                @foreach ($course as $id => $d)
                                    <option value="{{ $id }}">{{ $d }}</option>
                                @endforeach
                            </select>
                            <span id="course_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Admission Mode</label>
                            <select class="form-control select2" style="text-transform:uppercase" id="admission"
                                name="admission" value="">
                                <option value="">Select Admission Mode</option>
                                @foreach ($admission as $id => $d)
                                    <option value="{{ $id }}">{{ $d }}</option>
                                @endforeach
                            </select>
                            <span id="admission_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Applicable Batch</label>
                            <select class="form-control select2" id="applied_batch" name="applied_batch">
                                <option value="">Select Batch</option>
                                @foreach ($batch as $id => $b)
                                    <option value="{{ $id }}">{{ $b }}</option>
                                @endforeach
                            </select>
                            <span id="applied_batch_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group" id="applied_ay_div"
                            style="display:none;">
                            <label for="applied_ay" class="required">Applicable AY</label>
                            <select class="form-control select2" id="applied_ay" name="applied_ay">
                                <option value="">Select AY</option>
                                @foreach ($ay as $id => $b)
                                    <option value="{{ $id }}">{{ $b }}</option>
                                @endforeach
                            </select>
                            <span id="applied_ay_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Fees Component</th>
                                        <th>Fees Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tuition Fee</td>
                                        <td><input type="number" id="tuition_fee" class="amtInp" value=""></td>
                                    </tr>
                                    <tr>
                                        <td>Hostel Fee</td>
                                        <td><input type="number" id="hostel_fee" class="amtInp" value=""></td>
                                    </tr>
                                    <tr>
                                        <td>Other Fee</td>
                                        <td><input type="number" id="other_fee" class="amtInp" value=""></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-success"
                            onclick="saveFeeStructure()">Save</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader">Processing...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="feeGenerateModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="feeBatch" class="required">Batch</label>
                            <select class="form-control select2" id="feeBatch" name="feeBatch">
                                <option value="">Select Batch</option>
                                @foreach ($batch as $id => $b)
                                    <option value="{{ $id }}">{{ $b }}</option>
                                @endforeach
                            </select>
                            <span id="feeBatch_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="feeAy" class="required">AY</label>
                            <select class="form-control select2" id="feeAy" name="feeAy">
                                <option value="">Select AY</option>
                                @foreach ($ay as $id => $a)
                                    <option value="{{ $id }}">{{ $a }}</option>
                                @endforeach
                            </select>
                            <span id="feeAy_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group text-right">
                            <div id="feeGenerate_div">
                                <button type="button" id="action_btn" class="btn btn-success"
                                    onclick="generateFee()">Generate Fee</button>
                            </div>
                            <div id="feeLoading_div" style="display:none;">
                                <span class="theLoader">Processing...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="feeStructureImp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel">Import Fee Structure</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-12'>

                            <form class="form-horizontal" method="POST"
                                action="{{ route('admin.academic-fee.parseCsvImport', ['model' => 'AcademicFee']) }}"
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
    <div class="modal fade" id="paidImp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel">Import Paid Fee</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-12'>

                            <form class="form-horizontal" method="POST"
                                action="{{ route('admin.fee-data-import.paid', ['model' => 'AcademicFee']) }}"
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
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('nationality_delete')
                let deleteButton = {
                    text: 'Delete Selected',
                    className: 'btn-danger',
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


                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: "{{ route('admin.fee-structure.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        Swal.fire('', response.data, response.status);


                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-FeeStructure')) {
                $('.datatable-FeeStructure').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.fee-structure.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
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
                        data: 'ay',
                        name: 'ay'
                    },
                    {
                        data: 'admission',
                        name: 'admission'
                    },
                    {
                        data: 'user',
                        name: 'user'
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
            let table = $('.datatable-FeeStructure').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#feeStructure_id").val('');
            $("#tuition_fee").val('');
            $("#hostel_fee").val('');
            $("#other_fee").val('');
            $("#course").val('').select2()
            $("#admission").val('').select2()
            $("#applied_batch").val('').select2()
            $("#applied_ay_div").hide();
            $("select,input").prop('disabled', false)
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#feeStructureModel").modal();
        }

        function saveFeeStructure() {
            $("#course_span").hide();
            $("#admission_span").hide();
            $("#course_span").hide();
            if ($("#course").val() == '') {
                $("#course_span").html('Course Is Required').show();
            } else if ($("#admission").val() == '') {
                $("#admission_span").html('Admission Mode Is Required').show();
            } else if ($("#applied_batch").val() == '') {
                $("#applied_batch_span").html('Applicable Batch Is Required').show();
            } else if ($("#tuition_fee").val() == '' || $("#hostel_fee").val() == '' || $("#other_fee").val() == '') {
                Swal.fire('', 'Please Provide The Fee Details', 'warning');
            } else {
                $("#save_div").hide();
                $("#loading_div").show();
                $.ajax({
                    url: '{{ route('admin.fee-structure.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $("#feeStructure_id").val(),
                        'course': $("#course").val(),
                        'admission': $("#admission").val(),
                        'batch': $("#applied_batch").val(),
                        'tuition_fee': $("#tuition_fee").val(),
                        'hostel_fee': $("#hostel_fee").val(),
                        'other_fee': $("#other_fee").val(),
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#feeStructureModel").modal('hide');
                        callAjax();
                    }
                })
            }
        }

        function viewfeeStructure(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                $.ajax({
                    url: "{{ route('admin.fee-structure.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {

                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            $("#feeStructure_id").val(data.fees_id);
                            $("#course").val(data.course)
                            $("#admission").val(data.admission)
                            $("#applied_ay_div").show();
                            $("#applied_batch").val(data.batch)
                            $("#applied_ay").val(data.ay)
                            $("#tuition_fee").val(data.tuition_fee)
                            $("#hostel_fee").val(data.hostel_fee)
                            $("#other_fee").val(data.other_fee)
                            $("#save_div").hide();
                            $("select").prop('disabled', true).select2()
                            $("#loading_div").hide();
                            $("#feeStructureModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }

        function editfeeStructure(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                $.ajax({
                    url: "{{ route('admin.fee-structure.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {

                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            $("#feeStructure_id").val(data.fees_id);
                            $("#course").val(data.course)
                            $("#admission").val(data.admission)
                            $("#applied_ay_div").show();
                            $("#applied_batch").val(data.batch)
                            $("#applied_ay").val(data.ay)
                            $("#tuition_fee").val(data.tuition_fee)
                            $("#hostel_fee").val(data.hostel_fee)
                            $("#other_fee").val(data.other_fee)
                            $("select").prop('disabled', true).select2()
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#loading_div").hide();
                            $("#feeStructureModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }

        function deletefeeStructure(id) {
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

                        $.ajax({
                            url: "{{ route('admin.fee-structure.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                Swal.fire('', response.data, response.status);
                                callAjax();
                            }
                        })
                    }
                })
            }
        }

        function feeGenerateModal(action) {
            $("select").prop('disabled', false).select2()
            if (action == 'Generate') {
                $("#action_btn").attr('onclick', 'generateFee()').html('Generate Fee');
            } else {
                $("#action_btn").attr('onclick', 'publishFee()').html('Publish Fee');
            }
            $("#feeGenerateModal").modal();
        }

        function generateFee() {
            if ($("#feeBatch").val() == '') {
                $("#feeBatch_span").html(`Batch Is Required`).show();
                $("#feeAy_span").hide();
            } else if ($("#feeAy").val() == '') {
                $("#feeAy_span").html(`AY Is Required`).show();
                $("#feeBatch_span").hide();
            } else {
                $("#feeAy_span").hide();
                $("#feeBatch_span").hide();
                Swal.fire({
                    title: "CONFIRM",
                    text: "This action will generate the Fees Dues to all selected batch students for selected academic year",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $("#feeGenerate_div").hide()
                        $("#feeLoading_div").show()
                        return $.ajax({
                            url: '{{ route('admin.fee-structure.generate-fee') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'batch': $("#feeBatch").val(),
                                'ay': $("#feeAy").val(),
                            },
                            success: function(response) {

                                if (response.status) {
                                    Swal.fire('', response.data, "success");
                                    callAjax();
                                } else {
                                    Swal.fire('', response.data, "error");
                                }
                                $("#feeGenerateModal").modal('hide')
                                $("#feeGenerate_div").show()
                                $("#feeLoading_div").hide()

                            },
                            error: function(jqXHR, textStatus, errorThrown) {

                                if (jqXHR.status == 422) {
                                    var errors = jqXHR.responseJSON.errors;
                                    var errorMessage = errors[Object.keys(errors)[0]][0];
                                    Swal.fire('', errorMessage, "error");
                                } else {
                                    Swal.fire('', 'Request failed with status: ' + jqXHR.status,
                                        "error");
                                }
                                $("#feeGenerate_div").show()
                                $("#feeLoading_div").hide()
                            }
                        });
                    }
                })
            }
        }

        function publishFee() {
            if ($("#feeBatch").val() == '') {
                $("#feeBatch_span").html(`Batch Is Required`).show();
                $("#feeAy_span").hide();
            } else if ($("#feeAy").val() == '') {
                $("#feeAy_span").html(`AY Is Required`).show();
                $("#feeBatch_span").hide();
            } else {
                $("#feeAy_span").hide();
                $("#feeBatch_span").hide();
                Swal.fire({
                    title: "CONFIRM",
                    text: "This action will publish the Fees Dues to all selected batch students for selected academic year",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $("#feeGenerate_div").hide()
                        $("#feeLoading_div").show()
                        return $.ajax({
                            url: '{{ route('admin.fee-structure.publish-fee') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'batch': $("#feeBatch").val(),
                                'ay': $("#feeAy").val(),
                            },
                            success: function(response) {

                                if (response.status) {
                                    Swal.fire('', response.data, "success");
                                    callAjax();
                                } else {
                                    Swal.fire('', response.data, "error");
                                }
                                $("#feeGenerateModal").modal('hide')
                                $("#feeGenerate_div").show()
                                $("#feeLoading_div").hide()

                            },
                            error: function(jqXHR, textStatus, errorThrown) {

                                if (jqXHR.status == 422) {
                                    var errors = jqXHR.responseJSON.errors;
                                    var errorMessage = errors[Object.keys(errors)[0]][0];
                                    Swal.fire('', errorMessage, "error");
                                } else {
                                    Swal.fire('', 'Request failed with status: ' + jqXHR.status,
                                        "error");
                                }
                                $("#feeGenerate_div").show()
                                $("#feeLoading_div").hide()
                            }
                        });
                    }
                })
            }
        }
    </script>
@endsection
