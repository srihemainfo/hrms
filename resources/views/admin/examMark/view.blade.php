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
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header text-center">
            <strong>View Mark</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark" id="tbl_exporttable_to_xls">
                    @php
                    $totalCount = count($coMarks) - 1;
                    $available = 5 - $totalCount;
                    $colSpan = 8 / ($available == 0 ? 1 : $available);

                    if ($colSpan <= 2) {
                        $colSpan = 3;
                    }
                    if ($colSpan >= 4) {
                        $colSpan = 2;
                    }
                    // dd($colSpan);
                @endphp
                    <thead>
                        <tr>
                            <th colspan="4">Exam Name : {{ $examName ?? '' }}</th>
                            <th colspan="4">Class Name : {{ $classname ?? '' }}</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Subject : </strong></td>
                            <td colspan="4"><strong>{{ $examSubject ?? '' }}</strong></td>
                            <td colspan="2"><strong>Exam Date : {{ $examDate ?? '' }}</strong></td>
                        </tr>

                        <tr>
                            <td><strong>Student Name</strong></td>
                            <td><strong>Register No</strong></td>
                            @if(isset($coMarks['CO-1'])) <td colspan="{{ $colSpan }}" ><strong> <span> CO-1 <br> ({{ $coMarks['CO-1'] }}-Marks)</span> </strong></td>@endif
                            @if(isset($coMarks['CO-2'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-2 <br> ({{ $coMarks['CO-2'] }}-Marks)</span> </strong></td>@endif
                            @if(isset($coMarks['CO-3'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-3 <br> ({{ $coMarks['CO-3'] }}-Marks)</span></strong></td>@endif
                            @if(isset($coMarks['CO-4'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-4 <br> ({{ $coMarks['CO-4'] }}-Marks)</span> </strong></td>@endif
                            @if(isset($coMarks['CO-5'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-5 <br>({{ $coMarks['CO-5'] }}-Marks)</span> </strong></td>@endif
                            <td colspan="{{ $colSpan }}"><strong>Total <br> @if(isset($coMarks['count'])) <span>({{ $coMarks['count'] }}-Marks)</span></strong>@endif</td>

                        </tr>
                        {{-- <form action="{{ route('admin.Exam-Mark.markStore') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf --}}
                        <input type="hidden" name="exame_name" value="{{ $examId ?? '' }}">
                        <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
                        <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">
                        @php
                            $array=[];
                        @endphp
                        @forelse ($exameData as $exameDatas)
                            <tr>
                                <td>{{ $exameDatas->name ?? '' }}</td>
                                <td>{{ $exameDatas->register_no ?? '' }}</td>
                                @if (isset($coMarks['CO-1']))
                                <td class="" colspan="{{ $colSpan }}">
                                    {{ isset($exameDatas->co_1) ? ($exameDatas->co_1 == '999' ? 'Absent' : $exameDatas->co_1) : '' }}
                                </td>
                                @endif
                                @if (isset($coMarks['CO-2']))


                                <td class="" colspan="{{ $colSpan }}">
                                    {{ isset($exameDatas->co_2) ? ($exameDatas->co_2 == '999' ? 'Absent' : $exameDatas->co_2) : '' }}
                                </td>
                                @endif
                                @if (isset($coMarks['CO-3']))

                                <td class="" colspan="{{ $colSpan }}">
                                    {{ isset($exameDatas->co_3) ? ($exameDatas->co_3 == '999' ? 'Absent' : $exameDatas->co_3) : '' }}
                                </td>
                                @endif
                                @if (isset($coMarks['CO-4']))

                                <td class="" colspan="{{ $colSpan }}">
                                    {{ isset($exameDatas->co_4) ? ($exameDatas->co_4 == '999' ? 'Absent' : $exameDatas->co_4) : '' }}
                                </td>
                                @endif
                                @if (isset($coMarks['CO-5']))

                                <td class="" colspan="{{ $colSpan }}">
                                    {{ isset($exameDatas->co_5) ? ($exameDatas->co_5 == '999' ? 'Absent' : $exameDatas->co_5) : '' }}
                                </td>
                                @endif
                                @php
                                $singleTotal = number_format(
                                    (100 * (
                                        ($exameDatas->co_1 ?? 0) +
                                        ($exameDatas->co_2 ?? 0) +
                                        ($exameDatas->co_3 ?? 0) +
                                        ($exameDatas->co_4 ?? 0) +
                                        ($exameDatas->co_5 ?? 0)
                                    )) / ($coMarks['count'] ?? 0), 2
                                );

                                $single=($exameDatas->co_1 ?? 0) +
                                        ($exameDatas->co_2 ?? 0) +
                                        ($exameDatas->co_3 ?? 0) +
                                        ($exameDatas->co_4 ?? 0) +
                                        ($exameDatas->co_5 ?? 0);
                                @endphp

                                <td style="{{ ($singleTotal < 50) ? 'background-color: red;' : '' }}" colspan="{{ $colSpan }}">
                                    @if (
                                        ($exameDatas->co_1 ?? 0) +
                                        ($exameDatas->co_2 ?? 0) +
                                        ($exameDatas->co_3 ?? 0) +
                                        ($exameDatas->co_4 ?? 0) +
                                        ($exameDatas->co_5 ?? 0) >= 999
                                    )
                                        Absent
                                    @else
                                        {{
                                            $single
                                        }}

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
                            <td colspan="2"><strong>Total No Of Students Present</strong></td>
                            <td colspan="1"><strong> Total No Of Students Absent</strong></td>
                            <td colspan="1"><strong>Total No Of Students Pass</strong></td>
                            <td colspan="1"><strong>No Of Students Fail</strong></td>
                            <td colspan="1"><strong>Pass percentage</strong></td>
                        </tr>
                        {{-- @for ($i = 0; $i < 3; $i++) --}}
                        <tr>
                            <td colspan="2">{{ ($totalPres ?? '') + ($totalAbs ?? '') }}</td>
                            <td colspan="2">{{ $totalPres ?? '' }}</td>
                            <td colspan="1">{{ $totalAbs ?? '' }}</td>
                            <td colspan="1">@if (!empty($array))
                                <?php
                                $sum = 0;
                                $Pass = 0;
                                $Fail = 0;
                                // $Fail=$Fail+($totalAbs ?? 0);
                                foreach ($array as $value) {
                                    if($value >= 50 ){
                                        $Pass++;
                                    }
                                    if($value < 50 ){
                                        $Fail++;
                                    }
                                    $sum += $value;

                                }
                                $passPercentage = ($Pass / (($totalPres ?? 0))) * 100;
                                ?>

                            @endif {{ $Pass  ?? '' }}</td>
                            <td colspan="1">{{ $Fail ?? '' }}</td>
                            @php
                                   $percentage=number_format($passPercentage, 2);
                            @endphp
                            <td colspan="1">{{ $percentage }}</td>


                        </tr>
                        {{-- @endfor --}}

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


            {{-- @if ($status == 1)
                <div class="row text-center" style="font-size:0.9rem;width:100%;">
                    <div class="col-md-5 col-12">
                        <span>Want To Retake Attendance ?</span>
                        <button type="button" class="enroll_generate_bn bg-danger" style="margin-left:0.4rem;"
                            onclick="editRequest()">Edit Request</button>
                    </div>
                </div>
         @endif --}}
         <div class="row text-right">
            <div class="col">
                @if ($status != 1 && $status != 2)
                @if($role_id == 40 || $role_id == 1 || $type_id == 1 || $type_id == 3)
                <div class="form-group"style='    margin-right: 85px;margin-top: 1rem;' >
                    <span class="px-3">Found some errors in the Entered Marks?</span>
                    <button type="button" class="enroll_generate_bn bg-warning" style="margin-left:0.4rem;"
                    onclick="editRequest()">Edit Request</button>
               </div>
               @endif
               @endif
            </div>

            <div class="col">
                @if($role_id == 40 || $role_id == 1 || $examCellCo == 'yes')
                @if ($status == 1 || $status == 2)
                <div class="form-group"  style='    margin-right: 30px;margin-top: 1rem;' >
                    <span class="px-3">Entered Marks are Verified and no errors :</span>
                    <button type="button" class="enroll_generate_bn bg-success" style="margin-left:0.4rem;"
                    >Verified</button>
               </div>
               @elseif ($status == 0)
               <div class="form-group"  style='    margin-right: 30px;margin-top: 1rem;' >
                <span class="px-3">Entered Marks are Verified and no errors :</span>
                <button type="button" class="enroll_generate_bn " style="margin-left:0.4rem;background-color: rgb(68, 207, 68); margin-left: 0.4rem;"
                onclick="verified()">Verify</button>
           </div>
               @endif
               @endif
            </div>
         </div>
         <hr style="height: 2px; width: 100%; background-color: darkgrey;">

         <div class="row ">
            <div class="col-6 text-center">
                @if($role_id == 40 || $role_id == 1 || $examCellCo == 'yes')
                <div class="form-group text-center" >

                   {{-- <a class="btn btn-primary" href="{{ route('admin.Exam-Mark.index') }}" style="margin-right: 36px;">
                      Print Mark
                   </a> --}}
                <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>

               </div>
               {{-- <div class="card-header " id="card_header">
            </div> --}}
               @endif
            </div>
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
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>

    <script>

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
                XLSX.writeFile(wb, fn || ('Exam Mark .' + (type || 'xlsx')));
        }
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
                    $('#loading').show();
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
                        $('#loading').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            location.reload();
                            $('#loading').hide();

                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            });

        }

        function verified(){
            $('#loading').show();
            $.ajax({
                url: "{{ route('admin.verifiedStatus') }}",
                method: 'POST',
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                data:{
                    exameId:$("#Exam_id_edit").val(),
                },
                success: function(response){

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
                        $('#loading').hide();
                        location.reload();


            },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.log('An error occurred: ' + error);
                    $('#loading').hide();

                }
            });
        }
    </script>
@endsection
