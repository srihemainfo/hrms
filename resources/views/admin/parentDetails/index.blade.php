@extends('layouts.admin')
@section('content')
@can('parent_detail_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.parent-details.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.parentDetail.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'ParentDetail', 'route' => 'admin.parent-details.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.parentDetail.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ParentDetail">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.father_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.father_mobile_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.fathers_occupation') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.mother_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.mother_mobile_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.mothers_occupation') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.guardian_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.guardian_mobile_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.parentDetail.fields.gaurdian_occupation') }}
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
@can('parent_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.parent-details.massDestroy') }}",
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
    ajax: "{{ route('admin.parent-details.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'father_name', name: 'father_name' },
{ data: 'father_mobile_no', name: 'father_mobile_no' },
{ data: 'fathers_occupation', name: 'fathers_occupation' },
{ data: 'mother_name', name: 'mother_name' },
{ data: 'mother_mobile_no', name: 'mother_mobile_no' },
{ data: 'mothers_occupation', name: 'mothers_occupation' },
{ data: 'guardian_name', name: 'guardian_name' },
{ data: 'guardian_mobile_no', name: 'guardian_mobile_no' },
{ data: 'gaurdian_occupation', name: 'gaurdian_occupation' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-ParentDetail').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
