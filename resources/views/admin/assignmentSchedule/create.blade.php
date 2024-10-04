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

    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}
    <div class="form-group" style="padding-top: 20px;padding-left:20px;">
        <a class="btn btn-default" href="{{ route('admin.assignment.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header text-uppercase text-center">
            <p class='text-center'> <strong> {{ trans('global.create') }} Assignment Schedule </strong></p>
        </div>


        <div class="card-body">
            <div class="row">
                <div id="spinner" class='form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12' style="display: none;"><i
                        class="fas fa-spinner fa-spin"></i> Loading...</div>
                <div class="form-group col-xl-9 col-lg-9 col-md-9 col-sm-6 col-12 col-9 error_message text-center text-danger"
                    style="display: none;"></div>
            </div>
            <div class="row">

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">

                    <label class="required" for="academic_year">Academic Year</label>
                    <select class="form-control select2 {{ $errors->has('academic_year') ? 'is-invalid' : '' }}"
                        name="academic_year" id="academic_year" required>
                        <option value="">Please Select</option>

                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
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
                        <option value="ODD" {{ old('semester_type') == 'ODD' ? 'selected' : '' }}>ODD</option>
                        <option value="EVEN" {{ old('semester_type') == 'EVEN' ? 'selected' : '' }}>EVEN</option>

                    </select>
                    @if ($errors->has('semester_type'))
                        <span class="text-danger">{{ $errors->first('semester_type') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
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


                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label for="year" class="required">Year</label>
                    <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year"
                        id="year">
                        <option value="">Select Year</option>
                        <option value="01" {{ old('semester_type') == '01' ? 'selected' : '' }}>I</option>
                        <option value="02" {{ old('semester_type') == '02' ? 'selected' : '' }}>II</option>
                        <option value="03" {{ old('semester_type') == '03' ? 'selected' : '' }}>III</option>
                        <option value="04" {{ old('semester_type') == '04' ? 'selected' : '' }}>IV</option>

                    </select>
                    @if ($errors->has('year'))
                        <span class="text-danger">{{ $errors->first('year') }}</span>
                    @endif
                    <span class="help-block"> </span>
                </div>


                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
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



                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 " id="sectioncheck"
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

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label for="due_date" class="required">Due Date</label>
                    <input type="date" class="form-control  dateFormate" name="due_date" id="due_date" value="">
                    <input type="hidden" name="labExam" id="labExam" value="labExam">
                </div>
            </div>


            <div id="status" style='display:none'></div>
            <div id='subject_head' style='display:none'>
                <div class="table-responsive">
                    <table class="table table-bordered text-center table-striped table-hover mt-5">
                        <thead>
                            <tr class='text-uppercase bg-primary'>
                                <th colspan='4'>Assignment schedule Subject Details</th>
                            </tr>

                        </thead>

                        <thead>
                            <tr class='text-uppercase bg-secondary'>
                                <th>Subject Code</th>
                                <th>Subject Tittle</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody id="tabledata">

                        </tbody>
                    </table>
                </div>
                <div class="hidden-menu" style='display:none'>
                    <div class="col-12 ">
                        <div class="form-group col-6">
                            <label for="extraSubject">Add Subject</label>
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

                <div class="col-12" id='addSubjectBox'>
                    <div> <button class="btn bg-success" id="addSubject" onclick="addSubject()">Add Subject </button>
                    </div>
                </div>


                <div class="row mt-2 ml-2">


                    <button class="btn btn-danger" style='display:none' id='submit' onclick="submit()"
                        type="button">
                        {{ trans('global.save') }}
                    </button>

                    <button type="button" style='display:none;' id="waiting" value=""
                        class="btn btn-primary">Loading...</button>
                </div>

            </div>

        </div>





    </div>
    <input type="hidden" name="hidden" id="hidden" value="">
    <input type="hidden" name="hidden2" id="hidden2" value="">
    <input type="hidden" name="hidden3" id="hidden3" value="">
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(".dateFormate").on("change", function() {
            this.setAttribute(
                "data-date",
                moment(this.value, "YYYY-MM-DD").format("DD MM YYYY")
            );
        }).trigger("change");

        $(document).on("click", ".add", function() {

            var id = $(this).data("id");
            var element = $(this);
            var dateId = '#date_' + id;
            // var trId = '#tr_' + id;

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


            // Rest of your code...
        })
        $(document).ready(function() {



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
            const $semesterSelect = $("#semester");
            const $courseSelect = $("#course_id");
            const $academicYearSelect = $("#academic_year");
            const $subject = $("#subject");
            const $tabledata = $("#tabledata");
            const $addSubject = $("#addSubject");
            const $section = $("#section");
            const $sectioncheck = $("#sectioncheck");
            const $spinner = $("#spinner");
            const $semester_type = $("#semester_type");
            const $checkboxs = $("#checkboxs");
            const $checked_co = $("#checked_co");
            const $submit = $("#submit");
            const $subject_head = $("#subject_head");
            const $error_message = $(".error_message");
            const $status = $("#status");
            const $year = $("#year");
            const $labExam = $("#labExam").val();


            let semester = "";
            let course_id = "";
            let academicYear = "";
            let semester_type = "";

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
            $semester_type.on("change", function() {
                semester_type = this.value;
                attemptAjaxCall();
            });

            function attemptAjaxCall() {
                newObj = []
                if (semester !== "" && course_id !== "" && academicYear !== "" && semester_type !== '' &&
                    $semester_type != '') {
                    // $spinner.show();
                    makeAjaxCall();
                } else {
                    $checkboxs.hide();
                    $submit.hide();
                    $tabledata.hide();
                    $subject_head.hide();
                    $error_message.show();
                    $error_message.html('Must be fill the first five columns');

                }

            }

            function makeAjaxCall() {
                $('#loading').show();
                $subject_head.hide();
                $("select").prop("disabled", true);
                $error_message.hide();
                $submit.hide();
                newObj = []
                $.ajax({
                    url: "{{ route('admin.assignment_examTimetable.Subject_get') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course_id': course_id,
                        'semester': semester,
                        'academic_year': academicYear,
                        'semester_type': semester_type,
                        'year': $year.val(),
                    },
                    success: function(response) {
                        newObj = [];
                        if (response.status == 'already done') {

                            $tabledata.html(
                                '<th colspan="4" class="text-center"><strong >Already  Assignment Created for this department Class</strong></th>'
                            );
                            $tabledata.show();
                            $('#submit').hide();
                            $checkboxs.hide();
                            $subject_head.show();
                            $('#addSubjectBox').hide();
                            $("select").prop("disabled", false);
                            $('#loading').hide();


                        } else if (response.status == 'fail') {
                            let sec = ' <option value="">No Available Section</option>';
                            let sub = ' <option value="">No Available Subject</option>';
                            $section.html(sec);
                            $subject.html(sub);
                            $checkboxs.hide();
                            $subject_head.show();
                            $tabledata.hide();
                            $checked_co.hide();
                            $("select").prop("disabled", false);
                            $('#addSubjectBox').hide();
                            $('#loading').hide();


                        } else {
                            $checked_co.hide('');
                            $tabledata.html('');
                            if (response.get_section.length > 0) {
                                $status.text(' ');
                                let sec = `<strong class='required'>Section</strong> <br>`;
                                let get_sections = response.get_section;
                                for (let i = 0; i < get_sections.length; i++) {

                                    sec += `<div class='form-check form-check-inline'>
                                                <input class='form-check-input checksection' type='checkbox' id='inlineCheckbox${i+1}' name='checkboxes[]'  value='${get_sections[i][0].section}'>
                                                <label class='form-check-label' for='inlineCheckbox${i+1}'>${get_sections[i][0].section}</label>
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
                                        subj += "<tr data-subject-id='" + subjid + "'>";

                                        subj += "<td style='display:none;' >";
                                        subj +=
                                            "<div class='input-group  div d-flex flex-column' id= 'div_" +
                                            i + "'>";
                                        subj += " <div class='mb-1' id='error-message_" + i +
                                            "'></div>";
                                        subj += " <div>";
                                        subj +=
                                            "<input class='form-control  date-field date dates' type='hidden' name='date' value='subject_id_" +
                                            i + "' data-subject-id='" +
                                            subjid + "' id='date_" + i + "'>";
                                        subj += "</div>";
                                        subj += "</div>";
                                        subj += "</td>";
                                        subj += "<td class='subject-code'>" + got_subjects[i]
                                            .subject_code + "</td>";
                                        subj += "<td class='subject-name'>" + got_subjects[i].name +
                                            "</td>";
                                        subj +=
                                            "<td><button type='button' class='btn btn-danger add' data-id='" +
                                            i + "'  id='" + i + "' >Remove</button></td>";
                                        subj += "</tr>";
                                    }
                                    $tabledata.html(subj);
                                    $tabledata.show();
                                    $('.date-field').on('change', function() {
                                        var selectedDate = $(this).val();
                                        var selectedDate2 = $(this).attr('id');
                                        var errorMessageId = "#error-message_" + selectedDate2
                                            .split("_")[1]; // Extract the index
                                        var dateRemoved = false;
                                        $('.date-field').not(this).each(function() {
                                            if ($(this).val() === selectedDate && !
                                                dateRemoved) {
                                                var inputId = $(this).attr("id");

                                                $("#" + selectedDate2).val('');
                                                $(errorMessageId).html(
                                                    'Selected Date Removed').css(
                                                    'background', 'red').fadeIn();
                                                setTimeout(function() {
                                                    $(errorMessageId).fadeOut();
                                                }, 3000);

                                                dateRemoved = true;
                                            }
                                        });
                                    });
                                    $addSubject.show();
                                    $subject_head.show();

                                    $submit.show();
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
                                    $("select").prop("disabled", false);
                                }

                            } else {

                                $sectioncheck.html(
                                    '<p><strong class="text-center">No Section Available For This Department</strong></p>'
                                );
                                $tabledata.html(
                                    '<th colspan="4" class="text-center"><strong >NO Section and No Subject Available For This Selected Year Department</strong></th>'
                                );
                                $tabledata.show();
                                $addSubject.hide();
                                $('#submit').hide();
                                $checkboxs.hide();
                                $status.html('No Section');
                                $subject_head.show();
                                $("select").prop("disabled", false);
                                $('#loading').hide();



                            }
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
            var checksection = $('.checksection');
            var dateClass = $('.dates');
            var isEmpty = false;
            var emptyIds = [];
            var elementIds = [
                'academic_year',
                'semester_type',
                'course_id',
                'year',
                'semester',
                'due_date',
            ];

            var section_check = false;
            secTion = [];
            checksection.each(function(index, element) {

                if ($(this).is(":checked")) {
                    var sectionsselection = $('#inlineCheckbox' + (index + 1)).val();
                    secTion.push(sectionsselection);
                    section_check = true;
                }
            })

            if (!section_check) {
                isEmpty = true;
                emptyIds.push('section');
            }

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



            var formData = {};

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
            let length_check = newObj.length;
            if (length_check > 0) {
                if (!isEmpty) {
                    $.ajax({
                        url: "{{ route('admin.assignment_schedule.store') }}",
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
                            'due_date': $('#due_date').val(),
                            'subject': $('#hidden2').val(),
                            'sections': secTion,
                        },
                        success: function(response) {

                            if (response.error) {
                                $('#loading').hide();

                            } else {
                                window.location.href = "{{ route('admin.assignment.index') }}";
                                $('#loading').show();

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
                } else {
                    // alert();

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
                // var list = $('tr').length - 1;

                let subj = "<tr data-subject-id='" + selectedSubject + "'>";

                subj += "<td style='display:none'>";
                subj += "<div class='input-group div d-flex flex-column' id='div_ '>";
                subj += "<div class='mb-1' id='error-message_" + (list + 1) + "'></div>";
                subj += "<div>";
                subj +=
                    "<input class='form-control date-field date dates' type='hidden' name='date' value='subject_id_" + (
                        list + 1) + "' data-subject-id='" +
                    selectedSubject + "' id='date_" + (list + 1) + "'>";
                subj += "</div>";
                subj += "</div>";
                subj += "</td>";

                subj += "<td class='subject-code'>" + filteredElements[0].subject_code + "</td>";
                subj += "<td class='subject-name'>" + filteredElements[0].name + "</td>";
                subj += "<td><button type='button ' class='btn btn-danger add' data-id='" + (list + 1) + "' id='" + (list +
                    1) + "'>Remove</button></td>";

                subj += "</tr>";

                $('#tabledata').append(subj);
                $('.date-field').on('change', function() {
                    var selectedDate = $(this).val();
                    var selectedDate2 = $(this).attr('id');
                    var errorMessageId = "#error-message_" + selectedDate2
                        .split("_")[1]; // Extract the index
                    var dateRemoved = false;
                    $('.date-field').not(this).each(function() {
                        if ($(this).val() === selectedDate && !
                            dateRemoved) {
                            var inputId = $(this).attr("id");

                            $("#" + selectedDate2).val('');
                            $(errorMessageId).html(
                                'Selected Date Removed').css(
                                'background', 'red').fadeIn();
                            setTimeout(function() {
                                $(errorMessageId).fadeOut();
                            }, 3000);

                            dateRemoved = true;
                        }
                    });
                });
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
