@php
   $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
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
        .null-cell {
            color: red;
        }
    </style>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="container">
        <div class="loader" id="loader" style="display:none;top:15%;">
            <div class="spinner-border text-primary"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-header text-center">
            <strong>Search LAB Attendance</strong>
        </div>
        <div class="card-body">

            <div class="d-flex flex-wrap">
                <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                    <label class="required" for="AcademicYear">Academic Year</label>
                    <select class="form-control select2" name="AcademicYear" id="AcademicYear" required>
                        <option value="">Please Select</option>
                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                    <label for="SemesterType" class="required">Semester Type</label>
                    <select class="form-control select2" name="SemesterType" id="SemesterType">
                        <option value="">Select Year</option>
                        <option value="ODD">ODD</option>
                        <option value="EVEN">EVEN</option>
                    </select>
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                    <label class="required" for="examNames">Mark Type</label>
                    <select class="form-control select2" name="examNames" id="examNames" required>
                        <option value="">Please Select</option>
                        @foreach ($examNames as $id => $entry)
                            <option value="{{ $entry->exam_name }}">
                                {{ $entry->exam_name }}
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
            <div style="display: none" class="col-12" id="firstHide">
                <div class="d-flex flex-wrap">

                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                        <label class="" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course">
                            <option value="">Please Select</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                        <label for="year" class="">Year</label><br>
                        <select class="form-control select2" name="year" id="year">
                            <option value="">Select Year</option>
                            <option value="01">I</option>
                            <option value="02">II</option>
                            <option value="03">III</option>
                            <option value="04">IV</option>
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                        <label for="semester" class="">Semester</label><br>
                        <select class="form-control select2" name="semester" id="semester">
                            <option value="">Please Select</option>
                            @foreach ($semester as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                        <label for="section" class="">Sections</label>
                        <br>
                        <select class="form-control select2" name="section" id="section">
                            <option value="">Please Select</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header text-center">
            <strong> lab Attendance</strong>
        </div>
        <div class="card-body">

            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-examtimetable">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        {{-- <th>Module Code</th> --}}
                        <th>Class </th>
                        <th>Title of the Exam</th>
                        <th>Course</th>
                        <th>Exam Date</th>
                        <th>Subject Code</th>
                        {{-- <th>AN/FN</th> --}}
                        <th>Subject Title</th>
                        <th>Total No of Students</th>
                        <th>No of Students Present</th>
                        <th>No of Students Absent</th>
                        <th>Attendance Entered Date</th>
                        <th>Attendance Entered By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $exameAttCollection = collect($exameAtt);
                    @endphp
                    @if ($exameAttCollection->count() > 0)
                        @foreach ($exameAttCollection as $exameAtts)
                            <tr>
                                <td></td>
                                <td> {{ $exameAtts->class ?? '' }}</td>
                                <td> {{ $exameAtts->examename ?? '' }}</td>
                                <td> {{ $exameAtts->course ?? '' }}</td>
                                <td> {{ $exameAtts->date ? date('d-m-Y', strtotime($exameAtts->date)) : '' }}</td>
                                <td>{{ $exameAtts->subject_code ?? '' }}</td>
                                <td> {{ $exameAtts->subject ?? '' }}</td>
                                <td> {{ $exameAtts->totalstudent ?? '' }}</td>
                                <td>

                                    @if (($exameAtts->total_present ?? '') == null)
                                        <span class="null-cell">Attendance Not Entered</span>
                                    @else
                                        {{ $exameAtts->total_present ?? '' }}
                                    @endif
                                </td>
                                <td> {{ $exameAtts->total_abscent ?? '-' }}</td>
                                <td>{{ $exameAtts->date_entered ? date('d-m-Y', strtotime($exameAtts->date_entered)) : '-' }}
                                </td>
                                <td>{{ $exameAtts->attenteredBY ?? '-' }}</td>
                                <td>{!! $exameAtts->actions ?? '' !!}</td>

                            </tr>
                        @endforeach
                    @endif


                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                // processing:true,
                // serverSide:true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        let mainResourse = null;
        let check = false;

        function callfunction(element) {
            var section = $('#section').val();
            var course = $('#course').val();
            var year = $('#year').val();
            var semester = $('#semester').val();


            var filteredElements = element.filter(function(item) {
                return (
                    (section === '' || item.section == section) &&
                    (course === '' || item.course_id == course) &&
                    (year === '' || item.year == year) &&
                    (semester === '' || item.sem == semester)
                );
            });
            if (check) {
                let dtOverrideGlobals = {
                    // buttons: dtButtons,
                    // processing: true,
                    // serverSide: true,
                    deferRender: true,
                    retrieve: true,
                    aaSorting: [],
                    data: filteredElements,
                    columns: [{
                            data: 'empty',
                            name: 'empty',
                            render: function(data, type, full, meta) {
                                // Add static data here
                                return ' ';
                            }
                        },
                        {
                            data: 'class',
                            name: 'examename'
                        },
                        {
                            data: 'examename',
                            name: 'examename'
                        },
                        {
                            data: 'course',
                            name: 'course'
                        },

                        {
                            data: 'date',
                            name: 'date',
                            render: function(data, type, full, meta) {
                                var parts = data.split('-');
                                if (parts.length === 3) {
                                    var formattedDate = parts[2] + '-' + parts[1] + '-' +
                                        parts[0];
                                    return formattedDate;
                                }

                                return data;
                            }
                        },
                        {
                            data: 'subject_code',
                            name: 'subject_code'
                        },

                        // {
                        //     data: 'time_period',
                        //     name: 'time_period'
                        // },
                        {
                            data: 'subject.name',
                            name: 'subject'
                        },
                        {
                            data: 'totalstudent',
                            name: 'totalstudent'
                        },
                        {
                            data: 'total_present',
                            name: 'total_present',
                            render: function(data, type, full, meta) {
                                if (data === null) {
                                    return '<span class="null-cell">Attendance Not Entered</span>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'total_abscent',
                            name: 'total_abscent',
                            render: function(data, type, full, meta) {
                                if (data === null) {
                                    return '<span class="null-cell">-</span>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'date_entered',
                            name: 'date_entered',
                            render: function(data, type, full, meta) {
                                if (data === null) {
                                    return '<span class="null-cell">-</span>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'attenteredBY',
                            name: 'attenteredBY',
                            render: function(data, type, full, meta) {
                                if (data == '') {
                                    return '<span class="null-cell">-</span>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'actions',
                            name: 'actions'
                        },


                    ],
                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 10,
                };
                let table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
                table.destroy();
                table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
                $('#loading').hide();
            }
        }
        $('#course, #section,#year,#semester').on('change', function() {
            search()
            // }
        });

        function search() {

            $('#loading').show();
            var academicYear_id = $('#AcademicYear').val();
            var SemesterType = $('#SemesterType').val();
            // var course_id = $('#course').val();
            // var semester_id = $('#semester').val();
            // var section = $('#section').val();
            var examename = $('#examNames').val();
            var token = $('meta[name="csrf-token"]').attr('content');

            var data = {};

            if (academicYear_id !== '') {
                data['academicYear_id'] = academicYear_id;
            }
            if (SemesterType !== '') {
                data['SemesterType'] = SemesterType;
            }



            if (examename !== '') {
                data['examename'] = examename;
            }
            if (token !== '') {
                data['_token'] = token;
            }

            // Check if requestData is empty
            if (Object.keys(data).length === 1) {
                $('#loading').hide();
                alert("Please fill in at least one field.");
                return;
            }
            $.ajax({
                url: "{{ route('admin.lab_examTimetable.find') }}",
                method: 'POST',
                data: data,
                success: function(response) {

                    $("#AcademicYear").select2();
                    $("#SemesterType").select2();
                    $("#examNames").select2();
                    $('#section').select2();
                    $('#course').select2();
                    $('#year').select2();
                    $('#semester').select2();

                    var length = response.data.length;

                    if (length > 0) {
                        $('#firstHide').show();
                        mainResourse = response.data;
                        check = true;
                        $('#loading').hide();
                        $('#section').select2();
                        $('#course').select2();
                        $('#year').select2();
                        $('#semester').select2();
                        $('#loading').hide();
                        callfunction(response.data);
                    } else {
                        $('#firstHide').hide();
                        check = false;
                        $('#loading').hide();
                    }
                    // console.log(response);
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
                                data: 'class',
                                name: 'examename'
                            },
                            {
                                data: 'examename',
                                name: 'examename'
                            },
                            {
                                data: 'course',
                                name: 'course'
                            },

                            {
                                data: 'date',
                                name: 'date',
                                render: function(data, type, full, meta) {
                                    var parts = data.split('-');
                                    if (parts.length === 3) {
                                        var formattedDate = parts[2] + '-' + parts[1] + '-' +
                                            parts[0];
                                        return formattedDate;
                                    }

                                    return data;
                                }
                            },
                            {
                                data: 'subject_code',
                                name: 'subject_code'
                            },

                            // {
                            //     data: 'time_period',
                            //     name: 'time_period'
                            // },
                            {
                                data: 'subject.name',
                                name: 'subject'
                            },
                            {
                                data: 'totalstudent',
                                name: 'totalstudent'
                            },
                            {
                                data: 'total_present',
                                name: 'total_present',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">Attendance Not Entered</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'total_abscent',
                                name: 'total_abscent',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">-</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'date_entered',
                                name: 'date_entered',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">-</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'attenteredBY',
                                name: 'attenteredBY',
                                render: function(data, type, full, meta) {
                                    if (data == '') {
                                        return '<span class="null-cell">-</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'actions',
                                name: 'actions'
                            },


                        ],
                        orderCellsTop: true,
                        order: [
                            [1, 'desc']
                        ],
                        pageLength: 10,
                    };
                    let table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
                    table.destroy();
                    table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable()
                            .columns.adjust();
                    });
                    $('#loading').hide();

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.log('An error occurred: ' + error);
                    $('#loading').hide();
                }
            });
        }
    </script>
@endsection
