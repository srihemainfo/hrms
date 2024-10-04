@extends('layouts.admin')
@section('content')
    <style>
        .toggle-wrapper {
            display: inline-block;
            position: relative;
            border-radius: 3.125em;
            overflow: hidden;
        }

        .toggle-checkbox {
            -webkit-appearance: none;
            appearance: none;
            position: absolute;
            z-index: 1;
            top: 0;
            left: 0;
            border-radius: inherit;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .toggle-container {
            display: flex;
            position: relative;
            border-radius: inherit;
            width: 3em;
            height: 1.5em;
            background-color: #d1d4dc;
            box-shadow: inset 0.0625em 0 0 #d4d2de, inset -0.0625em 0 0 #d4d2de, inset 0.125em 0.25em 0.125em 0.25em #b5b5c3;
            mask-image: radial-gradient(#fff, #000);
            transition: all 0.4s;
        }

        .toggle-wrapper.blue>.toggle-checkbox:checked+.toggle-container {
            background-color: #204ad4;
            box-shadow: inset 0.0625em 0 0 #1a45d6, inset -0.0625em 0 0 #1e4ade, inset 0.125em 0.25em 0.125em 0.25em #203785;
        }

        .toggle-ball {
            position: relative;
            border-radius: 50%;
            width: 1.5em;
            height: 1.5em;
            background-image: radial-gradient(rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0) 16%), radial-gradient(#d2d4dc, #babac2);
            background-position: -0.25em -0.25em;
            background-size: auto, calc(100% + 0.25em) calc(100% + 0.25em);
            background-repeat: no-repeat;
            box-shadow: 0.25em 0.25em 0.25em #8d889e, inset 0.0625em 0.0625em 0.25em #d1d1d6, inset -0.0625em -0.0625em 0.25em #8c869e;
            transition: transform 0.4s, box-shadow 0.4s;
        }

        .toggle-ball::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 50%;
            width: 100%;
            height: 100%;
            background-position: -0.25em -0.25em;
            background-size: auto, calc(100% + 0.25em) calc(100% + 0.25em);
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .toggle-wrapper.blue>.toggle-container>.toggle-ball::after {
            background-image: radial-gradient(rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0) 16%), radial-gradient(#143bba, #002397);
            box-shadow: 0.25em 0.25em 0.25em #02238f, inset 0.0625em 0.0625em 0.25em #8190c0, inset -0.0625em -0.0625em 0.25em #010029;

        }

        .toggle-wrapper>.toggle-checkbox:checked+.toggle-container>.toggle-ball::after {
            opacity: 1;
        }

        .toggle-checkbox:checked+.toggle-container>.toggle-ball {
            transform: translateX(100%);
        }
    </style>
    @can('semester_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Semester
                </button>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Semester List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Semester text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            Status
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
    <div class="modal fade" id="semesterModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="semester" class="required">Semester</label>
                            <input type="hidden" name="sem_id" id="sem_id" value="">
                            <input type="text" class="form-control" style="text-transform:uppercase" id="semester"
                                name="semester" value="">
                            <span id="sem_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveSem()">Save</button>
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
            @can('semester_delete')
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
                                        url: "{{ route('admin.semesters.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-Semester')) {
                $('.datatable-Semester').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.semesters.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'semester',
                        name: 'semester'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            let status = '';
                            if (data.status == 1) {
                                status = 'checked';
                            }
                            let statusBtn =
                                `<div class="toggle-wrapper blue text-center" >
                                     <input class="toggle-checkbox" type="checkbox" class="toggleData" data-id="${data.status}" ${status} onchange="currentStatus(${data.id},this)" />
                                     <div class="toggle-container">
                                        <div class="toggle-ball"></div>
                                     </div>
                                 </div>`;
                            return statusBtn;
                        },
                        type: 'html'
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
            let table = $('.datatable-Semester').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function currentStatus(id, element) {
            let status = 0;

            if ($(element).data('id') == 0) {
                $(element).data('id', 1)
                status = 1;
            } else {
                $(element).data('id', 0)
            }
            $.ajax({
                url: '{{ route('admin.semesters.change-status') }}',
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                    'status': status
                },
                success: function(response) {
                    let status = response.status;
                    let data = response.data;
                    if (status == true) {
                        Swal.fire('', data, 'success');
                    } else {
                        Swal.fire('', data, 'error');
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

        function openModal() {
            $("#semester").val('')
            $("#sem_id").val('')
            $("#sem_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#semesterModel").modal();
        }

        function saveSem() {
            $("#loading_div").hide();
            if ($("#semester").val() == '') {
                $("#sem_span").html(`Semester Is Required.`);
                $("#sem_span").show();
            } else if (isNaN($("#semester").val())) {
                $("#sem_span").html(`Semester Is Not a Number.`);
                $("#sem_span").show();
            } else {
                $("#save_div").hide();
                $("#sem_span").hide();
                $("#loading_div").show();
                let semester = $("#semester").val();
                let id = $("#sem_id").val();
                $.ajax({
                    url: "{{ route('admin.semesters.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'semester': semester,
                        'id': id
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#semesterModel").modal('hide');
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

        function viewSemester(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $.ajax({
                    url: "{{ route('admin.semesters.view') }}",
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
                            $("#semester").val(data.semester);
                            $("#save_div").hide();
                            $("#sem_span").hide();
                            $("#loading_div").hide();
                            $("#semesterModel").modal();
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

        function editSemester(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $.ajax({
                    url: "{{ route('admin.semesters.edit') }}",
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
                            $("#sem_id").val(data.id);
                            $("#semester").val(data.semester);
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#sem_span").hide();
                            $("#loading_div").hide();
                            $("#semesterModel").modal();
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

        function deleteSemester(id) {
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
                            url: "{{ route('admin.semesters.delete') }}",
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
