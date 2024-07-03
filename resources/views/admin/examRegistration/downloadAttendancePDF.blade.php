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
    <title>Attendance sheet</title>
    <style>
        .body {
            font-family: "Times New Roman", Times, serif;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    @if (count($data) > 0)
        @foreach ($data as $data)
            @if (count($data->data) > 0)
                @php
                    // dd($data[0]);
                    $count = count($data->data);
                    $pageCount = ceil($count / 25);
                    $practical = ['LABORATORY', 'LAB ORIENTED THEORY', 'LAB', 'LAB ORIENTED'];
                    $subject_type = $data->data[0]->subject_type;
                    if ($data->data[0]->subject_type == 'LAB ORIENTED THEORY') {
                        $k = 1;
                        $k_limit = 2;
                    } else {
                        $k = 1;
                        $k_limit = 1;
                    }

                    if (in_array($subject_type, $practical)) {
                        $theoryPractical = 0;

                        $data->data[0]->subject_type = 'Practical';
                    } else {
                        $data->data[0]->subject_type = 'Theory';
                    }

                @endphp
                @for ($k = 1; $k <= $k_limit; $k++)
                    @for ($i = 1; $i <= $pageCount; $i++)
                        <div style="width:100%;">
                            <table class="border-none">
                                <tr>
                                    <td style="width:30%;" class='align-top'> <img src="{{ public_path('adminlogo/school_menu_logo.png') }}"
                                            alt="Image Description" style="margin-top:-5px;width:100%;"></td>
                                    <td style="width:80%;font-size:0.8rem;">
                                        <div style="text-align:center;">
                                            <div style="font-size:0.9rem;"><b>Demo College Of Engineering & Technology -
                                                    2117</b></div>
                                            <div class="">(An Autonomous Insitution and Affliated to ANNA
                                                UNIVERSITY - Chennai)
                                            </div>
                                            {{-- ({{ $data[0]->subject_type }} - {{ $data[0]->exam_type }} ) --}}
                                            <div class="">
                                                <p> END SEMESTER EXAMINATIONS ({{ $data->data[0]->subject_type }} -
                                                    {{ $data->data[0]->exam_type }} ) -
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    /
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;20
                                                </p>
                                            </div>
                                            <div style="font-size:1rem;padding-top:5px;"><b>Attendance Sheet</b></div>
                                        </div>
                                    </td>
                                    <td style="width:10%;"></td>
                                </tr>
                            </table>
                            <table style="width:100%;padding-top:10px;font-size:0.9rem;">
                                <tr>
                                    <td style="width:70%;padding-left:15px;">Course : {{ $data->data[0]->course_name }}
                                    </td>
                                    <td style="width:30%;">Date of Examination :</td>
                                </tr>
                                <tr>
                                    <td style="width:70%;padding-left:15px;">Subject Code / Name :
                                        {{ $data->data[0]->subject_code }} / {{ $data->data[0]->subject_name }}</td>
                                    <td style="width:30%;">Session :</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="width:100%;padding-left:15px;">Semester
                                        : 0{{ $data->data[0]->subject_sem }}</td>
                                </tr>
                            </table>
                            <table class="table table-bordered border-dark text-center"
                                style="width:100%;margin-top:10px;font-size:0.8rem;">
                                <tr class='align-middle text-bold'>
                                    <td class="border-1 border-dark" style="padding:2px;width:5%;">S.No.</td>
                                    <td class="border-1 border-dark" style="padding:2px;width:12%;">Register Number</td>
                                    <td class="border-1 border-dark" style="padding:2px;width:23%;">Name of the
                                        Candidate</td>
                                    <td class="border-1 border-dark" style="padding:2px;width:2%;" colspan="7">Answer
                                        Book No.</td>
                                    <td class="border-1 border-dark" style="padding:2px;width:7%;">*Write AB <br> for
                                        Absent</td>
                                    <td class="border-1 border-dark" style="padding:2px;width:13%;">Signature of <br>
                                        the Candidate</td>
                                </tr>
                                @for ($j = $i * 25 - 25; $j < $i * 25; $j++)
                                    @if (isset($data->data[$j]))
                                        <tr style='font-size:0.83rem; text-align:left;' class='pl-2'>
                                            <td class="border-1 border-dark text-center" style="padding:2px 0px;">
                                                {{ $j + 1 }}</td>
                                            <td class="border-1 border-dark text-center" style="padding:2px 0px;">
                                                {{ $data->data[$j]->register_no }}</td>
                                            <td class="border-1 border-dark" style="padding:2px 5px;">
                                                {{ strtoupper($data->data[$j]->student_name) }}</td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;"></td>
                                        </tr>
                                    @else
                                        <tr style='font-size:0.83rem;'>
                                            <td class="border-1 border-dark" style="padding:2px 0px;">
                                                {{ $j + 1 }}</td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;width:2%;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;"></td>
                                            <td class="border-1 border-dark" style="padding:2px 0px;"></td>
                                        </tr>
                                    @endif
                                @endfor
                            </table>
                            <table style="width:100%;font-size:0.7rem;">
                                <tr>
                                    <td style="width:60%;padding-left:15px;"><b>TOTAL PRESENT :</b></td>
                                    <td style="width:30%;"><b>TOTAL ABSENT :</b></td>
                                </tr>
                            </table>
                            <hr>
                            <table style="width:100%;font-size:0.7rem;">
                                <tr>
                                    <td><b>Certified that the following particulars have been verified:</b></td>
                                    <td align='right'><b>*Hall Superintendent should mark 'AB' for absent </b></td>
                                </tr>
                                <tr>
                                    <td colspan="2">1.The register No.in the attendance sheetwith that in the hall
                                        ticket.</td>
                                </tr>
                                <tr>
                                    <td colspan="2">2.The identication of the candidate with the photo given in the
                                        hall ticket.</td>
                                </tr>

                                <tr>
                                    <td colspan="2">3.The answer book number entered in the attendance sheet by the
                                        candidate with the serial No. on the answer Book.</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class='pb-5'></td>
                                </tr>


                            </table>
                            <table style="width:100%;font-size:0.7rem;">
                                @if ($data->data[0]->subject_type != 'Practical')
                                    <tr>
                                        <td><b>Signature of the Hall Superintendent with the name and Designation</b>
                                        </td>
                                        <td align='right'><b>Signature of the Controller of Examination</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>

                                            <hr>
                                        </td>
                                    </tr>
                                @else
                                    <tr style='font-size:0.7rem'>
                                        <td><b>Signature of the Internal Examiner with Name and Designation</b></td>
                                        <td align='right'><b>Signature of the External Examiner with Name and
                                                Designation</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            <hr>
                                        </td>
                                    </tr>
                                @endif
                            </table>

                                <table style="width:100%;font-size:0.7rem;">
                                        <tr>
                                            <table style="width:100%;">
                                                    <tr>
                                                        <td class='float-left w-50 pr-1 text-secondary'>{{ date('d-m-Y') }}</td>
                                                        <td class='float-center w-50' align="right">{{ $i }}/{{ $pageCount }}</td>
                                                    </tr>
                                            </table>
                                                <td class='float-right w-50 text-secondary' align="right">Demo INSTITUTE
                                                    OF TECHNOLOGY-COE</td>
                                        </tr>
                                </table>
                        </div>
                    @endfor
                    @php
                        if ($subject_type == 'LAB ORIENTED THEORY') {
                            $data->data[0]->subject_type = 'Theory';
                        }
                    @endphp
                @endfor
            @endif
        @endforeach
    @endif
</body>

</html>
