@extends('layouts.admin')
@section('content')

<style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
</style>
  
    <div class="card">
        <div class="card-header">
           <h4 class='text-center text-uppercase'>Staff Current Status</h4> 
        </div>
        <div class="card-body">

            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TeachingStaff">
                <thead>
                    <tr>
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
                ajax: "{{ route('admin.Staff_status.show',['Staff_status' => $staff_status]) }}",
                columns: [
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

        
    </script>
@endsection
