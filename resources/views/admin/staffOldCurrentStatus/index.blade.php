@extends('layouts.admin')
@section('content')
  
 
    <div class="card">
        <div class="card-header">
           <h4 class='text-center text-uppercase'>Staff Current Status </h4> 
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $staff_status === 'teaching_staff' ? ' active' : '' }}"
                        href="{{ route('admin.Staff_status.index', ['staff_status' => 'teaching_staff']) }}">Teaching Staffs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $staff_status === 'non_teaching_staff' ? ' active' : '' }}"
                        href="{{ route('admin.Staff_status.index', ['staff_status' => 'non_teaching_staff']) }}">Non Teaching Staffs</a>
                </li>
            </ul>

            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TeachingStaff">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>ID</th>
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
                            Department
                        </th>
                        <th>
                            Previous Status
                        </th>
                        <th>
                            Start Date
                        </th>
                        <th>
                            End Date
                        </th>
                        <th>
                            Leave Taken Count
                        </th>
                        <th>
                           Current Status
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
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
                // processing: true,
                // serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.Staff_status.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
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
                        data: 'Dept',
                        name: 'Dept'
                    },
                    {
                        data: 'previous_status',
                        name: 'previous_status'
                    },
                    {
                        data: 'todate',
                        name: 'todate'
                    },
                    {
                        data: 'enddate',
                        name: 'enddate'
                    },
                    {
                        data: 'leavedays',
                        name: 'leavedays'
                    },
                    {
                        data: 'current_status',
                        name: 'current_status',
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
            let table = $('.datatable-TeachingStaff').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        // function attControl(element) {
        //     console.log($(element).find('.toggleData').data('class'));
        //     console.log($(element).find('.toggleData').data('id'));

        //     let currentStatus = $(element).find('.toggleData').data('id');
        //     let db_id = $(element).find('.toggleData').data('class');
        //     let Leave_value = $('#Leave_value');
        //     let status;
        //     if(currentStatus == '0'){
        //         status = 1;
        //     }else{
        //         status = 0;
        //     }
        //     $.ajax({
        //         url: '{{ route('admin.past_leave_apply_access') }}',
        //         method: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             'id': db_id,
        //             'status': status
        //         },
        //         success: function(response) {
        //             if (response.status == true) {
        //                 $true =  Leave_value.val(response.id);
        //                 console.log($true);
        //                 Swal.fire('', response.data, 'success');
        //                 //    location.reload();
        //             } else {
        //                 Swal.fire('', response.data, 'error');
        //             }

        //         }
        //     });
        // }

        function attControl(checkbox) {
            let db_id = $(checkbox).data('class');
            let currentStatus = $(checkbox).data('id');
            let status = currentStatus === 0 ? 1 : 0;

            $.ajax({
                url: '{{ route('admin.past_leave_apply_access') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': db_id,
                    'status': status
                },
                success: function (response) {
                    if (response.status == true) {
                        // Update the data-id attribute of the checkbox
                        $(checkbox).data('id', status);
                        Swal.fire('', response.data, 'success');
                        // location.reload();
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                }
            });
        }
    </script>
@endsection
