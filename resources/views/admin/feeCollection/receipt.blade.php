<!DOCTYPE html>
<html>

<head>
    <title>Receipt</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 0;
        }

        body,
        html {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .container {
            padding: 20px;
            margin: 10px;
        }

        .logo_div {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Adjust as needed */
        }

        .logo_div img {
            max-width: 50%;
            max-height: 15%;
        }

        .line {
            width: 80%;
            border-top: 2px solid black;
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
        }

        #head {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-top: 10px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 15px;
            margin-left: 90px;
        }

        .info-section span {
            display: inline-block;
            width: 30%;
        }

        table {
            width: 40%;
            margin: 20px 0;
            border-collapse: collapse;
            text-align: center;
            float: left;
            margin-left: 50px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
        }

        th {
            background-color: rgb(238, 234, 234);
        }

        .note {
            clear: both;

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo_section">
            <div class="logo_div">
                <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Kalvierp Logo">
            </div>
        </div>
        <div class="line"></div>
        <p id="head">Fee Details</p>
        <div class="info-section">
            <span>Receipt No : {{ $data['receiptNo'] }}</span>
            <span>Register No : {{ $data['register_no'] }}</span>
            <span>Name : {{ $data['name'] }}</span>
        </div>
        <div class="info-section">
            <span>Date : {{ $data['paid_date'] }}</span>
            <span>Batch : {{ $data['student_batch'] }}</span>
            <span>Degree Type : {{ $data['degree_type'] }}</span>
        </div>
        <div class="info-section">
            <span>Course : {{ $data['short_form'] }}</span>
            @if ($data['feeCycles'] == 'SemesterWise')
                <span>Semester : {{ $data['semester'] }}</span>
            @elseif ($data['feeCycles'] == 'YearlyWise')
                <span>Academic Year : {{ $data['academic_year'] }}</span>
            @else
                <span>Semester/Year : N/A</span>
            @endif
            <span>Section : {{ $data['section'] }}</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Fees Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['fee_component'] as $index => $component)
                    <tr>
                        @if ($component['name'] !== 'Total')
                            <td>{{ $index + 1 }}</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{ $component['name'] }}</td>
                        <td>{{ $component['amount'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Fees Details</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>1</th>
                    <th>Amount Received</th>
                    <th>{{ $data['amount'] }}</th>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Balance Due</td>
                    <td>{{ $data['balance_due'] }}</td>
                </tr>
                <tr>
                    <th colspan="2">Paid Amount</th>
                    <th>{{ $data['amount'] }}</th>
                </tr>
            </tbody>
        </table>
        <div class="info-section note">
            <p style="font-weight: bold;">Note : Fees Paid Cannot be Refunded</p>
        </div>
    </div>
</body>

</html>
