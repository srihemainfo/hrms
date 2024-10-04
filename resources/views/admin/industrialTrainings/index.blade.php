@extends('layouts.admin')
@section('content')
@can('industrial_training_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.industrial-trainings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.industrialTraining.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'IndustrialTraining', 'route' => 'admin.industrial-trainings.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.industrialTraining.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-IndustrialTraining">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.industrialTraining.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.industrialTraining.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.industrialTraining.fields.topic') }}
                    </th>
                    <th>
                        {{ trans('cruds.industrialTraining.fields.location') }}
                    </th>
                    <th>
                        {{ trans('cruds.industrialTraining.fields.remarks') }}
                    </th>
                    <th>
                        {{ trans('cruds.industrialTraining.fields.from_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.industrialTraining.fields.to_date') }}
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
@can('industrial_training_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.industrial-trainings.massDestroy') }}",
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
    ajax: "{{ route('admin.industrial-trainings.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name_name', name: 'name.name' },
{ data: 'topic', name: 'topic' },
{ data: 'location', name: 'location' },
{ data: 'remarks', name: 'remarks' },
{ data: 'from_date', name: 'from_date' },
{ data: 'to_date', name: 'to_date' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-IndustrialTraining').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
