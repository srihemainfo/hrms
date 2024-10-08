@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Leave Request List
        </div>
        <div class="card-body">
            <input type="hidden" id="status" value="{{ $status }}">
            <input type="hidden" id="principal" value="{{ $principal == true ? 1 : 0 }}">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'Pending' ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-leaves.index', ['status' => 'Pending']) }}">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'Approved' ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-leaves.index', ['status' => 'Approved']) }}">Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'Rejected' ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-leaves.index', ['status' => 'Rejected']) }}">Rejected</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'NeedClarification' ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-leaves.index', ['status' => 'NeedClarification']) }}">Need
                        Clarification</a>
                </li>
            </ul>
            <table class="table table-bordered table-striped table-hover datatable datatable-HrmRequestLeaf text-center">
                <thead>
                    <tr>
                        <th></th>
                        <th>Staff Name</th>
                        <th>Staff Code</th>
                        {{-- <th>Department</th> --}}
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Off Date</th>
                        <th>Alter Date</th>
                        <th>Half Day Leave Date</th>
                        <th>FN / AN</th>
                        <th>Total Days</th>
                        <th>Reason</th>
                        <th>Leave Type</th>
                        <th>Rejected / Clarification <br> (Reasons)</th>
                        <th>Approved / Rejected By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            @php
                                $final_from_date = $final_to_date = $final_off_date = $final_alter_date = $final_half_day_leave = null;

                                if ($item['from_date'] != null) {
                                    $from_date = explode('-', $item['from_date']);
                                    $final_from_date = $from_date[2] . '-' . $from_date[1] . '-' . $from_date[0];
                                }
                                if ($item['to_date'] != null) {
                                    $to_date = explode('-', $item['to_date']);
                                    $final_to_date = $to_date[2] . '-' . $to_date[1] . '-' . $to_date[0];
                                }
                                if ($item['off_date'] != null) {
                                    $off_date = explode('-', $item['off_date']);
                                    $final_off_date = $off_date[2] . '-' . $off_date[1] . '-' . $off_date[0];
                                }
                                if ($item['alter_date'] != null) {
                                    $alter_date = explode('-', $item['alter_date']);
                                    $final_alter_date = $alter_date[2] . '-' . $alter_date[1] . '-' . $alter_date[0];
                                }

                                if ($item['half_day_leave'] != null) {
                                    $half_day_leave = explode('-', $item['half_day_leave']);
                                    $final_half_day_leave =
                                        $half_day_leave[2] . '-' . $half_day_leave[1] . '-' . $half_day_leave[0];
                                }
                            @endphp
                            <td><b style="opacity:0;">{{ $item['id'] }}</b></td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['staff_code'] }}</td>
                            {{-- <td>{{ $item['dept'] }}</td> --}}
                            <td>{{ $final_from_date }}</td>
                            <td>{{ $final_to_date }}</td>
                            <td>{{ $final_off_date }}</td>
                            <td>{{ $final_alter_date }}</td>
                            <td>{{ $final_half_day_leave }}</td>
                            <td>{{ $item['noon'] }}</td>
                            <td>{{ $item['total_days'] + ($item['total_days_nxt_mn'] != null ? $item['total_days_nxt_mn'] : 0) }}</td>
                            <td>{{ $item['subject'] }}</td>
                            <td>
                                @foreach ($leave_types as $id => $entry)
                                    @if ($id == $item['leave_type'])
                                        {{ $entry }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @if ($item['rejected_reason'] != null)
                                    {{ $item['rejected_reason'] }}
                                @else
                                    {{ $item['clarification_reason'] }}
                                @endif
                            </td>
                            <td>{{ $item['approved_by'] }}</td>
                            <td>
                                <a class="btn btn-xs btn-success"
                                    href="{{ url('admin/' . $item['url'] . '/' . $item['user_id']) }}" target="_blank">
                                    Profile
                                </a>
                                <a class="btn btn-xs btn-primary"
                                    href="{{ route('admin.hrm-request-leaves.show', $item['id']) }}">
                                    {{ trans('global.view') }}
                                </a>
                                <br>
                                <a class="btn btn-xs btn-warning"
                                    href="{{ route('admin.hrm-request-leaves.leave-list', $item['user_id']) }}"
                                    target="_blank">
                                    Leave History
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($list1 as $item)
                        <tr>
                            @php
                                $final_from_date = $final_to_date = $final_off_date = $final_alter_date = $final_half_day_leave = null;

                                if ($item['from_date'] != null) {
                                    $from_date = explode('-', $item['from_date']);
                                    $final_from_date = $from_date[2] . '-' . $from_date[1] . '-' . $from_date[0];
                                }
                                if ($item['to_date'] != null) {
                                    $to_date = explode('-', $item['to_date']);
                                    $final_to_date = $to_date[2] . '-' . $to_date[1] . '-' . $to_date[0];
                                }
                                if ($item['off_date'] != null) {
                                    $off_date = explode('-', $item['off_date']);
                                    $final_off_date = $off_date[2] . '-' . $off_date[1] . '-' . $off_date[0];
                                }
                                if ($item['alter_date'] != null) {
                                    $alter_date = explode('-', $item['alter_date']);
                                    $final_alter_date = $alter_date[2] . '-' . $alter_date[1] . '-' . $alter_date[0];
                                }

                                if ($item['half_day_leave'] != null) {
                                    $half_day_leave = explode('-', $item['half_day_leave']);
                                    $final_half_day_leave =
                                        $half_day_leave[2] . '-' . $half_day_leave[1] . '-' . $half_day_leave[0];
                                }
                            @endphp
                            <td><b style="opacity:0;">{{ $item['id'] }}</b></td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['staff_code'] }}</td>
                            <td>{{ $item['dept'] }}</td>
                            <td>{{ $final_from_date }}</td>
                            <td>{{ $final_to_date }}</td>
                            <td>{{ $final_off_date }}</td>
                            <td>{{ $final_alter_date }}</td>
                            <td>{{ $final_half_day_leave }}</td>
                            <td>{{ $item['noon'] }}</td>
                            <td>{{ $item['total_days'] }}</td>
                            <td>{{ $item['subject'] }}</td>
                            <td>
                                @foreach ($leave_types as $id => $entry)
                                    @if ($id == $item['leave_type'])
                                        {{ $entry }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @if ($item['rejected_reason'] != null)
                                    {{ $item['rejected_reason'] }}
                                @else
                                    {{ $item['clarification_reason'] }}
                                @endif
                            </td>
                            <td>{{ $item['approved_by'] }}</td>
                            <td>
                                <a class="btn btn-xs btn-success"
                                    href="{{ url('admin/' . $item['url'] . '/' . $item['user_id']) }}" target="_blank">
                                    Profile
                                </a>
                                <a class="btn btn-xs btn-primary"
                                    href="{{ route('admin.hrm-request-leaves.show', $item['id']) }}" target="_blank">
                                    {{ trans('global.view') }}
                                </a>
                                <br>
                                <a class="btn btn-xs btn-warning"
                                    href="{{ route('admin.hrm-request-leaves.leave-list', $item['user_id']) }}"
                                    target="_blank">
                                    Leave History
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
            dtButtons.splice(2, 2);
            if ($("#status").val() == 'Pending' && $("#principal").val() == '1') {
                let bulkApprove = {
                    text: 'Approve OD',
                    url: "{{ route('admin.hrm-request-leaves.bulk-approve') }}",
                    className: 'btn-success btn-sm',
                    action: function(e, dt, node, config) {

                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            let theEntry = entry[0];
                            var firstSplit = theEntry.split(">");
                            var secondSplit = firstSplit[1].split("<");
                            return secondSplit[0];
                        });

                        if (ids.length === 0) {
                            Swal.fire('', '{{ trans('global.datatables.zero_selected') }}', 'warning');
                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                    }
                                })
                                .done(function(response) {
                                    if (response.status == true) {
                                        Swal.fire('', response.data, 'success');
                                        location.reload();
                                    } else {
                                        Swal.fire('', response.data, 'error');
                                    }
                                })
                        }
                    }
                }
                dtButtons.push(bulkApprove);
            }
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

            let table = $('.datatable-HrmRequestLeaf').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
