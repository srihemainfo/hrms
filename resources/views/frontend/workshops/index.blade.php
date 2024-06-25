@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('workshop_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.workshops.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.workshop.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'Workshop', 'route' => 'admin.workshops.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.workshop.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Workshop">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workshop.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workshop.fields.user_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workshop.fields.topic') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workshop.fields.remarks') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workshop.fields.from_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workshop.fields.to_date') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workshops as $key => $workshop)
                                    <tr data-entry-id="{{ $workshop->id }}">
                                        <td>
                                            {{ $workshop->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workshop->user_name->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workshop->topic ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workshop->remarks ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workshop->from_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workshop->to_date ?? '' }}
                                        </td>
                                        <td>
                                            @can('workshop_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.workshops.show', $workshop->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('workshop_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.workshops.edit', $workshop->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('workshop_delete')
                                                <form action="{{ route('frontend.workshops.destroy', $workshop->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('workshop_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.workshops.massDestroy') }}",
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
  let table = $('.datatable-Workshop:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection