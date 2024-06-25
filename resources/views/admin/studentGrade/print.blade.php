<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Grade Book</title>
</head>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

<body>
    <div style="text-align: center"><img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Institute Logo"
            style="width:30%;margin:auto;"></div>
    <p style="text-align:center;"><b>Grade Book</b></p>
    <table style="width: 100%;">
        <tr>
            <th>Academic Year</th>
            <th>Semester</th>
            <th>Subject Code</th>
            <th>Subject Title</th>
            <th>Grade</th>
            <th>Result</th>
            <th>Exam Month And Year</th>
        </tr>
        @forelse ($getData as $data)
            <tr>
                <td style="text-align: center;">{{ $data->getAy->name }}</td>
                <td style="text-align: center;">0{{$data->semester }}</td>
                <td style="text-align: center;">{{ $data->getSubject->subject_code }}</td>
                <td style="padding-left:5px;">{{ $data->getSubject->name }}</td>
                <td style="text-align: center;">{{ $data->getGrade->grade_letter }}</td>
                <td style="text-align: center;">{{ $data->getGrade->result }}</td>
                <td style="text-align: center;">{{ $data->exam_date }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No Data Available</td>
            </tr>
        @endforelse
    </table>
</body>

</html>
