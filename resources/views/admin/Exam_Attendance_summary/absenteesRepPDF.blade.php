@php
$uniqueDepts = [];
$uniqueDates = [];
$uniqueSubjects = [];
@endphp
@foreach($data as $value)
@php
$key = $value['Class'] ?? [];
$dept = explode('/', $key)[0];
$date = $value['date'] ?? [];
$formattedDate = date('d-m-y', strtotime($date));
$subject = $value['subject'] ?? [];
$parts = explode('(', $subject);
$subject_code = '(' . end($parts);
@endphp

@if (!in_array($dept, $uniqueDepts))
@php
$uniqueDepts[] = $dept;
@endphp
@endif


@if (!in_array($formattedDate, $uniqueDates))
@php
$uniqueDates[] = $formattedDate;
@endphp
@endif


@if (!in_array($subject_code, $uniqueSubjects))
@php
$uniqueSubjects[] = $subject_code;
@endphp
@endif



@endforeach




<!DOCTYPE html>
<html>

<head>
    <title>
        @foreach($uniqueSubjects as $uniqueSubject)
        {{ $uniqueSubject ?? ''}} ABSENTEES SUMMARY
        @endforeach
    </title>

</head>

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
        padding: 10px;
        border: 1px solid #dddddd;
    }

    /* Style unordered lists within table cells */
    td ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    td ul li {
        margin-bottom: 5px;
    }

    /* Add some spacing below the table */
    table+p {
        margin-top: 20px;
    }

    ul li {
        list-style: none;
    }

    table,
    tr,
    th,
    td {
        border: 1px solid black;
        text-align: center;

    }

    th {
        font-size: 15px;
    }

    td {
        font-size: 12px;
    }
</style>


<body>

<table style='border:none'>
    <thead>
        <tr style='border:none' >
            <td colspan='' style='border:none;float:left;' >
            <div style='margin-top:-60px;'>
                <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Image Description" style="width: 150px; height: 70px; margin-top: 20px;">
            </div>
            </td>
            <td colspan='' style='border:none;text-align:center;'>
            @foreach($data2 as $row )
                <h3 style='margin-right:90px'> {{ $row['course_title'] }} </h3>
                <h3 style='margin-right:100px'> {{ $row['department_title'] }} </h3>
                <h3 style='margin-right:70px'> {{ $row['analysis'] }} </h3>
                <h3 style='margin-right:70px'> {{ $row['assessment_title'] }} </h3>
            @endforeach
            </td>
        </tr>
    </thead>
   </table>
    <div style="text-align:right;">
        @foreach($uniqueDates as $uniqueDate)
        <p> Date : {{ $uniqueDate ?? ''}}</p>
        @endforeach
    </div>


    <table style="font-size:0.6rem;width:100%;">
        <thead>
            <tr>
                <th>Class</th>
                <th>Subject</th>
                <th>Absent Students <br> (Roll Number) </th>
                <th>Total Counts</th>
                <th>Total Presents</th>
                <th>Total Absents</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row['Class'] }}</td>
                <td>{{ $row['subject'] }}</td>
                

                <td>
                    <ul>
                        @foreach($row['Absent Students'] as $key => $student)
                        <li style ='text-align:left'>{{ $student }}  <span style = 'white-space:nowrap;'>  ({{ $row['Student Register Number'][$key] }}) </span></li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $row['totalCount'] }}</td>
                <td>{{ $row['totalPres'] }}</td>
                <td>{{ $row['totalAbs'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>  
</body>

</html>