@extends('layouts.admin')
@section('content')
    @can('teaching_staff_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.teaching-staffs.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.teachingStaff.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'TeachingStaff',
                    'route' => 'admin.teaching-staffs.parseCsvImport',
                ])
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
            background-color: #55292e;
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
        .toggle {
            position: relative;
            width: 60%;
            margin: auto;
        }

        .toggle:before {
            content: '';
            position: absolute;
            border-bottom: 3px solid #fff;
            border-right: 3px solid #fff;
            width: 6px;
            height: 14px;
            z-index: 2;
            transform: rotate(45deg);
            top: 8px;
            left: 15px;
        }

        .toggle:after {
            content: '×';
            position: absolute;
            top: -6px;
            left: 49px;
            z-index: 2;
            line-height: 42px;
            font-size: 26px;
            color: #aaa;
        }

        .toggle input[type="checkbox"] {
            position: absolute;
            left: 0;
            top: 0;
            z-index: 10;
            width: 100%;
            height: 100%;
            cursor: pointer;
            opacity: 0;
        }

        .toggle label {
            position: relative;
            display: flex;
            align-items: center;
        }

        .toggle label:before {
            content: '';
            width: 70px;
            height: 30px;
            box-shadow: 0 0 1px 2px #0001;
            background: #eee;
            position: relative;
            display: inline-block;
            border-radius: 46px;
        }

        .toggle label:after {
            content: '';
            position: absolute;
            width: 31px;
            height: 29px;
            border-radius: 50%;
            left: 0;
            top: 0;
            z-index: 5;
            background: #fff;
            box-shadow: 0 0 5px #0002;
            transition: 0.2s ease-in;
        }

        .toggle input[type="checkbox"]:hover+label:after {
            box-shadow: 0 2px 15px 0 #0002, 0 3px 8px 0 #0001;
        }

        .toggle input[type="checkbox"]:checked+label:before {
            transition: 0.1s 0.2s ease-in;
            background: #4BD865;
        }

        .toggle input[type="checkbox"]:checked+label:after {
            left: 38px;
        }
    </style>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.teachingStaff.title_singular') }} {{ trans('global.list') }}
        </div>



        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TeachingStaff">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>ID</th>
                        {{-- <th>
                            Staff Code
                        </th> --}}
                        <th>
                            Name
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Designation
                        </th>
                        {{-- <th>
                            Role Type
                        </th> --}}
                        {{-- <th>
                            Shift
                        </th> --}}
                        <th>
                            Past Leave Access Control
                        </th>
                        {{-- <th>
                            Status
                        </th> --}}
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
            @can('teaching_staff_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.teaching-staffs.massDestroy') }}",
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
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,

                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.teaching-staffs.index') }}",

                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    // {
                    //     data: 'StaffCode',
                    //     name: 'StaffCode'
                    // },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'Dept',
                        name: 'Dept'
                    },
                    {
                        data: 'Designation',
                        name: 'Designation'
                    },
                    // {
                    //     data: 'teach_type',
                    //     name: 'teach_type'
                    // },
                    // {
                    //     data: 'shift',
                    //     name: 'shift'
                    // },
                     {
                        data: 'past_leave_access',
                        name: 'past_leave_access'
                    },
                    // {
                    //     data: 'active_status',
                    //     name: 'active_status',
                    //     render: function(data, type, row) {
                    //         if (type === 'display' || type === 'filter') {
                    //             if (data) {
                    //                 var buttonLabel = '';
                    //                 var buttonClass = '';

                    //                 if (data === 'Active') {
                    //                     buttonLabel = 'Active';
                    //                     buttonClass = 'status open';
                    //                 } else {
                    //                     buttonLabel = 'Inactive';
                    //                     buttonClass = 'status dead';
                    //                 }

                    //                 var button = $('<span>').addClass(buttonClass).text(buttonLabel);
                    //                 return $('<div>').append(button).html();
                    //             } else {
                    //                 return '<p>Status Not Updated</p>';
                    //             }
                    //         }

                    //         return data;
                    //     },
                    //     type: 'html',
                    //     className: 'text-center'
                    // },
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
            let table = $('.datatable-TeachingStaff').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function attControl(checkbox) {
            let db_id = $(checkbox).data('class');
            let status = 0;
            if (checkbox.checked) {
                status = 1;
            }
            $('#loading').show()

            $.ajax({
                url: '{{ route('admin.past_leave_apply_access') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': db_id,
                    'status': status
                },
                success: function (response) {
                    if (response.status == true) {

                        Swal.fire('', response.data, 'success');
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                    $('#loading').hide()
                }
            });
        }
    </script>
@endsection
