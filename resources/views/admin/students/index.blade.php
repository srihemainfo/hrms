@extends('layouts.admin')
@section('content')
    @can('student_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.students.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.student.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'Student',
                    'route' => 'admin.students.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    @if ($error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <strong>Student Search</strong>
        </div>
        <div class="card-body row">

            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                <label for="academicYear" class="required">Academic year</label>
                <select class="form-control select2" name="academicYear" id="academicYear" required>
                    <option value="">Please Select</option>
                    @foreach ($academicYear as $id => $entry)
                        <option value="{{ $entry }}">
                            {{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            {{-- @if (auth()->user()->roles[0]->id != '14') --}}
            @php
                $dept = auth()->user()->dept;
                $deptModel = \App\Models\ToolsDepartment::where('name', $dept)->first();
                if ($deptModel) {
                    $courseModel = \App\Models\ToolsCourse::where('department_id', $deptModel->id)->first();
                } else {
                    $courseModel = '';
                }

                $selectedCourse = $courseModel ? $courseModel->name : '';
            @endphp
            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                <select class="form-control select2 " name="course" id="course" required>
                    <option value="">Please Select</option>
                    @foreach ($courses as $id => $entry)
                        <option value="{{ $entry }}" {{ $selectedCourse == $entry ? 'selected' : '' }}>
                            {{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            @php
                $role_id = auth()->user()->roles[0]->id;
                $dept = auth()->user()->dept;
                if ($role_id == 14 && $dept != null) {
                    if ($dept == 'S & H') {
                        $semester = [1, 2];
                    } else {
                        $semester = [3, 4, 5, 6, 7, 8];
                    }
                } else {
                    $semester = [1, 2, 3, 4, 5, 6, 7, 8];
                }
            @endphp

            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                <label for="semester" class="required">Semester</label>
                <select class="form-control select2" name="semester" id="semester" required>
                    <option value="">Please Select</option>
                    @foreach ($semester as $id => $entry)
                        <option value="{{ $entry }}">
                            {{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                <label for="section" class="required">Section</label>
                <select class="form-control select2" name="section" id="section" required>
                    <option value="">Please Select</option>
                    @foreach ($section as $id => $entry)
                        <option value="{{ $entry }}">
                            {{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-3">
                <button class="btn btn-primary" style="margin-top: 30px;" id="search">Search</button>
            </div>

        </div>
    </div>
    <div class="card">

        <div class="card-header">
            {{ trans('cruds.student.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Student text-center">
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
        var $selects = $('#academicYear, #course, #semester, #section');
        $('#search').click(function() {
            var allFilled = true;
            var missingFields = [];

            $selects.each(function() {
                if ($(this).val() === '') {
                    allFilled = false;
                    missingFields.push($(this).attr('id'));
                }
            });

            var token = $('meta[name="csrf-token"]').attr('content');


            if (allFilled) {
                var data = {
                    academicYear: $('#academicYear').val(),
                    course: $('#course').val(),
                    semester: $('#semester').val(),
                    section: $('#section').val(),

                    _token: token
                };

                $.ajax({
                    url: "{{ route('admin.students.search') }}",
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        console.log(response.data);

                        let dtOverrideGlobals = {
                            // buttons: dtButtons,
                            // processing: true,
                            // serverSide: true,
                            deferRender: true,
                            retrieve: true,
                            aaSorting: [],
                            data: response.data,
                            columns: [{
                                    data: 'empty',
                                    name: 'empty',
                                    render: function(data, type, full, meta) {
                                        // Add static data here
                                        return ' ';
                                    }
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
                                // { data: 'Department', name: 'enroll_master' },
                                {
                                    data: 'AccademicYear',
                                    name: 'enroll_master'
                                },
                                {
                                    data: 'Section',
                                    name: 'enroll_master'
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
                        let table = $('.datatable-Student').DataTable(dtOverrideGlobals);
                        table.destroy();
                        table = $('.datatable-Student').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // console.log(textStatus, errorThrown);
                        if (jqXHR.status === 400 || jqXHR.status === 404) {
                            var errorMessage = jqXHR.responseJSON.errors;
                            Swal.fire({
                                icon: 'error',
                                title: 'No Data Found',
                                text: errorMessage,
                            });
                        }
                    }
                });

            } else {
                var errorMessage = 'Please fill in the following fields: ' + missingFields.join(', ');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                });
            }
        });

        $(document).ready(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.students.index') }}",
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
                        data: 'AccademicYear',
                        name: 'enroll_master'
                    },
                    {
                        data: 'Section',
                        name: 'enroll_master'
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
            let table = $('.datatable-Student').DataTable(dtOverrideGlobals);
        });

        function deleteStudent(element) {

            let url = $(element).attr('id');
            let id = $(element).data('id');
            Swal.fire({
                title: 'Delete Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            '_method': 'DELETE',
                            'id': id,
                            'reason': reason
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Done!',
                                'Student Deleted Successfully',
                                'success'
                            )
                            location.reload();
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            Swal.fire('', xhr.responseText, 'error');
                        }
                    });


                },
                // allowOutsideClick: () => !Swal.isLoading()
            })

        }
    </script>
@endsection
