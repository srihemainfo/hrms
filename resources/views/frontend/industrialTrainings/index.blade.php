@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('industrial_training_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.industrial-trainings.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.industrialTraining.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'IndustrialTraining', 'route' => 'admin.industrial-trainings.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.industrialTraining.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-IndustrialTraining">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.topic') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.location') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.remarks') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.from_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.to_date') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($industrialTrainings as $key => $industrialTraining)
                                    <tr data-entry-id="{{ $industrialTraining->id }}">
                                        <td>
                                            {{ $industrialTraining->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $industrialTraining->name->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $industrialTraining->topic ?? '' }}
                                        </td>
                                        <td>
                                            {{ $industrialTraining->location ?? '' }}
                                        </td>
                                        <td>
                                            {{ $industrialTraining->remarks ?? '' }}
                                        </td>
                                        <td>
                                            {{ $industrialTraining->from_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $industrialTraining->to_date ?? '' }}
                                        </td>
                                        <td>
                                            @can('industrial_training_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.industrial-trainings.show', $industrialTraining->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('industrial_training_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.industrial-trainings.edit', $industrialTraining->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('industrial_training_delete')
                                                <form action="{{ route('frontend.industrial-trainings.destroy', $industrialTraining->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('industrial_training_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.industrial-trainings.massDestroy') }}",
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
  let table = $('.datatable-IndustrialTraining:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection