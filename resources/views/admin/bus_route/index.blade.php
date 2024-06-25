@extends('layouts.admin')
@section('content')
    @can('bus_route_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Bus Route
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
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-BusRoute text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Total Km
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
    <div class="modal fade" id="busRouteModel" role="dialog">
        <form id="myForm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters" id="gutters">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="designation" class="required">Designation</label>
                                <input type="hidden" name="bus_route_id" id="bus_route_id" value="">
                                <input type="text" class="form-control" name="designation" id="designation">
                                <span id="designation_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="km" class="required">Total Km</label>
                                <input type="text" class="form-control" name="km" id="km">
                                <span id="km_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="dynamic d-flex" style="flex-wrap: wrap;">
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                    <label for="stops1" class="required">Stop Name</label>
                                    <input type="text" class="form-control stop" name="stops1" id="stops1">
                                    <span id="stops1_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                    <label for="km1" class="required">Stop Km</label>
                                    <input type="text" class="form-control km" name="km1" id="km1">
                                    <span id="km1_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                    <label for="stops2" class="required">Stop Name</label>
                                    <input type="text" class="form-control stop" name="stops2" id="stops2">
                                    <span id="stops2_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                    <label for="km2" class="required">Stop Km</label>
                                    <input type="text" class="form-control km" name="km2" id="km2">
                                    <span id="km2_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>
                            </div>

                            <input type="hidden" name="count" id="count" value="2">
                        </div>
                        <div class="row mr-3" style="float: right">
                            <button type="button" class="newPrimaryBtn add_stop save_div text-right" id="add_stop"
                                style="font-size: 1.7rem; padding-top: 30px !important;" title="Add Stops"><i
                                    class="far fa-plus-square"></i></button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="save_div">
                            <span id="error_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                            <button type="submit" class="btn btn-outline-success">Save</button>
                        </div>
                        <div id="loading_div">
                            <span class="theLoader"></span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                                        url: "{{ route('admin.bus-route.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-BusRoute')) {
                $('.datatable-BusRoute').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.bus-route.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'km',
                        name: 'km'
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
            let table = $('.datatable-BusRoute').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $('.dynamic').remove()
            let row = $('#gutters')
            row.append(`<div class="dynamic d-flex" style="flex-wrap: wrap;">
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                    <label for="stops1" class="required">Stop Name</label>
                                    <input type="text" class="form-control stop" name="stops1" id="stops1">
                                    <span id="stops1_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                    <label for="km1" class="required">Stop Km</label>
                                    <input type="text" class="form-control" name="km1" id="km1">
                                    <span id="km1_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                    <label for="stops2" class="required">Stop Name</label>
                                    <input type="text" class="form-control stop" name="stops2" id="stops2">
                                    <span id="stops2_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                    <label for="km2" class="required">Stop Km</label>
                                    <input type="text" class="form-control" name="km2" id="km2">
                                    <span id="km2_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>
                            </div>`)

            $("#bus_route_id").val('')
            $("#designation").val('')
            $("#km").val('');
            $("#designation_span").hide();
            $("#km_span").hide();
            $("#loading_div").hide();
            $(".save_div").show();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#busRouteModel").modal();
        }



        $('#add_stop').click(function() {
            let stopCount = $('.stop').length;
            let row2 = $('.dynamic')
            row2.append(`
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="stops${stopCount+=1}" class="required">Stop Name</label>
                                <input type="text" class="form-control stop" name="stops${stopCount}" id="stops${stopCount}">
                                <span id="stops${stopCount}_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="km${stopCount}" class="required">Stop Km</label>
                                <input type="text" class="form-control km" name="km${stopCount}" id="km${stopCount}">
                                <span id="km${stopCount}_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
            `)
            $('#count').val(stopCount)
            stopCount = 0;

        })


        // function checkValidation(event) {
        //     // event.preventDefault();
        //     let count = $('#count').val()
        //     console.log(count);
        //     if ($('#designation').val() == '') {
        //         $("#designation_span").html(`Designation is Required.`);
        //         $('#designation_span').show()
        //         $('#km_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if ($('#km').val() == '') {
        //         $("#km_span").html(`Total Km is Required.`);
        //         $('#km_span').show()
        //         $('#designation_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if (isNaN($('#km').val())) {
        //         $("#km_span").html(`Total Km Only in Number.`);
        //         $('#km_span').show()
        //         $('#designation_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if ($('#count').val() != '') {
        //         for (let i = 1; count >= i; i++) {
        //             let stops = 'stops' + i
        //             let km = 'km' + i
        //             let stopspan = 'stops' + i + '_span'
        //             let kmspan = 'km' + i + '_span'

        //             if ($('#' + stops).val() == '') {
        //                 $('.text-danger').hide()
        //                 $('#' + stopspan).html(`Stop Name Required.`);
        //                 $('#' + stopspan).show()
        //                 $('#designation_span').hide()
        //                 $('#error_span').hide()
        //                 return false;

        //             } else if ($('#' + km).val() == '') {
        //                 $('.text-danger').hide()
        //                 $('#' + kmspan).html(`Stop Km Required.`);
        //                 $('#' + kmspan).show()
        //                 $('#designation_span').hide()
        //                 $('#error_span').hide()
        //                 return false;

        //             } else if (isNaN($("#" + km).val())) {
        //                 $('.text-danger').hide()
        //                 $('#' + kmspan).html(`Stop Km Only in Number.`);
        //                 $('#' + kmspan).show()
        //                 $('#designation_span').hide()
        //                 $('#error_span').hide()
        //                 return false;
        //             }
        //         }
        //         $('#loading_div').show()
        //         $('#save_div').hide()
        //         let data = $(this).serialize()
        //         console.log(data);
        //         var formDataObject = {};
        //         data.split('&').forEach(function(keyValue) {
        //             var pair = keyValue.split('=');
        //             formDataObject[pair[0]] = decodeURIComponent(pair[1] || '');
        //         });

        //         console.log(JSON.stringify(formDataObject));
        //         // $.ajax({
        //         //     url: "{{ route('admin.bus-route.store') }}",
        //         //     method: 'POST',
        //         //     headers: {
        //         //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         //     },
        //         //     data: {
        //         //         'request': JSON.stringify(formDataObject)
        //         //     },
        //         //     success: function(response) {
        //         //         let status = response.status
        //         //         let data = response.data
        //         //         if (status == true) {
        //         //             Swal.fire('', data, 'success')
        //         //             location.reload()
        //         //         } else {
        //         //             Swal.fire('', data, 'error')
        //         //         }
        //         //         $('#loading_div').hide()
        //         //         $('#save_div').show()
        //         //     }
        //         // })

        //     }
        // }

        $('#myForm').submit(function(event) {

            event.preventDefault();
            let count = $('#count').val()
            console.log(count);
            if ($('#designation').val() == '') {
                $("#designation_span").html(`Designation is Required.`);
                $('#designation_span').show()
                $('#km_span').hide()
                $('#error_span').hide()

                return false;

            } else if ($('#km').val() == '') {
                $("#km_span").html(`Total Km is Required.`);
                $('#km_span').show()
                $('#designation_span').hide()
                $('#error_span').hide()

                return false;

            } else if (isNaN($('#km').val())) {
                $("#km_span").html(`Total Km Only in Number.`);
                $('#km_span').show()
                $('#designation_span').hide()
                $('#error_span').hide()

                return false;

            } else if ($('#count').val() != '') {
                for (let i = 1; count >= i; i++) {
                    let stops = 'stops' + i
                    let km = 'km' + i
                    let stopspan = 'stops' + i + '_span'
                    let kmspan = 'km' + i + '_span'

                    if ($('#' + stops).val() == '') {
                        $('.text-danger').hide()
                        $('#' + stopspan).html(`Stop Name Required.`);
                        $('#' + stopspan).show()
                        $('#designation_span').hide()
                        $('#error_span').hide()
                        console.log('3');

                        return false;

                    } else if ($('#' + km).val() == '') {
                        $('.text-danger').hide()
                        $('#' + kmspan).html(`Stop Km Required.`);
                        $('#' + kmspan).show()
                        $('#designation_span').hide()
                        $('#error_span').hide()
                        console.log('2');

                        return false;

                    } else if (isNaN($("#" + km).val())) {
                        $('.text-danger').hide()
                        $('#' + kmspan).html(`Stop Km Only in Number.`);
                        $('#' + kmspan).show()
                        $('#designation_span').hide()
                        $('#error_span').hide()
                        console.log('1');

                        return false;
                    }
                }
                $('#loading_div').show()
                $('#save_div').hide()
                $('#designation_span').hide()
                $('#error_span').hide()
                let data = $(this).serialize()
                console.log('hii');
                var formDataObject = {};
                data.split('&').forEach(function(keyValue) {
                    var pair = keyValue.split('=');
                    formDataObject[pair[0]] = decodeURIComponent(pair[1] || '');
                });

                console.log(JSON.stringify(formDataObject));
                $.ajax({
                    url: "{{ route('admin.bus-route.store') }}",
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
                        } else {
                            Swal.fire('', data, 'error')
                        }
                        $('#loading_div').hide()
                        $('#save_div').show()
                        $('#busRouteModel').modal('hide')
                        callAjax()
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


        // function vv() {

        //     let count = $('#count').val()

        //     if ($('#designation').val() == '') {
        //         $("#designation_span").html(`Designation is Required.`);
        //         $('#designation_span').show()
        //         $('#km_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if ($('#km').val() == '') {
        //         $("#km_span").html(`Total Km is Required.`);
        //         $('#km_span').show()
        //         $('#designation_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if (isNaN($('#km').val())) {
        //         $("#km_span").html(`Total Km Only in Number.`);
        //         $('#km_span').show()
        //         $('#designation_span').hide()
        //         $('#error_span').hide()

        //         return false;

        //     } else if ($('#count').val() != '') {



        //     }

        // }

        function viewBusRoute(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $.ajax({
                    url: "{{ route('admin.bus-route.view') }}",
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
                            var stopNo = response.stopNo;
                            var data = response.data;
                            var stops = response.stops;
                            console.log(stopNo.length);
                            if (stopNo.length > 0) {
                                let i = 0;
                                let row = $('.dynamic').empty()

                                $.each(stops, function(index, data) {

                                    let stopElement = '#stops' + (i += 1)
                                    let kmElement = '#km' + (i)

                                    row.append(`
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="stops${i}" class="required">Stop Name</label>
                                <input type="text" class="form-control stop" name="stops${i}" id="stops${i}">
                                <span id="stops${i}_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="km${i}" class="required">Stop Km</label>
                                <input type="text" class="form-control km" name="km${i}" id="km${i}">
                                <span id="km${i}_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
            `)

                                    $(stopElement).val(index)
                                    $(kmElement).val(data)


                                })

                            }

                            $('#designation').val(data.designation)
                            $('#km').val(data.total_km)


                            $("#from").select2()
                            $("#from").prop('disabled', true)
                            $("#save_div").hide();
                            $(".save_div").hide();
                            $("#degree_span").hide();
                            $("#from_span").hide();
                            $("#to_span").hide();
                            $("#loading_div").hide();
                            $("#busRouteModel").modal();
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

        function editBusRoute(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $("#from").prop('disabled', false)
                $.ajax({
                    url: "{{ route('admin.bus-route.edit') }}",
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
                            var stopNo = response.stopNo;
                            var data = response.data;
                            var stops = response.stops;
                            console.log(stopNo.length);
                            if (stopNo.length > 0) {
                                let i = 0;
                                let row = $('.dynamic').empty()

                                $.each(stops, function(index, data) {

                                    let stopElement = '#stops' + (i += 1)
                                    let kmElement = '#km' + (i)

                                    row.append(`
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="stops${i}" class="required">Stop Name</label>
                                <input type="text" class="form-control stop" name="stops${i}" id="stops${i}">
                                <span id="stops${i}_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="km${i}" class="required">Stop Km</label>
                                <input type="text" class="form-control km" name="km${i}" id="km${i}">
                                <span id="km${i}_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
            `)

                                    $(stopElement).val(index)
                                    $(kmElement).val(data)


                                })

                            }

                            $('#designation').val(data.designation)
                            $('#km').val(data.total_km)
                            $('#bus_route_id').val(data.id)
                            let stopCount = $('.stop').length;
                            $('#count').val(stopCount)
                            $("#save_div").show();
                            $(".save_div").show();
                            $("#degree_span").hide();
                            $("#from_span").hide();
                            $("#to_span").hide();
                            $("#loading_div").hide();
                            $(".text-danger").hide();
                            $("#busRouteModel").modal();
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

        function deleteBusRoute(id) {
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
                            url: "{{ route('admin.bus-route.delete') }}",
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
