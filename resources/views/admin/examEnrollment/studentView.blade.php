@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="card">
                <div class="card-header text-center">
                    Student Wise Exam Enrollment
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label class="required" for="regulation">Regulation</label>
                            <select class="form-control select2" name="regulation" id="regulation" required>
                                @foreach ($regulations as $id => $entry)
                                    @if ($regulation == $id)
                                        <option value="{{ $id }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="academic_year" class="required">Academic Year</label>
                            <select class="form-control select2" name="academic_year" id="academic_year">
                                @foreach ($ays as $id => $entry)
                                    @if ($ay == $id)
                                        <option value="{{ $id }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label class="required" for="exam_month">Exam Month </label>
                            <select class="form-control select2" name="exam_month" id="exam_month" required>
                                <option value="{{ $exam_month }}">{{ $exam_month }}</option>
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="exam_year" class="required">Exam Year</label>
                            <select class="form-control select2" name="exam_year" id="exam_year">
                                @foreach ($years as $id => $entry)
                                    @if ($exam_year == $entry)
                                        <option value="{{ $entry }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="course" class="required">Course</label>
                            <select class="form-control select2" name="course" id="course" required>
                                @foreach ($courses as $id => $entry)
                                    @if ($course == $id)
                                        <option value="{{ $id }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="batch" class="required">Batch</label>
                            <select class="form-control select2" name="batch" id="batch" required>
                                @foreach ($batches as $id => $entry)
                                    @if ($batch == $id)
                                        <option value="{{ $id }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="semester" class="required">Current Semester</label>
                            <select class="form-control select2" name="semester" id="semester" required>
                                <option value="{{ $semester }}">{{ $semester }}</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="required" for="">Register No</label>
                                <select name="user_name_id" id="user_name_id" class="form-control select2">
                                    @if (count($students) > 0)
                                        @foreach ($students as $student)
                                            <option value="{{ $student->user_name_id }}"
                                                {{ $user_name_id == $student->user_name_id ? 'selected' : '' }}>
                                                {{ $student->name }}
                                                ({{ $student->register_no }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-4 col-12">
                            <div class="form-group text-right">
                                <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                                    onclick="fetchDetails()">Fetch Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $totalCredits = 0;
            @endphp
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center" id="regularTableClassWise">
                        <thead>
                            <tr>
                                <th colspan="4"> Regular Subjects</th>
                            </tr>
                            <tr>
                                <th>S.No</th>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody id="regularTableBody">
                            @if (count($regularSubjects) > 0)
                                @foreach ($regularSubjects as $i => $regular)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $regular->subject->subject_code }}</td>
                                        <td>{{ $regular->subject->name }}</td>
                                        <td>{{ $regular->subject->credits }}</td>
                                    </tr>
                                    @php
                                        $credits = $regular->subject ? $regular->subject->credits : 0;
                                        $totalCredits += (float) $credits;
                                    @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">No Data Available...</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <table class="table table-bordered table-striped text-center mt-4" id="arrearTableClassWise">
                        <thead>
                            <tr>
                                <th colspan="5"> Arrear Subjects</th>
                            </tr>
                            <tr>
                                <th>S.No</th>
                                <th>Subject Semester</th>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody id="arrearTableBody">
                            @if (count($arrearSubjects) > 0)
                                @foreach ($arrearSubjects as $i => $arrear)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $arrear->subject_sem }}</td>
                                        <td>{{ $arrear->subject->subject_code }}</td>
                                        <td>{{ $arrear->subject->name }}</td>
                                        <td>{{ $arrear->subject->credits }}</td>
                                    </tr>
                                    @php
                                        $credits = $arrear->subject ? $arrear->subject->credits : 0;
                                        $totalCredits += (float) $credits;
                                    @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No Data Available...</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="text-right">Total Credits : </td>
                                <td>{{ $totalCredits }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function fetchDetails() {
            $("#regularTableBody").html(`<tr><td colspan="4">Loading...</td></tr>`);
            $("#arrearTableBody").html(`<tr><td colspan="5">Loading...</td></tr>`);
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Regulation Not Found', 'error');
                return false;
            } else if ($("#academic_year").val() == '') {
                Swal.fire('', 'AY Not Found', 'error');
                return false;
            } else if ($("#exam_month").val() == '') {
                Swal.fire('', 'Exam Month Not Found', 'error');
                return false;
            } else if ($("#exam_year").val() == '') {
                Swal.fire('', 'Exam Year Not Found', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Course Not Found', 'error');
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Batch Not Found', 'error');
                return false;
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Semester Not Found', 'error');
                return false;
            } else if ($("#user_name_id").val() == '') {
                Swal.fire('', 'Student Not Found', 'error');
                return false;
            } else {
                let regulation = $("#regulation").val();
                let ay = $("#academic_year").val();
                let exam_month = $("#exam_month").val();
                let exam_year = $("#exam_year").val();
                let course = $("#course").val();
                let batch = $("#batch").val();
                let semester = $("#semester").val();
                let user_name_id = $("#user_name_id").val();

                $.ajax({
                    url: "{{ route('admin.exam-enrollment.view-enrolled-each-student') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': regulation,
                        'ay': ay,
                        'exam_month': exam_month,
                        'exam_year': exam_year,
                        'course': course,
                        'batch': batch,
                        'semester': semester,
                        'user_name_id': user_name_id,
                    },
                    success: function(response) {

                        let status = response.status;
                        let data = response.data;
                        if (status == false) {
                            Swal.fire('', data, 'error');
                            $("#regularTableBody").html(`<tr><td colspan="4">No Data Available..</td></tr>`);
                            $("#arrearTableBody").html(`<tr><td colspan="5">No Data Available..</td></tr>`);
                        } else {
                            let regularSubjects = data.regularSubjects;
                            let arrearSubjects = data.arrearSubjects;
                            let regularLen = regularSubjects.length;
                            let arrearLen = arrearSubjects.length;
                            let regularData = '';
                            let arrearData = '';
                            let TotalCredits = 0;
                            if (regularLen > 0) {
                                for (let v = 0; v < regularLen; v++) {
                                    regularData +=
                                        `<tr><td>${v+1}</td><td>${regularSubjects[v].subject.subject_code}</td><td>${regularSubjects[v].subject.name}</td><td>${regularSubjects[v].subject.credits}</td></tr>`;
                                    TotalCredits += parseFloat(regularSubjects[v].subject.credits);
                                }
                                $("#regularTableBody").html(regularData);
                            } else {
                                $("#regularTableBody").html(
                                    `<tr><td colspan="4">No Data Available...</td></tr>`);
                            }
                            if (arrearLen > 0) {
                                for (let v = 0; v < arrearLen; v++) {
                                    arrearData +=
                                        `<tr><td>${v+1}</td><td>${arrearSubjects[v].subject_sem}</td><td>${arrearSubjects[v].subject.subject_code}</td><td>${arrearSubjects[v].subject.name}</td><td>${arrearSubjects[v].subject.credits}</td></tr>`;
                                    TotalCredits += parseFloat(arrearSubjects[v].subject.credits);
                                }
                                arrearData +=
                                    `<tr><td colspan="4" class="text-right">Total Credits</td><td>${TotalCredits}</td></tr>`;
                                $("#arrearTableBody").html(arrearData);
                            } else {
                                $("#arrearTableBody").html(
                                    `<tr><td colspan="5">No Data Available...</td></tr><tr><td colspan="4" class="text-right">Total Credits</td><td>${TotalCredits}</td></tr>`
                                    );
                            }
                        }
                    }
                })
            }
        }
    </script>
@endsection
