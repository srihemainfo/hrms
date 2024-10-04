@extends('layouts.admin')
@section('content')
    @can('route_allot_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Route Allot
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
            Route Allot
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RouteAllot text-center">
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
                            Driver Name
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Driver Phone
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
    <div class="modal fade" id="routeAllotModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters" id="gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="bus" class="required">Bus</label>
                            <input type="hidden" name="route_allot_id" id="route_allot_id" value="">
                            <select class="form-control select2" name="bus" id="bus">
                                <option value="">Select Bus</option>
                                @foreach ($bus as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="bus_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="designation" class="required">Designation</label>
                            <select class="form-control select2" name="designation" id="designation">
                                <option value="">Select Designation</option>
                                @foreach ($bus_route as $key => $route)
                                    <option value="{{ $key }}">{{ $route }}</option>
                                @endforeach
                            </select>
                            <span id="designation_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="driver" class="required">Driver</label>
                            <select class="form-control select2" name="driver" id="driver">
                                <option value="">Select Driver</option>
                                @foreach ($driver as $key => $item)
                                    <option value="{{ $item->user_name_id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <span id="driver_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <span id="error_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                        <button type="button" class="btn btn-outline-success" onclick="saveRouteAllot()">Save</button>
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
            callAjax()
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('bus_route_delete')
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
                                        url: "{{ route('admin.route-allot.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-RouteAllot')) {
                $('.datatable-RouteAllot').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.route-allot.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'bus',
                        name: 'bus'
                    },
                    {
                        data: 'driver',
                        name: 'driver'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
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
            let table = $('.datatable-RouteAllot').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {

            $("#route_allot_id").val('')
            $("#bus").val('')
            $("#designation").val('');
            $("#driver").val('');
            $("#bus").select2()
            $("#driver").select2()
            $("#designation").select2()
            $("#bus_span").hide();
            $("#designation_span").hide();
            $("#driver_span").hide();
            $("#loading_div").hide();
            $(".save_div").show();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#routeAllotModel").modal();
        }

        // function checkValidation() {

        //     let count = $('#count').val()

        //     if ($('#bus').val() == '') {
        //         $("#bus_span").html(`bus is Required.`);
        //         $('#bus_span').show()
        //         $('#km_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if ($('#km').val() == '') {
        //         $("#km_span").html(`Total Km is Required.`);
        //         $('#km_span').show()
        //         $('#bus_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if (isNaN($('#km').val())) {
        //         $("#km_span").html(`Total Km Only in Number.`);
        //         $('#km_span').show()
        //         $('#bus_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if ($('#count').val() != '') {
        //         for (let i = 1; count >= i; i++) {
        //             let driver = 'driver' + i
        //             let km = 'km' + i
        //             let stopspan = 'driver' + i + '_span'
        //             let kmspan = 'km' + i + '_span'

        //             if ($('#' + driver).val() == '') {
        //                 $('.text-danger').hide()
        //                 $('#' + stopspan).html(`Stop Name Required.`);
        //                 $('#' + stopspan).show()
        //                 $('#bus_span').hide()
        //                 $('#error_span').hide()
        //                 return false;

        //             } else if ($('#' + km).val() == '') {
        //                 $('.text-danger').hide()
        //                 $('#' + kmspan).html(`Stop Km Required.`);
        //                 $('#' + kmspan).show()
        //                 $('#bus_span').hide()
        //                 $('#error_span').hide()
        //                 return false;

        //             } else if (isNaN($("#" + km).val())) {
        //                 $('.text-danger').hide()
        //                 $('#' + kmspan).html(`Stop Km Only in Number.`);
        //                 $('#' + kmspan).show()
        //                 $('#bus_span').hide()
        //                 $('#error_span').hide()
        //                 return false;

        //             }
        //         }
        //         console.log('hello');
        //         return true;

        //     }
        // }

        function saveRouteAllot() {
            $("#loading_div").hide();
            if ($("#bus").val() == '') {
                $("#bus_span").html(`Bus Is Required.`);
                $("#bus_span").show();
                $("#designation_span").hide();
                $("#driver_span").hide();
            } else if ($("#designation").val() == '') {
                $("#designation_span").html(`Designation Is Required.`);
                $("#designation_span").show();
                $("#bus_span").hide();
                $("#driver_span").hide();
            } else if ($("#driver").val() == '') {
                $("#driver_span").html(`Driver Is Required.`);
                $("#driver_span").show();
                $("#bus_span").hide();
                $("#designation_span").hide();
            } else {
                $("#save_div").hide();
                $("#driver_span").hide();
                $("#bus_span").hide();
                $("#designation_span").hide();
                $("#loading_div").show();
                let id = $("#route_allot_id").val();
                let bus = $("#bus").val()
                let designation = $("#designation").val();
                let driver = $("#driver").val();
                $.ajax({
                    url: "{{ route('admin.route-allot.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'bus': bus,
                        'designation': designation,
                        'driver': driver
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#routeAllotModel").modal('hide');
                        callAjax();
                    }
                })
            }
        }


        function viewRouteAllot(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $.ajax({
                    url: "{{ route('admin.route-allot.view') }}",
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
                            let data = response.data;
                            $('#bus').val(data.bus_id)
                            $('#driver').val(data.driver_id)
                            $('#designation').val(data.designation_id)
                            $("#bus").select2()
                            $("#driver").select2()
                            $("#designation").select2()
                            $("#save_div").hide();
                            $(".save_div").hide();
                            $("#degree_span").hide();
                            $("#from_span").hide();
                            $("#to_span").hide();
                            $("#loading_div").hide();
                            $("#routeAllotModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }

        function editRouteAllot(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $("#from").prop('disabled', false)
                $.ajax({
                    url: "{{ route('admin.route-allot.edit') }}",
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
                            let data = response.data;
                            $('#bus').val(data.bus_id)
                            $('#driver').val(data.driver_id)
                            $('#designation').val(data.designation_id)
                            $("#bus").select2()
                            $("#driver").select2()
                            $("#designation").select2()
                            $("#save_div").show();
                            $(".save_div").show();
                            $("#degree_span").hide();
                            $("#from_span").hide();
                            $("#to_span").hide();
                            $("#loading_div").hide();
                            $(".text-danger").hide();
                            $("#routeAllotModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }

        function deleteRouteAllot(id) {
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
                            url: "{{ route('admin.route-allot.delete') }}",
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
                            }
                        })
                    }
                })
            }
        }
    </script>
@endsection
