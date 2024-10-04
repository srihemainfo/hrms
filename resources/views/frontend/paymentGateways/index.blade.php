@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('payment_gateway_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.payment-gateways.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.paymentGateway.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'PaymentGateway', 'route' => 'admin.payment-gateways.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.paymentGateway.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-PaymentGateway">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.paymentGateway.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.paymentGateway.fields.gateway_type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.paymentGateway.fields.prefix') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.paymentGateway.fields.url') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.paymentGateway.fields.username') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.paymentGateway.fields.password') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.paymentGateway.fields.merchand') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentGateways as $key => $paymentGateway)
                                    <tr data-entry-id="{{ $paymentGateway->id }}">
                                        <td>
                                            {{ $paymentGateway->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $paymentGateway->gateway_type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $paymentGateway->prefix ?? '' }}
                                        </td>
                                        <td>
                                            {{ $paymentGateway->url ?? '' }}
                                        </td>
                                        <td>
                                            {{ $paymentGateway->username ?? '' }}
                                        </td>
                                        <td>
                                            {{ $paymentGateway->password ?? '' }}
                                        </td>
                                        <td>
                                            {{ $paymentGateway->merchand ?? '' }}
                                        </td>
                                        <td>
                                            @can('payment_gateway_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.payment-gateways.show', $paymentGateway->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('payment_gateway_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.payment-gateways.edit', $paymentGateway->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('payment_gateway_delete')
                                                <form action="{{ route('frontend.payment-gateways.destroy', $paymentGateway->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('payment_gateway_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.payment-gateways.massDestroy') }}",
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
    pageLength: 100,
  });
  let table = $('.datatable-PaymentGateway:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection