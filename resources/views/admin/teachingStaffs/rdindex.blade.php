@extends('layouts.admin')
@section('content')
    <style>
        .status.open:before {
            background-color: #94E185;
            border-color: #78D965;
            box-shadow: 0px 0px 4px 1px #94E185;
        }

        .status.dead:before {
            background-color: #C9404D;
            border-color: #C42C3B;
            box-shadow: 0px 0px 4px 1px #C9404D;
        }

        .status:before {
            content: ' ';
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 10px;
            border: 1px solid #000;
            border-radius: 10px;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    @can('rd_staff_add_access')
        <button class="btn btn-success" onclick="openModal()">
            Add R & D Staff
        </button>
    @endcan
    <div class="card mt-3">
        <div class="card-header">
            R & D Staffs List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RdStaff">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            Staff Code
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="modal fade" id="rdModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="staff" class="required">Select Staff</label>
                        <select name="staff" class="form-control select2" id="staff">
                            <option value="">Select Staff</option>
                            @foreach ($staff as $data)
                                <option value="{{ $data->user_name_id }}">{{ $data->name }} ({{ $data->StaffCode }})
                                </option>
                            @endforeach
                        </select>
                        <span id="staff_span" class="text-danger text-center" style="display:none;font-size:0.9rem;">
                            Please Select Staff
                        </span>
                    </div>
                    <div id="loading_div" class="text-primary text-center" style="display:none;">Loading...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="save()">Add as R & D Staff</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.rd-staffs.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'StaffCode',
                        name: 'StaffCode'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'Designation',
                        name: 'Designation'
                    },
                    {
                        data: 'remove',
                        name: 'remove',
                        render: function(data) {
                            return `@can('rd_staff_remove_access')<div><span class="btn btn-xs btn-danger" onclick="remove(${data})">Remove</span></div>@endcan`;
                        },
                        type: 'html',
                        className: 'text-center'
                    },
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-RdStaff').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function openModal() {
            $("#rdModel").modal();
        }

        function save() {
            if ($("#staff").val() == '') {
                $("#staff_span").show();
                return false;
            } else {
                $("#staff_span").hide();
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You Want To Add This Staff ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $("#loading_div").show();
                        $.ajax({
                            url: '{{ route('admin.rd-staffs.store') }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'staff': $("#staff").val()
                            },
                            success: function(response) {
                                // console.log(response)
                                let status = response.status;
                                if (status == true) {
                                    $("#rdModel").modal('hide');
                                    Swal.fire('', 'Staff Added In R & D Successfully', 'success');
                                    location.reload();
                                } else {
                                    Swal.fire('', response.data, 'error');
                                }
                                $("#loading_div").hide();
                            }
                        })
                    } else if (result.dismiss == "cancel") {
                        Swal.fire(
                            "Cancelled",
                            "R & D Staff Adding Cancelled",
                            "error"
                        )
                    }
                });
            }
        }

        function remove(id) {

            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Want To Remove This Staff ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.rd-staffs.remove') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'staff': id
                        },
                        success: function(response) {
                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', 'Staff Removed From R & D Successfully', 'success');
                                location.reload();
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                        }
                    })
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "R & D Staff Removing Cancelled",
                        "error"
                    )
                }
            });
        }
    </script>
@endsection
