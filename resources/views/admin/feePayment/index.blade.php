@extends('layouts.admin')
@section('content')
    <style>
        /* .card-body {
                                padding: 10px !important;
                            }

                            .card {
                                margin-bottom: 10px !important;
                            } */

        .select2-container {
            width: 100% !important;
        }

        .table.datatable tbody td.select-checkbox:before {
            content: none !important;
        }

        .backColor {
            background-color: #dff6fc;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Fee Payment
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <label class="required" for="batch">Search Student</label>
                    <select class="form-control select2" name="user_name_id" id="user_name_id" onchange="hider()">
                        <option value="">Student Name (Register No)</option>
                        @if (count($students) > 0)
                            @foreach ($students as $student)
                                <option value="{{ $student->user_name_id }}">{{ $student->name }}
                                    ({{ $student->register_no }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12 text-right">
                    <div>
                        <button type="button" class="enroll_generate_bn bg-primary" style="margin-top:30px;"
                            onclick="getFee()">Get Fee</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loading_div" class="text-primary text-center p-3" style="display:none;">Loading...</div>
    <div id="fee_list">
        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-header text-center">Student Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Register No</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="register_no_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Student Name</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="student_name_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Degree</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="degree_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Course</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="course_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Batch</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="batch_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Admission Mode</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="admission_mode_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Scholarship</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="scholarship_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Scholarship Name</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="scholarship_name_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Current Year</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="current_year_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Current Semester</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="current_semester_div"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group row">
                                <div class="col-4">Current Academic Year</div>
                                <div class="col-1">:</div>
                                <div class="col-7" id="current_ay_div"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header text-center">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-12 col-12">
                                Fee Details
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-12 col-12">
                                <button class="manual_bn bg-warning">Payment History</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 form-group">
                                <label for="payment_mode">Payment Mode</label>
                                <select id="payment_mode" class="select2 form-control">
                                    <option value="">Select Payment Mode</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        let theHostel_1 = false;
        let theHostel_2 = false;
        let theHostel_3 = false;
        let theHostel_4 = false;
        // let TuitionFee_1 = 0;
        // let TuitionFee_2 = 0;
        // let TuitionFee_3 = 0;
        // let TuitionFee_4 = 0;
        let HostelFee_1 = 0;
        let HostelFee_2 = 0;
        let HostelFee_3 = 0;
        let HostelFee_4 = 0;
        let paidStatus_1 = true;
        let paidStatus_2 = true;
        let paidStatus_3 = true;
        let paidStatus_4 = true;
        let checkBoxStatus_1 = true;
        let checkBoxStatus_2 = true;
        let checkBoxStatus_3 = true;
        let checkBoxStatus_4 = true;

        let admittedCat = '';

        window.onload = function() {
            $("#total_fee_div").html(0);
            $("#paying_fee_div").html(0);
            $("#paid_fee_div").html(0);
            $("#balance_fee_div").html(0);
            $("#loading_div").hide();
            // $("#fee_list").hide();
            $("#name_span").hide();
            $("#year_span").hide();

        }

        function hider() {
            // $("#fee_structure").hide();
            // $("#history_bn").hide();
            $("#fee_list").hide();
        }

        function getFee() {
            let user_name_id = $("#user_name_id").val();
            if (user_name_id == '') {
                Swal.fire('', 'Please Select Student', 'error');
            } else {
                // $("#fee_structure").show();
                $("#loading_div").show();
                $("#fee_list").hide();
                checkBoxStatus_1 = true;
                checkBoxStatus_2 = true;
                checkBoxStatus_3 = true;
                checkBoxStatus_4 = true;
                $.ajax({
                    url: '{{ route('admin.fee-payment.getFee') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'user_name_id': user_name_id,
                    },
                    success: function(response) {

                        let status = response.status;
                        let data = response.data;
                        $("#loading_div").hide();
                        if (status == true) {
                            $("#register_no_div").html(data.student.register_no);
                            $("#student_name_div").html(data.student.name);
                            $("#course_div").html(data.course);
                            $("#batch_div").html(data.batch);
                            $("#current_semester_div").html(data.semester);
                            $("#admission_mode_div").html(data.academicDetail.admitted_mode);
                            $("#fee_list").show();
                        } else {
                            Swal.fire('', data, 'error');
                        }

                    }
                })
            }
        }

        function view_more(element, id) {

            let checkBox_status;
            if (id == 1) {
                checkBox_status = checkBoxStatus_1;
            } else if (id == 2) {

                checkBox_status = checkBoxStatus_2;
            } else if (id == 3) {

                checkBox_status = checkBoxStatus_3;
            } else if (id == 4) {

                checkBox_status = checkBoxStatus_4;
            }
            // console.log(checkBox_status + ' check')

            $(element).toggleClass('rotated');
            let theId = '#view_more_' + id;
            let checkId = '#check_' + id;
            let checkId2 = '#check_' + id + id;
            $(theId).toggle();
            if (checkBox_status == true) {
                $(checkId).toggle();
                $(checkId2).toggle();
            }
        };

        function checkMax(element, id, tag) {
            let fg = 0;
            let sponser_amt = 0;

            if (element == 'tuition_' && admittedCat == 'FG') {
                fg = parseInt($('#fg_deduction_' + id).html());
            }
            if (element == 'other_' && admittedCat == 'Scholarship') {
                sponser_amt = parseInt($('#sponser_amt_' + id).html());
            }
            let theTag = $(tag).val();
            let makeArray = theTag.split('');
            if (makeArray.length == 0) {
                $(tag).val(0);
            }
            let totalAmount = parseInt($('#' + element + 'amt_' + id).html());
            let paidAmount = parseInt($('#' + element + 'paid_' + id).html());
            let payableAmount = totalAmount - (fg + paidAmount + sponser_amt);

            if ($(tag).val() > payableAmount) {
                $(tag).val(payableAmount);
            }
            if ($(tag).val() < 0) {
                $(tag).val(0);
            }
        }

        function sum(element, id) {

            let fg = 0;
            let sponser_amt = 0;

            if (element == 'tuition_' && admittedCat == 'FG') {
                fg = parseInt($('#fg_deduction_' + id).html());
            }
            if (element == 'other_' && admittedCat == 'Scholarship') {
                sponser_amt = parseInt($('#sponser_amt_' + id).html());
            }
            let totalAmount = parseInt($('#' + element + 'amt_' + id).html());
            let paidAmount = parseInt($('#' + element + 'paid_' + id).html());
            let payAmount = $('#' + element + 'pay_' + id).val();
            let balance = 0;

            if (payAmount == '') {
                payAmount = 0;
            } else {
                payAmount = parseInt(payAmount);
            }
            let calculate = totalAmount - (fg + paidAmount + payAmount + sponser_amt);

            if (calculate > 0) {
                balance = Math.abs(calculate);
            }

            $('#' + element + 'balance_' + id).html(balance);

            let tuitionPay = $("#tuition_pay_" + id).val();
            let hostelPay = $("#hostel_pay_" + id).val();
            let otherPay = $("#other_pay_" + id).val();

            if (tuitionPay == '') {
                tuitionPay = 0;
            } else {
                tuitionPay = parseInt(tuitionPay);
            }
            if (hostelPay == '') {
                hostelPay = 0;
            } else {
                hostelPay = parseInt(hostelPay);
            }
            if (otherPay == '') {
                otherPay = 0;
            } else {
                otherPay = parseInt(otherPay);
            }

            let tuitionBalance = parseInt($("#tuition_balance_" + id).html());
            let hostelBalance = parseInt($("#hostel_balance_" + id).html());
            let otherBalance = parseInt($("#other_balance_" + id).html());

            let totalPayingAmount = tuitionPay + hostelPay + otherPay;
            let totalBalanceAmount = tuitionBalance + hostelBalance + otherBalance;

            $("#paying_fee_div_" + id).html(totalPayingAmount);
            $("#balance_fee_div_" + id).html(totalBalanceAmount);
        }

        function payFee(id) {
            let user_name_id = $("#user_name_id").val();
            let hostel = false;


            let fee_id = $("#fee_id_" + id).val();
            let fg = null;
            let sponser_amt = null;
            if (admittedCat == 'FG') {
                fg = parseInt($("#fg_deduction_" + id).html());
            }
            if (admittedCat == 'Scholarship') {
                sponser_amt = parseInt($("#sponser_amt_" + id).html());
            }
            if (id == 1) {
                hostel = theHostel_1;
            } else if (id == 2) {
                hostel = theHostel_2;
            } else if (id == 3) {
                hostel = theHostel_3;
            } else if (id == 4) {
                hostel = theHostel_4;
            }

            let hostelFee = 0;
            let hostelPayingFee = 0;
            let hostelPaidFee = 0;
            let hostelBalanceFee = 0;

            // TotalFee
            let totalFee = parseInt($("#total_fee_div_" + id).html());
            let totalPayingFee = parseInt($("#paying_fee_div_" + id).html());
            let totalPaidFee = parseInt($("#paid_fee_div_" + id).html());
            let totalBalanceFee = parseInt($("#balance_fee_div_" + id).html());

            //TuitionFee
            let tuitionFee = parseInt($("#tuition_amt_" + id).html());
            let tuitionPayingFee = parseInt($("#tuition_pay_" + id).val());
            let tuitionPaidFee = parseInt($("#tuition_paid_" + id).html());
            let tuitionBalanceFee = parseInt($("#tuition_balance_" + id).html());

            //HostelFee
            if (hostel == true) {
                hostelFee = parseInt($("#hostel_amt_" + id).html());
                hostelPayingFee = parseInt($("#hostel_pay_" + id).val());
                hostelPaidFee = parseInt($("#hostel_paid_" + id).html());
                hostelBalanceFee = parseInt($("#hostel_balance_" + id).html());
            }

            //OtherFee
            let otherFee = parseInt($("#other_amt_" + id).html());
            let otherPayingFee = parseInt($("#other_pay_" + id).val());
            let otherPaidFee = parseInt($("#other_paid_" + id).html());
            let otherBalanceFee = parseInt($("#other_balance_" + id).html());

            Swal.fire({
                title: "Do You Want To Pay Fee ?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {

                    $.ajax({
                        url: '{{ route('admin.fee-payment.store') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'user_name_id': user_name_id,
                            'year': id,
                            'fee_id': fee_id,
                            'totalFee': totalFee,
                            'totalPayingFee': totalPayingFee,
                            'totalPaidFee': totalPaidFee,
                            'totalBalanceFee': totalBalanceFee,
                            'tuitionFee': tuitionFee,
                            'tuitionPayingFee': tuitionPayingFee,
                            'tuitionPaidFee': tuitionPaidFee,
                            'tuitionBalanceFee': tuitionBalanceFee,
                            'hostelFee': hostelFee,
                            'hostelPayingFee': hostelPayingFee,
                            'hostelPaidFee': hostelPaidFee,
                            'hostelBalanceFee': hostelBalanceFee,
                            'otherFee': otherFee,
                            'otherPayingFee': otherPayingFee,
                            'otherPaidFee': otherPaidFee,
                            'otherBalanceFee': otherBalanceFee,
                            'hostel': hostel,
                            'admittedCat': admittedCat,
                            'fg': fg,
                            'sponser_amt': sponser_amt
                        },
                        success: function(response) {

                            let status = response.status;
                            let data = response.data;

                            if (status == true) {
                                Swal.fire('', data, 'success');
                            } else {
                                Swal.fire('', data, 'error');
                            }
                            location.reload();

                        }
                    })
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Fee Payment Process Cancelled",
                        "error"
                    )
                }
            });
        }

        function addHostelFee(element, id) {
            let givenId = id;
            let status = false;
            if (id == 1) {
                status = theHostel_1;
            } else if (id == 2) {
                status = theHostel_2;
            } else if (id == 3) {
                status = theHostel_3;
            } else if (id == 4) {
                status = theHostel_4;
            }

            let total_amt = 0;
            // if (status == false) {
            if ($(element).prop("checked")) {
                $(element).removeAttr('checked');
                if ($(element).attr('id') == 'hostel_check_1') {
                    theHostel_1 = true;
                    total_amt = parseInt($("#total_fee_div_1").html());
                    total_amt = total_amt + HostelFee_1;
                    $("#total_fee_div_1").html(total_amt);
                    $("#hostel_amt_1").html(HostelFee_1);
                    $("#hostel_pay_1").val(0);
                    $("#hostel_paid_1").html(0);
                    $("#hostel_balance_1").html(HostelFee_1);
                    $("#balance_fee_div_1").html(HostelFee_1 + parseInt($("#tuition_balance_1").html()) + parseInt($(
                        "#other_balance_1").html()));
                    $("#paying_fee_div_1").html(parseInt($("#tuition_pay_1").val()) + parseInt($("#other_pay_1")
                        .val()));
                    $("#hostel_card_1").removeClass('backColor');
                    $("#hostel_pay_1").removeAttr('disabled');
                } else if ($(element).attr('id') == 'hostel_check_2') {
                    theHostel_2 = true;
                    total_amt = parseInt($("#total_fee_div_2").html());
                    total_amt = total_amt + HostelFee_2;
                    $("#total_fee_div_2").html(total_amt);
                    $("#hostel_amt_2").html(HostelFee_2);
                    $("#hostel_pay_2").val(0);
                    $("#hostel_paid_2").html(0);
                    $("#hostel_balance_2").html(HostelFee_2);
                    $("#balance_fee_div_2").html(HostelFee_2 + parseInt($("#tuition_balance_2").html()) + parseInt($(
                        "#other_balance_2").html()));
                    $("#paying_fee_div_2").html(parseInt($("#tuition_pay_2").val()) + parseInt($("#hostel_pay_2")
                            .val()) +
                        parseInt($("#other_pay_2").val()));
                    $("#hostel_pay_2").removeAttr('disabled');
                    $("#hostel_card_2").removeClass('backColor');
                } else if ($(element).attr('id') == 'hostel_check_3') {
                    theHostel_3 = true;
                    total_amt = parseInt($("#total_fee_div_3").html());
                    total_amt = total_amt + HostelFee_3;
                    $("#total_fee_div_3").html(total_amt);
                    $("#hostel_amt_3").html(HostelFee_3);
                    $("#hostel_pay_3").val(0);
                    $("#hostel_paid_3").html(0);
                    $("#hostel_balance_3").html(HostelFee_3);
                    $("#balance_fee_div_3").html(HostelFee_3 + parseInt($("#tuition_balance_3").html()) + parseInt($(
                        "#other_balance_3").html()));
                    $("#paying_fee_div_3").html(parseInt($("#tuition_pay_3").val()) + parseInt($("#hostel_pay_3")
                            .val()) +
                        parseInt($("#other_pay_3").val()));
                    $("#hostel_pay_3").removeAttr('disabled');
                    $("#hostel_card_3").removeClass('backColor');
                } else if ($(element).attr('id') == 'hostel_check_4') {
                    theHostel_4 = true;
                    total_amt = parseInt($("#total_fee_div_4").html());
                    total_amt = total_amt + HostelFee_4;
                    $("#total_fee_div_4").html(total_amt);
                    $("#hostel_amt_4").html(HostelFee_4);
                    $("#hostel_pay_4").val(0);
                    $("#hostel_paid_4").html(0);
                    $("#hostel_balance_4").html(HostelFee_4);
                    $("#balance_fee_div_4").html(HostelFee_4 + parseInt($("#tuition_balance_4").html()) + parseInt($(
                        "#other_balance_4").html()));
                    $("#paying_fee_div_4").html(parseInt($("#tuition_pay_4").val()) + parseInt($("#hostel_pay_3")
                            .val()) +
                        parseInt($("#other_pay_4").val()));
                    $("#hostel_pay_4").removeAttr('disabled');
                    $("#hostel_card_4").removeClass('backColor');
                }

            } else {
                $(element).attr('checked', true);

                if ($(element).attr('id') == 'hostel_check_1') {
                    theHostel_1 = false;
                    total_amt = parseInt($("#total_fee_div_1").html());
                    total_amt = total_amt - HostelFee_1;
                    $("#total_fee_div_1").html(total_amt);
                    $("#hostel_amt_1").html(0);
                    $("#hostel_pay_1").val(0);
                    $("#hostel_paid_1").html(0);
                    $("#hostel_balance_1").html(0);
                    $("#balance_fee_div_1").html(parseInt($("#tuition_balance_1").html()) + parseInt($(
                        "#other_balance_1").html()));
                    $("#paying_fee_div_1").html(parseInt($("#tuition_pay_1").val()) + parseInt($("#other_pay_1")
                        .val()));
                    $("#hostel_card_1").addClass('backColor');
                    $("#hostel_pay_1").attr('disabled', true);
                } else if ($(element).attr('id') == 'hostel_check_2') {
                    theHostel_2 = false;
                    total_amt = parseInt($("#total_fee_div_2").html());
                    total_amt = total_amt - HostelFee_2;
                    $("#total_fee_div_2").html(total_amt);
                    $("#hostel_amt_2").html(0);
                    $("#hostel_pay_2").val(0);
                    $("#hostel_paid_2").html(0);
                    $("#hostel_balance_2").html(0);
                    $("#balance_fee_div_2").html(parseInt($("#tuition_balance_2").html()) + parseInt($(
                        "#other_balance_2").html()));
                    $("#paying_fee_div_2").html(parseInt($("#tuition_pay_2").val()) + parseInt($("#other_pay_2")
                        .val()));
                    $("#hostel_card_2").addClass('backColor');
                    $("#hostel_pay_2").attr('disabled', true);
                } else if ($(element).attr('id') == 'hostel_check_3') {
                    theHostel_3 = false;
                    total_amt = parseInt($("#total_fee_div_3").html());
                    total_amt = total_amt - HostelFee_3;
                    $("#total_fee_div_3").html(total_amt);
                    $("#hostel_amt_3").html(0);
                    $("#hostel_pay_3").val(0);
                    $("#hostel_paid_3").html(0);
                    $("#hostel_balance_3").html(0);
                    $("#balance_fee_div_3").html(parseInt($("#tuition_balance_3").html()) + parseInt($(
                        "#other_balance_3").html()));
                    $("#paying_fee_div_3").html(parseInt($("#tuition_pay_3").val()) + parseInt($("#other_pay_3")
                        .val()));
                    $("#hostel_pay_3").attr('disabled', true);
                    $("#hostel_card_3").addClass('backColor');
                } else if ($(element).attr('id') == 'hostel_check_4') {
                    theHostel_4 = false;
                    total_amt = parseInt($("#total_fee_div_4").html());
                    total_amt = total_amt - HostelFee_4;
                    $("#total_fee_div_4").html(total_amt);
                    $("#hostel_amt_4").html(0);
                    $("#hostel_pay_4").val(0);
                    $("#hostel_paid_4").html(0);
                    $("#hostel_balance_4").html(0);
                    $("#balance_fee_div_4").html(parseInt($("#tuition_balance_4").html()) + parseInt($(
                        "#other_balance_4").html()));
                    $("#paying_fee_div_4").html(parseInt($("#tuition_pay_4").val()) + parseInt($("#other_pay_4")
                        .val()));
                    $("#hostel_card_4").addClass('backColor');
                    $("#hostel_pay_4").attr('disabled', true);
                }
            }
            // } else {
            //     if ($(element).prop("checked")) {
            //         $(element).removeAttr('checked');
            //         if ($(element).attr('id') == 'hostel_check_1') {
            //             theHostel_1 = true;
            //             total_amt = parseInt($("#total_fee_div_1").html());
            //             total_amt = total_amt + HostelFee_1;
            //             $("#total_fee_div_1").html(total_amt);
            //             $("#hostel_amt_1").html(HostelFee_1);
            //             $("#hostel_pay_1").val(0);
            //             $("#hostel_paid_1").html(0);
            //             $("#hostel_balance_1").html(HostelFee_1);
            //             $("#balance_fee_div_1").html(HostelFee_1 + parseInt($("#tuition_balance_1").html()) + parseInt($(
            //                 "#other_balance_1").html()));
            //             $("#paying_fee_div_1").html(parseInt($("#tuition_pay_1").html()) + parseInt($("#hostel_pay_1").html()) + parseInt($("#other_pay_1").html()));
            //             $("#hostel_card_1").removeClass('backColor');
            //             $("#hostel_pay_1").removeAttr('disabled');
            //         } else if ($(element).attr('id') == 'hostel_check_2') {
            //             theHostel_2 = true;
            //             total_amt = parseInt($("#total_fee_div_2").html());
            //             total_amt = total_amt + HostelFee_2;
            //             $("#total_fee_div_2").html(total_amt);
            //             $("#hostel_amt_2").html(HostelFee_2);
            //             $("#hostel_pay_2").val(0);
            //             $("#hostel_paid_2").html(0);
            //             $("#hostel_balance_2").html(HostelFee_2);
            //             $("#balance_fee_div_2").html(HostelFee_2 + parseInt($("#tuition_balance_2").html()) + parseInt($(
            //                 "#other_balance_2").html()));
            //             $("#paying_fee_div_2").html(parseInt($("#tuition_pay_2").val()) + parseInt($("#hostel_pay_2")
            //                 .html()) + parseInt($("#other_pay_2").html()));
            //             $("#hostel_pay_2").removeAttr('disabled');
            //             $("#hostel_card_2").removeClass('backColor');
            //         } else if ($(element).attr('id') == 'hostel_check_3') {
            //             theHostel_3 = true;
            //             total_amt = parseInt($("#total_fee_div_3").html());
            //             total_amt = total_amt + HostelFee_3;
            //             $("#total_fee_div_3").html(total_amt);
            //             $("#hostel_amt_3").html(HostelFee_3);
            //             $("#hostel_pay_3").val(0);
            //             $("#hostel_paid_3").html(0);
            //             $("#hostel_balance_3").html(HostelFee_3);
            //             $("#balance_fee_div_3").html(HostelFee_3 + parseInt($("#tuition_balance_3").html()) + parseInt($(
            //                 "#other_balance_3").html()));
            //             $("#paying_fee_div_3").html(parseInt($("#tuition_pay_3").html()) + parseInt($("#hostel_pay_3")
            //                 .html()) + parseInt($("#other_pay_3").html()));
            //             $("#hostel_pay_3").removeAttr('disabled');
            //             $("#hostel_card_3").removeClass('backColor');
            //         } else if ($(element).attr('id') == 'hostel_check_4') {
            //             theHostel_4 = true;
            //             total_amt = parseInt($("#total_fee_div_4").html());
            //             total_amt = total_amt + HostelFee_4;
            //             $("#total_fee_div_4").html(total_amt);
            //             $("#hostel_amt_4").html(HostelFee_4);
            //             $("#hostel_pay_4").val(0);
            //             $("#hostel_paid_4").html(0);
            //             $("#hostel_balance_4").html(HostelFee_4);
            //             $("#balance_fee_div_4").html(HostelFee_4 + parseInt($("#tuition_balance_4").html()) + parseInt($(
            //                 "#other_balance_4").html()));
            //             $("#paying_fee_div_4").html(parseInt($("#tuition_pay_4").html()) + parseInt($("#hostel_pay_4")
            //                 .html()) + parseInt($("#other_pay_4").html()));
            //             $("#hostel_pay_4").removeAttr('disabled');
            //             $("#hostel_card_4").removeClass('backColor');
            //         }

            //     } else {
            //         $(element).attr('checked', true);

            //         if ($(element).attr('id') == 'hostel_check_1') {
            //             theHostel_1 = false;
            //             total_amt = parseInt($("#total_fee_div_1").html());
            //             total_amt = total_amt - HostelFee_1;
            //             $("#total_fee_div_1").html(total_amt);
            //             $("#hostel_amt_1").html(0);
            //             $("#hostel_pay_1").val(0);
            //             $("#hostel_paid_1").html(0);
            //             $("#hostel_balance_1").html(0);
            //             $("#balance_fee_div_1").html(parseInt($("#tuition_balance_1").html()) + parseInt($(
            //                 "#other_balance_1").html()));
            //             $("#paying_fee_div_1").html(parseInt($("#tuition_pay_1").html()) + parseInt($("#hostel_pay_1")
            //                 .html()) + parseInt($("#other_pay_1").html()));
            //             $("#hostel_card_1").addClass('backColor');
            //             $("#hostel_pay_1").attr('disabled', true);
            //         } else if ($(element).attr('id') == 'hostel_check_2') {
            //             theHostel_2 = false;
            //             total_amt = parseInt($("#total_fee_div_2").html());
            //             total_amt = total_amt - HostelFee_2;
            //             $("#total_fee_div_2").html(total_amt);
            //             $("#hostel_amt_2").html(0);
            //             $("#hostel_pay_2").val(0);
            //             $("#hostel_paid_2").html(0);
            //             $("#hostel_balance_2").html(0);
            //             $("#balance_fee_div_2").html(parseInt($("#tuition_balance_2").html()) + parseInt($(
            //                 "#other_balance_2").html()));
            //             $("#paying_fee_div_2").html(parseInt($("#tuition_pay_2").val()) + parseInt($("#hostel_pay_2")
            //                 .html()) + parseInt($("#other_pay_2").html()));
            //             $("#hostel_card_2").addClass('backColor');
            //             $("#hostel_pay_2").attr('disabled', true);
            //         } else if ($(element).attr('id') == 'hostel_check_3') {
            //             theHostel_3 = false;
            //             total_amt = parseInt($("#total_fee_div_3").html());
            //             total_amt = total_amt - HostelFee_3;
            //             $("#total_fee_div_3").html(total_amt);
            //             $("#hostel_amt_3").html(0);
            //             $("#hostel_pay_3").val(0);
            //             $("#hostel_paid_3").html(0);
            //             $("#hostel_balance_3").html(0);
            //             $("#balance_fee_div_3").html(parseInt($("#tuition_balance_3").html()) + parseInt($(
            //                 "#other_balance_3").html()));
            //             $("#paying_fee_div_3").html(parseInt($("#tuition_pay_3").html()) + parseInt($("#hostel_pay_3")
            //                 .html()) + parseInt($("#other_pay_3").html()));
            //             $("#hostel_pay_3").attr('disabled', true);
            //             $("#hostel_card_3").addClass('backColor');
            //         } else if ($(element).attr('id') == 'hostel_check_4') {
            //             theHostel_4 = false;
            //             total_amt = parseInt($("#total_fee_div_4").html());
            //             total_amt = total_amt - HostelFee_4;
            //             $("#total_fee_div_4").html(total_amt);
            //             $("#hostel_amt_4").html(0);
            //             $("#hostel_pay_4").val(0);
            //             $("#hostel_paid_4").html(0);
            //             $("#hostel_balance_4").html(0);
            //             $("#balance_fee_div_4").html(parseInt($("#tuition_balance_4").html()) + parseInt($(
            //                 "#other_balance_4").html()));
            //             $("#paying_fee_div_4").html(parseInt($("#tuition_pay_4").html()) + parseInt($("#hostel_pay_4")
            //                 .html()) + parseInt($("#other_pay_4").html()));
            //             $("#hostel_card_4").addClass('backColor');
            //             $("#hostel_pay_4").attr('disabled', true);
            //         }
            //     }

            // }
        }
    </script>
@endsection
