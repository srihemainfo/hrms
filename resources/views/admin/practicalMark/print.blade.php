@php
    set_time_limit(0);
    ini_set('memory_limit', '-1');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Practical Marks</title>
    <style>
        .body {
            font-family: "Times New Roman", Times, serif;
        }

        .text-center {
            text-align: center;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    @php
        if ($detail['data'] != null) {
            $count = count($detail['data']);
            $pageCount = ceil($count / 35);
            $data = $detail['data'];
        }
        $start = 0;
        if ($count > 35) {
            $stop = 35;
        } else {
            $stop = $count;
        }
    @endphp

    @for ($i = 0; $i < $pageCount; $i++)
        <div style="width:100%;margin:auto;font-size:0.9rem;height:1025px;">
            <table style="width:100%;">
                <tr>
                    <td style="width:150px;">
                        <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Image Description"
                            style="width:100%;margin-top:-30px;">
                    </td>
                    <td>
                        <div style="padding-left:50px;">
                            <b>Demo College Of Engineering & Technology</b>
                        </div>
                        <div>(An Autonomous Insitution and Affliated to Anna University, Chennai)
                        </div>
                        <div style="padding-left:135px;">Kuthambakkam - 600124</div>
                        <div style="margin-top:10px;margin-bottom:10px;padding-left:10px;"><b>END SEMESTER PRACTICAL
                                EXAMINATIONS
                                -
                                {{ strtoupper($detail['exam_month']) }}
                                {{ $detail['exam_year'] }}</b></div>
                    </td>
                </tr>
            </table>
            <table style="width:100%;">
                <tr>
                    <td>
                        <p><b>Branch : </b> {{ $detail['course']['name'] }}</p>
                    </td>
                    <td>
                        <p><b>Date & Time : </b> </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Semester : </b> 0{{ $data[0]['subject_sem'] }}</p>
                    </td>
                    <td>
                        <p><b>Exam Type : </b> {{ $detail['exam_type'] }} </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Subject Code & Name : </b> {{ $detail['subject']['subject_code'] }} &
                            {{ $detail['subject']['name'] }}</p>
                    </td>
                    <td></td>
                </tr>
            </table>
            <div class="text-center" style="margin-top:10px;">
                <table style="border:1px solid black;border-collapse:collapse;width:100%;margin:auto;">

                    <tr>
                        <th style="border:1px solid black;text-align:center;">S.No</th>
                        <th style="border:1px solid black;text-align:center;">Register Number</th>
                        <th style="border:1px solid black;text-align:center;">Marks Awarded</th>
                        <th style="border:1px solid black;text-align:center;">Mark in Words</th>
                    </tr>
                    @for ($j = $start; $j < $stop; $j++)
                        <tr>
                            <td style="border:1px solid black;text-align:center;">{{ $j + 1 }}</td>
                            <td style="border:1px solid black;text-align:center;">
                                {{ $data[$j]['student']['register_no'] }}</td>
                            <td style="border:1px solid black;text-align:center;">
                                {{ $data[$j]['mark'] == -1 ? 'Absent' : $data[$j]['mark'] }}</td>
                            <td style="border:1px solid black;padding-left:20px;">{{ $data[$j]['mark_in_word'] }}</td>
                        </tr>
                    @endfor
                </table>
            </div>
            <table class="text-center" style="width:100%;padding-top:60px;">
                <tr>
                    <td>Internal Examiner</td>
                    <td>External Examiner</td>
                </tr>
            </table>
            <div style="text-align:right;font-size:0.7rem;">Page {{ $i + 1 }}/ {{ $pageCount }}</div>
        </div>
        @php
            $start = $stop;
            $calculate = $count - $stop;
            if ($calculate >= 35) {
                $stop += 35;
            } else {
                $stop += $calculate;
            }

        @endphp
    @endfor

</body>

</html>
