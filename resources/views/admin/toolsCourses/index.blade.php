@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    @can('tools_course_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Course
                </button>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Course List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ToolsCourse text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Shift
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Degree Type
                        </th>
                        <th>
                            Course Name
                        </th>
                        <th>Short Form</th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div>
    <div class="modal fade" id="toolsCourseModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="dept" class="required">Department</label>
                            <select name="dept" id="dept" class="form-control select2">
                                <option value="">Select Department</option>
                                @foreach ($dept as $id => $dpt)
                                    <option value="{{ $id }}">{{ $dpt }}</option>
                                @endforeach
                            </select>
                            <span id="dept_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="degree" class="required">Degree Type</label>
                            <select name="degree" id="degree" class="form-control select2">
                                <option value="">Select Degree Type</option>
                                @foreach ($degree as $id => $d)
                                    <option value="{{ $id }}">{{ $d }}</option>
                                @endforeach
                            </select>
                            <span id="degree_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="course" class="required">Course Name</label>
                            <input type="hidden" name="course_id" id="course_id" value="">
                            <input type="text" class="form-control" style="text-transform:uppercase" id="course"
                                name="course" value="">
                            <span id="course_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="short_form" class="required">Course In Short Form</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="short_form"
                                name="short_form" value="">
                            <span id="short_form_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveCourse()">Save</button>
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
            @can('tools_course_delete')
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
                                        url: "{{ route('admin.tools-courses.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        $(".secondLoader").hide();
                                        Swal.fire('', response.data, response.status);
                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-ToolsCourse')) {
                $('.datatable-ToolsCourse').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.tools-courses.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'shift',
                        name: 'shift'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'degree_type',
                        name: 'degree_type'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'short_form',
                        name: 'short_form'
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
            let table = $('.datatable-ToolsCourse').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#course_id").val('')
            $("#dept").val('').select2()
            $("#degree").val('').select2()
            $("#course").val('')
            $("#short_form").val('')
            $("#course_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#toolsCourseModel").modal();
        }

        function saveCourse() {
            $("#loading_div").hide();
            if ($("#course").val() == '') {
                $("#course_span").html(`Course Is Required.`);
                $("#course_span").show();
                $("#dept_span").hide();
                $("#degree_span").hide();
                $("#short_form_span").hide();
            } else if ($("#degree").val() == '') {
                $("#degree_span").html(`Degree Type Is Required.`);
                $("#degree_span").show();
                $("#dept_span").hide();
                $("#course_span").hide();
                $("#short_form_span").hide();
            } else if ($("#dept").val() == '') {
                $("#dept_span").html(`Department Is Required.`);
                $("#dept_span").show();
                $("#degree_span").hide();
                $("#short_form_span").hide();
                $("#course_span").hide();
            } else if ($("#short_form").val() == '') {
                $("#short_form_span").html(`Short Form Is Required.`);
                $("#short_form_span").show();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#degree_span").hide();
            } else {
                $("#save_div").hide();
                $("#course_span").hide();
                $("#short_form_span").hide();
                $("#dept_span").hide();
                $("#degree_span").hide();
                $("#loading_div").show();
                let id = $("#course_id").val();
                let course = $("#course").val();
                let dept = $("#dept").val();
                let degree = $("#degree").val();
                let short_form = $("#short_form").val();
                $.ajax({
                    url: "{{ route('admin.tools-courses.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'course': course,
                        'short_form': short_form,
                        'degree': degree,
                        'dept': dept
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#toolsCourseModel").modal('hide');
                        callAjax();
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

        function viewCourse(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $.ajax({
                    url: "{{ route('admin.tools-courses.view') }}",
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
                            $("#course_id").val(data.id);
                            $("#course").val(data.name);
                            $("#short_form").val(data.short_form);
                            $("#dept").val(data.department_id).select2();
                            $("#degree").val(data.degree_type_id).select2();
                            $("#save_div").hide();
                            $("#course_span").hide();
                            $("#short_form_span").hide();
                            $("#loading_div").hide();
                            $("#toolsCourseModel").modal();
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

        function editCourse(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $.ajax({
                    url: "{{ route('admin.tools-courses.edit') }}",
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
                            $("#course_id").val(data.id);
                            $("#course").val(data.name);
                            $("#short_form").val(data.short_form);
                            $("#dept").val(data.department_id).select2();
                            $("#degree").val(data.degree_type_id).select2();
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#course_span").hide();
                            $("#short_form_span").hide();
                            $("#loading_div").hide();
                            $("#toolsCourseModel").modal();
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

        function deleteCourse(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
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
                        $(".secondLoader").hide();
                        $.ajax({
                            url: "{{ route('admin.tools-courses.delete') }}",
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
                })
            }
        }
    </script>
@endsection
