@extends('layouts.admin')
@section('content')
@can('staff_transfer_info_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.staff-transfer-infos.create') }}">
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-StaffTransferInfo">
            <thead>
                <tr>
                    <th width="10">

                    </th>
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
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('staff_transfer_info_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.staff-transfer-infos.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.staff-transfer-infos.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'enroll_master_enroll_master_number', name: 'enroll_master.enroll_master_number' },
{ data: 'enroll_master.deletes', name: 'enroll_master.deletes' },
{ data: 'period', name: 'period' },
{ data: 'from_user', name: 'from_user' },
{ data: 'to_user', name: 'to_user' },
{ data: 'transfer_date', name: 'transfer_date' },
{ data: 'approved_by_user', name: 'approved_by_user' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-StaffTransferInfo').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
