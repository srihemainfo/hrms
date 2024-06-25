@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>


    <div class="card">
        <div class="card-header">
            Exam Registration Preview Form
        </div>
        <div class="card-body">
            <div class="row">
                {{-- <div class="col-4">
                <div class="form-group">
                    <label class="required" for="">Regulation</label>
                    <select name="regulation" id="regulation" class="form-control select2">
                        <option value="">Select Regulation</option>
                        @foreach ($regulations as $id => $data)
                            <option value="{{ $id }}">{{ $data }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
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
                {{-- <div class="col-4">
                <div class="form-group">
                    <label class="required" for="">Exam Type</label>
                    <select name="exam_type" id="exam_type" class="form-control select2">
                        <option value="">Select Exam Type</option>
                        <option value="Regular">Regular</option>
                        <option value="Arrear">Arrear</option>
                    </select>
                </div>
            </div> --}}
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Register No</label>
                        <select name="user_name_id" id="user_name_id" class="form-control select2">
                            {{-- <option value="">Select Register No</option>
                            <option value="All">All Students</option> --}}
                            {{-- @foreach ($registerNos as $data)
                                <option value="{{ $data->student->user_name_id }}">{{ $data->student->name }}
                                    ({{ $data->student->register_no }})
                                </option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="preview()">Preview</button>
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .table thead th {
                vertical-align: bottom;
                border-bottom: none;
            }

            .table th,
            .table td {
                border-top: none;
            }

            .tab>.th {
                border-top: 1px solid black;
                border-bottom: 1px solid black;
            }

            .table td,
            .table td {
                padding: none;
            }

            .ft .table td,
            .ft .table th {
                padding: .15rem;
                vertical-align: middle;
                /* border-top: 1px solid #dee2e6; */
            }

            .table td,
            .table th {
                padding-top: 0px;
                padding-bottom: 0px;
                padding-left: 0px;
                padding-right: 0px;
                font-size: .7rem;
            }

            .border-rl {
                border-right: 1px solid black;
                border-left: 1px solid black;

            }

            body {
                margin-top: 2rem;
            }
        </style>
    </div>

    <div class="card text-primary text-center" id="loading_div" style="display:none;">
        <div class="card-body">
            Loading...
        </div>
    </div>
    <div class="card" id="exam_fee_div" style="display:none;">
        <div class="card-body">
            <div class=" text-center">
                <b>EXAMINATION FEE REPORT</b>
            </div>
            <div class="pb-2 row">
                <div class="col-6 text-left text-primary">
                    <span id="prev_btn" style="display:none;font-size:1.7rem;cursor:pointer;" data-id="" class="ml-2"
                        onclick="action(this)"><i class="fas fa-arrow-circle-left"></i></span>
                    <span id="next_btn" style="display:none;font-size:1.7rem;cursor:pointer;" data-id="" class="ml-4"
                        onclick="action(this)"><i class="fas fa-arrow-circle-right"></i></span>
                </div>
                <div class="col-6 text-right" id="downloadContainer">

                </div>
            </div>
            <div class="row" id='hallTicketDetails'></div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function reset() {
            $("#ay").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#batch").val($("#target option:first").val());
            $("#semester").val($("#target option:first").val());
            $("#user_name_id").val($("#target option:first").val());
            $('select').select2();
            $('#user_name_id').html('');
        }

        let theData;
        let theEnrolls = [];

        function checkFields() {
            if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
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
                $("#exam_fee_div").hide();
                let ay = $("#ay").val();
                let batch = $("#batch").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let user_name_id = $("#user_name_id").val();
                $.ajax({
                    url: '{{ route('admin.exam-registrations.getPreview') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'ay': ay,
                        'batch': batch,
                        'course': course,
                        'semester': semester,
                        'user_name_id': user_name_id
                    },
                    success: function(response) {
                        // console.log(response)
                        let status = response.status;
                        let data = response.data;
                        $("#loading_div").hide();
                        if (status == true) {
                            theEnrolls = [];
                            let data_len = data.length;
                            let stu_status = response.stu_status;
                            let enrolls = response.enrolls;
                            let enroll_length = enrolls.length;
                            theData = data;
                            if (data_len > 0) {
                                let firstDataForRow = data[0];
                                let firstData = data[0][0];
                                let inputDate = new Date(firstData.personal_details.dob);
                                let dob = inputDate.toLocaleDateString('en-GB');
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

                                let downloadBtns = '';
                                let links = '';
                                let section = ['A','B','C','D','E','F'];

                                if (enroll_length > 0) {
                                    for (let j = 0; j < enroll_length; j++) {
                                        if (enrolls[j] != '') {
                                            theEnrolls.push(enrolls[j]);

                                            let link = "{{ url('admin/exam-registration-preview/pdf/') }}" + '/'+
                                                batch + '/' + ay + '/' + course + '/' + semester + '/' +
                                                stu_status + '/' + enrolls[j];
                                            downloadBtns +=
                                                `<a href="${link}" target="_blank" class="btn btn-sm btn-success mr-1">Download Pdf (${section[j-1]})</a>`;
                                        }
                                    }
                                } else {

                                    let link = "{{ url('admin/exam-registration-preview/pdf/') }}" + '/' + batch +
                                        '/' + ay + '/' + course + '/' + semester + '/' + stu_status + '/' + 'S';
                                    downloadBtns =
                                        `<a href="${link}" target="_blank" class="btn btn-sm btn-success">Download Pdf</a>`;
                                }

                                $("#downloadContainer").html(downloadBtns);

                                let regularSubjectRows = '';
                                let arrearSubjectRows = '';
                                let totalAmount = 0;
                                let regularCredits = 0;
                                let arrearCredits = 0;
                                let regularCount = 0;
                                let arrearCount = 0;


                                for (let i = 0; i < firstDataForRow.length; i++) {
                                    if (firstDataForRow[i].exam_type == "Regular") {
                                        regularSubjectRows += `
                                                <tr>
                                                    <td class="text-center" style="border-right:1px solid black;">${firstDataForRow[i].subject_sem}</td>
                                                    <td class="text-center" style="border-right:1px solid black;">${firstDataForRow[i].subject.subject_code}
                                                    </td>
                                                    <td style='padding-left:5px;'>
                                                        ${firstDataForRow[i].subject.name}</td>
                                                </tr>`;

                                        regularCredits += parseInt(firstDataForRow[i].credits);
                                        regularCount++;
                                    } else {
                                        arrearCount++;
                                    }
                                    totalAmount += parseInt(firstDataForRow[i].exam_fee != null ? firstDataForRow[i].exam_fee : 0);
                                }



                                for (let i = 0; i < firstDataForRow.length; i++) {
                                    if (firstDataForRow[i].exam_type == "Arrear") {
                                        arrearSubjectRows += `
                                                <tr>
                                                    <td class="text-center" style="border-right:1px solid black">${firstDataForRow[i].subject_sem}</td>
                                                    <td class="text-center" style="border-right:1px solid black">${firstDataForRow[i].subject.subject_code}
                                                    </td>
                                                    <td style='padding-left:5px;'>
                                                        ${firstDataForRow[i].subject.name}</td>
                                                </tr>`;

                                        arrearCredits += parseInt(firstDataForRow[i].credits);
                                    }
                                }


                                if (arrearCount > regularCount) {
                                    var emptyCount = arrearCount - regularCount;
                                    for (let i = 0; i < emptyCount; i++) {
                                        regularSubjectRows += `
                                            <tr style='padding:5px'>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;</td>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                                    </td>
                                            </tr>`;
                                    }


                                } else {
                                    var emptyCount = regularCount - arrearCount;
                                    for (let i = 0; i < emptyCount; i++) {
                                        arrearSubjectRows += `
                                            <tr style='padding:5px'>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;</td>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    </td>
                                            </tr>`;

                                    }

                                }

                                var responsive = `<div class="table-responsive">
                                                     <div style="overflow-x: auto;">
                                                         <table class="ft table" style="width:100%;">
                                                             <thead>
                                                                 <tr>
                                                                     <td style='border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;width:50%;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="margin-bottom:0px;border:0px;width:100%;">
                                                                                 <thead>
                                                                                     <tr>
                                                                                         <th style="width:30%;">
                                                                                             Name of the Candidate
                                                                                         </th>
                                                                                         <td style="width:3%;">:</td>
                                                                                         <td class='text-left pl-1' align="right">
                                                                                             ${firstData.student.name}
                                                                                         </td>
                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th>
                                                                                             Degree & Branch
                                                                                         </th>
                                                                                         <td>:</td>
                                                                                         <td class='text-left pl-1' align="right">
                                                                                             ${firstData.courses.name}
                                                                                         </td>
                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th>
                                                                                             Academic Year
                                                                                         </th>
                                                                                         <td>:</td>
                                                                                         <td class='text-left pl-1' align="right">
                                                                                             ${firstData.ay.name}
                                                                                         </td>
                                                                                     </tr>
                                                                                 </thead>
                                                                             </table>
                                                                         </div>
                                                                     </td>
                                                                     <td style='border-top:1px solid black;border-left:1px solid black;width:50%;border-right:1px solid black;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="margin-bottom:0px;border:0px;width:100%;">
                                                                                 <thead>
                                                                                     <tr>
                                                                                         <th style="width:30%;">
                                                                                             Register No
                                                                                         </th>
                                                                                         <td style="width:3%;">:</td>
                                                                                         <td class='text-left pl-1' align="left">
                                                                                             ${firstData.student.register_no}
                                                                                         </td>
                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th>
                                                                                             Date Of Birth
                                                                                         </th>
                                                                                         <td>:</td>
                                                                                         <td class='text-left pl-1' align="right">
                                                                                             ${dob}
                                                                                         </td>
                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th>
                                                                                             Regulation
                                                                                         </th>
                                                                                         <td>:</td>
                                                                                         <td class='text-left pl-1' align="right">
                                                                                             ${firstData.regulations.name}
                                                                                         </td>
                                                                                     </tr>
                                                                                 </thead>
                                                                             </table>
                                                                         </div>
                                                                     </td>
                                                                 </tr>
                                                             </thead>
                                                             <tbody>
                                                                 <tr>
                                                                     <td style='border-left:1px solid black;border-right: 1px solid black;border-top:1px solid black;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="margin-bottom:0px;border:0px;">
                                                                                 <thead>
                                                                                     <tr class="">
                                                                                         <th style="text-align:center;border-right:1px solid black; border-bottom:1px solid black;">

                                                                                             Sem No
                                                                                         </th>
                                                                                         <th style="text-align:center;border-right:1px solid black; border-bottom:1px solid black;">

                                                                                             Subject Code
                                                                                         </th>
                                                                                         <th style="text-align:center;border-bottom:1px solid black;">

                                                                                             Subject Title (Regular)
                                                                                         </th>
                                                                                     </tr>
                                                                                     ${regularSubjectRows}
                                                                                 </thead>
                                                                             </table>
                                                                         </div>
                                                                     </td>
                                                                     <td style='border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="width:100%;margin-bottom:0px;border:0px;">
                                                                                 <thead>
                                                                                     <tr>
                                                                                         <th style="text-align:center;border-right:1px solid black; border-bottom:1px solid black;">

                                                                                             Sem No
                                                                                         </th>
                                                                                         <th style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">

                                                                                             Subject Code
                                                                                         </th>
                                                                                         <th style="text-align:center;border-bottom:1px solid black;">

                                                                                             Subject Title (Arrear Exam - If Any)
                                                                                         </th>
                                                                                     </tr>
                                                                                     ${arrearSubjectRows}
                                                                                 </thead>
                                                                             </table>
                                                                         </div>
                                                                     </td>
                                                                 </tr>
                                                                 <tr>
                                                                     <td style='border-right:1px solid black;border-left:1px solid black;'>
                                                                         <div class="table-responsive" >
                                                                             <table class="table tab" style="margin-bottom:0px;">
                                                                                 <thead>
                                                                                     <tr>
                                                                                         <th style="border-top:1px solid black;">&nbsp;</th>
                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th class="text-right pr-1" rowspan ='4' colspan="4" style="">
                                                                                             Total
                                                                                             Credits: &nbsp; ${regularCredits}</th>
                                                                                     </tr>
                                                                                 </thead>
                                                                             </table>
                                                                         </div>
                                                                     </td>
                                                                     <td style='border-right:1px solid black;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="margin-bottom:0px;">
                                                                                 <thead class="the text-right">
                                                                                    <tr>

                                                                                         <th class="text-right pr-1" aligh="left">Total Credits: &nbsp; ${arrearCredits}</th>

                                                                                     </tr>
                                                                                     <tr class="text-right">
                                                                                         <th class='text-right pr-1' aligh="right"> Grand Total Credits: &nbsp;${regularCredits + arrearCredits}</th>
                                                                                     </tr>
                                                                                 </thead>
                                                                             </table>
                                                                         </div>
                                                                     </td>
                                                                 </tr>
                                                                 <tr>
                                                                     <th style='border-right:1px solid black;border-left:1px solid black; border-top:1px solid black;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="margin-bottom: 0px;width:100%;">
                                                                                 <thead>
                                                                                     <tr>
                                                                                         <th style="width:30%;" align="right">Mobile No</th>
                                                                                         <td>:</td>
                                                                                         <th class='text-left pl-2 'align="right">${firstData.personal_details.mobile_number}</th>
                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th style="width:30%;">Email</th>
                                                                                         <td>:</td>
                                                                                         <th class='text-left pl-2'align="right">${firstData.personal_details.email}</th>
                                                                                     </tr>
                                                                                 </thead>
                                                                             </table>
                                                                         </div>
                                                                     </th>
                                                                     <th style='border-right:1px solid black; border-top:1px solid black;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="margin-bottom:0px;width:100%;">
                                                                                 <thead class="the">
                                                                                     <tr>
                                                                                         <th class='text-right pr-1' style="width:85%;">
                                                                                             No Of Papers &nbsp;
                                                                                         </th>
                                                                                         <th>:</th>
                                                                                         <th>${firstDataForRow.length}</th>
                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th class='text-right pr-1' style="width:85%;">
                                                                                             Total Amount : &nbsp;
                                                                                         </th>
                                                                                         <th>:</th>
                                                                                         <th>${totalAmount}</th>
                                                                                     </tr>
                                                                                 </thead>
                                                                             </table>
                                                                         </div>
                                                                     </th>
                                                                 </tr>

                                                                 <tr>
                                                                     <th style='border-left:1px solid black; border-top: 1px solid black;border-bottom: 1px solid black;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="margin-bottom:0px;">
                                                                                 <thead class="the">
                                                                                     <tr>
                                                                                         <th class="pb-5 ">
                                                                                             I hereby declare that the particulars furnished by me in this application
                                                                                             are
                                                                                             correct
                                                                                         </th>

                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th class="pb-3"></th>

                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th class="text-right pr-1" style="padding-bottom:5px;">
                                                                                             Signature of the Candidate with Date
                                                                                         </th>

                                                                                     </tr>

                                                                                 </thead>

                                                                             </table>
                                                                         </div>
                                                                     </th>
                                                                     <th class="" style='border:1px solid black;'>
                                                                         <div class="table-responsive">
                                                                             <table class="table tab" style="margin-bottom:0px;">
                                                                                 <thead class="the">
                                                                                     <tr>
                                                                                         <th class="pb-5">&nbsp;</th>

                                                                                     </tr>

                                                                                     <tr>
                                                                                         <th class="pb-3"></th>

                                                                                     </tr>
                                                                                     <tr>
                                                                                         <th class="text-right pr-1" style="padding-bottom:5px;">
                                                                                             Signature of the Head of the Department with Date
                                                                                         </th>

                                                                                     </tr>
                                                                                 </thead>

                                                                             </table>
                                                                         </div>
                                                                     </th>
                                                                 </tr>
                                                             </tbody>
                                                         </table>
                                                     </div>
                                                 </div>`;

                                $('#hallTicketDetails').html(responsive);
                            }
                            $("#exam_fee_div").show();
                        } else {
                            $("#exam_fee_div").hide();
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
                let dob = inputDate.toLocaleDateString('en-GB');
                let ay = $("#ay").val();
                let batch = $("#batch").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let enroll_len = theEnrolls.length;
                let section = ['A','B','C','D','E','F'];
                let downloadBtns = '';
                for (let i = 0; i < enroll_len; i++) {
                    if (theEnrolls[i] != '') {
                        let link = "{{ url('admin/exam-registration-preview/pdf/') }}" + '/'+
                            batch + '/' + ay + '/' + course + '/' + semester + '/' +
                            'All' + '/' + theEnrolls[i];
                        downloadBtns +=
                            `<a href="${link}" target="_blank" class="btn btn-sm btn-success mr-1">Download Pdf (${section[i]})</a>`;
                    }
                }
                $("#downloadContainer").html(downloadBtns);
                let nxtBn = theIndex + 1;

                let prvBn = 0;
                if (theIndex >= 1) {
                    prvBn = theIndex - 1;
                    $("#prev_btn").data('id', prvBn);
                } else {
                    $("#prev_btn").data('id', prvBn);
                }
                $("#next_btn").data('id', nxtBn);
                let regularSubjectRows = '';
                let arrearSubjectRows = '';
                let totalAmount = 0;
                let regularCredits = 0;
                let arrearCredits = 0;
                let regularCount = 0;
                let arrearCount = 0;

                for (let i = 0; i < firstDataForRow.length; i++) {
                    if (firstDataForRow[i].exam_type == "Regular") {
                        regularSubjectRows += `
                                                <tr>
                                                    <td class="text-center" style="border-right:1px solid black;">${firstDataForRow[i].subject_sem}</td>
                                                    <td class="text-center" style="border-right:1px solid black;">${firstDataForRow[i].subject.subject_code}
                                                    </td>
                                                    <td style='padding-left:5px;'>
                                                        ${firstDataForRow[i].subject.name}</td>
                                                </tr>`;

                        regularCredits += parseInt(firstDataForRow[i].credits);
                        regularCount++;
                    } else {
                        arrearCount++;
                    }
                    totalAmount += parseInt(firstDataForRow[i].exam_fee != null ? firstDataForRow[i].exam_fee : 0);
                }



                for (let i = 0; i < firstDataForRow.length; i++) {
                    if (firstDataForRow[i].exam_type == "Arrear") {
                        arrearSubjectRows += `
                                                <tr>
                                                    <td class="text-center" style="border-right:1px solid black">${firstDataForRow[i].subject_sem}</td>
                                                    <td class="text-center" style="border-right:1px solid black">${firstDataForRow[i].subject.subject_code}
                                                    </td>
                                                    <td style='padding-left:5px;'>
                                                        ${firstDataForRow[i].subject.name}</td>
                                                </tr>`;

                        arrearCredits += parseInt(firstDataForRow[i].credits);
                    }
                }


                if (arrearCount > regularCount) {
                    var emptyCount = arrearCount - regularCount;
                    for (let i = 0; i < emptyCount; i++) {

                        regularSubjectRows += `
                                            <tr style='padding:5px'>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;</td>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                                    </td>
                                            </tr>`;
                    }


                } else {
                    var emptyCount = regularCount - arrearCount;
                    for (let i = 0; i < emptyCount; i++) {

                        arrearSubjectRows += `
                                            <tr style='padding:5px'>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;</td>
                                                <td class="text-center" style="border-right:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    </td>
                                            </tr>`;

                    }

                }


                var responsive = `<div class="table-responsive">
                                                 <div style="overflow-x: auto;">
                                                     <table class="ft table" style="width:100%;">
                                                         <thead>
                                                             <tr>
                                                                 <td style='border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;width:50%;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="margin-bottom:0px;border:0px;width:100%;">
                                                                             <thead>
                                                                                 <tr>
                                                                                     <th style="width:30%;">
                                                                                         Name of the Candidate
                                                                                     </th>
                                                                                     <td style="width:3%;">:</td>
                                                                                     <td class='text-left pl-1' align="right">
                                                                                         ${firstData.student.name}
                                                                                     </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th>
                                                                                         Degree & Branch
                                                                                     </th>
                                                                                     <td>:</td>
                                                                                     <td class='text-left pl-1' align="right">
                                                                                         ${firstData.courses.name}
                                                                                     </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th>
                                                                                         Academic Year
                                                                                     </th>
                                                                                     <td>:</td>
                                                                                     <td class='text-left pl-1' align="right">
                                                                                         ${firstData.ay.name}
                                                                                     </td>
                                                                                 </tr>
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </td>
                                                                 <td style='border-top:1px solid black;border-left:1px solid black;width:50%;border-right:1px solid black;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="margin-bottom:0px;border:0px;width:100%;">
                                                                             <thead>
                                                                                 <tr>
                                                                                     <th style="width:30%;">
                                                                                         Register No
                                                                                     </th>
                                                                                     <td style="width:3%;">:</td>
                                                                                     <td class='text-left pl-1' align="left">
                                                                                         ${firstData.student.register_no}
                                                                                     </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th>
                                                                                         Date Of Birth
                                                                                     </th>
                                                                                     <td>:</td>
                                                                                     <td class='text-left pl-1' align="right">
                                                                                         ${dob}
                                                                                     </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th>
                                                                                         Regulation
                                                                                     </th>
                                                                                     <td>:</td>
                                                                                     <td class='text-left pl-1' align="right">
                                                                                         ${firstData.regulations.name}
                                                                                     </td>
                                                                                 </tr>
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </td>
                                                             </tr>
                                                         </thead>
                                                         <tbody>
                                                             <tr>
                                                                 <td style='border-left:1px solid black;border-right: 1px solid black;border-top:1px solid black;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="margin-bottom:0px;border:0px;">
                                                                             <thead>
                                                                                 <tr class="">
                                                                                     <th style="text-align:center;border-right:1px solid black; border-bottom:1px solid black;">
                                                                                         Sem No
                                                                                     </th>
                                                                                     <th style="text-align:center;border-right:1px solid black; border-bottom:1px solid black;">
                                                                                         Subject Code
                                                                                     </th>
                                                                                     <th style="text-align:center;border-bottom:1px solid black;">
                                                                                         Subject Title (Regular)
                                                                                     </th>
                                                                                 </tr>
                                                                                 ${regularSubjectRows}
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </td>
                                                                 <td style='border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="width:100%;margin-bottom:0px;border:0px;">
                                                                             <thead>
                                                                                 <tr>
                                                                                     <th style="text-align:center;border-right:1px solid black; border-bottom:1px solid black;">
                                                                                         Sem No
                                                                                     </th>
                                                                                     <th style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">
                                                                                         Subject Code
                                                                                     </th>
                                                                                     <th style="text-align:center;border-bottom:1px solid black;">
                                                                                         Subject Title (Arrear Exam - If Any)
                                                                                     </th>
                                                                                 </tr>
                                                                                 ${arrearSubjectRows}
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </td>
                                                             </tr>
                                                             <tr>
                                                                 <td style='border-right:1px solid black;border-left:1px solid black;'>
                                                                     <div class="table-responsive" >
                                                                         <table class="table tab" style="margin-bottom:0px;">
                                                                             <thead class="the">
                                                                                 <tr>
                                                                                     <th style="border-top:1px solid black;">&nbsp;</th>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th class="text-right pr-1" rowspan ='4' colspan="4" style="">
                                                                                         Total
                                                                                         Credits: &nbsp; ${regularCredits}</th>
                                                                                 </tr>
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </td>
                                                                 <td style='border-right:1px solid black;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="margin-bottom:0px;">
                                                                             <thead class="the text-right">
                                                                                <tr>
                                                                                     <th class="text-right pr-1" aligh="left">Total Credits: &nbsp; ${arrearCredits}</th>
                                                                                 </tr>
                                                                                 <tr class="text-right">
                                                                                     <th class='text-right pr-1' aligh="right"> Grand Total Credits: &nbsp;${regularCredits + arrearCredits}</th>
                                                                                 </tr>
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </td>
                                                             </tr>
                                                             <tr>
                                                                 <th style='border-right:1px solid black;border-left:1px solid black; border-top:1px solid black;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="margin-bottom: 0px;width:100%;">
                                                                             <thead>
                                                                                 <tr>
                                                                                     <th style="width:30%;" align="right">Mobile No</th>
                                                                                     <td>:</td>
                                                                                     <th class='text-left pl-2 'align="right">${firstData.personal_details.mobile_number}</th>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th style="width:30%;">Email</th>
                                                                                     <td>:</td>
                                                                                     <th class='text-left pl-2'align="right">${firstData.personal_details.email}</th>
                                                                                 </tr>
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </th>
                                                                 <th style='border-right:1px solid black; border-top:1px solid black;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="margin-bottom:0px;width:100%;">
                                                                             <thead class="the">
                                                                                 <tr>
                                                                                     <th class='text-right pr-1' style="width:85%;">
                                                                                         No Of Papers &nbsp;
                                                                                     </th>
                                                                                     <th>:</th>
                                                                                     <th>${firstDataForRow.length}</th>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th class='text-right pr-1' style="width:85%;">
                                                                                         Total Amount : &nbsp;
                                                                                     </th>
                                                                                     <th>:</th>
                                                                                     <th>${totalAmount}</th>
                                                                                 </tr>
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </th>
                                                             </tr>
                                                             <tr>
                                                                 <th style='border-left:1px solid black; border-top: 1px solid black;border-bottom: 1px solid black;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="margin-bottom:0px;">
                                                                             <thead class="the">
                                                                                 <tr>
                                                                                     <th class="pb-5 ">
                                                                                         I hereby declare that the particulars furnished by me in this application
                                                                                         are
                                                                                         correct
                                                                                     </th>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th class="pb-3"></th>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th class="text-right pr-1" style="padding-bottom:5px;">
                                                                                         Signature of the Candidate with Date
                                                                                     </th>
                                                                                 </tr>
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </th>
                                                                 <th class="" style='border:1px solid black;'>
                                                                     <div class="table-responsive">
                                                                         <table class="table tab" style="margin-bottom:0px;">
                                                                             <thead class="the">
                                                                                 <tr>
                                                                                     <th class="pb-5">
                                                                                         I hereby declare that the particulars furnished by me in this application
                                                                                         are
                                                                                         correct
                                                                                     </th>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th class="pb-3"></th>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <th class="text-right pr-1" style="padding-bottom:5px;">
                                                                                         Signature of the Head of the Department with Date
                                                                                     </th>
                                                                                 </tr>
                                                                             </thead>
                                                                         </table>
                                                                     </div>
                                                                 </th>
                                                             </tr>
                                                         </tbody>
                                                     </table>
                                                 </div>
                                                 </div>`;

                $('#hallTicketDetails').html(responsive);
            }
        }
    </script>
@endsection
