@extends('layouts.admin')
@section('content')
    <style>
        .borderNone {
            border: none;
        }

        @media screen and (max-width: 575px) {
            .select2 {
                width: 100% !important;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <div class="form-group" style="padding-top: 20px;padding-left:20px;">
        <a class="btn btn-default" href="{{ route('admin.Exam-time-table.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header text-uppercase text-center">
            {{ trans('global.create') }} Exam TimeTable
        </div>


        <div class="card-body">

            {{-- <form method="POST" action="{{ route('admin.examTimetable.store') }}" enctype="multipart/form-data"
        id="myForm">
        @csrf --}}
            <div class="row">
                <div id="spinner" class='form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12' style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
                <div class="form-group col-xl-9 col-lg-9 col-md-9 col-sm-6 col-12 col-9 error_message text-center text-danger"
                    style="display: none;">

                </div>
            </div>
            <div class="row">

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">

                    <label class="required" for="accademicYear">Academic Year</label>
                    <select class="form-control select2 {{ $errors->has('accademicYear') ? 'is-invalid' : '' }}"
                        name="accademicYear" id="accademicYear" required>
                        <option value="">Please Select</option>

                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('accademicYear'))
                        <span class="text-danger">{{ $errors->first('accademicYear') }}</span>
                    @endif
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 float-right">
                    <label for="semesterType" class="required">Semester Type</label>
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
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <select class="form-control select2 {{ $errors->has('course') ? 'is-invalid' : '' }}" name="course"
                        id="course_id" required>
                        <option value="">Please Select</option>

                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('course'))
                        <span class="text-danger">{{ $errors->first('course') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.lesson.fields.course_helper') }}</span>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="year" class="required">Year</label>
                    <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year"
                        id="year">
                        <option value="">Select Year</option>
                        <option value="01" {{ old('semesterType') == '01' ? 'selected' : '' }}>I</option>
                        <option value="02" {{ old('semesterType') == '02' ? 'selected' : '' }}>II</option>
                        <option value="03" {{ old('semesterType') == '03' ? 'selected' : '' }}>III</option>
                        <option value="04" {{ old('semesterType') == '04' ? 'selected' : '' }}>IV</option>

                    </select>
                    @if ($errors->has('year'))
                        <span class="text-danger">{{ $errors->first('year') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>



                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="semester" class="required">Semester</label>
                    <select class="form-control select2" name="semester" id="semester" required>
                        <!-- <option value="">Please Select</option> -->

                        @foreach ($semester as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('semester'))
                        <span class="text-danger">{{ $errors->first('semester') }}</span>
                    @endif
                </div>


                {{-- <div class="form-group col-3">
                    <label for="exameType" class="required">Exam Type</label>
                    <select class="form-control select2 {{ $errors->has('exameType') ? 'is-invalid' : '' }}"
            name="exameType" id="exameType">
            <option value="">Select Exame Type</option>
            <option value="01" {{ old('exameType') == '01' ? 'selected' : '' }}>Internal</option>
            <option value="02" {{ old('exameType') == '02' ? 'selected' : '' }}>External</option>

            </select>
            @if ($errors->has('exameType'))
            <span class="text-danger">{{ $errors->first('exameType') }}</span>
            @endif
            <span class="help-block"> </span>
        </div> --}}
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="required" for="examName">Title of the Exam</label>
                    {{-- <input class="form-control {{ $errors->has('examName') ? 'is-invalid' : '' }}" type="text" name="examName" id="examName" placeholder="Exam Name" value="{{ old('examName', '') }}">
            @if ($errors->has('examName'))
            <span class="text-danger">{{ $errors->first('examName') }}</span>
            @endif       --}}

                    <select class="form-control select2{{ $errors->has('examName') ? 'is-invalid' : '' }}" name="examName"
                        id="examName">
                        <option value="">Select an Exam</option>
                        <option value="CAT - 1" {{ old('examName') == 'CAT - 1' ? 'selected' : '' }}>CAT - 1</option>
                        <option value="CAT-II" {{ old('examName') == 'CAT-II' ? 'selected' : '' }}>CAT-II</option>
                        <option value="CAT-III" {{ old('examName') == 'CAT-III' ? 'selected' : '' }}>CAT-III</option>
                        <option value="CAT-IV"{{ old('examName') == 'CAT-IV' ? 'selected' : '' }}>CAT-IV</option>
                        <option value="CAT-V" {{ old('examName') == 'CAT-V' ? 'selected' : '' }}>CAT-V</option>
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12" id="sectioncheck"
                    style="padding-top: 5px;">
                    <strong>Section</strong><br>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="A" disabled>
                        <label class="form-check-label" for="inlineCheckbox1">A</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="B" disabled>
                        <label class="form-check-label" for="inlineCheckbox2">B</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="C" disabled>
                        <label class="form-check-label" for="inlineCheckbox3">C</label>
                    </div>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="modeofExam" class="required">Mode of Exam</label>
                    <select class="form-control select2 {{ $errors->has('modeofExam') ? 'is-invalid' : '' }}"
                        name="modeofExam" id="modeofExam">
                        <option value="">Select Mode of Exam</option>
                        <option value="online" {{ old('modeofExam') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="written" {{ old('modeofExam') == 'written' ? 'selected' : '' }}>Written
                        </option>
                    </select>
                    @if ($errors->has('year'))
                        <span class="text-danger">{{ $errors->first('year') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>

                {{--
                <div class="col-3">
                    <label for="modeofExam" class="required">Mode of Exam</label>
                    <select class="form-control select2 {{ $errors->has('modeofExam') ? 'is-invalid' : '' }}"
        name="modeofExam" id="modeofExam">
        <option value="">Select Mode of Exam</option>
        <option value="online" {{ old('modeofExam') == 'online' ? 'selected' : '' }}>Online</option>
        <option value="written" {{ old('modeofExam') == 'written' ? 'selected' : '' }}>Written
        </option>
        </select>
        @if ($errors->has('year'))
        <span class="text-danger">{{ $errors->first('year') }}</span>
        @endif
        <span class="help-block"> </span>
    </div>
    <div class="form-group col-3" style="padding-top: 5px;">
        <div id="sectioncheck">
            <strong>Section</strong><br>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="A" disabled>
                <label class="form-check-label" for="inlineCheckbox1">A</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="B" disabled>
                <label class="form-check-label" for="inlineCheckbox2">B</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="C" disabled>
                <label class="form-check-label" for="inlineCheckbox3">C</label>
            </div>
        </div>
    </div> --}}
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="start_time" class="required">Start Time</label>
                    <div class="input-group ">
                        <input class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}" type="time"
                            name="start_time" id="start_time" value="{{ old('start_time', '') }}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-clock"></i></span>
                        </div>
                    </div>
                    @if ($errors->has('start_time'))
                        <span class="text-danger">{{ $errors->first('start_time') }}</span>
                    @endif
                    <span class="help-block"></span>

                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="end_time" class="required">End Time</label>
                    <div class="input-group ">
                        <input class="form-control  {{ $errors->has('end_time') ? 'is-invalid' : '' }}" type="time"
                            name="end_time" id="end_time" value="{{ old('end_time', '') }}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-clock"></i></span>
                        </div>
                    </div>
                    @if ($errors->has('end_time'))
                        <span class="text-danger">{{ $errors->first('end_time') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">

                    <div class="form-group"><span class='text-danger' id="timeError"></span></div>
                    <div class="form-group"><strong>Duration :</strong><span id="timeDef"></span></div>

                </div>
            </div>
            <!-- <div class="form-group"><strong>Duration :</strong><span id="timeDef"></span></div> -->
            <table class="table table-bordered text-center table-striped table-hover mb-4 mt-4" id='checked_co'
                style='display:none'>
                <thead>
                    <tr class='text-uppercase bg-primary'>
                        <th colspan='3'>COE Exam schedule Mark Details</th>
                    </tr>

                </thead>
                <thead>
                    <tr>
                        <th colspan='3' class='text-uppercase'> All CO Exams Created </th>

                    </tr>

                </thead>
            </table>
            <div id="status" style='display:none'></div>
            <div class="table-responsive">
                <table class="table table-bordered text-center table-striped table-hover mb-4 mt-4" id='checkboxs'
                    style='display:none'>
                    <thead>
                        <tr class='text-uppercase bg-primary'>
                            <th colspan='3'>COE Exam schedule Mark Details</th>
                        </tr>

                    </thead>
                    <thead>
                        <tr class='bg-secondary'>
                            <th>Select</th>
                            <th>Course Outcome</th>
                            <th>Maximum Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check1" name="check[]" value="01">
                            </td>
                            <td>CO-1</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="" id="text1">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check2" name="check[]" value="02">

                            </td>
                            <td>CO-2</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="" id="text2">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check3" name="check[]" value="03">

                            </td>
                            <td>CO-3</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="" id="text3">
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check4" name="check[]" value="04">

                            </td>
                            <td>CO-4</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="" id="text4">
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check5" name="check[]" value="05">

                            </td>
                            <td>CO-5</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="" id="text5">
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered text-center table-striped table-hover mt-5" id='subject_head'
                    style='display:none'>
                    <thead>
                        <tr class='text-uppercase bg-primary'>
                            <th colspan='4'>COE Exam schedule Subject Details</th>
                        </tr>

                    </thead>

                    <thead>
                        <tr class='text-uppercase bg-secondary'>
                            <th>Date</th>
                            <th>Subject Code</th>
                            <th>Subject Tittle</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody id="tabledata">

                    </tbody>
                </table>
            </div>

            <div class="row mt-2 ml-2">


                <button class="btn btn-danger " style='display:none' id='submit' onclick="submit()" type="button">
                    {{ trans('global.save') }}
                </button>

                <button type="button" style='display:none;' id="waiting" value=""
                    class="btn btn-primary">Loading...</button>
            </div>

        </div>




    </div>
    <input type="hidden" name="hidden" id="hidden" value="">
    <input type="hidden" name="hidden2" id="hidden2" value="">
    <input type="hidden" name="hidden3" id="hidden3" value="">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {

            $('#waiting').hide();

            function calculateTimeDifference() {
                var fromTime = $('#start_time').val();
                var toTime = $('#end_time').val();

                if (fromTime && toTime) {
                    var fromHours = parseInt(fromTime.split(':')[0], 10);
                    var toHours = parseInt(toTime.split(':')[0], 10);
                    var fromMinutes = parseInt(fromTime.split(':')[1], 10);
                    var toMinutes = parseInt(toTime.split(':')[1], 10);

                    if (fromTime.includes('PM') && fromHours !== 12) {
                        fromHours += 12;
                    }
                    if (toTime.includes('PM') && toHours !== 12) {
                        toHours += 12;
                    }

                    var totalMinutes = (toHours * 60 + toMinutes) - (fromHours * 60 + fromMinutes);

                    var hours = Math.floor(totalMinutes / 60);
                    var remainingMinutes = totalMinutes % 60;

                    var timeDifference = hours + ' hours ';
                    if ((toHours * 60 + toMinutes) > (fromHours * 60 + fromMinutes)) {
                        if (remainingMinutes > 0) {
                            timeDifference += remainingMinutes + ' minutes';
                        }
                        $('#timeDef').html('' + timeDifference);
                    } else {
                        $('#end_time').val('');
                        $('#timeDef').html('End Time Not less than Start Time');
                    }
                } else {
                    $('#timeDef').html('Please fill in both From Time and To Time.');
                }
            }
            $('#start_time, #end_time').on('input', calculateTimeDifference);
        });




        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#start_time,#end_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",
                clickOpens: true
            });
        });

        $(document).ready(function() {

            const semesterSelect = $("#semester");

            let valuesAndText = getAllSelectValuesAndText(semesterSelect);

            if ($("#year").val() === "") {
                semesterSelect.html('<option value="">Please select a year first</option>');
            }

            $("#year").on("change", function() {
                const year = this.value;
                getSemester();
            });

            $("#semesterType").on("change", function() {
                const semesterType = this.value;
                getSemester();
            });
            // var previousCheckbox = '';

            // $('.checkBox').change(function() {
            //     // Uncheck the previously selected checkbox (if any)
            //     if (previousCheckbox) {
            //         previousCheckbox.prop('checked', false);
            //     }

            //     // If the clicked checkbox is the same as the previously selected one, uncheck it
            //     if (previousCheckbox && previousCheckbox[0] === this) {
            //         $(this).prop('checked', false);
            //         previousCheckbox = null;
            //     } else {
            //         // Uncheck the previously selected checkbox (if any)
            //         if (previousCheckbox) {
            //             previousCheckbox.prop('checked', false);
            //         }

            //         // Store the current checkbox as the previously selected one
            //         previousCheckbox = $(this);
            //     }
            // });

            function getSemester() {
                const year = $("#year").val();
                const semesterType = $("#semesterType").val();

                if (semesterType !== "" && year !== "") {
                    let start = 0;
                    let end = 0;

                    if (year == 1 && semesterType == 'ODD') {
                        start = 0;
                        end = 0;
                    } else if (year == 1 && semesterType == 'EVEN') {
                        start = 1;
                        end = 1;
                    } else if (year == 2 && semesterType == 'ODD') {
                        start = 2;
                        end = 2;
                    } else if (year == 2 && semesterType == 'EVEN') {
                        start = 3;
                        end = 3;
                    } else if (year == 3 && semesterType == 'ODD') {
                        start = 4;
                        end = 4;
                    } else if (year == 3 && semesterType == 'EVEN') {
                        start = 5;
                        end = 5;
                    } else if (year == 4 && semesterType == 'ODD') {
                        start = 6;
                        end = 6;
                    } else if (year == 4 && semesterType == 'EVEN') {
                        start = 7;
                        end = 7;
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

        var newObj = [];
        let semester = "";
        let course_id = "";
        let academicYear = "";
        let semesterType = "";

        $(document).ready(function() {

            if ($("#course_id").val() === "") {
                let sec = '<option value="">Please select a Course first</option>';
                $("#section").html(sec);
            }

            $("#semester").on("change", function() {
                semester = this.value;
                attemptAjaxCall();
            });

            $("#course_id").on("change", function() {
                course_id = this.value;
                attemptAjaxCall();
            });

            $("#accademicYear").on("change", function() {
                academicYear = this.value;
                attemptAjaxCall();
            });
            $("#semesterType").on("change", function() {
                semesterType = this.value;
                attemptAjaxCall();
            });

            function attemptAjaxCall() {
                newObj = []
                if (semester !== "" && course_id !== "" && academicYear !== "" && semesterType !== '' &&
                    $("#semesterType") != '') {
                    // $("#spinner").show();
                    makeAjaxCall();
                } else {
                    $("#checkboxs").hide();
                    $("#submit").hide();
                    $("#tabledata").hide();
                    $("#subject_head").hide();
                    $(".error_message").show();
                    $(".error_message").html('Must be fill the first five columns');

                }
                //  else {
                //     // $("#subject").empty('');
                //     $("#checked_co").hide();
                //     // $("#spinner").hide();
                //     $("#tabledata").html('<th colspan="4" class="text-center"><strong > Fill The First 5 Columns </strong></th>');
                //     $("#subject_head").hide();
                //     $("#sectioncheck").html('<p><strong class="text-center">Select Semester First</strong></p>');
                //     $("#checkboxs").hide();
                //     $("#submit").hide();

                // }
                // if (course_id == "") {
                //     let sec = ' <option value="">No Available Section</option>';
                //     $("#section").html(sec);
                //     // $("#spinner").hide();
                //     $("#checkboxs").hide();
                //     $("#submit").hide();
                //     $("#tabledata").hide();
                //     $("#subject_head").hide();


                // }
            }

            function makeAjaxCall() {
                $("select").prop("disabled", true);
                $(".error_message").hide();
                $("#submit").hide();
                newObj = []
                $.ajax({
                    url: "{{ route('admin.examTimetable.Subject_get') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course_id': course_id,
                        'semester': semester,
                        'accademicYear': academicYear,
                        'semesterType': semesterType,
                    },
                    success: function(response) {
                        newObj = [];
                        // $("#spinner").hide();
                        if (response.status == 'fail') {
                            let sec = ' <option value="">No Available Section</option>';
                            let sub = ' <option value="">No Available Subject</option>';
                            $("#section").html(sec);
                            $("#subject").html(sub);
                            // $("#spinner").hide();
                            $("#checkboxs").hide();
                            $("#subject_head").hide();
                            $("#tabledata").hide();
                            $("#checked_co").hide();
                            // $("select").prop("disabled", false);

                        } else {
                            // console.log(response.get_section);
                            $("#checked_co").hide('');
                            $("#tabledata").html('');
                            if (response.get_section.length > 0) {
                                $("#status").text(' ');
                                let sec = `<strong class='required'>Section</strong> <br>`;
                                let get_sections = response.get_section;
                                for (let i = 0; i < get_sections.length; i++) {
                                    // console.log(get_sections[i].section);

                                    sec += `<div class='form-check form-check-inline'>
        <input class='form-check-input checksection' type='checkbox' id='inlineCheckbox${i+1}' name='checkboxes[]'  value='${get_sections[i][0].section}' onchange="getDetails()">
        <label class='form-check-label' for='inlineCheckbox${i+1}'>${get_sections[i][0].section}</label>
    </div>`;
                                }

                                $("#sectioncheck").html(sec);
                                let sub;
                                if (response.subjects.length > 0) {
                                    let sub = ' <option value="">Select Subject</option>';
                                    let got_subjects = response.subjects;
                                    for (let i = 0; i < got_subjects.length; i++) {
                                        sub +=
                                            `<option style="color:blue;" value="${got_subjects[i].id}"> ${got_subjects[i].name}  (${got_subjects[i].subject_code})</option>`;
                                    }
                                    $("select").select2();
                                    var subj = "";
                                    for (var i = 0; i < got_subjects.length; i++) {
                                        var key = got_subjects[i].id;
                                        newObj.push({
                                            [key]: got_subjects[i].subject_code
                                        });

                                        var subjectCode = got_subjects[i].subject_code;
                                        var subjectName = got_subjects[i].name;
                                        var subjid = got_subjects[i].id;
                                        subj += "<tr>";
                                        // subj += "<td>";
                                        // subj += "<div class='input-group date'>";
                                        // subj +=
                                        //     "<input class='form-control date dates date-field' type='date' name='date' value='' data-subject-id='" +
                                        //     subjid + "' id='date_" + i + "'>";
                                        // subj += "</div>";
                                        // subj += "</td>";
                                        subj += "<td>";
                                        subj +=
                                            "<div class='input-group  div d-flex flex-column' id= 'div_" +
                                            i + "'>";
                                        subj += " <div class='mb-1' id='error-message_" + i +
                                            "'></div>";
                                        subj += " <div>";
                                        subj +=
                                            "<input class='form-control  date-field date dates' type='date' name='date' value='' data-subject-id='" +
                                            subjid + "' id='date_" + i + "'>";
                                        subj += "</div>";
                                        subj += "</div>";
                                        subj += "</td>";
                                        subj += "<td class='subject-code'>" + got_subjects[i]
                                            .subject_code + "</td>";
                                        subj += "<td class='subject-name'>" + got_subjects[i].name +
                                            "</td>";
                                        subj +=
                                            "<td><button type='button' class='btn btn-danger' onclick='removeRow(this, \"" +
                                            key + "\")'>Remove</button></td>";
                                        subj += "</tr>";
                                    }
                                    // $("#subject_head").show();
                                    $("#tabledata").html(subj);
                                    $("#tabledata").show();
                                    // $('#submit').hide();
                                    // $("#checkboxs").show();

                                    $('.date-field').on('change', function() {
                                        var selectedDate = $(this).val();
                                        var selectedDate2 = $(this).attr('id');
                                        var errorMessageId = "#error-message_" + selectedDate2
                                            .split("_")[1]; // Extract the index
                                        // Track whether the date has been removed from another input field
                                        var dateRemoved = false;
                                        $('.date-field').not(this).each(function() {
                                            if ($(this).val() === selectedDate && !
                                                dateRemoved) {
                                                var inputId = $(this).attr("id");

                                                $("#" + selectedDate2).val('');
                                                $(errorMessageId).html(
                                                    'Selected Date Removed').css(
                                                    'background', 'red').fadeIn();
                                                // console.log($(this).attr("id"));
                                                // console.log(selectedDate2);

                                                // Set a timer to hide the error message after 3 seconds (3000 milliseconds)
                                                setTimeout(function() {
                                                    $(errorMessageId).fadeOut();
                                                }, 3000);

                                                // Mark that the date has been removed from another input field
                                                dateRemoved = true;
                                            }
                                        });
                                    });
                                    // $("select").prop("disabled", false);
                                } else {
                                    $("#tabledata").html(
                                        '<th colspan="4" class="text-center"><strong >No Subject Available For This Department</strong></th>'
                                    );
                                    $("#tabledata").show();
                                    $('#submit').hide();
                                    // $("#spinner").hide();
                                    $("#checkboxs").hide();
                                    $("#subject_head").hide();
                                    // $("select").prop("disabled", false);


                                    // $("#subject").html(sub);
                                }
                            } else {

                                $("#sectioncheck").html(
                                    '<p><strong class="text-center">No Section Available For This Department</strong></p>'
                                );
                                $("#tabledata").html(
                                    '<th colspan="4" class="text-center"><strong >NO Section and No Subject Available For This Selected Year Department</strong></th>'
                                );
                                $("#tabledata").show();
                                $('#submit').hide();
                                $("#checkboxs").hide();
                                $("#status").html('No Section');
                                $("#subject_head").hide();
                                $("select").prop("disabled", false);

                                // $('#waiting').hide();

                            }
                        }
                    }
                });
            }
        });


        function removeRow(button, id) {
            button.closest('tr').remove();
            newObj = newObj.filter(function(obj) {
                return Object.keys(obj)[0] !== id;
            });
        }

        // $(document).on('change', '.checksection', handleCheckboxSelection);



        var secTion = [];

        function section() {

            // console.log(secTion);
        }

        function submit() {
            $('#submit').hide();
            $('#waiting').show();
            var checksection = $('.checksection');
            var dateClass = $('.dates');
            var isEmpty = false;
            var emptyIds = [];
            var elementIds = [
                'accademicYear',
                'semesterType',
                'course_id',
                'year',
                'semester',
                // 'exameType',
                'examName',
                'modeofExam',
                'start_time',
                'end_time'
            ];

            $.each(elementIds, function(index, elementId) {
                var value = $('#' + elementId).val();
                if (!value) {
                    isEmpty = true;
                    emptyIds.push(elementId);
                }
            });

            // $.each(dateClass, function(index, element) {
            //     var values = $('#date_' + index).val();
            //     if (!values) {
            //         isEmpty = true;
            //         emptyIds.push(element);
            //     }
            // })
            $.each(dateClass, function(index, element) {
                var $dateElement = $('#date_' + index);

                // Check if the element with the specified ID exists and is enabled
                if ($dateElement.length === 0 || !$dateElement.is(':enabled')) {
                    // Skip this iteration and move on to the next one
                    return true;
                }

                var values = $dateElement.val();

                if (values === '') {
                    values = $dateElement.attr('id');
                    isEmpty = true;
                    emptyIds.push(element);
                }
            });



            checksection.each(function(index) {
                if ($(this).is(":checked")) {
                    var sectionsselection = $('#inlineCheckbox' + (index + 1)).val();
                    secTion.push(sectionsselection);
                }
            })

            var formData = {};
            var hasError = false;

            $('input[type="checkbox"].checkBox').each(function(index) {
                if ($(this).is(":checked")) {
                    var inputField = $('input[name="marks[]"]').eq(index);
                    var inputValue = inputField.val();

                    if (inputValue.trim() === "") {
                        hasError = true;
                    } else if (isNaN(parseInt(inputValue))) {
                        hasError = true;
                    } else {

                        if (!isNaN(parseInt(inputValue))) {
                            formData['CO-' + (index + 1)] = inputValue;
                        }
                    }
                }
            });

            if (hasError) {
                alert("Please enter valid non-empty marks for the selected checkboxes.");
            } else {
                // Initialize the newObj with the subject IDs and their respective dates
                var newObj = [];
                $('.dates').each(function(index) {
                    var subjectId = $(this).data('subject-id');
                    var date = $(this).val();
                    var subjectEntry = {};
                    subjectEntry[subjectId] = date;
                    newObj.push(subjectEntry);
                });


                $("#hidden").val(JSON.stringify(formData));
                $("#hidden2").val(JSON.stringify(newObj));

                if (!isEmpty) {
                    $.ajax({
                        url: "{{ route('admin.examTimetable.store') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'accademicYear': $('#accademicYear').val(),
                            'semesterType': $('#semesterType').val(),
                            'course_id': $('#course_id').val(),
                            'year': $('#year').val(),
                            'semester': $('#semester').val(),
                            // 'exameType': $('#exameType').val(),
                            'examName': $('#examName').val(),
                            'modeofExam': $('#modeofExam').val(),
                            'start_time': $('#start_time').val(),
                            'end_time': $('#end_time').val(),
                            'co_mark': $('#hidden').val(),
                            'subject': $('#hidden2').val(),
                            'sections': secTion,
                        },
                        success: function(response) {
                            window.location.href = "{{ route('admin.Exam-time-table.index') }}";
                            // $('#submit').show();
                            // $('#waiting').hide();
                        }
                    });
                } else {
                    alert(`Fill the following fields: ${emptyIds.join(', ')}`);
                    $('#submit').show();
                    $('#waiting').hide();
                }
            }
        }


        $(document).ready(function() {

            $("#checked_co").hide();
            //  $("#subject_head").hide();
            var ids = $("#accademicYear, #year, #course_id, #semester, #checksection, #semesterType");
            $(document).on('click', '.checksection', function() {
                section();
            });


            ids.on('change', function() {
                if ($("#semester").val() !== "" && $("#course_id").val() !== "" && $("#accademicYear")
                    .val() !== "" && $("#semesterType").val() !== '' && $("#year").val() != '') {
                    runFunction();
                } else {
                    $(".error_message").show();
                    $(".error_message").html('Must be fill the first five columns');
                    $("#checkboxs").hide();
                    $("#submit").hide();
                    $("#tabledata").hide();
                    $("#subject_head").hide();
                    $("#sectioncheck").show();
                    $("select").prop("disabled", false);
                }
            });
        });
        let checkBoxValues = [];

        function getDetails() {
            checkBoxValues = [];
            $(".form-check-input").each(function() {
                if ($(this).prop("checked")) {
                    checkBoxValues.push($(this).val());
                }
            })
            runFunction();
        }
        var token = $('meta[name="csrf-token"]').attr('content');

        function runFunction() {

            $("select").prop("disabled", true);
            $("#sectioncheck").hide();

            $(".error_message").hide();
            $("#spinner").show();
            $("#checkboxs").hide();
            $("#subject_head").hide();
            $("#checked_co").hide();

            let newDatas;
            $('#check1').prop("checked", false);
            $('#check2').prop("checked", false);
            $('#check3').prop("checked", false);
            $('#check4').prop("checked", false);
            $('#check5').prop("checked", false);

            $('#check1').prop("disabled", false);
            $('#check2').prop("disabled", false);
            $('#check3').prop("disabled", false);
            $('#check4').prop("disabled", false);
            $('#check5').prop("disabled", false);
            $('#text1').val('');
            $('#text2').val('');
            $('#text3').val('');
            $('#text4').val('');
            $('#text5').val('');

            $('#text1').prop('disabled', false);
            $('#text2').prop('disabled', false);
            $('#text3').prop('disabled', false);
            $('#text4').prop('disabled', false);
            $('#text5').prop('disabled', false);
            section();
            let boxValues = '';
            if (checkBoxValues.length > 0) {
                for(let i = 0; i < checkBoxValues.length;i++){
                    boxValues += checkBoxValues[i];
                }
            }
            var theData = {
                'accademicYear': $('#accademicYear').val(),
                'course': $('#course_id').val(),
                'sem': $('#semester').val(),
                'year': $('#year').val(),
                'boxValues': boxValues
            };
            $.ajax({
                url: "{{ route('admin.examTimetable.Check') }}",
                type: 'POST',
                data: theData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    newDatas = response.data;
                    if (newDatas != 'No valid data found in the response for this item.') {
                        if (newDatas != 'No matching records found') {

                            if (newDatas && newDatas.serializeCo) {
                                try {
                                    const decodedCo = JSON.parse(newDatas.serializeCo);
                                    const count = Object.keys(decodedCo).length;

                                    // console.log(count);
                                    if (count == 5) {
                                        $("#checked_co").show();
                                        $("#checkboxs").hide();
                                        $("#subject_head").show();
                                        $("#tabledata").html('');
                                        $("#tabledata").html(
                                            '<th colspan="4" class="text-center"><strong >All CO Exams Created</strong></th>'
                                        );
                                        $("#submit").hide();
                                        $("#subject_head").show();
                                        $("#spinner").hide();
                                        $("select").prop("disabled", false);


                                    } else {


                                        if (decodedCo) {
                                            const coKeys = Object.keys(decodedCo);

                                            for (const [index, key] of coKeys.entries()) {
                                                const checkbox = $("#check" + (index + 1));
                                                const text = $('#text' + (index + 1));

                                                checkbox.prop("checked", false);
                                                text.val('');
                                                checkbox.prop("disabled", false);
                                                text.prop('disabled', false);
                                            }

                                            for (const [index, key] of coKeys.entries()) {
                                                const checkbox = $("#check" + (index + 1));
                                                const text = $('#text' + (index + 1));

                                                const value = decodedCo[key];

                                                if (value !== null) {
                                                    // checkbox.prop("checked", true);
                                                    checkbox.prop("disabled", true);

                                                    text.val(value);
                                                    text.prop('disabled', true);
                                                }
                                            }
                                        }
                                        $("#checkboxs").show();
                                        $("#subject_head").show();
                                        $("#submit").show();
                                        $("#spinner").hide();
                                        $("#sectioncheck").show();
                                        $("select").prop("disabled", false);
                                        // console.log('submit');

                                    }

                                } catch (error) {
                                    console.error('Error parsing JSON:', error);
                                    $("#spinner").hide();
                                    $("#sectioncheck").show();
                                    $("select").prop("disabled", false);

                                }
                            } else {
                                // console.log('No valid data found in the response for this item.');
                                $("select").prop("disabled", false);
                                $("#sectioncheck").show();

                                // $("#spinner").hide();

                            }
                        } else if (newDatas == 'No matching records found') {

                            if ($("#status").text() != 'No Section') {
                                $("#checkboxs").show();
                                $("#submit").show();


                            } else {
                                $("#checkboxs").hide();
                            }
                            $("#sectioncheck").show();
                            $("#subject_head").show();
                            $("#spinner").hide();
                            $("select").prop("disabled", false);

                        } else {
                            // console.log('No valid data found in the response for this item.');
                            // $("#spinner").hide();
                            $("select").prop("disabled", false);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.log('An error occurred: ' + error);
                    $("#spinner").hide();
                    $("select").prop("disabled", false);

                }
            });
        }
    </script>
@endsection

@if (session('success'))
    @section('scripts')
        @parent
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endsection
@endif
