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

        .toggle {
            position: relative;
            margin-left: 25px;
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
            /* content: '';
                            width: 80px;
                            height: 42px;
                            box-shadow: 0 0 1px 2px #0001;
                            background: #eee;
                            position: relative;
                            display: inline-block;
                            border-radius: 46px;
                            transition: 0.2s ease-in; */
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

        @media screen and (max-width: 575px) {
            .select2 {
                width: 100% !important;
            }
        }

        @media screen and (max-width: 767px) {
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

    <div class="card">
        <div class="card-header text-center">
            <strong> Lab Marks Master </strong>
        </div>
        <div class="card-body">
            <div id="spinner" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <div class="row">

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required d-block" for="AcademicYear">Academic Year</label>
                    <select class="form-control  select2" name="AcademicYear" id="AcademicYear">
                        <option value="">Please Select</option>
                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="semesterType d-block" class="required">Semester Type</label>
                    <select class="form-control select2 {{ $errors->has('semesterType') ? 'is-invalid' : '' }}"
                        name="semesterType" id="semesterType">
                        <option value="">Select Semester Type</option>
                        <option value="ODD" {{ old('semesterType') == 'ODD' ? 'selected' : '' }}>ODD</option>
                        <option value="EVEN" {{ old('semesterType') == 'EVEN' ? 'selected' : '' }}>EVEN</option>

                    </select>
                    @if ($errors->has('semesterType'))
                        <span class="text-danger">{{ $errors->first('semesterType') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="examename" class="required d-block">Lab Mark Title</label>
                    <select class="form-control select2" name="examename" id="examename" required>
                        <option value="">Please Select</option>
                        @foreach ($examNames as $id => $entry)
                            <option value="{{ $entry->exam_name }}">
                                {{ $entry->exam_name }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12" id='Search'
                    style="padding-top: 32px;">
                    <button class="manual_bn">Filter</button>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12" id='load'
                    style="padding-top: 32px;display:none">
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                </div>



            </div>
            <div style='display:none' id="firstHide">
                <div class="row" id="firstHide">

                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <div>

                            <label class="required d-block" for="course1">Course</label>
                        </div>
                        <select class="form-control  select2 col-12" name="course" id="course1">
                            <option value=""> Select Course</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <div>
                            <label for="year" class='d-block'>Year</label>
                        </div>
                        <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year"
                            id="year1">
                            <option value="">Select Year</option>
                            <option value="01" {{ old('year') == '01' ? 'selected' : '' }}>I</option>
                            <option value="02" {{ old('year') == '02' ? 'selected' : '' }}>II</option>
                            <option value="03" {{ old('year') == '03' ? 'selected' : '' }}>III</option>
                            <option value="04" {{ old('year') == '04' ? 'selected' : '' }}>IV</option>
                        </select>
                        @if ($errors->has('semesterType'))
                            <span class="text-danger">{{ $errors->first('semesterType') }}</span>
                        @endif
                        <span class="help-block"> </span>
                    </div>

                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <div>

                            <label for="semester" class='d-block'>Semester</label>
                        </div>
                        <select class="form-control select2 col-12" name="semester" id="semester1" required>
                            <option value="">Please Select</option>
                            @foreach ($semester as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label for="section" class='d-block'>Section</label>
                        <select class="form-control select2" name="section" id="section1" required>
                            <option value="">Please Select</option>
                            @foreach ($section as $id => $entry)
                                <option value="{{ $entry->section }}">
                                    {{ $entry->section }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12" id='Search'
                        style="padding-top: 32px;">
                        <button class="manual_bn">Filter</button>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12" id='load'
                        style="padding-top: 32px;display:none">
                        <button class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style ='display:none'id="mydiv" data-hero=''></div>

    <div class="card">
        <div class="card-header text-center">
            <strong>LAB Mark {{ trans('global.list') }}</strong>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table
                    class=" table table-bordered table-striped text-center table-hover ajaxTable datatable datatable-exameMark">
                    <thead>
                        <tr>
                            <th>

                            </th>
                            <th>
                                Class
                            </th>
                            <th>
                                Exam Title
                            </th>
                            <th>
                                Subject Code
                            </th>
                            <th>
                                Subject Title
                            </th>
                            <th>
                                Faculty Name
                            </th>
                            <th>
                                Total No of Students
                            </th>

                            <th>
                                Marks Entered Date
                            </th>
                            <th>
                                Marks Entered By
                            </th>

                            <th>
                                Enable/Disable Mark Entry
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($exameMark->count() > 0)
                            @foreach ($exameMark as $index => $data)
                                <tr>
                                    <td></td>
                                    <td>{{ $data->classDetails ?? '' }}</td>
                                    <td>{{ $data->examename ?? '' }}</td>
                                    <td>{{ $data->subject_code ?? '' }}</td>
                                    <td>{{ $data->subject ?? '' }}</td>
                                    <td>{{ $data->staffName ?? '' }}</td>
                                    <td>{{ $data->totalstudent ?? '' }}</td>
                                    <td class="{{ $data->mark_date ? '' : 'text-danger' }}">
                                        {{ $data->mark_date ? date('d-m-Y', strtotime($data->mark_date)) : 'Not Yet Entered' }}
                                    </td>
                                    <td>{{ $data->markStaff ?? '-' }}</td>

                                    <td>{!! $data->toggle ?? '' !!}</td>
                                    <td>
                                        @if (($data->status ?? 0) == 0)
                                            <span class="null-cell ">Not Submitted</span>
                                        @elseif (($data->status ?? '') == 3)
                                            <span class=" text-info"> Submitted</span>
                                        @elseif (($data->status ?? '') == 1)
                                            <span class=" text-primary"> Verified</span>
                                        @elseif (($data->status ?? '') == 2)
                                            <span class=" text-success"> Published</span>
                                        @endif
                                    </td>

                                    <td>{!! $data->actions ?? '' !!}</td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
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
                // processing: true,
                // serverSide: true,
                retrieve: true,
                aaSorting: [],
                orderCellsTop: true,
                pageLength: 10,
            };
            let table = $('.datatable-exameMark').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function checkFluency(element) {
            $('#loading').show();
            var token = $('meta[name="csrf-token"]').attr('content');


            var checkbox = $(element).find('.toggleData');
            var ExamattendanceData = $(element).find('.toggleData').data('id');
            if (checkbox.is(':checked')) { // Check if the checkbox is checked
                $.ajax({
                    url: "{{ route('admin.lab_Exam-Mark.toggle_status') }}",
                    method: 'POST',
                    data: {
                        _token: token,
                        update: '1',
                        examID: ExamattendanceData,
                    },
                    success: function(response) {
                        if (response.data == 200) {
                            $('#loading').hide();
                        } else {
                            $(':checkbox').prop('checked', false)
                        }

                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                        $('#loading').hide();

                    }
                });
            } else {

                $.ajax({
                    url: "{{ route('admin.lab_Exam-Mark.toggle_status') }}",
                    method: 'POST',
                    data: {
                        _token: token,
                        update: '0',
                        examID: ExamattendanceData,

                    },
                    success: function(response) {
                        $('#loading').hide();
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                        $('#loading').hide();
                    }
                });
            }
        }


        //  Search Function

        let mainResourse = null;
        let check = false;

        function callfunction() {
            if ($('#course1').val() != '') {
                var data_str = $("div#mydiv").attr("data-hero");
                var my_object = JSON.parse(decodeURIComponent(data_str))
                var filteredElements = my_object.filter(function(item) {
                    // Apply filtering conditions here
                    var course1 = $('#course1').val();
                    var year1 = $('#year1').val();
                    var semester1 = $('#semester1').val();
                    var section1 = $('#section1').val();

                    return (
                        (course1 === '' || item.course == course1) &&
                        (semester1 === '' || item.sem == semester1) &&
                        (section1 === '' || item.section == section1) &&
                        (year1 === '' || item.year == year1)
                    );
                });
                // console.log(filteredElements);
                if (check) {
                    // console.log(element);
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
                                data: 'classDetails',
                                name: 'classDetails'
                            },
                            {
                                data: 'examename',
                                name: 'examename'
                            },
                            {
                                data: 'subject_code',
                                name: 'subject_code'
                            },
                            {
                                data: 'subject',
                                name: 'subject'
                            },
                            {
                                data: 'staffName',
                                name: 'staffName'
                            },

                            {
                                data: 'totalstudent',
                                name: 'totalstudent'
                            },



                            {
                                data: 'mark_date',
                                name: 'mark_date',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">Not Yet Entered</span>';

                                    }
                                    return data;
                                }
                            },

                            {
                                data: 'markStaff',
                                name: 'markStaff',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">-</span>';
                                    }
                                    return data;
                                }
                            },

                            {
                                data: 'toggle',
                                name: 'toggle',

                            },
                            {
                                data: 'status',
                                name: 'status',
                                render: function(data, type, full, meta) {
                                    if (data == '0') {
                                        return '<span class="null-cell">Not Submitted</span>';
                                    }
                                    if (data == '1') {
                                        return '<span class="text-info">Verified</span>';
                                    }
                                    if (data == '2') {
                                        return '<span class="text-success">Published</span>';
                                    }
                                    if (data == '3') {
                                        return '<span class="text-success">Submitted</span>';
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
                        pageLength: 10,
                    };
                    let table = $('.datatable-exameMark').DataTable(dtOverrideGlobals);
                    table.destroy();
                    table = $('.datatable-exameMark').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable()
                            .columns.adjust();
                    });
                    $('#Search').show();
                    $('#load').hide();
                }
            }
        }

        $('#semester1, #year1,#course1, #section1').on('change', function() {

            var $section = $('#section1');
            var $semester = $('#semester1');
            var $course = $('#course1');
            var $year = $('#year1');
            // search();
            callfunction();
            // }
        });
        $('#AcademicYear, #examename, #semesterType').on('change', function() {
            var AcademicYear = $('#AcademicYear').val();
            var examename = $('#examename').val();
            var semesterType = $('#semesterType').val();
            if (AcademicYear != '' && examename != '' && semesterType != '') {
                search();
            };
            // }
        });


        function search() {
            $('#loading').show();
            //  $('#AcademicYear1').val('');
            //         $('#course1').val('');
            //         $('#section1').val('');
            // $('.select2').;

            var examename = $('#examename').val();
            var AcademicYear = $('#AcademicYear').val();
            var semesterType = $('#semesterType').val();
            // var semesterType = $('#AcademicYear1').val();
            // var semesterType = $('#semesterType').val();

            var $section = $('#section1');
            var $semester = $('#semester1');
            var $course = $('#course1');
            var $year = $('#year1');

            var token = $('meta[name="csrf-token"]').attr('content');
            $('#Search').hide();
            $('#load').show();

            var data = {};
            if (examename !== '') {
                data['examename'] = examename;
            }
            if (AcademicYear !== '') {
                data['AcademicYear'] = AcademicYear;
            }
            if (semesterType !== '') {
                data['semesterType'] = semesterType;
            }
            if (token !== '') {
                data['_token'] = token;
            }

            if (Object.keys(data).length === 1) {
                $('#loading').hide();
                alert("Please fill in at least one field.");
                $('#Search').hide();
                $('#load').show();
                return;
            }


            $.ajax({
                url: "{{ route('admin.lab_Exam_Mark.find') }}",
                method: 'POST',
                data: data,
                success: function(response) {

                    let sections_get = response.sections;
                    let sections_get_length = Object.keys(sections_get).length;
                    let semester_get = response.semester;
                    let semester_get_length = Object.keys(semester_get).length;
                    let course_get = response.course;
                    let course_get_length = Object.keys(course_get).length;
                    let year_get = response.year;
                    let year_get_length = Object.keys(year_get).length;

                    let course = '';
                    // if (sections_get_length > 0) {
                    //     course += ' <option value="">Select Course</option>';
                    //     for (let i = 0; i < course_get_length; i++) {
                    //         course +=
                    //             `<option style="color:blue;" ${course_get.section == $course.val() ?? '' ? 'selected' : '' }  value="${course_get.section }"> ${course_get.section }</option>`;
                    //     }
                    // } else {
                    //     course += ' <option value=""> Course Not Available</option>';

                    // }


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
                                `<option style="color:blue;" ${key == $year.val() ?? '' ? 'selected' : '' }  value="${key}"> ${year_get[key] }</option>`;
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

                    $("#semester1").select2({
                        width: '160px'
                    });
                    $("#course1").select2({
                        width: '160px'
                    });
                    $("#section1").select2({
                        width: '160px'
                    });
                    $("#year1").select2({
                        width: '160px'
                    });
                    var length = response.data.length;

                    if (length > 0) {
                        // alert('hello');
                        $('#firstHide').show();
                        mainResourse = response.data;
                        check = true;
                        $('#loading').hide();
                        // callfunction(response.data);
                        var data_str = encodeURIComponent(JSON.stringify(response.data));
                        $("div#mydiv").attr("data-hero", data_str);
                    } else {
                        // alert('hello2');
                        $('#firstHide').hide();
                        check = false;
                        $('#loading').hide();
                    }
                    // console.log();
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
                                data: 'classDetails',
                                name: 'classDetails'
                            },
                            {
                                data: 'examename',
                                name: 'examename'
                            },
                            {
                                data: 'subject_code',
                                name: 'subject_code'
                            },
                            {
                                data: 'subject',
                                name: 'subject'
                            },
                            {
                                data: 'staffName',
                                name: 'staffName'
                            },

                            {
                                data: 'totalstudent',
                                name: 'totalstudent'
                            },



                            {
                                data: 'mark_date',
                                name: 'mark_date',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">Not Yet Entered</span>';

                                    }
                                    return data;
                                }
                            },

                            {
                                data: 'markStaff',
                                name: 'markStaff',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">-</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'toggle',
                                name: 'toggle',

                            },
                            {
                                data: 'status',
                                name: 'status',
                                render: function(data, type, full, meta) {
                                    if (data == '0') {
                                        return '<span class="null-cell">Not Submitted</span>';
                                    }
                                    if (data == '3') {
                                        return '<span class="text-info">Submitted</span>';
                                    }
                                    if (data == '1') {
                                        return '<span class="text-info">Verified</span>';
                                    }
                                    if (data == '2') {
                                        return '<span class="text-success">Published</span>';
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
                        pageLength: 10,
                    };
                    let table = $('.datatable-exameMark').DataTable(dtOverrideGlobals);
                    table.destroy();
                    table = $('.datatable-exameMark').DataTable(dtOverrideGlobals);
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
