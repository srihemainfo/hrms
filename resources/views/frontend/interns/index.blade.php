@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('intern_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.interns.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.intern.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'Intern', 'route' => 'admin.interns.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.intern.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Intern">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.intern.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.intern.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.intern.fields.topic') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.intern.fields.from_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.intern.fields.to_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.intern.fields.progress_report') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interns as $key => $intern)
                                    <tr data-entry-id="{{ $intern->id }}">
                                        <td>
                                            {{ $intern->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $intern->name->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $intern->topic ?? '' }}
                                        </td>
                                        <td>
                                            {{ $intern->from_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $intern->to_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $intern->progress_report ?? '' }}
                                        </td>
                                        <td>
                                            @can('intern_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.interns.show', $intern->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('intern_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.interns.edit', $intern->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('intern_delete')
                                                <form action="{{ route('frontend.interns.destroy', $intern->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('intern_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.interns.massDestroy') }}",
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
  let table = $('.datatable-Intern:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection