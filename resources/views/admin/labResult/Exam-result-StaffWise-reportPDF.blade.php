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
                @foreach($examMarks as  $examMark)
                        <h3> {{ strtoupper('Department Of ' .$examMark['department_name']) ?? ''}}</h3>
                        <h3>  {{ strtoupper($examMark['subject_name']) ?? ''}} ({{$examMark['subject_code'] ?? ''}})</h3>
                        <h3>SUBJECT WISE REPORT</h3>
                @endforeach
                    
                </td>

            </tr>
        </thead>
    </table>


      @if(count($examMarks) > 0)
    
    <div class="card">
        
        <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark" id="tbl_exporttable_to_xls">
                        @foreach($examMarks as  $examMark)
                        @php 
                        $count = count($examMark['co_val'])
                        @endphp
                       
                        <thead>
                            <tr>
                                <th colspan='{{ ($count + 3) /2 }}' > Class Name:  &nbsp;{{$examMark['class_name'] ?? ''}}</th>
                                <th colspan='{{ ($count + 3 )/2}}'> Subject Name:  &nbsp; {{$examMark['subject_name'] ?? ''}} ({{$examMark['subject_code'] ?? ''}})</th>
                                @php 
                                $result =  (($count + 3 )/2) ;
                                $fload_check = is_float($result);
                                @endphp
                                @if($fload_check)
                                <th></th>
                                @endif
                             </tr>
                        </thead>
                        
                        <thead>
                        <tr>
                        <th colspan = '2'> Exam Title</th>
                        @foreach($examMark['exam_title'] as $id => $value)
                            <th>{{$value ?? ''}}</th>
                        @endforeach
                        <th></th>
                        </tr>
                        </thead>
                        <thead>
                        <tr>
                        <th>Students Name</th>
                        <th>Students Register NO</th>
                        @foreach($examMark['exam_title'] as $id => $value)
                        @php 
                        $parts = explode('/',$value)[0];
                        @endphp
                            <th>{{$parts ?? ''}} <br> ({{$examMark['co_val'][$id]}}-Marks)</th>
                        @endforeach
                        <th>Total <br> ({{$examMark['co_total'] }}-Marks)</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                            @foreach($examMark['student_details'] as $Student_detail)
                            <tr> 
                                <td>{{$Student_detail['name'] ?? ''}} </td>
                                <td>{{$Student_detail['register_no'] ?? ''}} </td>
                                @foreach($Student_detail['status']  as  $status)
                                <td>{{$status ?? ''}} </td>
                                @endforeach
                                @php 
                                 $student_mark = array_sum($Student_detail['total']);
                                @endphp

                                <td style="{{ $student_mark < ($examMark['co_total'] / 2) ? 'background-color:red;':''}}">{{$student_mark}} </td>
                            </tr>
                            @endforeach
                            

                            <tbody>
                            <tr> <th colspan="{{$count + 3}}">Summary</th></tr>
                            </tbody>
                            <tbody>
                                <th colspan='2'>Total No Of Students</th>
                            @foreach($examMark['total'] as  $totalStudent)
                            <td>{{$totalStudent ?? ''}}</td>

                            @endforeach
                            <td></td>

                            </tbody>

                            <tbody>
                                <th colspan='2'>Total No Of Students Pass</th>
                            @foreach($examMark['pass'] as  $pass)
                            <td>{{$pass ?? ''}}</td>

                            @endforeach
                            <td></td>
                            </tbody>

                            <tbody>
                                <th colspan='2'>Total No Of Students Fail</th>
                            @foreach($examMark['fail'] as  $fail)
                            <td>{{$fail ?? ''}}</td>

                            @endforeach
                            <td></td>
                            </tbody>

                            <tbody>
                                <th colspan='2'>Pass percentage</th>
                            @foreach($examMark['pass_percentage'] as  $pass_percentage)
                            <td>{{$pass_percentage ?? ''}}</td>

                            @endforeach
                            <td></td>
                            </tbody>

                            @endforeach 
                            

                    </table>
                </div>

        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body">
            <p class="text-center">
            No LAB Exam Marks are available for this subject.
            </p>
        </div>

        @endif
    


</body>

</html>