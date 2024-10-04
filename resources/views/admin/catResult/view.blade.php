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


    @if($count > 0)
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
                            <button class="enroll_generate_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>

                        </div>
                        <div class="col-6">

                            <a href="{{ URL::to('admin/Exam-result-StaffWise-report/pdf', ['classId'=> $classId, 'subjectId'=> $subjectId,'pdf' => 'pdf']) }}" target="_blank" class="btn btn-primary" id="download_btn">Download PDF File</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark" id="tbl_exporttable_to_xls">
                    <thead>
                        <tr>
                            {{-- <th colspan="4">Exam Name : {{ $examName ?? '' }}</th> --}}
                            <th colspan="8">{{ isset($student[0]->classname) ? $student[0]->classname : '' }}</th>


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
                            <td></td>
                            <td></td>
                            @if (isset($student[0]->co_1Name))
                                <td><strong><span>{{ $student[0]->co_1Name }}</span> </strong></strong></td>
                            @endif
                            @if (isset($student[0]->co_2Name))
                                <td><strong> <span> {{ $student[0]->co_2Name }}</span> </strong></td>
                            @endif
                            @if (isset($student[0]->co_3Name))
                                <td><strong> <span>{{ $student[0]->co_3Name }} </span> </strong></td>
                            @endif
                            @if (isset($student[0]->co_4Name))
                                <td><strong> <span> {{ $student[0]->co_4Name }}</span> </strong></td>
                            @endif
                            @if (isset($student[0]->co_5Name))
                                <td><strong> <span> {{ $student[0]->co_5Name }}</span> </strong></td>
                            @endif

                            <td></td>
                        </tr>

                        <tr>
                            <td><strong>Student Name</strong></td>
                            <td><strong>Register No</strong></td>
                            @if (isset($student[0]->co_1Mark))
                                <td><strong> <span> CO-1 <br>({{ $student[0]->co_1Mark }}) </span> </strong></td>
                            @endif
                            @if (isset($student[0]->co_2Mark))
                                <td><strong> <span> CO-2 <br>({{ $student[0]->co_2Mark }}) </span></strong></td>
                            @endif
                            @if (isset($student[0]->co_3Mark))
                                <td><strong> <span> CO-3 <br> ({{ $student[0]->co_3Mark }}) </span></strong></td>
                            @endif
                            @if (isset($student[0]->co_4Mark))
                                <td><strong> <span> CO-4 <br>({{ $student[0]->co_4Mark }}) </span> </strong></td>
                            @endif
                            @if (isset($student[0]->co_5Mark))
                                <td><strong> <span> CO-5 <br>({{ $student[0]->co_5Mark }}) </span> </strong></td>
                            @endif
                            <td><strong>Total <br>
                                    @php
                                    // dd($student[0]->co_1Mark , ($student[0]->co_2Mark ?? 0) );
                                        $totalmark = ($student[0]->co_1Mark ?? 0) + ($student[0]->co_2Mark ?? 0) + ($student[0]->co_3Mark ?? 0) + ($student[0]->co_4Mark ?? 0) + ($student[0]->co_5Mark ?? 0);
                                    @endphp
                                    ({{ $totalmark }})
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

                            $co_2colmPassMark = 0;
                            $co_2colmFailMark = 0;

                            $co_3colmPassMark = 0;
                            $co_3colmFailMark = 0;

                            $co_4colmPassMark = 0;
                            $co_4colmFailMark = 0;

                            $co_5colmPassMark = 0;
                            $co_5colmFailMark = 0;
                        @endphp
                        @forelse ($student as $exameDatas)
                            <tr>
                                <td>{{ $exameDatas->name ?? '' }}</td>
                                <td>{{ $exameDatas->register_no ?? '' }}</td>
                                @if (isset($student[0]->co_1Mark))
                                    <td class="">{{ $exameDatas->co_1 == '999' ? 'Absent' : $exameDatas->co_1 }}
                                        @php
                                            if ($exameDatas->co_1 != '999') {
                                                $percentage = ($exameDatas->co_1 / $student[0]->co_1Mark ?? 1) * 100;
                                                if ($percentage >= 50) {
                                                    $co_1colmPassMark++;
                                                }
                                                if ($percentage < 50) {
                                                    $co_1colmFailMark++;
                                                }
                                            }

                                        @endphp
                                    </td>
                                @endif

                                @if (isset($student[0]->co_2Mark))
                                    <td class="">{{ $exameDatas->co_2 == '999' ? 'Absent' : $exameDatas->co_2 }}
                                        @php
                                            if ($exameDatas->co_2 != '999') {
                                                $percentage = ($exameDatas->co_2 / $student[0]->co_2Mark ?? 1) * 100;
                                                if ($percentage >= 50) {
                                                    $co_2colmPassMark++;
                                                }
                                                if ($percentage < 50) {
                                                    $co_2colmFailMark++;
                                                }
                                            }

                                        @endphp
                                    </td>
                                @endif

                                @if (isset($student[0]->co_3Mark))
                                    <td class="">{{ $exameDatas->co_3 == '999' ? 'Absent' : $exameDatas->co_3 }}
                                        @php
                                            if ($exameDatas->co_3 != '999') {
                                                $percentage = ($exameDatas->co_3 / $student[0]->co_3Mark ?? 1) * 100;
                                                if ($percentage >= 50) {
                                                    $co_3colmPassMark++;
                                                }
                                                if ($percentage < 50) {
                                                    $co_3colmFailMark++;
                                                }
                                            }

                                        @endphp
                                    </td>
                                @endif

                                @if (isset($student[0]->co_4Mark))
                                    <td class="">{{ $exameDatas->co_4 == '999' ? 'Absent' : $exameDatas->co_4 }}
                                        @php
                                            if ($exameDatas->co_4 != '999') {
                                                $percentage = ($exameDatas->co_4 / $student[0]->co_4Mark ?? 1) * 100;
                                                if ($percentage >= 50) {
                                                    $co_4colmPassMark++;
                                                }
                                                if ($percentage < 50) {
                                                    $co_4colmFailMark++;
                                                }
                                            }

                                        @endphp
                                    </td>
                                @endif

                                @if (isset($student[0]->co_5Mark))
                                    <td class="">{{ $exameDatas->co_5 == '999' ? 'Absent' : $exameDatas->co_5 }}
                                        @php
                                            if ($exameDatas->co_5 != '999') {
                                                $percentage = ($exameDatas->co_5 / $student[0]->co_5Mark ?? 1) * 100;
                                                if ($percentage >= 50) {
                                                    $co_5colmPassMark++;
                                                }
                                                if ($percentage < 50) {
                                                    $co_5colmFailMark++;
                                                }
                                            }

                                        @endphp
                                    </td>
                                @endif

                                @php
                                    $singleTotal = number_format((100 * ((isset($exameDatas->co_1) && $exameDatas->co_1 != '999' ? $exameDatas->co_1 : 0) +
                                            (isset($exameDatas->co_2) && $exameDatas->co_2 != '999' ? $exameDatas->co_2 : 0) +
                                            (isset($exameDatas->co_3) && $exameDatas->co_3 != '999' ? $exameDatas->co_3 : 0) +
                                            (isset($exameDatas->co_4) && $exameDatas->co_4 != '999' ? $exameDatas->co_4 : 0) +
                                            (isset($exameDatas->co_5) && $exameDatas->co_5 != '999' ? $exameDatas->co_5 : 0))) / ($totalmark != '0'?$totalmark: 1), 2);

                                    $single = (isset($exameDatas->co_1) && $exameDatas->co_1 != '999' ? $exameDatas->co_1 : 0) +
                                            (isset($exameDatas->co_2) && $exameDatas->co_2 != '999' ? $exameDatas->co_2 : 0) +
                                            (isset($exameDatas->co_3) && $exameDatas->co_3 != '999' ? $exameDatas->co_3 : 0) +
                                            (isset($exameDatas->co_4) && $exameDatas->co_4 != '999' ? $exameDatas->co_4 : 0) +
                                            (isset($exameDatas->co_5) && $exameDatas->co_5 != '999' ? $exameDatas->co_5 : 0);
                                @endphp


                                <td style="{{ $singleTotal < 50 ? 'background-color: red;' : '' }}">
                                    @if (
                                            (isset($exameDatas->co_1) && $exameDatas->co_1 != '999' ? $exameDatas->co_1 : 0) +
                                            (isset($exameDatas->co_2) && $exameDatas->co_2 != '999' ? $exameDatas->co_2 : 0) +
                                            (isset($exameDatas->co_3) && $exameDatas->co_3 != '999' ? $exameDatas->co_3 : 0) +
                                            (isset($exameDatas->co_4) && $exameDatas->co_4 != '999' ? $exameDatas->co_4 : 0) +
                                            (isset($exameDatas->co_5) && $exameDatas->co_5 != '999' ? $exameDatas->co_5 : 0) >=
                                            999)
                                        0
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

                        <tr>
                            <td colspan="8"><strong>Summary</strong></td>

                        </tr>
                        <tr>
                            <td colspan="2"><strong>Total No Of Students</strong> </td>
                            @if (isset($student[0]->co_1Present))
                                <td>{{ $student[0]->co_1Present + $student[0]->co_1Absent ?? 0 }}</td>
                            @endif
                            @if (isset($student[0]->co_2Present))
                                <td>{{ $student[0]->co_2Present + $student[0]->co_2Absent ?? 0 }}</td>
                            @endif
                            @if (isset($student[0]->co_3Present))
                                <td>{{ $student[0]->co_3Present + $student[0]->co_3Absent ?? 0 }}</td>
                            @endif
                            @if (isset($student[0]->co_4Present))
                                <td>{{ $student[0]->co_4Present + $student[0]->co_4Absent ?? 0 }}</td>
                            @endif
                            @if (isset($student[0]->co_5Present))
                                <td>{{ $student[0]->co_5Present + $student[0]->co_5Absent ?? 0 }}</td>
                            @endif
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Total No Of Students Present</strong></td>
                            @if (isset($student[0]->co_1Present))
                                <td>{{ $student[0]->co_1Present }}</td>
                            @endif
                            @if (isset($student[0]->co_2Present))
                                <td>{{ $student[0]->co_2Present }}</td>
                            @endif
                            @if (isset($student[0]->co_3Present))
                                <td>{{ $student[0]->co_3Present }}</td>
                            @endif
                            @if (isset($student[0]->co_4Present))
                                <td>{{ $student[0]->co_4Present }}</td>
                            @endif
                            @if (isset($student[0]->co_5Present))
                                <td>{{ $student[0]->co_5Present }}</td>
                            @endif
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong> Total No Of Students Absent</strong></td>
                            @if (isset($student[0]->co_1Absent))
                                <td>{{ $student[0]->co_1Absent ?? 0 }}</td>
                            @endif
                            @if (isset($student[0]->co_2Absent))
                                <td>{{ $student[0]->co_2Absent ?? 0 }}</td>
                            @endif
                            @if (isset($student[0]->co_3Absent))
                                <td>{{ $student[0]->co_3Absent ?? 0 }}</td>
                            @endif
                            @if (isset($student[0]->co_4Absent))
                                <td>{{ $student[0]->co_4Absent ?? 0 }}</td>
                            @endif
                            @if (isset($student[0]->co_5Absent))
                                <td>{{ $student[0]->co_5Absent ?? 0 }}</td>
                            @endif
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Total No Of Students Pass</strong></td>
                            @if (isset($student[0]->co_1Mark))
                                <td>{{ $co_1colmPassMark }}</td>
                            @endif
                            @if (isset($student[0]->co_2Mark))
                                <td>{{ $co_2colmPassMark }}</td>
                            @endif
                            @if (isset($student[0]->co_3Mark))
                                <td>{{ $co_3colmPassMark }}</td>
                            @endif
                            @if (isset($student[0]->co_4Mark))
                                <td>{{ $co_4colmPassMark }}</td>
                            @endif
                            @if (isset($student[0]->co_5Mark))
                                <td>{{ $co_5colmPassMark }}</td>
                            @endif
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>No Of Students Fail</strong></td>
                            @if (isset($student[0]->co_1Mark))
                                <td>{{ $co_1colmFailMark }}</td>
                            @endif
                            @if (isset($student[0]->co_2Mark))
                                <td>{{ $co_2colmFailMark }}</td>
                            @endif
                            @if (isset($student[0]->co_3Mark))
                                <td>{{ $co_3colmFailMark }}</td>
                            @endif
                            @if (isset($student[0]->co_4Mark))
                                <td>{{ $co_4colmFailMark }}</td>
                            @endif
                            @if (isset($student[0]->co_5Mark))
                                <td>{{ $co_5colmFailMark }}</td>
                            @endif
                            <td></td>
                        </tr>
                        <td colspan="2"><strong>Pass percentage</strong></td>
                        @if (isset($student[0]->co_1Present))
                            <td>
                                {{ $passPercentageCo_1 = number_format((($co_1colmPassMark ?? 0) / ($student[0]->co_1Present ?? 1)) * 100, 2) }}
                            </td>
                        @endif
                        @if (isset($student[0]->co_2Mark))
                            <td>
                                {{ $passPercentageCo_2 = number_format((($co_2colmPassMark ?? 0) / ($student[0]->co_2Present ?? 1)) * 100, 2) }}
                            </td>
                        @endif
                        @if (isset($student[0]->co_3Mark))
                            <td>
                                {{ $passPercentageCo_3 = number_format((($co_3colmPassMark ?? 0) / ($student[0]->co_3Present ?? 1)) * 100, 2) }}
                            </td>
                        @endif
                        @if (isset($student[0]->co_4Mark))
                            <td>
                                {{ $passPercentageCo_4 = number_format((($co_4colmPassMark ?? 0) / ($student[0]->co_4Present ?? 1)) * 100, 2) }}
                            </td>
                        @endif
                        @if (isset($student[0]->co_5Mark))
                            <td>
                                {{ $passPercentageCo_5 = number_format((($co_5colmPassMark ?? 0) / ($student[0]->co_5Present ?? 1)) * 100, 2) }}
                            </td>
                        @endif
                        <td></td>
                        </tr>
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
            <hr style="height: 2px; width: 100%; background-color: darkgrey;">

            <div class="row ">
                <div class="col-6 text-center"></div>
                <div class="col-6 text-center">
                     @if ($role_id == 40 || $role_id == 1)
                         <div class="form-group">
                             <a class="btn btn-primary" href="{{ route('admin.Exam-Mark.index') }}">
                                 Exit
                             </a>
                         </div>
                     @else
                         <div class="form-group">
                             <a class="btn btn-primary" href="{{ route('admin.Exam-Mark-Result.index') }}">
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
            No CO Exam Marks are available for this subject.
            </p>
        </div>
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
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
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
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
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
                XLSX.writeFile(wb, fn || (`CO_Exam_Staffwise_{{{  $student[0]->classname ?? '' }}}.` + (type || 'xlsx')));
        }
    </script>
@endsection
