@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('non_teaching_staff_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.non-teaching-staffs.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.nonTeachingStaff.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'NonTeachingStaff', 'route' => 'admin.non-teaching-staffs.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.nonTeachingStaff.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-NonTeachingStaff">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonTeachingStaff.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.nonTeachingStaff.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.nonTeachingStaff.fields.working_as') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nonTeachingStaffs as $key => $nonTeachingStaff)
                                    <tr data-entry-id="{{ $nonTeachingStaff->id }}">
                                        <td>
                                            {{ $nonTeachingStaff->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $nonTeachingStaff->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $nonTeachingStaff->working_as->title ?? '' }}
                                        </td>
                                        <td>
                                            @can('non_teaching_staff_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.non-teaching-staffs.show', $nonTeachingStaff->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('non_teaching_staff_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.non-teaching-staffs.edit', $nonTeachingStaff->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('non_teaching_staff_delete')
                                                <form action="{{ route('frontend.non-teaching-staffs.destroy', $nonTeachingStaff->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('non_teaching_staff_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.non-teaching-staffs.massDestroy') }}",
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
  let table = $('.datatable-NonTeachingStaff:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection