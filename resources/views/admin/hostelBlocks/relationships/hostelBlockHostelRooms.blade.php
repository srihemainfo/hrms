<div class="m-3">
    @can('hostel_room_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-outline-success" href="{{ route('admin.hostel-rooms.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.hostelRoom.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.hostelRoom.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-hostelBlockHostelRooms">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.hostelRoom.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.hostelRoom.fields.hostel_block') }}
                            </th>
                            <th>
                                {{ trans('cruds.hostelRoom.fields.hostel_room_no') }}
                            </th>
                            <th>
                                {{ trans('cruds.hostelRoom.fields.total_beds') }}
                            </th>
                            <th>
                                {{ trans('cruds.hostelRoom.fields.amount') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hostelRooms as $key => $hostelRoom)
                            <tr data-entry-id="{{ $hostelRoom->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $hostelRoom->id ?? '' }}
                                </td>
                                <td>
                                    {{ $hostelRoom->hostel_block->block_name ?? '' }}
                                </td>
                                <td>
                                    {{ $hostelRoom->hostel_room_no ?? '' }}
                                </td>
                                <td>
                                    {{ $hostelRoom->total_beds ?? '' }}
                                </td>
                                <td>
                                    {{ $hostelRoom->amount ?? '' }}
                                </td>
                                <td>
                                    @can('hostel_room_show')
                                        <a class="btn btn-xs btn-outline-primary" href="{{ route('admin.hostel-rooms.show', $hostelRoom->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('hostel_room_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.hostel-rooms.edit', $hostelRoom->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('hostel_room_delete')
                                        <form action="{{ route('admin.hostel-rooms.destroy', $hostelRoom->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-outline-danger" value="{{ trans('global.delete') }}">
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
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('hostel_room_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.hostel-rooms.massDestroy') }}",
    className: 'btn-outline-danger',
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
  let table = $('.datatable-hostelBlockHostelRooms:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
