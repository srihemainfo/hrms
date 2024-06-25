@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.users.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
    <div class="card">

        <div class="card-header">
            Blocked {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-User text-center">
                <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Staff Code
                        </th>
                        <th>
                            Register Number
                        </th>
                        <th>
                            Roles
                        </th>
                        <th>Blocked Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $data)
                        <tr>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->employID }}</td>
                            <td>{{ $data->register_no }}</td>
                            <td><span
                                    style="padding: 0.40rem 0.75rem;background-color:#148ea1;border-radius:3px;color:white;">{{ $data->roles[0]->title }}</span>
                            </td>
                            <td>{{ $data->block_reason }}</td>
                            <td>

                                <a href="{{ route('admin.users.unblock_user',['user' => $data->id]) }}" class="btn btn-xs btn-success" style="color:white;">Unblock</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    {{-- <script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.users.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
// { data: 'name', name: 'name' },
{ data: 'email', name: 'email' },
{ data: 'email_verified_at', name: 'email_verified_at' },
{ data: 'roles', name: 'roles.title' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-User').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script> --}}
@endsection
