@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('parent_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.parent-details.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.parentDetail.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'ParentDetail', 'route' => 'admin.parent-details.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.parentDetail.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ParentDetail">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.father_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.father_mobile_no') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.fathers_occupation') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.mother_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.mother_mobile_no') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.mothers_occupation') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.guardian_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.guardian_mobile_no') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.gaurdian_occupation') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parentDetails as $key => $parentDetail)
                                    <tr data-entry-id="{{ $parentDetail->id }}">
                                        <td>
                                            {{ $parentDetail->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->father_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->father_mobile_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->fathers_occupation ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->mother_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->mother_mobile_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->mothers_occupation ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->guardian_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->guardian_mobile_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $parentDetail->gaurdian_occupation ?? '' }}
                                        </td>
                                        <td>
                                            @can('parent_detail_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.parent-details.show', $parentDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('parent_detail_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.parent-details.edit', $parentDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('parent_detail_delete')
                                                <form action="{{ route('frontend.parent-details.destroy', $parentDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('parent_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.parent-details.massDestroy') }}",
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
  let table = $('.datatable-ParentDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection