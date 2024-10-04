@extends('layouts.admin')
@section('content')
    @can('driver_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-outline-success" href="{{ route('admin.driver.create') }}">
                    {{ trans('global.add') }} Driver
                </a>
                {{-- <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'NonTeachingStaff',
                    'route' => 'admin.driver.parseCsvImport',
                ]) --}}
            </div>
        </div>
    @endcan
    <style>
        .status.open:before {
            background-color: #94E185;
            border-color: #78D965;
            box-shadow: 0px 0px 4px 1px #94E185;
        }

        .status.dead:before {
            background-color: #C9404D;
            border-color: #C42C3B;
            box-shadow: 0px 0px 4px 1px #C9404D;
        }

        .status:before {
            content: ' ';
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 10px;
            border: 1px solid #000;
            border-radius: 10px;
        }
    </style>
    <style>
        .toggle-wrapper {
            display: inline-block;
            position: relative;
            border-radius: 3.125em;
            overflow: hidden;
        }

        .toggle-checkbox {
            -webkit-appearance: none;
            appearance: none;
            position: absolute;
            z-index: 1;
            top: 0;
            left: 0;
            border-radius: inherit;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .toggle-container {
            display: flex;
            position: relative;
            border-radius: inherit;
            width: 3em;
            height: 1.5em;
            background-color: #d1d4dc;
            box-shadow: inset 0.0625em 0 0 #d4d2de, inset -0.0625em 0 0 #d4d2de, inset 0.125em 0.25em 0.125em 0.25em #b5b5c3;
            mask-image: radial-gradient(#fff, #000);
            transition: all 0.4s;
        }

        .toggle-wrapper.blue>.toggle-checkbox:checked+.toggle-container {
            background-color: #6c21e9;
            box-shadow: inset 0.0625em 0 0 #4f1fe3, inset -0.0625em 0 0 #4f1fe3, inset 0.125em 0.25em 0.125em 0.25em #2b3f7e;
        }

        .toggle-ball {
            position: relative;
            border-radius: 50%;
            width: 1.5em;
            height: 1.5em;
            background-image: radial-gradient(rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0) 16%), radial-gradient(#d2d4dc, #babac2);
            background-position: -0.25em -0.25em;
            background-size: auto, calc(100% + 0.25em) calc(100% + 0.25em);
            background-repeat: no-repeat;
            box-shadow: 0.25em 0.25em 0.25em #8d889e, inset 0.0625em 0.0625em 0.25em #d1d1d6, inset -0.0625em -0.0625em 0.25em #8c869e;
            transition: transform 0.4s, box-shadow 0.4s;
        }

        .toggle-ball::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 50%;
            width: 100%;
            height: 100%;
            background-position: -0.25em -0.25em;
            background-size: auto, calc(100% + 0.25em) calc(100% + 0.25em);
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .toggle-wrapper.blue>.toggle-container>.toggle-ball::after {
            background-image: radial-gradient(rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0) 16%), radial-gradient(#7c27ff, #33008b);
            box-shadow: 0.25em 0.25em 0.25em #1e0066, inset 0.0625em 0.0625em 0.25em #7773bb, inset -0.0625em -0.0625em 0.25em #010046;
        }

        .toggle-wrapper>.toggle-checkbox:checked+.toggle-container>.toggle-ball::after {
            opacity: 1;
        }

        .toggle-checkbox:checked+.toggle-container>.toggle-ball {
            transform: translateX(100%);
        }
    </style>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>

    <div class="card">
        <div class="card-header">
           Driver {{ trans('global.list') }}
        </div>

        <div class="card-body">

            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Driver text-center">
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
                        <th>
                            Staff Code
                        </th>
                        <th>
                            Working As
                        </th>
                        <th>
                            Role Type
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Past Leave Access
                        </th>
                        <th>
                            Status
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
            @can('driver_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.driver.massDestroy') }}",
                    className: 'btn-outline-danger',
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
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.driver.index') }}",
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
                        data: 'StaffCode',
                        name: 'StaffCode'
                    },
                    {
                        data: 'Designation',
                        name: 'Designation'
                    },
                    {
                        data: 'teach_type',
                        name: 'teach_type'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'past_leave_access',
                        name: 'past_leave_access'
                    },
                    {
                        data: 'active_status',
                        name: 'active_status',
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                if (data) {
                                    var buttonLabel = '';
                                    var buttonClass = '';

                                    if (data == 'Active') {
                                        buttonLabel = 'Active';
                                        buttonClass = 'status open';
                                    } else {
                                        buttonLabel = 'Inactive';
                                        buttonClass = 'status dead';
                                    }

                                    var button = $('<span>').addClass(buttonClass).text(buttonLabel);
                                    return $('<div>').append(button).html();
                                } else {
                                    return '<p>Status Not Updated</p>';
                                }
                            }

                            return data;
                        },
                        type: 'html',
                        className: 'text-center'
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
            let table = $('.datatable-Driver').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function attControl(checkbox) {
            $('#loading').show()
            let db_id = $(checkbox).data('class');
            let status = 0;
            if (checkbox.checked) {
                status = 1;
            }

            $.ajax({
                url: '{{ route('admin.past_leave_apply_Non_Teaching_access') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': db_id,
                    'status': status
                },
                success: function(response) {
                    if (response.status == true) {
                        Swal.fire('', response.data, 'success');
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                    $('#loading').hide()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                            "error");
                    }
                }
            });
        }
    </script>
@endsection
