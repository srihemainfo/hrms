@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 5) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    {{-- <div class="card float-right"
        style="width: fit-content; background-color: #2276cf; color: white;     box-shadow: 0 0 0 0;">
        <div class="card-body" style="padding: 1rem; ">
            <div class="div"><b style="font-weight: initial;">Total Fine Amount :
                </b>&#x20B9;{{ $totalFine->total_fine }}.00</div>
        </div>
    </div> --}}
    {{-- <div class="card" style="margin-top: 80px;">
        <div class="card-header">
            Department Wise Report
        </div>
        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FineReport text-center">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            SNo
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Total Members
                        </th>
                        <th>
                            Total Books
                        </th>
                        <th>
                            Total Returned
                        </th>
                        <th>
                            Total Loaned
                        </th>
                        <th>
                            Total OverDue Books
                        </th>
                        <th>
                            Total Fine Amount(&#x20B9;)
                        </th>
                    </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div> --}}


    <div class="card">
        <div class="card-header">
            Department Wise Report
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $who === 'student' ? ' active' : '' }}"
                        href="{{ route('admin.departWise-report.departWiseReport', ['who' => 'student']) }}">Student Wise</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $who === 'staff' ? ' active' : '' }}"
                        href="{{ route('admin.departWise-report.departWiseReport', ['who' => 'staff']) }}">Staff Wise</a>
                </li>
            </ul>
            <table class="table table-bordered table-striped table-hover datatable datatable-FineReport text-center">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            SNo
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Total Members
                        </th>
                        <th>
                            Total Books
                        </th>
                        <th>
                            Total Returned
                        </th>
                        <th>
                            Total Loaned
                        </th>
                        <th>
                            Total OverDue Books
                        </th>
                        <th>
                            Total Fine Amount(&#x20B9;)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($query as $k => $item)
                        <tr>
                            <td></td>
                            <td>{{ $k += 1 }}</td>
                            <td>{{ $item->name != null ? $item->name : '' }}</td>
                            <td>{{ $item->student_count }}</td>
                            <td>{{ $item->book_count }}</td>
                            <td>{{ $item->available }}</td>
                            <td>{{ $item->loaned }}</td>
                            <td>{{ $item->overdue }}</td>
                            <td>{{ $item->total_fine }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            $('#loading_div').hide();
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(1, 3);
            dtButtons.splice(4, 4);

            if ($.fn.DataTable.isDataTable('.datatable-FineReport')) {
                $('.datatable-FineReport').DataTable().destroy();
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
            let table = $('.datatable-FineReport').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        });
    </script>
@endsection
