@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('fundingdetali_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.fundingdetalis.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.fundingdetali.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.fundingdetali.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Fundingdetali">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.fundingdetali.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.fundingdetali.fields.user_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.fundingdetali.fields.topic') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.fundingdetali.fields.remark') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fundingdetalis as $key => $fundingdetali)
                                    <tr data-entry-id="{{ $fundingdetali->id }}">
                                        <td>
                                            {{ $fundingdetali->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $fundingdetali->user_name->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $fundingdetali->topic ?? '' }}
                                        </td>
                                        <td>
                                            {{ $fundingdetali->remark ?? '' }}
                                        </td>
                                        <td>
                                            @can('fundingdetali_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.fundingdetalis.show', $fundingdetali->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('fundingdetali_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.fundingdetalis.edit', $fundingdetali->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('fundingdetali_delete')
                                                <form action="{{ route('frontend.fundingdetalis.destroy', $fundingdetali->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('fundingdetali_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.fundingdetalis.massDestroy') }}",
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
  let table = $('.datatable-Fundingdetali:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection