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
        <strong>Result Analysis Report</strong>
    </div>
    <div class="card-body">
        <form id='my_form' action="{{ route('admin.lab_Result_Analysis_Class_Wise.get') }}" method="post" enctype="multipart/form-data">
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
                            {{ $entry }}
                        </option>
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
                            {{ $entry }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
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
                            {{ $entry->exam_name }}
                        </option>
                        @endforeach
                    </select>
                    <div class="from-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12" style="padding-top: 32px;">
                        <button id='result_analysis' class=" btn manual_bn">Filter</button>
                    </div>
                </div>


            </div>
        </form>
    </div>
</div>

@if (isset($response) )
@if(count($response) > 0)



<div class="card" id="report" style="text-align:center;">

    <div class="row card-header ">
        <div class=" col-xl-6 "></div>

        <div class=" col-xl-3 col-md-4">
            <a href="{{ URL::to('admin/lab_Exam-classWise-summary-report/pdf', ['ay'=> $newData['Ay'], 'year'=> $newData['Year'], 'sem' => $newData['Sem'], 'examname'=> base64_encode($newData['Ex']), 'course'=> $newData['Course'],'section' => $newData['Sec']]) }}" target="_blank" class="btn btn-primary" id="download_btn">Download PDF File</a>
        </div>
        <div class=" col-xl-3 col-md-4">
            <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>
        </div>
    </div>


    <div class="text-align">
        <strong> {{ $response[0]->className ?? '' }}</strong>
    </div>

    <div class="card-body" id="card_body" style="max-width:100%;overflow-x:auto;">
        <table class="table table-bordered" id="tbl_exporttable_to_xls">
            <thead>
                <tr>
                    <td><strong> SL.NO </strong></td>
                    {{-- <td><strong>ROLL NO</strong></td> --}}
                    <td><strong>REGISTER NO</strong></td>
                    <td><strong>STUDENT'S NAME LIST</strong></td>
                    @foreach ($response as $responses)
                    <td><strong>{{ $responses->subjectName }}</strong></td>
                    @endforeach
                    <td><strong>No.of subjects failed</strong></td>
                </tr>
                @if (count($response) > 0)
            <tbody>
                @php
                $studentData = []; // Initialize an array to store data for each student
                $subjects = []; // Initialize an array to store unique subject names
                $subjectsPasscount = []; // Initialize an array to store unique subject names
                $subjectsfailcount = []; // Initialize an array to store unique subject names
                $i = 0;
                $count = 0;
                @endphp
                @foreach ($response as $responses1)
                @php
                $passcount = 1;
                $failcount = 1;
                $responses1->subjectTotal;
                if($responses1->newArray != null){


                foreach ($responses1->newArray as $new) {
                $studentId = $new->student_id;
                $co1 = ($new->attendance != 'Absent' ? $new->cycle_mark : "AB") ;
                if ($co1 == 'AB') {
                $co1 = 'AB';
                $subjectsfailcount[$i] = $failcount++;
                }elseif($co1 < 100/2 ){ $subjectsfailcount[$i]=$failcount++; } else{ $subjectsPasscount[$i]=$passcount++; } $subject=$new->subject;

                    // Check if the student ID is not already in the array
                    if (!isset($studentData[$studentId])) {
                    $studentData[$studentId] = [
                    'subjects' => [], // Initialize an array to store subjects and their marks
                    ];
                    }

                    // Add subject and mark to the student's data
                    $studentData[$studentId]['subjects'][] = [
                    'subject' => $subject,
                    'co_1' => $co1,
                    'totalMark' => $responses1->subjectTotal,
                    ];

                    // Collect unique subject names
                    if (!in_array($subject, $subjects)) {
                    $subjects[] = $subject;
                    }
                    }
                    }
                    $i++;
                    $count++;
                    @endphp

                    @endforeach
                    @php
                    // dd($count);
                    // dd($subjectsPasscount);
                    @endphp

                    {{-- Table headers --}}
                    {{-- <tr>
                                    <th>Student ID</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    @foreach ($subjects as $subject)
                                        <th>{{ $subject }}</th>
                    @endforeach
                    <th>Total</th>
                    </tr> --}}

                    {{-- Loop through student data --}}
                    @php
                    $i = 1;
                    $totalStudentsPass=0;
                    $oneSub=0;
                    $twoSub=0;
                    $threeSub=0;
                    $fourSub=0;
                    $fiveSub=0;
                    $sixSub=0;
                    $morethan=0;
                    @endphp
                    @foreach ($studentData as $studentId => $student)
                    <tr>
                        <td>{{ $i++ }}</td>
                        {{-- <td>
                                            @foreach ($student1 as $students1)

                                            @if ($students1->user_name_id == $studentId)
                                                {{$students1->roll_no }}
                        @endif
                        @endforeach</td> --}}

                        <td>
                            @foreach ($student1 as $students1)
                            {{-- {{dd($students)}} --}}
                            @if ($students1->user_name_id == $studentId)
                            {{ $students1->register_no }}
                            @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($student1 as $students1)
                            {{-- {{dd($students)}} --}}
                            @if ($students1->user_name_id == $studentId)
                            {{ $students1->name }}
                            @endif
                            @endforeach

                        </td>
                        {{-- You should replace $studentName with the actual student name --}}
                        @php
                        $passedSubjectCount = 0;
                        $failedSubjectCount = 0;

                        @endphp
                        @foreach ($response as $responses)
                        <td>
                            {{-- Find the corresponding subject mark --}}
                            @foreach ($student['subjects'] as $subjectData)
                            @if ($subjectData['subject'] == $responses->subject && $responses->mark_entereby != null)
                            <div style="{{ isset($subjectData['co_1']) && $subjectData['co_1'] == 'AB' ? 'background-color: red' : ($subjectData['co_1'] < $subjectData['totalMark'] * 0.5 ? 'background-color: red;' : 'background-color: lightgreen;') }}">
                                {{ $subjectData['co_1'] }}
                            </div>

                            @if ($subjectData['co_1'] == 'AB')
                            @php
                            $failedSubjectCount++;
                            @endphp
                            @elseif ($subjectData['co_1'] < $subjectData['totalMark'] * 0.5) @php $failedSubjectCount++; @endphp @else @php $passedSubjectCount++; @endphp @endif @endif @endforeach </td>
                                @endforeach
                                @php


                                if($passedSubjectCount == $count){
                                $totalStudentsPass++;
                                }
                                if($failedSubjectCount == 1){
                                $oneSub++;
                                }
                                if($failedSubjectCount == 2){
                                $twoSub++;
                                }
                                if($failedSubjectCount == 3){
                                $threeSub++;
                                }
                                if($failedSubjectCount == 4){
                                $fourSub++;
                                }
                                if($failedSubjectCount == 5){
                                $fiveSub++;
                                }
                                if($failedSubjectCount == 6){
                                $sixSub++;
                                }
                                if($failedSubjectCount > 6){
                                $morethan++;
                                }



                                @endphp

                        <td> {{ $failedSubjectCount }}</td>

                    </tr>
                    @endforeach
            </tbody>


            <tfoot>
                <tr class="text-center">
                    <td colspan="11"> <strong>Summary </strong></td>
                </tr>
                <tr>
                    {{-- <td></td>
                                <td></td> --}}
                    <td colspan="3"><strong>Total Students</strong></td>
                    @foreach ($response as $responses)
                    <td>
                        @if (isset($student))
                        @foreach ($student['subjects'] as $subjectData)
                        @if ($subjectData['subject'] == $responses->subject && $responses->mark_entereby != null)
                        {{ $responses->total_present + $responses->total_abscent }}
                        @endif
                        @endforeach
                        @endif
                    </td>
                    @endforeach
                    <td></td>

                </tr>
                <tr>
                    {{-- <td></td>
                                <td></td> --}}
                    <td colspan="3"><strong>Absent</strong></td>
                    @foreach ($response as $responses)
                    <td>
                        @if (isset($student))
                        @foreach ($student['subjects'] as $subjectData)
                        @if ($subjectData['subject'] == $responses->subject && $responses->mark_entereby != null)
                        {{ $responses->total_abscent }}
                        @endif
                        @endforeach
                        @endif
                    </td>
                    @endforeach
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Present</strong></td>
                    @foreach ($response as $responses)
                    <td>
                        @if (isset($student))
                        @foreach ($student['subjects'] as $subjectData)
                        @if ($subjectData['subject'] == $responses->subject && $responses->mark_entereby != null)
                        {{ $responses->total_present }}
                        @endif
                        @endforeach
                        @endif
                    </td>
                    @endforeach
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Failed</strong></td>
                    @for($i=0; $i < $count; $i++) <td>
                        {{ $subjectsfailcount[$i] ?? 0 }}
                        </td>
                        @endfor
                        <td></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Passed</strong></td>
                    @for($i=0; $i < $count; $i++) <td>
                        {{ $subjectsPasscount[$i] ?? 0 }}
                        </td>
                        @endfor

                        <td></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Pass%</strong></td>

                    @for($i=0; $i < $count; $i++) @php $student_count=0; $passstudent_count=$subjectsPasscount[$i] ?? 0 ; $Failstudent_count=$subjectsfailcount[$i] ?? 0 ; $student_count=$passstudent_count + $Failstudent_count; if( $passstudent_count> 0){
                        $passPercentage = ($passstudent_count / $student_count) * 100;
                        }else{
                        $passPercentage = 0;
                        }
                        @endphp
                        <td>
                            {{ $passPercentage != 0 ? number_format($passPercentage,2) : 0  }}
                        </td>
                        @endfor
                        <td></td>
                </tr>
                <tr class="text-center">
                    <td colspan="11"> <strong>Summary of Failures Count</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center">Total Number of Students </td>
                    <td>
                        {{ $totalStudentinclass=$response[0]->total_present !='' && $response[0]->total_abscent !='' ? $response[0]->total_present + $response[0]->total_abscent:0 }}
                    </td>
                    <td colspan="4">Arrear in one subject</td>
                    <td colspan="3">
                        {{$oneSub ?? '-'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center">Number of students Passed </td>
                    <td>{{ $totalStudentsPass ?? 0}}</td>
                    <td colspan="4">Arrear in two subject</td>
                    <td colspan="3">
                        {{$twoSub ?? '-'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center">Over All Pass Percentage </td>
                    <td>
                        {{$totalStudentinclass !=0 && $totalStudentsPass ? number_format(($totalStudentsPass/$totalStudentinclass ) * 100,2) : 0}}
                    </td>
                    <td colspan="4">Arrear in three subject</td>
                    <td colspan="3">
                        {{$threeSub ?? '-'}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td></td>
                    <td colspan="4">Arrear in four subject</td>
                    <td colspan="3">
                        {{$fourSub ?? '-'}}
                    </td>

                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td></td>
                    <td colspan="4">Arrear in five subject</td>
                    <td colspan="3">
                        {{$fiveSub ?? '-'}}
                    </td>

                </tr>
                <tr>
                    <td colspan="3"> </td>
                    <td></td>
                    <td colspan="4">Arrear in six subject</td>
                    <td colspan="3">
                        {{$sixSub ?? '-'}}
                    </td>

                </tr>
                <tr>
                    <td colspan="3"> </td>
                    <td></td>
                    <td colspan="4">Arrear in more than six subject</td>
                    <td colspan="3">
                        {{$morethan ?? '-'}}
                    </td>

                </tr>
                <tr>
                    <td colspan="4" class="text-center"><strong> AUTHORITY</strong></td>
                    <td colspan="4" class="text-center"><strong>NAME</strong></td>
                    <td colspan="3" class="text-center"><strong>SIGNATURE</strong></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center"><strong>PREPARED BY</strong></td>
                    <td colspan="4"></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center"><strong>HOD</strong></td>

                    <td colspan="4"></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center"><strong>PRINCIPAL</strong></td>

                    <td colspan="4"></td>
                    <td colspan="3"></td>
                </tr>
            </tFoot>
            @endif
        </table>
    </div>
</div>
@else
<div class="card">
    <div class="card-body">
        <p class="text-center">ALL Subject is Not Published</p>
    </div>
</div>
@endif
@endif
@endsection
@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
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
            XLSX.writeFile(wb, fn || (`{{{$newData['Ex'] ?? ''}}}_Result_Analysis_Classwise_Report_{{{$response[0]->className ?? '' }}}.` + (type || 'xlsx')));
    }
</script>
<script>
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        let deleteButtonTrans = '{{ trans('global.datatables.delete ') }}';
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


        const $AcademicYear =$('#AcademicYear').val();
        const $Year =$('#year').val();
        const $Course =$('#course').val();
        const $semester =$('#semester').val();
        const $section =$('#section').val();
        const $exame_name =$('#examename').val();

            $('#result_analysis').on('click', function(e) {
                if($AcademicYear != '' && $Year != '' && $Course != '' && $semester != '' && $section != '' && $exame_name != '' ){
                var form = $('#my-form');
                form.submit();
                $('#loading').show();
                }else{
                    e.preventDefault();
                }
            });





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
</script>
@endsection
