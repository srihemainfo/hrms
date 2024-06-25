@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('bank_account_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.bank-account-details.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.bankAccountDetail.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'BankAccountDetail', 'route' => 'admin.bank-account-details.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.bankAccountDetail.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-BankAccountDetail">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.bankAccountDetail.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.bankAccountDetail.fields.account_type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.bankAccountDetail.fields.account_no') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.bankAccountDetail.fields.ifsc_code') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.bankAccountDetail.fields.bank_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.bankAccountDetail.fields.branch_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.bankAccountDetail.fields.bank_location') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bankAccountDetails as $key => $bankAccountDetail)
                                    <tr data-entry-id="{{ $bankAccountDetail->id }}">
                                        <td>
                                            {{ $bankAccountDetail->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $bankAccountDetail->account_type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $bankAccountDetail->account_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $bankAccountDetail->ifsc_code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $bankAccountDetail->bank_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $bankAccountDetail->branch_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $bankAccountDetail->bank_location ?? '' }}
                                        </td>
                                        <td>
                                            @can('bank_account_detail_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.bank-account-details.show', $bankAccountDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('bank_account_detail_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.bank-account-details.edit', $bankAccountDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('bank_account_detail_delete')
                                                <form action="{{ route('frontend.bank-account-details.destroy', $bankAccountDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('bank_account_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.bank-account-details.massDestroy') }}",
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
  let table = $('.datatable-BankAccountDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection