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
                            <select class="form-control select2" name="semester" id="semester" required
                                onchange="checkFields()">
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
                            <div class="form-group">
                                <label class="required" for="">Register No</label>
                                <select name="user_name_id" id="user_name_id" class="form-control select2"> </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-4 col-12">
                            <div class="form-group text-right">
                                <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                                    onclick="fetchDetails()">Fetch Details</button>
                                <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                                    onclick="reset()">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered text-center datatable datatable-studentWise" style="width:100%;">
                        <thead style="width:100%;">
                            <tr>
                                <th>S.No</th>
                                <th>Register No</th>
                                <th>Name</th>
                                <th>Total No of Regular Subjects</th>
                                <th>Total No of Arrear Subjects</th>
                                <th>Total No of Credits</th>
                                <th>Exam Enrollment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            callAjax();
        }

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
            dtButtons.splice(0, 7);
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'asc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-studentWise').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        }
        let theSelectors = $("#regulation,#academic_year,#exam_month,#exam_year,#course,#batch");
        if ($(theSelectors).on('change', function() {
                $("#tbody").html('<tr><td colspan="8">No Data Available...</td></tr>');
                $("#semester").val('');
                $("#semester").select2();
            }));

        function checkFields() {
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#academic_year").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#exam_month").val() == '') {
                Swal.fire('', 'Please Select Exam Month', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#exam_year").val() == '') {
                Swal.fire('', 'Please Select Exam Year', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else {
                $("#user_name_id").html(`<option value="">Loading...</option>`);
                let ay = $("#academic_year").val();
                let batch = $("#batch").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                $.ajax({
                    url: '{{ route('admin.exam-registrations.getStudents') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'ay': ay,
                        'batch': batch,
                        'course': course,
                        'semester': semester,
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        let enroll = response.enrolls;

                        if (status == true) {
                            let data_len = data.length;
                            let options =
                                `<option value=""> Select Register No</option><option value="${enroll}">All Students</option>`;
                            for (let i = 0; i < data_len; i++) {
                                for (let j = 0; j < data[i].length; j++) {
                                    options +=
                                        `<option value="${data[i][j].user_name_id}">${data[i][j].name} (${data[i][j].register_no})</option>`;
                                }
                            }
                            $("#user_name_id").html(options);
                        } else {
                            $("#user_name_id").html('');
                            Swal.fire('', data, 'error');
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
            $("#user_name_id").val($("#target option:first").val());

            $('select').select2();
            $("select").removeAttr("disabled");
        }

        function fetchDetails() {
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
            } else if ($("#user_name_id").val() == '') {
                Swal.fire('', 'Please Select Student', 'error');
                return false;
            } else {
                let regulation = $("#regulation").val();
                let ay = $("#academic_year").val();
                let exam_month = $("#exam_month").val();
                let exam_year = $("#exam_year").val();
                let batch = $("#batch").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let user_name_id = $("#user_name_id").val();
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
                $("#tbody").html(`<tr><td colspan="8">Loading...</td></tr>`);
                $.ajax({
                    url: '{{ route('admin.exam-enrollment.get-details-studentwise') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': regulation,
                        'ay': ay,
                        'batch': batch,
                        'course': course,
                        'semester': semester,
                        'user_name_id': user_name_id,
                        'exam_month': exam_month,
                        'exam_year': exam_year
                    },
                    success: function(response) {
                        let table = $('.datatable-studentWise').DataTable();
                        table.destroy();
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            let data_len = data.length;
                            let rows = '';
                            let enrollBtn;
                            let actionBtns;
                            if (data_len > 0) {
                                for (let v = 0; v < data_len; v++) {
                                    if (data[v].enrollment == true) {
                                        enrollBtn =
                                            `<button class="btn btn-xs btn-outline-success" style="cursor:default;" disabled>Enrolled</button>`;
                                        actionBtns =
                                            `<a class="btn btn-xs btn-success mr-1" target="_blank" href="{{ url('admin/exam-enrollment/view-enrolled-student') }}/${data[v].user_name_id}/${regulation}/${ay}/${batch}/${course}/${semester}/${exam_month}/${exam_year}">View</button>
                                                      <a class="btn btn-xs btn-info" target="_blank" href="{{ url('admin/exam-enrollment/edit-enrolled-student') }}/${data[v].user_name_id}/${regulation}/${ay}/${batch}/${course}/${semester}/${exam_month}/${exam_year}/${data[v].enroll}">Edit</button>
                                                      <a class="btn btn-xs btn-warning" target="_blank" style="display:none;" href="{{ url('admin/exam-enrollment/download-enrolled-student') }}/${data[v].user_name_id}/${regulation}/${ay}/${batch}/${course}/${semester}/${exam_month}/${exam_year}">Download</button>`;
                                    } else {
                                        actionBtns =
                                            `<a class="btn btn-xs btn-info" target="_blank" href="{{ url('admin/exam-enrollment/edit-enrolled-student') }}/${data[v].user_name_id}/${regulation}/${ay}/${batch}/${course}/${semester}/${exam_month}/${exam_year}/${data[v].enroll}">Edit</button>`;
                                        enrollBtn =
                                            `<button class="btn btn-xs btn-primary" onclick=enrollStudent(this,${data[v].user_name_id},${data[v].enroll},${data[v].credits},${data[v].credit_limit})>Enroll</button>`;
                                    }
                                    rows +=
                                        `<tr><td>${v+1}</td><td>${data[v].register_no}</td><td>${data[v].name}</td><td>${data[v].regularCount}</td><td>${data[v].arrearCount}</td><td>${data[v].credits}</td><td>${enrollBtn}</td><td>${actionBtns}</td></tr>`
                                }
                            } else {
                                rows = `<tr><td colspan="8">No Data Available...</td></tr>`;
                            }
                            $("#tbody").html(rows);
                            dtButtons.splice(0, 7);
                            let dtOverrideGlobals = {
                                buttons: dtButtons,
                                retrieve: true,
                                aaSorting: [],
                                orderCellsTop: true,
                                order: [
                                    [1, 'asc']
                                ],
                                pageLength: 10,
                            };
                            let table = $('.datatable-studentWise').DataTable(dtOverrideGlobals);
                            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                                $($.fn.dataTable.tables(true)).DataTable()
                                    .columns.adjust();
                            });

                        } else {
                            Swal.fire('', data, 'error');
                        }
                    }
                })
            }
        }

        function enrollStudent(element, user_name_id, enroll, credits, credit_limit) {

            if (parseFloat(credits) > parseInt(credit_limit)) {
                Swal.fire('', 'Total Credits Exceeds The Credit Limit', 'info');
            } else {
                let regulation = $("#regulation").val();
                let ay = $("#academic_year").val();
                let exam_month = $("#exam_month").val();
                let exam_year = $("#exam_year").val();
                let batch = $("#batch").val();
                let course = $("#course").val();
                let semester = $("#semester").val();

                $.ajax({
                    url: '{{ route('admin.exam-enrollment.enroll-studentWise') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': regulation,
                        'ay': ay,
                        'batch': batch,
                        'course': course,
                        'semester': semester,
                        'user_name_id': user_name_id,
                        'exam_month': exam_month,
                        'exam_year': exam_year,
                        'enroll': enroll
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            $(element).removeClass('btn-primary');
                            $(element).addClass('btn-outline-success');
                            $(element).html('Enrolled');
                            $(element).attr('disabled', true);
                            Swal.fire('', data, 'success');
                        } else {
                            Swal.fire('', data, 'error');
                        }
                    }
                })
            }
        }
    </script>
@endsection
