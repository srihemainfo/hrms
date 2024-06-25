@extends('layouts.admin')
@section('content')
@can('od_request_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.od-requests.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.odRequest.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'OdRequest', 'route' => 'admin.od-requests.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.odRequest.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-OdRequest">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.odRequest.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.odRequest.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.odRequest.fields.from_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.odRequest.fields.to_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.odRequest.fields.level_1_userid') }}
                    </th>
                    <th>
                        {{ trans('cruds.odRequest.fields.level_2_userid') }}
                    </th>
                    <th>
                        {{ trans('cruds.odRequest.fields.level_3_userid') }}
                    </th>
                    <th>
                        {{ trans('cruds.odRequest.fields.approved_by') }}
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
@can('od_request_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.od-requests.massDestroy') }}",
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
    ajax: "{{ route('admin.od-requests.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'user', name: 'user' },
{ data: 'from_date', name: 'from_date' },
{ data: 'to_date', name: 'to_date' },
{ data: 'level_1_userid', name: 'level_1_userid' },
{ data: 'level_2_userid', name: 'level_2_userid' },
{ data: 'level_3_userid', name: 'level_3_userid' },
{ data: 'approved_by', name: 'approved_by' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-OdRequest').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
