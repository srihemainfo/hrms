@extends('layouts.admin')
@section('content')
    @can('staff_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.staffs.create') }}">
                    Add Staff
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'Staffs',
                    'route' => 'admin.staffs.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
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


        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(14px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Staffs List
        </div>
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Staffs text-center">
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
                            Employee Id
                        </th>
                        <th>
                            Role
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Contact Number
                        </th>
                        <th>
                            Profile Edit Access
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
        <div class="secondLoader"></div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);

            let deleteButton = {
                text: 'Delete Selected',
                className: 'btn-outline-danger',
                action: function(e, dt, node, config) {
                    var ids = $.map(dt.rows({
                        selected: true
                    }).data(), function(entry) {
                        return entry.id
                    });

                    if (ids.length === 0) {
                        Swal.fire('', 'No Rows Selected', 'warning');

                        return
                    }

                    Swal.fire({
                        title: "Are You Sure?",
                        text: "Do You Really Want To Delete !",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.value) {
                            $('.secondLoader').show()
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: "{{ route('admin.staffs.massDestroy') }}",
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function(response) {
                                    Swal.fire('', response.data, response.status);
                                    $('.secondLoader').hide()
                                    callAjax()
                                })
                        }
                    })
                }
            }
            dtButtons.push(deleteButton)

            if ($.fn.DataTable.isDataTable('.datatable-Staffs')) {
                $('.datatable-Staffs').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.staffs.index') }}",
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
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'edit_access',
                        name: 'edit_access',
                        render: function(data, type, row) {
                            var checked = data ? 'checked' : '';
                            return `
                <label class="switch">
                    <input type="checkbox" class="toggle-edit-access" data-id="${row.id}" ${checked}>
                    <span class="slider round"></span>
                </label>`;
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'active_status',
                        name: 'active_status',
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                if (data) {
                                    var buttonLabel = '';
                                    var buttonClass = '';

                                    if (data === 'Active') {
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
                        name: 'actions'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-Staffs').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };


        $(document).on('change', '.toggle-edit-access', function() {
            $(".secondLoader").show();
            var userId = $(this).data('id');
            var editAccess = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('admin.staffs.updateEditAccess') }}",
                type: 'POST',
                headers: {
                    'x-csrf-token': _token
                },
                data: {
                    id: userId,
                    edit_access: editAccess
                },
                success: function(response) {
                    Swal.fire('', response.data, 'success');
                    $(".secondLoader").hide();
                },
                error: function(response) {
                    Swal.fire('Error', 'An error occurred', 'error');
                    $(".secondLoader").hide();

                }
            });
        });
    </script>
@endsection
