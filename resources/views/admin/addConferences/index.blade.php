@extends('layouts.admin')
@section('content')
@can('add_conference_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.add-conferences.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.addConference.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'AddConference', 'route' => 'admin.add-conferences.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.addConference.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AddConference">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.addConference.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.addConference.fields.user_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.addConference.fields.topic_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.addConference.fields.location') }}
                    </th>
                    <th>
                        {{ trans('cruds.addConference.fields.conference_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.addConference.fields.contribution_of_conference') }}
                    </th>
                    <th>
                        {{ trans('cruds.addConference.fields.project_name') }}
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
@can('add_conference_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.add-conferences.massDestroy') }}",
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
    ajax: "{{ route('admin.add-conferences.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'user_name_name', name: 'user_name.name' },
{ data: 'topic_name', name: 'topic_name' },
{ data: 'location', name: 'location' },
{ data: 'conference_date', name: 'conference_date' },
{ data: 'contribution_of_conference', name: 'contribution_of_conference' },
{ data: 'project_name', name: 'project_name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-AddConference').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
