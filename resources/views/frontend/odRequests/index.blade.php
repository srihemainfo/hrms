@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('od_request_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.od-requests.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.odRequest.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'OdRequest', 'route' => 'admin.od-requests.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.odRequest.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-OdRequest">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.odRequest.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.odRequest.fields.user') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.odRequest.fields.from_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.odRequest.fields.to_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.odRequest.fields.level_1_userid') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.odRequest.fields.level_2_userid') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.odRequest.fields.level_3_userid') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.odRequest.fields.approved_by') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($odRequests as $key => $odRequest)
                                    <tr data-entry-id="{{ $odRequest->id }}">
                                        <td>
                                            {{ $odRequest->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $odRequest->user ?? '' }}
                                        </td>
                                        <td>
                                            {{ $odRequest->from_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $odRequest->to_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $odRequest->level_1_userid ?? '' }}
                                        </td>
                                        <td>
                                            {{ $odRequest->level_2_userid ?? '' }}
                                        </td>
                                        <td>
                                            {{ $odRequest->level_3_userid ?? '' }}
                                        </td>
                                        <td>
                                            {{ $odRequest->approved_by ?? '' }}
                                        </td>
                                        <td>
                                            @can('od_request_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.od-requests.show', $odRequest->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('od_request_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.od-requests.edit', $odRequest->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('od_request_delete')
                                                <form action="{{ route('frontend.od-requests.destroy', $odRequest->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('od_request_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.od-requests.massDestroy') }}",
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
  let table = $('.datatable-OdRequest:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection