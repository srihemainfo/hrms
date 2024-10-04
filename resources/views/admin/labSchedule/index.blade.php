@php
   $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    }elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    }else{
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }

        @media screen and (max-width: 575px) {
            .select2 {
                width: 100% !important;
            }
        }

        @media screen and (max-width: 1366px) {
            .select2 {
                width: 100% !important;
            }
        }
    </style>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div style="margin-bottom: 10px; display:flex;" class="row ">
        {{-- @can('examTimetable_create') --}}
        <div class="pl-2">
            <a class="btn btn-success" href="{{ route('admin.lab_schedule.create') }}">
                Create LAB Schedule
            </a>
        </div>
        {{-- @endcan --}}
    </div>
    <div class="card">
        <div class="card-header text-center">
            <strong>Search</strong>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required d-block" for="AcademicYear">Academic Year</label>
                    <select class="form-control select2" name="AcademicYear" id="AcademicYear" required>
                        <option value="">Please Select</option>
                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="SemesterType" class="required d-block">Semester Type</label>
                    <select class="form-control select2" name="SemesterType" id="SemesterType">
                        <option value="">Select Year</option>
                        <option value="ODD">ODD</option>
                        <option value="EVEN">EVEN</option>
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required d-block" for="examName">Mark Type</label>
                    <select class="form-control select2" name="MarkType" id="MarkType" required>
                        <option value="">Please Select</option>
                        @foreach ($MarkType as $id => $entry)
                            <option value="{{ $entry }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id='Search' style="padding-top: 32px;">
                    <button class="manual_bn" onclick="search()">Filter</button>
                </div>
                <div class="form-group" id='load' style="padding-top: 32px;display:none">
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                </div>
            </div>

            <div class="row" id="firstHide" style="display: none">
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="d-block" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <select class="form-control select2 " name="course" id="course">
                        <option value="">Please Select</option>
                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="d-block" for="year">Year</label>
                    <select class="form-control select2 " name="year" id="year">
                        <option value="">Select Year</option>
                        <option value="I">I</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="d-block" for="Semester">Semester</label>
                    <select class="form-control select2 " name="Semester" id="semester">
                        <option value="">Please Select</option>
                        @foreach ($semester as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="d-block" for="Section">Section</label>
                    <select class="form-control select2 " name="Section" id="section">
                        <option value="">Please Select</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                {{--
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="year" class="d-block">Year</label><br>
                    <select class="form-control select2" name="year" id="year">
                        <option value="">Select Year</option>
                        <option value="I">I</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="semester" class="d-block">Semester</label><br>
                    <select class="form-control select2" name="semester" id="semester">
                        <option value="">Please Select</option>
                        @foreach ($semester as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="section" class="d-block">Sections</label>
                    <br>
                    <select class="form-control select2" name="section" id="section">
                        <option value="">Please Select</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                 --}}
            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-header text-center">
            <strong> LAB Schedule {{ trans('global.list') }}</strong>
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-events text-center">
                <thead>
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>
                            Exam Title
                        </th>
                        <th>
                            Academic Year
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Year
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            Section
                        </th>
                        <th>
                            Due Date
                        </th>

                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success ') }}",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error ') }}",
            });
        </script>
    @endif
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $('#loading').show();
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.lab_mark.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'exam_name',
                        name: 'exam_name'
                    },
                    {
                        data: 'accademicYear',
                        name: 'accademicYear'
                    },
                    {
                        data: 'course_id',
                        name: 'course_id'
                    },
                    {
                        data: 'year',
                        name: 'year'
                    },
                    {
                        data: 'semester',
                        name: 'semester'
                    },

                    {
                        data: 'section',
                        name: 'section'
                    },

                    {
                        data: 'due_date',
                        name: 'due_date'
                    },

                    {
                        data: 'actions',
                        name: "{{ trans('global.actions ') }}"
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,

            };
            let table = $('.datatable-events').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
            $('#loading').hide();

        });

        let mainResourse = null;
        let check = false;
        $('#course, #section,#year,#semester').on('change', function() {
            var section = $('#section').val();
            var course = $('#course').val();
            var year = $('#year').val();
            var semester = $('#semester').val();
            var filteredElements = mainResourse.filter(function(item) {
                // Apply filtering conditions here
                return (
                    (section === '' || item.section === section) &&
                    (course === '' || item.course === course) &&
                    (year === '' || item.year === year) &&
                    (semester === '' || item.semester === semester)
                );
            });
            if (check) {
                let dtOverrideGlobals = {

                    deferRender: true,
                    retrieve: true,
                    aaSorting: [],
                    data: filteredElements,
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'exam_name',
                            name: 'exam_name'
                        },
                        {
                            data: 'accademicYear',
                            name: 'accademicYear'
                        },
                        {
                            data: 'course_id',
                            name: 'course_id'
                        },
                        {
                            data: 'year',
                            name: 'year'
                        },
                        {
                            data: 'semester',
                            name: 'semester'
                        },

                        {
                            data: 'section',
                            name: 'section'
                        },

                        {
                            data: 'due_date',
                            name: 'due_date'
                        },

                        {
                            data: 'actions',
                            name: "{{ trans('global.actions ') }}"
                        }
                    ],
                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 10,
                };

                let table = $('.datatable-events').DataTable(dtOverrideGlobals);
                table.destroy();
                table = $('.datatable-events').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
                $('#Search').show();
                $('#load').hide();
            }
            // }
        });

        function search() {
            $('#loading').show();
            var academicYear_id = $('#AcademicYear').val();
            var SemesterType = $('#SemesterType').val();
            var MarkType = $('#MarkType').val();

            var $section = $('#section');
            var $semester = $('#semester');
            var $course = $('#course');
            var $year = $('#year');

            var token = $('meta[name="csrf-token"]').attr('content');

            $('#Search').hide();
            $('#load').show();

            var data = {};

            if (academicYear_id !== '') {
                data['academicYear_id'] = academicYear_id;
            }
            if (SemesterType !== '') {
                data['SemesterType'] = SemesterType;
            }

            if (MarkType !== '') {
                data['MarkType'] = MarkType;
            }
            if (token !== '') {
                data['_token'] = token;
            }

            // Check if requestData is empty
            if (Object.keys(data).length === 1) {
                $('#loading').hide();
                Swal.fire({
                    icon: 'info',
                    title: 'Please',
                    text: ' fill in at least one field.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
                $('#Search').show();
                $('#load').hide();
                return;
            }

            $.ajax({
                url: "{{ route('admin.lab_schedule.search') }}",
                method: 'POST',
                data: data,
                success: function(response) {

                    $("#AcademicYear").select2();
                    $("#SemesterType").select2();
                    $("#MarkType").select2();
                    $('#course').select2();
                    $('#year').select2();
                    $('#semester').select2();
                    $('#section').select2();

                    var length = response.data.collection.length;
                    // console.log(response.data.collection);
                    // console.log(length);

                    if (length > 0) {
                        $('#firstHide').show();
                        mainResourse = response.data.collection;
                        check = true;
                        $('#loading').hide();
                        $('#section').select2({
                            width: '160px'
                        });
                        $('#course').select2({
                            width: '160px'
                        });
                        $('#year').select2({
                            width: '160px'
                        });
                        $('#semester').select2({
                            width: '160px'
                        });
                        $('#loading').hide();
                    } else {
                        $('#firstHide').hide();
                        check = false;
                        $('#loading').hide();
                        $('#loading').hide();
                    }
                    let sections_get = response.sections;
                    let sections_get_length = Object.keys(sections_get).length;
                    let semester_get = response.semester;
                    let semester_get_length = Object.keys(semester_get).length;
                    let course_get = response.course;
                    let course_get_length = Object.keys(course_get).length;
                    let year_get = response.year;
                    let year_get_length = Object.keys(year_get).length;

                    let course = '';
                    if (course_get_length > 0) {
                        course += ' <option value="">Select Course</option>';
                        for (const key in course_get) {
                            course +=
                                `<option style="color:blue;" ${key == $course.val() ?? '' ? 'selected' : '' }  value="${key}"> ${course_get[key]}</option>`;
                        }
                    } else {
                        course += ' <option value=""> Course Not Available</option>';

                    }


                    $course.html(course);

                    let year = '';
                    if (year_get_length > 0) {
                        year += ' <option value="">Select Year</option>';
                        for (const key in year_get) {
                            year +=
                                `<option style="color:blue;" ${year_get[key]  == $year.val() ?? '' ? 'selected' : '' }  value="${year_get[key] }"> ${year_get[key] }</option>`;
                        }
                    } else {
                        year += ' <option value=""> Year Not Available</option>';

                    }


                    $year.html(year);
                    let semester = '';
                    if (semester_get_length > 0) {
                        semester += ' <option value="">Select Semester</option>';
                        for (const key in semester_get) {
                            semester +=
                                `<option style="color:blue;" ${key == $semester.val() ?? '' ? 'selected' : '' }  value="${key}"> ${semester_get[key] }</option>`;
                        }
                    } else {
                        semester += ' <option value=""> Semester Not Available</option>';

                    }


                    $semester.html(semester);


                    let sec = '';
                    if (sections_get_length > 0) {
                        sec += ' <option value="">Select Section</option>';
                        for (const key in sections_get) {
                            sec +=
                                `<option style="color:blue;" ${key== $section.val() ?? '' ? 'selected' : '' }  value="${key}"> ${sections_get[key] }</option>`;
                        }
                    } else {
                        sec += ' <option value=""> Section Not Available</option>';

                    }


                    $section.html(sec);

                    let dtOverrideGlobals = {
                        deferRender: true,
                        retrieve: true,
                        aaSorting: [],
                        data: response.data.collection,
                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'exam_name',
                                name: 'exam_name'
                            },
                            {
                                data: 'accademicYear',
                                name: 'accademicYear'
                            },
                            {
                                data: 'course_id',
                                name: 'course_id'
                            },
                            {
                                data: 'year',
                                name: 'year'
                            },
                            {
                                data: 'semester',
                                name: 'semester'
                            },

                            {
                                data: 'section',
                                name: 'section'
                            },
                            {
                                data: 'due_date',
                                name: 'due_date'
                            },


                            {
                                data: 'actions',
                                name: "{{ trans('global.actions ') }}"
                            }
                        ],
                        orderCellsTop: true,
                        order: [
                            [1, 'desc']
                        ],
                        pageLength: 10,
                    };

                    let table = $('.datatable-events').DataTable(dtOverrideGlobals);
                    table.destroy();
                    table = $('.datatable-events').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable()
                            .columns.adjust();
                    });
                    $('#Search').show();
                    $('#load').hide();
                    $('#loading').hide();

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    $('#Search').show();
                    $('#load').hide();
                    $('#loading').hide();
                }
            });
        }
    </script>
@endsection
