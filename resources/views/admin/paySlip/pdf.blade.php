<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pay Slip</title>
</head>
<style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    body {
        height: 100%;
        width: 100%;
        font-family: "DejaVu Sans", sans-serif;
        /* font-family: sans-serif; */
        font-size: 0.8rem;
        background-image: url('{{ public_path('upload/payslip_bg.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
    }

    .container {
        width: 80%;
        z-index: 999;
        /* border: 1px solid black; */
        position: absolute;
        top: 200px;
        left: 80px;
    }

    .tbl {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid black;
        margin-top: 50px;
    }

    .tbl_tr {
        border: 1px solid black;
        /* Borders for each row */
    }

    .tbl_td,
    .tbl_th {
        border: 1px solid black;
        padding: 5px;
        text-align: left;
    }

    /* .tbl_td:nth-child(odd){
        font-weight: 90;
    } */

    .tbl_th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    table {
        width: 100%;
    }

    .footer {
        margin-top: 50px;
    }
</style>

<body>
    @if (count($data) > 0)
        @foreach ($data as $results)
        {{-- {{ dd($results->employee_id) }} --}}
            <div class="container">
                <h3 style="text-align: center; margin-bottom: 50px;">Pay Slip For The Month {{ $results->month }} {{ $results->year }}</h3>
                <table>
                    <tr>
                        <td>Emp. ID</td>
                        <td>: {{ !isset($results->employee_id) ? '' : $results->employee_id }}</td>
                        <td>Salary for the Month of</td>
                        <td>: {{ $results->month }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>: {{ $results->name }}</td>
                        <td>LOP Days</td>
                        <td>: {{ $results->total_lop_days }}</td>
                    </tr>
                    <tr>
                        <td>Designation</td>
                        <td>: {{ $results->designation }}</td>
                        <td>LOP Amount</td>
                        <td>: &#8377; {{ $results->lop }}</td>
                    </tr>
                    <tr>
                        <td>Date of Joining</td>
                        <td>: {{ $results->DOJ }}</td>
                        <td>UAN Number</td>
                        <td>: </td>
                    </tr>
                    <tr>
                        <td>Bank A/C no.</td>
                        <td>: {{ $results->account_no }}</td>
                        <td>ESIC Number</td>
                        <td>: </td>
                    </tr>
                    <tr>
                        <td>No. of Days</td>
                        <td>: {{ $results->total_working_days }}</td>
                        <td>No. of Days Worked</td>
                        <td>: {{ $results->total_payable_days }}</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                    </tr>
                </table>


                <table class="tbl">
                    <tr class="tbl_tr">
                        <th class="tbl_th">EARNINGS</th>
                        <th class="tbl_th" style="text-align: right;">INR</th>
                        <th class="tbl_th">DEDUCTIONS</th>
                        <th class="tbl_th" style="text-align: right;">INR</th>
                    </tr>
                    <tr class="tbl_tr">
                        <td class="tbl_td">Basic Pay</td>
                        <td class="tbl_td" style="text-align: right;">&#8377; {{ $results->basicpay }}</td>
                        <td class="tbl_td">Leave</td>
                        <td class="tbl_td" style="text-align: right;">&#8377; {{ $results->lop }}</td>

                    </tr>
                    <tr class="tbl_tr">
                        <td class="tbl_td">Allowances</td>
                        <td class="tbl_td" style="text-align: right;"></td>
                        <td class="tbl_td">Late / Permission</td>
                        <td class="tbl_td" style="text-align: right;">&#8377; {{ $results->late_amt }}</td>

                    </tr>
                    <tr class="tbl_tr">
                        <td class="tbl_td">Over Time</td>
                        <td class="tbl_td" style="text-align: right;"></td>
                        <td class="tbl_td">Salary Advance</td>
                        <td class="tbl_td" style="text-align: right;"></td>
                    </tr>
                    <tr class="tbl_tr">
                        <td class="tbl_td">Gross Salary</td>
                        <td class="tbl_td" style="text-align: right;">&#8377; {{ $results->gross_salary }}</td>
                        <td class="tbl_td">Total Deductions</td>
                        <td class="tbl_td" style="text-align: right;">
                            {{-- &#8377; {{ $results->lop + $results->late_amt }} --}}
                            &#8377; {{ !isset($results->totaldeductions) ? 0 : $results->totaldeductions }}
                        </td>
                    </tr>
                    <tr class="tbl_tr">
                        <td colspan="4" class="tbl_td"></td>
                    </tr>
                    <tr class="tbl_tr">
                        <td class="tbl_td"><strong>CTC</strong></td>
                        <td class="tbl_td" style="text-align: right;"><strong>&#8377; {{ $results->gross_salary }}</strong></td>
                        <td class="tbl_td"><strong>Net Pay</strong></td>
                        <td class="tbl_td" style="text-align: right;"><strong>&#8377; {{ $results->netpay }}</strong></td>
                    </tr>
                </table>

                <div class="footer">
                    <table style="width: 100%; border: none; margin-top: 20px;">
                        <tr>
                            <td style="width: 50%; text-align: left; padding: 10px; border: none;">Prepared By</td>
                            <td style="width: 50%; text-align: right; padding: 10px; border: none;">Authorised Signature
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%; text-align: left; padding: 10px; border: none;">
                                <small style="font-size: 0.9rem;">HR Team</small>
                            </td>
                            <td style="width: 50%; text-align: right; padding: 10px; border: none;"></td>
                        </tr>
                    </table>
                </div>

            </div>
        @endforeach
    @endif
</body>

</html>
