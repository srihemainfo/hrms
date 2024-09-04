@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        #loading {
            z-index: 99999;
        }
    </style>
    @can('fee_structure_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-md-6 col-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Assign Hostel Fee
                </button>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Assign HostelFee
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-HostelFee text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Register Number
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Academic Year
                        </th>
                        <th>
                            Hostel Block
                        </th>
                        <th>
                            Amount
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
    <div class="modal fade" id="hostel_fee" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <input type="hidden" name="hostel_fee_id" id="hostel_fee_id" value="">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="hostel_block_div">
                            <label for="result" class="required">Hostel Block</label>
                            <select class="form-control select2" id="hostel_block" name="hostel_block">
                                <option value="">Select Batch</option>
                                @foreach ($hostel_block as $id => $h)
                                    <option value="{{ $id }}">{{ $h }}</option>
                                @endforeach
                            </select>
                            <span id="hostel_block_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="applied_batch_div">
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

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                            <label for="applied_ay" class="required">Applicable AY</label>
                            <select class="form-control select2" id="applied_ay" name="applied_ay">
                                <option value="">Select AY</option>
                                @foreach ($ay as $id => $a)
                                    <option value="{{ $id }}">{{ $a }}</option>
                                @endforeach
                            </select>
                            <span id="applied_ay_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                            <div class="form-group">
                                <label for="applied_ay" class="required">Hostel Fees</label>
                                <input type="number" class="form-control" id="hostel_fee_amount"
                                    placeholder="Enter Amount for Year">
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="batch_filter_std_div">
                            <label for="feeBatch" class="required">Select Students</label>
                            <select class="form-control select2" id="batch_filter_std" name="batch_filter_std" multiple>
                                <option value="">Select Students</option>
                            </select>
                            <span id="batch_filter_std_span" class="text-danger text-center"
                                style="display:none; font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="view_edit_name_div"
                            style="display: none;">
                            <div class="form-group">
                                <label for="view_edit_name" class="required">Student Name</label>
                                <input type="text" class="form-control" id="view_edit_name" readonly>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveSection()">Save</button>
                    </div>
                    {{-- <div id="loading_div">
                        <span class="theLoader"></span>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
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
            @can('batch_delete')
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
                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: "{{ route('admin.hostel_fee.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-HostelFee')) {
                $('.datatable-HostelFee').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.hostel_fee.index') }}",
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
                        data: 'register_number',
                        name: 'register_number'
                    },
                    {
                        data: 'batch',
                        name: 'batch'
                    },
                    {
                        data: 'ay',
                        name: 'ay'
                    },
                    {
                        data: 'hostel_block_id',
                        name: 'hostel_block_id'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-HostelFee').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {

            $("#hostel_fee").modal();
            $("#applied_batch").val('').select2();
            $("#applied_ay").val('').select2();
            $("#hostel_fee_amount").val('');
            $("#hostel_block").val('');

            // $("#batch_filter_std").val('').select2();


        }
        $('#applied_batch, #applied_ay').on('change', function() {

            var batch = $('#applied_batch').val();
            var applied_ay = $('#applied_ay').val();

            if (batch !== '' && applied_ay !== '') {
                initiateFunction(batch, applied_ay);
            } else {
                console.log("Please select both Batch and Course.");
            }
        })

        function initiateFunction(batch, applied_ay) {

            var batch = $('#applied_batch').val();
            var applied_ay = $('#applied_ay').val();


            $('#loading').show();

            $.ajax({

                url: '{{ route('admin.hostel_fee.filter_student') }}',
                type: 'POST',
                data: {
                    'batch': batch,
                    'applied_ay': applied_ay
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    let status = response.status;
                    if (status) {
                        if (status == true) {
                            console.log(response);
                            let students = response.data;
                            $('#batch_filter_std').empty();
                            $('#batch_filter_std').append('<option value="">Select Students</option>');

                            $.each(students, function(register_number, name) {
                                $('#batch_filter_std').append(
                                    `<option value="${register_number}">${name} (${register_number})</option>`
                                );
                            });

                            $('#batch_filter_std').trigger('change.select2');

                        } else {
                            Swal.fire('', response.data, 'error');
                        }

                    } else {
                        Swal.fire('', response.data, 'error');

                    }
                    $('#loading').hide();


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = textStatus || errorThrown || 'Request Failed';
                    Swal.fire('', errorMessage, 'error');

                    $('#loading').hide();


                }

            })


        }


        function saveSection() {
  
            
            var applied_batch = $("#applied_batch").val();
            var hostel_fee_id = $("#hostel_fee_id").val();
            var applied_ay = $("#applied_ay").val();
            var hostel_fee_amount = $("#hostel_fee_amount").val();
            var batch_filter_std = $("#batch_filter_std").val();
            var hostel_block = $("#hostel_block").val();

            $('#loading').show();

            $.ajax({

                url: '{{ route('admin.hostel_fee.store') }}',
                type: 'POST',
                data: {
                    'applied_batch': applied_batch,
                    'applied_ay': applied_ay,
                    'hostel_fee_amount': hostel_fee_amount,
                    'batch_filter_std': batch_filter_std,
                    'hostel_block': hostel_block,
                    'hostel_fee_id' :hostel_fee_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    let status = response.status;
                    if (status) {
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                            $("#hostel_fee").modal('hide');
                            callAjax();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }

                    } else {
                        Swal.fire('', response.data, 'error');

                    }
                    $('#loading').hide();


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = textStatus || errorThrown || 'Request Failed';
                    Swal.fire('', errorMessage, 'error');

                    $('#loading').hide();


                }

            })

        }

        function hostel_fee_delete(id) {
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
                            url: "{{ route('admin.hostel_fee.delete') }}",
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

        function hostel_fee_view(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                $('.secondLoader').show()


                $.ajax({

                    url: "{{ route('admin.hostel_fee.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        console.log(response)

                        $('.secondLoader').hide()


                        let status = response.status;
                        if (status == true) {

                            var data = response.data;
                            $("#hostel_fee_amount").val(data.amount);
                            $("#hostel_block").val(data.hostel_block_id).select2();
                            $("#applied_batch").val(data.batch_id).select2();
                            $("#applied_ay").val(data.academic_year_id).select2();
                            $("#view_edit_name").val(data.name);
                            $('#view_edit_name_div').show();
                            $('#batch_filter_std_div').hide();
                            $("#hostel_fee").modal();
                            $("#save_div").hide();

                        } else {
                            Swal.fire('', response.data, 'error');

                        }
                    }
                })
            }
        }



        function hostel_fee_edit(id) {
            // alert(id)
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.hostel_fee.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        console.log(response)
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;

                            $("#hostel_fee_amount").val(data.amount);
                            $("#hostel_fee_id").val(data.id);
                            $("#hostel_block").val(data.hostel_block_id).select2();
                            $("#applied_batch").val(data.batch_id).select2();
                            $("#applied_ay").val(data.academic_year_id).select2();
                            $("select").prop('disabled', true).select2()
                            $("#view_edit_name").val(data.name);
                            $('#view_edit_name_div').show();
                            $('#batch_filter_std_div').hide();
                            $("#hostel_fee").modal();



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
    </script>
@endsection
