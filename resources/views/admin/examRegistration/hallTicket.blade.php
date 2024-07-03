@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Hall Ticket Preview Form
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Academic Year</label>
                        <select name="ay" id="ay" class="form-control select2">
                            <option value="">Select AY</option>
                            @foreach ($ays as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Month & Year Of Exam</label>
                        <select name="exam_month_year" id="exam_month_year" class="form-control select2">
                            <option value="">Select Month & Year Of Exam</option>
                            @foreach ($exam_month_year as $data)
                                <option value="{{ $data->exam_month . '|' . $data->exam_year }}">
                                    {{ $data->exam_month . ' ' . $data->exam_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Course</label>
                        <select name="course" id="course" class="form-control select2">
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Batch</label>
                        <select name="batch" id="batch" class="form-control select2">
                            <option value="">Select Batch</option>
                            @foreach ($batches as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Semester</label>
                        <select name="semester" id="semester" class="form-control select2" onchange="checkFields()">
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
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Register No</label>
                        <select name="user_name_id" id="user_name_id" class="form-control select2"> </select>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="preview()">Preview</button>
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card text-primary text-center" id="loading_div" style="display:none;">
        <div class="card-body">
            Loading...
        </div>
    </div>
    <div class="card" id="hollticket_div" style="display:none;position:relative;">
        <div class="card-body" style="z-index:2;">
            <div class="pb-2 row">
                <div class="col-md-3 col-12 text-left text-primary">
                    <span id="prev_btn" style="display:none;font-size:1.7rem;cursor:pointer;" data-id="" class="ml-2"
                        onclick="action(this)"><i class="fas fa-arrow-circle-left"></i></span>
                    <span id="next_btn" style="display:none;font-size:1.7rem;cursor:pointer;" data-id="" class="ml-4"
                        onclick="action(this)"><i class="fas fa-arrow-circle-right"></i></span>
                </div>
                <div class="col-md-9 col-12 text-right" id="download_div">

                </div>
            </div>
            <div>
                <table style="width:100%;border:1px solid black;border-collapse:collapse;font-size:0.9rem;">
                    <tr>
                        <td style="width:20%;border-right:1px solid black;">
                            <img src="{{ asset('adminlogo/school_menu_logo.png') }}" style="width:100%;height:90px;"
                                alt="Institute Logo">
                        </td>
                        <td class="text-center" style="width:60%;border-right:1px solid black;font-size:0.8rem;">
                            <div> <b style="font-size:1rem;"> Demo College Of Engineering & Technology </b></div>
                            <div> <b> (An AUTONOMOUS Institution, Affiliated to ANNA UNIVERSITY, Chennai.) </b></div>
                            <div> <b> KUTHAMBAKKAM, CHENNAI – 600124. </b></div>
                            <div> <b id="exam_month_div"> </b></div>
                            <div> <b> HALL TICKET (Page 1/1) </b></div>
                        </td>
                        <td rowspan="2" colspan="2" style="width:20%;">
                            <img src="" id="student_img"
                                style="margin-left:15%;margin-top:50px;width:70%;height:120px;" alt="Student image">

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top:1px solid black;border-right:1px solid black;">
                            <div class="row m-auto">
                                <div class="col-3" style="border-right:1px solid black;border-bottom:1px solid black;">
                                    Register Number</div>
                                <div class="col-5" style="border-right:1px solid black;border-bottom:1px solid black;">
                                    <b id="register_no_div"></b>
                                </div>
                                <div class="col-2" style="border-right:1px solid black;border-bottom:1px solid black;">
                                    Current Semester</div>
                                <div class="col-2" style="border-bottom:1px solid black;"><b id="sem_div"></b></div>
                            </div>
                            <div class="row m-auto">
                                <div class="col-3" style="border-right:1px solid black;border-bottom:1px solid black;">
                                    Name</div>
                                <div class="col-5" style="border-right:1px solid black;border-bottom:1px solid black;">
                                    <b id="name_div"></b>
                                </div>
                                <div class="col-2" style="border-right:1px solid black;border-bottom:1px solid black;">
                                    DOB</div>
                                <div class="col-2" style="border-bottom:1px solid black;"><b id="dob_div"></b></div>
                            </div>
                            <div class="row m-auto">
                                <div class="col-3" style="border-right:1px solid black;border-bottom:1px solid black;">
                                    Degree & Branch</div>
                                <div class="col-9" style="border-bottom:1px solid black;"><b id="degree_div">1234567</b>
                                </div>
                            </div>
                            <div class="row m-auto">
                                <div class="col-3" style="border-right:1px solid black;">Examination Centre</div>
                                <div class="col-9"><b>2117 : Demo College Of Engineering & Technology</b></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border:1px solid black;">
                            <div class="row m-auto">
                                <div class="col-6 row m-auto" style="border-right:1px solid black;">
                                    <div class="col-12 row">
                                        <div class="col-2 p-1 text-center"><b>Sem</b></div>
                                        <div class="col-3 p-1 text-center"><b>Subject Code</b></div>
                                        <div class="col-7 p-1"><b>Subject Name</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 row">
                                    <div class="col-12 row">
                                        <div class="col-2 p-1 text-center"><b>Sem</b></div>
                                        <div class="col-3 p-1 text-center"><b>Subject Code</b></div>
                                        <div class="col-7 p-1"><b>Subject Name</b></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border:1px solid black;" id="subject_div">
                            <div class="row m-auto">
                                <div class="col-6 row m-auto" id="left_div" style="border-right:1px solid black;">
                                    <div class="col-12 row">
                                        <div class="col-2 p-2 text-center" style="height:21.609px;"></div>
                                        <div class="col-3 p-2 text-center"></div>
                                        <div class="col-7 p-2"></div>
                                    </div>
                                </div>
                                <div class="col-6 row" id="right_div">
                                    <div class="col-12 p-2"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="font-size:0.8rem;">
                        <td colspan="4" style="border:1px solid black;padding-left:10px;">
                            <div style="padding:5px;">
                                <b>No of Subjects Registered: <span id="subjectsCount"></span> </b>
                                (Page 1/1 Hall Ticket contain per Page Maximum 30 Subjects only)
                            </div>
                        </td>
                    </tr>
                    <tr style="font-size:0.8rem;">
                        <td colspan="4" style="border:1px solid black;padding-left:10px;">
                            <div>
                                <div style="padding:4px;">NOTE : </div>

                                <div style="padding:4px;">
                                    1. Correction in the Name / Date of Birth and missing of Photograph or
                                    incorrect Photograph, if any is to be updated in the IMS Portal when it is
                                    opened for correction.
                                </div>
                                <div style="padding:4px;">2. Instructions printed overleaf are to be followed
                                    strictly.</div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <div style="padding-top:40px;">Signature of the Candidate</div>
                        </td>
                        <td class="text-center">
                            <div style="padding-top:40px;">Signature of the Principal with seal</div>
                        </td>
                        <td colspan="2" class="text-center">
                            <div style="padding-top:40px;">Controller of Examinations</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <img src="{{ asset('adminlogo/school_menu_logo.png') }}"
            style="margin-left: 35%;width: 30%;height: 130px;position: absolute;top: 45%;z-index: 0;opacity:0.2;"
            alt="Institute Logo">
    </div>
@endsection
@section('scripts')
    <script>
        function reset() {
            $("#ay").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#batch").val($("#target option:first").val());
            $("#regulation").val($("#target option:first").val());
            $("#semester").val($("#target option:first").val());
            $("#exam_month_year").val($("#target option:first").val());
            $("#user_name_id").val($("#target option:first").val());
            $('select').select2();
            $('#user_name_id').html('');
        }
        let theData;

        function checkFields() {
            if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#exam_month_year").val() == '') {
                Swal.fire('', 'Please Select Exam Month & Year', 'error');
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
                let ay = $("#ay").val();
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
                        // console.log(response)
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

        function preview() {
            if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#exam_month_year").val() == '') {
                Swal.fire('', 'Please Select Exam Month & Year', 'error');
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
                Swal.fire('', 'Please Select Register No', 'error');
                return false;
            } else {
                $("#loading_div").show();
                $("#hollticket_div").hide();
                let ay = $("#ay").val();
                let batch = $("#batch").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let user_name_id = $("#user_name_id").val();
                let exam_month_year = $("#exam_month_year").val();
                $.ajax({
                    url: '{{ route('admin.exam-registrations.getHallTicketPreview') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'ay': ay,
                        'batch': batch,
                        'course': course,
                        'semester': semester,
                        'user_name_id': user_name_id,
                        'exam_month_year': exam_month_year
                    },
                    success: function(response) {

                        let status = response.status;
                        let data = response.data;
                        $("#loading_div").hide();
                        if (status == true) {
                            let data_len = data.length;
                            let stu_status = response.stu_status;
                            let enrolls = response.enrolls;
                            let split_month = exam_month_year.split('|');
                            let exam_month_div = `End Semester Examinations – November / ` + split_month[0] +
                                ' ' +
                                split_month[1];
                            $("#exam_month_div").html(exam_month_div);
                            $("#sem_div").html('0' + semester);
                            theData = data;
                            $("#student_img").attr('src', '');
                            if (data_len > 0) {
                                let firstDataForRow = data[0];
                                let firstData = data[0][0];
                                let subjectsCount = 0;
                                let left_div = '';
                                let right_div = '';
                                let inputDate = new Date(firstData.personal_details.dob);
                                let dob = inputDate.toLocaleDateString('en-GB');
                                $("#dob_div").html(dob);
                                $("#name_div").html(firstData.student.name);
                                $("#register_no_div").html(firstData.student.register_no);
                                $("#degree_div").html(firstData.courses.name);
                                if (firstData.profile != null) {
                                    let imgSrc = firstData.profile.filePath;
                                    let img = 'https://enggdemo.kalvierp.com/' + imgSrc;
                                    $("#student_img").attr('src', img);

                                }
                                if (data_len > 1) {
                                    $("#prev_btn").show();
                                    $("#next_btn").show();
                                    $("#prev_btn").attr('data-id', 0);
                                    $("#next_btn").attr('data-id', 1);
                                } else {
                                    $("#prev_btn").attr('data-id', '');
                                    $("#next_btn").attr('data-id', '');
                                    $("#prev_btn").hide();
                                    $("#next_btn").hide();
                                }
                                let enrollLen = enrolls.length;
                                let link = '';
                                let downloadBtns = '';
                                if (enrollLen > 0) {
                                    let section = ['A', 'B', 'C', 'D', 'E', 'F'];
                                    for (let d = 0; d < enrollLen; d++) {
                                        if (enrolls[d] != '') {
                                            link =
                                                "{{ url('admin/exam-registration-hallticketPreview/pdf') }}" +
                                                '/' +
                                                batch + '/' + ay + '/' + course + '/' + semester + '/' +
                                                stu_status + '/' + enrolls[d];
                                            downloadBtns +=
                                                `<a href="${link}" target="_blank" class="btn btn-sm btn-success mr-1">Download Pdf (${section[d-1]})</a>`;
                                        }
                                    }
                                } else {
                                    link = "{{ url('admin/exam-registration-hallticketPreview/pdf') }}" + '/' +
                                        batch + '/' + ay + '/' + course + '/' + semester + '/' +
                                        stu_status + '/' + 'S';
                                    downloadBtns =
                                        `<a href="${link}" target="_blank" class="btn btn-sm btn-success">Download Pdf</a>`;
                                }
                                $("#download_div").html(downloadBtns);

                                for (let i = 0; i < firstDataForRow.length; i++) {
                                    if (i < 15) {
                                        left_div += `<div class="col-12 row m-auto">
                                                        <div class="col-2 p-1">${firstDataForRow[i].subject_sem}</div>
                                                        <div class="col-3 p-1">${firstDataForRow[i].subject.subject_code}</div>
                                                        <div class="col-7 p-1">${firstDataForRow[i].subject.name}</div>
                                                     </div>`;
                                    } else {
                                        right_div += `<div class="col-12 row m-auto">
                                                        <div class="col-2 p-1">${firstDataForRow[i].subject_sem}</div>
                                                        <div class="col-3 p-1">${firstDataForRow[i].subject.subject_code}</div>
                                                        <div class="col-7 p-1">${firstDataForRow[i].subject.name}</div>
                                                     </div>`;
                                    }
                                    subjectsCount++;
                                }
                                let calculate = 0;
                                if (firstDataForRow.length > 15) {
                                    calculate = 30 - firstDataForRow.length;
                                    for (let k = firstDataForRow.length; k < calculate; k++) {
                                        right_div += `<div class="col-12" style="height:29.609px;"></div>`;
                                    }
                                } else {
                                    calculate = 30 - firstDataForRow.length;
                                    for (let k = (firstDataForRow.length + 1); k <= 30; k++) {
                                        if (k <= 15) {
                                            left_div += `<div class="col-12" style="height:29.609px;"></div>`;
                                        } else {
                                            right_div +=
                                                `<div class="col-12" style="height:29.609px;"></div>`;
                                        }
                                    }
                                }
                                $("#right_div").html(right_div);
                                $("#left_div").html(left_div);
                                $("#subjectsCount").html(subjectsCount);
                            }
                            $("#hollticket_div").show();
                        } else {
                            $("#hollticket_div").hide();
                            Swal.fire('', data, 'error');
                        }
                    }
                })
            }
        }

        function action(element) {
            let index = $(element).data('id');

            if (index != '') {
                let theIndex = parseInt(index);

                let firstDataForRow = theData[theIndex];
                let firstData = firstDataForRow[0];
                let inputDate = new Date(firstData.personal_details.dob);
                $("#name_div").html(firstData.student.name);
                $("#register_no_div").html(firstData.student.register_no);
                $("#degree_div").html(firstData.courses.name);
                $("#student_img").attr('src', '');
                
                if (firstData.profile != null) {
                    let imgSrc = firstData.profile.filePath;
                    let img = 'https://enggdemo.kalvierp.com/' + imgSrc;
                    $("#student_img").attr('src', img);
                }
                let dob = inputDate.toLocaleDateString('en-GB');
                $("#dob_div").html(dob);
                inputDate = '';
                // let ay = $("#ay").val();
                // let batch = $("#batch").val();
                // let course = $("#course").val();
                // let semester = $("#semester").val();
                //   $("#sem_div").html('0' + semester);
                //   let download = batch + '/' + ay + '/' + course + '/' + semester + '/All';
                //   $("#download").attr('href', "{{ url('admin/exam-registration-hallticketPreview/pdf/') }}" + '/' +
                //  download);
                let nxtBn = theIndex + 1;

                let prvBn = 0;
                let subjectsCount = 0;
                let left_div = '';
                let right_div = '';
                if (theIndex >= 1) {
                    prvBn = theIndex - 1;
                    $("#prev_btn").data('id', prvBn);
                } else {
                    $("#prev_btn").data('id', prvBn);
                }
                $("#next_btn").data('id', nxtBn);
                for (let i = 0; i < firstDataForRow.length; i++) {
                    if (i <= 15) {
                        left_div += `<div class="col-12 row m-auto">
                                                        <div class="col-2 p-1">${firstDataForRow[i].subject_sem}</div>
                                                        <div class="col-3 p-1">${firstDataForRow[i].subject.subject_code}</div>
                                                        <div class="col-7 p-1">${firstDataForRow[i].subject.name}</div>
                                                     </div>`;
                    } else {
                        right_div += `<div class="col-12 row m-auto">
                                                        <div class="col-2 p-1">${firstDataForRow[i].subject_sem}</div>
                                                        <div class="col-3 p-1">${firstDataForRow[i].subject.subject_code}</div>
                                                        <div class="col-7 p-1">${firstDataForRow[i].subject.name}</div>
                                                     </div>`;
                    }
                    subjectsCount++;
                }
                let calculate = 0;
                if (firstDataForRow.length > 15) {
                    calculate = 30 - firstDataForRow.length;
                    for (let k = firstDataForRow.length; k < calculate; k++) {
                        right_div += `<div class="col-12 p-2" style="height:29.609px;"></div>`;
                    }
                } else {
                    calculate = 30 - firstDataForRow.length;
                    for (let k = (firstDataForRow.length + 1); k <= 30; k++) {
                        if (k <= 15) {
                            left_div += `<div class="col-12" style="height:29.609px;"></div>`;
                        } else {
                            right_div += `<div class="col-12 p-2" style="height:29.609px;"></div>`;
                        }
                    }
                }
                $("#right_div").html(right_div);
                $("#left_div").html(left_div);
                $("#subjectsCount").html(subjectsCount);
            }
        }
    </script>
@endsection
