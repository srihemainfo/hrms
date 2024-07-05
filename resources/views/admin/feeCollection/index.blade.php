@extends('layouts.admin')
@section('content')
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="row">
        <div class="form-group col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
            <div class="row">
                <div class="col-10">
                    <select name="reg_no" id="reg_no" class="form-control select2" style="font-size: 18px;"
                        onchange="getdetails()">
                        <option value="">Select Student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->register_no }}">{{ $student->name }} ({{ $student->register_no }})
                            </option>
                        @endforeach
                    </select>
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
        </div>
    </div>
    <div class="card">
        <div class="card-header text-center">
            Fees Details
        </div>
        <div class="card-body">
            <table class="table table-striped" id="feeDetailsTable">
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Pending</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header text-center">
            Payment History
        </div>
        <div class="card-body">
            <table class="table table-striped" id="feeHistoryDetails">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Receipt No</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text center" id="myModalLabel">Fees Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div><input type="number" placeholder="Enter Amount" id="paid_amount" class="form-control"></div>
                    <span id="paid_amount_error" style="color: red;"></span>
                    <div class="mt-2"><input type="text" placeholder="Enter Remark" id="remark"
                            class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="pay_now">Pay Now</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    @parent
    <script>
        $(document).on('click', '#payButton', function() {
            $('#myModal').modal('show');
        });

        $("#pay_now").click(function() {
            let pay_now = $("#paid_amount").val();
            if (pay_now == '') {
                $("#paid_amount_error").text("Please Enter Amount");
                return false;
            } else {
                $("#paid_amount_error").text("");
                return true;
            }

        })

        function getdetails() {
            $('#loading').show();
            let reg_no = $("#reg_no").val();

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
                    $('#loading').hide();
                    console.log(response);
                    let status = response.status;

                    if (status) {
                        $("#name").text(response.name);
                        $("#course").text(response.short_form);
                        $("#batch").text(response.batch);
                        $("#semester").text(response.semester);
                        $("#current_semester").text(response.semester);
                        $("#section").text(response.section);
                        $("#phone_no").text(response.phone_no);

                        let feeDetails = response.fee_details;
                        $('#feeDetailsTable tbody').empty();

                        $.each(feeDetails, function(semester_id, amount) {
                            let row = `<tr>
                        <td>${semester_id}</td>
                        <td>${amount}.00</td>
                        <td><!-- Paid amount --></td>
                        <td><!-- Pending amount --></td>
                        <td><!-- Status --></td>
                        <td><button id="payButton" data-semester-id="${semester_id}" class="btn btn-success btn-sm">Pay</button></td>
                    </tr>`;
                            $('#feeDetailsTable tbody').append(row);
                        });
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loading').hide();
                    let errorMessage = textStatus || errorThrown || 'Request Failed';
                    Swal.fire('', errorMessage, 'error');
                }
            });
        }
    </script>
@endsection
