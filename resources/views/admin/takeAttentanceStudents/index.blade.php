@extends('layouts.admin')
@section('content')
@can('take_attentance_student_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.take-attentance-students.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.takeAttentanceStudent.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'TakeAttentanceStudent', 'route' => 'admin.take-attentance-students.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.takeAttentanceStudent.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TakeAttentanceStudent">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.takeAttentanceStudent.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.takeAttentanceStudent.fields.enroll_master') }}
                    </th>
                    <th>
                        {{ trans('cruds.courseEnrollMaster.fields.deletes') }}
                    </th>
                    <th>
                        {{ trans('cruds.takeAttentanceStudent.fields.period') }}
                    </th>
                    <th>
                        {{ trans('cruds.takeAttentanceStudent.fields.taken_from') }}
                    </th>
                    <th>
                        {{ trans('cruds.takeAttentanceStudent.fields.approved_by') }}
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
@can('take_attentance_student_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.take-attentance-students.massDestroy') }}",
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
    ajax: "{{ route('admin.take-attentance-students.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'enroll_master_enroll_master_number', name: 'enroll_master.enroll_master_number' },
{ data: 'enroll_master.deletes', name: 'enroll_master.deletes' },
{ data: 'period', name: 'period' },
{ data: 'taken_from', name: 'taken_from' },
{ data: 'approved_by', name: 'approved_by' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-TakeAttentanceStudent').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
