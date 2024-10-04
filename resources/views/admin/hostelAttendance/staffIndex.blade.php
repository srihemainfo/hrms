@extends('layouts.non_techStaffHome')
@section('content')
   
    
@endsection
@section('scripts')
    @parent
    {{-- <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('hostel_room_delete')
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
                                        url: "{{ route('admin.hostel-students.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        Swal.fire('', response.data, response.status);
                                        $('.secondLoader').hide()
                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-HostelWarden')) {
                $('.datatable-HostelWarden').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.hostel-students.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'sno',
                        name: 'sno'
                    },
                    {
                        data: 'hostel_name',
                        name: 'hostel_name'
                    },
                    {
                        data: 'room_no',
                        name: 'room_no'
                    },
                    {
                        data: 'student',
                        name: 'student'
                    },
                    {
                        data: 'enroll',
                        name: 'enroll'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-HostelWarden').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#hostel_name").val('').select2();
            $("#room").val('').select2()
            $("#hostel_name_span").hide();
            $("#room_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#hostel_warden").modal();
        }

        function search() {
            $("#loading_div").hide();
            if ($("#hostel_name").val() == '') {
                $("#hostel_name_span").html(`Hostel Is Required.`);
                $("#hostel_name_span").show();
                $("#room_span").hide();

            } else if ($("#room").val() == '') {
                $("#hostel_span").html(`Room Is Required.`);
                $("#hostel_span").show();
                $("#hostel_name_span").hide();

            } else {
                $("#save_div").hide();
                $("#hostel_name_span").hide();
                $("#room_span").hide();
                $("#loading_div").show();
                let room = $("#room").val();
                // let hostel = $("#hostel_name").val();
                $.ajax({
                    url: "{{ route('admin.hostel-students.search') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        // 'hostel': hostel,
                        'room': room,
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            let table = $('.datatable-HostelWarden').DataTable();
                            table.destroy();
                            let body = $('#tbody').empty()
                            let i = 0;
                            $.each(data, function(index, value) {
                                let row = $('<tr>')
                                row.append(`<td></td>`)
                                row.append(`<td>${i+=1}</td>`)
                                row.append(`<td>${value.hostel_name}</td>`)
                                row.append(`<td>${value.room_no}</td>`)
                                row.append(`<td>${value.name}</td>`)
                                row.append(`<td>${value.enroll}</td>`)
                                body.append(row)
                            })

                            table = $('.datatable-HostelWarden').DataTable();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#loading_div").hide();
                        $("#save_div").show()

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

        // function changeHostel() {
        //     console.log('hii');
        //     console.log($('#hostel_name').val());
        //     if ($('#hostel_name').val() != '') {
        //         $('#room').html(`<option value="">Loading...</option>`)
        //         $.ajax({
        //             url: "{{ route('admin.hostel-students.getRoom') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'hostel': $('#hostel_name').val()
        //             },
        //             success: function(response) {
        //                 $('.secondLoader').hide()

        //                 let status = response.status;
        //                 if (status == true) {
        //                     var data = response.data;
        //                     $('#room').empty()
        //                     $.each(data, function(index, value) {
        //                         $('#room').append(`<option value="${index}">${value}</option>`)
        //                     })
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 if (jqXHR.status) {
        //                     if (jqXHR.status == 500) {
        //                         Swal.fire('', 'Request Timeout / Internal Server Error',
        //                             'error');
        //                     } else {
        //                         Swal.fire('', jqXHR.status, 'error');
        //                     }
        //                 } else if (textStatus) {
        //                     Swal.fire('', textStatus, 'error');
        //                 } else {
        //                     Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
        //                         "error");
        //                 }
        //             }
        //         })

        //     }
        // }

        // function editHostelWarden(id) {
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         $('.secondLoader').show()
        //         $.ajax({
        //             url: "{{ route('admin.hostel-warden.edit') }}",
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
        //                     $("#hostel_name_id").val(data.id);
        //                     $("#hostel_name").val(data.warden_id);
        //                     $("#hostel_name").select2();
        //                     $("#room").val(data.hostel_id).select2();
        //                     $('.roomViews').show();
        //                     $("#save_btn").html(`Update`);
        //                     $("#save_div").show();
        //                     $("#room").hide();
        //                     $("#hostel_name_span").hide();
        //                     $("#loading_div").hide();
        //                     $("#hostel_warden").modal();
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 if (jqXHR.status) {
        //                     if (jqXHR.status == 500) {
        //                         Swal.fire('', 'Request Timeout / Internal Server Error',
        //                             'error');
        //                     } else {
        //                         Swal.fire('', jqXHR.status, 'error');
        //                     }
        //                 } else if (textStatus) {
        //                     Swal.fire('', textStatus, 'error');
        //                 } else {
        //                     Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
        //                         "error");
        //                 }
        //             }
        //         })
        //     }
        // }

        // function deleteHostelWarden(id) {
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
        //                     url: "{{ route('admin.hostel-warden.delete') }}",
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
        //                     },
        //                     error: function(jqXHR, textStatus, errorThrown) {
        //                         if (jqXHR.status) {
        //                             if (jqXHR.status == 500) {
        //                                 Swal.fire('', 'Request Timeout / Internal Server Error',
        //                                     'error');
        //                             } else {
        //                                 Swal.fire('', jqXHR.status, 'error');
        //                             }
        //                         } else if (textStatus) {
        //                             Swal.fire('', textStatus, 'error');
        //                         } else {
        //                             Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
        //                                 "error");
        //                         }
        //                     }
        //                 })
        //             }
        //         })
        //     }
        // }
    </script> --}}
@endsection