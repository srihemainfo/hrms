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
        @media screen and (max-width: 1366px) {
            .select2 {
                width: 100% !important;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.assignment.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header text-uppercase text-center">
            <p class='text-center  text-uppercase'> <strong>
                    {{ trans('global.create') }} Assignment Schedule </strong></p>
        </div>


        <div class="card-body bg-light bg-gradient-primary">

            <div class="row">
                <div id="spinner" class='form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12' style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
                <div class="form-group col-xl-9 col-lg-9 col-md-9 col-sm-6 col-12 error_message text-center text-danger" style="display: none;">

                </div>
            </div>
            <div class="row">

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 justify-content-between ">
                    <label class="required" for="academic_year">Academic Year</label>
                    <select class="form-control select2 {{ $errors->has('academic_year') ? 'is-invalid' : '' }}"
                        name="academic_year" id="academic_year" required>
                        <option value="">Please Select</option>

                        @foreach ($AcademicYear as $id => $entry)
                            <option
                                value="{{ $id }}"{{ ($assignmentSchedule->academic_year ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('academic_year'))
                        <span class="text-danger">{{ $errors->first('academic_year') }}</span>
                    @endif
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12  float-right">
                    <label for="semester_type" class="required">Semester Type</label>
                    <select class="form-control select2 {{ $errors->has('semester_type') ? 'is-invalid' : '' }}"
                        name="semester_type" id="semester_type">
                        <option value="">Select Semester Type</option>
                        <option value="ODD" {{ ($assignmentSchedule->semester_type ?? '') == 'ODD' ? 'selected' : '' }}>
                            ODD
                        </option>
                        <option value="EVEN"
                            {{ ($assignmentSchedule->semester_type ?? '') == 'EVEN' ? 'selected' : '' }}>EVEN
                        </option>

                    </select>
                    @if ($errors->has('semester_type'))
                        <span class="text-danger">{{ $errors->first('semester_type') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <select class="form-control select2 {{ $errors->has('course_id') ? 'is-invalid' : '' }}" name="course"
                        id="course_id" required>
                        <option value="">Please Select</option>

                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}"
                                {{ ($assignmentSchedule->course_id ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('course'))
                        <span class="text-danger">{{ $errors->first('course') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.lesson.fields.course_helper') }}</span>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label for="year" class="required">Year</label>
                    <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year"
                        id="year">
                        <option value="">Select Year</option>
                        <option value="01" {{ ($assignmentSchedule->year ?? '') == '01' ? 'selected' : '' }}>I
                        </option>
                        <option value="02" {{ ($assignmentSchedule->year ?? '') == '02' ? 'selected' : '' }}>II
                        </option>
                        <option value="03" {{ ($assignmentSchedule->year ?? '') == '03' ? 'selected' : '' }}>III
                        </option>
                        <option value="04" {{ ($assignmentSchedule->year ?? '') == '04' ? 'selected' : '' }}>IV
                        </option>

                    </select>
                    @if ($errors->has('year'))
                        <span class="text-danger">{{ $errors->first('year') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>



                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label for="semester" class="required">Semester</label>
                    <select class="form-control select2" name="semester" id="semester" required>
                        @foreach ($semester as $id => $entry)
                            <option value="{{ $id }}"
                                {{ ($assignmentSchedule->semester ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('semester'))
                        <span class="text-danger">{{ $errors->first('semester') }}</span>
                    @endif
                </div>



                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label for="section" class="required">Sections</label>
                    <select class="form-control select2" name="section" id="sections">
                        <option value="">Please Select</option>
                        <option value="A" {{ ($assignmentSchedule->section ?? '') == 'A' ? 'selected' : '' }}>A
                        </option>
                        <option value="B" {{ ($assignmentSchedule->section ?? '') == 'B' ? 'selected' : '' }}>B
                        </option>
                        <option value="C" {{ ($assignmentSchedule->section ?? '') == 'C' ? 'selected' : '' }}>C
                        </option>


                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label for="due_date" class="required">Due Date</label>
                    <input type="date" class="form-control date" name="due_date" id="due_date"
                        value="{{ $assignmentSchedule->due_date ?? '' }}">
                    <input type="hidden" name="labExam" id="labExam" value="labExam">
                    @if ($errors->has('year'))
                        <span class="text-danger">{{ $errors->first('due_date') }}</span>
                    @endif

                </div>
            </div>

            <div id="status" style='display:block'></div>

            <div id='subject_head' style='display:block'>
                <div class="table-responsive">


                <table class="table table-bordered text-center table-striped table-hover mt-3">
                    <thead>
                        <tr class='text-uppercase '>
                            <th colspan='4'>Assignment schedule Subject Details</th>
                        </tr>
                        <thead class='bg-primary' id='hidehead'>
                            <tr>
                                <th>Subject Tittle</th>
                                <th>Subject code</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                    <tbody id="tabledata">
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($assignmentSchedule->newsubject as $id => $subjectA)
                            <tr id="tr_{{ $i }}" data-subject-id="{{ $subjectA['id'] ?? '' }}"
                                class="{{ $subjectA['date'] == null ? '' : '' }} ">
                                <td style='display:none'>

                                    <div class="input-group  div d-flex flex-column" id="div_{{ $i }}">
                                        <div class="mb-1" id="error-message_{{ $i }}"></div>
                                        <div>
                                            <input type="hidden"
                                                class="form-control  date-field {{ $subjectA['date'] != null ? 'dates' : '' }} "
                                                @if ($subjectA['date'] == null) disabled @endif
                                                id="date_{{ $id }}" name="subject_dates[]"
                                                value=" subject_id_{{ $id ?? '' }}"
                                                data-subject-id="{{ $subjectA['id'] ?? '' }}">
                                        </div>
                                    </div>

                                </td>
                                <td class="subject-code">{{ $subjectA['name'] ?? '' }}</td>
                                <td>{{ $subjectA['subject_code'] ?? '' }}</td>
                                <td>

                                    <button type='button'
                                        class="btn add subject_add {{ $subjectA['date'] != null ? 'btn-danger' : 'btn-success' }} "
                                        id='subject_add' data-id="{{ $id }}">
                                        @if ($subjectA['date'] == null)
                                            Add
                                        @else
                                            Remove
                                        @endif
                                    </button>
                                </td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

                <div class="hidden-menu mt-2" style='display:none'>
                    <div class="col-12 ">
                        <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                            <label class='d-block' for="extraSubject">Add Subject</label>
                            <select class="form-control select2" name="extraSubject" id="extraSubject"
                                onchange="dropdownSub(event)">
                                <option value="">Please Select</option>
                                @foreach ($Subjects as $entry)
                                    <option value="{{ $entry->id }}">
                                        {{ $entry->name }}({{ $entry->subject_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div style="">
                        <button class="btn bg-success  mb-2" id="addSubject" onclick="addSubject()">Add Subject </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center col-12 m-2 ">
            <button class="btn btn-danger float-" style='display:block' id='submit' onclick="submit()" type="button">{{ trans('global.save') }} </button>
            <button type="button" style='display:none;' id="waiting" value="" class="btn btn-primary">Loading...</button>
        </div>

        <input type="hidden" name="examName" id="examName" value="{{ $assignmentSchedule->exam_name ?? '' }}">
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

            $(document).on("click", "#subject_add", function() {
                var $button = $(this);
                var id = $button.data("id");
                var dateId = '#date_' + id;
                var dateElement = $(dateId);

                // Toggle the class and text
                if ($button.hasClass('btn-success')) {
                    dateElement.addClass('dates');
                    $button.text("Remove");
                    $button.removeClass('btn-success').addClass('btn-danger');
                } else {
                    dateElement.removeClass('dates');
                    $button.text("Add");
                    $button.removeClass('btn-danger').addClass('btn-success');
                }
            });



            const $year = $("#year");
            const semesterSelect = $("#semester");
            const $semester_type = $("#semester_type");

            let valuesAndText = getAllSelectValuesAndText(semesterSelect);

            if ($year.val() === "") {
                semesterSelect.html('<option value="">Please select a year first</option>');
            }

            $year.on("change", function() {
                const year = this.value;
                getSemester();
            });

            $semester_type.on("change", function() {
                const semester_type = this.value;
                getSemester();
            });


            function getSemester() {
                const year = $year.val();
                const semester_type = $semester_type.val();

                if (semester_type !== "" && year !== "") {
                    let start = 0;
                    let end = 0;

                    if (year == 1 && semester_type == 'ODD') {
                        start = 0;
                        end = 0;
                    } else if (year == 1 && semester_type == 'EVEN') {
                        start = 1;
                        end = 1;
                    } else if (year == 2 && semester_type == 'ODD') {
                        start = 2;
                        end = 2;
                    } else if (year == 2 && semester_type == 'EVEN') {
                        start = 3;
                        end = 3;
                    } else if (year == 3 && semester_type == 'ODD') {
                        start = 4;
                        end = 4;
                    } else if (year == 3 && semester_type == 'EVEN') {
                        start = 5;
                        end = 5;
                    } else if (year == 4 && semester_type == 'ODD') {
                        start = 6;
                        end = 6;
                    } else if (year == 4 && semester_type == 'EVEN') {
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
        $(document).ready(function() {
            const $academicYearSelect = $("#academic_year");
            const $semester_type = $("#semester_type");
            const $courseSelect = $("#course_id");
            const $year = $("#year");
            const $semesterSelect = $("#semester");
            const $section = $("#sections");
            const $subject = $("#subject");
            const $tabledata = $("#tabledata");
            const $addSubject = $("#addSubject");
            const $sectioncheck = $("#sectioncheck");
            const $spinner = $("#spinner");
            const $checkboxs = $("#checkboxs");
            const $checked_co = $("#checked_co");
            const $submit = $("#submit");
            const $subject_head = $("#subject_head");
            const $error_message = $(".error_message");
            const $status = $("#status");
            const $due_date = $("#due_date");

            const $old_academic_year = $academicYearSelect.val();
            const $old_semester_type = $semester_type.val();
            const $old_course_id = $courseSelect.val();
            const $old_year = $year.val();
            const $old_semester = $semesterSelect.val();
            const $labExam = $("#labExam").val();
            const $old_section = $section.val();
            $semesterSelect.val();


            let semester = "";
            let course_id = "";
            let academicYear = "";
            let semester_type = "";
            let marktype = "";

            if ($courseSelect.val() === "") {
                let sec = '<option value="">Please select a Course first</option>';
                $section.html(sec);
            }

            $academicYearSelect.on("change", function() {
                academicYear = this.value;
                attemptAjaxCall();
            });
            $semester_type.on("change", function() {
                semester_type = this.value;
                attemptAjaxCall();
            });
            $courseSelect.on("change", function() {
                course_id = this.value;
                attemptAjaxCall();
            });
            $year.on("change", function() {
                attemptAjaxCall();
            });
            $semesterSelect.on("change", function() {
                semester = this.value;
                attemptAjaxCall();
            });


            $section.on("change", function() {
                const $section_get = $section.val();
                attemptAjaxCall();
            });


            function attemptAjaxCall() {
                newObj = []

                if ($academicYearSelect.val() !== "" && $semester_type.val() != '' && $courseSelect.val() !== "" &&
                    $year.val() != '' && $semesterSelect.val() !== "") {
                    $('#loading').show();
                    makeAjaxCall();
                } else {
                    $checkboxs.hide();
                    $submit.hide();
                    $tabledata.hide();
                    $subject_head.hide();
                    $error_message.show();
                    $error_message.html('Must be fill the first seven columns');

                }

            }

            function makeAjaxCall() {
                $subject_head.hide();
                $error_message.hide();
                $submit.hide();
                newObj = []
                $.ajax({
                    url: "{{ route('admin.assignment_edit.Subject_get') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'academic_year': $academicYearSelect.val(),
                        'semester_type': $semester_type.val(),
                        'course_id': $courseSelect.val(),
                        'year': $year.val(),
                        'semester': $semesterSelect.val(),
                        'section': $section.val(),
                        'select_section': 'select_section',
                        'old_academic_year': $old_academic_year,
                        'old_semester_type': $old_semester_type,
                        'old_semester': $old_semester,
                        'old_course_id': $old_course_id,
                        'old_year': $old_year,
                        'labExam': $labExam,
                        'old_section': $old_section,
                        'id': {{ $assignmentSchedule->id }},
                    },
                    success: function(response) {
                        if (response.status != 'already done') {
                            newObj = [];
                            if (response.status == 'fail') {
                                let sec = ' <option value="">No Available Section</option>';
                                let sub = ' <option value="">No Available Subject</option>';
                                $section.html(sec);
                                $subject.html(sub);
                                $checkboxs.hide();
                                $subject_head.show();
                                $tabledata.hide();
                                $checked_co.hide();

                            } else {
                                $checked_co.hide('');
                                $tabledata.html('');
                                if (response.get_section.length > 0) {

                                    let sec = '';
                                    if (response.get_section.length > 0) {
                                        let get_sections = response.get_section;
                                        sec += ' <option value="">Select Section</option>';
                                        for (let i = 0; i < get_sections.length; i++) {
                                            sec +=
                                                `<option style="color:blue;" ${get_sections[i][0].section == $section.val() ?? '' ? 'selected' : '' }  value="${get_sections[i][0].section }"> ${get_sections[i][0].section }</option>`;
                                        }
                                    } else {
                                        sec += ' <option value=""> Section Not Available</option>';

                                    }

                                    $section.html(sec);
                                    let sub;
                                    var get_subjects = response.subjects;
                                    if (get_subjects.length > 0) {
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
                                            var date = got_subjects[i].date ? got_subjects[i].date : '';
                                            var due_date = got_subjects[0].due_date ? got_subjects[0]
                                                .due_date : '';
                                            subj += " <tr data-subject-id='" + subjid + "'>";
                                            subj += "<td style='display:none'>";
                                            subj +=
                                                "<div class='input-group  div d-flex flex-column' id= 'div_" +
                                                i + "'>";
                                            subj += " <div class='mb-1' id='error-message_" + i +
                                                "'></div>";
                                            subj += " <div>";
                                            subj +=
                                                "<input class='form-control  date-field date dates' type='hidden' name='subject_dates[]' value='subject_id_" +
                                                i + "' data-subject-id='" + subjid + "' id='date_" + i +
                                                "'>";
                                            subj += "</div>";
                                            subj += "</div>";
                                            subj += "</td>";
                                            subj += "<td class='subject-name'>" + got_subjects[i].name +
                                                "</td>";
                                            subj += "<td class='subject-code'>" + got_subjects[i]
                                                .subject_code + "</td>";
                                            subj +=
                                                "<td><button type='button' class='btn btn-danger add subject_add ' data-id='" +
                                                i + "'>Remove</button></td>";
                                            subj += "</tr>";
                                        }
                                        // onclick='removeRow(this, \"" +
                                        // key + "\")'
                                        $tabledata.html(subj);
                                        $tabledata.show();
                                        $due_date.val(due_date);
                                        $submit.show();
                                        $subject_head.show();
                                        $("select").prop("disabled", false);
                                        $('#loading').hide();


                                    } else {
                                        $tabledata.html(
                                            '<th colspan="4" class="text-center"><strong >No Subject Available For This Department</strong></th>'
                                        );
                                        $tabledata.show();
                                        $('#submit').hide();
                                        $checkboxs.hide();
                                        $subject_head.show();
                                        $('#loading').hide();


                                    }
                                } else {

                                    $sectioncheck.html(
                                        '<p><strong class="text-center">No Section Available For This Department</strong></p>'
                                    );
                                    $tabledata.html(
                                        '<th colspan="4" class="text-center"><strong >NO Section and No Subject Available For This Selected Year Department</strong></th>'
                                    );
                                    $tabledata.show();
                                    $('#submit').hide();
                                    $checkboxs.hide();
                                    let section = ' <option value="">No Section Available</option>';
                                    $section.html(section);
                                    $subject_head.show();
                                    $("select").prop("disabled", false);
                                    $('#addSubject').hide();
                                    $('#loading').hide();

                                }
                            }
                        } else {
                            $tabledata.html(
                                '<th colspan="4" class="text-center"><strong > Assignment Already Created For Selected Section</strong></th>'
                            );
                            $tabledata.show();
                            $('#submit').hide();
                            $('#addSubject').hide();
                            $subject_head.show();
                            $("select").prop("disabled", false);
                            $('#loading').hide();

                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
                });
            }
        });


        // function removeRow(button, id) {
        //     button.closest('tr').remove();
        //     newObj = newObj.filter(function(obj) {
        //         return Object.keys(obj)[0] !== id;
        //     });
        // }

        var secTion = [];

        function section() {

        }

        function submit() {
            $('#loading').show();
            $('#submit').hide();
            $('#waiting').show();
            var dateClass = $('.dates');
            var isEmpty = false;
            var emptyIds = [];
            var elementIds = [
                'academic_year',
                'semester_type',
                'course_id',
                'year',
                'semester',
                'sections',
                'due_date',
            ];

            $.each(elementIds, function(index, elementId) {
                var value = $('#' + elementId).val();
                if (!value) {
                    isEmpty = true;
                    emptyIds.push(elementId);
                }
            });

            $.each(dateClass, function(index, element) {
                var $dateElement = $('#date_' + index);

                if ($dateElement.length === 0 || !$dateElement.is(':enabled')) {
                    return true;
                }

                var values = $dateElement.val();

                if (values === '') {
                    values = $dateElement.attr('id');
                    isEmpty = true;
                    emptyIds.push(element.id);
                }
            });





            // checksection.each(function(index) {
            //     if ($(this).is(":checked")) {
            //         var sectionsselection = $('#inlineCheckbox' + (index + 1)).val();
            //         secTion.push(sectionsselection);
            //     }
            // })

            var formData = {};

            var newObj = [];
            // $('.subject_add').each(function(index) {
            //     var subjectId = $(this).data('subject-id');
            //     var subjectEntry = {};
            //     subjectEntry[subjectId] = subjectId;
            //     newObj.push(subjectEntry);
            // });

            $('.dates').each(function(index) {
                var subjectId = $(this).data('subject-id');
                var date = $(this).val();
                var subjectEntry = {};
                subjectEntry[subjectId] = date;
                newObj.push(subjectEntry);
            });

            $("#hidden").val(JSON.stringify(formData));
            $("#hidden2").val(JSON.stringify(newObj));
            let length_check = newObj.length;
            if (length_check > 0) {
                if (!isEmpty) {
                    $.ajax({
                        url: "{{ route('admin.assignment_schedule_update', [$assignmentSchedule->id]) }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'academic_year': $('#academic_year').val(),
                            'semester_type': $('#semester_type').val(),
                            'course_id': $('#course_id').val(),
                            'year': $('#year').val(),
                            'semester': $('#semester').val(),
                            'modeofExam': $('#modeofExam').val(),
                            'due_date': $('#due_date').val(),
                            'subject': $('#hidden2').val(),
                            'sections': $('#sections').val(),
                            'examName': $('#examName').val(),
                        },
                        success: function(response) {
                            window.location.href = "{{ route('admin.assignment.index') }}";
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fill the following fields :',
                        text: ` ${emptyIds.join(', ')}`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    $('#submit').show();
                    $('#waiting').hide();
                    $('#loading').hide();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Choose  Atleast One  Subject :',
                    text: ` ${emptyIds.join(', ')}`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                $('#submit').show();
                $('#waiting').hide();
                $('#loading').hide();
            }

        }

        function addSubject() {
            $('.hidden-menu').slideToggle("slow");
            $("#extraSubject").select2();
        }

        function dropdownSub(e) {
            $("#extraSubject").select2();
            let selectedSubject = e.target.value;
            var controllerArray = @json($Subjects);

            var filteredElements = controllerArray.filter(function(item) {
                return (
                    (selectedSubject === '' || item.id == selectedSubject)
                );
            });
            const subjectExists = newObj.some(obj => obj[selectedSubject] === filteredElements[0].id);

            if (!subjectExists) {
                newObj.push({
                    [selectedSubject]: filteredElements[0].subject_code
                });
            }

            if ($('#tabledata').find('tr[data-subject-id="' + selectedSubject + '"]').length === 0) {
                var list = $('tr:last').index();

                let subj = "<tr data-subject-id='" + selectedSubject + "'>";
                subj += "<td style ='display:none;'>";
                subj += "<div class='input-group div d-flex flex-column' id='div_" + (list + 1) + "'>";
                subj += "<div class='mb-1' id='error-message_" + (list + 1) + "'></div>";
                subj += "<div>";
                subj +=
                    "<input class='form-control date-field date dates' type='hidden' name='hidden' value='subject_id_" + (
                        list + 1) + "'' data-subject-id='" +
                    selectedSubject + "' id='date_" + (list + 1) + "'>";
                subj += "</div>";
                subj += "</div>";
                subj += "</td>";

                subj += "<td class='subject-name'>" + filteredElements[0].name + "</td>";
                subj += "<td class='subject-code'>" + filteredElements[0].subject_code + "</td>";
                subj += "<td><button type='button' class='btn btn-danger add subject_add' >Remove</button></td>";
                // onclick='removeRow(this, \"" + selectedSubject +
                //     "\")'
                subj += "</tr>";
                $('#tabledata').append(subj);
                $("#extraSubject").val('');
                $("#extraSubject").select2();


            } else {
                $("#extraSubject").val('');
                $("#extraSubject").select2();


                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'The subject already added',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            }

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
