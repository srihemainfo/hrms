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
    <div class="form-group " style="padding-top: 20px;padding-left:20px;">
        <a class="btn btn-default" href="{{ route('admin.Exam-time-table.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header text-center text-bold">
            View Exam TimeTable
        </div>


        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label class="d-block" for="accademicYear">Academic Year</label>
                    <select class="form-control select2" name="accademicYear" id="accademicYear" disabled>
                        <option value="">Please Select</option>
                        @foreach ($AcademicYear as $id => $entry)
                            <option
                                value="{{ $id }}"{{ ($examTimetable->accademicYear ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="d-block" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <select class="form-control select2" name="course" id="course_id" disabled>
                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}"
                                {{ ($examTimetable->course ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="year" class="d-block">Year</label>
                    <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year"
                        id="year" disabled>
                        <option value="">Select Year</option>
                        <option value="01" {{ ($examTimetable->year ?? '') == '01' ? 'selected' : '' }}>First Year
                        </option>
                        <option value="02" {{ ($examTimetable->year ?? '') == '02' ? 'selected' : '' }}>Second Year
                        </option>
                        <option value="03" {{ ($examTimetable->year ?? '') == '03' ? 'selected' : '' }}>Third Year
                        </option>
                        <option value="04" {{ ($examTimetable->year ?? '') == '04' ? 'selected' : '' }}>Fourth Year
                        </option>

                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="semester" class="d-block">Semester</label>
                    <select class="form-control select2" name="semester" id="semester" disabled>
                        <option value="">Please Select</option>

                        @foreach ($semester as $id => $entry)
                            <option value="{{ $id }}"
                                {{ ($examTimetable->semester ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="section" class=" d-block">Sections</label>
                    <select class="form-control select2" name="section" id="section" disabled>
                        <option value="">Please Select</option>
                        <option value="A" {{ ($examTimetable->sections ?? '') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ ($examTimetable->sections ?? '') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ ($examTimetable->sections ?? '') == 'C' ? 'selected' : '' }}>C</option>
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="semesterType" class="d-block">Semester Type</label>
                    <select class="form-control select2 {{ $errors->has('semesterType') ? 'is-invalid' : '' }}"
                        name="semesterType" id="semesterType" disabled>
                        <option value="">Select Semester Type</option>
                        <option value="ODD" {{ ($examTimetable->semesterType ?? '') == 'ODD' ? 'selected' : '' }}>ODD
                        </option>
                        <option value="EVEN" {{ ($examTimetable->semesterType ?? '') == 'EVEN' ? 'selected' : '' }}>EVEN
                        </option>
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="d-block" for="examName">Title of the Exam</label>
                    <input class="form-control {{ $errors->has('examName') ? 'is-invalid' : '' }}" type="text"
                        name="examName" id="examName" value="{{ $examTimetable->exam_name }}" readonly>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="modeofExam" class="d-block">Mode of Exam</label>
                    <select class="form-control select2 {{ $errors->has('modeofExam') ? 'is-invalid' : '' }}"
                        name="modeofExam" id="modeofExam" disabled>
                        <option value="">Select Mode of Exam</option>
                        <option value="online" {{ ($examTimetable->modeofExam ?? '') == 'online' ? 'selected' : '' }}>
                            Online</option>
                        <option value="written" {{ ($examTimetable->modeofExam ?? '') == 'written' ? 'selected' : '' }}>
                            Written</option>
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label for="start_time" class="d-block">Start Time</label>
                    <div class="input-group ">
                        <input class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}" type="text"
                            name="start_time" id="start_time" value="{{ $examTimetable->start_time }}" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-clock"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="end_time" class="d-block">End Time</label>
                    <div class="input-group ">
                        <input class="form-control  {{ $errors->has('end_time') ? 'is-invalid' : '' }}" type="text"
                            name="end_time" id="end_time" value="{{ $examTimetable->end_time }}" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-clock"></i></span>
                        </div>
                    </div>
                </div>
            </div>

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
                                    {{ ($examTimetable->co_1 ?? '') != '' ? 'checked' : '' }}
                                    {{ ($examTimetable->co_1 ?? '') != '' ? 'disabled' : '' }} readonly>
                            </td>
                            <td>CO-1</td>
                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_1 }}"
                                    id="text1"{{ ($examTimetable->co_1 != '' ? $examTimetable->co_1 : '') != '' ? 'disabled' : '' }}
                                    readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check2" name="check[]" value="02"
                                    {{ ($examTimetable->co_2 ?? '') != '' ? 'checked' : '' }}
                                    {{ ($examTimetable->co_2 ?? '') != '' ? 'disabled' : '' }} readonly>

                            </td>
                            <td>CO-2</td>
                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_2 }}"
                                    id="text2"{{ ($examTimetable->co_2 != '' ? $examTimetable->co_2 : '') != '' ? 'disabled' : '' }}
                                    readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check3" name="check[]" value="03"
                                    {{ ($examTimetable->co_3 ?? '') != '' ? 'checked' : '' }}
                                    {{ ($examTimetable->co_3 ?? '') != '' ? 'disabled' : '' }} readonly>

                            </td>
                            <td>CO-3</td>
                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_3 }}"
                                    id="text3"{{ ($examTimetable->co_3 != '' ? $examTimetable->co_3 : '') != '' ? 'disabled' : '' }}
                                    readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check4" name="check[]"
                                    value="04"{{ ($examTimetable->co_4 ?? '') != '' ? 'checked' : '' }}
                                    {{ ($examTimetable->co_4 ?? '') != '' ? 'disabled' : '' }} readonly>

                            </td>
                            <td>CO-4</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_4 }}" id="text4"
                                    {{ ($examTimetable->co_4 != '' ? $examTimetable->co_4 : '') != '' ? 'disabled' : '' }}
                                    readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="checkBox" id="check5" name="check[]"
                                    value="05"{{ ($examTimetable->co_5 ?? '') != '' ? 'checked' : '' }}
                                    {{ ($examTimetable->co_5 ?? '') != '' ? 'disabled' : '' }} readonly>

                            </td>
                            <td>CO-5</td>

                            <td>
                                <input type="text" class="borderNone text-center" name="marks[]"
                                    placeholder="Enter Mark" value="{{ $examTimetable->co_5 }}"
                                    id="text5"{{ ($examTimetable->co_5 != '' ? $examTimetable->co_5 : '') != '' ? 'disabled' : '' }}
                                    readonly>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Subject Tittle</th>
                            <th>Subject code</th>
                        </tr>
                    </thead>
                    <tbody id="tabledata">

                        @foreach ($examTimetable->newsubject as $subjectA)
                            <tr>
                                <td>
                                    {{ $subjectA['date'] }}
                                </td>
                                <td class="subject-code">{{ $subjectA['name'] }}</td>
                                <td>{{ $subjectA['code'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr">
            $(document).ready(function() {
                $('#customSelect2').select2();
            });
        </script>

    </div>
@endsection
