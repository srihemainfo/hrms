@extends('layouts.admin')
@section('content')
    @can('academic_year_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    {{ trans('global.add') }} {{ trans('cruds.academicYear.title_singular') }}
                </button>
            </div>
        </div>
    @endcan
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

        .select2 {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Academic Year List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AcademicYear text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            AY
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

    <div class="modal fade" id="ayModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="hidden" name="ay_id" id="ay_id" value="">
                                <label class="required" for="from">From Year</label>
                                <select class="form-control select2" name="from" id="from"
                                    onchange="addToYear(this)">
                                    <option value="">Select From Year</option>
                                    @foreach ($year as $id => $entry)
                                        <option value="{{ $entry }}" {{ old('from') == $entry ? 'selected' : '' }}>
                                            {{ $entry }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="from_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="form-group">
                                <label class="required" for="to">To Year</label>
                                <input class="form-control" type="text" name="to" id="to" value=""
                                    disabled>
                                <span id="to_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveAy()">Save</button>
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
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    className: 'btn-outline-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            Swal.fire('', '{{ trans('global.datatables.zero_selected') }}', 'warning');

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
                                        url: config.url,
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
            if ($.fn.DataTable.isDataTable('.datatable-AcademicYear')) {
                $('.datatable-AcademicYear').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.academic-years.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'academic_year'
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
            let table = $('.datatable-AcademicYear').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function addToYear(element) {
            if ($(element).val() != '') {
                $("#from_span").hide()
                var toYear = parseInt($(element).val()) + 1;
                $("#to").val(toYear)
            } else {
                $("#from_span").html(`From Year Is Required`);
                $("#from_span").show()
                $("#to").val('')
            }
        }

        function currentStatus(id, element) {
            let status = 0;
            $(".secondLoader").show();
            if ($(element). == 0) {
                $(element).data('id', 1)
                status = 1;
            } else {
                $(element).data('id', 0)
            }
            $.ajax({
                url: '{{ route('admin.academic-years.change-status') }}',
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                    'status': status
                },
                success: function(response) {
                    $(".secondLoader").hide();
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

        function openModal() {
            $("#ay_id").val('');
            $("#name").val('');
            $("#from").val('').prop("disabled", false);
            $("#from").select2()
            $("#to").val('');
            $("#ay_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#ayModel").modal();
        }

        function saveAy() {
            $("#loading_div").hide();
            if ($("#from").val() == '') {
                $("#from_span").html(`From Year Is Required.`);
                $("#from_span").show();
            } else {
                $("#save_div").hide();
                $("#from_span").hide();
                $("#loading_div").show();
                let to = $("#to").val();
                let from = $("#from").val();
                let id = $("#ay_id").val();
                $.ajax({
                    url: "{{ route('admin.academic-years.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'from': from,
                        'to': to,
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#ayModel").modal('hide');
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

        function viewAy(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.academic-years.view') }}",
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
                            $("#ay_id").val(data.id);
                            $("#from").val(data.from).prop("disabled", true);
                            $("#from").select2()
                            $("#to").val(data.to);
                            $("#name").val(data.name);
                            $("#save_div").hide();
                            $("#ay_span").hide();
                            $("#loading_div").hide();
                            $("#ayModel").modal();
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

        function editAy(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $.ajax({
                    url: "{{ route('admin.academic-years.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $(".secondLoader").hide();
                        // $("#from").prop('disable', false)
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            $("#ay_id").val(data.id);
                            $("#name").val(data.name);
                            $("#from").val(data.from).prop('disabled', false);
                            $('#from').select2()
                            $("#to").val(data.to);
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#ay_span").hide();
                            $("#loading_div").hide();
                            $("#ayModel").modal();
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

        function deleteAy(id) {

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
                            url: "{{ route('admin.academic-years.delete') }}",
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
