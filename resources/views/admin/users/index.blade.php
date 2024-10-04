@extends('layouts.admin')
@section('content')
    {{-- @can('user_create') --}}
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-10">
                <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
                </a>
                {{-- <a class="btn btn-danger" href="{{ route('admin.users.block') }}">
                    Block User
                </a> --}}
                {{-- <a class="btn btn-info" href="{{ route('admin.users.unblock') }}">
                Unblock User
            </a> --}}
                {{-- <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'User', 'route' => 'admin.users.parseCsvImport']) --}}
            </div>
            <div class="col-lg-2" style="text-align:right;">
                <a class="btn btn-danger" href="{{ route('admin.users.block_list') }}">
                    Blocked List
                </a>
            </div>
        </div>
    {{-- @endcan --}}
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        {{-- <th>
                            Staff Code / Reg No
                        </th> --}}
                        <th>
                            Email
                        </th>
                        {{-- <th>
                            Role Type
                        </th> --}}
                        <th>
                            Created At
                        </th>
                        <th>
                            Roles
                        </th>
                        <th>
                            Action
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.users.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
        

            let dtOverrideGlobals = {
                buttons: dtButtons,
                // processing: true,
                // serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.users.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'created',
                        name: 'created'
                    },
                    {
                        data: 'roles',
                        name: 'roles.title'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-User').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
