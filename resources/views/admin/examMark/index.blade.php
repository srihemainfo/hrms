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
        .select2-container {
            width: 100% !important;
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
    @can('cat_mark_csv_import')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-12">
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    CSV Import For Mark Publish
                </button>
                @include('csvImport.modal', [
                    'model' => 'ExamattendanceData',
                    'route' => 'admin.examattendance-data.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header text-center">
            <strong> Search Exam Mark {{ trans('global.list') }}</strong>
        </div>
        <div class="card-body">
            <div id="spinner" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <div class="row">
                {{-- <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label class="required d-block" for="AcademicYear">Academic Year</label>
                <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required>
                    <option value="">Please Select</option>
                    @foreach ($AcademicYear as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label for="year" class="required d-block">Year</label>
                <select class="form-control select2" name="year" id="year">
                    <option value="">Select Year</option>
                    <option value="01">I</option>
                    <option value="02">II</option>
                    <option value="03">III</option>
                    <option value="04">IV</option>

                </select>

            </div>
            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label class="required d-block" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                <select class="form-control select2 " name="course" id="course" required>
                    <option value="">Please Select</option>
                    @foreach ($courses as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label for="semester" class="required d-block">Semester</label>
                <select class="form-control select2" name="semester" id="semester" required>
                    <option value="">Please Select</option>
                    @foreach ($semester as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label for="section" class="required d-block">Sections</label>
                <select class="form-control select2" name="section" id="section" required>
                    <option value="">Please Select</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>


                </select>
            </div> --}}
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="examename" class="required d-block">Exam Title</label>
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
                    <button class="manual_bn" onclick="search()">Filter</button>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12" id='load'
                    style="padding-top: 32px;display:none">
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                </div>
            </div>
            <div style="display: none" class="" id="firstHide">
                <div class="row">
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label class="d-block" for="AcademicYear">Academic Year</label>
                        <select class="form-control  select2" name="AcademicYear" id="AcademicYear1">
                            <option value="">Please Select</option>
                            @foreach ($AcademicYear as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label class="d-block" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course1">
                            <option value="">Please Select</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label for="section" class="d-block">Sections</label>
                        <select class="form-control select2" name="section" id="section1">
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
            <strong>Exam Mark {{ trans('global.list') }}</strong>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table
                    class=" table table-bordered table-striped text-center table-hover ajaxTable datatable datatable-exameMark">
                    <thead>
                        <tr>
                            <th width="10">

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
                                Edit Request By & Date
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
                                    <td>{{ $data->mark_date ?? '' }}</td>
                                    <td>{{ $data->markStaff ?? '' }}</td>

                                    <td>{{ $data->edit_request ?? '' }}</td>
                                    <td>{!! $data->toggle ?? '' !!}</td>
                                    <td>
                                        @if (($data->status ?? '') == 0)
                                            <span class="null-cell ">Not Verified</span>
                                        @elseif (($data->status ?? '') == 1)
                                            <span class=" text-info"> Verified</span>
                                        @elseif (($data->status ?? '') == 2)
                                            <span class=" text-success"> Published</span>
                                        @endif
                                    </td>

                                    <td>{!! $data->actions ?? '' !!}</td>
                                    {{-- <td>
                      <form method="POST"
                        action=""
                        enctype="multipart/form-data">
                        @csrf
                        <button type="submit" id="updater" name="updater" value="updater"
                            class="btn btn-xs btn-info">Edit</button>
                    </form>
                     <a  class="btn btn-info btn-xs" href=""
                        class="dropdown-item">View</a>
                    <form
                        action=""
                        method="POST"
                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                    style="display: inline-block;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn btn-xs btn-danger" value="Remove">
                    </form>
                    </td> --}}
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">No Data Found</td>
                            </tr>
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
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-exameMark').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function checkFluency(element) {
            var token = $('meta[name="csrf-token"]').attr('content');

            // console.log($(element).find('.toggleData').data('id'));

            var checkbox = $(element).find('.toggleData');
            // console.log(checkbox);
            var ExamattendanceData = $(element).find('.toggleData').data('id');
            if (checkbox.is(':checked')) { // Check if the checkbox is checked
                $.ajax({
                    url: "{{ route('admin.Exam-Mark.toggle_status') }}",
                    method: 'POST',
                    data: {
                        _token: token,
                        update: '1',
                        examID: ExamattendanceData,
                    },
                    success: function(response) {
                        // console.log(response)


                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                    }
                });
            } else {
                $.ajax({
                    url: "{{ route('admin.Exam-Mark.toggle_status') }}",
                    method: 'POST',
                    data: {
                        _token: token,
                        update: '0',
                        examID: ExamattendanceData,

                    },
                    success: function(response) {
                        // console.log(response)

                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                    }
                });
            }
        }

        $(document).ready(function() {



            const $year = $("#year");
            const semesterSelect = $("#semester");
            const $semesterType = $("#semesterType");

            let valuesAndText = getAllSelectValuesAndText(semesterSelect);

            if ($year.val() === "") {
                semesterSelect.html('<option value="">Please select a year first</option>');
            }

            $year.on("change", function() {
                const year = this.value;
                getSemester();
            });

            $semesterType.on("change", function() {
                const semesterType = this.value;
                getSemester();
            });
            // var previousCheckbox = '';



            function getSemester() {
                const year = $year.val();
                const semesterType = $semesterType.val();
                let $year_value = '';

                if (year !== "") {
                    let start = 0;
                    let end = 0;
                    $year_value = $year.val();

                    if ($year_value == '01') {
                        start = 1;
                        end = 2;
                    } else if ($year_value == '02') {
                        start = 3;
                        end = 4;
                    } else if ($year_value == '03') {
                        start = 5;
                        end = 6;
                    } else if ($year_value == '04') {
                        start = 7;
                        end = 8;
                    } else {
                        semesterSelect.html('<option value="">Please select a year first</option>');
                        return;
                    }

                    let sem = ' <option value="">Select Semester</option>';
                    for (let i = start; i <= end; i++) {
                        const option = valuesAndText[i];
                        sem += `<option style="color:blue;" value="${option.id}">${option.text}</option>`;
                    }
                    semesterSelect.html(sem);
                }
            }

            function getAllSelectValuesAndText($select) {
                const valuesAndText = [];
                $select.find("option").each(function() {
                    const id = $(this).val();
                    const text = $(this).text();
                    valuesAndText.push({
                        id,
                        text
                    });
                });
                return valuesAndText;
            }


        });
        //  Search Function
        $(document).ready(function() {


            const $academicYear_id = $("#AcademicYear");
            const $year_id = $("#year");
            const $course_id = $("#course");
            const $semester_id = $("#semester");
            const $section = $("#section");
            const $spinner = $("#spinner");
            const $examename = $("#examename");


            let course_id = '';
            let year_id = '';
            let academicYear_id = '';
            let semester_id = '';
            let section = '';
            let examename = '';

            if ($course_id.val() === "") {
                $section.html('<option value=""> Select Course First</option>');
            }
            if ($academicYear_id.val() == '' || $year_id.val() == '' || $course_id.val() == '' || $semester_id
                .val() ==
                '' || $section.val() == '') {
                $examename.html('<option value=""> Fill  All Value</option>');
            }

            // let valuesAndText = getAllSelectValuesAndText($semester_id);

            $academicYear_id.on("change", function() {
                getSemester();
            });
            $year_id.on("change", function() {
                getSemester();
            });

            $course_id.on("change", function() {
                getSemester();
                course();
            });
            $semester_id.on("change", function() {
                getSemester();
            });
            $section.on("change", function() {
                getSemester();
            });
            $examename.on("change", function() {
                // getSemester();
            });

            function course() {
                course_id = $course_id.val();
                $spinner.show();
                $section.html(' <option value="">Loading Section..</option>')

                $.ajax({
                    url: "{{ route('admin.examTimetable.section_exam_name_get') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course_id': course_id,
                    },
                    success: function(response) {
                        newObj = [];
                        $spinner.hide();
                        if (response.status == 'fail') {
                            let sec = ' <option value="">No Available Section</option>';
                            $section.html(sec);
                            $spinner.hide();
                        } else {
                            let sec = '';
                            if (response.get_section.length > 0) {
                                sec = `<option value=""> Available Section</option>`;
                                let get_sections = response.get_section;
                                for (let i = 0; i < get_sections.length; i++) {
                                    sec +=
                                        `<option style="color:blue;" value="${get_sections[i].section}"> ${get_sections[i].section}</option>`;
                                }
                                $("select").select2();
                                $section.html(sec);

                            }
                        }
                    }
                });

            }




            function getSemester() {

                academicYear_id = $academicYear_id.val();
                year_id = $year_id.val();
                course_id = $course_id.val();
                semester_id = $semester_id.val();
                section = $section.val();
                examename = $examename.val();

                if (academicYear_id != '' && course_id != '' && semester_id != '' && section != '') {

                    $spinner.show();
                    $examename.html('<option value="">Loading..</option>');

                    $.ajax({
                        url: "{{ route('admin.examTimetable.section_exam_name_get') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'academicYear_id': academicYear_id,
                            'course_id': course_id,
                            'semester_id': semester_id,
                            'section': section,
                        },
                        success: function(response) {
                            newObj = [];
                            $spinner.hide();
                            let exam_name = '';
                            if (response.status == 'exam_Name') {
                                exam_name += ' <option value="">No Available Exam Name</option>';
                                $examename.html(exam_name);
                            } else {
                                let exam_name = '';
                                if (response.exam_name.length > 0) {
                                    exam_name = ' <option value="">Select Exam Name</option>';
                                    let got_exam = response.exam_name;
                                    for (let i = 0; i < got_exam.length; i++) {
                                        exam_name +=
                                            `<option style="color:blue;" value="${got_exam[i].exam_name}"> ${got_exam[i].exam_name}</option>`;
                                    }
                                }
                                $examename.html(exam_name);
                                $("select").select2();
                            }
                        }
                    })
                } else {

                    var exam_name = ' <option value="">Fill All Value</option>';
                    $examename.html(exam_name);
                    // $("select").select2();

                }

            }

        });
        let mainResourse = null;
        let check = false;

        function callfunction(element) {
            var academicYear1 = $('#AcademicYear1').val();
            var course1 = $('#course1').val();
            var section1 = $('#section1').val();

            // console.log(academicYear1);
            // console.log(course1);
            // console.log(section1);
            var filteredElements = element.filter(function(item) {
                // Apply filtering conditions here
                return (
                    (academicYear1 === '' || item.acyear === academicYear1) &&
                    (course1 === '' || item.course === course1) &&
                    (section1 === '' || item.section === section1)
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
                                    return '<span class="null-cell">Mark Not Entered</span>';

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
                            data: 'edit_request',
                            name: 'edit_request',
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
                                if (data === '0') {
                                    return '<span class="null-cell">Not Verified</span>';
                                }
                                if (data === '1') {
                                    return '<span class="text-info">Verified</span>';
                                }
                                if (data === '2') {
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
                    order: [
                        [1, 'desc']
                    ],
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

        $('#AcademicYear1, #course1, #section1').on('change', function() {
            search();

            // }
        });

        function search() {
            $('#loading').show();
            //  $('#AcademicYear1').val('');
            //         $('#course1').val('');
            //         $('#section1').val('');
            // $('.select2').;

            var academicYear_id = $('#AcademicYear').val();
            // var academicYear_id1 = $('#AcademicYear1').val();
            var year = $('#year').val();
            var course_id = $('#course').val();
            // var course_id1 = $('#course1').val();
            var semester_id = $('#semester').val();
            var section = $('#section').val();
            // var section1 = $('#section1').val();
            var examename = $('#examename').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            $('#Search').hide();
            $('#load').show();

            var data = {};

            if (academicYear_id !== '') {
                data['academicYear_id'] = academicYear_id;
            }

            if (year !== '') {
                data['year'] = year;
            }

            if (course_id !== '') {
                data['course_id1'] = course_id;
            }


            if (semester_id !== '') {
                data['semester_id'] = semester_id;
            }

            if (section !== '') {
                data['section'] = section;
            }

            if (examename !== '') {
                data['examename'] = examename;
            }
            if (token !== '') {
                data['_token'] = token;
            }
            // console.log(data);

            if (Object.keys(data).length === 1) {
                $('#loading').hide();
                alert("Please fill in at least one field.");
                $('#Search').hide();
                $('#load').show();
                return;
            }

            $.ajax({
                url: "{{ route('admin.Exam-Mark.find') }}",
                method: 'POST',
                data: data,
                success: function(response) {

                    $("#AcademicYear1").select2();
                    $("#course1").select2();
                    $("#section1").select2();
                    // console.log(response);
                    var length = response.data.length;

                    if (length > 0) {
                        // alert('hello');
                        $('#firstHide').show();
                        mainResourse = response.data;
                        check = true;
                        callfunction(response.data);
                        $('#loading').hide();
                    } else {
                        // alert('hello2');
                        $('#firstHide').hide();
                        check = false;
                        $('#loading').hide();
                    }
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
                                        return '<span class="null-cell">Mark Not Entered</span>';

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
                                data: 'edit_request',
                                name: 'edit_request',
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
                                    if (data === '0') {
                                        return '<span class="null-cell">Not Verified</span>';
                                    }
                                    if (data === '1') {
                                        return '<span class="text-info">Verified</span>';
                                    }
                                    if (data === '2') {
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
                        order: [
                            [1, 'desc']
                        ],
                        pageLength: 10,
                    };
                    let table = $('.datatable-exameMark').DataTable(dtOverrideGlobals);
                    // table.clear().rows.add(response.data).draw();
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
                    console.log('An error occurred: ' + error);
                    $('#Search').show();
                    $('#load').hide();
                    $('#loading').hide();
                }
            });
        }

        function checkFluency(element) {
            var token = $('meta[name="csrf-token"]').attr('content');

            // console.log($(element).find('.toggleData').data('id'));

            var checkbox = $(element).find('.toggleData');
            // console.log(checkbox);
            var ExamattendanceData = $(element).find('.toggleData').data('id');
            if (checkbox.is(':checked')) { // Check if the checkbox is checked
                $.ajax({
                    url: "{{ route('admin.Exam-Mark.toggle_status') }}",
                    method: 'POST',
                    data: {
                        _token: token,
                        update: '1',
                        examID: ExamattendanceData,
                    },
                    success: function(response) {
                        // console.log(response)


                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                    }
                });
            } else {
                $.ajax({
                    url: "{{ route('admin.Exam-Mark.toggle_status') }}",
                    method: 'POST',
                    data: {
                        _token: token,
                        update: '0',
                        examID: ExamattendanceData,

                    },
                    success: function(response) {
                        // console.log(response)

                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                    }
                });
            }
        }
    </script>
@endsection
