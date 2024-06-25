@extends('layouts.admin')
@section('content')
    @can('foundation_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <btn class="btn btn-success" onclick="openModal()">
                    Add Payment Mode
                    </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Payment Mode List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-PaymentMode text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Id
                        </th>
                        <th>
                            Payment Mode
                        </th>
                        <th>
                           Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="paymentModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Payment Mode</label>
                            <input type="hidden" name="payment_id" id="payment_id" value="">
                            <input type="text" class="form-control" style="text-transform:uppercase" id="payment"
                                name="payment" value="">
                            <span id="payment_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-success"
                            onclick="savePayment()">Save</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader">Processing...</span>
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
            @can('foundation_create')
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
                                        url: "{{ route('admin.paymentMode.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-PaymentMode')) {
                $('.datatable-PaymentMode').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.paymentMode.index') }}",
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
            let table = $('.datatable-PaymentMode').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#payment").val('')
            $("#payment_id").val('')
            $("#payment_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#paymentModel").modal();
        }

        function savePayment() {
            $("#loading_div").hide();
            if ($("#payment").val() == '') {
                $("#payment_span").html(`Payment Mode Is Required.`);
                $("#payment_span").show();
            } else if (!isNaN($("#payment").val())) {
                $("#payment_span").html(`It Is Not a Word.`);
                $("#payment_span").show();
            } else {
                $("#save_div").hide();
                $("#payment_span").hide();
                $("#loading_div").show();
                let payment = $("#payment").val();
                let id = $("#payment_id").val();
                $.ajax({
                    url: '{{ route('admin.paymentMode.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'payment': payment,
                        'id': id
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#paymentModel").modal('hide');
                        callAjax();
                    }
                })
            }
        }

        function viewPayment(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                $.ajax({
                    url: "{{ route('admin.paymentMode.view') }}",
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
                            $("#payment").val(data.name);
                            $("#payment_id").val(data.id);
                            $("#save_div").hide();
                            $("#payment_span").hide();
                            $("#loading_div").hide();
                            $("#paymentModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }

        function editPayment(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                $.ajax({
                    url: "{{ route('admin.paymentMode.edit') }}",
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
                            console.log(data.id);
                            $("#payment_id").val(data.id);
                            $("#payment").val(data.name);
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#payment_span").hide();
                            $("#loading_div").hide();
                            $("#paymentModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }

        function deletePayment(id) {
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
                            url: "{{ route('admin.paymentMode.delete') }}",
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
    </script>
@endsection
