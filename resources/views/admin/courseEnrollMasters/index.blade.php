@extends('layouts.admin')
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-outline-success" onclick="openModal()">
                Generate Enroll Master
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Enroll Master List
        </div>
        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-CourseEnrollMaster text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Course Enroll Master
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
    <div class="modal fade" id="enrollModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="degree_type" class="required">Degree Types</label>
                            <input type="hidden" name="batch_id" id="batch_id" value="">
                            <select name="degree_type" id="degree_type" class="form-control select2"
                                onchange="clearYears()">
                                <option value="">Select Degree Type</option>
                                @foreach ($degreeTypes as $id => $degree)
                                    <option value="{{ $degree }}">{{ $degree }}</option>
                                @endforeach
                            </select>
                            <span id="degree_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="from" class="required">From Year</label>
                            <input type="hidden" name="enroll_id" id="enroll_id" value="">
                            <select name="from" id="from" class="form-control select2" onchange="addToYear(this)">
                                <option value="">Select Year</option>
                                @foreach ($years as $data)
                                    <option value="{{ $data->year }}">{{ $data->year }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control" style="text-transform:uppercase; display: none;"
                                id="enroll" name="enroll" value="">
                            <span id="from_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12 form-group">
                            <label for="to" class="required">To Year</label>
                            <input type="text" class="form-control" id="to" name="to" value=""
                                disabled>
                            <span id="to_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="generateEnroll()">Save</button>
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
            @can('course_enroll_master_delete')
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
                                        url: "{{ route('admin.course-enroll-masters.massDestroy') }}",
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
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-CourseEnrollMaster')) {
                $('.datatable-CourseEnrollMaster').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.course-enroll-masters.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'enroll_master_number',
                        name: 'enroll_master_number'
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
            let table = $('.datatable-CourseEnrollMaster').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        };

        function openModal() {
            $("#from").prop('disabled', false)
            $("#batch_id").val('')
            $("#degree_type").val($("#target option:first").val())
            $("#degree_type").select2()
            $("#from").val($("#target option:first").val())
            $("#from").select2();
            $("#to").val('')
            $("#degree_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Generate`);
            $("#save_div").show();
            $("#enrollModel").modal();
        }

        function clearYears() {
            $("#from").val('')
            $("#from").select2()
            $("#to").val('')
        }

        function addToYear(element) {
            if ($("#degree_type").val() != '') {
                var toYear = 0;
                if ($(element).val() != '') {
                    $("#from_span").hide()
                    if ($("#degree_type").val() == 'UG') {
                        toYear = parseInt($(element).val()) + 4;
                    } else {
                        toYear = parseInt($(element).val()) + 2;
                    }
                    $("#to").val(toYear)
                } else {
                    $("#from_span").html(`From Year Is Required`);
                    $("#from_span").show()
                    $("#to").val('')
                }
            } else {
                $("#degree_span").html(`Degree Type Is Required.`);
                $("#degree_span").show();
            }
        }

        function generateEnroll() {

            $("#loading_div").hide();
            if ($("#degree_type").val() == '') {
                $("#degree_span").html(`Degree Type Is Required.`);
                $("#degree_span").show();
                $("#from_span").hide();
                $("#to_span").hide();
            } else if ($("#from").val() == '') {
                $("#from_span").html(`From Year Is Required.`);
                $("#from_span").show();
                $("#to_span").hide();
                $("#degree_span").hide();

            } else if (isNaN($("#from").val())) {
                $("#from_span").html(`From Year Is Not a Number.`);
                $("#from_span").show();
                $("#to_span").hide();
                $("#degree_span").hide();

            } else if ($("#to").val() == '') {
                $("#to_span").html(`To Year Is Required.`);
                $("#to_span").show();
                $("#from_span").hide();
                $("#degree_span").hide();

            } else if (isNaN($("#to").val())) {
                $("#to_span").html(`To Year Is Not a Number.`);
                $("#to_span").show();
                $("#from_span").hide();
                $("#degree_span").hide();

            } else {
                $("#save_div").hide();
                $("#from_span").hide();
                $("#to_span").hide();
                $("#degree_span").hide();

                $("#loading_div").show();
                let from = $("#from").val();
                let to = $("#to").val();
                let degree_type = $("#degree_type").val()
                $.ajax({
                    url: "{{ route('admin.course-enroll-masters.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'from': from,
                        'to': to,
                        'degree_type': degree_type
                    },
                    success: function(response) {
                        $("#enrollModel").modal('hide');
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
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





        function deleteEnroll(id) {
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
                }).then(function(credit) {
                    if (credit.value) {
                        $('.secondLoader').show()
                        $.ajax({
                            url: "{{ route('admin.course-enroll-masters.delete') }}",
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
