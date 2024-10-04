@extends('layouts.admin')
@section('content')
    <style>
        table.dataTable tbody td.select-checkbox::before {
            content: none !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Search Students
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course" required>
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <label for="semester" class="">Semester</label>
                        <select class="form-control select2" name="semester" id="semester">
                            <option value="">Select Semester</option>
                            @foreach ($semesters as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="padding-top: 32px;">
                        <button type="button" id="submit" name="submit" onclick="get_data()"
                            class="enroll_generate_bn">Go</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Honors Degree List
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center datatable datatable-Subject"
                style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Class</th>
                        <th>Students</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        window.onload = function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.honor-subjects-report.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'class',
                        name: 'class'
                    },
                    {
                        data: 'students',
                        name: 'students'
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
            let table = $('.datatable-Subject').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });



        };

        function get_data() {

            if ($('#course').val() != '') {
                var data = {
                    course: $('#course').val(),
                    semester: $('#semester').val(),

                };

                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                $.ajax({
                    url: "{{ route('admin.honor-subjects-report.search') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    success: function(response) {
                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            deferRender: true,
                            retrieve: true,
                            aaSorting: [],
                            data: response.data,
                            columns: [{
                                    data: 'id',
                                    name: 'id'
                                },
                                {
                                    data: 'class',
                                    name: 'class'
                                },
                                {
                                    data: 'students',
                                    name: 'students'
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

                        let table = $('.datatable-Subject').DataTable();
                        table.destroy();
                        table = $('.datatable-Subject').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                    }
                });
            } else {
                alert('Please Provide the Required Fields..');
            }
        }
    </script>
@endsection
