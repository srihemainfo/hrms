@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header text-center">
        <strong>Staff Alteration Report</strong>
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
           <li class="nav-item">
                <a class="nav-link{{ $status === 'Pending' ? ' active' : '' }}"
                    href="{{ route('admin.Staff-Alteration-Report.index', ['status' => 'Pending']) }}">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ $status === 'Approved' ? ' active' : '' }}"
                    href="{{ route('admin.Staff-Alteration-Report.index', ['status' => 'Approved']) }}">Approved</a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ $status === 'Rejected' ? ' active' : '' }}"
                    href="{{ route('admin.Staff-Alteration-Report.index', ['status' => 'Rejected']) }}">Rejected</a>
            </li>

        </ul>
        <table class="table table-bordered table-striped table-hover datatable datatable-staffAlterationreport text-center">
            <thead>
                <tr>
                    <th></th>
                    <th>Sl/No</th>
                    <th>Leave Dates</th>
                    <th>From Staff</th>
                    <th>To Staff</th>
                    <th>Period</th>
                    <th>Class </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($checking as $index => $item)
                    <tr>

                        <td></td>
                        <td>{{ $index +1 }}</td>
                        <td>{{ $item['leaveDate'] ?? '' }}</td>
                        <td>{{ $item['fromestaffName'] ?? '' }}({{ $item['fromr_staff_code'] ??'' }})</td>
                        <td>{{ $item['tostaffName'] ??'' }}({{  $item['to_staff_code'] ??''  }})</td>
                        <td>{{ $item['class_period'] ?? '' }}</td>
                        <td>{{ $item['classname'] ?? '' }}</td>


                    </tr>
                @endforeach
            </tbody>
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
                retrieve: true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-staffAlterationreport').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
