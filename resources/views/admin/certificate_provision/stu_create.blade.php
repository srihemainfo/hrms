@extends('layouts.studentHome')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>


    <a class="btn btn-default mb-3" href="{{ route('admin.student-apply-certificate.stu_index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="row">
        <div class="col-xl-8 col-lg-9 col-md-10 col-sm-10 col-12 m-auto card" style="position: relative;">
            <div class="card-header text-primary">
                Certificate Application
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="" class="required">Certificate</label>
                            <select name="cer_type" id="certificate" class="form-control select2">
                                <option value="">Select Certificate</option>
                                <option value="BONAFIDE">BONAFIDE CERTIFICATE</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="" class="required">Date</label>
                            <input type="text" name="date" id="date" class="form-control date"
                                placeholder="Select Date">
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id='bonfide_type' style='display:none;'>
                        <div class="row">
                            <div class="form-group col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                                <label for="" class="required">Reason For Applying</label>
                                <select name="purpose_type" id="purpose_type" class="form-control select2">
                                    <option value="">Select Bonafide Reason</option>
                                </select>
                            </div>
                            <div class="form-group col-xl-1 col-lg-1 col-md-1 col-sm-5 col-5">
                                <div class="form-check form-check-inline" style='padding-top:32px'>
                                    <input class="form-check-input" id='checkbox' type="checkbox" value="Hostel"
                                        onclick="checkHostel(this)" style="cursor:pointer;">
                                    <label class="form-check-label" for="inlineCheckbox1">Hostel</label>
                                </div>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-7 col-7" id="hostel_no_div"
                                style="display:none;">
                                <label for="hostel_no" style="margin-left:8px;">Hostel Room No</label>
                                <input type="text" class="form-control" name="hostel_no" id="hostel_no" value=""
                                    style="margin-left:8px;width:94%;">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='bonafide_other_type' style='display:none;'>
                            <label for="" class="required">Enter The Reason For Applying</label>
                            <input type="text" name="purpose" id="purpose" class="form-control"
                                placeholder="Enter The Reason For Applying">
                            <input type="hidden" name="hostelmessage" id="hostelmessage" class="form-control"
                                placeholder="Enter The Reason For Applying">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 m-auto">
                        <div class="form-group text-center">
                            <button class="enroll_generate_bn bg-success" style="margin-top:1.9rem;"
                                onclick="previewCertificate()">
                                Preview Certificate</button>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 m-auto">
                        <div class="form-group text-center">
                            <button class="enroll_generate_bn bg-primary" style="margin-top:1.9rem;" onclick="apply()">
                                Apply</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loader" id="loader" style="display:none;top:14%;">
                <div class="spinner-border text-primary"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="previewModal" role="dialog">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="head_label"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="previewModalBody">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            const $certificate = $("#certificate");
            var $purpose_type = $("#purpose_type");
            $certificate.on("change", function() {

                const certificate = this.value;
                $("#purpose").val('')
                $("#purpose_type").val('')
                if (certificate == '') {
                    $('#bonfide_type').hide();
                    $('#bonafide_other_type').hide();
                } else if (certificate == 'Other') {
                    $('#bonfide_type').hide();
                    $('#bonafide_other_type').hide();
                } else if (certificate == 'BONAFIDE') {
                    const $certificate = $("#certificate");
                    $purpose_type = $("#purpose_type");
                    $.ajax({
                        url: '{{ route('admin.student_bonfide_reason.get') }}',
                        type: 'POST',
                        data: {
                            'certificate': $certificate.val(),
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            let status = response.data;
                            if (status) {

                                let content = '';
                                content += ` <option value="">Select Bonafide Reason</option>`;
                                for (let key in status) {
                                    if (status.hasOwnProperty(key)) {
                                        content +=
                                            `<option style="color:blue;"   value="${key}"> ${status[key]}</option>`;
                                    }
                                }
                                $purpose_type.html(content);
                                $('#bonfide_type').show();
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#bonafide_other_type').hide();
                            $('#bonfide_type').hide();
                            if (jqXHR.status) {
                                if (jqXHR.status == 500) {
                                    Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                                } else {
                                    Swal.fire('', jqXHR.status, 'error');
                                }
                            } else if (textStatus) {
                                Swal.fire('', textStatus, 'error');
                            } else {
                                Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                    "error");
                            }
                        }
                    });

                } else {
                    $('#bonfide_type').hide();
                    $('#bonafide_other_type').hide();
                }

            });

            $purpose_type.on("change", function() {
                $("#purpose").val('');
                if ($purpose_type.val() == '11') {
                    $('#bonafide_other_type').show();
                } else {
                    $('#bonafide_other_type').hide();
                }

            });
        });

        function checkHostel(element) {
            if (element.checked === true) {
                $("#hostel_no_div").show();
            } else {
                $("#hostel_no_div").hide();
            }
        }

        function apply() {

            if ($("#certificate").val() == '') {
                Swal.fire('', 'Please Choose The Certificate Type!', 'warning');
                return false;
            } else if ($("#date").val() == '') {
                Swal.fire('', 'Please Choose The Date!', 'warning');
                return false;
            } else if ($("#purpose").val() == '' && $("#purpose_type").val() == '') {
                Swal.fire('', 'Please Enter The Reason For Applying !', 'warning');
                return false;
            } else if ($("#purpose").val() == '' && $("#purpose_type").val() == 11) {
                Swal.fire('', 'Please Enter The Reason For Applying !', 'warning');
                return false;
            } else {
                $("#loader").hide();
                Swal.fire({
                    title: "Are You Sure?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Apply!",
                    cancelButtonText: "No, Cancel It!",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $("#loader").show();

                        $.ajax({
                            url: '{{ route('admin.student-apply-certificate.get_details') }}',
                            type: 'POST',
                            data: {
                                'date': 'dummy',
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                let status = response.status;
                                let data = response.data;
                                let register_no = '';
                                if (data.register_no != null) {
                                    register_no = data.register_no;
                                }
                                if (status == true) {

                                    let date = $("#date").val();
                                    let certificate = $("#certificate").val();
                                    let purpose = $("#purpose").val();
                                    if (purpose == '') {
                                        purpose = $("#purpose_type option:selected").text();
                                    }
                                    let purpose_type = $("#purpose_type").val();
                                    var hostelCheck = '';
                                    var hostel = false;
                                    let roomNo = null;
                                    if ($('#checkbox').is(":checked")) {
                                        hostelCheck = $('#checkbox').val();
                                        if (hostelCheck == "Hostel") {
                                            roomNo = $("#hostel_no").val();
                                            var $gender = `${data.stu_front  == 'Mr.' ?  'He': 'She' }`;
                                            var hostel_status =
                                                `${$gender} is staying in College Hostel Room No. ${roomNo}  during ${data.ay}`;
                                            hostel = true;
                                        }

                                    }
                                    var reason = $("#purpose").val();
                                    if (reason == '') reason = $("#purpose_type option:selected")
                                        .text();
                                    var message = '';
                                    if (purpose_type == '4') message =
                                        'The Medium of Instruction during the course of study as mentioned above was in English.';
                                    $('#hostelmessage').val(
                                        `${message}${hostel == true ?  hostel_status: '' }`);

                                    $.ajax({
                                        url: '{{ route('admin.student-apply-certificate.store') }}',
                                        type: 'POST',
                                        data: {
                                            'date': date,
                                            'certificate': certificate,
                                            'purpose': purpose,
                                            'hostelcheck': hostelCheck,
                                            'hostel_no': roomNo,
                                            'purpose_type': purpose_type,
                                            'message': $('#hostelmessage').val(),
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                                'content')
                                        },
                                        success: function(response) {
                                            let status = response.status;

                                            if (status == true) {
                                                Swal.fire('',
                                                    'Certificate Application Submitted!',
                                                    'success');

                                            } else {
                                                Swal.fire('', response.data, 'error');
                                            }
                                            $("#loader").hide();
                                            window.location.href =
                                                '{{ route('admin.student-apply-certificate.stu_index') }}';
                                            // location.reload();
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            if (jqXHR.status) {
                                                if (jqXHR.status == 500) {
                                                    Swal.fire('', 'Request Timeout / Internal Server Error',
                                                        'error');
                                                } else {
                                                    Swal.fire('', jqXHR.status, 'error');
                                                }
                                            } else if (textStatus) {
                                                Swal.fire('', textStatus, 'error');
                                            } else {
                                                Swal.fire('',
                                                    'Request Failed With Status: ' +
                                                    jqXHR.statusText, "error");
                                            }
                                        }
                                    });

                                } else {
                                    Swal.fire('', data, 'error');
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
                                    Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                        "error");
                                }
                            }
                        });

                    } else {
                        Swal.fire('', 'You Cancelled The Application!', 'info');
                    }
                });
            }
        }

        function previewCertificate() {
            if ($("#certificate").val() == '') {
                Swal.fire('', 'Please Choose The Certificate Type !', 'warning');
                return false;
            } else if ($("#date").val() == '') {
                Swal.fire('', 'Please Choose The Date !', 'warning');
                return false;
            } else if ($("#purpose").val() == '' && $('#purpose_type').val() == '') {
                Swal.fire('', 'Please Enter The Reason For Applying !', 'warning');
                return false;
            } else if ($("#purpose").val() == '' && $("#purpose_type").val() == 11) {
                Swal.fire('', 'Please Enter The Reason For Applying !', 'warning');
                return false;
            } else {

                let certificate = $("#certificate").val() + ' CERTIFICATE PREVIEW';
                let theDate = $("#date").val();
                var parts = theDate.split('-');

                if (parts.length == 3) {
                    var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                } else {
                    Swal.fire('', 'Invalid Date !', 'warning');
                    return false;
                }
                let makeBody =
                    `<div class="row" style="padding:10px;border:2px solid #b3b3b3;border-radius:3px;"><div class="col-12 text-right"><b>Date : ${formattedDate}</b></div>`;
                let bodyHeader =
                    `<div class="col-12 text-center"><b style="font-size:1.5rem;text-alteration-style:underline;">${$("#certificate").val() } CERTIFICATE</b></div>`;
                let bodyContent = '';
                if ($("#certificate").val() == 'BONAFIDE') {
                    $("#previewModalBody").html('');
                    $.ajax({
                        url: '{{ route('admin.student-apply-certificate.get_details') }}',
                        type: 'POST',
                        data: {
                            'date': 'dummy',
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            let status = response.status;
                            let data = response.data;
                            let register_no = '';
                            if (data.register_no != null) {
                                register_no = data.register_no;
                            }
                            if (status == true) {
                                const purpose_type = $("#purpose_type").val();
                                let hostelCheck = '';
                                var hostel = false;
                                if ($('#checkbox').is(":checked")) {
                                    hostelCheck = $('#checkbox').val();
                                    if (hostelCheck == "Hostel") {
                                        console.log(data.stu_front);
                                        let roomNo = $("#hostel_no").val();
                                        var $gender = `${data.stu_front  == 'Mr.' ?  'He': 'She' }`;
                                        var hostel_status =
                                            `${$gender} is staying in College Hostel Room No. ${roomNo} during ${data.ay}`;
                                        hostel = true;
                                    }
                                }

                                var reason = $("#purpose").val();
                                if (reason == '') reason = $("#purpose_type option:selected").text();
                                var message = '';
                                if (purpose_type == '4') message =
                                    'The Medium of Instruction during the course of study as mentioned above was in English.';

                                bodyContent = `<div class="col-12" style="text-indent:30px;padding-top:30px">
                                   This is to certify that  <b> ${data.stu_front} ${data.name}.</b> Register No : <b>${register_no}</b>, <b>${data.gender} Mr. ${data.father_name}</b>, is
                                   a bonafide student of our college studying in ${data.year} ${data.degree} Degree course in ${data.course} during the academic year ${data.ay}.
                               </div>`;
                                bodyContent += `<div class="col-12 text-center" style="padding-top:30px;"><b>
                                                    This Certificate is issued to enable ${data.stu_gen} to apply for ${reason}.${message}${hostel == true ?  hostel_status: '' }</b>
                                               </div>`;
                                bodyContent += `<div class="col-12 text-right" >
                                   <b style="font-size:1.2rem;"  style=" margin-top: 10px;padding-top:30px;">Principal</b>
                               </div>`;
                                makeBody += bodyHeader;
                                makeBody += bodyContent;

                                $('#hostelmessage').val(`${message}${hostel == true ?  hostel_status: '' }`);

                                $("#previewModalBody").html(makeBody);
                                $("#head_label").html(certificate);
                                $("#previewModal").modal();
                            } else {
                                Swal.fire('', data, 'error');
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

            }
        }
    </script>
@endsection
