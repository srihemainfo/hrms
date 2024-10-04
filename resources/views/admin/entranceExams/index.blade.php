@extends('layouts.admin')
@section('content')
@can('entrance_exam_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.entrance-exams.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.entranceExam.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'EntranceExam', 'route' => 'admin.entrance-exams.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.entranceExam.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-EntranceExam">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.entranceExam.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.entranceExam.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.entranceExam.fields.exam_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.entranceExam.fields.passing_year') }}
                    </th>
                    <th>
                        {{ trans('cruds.entranceExam.fields.scored_mark') }}
                    </th>
                    <th>
                        {{ trans('cruds.entranceExam.fields.total_mark') }}
                    </th>
                    <th>
                        {{ trans('cruds.entranceExam.fields.rank') }}
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
@can('entrance_exam_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.entrance-exams.massDestroy') }}",
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
    ajax: "{{ route('admin.entrance-exams.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name_name', name: 'name.name' },
{ data: 'exam_type_name', name: 'exam_type.name' },
{ data: 'passing_year', name: 'passing_year' },
{ data: 'scored_mark', name: 'scored_mark' },
{ data: 'total_mark', name: 'total_mark' },
{ data: 'rank', name: 'rank' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-EntranceExam').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
