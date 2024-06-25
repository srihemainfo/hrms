@extends('layouts.admin')
@section('content')
    <style>
        .borderNone {
            border: none;
        }

        @media screen and (max-width: 575px) {
        .select2 {
        width: 100% !important;
    }}
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <div class="form-group" style="padding-top: 20px;padding-left:20px;">
        <a class="btn btn-default" href="{{ route('admin.Exam-time-table.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header text-center text-bold">
            Edit Exam TimeTable
        </div>


        <div class="card-body">

            {{-- <form method="POST" action="{{ route('admin.examTimetable.store') }}" enctype="multipart/form-data"
                id="myForm">
                @csrf --}}
            <div id="spinner" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <div class="row">

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 col">
                    <label class="required d-block" for="accademicYear">Academic Year</label>
                    <select class="form-control select2 {{ $errors->has('accademicYear') ? 'is-invalid' : '' }}"
                        name="accademicYear" id="accademicYear" required>
                        <option value="">Please Select</option>

                        @foreach ($AcademicYear as $id => $entry)
                            <option
                                value="{{ $id }}"{{ ($examTimetable->accademicYear ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('accademicYear'))
                        <span class="text-danger">{{ $errors->first('accademicYear') }}</span>
                    @endif
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 float-right">
                    <label for="semesterType" class="required d-block">Semester Type</label>
                    <select class="form-control select2 {{ $errors->has('semesterType') ? 'is-invalid' : '' }}"
                        name="semesterType" id="semesterType">
                        <option value="">Select Semester Type</option>
                        <option value="ODD" {{ ($examTimetable->semesterType ?? '') == 'ODD' ? 'selected' : '' }}>ODD
                        </option>
                        <option value="EVEN" {{ ($examTimetable->semesterType ?? '') == 'EVEN' ? 'selected' : '' }}>EVEN
                        </option>

                    </select>
                    @if ($errors->has('semesterType'))
                        <span class="text-danger">{{ $errors->first('semesterType') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="required d-block" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <select class="form-control select2 {{ $errors->has('course') ? 'is-invalid' : '' }}" name="course"
                        id="course_id" required>
                        <option value="">Please Select</option>

                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}"
                                {{ ($examTimetable->course ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('course'))
                        <span class="text-danger">{{ $errors->first('course') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.lesson.fields.course_helper') }}</span>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="year" class="required d-block">Year</label>
                    <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year"
                        id="year">
                        <option value="">Select Year</option>
                        <option value="01" {{ ($examTimetable->year ?? '') == '01' ? 'selected' : '' }}>I
                        </option>
                        <option value="02" {{ ($examTimetable->year ?? '') == '02' ? 'selected' : '' }}>II
                        </option>
                        <option value="03" {{ ($examTimetable->year ?? '') == '03' ? 'selected' : '' }}>III
                        </option>
                        <option value="04" {{ ($examTimetable->year ?? '') == '04' ? 'selected' : '' }}>IV
                        </option>

                    </select>
                    @if ($errors->has('year'))
                        <span class="text-danger">{{ $errors->first('year') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>


            
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="semester" class="required d-block">Semester</label>
                    <select class="form-control select2" name="semester" id="semester" required>
                        <!-- <option value="">Please Select</option> -->

                        @foreach ($semester as $id => $entry)
                            <option value="{{ $id }}"
                                {{ ($examTimetable->semester ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('semester'))
                        <span class="text-danger">{{ $errors->first('semester') }}</span>
                    @endif
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="section" class="required d-block">Sections</label>
                    <select class="form-control select2" name="sections" id="sections">
                        <option value="">Please Select</option>
                        <option value="A" {{ ($examTimetable->sections ?? '') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ ($examTimetable->sections ?? '') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ ($examTimetable->sections ?? '') == 'C' ? 'selected' : '' }}>C</option>


                    </select>
                </div>

                {{-- <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="exameType" class="required">Exame Type</label>
                    <select class="form-control select2 {{ $errors->has('exameType') ? 'is-invalid' : '' }}"
                        name="exameType" id="exameType">
                        <option value="">Select Exame Type</option>
                        <option value="01" {{ ($examTimetable->exameType ?? '') == '01' ? 'selected' : '' }}>Internal
                        </option>
                        <option value="02" {{ ($examTimetable->exameType ?? '') == '02' ? 'selected' : '' }}>External
                        </option>

                    </select>
                    @if ($errors->has('exameType'))
                        <span class="text-danger">{{ $errors->first('exameType') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div> --}}
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="required d-block" for="examName">Title of the Exam</label>
                    <input class="form-control {{ $errors->has('examName') ? 'is-invalid' : '' }}" type="text"
                        name="examName" id="examName" value="{{ $examTimetable->exam_name }}">
                    @if ($errors->has('examName'))
                        <span class="text-danger">{{ $errors->first('examName') }}</span>
                    @endif
                    {{-- <span class="help-block">examName</span> --}}
                </div>
            
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="modeofExam" class="required d-block">Mode of Exam</label>
                    <select class="form-control select2 {{ $errors->has('modeofExam') ? 'is-invalid' : '' }}"
                        name="modeofExam" id="modeofExam">
                        <option value="">Select Mode of Exam</option>
                        <option value="online" {{ ($examTimetable->modeofExam ?? '') == 'online' ? 'selected' : '' }}>
                            Online</option>
                        <option value="written" {{ ($examTimetable->modeofExam ?? '') == 'written' ? 'selected' : '' }}>
                            Written</option>

                    </select>
                    @if ($errors->has('year'))
                        <span class="text-danger">{{ $errors->first('year') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label for="start_time" class="required d-block">Start Time</label>
                    <div class="input-group ">
                        <input class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}" type="text"
                            name="start_time" id="start_time" value="{{ $examTimetable->start_time }}">
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
                    <label for="end_time" class="required d-block">End Time</label>
                    <div class="input-group ">
                        <input class="form-control  {{ $errors->has('end_time') ? 'is-invalid' : '' }}" type="text"
                            name="end_time" id="end_time" value="{{ $examTimetable->end_time }}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-clock"></i></span>
                        </div>
                    </div>
                    @if ($errors->has('end_time'))
                        <span class="text-danger">{{ $errors->first('end_time') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
                {{-- <div class="form-group col-3"><strong>Duration :</strong><span id="timeDef"></span></div> --}}

                {{-- <div class="form-group">
                    <label for="subject" class="required">Subject</label>
                    <select class="form-control select2" name="subject" id="subject" required>
                        <option value="">Please Select</option>

                        @foreach ($Subjects as $entry)
                            <option value="{{ $entry->id }}"{{ ($examTimetable->subject ?? '') == $entry->id ? 'selected' : '' }}>
                                {{ $entry->name }}({{ $entry->subject_code }})</option>
                        @endforeach
                    </select>
                    @if ($errors->has('subject'))
                        <span class="text-danger">{{ $errors->first('subject') }}</span>
                    @endif
                </div> --}}
                <div class="table-responsive">
                <table class="table table-bordered text-center table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Course Outcome</th>
                            <th>Maximum Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check1" name="check[]" value="01"
                                    {{ ($examTimetable->co_1 ?? '') != '' ? 'checked' : '' }}>
                            </td>
                            <td>CO-1</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_1 }}" id="text1">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check2" name="check[]" value="02"
                                    {{ ($examTimetable->co_2 ?? '') != '' ? 'checked' : '' }}>

                            </td>
                            <td>CO-2</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_2 }}" id="text2">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check3" name="check[]" value="03"
                                    {{ ($examTimetable->co_3 ?? '') != '' ? 'checked' : '' }}>

                            </td>
                            <td>CO-3</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_3 }}" id="text3">
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check4" name="check[]"
                                    value="04"{{ ($examTimetable->co_4 ?? '') != '' ? 'checked' : '' }}>

                            </td>
                            <td>CO-4</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_4 }}" id="text4">
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check5" name="check[]"
                                    value="05"{{ ($examTimetable->co_5 ?? '') != '' ? 'checked' : '' }}>

                            </td>
                            <td>CO-5</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_5 }}" id="text5">
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">

          
                </table>
                <table class="table table-bordered text-center table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Subject Tittle</th>
                            <th>Subject code</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <!-- <tbody id="tabledata">
                        @php
                            $i=0;
                        @endphp
                        @foreach ($examTimetable->newsubject as $id => $subjectA)
                            <tr>

                                <td>

                                    <div class="input-group  div d-flex flex-column" id="div_{{ $i }}">
                                        <div class="mb-1" id="error-message_{{ $i }}"></div>
                                        <div>
                                            <input type="date" class="form-control  dates date-field"
                                        id="date_{{ $id }}" name="subject_dates[]"
                                        value="{{ $subjectA['date'] ?? '' }}"
                                        data-subject-id="{{ $subjectA['id'] ?? '' }}">
                                        </div>
                                    </div>

                                </td>
                                <td class="subject-code">{{ $subjectA['name'] ?? '' }}</td>
                                <td>{{ $subjectA['code'] ?? '' }}</td>

                            </tr>
                            @php
                            $i++;
                        @endphp
                        @endforeach
                    </tbody> -->
                    <tbody id="tabledata">
                        @php
                            $i=0;
                        @endphp
                        @foreach ($examTimetable->newsubject as $id => $subjectA)
                            <tr id="tr_{{$i}}" class="{{ $subjectA['date'] == NULL ? '' : '' }} ">

                                <td>

                                    <div class="input-group  div d-flex flex-column" id="div_{{ $i }}">
                                        <div class="mb-1" id="error-message_{{ $i }}"></div>
                                        <div>
                                            <input type="date" class="form-control  date-field {{ $subjectA['date'] != NULL ? 'dates' : '' }} " @if( $subjectA['date'] == NULL) disabled @endif id="date_{{ $id }}" name="subject_dates[]"   value="{{ $subjectA['date'] ?? '' }}"
                                        data-subject-id="{{ $subjectA['id'] ?? '' }}">
                                        </div>
                                    </div>

                                </td>
                                <td class="subject-code">{{ $subjectA['name'] ?? '' }}</td>
                                <td>{{ $subjectA['code'] ?? '' }}</td>
                                <td>
                                        <!-- <button type='button' class='btn btn-danger' onclick="removeRow(this, {{$id}})">Remove</button> -->

                                        <button type='button' class="btn  add {{ $subjectA['date'] != NULL ? 'btn-danger' : 'btn-success' }} "  id='subject_add' data-id="{{$id}}">@if( $subjectA['date'] == NULL) Add @else Remove @endif</button>

                                </td>


                            </tr>
                            @php
                            $i++;
                        @endphp
                        @endforeach
                    </tbody>

                </table>
            </div>



                <div class="row mt-2 ml-2">
                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                        <button class="btn btn-danger" onclick="submit()" type="button">
                            {{ trans('global.save') }}
                        </button>

                    </div>

                </div>




            </div>
            <input type="hidden" name="hidden" id="hidden" value="">
            <input type="hidden" name="hidden2" id="hidden2" value="">
            <input type="hidden" name="hidden3" id="hidden3" value="">

            {{-- </form> --}}
            {{-- <div class="form-group">
            <label for="subject" class="required">Sections</label> <br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="checkboxes[]"
                    value="A">
                <label class="form-check-label" for="inlineCheckbox1">A</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="checkboxes[]"
                    value="B">
                <label class="form-check-label" for="inlineCheckbox2">B</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="checkboxes[]"
                    value="C">
                <label class="form-check-label" for="inlineCheckbox3">C</label>
            </div>
    </div> --}}




















            {{-- <div class="form-group">
                    <label for="block" class="required">Block</label>
                    <select class="form-control select2" name="block" id="block" required>
                        <option value="">Please Select</option>

                        @foreach ($blocks as $id => $entry)
                            <option value="{{ $id }}" {{ old('block') == $id ? 'selected' : '' }}>
    {{ $entry }}</option>
    @endforeach
    </select>
    @if ($errors->has('block'))
    <span class="text-danger">{{ $errors->first('block') }}</span>
    @endif
</div> --}}
            {{-- <div class="form-group"> --}}
            {{-- <label for="room" class="required">Room Number</label> --}}
            {{-- <select class="form-control select2" name="room[]" id="room" multiple required> --}}
            {{-- <option value="">Please Select</option> --}}
            {{-- @foreach ($classrooms as $id => $entry) --}}
            {{-- <option value="{{ $entry }}" {{ in_array($entry, []) ? 'selected' : '' }}> --}}
            {{-- {{ $entry }}</option> --}}
            {{-- @endforeach --}}
            {{-- </select> --}}
            {{-- @if ($errors->has('room')) --}}
            {{-- <span class="text-danger">{{ $errors->first('room') }}</span> --}}
            {{-- @endif --}}
            {{-- </div> --}}



        </div>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </div>
    {{-- <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Course Outcomes and Marks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="save" value="" onclick="comarkenter(this)"
                        class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('scripts')
    @parent
    <script>
        //     $(document).ready(function () {
        //     var selectedDates = [];

        //     $('.date').on('change', function () {
        //         var selectedDate = $(this).val();
        //         console.log(selectedDate);
        //         // Check if the selected date is already in the array
        //         if (selectedDates.indexOf(selectedDate) !== -1) {
        //             alert('This date has already been selected.');
        //             // Reset the input value
        //             $(this).val('');
        //         } else {
        //             // Add the selected date to the array
        //             selectedDates.push(selectedDate);
        //         }
        //     });
        // });

        $(document).ready(function() {
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
                    if (remainingMinutes > 0) {
                        timeDifference += remainingMinutes + ' minutes';
                    }

                    $('#timeDef').html('' + timeDifference);
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

            $(".add").each(function() {
            var id = $(this).data("id");
            var element = $(this);
            var dateId = '#date_' + id;
            // var trId = '#tr_' + id;
            element.click(function() {
                var dateElement = $(dateId);
                dateElement.prop("disabled", !dateElement.prop("disabled"));

                if (dateElement.prop("disabled")) {
                    dateElement.removeClass('dates');
                    element.text("Add");
                    element.removeClass('btn-danger');
                    element.addClass('btn-success');

                } else {
                    dateElement.addClass('dates');
                    element.text("Remove");
                    element.removeClass('btn-success');
                    element.addClass('btn-danger');

                }

            });
        });

            $('.date-field').on('change', function() {
                var selectedDate = $(this).val();
                var selectedDate2 = $(this).attr('id');
                var errorMessageId = "#error-message_" + selectedDate2
                    .split("_")[1]; // Extract the index
                // Track whether the date has been removed from another input field
                var dateRemoved = false;

                    var selectedDate = $(this).val();
                    var selectedDate2 = $(this).attr('id');
                    var errorMessageId = "#error-message_" +
                        selectedDate2.split("_")[
                            1]; // Extract the index
                    // Track whether the date has been removed from another input field
                    var dateRemoved = false;
                    $('.date-field').not(this).each(function() {
                        if ($(this).val() ===
                            selectedDate && !dateRemoved
                        ) {
                            var inputId = $(this).attr(
                                "id");

                            $("#" + selectedDate2).val(
                                '');
                            $(errorMessageId).html(
                                'Selected Date Removed'
                            ).css('background',
                                'red').fadeIn();
                            console.log($(this).attr(
                                "id"));
                            console.log(selectedDate2);

                            // Set a timer to hide the error message after 3 seconds (3000 milliseconds)
                            setTimeout(function() {
                                $(errorMessageId)
                                    .fadeOut();
                            }, 3000);

                            // Mark that the date has been removed from another input field
                            dateRemoved = true;
                        }
                    });
                });

        })
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

            function getSemester() {
                const year = $year.val();
                const semesterType = $semesterType.val();

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

        var newObj = {};
        $(document).ready(function() {
            const $semesterSelect = $("#semester");
            const $courseSelect = $("#course_id");
            const $academicYearSelect = $("#accademicYear");
            const $subject = $("#subject");
            const $tabledata = $("#tabledata");
            const $section = $("#section");
            const $sectioncheck = $("#sectioncheck");
            const $spinner = $("#spinner");
            const $semesterType = $("#semesterType");

            let semester = "";
            let course_id = "";
            let academicYear = "";
            let semesterType = "";

            if ($courseSelect.val() === "") {
                let sec = '<option value="">Please select a Course first</option>';
                $section.html(sec);
            }

            $semesterSelect.on("change", function() {
                semester = this.value;
                attemptAjaxCall();
            });

            $courseSelect.on("change", function() {
                course_id = this.value;
                attemptAjaxCall();
            });

            $academicYearSelect.on("change", function() {
                academicYear = this.value;
                attemptAjaxCall();
            });
            $semesterType.on("change", function() {
                semesterType = this.value;
                attemptAjaxCall();
            });

            function attemptAjaxCall() {
                newObj = {}
                if (semester !== "" && course_id !== "" && academicYear !== "" && semesterType !== '') {
                    $spinner.show();
                    makeAjaxCall();
                } else {
                    $subject.html('');
                    $spinner.hide();
                }
                if (course_id == "") {
                    let sec = ' <option value="">No Available Section</option>';
                    $section.html(sec);
                    $spinner.hide();
                }
            }

            function makeAjaxCall() {
                newObj = {}
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
                        newObj = {}
                        $spinner.hide();
                        if (response.status == 'fail') {
                            let sec = ' <option value="">No Available Section</option>';
                            let sub = ' <option value="">No Available Subject</option>';
                            $section.html(sec);
                            $subject.html(sub);
                            $spinner.hide();
                        } else {

                            if (response.get_section.length > 0) {
                                let sec = `<strong class='required'>Section</strong> <br>`;
                                let get_sections = response.get_section;
                                for (let i = 0; i < get_sections.length; i++) {
                                    sec += `<div class='form-check form-check-inline'>
        <input class='form-check-input checksection' type='checkbox' id='inlineCheckbox${i+1}' name='checkboxes[]'  value='${get_sections[i].section}'>
        <label class='form-check-label' for='inlineCheckbox${i+1}'>${get_sections[i].section}</label>
    </div>`;
                                }
                                $sectioncheck.html(sec);
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
                                    $tabledata.html(subj);

                                } else {
                                    $spinner.hide();
                                    $subject.html(sub);
                                }
                            }
                        }
                    }
                });
            }
        });

        function removeRow(button, id) {
            button.closest('tr').remove();
            delete newObj[id];
        }
        $(".remove-row").on("click", function() {
            // Find the closest <tr> element to the button and remove it
            $(this).closest("tr").remove();
        });
        // $(document).on('change', '.checksection', handleCheckboxSelection);



        var secTion = [];

        function section() {




            console.log(secTion);
        }

        function submit() {
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
                        inputField.addClass("error");
                        hasError = true;
                    } else if (isNaN(parseInt(inputValue))) {
                        inputField.addClass("error");
                        hasError = true;
                    } else {
                        inputField.removeClass("error");

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
                var newObj = {};
                var subjectArray = [];
                $('.dates').each(function(index) {
                    var subjectId = $(this).data('subject-id');
                    var date = $(this).val();
                    // newObj[subjectId] = date;
                    subjectArray.push({
                        [subjectId]: date
                    });
                });
                // var subjectData = @json($examTimetable->newsubject);
                // var subjectArray = [];

                // $.each(subjectData, function(index, subject) {
                //     subjectArray.push({ [subject.id]: subject.date });
                // });


                $("#hidden").val(JSON.stringify(formData));
                $("#hidden2").val(JSON.stringify(subjectArray));

                if (!isEmpty) {
                    $.ajax({
                        url: "{{ route('admin.examTimetable.update', [$examTimetable->id]) }}",
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
                            'sections': $('#sections').val(),
                        },
                        success: function(response) {
                            window.location.href = "{{ route('admin.Exam-time-table.index') }}";
                        }
                    });
                } else {
                    alert(`Fill the following fields: ${emptyIds.join(', ')}`);
                }
            }
        }


        $(document).ready(function() {
            var ids = $("#accademicYear, #year, #course_id, #semester, #checksection");
            var token = $('meta[name="csrf-token"]').attr('content');
            $(document).on('click', '.checksection', function() {
                section();
            });

            ids.on('change', function() {
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
                var data = {
                    accademicYear: $('#accademicYear').val(),
                    course: $('#course_id').val(),
                    sem: $('#semester').val(),
                    year: $('#year').val(),
                    _token: token
                };
                $.ajax({
                    url: "{{ route('admin.examTimetable.Check') }}",
                    method: 'POST',
                    data: data,
                    success: function(response) {

                        newDatas = response.data;

                        if (newDatas && newDatas.serializeCo) {
                            try {
                                const decodedCo = JSON.parse(newDatas.serializeCo);

                                if (decodedCo) {
                                    const coKeys = Object.keys(decodedCo);

                                    for (const [index, key] of coKeys.entries()) {
                                        const checkbox = $("#check" + (index + 1));
                                        const text = $('#text' + (index + 1));

                                        checkbox.prop("checked", false);
                                        text.val('');
                                        checkbox.prop("disabled", false);
                                        // text.prop('disabled', false);
                                    }

                                    for (const [index, key] of coKeys.entries()) {
                                        const checkbox = $("#check" + (index + 1));
                                        const text = $('#text' + (index + 1));

                                        const value = decodedCo[key];

                                        if (value !== null) {
                                            // checkbox.prop("checked", true);
                                            // checkbox.prop("disabled", true);

                                            text.val(value);
                                            // text.prop('disabled', true);
                                        }
                                    }
                                }
                            } catch (error) {
                                console.error('Error parsing JSON:', error);
                            }
                        } else {
                            console.log('No valid data found in the response for this item.');
                        }


                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                    }
                });
            });
        });
    </script>
@endsection

@if (session('success'))
    @section('scripts')
        @parent
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success ') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endsection
@endif
