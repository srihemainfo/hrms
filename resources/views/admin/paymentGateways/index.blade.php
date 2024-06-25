@extends('layouts.admin')
@section('content')
@can('payment_gateway_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.payment-gateways.create') }}">
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-PaymentGateway">
            <thead>
                <tr>
                    <th width="10">

                    </th>
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
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('payment_gateway_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.payment-gateways.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.payment-gateways.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'gateway_type', name: 'gateway_type' },
{ data: 'prefix', name: 'prefix' },
{ data: 'url', name: 'url' },
{ data: 'username', name: 'username' },
{ data: 'password', name: 'password' },
{ data: 'merchand', name: 'merchand' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-PaymentGateway').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
