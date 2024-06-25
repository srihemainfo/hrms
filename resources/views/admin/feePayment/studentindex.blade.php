@extends('layouts.studentHome')
@section('content')
    <style>
        .card {
            margin-bottom: 10px !important;
        }

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
    <div id="loading_div" class="text-primary text-center p-3" style="display:none;">Loading...</div>
    <div id="fee_list">

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

        let admittedCat = '';

        window.onload = function() {
            $("#total_fee_div").html(0);
            $("#paying_fee_div").html(0);
            $("#paid_fee_div").html(0);
            $("#balance_fee_div").html(0);
            $("#excess_fee_div").html(0);
            // $("#history_bn").hide();
            // $("#fee_structure").hide();
            $("#loading_div").hide();
            // $("#fee_details").hide();
            // $("#history_details").hide();
            $("#name_span").hide();
            $("#year_span").hide();
            // $("#history_loader").hide();
            // $("#history_card").hide();
            getFee();
        }

        function hider() {
            // $("#fee_structure").hide();
            // $("#history_bn").hide();
            // $("#fee_details").hide();
        }

        function getFee() {
            let user_name_id = '{{ auth()->user()->id }}';
            // $("#fee_structure").show();
            $("#loading_div").show();
            // $("#history_bn").hide();
            $("#fee_details").hide();
            // $("#history_card").hide();

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
                        let content = '';
                        let theYear;
                        let Lstate;
                        let Tstate;
                        let Fg;
                        let ScholarAmt;
                        let Scholar = '';
                        let TotalAmt;
                        let TotalPaid;
                        let TotalBalance;
                        let TuitionAmt;
                        let TuitionPaid;
                        let TuitionBalance;
                        let HostelAmt;
                        let HostelPaid;
                        let HostelBalance;
                        let OthersAmt;
                        let OthersPaid;
                        let OthersBalance;
                        let OthersBySponsership;
                        let SponserPercentage;


                        let paidStatus;
                        let hidePayButton = '';
                        let showPaidStatus = '';
                        let historyBtn = '';
                        var admissionCat = data[4];

                        HostelFee_1 = data[0].hostel_fee;
                        HostelFee_2 = data[1].hostel_fee;
                        HostelFee_3 = data[2].hostel_fee;
                        HostelFee_4 = data[3].hostel_fee;

                        // OtherFee_1 = data[0].others;
                        // OtherFee_2 = data[1].others;
                        // OtherFee_3 = data[2].others;
                        // OtherFee_4 = data[3].others;

                        console.log(data)
                        if (admissionCat != '') {
                            if (admissionCat.admitted_category == 'FG') {
                                admittedCat = 'FG';
                                console.log('fg student');
                            } else if (admissionCat.admitted_category == 'GQG') {
                                admittedCat = 'GQG';
                                console.log('gqg student');
                            } else if (admissionCat.admitted_category == 'Scholarship') {
                                admittedCat = 'Scholarship';
                                console.log('scho student');
                            }
                            if (admissionCat.fg != null) {
                                Fg = admissionCat.fg;
                            } else {
                                Fg = 0;
                            }
                            if (admissionCat.foundation_percentage != null) {
                                ScholarAmt = admissionCat.foundation_percentage;
                            } else {
                                ScholarAmt = 0;
                            }

                            if (admissionCat.scholar != null) {
                                Scholar = admissionCat.scholar;
                            }

                        } else {
                            admittedCat = 'MQ';
                            console.log('manage student');
                        }
                        if (admissionCat != '') {

                            if (admissionCat.admitted_category != 'GQG') {
                                if (admissionCat.admitted_category == 'FG') {
                                    for (let i = 0; i < 4; i++) {
                                        hidePayButton = 'display:none;';
                                        historyBtn = 'display:none;';
                                        showPaidStatus = '';
                                        var j = i + 1;


                                        Lstate = 'style="display:none;"';
                                        classKey = 'backColor';
                                        Tstate = 'style="display:block;"';

                                        if (data[i].hosteler == true) {
                                            TotalAmt = data[i].gqh_total_amt;
                                            HostelAmt = data[i].hostel_fee;

                                        } else {
                                            if (data[i].feeCollect != null) {
                                                if (data[i].feeCollect.hosteler == 1) {
                                                    TotalAmt = data[i].gqh_total_amt;
                                                    HostelAmt = data[i].hostel_fee;
                                                } else {
                                                    TotalAmt = data[i].gq_total_amt;
                                                    HostelAmt = 0;
                                                }

                                            } else {
                                                TotalAmt = data[i].gq_total_amt;
                                                HostelAmt = 0;
                                            }

                                        }
                                        TuitionAmt = data[i].gq_tuition_fee;
                                        OthersAmt = data[i].others;

                                        if (data[i].feeCollect != null) {
                                            TuitionPaid = data[i].feeCollect.tuition_paid;
                                            HostelPaid = data[i].feeCollect.hostel_paid;
                                            OthersPaid = data[i].feeCollect.other_paid;
                                            paidStatus = data[i].feeCollect.status;

                                        } else {
                                            TuitionPaid = 0;
                                            HostelPaid = 0;
                                            OthersPaid = 0;
                                            paidStatus = '';
                                        }

                                        TuitionBalance = TuitionAmt - (Fg + TuitionPaid);
                                        HostelBalance = HostelAmt - HostelPaid;
                                        OthersBalance = OthersAmt - OthersPaid;

                                        TotalAmt = TuitionAmt + HostelAmt + OthersAmt;
                                        TotalPaid = TuitionPaid + HostelPaid + OthersPaid;
                                        TotalBalance = TuitionBalance + HostelBalance + OthersBalance;

                                        if (j == 1) {
                                            theYear = 'First Year';
                                            if (data[i].activeYear == 1) {
                                                Lstate = 'style="display:block;"';
                                                Tstate = 'style="display:none;"';
                                                if (data[i].hosteler == true) {
                                                    classKey = '';
                                                }
                                            } else {
                                                var feeHistroy = data[i].feeCollect;
                                                if (feeHistroy != null && feeHistroy.hostel_paid != 0) {
                                                    classKey = '';
                                                    theHostel_1 = true;
                                                }
                                            }
                                            if (paidStatus != 'PAID') {
                                                paidStatus_1 = false;
                                                hidePayButton = '';
                                                showPaidStatus = 'display:none;';
                                            }
                                            if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                historyBtn = '';
                                            }
                                        } else if (j == 2) {
                                            theYear = 'Second Year';
                                            if (data[i].activeYear == 2) {
                                                Lstate = 'style="display:block;"';
                                                Tstate = 'style="display:none;"';
                                                if (data[i].hosteler == true) {
                                                    classKey = '';
                                                }
                                            } else {
                                                var feeHistroy = data[i].feeCollect;
                                                if (feeHistroy != null && feeHistroy.hosteler != 0) {
                                                    classKey = '';
                                                    theHostel_2 = true;
                                                }
                                            }
                                            if (paidStatus != 'PAID') {
                                                paidStatus_2 = false;
                                                hidePayButton = '';
                                                showPaidStatus = 'display:none;';
                                            }
                                            if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                historyBtn = '';
                                            }
                                        } else if (j == 3) {
                                            theYear = 'Third Year';
                                            if (data[i].activeYear == 3) {
                                                Lstate = 'style="display:block;"';
                                                Tstate = 'style="display:none;"';
                                                if (data[i].hosteler == true) {
                                                    classKey = '';

                                                }
                                            } else {
                                                var feeHistroy = data[i].feeCollect;
                                                if (feeHistroy != null && feeHistroy.hosteler != 0) {
                                                    classKey = '';
                                                    theHostel_3 = true;

                                                }
                                            }
                                            if (paidStatus != 'PAID') {
                                                paidStatus_3 = false;
                                                hidePayButton = '';
                                                showPaidStatus = 'display:none;';
                                            }
                                            if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                historyBtn = '';
                                            }
                                        } else if (j == 4) {
                                            theYear = 'Final Year';
                                            if (data[i].activeYear == 4) {
                                                Lstate = 'style="display:block;"';
                                                Tstate = 'style="display:none;"';
                                                if (data[i].hosteler == true) {
                                                    classKey = '';
                                                }
                                            } else {
                                                var feeHistroy = data[i].feeCollect;
                                                if (feeHistroy != null && feeHistroy.hosteler != 0) {
                                                    classKey = '';
                                                    theHostel_4 = true;

                                                }
                                            }
                                            if (paidStatus != 'PAID') {
                                                paidStatus_4 = false;
                                                hidePayButton = '';
                                                showPaidStatus = 'display:none;';
                                            }
                                            if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                historyBtn = '';
                                            }
                                        }

                                        content += ` <div class="card" id="fee_structure">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-6 text-primary">
                                                            ${theYear} Fee Details
                                                        </div>
                                                        <div class="col-6 text-right row">
                                                            <div class="col-12 text-right">
                                                                <div ${Tstate}>
                                                                   <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus" onclick="view_more(this,${j})"
                                                                    style="font-size:1.5em;"></i></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body" id="view_more_${j}" ${Lstate}>
                                                    <div class="row" id="fee_details_${j}">
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Tuition Fee</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_amt_${j}">${TuitionAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>FG Discount </b> </div>
                                                                       <div style="width:70%">: <span id="fg_deduction_${j}">${Fg}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_paid_${j}">${TuitionPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_balance_${j}">${TuitionBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card ${classKey}" id="hostel_card_${j}" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Hostel Fee</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_amt_${j}">${HostelAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_paid_${j}">${HostelPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_balance_${j}">${HostelBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Others</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="other_amt_${j}">${OthersAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="other_paid_${j}">${OthersPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="other_balance_${j}">${OthersBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Fee</b> </p>
                                                                   <div id="total_fee_div_${j}">${TotalAmt}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Paid Fee</b> </p>
                                                                   <div id="paid_fee_div_${j}">${TotalPaid}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Balance Fee</b> </p>
                                                                   <div id="balance_fee_div_${j}">${TotalBalance}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center"></div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center p-2">
                                                        <div style="margin-top:30px;width:90%;${historyBtn}" class="mx-auto enroll_generate_bn bg-warning"><a target="_blank" href="{{ url('admin/fee-payment/payment-history/`+j+`/`+data[i].id +`/`+user_name_id+`') }}"  onclick="payHistory(${j})">Payment History</a></div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center p-2">
                                                          <input type="hidden" name="fee_id" id="fee_id_${j}" value="${data[i].id}">
                                                            <div  class="mx-auto rounded border border-success text-success" style="margin-top:30px;width:90%;padding:4px;${showPaidStatus}">${theYear} Fee Paid </div>
                                                       </div>
                                                   </div>
                                                </div>
                                            </div>`;

                                    }
                                } else if (admissionCat.admitted_category == 'Scholarship') {
                                    if (ScholarAmt == 100) {
                                        content += `<div class="card">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-6 text-primary">
                                                            Scholarship / Foundation Sponsership
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="card">
                                                       <b class="p-2 text-center">100 % Sponsership From <span class="text-primary"> ${Scholar}</span></b>
                                                    </div>
                                                </div>
                                            </div>`;
                                    } else {

                                        for (let i = 0; i < 4; i++) {
                                            hidePayButton = 'display:none;';
                                            showPaidStatus = '';
                                            historyBtn = 'display:none;';

                                            var j = i + 1;


                                            Lstate = 'style="display:none;"';
                                            classKey = 'backColor';

                                            Tstate = 'style="display:block;"';

                                            if (data[i].hosteler == true) {
                                                TotalAmt = data[i].gqh_total_amt;
                                                HostelAmt = data[i].hostel_fee;

                                            } else {
                                                if (data[i].feeCollect != null) {
                                                    if (data[i].feeCollect.hosteler == 1) {
                                                        TotalAmt = data[i].gqh_total_amt;
                                                        HostelAmt = data[i].hostel_fee;
                                                    } else {
                                                        TotalAmt = data[i].gq_total_amt;
                                                        HostelAmt = 0;
                                                    }

                                                } else {
                                                    TotalAmt = data[i].gq_total_amt;
                                                    HostelAmt = 0;
                                                }

                                            }
                                            TuitionAmt = data[i].gq_tuition_fee;
                                            OthersAmt = parseInt(data[i].others);
                                            SponserPercentage = admissionCat.foundation_percentage;
                                            if (SponserPercentage != null && SponserPercentage != 0) {
                                                OthersBySponsership = (OthersAmt * SponserPercentage) / 100;
                                                SponserPercentage = SponserPercentage + ' %';
                                            } else {
                                                OthersBySponsership = 0;
                                                SponserPercentage = '';
                                            }

                                            if (data[i].feeCollect != null) {
                                                TuitionPaid = data[i].feeCollect.tuition_paid;
                                                HostelPaid = data[i].feeCollect.hostel_paid;
                                                OthersPaid = data[i].feeCollect.other_paid;
                                                paidStatus = data[i].feeCollect.status;

                                            } else {
                                                TuitionPaid = 0;
                                                HostelPaid = 0;
                                                OthersPaid = 0;
                                                paidStatus = '';
                                            }


                                            TuitionBalance = TuitionAmt - TuitionPaid;
                                            HostelBalance = HostelAmt - HostelPaid;
                                            OthersBalance = OthersAmt - (OthersPaid + OthersBySponsership);

                                            TotalAmt = TuitionAmt + HostelAmt + OthersAmt;
                                            TotalPaid = TuitionPaid + HostelPaid + OthersPaid;
                                            TotalBalance = TuitionBalance + HostelBalance + OthersBalance;

                                            if (j == 1) {
                                                theYear = 'First Year';
                                                if (data[i].activeYear == 1) {
                                                    Lstate = 'style="display:block;"';
                                                    Tstate = 'style="display:none;"';
                                                    if (data[i].hosteler == true) {
                                                        classKey = '';

                                                    }
                                                } else {
                                                    var feeHistroy = data[i].feeCollect;
                                                    if (feeHistroy != null && feeHistroy.hostel_paid !=
                                                        null) {
                                                        classKey = '';

                                                        theHostel_1 = true;

                                                    }
                                                }
                                                if (paidStatus != 'PAID') {
                                                    paidStatus_1 = false;
                                                    hidePayButton = '';
                                                    showPaidStatus = 'display:none;';
                                                }
                                                if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                    historyBtn = '';
                                                }
                                            } else if (j == 2) {
                                                theYear = 'Second Year';
                                                if (data[i].activeYear == 2) {
                                                    Lstate = 'style="display:block;"';
                                                    Tstate = 'style="display:none;"';
                                                    if (data[i].hosteler == true) {
                                                        classKey = '';
                                                    }
                                                    if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                        historyBtn = '';
                                                    }
                                                } else {
                                                    var feeHistroy = data[i].feeCollect;
                                                    if (feeHistroy != null && feeHistroy.hostel_paid !=
                                                        null) {
                                                        classKey = '';

                                                        theHostel_2 = true;
                                                    }
                                                }
                                                if (paidStatus != 'PAID') {
                                                    paidStatus_2 = false;
                                                    hidePayButton = '';
                                                    showPaidStatus = 'display:none;';
                                                }
                                                if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                    historyBtn = '';
                                                }
                                            } else if (j == 3) {
                                                theYear = 'Third Year';
                                                if (data[i].activeYear == 3) {
                                                    Lstate = 'style="display:block;"';
                                                    Tstate = 'style="display:none;"';
                                                    if (data[i].hosteler == true) {
                                                        classKey = '';
                                                    }
                                                } else {
                                                    var feeHistroy = data[i].feeCollect;
                                                    if (feeHistroy != null && feeHistroy.hostel_paid !=
                                                        null) {
                                                        classKey = '';
                                                        theHostel_3 = true;
                                                    }
                                                }
                                                if (paidStatus != 'PAID') {
                                                    paidStatus_3 = false;
                                                    hidePayButton = '';
                                                    showPaidStatus = 'display:none;';
                                                }
                                                if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                    historyBtn = '';
                                                }
                                            } else if (j == 4) {
                                                theYear = 'Final Year';
                                                if (data[i].activeYear == 4) {
                                                    Lstate = 'style="display:block;"';
                                                    Tstate = 'style="display:none;"';
                                                    if (data[i].hosteler == true) {
                                                        classKey = '';
                                                    }
                                                } else {
                                                    var feeHistroy = data[i].feeCollect;
                                                    if (feeHistroy != null && feeHistroy.hostel_paid !=
                                                        null) {
                                                        classKey = '';
                                                        theHostel_4 = true;
                                                    }
                                                }
                                                if (paidStatus != 'PAID') {
                                                    paidStatus_4 = false;
                                                    hidePayButton = '';
                                                    showPaidStatus = 'display:none;';
                                                }
                                                if (paidStatus != '' && paidStatus != 'UNPAID') {
                                                    historyBtn = '';
                                                }
                                            }

                                            content += ` <div class="card" id="fee_structure">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-6 text-primary">
                                                            ${theYear} Fee Details
                                                        </div>
                                                        <div class="col-6 text-right row">
                                                            <div class="col-12 text-right">
                                                                <div ${Tstate}>
                                                                   <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus" onclick="view_more(this,${j})"
                                                                    style="font-size:1.5em;"></i></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body" id="view_more_${j}" ${Lstate}>
                                                    <div class="row" id="fee_details_${j}">
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Tuition Fee</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_amt_${j}">${TuitionAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_paid_${j}">${TuitionPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_balance_${j}">${TuitionBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card ${classKey}" id="hostel_card_${j}" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Hostel Fee</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_amt_${j}">${HostelAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_paid_${j}">${HostelPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_balance_${j}">${HostelBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Others</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="other_amt_${j}">${OthersAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>From Foundation </b> </div>
                                                                       <div style="width:70%">: <span id="sponser_amt_${j}">${OthersBySponsership}</span> <span>(${SponserPercentage})</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="other_paid_${j}">${OthersPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="other_balance_${j}">${OthersBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Fee</b> </p>
                                                                   <div id="total_fee_div_${j}">${TotalAmt}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Paid Fee</b> </p>
                                                                   <div id="paid_fee_div_${j}">${TotalPaid}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Balance Fee</b> </p>
                                                                   <div id="balance_fee_div_${j}">${TotalBalance}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center"></div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center p-2">
                                                        <div style="margin-top:30px;width:90%;${historyBtn}" class="mx-auto enroll_generate_bn bg-warning"><a target="_blank" href="{{ url('admin/fee-payment/payment-history/`+j+`/`+data[i].id +`/`+user_name_id+`') }}"  onclick="payHistory(${j})">Payment History</a></div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center p-2">
                                                          <input type="hidden" name="fee_id" id="fee_id_${j}" value="${data[i].id}">
                                                               <div  class="mx-auto rounded border border-success text-success" style="margin-top:30px;width:90%;padding:4px;${showPaidStatus}">${theYear} Fee Paid </siv>
                                                       </div>
                                                   </div>
                                                </div>
                                              </div>`;

                                        }
                                    }
                                }
                            } else {
                                let govt_fee = '';
                                let foundation_percentage = '';
                                if (admissionCat.from_gov_fee != null) {
                                    govt_fee = admissionCat.from_gov_fee;
                                }
                                if (admissionCat.foundation_percentage != null) {
                                    foundation_percentage = admissionCat.foundation_percentage + ' %';
                                }
                                content += `<div class="card">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-6 text-primary">
                                                             GQG - Student
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                       <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                           <div class="card">
                                                             <div class="card-body">
                                                                <div class="form-group text-center">
                                                                    <label for="">From Government (Fee)</label>
                                                                    <p class="text-primary "><b>${govt_fee}</b></p>
                                                                </div>
                                                             </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                           <div class="card">
                                                             <div class="card-body">
                                                                <div class="form-group text-center">
                                                                        <label for="">Foundation Sponsership</label>
                                                                        <p class="text-primary"><b>${foundation_percentage}</b></p>
                                                                </div>
                                                              </div>
                                                           </div>
                                                       </div>
                                                    </div>
                                                </div>
                                            </div>`;
                            }
                        } else {

                            for (let i = 0; i < 4; i++) {
                                hidePayButton = 'display:none;';
                                historyBtn = 'display:none;';
                                showPaidStatus = '';
                                var j = i + 1;


                                Lstate = 'style="display:none;"';
                                classKey = 'backColor';

                                Tstate = 'style="display:block;"';


                                if (data[i].hosteler == true) {
                                    TotalAmt = data[i].gqh_total_amt;
                                    HostelAmt = data[i].hostel_fee;

                                } else {
                                    if (data[i].feeCollect != null) {
                                        if (data[i].feeCollect.hosteler == 1) {
                                            TotalAmt = data[i].gqh_total_amt;
                                            HostelAmt = data[i].hostel_fee;

                                        } else {
                                            TotalAmt = data[i].gq_total_amt;
                                            HostelAmt = 0;
                                        }

                                    } else {
                                        TotalAmt = data[i].gq_total_amt;
                                        HostelAmt = 0;
                                    }

                                }
                                TuitionAmt = data[i].mq_tuition_fee;
                                OthersAmt = data[i].others;

                                if (data[i].feeCollect != null) {
                                    TuitionPaid = data[i].feeCollect.tuition_paid;
                                    HostelPaid = data[i].feeCollect.hostel_paid;
                                    OthersPaid = data[i].feeCollect.other_paid;
                                    paidStatus = data[i].feeCollect.status;

                                } else {
                                    TuitionPaid = 0;
                                    HostelPaid = 0;
                                    OthersPaid = 0;
                                    paidStatus = '';
                                }

                                TuitionBalance = TuitionAmt - TuitionPaid;
                                HostelBalance = HostelAmt - HostelPaid;
                                OthersBalance = OthersAmt - OthersPaid;

                                TotalAmt = TuitionAmt + HostelAmt + OthersAmt;
                                TotalPaid = TuitionPaid + HostelPaid + OthersPaid;
                                TotalBalance = TuitionBalance + HostelBalance + OthersBalance;

                                if (j == 1) {
                                    theYear = 'First Year';
                                    if (data[i].activeYear == 1) {
                                        Lstate = 'style="display:block;"';
                                        Tstate = 'style="display:none;"';
                                        if (data[i].hosteler == true) {
                                            classKey = '';
                                            disableKey = '';

                                        }
                                    } else {
                                        var feeHistroy = data[i].feeCollect;
                                        if (feeHistroy != null && feeHistroy.hosteler != 0) {
                                            classKey = '';
                                            disableKey = '';
                                            checked = 'checked';
                                            theHostel_1 = true;

                                        }
                                    }
                                    if (paidStatus != 'PAID') {
                                        paidStatus_1 = false;
                                        hidePayButton = '';
                                        showPaidStatus = 'display:none;';
                                    }
                                    if (paidStatus != '' && paidStatus != 'UNPAID') {
                                        historyBtn = '';
                                    }
                                } else if (j == 2) {
                                    theYear = 'Second Year';
                                    if (data[i].activeYear == 2) {
                                        Lstate = 'style="display:block;"';
                                        Tstate = 'style="display:none;"';
                                        if (data[i].hosteler == true) {
                                            classKey = '';
                                            disableKey = '';

                                        }
                                    } else {
                                        var feeHistroy = data[i].feeCollect;
                                        if (feeHistroy != null && feeHistroy.hosteler != 0) {
                                            classKey = '';
                                            disableKey = '';
                                            checked = 'checked';
                                            theHostel_2 = true;

                                        }
                                    }
                                    if (paidStatus != 'PAID') {
                                        paidStatus_2 = false;
                                        hidePayButton = '';
                                        showPaidStatus = 'display:none;';
                                    }
                                    if (paidStatus != '' && paidStatus != 'UNPAID') {
                                        historyBtn = '';
                                    }
                                } else if (j == 3) {
                                    theYear = 'Third Year';
                                    if (data[i].activeYear == 3) {
                                        Lstate = 'style="display:block;"';
                                        Tstate = 'style="display:none;"';
                                        if (data[i].hosteler == true) {
                                            classKey = '';
                                            disableKey = '';

                                        }
                                    } else {
                                        var feeHistroy = data[i].feeCollect;
                                        if (feeHistroy != null && feeHistroy.hosteler != 0) {
                                            classKey = '';
                                            disableKey = '';
                                            checked = 'checked';
                                            theHostel_3 = true;

                                        }
                                    }
                                    if (paidStatus != 'PAID') {
                                        paidStatus_3 = false;
                                        hidePayButton = '';
                                        showPaidStatus = 'display:none;';
                                    }
                                    if (paidStatus != '' && paidStatus != 'UNPAID') {
                                        historyBtn = '';
                                    }
                                } else if (j == 4) {
                                    theYear = 'Final Year';
                                    if (data[i].activeYear == 4) {
                                        Lstate = 'style="display:block;"';
                                        Tstate = 'style="display:none;"';
                                        if (data[i].hosteler == true) {
                                            classKey = '';
                                            disableKey = '';

                                        }
                                    } else {
                                        var feeHistroy = data[i].feeCollect;
                                        if (feeHistroy != null && feeHistroy.hosteler != 0) {
                                            classKey = '';
                                            disableKey = '';
                                            checked = 'checked';
                                            theHostel_4 = true;

                                        }
                                    }
                                    if (paidStatus != 'PAID') {
                                        paidStatus_4 = false;
                                        hidePayButton = '';
                                        showPaidStatus = 'display:none;';
                                    }
                                    if (paidStatus != '' && paidStatus != 'UNPAID') {
                                        historyBtn = '';
                                    }
                                }

                                content += ` <div class="card" id="fee_structure">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-6 text-primary">
                                                            ${theYear} Fee Details
                                                        </div>
                                                        <div class="col-6 text-right row">
                                                            <div class="col-12 text-right">
                                                                <div ${Tstate}>
                                                                   <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus" onclick="view_more(this,${j})"
                                                                    style="font-size:1.5em;"></i></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body" id="view_more_${j}" ${Lstate}>
                                                    <div class="row" id="fee_details_${j}">
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Tuition Fee</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_amt_${j}">${TuitionAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_paid_${j}">${TuitionPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="tuition_balance_${j}">${TuitionBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card ${classKey}" id="hostel_card_${j}" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Hostel Fee</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_amt_${j}">${HostelAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_paid_${j}">${HostelPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="hostel_balance_${j}">${HostelBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                                                           <div class="card" style="height:100%;">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Others</b> </p>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Amount </b> </div>
                                                                       <div style="width:70%">: <span id="other_amt_${j}">${OthersAmt}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Paid </b> </div>
                                                                       <div style="width:70%">: <span id="other_paid_${j}">${OthersPaid}</span></div>
                                                                   </div>
                                                                   <div style="display:flex;margin-bottom:10px;width:100%;">
                                                                       <div style="width:48%"><b>Balance </b> </div>
                                                                       <div style="width:70%">: <span id="other_balance_${j}">${OthersBalance}</span></div>
                                                                   </div>

                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Fee</b> </p>
                                                                   <div id="total_fee_div_${j}">${TotalAmt}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Paid Fee</b> </p>
                                                                   <div id="paid_fee_div_${j}">${TotalPaid}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center">
                                                           <div class="card">
                                                               <div class="card-body">
                                                                   <p class="text-center text-primary" style="margin-bottom:5px;"> <b>Total Balance Fee</b> </p>
                                                                   <div id="balance_fee_div_${j}">${TotalBalance}</div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center"></div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center p-2">
                                                        <div style="margin-top:30px;width:90%;${historyBtn}" class="mx-auto enroll_generate_bn bg-warning"> <a target="_blank" href="{{ url('admin/fee-payment/payment-history/`+j+`/`+data[i].id +`/`+user_name_id+`') }}" onclick="payHistory(${j})">Payment History</a></div>
                                                       </div>
                                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 text-center p-2">
                                                          <input type="hidden" name="fee_id" id="fee_id_${j}" value="${data[i].id}">
                                                            <div  class="mx-auto rounded border border-success text-success" style="margin-top:30px;width:90%;padding:4px;${showPaidStatus}">${theYear} Fee Paid </div>
                                                       </div>
                                                   </div>
                                                </div>
                                            </div>`;
                            }
                        }
                        $("#fee_list").html(content);
                    } else {
                        // $("#fee_structure").hide();
                        Swal.fire('', data, 'error');
                    }

                }
            })
        }


        function view_more(element, id) {
            let paid_status;
            if (id == 1) {
                paid_status = paidStatus_1;
            } else if (id == 2) {
                paid_status = paidStatus_2;
            } else if (id == 3) {
                paid_status = paidStatus_3;
            } else if (id == 4) {
                paid_status = paidStatus_4;
            }

            $(element).toggleClass('rotated');
            let theId = '#view_more_' + id;
            let checkId = '#check_' + id;

            $(theId).toggle();
            if (paid_status == false) {
                $(checkId).toggle();
            }
        };
    </script>
@endsection
