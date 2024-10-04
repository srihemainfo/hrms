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

@php
        ini_set('memory_limit', '256M');
    @endphp

@extends($key)
@section('content')

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select-checkbox:before {
            content: none !important;
        }

        .event a {
            background-color: #2f00ff !important;
            color: #ffffff !important;
        }

        .table-container {
            height: 300px;
            /* Set the desired height */
            width: 100%;
            /* Set the desired width */
            overflow: auto;
            /* Add overflow auto to enable scrolling if content exceeds the div's dimensions */
        }
    </style>

<div class="loading" id='loading' style='display:none'>Loading&#8230;</div>


        <div class="card">
        <div class="card-header text-center">
            <span class="text-primary text-capitalize" style="font-size:1.2rem"> <strong> LAB Absentees Report </strong></span>
        </div>
        <div class="card-body">
            <div id="spinner" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <div class="row">

                <div class="col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <div class="form-group ">
                        <label for="department" class="required">Department</label>
                        <select class="form-control select2" name="department" id="department" required>
                            @foreach ($departments as $id => $entry)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <select class="form-control select2 " name="course" id="course" required>
                        <option value="">Please Select</option>
                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label class="required" for="AcademicYear">Academic Year</label>
                    <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required>
                        <option value="">Please Select</option>
                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label for="year" class="required">Year</label>
                    <select class="form-control select2" name="year" id="year" required>
                        <option value="">Select Year</option>
                        <option value="01">I</option>
                        <option value="02">II</option>
                        <option value="03">III</option>
                        <option value="04">IV</option>
                    </select>

                </div>

                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label for="semester" class="required">Semester</label>
                    <select class="form-control select2" name="semester" id="semester" required>
                        <option value="">Please Select</option>
                        @foreach ($semester as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label for="examename" class="required">Exam Title</label>
                    <select class="form-control select2" name="examename" id="examename" required>
                        <option value="">Please Select</option>
                        @foreach ($examNames as $id => $entry)
                            <option value="{{ $entry->exam_name }}">
                                {{ $entry->exam_name }}
                            </option>
                        @endforeach
                    </select>


                </div>
                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <div class="form-group">
                        <label for="search_date" class="required">Date</label>
                        <input type="text" class="form-control " id="search_date" name="search_date" autocomplete="off">
                    </div>
                </div>

                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12" style="padding-top: 32px;">
                    <button class="manual_bn" onclick="search()">Filter</button>
                </div>
            </div>

        </div>
    </div>

    <div class="card" style="display: none" id="mainCard">
        <div class="card-header text-center">
            <span class="text-primary" style="font-size:1.2rem"> <strong> LAB Absentees List </strong></span>
        </div>
        <div class="card-body">
            <div id="table-container">
                <!-- Your table will be appended here -->
            </div>
            <div  style="float:right" >
                <a href="" class="btn btn-primary" id="download_btn"> Download PDF </a>
            </div>
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

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
    <script>
        function get_data() {


            let dept = $("#department").val();
            let course = $("#course").val();
            let ay = $("#ay").val();
            let sem_type = $("#sem_type").val();
            let theDate = $("#search_date").val();
            $("#report").hide();
            $("#card_header").hide();
            if (dept == '') {
                Swal.fire('', 'Please Choose the Department', 'warning');
            } else if (course == '') {
                Swal.fire('', 'Please Choose the Course', 'warning');

            } else if (ay == '') {
                Swal.fire('', 'Please Choose the Academic Year', 'warning');

            } else if (sem_type == '') {
                Swal.fire('', 'Please Choose the Sem Type', 'warning');

            } else if (theDate == '') {
                Swal.fire('', 'Please Choose the Date', 'warning');

            } else {
                $("#loading").show();
                $("#card-body").html(`<div class="text-primary text-center">Loading...</div>`);
                $("#report").show();
                $.ajax({
                    url: '{{ route('admin.Exam_AttendanceSummary.summary-report') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'department': dept,
                        'course': course,
                        'ay': ay,
                        'sem_type': sem_type,
                        'date': theDate
                    },
                    success: function(response) {

                        let status = response.status;
                        let data = response.data;
                        let make_card;
                        let theDatas;
                        let theDatas_len;
                        let theSection;
                        let theCards = '';
                        let absentList = '';
                        let absentList_len;
                        let leaveList = '';
                        let leaveList_len;
                        let odList = '';
                        let odList_len;
                        let absent_students = '';
                        let leave_students = '';
                        let od_students = '';
                        let cumulative = '';
                        let strength = 0;
                        let present = 0;
                        let absent = 0;
                        let leave = 0;
                        let od = 0;
                        let students = 0;


                        if (status != false) {

                            let data_len = data.length;
                            for (let a = 0; a < data_len; a++) {
                                make_card =
                                    `<div class="card" style="margin-bottom:1rem;"><div class="card-header bg-primary"><span style="font-size:1.1rem;">${data[a].year}</span></div><div class="card-body"><div class="row">`;
                                theDatas = data[a].data;
                                theDatas_len = theDatas.length;
                                for (let b = 0; b < theDatas_len; b++) {
                                    students += theDatas[b].students;
                                    absentList = theDatas[b].absentlist;
                                    absentList_len = absentList.length;
                                    if (absentList_len > 0) {
                                        for (let c = 0; c < absentList_len; c++) {
                                            absent_students += absentList[c].student + ', ';
                                            absent++;
                                        }
                                    }
                                    absent_students = absent_students.slice(0, -2);

                                    leaveList = theDatas[b].leavelist;
                                    leaveList_len = leaveList.length;
                                    if (leaveList_len > 0) {
                                        for (let c = 0; c < leaveList_len; c++) {
                                            leave_students += leaveList[c].student + ', ';
                                            leave++;
                                        }
                                    }
                                    leave_students = leave_students.slice(0, -2);

                                    odList = theDatas[b].odlist;
                                    odList_len = odList.length;
                                    if (odList_len > 0) {
                                        for (let c = 0; c < odList_len; c++) {
                                            od_students += odList[c].student + ', ';
                                            od++;
                                        }
                                    }
                                    od_students = od_students.slice(0, -2);
                                    if (theDatas[b].status == true) {
                                        theSection =
                                            `<div class="col-md-3 table-bordered p-0"><div class="table-bordered text-center"><strong>${theDatas[b].name}</strong></div><div class="table-bordered"><div style="padding-left:5px;"><strong>Absent</strong></div><div class="text-center">${absent_students} </div></div><div class="table-bordered"><div style="padding-left:5px;"><strong>Leave</strong></div><div class="text-center">${leave_students} </div></div><div class="table-bordered"><div style="padding-left:5px;"><strong>OD</strong></div><div class="text-center">${od_students} </div></div></div>`;
                                    } else {
                                        theSection =
                                            `<div class="col-md-3 table-bordered p-0 text-center"><div class="table-bordered"><strong>${theDatas[b].name}</strong></div><div style="padding-top:1rem;">Not Yet Taken</div></div>`;
                                    }
                                    make_card += theSection;
                                    absent_students = '';
                                    leave_students = '';
                                    od_students = '';
                                }
                                strength = students;
                                present = strength - (absent + leave + od);
                                cumulative =
                                    `<div class="col-md-3 table-bordered"><div><strong>Strength : </strong> ${strength}</div><div><strong>Present : </strong>${present}</div><div><strong>Leave : </strong>${leave}</div><div><strong>OD : </strong>${od}</div><div><strong>Absent : </strong>${absent}</div></div>`;

                                make_card += `${cumulative}</div></div></div>`;
                                students = 0;
                                strength = 0;
                                present = 0;
                                absent = 0;
                                leave = 0;
                                od = 0;
                                theCards += make_card;
                            }
                            $("#card_header").html(`<a class="manual_bn bg-success" target="blank" href="{{ URL::to('admin/absentees-summary-report/pdf/${dept}/${course}/${ay}/${sem_type}/${theDate}') }}">
                    Download PDF File
                 </a>`);
                            $("#card-body").html(theCards);
                            $("#card_header").show();
                            $("#loading").hide();
                        } else {
                            Swal.fire('', data, 'error');
                            $("#report").hide();
                            $("#loading").hide();
                        }
                    }
                })
            }
        }

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.examTimetable.massDestroy') }}",
                className: 'btn-danger',
                action: function(e, dt, node, config) {
                    var ids = $.map(dt.rows({
                        selected: true
                    }).data(), function(entry) {
                        return entry.id
                    });

                    if (ids.length === 0) {
                        alert("{{ trans('global.datatables.zero_selected ') }}")

                        return
                    }

                    if (confirm("{{ trans('global.areYouSure ') }}")) {
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


            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.Exam_attendance.summary.index') }}",
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
                        data: 'course',
                        name: 'course'
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
                        data: 'sections',
                        name: 'sections'
                    },
                    {
                        data: 'start_time',
                        name: 'start_time',
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
                        data: 'end_time',
                        name: 'end_time',
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

        });



        $(document).ready(function() {



            const $year = $("#year");
            const semesterSelect = $("#semester");
            const $semesterType = $("#semesterType");
            const $department = $("#department");
            const $course = $("#course");
            const $search_date = $("#search_date");

            let valuesAndText = getAllSelectValuesAndText(semesterSelect);

            if ($year.val() === "") {
                semesterSelect.html('<option value=""> select a year first</option>');
            }
            if ($department.val() === "") {
                $course.html('<option value=""> select a Department first</option>');
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
                        semesterSelect.html('<option value="">Select year first</option>');
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
            // const $section = $("#section");
            const $spinner = $("#spinner");
            const $examename = $("#examename");
            const $department_id = $("#department");
            const $search_date = $("#search_date");


            let course_id = '';
            let year_id = '';
            let academicYear_id = '';
            let semester_id = '';
            // let section = '';
            let examename = '';
            let department_id = '';
            let search_date = '';

            // if ($course_id.val() === "") {
            //     $section.html('<option value=""> Select Course First</option>');
            // }
            if ($academicYear_id.val() == '' || $year_id.val() == '' || $course_id.val() == '' || $semester_id
                .val() == '') {
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
                // course();
            });
            $semester_id.on("change", function() {
                getSemester();
            });
            // $section.on("change", function() {
            //     getSemester();
            // });
            $examename.on("change", function() {
                var selectedValue = $(this).val();
                dateShow(selectedValue);

            });
            $department_id.on("change", function() {
                getcourse();
            });
            $search_date.on("change", function() {
                // getcourse();
            });


            function getcourse() {
                department_id = $department_id.val();
                $spinner.show();
                $course_id.html(' <option value="">Loading Course..</option>')

                $.ajax({
                    url: "{{ route('admin.lab_Exam_AttendanceSummary.course_get') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'department_id': department_id,
                    },
                    success: function(response) {
                        newObj = [];
                        $spinner.hide();
                        if (response.status == 'fail') {
                            let course = ' <option value="">No Available Course</option>';
                            $course_id.html(course);
                            $spinner.hide();
                        } else {
                            let course = '';
                            if (response.courses.length > 0) {
                                course = `<option value=""> Available Course</option>`;
                                let get_courses = response.courses;
                                for (let i = 0; i < get_courses.length; i++) {
                                    course +=
                                        `<option style="color:blue;" value="${get_courses[i].id}"> ${get_courses[i].name}</option>`;
                                }
                                $("select").select2();
                                $course_id.html(course);

                            }
                        }
                    }
                });

            }

            function dateShow(selectedValue) {

                $("#loading").show();
                $.ajax({
                    url: "{{ route('admin.lab_Exam_AttendanceSummary.getDate') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course_id': course_id,
                        'year_id': year_id,
                        'academicYear_id': academicYear_id,
                        'semester_id': semester_id,
                        'examename': selectedValue,
                        // 'course_id': course_id,
                    },
                    success: function(response) {
                        if (response.data != undefined && response.data != '') {
                            // Assuming response.data is an array of date strings in the format 'yyyy-mm-dd'
                            var eventDates = {};
                            $.each(response.data, function(index, dateStr) {
                                // Parse the date string and create a Date object
                                var date = new Date(dateStr.date);
                                // Set the date without time to midnight
                                date.setHours(0, 0, 0, 0);
                                // Use the date as the key and set its value to true
                                eventDates[date.getTime()] = true;
                            });

                            // console.log(eventDates);

                            // Destroy the existing Datepicker
                            $('#search_date').datepicker('destroy');

                            // Create a new Datepicker with the updated event dates
                            createDatePicker(eventDates);
                            $("#loading").hide();
                        }
                    }

                });

            }

            function createDatePicker(eventDates) {
                $('#search_date').datepicker({
                    beforeShowDay: function(date) {
                        // Set the date without time to midnight
                        date.setHours(0, 0, 0, 0);
                        var highlight = eventDates[date
                            .getTime()]; // Use getTime() to get timestamp for comparison
                        if (highlight) {
                            return [true, "event", 'Tooltip text'];
                        } else {
                            return [true, '', ''];
                        }
                    }
                });
            }

            // Assuming this code is executed initially to set up the Datepicker
            createDatePicker({});


            function getSemester() {

                academicYear_id = $academicYear_id.val();
                year_id = $year_id.val();
                course_id = $course_id.val();
                semester_id = $semester_id.val();
                // section = $section.val();
                examename = $examename.val();

                if ($academicYear_id.val() == '' || $year_id.val() == '' || $course_id.val() == '' || $semester_id
                    .val() == '') {
                    $examename.html('<option value=""> Fill  All Value</option>');
                }

                if (academicYear_id == '' || course_id == '' || semester_id == '') {
                    $examename.html('<option value="">Fill All Value</option>');
                }
                if (year_id == '') {
                    $semester_id.html('<option value="">Select Year First</option>')
                }


                if (academicYear_id != '' && course_id != '' && semester_id != '') {

                    $spinner.show();
                    $examename.html('<option value="">Loading..</option>');

                    $.ajax({
                        url: "{{ route('admin.lab_Exam_AttendanceSummary.Subject_get') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'academicYear_id': academicYear_id,
                            'course_id': course_id,
                            'semester_id': semester_id,
                        },
                        success: function(response) {
                            newObj = [];
                            $spinner.hide();
                            let exam_name = '';
                            if (response.status == 'exam_Name') {
                                exam_name += ' <option value="">No Available Exam Title</option>';
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

        function search() {

            var academicYear_id = $('#AcademicYear').val();
            var year = $('#year').val();
            var course_id = $('#course').val();
            var semester_id = $('#semester').val();
            var section = $('#section').val();
            var examename = $('#examename').val();
            var search_date = $('#search_date').val();
            var department = $('#department').val();
            var token = $('meta[name="csrf-token"]').attr('content');
        if(academicYear_id != '' && year != '' && course_id != '' && semester_id != '' && section != '' && examename != '' && token != '' && search_date != '' && department != ''){

            $("#loading").show();

            var data = {};

            if (academicYear_id !== '') {
                data['academicYear_id'] = academicYear_id;
            }
            if (year !== '') {
                data['year'] = year;
            }

            if (course_id !== '') {
                data['course_id'] = course_id;
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
            if (search_date !== '') {
                data['search_date'] = search_date;
            }
            if (department !== '') {
                data['department'] = department;
            }
            // Check if requestData is empty
            if (Object.keys(data).length === 1) {
                $("#loading").hide();
                alert("Please fill in at least one field.");
                return;
            }

            $.ajax({
                url: "{{ route('admin.lab_Exam_AttendanceSummary.search') }}",
                method: 'POST',
                data: data,
                success: function(response) {
                      var newData = response.newData;

                    if(response.newData.length >0){

                        var academicYear_id = newData[0];
                        var year = newData[1];
                        var semester_id = newData[2];
                        var exameName = newData[3];
                        var date = newData[4];
                        var department = newData[5];
                        var course_id = newData[6];

                        //                 for (var i = 0; i < response.newData.length; i++) {
                        //                     var value = response.newData[i];
                        //                     // Create a hidden input element
                        //                     var hiddenInput = $('<input>').attr({
                        //                         type: 'hidden',
                        //                         name: 'newData[]', // Set the name as needed
                        //                         value: value,       // Set the value from the array
                        //                     });

                        //     // Append the hidden input element to a container
                        //     $('#hidden-inputs-container').append(hiddenInput);
                        // }
                }


                    if (response.data != undefined && response.data.length > 0) {
                        $('#mainCard').show();
                        var dataArray = response
                            .data;

                        var dataArray2 = response
                            .data2;

                        // Clear the existing content in the container
                        $('#table-container').empty();
                        if (response.subject !== undefined) {
                            let heading = $('<div>').text('Subject: ' + response.subject).css({
                                'margin-bottom': '20px',
                                'text-align': 'center',
                                'font-weight': 'bold',
                            });
                            $('#table-container').append(heading);
                        }

                        var anchorElement = $('#download_btn');
                        let id = response.id || 0;
                        let link = `{{ url('admin/lab_Exam-absentees-report/pdf/${id}/${academicYear_id}/${year}/${semester_id}/${date}/${department}/${course_id}/${exameName}') }}`;
                        anchorElement.attr('href',link);
                        anchorElement.attr('target', '_blank');
                        var tableId = 'tbl_exporttable_to_xls';
                        // Loop through the data array
                        $.each(dataArray, function(index, data) {
                            // Create a new table for each data element
                           // var table = $('<table>').addClass('table table-bordered tbl_exporttable_to_xls');
                            var table = $('<table>').attr('id', tableId).addClass('table table-bordered ');
                            var thead = $('<thead>').addClass('text-center');
                            var tbody = $('<tbody>').addClass('text-center');

                            // Populate the table with data
                            var headers = Object.keys(data);
                            var headerRow = $('<tr>');
                            $.each(headers, function(index, header) {
                                headerRow.append($('<th>').text(header));
                            });
                            thead.append(headerRow);

                            var dataRow = $('<tr>');
                            $.each(headers, function(index, header) {
                                // console.log(data)
                                dataRow.append($('<td>').text(data[header])).css({
                                    'height': '175px',
                                    'width': '300px'
                                });
                            });
                            tbody.append(dataRow);
                            var dataRow1 = $('<tr>');
                            // $.each(headers, function(index, header) {
                            // console.log(dataArray2[index].totalCount)
                            dataRow1.append($('<td>').text('Total Students :' + dataArray2[index]
                                .totalCount)).css({
                                // 'height': '50px',
                                'width': '300px'
                            });
                            dataRow1.append($('<td>').text('Total Present :' + dataArray2[index]
                                .totalPres)).css({
                                // 'height': '50px',
                                'width': '300px'
                            });
                            dataRow1.append($('<td>').text('Total Absent :' + dataArray2[index]
                                .totalAbs)).css({
                                // 'height': '50px',
                                'width': '300px'
                            });
                            // });
                            tbody.append(dataRow1);

                            table.append(thead);
                            table.append(tbody);

                            // Wrap the table in a div and set the height and width
                            var tableDiv = $('<div>').addClass('table-container');
                            tableDiv.append(table);

                            // Append each table to the container
                            $('#table-container').append(tableDiv);
                            $("#loading").hide();
                        });

                    } else {
                        $('#mainCard').hide();
                        Swal.fire('', 'No Data Found', 'warning');
                        $("#loading").hide();
                    }

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.log('An error occurred: ' + error);
                    $("#loading").hide();
                }
            });
            }

        }




        </script>


        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
    <script>
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`Result_Analysis_Report_${type || 'xlsx'}.xlsx`));


        } -->








    </script>
@endsection
