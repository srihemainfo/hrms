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
    <div class="row">
     <div class="form-group pl-3 col-6">

        <a class="btn btn-default" href="{{ route('admin.assignment_Exam_Mark_Result.index') }}">
            Back
        </a>
    </div>
</div>

    @if(count($exameData) > 0)
    <div class="card">
        <div class="card-header text-center">
            <strong>View Marks</strong>
        </div>
        <div class="card-body">

            <div class="row m-2">
                <div class="col-9"></div>
                <div class="col-3">
                    <div class="row">
                        <div class="">
                            <button class="enroll_generate_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>

                        </div>
                        {{--
                        <div class="col-6">
                            <a href="{{ URL::to('admin/lab_Exam-result-StaffWise-report/pdf', ['classId'=> $classId, 'subjectId'=> $subjectId,'pdf' => 'pdf']) }}" target="_blank" class="btn btn-primary" id="download_btn">Download PDF File</a>
                        </div>
                        --}}


                    </div>

                </div>
            </div>


        <div class="table-responsive">
            <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark" id="tbl_exporttable_to_xls">
                <thead>
                    <tr>
                        <th colspan='4'>Class Name : {{ $classname ?? '' }}</th>
                        <td colspan="4"><strong> Subject: &nbsp;{{ $examSubject ?? '' }}</strong></td>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td><strong>Student Name</strong></td>
                        <td><strong>Register No</strong></td>
                        <td><strong>Assignment-1 <br> (10 Marks)</strong></td>
                        <td><strong>Assignment-2 <br> (10 Marks)</strong></td>
                        <td><strong>Assignment-3 <br> (10 Marks)</strong></td>
                        <td><strong>Assignment-4 <br> (10 Marks)</strong></td>
                        <td><strong>Assignment-5 <br> (10 Marks)</strong></td>
                        <td colspan='1'><strong>Total <br> (50 Marks)</strong></td>

                    </tr>
                    @if (isset($assignmentMarks))
                    <input type="hidden" id="co_1" value="{{ $assignmentMarks ?? 0 }}">
                    @endif
                    <input type="hidden" name="exame_name" value="{{ $examId ?? '' }}">
                    <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
                    <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">
                    @php
                    $array=[];
                    @endphp
                    @forelse ($exameData as $exameDatas)
                    <tr>
                        <td>{{ $exameDatas->studentName ?? '' }}</td>
                        <td>{{ $exameDatas->studentReg ?? '' }}</td>
                        @php
                        $total = 0;
                        @endphp
                        @for($i =1 ; $i <=5; $i++) @php $exam_name='assignment_mark_' .$i; $exam_value=$exameDatas->$exam_name;
                            $total += $exam_value;
                            @endphp

                            <td class=" " colspan="1">
                                <input type="number" min="0" max="{{$assignmentMarks - 40 ?? 0 }}" class="mark-input {{ isset($assignmentMarks) ? 'assignment_mark_' . $i : '' }} mark" id="{{ isset($assignmentMarks) ? 'assignment_mark_' . $i : '' }}" name="assignment_mark_{{$i}}[{{ $exameDatas->student_id }}]" value="{{ $exam_value ??  '' }}" readonly />
                            </td>

                            @endfor

                            <td colspan='1'>
                                {{ $total ?? '' }}
                            </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No data Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
@else
    <div class="card">
        <div class="card-body">
            <p class="text-center">
            No Assignment Exam Marks are available for this subject.
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
                XLSX.writeFile(wb, fn || (`CO_Exam_Staffwise_{{{  $student[0]->classname?? '' }}}.` + (type || 'xlsx')));
        }
    </script>
@endsection
