@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Permission Request List
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $status == 0 ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-permissions.index', ['status' => 0]) }}">Pending</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link{{ $status == 1 ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-permissions.index', ['status' => 1]) }}">Verified</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link{{ $status == 2 ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-permissions.index', ['status' => 2]) }}">Approved</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link{{ $status == 3 ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-permissions.index', ['status' => 3]) }}">Rejected</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status == 4 ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-permissions.index', ['status' => 4]) }}">NeedClarification</a>
                </li>
            </ul>
            <table
                class="table table-bordered table-striped table-hover datatable datatable-HrmRequestPermission text-center">
                <thead>
                    <tr>
                        <th></th>
                        <th>Staff Name</th>
                        <th>Staff Code</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>From Time</th>
                        <th>To Time</th>
                        <th>Permission Type</th>
                        <th>Reason</th>
                        <th>Approved / Rejected By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            @php
                                $date = explode('-', $item['date']);
                                $final_date = $date[2] . '-' . $date[1] . '-' . $date[0];
                            @endphp
                            <td></td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['staff_code'] }}</td>
                            <td>{{ $item['dept'] }}</td>
                            <td>{{ $final_date }}</td>
                            <td>{{ $item['from_time'] }}</td>
                            <td>{{ $item['to_time'] }}</td>
                            <td>{{  $item['Permission']  != '' ? $item['Permission'] != 'Personal'? 'Admin' : $item['Permission'] : '' }}</td>
                            <td>{{ $item['reason'] }}</td>
                            <td>{{ $item['approved_by'] }}</td>
                            <td>
                                <a class="btn btn-xs btn-success"
                                    href="{{ url('admin/teaching-staff-edge/' . $item['user_name_id']) }}" target="_blank">
                                    Profile
                                </a>
                                <a class="btn btn-xs btn-primary"
                                    href="{{ route('admin.hrm-request-permissions.show', $item['id']) }}" target="_blank">
                                    {{ trans('global.view') }}
                                </a>
                                <br>
                                <a class="btn btn-xs btn-warning"
                                    href="{{ route('admin.hrm-request-permissions.permission-list', $item['user_name_id']) }}"
                                    target="_blank">
                                    Permission History
                                </a>
                            </td>
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
                pageLength: 100,
            };
            let table = $('.datatable-HrmRequestPermission').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
