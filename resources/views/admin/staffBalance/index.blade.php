@extends('layouts.admin')
@section('content')
<style>
    .table.dataTable tbody td.select-checkbox:before {
        content: none !important;
    }
</style>
<div class="card">
    <div class="card-header">
        Staffs Balance CL & Permissions
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped table-hover datatable datatable-balanceCl text-center">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Staff Name</th>
                    <th>Staff Code</th>
                    <th>Balance CL</th>
                    <th>Balance Permission</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

        let dtOverrideGlobals = {
            buttons: dtButtons,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.staff.balance') }}",
            columns: [{
                    data: null,
                    name: 'sno',
                    render: function(data, type, row, meta) {
                        var rowId = meta.row + 1;
                        return rowId;
                    }
                },
                {
                    data: 'staff_name',
                    name: 'staff_name'
                },
                {
                    data: 'staff_code',
                    name: 'staff_code'
                },
                {
                    data: 'casual_leave',
                    name: 'casual_leave'
                },
                {
                    data: 'permission',
                    name: 'permission'
                }

            ],
            orderCellsTop: true,
            order: [
                [0, 'desc']
            ],
            pageLength: 10,
        };
        let table = $('.datatable-balanceCl').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    });
</script>
@endsection