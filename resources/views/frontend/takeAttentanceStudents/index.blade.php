@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('take_attentance_student_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.take-attentance-students.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-TakeAttentanceStudent">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($takeAttentanceStudents as $key => $takeAttentanceStudent)
                                    <tr data-entry-id="{{ $takeAttentanceStudent->id }}">
                                        <td>
                                            {{ $takeAttentanceStudent->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $takeAttentanceStudent->enroll_master->enroll_master_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $takeAttentanceStudent->enroll_master->deletes ?? '' }}
                                        </td>
                                        <td>
                                            {{ $takeAttentanceStudent->period ?? '' }}
                                        </td>
                                        <td>
                                            {{ $takeAttentanceStudent->taken_from ?? '' }}
                                        </td>
                                        <td>
                                            {{ $takeAttentanceStudent->approved_by ?? '' }}
                                        </td>
                                        <td>
                                            @can('take_attentance_student_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.take-attentance-students.show', $takeAttentanceStudent->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('take_attentance_student_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.take-attentance-students.edit', $takeAttentanceStudent->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('take_attentance_student_delete')
                                                <form action="{{ route('frontend.take-attentance-students.destroy', $takeAttentanceStudent->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('take_attentance_student_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.take-attentance-students.massDestroy') }}",
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
  let table = $('.datatable-TakeAttentanceStudent:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection