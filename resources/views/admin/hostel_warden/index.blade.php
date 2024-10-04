@extends('layouts.admin')
@section('content')
    @can('hostel_room_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Warden
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
            Hostel Warden Lists
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-HostelWarden text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Hostel Name
                        </th>
                        <th>
                            Warden Name
                        </th>
                        <th>
                            Staff Code
                        </th>
                        <th>
                            Phone Number
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
    <div class="modal fade" id="hostel_warden" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="hostel" class="required">Warden</label>
                            <input type="hidden" name="warden_id" id="warden_id" value="">
                            <select name="warden" id="warden" class="form-control select2" required>
                                <option value="">Select Warden</option>
                                @foreach ($warden as $id => $w)
                                    <option value="{{ $id }}">{{ $w }}</option>
                                @endforeach
                            </select>
                            <span id="warden_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="hostel" class="required">Hostel Name</label>
                            <select name="hostel" id="hostel" class="form-control select2" required>
                                <option value="">Select Hostel</option>
                                @foreach ($hostel as $id => $h)
                                    <option value="{{ $id }}">{{ $h }}</option>
                                @endforeach
                            </select>
                            <span id="hostel_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveSection()">Save</button>
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
            $('.roomViews').hide();
            $('.slot').show();
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('hostel_room_delete')
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
                                        url: "{{ route('admin.hostel-warden.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-HostelWarden')) {
                $('.datatable-HostelWarden').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.hostel-warden.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'hostel',
                        name: 'hostel'
                    },
                    {
                        data: 'warden',
                        name: 'warden'
                    },
                    {
                        data: 'staff_code',
                        name: 'staff_code'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
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
            let table = $('.datatable-HostelWarden').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $('.roomViews').hide();
            $('.slot').show();
            $("#hostelRoom_id").val('')
            $("#warden").val('')
            $("#warden").select2();
            $("#hostel").val('').select2()
            $("#hostel_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#hostel_warden").modal();
        }

        function saveSection() {
            $("#loading_div").hide();
            if ($("#warden").val() == '') {
                $("#warden_span").html(`Warden Is Required.`);
                $("#warden_span").show();
                $("#hostel_span").hide();

            } else if ($("#hostel").val() == '') {
                $("#hostel_span").html(`Hostel Is Required.`);
                $("#hostel_span").show();
                $("#warden_span").hide();

            } else {
                $("#save_div").hide();
                $("#warden_span").hide();
                $("#hostel_span").hide();
                $("#loading_div").show();
                let id = $("#warden_id").val();
                let hostel = $("#hostel").val();
                let warden = $("#warden").val();
                $.ajax({
                    url: "{{ route('admin.hostel-warden.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'hostel': hostel,
                        'warden': warden,
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#hostel_warden").modal('hide');
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

        function viewHostelWarden(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()

                $.ajax({
                    url: "{{ route('admin.hostel-warden.view') }}",
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
                            $("#warden_id").val(data.id);
                            $("#warden").val(data.warden_id);
                            $("#warden").select2();
                            $("#hostel").val(data.hostel_id).select2();
                            $("#save_div").hide();
                            $("#hostel_span").hide();
                            $("#warden_span").hide();
                            $("#loading_div").hide();
                            $("#hostel_warden").modal();
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

        function editHostelWarden(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.hostel-warden.edit') }}",
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
                            $("#warden_id").val(data.id);
                            $("#warden").val(data.warden_id);
                            $("#warden").select2();
                            $("#hostel").val(data.hostel_id).select2();
                            $('.roomViews').show();
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#hostel").hide();
                            $("#warden_span").hide();
                            $("#loading_div").hide();
                            $("#hostel_warden").modal();
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

        function deleteHostelWarden(id) {
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
                            url: "{{ route('admin.hostel-warden.delete') }}",
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
