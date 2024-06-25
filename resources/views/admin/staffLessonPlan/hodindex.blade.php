@extends('layouts.admin')
@section('content')
    {{-- {{ dd($lesson_plan_req) }} --}}
    <style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card" style="margin-top:1rem;">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> Staff's Lesson Plans</h5>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $status == 0 ? ' active' : '' }}"
                        href="{{ route('admin.staff-lesson-plan.index', ['status' => 0]) }}">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status == 1 ? ' active' : '' }}"
                        href="{{ route('admin.staff-lesson-plan.index', ['status' => 1]) }}">Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status == 2 ? ' active' : '' }}"
                        href="{{ route('admin.staff-lesson-plan.index', ['status' => 2]) }}">Need Revision</a>
                </li>
            </ul>
            <table class="table table-bordered table-striped table-hover datatable datatable-lesson_plan text-center">
                <thead>
                    <tr>
                        {{-- <th></th> --}}
                        <th>Staff Name</th>
                        <th>Class Name</th>
                        <th>Subject</th>
                        {{-- <th>Status</th> --}}
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @if (count($lesson_plan_req) > 0)
                        @foreach ($lesson_plan_req as $plans)

                                <tr>

                                    <td>
                                        {{ $plans->name }}
                                    </td>
                                    <td>
                                        {{ $plans->short_form }}
                                    </td>
                                    <td>
                                       {{ $plans->got_subject }}
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.staff-subjects.lesson-plan.hod-view', ['enroll' => $plans->class, 'subject' => $plans->subject, 'status' => $status]) }}">
                                            View
                                        </a>
                                    </td>

                        @endforeach
                    @else
                        <tr>

                            <td colspan="4">No Date Available</td>
                        </tr>
                    @endif
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
            dtButtons.splice(0, 2);
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
            let table = $('.datatable-lesson_plan').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
