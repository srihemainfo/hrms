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
        .select2-container {
            width: 100% !important;
        }
    </style>
    {{-- {{dd(phpinfo())}} --}}

    @php
        ini_set('memory_limit', '256M');
    @endphp
    {{-- ini_set('memory_limit', '256M'); --}}


    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header text-center">
            <strong>Result Analysis Report - Bar chart</strong>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
                <div id="spinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
                <div class="d-flex">
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label class="required" for="AcademicYear">Academic Year</label>
                        <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required>
                            <option value="">Please Select</option>
                            @foreach ($AcademicYear as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label for="year" class="required">Year</label>
                        <select class="form-control select2" name="year" id="year">
                            <option value="">Select Year</option>
                            <option value="01">I</option>
                            <option value="02">II</option>
                            <option value="03">III</option>
                            <option value="04">IV</option>

                        </select>

                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course" required>
                            <option value="">Please Select</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label for="semester" class="required">Semester</label>
                        <select class="form-control select2" name="semester" id="semester" required>
                            <option value="">Please Select</option>
                            @foreach ($semester as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label for="section" class="required">Sections</label>
                        <select class="form-control select2" name="section" id="section" required>
                            <option value="">Please Select</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label for="examename" class="required">Exam Title</label>
                        <select class="form-control select2" name="examename" id="examename" required>
                            <option value="">Please Select</option>
                            @foreach ($examNames as $id => $entry)
                                <option value="{{ $entry->exam_name }}">
                                    {{ $entry->exam_name }}</option>
                            @endforeach
                        </select>

                    </div>


                </div>
            </form>
            <div class="form-group" style="padding-top: 32px;">
                <button class="btn manual_bn" onclick="search()">Filter</button>
            </div>
        </div>
    </div>
    <div class="card" id='myChart_card' style='display:none;'>
        <div class="card-header">
            <p class="message text-center"></p>

        </div>
        <div class="card-body" id='myChart_view' style='display:none;'>
            <div>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
    {{-- <script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.min.js"></script> --}}
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
                XLSX.writeFile(wb, fn || (`Result_Analysis_Report_Staff_Wise_${{{ $response[0]->className ?? '' }}}.` + (
                    type || 'xlsx')));
        }

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
            if ($academicYear_id.val() == '' || $year.val() == '' || $course_id.val() == '' || $semester_id.val() ==
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
                    url: "{{ route('admin.lab_Exam.section_exam_name_get') }}",
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
                        url: "{{ route('admin.lab_Exam.section_exam_name_get') }}",
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




        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart;

        // Function to initialize the chart with empty data
        function initializeChart() {
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: []
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        initializeChart();

        function search() {

            $('#loading').show();
            var academicYear_id = $('#AcademicYear').val();
            var year = $('#year').val();
            var course_id = $('#course').val();
            var semester_id = $('#semester').val();
            var section = $('#section').val();
            var examename = $('#examename').val();
            var token = $('meta[name="csrf-token"]').attr('content');
                $('#myChart_view').hide();
                $('#myChart_card').hide();
                $('.message').val('');

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

            if (Object.keys(data).length === 1) {
                $('#loading').hide();
                alert("Please fill in at least one field.");
                return;
            }

            $.ajax({
                url: "{{ route('admin.lab_Result_Analysis_bar_chart.showChart') }}",
                method: 'POST',
                data: data,
                success: function(response) {

                    if(response.length > 0){
                    console.log(response);

                    var labelsArray = [];
                    // console.log(response.labels);
                    $.each(response, function(index, element) {
                        // console.log(element);

                        labelsArray.push(element.labels);
                    });

                    var responseArray = {
                        studentPass: [],
                        studentFail: [],
                        present: [],
                        absent: [],
                        subject: [],
                    };

                    $.each(response, function(index, element) {


                        responseArray['studentPass'].push(element.response.studentPass);
                        responseArray['studentFail'].push(element.response.studentFail);
                        responseArray['present'].push(element.response.present);
                        responseArray['absent'].push(element.response.absent);
                        responseArray['subject'].push(element.response.subject);
                    });


                    let newResponse = [];
                    $.each(responseArray, function(index, element) {
                        // console.log(element);
                        newResponse.push(element);
                    });


                    myChart.data.labels = labelsArray;
                    myChart.data.datasets = [];




                    var labelColors = [{
                            label: 'studentPass',
                            color: 'rgba(75, 192, 192, 0.2)'
                        },
                        {
                            label: 'studentFail',
                            color: 'rgba(255, 99, 132, 0.2)'
                        },
                        {
                            label: 'present',
                            color: 'rgba(255, 205, 86, 0.2)'
                        },
                        {
                            label: 'absent',
                            color: 'rgba(54, 162, 235, 0.2)'
                        }
                    ];

                    // Loop through the labelColors array to create datasets
                    for (var i = 0; i < labelColors.length; i++) {
                        var labelColor = labelColors[i];
                        var groupData = responseArray[labelColor.label]; // Assuming your data is organized by label
                        var backgroundColor = labelColor.color;

                        myChart.data.datasets.push({
                            label: labelColor.label,
                            data: groupData,
                            backgroundColor: backgroundColor,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        });
                    }

                    // Update the chart
                    myChart.update();
                    $('.message').val('');
                    $('#myChart_card').show();
                    $('#myChart_view').show();
                    $('#loading').hide();
                    }else{
                        $('.message').text('No Data Available');
                        $('#myChart_card').show();
                        $('#loading').hide();
                    }

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
