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
                    Add Fee Structure
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
                            Shift
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Semester
                        </th>
                        {{-- <th>
                            Admission Mode
                        </th> --}}
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
                        @can('shift_alter_access')
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">Shift</label>
                                <input type="hidden" id="feeStructure_id" value="">
                                <select class="form-control select2" style="text-transform:uppercase" id="shift"
                                    name="shift" value="" onchange="printSelectedShift()">
                                    <option value="">Select Shift</option>
                                    @foreach ($shift as $id => $sht)
                                        <option value="{{ $id }}">{{ $sht }}</option>
                                    @endforeach
                                </select>
                                <span id="shift_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                        @endcan
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
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Applicable Semester</label>
                            <select class="form-control select2" id="semester" name="semester">
                                <option value="">Select Semester</option>
                                @foreach ($semester as $id => $sem)
                                    <option value="{{ $id }}">{{ $sem }}</option>
                                @endforeach
                            </select>
                            <span id="semester_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        {{-- <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Fee Components</label>
                            <select class="form-control select2" id="fee_components" name="fee_components">
                                <option value="">Select Fee Components</option>
                                @foreach ($fee_compnents as $id => $fee_com)
                                    <option value="{{ $id }}">{{ $fee_com }}</option>
                                @endforeach
                            </select>
                            <span id="fee_components_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div> --}}
                        {{-- <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
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
                        </div> --}}
                        {{-- <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group" id="applied_ay_div"
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
                        </div> --}}
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <table class="table table-bordered table-striped table-hover text-center"
                                id="fee_componentstable">
                                <thead>
                                    <tr>
                                        <th>Fee Component</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="feeComponentsTable">

                                </tbody>
                                <tfoot id="table_footer">
                                    <tr>
                                        <td id="total_text">Total</td>
                                        <td id="totalAmount" style="font-weight: bold;"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="secondLoader"></div>
                    </div>
                    <div class="row" id="addfee_Amount">
                        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 form-group tbl-fees tbl-view">
                            <label for="result" class="required">Fee Component</label>
                            <select class="form-control select2" id="fee_components" name="fee_components">
                                <option value="">Select Fee Components</option>
                                @foreach ($fee_compnents as $id => $fee_com)
                                    <option value="{{ $id }}">{{ $fee_com }}</option>
                                @endforeach
                            </select>
                            <span id="fee_components_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 form-group tbl-fees tbl-view">
                            <label for="regulation" class="required">Amount</label>
                            <input type="text" class="form-control" name="amount" id="amount" value="">
                            <span id="amount_span" class="text-danger text-center"
                                style="display:none; font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 form-group tbl-fees text-center tbl-view">
                            <button onclick="addFees()" id="addFees" class="newViewBtn"
                                style="font-size: 1.7rem; padding-top: 30px !important;" title="Add Fee"><i
                                    class="far fa-plus-square"></i></button>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-success"
                            onclick="saveFeeStructure()">Save</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader"></span>
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
                        data: 'shift',
                        name: 'shift'
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
                        data: 'semester',
                        name: 'semester'
                    },
                    // {
                    //     data: 'admission',
                    //     name: 'admission'
                    // },
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

        let courseVal = '';
        let batchVal = '';
        let semesterVal = '';
        let tbody = $('#fee_componentstable tbody');

        function openModal() {
            $("#feeStructure_id").val('');
            $('#totalAmount').hide();
            $('#total_text').hide();
            $("#course").val('').select2()
            $("#fee_components").val('').select2()
            $("#shift").val('').select2()
            $("#semester").val('').select2()
            $("#admission").val('').select2()
            $("#applied_batch").val('').select2()
            $("#applied_ay_div").hide();
            $("select,input").prop('disabled', false)
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#feeStructureModel").modal();
            let tableFooter = $('#table_footer');
            let addfee_Amount = $('#addfee_Amount');
            addfee_Amount.show();
            tableFooter.show();

            tbody.empty();
            courseVal = '';
            batchVal = '';
            semesterVal = '';


        }

        function addFees() {

            $('#totalAmount').show();
            $('#total_text').show();
            var feeComponentId = $('#fee_components').val();
            var feeComponentText = $('#fee_components option:selected').text();
            var amount = $('#amount').val();

            if (feeComponentId === "" || amount === "") {
                if (feeComponentId === "") {
                    $('#fee_components_span').text('Please select a fee component').show();
                } else {
                    $('#fee_components_span').hide();
                }
                if (amount === "") {
                    $('#amount_span').text('Please enter an amount').show();
                } else {
                    $('#amount_span').hide();
                }
                return;
            }

            $('#fee_components_span').hide();
            $('#amount_span').hide();

            var newRow = `
        <tr data-component-id="${feeComponentId}">
            <td>${feeComponentText}</td>
            <td>${amount}</td>
        </tr>
    `;

            $('#feeComponentsTable').append(newRow);
            updateTotal();

            // Clear the inputs after adding
            $('#fee_components').val('');
            $('#amount').val('');
        }
        var componentsJson = '';

        function updateTotal() {
            var total = 0;
            let tbody = $('#feeComponentsTable');
            let components = [];

            tbody.find('tr').each(function(index, row) {
                let componentName = $(row).find('td:eq(0)').text();
                let componentAmount = parseFloat($(row).find('td:eq(1)').text());
                let componentId = $(row).data('component-id');

                total += componentAmount;

                let component = {
                    id: componentId,
                    name: componentName,
                    amount: componentAmount
                };

                components.push(component);
            });

            components.push({
                name: "Total",
                amount: total
            });

            $('#totalAmount').text(total);

            componentsJson = JSON.stringify(components);
            console.log(componentsJson);
        }


        // $("#course").on('change', function() {
        //     courseVal = $("#course option:selected").val();
        //     checkValues();
        // });

        // $("#applied_batch").on('change', function() {
        //     batchVal = $("#applied_batch option:selected").val();
        //     checkValues();
        // });

        // $("#semester").on('change', function() {
        //     semesterVal = $("#semester option:selected").val();
        //     checkValues();
        // });

        // function checkValues() {
        //     if (courseVal != '' && batchVal != '' && semesterVal != '') {
        //         $('.secondLoader').show()
        //         $.ajax({
        //             url: "{{ route('admin.fee-structure.getfeecomponents') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'courseVal': courseVal,
        //                 'batchVal': batchVal,
        //                 'semesterVal': semesterVal
        //             },
        //             success: function(response) {
        //                 let status = response.status;
        //                 if (status == true) {
        //                     let feeComponents_name = response.data;
        //                     tbody.empty();
        //                     $.each(feeComponents_name, function(index, component) {
        //                         if (component == '') {
        //                             Swal.fire('', 'Fees Components was not Created..', 'error');
        //                             $('.secondLoader').hide();
        //                         } else {
        //                             // console.log(index);
        //                             tbody.append(
        //                                 `<tr data-component-id="${index}"><td>${component}</td><td><input type="number" class="form-control" name="fee_component_${index}" /></td></tr>`
        //                             );
        //                             $('.secondLoader').hide();
        //                         }
        //                     });
        //                     tbody.append(
        //                         `<tr><td>Total</td><td><input type="number" class="form-control" name="total" id="total" readonly /></td></tr>`
        //                     );
        //                     calculateTotal();
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                     $('.secondLoader').hide()
        //                     tbody.empty();
        //                 }
        //             },

        //             error: function(jqXHR, textStatus, errorThrown) {

        //                 if (jqXHR.status == 422) {
        //                     var errors = jqXHR.responseJSON.errors;
        //                     var errorMessage = errors[Object.keys(errors)[0]][0];
        //                     Swal.fire('', errorMessage, "error");
        //                 } else {
        //                     Swal.fire('', 'Request Failed: ' + jqXHR.status,
        //                         "error");
        //                 }
        //             }
        //         })

        //     }
        // }

        // var total = 0;

        // function calculateTotal() {
        //     $('input[name^="fee_component_"]').on('input', function() {
        //         total = 0; // Reset total before recalculating
        //         $('input[name^="fee_component_"]').each(function() {
        //             let value = parseFloat($(this).val());
        //             if (!isNaN(value)) {
        //                 total += value;
        //             }
        //         });
        //         $('#total').val(total);
        //         // console.log("Total Amount: " + total);
        //     });
        // }

        function printSelectedShift() {
            var shiftSelect = $("#shift option:selected").val();
            $('.secondLoader').show()
            $.ajax({

                url: "{{ route('admin.fee-structure.get-course') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'shiftSelect': shiftSelect
                },
                success: function(response) {
                    let status = response.status;
                    if (status == true) {
                        if (response.data.length === 0) {
                            var selectElement = $("#course");
                            selectElement.find('option:not(:first)').remove();
                            $('.secondLoader').hide()
                            Swal.fire('', 'Courses Not Found..!', 'error');
                        } else {
                            $('.secondLoader').hide();
                            console.log(response);
                            var selectElement = $("#course");
                            selectElement.find('option:not(:first)').remove();
                            $.each(response.data, function(id, courseName) {
                                selectElement.append(new Option(courseName, id));
                            });
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status == 422) {
                        var errors = jqXHR.responseJSON.errors;
                        var errorMessage = errors[Object.keys(errors)[0]][0];
                        $('.secondLoader').hide()
                        Swal.fire('', errorMessage, "error");
                    } else {
                        $('.secondLoader').hide()
                        Swal.fire('', 'Request Failed: ' + jqXHR.status, "error");
                    }
                }


            })

        }

        function saveFeeStructure() {

            // let tbody = $('#feeComponentsTable');
            // let components = [];

            // tbody.find('tr').each(function(index, row) {
            //     let componentName = $(row).find('td:first').text();
            //     let componentAmount = $(row).find('input').val();
            //     // console.log(componentAmount);
            //     let componentId = $(row).data('component-id');

            //     let component = {
            //         id: componentId,
            //         name: componentName,
            //         amount: componentAmount
            //     };

            //     components.push(component);
            // });

            // let componentsJson = JSON.stringify(components);
            // console.log(componentsJson);

            $("#course_span").hide();
            // $("#admission_span").hide();
            $("#course_span").hide();
            $("#shift_span").hide();
            $("#semester_span").hide();

            if ($("#course").val() == '') {
                $("#course_span").html('Course Is Required').show();
            }
            // else if ($("#admission").val() == '') {
            //     $("#admission_span").html('Admission Mode Is Required').show();
            // } 
            else if ($("#shift").val() == '') {
                $("#shift_span").html('Shift Is Required').show();
            } else if ($("#semester").val() == '') {
                $("#semester_span").html('Semester Is Required').show();
            } else if ($("#applied_batch").val() == '') {
                $("#applied_batch_span").html('Applicable Batch Is Required').show();
            }
            // else if ($("#tuition_fee").val() == '' || $("#hostel_fee").val() == '' || $("#other_fee").val() == '' || $(
            //         "#admission_fee").val() == '' || $("#special_fee").val() == '') {
            //     Swal.fire('', 'Please Provide The Fee Details', 'warning');
            // } 
            else {
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
                        // 'admission': $("#admission").val(),
                        'batch': $("#applied_batch").val(),
                        // 'tuition_fee': $("#tuition_fee").val(),
                        // 'special_fee': $("#special_fee").val(),
                        // 'admission_fee': $("#admission_fee").val(),
                        // 'hostel_fee': $("#hostel_fee").val(),
                        // 'other_fee': $("#other_fee").val(),
                        'shift': $("#shift").val(),
                        'semester': $("#semester").val(),
                        'componentsJson': componentsJson
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                            $("#feeComponentsTable").empty();
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
            $("#loading_div").show();


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
                        $("#loading_div").hide();

                        let status = response.status;
                        if (status == true) {
                            var data = response.data;

                            let feeComponentsAppend = data.fee_component;
                            let tableBody = $('#feeComponentsTable');
                            let tableFooter = $('#table_footer');
                            let addfee_Amount = $('#addfee_Amount');
                            addfee_Amount.hide();
                            tableFooter.hide();
                            tableBody.empty();

                            $.each(feeComponentsAppend, function(index, item) {
                                let row = `<tr>
                            <td>${item.name}</td>
                            <td>${item.amount}</td>
                            </tr>`;
                                tableBody.append(row);
                            });

                            $("#feeStructure_id").val(data.fees_id);
                            $("#shift").val(data.shi);
                            $("#semester").val(data.sem);
                            $("#course").val(data.course)
                            $("#admission").val(data.admission)
                            $("#applied_ay_div").show();
                            $("#applied_batch").val(data.batch);
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
        let jsonData = [];

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
                            let tableBody = $('#feeComponentsTable');
                            let tableFooter = $('#table_footer');
                            let addfee_Amount = $('#addfee_Amount');
                            addfee_Amount.hide();
                            tableFooter.hide();
                            tableBody.empty();

                            let feeComponentsAppend = JSON.parse(data.fee_component);

                            $.each(feeComponentsAppend, function(index, item) {
                                let row = `<tr>
                            <td>${item.name}</td>
                            <td>${item.amount}</td>
                            </tr>`;
                                tableBody.append(row);
                            });


                            // $.each(feeComponentsAppend, function(index, item) {
                            //     let row = `<tr>
                        // <td style="display:none;"><input type="text" name="id[]" value="${item.id}"></td>      
                        // <td><input type="text" class="form-control " name="name[]" value="${item.name}" readonly  /></td>
                        // <td><input type="text" class="form-control"  name="amount[]" value="${item.amount}" /></td>
                        // </tr>`;
                            //     tableBody.append(row);

                            //     let rowData = {
                            //         id: item.id,
                            //         name: item.name,
                            //         amount: item.amount
                            //     };
                            //     jsonData.push(rowData);

                            // });

                            // let componentsJson = JSON.stringify(jsonData);
                            // console.log(componentsJson)



                            $("#feeStructure_id").val(data.fees_id);

                            $("#shift").val(data.shi);
                            $("#semester").val(data.sem);
                            $("#course").val(data.course)
                            $("#admission").val(data.admission)
                            $("#applied_ay_div").show();
                            $("#applied_batch").val(data.batch)
                            $("#applied_ay").val(data.ay)

                            // $("#tuition_fee").val(data.tuition_fee)
                            // $("#special_fee").val(data.special_fee)
                            // $("#admission_fee").val(data.admission_fee)
                            // $("#hostel_fee").val(data.hostel_fee)
                            // $("#other_fee").val(data.other_fee)
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
