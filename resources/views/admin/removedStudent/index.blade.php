@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
           Removed Students List
        </div>

        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-removedStudent text-center">
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
                            Register No
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            Academic year
                        </th>
                        <th>
                            Section
                        </th>
                        <th>
                            Reason
                        </th>
                        <th>
                            Removed Date
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

        $(document).ready(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.removed-students.index') }}",
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
                        data: 'Course',
                        name: 'enroll_master'
                    },
                    {
                        data: 'semester',
                        name: 'enroll_master'
                    },
                    {
                        data: 'AcademicYear',
                        name: 'enroll_master'
                    },
                    {
                        data: 'Section',
                        name: 'enroll_master'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-removedStudent').DataTable(dtOverrideGlobals);
        });
    </script>
@endsection
