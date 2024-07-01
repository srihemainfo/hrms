@extends('layouts.admin')
@section('content')
<style>
    .select2-container
    {
        width: 100% !important;
    }
</style>
    @can('nationality_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <btn class="btn btn-outline-success" onclick="openModal()">
                    Create Fee Components
                    </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            <p>Fees Components List</p>
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Nationality text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.nationality.fields.id') }}
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            {{ trans('cruds.nationality.fields.name') }}
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

    <div class="modal fade" id="feeComponentsModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Batch</label>
                            <select class="form-control select2" id="applied_batch" name="applied_batch">
                                <option value="">Select Batch</option>
                                @foreach ($batch as $id => $b)
                                    <option value="{{ $id }}">{{ $b }}</option>
                                @endforeach
                            </select>
                            <span id="applied_batch_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Course</label>
                            <input type="hidden" id="feeStructure_id" value="">
                            <select class="form-control select2" style="text-transform:uppercase" id="course"
                                name="course" value="">
                                <option value="">Select Course</option>
                                @foreach ($course as $id => $d)
                                    <option value="{{ $id }}">{{ $d }}</option>
                                @endforeach
                            </select>
                            <span id="course_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Semester</label>
                            <select class="form-control select2" id="semester" name="semester">
                                <option value="">Select Semester</option>
                                @foreach ($semester as $id => $sem)
                                    <option value="{{ $id }}">{{ $sem }}</option>
                                @endforeach
                            </select>
                            <span id="semester_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="result" class="required">Fees Components</label>
                            <input type="hidden" name="fee_components_id" id="fee_components_id" value="">
                            <input type="text" class="form-control" id="fee_components" name="fee_components"
                                value="">
                            <span id="fee_components_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveFeecomponents()">Save</button>
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
            @can('year_delete')
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
                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: "{{ route('admin.fee-components.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-Nationality')) {
                $('.datatable-Nationality').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.fee-components.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'batch',
                        name: 'batch'
                    },
                    {
                        data: 'course',
                        name: 'course'
                    },
                    {
                        data: 'semester',
                        name: 'semester'
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
            let table = $('.datatable-Nationality').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#fee_components").val('')
            $("#applied_batch").val('').select2()
            $("#course").val('').select2()
            $("#fee_components_id").val('')
            $("#semester").val('').select2()
            $("#fee_components_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#feeComponentsModel").modal();
        }

        function saveFeecomponents() {
            $("#loading_div").hide();
            $("#applied_batch_span").hide();
            $("#course_span").hide();
            $("#semester_span").hide();

            if($("#applied_batch").val()== '')
            {
                $("#applied_batch_span").html('Batch Is Required').show();
            }
            else if($("#course").val()== '')
            {
               $("#course_span").html('Course Is Required').show();
            }
            else if($("#semester").val()=='')
            {
                $("#semester_span").html('Semester Is Required').show();
            }
            else if ($("#fee_components").val() == '') {
                $("#fee_components_span").html(`Fees Components Is Required.`);
                $("#fee_components_span").show();
            } else if (!isNaN($("#fee_components").val())) {
                $("#fee_components_span").html(`Only Alphabet.`);
                $("#fee_components_span").show();
            } else {
                $("#save_div").hide();
                $("#fee_components_span").hide();
                $("#applied_batch_span").hide();
                $("#semester_span").hide();
                $("#course_span").hide();
                $("#loading_div").show();

                let course = $("#course").val();
                let fee_components = $("#fee_components").val();
                let id = $("#fee_components_id").val();
                let applied_batch = $("#applied_batch").val();
                let semester = $("#semester").val();
                $.ajax({
                    url: '{{ route('admin.fee-components.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'fee_components': fee_components,
                        'id': id,
                        'course': course,
                        'applied_batch' : applied_batch,
                        'semester' : semester
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#feeComponentsModel").modal('hide');
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

        function viewFeeComp(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.fee-components.view') }}",
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
                            console.log(data)
                            
                            $("#fee_components").val(data.name);
                            $("#fee_components_id").val(data.id);
                            $("#course").val(data.course_id).select2();
                            $("#applied_batch").val(data.batch_id).select2();;
                            $("#semester").val(data.semester_id).select2();;
                            $("#save_div").hide();
                            $("#fee_components_span").hide();
                            $("#loading_div").hide();
                            $("#feeComponentsModel").modal();
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

        function editFeeComp(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.fee-components.edit') }}",
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
                            console.log(data.id);
                            $("#fee_components_id").val(data.id);
                            $("#fee_components").val(data.name);
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#fee_components_span").hide();
                            $("#loading_div").hide();
                            $("#feeComponentsModel").modal();
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

        function deleteFeeComp(id) {
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
                        $('.secondLoader').show()
                        $.ajax({
                            url: "{{ route('admin.fee-components.delete') }}",
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
