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
        height: 500px;
        width: 100%;
        overflow: auto;
    }
</style>
<div class="loading" id='loading' style='display:none'>Loading&#8230;</div>

<div class="card">
    <div class="card-header text-center">
        <span class="text-primary" style="font-size:1.2rem"> <strong> LAB Abstract Report </strong></span>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.lab_Result_Analysis_Abstract.Abstractget') }}" method="post" enctype="multipart/form-data" id='my-form'   >
            @csrf
            <div id="spinner" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <div class="row">

                <div class="col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <div class="form-group ">
                        <label for="department" class="required">Department</label>
                        <select class="form-control select2 required" name="department" id="department" required >
                            @foreach ($departments as $id => $entry)
                            @if($id != '10' && $id != '9')
                            <option value="{{ $id }}">{{ $entry }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <select class="form-control select2 " name="course" id="course" required >
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
                    <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required >
                        <option value="">Please Select</option>
                        @foreach ($AcademicYear as $id => $entry)
                        <option value="{{ $id }}">
                            {{ $entry }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label for="examename" class="required">Exam Title</label>
                    <select class="form-control select2" name="examename" required>
                        <option value="">Please Select</option>

                        @foreach ($uniqueExamNames as $id => $entry)
                        <option value="{{ $entry }}">
                            {{ $entry }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12">
                    <div class="form-group">
                        <label for="search_date" class="required">Date</label>
                        <input type="text" class="form-control " id="search_date" name="search_date" autocomplete="off">
                    </div>
                </div> --}}

                <div class="form-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12" style="padding-top: 32px;">
                    <button class="manual_bn"  id='submit_btn'>Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if(isset($classPassFail_list))
@if (count($classPassFail_list) > 0 && count($sectionPassFailCount) > 0 )
<div class="card" id="mainCard">
    <div class="card-header text-center">
        <span class="text-primary text-uppercase" style="font-size:1.2rem"> <strong> CAT Abstract List </strong></span>
        <span class="" style="font-size:1.2rem"> <strong>
                <h5> {{ $classPassFail_list['course_name_title'] ?? ''}} </h5>
            </strong></span>
    </div>
    <div class="card-body">
        <div id="table-container" class='table-responsive'>
            <table class="table table-bordered table-container" style="border-color: black;">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2">S.No.</th>
                        <th rowspan="2">Semester</th>
                        <th rowspan="2">Section</th>
                        <th rowspan="2">Class Strength</th>
                        <th colspan="5">{{ $classPassFail_list['assessment_title'] ?? ''}}</th>
                        <th colspan="5">Overall</th>
                    </tr>
                    <tr>
                        {{-- <th>Total students </th> --}}
                        <th>Absent</th>
                        <th>Present</th>
                        <th>Failed</th>
                        <th>Passed</th>
                        <th>Pass%</th>
                        <th>Registered</th>
                        <th>Present</th>
                        <th>Failed</th>
                        <th>Passed</th>
                        <th>Pass %</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @php
                    $id = 1;
                    $printFirstValues = true;
                    @endphp
                    @foreach ($sectionPassFailCount as $sectionId => $section)
                    @php
                    $printFirstValues = true;
                    $rowspanValue = $section['semester_count'];
                    @endphp
                    <tr>
                        <td rowspan="{{ $rowspanValue }}" class='align-middle'>{{ $id++ }}</td>
                        <td rowspan="{{ $rowspanValue }}" class='align-middle'>{{ $sectionId ??  '' }}</td>
                        @foreach ($classPassFail_list as $classId => $class)
                        @if (is_array($class) && isset($class['semester']) && $section['semester'] == $class['semester'])
                        <td class='align-middle '>{{ $class['section'] ??  '' }}</td>
                        <td class='align-middle '>{{ $class['total_student'] ??  '' }}</td>
                        <td class='align-middle '>{{ $class['studentsWithOverallAbsent'] ??  '' }}</td>
                        <td class='align-middle '>{{ $class['total_present'] ??  '' }}</td>
                        <td class='align-middle '>{{ $class['studentsWithOneFail'] ??  '' }}</td>
                        <td class='align-middle '>{{ $class['studentsWithOverallPass'] ??  '' }}</td>
                        <td class='align-middle '>{{ number_format($class['pass_percentage'], 2)  ??  '' }}</td>
                        @if ($printFirstValues)
                        <td rowspan="{{ $rowspanValue }}" class='align-middle '>{{ $section['total_student'] ??  '' }}</td>
                        <td rowspan="{{ $rowspanValue }}" class='align-middle '>{{ $section['total_present'] ??  '' }}</td>
                        <td rowspan="{{ $rowspanValue }}" class='align-middle'>{{ $section['total_fail'] ??  '' }}</td>
                        <td rowspan="{{ $rowspanValue }}" class='align-middle'>{{ $section['total_pass'] ??  '' }}</td>
                        <td rowspan="{{ $rowspanValue }}" class='align-middle'>{{ number_format($section['pass_percentage'], 2)??  '' }}</td>
                        @php
                        $printFirstValues = false;
                        @endphp
                        @endif
                    </tr>
                    @endif
                    @endforeach
                    @endforeach
                </tbody>
                </tbody>
            </table>
        </div>
        {{-- <div class="card-footer" style="float: right">
                <div id="hidden-inputs-container"></div>
                <a href="" class="btn btn-primary" id="download_btn"> Download PDF </a>
            </div> --}}
    </div>
</div>
@else
<div class="card">
    <div class="card-body">

        <center>NO Data Available</center>
    </div>
</div>
@endif
@endif

@endsection
@section('scripts')
@parent
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: "{{ session('error') }}",
    });
</script>
@endif

<script>
    //  Search Function
    $(document).ready(function() {


        const $department =$('#department').val();
        const $course =$('#course').val();
        const $AcademicYear =$('#AcademicYear').val();
        const $exame_name =$('#examename').val();

            $('#submit_btn').on('click', function(e) {
                if($department != '' && $course != '' && $AcademicYear != '' && $exame_name != ''){
                var form = $('#my-form');
                form.submit();
                $('#loading').show();
                }else{
                    e.preventDefault();
                }
            });


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
        let examename = '';
        let department_id = '';
        let search_date = '';
        if ($academicYear_id.val() == '' || $year_id.val() == '' || $course_id.val() == '' || $semester_id
            .val() == '') {
            $examename.html('<option value=""> Fill  All Value</option>');
        }
        $department_id.on("change", function() {
            getcourse();
        });



        function getcourse() {
            department_id = $department_id.val();
            $spinner.show();
            $course_id.html(' <option value="">Loading Course..</option>')

            $.ajax({
                url: "{{ route('admin.Exam_AttendanceSummary.course_get') }}",
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

    });
</script>
@endsection
