@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header text-center">
            PaySlip Request
        </div>
        <div class="card-body">
            {{-- <input type="hidden" id="status" value="{{ $status }}">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'Pending' ? ' active' : '' }}"
                        href="{{ route('admin.payslip-request.index', ['status' => 'Pending']) }}">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'Approved' ? ' active' : '' }}"
                        href="{{ route('admin.payslip-request.index', ['status' => 'Approved']) }}">Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'Rejected' ? ' active' : '' }}"
                        href="{{ route('admin.payslip-request.index', ['status' => 'Rejected']) }}">Rejected</a>
                </li>
            </ul> --}}
            <table id="my-table-request"
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Staff-Payslip-Request text-center">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Staff Name
                        </th>
                        <th>
                            Month
                        </th>
                        <th>
                            Year
                        </th>
                        <th>
                            Reason
                        </th>
                        <th>
                            Actions
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
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);

            if ($.fn.DataTable.isDataTable('.datatable-Staff-Payslip-Request')) {
                $('.datatable-Staff-Payslip-Request').DataTable().destroy();
            }

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.payslip-request.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'year',
                        name: 'year'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'actions',
                        name: 'actions'

                    },
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };

            let table = $('.datatable-Staff-Payslip-Request').DataTable(dtOverrideGlobals);

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        }

        function approveRequest(id) {
            Swal.fire({
                title: "Are You Sure?",
                text: "Are you sure you want to Approve this request?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $('.secondLoader').show();
                    $.ajax({
                        url: "{{ route('admin.payslip-request.approve', '') }}/" + id,

                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                            status: 'Approved'
                        },
                        success: function(response) {
                            $('.secondLoader').hide();

                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', response.data, 'success');
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                            callAjax();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('.secondLoader').hide();

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
                    });
                }
            });
        }

        function rejectRequest(id) {
            Swal.fire({
                title: "Are You Sure?",
                text: "Are you sure you want to Reject this request?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $('.secondLoader').show();
                    $.ajax({
                        url: "{{ route('admin.payslip-request.reject', '') }}/" + id,

                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                            status: 'Rejected'
                        },
                        success: function(response) {
                            $('.secondLoader').hide();

                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', response.data, 'success');
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                            callAjax();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('.secondLoader').hide();

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
                    });
                }
            });
        }
    </script>
@endsection
