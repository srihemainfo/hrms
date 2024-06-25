@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('staff_transfer_info_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.staff-transfer-infos.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.staffTransferInfo.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'StaffTransferInfo', 'route' => 'admin.staff-transfer-infos.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.staffTransferInfo.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-StaffTransferInfo">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.enroll_master') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.deletes') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.period') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.from_user') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.to_user') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.transfer_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.approved_by_user') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staffTransferInfos as $key => $staffTransferInfo)
                                    <tr data-entry-id="{{ $staffTransferInfo->id }}">
                                        <td>
                                            {{ $staffTransferInfo->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $staffTransferInfo->enroll_master->enroll_master_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $staffTransferInfo->enroll_master->deletes ?? '' }}
                                        </td>
                                        <td>
                                            {{ $staffTransferInfo->period ?? '' }}
                                        </td>
                                        <td>
                                            {{ $staffTransferInfo->from_user ?? '' }}
                                        </td>
                                        <td>
                                            {{ $staffTransferInfo->to_user ?? '' }}
                                        </td>
                                        <td>
                                            {{ $staffTransferInfo->transfer_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $staffTransferInfo->approved_by_user ?? '' }}
                                        </td>
                                        <td>
                                            @can('staff_transfer_info_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.staff-transfer-infos.show', $staffTransferInfo->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('staff_transfer_info_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.staff-transfer-infos.edit', $staffTransferInfo->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('staff_transfer_info_delete')
                                                <form action="{{ route('frontend.staff-transfer-infos.destroy', $staffTransferInfo->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
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
@can('staff_transfer_info_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.staff-transfer-infos.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-StaffTransferInfo:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection