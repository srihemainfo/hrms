@php
   $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    }elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    }else{
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        input.mark {
            background: unset;
            border: 0;
            width: 100px;
        }

        input.mark:focus-visible {
            border: 0;
            outline: 0;
        }
    </style>

@if(isset($count))
    @if( $count > 0)
    <div class="card">
        <div class="card-header text-center">
            <strong>View Marks</strong>
        </div>
        <div class="card-body">

            <div class="row m-2">
                <div class="col-6"></div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-6">
                            <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>

                        </div>
                        <div class="col-6">

                            <a href="{{ URL::to('admin/lab_Exam-result-StaffWise-report/pdf', ['classId'=> $classId, 'subjectId'=> $subjectId,'pdf' => 'pdf']) }}" target="_blank" class="btn btn-primary" id="download_btn">Download PDF File</a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark" id="tbl_exporttable_to_xls">
                    <thead>
                        <tr>
                            <th colspan="2">{{ isset($student[0]->classname) ? $student[0]->classname : '' }}</th>
                            @if (isset($student[0]->co_1Name))
                                <th colspan="2"><strong><span>{{ $student[0]->co_1Name }}</span></strong></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Subject : </strong></td>
                            <td colspan="6">
                                <strong>{{ isset($student[0]->subjectName) ? $student[0]->subjectName : '' }}</strong>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Student Name</strong></td>
                            <td><strong>Register No</strong></td>
                            @if (isset($student[0]->co_1Mark))
                                @php
                            $parts = explode('/',$student[0]->co_1Name )[0];
                            @endphp
                                <td><strong> <span>{{ $parts}}  <br>({{ $student[0]->co_1Mark }}-Marks) </span> </strong></td>
                            @endif

                            <td><strong>Total <br>
                                    @php
                                    // dd($student[0]->co_1Mark , ($student[0]->co_2Mark ?? 0) );
                                        $totalmark = ($student[0]->co_1Mark ?? 0);
                                    @endphp
                                    ({{ $totalmark }}-Marks)
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


                        @endphp
                        @forelse ($student as $exameDatas)


                            <tr>
                                <td>{{ $exameDatas->name ?? '' }}</td>
                                <td>{{ $exameDatas->register_no ?? '' }}</td>
                                @if (isset($student[0]->co_1Mark))
                                    @php
                                        $singleTotal = number_format((100 * (isset($exameDatas->co_1) && $exameDatas->attendance != 'Absent' ? $exameDatas->co_1 : 0)
                                        ) / ($totalmark != '0' ? $totalmark: 1), 2);

                                        $single = (isset($exameDatas->co_1) && $exameDatas->attendance != 'Absent' ? $exameDatas->co_1 : 'Absent')
                                    @endphp
                                    <td style="{{ $singleTotal < 50 ? 'background-color: red;' : '' }}" class="">{{ $exameDatas->attendance == 'Absent' ? 'Absent' : $exameDatas->co_1 }}
                                        @php
                                            if ($exameDatas->attendance != 'Absent') {
                                                $percentage = ($exameDatas->co_1 / $student[0]->co_1Mark ?? 1) * 100;
                                                if ($percentage >= 50) {
                                                    $co_1colmPassMark++;
                                                }
                                                if ($percentage < 50) {
                                                    $co_1colmFailMark++;
                                                }
                                            }else{
                                                $co_1colmFailMark++;
                                            }

                                        @endphp
                                    </td>
                                @endif






                                <td style="{{ $singleTotal < 50 ? 'background-color: red;' : '' }}">
                                    @if ( $single == 'Absent')
                                        {{ $single }}
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



                        {{-- </form> --}}
                        <tr>
                            <td colspan="8"><strong>Summary</strong></td>

                        </tr>
                        <tr>
                            <td colspan="2"><strong>Total No Of Students</strong> </td>
                            @if (isset($student[0]->co_1Present))
                                <td>{{ $student[0]->co_1Present + $student[0]->co_1Absent ?? 0 }}</td>
                            @endif

                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Total No Of Students Present</strong></td>
                            @if (isset($student[0]->co_1Present))
                                <td>{{ $student[0]->co_1Present }}</td>
                            @endif

                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong> Total No Of Students Absent</strong></td>
                            @if (isset($student[0]->co_1Absent))
                                <td>{{ $student[0]->co_1Absent ?? 0 }}</td>
                            @endif

                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Total No Of Students Pass</strong></td>
                            @if (isset($student[0]->co_1Mark))
                                <td>{{ $co_1colmPassMark }}</td>
                            @endif

                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>No Of Students Fail</strong></td>
                            @if (isset($student[0]->co_1Mark))
                                <td>{{ $co_1colmFailMark }}</td>
                            @endif

                            <td></td>
                        </tr>
                        <td colspan="2"><strong>Pass percentage</strong></td>
                        @if (isset($student[0]->co_1Present))
                            <td>
                                {{ $passPercentageCo_1 = number_format((($co_1colmPassMark ?? 0) / ( $student[0]->co_1Present + $student[0]->co_1Absent ?? 1)) * 100, 2) }}
                            </td>
                        @endif

                        <td></td>
                        </tr>

                        {{-- <tr>
                            <td colspan="2">{{ ($student[0]->totalPresent ?? 0) + ($student[0]->totalAbsent ?? 0) }}</td>
                            <td colspan="2">{{ $student[0]->totalPresent ?? 0}}</td>
                            <td colspan="1">{{$student[0]->totalAbsent ?? 0}}</td>
                            <td colspan="1">@if (!empty($array))
                                <?php
                                $sum = 0;
                                $Pass = 0;
                                $Fail = 0;
                                // $Fail=$Fail+($totalAbs ?? 0);
                                foreach ($array as $value) {
                                    if ($value >= 50) {
                                        $Pass++;
                                    }
                                    if ($value < 50) {
                                        $Fail++;
                                    }
                                    $sum += $value;
                                }
                                $passPercentage = ($Pass / 10) * 100;
                                ?>

                            @endif {{ $Pass  ?? '' }}</td>
                            <td colspan="1">{{ $Fail ?? '' }}</td>
                            <td colspan="1">{{ number_format($passPercentage, 2) }}%</td>


                        </tr> --}}


                    </tbody>
                </table>
            </div>
            <div class="d-none">
                <input type="hidden" id='Exam_name_edit' value="{{ $examName ?? '' }}">
                <input type="hidden" id='class_name_edit' value="{{ $classname ?? '' }}">
                <input type="hidden" id='class_subject_edit' value="{{ $subjectId ?? '' }}">
                <input type="hidden" id='Exam_date_edit' value="{{ $examDate ?? '' }}">
                <input type="hidden" id='Exam_id_edit' value="{{ $examId ?? '' }}">
            </div>
            <div class="row text-right">
                <div class="col"></div>
                <div class="col"></div>
            </div>
            <hr style="height: 2px; width: 100%; background-color: darkgrey;">
            <div class="row ">
                <div class="col-6 text-center">
                </div>
                <div class="col-6 text-center">
                    @if ($role_id == 40 || $role_id == 1)
                        <div class="form-group">
                            <a class="btn btn-primary" href="{{ route('admin.lab_Exam-Mark.index') }}">
                                Exit
                            </a>
                        </div>
                    @else
                        <div class="form-group">
                            <a class="btn btn-primary" href="{{ route('admin.lab_Exam-Mark-Result.index') }}">
                                Exit
                            </a>
                        </div>
                    @endif
                </div>
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
    </div>
   @endif
@endif


 @if(count($examMarks) > 0)

    <div class="card">
        <div class="card-header text-center">
            <strong>View Mark</strong>
        </div>
        <div class="card-body">

            <div class="row m-2">
                <div class="col-6"></div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-6">
                            <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>

                        </div>
                        <div class="col-6">

                            <a href="{{ URL::to('admin/lab_Exam-result-StaffWise-report/pdf', ['classId'=> $classId, 'subjectId'=> $subjectId,'pdf' => 'pdf']) }}" target="_blank" class="btn btn-primary" id="download_btn">Download PDF File</a>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark" id="tbl_exporttable_to_xls">
                        @foreach($examMarks as  $examMark)
                        @php
                        $count = count($examMark['co_val'])
                        @endphp

                        <thead>
                            <tr>
                                <th colspan='{{ ($count + 3) /2 }}'>Class Name: &nbsp;{{$examMark['class_name'] ?? ''}}</th>
                                <th colspan='{{ ($count + 3 )/2}}'> Subject Name &nbsp;{{$examMark['subject_name'] ?? ''}} ({{$examMark['subject_code'] ?? ''}})</th>
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

                                <td style="{{ ($student_mark < ($examMark['co_total']/2)) ? 'background-color:red;':''}}">{{$student_mark}} </td>
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
    </div>
 @else
    <div class="card">
        <div class="card-body">
            <p class="text-center">
            No LAB Exam Marks are available for this subject.
            </p>
        </div>

 @endif


@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
    <script>
        function editRequest() {
            let Exame_name = $("#Exam_name_edit").val();
            let class_name = $("#class_name_edit").val();
            let Class_subject = $("#class_subject_edit").val();
            let Exam_date = $("#Exam_date_edit").val();
            let Exam_id = $("#Exam_id_edit").val();

            Swal.fire({
                title: 'Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    return $.ajax({
                        url: "{{ route('admin.cat_exam_edit_request') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'Exam_id': Exam_id,
                            'exam_name': Exame_name,
                            'Exam_date': Exam_date,
                            'class_name': class_name,
                            'Class_subject': Class_subject,
                            'reason': reason
                        },
                        success: function(response) {

                            if (response.data === 200) {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Edit request send ',
                                    icon: 'success'
                                });
                            }
                            if (response.data === 400) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to send edit request',
                                    icon: 'error'
                                });
                            }
                            location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            location.reload();
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            });

        }

        function verified() {
            $.ajax({
                url: "{{ route('admin.verifiedStatus') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    exameId: $("#Exam_id_edit").val(),
                },
                success: function(response) {

                    if (response.data === 200) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Mark Verified',
                            icon: 'success'
                        });
                    }
                    if (response.data === 400) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to Verify Mark',
                            icon: 'error'
                        });
                    }

                    location.reload();


                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.log('An error occurred: ' + error);
                }
            });
        }

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`CO_Exam_Staffwise_{{{  $student[0]->classname?? '' }}}.` + (type || 'xlsx')));
        }
    </script>
@endsection
