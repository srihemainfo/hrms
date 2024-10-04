@php
    set_time_limit(0); // 0 means no time limit
    ini_set('memory_limit', '-1');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result Publish sheet</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
        }

        table,
        th,
        td {
            text-align: center;
            padding: 0px;
        }

        .text-right {
            text-align: right;
        }

        .table-body {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <table class="table text-center">
        <thead>
            <tr>
                <td style='border:none;'>
                    <h6>Demo College Of Engineering & Technology</h6>
                    <p>(An Autonomous Institution)</p>
                    <h6>OFFICE OF THE CONTROLLER OF EXAMINATIONS</h6>
                    <h6>{{ strtoupper($data[0]['result_type']) }} Results of
                        {{ strtoupper(substr($data[0]['exam_month'], 0, 3)) }} {{ $data[0]['exam_year'] }} Examination
                    </h6>
                </td>
            </tr>
        </thead>
    </table>

    <table class='table-body'>
        <tr>
            <td class='text-left'>Course :&nbsp;{{ $data[0]['course'] ?? '' }}</td>
            <td class='text-right'>Semester :&nbsp; 0{{ $data[0]['semester'] }}</td>
        </tr>
    </table>
    <table class="table table-bordered p-0" style="width:100%;">
        <thead>
            <tr class='table-success align-middle' style="width:100%;font-size:0.7rem;">
                <th class='align-middle' style='padding: 5px 5px;'>Register No.</th>
                <th style='padding: 5px 5px;' class='align-middle'>Student Name</th>
                @foreach ($theSubjects as $id => $code)
                    <th style='text-align:center;padding: 5px 5px;'>{{ $code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $data)
                <tr class='align-middle m-auto' style="width:100%;font-size:0.7rem;">
                    <td style="padding: 5px 5px;" class='align-middle'>{{ $data['register_no'] }}</td>
                    <td style="font-size:0.7rem;padding: 5px 5px;" class='align-middle'>
                        {{ $data['student'] }} </td>

                    @foreach ($theSubjects as $id => $subject)
                        @if (array_key_exists($id, $data['subjects']))
                            <td style="text-align:center;padding:0;">{{ $data['subjects'][$id] }}</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach

                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width:100%;text-align:left;">
        <tbody>
            <tr>
                <td style="font-size:0.6rem; border:none;text-align:left;"><b>RA</b> – Reappearance is required</td>
                <td style="font-size:0.6rem; border:none;text-align:left;"><b>RA*</b> - Absent for End Exam</td>
                <td style="font-size:0.6rem; border:none;text-align:left;"><b>W/WD</b> – Withdrawal</td>
            </tr>
            <tr>
                <td style="font-size:0.6rem; border:none;text-align:left;"><b>SA</b> – Shortage of Attendance</td>
                <td style="font-size:0.6rem; border:none;text-align:left;"><b>SE</b> – Sports Exemption</td>
                <td style="font-size:0.6rem; border:none;text-align:left;"><b>WH1</b> – Suspected Malpractice</td>
            </tr>

            <tr>
                <td style="font-size:0.6rem; border:none;text-align:left;"><b>WH2</b> – Contact COE office</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
