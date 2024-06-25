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
                            <input type="hidden" id="creditLimit" value="{{ $creditLimit }}">
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
                                <th colspan="5"> Regular Subjects</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>S.No</th>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody id="regularTableBody">
                            @if (count($regularSubjects) > 0 || count($subFromSubRegis) > 0)
                                @php
                                    $j = 0;
                                @endphp
                                @if (count($regularSubjects) > 0)
                                    @foreach ($regularSubjects as $i => $regular)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="checkBox"
                                                    style="width:18px;height:18px;accent-color:rgb(1, 161, 1);" checked
                                                    onchange="creditCalculator()">
                                                <input type="hidden"
                                                    value="{{ $regular->subject ? $regular->subject->credits : 0 }}">
                                                <input type="hidden" class="regularSub"
                                                    value="{{ $regular->subject_id }}">
                                            </td>
                                            <td>{{ $j + 1 }}</td>
                                            <td>{{ $regular->subject->subject_code }}</td>
                                            <td>{{ $regular->subject->name }}</td>
                                            <td>{{ $regular->subject->credits }}</td>
                                        </tr>
                                        @php
                                            $credits = $regular->subject ? $regular->subject->credits : 0;
                                            $totalCredits += (float) $credits;
                                            $j++;
                                        @endphp
                                    @endforeach
                                @endif
                                @if (count($subFromSubRegis) > 0)
                                    @php
                                        if (count($regularSubjects) <= 0) {
                                            $checker = 'checked';
                                        } else {
                                            $checker = '';
                                        }
                                    @endphp
                                    @foreach ($subFromSubRegis as $i => $regular)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="checkBox"
                                                    style="width:18px;height:18px;accent-color:rgb(1, 161, 1);"
                                                    onchange="creditCalculator()" {{ $checker }}>
                                                <input type="hidden"
                                                    value="{{ $regular->subjects ? $regular->subjects->credits : 0 }}">
                                                <input type="hidden" class="regularSub"
                                                    value="{{ $regular->subject_id }}">
                                            </td>
                                            <td>{{ $j + 1 }}</td>
                                            <td>{{ $regular->subjects->subject_code }}</td>
                                            <td>{{ $regular->subjects->name }}</td>
                                            <td>{{ $regular->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            if ($checker == 'checked') {
                                                $credits = $regular->subjects ? $regular->subjects->credits : 0;
                                                $totalCredits += (float) $credits;
                                            }
                                            $j++;
                                        @endphp
                                    @endforeach
                                @endif
                            @else
                                <tr>
                                    <td colspan="5">No Data Available...</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <table class="table table-bordered table-striped text-center mt-4" id="arrearTableClassWise">
                        <thead>
                            <tr>
                                <th colspan="6"> Arrear Subjects</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>S.No</th>
                                <th>Subject Semester</th>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody id="arrearTableBody">
                            @if (count($arrearSubjects) > 0 || count($balanceArrears) > 0)
                                @php
                                    $j = 0;
                                @endphp
                                @if (count($arrearSubjects) > 0)
                                    @foreach ($arrearSubjects as $i => $arrear)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="checkBox"
                                                    style="width:18px;height:18px;accent-color:rgb(1, 161, 1);" checked
                                                    onchange="creditCalculator()">
                                                <input type="hidden"
                                                    value="{{ $arrear->subject ? $arrear->subject->credits : 0 }}">
                                                <input type="hidden" class="arrearSub"
                                                    value="{{ $arrear->subject_id }}">
                                            </td>
                                            <td>{{ $j + 1 }}</td>
                                            <td>{{ $arrear->subject_sem }}</td>
                                            <td>{{ $arrear->subject->subject_code }}</td>
                                            <td>{{ $arrear->subject->name }}</td>
                                            <td>{{ $arrear->subject->credits }}</td>
                                        </tr>
                                        @php
                                            $credits = $arrear->subject ? $arrear->subject->credits : 0;

                                            $totalCredits += (float) $credits;
                                            $j++;
                                        @endphp
                                    @endforeach
                                @endif
                                @if (count($balanceArrears) > 0)
                                    @foreach ($balanceArrears as $i => $arrear)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="checkBox"
                                                    style="width:18px;height:18px;accent-color:rgb(1, 161, 1);"
                                                    onchange="creditCalculator()">
                                                <input type="hidden"
                                                    value="{{ $arrear->getSubject ? $arrear->getSubject->credits : 0 }}">
                                                <input type="hidden" class="arrearSub" value="{{ $arrear->subject }}">
                                            </td>
                                            <td>{{ $j + 1 }}</td>
                                            <td>{{ $arrear->semester }}</td>
                                            <td>{{ $arrear->getSubject->subject_code }}</td>
                                            <td>{{ $arrear->getSubject->name }}</td>
                                            <td>{{ $arrear->getSubject->credits }}</td>
                                        </tr>
                                        @php
                                            $j++;
                                        @endphp
                                    @endforeach
                                @endif
                            @else
                                <tr>
                                    <td colspan="6">No Data Available...</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot id="tfoot">
                            <tr>
                                <td colspan="5" class="text-right">Total Credits : </td>
                                <td id="totalCredits">{{ $totalCredits }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row mt-4">
                        <div class="col-md-6 col-12 text-center"><button class="enroll_generate_bn bg-primary"
                                onclick="addSubject()">Add Subjects</button></div>
                        <div class="col-md-6 col-12 text-center"><button class="enroll_generate_bn bg-success"
                                id="enrollBtn" onclick="confirmEnrollment()">Confirm Enrollment</button>
                            <span class="text-success" id="enrollProcesser"
                                style="display:none;"><b>Processing...</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addSubjectModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="position:relative;">
                    <div class="text-center" style="width:100%;"><b>Add Subjects</b></div>
                    <div style="position:absolute;right:20px;"><button type="button" class="close"
                            data-dismiss="modal">&times;</button></div>
                </div>
                <div class="modal-body">
                    <div class="row gutters text-center">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 form-group">
                            <label for="spl_sem" class="required">Subject Semester</label>
                            <select name="spl_sem" id="spl_sem" class="form-control select2"
                                onchange="getSplSubjects(this)">
                                <option value="">Select Subject Sem</option>
                                @if ($semester != '')
                                    @for ($i = 1; $i < (int) $semester; $i++)
                                        <option value="{{ $i }}">0{{ $i }}</option>
                                    @endfor
                                @endif
                            </select>
                            <span id="semester_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;">Please Select Semester</span>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 form-group">
                            <label for="exam_type" class="required">Exam Type</label>
                            <select name="exam_type" id="exam_type" class="form-control select2">
                                <option value="Regular">Regular</option>
                                <option value="Arrear">Arrear</option>
                            </select>
                            <span id="exam_type_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;">Please Select Exam Type</span>
                        </div>
                        <div class="col-12 form-group">
                            <label for="spl_subject" class="required">Subject</label>
                            <select name="spl_subject" id="spl_subject" class="form-control select2"
                                onchange="checkSubject()"></select>
                            <span id="spl_subject_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;">Please Select Subject</span>
                            <span id="spl_subject_span2" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;">The Subject Already Added</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="addSubBtn" style="display:none;" class="btn btn-success" onclick="addSplSubject()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function creditCalculator() {
            let credits = 0;
            let creditLimit = $("#creditLimit").val();
            $(".checkBox").each(function() {
                if ($(this).prop("checked")) {
                    credits += parseFloat($(this).next().val());
                }
            });
            let totalCredits = parseFloat(credits);
            $("#totalCredits").html(totalCredits);
            if (creditLimit != '') {
                if (totalCredits > creditLimit) {
                    let data = 'The Credit Limit is ' + creditLimit;
                    Swal.fire('The Credit Limit Exceeds', data, 'warning');
                }
            }
        }

        function fetchDetails() {
            $("#regularTableBody").html(`<tr><td colspan="5">Loading...</td></tr>`);
            $("#arrearTableBody").html(`<tr><td colspan="6">Loading...</td></tr>`);
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
                    url: "{{ route('admin.exam-enrollment.edit-enrolled-each-student') }}",
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
                            $("#regularTableBody").html(`<tr><td colspan="5">No Data Available..</td></tr>`);
                            $("#arrearTableBody").html(`<tr><td colspan="6">No Data Available..</td></tr>`);
                        } else {
                            let regularSubjects = data.regularSubjects;
                            let subFromSubRegis = data.subFromSubRegis;
                            let arrearSubjects = data.arrearSubjects;
                            let balanceArrears = data.balanceArrears;
                            let regularLen = regularSubjects.length;
                            let SubRegisLen = subFromSubRegis.length;
                            let arrearLen = arrearSubjects.length;
                            let balArrLen = balanceArrears.length;
                            let regularData = '';
                            let arrearData = '';
                            let totalCredits = 0;
                            let checker = 'checked';

                            // // ertyui

                            if (regularLen > 0 || SubRegisLen > 0) {
                                let x = 0;
                                if (regularLen > 0) {
                                    for (let v = 0; v < regularLen; v++) {
                                        regularData +=
                                            `<tr>
                                            <td>
                                                <input type="checkbox"
                                                    class="checkBox"
                                                    style="width:18px;height:18px;accent-color:rgb(1, 161, 1);" checked  onchange="creditCalculator()">
                                                <input type="hidden"
                                                    value="${regularSubjects[v].subject.credits}">
                                                <input type="hidden"  class="regularSub"
                                                    value="${regularSubjects[v].subject_id }">
                                            </td>
                                            <td>${x+1}</td><td>${regularSubjects[v].subject.subject_code}</td><td>${regularSubjects[v].subject.name}</td><td>${regularSubjects[v].subject.credits}</td></tr>`;
                                        totalCredits += parseFloat(regularSubjects[v].subject.credits);
                                        x++;
                                    }
                                }
                                if (SubRegisLen > 0) {
                                    if (regularLen > 0) {
                                        checker = '';
                                    }
                                    for (let v = 0; v < SubRegisLen; v++) {
                                        regularData +=
                                            `<tr>
                                                <td>
                                                    <input type="checkbox"
                                                        class="checkBox"
                                                        style="width:18px;height:18px;accent-color:rgb(1, 161, 1);" ${checker} onchange="creditCalculator()">
                                                    <input type="hidden"
                                                        value="${subFromSubRegis[v].subjects.credits}">
                                                    <input type="hidden" class="regularSub"
                                                        value="${subFromSubRegis[v].subject_id }">
                                                </td>
                                                <td>${x+1}</td><td>${subFromSubRegis[v].subjects.subject_code}</td><td>${subFromSubRegis[v].subjects.name}</td><td>${subFromSubRegis[v].subjects.credits}</td>
                                             </tr>`;
                                        if (checker == 'checked') {
                                            totalCredits += parseFloat(subFromSubRegis[v].subjects.credits);
                                        }
                                        x++;
                                    }
                                }
                                $("#regularTableBody").html(regularData);
                            } else {
                                $("#regularTableBody").html(
                                    `<tr><td colspan="5">No Data Available...</td></tr>`);
                            }

                            // ertrwetyutrew
                            if (balArrLen > 0 || arrearLen > 0) {
                                let w = 0;
                                if (arrearLen > 0) {
                                    for (let v = 0; v < arrearLen; v++) {
                                        arrearData +=
                                            `<tr>
                                            <td>
                                                <input type="checkbox"
                                                    class="checkBox"
                                                    style="width:18px;height:18px;accent-color:rgb(1, 161, 1);" checked  onchange="creditCalculator()">
                                                <input type="hidden"
                                                    value="${arrearSubjects[v].subject.credits}">
                                                <input type="hidden" class="arrearSub"
                                                    value="${arrearSubjects[v].subject_id }">
                                            </td>
                                            <td>${w+1}</td><td>${arrearSubjects[v].subject_sem}</td><td>${arrearSubjects[v].subject.subject_code}</td><td>${arrearSubjects[v].subject.name}</td><td>${arrearSubjects[v].subject.credits}</td></tr>`;
                                        totalCredits += parseFloat(arrearSubjects[v].subject.credits);
                                        w++;
                                    }

                                }
                                if (balArrLen > 0) {
                                    for (let v = 0; v < balArrLen; v++) {
                                        arrearData +=
                                            `<tr>
                                            <td>
                                                <input type="checkbox"
                                                    class="checkBox"
                                                    style="width:18px;height:18px;accent-color:rgb(1, 161, 1);" checked  onchange="creditCalculator()">
                                                <input type="hidden"
                                                    value="${balanceArrears[v].subject.credits}">
                                                <input type="hidden" class="arrearSub"
                                                    value="${balanceArrears[v].subject }">
                                            </td>
                                            <td>${w+1}</td><td>${balanceArrears[v].subject_sem}</td><td>${balanceArrears[v].getSubject.subject_code}</td><td>${balanceArrears[v].getSubject.name}</td><td>${balanceArrears[v].getSubject.credits}</td></tr>`;
                                        totalCredits += parseFloat(balanceArrears[v].getSubject.credits);
                                        w++;
                                    }
                                }

                                $("#arrearTableBody").html(arrearData);
                            } else {
                                $("#arrearTableBody").html(
                                    `<tr><td colspan="6">No Data Available...</td></tr>`);
                            }
                            $("#tfoot").html(
                                `<tr><td colspan="5" class="text-right">Total Credits</td><td id="totalCredits">${totalCredits}</td></tr>`
                            );
                        }
                    }
                })
            }
        }

        function addSubject() {
            $("select").select2()
            $("#addSubBtn").hide();
            $("#addSubjectModel").modal();
        }

        function getSplSubjects(element) {
            if ($(element).val() == '') {
                $("#semester_span").show();
                $("#spl_subject_span").hide();
                $("#exam_type_span").hide();
                $("#spl_subject").html('');
            } else {
                $("#semester_span").hide();
                $("#spl_subject_span").hide();
                $("#exam_type_span").hide();
                $("#spl_subject").html('<option>Loading...</option>');
                $.ajax({
                    url: "{{ route('admin.exam-enrollment.get-spl-subjects') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'sem': $(element).val(),
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == false) {
                            Swal.fire('', data, 'error');
                        } else {
                            let data_len = data.length;
                            let subjects = '';
                            if (data_len > 0) {
                                subjects +=
                                    `<option value="">Select Subject</option>`;
                                for (let b = 0; b < data_len; b++) {
                                    subjects +=
                                        `<option value="${data[b].id}|${data[b].name}|${data[b].subject_code}|${data[b].credits}">${data[b].name} (${data[b].subject_code})</option>`;
                                }
                            } else {
                                subjects += `<option value="">Subjects Not Found</option>`;
                            }
                            $("#spl_subject").html(subjects);
                        }
                    }
                });
            }
        }

        function checkSubject() {
            $("#spl_subject_span2").hide();
            let selectedSubs = $("#spl_subject").val();
            let subjectDetail = selectedSubs.split('|');

            let subject_id = subjectDetail[0];
            let subject_name = subjectDetail[1];
            let subject_code = subjectDetail[2];
            let credits = subjectDetail[3];
            let theSubjects = [];
            $(".checkBox").each(function() {
                creditElement = $(this).next();
                nextElement = $(creditElement).next().val();
                theSubjects.push(nextElement);
            });
            if (theSubjects.includes(subject_id)) {
                $("#spl_subject_span2").show();
                $("#spl_subject").val($("#target option:first").val())
                $("#spl_subject").select2();
                $("#addSubBtn").hide();
                return false;
            }else{
                $("#addSubBtn").show();
                return true;
            }
        }

        function addSplSubject() {
            $("#spl_subject_span2").hide();
            if ($("#spl_sem").val() == '') {
                $("#semester_span").show();
                $("#spl_subject_span").hide();
                $("#exam_type_span").hide();
                return false;
            } else if ($("#exam_type").val() == '') {
                $("#semester_span").hide();
                $("#spl_subject_span").hide();
                $("#exam_type_span").show();
                return false;
            } else if ($("#spl_subject").val() == '') {
                $("#semester_span").hide();
                $("#spl_subject_span").show();
                $("#exam_type_span").hide();
                return false;
            } else {
                $("#semester_span").hide();
                $("#spl_subject_span").hide();
                $("#exam_type_span").hide();
                let sem = $("#spl_sem").val();
                let subject = $("#spl_subject").val();
                let subjectDetail = subject.split('|');
                let subject_id = subjectDetail[0];
                let subject_name = subjectDetail[1];
                let subject_code = subjectDetail[2];
                let credits = subjectDetail[3];
                let len = '';
                let data = '';
                if ($("#exam_type").val() == 'Regular') {
                    len = $(".regularSub").length;
                    data =
                        `<tr>
                          <td>
                              <input type="checkbox"
                                  class="checkBox"
                                  style="width:18px;height:18px;accent-color:rgb(1, 161, 1);" checked  onchange="creditCalculator()">
                              <input type="hidden"
                                  value="${credits}">
                              <input type="hidden" class="regularSub"
                                  value="${subject_id }">
                          </td>
                          <td>${len + 1}</td><td>${subject_code}</td><td>${subject_name}</td><td>${credits}</td>
                        </tr>`;
                    if (len > 0) {
                        $("#regularTableBody").append(data);
                    } else {
                        $("#regularTableBody").html(data);
                    }

                } else {
                    len = $(".arrearSub").length;
                    data =
                        `<tr>
                          <td>
                              <input type="checkbox"
                                  class="checkBox"
                                  style="width:18px;height:18px;accent-color:rgb(1, 161, 1);" checked  onchange="creditCalculator()">
                              <input type="hidden"
                                  value="${credits}">
                              <input type="hidden" class="arrearSub"
                                  value="${subject_id }">
                          </td>
                          <td>${len + 1}</td><td>${sem}</td><td>${subject_code}</td><td>${subject_name}</td><td>${credits}</td>
                        </tr>`;
                    if (len > 0) {
                        $("#arrearTableBody").append(data);
                    } else {
                        $("#arrearTableBody").html(data);
                    }
                }
                $("#spl_sem").val($("#target option:first").val())
                $("#exam_type").html(`<option value="Regular">Regular</option><option value="Arrear">Arrear</option>`)
                $("#spl_subject").html('')
                $("#addSubjectModel").modal('hide');
                creditCalculator();
            }
        }

        function confirmEnrollment() {
            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Want To Do Enrollment ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    let regularSubjectIds = [];
                    let arrearSubjectIds = [];
                    let removeIds = [];
                    let action = false;
                    let creditElement = '';
                    let nextElement = '';
                    let nextElementVal = '';
                    let nextElementClass = '';
                    if ($("#creditLimit").val() != '') {
                        let creditLimit = $("#creditLimit").val();
                        let credits = 0;
                        $(".checkBox").each(function() {
                            if ($(this).prop("checked")) {
                                creditElement = $(this).next();
                                credits += parseFloat($(this).next().val());
                                nextElement = $(creditElement).next();
                                nextElementVal = $(creditElement).next().val();
                                nextElementClass = $(creditElement).next().attr('class');
                                if (nextElementClass == 'arrearSub') {
                                    arrearSubjectIds.push(nextElementVal);
                                } else if (nextElementClass == 'regularSub') {
                                    regularSubjectIds.push(nextElementVal);
                                } else {
                                    Swal.fire('', 'Subject Classification Not Found', 'error');
                                    return false;
                                }
                            } else {
                                creditElement = $(this).next();
                                nextElement = $(creditElement).next().val();
                                removeIds.push(nextElement);
                            }
                        });
                        let totalCredits = parseFloat(credits);
                        $("#totalCredits").html(totalCredits);
                        if (totalCredits > creditLimit) {
                            Swal.fire('The Credit Limit Exceeds', 'The Credit Limit is ' + creditLimit, 'warning');
                            return false;
                        } else {
                            action = true;
                        }
                    } else {
                        $(".checkBox").each(function() {
                            if ($(this).prop("checked")) {
                                creditElement = $(this).next();
                                nextElement = $(creditElement).next();
                                nextElementVal = $(creditElement).next().val();
                                nextElementClass = $(creditElement).next().attr('class');
                                if (nextElementClass == 'arrearSub') {
                                    arrearSubjectIds.push(nextElementVal);
                                } else if (nextElementClass == 'regularSub') {
                                    regularSubjectIds.push(nextElementVal);
                                } else {
                                    Swal.fire('', 'Subject Classification Not Found', 'error');
                                    return false;
                                }
                            } else {
                                creditElement = $(this).next();
                                nextElement = $(creditElement).next().val();
                                removeIds.push(nextElement);
                            }
                        });
                        action = true;
                    }
                    if (action == true) {
                        if (regularSubjectIds.length <= 0 && removeIds.length <= 0) {
                            Swal.fire('', 'Subjects Not Selected', 'info');
                            return false;
                        }
                        $("#enrollProcesser").show();
                        $("#enrollBtn").hide();
                        let regulation = $("#regulation").val();
                        let ay = $("#academic_year").val();
                        let exam_month = $("#exam_month").val();
                        let exam_year = $("#exam_year").val();
                        let batch = $("#batch").val();
                        let course = $("#course").val();
                        let semester = $("#semester").val();
                        let user_name_id = $("#user_name_id").val();
                        let formData = new FormData();
                        formData.append('regulation', regulation);
                        formData.append('ay', ay);
                        formData.append('batch', batch);
                        formData.append('course', course);
                        formData.append('semester', semester);
                        formData.append('user_name_id', user_name_id);
                        formData.append('exam_month', exam_month);
                        formData.append('exam_year', exam_year);

                        for (let h = 0; h < regularSubjectIds.length; h++) {
                            formData.append('regularSubjects[]', regularSubjectIds[h]);
                        }
                        for (let i = 0; i < arrearSubjectIds.length; i++) {
                            formData.append('arrearSubjects[]', arrearSubjectIds[i]);
                        }
                        for (let j = 0; j < removeIds.length; j++) {
                            formData.append('removeSubjects[]', removeIds[j]);
                        }

                        $.ajax({
                            url: '{{ route('admin.exam-enrollment.store-enrolled-student') }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                let status = response.status;
                                let data = response.data;
                                $("#enrollProcesser").hide();
                                $("#enrollBtn").show();
                                if (status == true) {
                                    Swal.fire('', data, 'success');
                                } else {
                                    Swal.fire('', data, 'error');
                                }
                            }
                        })
                    } else {
                        return false;
                    }
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Exam Enrollment Cancelled",
                        "error"
                    )
                }
            });
        }
    </script>
@endsection
