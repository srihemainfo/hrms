@extends('layouts.teachingStaffHome')
@section('content')
    <style>
        #remarks {
            border: 1px solid #cfd1d8;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
            height: 33px;
            font-size: 1rem;
            background: #ffffff;
            color: #000000;
        }

        #studentModal {
            overflow-y: auto !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> Student Daily Attendance</h5>
        </div>
        <div class="card-body" style="max-width:100%;overflow-x:auto;">
            <table class="table table-bordered table-striped table-hover text-center" style="min-width:1000px;">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Class Name</th>
                        <th>Subject</th>
                        <th colspan="3">Action</th>

                    </tr>
                </thead>
                <tbody>
                    @if (count($got_subjects) > 0)
                        @foreach ($got_subjects as $id => $data)
                            <tr>
                                <td>{{ $id + 1 }}</td>
                                <td>
                                    {{ $data[1] }}
                                </td>
                                <td>

                                    {{ $data[4] }} ({{ $data[5] }})

                                </td>
                                <td>
                                    <form>
                                        <input type="hidden" name="staff" value="{{ $data[3] }}">
                                        <input type="hidden" name="subject" value="{{ $data[0] }}">
                                        <input type="hidden" name="class_name" value="{{ $data[1] }}">
                                        <input type="hidden" name="class" value="{{ $data[2] }}">
                                        <input type="hidden" name="subject_name" value="{{ $data[4] }}">
                                        <input type="hidden" name="subject_code" value="{{ $data[5] }}">
                                    </form>
                                    <a class="btn btn-xs btn-warning" onclick="open_modal(this)">
                                        Take Attendance
                                    </a>
                                </td>
                                <td>
                                    <form>
                                        <input type="hidden" name="staff" value="{{ $data[3] }}">
                                        <input type="hidden" name="subject" value="{{ $data[0] }}">
                                        <input type="hidden" name="class_name" value="{{ $data[1] }}">
                                        <input type="hidden" name="class" value="{{ $data[2] }}">
                                        <input type="hidden" name="subject_name" value="{{ $data[4] }}">
                                        <input type="hidden" name="subject_code" value="{{ $data[5] }}">
                                    </form>
                                    <a class="btn btn-xs btn-success" style="color: white;" onclick="open_modal(this)">
                                        View Attendance
                                    </a>
                                </td>
                                <td>
                                    <form>
                                        <input type="hidden" name="staff" value="{{ $data[3] }}">
                                        <input type="hidden" name="subject" value="{{ $data[0] }}">
                                        <input type="hidden" name="class_name" value="{{ $data[1] }}">
                                        <input type="hidden" name="class" value="{{ $data[2] }}">
                                        <input type="hidden" name="subject_name" value="{{ $data[4] }}">
                                        <input type="hidden" name="subject_code" value="{{ $data[5] }}">
                                    </form>
                                    <a class="btn btn-xs btn-info" style="color: white;" onclick="open_logModal(this)">
                                        Attendance Log
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No Date Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> Archived Attendance Logs</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4 form-group">
                    <label for="past_ay" class="required">Select Academic Year</label>
                    <select class="select2 form-control" name="past_ay" id="past_ay">
                        <option value="">Select AY</option>
                        @foreach ($getAys as $id => $ay)
                            <option value="{{ $ay }}">{{ $ay }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 form-group">
                    <label for="past_semester" class="required">Select Semester </label>
                    <select class="select2 form-control" name="past_semester" id="past_semester">
                        <option value="">Select Semester</option>
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
                <div class="col-4 form-group">
                    <button class="enroll_generate_bn" style="margin-top:32px;" onclick="getPastRecords()">Get
                        Details</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="text-center" id="pastRecords" style="max-width:100%;overflow-x:auto;">
                <table class="table table-bordered table-striped table-hover text-center" style="min-width:1000px;">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Class Name</th>
                            <th>Subject</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr>
                            <td colspan="4">No Data Available...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="studentModal" role="dialog">
        <div class="modal-dialog  modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="head_label"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="position:relative;">
                    <div class="loader" id="loader" style="display:none;top:7%;">
                        <div class="spinner-border text-primary"></div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-primary" id="list_header">

                        </div>
                        <div class="card-body">
                            <form id="selector_form">
                                <div class="row">
                                    <div class="col-12 row">
                                        <div class="col-1"></div>
                                        <div class="col-md-4 col-10" id="date_div">
                                            <label for=""> Date</label>
                                            <input type="text" name="date" id="date"
                                                class="form-control date" onfocusout="checkDate(this)">
                                        </div>
                                        <div class="col-1"></div>
                                        <div class="col-md-4 col-10" id="period_div">
                                            <label for="">Period</label>
                                            <select name="period" id="period" class="form-control select2"
                                                onchange="checkPeriod(this)">
                                            </select>
                                            <input type="hidden" name="selected_day" id="selected_day" value="">
                                        </div>
                                        <div class="col-2" id="lab_checker_div">
                                            <label for="">Apply Batch</label>
                                            <div style="padding-left:1rem;padding-top:0.4rem;">
                                                <input type="checkbox" name="checkbox" id="lab_checker" value=""
                                                    style="width:18px;height:18px;accent-color:red;"
                                                    onchange="labChecker(this)">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 m-auto text-center">
                                        <div id="batch_div">
                                            <label for="">Select Batch</label>
                                            <select name="batch" id="batch">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="data_div"></div>
                            <div style="text-align:right;" id="button_div">
                                <button type="button" class="btn btn-primary" onclick="get_students(this)">Get
                                    Students</button>
                            </div>
                            <div class="row ">
                                <div class="col-12 row">
                                    <div class="col-md-6 col-12" id="unit_div"></div>
                                    <div class="col-md-6 col-12" id="topic_div"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="stu_list_card" style="display:none;">
                        <div class="card-header bg-primary">
                            <div class="row text-center">
                                <div class="col-1">S.No</div>
                                <div class="col-4">Name</div>
                                <div class="col-2">Register No</div>
                                <div class="col-2">Present</div>
                                <div class="col-2">Absent</div>
                                <div class="col-1">OD</div>
                            </div>
                        </div>
                        <div class="card-body" id="stu_list">

                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="footer">
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="attendanceLogModal" role="dialog">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class='row'>
                        <div class="col-6">
                            <a href="" class="btn btn-primary" id="download_btn"> Download PDF </a>
                        </div>
                        <div class="col-6">
                            <a href="" class="btn btn-primary" id="download_btn2"> Download Excel </a>
                        </div>
                    </div>
                    <h5 class="modal-title text-primary" id="head_label"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="logModalBody">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let modal_array;
        let theOdCount = 0;
        let theTopic = '';

        function open_modal(element) {
            $("#lab_checker").prop('checked', false);
            $("#lab_checker_div").show();
            $("#period").html('')
            $("#period").removeAttr("disabled");
            $("#date").removeAttr("disabled");
            $("#lab_checker").removeAttr("disabled");
            $("#batch").removeAttr("disabled");
            $("#list_header").html('')
            $("#unit_div").html('')
            $("#topic_div").html('')
            $("#footer").html('')
            $("#unit_div").html('')
            $("#batch").html('')
            $("#batch_div").hide()
            $("#stu_list_card").hide()
            $("#stu_list").html('')
            $("#date").val('')
            $("#selected_day").val('')
            $("select").select2();
            let prev = $(element).prev();
            modal_array = $(prev).serializeArray();
            let label = $(element).html();
            let head_label = label.trim();
            $("#head_label").html(head_label)
            // console.log(head_label)
            $("#data_div").html(
                `<input type="hidden" id="the_class" name="the_class" value="${modal_array[3]['value']}">
                <input type="hidden" id="the_subject" name="the_subject" value="${modal_array[1]['value']}">`
            );
            let header =
                `<div class="row">
                    <div class="col-md-5 col-12">Class  :  ${modal_array[2]['value']}</div>
                    <div class="col-md-7 col-12">Subject  :   ${modal_array[4]['value']}  (${modal_array[5]['value']})</div>
                </div>`;
            var today = new Date();
            var year = today.getFullYear();
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var day = String(today.getDate()).padStart(2, '0');
            var formattedDate = year + '-' + month + '-' + day;
            let content;
            if (head_label == 'Take Attendance') {
                content = `<button type="button" class="btn btn-primary" onclick="get_students(this)">Get
                                    Students</button>`;
            } else {
                content = `<button type="button" class="btn btn-primary" onclick="view_students(this)">Get
                                    Students</button>`;
            }
            let theSubject = modal_array[1]['value'];
            let theClass = modal_array[3]['value'];
            let theDate = formattedDate;
            if (head_label == 'Take Attendance') {
                $.ajax({
                    url: '{{ route('admin.student-period-attendance.take_periods') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'subject': theSubject,
                        'class': theClass,
                        'date': theDate
                    },
                    success: function(response) {
                        // console.log(response)
                        let taken_periods = response.taken_periods;
                        let len = taken_periods.length;
                        let periods = ['1', '2', '3', '4', '5', '6', '7', '8'];
                        let final_periods = '';
                        for (let i = 0; i < len; i++) {

                            if (periods.includes(taken_periods[i])) {
                                var findIndex = periods.indexOf(taken_periods[i]);

                                if (findIndex > -1) {
                                    periods.splice(findIndex, 1);
                                }
                            }

                        }

                        for (let q = 0; q < periods.length; q++) {
                            final_periods += `<option>${periods[q]}</option>`;
                        }
                        $("#period").html(final_periods)
                        $('select').select2();
                    }
                })
            } else {
                $("#lab_checker_div").hide();
                $.ajax({
                    url: '{{ route('admin.student-period-attendance.taken_periods') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'subject': theSubject,
                        'class': theClass,
                        'date': theDate
                    },
                    success: function(response) {
                        // console.log(response)
                        let taken_periods = response.taken_periods;
                        let len = taken_periods.length;

                        let final_periods = '<option value="">Select Period</option>';

                        for (let q = 0; q < len; q++) {
                            final_periods += `<option>${taken_periods[q]}</option>`;
                        }
                        $("#period").html(final_periods)
                        $('select').select2();
                    }
                })
            }

            $("#button_div").html(content);
            $("#list_header").html(header)
            $("#date").val(formattedDate)
            $("#studentModal").modal();

        }

        function open_logModal(element) {
            let prev = $(element).prev();
            let data_array = $(prev).serializeArray();
            let theSubject = data_array[1]['value'];
            let theClass = data_array[3]['value'];
            $.ajax({
                url: '{{ route('admin.student-period-attendance.attendance_log') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'subject': theSubject,
                    'class': theClass,
                },
                success: function(response) {

                    let status = response.status;
                    if (status == true) {
                        let data = response.data;
                        let data_len = data.length;
                        let td = '';
                        let period;
                        let period_len;
                        let period_td = '';
                        var periods;
                        let therow = '';
                        let totalPeriods = 0;
                        let table =
                            `<table class="table table-bordered table-hover text-center table-striped"><thead><tr><th>S.No</th><th>Date</th><th>Period</th></tr></thead><tbody>`;
                        for (let q = 0; q < data_len; q++) {
                            td = data[q].actual_date;
                            period = data[q].period;
                            period_len = period.length;
                            for (let j = 0; j < period_len; j++) {

                                period_td += period[j].period + ', ';
                                totalPeriods++;
                            }
                            periods = period_td.slice(0, -2);
                            therow += `<tr><td>${q+1}</td><td>${td}</td><td>${periods}</td></tr>`;
                            period_td = '';
                        }
                        let tfooter = `<tr><td colspan="3"> Total Periods : ${totalPeriods}</td></tr>`;
                        table += `${therow}</tbody><tfoot>${tfooter}</tfoot></table>`;
                        let content = `<div class="card">
                                          <div class="card-header bg-primary">
                                              <div class="row"><div class="col-md-5 col-12">Class  :  ${response.class}</div><div class="col-md-7 col-12">Subject  :   ${response.subject}</div></div>
                                          </div>
                                          <div class="card-body">
                                           ${table}
                                          </div>
                                      </div>`;
                        $("#logModalBody").html(content);
                        //PDF Button-start
                        var anchorElement = $('#download_btn');
                        var Subject = theSubject;
                        var Class = theClass;
                        let link =
                            `{{ url('admin/Student_period_Attendance_report/pdf/${Subject}/${Class}') }}`;
                        anchorElement.attr('href', link);
                        anchorElement.attr('target', '_blank');
                        // PDF Button End

                        //excel button -start
                        var anchorElement2 = $('#download_btn2');
                        var Subject = theSubject;
                        var Class = theClass;
                        var excel = "excel";
                        let link2 =
                            `{{ url('admin/Student_period_Attendance_report/excel/${Subject}/${Class}/${excel}') }}`;
                        anchorElement2.attr('href', link2);
                        anchorElement2.attr('target', '_blank');
                        //excel button -End

                        $("#attendanceLogModal").modal();
                    } else {
                        Swal.fire('', 'Technical Error', 'error');

                    }

                }
            })
        }

        function labChecker(element) {

            let theDate = $("#date").val();
            let theSubject = $("#the_subject").val();
            let theClass = $("#the_class").val();

            if ($(element).prop("checked")) {

                if (theDate == '') {
                    $("#batch_div").hide()
                    Swal.fire('', 'Please Choose The Date', 'warning');
                    return false;
                } else {
                    $("#date").attr("disabled", true);
                    $("#period").attr("disabled", true);
                    $("#loader").show();
                    $.ajax({
                        url: '{{ route('admin.student-period-attendance.get_batch') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'subject': theSubject,
                            'class': theClass,
                        },
                        success: function(response) {
                            let batches = response.batch;
                            let len = batches.length;
                            let got_batch = '<option value="">Select Batch</option>';
                            for (let e = 0; e < len; e++) {
                                got_batch +=
                                    `<option value="${batches[e].batch}">${batches[e].batch}</option>`;
                            }
                            $("#batch").html(got_batch)
                            $("#batch").select2()
                        }
                    })

                    // $.ajax({
                    //     url: '{{ route('admin.student-period-attendance.get_period') }}',
                    //     type: 'POST',
                    //     headers: {
                    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //     },
                    //     data: {
                    //         'subject': theSubject,
                    //         'class': theClass,
                    //         'date': theDate
                    //     },
                    //     success: function(response) {
                    // console.log(response)
                    // let taken_periods = response.taken_periods;
                    // let len = taken_periods.length;
                    let final_periods = '<option value="">Select Period</option>';
                    for (let q = 1; q < 9; q++) {
                        final_periods += `<option>${q}</option>`;
                    }
                    $("#period").removeAttr("disabled");
                    $("#period").html(final_periods)
                    $('select').select2();
                    $("#loader").hide();
                    $("#date").removeAttr("disabled");
                    // }
                    // })

                    $("#batch_div").show()
                }

            } else {

                $("#batch_div").hide()
                $("#batch").html('')
                checkDate();
            }
        }

        function checkDate() {
            let givenDate = $("#date").val();
            $("#batch_div").hide()
            $("#batch").html('')
            $("#lab_checker").prop('checked', false);
            if (givenDate != '') {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const parsedGivenDate = new Date(givenDate);
                parsedGivenDate.setHours(0, 0, 0, 0);

                let label = $("#head_label").html();
                let head_label = label.trim();

                // Compare the dates
                if (parsedGivenDate > today) {
                    Swal.fire('', 'It\'s Not a Valid Date', 'error');
                    $("#date").val('');
                    return false;
                } else {
                    let theSubject = $("#the_subject").val();
                    let theClass = $("#the_class").val();
                    let theDate = givenDate;
                    $("#loader").show();
                    $("#date").prop("disabled", true);
                    if (head_label == 'Take Attendance') {
                        $.ajax({
                            url: '{{ route('admin.student-period-attendance.take_periods') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'subject': theSubject,
                                'class': theClass,
                                'date': theDate
                            },
                            success: function(response) {

                                let status = response.status;
                                if (status == true) {
                                    let taken_periods = response.taken_periods;
                                    let len = taken_periods.length;
                                    let periods = ['1', '2', '3', '4', '5', '6', '7', '8'];
                                    let final_periods = '<option value="">Select Period</option>';
                                    for (let i = 0; i < len; i++) {

                                        if (periods.includes(taken_periods[i])) {
                                            var findIndex = periods.indexOf(taken_periods[i]);

                                            if (findIndex > -1) {
                                                periods.splice(findIndex, 1);
                                            }
                                        }

                                    }

                                    for (let q = 0; q < periods.length; q++) {
                                        final_periods += `<option>${periods[q]}</option>`;
                                    }
                                    $("#period").html(final_periods)
                                    $('select').select2();
                                } else {
                                    $("#period").html('');
                                    $('select').select2();
                                    Swal.fire('', response.data, 'error');
                                }

                                $("#loader").hide();
                                $("#date").removeAttr("disabled");
                            }
                        })
                    } else {
                        $.ajax({
                            url: '{{ route('admin.student-period-attendance.taken_periods') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'subject': theSubject,
                                'class': theClass,
                                'date': theDate
                            },
                            success: function(response) {

                                let taken_periods = response.taken_periods;
                                let len = taken_periods.length;

                                let final_periods = '<option value="">Select Period</option>';

                                for (let q = 0; q < len; q++) {
                                    final_periods += `<option>${taken_periods[q]}</option>`;
                                }
                                $("#period").html(final_periods)
                                $('select').select2();
                                $("#loader").hide();
                                $("#date").removeAttr("disabled");
                            }
                        })
                    }
                }

            }
        }

        function checkPeriod(element) {
            if (element.value != '') {
                let label = $("#head_label").html();
                let head_label = label.trim();
                if (head_label != "Take Attendance") {
                    let theDate = $("#date").val();
                    let theSubject = $("#the_subject").val();
                    let theClass = $("#the_class").val();
                    let thePeriod = $(element).val();
                    $("#loader").show();
                    $("#date").prop("disabled", true);
                    $("#period").prop("disabled", true);
                    $("#lab_checker_div").hide();
                    $.ajax({
                        url: '{{ route('admin.student-period-attendance.check_period') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'subject': theSubject,
                            'class': theClass,
                            'date': theDate,
                            'period': thePeriod
                        },
                        success: function(response) {
                            // console.log(response)
                            let lab_hour = response.lab_hour;
                            let len = lab_hour.length;
                            let batch = '';
                            if (len > 0) {
                                for (let a = 0; a < len; a++) {
                                    batch += `<option>${lab_hour[a].lab_batch}</option>`;
                                }
                                $("#lab_checker").prop('checked', true);
                                $("#batch").html(batch);
                                $("#batch").prop("disabled", true);
                                $("#batch_div").show();
                                $("#lab_checker_div").show();
                            }
                            $("#lab_checker").prop("disabled", true);
                            $("#loader").hide();
                        }
                    })
                }

            }
        }

        function get_students(element) {

            let selected_date = $("#date").val();
            let selected_period = $("#period").val();

            if (selected_date == '') {
                Swal.fire('', 'Please Choose the Date', 'warning');
                return false;
            } else if (selected_period == '') {
                Swal.fire('', 'Please Choose the Period', 'warning');
                return false;
            } else {
                if ($("#lab_checker").prop("checked")) {
                    let checkBatch = $("#batch").val();
                    if (checkBatch == '' || checkBatch == null) {
                        Swal.fire('', 'Please Choose the Batch', 'warning');
                        return false;
                    }
                }
                let content = `<div class="text-primary text-center" style="margin-top:20px;">Loading...</div>`;

                $("#button_div").html(content);
                let selector_form = $('#selector_form').serializeArray();
                let form_len = selector_form.length;
                for (let i = 0; i < form_len; i++) {
                    modal_array.push(selector_form[i])
                }

                $("#stu_list").html('');
                $("#unit_div").html('');
                $("#topic_div").html('');
                $("#period").prop("disabled", true);
                $("#date").prop("disabled", true);
                $("#lab_checker").prop("disabled", true);
                $("#batch").prop("disabled", true);
                $.ajax({
                    url: '{{ route('admin.student-period-attendance.list') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'form_data': modal_array
                    },
                    success: function(response) {
                        // console.log(response)
                        if (typeof(response.error) == "undefined" && response.error == null) {
                            let unit_content =
                                `<label style="padding-right:0.5rem;" class="required">Unit</label>
                                 <input type="hidden" id="unit_class" value="${response.enroll_master_number}">
                                 <input type="hidden" id="unit_subject" value="${response.subject}">
                                 <input type="hidden" id="unit_staff" value="${response.get_staff}">
                                 <input type="hidden" id="unit_period" value="${response.period}">
                                 <input type="hidden" id="unit_date" value="${response.date}">
                                 <select class="form-control select2" style="width:100%;" name="unit" id="unit" onchange="check_unit()"><option value="">Select Unit</option>`;

                            let theUnit = response.theUnit;
                            if (response.units != null) {
                                theTopic = response.theTopic;
                                let takenUnits = response.takenUnits;
                                let units = response.units;
                                let units_len = units.length;
                                let unitSelected = '';
                                if (units_len > 0) {
                                    if (takenUnits.length > 0) {
                                        for (let v = 0; v < units_len; v++) {
                                            var unitNo = units[v]['unit_no'];
                                            var unitString = unitNo.toString();
                                            var inArray = jQuery.inArray(unitString, takenUnits);
                                            if (theUnit == units[v]['unit_no']) {
                                                unitSelected = 'selected';
                                            }
                                            if (inArray != -1) {

                                                unit_content +=
                                                    `<option value="${units[v]['unit_no']}" ${unitSelected}>&#10003;  ${units[v]['unit_no']} .  ${units[v]['unit']}</option>`;
                                            } else {

                                                unit_content +=
                                                    `<option value="${units[v]['unit_no']}" ${unitSelected}>${units[v]['unit_no']} .  ${units[v]['unit']}</option>`;
                                            }
                                            unitSelected = '';
                                        }
                                    } else {
                                        for (let v = 0; v < units_len; v++) {
                                            if (theUnit == units[v]['unit_no']) {
                                                unitSelected = 'selected';
                                            }
                                            unit_content +=
                                                `<option value="${units[v]['unit_no']}" ${unitSelected}>${units[v]['unit_no']} .  ${units[v]['unit']}</option>`;
                                            unitSelected = '';
                                        }
                                    }
                                }
                            }

                            unit_content +=
                                `<option ${theUnit == 'Laboratory' ? 'selected': ''}>Laboratory</option><option ${theUnit == 'Revision' ? 'selected': ''}>Revision</option><option ${theUnit == 'Others' ? 'selected': ''}>Others</option></select>`;

                            $('#selected_day').val(response.selected_day);
                            let class_name = response.class_name;
                            let subject = response.classSubject;
                            let period = response.period;

                            let header =
                                `<div class="row"><div class="col-md-5 col-12">Class  :  ${class_name}</div><div class="col-md-7 col-12">Subject  :  ${subject}</div></div>`;

                            if (response.students) {
                                let students = response.students;
                                let student_len = students.length;
                                let list = '';
                                theOdCount = 0;
                                if (student_len > 0) {
                                    list +=
                                        `<div class="row text-center pl-1 pr-1"><div class="col-7"></div><div class="col-2"><input type="radio" id="presentBox" name="mainBox"  style="width:18px;height:18px;accent-color:#159008;" onchange="attendanceAction('present',this)"></div><div class="col-2"><input type="radio" id="absentBox" name="mainBox" style="width:18px;height:18px;accent-color:#159008;" onchange="attendanceAction('absent',this)"></div><div class="col-1"><input type="radio" id="odBox" name="mainBox"  style="width:18px;height:18px;accent-color:#159008;" onchange="attendanceAction('od',this)"></div></div><hr style="margin:0;">`;

                                    let presentCount = 0;
                                    let absentCount = 0;
                                    let odCount = 0;
                                    for (let i = 0; i < student_len; i++) {

                                        let leave = students[i].leave;

                                        let balance = student_len - i;

                                        let leave_type = '';
                                        // console.log(leave_len)
                                        if (leave != '') {

                                            if (leave.leave_type == 'Leave') {

                                                leave_type = 'Leave Taken';

                                            } else if (leave.leave_type == 'Institute OD') {

                                                leave_type = 'Institute OD Taken';

                                            } else {

                                                if (leave == 'Present') {
                                                    leave_type = 'Present';
                                                } else if (leave == 'Absent') {
                                                    leave_type = 'Absent';
                                                } else {
                                                    leave_type = 'OD Taken';
                                                }
                                            }
                                        } else {
                                            leave_type = 'Present';
                                        }
                                        if (leave_type == 'Institute OD Taken') {
                                            let attendance;

                                            if (leave_type == 'Institute OD Taken') {
                                                attendance = 'Institute OD Taken';
                                                odCount++;
                                            }

                                            if (balance <= 1) {
                                                list +=
                                                    `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-5"><input type="hidden" name="attendance_${i}" value="${attendance}"><div style="width:80%;margin:auto;background-image: linear-gradient(to right,#fff,#f2f2f2,#e8e8e8,#cfcfcf,#e8e8e8,#f2f2f2,#fff);">${leave_type}</div></div></div></form>`;

                                            } else {
                                                list +=
                                                    `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-5"><input type="hidden" name="attendance_${i}" value="${attendance}"><div style="width:80%;margin:auto;background-image: linear-gradient(to right,#fff,#f2f2f2,#e8e8e8,#cfcfcf,#e8e8e8,#f2f2f2,#fff);">${leave_type}</div></div></div></form><hr style="margin:0;">`;
                                            }

                                        } else {

                                            if (leave_type == 'Present') {
                                                presentCount++;
                                                if (balance <= 1) {

                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present" checked onchange="doCount()"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent" onchange="doCount()"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken"  onchange="doCount()"></div></div></form>`;

                                                } else {
                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present" checked  onchange="doCount()"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent" onchange="doCount()"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken" onchange="doCount()"></div></div></form><hr style="margin:0;">`;
                                                }
                                            } else if (leave_type == 'Absent' || leave_type == 'Leave Taken') {
                                                absentCount++;
                                                if (balance <= 1) {

                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present" onchange="doCount()"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent" checked onchange="doCount()"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken" onchange="doCount()"></div></div></form>`;

                                                } else {
                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present" onchange="doCount()"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent" checked onchange="doCount()"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken" onchange="doCount()"></div></div></form><hr style="margin:0;">`;
                                                }
                                            } else if (leave_type == 'OD Taken') {
                                                odCount++;
                                                if (balance <= 1) {

                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present" onchange="doCount()"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent" onchange="doCount()"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken" checked onchange="doCount()"></div></div></form>`;

                                                } else {
                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present" onchange="doCount()"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent" onchange="doCount()"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken" checked onchange="doCount()"></div></div></form><hr style="margin:0;">`;
                                                }
                                            }
                                        }
                                    }
                                    list +=
                                        `<hr style="margin:0;"><div class="row text-center p-1" style="font-weight:bold;"><div class="col-7">Total</div><div class="col-2" id="presentCount">${presentCount}</div><div class="col-2" id="absentCount">${absentCount}</div><div class="col-1" id="odCount">${odCount}</div></div>`;

                                    $("#button_div").html('');
                                    $("#stu_list_card").show();
                                    $("#stu_list").html(list);
                                }
                                $("#footer").html(
                                    '<button type="button" id="save_btn" class="btn btn-success" onclick="save()">Save Attendance</button><span id="processing_div" class="text-success" style="font-weight:bold;display:none;">Processing...</span>'
                                );

                                $("#list_header").html(header);
                                $("#unit_div").html(unit_content);
                                $("#topic_div").html('');
                                $("#unit").select2();
                                $("#topic").select2();
                                if (theUnit != null) {
                                    check_unit();
                                }
                            }
                        } else {

                            $("#button_div").html(`<button type="button" class="btn btn-primary" onclick="get_students(this)">Get
                                Students</button>`);
                            Swal.fire('', response.error, 'error');
                            $("#period").removeAttr("disabled");
                            $("#date").removeAttr("disabled");
                            $("#lab_checker").removeAttr("disabled");
                            $("#batch").removeAttr("disabled");
                        }
                    }
                })
            }
        }

        function view_students(element) {

            let selected_date = $("#date").val();
            let selected_period = $("#period").val();
            $("#batch").prop("disabled", false);

            if ($("#lab_checker").prop("checked")) {
                $("#batch_div").show();

            } else {
                $("#batch_div").hide();
            }
            $("#lab_checker").prop("disabled", false);
            $("#period").prop("disabled", false);
            $("#date").prop("disabled", false);
            if (selected_date == '') {
                Swal.fire('', 'Please Choose the Date', 'warning');
                return false;
            } else if (selected_period == '') {
                Swal.fire('', 'Please Choose the Period', 'warning');
                return false;
            } else {
                let content = `<div class="text-primary text-center">Loading...</div>`;

                $("#button_div").html(content);
                let selector_form = $('#selector_form').serializeArray();
                let form_len = selector_form.length;
                for (let i = 0; i < form_len; i++) {
                    modal_array.push(selector_form[i])
                }

                $("#stu_list").html('');
                $("#unit_div").html('');
                $("#topic_div").html('');
                $("#period").prop("disabled", true);
                $("#date").prop("disabled", true);
                $("#batch").prop("disabled", true);
                $("#lab_checker").prop("disabled", true);
                $.ajax({
                    url: '{{ route('admin.student-period-attendance.got_list') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'form_data': modal_array
                    },
                    success: function(response) {
                        $("#button_div").html('');
                        if (typeof(response.error) == "undefined" && response.error == null) {
                            let attendanceRecord = response.attendance_record;
                            let check_unit;
                            if (attendanceRecord != '' && attendanceRecord != null) {
                                check_unit = isNaN(attendanceRecord.unit);
                            } else {
                                check_unit = null;
                            }

                            let topic_content = '';
                            let selected_content = '';
                            let unit_content =
                                `<label style="padding-right:0.5rem;" class="required">Unit</label>
                             <input type="hidden" id="unit_class" value="${response.enroll_master_number}">
                             <input type="hidden" id="unit_subject" value="${response.subject}">
                             <input type="hidden" id="unit_staff" value="${response.get_staff}">
                             <input type="hidden" id="unit_period" value="${response.period}">
                             <input type="hidden" id="unit_date" value="${response.date}">
                             <select class="form-control select2" style="width:100%;" name="unit" id="unit">`;

                            if (response.units != null) {
                                let units = response.units;
                                let units_len = units.length;
                                if (units_len > 0) {
                                    for (let v = 0; v < units_len; v++) {

                                        if (check_unit == false && attendanceRecord.unit == units[v][
                                                'unit_no'
                                            ]) {
                                            selected_content =
                                                `<option value="${units[v]['unit_no']}" selected>${units[v]['unit_no']} .  ${units[v]['unit']}</option>`;

                                            if (attendanceRecord.topic == units[v]['topic_no']) {
                                                topic_content =
                                                    `<label style="padding-right:0.5rem;" class="required">Topic</label><select class="form-control select2" style="width:100%;" name="topic" id="topic" value=""><option selected>${units[v]['topic_no']} . ${units[v]['topic']}</option></select>`;
                                            }
                                        }

                                    }
                                }

                                unit_content += selected_content;
                                if (check_unit == true && attendanceRecord.unit == 'Laboratory') {
                                    unit_content +=
                                        `<option selected>Laboratory</option>`;

                                } else if (check_unit == true && attendanceRecord.unit == 'Revision') {
                                    unit_content +=
                                        `<option selected>Revision</option>`;
                                } else if (check_unit == true && attendanceRecord.unit == 'Others') {
                                    unit_content +=
                                        `<option selected>Others</option>`;
                                }
                                if (check_unit == true) {
                                    topic_content =
                                        `<label style="padding-right:0.5rem;" class="required">Remarks</label><input type="text" style="width:100%;padding-left:1rem;" name="remarks" id="remarks" placeholder="Experiment Title / Remarks" value="${attendanceRecord.topic}" readonly>`;

                                }

                            }
                            let class_name = response.class_name;
                            let subject = response.classSubject;
                            let period = response.period;
                            let stage = response.stage;
                            let todayAttendance = response.todayAttendance;
                            let lab_batch = response.lab_batch;
                            let footer = '';

                            if (response.students) {
                                let students = response.students;
                                let student_len = students.length;
                                let list = '';

                                if (student_len > 0) {
                                    for (let i = 0; i < student_len; i++) {

                                        let leave = students[i].leave;

                                        let balance = student_len - i;

                                        let leave_type = '';

                                        // console.log(leave)
                                        if (leave != '') {

                                            if (leave.leave_type == 'Leave') {

                                                leave_type = 'Leave Taken';

                                            } else if (leave.leave_type == 'Institute OD') {

                                                leave_type = 'Institute OD Taken';

                                            } else {
                                                leave_type = leave;
                                            }
                                        }
                                        if (leave_type == 'OD Taken' || leave_type == 'Leave Taken' ||
                                            leave_type ==
                                            'Institute OD Taken') {
                                            let attendance;

                                            if (leave_type == 'Institute OD Taken') {
                                                attendance = 'Institute OD Taken';
                                            } else if (leave_type == 'OD Taken') {
                                                attendance = 'OD Taken';
                                            } else {
                                                attendance = 'Absent';
                                            }

                                            // if (balance <= 1) {
                                            //     list +=
                                            //         `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-5"><input type="hidden" name="attendance_${i}" value="${attendance}"><div style="width:80%;margin:auto;background-image: linear-gradient(to right,#fff,#f2f2f2,#e8e8e8,#cfcfcf,#e8e8e8,#f2f2f2,#fff);">${leave_type}</div></div></div></form>`;

                                            // } else {
                                            //     list +=
                                            //         `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-5"><input type="hidden" name="attendance_${i}" value="${attendance}"><div style="width:80%;margin:auto;background-image: linear-gradient(to right,#fff,#f2f2f2,#e8e8e8,#cfcfcf,#e8e8e8,#f2f2f2,#fff);">${leave_type}</div></div></div></form><hr style="margin:0;">`;
                                            // }
                                            if (balance <= 1) {

                                                list +=
                                                    `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken" checked></div></div></form>`;

                                            } else {
                                                list +=
                                                    `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken" checked></div></div></form><hr style="margin:0;">`;
                                            }
                                        } else {
                                            if (leave_type == 'Present') {
                                                if (balance <= 1) {

                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present" checked></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken"></div></div></form>`;

                                                } else {
                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present" checked></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent"></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken"></div></div></form><hr style="margin:0;">`;
                                                }
                                            } else if (leave_type == 'Absent') {
                                                if (balance <= 1) {

                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent" checked></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken"></div></div></form>`;

                                                } else {
                                                    list +=
                                                        `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-4">${students[i].name}</div><div class="col-2">${students[i].register_no} <input type="hidden" name="${students[i].user_name_id}" value="${students[i].user_name_id}"></div><div class="col-2"><input type="radio" class="attend_present" name="attendance_${i}" value="Present"></div><div class="col-2"><input type="radio" class="attend_absent" name="attendance_${i}" value="Absent" checked></div><div class="col-1"><input type="radio" class="attend_absent" name="attendance_${i}" value="OD Taken"></div></div></form><hr style="margin:0;">`;
                                                }
                                            }
                                        }
                                    }
                                }

                                $("#stu_list_card").show();
                                $("#stu_list").html(list);

                            }
                            if (lab_batch != null) {
                                $("#batch").html(`<option>${lab_batch}</option>`);
                                $("#lab_checker").prop("checked", true);
                                $("#lab_checker_div").show();
                                $("#batch_div").show();
                            }
                            if (stage == '1') {
                                if (todayAttendance == true) {
                                    footer =
                                        `<div class="row text-center" style="font-size:0.9rem;width:100%;"><div class="col-md-5 col-12"><span>Want To Retake Attendance ?</span><button type="button" class="enroll_generate_bn bg-warning" id="editAttendanceBtn" style="margin-left:0.4rem;" onclick="editAttendance()">Edit Attendance</button><span id="editAtProcess_div" style="display:none;font-weight:bold;" class="text-success">Processing...</span></div>
                                <div class="col-md-7 col-12"><span>Want To Delete This Period's Attendance ?</span><button type="button" class="enroll_generate_bn bg-danger" style="margin-left:0.4rem;" onclick="deleteRequest()">Delete Request</button></div></div>`;
                                } else {
                                    footer =
                                        `<div class="row text-center" style="font-size:0.9rem;width:100%;"><div class="col-md-5 col-12"><span>Want To Retake Attendance ?</span><button type="button" class="enroll_generate_bn bg-danger" style="margin-left:0.4rem;" onclick="editRequest()">Edit Request</button></div>
                                <div class="col-md-7 col-12"><span>Want To Delete This Period's Attendance ?</span><button type="button" class="enroll_generate_bn bg-danger" style="margin-left:0.4rem;" onclick="deleteRequest()">Delete Request</button></div></div>`;
                                }
                            } else if (stage == '0') {
                                footer =
                                    `<div class="row text-center" style="width:100%;"><div class="col-3"></div><div class="col-6 manual_bn bg-warning">Edit Request Sent</div><div class="col-3"></div></div>`;
                            } else if (stage == '55') {
                                footer =
                                    `<div class="row text-center" style="width:100%;"><div class="col-3"></div><div class="col-6 manual_bn bg-warning">Delete Request Sent</div><div class="col-3"></div></div>`;
                            } else if (stage == '100') {
                                footer =
                                    `<div class="row text-center" style="width:100%;"><div class="col-3"></div><div class="col-6 manual_bn bg-success">Edit Request Approved</div><div class="col-3"></div></div>`;
                            }

                            $("#footer").html(footer);

                            $("#unit_div").html(unit_content);
                            $("#topic_div").html(topic_content);
                            $("#studentModal").modal();
                            $("select").select2();
                        } else {
                            $("#button_div").html(`<button type="button" class="btn btn-primary" onclick="view_students(this)">Get
                                    Students</button>`);

                            Swal.fire('', response.error, 'error');
                        }
                    }
                })
            }
        }

        function editRequest() {
            let theDate = $("#date").val();
            let theSubject = $("#the_subject").val();
            let theClass = $("#the_class").val();
            let thePeriod = $("#period").val();

            Swal.fire({
                title: 'Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    $("#studentModal").modal('hide');
                    return $.ajax({
                        url: '{{ route('admin.student-period-attendance.edit_requesting') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'date': theDate,
                            'subject': theSubject,
                            'class': theClass,
                            'period': thePeriod,
                            'reason': reason
                        },
                        success: function(response) {

                            if (response.data == "success") {
                                Swal.fire('', "Successfully Request Sent!", "success");
                            } else if (response.data == 'failed') {
                                Swal.fire('', "Request Not Sent!", "error");
                            }
                            // location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            if (jqXHR.status == 422) {
                                var errors = jqXHR.responseJSON.errors;
                                var errorMessage = errors[Object.keys(errors)[0]][0];
                                Swal.fire('', errorMessage, "error");
                            } else {
                                Swal.fire('', 'Request failed with status: ' + jqXHR.status,
                                    "error");
                            }
                            // location.reload();
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            });

        }

        function deleteRequest() {
            let theDate = $("#date").val();
            let theSubject = $("#the_subject").val();
            let theClass = $("#the_class").val();
            let thePeriod = $("#period").val();

            Swal.fire({
                title: 'Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    $("#studentModal").modal('hide');
                    return $.ajax({
                        url: '{{ route('admin.student-period-attendance.delete_requesting') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'date': theDate,
                            'subject': theSubject,
                            'class': theClass,
                            'period': thePeriod,
                            'reason': reason
                        },
                        success: function(response) {
                            if (response.data == "success") {
                                Swal.fire('', "Successfully Request Sent!", "success");
                            } else if (response.data == 'failed') {
                                Swal.fire('', "Request Not Sent!", "error");
                            }
                            location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                var errors = jqXHR.responseJSON.errors;
                                var errorMessage = errors[Object.keys(errors)[0]][0];
                                Swal.fire('', errorMessage, "error");
                            } else {
                                Swal.fire('', 'Request failed with status: ' + jqXHR.status,
                                    "error");
                            }
                            location.reload();
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            });

        }

        function check_unit() {


            var selectedValue = $('#unit').val();

            if (selectedValue != '') {

                let status = isNaN(selectedValue);

                let unit_class = $("#unit_class").val();
                let unit_subject = $("#unit_subject").val();
                let unit_staff = $("#unit_staff").val();


                let topic_content;

                if (status) {
                    topic_content =
                        `<label style="padding-right:0.5rem;" class="required">Remarks</label><input type="text" style="width:100%;padding-left:1rem;" name="remarks" id="remarks" placeholder="Enter Experiment Title / Remarks">`;
                    $("#topic_div").html(topic_content);
                } else {

                    $.ajax({
                        url: '{{ route('admin.student-period-attendance.unitGet') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'class': unit_class,
                            'subject': unit_subject,
                            'staff': unit_staff,
                            'unit': selectedValue,
                        },
                        success: function(response) {
                            if (response.topic) {
                                topic_content =
                                    `<label style="padding-right:0.5rem;" class="required">Topic</label><select class="form-control select2" style="width:100%;" name="topic" id="topic"><option value="">Select Topic</option>`;
                                let topics = response.topic;
                                let topic_len = topics.length;
                                let takenTopics = response.takenTopics;
                                let topicSelected = '';
                                if (topic_len > 0) {
                                    if (takenTopics.length > 0) {
                                        for (let k = 0; k < topic_len; k++) {
                                            var topicNo = topics[k].topic_no;
                                            var topicString = topicNo.toString();
                                            var inArray = jQuery.inArray(topicString, takenTopics);
                                            if (theTopic == topics[k].topic_no) {
                                                topicSelected = 'selected';
                                            }
                                            if (inArray != -1) {
                                                topic_content +=
                                                    `<option value="${topics[k].topic_no}" ${topicSelected}>&#10003;  ${topics[k].topic_no} .  ${topics[k].topic}</option>`;
                                            } else {
                                                topic_content +=
                                                    `<option value="${topics[k].topic_no}" ${topicSelected}>${topics[k].topic_no} .  ${topics[k].topic}</option>`;
                                            }
                                            topicSelected = '';
                                        }
                                    } else {
                                        for (let k = 0; k < topic_len; k++) {
                                            if (theTopic == topics[k].topic_no) {
                                                topicSelected = 'selected';
                                            }
                                            topic_content +=
                                                `<option value="${topics[k].topic_no}" ${topicSelected}>${topics[k].topic_no} .  ${topics[k].topic}</option>`;
                                            topicSelected = '';
                                        }
                                    }
                                }
                                topic_content += `</select>`;

                                $("#topic_div").html(topic_content);
                            }
                            $("#topic").select2();
                        }
                    })


                }

                // console.log(status)

            }
        }

        function save() {

            let unit_class = $("#unit_class").val();
            let unit_subject = $("#unit_subject").val();
            let unit_staff = $("#unit_staff").val();
            let unit = $("#unit").val();
            let topic = '';
            let unit_period = $("#unit_period").val();
            let unit_date = $("#unit_date").val();
            let day = $("#selected_day").val();
            let lab_batch = $("#batch").val();


            if (unit == '') {

                Swal.fire('', 'Please Choose the Unit', "warning");
            } else {

                let status = isNaN(unit);

                if (status) {
                    topic = $("#remarks").val();
                    if (topic == '') {
                        Swal.fire('', 'Please Fill Remarks Box', "warning");

                    }
                } else {
                    topic = $("#topic").val();
                    if (topic == '') {
                        Swal.fire('', 'Please Choose the Topic Along with Unit', "warning");

                    }
                }

                if (topic != '') {
                    let form = $(".stu_form");
                    let form_len = form.length;
                    let form_data = [];

                    for (let k = 0; k < form_len; k++) {
                        let collect = $(form[k]).serializeArray();

                        let collect_len = collect.length;

                        if (collect_len < 2) {
                            Swal.fire('', 'Make Sure You Have Marked Attendance For All Students', "warning");

                            form_data = [];
                            break;
                        } else {
                            form_data.push(collect);
                        }

                    }

                    // console.log(form_data)

                    let form_data_len = form_data.length;
                    if (form_data_len > 0) {
                        $("#processing_div").show()
                        $("#save_btn").hide()
                        $.ajax({
                            url: '{{ route('admin.student-period-attendance.store') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'form_data': form_data,
                                'unit_class': unit_class,
                                'unit_subject': unit_subject,
                                'unit_staff': unit_staff,
                                'unit': unit,
                                'topic': topic,
                                'period': unit_period,
                                'date': unit_date,
                                'day': day,
                                'lab_batch': lab_batch
                            },
                            success: function(response) {
                                Swal.fire('', response.message, 'success');

                                $("#studentModal").modal('hide');
                                $("#unit").val('');
                                $("#topic").val('');
                                $("#remarks").val('');
                                $("#unit").select2();
                                $("#topic").select2();
                                $("#processing_div").hide()
                                location.reload();

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                if (jqXHR.status === 422) {
                                    var errors = jqXHR.responseJSON.errors;
                                    var errorMessage = errors[Object.keys(errors)[0]][0];
                                    Swal.fire('', errorMessage, 'error');
                                } else {
                                    Swal.fire('', 'Request failed with status: ' + jqXHR.status, 'error');
                                }
                                $("#processing_div").hide()
                                $("#save_btn").show()
                            }

                        })

                    }
                }

            }
        }

        function getPastRecords() {
            if ($("#past_ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#past_semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else {
                $("#tbody").html(`<tr><td colspan="5">Loading...</td></tr>`);
                $.ajax({
                    url: '{{ route('admin.student-period-attendance.get-past-records') }}',
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'past_ay': $("#past_ay").val(),
                        'past_semester': $("#past_semester").val()
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            let rows = '';
                            $.map(data, function(value, index) {

                                var details = `<form>
                                                  <input type="hidden" name="staff" value="${value[3]}">
                                                  <input type="hidden" name="subject" value="${value[0]}">
                                                  <input type="hidden" name="class_name" value="${value[1]}">
                                                  <input type="hidden" name="class" value="${value[2]}">
                                                  <input type="hidden" name="subject_name" value="${value[4]}">
                                                  <input type="hidden" name="subject_code" value="${value[5]}">
                                              </form>
                                              <a class="btn btn-xs btn-info" style="color: white;"
                                                  onclick="open_logModal(this)">
                                                  Attendance Log
                                              </a>`;

                                rows +=
                                    `<tr><td>${index + 1}</td><td>${value[1]}</td><td>${value[4]} (${value[5]})</td><td>${details}</td></tr>`;
                            });
                            $("#tbody").html(rows);
                        } else {
                            Swal.fire('', data, 'error');
                            $("#tbody").html(`<tr><td colspan="5">No Data Available...</td></tr>`);
                        }
                    }
                })
            }
        }

        function doCount() {
            let present_count = 0;
            let absent_count = 0;
            let od_count = theOdCount;

            $('.stu_form input[type="radio"]').each(function() {
                if ($(this).is(':checked')) {
                    switch ($(this).val()) {
                        case 'Present':
                            present_count++;
                            break;
                        case 'Absent':
                            absent_count++;
                            break;
                        case 'OD Taken':
                            od_count++;
                            break;
                        case 'Institute OD Taken':
                            od_count++;
                            break;
                        default:
                            break;
                    }
                }
            });

            $('#presentCount').html(present_count);
            $('#absentCount').html(absent_count);
            $('#odCount').html(od_count);
        }

        function attendanceAction(action, element) {
            $('.stu_form input[type="radio"]').prop('checked', false);
            if ($(element).is(':checked')) {
                if (action == 'present') {
                    $('.stu_form input[type="radio"]').each(function() {
                        if ($(this).val() == 'Present') {
                            $(this).prop('checked', true);
                        }
                    });
                } else if (action == 'absent') {
                    $('.stu_form input[type="radio"]').each(function() {
                        if ($(this).val() == 'Absent') {
                            $(this).prop('checked', true);
                        }
                    });
                } else if (action == 'od') {
                    $('.stu_form input[type="radio"]').each(function() {
                        if ($(this).val() == 'OD Taken') {
                            $(this).prop('checked', true);
                        }
                    });
                }
            }
            doCount();
        }

        function editAttendance() {
            let theDate = $("#date").val();
            let theSubject = $("#the_subject").val();
            let theClass = $("#the_class").val();
            let thePeriod = $("#period").val();

            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Really Want To Do Edit Attendance ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $("#editAtProcess_div").show()
                    $("#editAttendanceBtn").hide()
                    return $.ajax({
                        url: '{{ route('admin.student-period-attendance.edit_attendance') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'date': theDate,
                            'subject': theSubject,
                            'class': theClass,
                            'period': thePeriod
                        },
                        success: function(response) {

                            if (response.data == "success") {
                                Swal.fire('', "You Can Edit The Attendance Now !", "success");
                                $("#studentModal").modal('hide');
                            } else if (response.data == 'failed') {
                                Swal.fire('', "You Can't Edit The Attendance !", "error");
                                $("#editAttendanceBtn").show()
                            }
                            $("#editAtProcess_div").hide()
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            if (jqXHR.status == 422) {
                                var errors = jqXHR.responseJSON.errors;
                                var errorMessage = errors[Object.keys(errors)[0]][0];
                                Swal.fire('', errorMessage, "error");
                            } else {
                                Swal.fire('', 'Request failed with status: ' + jqXHR.status,
                                    "error");
                            }
                            $("#editAtProcess_div").hide()
                            $("#editAttendanceBtn").show()
                        }
                    });

                }
            });

        }
    </script>
@endsection
