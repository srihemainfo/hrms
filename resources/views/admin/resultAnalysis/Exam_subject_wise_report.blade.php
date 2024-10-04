

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
   


        <div class="card">
            <div class="card-body" >
           
                <table class="table table-bordered" id="tbl_exporttable_to_xls">
                    <thead>
                        <tr class="text-center">
                            <td> <strong> S.No</strong></td>
                            <td> <strong>  Sub Code & Subject Name</strong></td>
                            <td> <strong> Faculty Name</strong></td>
                            <td> <strong> Total students</strong></td>
                            <td> <strong> Absent</strong></td>
                            <td> <strong> Present</strong></td>
                            <td> <strong> Failed</strong></td>
                            <td> <strong> Passed</strong></td>
                            <td> <strong> Pass %</strong></td>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($response as $index => $responses)
                      
                        {{-- @php
                            if(isset($responses->totalMark){
                                if($responses->totalMark ){

                                }
                            })

                        @endphp --}}
                        <tr>
                            <td>{{  $index + 1 }}</td>
                            <td>{{  $responses->subjectName ?? '' }}</td>
                            <td>{{  $responses->staffName ?? '' }}</td>
                            <td>{{  $responses->totalstudent ?? '' }}</td>
                            <td>{{  $responses->total_abscent ?? '' }}</td>
                            <td>{{  $responses->total_present ?? '' }}</td>
                            <td>{{  $responses->studentFail  ?? '' }}</td>
                            <td>{{  $responses->studentPass ?? '' }}</td>
                            <td>{{  $responses->subPassper ?? '' }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                    {{-- <tFoot class="text-center">
                        <tr class="text-center"><td  colspan="9" > <strong>Summary of Failures Count</strong></td></tr>
                        <tr>
                            <td colspan="2" class="text-center">Total Number of Students </td>
                            <td>{{  $response[0]->totalstudent ?? '' }}</td>
                            <td colspan="4">Arrear in one subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>

                            <td colspan="2" class="text-center">Number of students Passed </td>
                            <td>{{ $response[0]->studentallPass ?? '' }}</td>
                            <td colspan="4">Arrear in two subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>

                            <td colspan="2" class="text-center">Over All Pass Percentage </td>
                            <td></td>
                            <td colspan="4">Arrear in three subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="4">Arrear in four subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="4">Arrear in five subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>
                            <td colspan="3"> </td>
                            <td colspan="4">Arrear in six subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><strong> AUTHORITY</strong></td>
                            <td colspan="3" class="text-center"><strong>NAME</strong></td>
                            <td colspan="3" class="text-center"><strong>SIGNATURE</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><strong>PREPARED BY</strong></td>
                            <td colspan="3"></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><strong>HOD</strong></td>

                            <td colspan="3"></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><strong>PRINCIPAL</strong></td>

                            <td colspan="3"></td>
                            <td colspan="3"></td>
                        </tr>
                    </tFoot> --}}
                </table>
            </div>
        </div>
    @endif
    

    <div style="text-align:right;">
        <p style="padding-top:1rem;padding-right:15px;"> <b>HOD Sign</b></p>
    </div>
    
</body>
</html>

