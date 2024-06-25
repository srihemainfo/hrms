@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('entrance_exam_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.entrance-exams.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-EntranceExam">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($entranceExams as $key => $entranceExam)
                                    <tr data-entry-id="{{ $entranceExam->id }}">
                                        <td>
                                            {{ $entranceExam->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $entranceExam->name->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $entranceExam->exam_type->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $entranceExam->passing_year ?? '' }}
                                        </td>
                                        <td>
                                            {{ $entranceExam->scored_mark ?? '' }}
                                        </td>
                                        <td>
                                            {{ $entranceExam->total_mark ?? '' }}
                                        </td>
                                        <td>
                                            {{ $entranceExam->rank ?? '' }}
                                        </td>
                                        <td>
                                            @can('entrance_exam_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.entrance-exams.show', $entranceExam->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('entrance_exam_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.entrance-exams.edit', $entranceExam->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('entrance_exam_delete')
                                                <form action="{{ route('frontend.entrance-exams.destroy', $entranceExam->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('entrance_exam_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.entrance-exams.massDestroy') }}",
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
    pageLength: 10,
  });
  let table = $('.datatable-EntranceExam:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection