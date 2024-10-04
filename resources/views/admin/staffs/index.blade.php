@extends('layouts.admin')
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Staffs List
        </div>

        <div class="card-body">
<<<<<<< HEAD
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Staffs text-center">
=======
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Staffs text-center">
>>>>>>> 6563285674506c09c4794a263e688088e7e74606
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Employee Id
                        </th>
                        <th>
                            Role
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Contact Number
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
                                    url: "{{ route('admin.staffs.massDestroy') }}",
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

            if ($.fn.DataTable.isDataTable('.datatable-Staffs')) {
                $('.datatable-Staffs').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.staffs.index') }}",
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
                        name: 'name'
                    },
                    {
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
<<<<<<< HEAD
                        name: 'actions'
=======
                        name: '{{ trans('global.actions') }}'
>>>>>>> 6563285674506c09c4794a263e688088e7e74606
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-Staffs').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };
<<<<<<< HEAD



        // function saveSection() {
        //     $("#loading_div").hide();
        //     if ($("#designation").val() == '') {
        //         $("#designation_span").html(`Course Is Required.`);
        //         $("#designation_span").show();
        //     } else {
        //         $("#save_div").hide();
        //         $("#designation_span").hide();
        //         $("#loading_div").show();
        //         let id = $("#designation_id").val();
        //         let designation = $("#designation").val();
        //         $.ajax({
        //             url: "{{ route('admin.designation.store') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'id': id,
        //                 'designation': designation
        //             },
        //             success: function(response) {
        //                 let status = response.status;
        //                 if (status == true) {
        //                     Swal.fire('', response.data, 'success');
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //                 $("#designationModel").modal('hide');
        //                 callAjax();
        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 if (jqXHR.status) {
        //                     if (jqXHR.status == 500) {
        //                         Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
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

        // function viewDesignation(id) {
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         $('.secondLoader').show()

        //         $.ajax({
        //             url: "{{ route('admin.designation.view') }}",
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
        //                     $("#designation").val(data.name);
        //                     $("#save_div").hide();
        //                     $("#designation_span").hide();
        //                     $("#loading_div").hide();
        //                     $("#designationModel").modal();
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 if (jqXHR.status) {
        //                     if (jqXHR.status == 500) {
        //                         Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
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

        // function editDesignation(id) {
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         $('.secondLoader').show()
        //         $.ajax({
        //             url: "{{ route('admin.designation.edit') }}",
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
        //                     $("#designation_id").val(data.id);
        //                     $("#designation").val(data.name);
        //                     $("#save_btn").html(`Update`);
        //                     $("#save_div").show();
        //                     $("#designation_span").hide();
        //                     $("#loading_div").hide();
        //                     $("#designationModel").modal();
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 if (jqXHR.status) {
        //                     if (jqXHR.status == 500) {
        //                         Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
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


        // function deleteDesignation(id) {
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         Swal.fire({
        //             title: "Are You Sure?",
        //             text: "Do You Really Want To Delete!",
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonText: "Yes",
        //             cancelButtonText: "No",
        //             reverseButtons: true
        //         }).then(function(result) {
        //             if (result.value) {
        //                 $('.secondLoader').show(); // Show loader only if confirmed
        //                 $.ajax({
        //                     url: "{{ route('admin.designation.delete') }}",
        //                     method: 'POST',
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                     },
        //                     data: {
        //                         'id': id
        //                     },
        //                     success: function(response) {
        //                         Swal.fire('', response.data, response.status);
        //                         $('.secondLoader').hide(); // Hide loader on success
        //                         callAjax();
        //                     },
        //                     error: function(jqXHR, textStatus, errorThrown) {
        //                         $('.secondLoader').hide(); // Hide loader on error
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
        //                 });
        //             }
        //         });
        //     }
        // }
=======
>>>>>>> 6563285674506c09c4794a263e688088e7e74606
    </script>
@endsection
