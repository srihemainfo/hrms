@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card">
        <div class="card-header text-center">
            Class Wise Exam Enrollment
        </div>
        <div class="card-body">
            <div class="text-right" style="padding-bottom:10px;">
                <button class="enroll_generate_bn bg-success" onclick="classWiseEnroll()">Enroll Class</button>
            </div>
            <div class="card" id="classWiseIndex" style="display:none;">
                <div class="card-body" style="position:relative;">
                    <div class="row">
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label class="required" for="regulation">Regulation</label>
                            <select class="form-control select2" name="regulation" id="get_regulation" required>
                                <option value="">Select Regulation</option>
                                @foreach ($regulations as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="academic_year" class="required">Academic Year</label>
                            <select class="form-control select2" name="academic_year" id="get_academic_year">
                                <option value="">Select AY</option>
                                @foreach ($ays as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label class="required" for="exam_month">Exam Month </label>
                            <select class="form-control select2" name="exam_month" id="get_exam_month" required>
                                <option value="">Select Exam Month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="exam_year" class="required">Exam Year</label>
                            <select class="form-control select2 " name="exam_year" id="get_exam_year">
                                <option value="">Select Exam Year</option>
                                @foreach ($years as $id => $entry)
                                    <option value="{{ $entry }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="course" class="required">Course</label>
                            <select class="form-control select2" name="course" id="get_course" required>
                                <option value="">Select Course</option>
                                @foreach ($courses as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="batch" class="required">Batch</label>
                            <select class="form-control select2" name="batch" id="get_batch" required>
                                <option value="">Select Batch</option>
                                @foreach ($batches as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="semester" class="required">Current Semester</label>
                            <select class="form-control select2" name="semester" id="get_semester" required>
                                <option value="">Select Current Semester</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="form-group text-right">
                                <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                                    onclick="getData()">Submit</button>
                                <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                                    onclick="reset()">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="classWiseListIndex" style="display:none;">
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center datatable datatable-classWise"
                        style="width:100%;">
                        <thead style="width:100%;">
                            <tr>
                                <th>Batch</th>
                                <th>Couse</th>
                                <th>Current Semester</th>
                                <th>Total No of Regular Subjects</th>
                                <th>Total Students Enrolled for Regular Subjects</th>
                                <th>Total No of Arrear Subjects</th>
                                <th>Total Students Enrolled for Arrear Subjects</th>
                                <th>Enrolled Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="indexList">
                            @if (count($dataArray) > 0)
                                @foreach ($dataArray as $data)
                                    <tr>
                                        <td>{{ $data['batch'] }}</td>
                                        <td>{{ $data['course'] }}</td>
                                        <td>{{ $data['semester'] }}</td>
                                        <td>{{ $data['regularSubjectCount'] }}</td>
                                        <td>{{ $data['regularStudentCount'] }}</td>
                                        <td>{{ $data['arrearSubjectCount'] }}</td>
                                        <td>{{ $data['arrearStudentCount'] }}</td>
                                        <td>{{ $data['date'] }}</td>
                                        <td><a class="btn btn-xs btn-primary" target="_blank"
                                                href="{{ url('admin/exam-enrollment/classwise-show-subjects/' . $data['theRegulation'] . '/' . $data['theAy'] . '/' . $data['theBatch'] . '/' . $data['theCourse'] . '/' . $data['theSemester'] . '/' . $data['theExamMonth'] . '/' . $data['theExamYear']) }}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card" id="classWiseEnroller" style="display:none;">
                <div class="card-body" style="position:relative;">
                    <div class="row">

                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label class="required" for="regulation">Regulation</label>
                            <select class="form-control select2" name="regulation" id="regulation"
                                onchange="checkMasters()" required>
                                <option value="">Select Regulation</option>
                                @foreach ($regulations as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="academic_year" class="required">Academic Year</label>
                            <select class="form-control select2" name="academic_year" id="academic_year">
                                <option value="">Select AY</option>
                                @foreach ($ays as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label class="required" for="exam_month">Exam Month </label>
                            <select class="form-control select2" name="exam_month" id="exam_month" required>
                                <option value="">Select Exam Month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="exam_year" class="required">Exam Year</label>
                            <select class="form-control select2 " name="exam_year" id="exam_year">
                                <option value="">Select Exam Year</option>
                                @foreach ($years as $id => $entry)
                                    <option value="{{ $entry }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="course" class="required">Course</label>
                            <select class="form-control select2" name="course" id="course" required>
                                <option value="">Select Course</option>
                                @foreach ($courses as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="batch" class="required">Batch</label>
                            <select class="form-control select2" name="batch" id="batch" required>
                                <option value="">Select Batch</option>
                                @foreach ($batches as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="semester" class="required">Current Semester</label>
                            <select class="form-control select2" name="semester" id="semester" required>
                                <option value="">Select Current Semester</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="form-group text-right">
                                <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                                    onclick="fetchSubjects()">Fetch Subjects</button>
                                <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                                    onclick="reset()">Reset</button>
                            </div>
                        </div>

                    </div>
                    <div class="card text-primary text-center" id="loader"
                        style="position:absolute;width:15%;margin:auto;display:none;padding:5px;">

                        Processing...

                    </div>
                </div>
            </div>
            <div class="card" id="classWiseList" style="display:none;">
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center" id="regularTableClassWise"
                        style="width:100%;">
                        <thead>
                            <tr>
                                <th colspan="3"> Regular Subjects</th>
                            </tr>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Total No of Students</th>
                            </tr>
                        </thead>
                        <tbody id="regularTableBody">
                        </tbody>
                    </table>

                    <table class="table table-bordered table-striped text-center mt-4" id="arrearTableClassWise">
                        <thead>
                            <tr>
                                <th colspan="4"> Arrear Subjects</th>
                            </tr>
                            <tr>
                                <th>Subject Semester</th>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Total No of Students</th>
                            </tr>
                        </thead>
                        <tbody id="arrearTableBody">
                        </tbody>
                    </table>
                    <div class="mt-3 pl-2">
                        <input type="checkbox" id="subject_verification"
                            style="width:18px;height:18px;accent-color:rgb(1, 161, 1);">
                        <span>Verified all Subject Codes and Subject Titles</span>
                    </div>
                    <div class="mt-3 pl-2">
                        <input type="checkbox" id="no_limit"
                            style="width:18px;height:18px;accent-color:rgb(1, 161, 1);">
                        <span>Declaring that “No Credit Limit” applicable for this Batch and Current
                            Semester.</span>
                    </div>
                    <div class="mt-1 text-right">
                        <button class="enroll_generate_bn" id="submitBtn" onclick="submit()">Submit</button>
                        <div id="processer" class="text-right text-primary" style="display:none;">Processing...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            $("#classWiseEnroller").hide();
            $("#classWiseList").hide();
            $("#classWiseIndex").show();
            $("#classWiseListIndex").show();
            callAjax();
        }

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
            dtButtons.splice(0, 7);
            if ($.fn.DataTable.isDataTable('.datatable-classWise')) {
                $('.datatable-classWise').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-classWise').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        }

        function classWiseEnroll() {
            $("#classWiseIndex").toggle();
            $("#classWiseEnroller").toggle();
            $("#classWiseList").hide();
            $("#classWiseListIndex").hide();
            $("select").removeAttr("disabled");
        }

        function checkMasters() {
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else {

                $("button, input,select").prop("disabled", true);
                $("#loader").show();
                let regulation = $("#regulation").val();

                $.ajax({
                    url: "{{ route('admin.exam-enrollment.checkMasters') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': regulation
                    },
                    success: function(response) {

                        $("#loader").hide();
                        let status = response.status;
                        let data = response.data;
                        $("button, input,select").removeAttr("disabled");
                        if (status == false) {
                            $("#regulation").val($("#target option:first").val());
                            $("#regulation").select2();
                            Swal.fire('', data, 'error');
                        }
                    }
                })
            }
        }

        function fetchSubjects() {
            $("#processer").hide();
            $("#submitBtn").show();
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else if ($("#academic_year").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#exam_month").val() == '') {
                Swal.fire('', 'Please Select Exam Month', 'error');
                return false;
            } else if ($("#exam_year").val() == '') {
                Swal.fire('', 'Please Select Exam Year', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else {
                let regulation = $("#regulation").val();
                let ay = $("#academic_year").val();
                let exam_month = $("#exam_month").val();
                let exam_year = $("#exam_year").val();
                let course = $("#course").val();
                let batch = $("#batch").val();
                let semester = $("#semester").val();
                $("#loader").show();
                $("select").prop("disabled", true);
                $.ajax({
                    url: "{{ route('admin.exam-enrollment.get-subjects-classwise') }}",
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
                    },
                    success: function(response) {

                        $("#loader").hide();
                        let status = response.status;
                        let data = response.data;
                        if (status == false) {
                            Swal.fire('', data, 'error');
                            location.reload();
                        } else {
                            let regular = data['regular'];
                            let arrear = data['arrear'];
                            let enrolls = data['enrolls'];
                            let regularSubs = data['regularSubs'];
                            let arrearSubs = data['arrearSubs'];
                            let regular_len = regular.length;
                            let arrear_len = arrear.length;

                            let regularRows =
                                `<input type="hidden" id="enrolls" value="${enrolls}"><input type="hidden" id="regularSubs" value="${regularSubs}"><input type="hidden" id="arrearSubs" value="${arrearSubs}">`;
                            let arrearRows = '';
                            if (regular_len > 0) {

                                for (let i = 0; i < regular_len; i++) {
                                    regularRows +=
                                        `<tr><td>${regular[i].subject_code}</td><td>${regular[i].subject_name}</td><td>${regular[i].count}</td></tr>`;
                                }
                            } else {
                                regularRows = `<tr><td colspan="3">No Data Available...</td></tr>`;
                            }
                            if (arrear_len > 0) {

                                for (let i = 0; i < arrear_len; i++) {
                                    arrearRows +=
                                        `<tr><td>${arrear[i].subject_sem}</td><td>${arrear[i].subject_code}</td><td>${arrear[i].subject_name}</td><td>${arrear[i].count}</td></tr>`;
                                }
                            } else {
                                arrearRows = `<tr><td colspan="4">No Data Available...</td></tr>`;
                            }
                            $("#regularTableBody").html(regularRows);
                            $("#arrearTableBody").html(arrearRows);
                            $("#classWiseList").show();
                        }

                    }
                })
            }
        }

        function getData() {

            if ($("#get_regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else if ($("#get_academic_year").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#get_exam_month").val() == '') {
                Swal.fire('', 'Please Select Exam Month', 'error');
                return false;
            } else if ($("#get_exam_year").val() == '') {
                Swal.fire('', 'Please Select Exam Year', 'error');
                return false;
            } else if ($("#get_course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else if ($("#get_batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#get_semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else {
                let regulation = $("#get_regulation").val();
                let ay = $("#get_academic_year").val();
                let exam_month = $("#get_exam_month").val();
                let exam_year = $("#get_exam_year").val();
                let course = $("#get_course").val();
                let batch = $("#get_batch").val();
                let semester = $("#get_semester").val();
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
                $("select").prop("disabled", true);
                $("#classWiseListIndex").show();
                $("#indexList").html('<tr><td colspan="9">Loading...</td></tr>');

                $.ajax({
                    url: "{{ route('admin.exam-enrollment.get-details-classwise') }}",
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
                    },
                    success: function(response) {
                        let table = $('.datatable-classWise').DataTable();
                        table.destroy();
                        $("select").removeAttr("disabled");
                        let status = response.status;
                        let data = response.data;
                        let rows = '';
                        if (status == false) {
                            rows = `<tr><td colspan="9">No Data Available...</td></tr>`;
                            Swal.fire('', data, 'error');
                        } else {

                            rows =
                                `<tr><td>${data.batch}</td><td>${data.course}</td><td>${data.currentSem}</td><td>${data.regularSubjectCount}</td>
                                    <td>${data.regularStudentCount}</td><td>${data.arrearSubjectCount}</td><td>${data.arrearStudentCount}</td><td>${data.enrolled_date}</td>
                                    <td><a class="btn btn-xs btn-primary" target="_blank" href="{{ url('admin/exam-enrollment/classwise-show-subjects') }}/${data.theRegulation}/${data.theAy}/${data.theBatch}/${data.theCourse}/${data.theSemester}/${data.theExamMonth}/${data.theExamYear}">View</a></td>
                                </tr>`;
                        }
                        $("#indexList").html(rows);
                        dtButtons.splice(0, 7);
                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            retrieve: true,
                            aaSorting: [],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };
                        table = $('.datatable-classWise').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                    }
                })
            }
        }

        function getDetails(regulation, ay, batch, course, semester, exam_month, exam_year) {

            if (regulation == '') {
                Swal.fire('', 'Regulation Not Found', 'error');
                return false;
            } else if (ay == '') {
                Swal.fire('', 'AY Not Found', 'error');
                return false;
            } else if (exam_month == '') {
                Swal.fire('', 'Exam Month Not Found', 'error');
                return false;
            } else if (exam_year == '') {
                Swal.fire('', 'Exam Year Not Found', 'error');
                return false;
            } else if (course == '') {
                Swal.fire('', 'Course Not Found', 'error');
                return false;
            } else if (batch == '') {
                Swal.fire('', 'Batch Not Found', 'error');
                return false;
            } else if (semester == '') {
                Swal.fire('', 'Semester Not Found', 'error');
                return false;
            } else {

                $.ajax({
                    url: "{{ route('admin.exam-enrollment.get-subjects-classwise') }}",
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
                    },
                    success: function(response) {

                        $("#loader").hide();
                        let status = response.status;
                        let data = response.data;
                        if (status == false) {
                            Swal.fire('', data, 'error');
                            location.reload();
                        } else {
                            let regular = data['regular'];
                            let arrear = data['arrear'];
                            let enrolls = data['enrolls'];
                            let regularSubs = data['regularSubs'];
                            let arrearSubs = data['arrearSubs'];
                            let regular_len = regular.length;
                            let arrear_len = arrear.length;

                            let regularRows =
                                `<input type="hidden" id="enrolls" value="${enrolls}"><input type="hidden" id="regularSubs" value="${regularSubs}"><input type="hidden" id="arrearSubs" value="${arrearSubs}">`;
                            let arrearRows = '';
                            if (regular_len > 0) {

                                for (let i = 0; i < regular_len; i++) {
                                    regularRows +=
                                        `<tr><td>${regular[i].subject_code}</td><td>${regular[i].subject_name}</td><td>${regular[i].count}</td></tr>`;
                                }
                            } else {
                                regularRows = `<tr><td colspan="3">No Data Available...</td></tr>`;
                            }
                            if (arrear_len > 0) {

                                for (let i = 0; i < arrear_len; i++) {
                                    arrearRows +=
                                        `<tr><td>${arrear[i].subject_sem}</td><td>${arrear[i].subject_code}</td><td>${arrear[i].subject_name}</td><td>${arrear[i].count}</td></tr>`;
                                }
                            } else {
                                arrearRows = `<tr><td colspan="4">No Data Available...</td></tr>`;
                            }
                            $("#regularTableBody").html(regularRows);
                            $("#arrearTableBody").html(arrearRows);
                            $("#classWiseList").show();
                        }

                    }
                })
            }
        }

        function reset() {
            $("#regulation").val($("#target option:first").val());
            $("#academic_year").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#semester").val($("#target option:first").val());
            $("#exam_month").val($("#target option:first").val());
            $("#exam_year").val($("#target option:first").val());
            $("#batch").val($("#target option:first").val());

            $("#get_regulation").val($("#target option:first").val());
            $("#get_academic_year").val($("#target option:first").val());
            $("#get_course").val($("#target option:first").val());
            $("#get_semester").val($("#target option:first").val());
            $("#get_exam_month").val($("#target option:first").val());
            $("#get_exam_year").val($("#target option:first").val());
            $("#get_batch").val($("#target option:first").val());
            $('select').select2();
            $("select").removeAttr("disabled");
        }

        function submit() {
            if ($("#subject_verification").is(":checked")) {
                if ($("#no_limit").is(":checked")) {

                    let noLimit = false;
                    if ($("#no_limit").is(":checked")) {
                        noLimit = true;
                    }
                    if ($("#enrolls").val() == '') {
                        Swal.fire('', 'Classes Not Found', 'error');
                        return false;
                    }
                    let regulation = $("#regulation").val();
                    let ay = $("#academic_year").val();
                    let exam_month = $("#exam_month").val();
                    let exam_year = $("#exam_year").val();
                    let course = $("#course").val();
                    let batch = $("#batch").val();
                    let semester = $("#semester").val();

                    let enrolls = $("#enrolls").val();
                    let regularSubs = $("#regularSubs").val();
                    let arrearSubs = $("#arrearSubs").val();
                    $("#processer").show();
                    $("#submitBtn").hide();
                    $.ajax({
                        url: "{{ route('admin.exam-enrollment.enroll-classWise') }}",
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
                            'enrolls': enrolls,
                            'regularSubs': regularSubs,
                            'arrearSubs': arrearSubs,
                        },
                        success: function(response) {
                            $("#processer").hide();
                            $("#submitBtn").show();
                            let status = response.status;
                            let data = response.data;
                            if (status == true) {
                                reset();
                                Swal.fire('', data, 'success');
                                $("#regularTableBody").html('');
                                $("#arrearTableBody").html('');
                                $("#subject_verification").removeAttr('checked');
                                $("#no_limit").removeAttr('checked');
                                $("#classWiseList").hide();
                                $("select").removeAttr("disabled");
                            } else {
                                $("select").removeAttr("disabled");
                                Swal.fire('', data, 'error');
                            }
                        }
                    });
                } else {
                    Swal.fire('',
                        'Please ensure you have declaring that “No Credit Limit” applicable for this Batch and Current Semester.',
                        'info');
                    return false;
                }
            } else {
                Swal.fire('', 'Please Ensure You Have Checked All The Subject Codes and Subject Titles', 'info');
                return false;
            }
        }
    </script>
@endsection
