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
            <table class=" table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>
                            SNO
                        </th>
                        <th>
                            Hostel
                        </th>
                        <th>
                            Attendance
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($hostel as $id => $h)
                        {{-- {{dd(auth()->user()->id != 1 && (auth()->user()->roles[0]->id == 9 && auth()->user()->hostel_id == 1))}} --}}
                        @if (auth()->user()->id != 1 && (auth()->user()->roles[0]->id == 9 && auth()->user()->hostel_id == $id))
                            <tr>
                                <td>{{ $id }}</td>
                                <td>{{ $h }}</td>
                                <td>
                                    <button class="btn btn-xs btn-outline-success" onclick="attend({{ $id }})"
                                        data-hostel_id="{{ $id }}">Take Attendance</button>
                                    @if (in_array($id, $attend))
                                        <button class="btn btn-xs btn-outline-secondary"
                                            onclick="attendView({{ $id }})">View Attendance</button>
                                        <button class="btn btn-xs btn-outline-primary"
                                            onclick="attendEdit({{ $id }})">Edit Attendance</button>
                                    @endif
                                </td>
                            </tr>
                        @elseif (auth()->user()->id == 1)
                            <tr>
                                <td>{{ $id }}</td>
                                <td>{{ $h }}</td>
                                <td>
                                    <button class="btn btn-xs btn-outline-success" onclick="attend({{ $id }})"
                                        data-hostel_id="{{ $id }}">Take Attendance</button>
                                    @if (in_array($id, $attend))
                                        <button class="btn btn-xs btn-outline-secondary"
                                            onclick="attendView({{ $id }})">View Attendance</button>
                                        <button class="btn btn-xs btn-outline-primary"
                                            onclick="attendEdit({{ $id }})">Edit Attendance</button>
                                    @endif

                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div>
    <form id="myForm">
        <div class="modal fade" id="roomAllotModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col">
                            <span id="hostel_name" name="hostel_name"></span>
                        </div>
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body d-flex">
                            <div class="col">
                                <label for="date">Date</label>
                                <input type="hidden" name="hostel_id" id="hostel_id" value="">
                                <input type="text" class="form-control date" name="date" id="date">
                            </div>
                            <div class="col">
                                <label for="day_type">Day Type</label>
                                <select name="day_type" id="day_type" class="form-control select2">
                                    <option value="">Select Day Type</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Evening">Evening</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="myInput">Search</label>
                                <input id="myInput" class="form-control" type="text" placeholder="Search...">
                            </div>
                        </div>
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <br>
                                <table class="table">
                                    <thead style="background-color: #076ad4; color: white; border-radius: 10px;">
                                        <tr>
                                            <th>SNo</th>
                                            <th>Name</th>
                                            <th>Room No</th>
                                            <th>Present</th>
                                            <th>Absent</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="row_count" id="row_count" value="">
                    <div class="modal-footer">
                        <div id="save_div">
                            <button type="submit" id="save_btn" class="btn btn-outline-success">Save</button>
                        </div>
                        <div id="view_div">
                            <button type="button" id="view_btn" class="btn btn-outline-success"
                                onclick="viewDayAttend()">View</button>
                        </div>
                        <div id="loading_div">
                            <span class="theLoader"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    @parent
    <script>
        // $(function() {
        //     // var currentDate = new Date().toISOString().slice(0, 10);
        //     // $('#date').html(currentDate);
        //     // // Display the value of the date
        //     // console.log('Current Date: ' + currentDate);
        // })

        function attend(id) {
            $('#date').val('')
            $('#day_type').val('')
            $('#hostel_id').val('')
            $('.secondLoader').show()
            $.ajax({
                url: "{{ route('admin.hostel-attendance.get-student') }}",
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
                        $('.secondLoader').hide()
                        var today = new Date();
                        var year = today.getFullYear();
                        var month = String(today.getMonth() + 1).padStart(2, '0');
                        var day = String(today.getDate()).padStart(2, '0');
                        var formattedDate = year + '-' + month + '-' + day;
                        $('#date').val(formattedDate);
                        let name = response.studentName
                        let userId = response.studentUserId
                        let enroll = response.studentEnroll
                        let roomId = response.studentRoomId
                        let roomName = response.studentRoomName
                        let hostel_name = response.hostel_name
                        $('#hostel_name').text(hostel_name.name)
                        let s = 0;
                        let body = $('#tbody').empty();
                        for (i = 0; i < name.length; i++) {
                            let row = $('<tr>')
                            row.append(`<td>${s+=1}</td>`)
                            row.append(
                                `<td>${name[i]}<input type="hidden" value="${userId[i]}" name="user_id${i}"> <input type="hidden" value="${enroll[i]}" name="enroll_id${i}"></td>`
                            )
                            row.append(
                                `<td>${roomName[i]}<input type="hidden" value="${roomId[i]}" name="room_id${i}"></td>`
                            )
                            row.append(`<td><input type="radio" class="presentBox" id="presentBox${i}" name="mainBox${i}"
                style="width:12px;height:12px;accent-color:#0f6dd1;" onchange="attendanceAction('present', this)">
                                </td>`)
                            row.append(
                                `<td><input type="radio" class="absentBox" id="absentBox${i}" name="mainBox${i}"
                style="width:12px;height:12px;accent-color:#0f6dd1;" onchange="attendanceAction('absent', this)"></td>`
                            )

                            body.append(row)
                        }
                        row2 = $('<tr>')
                        row2.append(`<td></td>`)
                        row2.append(`<td></td>`)
                        row2.append(`<td></td>`)
                        row2.append(`<td style="padding-left: 10px;"><input type="radio" id="mainPresent" name="mainPresent"
                style="width:15px;height:15px;accent-color:green;" onchange="selectAll('mainPresent', this)">
                                </td>`)
                        row2.append(`<td style="padding-left: 10px;"><input type="radio" id="mainAbsent" name="mainPresent"
                style="width:15px;height:15px;accent-color:green;" onchange="selectAll('mainAbsent', this)">
                                </td>`)
                        body.prepend(row2)
                        let row_count = $('#tbody').find('tr').length
                        if (row_count > 1) {
                            let value = row_count - 1;
                            $('#row_count').val(value);
                        }

                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                    $('.gutters').show()
                    $("#roomAllotModal").modal();
                    $('#student').attr('disabled', false);
                    $('.secondLoader').hide()
                    $('#loading_div').hide()
                    $('#hostel_id').val('')
                    $('#view_div').hide()
                    $('#save_div').show()

                    // callAjax();
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


        function attendanceAction(action, element) {
            $('.stu_form input[type="radio"]').prop('checked', false);
            if ($(element).is(':checked')) {
                if (action == 'present') {
                    $(element).val('Present')

                } else if (action == 'absent') {
                    $(this).prop('checked', true);
                    $(element).val('Absent')
                }
            }
        }

        function selectAll(value, element) {
            let row_count = $('#row_count').val();
            if (value == 'mainPresent') {
                for (let i = 0; i < row_count; i++) {
                    var presentBox = '#presentBox' + i
                    var absentBox = '#absentBox' + i
                    $(presentBox).val('Present')
                    $(absentBox).val('')
                    $(presentBox).prop('checked', true)
                    // $(data).prop('checked', true)
                }
            } else {
                for (let i = 0; i < row_count; i++) {
                    var absentBox = '#absentBox' + i
                    var presentBox = '#presentBox' + i
                    $(absentBox).val('Absent')
                    $(presentBox).val('')
                    $(absentBox).prop('checked', true)
                    // $(presentBox).prop('checked', false)
                }
            }
        }

        $('#myForm').submit(function(event) {
            event.preventDefault();
            if ($('#date').val() == '' || $('#day_type').val() == '') {
                Swal.fire('', "Enter Date, Day Hostel", 'error');
            } else {
                if ($('#row_count').val() != '') {
                    for (let i = 0; parseInt($('#row_count').val()) > i; i++) {
                        let presentBox = 'presentBox' + i;
                        let absentBox = 'absentBox' + i;
                        if ($('#' + presentBox).val() == 'Present' || $('#' + absentBox).val() == 'Absent') {

                        } else {
                            console.log($('#' + presentBox).val());
                            console.log($('#' + absentBox).val());
                            Swal.fire('', "Take Attendance for All Atudents", 'error');
                            return false;
                        }

                    }
                    $('#loading_div').show()
                    $('#save_div').hide()
                    let data = $(this).serialize()
                    var formDataObject = {};
                    data.split('&').forEach(function(keyValue) {
                        var pair = keyValue.split('=');
                        formDataObject[pair[0]] = decodeURIComponent(pair[1] || '');
                    });

                    console.log(JSON.stringify(formDataObject));
                    $.ajax({
                        url: "{{ route('admin.hostel-attendance.store') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'request': JSON.stringify(formDataObject)
                        },
                        success: function(response) {
                            let status = response.status
                            let data = response.data
                            if (status == true) {
                                Swal.fire('', data, 'success')
                                location.reload()
                            } else {
                                Swal.fire('', data, 'error')
                            }
                            $('#loading_div').hide()
                            $('#save_div').show()
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
                } else {
                    Swal.fire('', "Students Not Available", 'error');
                }



            }
        })

        $(function() {
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            // $('.roomViews').hide();
            // $('.slot').show();
            // callAjax();
        });

        function attendView(id) {
            $('#date').val('')
            $('#day_type').val('').select2()
            $('#tbody').empty()
            $('.gutters').hide()
            $('#hostel_id').val(id)
            $('#loading_div').hide()
            $('#save_div').hide()
            $('#view_btn').html('View')
            $('#view_div').show()
            $('#roomAllotModal').modal()
        }

        function viewDayAttend() {

            $('#loading_div').show()
            $('#save_div').hide()
            $('#view_div').hide()
            $.ajax({
                url: "{{ route('admin.hostel-attendance.view_attendance') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'date': $('#date').val(),
                    'day': $('#day_type').val(),
                    'hostel_id': $('#hostel_id').val()
                },
                success: function(response) {
                    let status = response.status
                    let data = response.data
                    if (status == true) {
                        let body = $('#tbody').empty();
                        let s = 0;
                        $.each(data, function(index, value) {
                            let row = $('<tr>')
                            row.append(`<td>${s+=1}</td>`)
                            row.append(
                                `<td>${value.student_name}<input type="hidden" value="${value.user_name_id}" name="user_id${index}"> <input type="hidden" value="${value.enroll_master_id}" name="enroll_id${index}"> <input type="hidden" value="${value.id}" name="id${index}"></td>`
                            )
                            row.append(
                                `<td>${value.room_no}<input type="hidden" value="${value.room_id}" name="room_id${index}"></td>`
                            )

                            if (value.attendance == 'Present') {
                                row.append(`<td><input type="radio" class="presentBox" value="Present" id="presentBox${index}" name="mainBox${index}"
                style="width:12px;height:12px;accent-color:#0f6dd1;" onchange="attendanceAction('present', this)" checked>
                                </td>`)
                                row.append(
                                    `<td><input type="radio" class="absentBox" id="absentBox${index}" name="mainBox${index}"
                style="width:12px;height:12px;accent-color:#0f6dd1;" onchange="attendanceAction('absent', this)"></td>`
                                )
                            } else if (value.attendance == 'Absent') {
                                row.append(`<td><input type="radio" class="presentBox" id="presentBox${index}" name="mainBox${index}"
                style="width:12px;height:12px;accent-color:#0f6dd1;" onchange="attendanceAction('present', this)" >
                                </td>`)
                                row.append(
                                    `<td><input type="radio" class="absentBox" value="Absent" id="absentBox${index}" name="mainBox${index}"
                style="width:12px;height:12px;accent-color:#0f6dd1;" onchange="attendanceAction('absent', this)" checked></td>`
                                )
                            }

                            body.append(row)
                        })
                        row2 = $('<tr>')
                        row2.append(`<td></td>`)
                        row2.append(`<td></td>`)
                        row2.append(`<td></td>`)
                        row2.append(`<td style="padding-left: 10px;"><input type="radio" id="mainPresent" name="mainPresent"
                style="width:15px;height:15px;accent-color:green;" onchange="selectAll('mainPresent', this)">
                                </td>`)
                        row2.append(`<td style="padding-left: 10px;"><input type="radio" id="mainAbsent" name="mainPresent"
                style="width:15px;height:15px;accent-color:green;" onchange="selectAll('mainAbsent', this)">
                                </td>`)
                        body.prepend(row2)
                        let row_count = $('#tbody').find('tr').length
                        if (row_count > 1) {
                            let value = row_count - 1;
                            $('#row_count').val(value);
                        }


                        $('#loading_div').hide()
                        if ($('#view_btn').text() == 'Get Attendance') {
                            $('#save_div').show()
                            $('#view_div').hide()
                        } else {
                            $('#save_div').hide()
                            $('#view_div').show()
                        }
                        $('.gutters').show()
                    } else {
                        $('#loading_div').hide()
                        $('#save_div').hide()
                        $('#view_div').show()
                        Swal.fire('', data, 'error')
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

        function attendEdit(id) {
            $('#date').val('')
            $('#day_type').val('').select2()
            $('#tbody').empty()
            $('.gutters').hide()
            $('#hostel_id').val(id)
            $('#loading_div').hide()
            $('#save_div').hide()
            $('#view_btn').html('Get Attendance')
            $('#view_div').show()
            $('#roomAllotModal').modal()
        }


        // function callAjax() {
        //     let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        //     dtButtons.splice(2, 2);
        //     dtButtons.splice(3, 3);
        //     @can('hostel_room_delete')
        //         let deleteButton = {
        //             text: 'Delete Selected',
        //             className: 'btn-outline-danger',
        //             action: function(e, dt, node, config) {
        //                 var ids = $.map(dt.rows({
        //                     selected: true
        //                 }).data(), function(entry) {
        //                     return entry.id
        //                 });

        //                 if (ids.length === 0) {
        //                     Swal.fire('', 'No Rows Selected', 'warning');

        //                     return
        //                 }

        //                 Swal.fire({
        //                     title: "Are You Sure?",
        //                     text: "Do You Really Want To Delete !",
        //                     icon: "warning",
        //                     showCancelButton: true,
        //                     confirmButtonText: "Yes",
        //                     cancelButtonText: "No",
        //                     reverseButtons: true
        //                 }).then(function(result) {
        //                     if (result.value) {
        //                         $('.secondLoader').show()
        //                         $.ajax({
        //                                 headers: {
        //                                     'x-csrf-token': _token
        //                                 },
        //                                 method: 'POST',
        //                                 url: "{{ route('admin.hostelRoom.massDestroy') }}",
        //                                 data: {
        //                                     ids: ids,
        //                                     _method: 'DELETE'
        //                                 }
        //                             })
        //                             .done(function(response) {
        //                                 Swal.fire('', response.data, response.status);
        //                                 $('.secondLoader').hide()
        //                                 callAjax()
        //                             })
        //                     }
        //                 })
        //             }
        //         }
        //         dtButtons.push(deleteButton)
        //     @endcan
        //     if ($.fn.DataTable.isDataTable('.datatable-RoomAllot')) {
        //         $('.datatable-RoomAllot').DataTable().destroy();
        //     }
        //     let dtOverrideGlobals = {
        //         buttons: dtButtons,
        //         retrieve: true,
        //         aaSorting: [],
        //         ajax: "{{ route('admin.room-allot.index') }}",
        //         columns: [{
        //                 data: 'placeholder',
        //                 name: 'placeholder'
        //             },
        //             {
        //                 data: 'id',
        //                 name: 'id',
        //             },
        //             {
        //                 data: 'room_no',
        //                 name: 'room_no'
        //             },
        //             {
        //                 data: 'available_slots',
        //                 name: 'available_slots'
        //             },
        //             {
        //                 data: 'actions',
        //                 name: 'actions'
        //             }
        //         ],
        //         orderCellsTop: true,
        //         order: [
        //             [1, 'desc']
        //         ],
        //         pageLength: 10,
        //     };
        //     let table = $('.datatable-RoomAllot').DataTable(dtOverrideGlobals);
        //     $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
        //         $($.fn.dataTable.tables(true)).DataTable()
        //             .columns.adjust();
        //     });

        // };

        // function openModal() {
        //     $('.roomViews').hide();
        //     $('.slot').show();
        //     $("#room_allot_id").val('')
        //     $("#student").val('')
        //     $('#student').attr('disabled', false);
        //     $("#student").select2();
        //     $("#hostel_room").val('')
        //     $("#student_span").hide();
        //     $("#hostel_room_span").hide();
        //     $("#loading_div").hide();
        //     $("#save_btn").html(`Save`);
        //     $("#save_div").show();
        //     $("#roomAllotModal").modal();
        // }

        // $('#hostel_room').change(function() {
        //     if ($('#hostel_room').val() != '') {
        //         $.ajax({
        //             url: "{{ route('admin.room-allot.checkRoom') }}",
        //             type: "POST",
        //             headers: {
        //                 'x-csrf-token': _token
        //             },
        //             data: {
        //                 'room_id': $('#hostel_room').val()
        //             },
        //             success: function(response) {
        //                 let status = response.status;
        //                 console.log(status);
        //                 if (status == true) {
        //                     $('.roomViews').show();
        //                     var data = response.data;
        //                     $("#available_slots").text(data.available_slots != null ? data
        //                         .available_slots : 0);
        //                     $('.roomViews').show();
        //                     $("#student_span").hide();
        //                     $("#hostel_room_span").hide();
        //                     $("#loading_div").hide();
        //                     $("#roomAllotModal").modal();
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //             }

        //         })
        //     }
        // })

        // $('#student').change(function() {

        //     let len = $('#student').val().length
        //     let value = $('#student').val()
        //     let slot = $('#available_slots').html()
        //     if (len > slot) {
        //         var students = $('#student').val()
        //         console.log($('#student').val())
        //         var remove = students.pop()
        //         $('#student').val(students)
        //     }
        // })

        // function saveSection() {
        //     $("#loading_div").hide();
        //     if ($("#student").val() == '') {
        //         $("#student_span").html(`Student Is Required.`);
        //         $("#student_span").show();
        //         $("#hostel_room_span").hide();
        //         $("#seat_span").hide();

        //     } else if ($("#hostel_room").val() == '') {
        //         $("#hostel_room_span").html(`HostelRoom Is Required.`);
        //         $("#hostel_room_span").show();
        //         $("#student_span").hide();
        //         $("#seat_span").hide();

        //     } else {
        //         $("#save_div").hide();
        //         $("#student_span").hide();
        //         $("#hostel_room_span").hide();
        //         $("#loading_div").show();
        //         let id = $("#room_allot_id").val();
        //         let student = $("#student").val();
        //         let hostel_room = $('#hostel_room').val()
        //         let available = $("#available_slots").text();
        //         $.ajax({
        //             url: "{{ route('admin.room-allot.store') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'id': id,
        //                 'student': student,
        //                 'hostel_room': hostel_room,
        //                 'available': available
        //             },
        //             success: function(response) {
        //                 let status = response.status;
        //                 if (status == true) {
        //                     Swal.fire('', response.data, 'success');
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //                 $("#roomAllotModal").modal('hide');
        //                 $('#student').attr('disabled', false);
        //                 callAjax();
        //             }
        //         })
        //     }
        // }

        // function viewHostelRoom(id) {
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         $('.secondLoader').show()

        //         $.ajax({
        //             url: "{{ route('admin.hostelRoom.view') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'id': id
        //             },
        //             success: function(response) {
        //                 $('.secondLoader').hide()

        //                 let status = response.status;
        //                 if (status == true) {
        //                     var data = response.data;
        //                     $("#student").val(data.hostel_id);
        //                     $("#student").select2();
        //                     $("#hostel_room").val(data.room_no);
        //                     $("#available_slots").text(data.available_slots != null ? data.available_slots : 0);
        //                     $('.roomViews').show();
        //                     $('.slot').hide();
        //                     $("#save_div").hide();
        //                     $("#student_span").hide();
        //                     $("#hostel_room_span").hide();
        //                     $("#loading_div").hide();
        //                     $("#roomAllotModal").modal();
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //             }
        //         })
        //     }
        // }

        // function editHostelRoom(id) {
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         $('.secondLoader').show()
        //         $.ajax({
        //             url: "{{ route('admin.hostelRoom.edit') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'id': id
        //             },
        //             success: function(response) {
        //                 $('.secondLoader').hide()
        //                 let status = response.status;
        //                 if (status == true) {
        //                     var data = response.data;
        //                     $("#room_allot_id").val(data.id);
        //                     $("#student").val(data.hostel_id);
        //                     $("#student").select2();
        //                     $("#hostel_room").val(data.room_no);
        //                     $("#total_slot").val(data.total_slots != null ? data.total_slots : 0);
        //                     $("#available_slots").val(data.available_slots != null ? data.available_slots : 0);
        //                     $("#filled_slot").val(data.filled_slots != null ? data.filled_slots : 0);
        //                     $('.roomViews').show();
        //                     $('.slot').hide();
        //                     $("#save_btn").html(`Update`);
        //                     $("#save_div").show();
        //                     $("#student_span").hide();
        //                     $("#hostel_room_span").hide();
        //                     $("#loading_div").hide();
        //                     $("#roomAllotModal").modal();
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //             }
        //         })
        //     }
        // }

        // function deleteHostelRoom(id) {
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         $('.secondLoader').show()
        //         Swal.fire({
        //             title: "Are You Sure?",
        //             text: "Do You Really Want To Delete !",
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonText: "Yes",
        //             cancelButtonText: "No",
        //             reverseButtons: true
        //         }).then(function(result) {
        //             if (result.value) {
        //                 $.ajax({
        //                     url: "{{ route('admin.hostelRoom.delete') }}",
        //                     method: 'POST',
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                     },
        //                     data: {
        //                         'id': id
        //                     },
        //                     success: function(response) {
        //                         Swal.fire('', response.data, response.status);
        //                         $('.secondLoader').hide()
        //                         callAjax();
        //                     }
        //                 })
        //             }
        //         })
        //     }
        // }
    </script>
@endsection
