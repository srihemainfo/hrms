@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('academic_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.academic-details.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.academicDetail.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'AcademicDetail', 'route' => 'admin.academic-details.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.academicDetail.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-AcademicDetail">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicDetail.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.academicDetail.fields.enroll_master_number') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.academicDetail.fields.register_number') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.academicDetail.fields.emis_number') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($academicDetails as $key => $academicDetail)
                                    <tr data-entry-id="{{ $academicDetail->id }}">
                                        <td>
                                            {{ $academicDetail->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicDetail->enroll_master_number->enroll_master_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicDetail->register_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicDetail->emis_number ?? '' }}
                                        </td>
                                        <td>
                                            @can('academic_detail_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.academic-details.show', $academicDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('academic_detail_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.academic-details.edit', $academicDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('academic_detail_delete')
                                                <form action="{{ route('frontend.academic-details.destroy', $academicDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('academic_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.academic-details.massDestroy') }}",
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
  let table = $('.datatable-AcademicDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection