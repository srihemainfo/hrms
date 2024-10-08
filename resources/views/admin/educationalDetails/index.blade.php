@extends('layouts.admin')
@section('content')
@can('educational_detail_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.educational-details.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.educationalDetail.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'EducationalDetail', 'route' => 'admin.educational-details.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.educationalDetail.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-EducationalDetail">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.education_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.institute_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.institute_location') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.medium') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.board_or_university') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.marks') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.marks_in_percentage') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.subject_1') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.mark_1') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.subject_2') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.mark_2') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.subject_3') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.mark_3') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.subject_4') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.mark_4') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.subject_5') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.mark_5') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.subject_6') }}
                    </th>
                    <th>
                        {{ trans('cruds.educationalDetail.fields.mark_6') }}
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
@can('educational_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.educational-details.massDestroy') }}",
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
    ajax: "{{ route('admin.educational-details.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'education_type_name', name: 'education_type.name' },
{ data: 'institute_name', name: 'institute_name' },
{ data: 'institute_location', name: 'institute_location' },
{ data: 'medium_medium', name: 'medium.medium' },
{ data: 'board_or_university', name: 'board_or_university' },
{ data: 'marks', name: 'marks' },
{ data: 'marks_in_percentage', name: 'marks_in_percentage' },
{ data: 'subject_1', name: 'subject_1' },
{ data: 'mark_1', name: 'mark_1' },
{ data: 'subject_2', name: 'subject_2' },
{ data: 'mark_2', name: 'mark_2' },
{ data: 'subject_3', name: 'subject_3' },
{ data: 'mark_3', name: 'mark_3' },
{ data: 'subject_4', name: 'subject_4' },
{ data: 'mark_4', name: 'mark_4' },
{ data: 'subject_5', name: 'subject_5' },
{ data: 'mark_5', name: 'mark_5' },
{ data: 'subject_6', name: 'subject_6' },
{ data: 'mark_6', name: 'mark_6' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-EducationalDetail').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
