<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - Wise Result Analysis</title>

    <style>
        @page {
            border: 1px solid #000;
        }

        /* Apply styles to the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        /* Style table headers */
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 10px;
        }

        /* Style table rows */
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Style table cells */
        td {
            border: 1px solid #dddddd;
        }

        

        table,
        tr,
        th,
        td {
            border: 1px solid black;
            text-align: center;

        }

        th {
            font-size: 12px;
        }

        td {
            font-size: 12px;
        }

        tr,
        td,
        th {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <table style='border:none' >
        <thead>
            <tr style='border:none'>
                <td colspan='' style='border:none;float:left;'>
                    <div style='margin-top:-60px;'>
                        <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Image Description" style="width: 150px; height: 70px; margin-top: 20px;">
                    </div>
                </td>
                <td colspan='' style='border:none;'>
                   
                    <h3> {{ isset($student[0]->classname) ? $student[0]->classname : '' }} </h3>
                    <h3> {{ isset($student[0]->subjectName) ? $student[0]->subjectName : '' }}</h3>
                    <h3> Staff Wise Result Report</h3>
                    

                </td>

            </tr>
        </thead>
    </table>
    <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark">
        <thead>
            <tr>
                {{-- <th colspan="4">Exam Name : {{ $examName ?? '' }}</th> --}}
                <th colspan="{{ $count + 3}}">{{ isset($student[0]->classname) ? $student[0]->classname : '' }}</th>


            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="{{ ($count + 3) /2}}"><strong>Subject : </strong></td>
                <td colspan="{{ ($count + 3) /2}}">
                    <strong>{{ isset($student[0]->subjectName) ? $student[0]->subjectName : '' }}</strong>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                @if (isset($student[0]->co_1Name))
                <td><strong> </strong><span>{{ $student[0]->co_1Name }}</span></strong></td>
                @endif
                @if (isset($student[0]->co_2Name))
                <td><strong> <span> {{ $student[0]->co_2Name }}</span> </strong></td>
                @endif
                @if (isset($student[0]->co_3Name))
                <td><strong> <span>{{ $student[0]->co_3Name }} </span> </strong></td>
                @endif
                @if (isset($student[0]->co_4Name))
                <td><strong> <span> {{ $student[0]->co_4Name }}</span> </strong></td>
                @endif
                @if (isset($student[0]->co_5Name))
                <td><strong> <span> {{ $student[0]->co_5Name }}</span> </strong></td>
                @endif

                <td></td>
            </tr>

            <tr>
                <td><strong>Student Name</strong></td>
                <td><strong>Register No</strong></td>
                @if (isset($student[0]->co_1Mark))
                <td><strong> <span> CO-1 <br>({{ $student[0]->co_1Mark }}) </span> </strong></td>
                @endif
                @if (isset($student[0]->co_2Mark))
                <td><strong> <span> CO-2 <br>({{ $student[0]->co_2Mark }}) </span></strong></td>
                @endif
                @if (isset($student[0]->co_3Mark))
                <td><strong> <span> CO-3 <br> ({{ $student[0]->co_3Mark }}) </span></strong></td>
                @endif
                @if (isset($student[0]->co_4Mark))
                <td><strong> <span> CO-4 <br>({{ $student[0]->co_4Mark }}) </span> </strong></td>
                @endif
                @if (isset($student[0]->co_5Mark))
                <td><strong> <span> CO-5 <br>({{ $student[0]->co_5Mark }}) </span> </strong></td>
                @endif
                <td><strong>Total <br>
                        @php
                        // dd($student[0]->co_1Mark , ($student[0]->co_2Mark ?? 0) );
                        $totalmark = ($student[0]->co_1Mark ?? 0) + ($student[0]->co_2Mark ?? 0) + ($student[0]->co_3Mark ?? 0) + ($student[0]->co_4Mark ?? 0) + ($student[0]->co_5Mark ?? 0);
                        @endphp
                        ({{ $totalmark }})
                    </strong></td>

            </tr>

            <input type="hidden" name="exame_name" value="{{ $examId ?? '' }}">
            <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
            <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">
            @php
            $array = [];
            // $totalStudent
            $co_1colmPassMark = 0;
            $co_1colmFailMark = 0;

            $co_2colmPassMark = 0;
            $co_2colmFailMark = 0;

            $co_3colmPassMark = 0;
            $co_3colmFailMark = 0;

            $co_4colmPassMark = 0;
            $co_4colmFailMark = 0;

            $co_5colmPassMark = 0;
            $co_5colmFailMark = 0;
            @endphp
            @forelse ($student as $exameDatas)
            <tr>
                <td>{{ $exameDatas->name ?? '' }}</td>
                <td>{{ $exameDatas->register_no ?? '' }}</td>
                @if (isset($student[0]->co_1Mark))
                <td class="">{{ $exameDatas->co_1 == '999' ? 'Absent' : $exameDatas->co_1 }}
                    @php
                    if ($exameDatas->co_1 != '999') {
                    $percentage = ($exameDatas->co_1 / $student[0]->co_1Mark ?? 1) * 100;
                    if ($percentage >= 50) {
                    $co_1colmPassMark++;
                    }
                    if ($percentage < 50) { $co_1colmFailMark++; } } @endphp </td>
                        @endif

                        @if (isset($student[0]->co_2Mark))
                <td class="">{{ $exameDatas->co_2 == '999' ? 'Absent' : $exameDatas->co_2 }}
                    @php
                    if ($exameDatas->co_2 != '999') {
                    $percentage = ($exameDatas->co_2 / $student[0]->co_2Mark ?? 1) * 100;
                    if ($percentage >= 50) {
                    $co_2colmPassMark++;
                    }
                    if ($percentage < 50) { $co_2colmFailMark++; } } @endphp </td>
                        @endif

                        @if (isset($student[0]->co_3Mark))
                <td class="">{{ $exameDatas->co_3 == '999' ? 'Absent' : $exameDatas->co_3 }}
                    @php
                    if ($exameDatas->co_3 != '999') {
                    $percentage = ($exameDatas->co_3 / $student[0]->co_3Mark ?? 1) * 100;
                    if ($percentage >= 50) {
                    $co_3colmPassMark++;
                    }
                    if ($percentage < 50) { $co_3colmFailMark++; } } @endphp </td>
                        @endif

                        @if (isset($student[0]->co_4Mark))
                <td class="">{{ $exameDatas->co_4 == '999' ? 'Absent' : $exameDatas->co_4 }}
                    @php
                    if ($exameDatas->co_4 != '999') {
                    $percentage = ($exameDatas->co_4 / $student[0]->co_4Mark ?? 1) * 100;
                    if ($percentage >= 50) {
                    $co_4colmPassMark++;
                    }
                    if ($percentage < 50) { $co_4colmFailMark++; } } @endphp </td>
                        @endif

                        @if (isset($student[0]->co_5Mark))
                <td class="">{{ $exameDatas->co_5 == '999' ? 'Absent' : $exameDatas->co_5 }}
                    @php
                    if ($exameDatas->co_5 != '999') {
                    $percentage = ($exameDatas->co_5 / $student[0]->co_5Mark ?? 1) * 100;
                    if ($percentage >= 50) {
                    $co_5colmPassMark++;
                    }
                    if ($percentage < 50) { $co_5colmFailMark++; } } @endphp </td>
                        @endif

                        @php
                        $singleTotal = number_format((100 * ((isset($exameDatas->co_1) && $exameDatas->co_1 != '999' ? $exameDatas->co_1 : 0) +
                        (isset($exameDatas->co_2) && $exameDatas->co_2 != '999' ? $exameDatas->co_2 : 0) +
                        (isset($exameDatas->co_3) && $exameDatas->co_3 != '999' ? $exameDatas->co_3 : 0) +
                        (isset($exameDatas->co_4) && $exameDatas->co_4 != '999' ? $exameDatas->co_4 : 0) +
                        (isset($exameDatas->co_5) && $exameDatas->co_5 != '999' ? $exameDatas->co_5 : 0))) / ($totalmark != '0'?$totalmark: 1), 2);

                        $single = (isset($exameDatas->co_1) && $exameDatas->co_1 != '999' ? $exameDatas->co_1 : 0) +
                        (isset($exameDatas->co_2) && $exameDatas->co_2 != '999' ? $exameDatas->co_2 : 0) +
                        (isset($exameDatas->co_3) && $exameDatas->co_3 != '999' ? $exameDatas->co_3 : 0) +
                        (isset($exameDatas->co_4) && $exameDatas->co_4 != '999' ? $exameDatas->co_4 : 0) +
                        (isset($exameDatas->co_5) && $exameDatas->co_5 != '999' ? $exameDatas->co_5 : 0);
                        @endphp


                <td style="{{ $singleTotal < 50 ? 'background-color: tomato;' : '' }}">
                    @if (
                    (isset($exameDatas->co_1) && $exameDatas->co_1 != '999' ? $exameDatas->co_1 : 0) +
                    (isset($exameDatas->co_2) && $exameDatas->co_2 != '999' ? $exameDatas->co_2 : 0) +
                    (isset($exameDatas->co_3) && $exameDatas->co_3 != '999' ? $exameDatas->co_3 : 0) +
                    (isset($exameDatas->co_4) && $exameDatas->co_4 != '999' ? $exameDatas->co_4 : 0) +
                    (isset($exameDatas->co_5) && $exameDatas->co_5 != '999' ? $exameDatas->co_5 : 0) >=
                    999)
                    0
                    @else
                    {{ $single }}

                    @php
                    array_push($array, $singleTotal);
                    @endphp
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No data Found</td>
            </tr>
            @endforelse

            <tr>
                <td colspan="{{ $count + 3}}"><strong>Summary</strong></td>

            </tr>
            <tr>
                <td colspan="2"><strong>Total No Of Students</strong> </td>
                @if (isset($student[0]->co_1Present))
                <td>{{ $student[0]->co_1Present + $student[0]->co_1Absent ?? 0 }}</td>
                @endif
                @if (isset($student[0]->co_2Present))
                <td>{{ $student[0]->co_2Present + $student[0]->co_2Absent ?? 0 }}</td>
                @endif
                @if (isset($student[0]->co_3Present))
                <td>{{ $student[0]->co_3Present + $student[0]->co_3Absent ?? 0 }}</td>
                @endif
                @if (isset($student[0]->co_4Present))
                <td>{{ $student[0]->co_4Present + $student[0]->co_4Absent ?? 0 }}</td>
                @endif
                @if (isset($student[0]->co_5Present))
                <td>{{ $student[0]->co_5Present + $student[0]->co_5Absent ?? 0 }}</td>
                @endif
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Total No Of Students Present</strong></td>
                @if (isset($student[0]->co_1Present))
                <td>{{ $student[0]->co_1Present }}</td>
                @endif
                @if (isset($student[0]->co_2Present))
                <td>{{ $student[0]->co_2Present }}</td>
                @endif
                @if (isset($student[0]->co_3Present))
                <td>{{ $student[0]->co_3Present }}</td>
                @endif
                @if (isset($student[0]->co_4Present))
                <td>{{ $student[0]->co_4Present }}</td>
                @endif
                @if (isset($student[0]->co_5Present))
                <td>{{ $student[0]->co_5Present }}</td>
                @endif
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><strong> Total No Of Students Absent</strong></td>
                @if (isset($student[0]->co_1Absent))
                <td>{{ $student[0]->co_1Absent ?? 0 }}</td>
                @endif
                @if (isset($student[0]->co_2Absent))
                <td>{{ $student[0]->co_2Absent ?? 0 }}</td>
                @endif
                @if (isset($student[0]->co_3Absent))
                <td>{{ $student[0]->co_3Absent ?? 0 }}</td>
                @endif
                @if (isset($student[0]->co_4Absent))
                <td>{{ $student[0]->co_4Absent ?? 0 }}</td>
                @endif
                @if (isset($student[0]->co_5Absent))
                <td>{{ $student[0]->co_5Absent ?? 0 }}</td>
                @endif
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Total No Of Students Pass</strong></td>
                @if (isset($student[0]->co_1Mark))
                <td>{{ $co_1colmPassMark }}</td>
                @endif
                @if (isset($student[0]->co_2Mark))
                <td>{{ $co_2colmPassMark }}</td>
                @endif
                @if (isset($student[0]->co_3Mark))
                <td>{{ $co_3colmPassMark }}</td>
                @endif
                @if (isset($student[0]->co_4Mark))
                <td>{{ $co_4colmPassMark }}</td>
                @endif
                @if (isset($student[0]->co_5Mark))
                <td>{{ $co_5colmPassMark }}</td>
                @endif
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><strong>No Of Students Fail</strong></td>
                @if (isset($student[0]->co_1Mark))
                <td>{{ $co_1colmFailMark }}</td>
                @endif
                @if (isset($student[0]->co_2Mark))
                <td>{{ $co_2colmFailMark }}</td>
                @endif
                @if (isset($student[0]->co_3Mark))
                <td>{{ $co_3colmFailMark }}</td>
                @endif
                @if (isset($student[0]->co_4Mark))
                <td>{{ $co_4colmFailMark }}</td>
                @endif
                @if (isset($student[0]->co_5Mark))
                <td>{{ $co_5colmFailMark }}</td>
                @endif
                <td></td>
            </tr>
            <td colspan="2"><strong>Pass percentage</strong></td>
            @if (isset($student[0]->co_1Present))
            <td>
                {{ $passPercentageCo_1 = number_format((($co_1colmPassMark ?? 0) / ($student[0]->co_1Present ?? 1)) * 100, 2) }}
            </td>
            @endif
            @if (isset($student[0]->co_2Mark))
            <td>
                {{ $passPercentageCo_2 = number_format((($co_2colmPassMark ?? 0) / ($student[0]->co_2Present ?? 1)) * 100, 2) }}
            </td>
            @endif
            @if (isset($student[0]->co_3Mark))
            <td>
                {{ $passPercentageCo_3 = number_format((($co_3colmPassMark ?? 0) / ($student[0]->co_3Present ?? 1)) * 100, 2) }}
            </td>
            @endif
            @if (isset($student[0]->co_4Mark))
            <td>
                {{ $passPercentageCo_4 = number_format((($co_4colmPassMark ?? 0) / ($student[0]->co_4Present ?? 1)) * 100, 2) }}
            </td>
            @endif
            @if (isset($student[0]->co_5Mark))
            <td>
                {{ $passPercentageCo_5 = number_format((($co_5colmPassMark ?? 0) / ($student[0]->co_5Present ?? 1)) * 100, 2) }}
            </td>
            @endif
            <td></td>
            </tr>

        </tbody>
    </table>


</body>

</html>