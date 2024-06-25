@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 5) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5 || ($type_id == 6 && $role_id == 9)) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    @can('room_allot_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Allot Room
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
            Alloted Room List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RoomAllot text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Hostel Room No
                        </th>
                        <th>
                            Available Slots
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
    <div class="modal fade" id="roomAllotModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="hostel_room" class="required">Hostel Room</label>
                            <input type="hidden" name="room_allot_id" id="room_allot_id" value="">
                            <select name="hostel_room" id="hostel_room" class="form-control select2" required>
                                <option value="">Select Room</option>
                                @foreach ($rooms as $id => $room)
                                    <option value="{{ $id }}">{{ $room }}</option>
                                @endforeach
                            </select>

                            <span id="hostel_room_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group roomViews">
                            <div class="text-center"><b class="text-center">Available Slots</b></div>
                            <div class="text-center mt-2" style="color: #005bbd"><b id="available_slots"></b></div>
                            <div class="text-center mt-2 d-none" style="color: #005bbd"><input type="hidden"
                                    name="filled_slots" id="filled_slots" value=""></div>
                            <div class="text-center mt-2 d-none" style="color: #005bbd"><input type="hidden"
                                    name="total_slots" id="total_slots" value=""></div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="student" class="required">Student</label>
                            <select name="student[]" id="student" class="form-control select2" multiple required>
                                @foreach ($student as $id => $stu)
                                    <option value="{{ $stu->user_name_id }}">{{ $stu->name }}</option>
                                @endforeach
                            </select>
                            <span id="student_span" class="text-danger text-center"
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
        const rooms = ` <option value="">Select Room</option>
        @foreach ($rooms as $id => $room)
                                    <option value="{{ $id }}">{{ $room }}</option>
                                @endforeach`;
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
            if ($.fn.DataTable.isDataTable('.datatable-RoomAllot')) {
                $('.datatable-RoomAllot').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.room-allot.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'room_no',
                        name: 'room_no'
                    },
                    {
                        data: 'available_slots',
                        name: 'available_slots'
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
            let table = $('.datatable-RoomAllot').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#room_allot_id").val('')
            $("#filled_slots").val('')
            $("#total_slots").val('')
            $('.roomViews').hide();
            $('.slot').show();
            $("#student").val('')
            $('#student').attr('disabled', false);
            $("#student").select2();
            $("#hostel_room").val('').prop('disabled', false)
            $("#hostel_room").html(rooms)
            $("#hostel_room").val('').select2()
            $("#student_span").hide();
            $("#hostel_room_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#roomAllotModal").modal();
        }

        $('#hostel_room').change(function() {
            if ($('#hostel_room').val() != '') {
                $.ajax({
                    url: "{{ route('admin.room-allot.checkRoom') }}",
                    type: "POST",
                    headers: {
                        'x-csrf-token': _token
                    },
                    data: {
                        'room_id': $('#hostel_room').val()
                    },
                    success: function(response) {
                        let status = response.status;
                        console.log(status);
                        if (status == true) {
                            $('.roomViews').show();
                            var data = response.data;
                            $("#available_slots").text(data != null ? data
                                .available_slots : 0);
                            $('.roomViews').show();
                            $("#student_span").hide();
                            $("#hostel_room_span").hide();
                            $("#loading_div").hide();
                            $("#roomAllotModal").modal();
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
        })

        $('#student').change(function() {

            if ($("#total_slots").val() != '') {
                let len = $('#student').val().length
                let value = $('#student').val()
                let slot = parseInt($('#total_slots').val())
                if (len > slot) {
                    var students = $('#student').val()
                    var remove = students.pop()
                    $('#student').val(students)
                }
            } else {
                let len = $('#student').val().length
                let value = $('#student').val()
                let slot = $('#available_slots').text()
                if (len > slot) {
                    var students = $('#student').val()
                    var remove = students.pop()
                    $('#student').val(students)
                }
            }

        })

        function saveSection() {
            $("#loading_div").hide();
            if ($("#student").val() == '') {
                $("#student_span").html(`Student Is Required.`);
                $("#student_span").show();
                $("#hostel_room_span").hide();
                $("#seat_span").hide();

            } else if ($("#hostel_room").val() == '') {
                $("#hostel_room_span").html(`HostelRoom Is Required.`);
                $("#hostel_room_span").show();
                $("#student_span").hide();
                $("#seat_span").hide();

            } else {
                $("#save_div").hide();
                $("#student_span").hide();
                $("#hostel_room_span").hide();
                $("#loading_div").show();
                let id = $("#room_allot_id").val();
                let student = $("#student").val();
                let hostel_room = $('#hostel_room').val()
                let available = $("#available_slots").text();
                $.ajax({
                    url: "{{ route('admin.room-allot.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'student': student,
                        'hostel_room': hostel_room,
                        'available': available
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#roomAllotModal").modal('hide');
                        $('#student').attr('disabled', false);
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

        function viewRoomAllot(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()

                $.ajax({
                    url: "{{ route('admin.room-allot.view') }}",
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
                            var hostel = response.hostel;
                            var user_name_id = response.user_name_id;
                            var student = response.student;

                            let select = $("#student")
                            select.empty()

                            let hostelRoom = $('#hostel_room')
                            hostelRoom.empty()
                            $.each(student, function(index, value) {
                                select.append(
                                    `<option value="${value.user_name_id}">${value.name}</option>`)
                            })
                            $.each(hostel, function(index, h) {
                                hostelRoom.append(
                                    `<option value="${h.id}">${h.room_no}</option>`)
                            })


                            $.each(user_name_id, function(index, d) {
                                $("#student option[value='" + d + "']").prop("selected", true);
                            })
                            // $("#student").prop("disabled", true);

                            $("#student").select2()
                            $("#room_allot_id").val(data.id);
                            $("#hostel_room").val(data.room_id).prop('disabled', true);
                            $("#hostel_room").select2();
                            $("#available_slots").html(data.hostel_room != null ? data
                                .hostel_room.available_slots : 0);
                            $("#total_slots").val(data.hostel_room != null ? data.hostel_room.total_slots : 0)
                            $('.roomViews').show();
                            $('.slot').hide();
                            $("#save_div").hide();
                            $(".roomViews").show();
                            $("#student_span").hide();
                            $("#hostel_room_span").hide();
                            $("#loading_div").hide();
                            $("#roomAllotModal").modal();
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

        function editRoomAllot(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.room-allot.edit') }}",
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
                            var hostel = response.hostel;
                            var user_name_id = response.user_name_id;
                            var student = response.student;

                            let select = $("#student")
                            select.empty()

                            let hostelRoom = $('#hostel_room')
                            hostelRoom.empty()
                            $.each(student, function(index, value) {
                                select.append(
                                    `<option value="${value.user_name_id}">${value.name}</option>`)
                            })
                            $.each(hostel, function(index, h) {
                                hostelRoom.append(
                                    `<option value="${h.id}">${h.room_no}</option>`)
                            })


                            $.each(user_name_id, function(index, d) {
                                $("#student option[value='" + d + "']").prop("selected", true);
                            })
                            $("#student").select2()
                            console.log(data.id);
                            $("#room_allot_id").val(data.id);
                            $("#hostel_room").val(data.room_id).prop('disabled', false);
                            $("#hostel_room").select2();
                            $("#available_slots").html(data.hostel_room != null ? data
                                .hostel_room.available_slots : 0);
                            $("#total_slots").val(data.hostel_room != null ? data.hostel_room.total_slots : 0)
                            $('.roomViews').show();
                            $('.slot').hide();
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#student_span").hide();
                            $("#hostel_room_span").hide();
                            $("#loading_div").hide();
                            $("#roomAllotModal").modal();
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

        function deleteRoomAllot(id) {
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
                            url: "{{ route('admin.room-allot.delete') }}",
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
