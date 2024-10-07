@extends('layouts.admin')
@section('content')
    @php
        ini_set('memory_limit', '256M');
    @endphp
    @can('staff_biometric_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                {{-- <a class="btn btn-success" href="{{ route('admin.staff-biometrics.create') }}">
                    Add Biometrics</a> --}}
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'StaffBiometric',
                    'route' => 'admin.staff-biometrics.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Staff Biometrics
        </div>

        <div class="card-body">
            <div class="row gutters">
                <div class="col-xl-9 col-lg-9 col-md- col-sm-9 col-12">
                    <form method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label class="" for="staff_code">Staff Name</label>
                                    <select class="form-control select2" name="staff_code" id="staff_code">
                                        <option value="">Select Staff</option>
                                        @foreach ($staff as $id => $key)
                                            <option value="{{ $id }}">{{ $key . ' (' . $id . ')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label class="" for="fromtime">From Date</label>
                                    <input type="text" class=" form-control date" placeholder="Enter The From Date"
                                        id="start_date" name="start_date">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label class="" id="end_date_label" for="end_date">To Date</label>
                                    <input type="text" class=" form-control date" id="end_date"
                                        placeholder="Enter The To Date" name="end_date">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="form-group" style="padding-top: 30px;">
                        <button id="searchButton" class="enroll_generate_bn" onclick="getReport()">Get
                            Report</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header">
            Staff Biometrics List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-StaffBiometric">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Date
                        </th>
                        <th>
                            Day
                        </th>
                        <th>
                            Staff Name
                        </th>
                        <th>
                            Staff Code
                        </th>
                        <th>
                            Day Punches
                        </th>
                        <th>
                            In Time
                        </th>
                        <th>
                            Out Time
                        </th>
                        <th>
                            Total Hours
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Permission
                        </th>
                        <th>
                            Details
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
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            let dtOverrideGlobals = {
                buttons: dtButtons,
                deferRender: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.staff-biometrics.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'day',
                        name: 'day'
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        data: 'staff_code',
                        name: 'staff_code'
                    },
                    {
                        data: 'day_punches',
                        name: 'day_punches'
                    },
                    {
                        data: 'in_time',
                        name: 'in_time'
                    },
                    {
                        data: 'out_time',
                        name: 'out_time'
                    },
                    {
                        data: 'total_hours',
                        name: 'total_hours'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'permission',
                        name: 'permission'
                    },
                    {
                        data: 'details',
                        name: 'details'
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
            let table = $('.datatable-StaffBiometric').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function getReport() {
            let s_date = $("#start_date").val();
            let e_date = $("#end_date").val();
            if (s_date != '') {
                if (e_date == '') {
                    Swal.fire('', 'Please Choose the End Date.', 'error');
                    return false;
                }
            }
            console.log('dfdf')
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
            if ($.fn.DataTable.isDataTable('.datatable-StaffBiometric')) {
                $('.datatable-StaffBiometric').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                deferRender: true,
                retrieve: true,
                aaSorting: [],
                ajax: {
                    url: "{{ route('admin.staff-biometrics.index') }}",
                    type: 'GET',
                    data: {
                        staff_code: $('#staff_code').val(),
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val()
                    }
                },
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'day',
                        name: 'day'
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        data: 'staff_code',
                        name: 'staff_code'
                    },
                    {
                        data: 'day_punches',
                        name: 'day_punches'
                    },
                    {
                        data: 'in_time',
                        name: 'in_time'
                    },
                    {
                        data: 'out_time',
                        name: 'out_time'
                    },
                    {
                        data: 'total_hours',
                        name: 'total_hours'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'permission',
                        name: 'permission'
                    },
                    {
                        data: 'details',
                        name: 'details'
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
            let table = $('.datatable-StaffBiometric').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        }
    </script>
@endsection
