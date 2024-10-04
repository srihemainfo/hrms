@php
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 360);
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>
        Grade Sheets
    </title>
</head>

<body>
    @foreach ($datas as $data)
        @php
            if ($data['dob'] != null) {
                $makeDob = date_create($data['dob']);
                $dob = date_format($makeDob, 'd-m-Y');
            } else {
                $dob = '';
            }
            if ($data['published_date'] != null) {
                $makeDob = date_create($data['published_date']);
                $published_date = date_format($makeDob, 'd-m-Y');
            } else {
                $published_date = '';
            }

            $count = count($data['subjectDetail']);
            if ($count > 27) {
                $pages = ceil($count / 27);
            } else {
                $pages = 1;
            }
            // $image = $data->filePath != null ? public_path($data->filePath) : public_path('');
        @endphp

        @for ($k = 1; $k <= $pages; $k++)
            <div style="height:1000px;margin-left:-30px;margin-right:-30px;font-size:0.9rem;">
                <div style="position: relative;">
                    <div style="top:118px;left:112px;position:absolute;">{{ $data['name'] }}</div>
                    <div style="top:119px;left:542px;position:absolute;">{{ $data['register_no'] }}</div>
                    <div style="top:150px;left:112px;position:absolute;">{{ $dob }}</div>
                    <div style="top:151px;left:542px;position:absolute;">{{ strtoupper($data['gender']) }}
                    </div>
                    <div style="top:180px;left:112px;position:absolute;">{{ strtoupper($data['exam_date']) }}</div>
                    <div style="top:181px;left:542px;position:absolute;">{{ $published_date }}</div>
                    <div style="top:206px;left:112px;position:absolute;">{{ $data['course'] }}</div>
                    <div style="top:207px;left:542px;position:absolute;">{{ $data['regulation'] }}</div>
                    {{-- <div style="top:120px;left:666px;position:absolute;"><img src="{{ $image }}" alt="Student Photo" style="width:100%;height:100px;"></div> --}}

                    <table style="width:100%;top:267px;left:0px;right:0;position:absolute;">
                        @php
                            if ($pages > 1) {
                                $theCount = 27 * $k;
                                if ($k > 1) {
                                    $initialCount = 27 * ($k - 1);
                                }
                                if ($theCount > $count) {
                                    $theCount = $count;
                                }
                            } else {
                                $theCount = $count;
                            }
                            $initialCount = 0;
                            $subData = $data['subjectDetail'];
                        @endphp
                        @for ($i = $initialCount; $i < $theCount; $i++)
                            <tr>
                                <td style="text-align:center;width:5%;">0{{ $subData[$i]->semester }}</td>
                                <td style="text-align:center;width:12%;">
                                    {{ $subData[$i]->getSubject ? $subData[$i]->getSubject->subject_code : '' }}</td>
                                <td style="text-align:left;width:57%;padding-left:10px;">
                                    {{ $subData[$i]->getSubject ? $subData[$i]->getSubject->name : '' }}
                                </td>
                                <td style="text-align:center;width:5%;">
                                    {{ $subData[$i]->getSubject ? $subData[$i]->getSubject->credits : '' }}
                                </td>
                                <td style="text-align:center;width:5%;">
                                    {{ $subData[$i]->getGrade ? $subData[$i]->getGrade->grade_letter : '' }}
                                </td>
                                <td style="text-align:center;width:5%;">
                                    {{ $subData[$i]->getGrade ? $subData[$i]->getGrade->grade_point : '' }}
                                </td>
                                <td style="text-align:center;width:8%;">
                                    {{ $subData[$i]->getGrade ? $subData[$i]->getGrade->result : '' }}
                                </td>
                            </tr>
                        @endfor

                    </table>
                    <table style="width:100%;top:819px;left:0;right:0;position:absolute;text-align:center;">
                        <tr>
                            <td style="width:17%;"></td>
                            <td style="width:10.37%;padding-bottom:8px;padding-top:1px;">
                                {{ $data['semOne']['registered'] }} </td>
                            <td style="width:10.37%;">{{ $data['semTwo']['registered'] }}</td>
                            <td style="width:10.37%;">{{ $data['semThree']['registered'] }}</td>
                            <td style="width:10.37%;">{{ $data['semFour']['registered'] }}</td>
                            <td style="width:10.37%;">{{ $data['semFive']['registered'] }}</td>
                            <td style="width:10.37%;">{{ $data['semSix']['registered'] }}</td>
                            <td style="width:10.37%;">{{ $data['semSeven']['registered'] }}</td>
                            <td style="width:10.37%;">{{ $data['semEight']['registered'] }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding-bottom:5px;">{{ $data['semOne']['earned'] }}</td>
                            <td style="width:10.37%;">{{ $data['semTwo']['earned'] }}</td>
                            <td style="width:10.37%;">{{ $data['semThree']['earned'] }}</td>
                            <td style="width:10.37%;">{{ $data['semFour']['earned'] }}</td>
                            <td style="width:10.37%;">{{ $data['semFive']['earned'] }}</td>
                            <td style="width:10.37%;">{{ $data['semSix']['earned'] }}</td>
                            <td style="width:10.37%;">{{ $data['semSeven']['earned'] }}</td>
                            <td style="width:10.37%;">{{ $data['semEight']['earned'] }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding-bottom:5px;">{{ $data['semOne']['points'] }}</td>
                            <td style="width:10.37%;">{{ $data['semTwo']['points'] }}</td>
                            <td style="width:10.37%;">{{ $data['semThree']['points'] }}</td>
                            <td style="width:10.37%;">{{ $data['semFour']['points'] }}</td>
                            <td style="width:10.37%;">{{ $data['semFive']['points'] }}</td>
                            <td style="width:10.37%;">{{ $data['semSix']['points'] }}</td>
                            <td style="width:10.37%;">{{ $data['semSeven']['points'] }}</td>
                            <td style="width:10.37%;">{{ $data['semEight']['points'] }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding-bottom:5px;">
                                @if ($data['semOne']['earned'] > 0)
                                    {{ round($data['semOne']['sum'] / $data['semOne']['earned'], 2) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if ($data['semTwo']['earned'] > 0)
                                    {{ round($data['semTwo']['sum'] / $data['semTwo']['earned'], 2) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if ($data['semThree']['earned'] > 0)
                                    {{ round($data['semThree']['sum'] / $data['semThree']['earned'], 2) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if ($data['semFour']['earned'] > 0)
                                    {{ round($data['semFour']['sum'] / $data['semFour']['earned'], 2) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if ($data['semFive']['earned'] > 0)
                                    {{ round($data['semFive']['sum'] / $data['semFive']['earned'], 2) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if ($data['semSix']['earned'] > 0)
                                    {{ round($data['semSix']['sum'] / $data['semSix']['earned'], 2) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if ($data['semSeven']['earned'] > 0)
                                    {{ round($data['semSeven']['sum'] / $data['semSeven']['earned'], 2) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if ($data['semEight']['earned'] > 0)
                                    {{ round($data['semEight']['sum'] / $data['semEight']['earned'], 2) }}
                                @else
                                    0
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{ $data['allCredits'] }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ round($data['cgpa'], 2) }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        @endfor
    @endforeach
</body>

</html>
