@extends('layouts.admin')
@section('content')
    <style>
        .fully-paid {
            color: green;
        }

        .select2-container {
            width: 100% !important;
        }

        .pending {
            color: red;
        }

        #payment_history {
            margin-left: 600%;
        }

        #loading {
            z-index: 9999999;
        }

        .error {
            color: red;
        }
    </style>
    @php
        $feeCycleText = $feeCycles[0];

    @endphp
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="row">
        <div class="form-group col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
            <div class="row">
                <div class="col-md-9">
                    <select name="reg_no" id="reg_no" class="form-control select2" style="font-size: 18px;"
                        onchange="getdetails()">
                        <option value="">Select Student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->register_no }}">{{ $student->name }} ({{ $student->register_no }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-warning" id="payment_history" data-toggle="modal"
                        data-target="#paymentHistoryModal">Payment History</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentHistoryModal" tabindex="-1" role="dialog"
        aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentHistoryModalLabel">Payment History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered" id="fee_history_table">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">S.No</th>
                                <th scope="col">Receipt No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">
                                    @if ($feeCycleText == 'SemesterWise')
                                        Semester
                                    @elseif($feeCycleText == 'YearlyWise')
                                        Academic Year
                                    @elseif($feeCycleText == 'CustomsWise')
                                        Fee Cycle
                                    @endif
                                </th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col" style="display: none;">Transaction Id</th>
                                <th scope="col">Type</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-center">
            Student Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-1">
                    <p>Name</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="name" style="font-weight: bold;"></p>
                </div>
                <div class="col-md-1">
                    <p>Batch</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="batch" style="font-weight: bold;"></p>
                </div>
                <div class="col-md-1">
                    <p>Course</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="course" style="font-weight: bold;"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <p>Semester</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="semester" style="font-weight: bold;"></p>
                </div>
                <div class="col-md-1">
                    <p>Section</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="section" style="font-weight: bold;"></p>
                </div>
                <div class="col-md-1">
                    <p>Phone No</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="phone_no" style="font-weight: bold;"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <p>Scholar</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="scholarship_yes_or_no" style="font-weight: bold;"></p>
                </div>
                <div class="col-md-1">
                    <p>Hostel</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="hostel" style="font-weight: bold;"></p>
                </div>
                <div class="col-md-1">
                    <p>Transport</p>
                </div>
                <div class="col-md-1">
                    <p>:</p>
                </div>
                <div class="col-md-2">
                    <p id="transport" style="font-weight: bold;"></p>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header text-center">
            Fee Details
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered" id="feeDetailsTable">
                <thead>
                    <tr class="text-center">
                        <th scope="col">
                            @if ($feeCycleText == 'SemesterWise')
                                Semester
                            @elseif($feeCycleText == 'YearlyWise')
                                Academic Year
                            @elseif($feeCycleText == 'CustomsWise')
                                Fee Cycle
                            @endif
                        </th>
                        <th>Total Amount</th>
                        <th>Paid</th>
                        <th>Pending</th>
                        <th>Status</th>
                        <th style="display: none;">Id</th>
                        <th>Action</th>
                        <th style="display: none;">ACA Y</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text center">
                    <h5 class="modal-title" id="myModalLabel">Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row gutters">
                        <div class="form-group col-12">
                            <label>Select Payment Type:</label>
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 form-check">
                                    <input class="form-check-input" type="radio" name="payment_type" id="fees_payment"
                                        value="fees" checked>
                                    <label class="form-check-label" for="fees_payment">
                                        Fee Payment
                                    </label>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 form-check">
                                    <input class="form-check-input" type="radio" name="payment_type"
                                        id="scholarship_payment" value="scholarship">
                                    <label class="form-check-label" for="scholarship_payment">
                                        Scholarship Payment
                                    </label>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 form-check">
                                    <input class="form-check-input" type="radio" name="payment_type"
                                        id="discount_payment" value="discount">
                                    <label class="form-check-label" for="discount_payment">
                                        Discount Payment
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" id="student_id">
                        <input type="hidden" id="student_name">
                        <div class="row gutters">

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" style="display: none;"
                                id="scholarship_name_details_div">
                                <label for="scholarship_name_details">Scholarship Name And Details</label>
                                <input type="text" id="scholarship_name_details" class="form-control" readonly>
                            </div>

                            <div style="display: none;" class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group"
                                id="reg_no_div">
                                <label for="register_no">Regsiter Number</label>
                                <input type="text" id="register_no" class="form-control" readonly>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" style="display: none;"
                                id="scholar_details_div">
                                <label for="scholar_details">Scholar Details</label>
                                <input type="text" id="scholar_details" class="form-control" readonly>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" style="display: none;"
                                id="total_amountsss_div">
                                <label for="total_amountsss">Total Amount</label>
                                <input type="text" id="total_amountsss" class="form-control" readonly>
                            </div>


                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" style="display: none;"
                                id="payable_amount_div">
                                <label for="payable_amo bunt">Scholarship Amount</label>
                                <input type="text" id="payable_amount" class="form-control" readonly>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" style="display: none;"
                                id="discount_amount_div">
                                <label for="discount_amount">Discount Amount <span class="text-danger">*</span></label>
                                <input type="text" id="discount_amount" class="form-control"
                                    placeholder="Enter Discount Amount">
                                <span id="discount_amount_error" class="error"></span>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" style="display: none;"
                                id="discount_remark_div">
                                <label for="discount_remark">Discount Remarks</label>
                                <input type="text" id="discount_remark" class="form-control"
                                    placeholder="Enter Discount Remarks">
                            </div>

                        </div>

                        <input type="hidden" id="semester_idss">
                        <input type="hidden" id="academic_year_idss">
                        <input type="hidden" id="customs_idss">
                        <input type="hidden" id="stu_fees_id">
                    </div>
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="applicable_fee_div">
                            <label for="applicable_fee" class="required">Applicable Fee</label>
                            <select class="form-control select2" id="applicable_fee" name="applicable_fee"
                                value="" onchange="applicable_fee()">
                                <option value="">Select Fee</option>
                                <option value="academic_fee">Academic Fee</option>
                                <option value="hostel_fee">Hostel Fee</option>
                                <option value="transport_fee">Transport Fee</option>
                            </select>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="paid_amount_div">
                            <label for="paid_amount">Academic Fee<span class="text-danger">*</span></label>
                            <input type="number" placeholder="Enter Academic Fee" id="paid_amount"
                                class="form-control">
                            <span id="paid_amount_error" class="error"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="hostel_amount_div"
                            style="display: none;">
                            <label for="hostel_amount">Hostel Fee<span class="text-danger">*</span></label>
                            <input type="number" placeholder="Enter Hostel Fee" id="hostel_amount"
                                class="form-control">
                            <span id="hostel_amount_error" class="error"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="payment_mode_div">
                            <label for="payment_mode" class="required">Payment Mode</label>
                            <select class="form-control select2" id="payment_mode" name="payment_mode" value=""
                                onchange="payment_mode()">
                                <option value="">Select Payment Mode</option>
                                @foreach ($payment_mode as $id => $payment_md)
                                    <option value="{{ $payment_md }}">{{ $payment_md }}</option>
                                @endforeach
                            </select>
                            <span id="payment_mode_error" class="error"></span>
                        </div>


                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="remark_details_div">
                            <label for="remark_details">Academic Fee Remark</label>
                            <input type="text" placeholder="Enter Academic Fee Remark" id="remark_details"
                                class="form-control">
                        </div>


                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="cheque_number_div">
                            <label for="cheque_number">Cheque Number<span class="text-danger">*</span></label>
                            <input type="text" placeholder="Enter Cheque Number" id="cheque_number"
                                class="form-control">
                            <span id="cheque_number_error" class="error"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="dd_div">
                            <label for="dd">DD Number<span class="text-danger">*</span></label>
                            <input type="text" placeholder="Enter DD Number" id="dd" class="form-control">
                            <span id="dd_error" class="error"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="reference_number_div">
                            <label for="reference_number">Reference Number<span class="text-danger">*</span></label>
                            <input type="text" placeholder="Enter Reference Number" id="reference_number"
                                class="form-control">
                            <span id="reference_number_error" class="error"></span>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="pay_now" onclick="pay_now()">Pay Now</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    @parent
    <script>
        var feeDetails = [];

        $(document).on('click', '#payButton', function() {
            $('#myModal').modal('show');

            $('#myModal').on('show.bs.modal', function(e) {
                $('#fees_payment').prop('checked', true);
            });

            $('#paid_amount').show();
            $('#remark_details').show();
            $("#pay_now").show();
            $('#paid_amount_error').show();
            $("#paid_amount").val('');
            $("#reg_no_div").hide();
            $("#scholar_details_div").hide();
            $("#total_amountsss_div").hide();
            $("#payable_amount_div").hide();
            $("#discount_amount_div").hide();
            $("#discount_remark_div").hide()
            $("#check_no_div").hide()
            $("#paid_amount_div").hide();
            $("#remark_details_div").hide();
            $("#pay_now").hide();
            $("#cheque_number_div").hide();
            $("#dd_div").hide();
            $("#applicable_fee").val('').select2();
            $("#payment_mode").val('').select2();
            $("#remark_details_div").hide();
            $("#reference_number_div").hide();





            var feeCycle = '{{ $feeCycleText }}';
            if (feeCycle == 'SemesterWise') {

                var row = $(this).closest('tr');
                var semester_idss = row.find('td:nth-child(1)').text().trim();
                var total_amount = row.find('td:nth-child(2)').text().trim();
                var fees_id = row.find('td:nth-child(6)').text().trim();

                $('#total_amountsss').val(total_amount);
                $('#semester_idss').val(semester_idss);
                $('#stu_fees_id').val(fees_id);

                $("#paid_amount").val('');
                $("#remark_details").val('')

            } else if (feeCycle == 'YearlyWise') {
                var row = $(this).closest('tr');
                var academic_year_idss = row.find('td:nth-child(1)').text().trim();
                var total_amount = row.find('td:nth-child(2)').text().trim();
                var fees_id = row.find('td:nth-child(6)').text().trim();

                $('#total_amountsss').val(total_amount);
                $('#academic_year_idss').val(academic_year_idss);
                $('#stu_fees_id').val(fees_id);

                $("#paid_amount").val('');
                $("#remark_details").val('')

            } else if (feeCycle == 'CustomsWise') {
                var row = $(this).closest('tr');
                var customs_idss = row.find('td:nth-child(1)').text().trim();
                var total_amount = row.find('td:nth-child(2)').text().trim();
                var fees_id = row.find('td:nth-child(6)').text().trim();
                var acad = row.find('td:nth-child(8)').text().trim();

                $('#total_amountsss').val(total_amount);
                $('#customs_idss').val(customs_idss);
                $('#stu_fees_id').val(fees_id);
                $('#academic_year_idss').val(acad);

                $("#paid_amount").val('');
                $("#remark_details").val('')

            }

        });

        function applicable_fee() {

            $("#payment_mode").val('')

            var applicable_fee = $("#applicable_fee").val();

            if (applicable_fee == 'academic_fee') {
                console.log("Condition met for academic_fee, displaying relevant elements.");
                $("#pay_now").show();
                $("#hostel_amount_div").hide();
                $("#paid_amount_div").show();
                $("#cheque_number_div").hide();
                $("#dd_div").hide();
            } else if (applicable_fee == 'hostel_fee') {
                $("#pay_now").show();
                $("#hostel_amount_div").show();
                $("#paid_amount_div").hide();
                $("#remark_details_div").hide();
                $("#cheque_number_div").hide();
                $("#dd_div").hide();
            } else {
                $("#pay_now").hide();
                $("#hostel_amount_div").hide();
                $("#paid_amount_div").hide();
                $("#remark_details_div").hide();
                $("#cheque_number_div").hide();
                $("#dd_div").hide();
            }
        }




        function payment_mode() {

            var payment_mode = $("#payment_mode").val();
            if (payment_mode == 'CHEQUE') {
                $("#cheque_number_div").show();
                $("#remark_details_div").hide();
                $("#reference_number_div").hide();
                $("#dd_div").hide();


            } else if (payment_mode == 'DD') {
                $("#dd_div").show();
                $("#remark_details_div").hide();
                $("#cheque_number_div").hide();
                $("#reference_number_div").hide();
            } else if (payment_mode == 'CASH') {
                $("#remark_details_div").show();
                $("#cheque_number_div").hide();
                $("#reference_number_div").hide();
                $("#dd_div").hide();
            } else if (payment_mode == 'BANK TRANSFER') {
                $("#remark_details_div").hide();
                $("#cheque_number_div").hide();
                $("#reference_number_div").show();
                $("#dd_div").hide();
            } else if (payment_mode == 'UPI - ONLINE PAYMENT') {
                $("#remark_details_div").hide();
                $("#cheque_number_div").hide();
                $("#reference_number_div").show();
                $("#dd_div").hide();
            } else {
                $("#cheque_number_div").hide();
                $("#remark_details_div").hide();
                $("#dd_div").hide();
                $("#reference_number_div").hide();

            }

        }


        $('input[name="payment_type"]').change(function() {

            if ($('#scholarship_payment').is(':checked')) {

                $('#loading').show();
                $('#paid_amount_div').hide();
                $('#remark_details_div').hide();
                $('#paid_amount_error').hide();
                $("#paid_amount").val('');

                $("#discount_amount_div").hide();
                $("#discount_remark_div").hide();
                $("#check_no_div").show();
                $("#applicable_fee_div").show();
                $("#payment_mode_div").show()
                $("#cheque_number_div").hide();
                $("#reference_number_div").hide();
                $("#dd_div").hide();
                $("#payment_mode").val('').select2();
                $("#hostel_amount_div").hide();






                var register_no_scholar = $("#register_no").val();
                var semesters_no = $("#semester_idss").val();
                var academic_year_no = $("#academic_year_idss").val();
                var customs_idss = $("#customs_idss").val();
                // alert(academic_year_no);

                $.ajax({
                    url: '{{ route('admin.student-scholarship') }}',
                    type: 'POST',
                    data: {
                        'register_no_scholar': register_no_scholar,
                        'semesters_no': semesters_no,
                        'academic_year_no': academic_year_no,
                        'customs_idss': customs_idss,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        let status = response.status;
                        let scholarshipDetails = response.data;
                        let scholarshipDetails1 = "";

                        // Check if response.scholarship exists and foundation_name is defined
                        if (response.scholarship && response.scholarship.foundation_name) {
                            scholarshipDetails1 = response.scholarship.foundation_name + " - " +
                                scholarshipDetails;
                        } else {
                            $("#scholarship_name_details_div").hide();
                        }

                        if (status) {
                            $("#pay_now").show();


                            // $("#scholar_details_div").show();
                            // $("#total_amountsss_div").show();
                            // $("#reg_no_div").show();
                            $("#paid_amount_div").hide()
                            $("#payable_amount_div").show();
                            $("#scholar_details").val(scholarshipDetails)
                            $("#scholarship_name_details_div").show();
                            $("#scholarship_name_details").val(scholarshipDetails1);

                            var lastChar = scholarshipDetails.trim().slice(-1);
                            // alert(lastChar)

                            if (lastChar === '%') {
                                var tot_amt = parseFloat($("#total_amountsss").val());
                                var schol = parseFloat(scholarshipDetails.replace('%', ''));
                                var paybale_amount = (schol / 100) * tot_amt;
                                // alert(paybale_amount)
                                $("#payable_amount").val(paybale_amount);
                                // alert(scholarshipDetails)
                                // alert(tot_amt)
                            } else {

                                var schol = parseFloat(scholarshipDetails.replace('%', ''));
                                $("#payable_amount").val(schol);
                            }

                        } else {
                            $("#scholar_details_div").hide();
                            $("#reg_no_div").hide();
                            $("#total_amountsss_div").hide()
                            $("#payable_amount_div").hide();
                            $("#scholarship_name_details_div").hide();
                            $("#pay_now").hide();
                            Swal.fire('', response.data, 'error');
                        }

                        $('#loading').hide();

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        let errorMessage = textStatus || errorThrown || 'Request Failed';
                        Swal.fire('', errorMessage, 'error');

                        $('#loading').hide();

                    }

                })

            } else if ($('#discount_payment').is(':checked')) {

                $("#payable_amount_div").hide();
                $("#total_amountsss_div").hide();
                $("#scholar_details_div").hide();
                $("#reg_no_div").hide();
                $('#paid_amount_div').hide();
                $("#scholarship_name_details_div").hide();
                $('#remark_details_div').hide();
                $('#paid_amount_error').hide();
                $("#paid_amount").val('');
                $("#applicable_fee_div").hide();
                $("#cheque_number_div").hide();
                $("#hostel_amount_div").hide();


                $("#discount_amount_div").show();
                $("#discount_remark_div").show();
                $("#pay_now").show();
                $("#check_no_div").hide();
                $("#payment_mode_div").hide()
                $("#dd_div").hide();
                $("#reference_number_div").hide();


            } else {

                $('#paid_amount_div').hide();
                $("#hostel_amount_div").hide();
                $('#remark_details_div').hide();
                $("#scholarship_name_details_div").hide();
                $('#paid_amount_error').show();
                $("#scholar_details_div").hide();
                $("#total_amountsss_div").hide()
                $("#payable_amount_div").hide();
                $("#reg_no_div").hide();
                $("#pay_now").show();
                $("#check_no_div").hide();

                $("#discount_amount_div").hide();
                $("#discount_remark_div").hide();



                $("#pay_now").hide();
                $("#applicable_fee_div").show();
                $("#payment_mode_div").show()
                $("#applicable_fee").val('').select2();
                $("#payment_mode").val('').select2();

                $("#dd_div").hide();

                $("#cheque_number_div").hide();
                $("#reference_number_div").hide();

                // $("#hostel_amount_div").show();


            }

        })


        $('#payment_history').hide();

        var reg_no = '';

        function getdetails() {


            $('#loading').show();
            var reg_no = $("#reg_no").val();

            var feeCycle = '{{ $feeCycleText }}';


            $.ajax({
                url: '{{ route('admin.student-rollnumber.geter') }}',
                type: 'POST',
                data: {
                    'reg_no': reg_no
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);

                    let status = response.status;
                    if (status) {
                        $("#student_id").val(response.student_id);
                        $("#student_name").val(response.name);
                        $("#register_no").val(response.register_no);
                        $("#name").text(response.name);
                        $("#course").text(response.short_form);
                        $("#batch").text(response.batch);
                        $("#semester").text(response.semester);
                        $("#section").text(response.section);
                        $("#phone_no").text(response.phone_no);
                        $("#scholarship_yes_or_no").text(response.scholar)

                        feeDetails = response.fee_details;

                        $('#feeDetailsTable tbody').empty();
                        if (feeDetails == '') {
                            $('#payment_history').hide();
                            let message =
                                '<tr><td colspan="6" style="text-align: center; font-size: 20px; color:red;">Fee Not Found..!</td></tr>';
                            $('#feeDetailsTable tbody').html(message);
                        }

                        $.each(feeDetails, function(index, details) {

                            let firstColumnValue;
                            if (feeCycle === 'YearlyWise') {
                                firstColumnValue = details.academic_year_name;
                            } else if (feeCycle === 'SemesterWise') {

                                firstColumnValue = details.semester;
                            } else {
                                firstColumnValue = details.fee_name;
                            }

                            var amountssss = details.amount;
                            var id = details.id;

                            $("#total_amount_summary").text(amountssss)

                            let row = `<tr>
                        <td>${firstColumnValue}</td>
                        <td>${amountssss}</td>
                        <td></td>
                        <td><!-- Pending amount --></td>
                        <td><!-- Status --></td>
                        <td style="display: none;">${id}</td>


                        <td>
                            <button class="newViewBtn" title="Pay" id="payButton">
                                <i class="fas fa-rupee-sign" style="font-size:22px;"></i>
                            </button>
                        </td>
                        <td style="display: none;">${details.academic_year_name}</td>

                    </tr>`;
                            $('#feeDetailsTable tbody').append(row);
                            $('#payment_history').show();
                        });
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = textStatus || errorThrown || 'Request Failed';
                    Swal.fire('', errorMessage, 'error');
                }
            });

            $.ajax({
                url: '{{ route('admin.fee_history') }}',
                type: 'POST',
                data: {
                    'reg_no': reg_no
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#loading').hide();
                    let status = response.status;
                    if (status) {

                        $("#fee_history_table tbody").empty();


                        let updatedSemesters = [];
                        let academicYearUpdate = [];
                        let customsUpdate = [];

                        $.each(response.data, function(index, fee) {

                            let semesterOrYear;
                            if (feeCycle === 'YearlyWise') {
                                semesterOrYear = fee.academic_year_id;
                            } else if (feeCycle === 'SemesterWise') {
                                semesterOrYear = fee.semester;
                            } else {
                                semesterOrYear = fee.fee_name;
                            }

                            let tablerow = `<tr>
                        <td scope="row">${index + 1}</td>
                        <td scope="row">${fee.receipt_no}</td>
                        <td scope="row">${fee.student_name}</td>
                        <td scope="row">${fee.paid_date}</td>
                        <td scope="row">${semesterOrYear}</td>
                        <td scope="row">${fee.paid_amount}</td>
                        <td scope="row">${fee.status}</td>
                        <td scope="row" style="display:none;">${fee.transaction_id}</td>
                        <td scope="row">${fee.payment_type}</td>
                        <td>
                            <button class="btn btn-secondary btn-xs btn-outline-secondary" title="View Receipt" id="view_receipt" target="_blank"  onclick="view_receipt('${fee.transaction_id}')">View</button>
                            <button class="btn btn-danger btn-xs btn-outline-danger" title="Delete Payment" id="delete_payment" onclick="delete_payment(this)">Delete</button>
                        </td>
                        </tr>`;
                            $('#fee_history_table tbody').append(tablerow);

                            var fee_status = fee.status;

                            if (fee_status == 'deleted') {
                                $("#fee_history_table tbody tr").each(function() {
                                    var status11 = $(this).find('td:nth-child(7)').text()
                                        .trim();
                                    if (status11 == 'deleted') {

                                        $(this).css({
                                            "background-color": "#e95959",
                                            "color": "white"
                                        });
                                        $(this).find("#delete_payment, #view_receipt").hide();
                                        // $(this).find('td:nth-child(9)').text('Deleted By Admin');
                                    } else {
                                        $(this).find("#delete_payment, #view_receipt").show();
                                    }
                                });
                            }

                            $(`#feeDetailsTable tbody tr`).each(function() {


                                if (feeCycle == 'SemesterWise') {

                                    var semesterssId = $(this).find('td:first').text().trim();

                                    var $tdThird = $(this).find('td:nth-child(3)');
                                    if (semesterssId == fee.semester) {
                                        var totals = parseInt(fee.total_paid_amount);
                                        $tdThird.text(totals);
                                        $('#total_paid_amount_summary').text(totals)
                                        updatedSemesters.push(semesterssId);
                                    }

                                    var currentAmount1 = parseInt($(this).find(
                                            'td:nth-child(2)')
                                        .text().trim());
                                    var paid_amount1 = parseInt($tdThird.text().trim());
                                    var pendingAmount1 = currentAmount1 - paid_amount1;
                                    $(this).find('td:nth-child(4)').text(pendingAmount1);
                                    $("#pending_amount_summary").text(pendingAmount1);

                                } else if (feeCycle == 'YearlyWise') {

                                    var semesterssId1 = $(this).find('td:first').text().trim();

                                    var $tdThird1 = $(this).find('td:nth-child(3)');
                                    if (semesterssId1 == fee.academic_year_id) {
                                        var totals1 = parseInt(fee.total_paid_amount);
                                        $tdThird1.text(totals1);
                                        $('#total_paid_amount_summary').text(totals1)
                                        academicYearUpdate.push(semesterssId1);
                                    }

                                    var currentAmount11 = parseInt($(this).find(
                                            'td:nth-child(2)')
                                        .text().trim());
                                    var paid_amount11 = parseInt($tdThird1.text().trim());
                                    var pendingAmount11 = currentAmount11 - paid_amount11;
                                    $(this).find('td:nth-child(4)').text(pendingAmount11);
                                    $("#pending_amount_summary").text(pendingAmount11);


                                } else if (feeCycle == 'CustomsWise') {

                                    var semesterssId2 = $(this).find('td:first').text().trim();

                                    var $tdThird2 = $(this).find('td:nth-child(3)');
                                    if (semesterssId2 == fee.fee_name) {
                                        var totals2 = parseInt(fee.total_paid_amount);
                                        $tdThird2.text(totals2);
                                        $('#total_paid_amount_summary').text(totals2)
                                        customsUpdate.push(semesterssId2);
                                    }

                                    var currentAmount111 = parseInt($(this).find(
                                            'td:nth-child(2)')
                                        .text().trim());
                                    var paid_amount111 = parseInt($tdThird2.text().trim());
                                    var pendingAmount111 = currentAmount111 - paid_amount111;
                                    $(this).find('td:nth-child(4)').text(pendingAmount111);
                                    $("#pending_amount_summary").text(pendingAmount111);


                                }


                            });


                        });

                        if (feeCycle == 'SemesterWise') {
                            // Set 3rd child to zero if not updated
                            $(`#feeDetailsTable tbody tr`).each(function() {
                                var semesterssId = $(this).find('td:first').text().trim();
                                if (!updatedSemesters.includes(semesterssId)) {
                                    $(this).find('td:nth-child(3)').text(0);
                                    var currentAmount1 = parseInt($(this).find('td:nth-child(2)').text()
                                        .trim());
                                    $(this).find('td:nth-child(4)').text(currentAmount1);
                                }
                            });
                        } else if (feeCycle == 'YearlyWise') {
                            $(`#feeDetailsTable tbody tr`).each(function() {
                                var semesterssId1 = $(this).find('td:first').text().trim();
                                if (!academicYearUpdate.includes(semesterssId1)) {
                                    $(this).find('td:nth-child(3)').text(0);
                                    var currentAmount11 = parseInt($(this).find('td:nth-child(2)')
                                        .text()
                                        .trim());
                                    $(this).find('td:nth-child(4)').text(currentAmount11);
                                }
                            });

                        } else if (feeCycle == 'CustomsWise') {
                            $(`#feeDetailsTable tbody tr`).each(function() {
                                var semesterssId2 = $(this).find('td:first').text().trim();
                                if (!customsUpdate.includes(semesterssId2)) {
                                    $(this).find('td:nth-child(3)').text(0);
                                    var currentAmount111 = parseInt($(this).find('td:nth-child(2)')
                                        .text()
                                        .trim());
                                    $(this).find('td:nth-child(4)').text(currentAmount111);
                                }
                            });

                        }


                        if (feeCycle == 'SemesterWise') {

                            $(`#feeDetailsTable tbody tr`).each(function() {

                                var statusUpdate = $(this).find('td:nth-child(4)').text().trim();
                                if (statusUpdate == 0 || statusUpdate < 0) {
                                    $(this).find('td:nth-child(5)').text('Fully Paid').addClass(
                                        'fully-paid');
                                    $(this).find('button#payButton').prop('disabled', true).css({
                                        'color': 'black',
                                    });
                                } else {
                                    $(this).find('td:nth-child(5)').text('Pending').addClass('pending');

                                }

                            })

                        } else if (feeCycle == 'YearlyWise') {


                            $(`#feeDetailsTable tbody tr`).each(function() {

                                var statusUpdate1 = $(this).find('td:nth-child(4)').text().trim();

                                if (statusUpdate1 == 0 || statusUpdate1 < 0) {
                                    $(this).find('td:nth-child(5)').text('Fully Paid').addClass(
                                        'fully-paid');
                                    $(this).find('button#payButton').prop('disabled', true).css({
                                        'color': 'black',
                                    });
                                } else {
                                    $(this).find('td:nth-child(5)').text('Pending').addClass('pending');

                                }

                            })

                        } else if (feeCycle == 'CustomsWise') {


                            $(`#feeDetailsTable tbody tr`).each(function() {

                                var statusUpdate2 = $(this).find('td:nth-child(4)').text().trim();

                                if (statusUpdate2 == 0 || statusUpdate2 < 0) {
                                    $(this).find('td:nth-child(5)').text('Fully Paid').addClass(
                                        'fully-paid');
                                    $(this).find('button#payButton').prop('disabled', true).css({
                                        'color': 'black',
                                    });
                                } else {
                                    $(this).find('td:nth-child(5)').text('Pending').addClass('pending');

                                }

                            })

                        }


                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loading').hide();
                    let errorMessage = textStatus || errorThrown || 'Request Failed';
                    Swal.fire('', errorMessage, 'error');
                }
            });
        }

        function pay_now() {


            $('#loading').show();
            var paid_amount = $("#paid_amount").val();
            var remark_details = $("#remark_details").val();
            var student_id = $("#student_id").val();
            var student_name = $("#student_name").val();
            var tot_amount = $("#total_amountsss").val();
            var register_number = $("#register_no").val();
            var sem = $("#semester_idss").val();
            var aca = $("#academic_year_idss").val();
            var fee_idis = $('#stu_fees_id').val();
            var payable_amount = $("#payable_amount").val();
            var discount_amount = $("#discount_amount").val();
            var discount_remark = $("#discount_remark").val();
            // var check_no = $("#check_no").val();
            var customs_idss = $("#customs_idss").val();
            var payment_mode = $("#payment_mode").val();
            var dd = $("#dd").val();
            var cheque_number = $("#cheque_number").val();
            var reference_number = $("#reference_number").val();
            var applicable_fee = $("#applicable_fee").val();
            customs_idss = customs_idss.replace(/\s*\(.*?\)\s*/g, '').trim();


            if ($('#discount_payment').is(':checked')) {

                var discount_amount = $("#discount_amount").val();

                if (discount_amount == '' || isNaN(discount_amount) || parseFloat(discount_amount) <= 0) {
                    $("#discount_amount_error").text('Enter a Valid Amount');
                    $('#loading').hide();
                    return false;
                } else {
                    $("#discount_amount_error").text('');

                }
            } else if ($('#scholarship_payment').is(':checked')) {

                var payment_mode = $("#payment_mode").val();
                if (payment_mode == '') {
                    $("#payment_mode_error").text('Please Select a Payment Mode');
                    $('#loading').hide();
                    return false;
                } else if (payment_mode == 'DD') {

                    var dd = $("#dd").val();
                    if (dd == '') {
                        $("#dd_error").text('Please Enter DD Number');
                        $("#cheque_number_error").text('');
                        $("#payment_mode_error").text('');
                        $('#loading').hide();
                        return false;
                    }

                } else if (payment_mode == 'CHEQUE') {

                    var cheque_number = $("#cheque_number").val();
                    if (cheque_number == '') {
                        $("#cheque_number_error").text('Please Enter Cheque Number');
                        $("#payment_mode_error").text('');
                        $("#dd_error").text('');
                        $("#reference_number_error").text('');
                        $('#loading').hide();
                        return false;
                    }

                } else if (payment_mode == 'UPI - ONLINE PAYMENT' || payment_mode == 'BANK TRANSFER') {

                    var reference_number = $("#reference_number").val();
                    if (reference_number == '') {
                        $("#reference_number_error").text('Please Enter Reference Number');
                        $('#loading').hide();
                        $("#payment_mode_error").text('');
                        return false;
                    }

                } else {
                    $("#payment_mode_error").text('');
                }

            } else if ($('#fees_payment').is(':checked')) {


                var paid_amount = $("#paid_amount").val();
                var payment_mode = $("#payment_mode").val();

                if (paid_amount == '') {

                    $("#paid_amount_error").text('Enter a Valid amount');
                    $('#loading').hide();
                    return false;

                }


                if (payment_mode == '') {
                    $("#payment_mode_error").text('Please Select a Payment Mode');
                    $("#paid_amount_error").text('')
                    $('#loading').hide();
                    return false;
                } else if (payment_mode == 'DD') {

                    var dd = $("#dd").val();
                    if (dd == '') {
                        $("#dd_error").text('Please Enter DD Number');
                        $("#cheque_number_error").text('');
                        $("#payment_mode_error").text('');
                        $('#loading').hide();
                        return false;
                    }

                } else if (payment_mode == 'CHEQUE') {

                    var cheque_number = $("#cheque_number").val();
                    if (cheque_number == '') {
                        $("#cheque_number_error").text('Please Enter Cheque Number');
                        $("#payment_mode_error").text('');
                        $("#dd_error").text('');
                        $('#loading').hide();
                        return false;
                    }

                } else if (payment_mode == 'UPI - ONLINE PAYMENT' || payment_mode == 'BANK TRANSFER') {

                    var reference_number = $("#reference_number").val();
                    if (reference_number == '') {
                        $("#reference_number_error").text('Please Enter Reference Number');
                        $('#loading').hide();
                        $("#payment_mode_error").text('');
                        return false;
                    }

                } else {
                    $("#payment_mode_error").text('');
                }


            }




            $.ajax({

                url: '{{ route('admin.fee_payment') }}',
                type: 'POST',
                data: {

                    'paid_amount': paid_amount,
                    'remark_details': remark_details,
                    'student_id': student_id,
                    'student_name': student_name,
                    'tot_amount': tot_amount,
                    'register_number': register_number,
                    'sem': sem,
                    'aca': aca,
                    'fee_idis': fee_idis,
                    'payable_amount': payable_amount,
                    'discount_amount': discount_amount,
                    'discount_remark': discount_remark,
                    // 'check_no': check_no,
                    'customs_idss': customs_idss,
                    'payment_mode': payment_mode,
                    'dd': dd,
                    'cheque_number': cheque_number,
                    'reference_number': reference_number,
                    'applicable_fee': applicable_fee

                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#loading').hide();
                    let status = response.status;
                    if (status == true) {
                        Swal.fire('', response.data, 'success');
                        $('#myModal').modal('hide');
                        location.reload();
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loading').hide();

                    if (jqXHR.status == 422) {
                        var errors = jqXHR.responseJSON.errors;
                        var errorMessage = errors[Object.keys(errors)[0]][0];
                        Swal.fire('', errorMessage, "error");
                    } else {
                        Swal.fire('', 'Request failed with status: ' + jqXHR.status,
                            "error");
                    }
                }
            })
        }

        function delete_payment(button) {


            var row = $(button).closest('tr');
            var transaction_Id = row.find('td:nth-child(8)').text().trim();
            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Really Want To Delete this Payment.!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $('#loading').show();
                    $.ajax({
                        url: '{{ route('admin.fee_delete') }}',
                        type: 'POST',
                        data: {
                            'transaction_Id': transaction_Id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#loading').hide();

                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', response.data, 'success');
                                location.reload();
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#loading').hide();

                            if (jqXHR.status == 422) {
                                var errors = jqXHR.responseJSON.errors;
                                var errorMessage = errors[Object.keys(errors)[0]][0];
                                Swal.fire('', errorMessage, "error");
                            } else {
                                Swal.fire('', 'Request failed with status: ' + jqXHR.status,
                                    "error");
                            }
                        }

                    })

                }
            })

        }

        function view_receipt(transaction_id) {
            window.location.href = `{{ route('admin.generate-pdf') }}?transaction_id=${transaction_id}`;
        }
    </script>
@endsection
