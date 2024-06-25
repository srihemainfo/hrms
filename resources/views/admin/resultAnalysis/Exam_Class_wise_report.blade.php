@php
        ini_set('memory_limit', '256M');
    @endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class- Wise Result Analysis</title>
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" /> -->

    <style>
        @page {
            border: 1px solid #000;
        }


        /* @page {
            size: A4;
            margin: 0; 
        }

        body {
            margin: 0;
            padding: 0;
        } */

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
            padding: 1px;
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
            padding: 0 2px;
            margin: 0;
        }

        td {
            font-size: 12px;
            padding: 0 2px;
            margin: 0;
        }

    

        .logo_title {
    display: flex; /* Use flexbox to arrange child elements */
    align-items: center; /* Vertically center align child elements */
        }

      

        
    </style>

    
</head>

<body>

    @if (isset($response))
   <table style='border:none'>
    <thead>
        <tr style='border:none' >
            <td colspan='' style='border:none;float:left;' >
            <div style='margin-top:-60px;'>
                <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Image Description" style="width: 150px; height: 70px; margin-top: 20px;">
            </div>
            </td>
            <td colspan='' style='border:none;'>
                 <h3> {{$response->course_title }} </h3>
                <h3 > {{$response->class_title }} </h3>
                <h3 > {{$response->analysis }} </h3>
                <h3 > {{$response->assessment_title }} </h3>

            </td>
        </tr>
    </thead>
   </table>


{{-- 
    <div class="logo_title">
        <div class="logo">
            <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Image Description" style="width: 150px; height: 70px; margin-top: 20px;">
        </div>
        <div class="title">
            <h5>{{$response->course_title }}</h5>
            <h5>{{$response->class_title }}</h5>
            <h5>{{$response->analysis }}</h5>
            <h5>{{$response->assessment_title }}</h5>
        </div>
    </div>--}}

    <div style="text-align:center;">

    </div>
 
    <div style="text-align:left;">
        <h6 style='margin-bottom:0px'>Subject Name (Code)</h6>
        @foreach ($response as $responses)
        @php
        $subject = $responses->subjectName ;
        @endphp
        <p style='font-size:12px'> {{ $subject}}</p>

        @endforeach
    </div>



    <table class="table table-bordered font-size:0.6rem;width:100%;">
        <thead>
            <tr>

                <td><strong> S.NO </strong></td>
                {{-- <td><strong>ROLL NO</strong></td> --}}
                <td><strong>REGISTER NO</strong></td>
                <td><strong>STUDENT'S NAME LIST</strong></td>
                @foreach ($response as $responses)

                @php
                $subject = $responses->subjectName ;
                $parts = explode('(', $subject)[1];
                $subject_code = explode(')',$parts)[0];
                @endphp
                <td><strong>{{ $subject_code}}</strong></td>
                @endforeach
                <td><strong>No.of subjects failed</strong></td>
            </tr>
            @if (count($response) > 0)
        <tbody>
            @php
            $studentData = []; // Initialize an array to store data for each student
            $subjects = []; // Initialize an array to store unique subject names
            @endphp

            @foreach ($response as $responses1)
            @php

            $responses1->subjectTotal;
            foreach ($responses1->newArray as $new) {
            $studentId = $new->student_id;
            $co1 = ($new->co_1 != 999 ? $new->co_1 : 999) + ($new->co_2 != 999 ? $new->co_2 : 999) + ($new->co_3 != 999 ? $new->co_3 : 999) + ($new->co_4 != 999 ? $new->co_4 : 999) + ($new->co_5 != 999 ? $new->co_5 : 999);
            if ($co1 == 999 || $co1 == 1998 || $co1 == 2997 || $co1 == 3996 || $co1 == 4995) {
            $co1 = 'AB';
            }
            $subject = $new->subject;

            // Check if the student ID is not already in the array
            if (!isset($studentData[$studentId])) {
            $studentData[$studentId] = [
            'subjects' => [], // Initialize an array to store subjects and their marks
            ];
            }

            // Add subject and mark to the student's data
            $studentData[$studentId]['subjects'][] = [
            'subject' => $subject,
            'co_1' => $co1,
            'totalMark' => $responses1->subjectTotal,
            ];

            // Collect unique subject names
            if (!in_array($subject, $subjects)) {
            $subjects[] = $subject;
            }
            }
            @endphp
            @endforeach

            {{-- Table headers --}}
            {{-- <tr>
                                    <th>Student ID</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    @foreach ($subjects as $subject)
                                        <th>{{ $subject }}</th>
            @endforeach
            <th>Total</th>
            </tr> --}}

            {{-- Loop through student data --}}
            @php
            $i = 1;
            $totalStudentsPass=0;
            $oneSub=0;
            $twoSub=0;
            $threeSub=0;
            $fourSub=0;
            $fiveSub=0;
            $sixSub=0;
            $morethan=0;

            @endphp
            @foreach ($studentData as $studentId => $student)
            <tr>
                <td>{{ $i++ }}</td>
                {{-- <td>
                            @foreach ($student1 as $students1)

                            @if ($students1->user_name_id == $studentId)
                                {{$students1->roll_no }}
                @endif
                @endforeach</td> --}}

                <td>
                    @foreach ($student1 as $students1)
                    {{-- {{dd($students)}} --}}
                    @if ($students1->user_name_id == $studentId)
                    {{ $students1->register_no }}
                    @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($student1 as $students1)
                    {{-- {{dd($students)}} --}}
                    @if ($students1->user_name_id == $studentId)
                    {{ $students1->name }}
                    @endif
                    @endforeach

                </td>
                {{-- You should replace $studentName with the actual student name --}}
                @php
                $passedSubjectCount = 0;

                @endphp
                @foreach ($response as $responses)
                <td>
                    {{-- Find the corresponding subject mark --}}
                    @foreach ($student['subjects'] as $subjectData)
                    @if ($subjectData['subject'] == $responses->subject && $responses->mark_entereby != null)
                    <div style="{{ is_string($subjectData['co_1']) ? 'background-color: lightgreen;' : ($subjectData['co_1'] < $subjectData['totalMark'] * 0.5 ? 'background-color: red;' : '') }}">
                        {{ $subjectData['co_1'] }}
                    </div>
                    @if (!is_string($subjectData['co_1']) && $subjectData['co_1'] >= $subjectData['totalMark'] * 0.5)
                    @php
                    $responses->totalPass++;
                    @endphp
                    @elseif (!is_string($subjectData['co_1']) && $subjectData['co_1'] < $subjectData['totalMark'] * 0.5) @php $responses->totalFail++;
                        @endphp
                        @endif

                        @if (!is_string($subjectData['co_1']) && $subjectData['co_1'] >= $subjectData['totalMark'] * 0.5)
                        @else
                        @php
                        // Increment the count of passed subjects for this student
                        $passedSubjectCount += 1;

                        @endphp
                        @endif
                        @endif
                        @endforeach
                </td>
                @endforeach
                @php


                if($passedSubjectCount == 0){
                $totalStudentsPass++;
                }
                if($passedSubjectCount == 1){
                $oneSub++;
                }
                if($passedSubjectCount == 2){
                $twoSub++;
                }
                if($passedSubjectCount == 3){
                $threeSub++;
                }
                if($passedSubjectCount == 4){
                $fourSub++;
                }
                if($passedSubjectCount == 5){
                $fiveSub++;
                }
                if($passedSubjectCount == 6){
                $sixSub++;
                }
                if($passedSubjectCount > 6){
                $morethan++;
                }



                @endphp

                <td> {{ $passedSubjectCount }}</td>

            </tr>
            @endforeach
            
        </tbody>


        <tfoot>
            <tr class="text-center">
                <td colspan="11"> <strong>Summary</strong></td>
            </tr>
            <tr>
                {{-- <td></td>
                                <td></td> --}}
                <td colspan="3"><strong>Total Students</strong></td>
                @foreach ($response as $responses)
                <td>
                    {{-- @if ($responses->subject) --}}
                    @foreach ($student['subjects'] as $subjectData)
                    @if ($subjectData['subject'] == $responses->subject && $responses->mark_entereby != null)
                    {{ $responses->total_present + $responses->total_abscent }}
                    @endif
                    @endforeach
                    {{-- @endif --}}
                </td>
                @endforeach
                <td></td>

            </tr>
            <tr>
                {{-- <td></td>
                                <td></td> --}}
                <td colspan="3"><strong>Absent</strong></td>
                @foreach ($response as $responses)
                <td>
                    {{-- @if ($responses->subject) --}}
                    @foreach ($student['subjects'] as $subjectData)
                    @if ($subjectData['subject'] == $responses->subject && $responses->mark_entereby != null)
                    {{ $responses->total_abscent }}
                    @endif
                    @endforeach
                    {{-- @endif --}}
                </td>
                @endforeach
                <td></td>
            </tr>
            <tr>
                {{-- <td></td>
                                <td></td> --}}
                <td colspan="3"><strong>Present</strong></td>
                @foreach ($response as $responses)
                <td>
                    {{-- @if ($responses->subject) --}}
                    @foreach ($student['subjects'] as $subjectData)
                    @if ($subjectData['subject'] == $responses->subject && $responses->mark_entereby != null)
                    {{ $responses->total_present }}
                    @endif
                    @endforeach
                    {{-- @endif --}}
                </td>
                @endforeach
                <td></td>
            </tr>
            <tr>
                {{-- <td></td>
                                <td></td> --}}
                <td colspan="3"><strong>Failed</strong></td>
                @foreach ($response as $responses)
                <td>
                    @if ($responses->mark_entereby != null)
                    {{ $responses->totalFail }}
                    @endif
                </td>
                @endforeach
                <td></td>
            </tr>
            <tr>
                {{-- <td></td>
                                <td></td> --}}
                <td colspan="3"><strong>Passed</strong></td>
                @foreach ($response as $responses)
                <td>
                    @if ($responses->mark_entereby != null)
                    {{ $responses->totalPass }}
                    @endif
                </td>
                @endforeach
                <td></td>
            </tr>
            <tr>
                {{-- <td></td>
                                <td></td> --}}
                <td colspan="3"><strong>Pass%</strong></td>
                @foreach ($response as $responses)
                <td>
                    @if ($responses->mark_entereby != null)
                    @php
                    $totalPass = $responses->totalPass;
                    $totalFail = $responses->totalFail;
                    $totalMarks = $totalPass + $totalFail;
                    $passPercentage = ($totalPass / $totalMarks) * 100;
                    @endphp
                    {{ number_format($passPercentage, 2) }}
                    @endif
                </td>
                @endforeach
                <td></td>


            </tr>

            <tr class="text-center">
                <td colspan="11"> <strong>Summary of Failures Count</strong></td>
            </tr>
            <tr>
                <td colspan="3" class="text-center">Total Number of Students </td>
                <td>
                    {{ $totalStudentinclass=$response[0]->total_present !='' && $response[0]->total_abscent !='' ? $response[0]->total_present + $response[0]->total_abscent:0 }}
                </td>
                <td colspan="4">Arrear in one subject</td>
                <td colspan="3">
                    {{$oneSub ?? '-'}}
                </td>

            </tr>
            <tr>

                <td colspan="3" class="text-center">Number of students Passed </td>
                <td>{{ $totalStudentsPass ?? 0}}</td>
                <td colspan="4">Arrear in two subject</td>
                <td colspan="3">
                    {{$twoSub ?? '-'}}
                </td>

            </tr>
            <tr>

                <td colspan="3" class="text-center">Over All Pass Percentage </td>
                <td>
                    {{$totalStudentinclass !=0 && $totalStudentsPass ? number_format(($totalStudentsPass/$totalStudentinclass ) * 100,2) : 0}}
                </td>
                <td colspan="4">Arrear in three subject</td>
                <td colspan="3">
                    {{$threeSub ?? '-'}}
                </td>

            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td colspan="4">Arrear in four subject</td>
                <td colspan="3">
                    {{$fourSub ?? '-'}}
                </td>

            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td colspan="4">Arrear in five subject</td>
                <td colspan="3">
                    {{$fiveSub ?? '-'}}
                </td>

            </tr>
            <tr>
                <td colspan="3"> </td>
                <td></td>
                <td colspan="4">Arrear in six subject</td>
                <td colspan="3">
                    {{$sixSub ?? '-'}}
                </td>

            </tr>
            <tr>
                <td colspan="3"> </td>
                <td></td>
                <td colspan="4">Arrear in more than six subject</td>
                <td colspan="3">
                    {{$morethan ?? '-'}}
                </td>

            </tr>
            <tr>
                <td colspan="4" class="text-center"><strong> AUTHORITY</strong></td>
                <td colspan="4" class="text-center"><strong>NAME</strong></td>
                <td colspan="3" class="text-center"><strong>SIGNATURE</strong></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center"><strong>PREPARED BY</strong></td>
                <td colspan="4"></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center"><strong>HOD</strong></td>

                <td colspan="4"></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center"><strong>PRINCIPAL</strong></td>

                <td colspan="4"></td>
                <td colspan="3"></td>
            </tr>
        </tFoot>
        @endif
    </table>


    @endif


    <div style="text-align:right;">
        <p style="padding-top:1rem;padding-right:5px;"> <b>HOD Sign</b></p>
    </div>
   


</body>

</html>