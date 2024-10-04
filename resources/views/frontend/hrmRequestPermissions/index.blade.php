@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('hrm_request_permission_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.hrm-request-permissions.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.hrmRequestPermission.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'HrmRequestPermission', 'route' => 'admin.hrm-request-permissions.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.hrmRequestPermission.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-HrmRequestPermission">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.user') }}
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
                                        {{ trans('cruds.hrmRequestPermission.fields.no_of_hours') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.from_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.reason') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.approved_by') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hrmRequestPermissions as $key => $hrmRequestPermission)
                                    <tr data-entry-id="{{ $hrmRequestPermission->id }}">
                                        <td>
                                            {{ $hrmRequestPermission->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->user->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->user->email_verified_at ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->user->remember_token ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $hrmRequestPermission->user->two_factor ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $hrmRequestPermission->user->two_factor ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->user->two_factor_code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->user->two_factor_expires_at ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->no_of_hours ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->from_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->reason ?? '' }}
                                        </td>
                                        <td>
                                            {{ $hrmRequestPermission->approved_by ?? '' }}
                                        </td>
                                        <td>
                                            @can('hrm_request_permission_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.hrm-request-permissions.show', $hrmRequestPermission->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('hrm_request_permission_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.hrm-request-permissions.edit', $hrmRequestPermission->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('hrm_request_permission_delete')
                                                <form action="{{ route('frontend.hrm-request-permissions.destroy', $hrmRequestPermission->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('hrm_request_permission_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.hrm-request-permissions.massDestroy') }}",
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
  let table = $('.datatable-HrmRequestPermission:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection