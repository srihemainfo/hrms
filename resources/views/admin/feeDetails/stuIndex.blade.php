@extends('layouts.studentHome')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row text-center">
                <div class="col-md-6 col-12 form-group">
                    <div><b>Course</b> : <span id="course_span"></span></div>
                </div>
                <div class="col-md-6 col-12 form-group">
                    <div><b>Admitted Mode</b> : <span id="admission_span"></span></div>
                </div>
                <div class="col-md-3 col-12 form-group">
                    <div><b>Scholarship</b> : <span id="scholarship_span"></span></div>
                </div>
                <div class="col-md-3 col-12 form-group">
                    <div><b>GQG</b> : <span id="gqg_span"></span></div>
                </div>
                <div class="col-md-3 col-12 form-group">
                    <div><b>First Graduate</b> : <span id="fg_span"></span></div>
                </div>
                <div class="col-md-3 col-12 form-group">
                    <div><b>Hosteler</b> : <span id="hostel_span"></span></div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center" style="width:100%;">
                <thead>
                    <tr>
                        <th>AY</th>
                        <th>Tuition Fees</th>
                        <th>Other Fees</th>
                        <th>Hostel Fee</th>
                        <th>Total Fee Amount</th>
                        <th>Scholarship</th>
                        <th>GQG</th>
                        <th>FG</th>
                        <th>Total Fee Paid</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
            <div style="margin-top: 10px;">
                <b> &nbsp; Last Updated On : </b> <span id="date_span"></span>
            </div>
            <div style="color:rgb(255, 0, 0)"><b>*Total Balance is calculated only from 2023-2024 academic year</b></div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {

            $("#tbody").html(`<tr><td colspan="9"> Loading...</td></tr>`);
            $.ajax({
                url: '{{ route('admin.fee-details.get-data') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        let data = response.data;
                        var course = data.course;
                        $("#course_span").html(course.short_form ?? '')
                        $("#admission_span").html(data.admitted_mode ?? '')
                        $("#scholarship_span").html(data.scholarship == '1' ? 'Yes' : 'No')
                        $("#gqg_span").html(data.gqg == '1' ? 'Yes' : 'No')
                        $("#fg_span").html(data.first_graduate == '1' ? 'Yes' : 'No')
                        $("#hostel_span").html(data.hosteler == '1' ? 'Yes' : 'No')
                        var overAllFeeAmount = 0;
                        var overAllPaidFee = 0;
                        var paidDate = '';
                        var overAllScholarship = 0;
                        var overAllGqg = 0;
                        var overAllFg = 0;
                        var feeData = data.feeData;
                        var rows = '';
                        if (feeData.length > 0) {
                            for (let i = 0; i < feeData.length; i++) {
                                var totalFeeAmount = parseInt(feeData[i].tuition_fee) + parseInt(feeData[i]
                                    .other_fee) + parseInt(feeData[i].hostel_fee);
                                overAllFeeAmount += totalFeeAmount;
                                overAllPaidFee += parseInt(feeData[i].paid_amt);

                                overAllScholarship += parseInt(feeData[i].scholarship_amt);
                                overAllGqg += parseInt(feeData[i].gqg_amt);
                                overAllFg += parseInt(feeData[i].fg_amt);
                                if (i == (feeData.length - 1)) {
                                    paidDate = feeData[i].paid_date;
                                }
                                rows +=
                                    `<tr><td>${feeData[i].get_ay.name}</td><td>${feeData[i].tuition_fee}</td><td>${feeData[i].other_fee}</td><td>${feeData[i].hostel_fee}</td><td>${totalFeeAmount}</td><td>${feeData[i].scholarship_amt}</td><td>${feeData[i].gqg_amt}</td><td>${feeData[i].fg_amt}</td><td>${feeData[i].paid_amt}</td></tr>`;
                            }
                            rows +=
                                `<tr><td>Grand Total </td><td></td><td></td><td></td><td>${overAllFeeAmount}</td><td></td><td></td><td></td><td>${overAllPaidFee}</td></tr>`;
                            rows +=
                                `<tr><td>Total Balance </td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>${overAllFeeAmount - (overAllPaidFee + overAllScholarship + overAllGqg + overAllFg)}</td></tr>`;
                        } else {
                            rows = `<tr><td colspan="9"> No Data Available...</td></tr>`;
                        }
                        $("#tbody").html(rows);
                        $("#date_span").html(paidDate);

                    } else {
                        Swal.fire('', response.data, 'error');
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
                    $("#tbody").html(`<tr><td colspan="9"> No Data Available...</td></tr>`);
                }
            });
        }
    </script>
@endsection
