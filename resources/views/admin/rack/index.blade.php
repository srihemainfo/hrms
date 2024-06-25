@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 5) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    @can('library_rack_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Rack
                </button>
            </div>
        </div>
    @endcan
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Library Racks List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Rack text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Rack No
                        </th>
                        <th>
                            Row No
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
    <div class="modal fade" id="rackModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="rack" class="required">Rack Name / No.</label>
                            <input type="hidden" name="rack_id" id="rack_id" value="">
                            <input type="text" name="rack" id="rack" class="form-control"
                                style="text-transform: uppercase;">
                            <span id="rack_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group rack_row">
                            <label for="rack_row" class="required">Total Rows</label>
                            <input type="text" class="form-control" id="rack_row" name="rack_row" value="">
                            <span id="rack_row_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveRack()">Save</button>
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
            @can('library_rack_delete')
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
                                        url: "{{ route('admin.rack.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-Rack')) {
                $('.datatable-Rack').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.rack.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'rack',
                        name: 'rack'
                    },
                    {
                        data: 'row',
                        name: 'row'
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
            let table = $('.datatable-Rack').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#rack_id").val('')
            $("#rack").val('');
            $("#rack_row").val('')
            $("#rack_span").hide();
            $("#rack_row_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#rackModal").modal();
        }

        function saveRack() {
            $("#loading_div").hide();
            if ($("#rack").val() == '') {
                $("#rack_span").html(`Rack Is Required.`);
                $("#rack_span").show();
                $("#rack_row_span").hide();
                $("#seat_span").hide();

            } else if ($("#rack_row").val() == '') {
                $("#rack_row_span").html(`Row Count Is Required.`);
                $("#rack_row_span").show();
                $("#rack_span").hide();
                $("#seat_span").hide();

            } else if (isNaN($("#rack_row").val())) {
                $("#rack_row_span").html(`Enter Only in Number`);
                $("#rack_row_span").show();
                $("#rack_span").hide();
                $("#seat_span").hide();
            } else {
                $("#save_div").hide();
                $("#rack_span").hide();
                $("#rack_row_span").hide();
                $("#loading_div").show();
                let id = $("#rack_id").val();
                let rack = $("#rack").val();
                let row = $("#rack_row").val();
                $.ajax({
                    url: "{{ route('admin.rack.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'rack': rack,
                        'row': row,
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#rackModal").modal('hide');
                        callAjax();
                    }
                })
            }
        }

        function viewRack(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.rack.view') }}",
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
                            $("#rack_id").val(data.id);
                            var rack_no = data.rack_no.match(/\d+/);
                            $("#rack").val(rack_no);
                            var row_no = data.row_no.match(/\d+/);
                            $("#rack_row").val(row_no[0]);
                            $("#save_div").hide();
                            $("#rack_span").hide();
                            $("#rack_row_span").hide();
                            $("#loading_div").hide();
                            $("#rackModal").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }

        function editRack(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.rack.edit') }}",
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
                            $("#rack_id").val(data.id);
                            var rack_no = data.rack_no.match(/\d+/);
                            $("#rack").val(rack_no);
                            var row_no = data.row_no.match(/\d+/);
                            $("#rack_row").val(row_no[0]);
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#rack_span").hide();
                            $("#rack_row_span").hide();
                            $("#loading_div").hide();
                            $("#rackModal").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }

        function deleteRack(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
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
                            url: "{{ route('admin.rack.delete') }}",
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
                            }
                        })
                    }
                })
            }
        }
    </script>
@endsection
