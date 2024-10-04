@extends('layouts.admin')
@section('content')
    @can('hostel_room_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Hostel Room
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
            HostelRoom List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-HostelRoom text-center">
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
                            Hostel Room No
                        </th>
                        <th>
                            Total Slots
                        </th>
                        <th>
                            Available Slots
                        </th>
                        <th>
                            Filled Slots
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
    <div class="modal fade" id="hostelRoom" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="hostel" class="required">Hostel</label>
                            <input type="hidden" name="hostelRoom_id" id="hostelRoom_id" value="">
                            <select name="hostel" id="hostel" class="form-control select2" required>
                                <option value="">Select Hostel</option>
                                @foreach ($hostel as $id => $h)
                                    <option value="{{ $id }}">{{ $h }}</option>
                                @endforeach
                            </select>
                            <span id="hostel_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="hostel_room" class="required">Hostel Room</label>
                            <input type="text" class="form-control" id="hostel_room" name="hostel_room" value="">
                            <span id="hostel_room_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group slot">
                            <label for="seat">Slot</label>
                            <input type="number" class="form-control" id="seat" name="seat" value="">
                            <span id="seat_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group roomViews">
                            <label for="total_slot" class="required">Total Slot</label>
                            <input type="text" class="form-control" id="total_slot" name="total_slot" value="">
                            <span id="total_slot_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group roomViews">
                            <label for="available_slot">Availble Slot</label>
                            <input type="number" class="form-control" id="available_slot" name="available_slot"
                                value="">
                            <span id="available_slot_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group roomViews">
                            <label for="filled_slot">Filled Slot</label>
                            <input type="number" class="form-control" id="filled_slot" name="filled_slot" value="">
                            <span id="filled_slot_span" class="text-danger text-center"
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
                                        url: "{{ route('admin.hostelRoom.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-HostelRoom')) {
                $('.datatable-HostelRoom').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.hostelRoom.index') }}",
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
                        data: 'HostelRoom_No',
                        name: 'HostelRoom_No'
                    },
                    {
                        data: 'total_seat',
                        name: 'total_seat'
                    },
                    {
                        data: 'available_seat',
                        name: 'available_seat'
                    },
                    {
                        data: 'filled_seat',
                        name: 'filled_seat'
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
            let table = $('.datatable-HostelRoom').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $('.roomViews').hide();
            $('.slot').show();
            $("#hostelRoom_id").val('')
            $("#hostel").val($("#target option:first").val())
            $("#hostel").select2();
            $("#hostel_room").val('')
            $("#hostel_span").hide();
            $("#hostel_room_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#hostelRoom").modal();
        }

        function saveSection() {
            $("#loading_div").hide();
            if ($("#hostel").val() == '') {
                $("#hostel_span").html(`Hostel Is Required.`);
                $("#hostel_span").show();
                $("#hostel_room_span").hide();
                $("#seat_span").hide();

            } else if ($("#hostel_room").val() == '') {
                $("#hostel_room_span").html(`HostelRoom Is Required.`);
                $("#hostel_room_span").show();
                $("#hostel_span").hide();
                $("#seat_span").hide();

            } else {
                $("#save_div").hide();
                $("#hostel_span").hide();
                $("#hostel_room_span").hide();
                $("#loading_div").show();
                let id = $("#hostelRoom_id").val();
                let hostel = $("#hostel").val();
                let HostelRoom = $("#hostel_room").val();
                let seat = $("#seat").val();
                let total = $("#total_slot").val();
                let available = $("#available_slot").val();
                let filled = $("#filled_slot").val();
                $.ajax({
                    url: "{{ route('admin.hostelRoom.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'hostel': hostel,
                        'hostel_room': HostelRoom,
                        'seat': seat,
                        'total': total,
                        'available': available,
                        'filled': filled
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#hostelRoom").modal('hide');
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

        function viewHostelRoom(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()

                $.ajax({
                    url: "{{ route('admin.hostelRoom.view') }}",
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
                            $("#hostel").val(data.hostel_id);
                            $("#hostel").select2();
                            $("#hostel_room").val(data.room_no);
                            $("#total_slot").val(data.total_slots != null ? data.total_slots : 0);
                            $("#available_slot").val(data.available_slots != null ? data.available_slots : 0);
                            $("#filled_slot").val(data.filled_slots != null ? data.filled_slots : 0);
                            $('.roomViews').show();
                            $('.slot').hide();
                            $("#save_div").hide();
                            $("#hostel_span").hide();
                            $("#hostel_room_span").hide();
                            $("#loading_div").hide();
                            $("#hostelRoom").modal();
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

        function editHostelRoom(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.hostelRoom.edit') }}",
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
                            $("#hostelRoom_id").val(data.id);
                            $("#hostel").val(data.hostel_id);
                            $("#hostel").select2();
                            $("#hostel_room").val(data.room_no);
                            $("#total_slot").val(data.total_slots != null ? data.total_slots : 0);
                            $("#available_slot").val(data.available_slots != null ? data.available_slots : 0);
                            $("#filled_slot").val(data.filled_slots != null ? data.filled_slots : 0);
                            $('.roomViews').show();
                            $('.slot').hide();
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#hostel_span").hide();
                            $("#hostel_room_span").hide();
                            $("#loading_div").hide();
                            $("#hostelRoom").modal();
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

        function deleteHostelRoom(id) {
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
                            url: "{{ route('admin.hostelRoom.delete') }}",
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
