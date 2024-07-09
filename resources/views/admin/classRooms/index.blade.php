@extends('layouts.admin')
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    @can('class_room_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Allocate Class
                </button>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Classes List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ClassRoom text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>Id</th>
                        <th>
                            Class Name (Enroll Master)
                        </th>
                        <th>Class Incharge</th>

                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="secondLoader"></div>
    </div>



    <div class="modal fade" id="inchargeModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="course" class="required">Course</label>
                            <input type="hidden" name="enroll_id" id="enroll_id" value="">
                            <select class="form-control select2" style="text-transform:uppercase" name="course"
                                id="course" required>
                                <option value="">Select Course</option>
                                @foreach ($course as $id => $c)
                                    <option value="{{ $id }}" {{ old('id') == $id ? 'selected' : '' }}>
                                        {{ $c }}</option>
                                @endforeach
                            </select>
                            <span id="course_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group" style="display: none">
                            <label for="enrollment" class="required">Enrollment</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="enrollment"
                                name="enrollment" value="">
                            <span id="enrollment_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="batch" class="required">Batch</label>
                            <select class="form-control select2" style="text-transform:uppercase" name="batch"
                                id="batch" required>
                                <option value="">Select Batch</option>
                                @foreach ($Batch as $id => $b)
                                    <option value="{{ $id }}" {{ old('id') == $id ? 'selected' : '' }}>
                                        {{ $b }}</option>
                                @endforeach
                            </select>
                            <span id="batch_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="ay" class="required">Academic Year</label>

                            <select class="form-control select2" style="text-transform:uppercase" name="ay"
                                id="ay" required>
                                <option value="">Select Ay</option>
                                @foreach ($ay as $id => $a)
                                    <option value="{{ $id }}" {{ old('id') == $id ? 'selected' : '' }}>
                                        {{ $a }}</option>
                                @endforeach
                            </select>
                            <span id="ay_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="sem" class="required">Semester</label>
                            <select class="form-control select2" style="text-transform:uppercase" name="sem"
                                id="sem" required>
                                <option value="">Select Sem</option>
                                @foreach ($Semester as $id => $sem)
                                    <option value="{{ $id }}" {{ old('id') == $id ? 'selected' : '' }}>
                                        {{ $sem }}</option>
                                @endforeach
                            </select>
                            <span id="sem_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="ay" class="required">Section</label>

                            <select class="form-control select2" style="text-transform:uppercase" name="sec"
                                id="sec" required>
                                <option value="">Select Section</option>
                                @foreach ($Section as $id => $sec)
                                    <option value="{{ $sec }}" {{ old('id') == $id ? 'selected' : '' }}>
                                        {{ $sec }}</option>
                                @endforeach
                            </select>
                            <span id="sec_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="sem" class="required">Class Inchage</label>
                            <select class="form-control select2" style="text-transform:uppercase" name="incharge"
                                id="incharge" required>
                                <option value="">Select Inchage</option>
                                @foreach ($staffs as $id => $staff)
                                    <option value="{{ $id }}" {{ old('id') == $id ? 'selected' : '' }}>
                                        {{ $staff }}</option>
                                @endforeach
                            </select>
                            <span id="incharge_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveIncharge()">Save</button>
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
            $('.gutters .form-group').show().eq(1).hide();
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
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
                    }).then(function(sem) {
                        if (sem.value) {
                            $('.secondLoader').show()
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: "{{ route('admin.class-rooms.massDestroy') }}",
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function(response) {
                                    $('.secondLoader').hide()
                                    Swal.fire('', response.data, response.status);
                                    callAjax()
                                })
                        }
                    })
                }
            }
            dtButtons.push(deleteButton)

            if ($.fn.DataTable.isDataTable('.datatable-ClassRoom')) {
                $('.datatable-ClassRoom').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.class-rooms.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'course',
                        name: 'course'
                    },
                    {
                        data: 'incharge',
                        name: 'incharge'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    },
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-ClassRoom').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#course").val('')
            $("#course").select2();
            $("#enroll_id").val('')
            $("#batch").val('')
            $("#batch").select2();
            $("#ay").val('')
            $("#ay").select2();
            $("#sem").val('')
            $("#sem").select2();
            $("#sec").val('')
            $("#sec").select2();
            $("#incharge").val('')
            $("#incharge").prop('disabled', false);
            $("#incharge").select2();
            $("#subject_id").val('')
            $("#course_span").hide();
            $("#batch_span").hide();
            $("#ay_span").hide();
            $("#sem_span").hide();
            $("#sec_span").hide();
            $("#incharge_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $('.gutters .form-group').show().eq(1).hide();
            $("#inchargeModal").modal();
        }

        $('#course').change(function() {
            if ($('#course').val() != '') {
                $('#batch').html(`<option value="">Loading...</option>`)
                $.ajax({
                    url: "{{ route('admin.class-rooms.getBatch') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': $('#course').val(),
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            let batch = $('#batch').empty()
                            batch.prepend(`<option value="">Select Batch</option>`)
                            $.each(data, function(index, value) {
                                batch.append(`<option value="${index}">${value}</option>`)
                            })
                        } else {
                            let batch = $('#batch').empty()
                            batch.prepend(`<option value="">Select Batch</option>`)
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


        function saveIncharge() {
            if ($('#enroll_id').val() == '') {
                if ($("#course").val() == '') {
                    $("#course_span").html(`Course Is Required.`);
                    $("#course_span").show();
                    $("#batch_span").hide();
                    $("#sem_span").hide();
                    $("#ay_span").hide();
                    $("#sec_span").hide();
                    $("#incharge_span").hide();

                } else if ($("#batch").val() == '') {
                    $("#batch_span").html(`Batch Is Required.`);
                    $("#batch_span").show();
                    $("#sem_span").hide();
                    $("#ay_span").hide();
                    $("#course_span").hide();
                    $("#sec_span").hide();
                    $("#incharge_span").hide();

                } else if ($("#ay").val() == '') {
                    $("#ay_span").html(`AY Is Required.`);
                    $("#ay_span").show();
                    $("#batch_span").hide();
                    $("#sem_span").hide();
                    $("#course_span").hide();
                    $("#sec_span").hide();
                    $("#incharge_span").hide();

                } else if ($("#sem").val() == '') {
                    $("#sem_span").html(`sem Is Required.`);
                    $("#sem_span").show();
                    $("#batch_span").hide();
                    $("#ay_span").hide();
                    $("#course_span").hide();
                    $("#sec_span").hide();
                    $("#incharge_span").hide();

                } else if ($("#sec").val() == '') {
                    $("#sem_span").html(`Sem Is Required.`);
                    $("#sec_span").show();
                    $("#sem_span").hide();
                    $("#batch_span").hide();
                    $("#ay_span").hide();
                    $("#course_span").hide();
                    $("#incharge_span").hide();

                } else if ($("#incharge").val() == '') {
                    $("#sem_span").html(`Incharge Is Required.`);
                    $("#incharge_span").show();
                    $("#sec_span").hide();
                    $("#sem_span").hide();
                    $("#batch_span").hide();
                    $("#ay_span").hide();
                    $("#course_span").hide();

                } else {
                    $("#batch_span").hide();
                    $("#ay_span").hide();
                    $("#course_span").hide();
                    $("#sem_span").hide();
                    $("#sec_span").hide();
                    $("#incharge_span").hide();
                    $("#loading_div").show();
                    $("#save_div").hide();
                    let id = $("#enroll_id").val();
                    let course = $("#course").val();
                    let batch = $("#batch").val();
                    let ay = $("#ay").val();
                    let sem = $("#sem").val();
                    let sec = $("#sec").val();
                    let incharge = $("#incharge").val();
                    let enrollment = $("#enrollment").val();

                    storeFunction(id, course, batch, ay, sem, sec, incharge, enrollment)
                }
            } else {
                $('.gutters .form-group').hide().eq(1).show().end().eq(6).show();
                $("#save_div").hide();
                $("#loading_div").show();
                let id = $("#enroll_id").val();
                let course = $("#course").val();
                let batch = $("#batch").val();
                let ay = $("#ay").val();
                let sem = $("#sem").val();
                let sec = $("#sec").val();
                let incharge = $("#incharge").val();
                let enrollment = $("#enrollment").val();
                storeFunction(id, course, batch, ay, sem, sec, incharge, enrollment);
            }

        }

        function storeFunction(id, course, batch, ay, sem, sec, incharge, enrollment) {

            $.ajax({
                url: "{{ route('admin.class-rooms.store') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'course': course,
                    'batch': batch,
                    'ay': ay,
                    'sem': sem,
                    'sec': sec,
                    'incharge': incharge,
                    'enrollment': enrollment,
                    'id': id
                },
                success: function(response) {
                    let status = response.status;
                    if (status == true) {
                        Swal.fire('', response.data, 'success');
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                    $("#inchargeModal").modal('hide');
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



        function editClass_room(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $.ajax({
                    url: "{{ route('admin.class-rooms.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()


                        $('.gutters .form-group').hide().eq(1).show().end().eq(6).show();

                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            console.log(data.id);
                            $("#enroll_id").val(data.id);
                            $("#enrollment").val(data.enroll_master_number).prop('disabled', true);
                            $("#incharge").val(data.user_name_id);
                            $("#incharge").select2();
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#batch_span").hide();
                            $("#loading_div").hide();
                            $("#inchargeModal").modal();
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

        function deleteClass_room(id) {
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
                }).then(function(sem) {
                    if (sem.value) {
                        $('.secondLoader').show()
                        $.ajax({
                            url: "{{ route('admin.class-rooms.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $('.secondLoader').hide()
                                Swal.fire('', response.data, response.status);
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
