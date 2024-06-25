@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <button class="btn btn-outline-success" onclick="openModal()">
        Create Credit Limit Master
    </button>
    <div class="card mt-3">
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-CreditLimit text-center">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>S.No</th>
                        <th>Regulation</th>
                        <th>Credit Limit</th>
                        <th>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
        <div class="secondLoader"></div>
    </div>

    <div class="modal fade" id="creditModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                            <label for="regulation" class="required">Regulation</label>
                            <input type="hidden" name="regulation_id" id="regulation_id" value="">
                            <select name="regulation" id="regulation" class="form-control select2">
                                <option value="">Select regulation</option>
                                @foreach ($regulation as $id => $reg)
                                    <option value="{{ $id }}">{{ $reg }}</option>
                                @endforeach
                            </select>
                            <span id="regulation_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>


                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                            <label for="creditLimit" class="required">Credit Limit</label>
                            <input type="text" class="form-control" id="creditLimit" name="creditLimit" value=""
                                style="text-transform:uppercase;">
                            <span id="creditLimit_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group text-center">
                            <label for="no_limit" class="required">No Limit</label>
                            <div class="pt-2">
                                <input type="checkbox" id="no_limit" style="width:18px;height:18px;accent-color:red;"
                                    onchange="checkCheckBox(this)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveCredit()">Save</button>
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

        function checkCheckBox(element) {

            if ($(element).prop("checked")) {
                $(element).removeAttr('checked');
                $("#creditLimit").attr('disabled', true);
                $("#creditLimit").val('');

            } else {
                $(element).attr('checked', true);
                $("#creditLimit").removeAttr('disabled');
            }
        }

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('section_delete')
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
                                        url: "{{ route('admin.credit-limit-master.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        Swal.fire('', response.data, response.status);
                                        $('.secondLoader').hide()

                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-CreditLimit')) {
                $('.datatable-CreditLimit').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.credit-limit-master.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'regulation',
                        name: 'regulation'
                    },
                    {
                        data: 'creditLimit',
                        name: 'creditLimit'
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
            let table = $('.datatable-CreditLimit').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#regulation_id").val('')
            $("#regulation").val($("#target option:first").val())
            $("#regulation").select2();
            $("#creditLimit").val('').attr('disabled', false);
            $("#no_limit").removeAttr('checked').attr('disabled', false);
            $("#no_limit").prop("checked", false)
            $("#regulation").val('').prop('disabled', false);
            $("#regulation_span").hide();
            $("#creditLimit_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#creditModal").modal();
        }

        function saveCredit() {
            $("#loading_div").hide();
            if ($("#regulation").val() == '') {
                $("#regulation_span").html(`Regulation Is Required.`);
                $("#regulation_span").show();
                $("#creditLimit_span").hide();
            } else if (isNaN($("#creditLimit").val())) {
                $("#creditLimit_span").html(`It can't be a Number.`);
                $("#creditLimit_span").show();
                $("#regulation_span").hide();
            } else {
                var creditLimit = '';

                if ($("#creditLimit").val() == '' && $("#no_limit").prop("checked")) {
                    $("#creditLimit_span").html(`CreditLimit Is Required.`);
                    $("#creditLimit_span").hide();
                    $("#regulation_span").hide();
                    creditLimit = 'NO LIMIT';
                } else if ($("#creditLimit").val() != '') {
                    creditLimit = $("#creditLimit").val();
                }

                $("#save_div").hide();
                $("#regulation_span").hide();
                $("#creditLimit_span").hide();
                $("#loading_div").show();
                let id = $("#regulation_id").val();
                let regulation = $("#regulation").val();
                console.log(creditLimit)
                $("#no_limit").prop("")
                $.ajax({
                    url: "{{ route('admin.credit-limit-master.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'regulation': regulation,
                        'creditLimit': creditLimit
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#creditModal").modal('hide');
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
        }

        function viewCredit(id) {
            $("#no_limit").removeAttr('checked')
            $("#no_limit").prop("checked", false)
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.credit-limit-master.view') }}",
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
                            $("#regulation").val(data.regulations.id).prop('disabled', true);
                            $("#regulation").select2();
                            if (data.credit_limit == 'NO LIMIT') {
                                $("#creditLimit").val('').attr('disabled', true);
                                $("#no_limit").prop("checked", true)
                                $("#no_limit").attr('checked', true);
                                $("#no_limit").attr('disabled', false);
                            } else {
                                $("#creditLimit").val(data.credit_limit).attr('disabled', true);
                                $("#no_limit").removeAttr('checked').attr('disabled', true);

                            }
                            $("#save_div").hide();
                            $("#regulation_span").hide();
                            $("#creditLimit_span").hide();
                            $("#loading_div").hide();
                            $("#creditModal").modal();
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
                    }
                })
            }
        }

        function editCredit(id) {
            $("#no_limit").removeAttr('checked')
            $("#no_limit").prop("checked", false)
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.credit-limit-master.edit') }}",
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
                            $("#regulation_id").val(data.id);
                            $("#regulation").val(data.regulation_id).prop('disabled', false);
                            $("#regulation").select2();
                            if (data.credit_limit == 'NO LIMIT') {
                                $("#creditLimit").val('').attr('disabled', true);
                                $("#no_limit").prop("checked", true)
                                $("#no_limit").attr('checked', true);
                                $("#no_limit").attr('disabled', false);
                            } else {
                                $("#creditLimit").val(data.credit_limit).prop('disabled', false);
                                $("#no_limit").removeAttr('checked').attr('disabled', false);
                            }
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#regulation_span").hide();
                            $("#creditLimit_span").hide();
                            $("#loading_div").hide();
                            $("#creditModal").modal();
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
                    }
                })
            }
        }

        function deleteCredit(id) {

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
                            url: "{{ route('admin.credit-limit-master.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                Swal.fire('', response.data, response.status);
                                $('.secondLoader').hide()
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
