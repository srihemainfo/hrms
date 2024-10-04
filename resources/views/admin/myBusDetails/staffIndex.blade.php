@extends('layouts.non_techStaffHome')
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }

        .driver {
            display: flex;
            /* justify-content: space-evenly; */
            /* margin-left: 200px; */

        }
    </style>
    <div class="row gutters driver">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="card text-Dark shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="card-title text-primary">Bus No</span>
                        </div>
                        <div class="col text-center">
                            <span class="card-title">{{ $bus_driver[0]->bus_no }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="card text-Dark shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="card-title text-primary">Destination</span>
                        </div>
                        <div class="col text-center">
                            <span class="card-title">{{ $bus_driver[0]->designation }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="card text-Dark shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="card-title text-primary">Student Count</span>
                        </div>
                        <div class="col text-center">
                            <span class="card-title">{{ $student_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="card" style="margin-top: 50px;">
        <div class="card-header">
            Bus Student List
        </div>
        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DriverIndex text-center">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            SNo
                        </th>
                        {{-- <th>
                                    Bus No
                                </th>
                                <th>
                                    Designation
                                </th> --}}
                        <th>
                            Stop Name
                        </th>
                        <th>
                            Student Name
                        </th>
                        <th>
                            Student Phone No
                        </th>
                    </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            $('#loading_div').hide();
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(1, 3);
            dtButtons.splice(4, 4);

            if ($.fn.DataTable.isDataTable('.datatable-DriverIndex')) {
                $('.datatable-DriverIndex').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.driver.staffIndex') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'sno',
                        name: 'sno'
                    },
                    {
                        data: 'stop',
                        name: 'stop'
                    },
                    {
                        data: 'student',
                        name: 'student'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },

                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-DriverIndex').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        };
    </script>
@endsection
