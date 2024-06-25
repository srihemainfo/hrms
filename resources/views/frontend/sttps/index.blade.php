@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('sttp_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.sttps.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.sttp.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'Sttp', 'route' => 'admin.sttps.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.sttp.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Sttp">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sttp.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sttp.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sttp.fields.topic') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sttp.fields.remarks') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sttp.fields.from') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sttp.fields.to') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sttps as $key => $sttp)
                                    <tr data-entry-id="{{ $sttp->id }}">
                                        <td>
                                            {{ $sttp->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sttp->name->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sttp->topic ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sttp->remarks ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sttp->from ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sttp->to ?? '' }}
                                        </td>
                                        <td>
                                            @can('sttp_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.sttps.show', $sttp->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('sttp_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.sttps.edit', $sttp->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('sttp_delete')
                                                <form action="{{ route('frontend.sttps.destroy', $sttp->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('sttp_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.sttps.massDestroy') }}",
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
  let table = $('.datatable-Sttp:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection