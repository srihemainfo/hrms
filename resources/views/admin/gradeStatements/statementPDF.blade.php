@php
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 360);
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>
        Consolidated Statements
    </title>
</head>

<body>
    @foreach ($students as $data)
        @php
            if ($data->personal_details->dob != null) {
                $makeDob = date_create($data->personal_details->dob);
                $dob = date_format($makeDob, 'd-m-Y');
            } else {
                $dob = '';
            }
            $count = count($data->subjectDetail);
            if ($count > 38) {
                $pages = 2;
                $printCount = 38;
            } else {
                $pages = 1;
                $printCount = $count;
            }
            $classification = '';
            if ($data->cgpa >= 6.0) {
                $classification = 'FIRST CLASS';
            } elseif ($data->cgpa >= 5.0) {
                $classification = 'SECOND CLASS';
            } elseif ($data->cgpa >= 3.0) {
                $classification = 'THIRD CLASS';
            } elseif ($data->cgpa < 3.0) {
                $classification = 'PASS';
            }
            $image = $data->documents->filePath != null ? public_path($data->documents->filePath) : public_path('');
        @endphp

        <div style="height:1000px;margin-left:-30px;margin-right:-30px;">
            <div style="position: relative;">
                <div style="top:200px;left:245px;position:absolute;">{{ $data->name }}</div>
                <div style="top:201px;left:930px;position:absolute;">{{ $data->register_no }}</div>
                <div style="top:201px;left:1385px;position:absolute;">{{ $data->theRegulation }}</div>
                <div style="top:225px;left:245px;position:absolute;">{{ $dob }}</div>
                <div style="top:225px;left:930px;position:absolute;">{{ strtoupper($data->personal_details->gender) }}
                </div>
                <div style="top:225px;left:1385px;position:absolute;">
                    {{ strtoupper($data->lastMonth) . ' ' . $data->lastYear }}</div>
                <div style="top:249px;left:245px;position:absolute;">{{ $data->theCourse }}</div>
                <div style="width: 100%;top:295px;position:absolute;font-size:0.9rem;">
                    <table style="width: 95%;margin:auto;">
                        <tr>
                            @php
                                $subjectDetail = $data->subjectDetail;
                            @endphp
                            @for ($k = 1; $k <= $pages; $k++)
                                <td style="width:50%;">
                                    <table style="width:100%;">
                                        @for ($i = 0; $i < $printCount; $i++)
                                            <tr>
                                                <td style="width:3%;text-align:center;">
                                                    0{{ $subjectDetail[$i]->semester }}</td>
                                                <td style="width:5%;text-align:center;">
                                                    {{ $subjectDetail[$i]->getSubject != null ? $subjectDetail[$i]->getSubject->subject_code : '' }}
                                                </td>
                                                <td style="width:25%;padding-left:10px;">
                                                    {{ $subjectDetail[$i]->getSubject != null ? $subjectDetail[$i]->getSubject->name : '' }}
                                                </td>
                                                <td style="width:3%;text-align:center;">
                                                    {{ $subjectDetail[$i]->getSubject != null ? $subjectDetail[$i]->getSubject->credits : '' }}
                                                </td>
                                                <td style="width:3%;text-align:center;">
                                                    {{ $subjectDetail[$i]->getGrade != null ? $subjectDetail[$i]->getGrade->grade_letter : '' }}
                                                </td>
                                                <td style="width:3%;text-align:center;">
                                                    {{ $subjectDetail[$i]->getGrade != null ? $subjectDetail[$i]->getGrade->grade_point : '' }}
                                                </td>
                                                <td style="width:8%;text-align:center;">
                                                    {{ $subjectDetail[$i]->exam_date }}</td>
                                            </tr>
                                        @endfor
                                        @if ($k == $count)
                                            <tr>
                                                <td colspan="7">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td style="font-size:1rem;text-align:center;"><b>*** End Of Statement
                                                        ***</b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td style="font-size:1rem;text-align:center;"><b>Cumulative Grade Point
                                                        Average :
                                                        {{ $data->cgpa }}</b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td style="font-size:1rem;text-align:center;"><b>Classification :
                                                        {{ $classification }}</b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                        @php
                                            if ($pages > 1) {
                                                $i = $printCount;
                                                $printCount = $count;
                                            }
                                        @endphp
                                    </table>
                                </td>
                                @if ($count <= 38)
                                    <td style="width:50%;">&nbsp;</td>
                                @endif
                            @endfor
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>
