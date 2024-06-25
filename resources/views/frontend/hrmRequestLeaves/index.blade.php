@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('hrm_request_leaf_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.hrm-request-leaves.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.hrmRequestLeaf.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'HrmRequestLeaf', 'route' => 'admin.hrm-request-leaves.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.hrmRequestLeaf.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-HrmRequestLeaf">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.user') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.email_verified_at') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.remember_token') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.two_factor') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.two_factor_code') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.two_factor_expires_at') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.from_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.to_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.reason') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.approved_by') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hrmRequestLeaves as $key => $hrmRequestLeaf)
                                    <tr data-entry-id="{{ $hrmRequestLeaf->id }}">
                                        <td>
                                            {{ $hrmRequestLeaf->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->user->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->user->email_verified_at ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->user->remember_token ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $hrmRequestLeaf->user->two_factor ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $hrmRequestLeaf->user->two_factor ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->user->two_factor_code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->user->two_factor_expires_at ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->from_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->to_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->reason ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestLeaf->approved_by ?? '' }}
                                        </td>
                                        <td>
                                            @can('hrm_request_leaf_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.hrm-request-leaves.show', $hrmRequestLeaf->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('hrm_request_leaf_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.hrm-request-leaves.edit', $hrmRequestLeaf->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('hrm_request_leaf_delete')
                                                <form action="{{ route('frontend.hrm-request-leaves.destroy', $hrmRequestLeaf->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('hrm_request_leaf_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.hrm-request-leaves.massDestroy') }}",
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
  let table = $('.datatable-HrmRequestLeaf:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection