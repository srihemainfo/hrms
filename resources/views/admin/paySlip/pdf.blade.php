<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            /* background-color: rgb(235, 249, 255); */
        }

        .header_div {
            text-align: center;
            margin-top: -10px;
        }

        .header_div p:first-child {
            font-family: 'Merriweather', serif;
            font-size: 1.7rem;
            font-style: italic;
            font-weight: bold;
        }

        .header_div p {
            /* margin-top: -20px; */
            text-align: center;
            font-family: 'Merriweather', serif;
            font-weight: 500;
            font-style: italic;
        }

        /* #body_div{
            position: relative;
        } */
        .table_one,
        .table_four {
            /* position: absolute;
            left: 0; */
            float: left;
            font-size: 0.7rem;
        }

        .table_two,
        .table_five {
            /* position: absolute;
            right: 0; */
            float: right;
            font-size: 0.7rem;
        }

        .table_three {
            clear: both;
            width: 100%;
            border: 1px solid rgb(110, 110, 110);
            border-radius: 2px;
            text-align: center;
            font-size: 0.8rem;
            margin-bottom: 10px;
            /* border-collapse: collapse; */
        }

        .table_three td,
        .table_three th {
            border: 1px solid rgb(110, 110, 110);

        }
    </style>
</head>

<body>
    {{-- {{ dd($results) }} --}}
    @if (count($data) > 0)
        @foreach ($data as $results)
            <div class="container">
                <div class="header_div">
                    <p>SRI HEMA INFOTECH</p>
                    <p style="margin-top:-30px;">No: 1A, 2nd Floor, Paper Mills Road, Perambur, Chennai, Tamil Nadu 600082</p>
                    <p style="margin-top:-15px;font-weight:bold;"> Payment Slip for the month of <span>{{ $results->month }}  {{ $results->year }}</span>
                    </p>
                    <hr style="border:0.2px solid rgb(97, 97, 97);margin-top:-10px;">
                </div>
                <table style="padding-left:1rem;margin-top:-5px;" class="table_one">
                    <tr>
                        <td>Employee ID</td>
                        <td style="padding-left:1.5rem;"><span
                                style="padding-right:1rem;">:</span>{{ $results->employee_id }}</td>
                    </tr>
                    <tr>
                        <td>Designation</td>
                        <td style="padding-left:1.5rem;"><span
                                style="padding-right:1rem;">:</span>{{ $results->designation }}</td>
                    </tr>
                    <tr>
                        <td>PF Number</td>
                        <td style="padding-left:1.5rem;"><span style="padding-right:1rem;">:</span>-</td>
                    </tr>
                    <tr>
                        <td>Cheque No</td>
                        <td style="padding-left:1.5rem;"><span style="padding-right:1rem;">:</span>-</td>
                    </tr>
                </table>
                <table style="padding-right:1rem;margin-top:-5px;" class="table_two">
                    <tr>
                        <td>Employee Name</td>
                        <td style="padding-left:1.5rem;"><span style="padding-right:1rem;">:</span>{{ $results->name }}
                        </td>
                    </tr>
                    <tr>
                        <td>Bank Name</td>
                        <td style="padding-left:1.5rem;"><span
                                style="padding-right:1rem;">:</span>{{ $results->bankname }}</td>
                    </tr>
                    {{-- <tr>
                        <td>DOJ</td>
                        <td style="padding-left:1.5rem;"><span style="padding-right:1rem;">:</span>-</td>
                    </tr>
                    <tr>
                        <td>Bank Name</td>
                        <td style="padding-left:1.5rem;"><span
                                style="padding-right:1rem;">:</span>{{ $results->bankname }}</td>
                    </tr> --}}
                </table>
                <table class="table_three">
                    <thead>
                        <tr>
                            <th>Earnings</th>
                            <th>Amount</th>
                            <th>Deductions</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight:100;">Basic</td>
                            <td>{{ $results->basicpay }}</td>
                            <td style="font-weight:100;">EPF</td>
                            <td>{{ $results->epf }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">AGP</td>
                            <td>{{ $results->agp }}</td>
                            <td style="font-weight:100;">ESI</td>
                            <td>{{ $results->esi }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">DA</td>
                            <td>{{ $results->da }}</td>
                            <td style="font-weight:100;">IT</td>
                            <td>{{ $results->it }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">Conveyance</td>
                            <td>{{ $results->conveyance }}</td>
                            <td style="font-weight:100;">PT</td>
                            <td>{{ $results->pt }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">Special Pay</td>
                            <td>{{ $results->specialpay }}</td>
                            <td style="font-weight:100;">Salary Advance</td>
                            <td>{{ $results->salaryadvance }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">Arrears</td>
                            <td>{{ $results->arrears }}</td>
                            <td style="font-weight:100;">Other Deductions</td>
                            <td>{{ $results->otherdeduction }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">Other Allowance</td>
                            <td>{{ $results->otherall }}</td>
                            <td style="font-weight:100;">Total Deduction</td>
                            <td>{{ $results->totaldeductions }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">ABI</td>
                            <td>{{ $results->abi }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">Ph.D Allowance</td>
                            <td>{{ $results->phdallowance }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">Total Earnings</td>
                            <td>{{ $results->earnings }}</td>
                            <td style="font-weight:100;">LOP</td>
                            <td>{{ $results->lop }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:100;">Date</td>
                            <td>{{ $results->date }}</td>
                            <td style="font-weight:100;">Netpay</td>
                            <td>{{ $results->netpay }}</td>
                        </tr>

                    </tbody>
                </table>
                <table style="padding-left:1rem;padding-top:1.5rem;padding-bottom:1rem;" class="table_four">
                    <tr>
                        <td>Prepared By</td>
                    </tr>
                </table>
                <table style="padding-right:1rem;padding-top:1.5rem;padding-bottom:1rem;" class="table_five">
                    <tr>
                        <td>Authorized Signatory</td>
                    </tr>
                </table>
                <div style="clear:both;"></div>
            </div>
        @endforeach
    @endif
</body>

</html>
