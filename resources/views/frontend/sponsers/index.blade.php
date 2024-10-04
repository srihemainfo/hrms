@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('sponser_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.sponsers.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.sponser.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'Sponser', 'route' => 'admin.sponsers.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.sponser.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Sponser">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sponser.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sponser.fields.user_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sponser.fields.sponser_type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sponser.fields.sponser_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sponser.fields.sponsered_items') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sponser.fields.sponsered_to') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sponsers as $key => $sponser)
                                    <tr data-entry-id="{{ $sponser->id }}">
                                        <td>
                                            {{ $sponser->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sponser->user_name->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sponser->sponser_type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sponser->sponser_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sponser->sponsered_items ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sponser->sponsered_to ?? '' }}
                                        </td>
                                        <td>
                                            @can('sponser_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.sponsers.show', $sponser->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('sponser_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.sponsers.edit', $sponser->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('sponser_delete')
                                                <form action="{{ route('frontend.sponsers.destroy', $sponser->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('sponser_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.sponsers.massDestroy') }}",
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
  let table = $('.datatable-Sponser:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection