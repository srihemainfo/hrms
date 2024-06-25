@php
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 3000);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Exam Registration Pdf
    </title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />


    <style>
        .table thead th {
            /* vertical-align: bottom; */
            border-bottom: none;
        }

        .table th,
        .table td {
            border-top: none;
        }

        .>.the {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        .table td,
        .table td {
            padding: none;
        }

        .ft .table td,
        .ft .table th {
            padding: 0rem;
            vertical-align: middle;
            /* border-top: 1px solid #dee2e6; */
        }

        .table td,
        .table th {
            padding-top: 0px;
            padding-bottom: 0px;
            padding-left: 0px;
            padding-right: 0px;
            font-size: .55rem;
        }

        .border-rl {
            /* border-right: 1px solid black; */
            border-left: 1px solid black;

        }

        body {
            margin-top: 2rem;
        }
    </style>
</head>

<body>

    @foreach ($datas as $data)
        <div style="height:950px;">
            <div style="text-align:center;">
                <h5>REGISTRATION PREVIEW</h5>
            </div>
            <div style="text-align:center;">
                <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Image Description"
                    style="width:30%; margin-top: 10px;">
            </div>
            <div style="text-align:center;margin-top:10px;margin-bottom:15px;">
                <b>Application for {{ $data['exam_date'] }} Examination</b>
            </div>

            <table class="ft table" style="border:1.3px solid black;width:100%;">
                <thead>
                    <tr>
                        <td>
                            <table class="table" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th class='pl-1'>Name of the Candidate</th>
                                        <td>:</td>
                                        <td class='text-left pl-1' align="right">{{ $data['student_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class='pl-1'>Degree & Branch</th>
                                        <td>:</td>
                                        <td class='text-left pl-1' align="right">{{ $data['course'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class='pl-1'>Academic Year</th>
                                        <td>:</td>
                                        <td class='text-left pl-1' align="right">{{ $data['ay'] }}</td>
                                    </tr>
                                </thead>
                            </table>

                        </td>
                        <td style='border-left:1px solid black;'>
                            <table class="table" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th class='pl-1'>Register No</th>
                                        <td>:</td>
                                        <td class='text-left pl-1' align="left">
                                            {{ $data['register_no'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class='pl-1'>Date Of Birth</th>
                                        <td>:</td>
                                        <td class='text-left pl-1' align="right">
                                            {{ $data['formattedDate'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class='pl-1'>Regulation</th>
                                        <td>:</td>
                                        <td class='text-left pl-1' align="right">
                                            {{ $data['regulation'] }}</td>
                                    </tr>
                                </thead>
                            </table>

                        </td>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td style='border-bottom:1px solid black;border-top:1px solid black;width:50%;'>
                            <table class="table" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th class="text-center"
                                            style="border-right:1px solid black; border-bottom:1px solid black;">Sem No
                                        </th>
                                        <th class="text-center"
                                            style="border-right:1px solid black; border-bottom:1px solid black;">Subject
                                            Code</th>
                                        <th class="text-center" style="border-bottom:1px solid black;">Subject Title
                                            (Regular)
                                        </th>
                                    </tr>
                                    {!! $data['regularSubjectRows'] !!}
                                </thead>

                            </table>

                        </td>
                        <td
                            style='border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;width:50%;'>

                            <table class="table" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th class="text-center"
                                            style="border-right:1px solid black; border-bottom:1px solid black;">Sem No
                                        </th>
                                        <th class="text-center"
                                            style="border-right:1px solid black;border-bottom:1px solid black;">Subject
                                            Code
                                        </th>
                                        <th class="text-center" style="border-bottom:1px solid black;">Subject
                                            Title (Arrear Exam - If Any)</th>
                                    </tr>
                                    {!! $data['arrearSubjectRows'] !!}
                                </thead>

                            </table>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table" style="margin-bottom:0px;">
                                <thead style="border-bottom:1px solid black;">
                                    <tr>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th class="pr-1" style="text-align:right;">Total Credits:
                                            {{ $data['regularCredits'] }}
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </td>
                        <td style='border-left:1px solid black;'>
                            <table class="table" style="margin-bottom:0px;">
                                <thead style="border-bottom:1px solid black;">
                                    <tr>
                                        <th class="pr-1" style="text-align:right;">Total Credits:
                                            {{ $data['arrearCredits'] }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class='pr-1' style="text-align:right;">Grand Total Credits:
                                            {{ $data['regularCredits'] + $data['arrearCredits'] }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th style="border-bottom: 1px solid black;">
                            <table class="table" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th class="pl-1">Mobile No:</th>
                                        <th class='text-left pl-2' align="right">
                                            {{ $data['mobile'] }}</th>
                                    </tr>
                                    <tr>
                                        <th class="pl-1">Email:</th>
                                        <th class='text-left pl-2' align="right">
                                            {{ $data['email'] }}</th>
                                    </tr>
                                </thead>
                            </table>

                        </th>
                        <th style='border-left:1px solid black;border-bottom: 1px solid black;'>
                            <table class="table" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th class='text-right pr-1'>No Of Papers: &nbsp; {{ $data['totalPaper'] }}</th>
                                    </tr>
                                    <tr>
                                        <th class='text-right pr-1'>Total Amount: &nbsp; {{ $data['exam_fee'] }}</th>
                                    </tr>
                                </thead>
                            </table>

                        </th>
                    </tr>
                    <tr>
                        <th style=' border-bottom:1px solid black;'>

                            <table class="table" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th class="pb-3 ml-5 pl-1">I hereby declare that the particulars furnished by me
                                            in
                                            this
                                            application are correct </th>
                                    </tr>
                                    <tr>
                                        <th class="pb-3"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right pr-1 pb-1"> Signature of the Candidate with Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </th>
                        <th style='border-left:1px solid black; border-bottom:1px solid black;'>
                            <table class="table" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th class="pb-3 pl-1">&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th class="pb-3"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right pr-1 pb-1">Signature of the Head of the Department with
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
</body>

</html>
