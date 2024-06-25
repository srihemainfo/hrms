@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Search Honor Subject
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-xl-11 col-lg-11 col-md-12 col-sm-12 col-12">
                        <div class="row">
                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                <label for="regulation" class="required">Regulation</label>
                                <select class="form-control select2" name="regulation" id="regulation" required>
                                    <option value="">Select Regulation</option>
                                    @foreach ($regulation as $id => $entry)
                                        <option value="{{ $id }}"
                                            {{ old('regulation') == $id ? 'selected' : '' }}>
                                            {{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                <label class="required" for="department	">Department</label>
                                <select class="form-control select2 " name="department" id="department" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $id => $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                                    @foreach ($semester as $id => $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12">
                        <div class="form-group" style="padding-top: 32px;">
                            <button type="button" id="submit" name="submit" onclick="get_data()"
                                class="enroll_generate_bn">Go</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Honor Subjects List
        </div>

        <div class="card-body" style="max-width:100%;overflow-x:scroll;margin:auto;">
            <table class="table table-bordered table-striped table-hover text-center datatable datatable-Subject">
                <thead>
                    <tr>
                        <th>
                           ID
                        </th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Subject Code</th>
                        <th>Subject Title</th>
                        <th>Regulation</th>
                        <th>Subject Type</th>
                        <th>Subject Category</th>
                        <th>Lecture</th>
                        <th>Tutorial</th>
                        <th>Practical</th>
                        <th>Total Contact Periods</th>
                        <th>Credits</th>
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
        window.onload = function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('subject_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.subjects.massDestroy') }}",
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
                ajax: "{{ route('admin.honor-subjects.index') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'department',
                        name: 'department.name'
                    },
                    {
                        data: 'course',
                        name: 'course.short_form'
                    },
                    {
                        data: 'semester',
                        name: 'semester.semester'
                    },
                    {
                        data: 'subject_code',
                        name: 'subject_code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'regulation',
                        name: 'regulation.name'
                    },
                    {
                        data: 'subject_type',
                        name: 'subject_type.name'
                    },
                    {
                        data: 'subject_category',
                        name: 'subject_category.name'
                    },
                    {
                        data: 'lecture',
                        name: 'lecture'
                    },
                    {
                        data: 'tutorial',
                        name: 'tutorial'
                    },
                    {
                        data: 'practical',
                        name: 'practical'
                    },
                    {
                        data: 'contact_periods',
                        name: 'contact_periods'
                    },
                    {
                        data: 'credits',
                        name: 'credits'
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

            if ($("#regulation").val() != '' && $('#department').val() != '' && $('#course').val() != '') {
                var data = {
                    regulation: $("#regulation").val(),
                    department: $('#department').val(),
                    course: $('#course').val(),
                    semester: $('#semester').val(),

                };

                // console.log(data)
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                $.ajax({
                    url: "{{ route('admin.honor-subjects.search') }}",
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
                            columns: [
                                {
                                    data: 'id',
                                    name: 'id'
                                },
                                {
                                    data: 'department',
                                    name: 'department.name'
                                },
                                {
                                    data: 'course',
                                    name: 'course.short_form'
                                },
                                {
                                    data: 'semester',
                                    name: 'semester.semester'
                                },
                                {
                                    data: 'subject_code',
                                    name: 'subject_code'
                                },
                                {
                                    data: 'name',
                                    name: 'name'
                                },
                                {
                                    data: 'regulation',
                                    name: 'regulation.name'
                                },
                                {
                                    data: 'subject_type',
                                    name: 'subject_type.name'
                                },
                                {
                                    data: 'subject_category',
                                    name: 'subject_category.name'
                                },
                                {
                                    data: 'lecture',
                                    name: 'lecture'
                                },
                                {
                                    data: 'tutorial',
                                    name: 'tutorial'
                                },
                                {
                                    data: 'practical',
                                    name: 'practical'
                                },
                                {
                                    data: 'contact_periods',
                                    name: 'contact_periods'
                                },
                                {
                                    data: 'credits',
                                    name: 'credits'
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
