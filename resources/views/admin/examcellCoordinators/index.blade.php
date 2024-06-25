@extends('layouts.admin')
@section('content')
<div class="pl-2 mb-2">
    <a class="btn btn-success"  href="{{ route('admin.exam_cell_coordinators.create') }}">
         Add Exam Cell Coordinators
    </a>
</div>
<div class="card">
    <div class="card-header">
        Exam Cell Coordinators {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-coeIndex">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        ID
                    </th>
                    <th>
                         Name
                    </th>
                    <th>
                        Staff Code
                    </th>
                    <th>
                        Staff Type
                    </th>
                    <th>
                        Department
                    </th>
                    <th>
                        Designation
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($datas->count() > 0)
                @foreach ($datas as $index => $data )
                <tr>
                    <td></td>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $data->name ?? '' }}</td>
                    <td>{{  $data->StaffCode ?? '' }}</td>
                    <td>{{  $data->staffType ?? '' }}</td>
                    <td>{{  $data->Dept ?? '' }}</td>
                    <td>{{  $data->Designation ?? '' }}</td>
                    <td>
                        {{-- <form method="POST"
                        action="{{ route('admin.promotion-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <button type="submit" id="updater" name="updater" value="updater"
                            class="btn btn-xs btn-info">Edit</button>
                    </form> --}}
                    <a  class="btn btn-info btn-xs" href="{{ route('admin.exam_cell_coordinators.show', $data->user_name_id) }}"
                        class="dropdown-item">View</a>
                    <form
                        action="{{ route('admin.exam_cell_coordinators.remove', $data->id) }}"
                        method="POST"
                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                        style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-xs btn-danger"
                            value="Remove">
                    </form>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6">No Data Found</td>
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
        let table = $('.datatable-coeIndex').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    });
</script>
@endsection
