@php
    $role_id = auth()->user()->roles[0]->id;
    if ($role_id == 1 || $role_id == 5) {
        $key = 'layouts.admin';
    } else {
        $key = 'layouts.staffs';
    }
@endphp
@extends($key)
@section('content')
    <style>
        .secondLoader {
            z-index: 999;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Payroll List
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6 form-group">
                    <label for="designation" class="required">Designation</label>
                    <select name="designation" id="designation" class="form-control select2">
                        <option value="">Select Designation</option>
                        <option value="All">All</option>
                        @foreach ($designations as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <span id="designation_span" class="text-danger text-center" style="font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6 form-group">
                    <label for="month" class="required">Month</label>
                    <select name="month" class="form-control select2" id="month">
                        <option value="">Select Month</option>
                        <option value="All">All Month</option>
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
                    <span id="month_span" class="text-danger text-center" style="font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6 form-group">
                    <label for="year" class="required">Year</label>
                    <select name="year" class="form-control select2" id="year">
                        <option value="">Select Year</option>
                        @for ($year = date('Y'); $year >= 2023; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                    <span id="year_span" class="text-danger text-center" style="font-size:0.9rem;"></span>
                </div>
                <div class="col-1 form-group">
                    <div class="form-group" style="text-align:right;padding-top:2.2rem;">
                        <button class="enroll_generate_bn" onclick="get_data()">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="secondLoader"></div>

    <div class="card" id="report" style="display:none;max-width:fix-content;overflow-x:auto;z-index:0;">
        <div class="card-header text-center text-primary" id="card_header">Payroll Report</div>
        <div class="card-body" id="card-body">
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function get_data() {
            let month = $("#month").val();
            let year = $("#year").val();
            let designation = $("#designation").val();

            // Validation checks
            if (designation === '') {
                $("#designation_span").text("Please Select Designation");
                return false;
            } else {
                $("#designation_span").text("");
            }

            if (month === '') {
                $("#month_span").text("Please Select Month");
                return false;
            } else {
                $("#month_span").text("");
            }

            if (year === '') {
                $("#year_span").text("Please Select Year");
                return false;
            } else {
                $("#year_span").text("");
            }
            $(".secondLoader").show();

            $.ajax({
                url: "{{ route('admin.payroll.get-data') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'month': month,
                    'year': year,
                    'designation': designation
                },
                success: function(response) {
                    $('.secondLoader').hide();
                    let status = response.status;
                    if (status === true) {
                        var data = response.data;
                        console.log(data);
                        let totalNetPay = 0;
                        let sumdeduction = 0;
                        let totalbasicpay = 0;

                        if (data && data.length > 0) {
                            // Build the table structure
                            let staffTable = `
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Staff Name</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Basic Pay</th>
                            <th>Total Deduction</th>
                            <th>Net Pay</th>
                        </tr>
                    </thead>
                    <tbody>`;

                            // Populate table rows
                            data.forEach((item, index) => {
                                staffTable += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.name}</td>
                        <td>${item.month}</td>
                        <td>${item.year}</td>
                        <td>${item.basicpay}</td>
                        <td>${item.totaldeductions}</td>
                        <td>${item.netpay}</td>
                    </tr>`;

                                totalNetPay += parseFloat(item.netpay);
                                sumdeduction += parseFloat(item.totaldeductions);
                                totalbasicpay += parseFloat(item.basicpay);

                            });

                            // Append total row inside the table
                            staffTable += `
                    <tr>
                        <td colspan="4"><strong>Total</strong></td>
                        <td><strong>${totalbasicpay.toFixed(2)}</strong></td>
                        <td><strong>${sumdeduction.toFixed(2)}</strong></td>
                        <td><strong>${totalNetPay.toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>`;

                            // Display the report
                            $('#report').show();
                            $('#card-body').html(staffTable);

                        } else {
                            // Hide the report if no data is available
                            $('#report').hide();
                            $('#card-body').html(''); // Clear any previous content
                            Swal.fire('', 'No data found for the selected criteria.', 'info');
                        }
                    } else {
                        Swal.fire('', response.data, 'error');
                        $('#report').hide();
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    $('.secondLoader').hide();
                    if (jqXHR.status === 500) {
                        Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                    } else {
                        Swal.fire('', 'Error: ' + jqXHR.status, 'error');
                    }
                }
            });
        }
    </script>
@endsection
