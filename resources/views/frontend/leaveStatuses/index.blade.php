@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('leave_status_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.leave-statuses.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.leaveStatus.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'LeaveStatus', 'route' => 'admin.leave-statuses.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.leaveStatus.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-LeaveStatus">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.leaveStatus.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.leaveStatus.fields.name') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveStatuses as $key => $leaveStatus)
                                    <tr data-entry-id="{{ $leaveStatus->id }}">
                                        <td>
                                            {{ $leaveStatus->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $leaveStatus->name ?? '' }}
                                        </td>
                                        <td>
                                            @can('leave_status_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.leave-statuses.show', $leaveStatus->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('leave_status_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.leave-statuses.edit', $leaveStatus->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan


                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-LeaveStatus:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection