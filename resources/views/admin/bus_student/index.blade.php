@extends('layouts.admin')
@section('content')
    @can('bus_student_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Bus Student
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
            Bus Route List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-BusStudent text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Bus No
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Stop Name
                        </th>
                        <th>
                            Student
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
    <div class="modal fade" id="busStudentModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters" id="gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="designation" class="required">Designation</label>
                            <input type="hidden" name="bus_stu_id" id="bus_stu_id" value="">
                            <select class="form-control select2" name="designation" id="designation"
                                onclick="changeDesignation()">
                                <option value="">Select Designation</option>
                                @foreach ($designation as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="designation_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="seats" class="required">Total seats</label>
                            <input type="text" class="form-control" name="seats" id="seats">
                            <span id="seats_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                            <label for="driver" class="required">Bus Driver</label>
                            <input type="text" class="form-control" name="driver" id="driver">
                            <span id="driver_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                            <label for="bus" class="required">Bus No</label>
                            <input type="text" class="form-control" name="bus" id="bus">
                            <span id="bus_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                            <label for="stops" class="required">Stop</label>
                            <select class="form-control select2" name="stops" id="stops">
                                <option value="">Select Stop</option>
                            </select>
                            <span id="stops_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group student">
                            <label for="student" class="required">Students</label>
                            <select class="form-control select2" name="student[]" id="student" multiple>

                            </select>
                            <span id="student_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group tbody1">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>SNo</th>
                                        <th>Student Name</th>
                                        <th>Register No</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">

                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="count" id="count" value="2">
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <span id="error_span" class="text-danger text-center"
                            style="display:none;font-size:0.9rem;"></span>
                        <button type="button" class="btn btn-outline-success" onclick="saveBusStudent()">Save</button>
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
        const get_student = `@foreach ($student as $key => $item)
                                    <option value="{{ $item->user_name_id }}">{{ $item->name }}</option>
                                @endforeach`;
        const all_student = `@foreach ($allStudent as $key => $value)
                                    <option value="{{ $value->user_name_id }}">{{ $value->name }}</option>
                                @endforeach`;
        $(function() {
            $('.tbody1').hide()
            $('.student').show()
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('bus_student_delete')
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
                                $(".secondLoader").show();

                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: "{{ route('admin.bus-student.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        Swal.fire('', response.data, response.status);
                                        $(".secondLoader").hide();
                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-BusStudent')) {
                $('.datatable-BusStudent').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.bus-student.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'bus_no',
                        name: 'bus_no'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'stops',
                        name: 'stops'
                    },
                    {
                        data: 'student_count',
                        name: 'student_count'
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
            let table = $('.datatable-BusStudent').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $('.tbody1').hide()
            $('.student').show()
            $("#bus_stu_id").val('')
            $("#designation").prop('disabled', false)
            $("#designation").val('').select2()
            $("#seats").val('')
            $("#driver").val('')
            $("#bus").val('')
            $("#student").empty()
            $("#student").append(get_student)
            $("#student").val('').select2()
            $("#stops").empty()
            $("#stops").append(`<option value="">Select Stop</option>`).select2()
            $("#designation_span").hide();
            $("#seats_span").hide();
            $("#loading_div").hide();
            $(".save_div").show();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#busStudentModel").modal();
        }

        $('#student').change(function() {

            if ($("#seats").val() != '') {
                let len = $('#student').val().length
                let value = $('#student').val()
                let slot = parseInt($('#seats').val())
                if (len > slot) {
                    var students = $('#student').val()
                    var remove = students.pop()
                    $('#student').val(students)
                }
            } else {
                let len = $('#student').val().length
                let value = $('#student').val()
                let slot = $('#seats').text()
                if (len > slot) {
                    var students = $('#student').val()
                    var remove = students.pop()
                    $('#student').val(students)
                }
            }

        })

        $('#designation').change(function() {
            $('#seats').val('Loading...')
            $('#driver').val('Loading...')
            $('#bus').val('Loading...')
            $('#stops').html(`<option value="">Loading...</option>`)
            $.ajax({
                url: "{{ route('admin.bus-student.checkDesignation') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': $('#designation').val()
                },
                success: function(response) {
                    let status = response.status;
                    $(".secondLoader").hide();
                    if (status == true) {
                        var data = response.data;
                        var student = response.student;

                        $('#seats').val(data[0].available_seats)
                        $('#driver').val(data[0].name)
                        $('#bus').val(data[0].bus_no)
                        let get_stops = JSON.parse(data[0].stops)
                        // let got_stops = JSON.parse(get_stops)
                        let stops = [];
                        $.each(get_stops, function(index, value) {
                            $.each(value, function(key, getValue) {
                                stops[index] = key + '(' + getValue + ' Km)';
                            })
                        })
                        let select = $('#stops').empty()
                        if (stops.length > 0) {
                            $.each(stops, function(index, value) {
                                if (value == undefined) {

                                } else {
                                    select.append(`<option value="${index}">${value}</option>`)
                                }
                            })
                            select.prepend(`<option value="">Select Stop</option>`)
                            select.select2()
                        }
                        // console.log(get_stops, stops);

                        let stu = $('#student').empty()
                        $.each(student, function(index, value) {
                            stu.append(
                                `<option value="${value.user_name_id}">${value.name}</option>`
                            )
                        })


                        $("#save_div").show();
                        $("#degree_span").hide();
                        $("#from_span").hide();
                        $("#to_span").hide();
                        $("#loading_div").hide();
                        $("#busStudentModel").modal();
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
        })

        function changeDesignation() {
            callDesignation()
        }

        function callDesignation() {
            return new Promise((resolve, reject) => {
                $('#stops').empty()

                $('#seats').val('Loading...')
                $('#driver').val('Loading...')
                $('#bus').val('Loading...')
                $('#stops').html(`<option value="">Loading...</option>`)

                $.ajax({
                    url: "{{ route('admin.bus-student.checkDesignation') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#designation').val()
                    }
                }).done(function(response) {
                    let status = response.status;
                    $(".secondLoader").hide();
                    if (status == true) {
                        var data = response.data;
                        var student = response.student;
                        $('#seats').val(data[0].available_seats != 0 ? data[0].available_seats : 0)
                        $('#driver').val(data[0].name)
                        $('#bus').val(data[0].bus_no)
                        let get_stops = JSON.parse(data[0].stops)
                        let stops = [];
                        $.each(get_stops, function(index, value) {
                            $.each(value, function(key, getValue) {
                                stops[index] = key + '(' + getValue + ' Km)';
                            })
                        })
                        let select = $('#stops').empty()
                        if (stops.length > 0) {
                            select.append(`<option value="">Select Stop</option>`)
                            $.each(stops, function(index, value) {
                                if (value != undefined) {
                                    select.append(`<option value="${index}">${value}</option>`)
                                }
                            })
                            select.select2()
                        }
                        let stu = $('#student').empty()
                        $.each(student, function(index, value) {
                            stu.append(
                                `<option value="${value.user_name_id}">${value.name}</option>`
                            )
                        })


                        $("#save_div").show();
                        $("#degree_span").hide();
                        $("#from_span").hide();
                        $("#to_span").hide();
                        $("#loading_div").hide();
                        resolve(); // Resolve the promise when everything is done
                    } else {
                        Swal.fire('', response.data, 'error');
                        reject(new Error(
                            'Error in callDesignation')); // Reject the promise if there's an error
                    }
                }).fail(function(xhr, status, error) {
                    // Handle AJAX error
                    reject(new Error(error)); // Reject the promise with the error object
                });
            });
        }


        function saveBusStudent() {
            $("#loading_div").hide();
            if ($("#designation").val() == '') {
                $("#designation_span").html(`Designation Is Required.`);
                $("#designation_span").show();
                $("#seats_span").hide();
                $("#student_span").hide();
            } else if ($("#seats").val() == '') {
                $("#seats_span").html(`Seats Is Required.`);
                $("#seats_span").show();
                $("#designation_span").hide();
                $("#student_span").hide();
            } else if ($("#student").val() == '') {
                $("#student_span").html(`Seats Is Required.`);
                $("#student_span").show();
                $("#bus_span").hide();
                $("#seats_span").hide();

            } else {
                console.log('succ');
                $("#save_div").hide();
                $("#bus_span").hide();
                $("#loading_div").show();
                let id = $("#bus_stu_id").val();
                let designation = $("#designation").val();
                let seats = $("#seats").val();
                let stop = $("#stops").val();
                let stop_name = $("#stops option:selected").text();
                let student = $("#student").val();
                $.ajax({
                    url: "{{ route('admin.bus-student.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'designation': designation,
                        'seats': seats,
                        'stops': stop,
                        'stop_name': stop_name,
                        'student': student
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#busStudentModel").modal('hide');
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

        function viewBusStudent(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $.ajax({
                    url: "{{ route('admin.bus-student.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        let status = response.status;
                        $(".secondLoader").hide();
                        if (status == true) {
                            var data = response.data;
                            var student = response.student;
                            $('#designation').val(data.designation_id).select2()
                            $("#designation").prop('disabled', true)
                            $('#bus_stu_id').val(data.id)
                            $('#seats').val(data.available_seats)
                            $('#driver').val(data.name)
                            $('#bus').val(data.bus_no)
                            let stops = $('#stops').empty()
                            stops.html(`<option value="${data.stop_id}">${data.stop_name}</option>`).select2()

                            if (student != '') {
                                let tbody = $('#tbody').empty()
                                let i = 0;
                                $.each(student, function(index, value) {
                                    let row = $('<tr>')
                                    row.append(`<td>${i+=1}</td>`)
                                    row.append(`<td>${value.name}</td>`)
                                    row.append(`<td>${value.register_no}</td>`)
                                    tbody.append(row)
                                })
                            }
                            $('.tbody1').show()
                            $('.student').hide()
                            $("#save_div").hide();
                            $(".save_div").hide();
                            $("#to_span").hide();
                            $("#loading_div").hide();
                            $("#busStudentModel").modal();
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

        function editBusStudent(id) {
            $('#student').val('').select2()
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $("#from").prop('disabled', false)
                $.ajax({
                    url: "{{ route('admin.bus-student.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        let status = response.status;
                        $(".secondLoader").hide();
                        if (status == true) {
                            var data = response.data;
                            var student = response.student;
                            $('#bus_stu_id').val(data.id)
                            $('#designation').val(data.designation_id).select2()
                            $("#designation").prop('disabled', true)
                            callDesignation().then(() => {
                                let stop = parseInt(data.stop_id);
                                $('#stops').val(stop).select2();
                                let stu = $('#student').empty()
                                stu.append(all_student)
                                $.each(student, function(index, d) {
                                    console.log(d.user_name_id);
                                    $("#student option[value='" + d.user_name_id + "']").prop(
                                        "selected",
                                        true);
                                })
                            });
                            $('#seats').val(data.available_seats)
                            $('#driver').val(data.name)
                            $('#bus').val(data.bus_no)

                            $('#student').select2()
                            $('.tbody1').hide()
                            $('.student').show()
                            $("#save_div").show();
                            $(".save_div").show();
                            $("#degree_span").hide();
                            $("#from_span").hide();
                            $("#to_span").hide();
                            $("#loading_div").hide();
                            $(".text-danger").hide();
                            $("#busStudentModel").modal();
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

        function deleteBusStudent(id) {
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
                        $(".secondLoader").show();
                        $.ajax({
                            url: "{{ route('admin.bus-student.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $(".secondLoader").hide();
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
