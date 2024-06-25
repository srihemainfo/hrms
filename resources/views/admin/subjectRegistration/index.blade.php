@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', [
                'model' => 'SubjectRegistration',
                'route' => 'admin.subject-registrations.parseCsvImport',
            ])
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Subject Registration List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover datatable datatable-SubjectRegistration">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Student Name
                        </th>
                        <th>
                            Register No
                        </th>
                        <th>
                           Enroll Master
                        </th>
                        <th>
                            &nbsp;
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
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.subject-registrations.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'student_name',
                        name: 'students.name'
                    },
                    {
                        data: 'register_no',
                        name: 'register_no'
                    },
                    {
                        data: 'enroll_master',
                        name: 'enroll_masters.enroll_master_number'
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
            let table = $('.datatable-SubjectRegistration').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
