@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    }else{
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    {{-- @can('hrm_request_permission_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.hrm-request-permissions.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.hrmRequestPermission.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'HrmRequestPermission', 'route' => 'admin.hrm-request-permissions.parseCsvImport'])
        </div>
    </div>
@endcan --}}
    <div class="card">
        <div class="card-header">
            Student Leave Requests
        </div>

        <div class="card-body">
            {{-- <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $status === '0' ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-permissions.index', ['status' => 'Pending']) }}">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status === '1' ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-permissions.index', ['status' => 'Approved']) }}">Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status === '2' ? ' active' : '' }}"
                        href="{{ route('admin.hrm-request-permissions.index', ['status' => 'Rejected']) }}">Rejected</a>
                </li>
            </ul> --}}
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-HrmRequestPermission">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.hrmRequestPermission.fields.id') }}
                        </th>
                        <th>
                            {{ 'Name ' }}
                        </th>
                        <th>
                            {{ 'Register Number' }}
                        </th>
                        <th>
                            {{ 'From ' }}
                        </th>
                        <th>
                            {{ 'To' }}
                        </th>
                        <th>
                            {{ 'Reason' }}
                        </th>

                        <th>
                            {{ 'Leave Type' }}
                        </th>
                        <th>
                            {{ 'Approved/Rejected By' }}
                        </th>
                        <th>
                            {{ 'Action' }}
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
                url: "{{ route('admin.hrm-request-permissions.massDestroy') }}",
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
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.student-leave-requests.stu_index') }}",
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
                        data: 'register_no',
                        name: 'register_no'
                    },

                    {
                        data: 'from_date',
                        name: 'from_date'
                    },
                    {
                        data: 'to_date',
                        name: 'to_date'
                    },

                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'leave_type',
                        name: 'leave_type'
                    },
                    {
                        data: 'approved_by',
                        name: 'approved_by'
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
                pageLength: 25,
            };
            let table = $('.datatable-HrmRequestPermission').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
