@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
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
     @if ($role_id == 40 || $role_id == 1)
     <div class="row">

         <div class="form-group col-6">

             <a class="btn btn-default" href="{{ route('admin.lab_Exam-Mark.index') }}">
                 Back
             </a>
         </div>

         <div class="col-6 text-center">
            @if($role_id == 40 || $role_id == 1 || $examCellCo == 'yes')
            <div class="form-group text-center" >
            <button class="enroll_generate_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>
           </div>

           @endif
        </div>


     </div>
     @else
     <div class="form-group">

         <a class="btn btn-default" href="{{ route('admin.lab_Exam-Mark.staff') }}">
            Back
         </a>
     </div>
 @endif
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header text-center">
            <strong>View LAB Mark</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark" id="tbl_exporttable_to_xls">
                    <thead>
                    <tr>
                            <th colspan='2'>Exam Name : {{ $examName ?? '' }}</th>
                            <th colspan='2'>Class Name : {{ $classname ?? '' }}</th>


                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                            <td colspan="4"><strong> Subject: &nbsp;{{ $examSubject ?? '' }}</strong></td>
                        </tr>

                        <tr>
                            <td><strong>Student Name</strong></td>
                            <td><strong>Register No</strong></td>
                            <td colspan='1'><strong> {{ $examName ? explode('/', $examName)[0] : '' }} <br>({{ $coMarks ?? ''}}-Marks)</strong></td>
                            <td colspan='1'><strong>Total <br> ({{$coMarks ?? ''}}-Marks)</strong></td>

                        </tr>
                        @if (isset($coMarks))
                            <input type="hidden" id="co_1" value="{{ $coMarks ?? 0 }}">
                        @endif
                        <input type="hidden" name="exame_name" value="{{ $examId ?? '' }}">
                        <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
                        <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">
                        @php
                            $array=[];
                        @endphp
                        @forelse ($exameData as $exameDatas)
                        @if($exameDatas->studentName != '')
                        <tr>
                            <td>{{ $exameDatas->studentName ?? '' }}</td>
                            <td>{{ $exameDatas->studentReg ?? '' }}</td>

                            <td class="" colspan='1' style="{{ ($exameDatas->cycle_mark < 50) ? 'background-color:red;color:white' : '' }}" >
                                {{ $exameDatas->cycle_mark ?? '' }}

                            </td>

                            <td colspan='1' style="{{ ($exameDatas->cycle_mark < 50) ? 'background-color:red;color:white' : '' }}">
                                {{ $exameDatas->cycle_mark ?? '' }}
                            </td>
                        </tr>
                        @endif
                        @empty
                            <tr>
                                <td colspan="4">No data Found</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="4"><strong>Summary</strong></td>

                        </tr>
                        <tr>
                            <td colspan="1"><strong>Total No Of Students</strong> </td>
                            <td colspan="1"><strong>Total No Of Students Pass</strong></td>
                            <td colspan="1"><strong>No Of Students Fail</strong></td>
                            <td colspan="1"><strong>Pass percentage</strong></td>
                        </tr>
                        <tr>
                            <td colspan="1">{{ ($total_students ?? '')}}</td>
                            <td colspan="1">{{ $exameData->pass ?? '' }}</td>
                            <td colspan="1">{{ $exameData->fail ?? '' }}</td>
                            <td colspan="1">{{ $exameData->passPercentage ?? '' }}</td>
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


         <div class="row text-right">
            <div class="col">
            </div>

            <div class="col">
                @if($role_id == 40 || $role_id == 1 || $examCellCo == 'yes')
                @if ( $status == 2 || $status == 1)
                <div class="form-group"  style='    margin-right: 30px;margin-top: 1rem;' >
                    <span class="px-3">Entered Marks are Verified and no errors :</span>
                    <button type="button" class="enroll_generate_bn bg-success" style="margin-left:0.4rem;"
                    >Verified</button>
               </div>
               @elseif ( $status != 1 && $status == 3)
               <div class="form-group"  style='    margin-right: 30px;margin-top: 1rem;' >
                <span class="px-3">Entered Marks are Verified and no errors :</span>
                <button type="button" class="enroll_generate_bn " style="margin-left:0.4rem;background-color: rgb(68, 207, 68); margin-left: 0.4rem;"
                onclick="verified()">Verify</button>
           </div>
               @endif
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
                XLSX.writeFile(wb, fn || (`{{{$examName}}}_{{{ $examSubject }}}_Exam Mark .` + (type || 'xlsx')));
        }



        $(document).ready(function() {
            // $('#Publish').on('click', function(e) {
            //     $('#loading').show();
            //     var form = $('#my-form');
            //     form.submit();
            // });

            // $('#submit').on('click', function(e) {
            //     $('#loading').show();
            //     var form = $('#my-form');
            //     form.submit();
            // });

            $('#my-form').on('focus', 'input[type="number"]', function(e) {
                $(this).on('wheel.disableScroll', function(e) {
                    e.preventDefault();
                });
            });

            $('#my-form').on('blur', 'input[type="number"]', function(e) {
                $(this).off('wheel.disableScroll');
            });

            // mark check

            var passCount = 0;
            var failCount = 0;
            var percentageArray = [];
            co_lenth = '';

            $('.mark-input').on('keyup', function() {
                var inputValue = parseFloat($(this).val()) || 0 ;
                // var inputValue = $(this).val() || 0;

                if ($(this).hasClass('mark-input') && $(this).hasClass('co_1a')) {
                    if ($('#co_1').length > 0) {
                        co_lenth = $('#co_1').val();
                    }
                }
                 if(inputValue == 0){
                    $(this).closest('tr').find('.total-td').html(0);
                    $(this).closest('tr').find('.total-td').css('background-color', 'red');
                    $(this).closest('tr').find('.total-td').css('color', 'white');
                }

               else if (inputValue < {{ $coMarks / 2 }}) {
                    $(this).closest('tr').find('.total-td').html(inputValue);
                    $(this).closest('tr').find('.total-td').css('background-color', 'red');
                    $(this).closest('tr').find('.total-td').css('color', 'white');
                    // $(this).val(inputValue).css('background-color', 'red');
                } else {
                    $(this).closest('tr').find('.total-td').html(inputValue);
                    $(this).closest('tr').find('.total-td').css('background-color', '');
                    $(this).closest('tr').find('.total-td').css('color', 'black');
                    // $(this).val(inputValue).css('background-color', 'red');
                }

                if (inputValue > co_lenth) {
                    $(this).val('').css('background-color', 'red');
                    $(this).closest('tr').find('.total-td').text('');
                    $(this).closest('tr').find('.total-td').css('background-color', '');
                    alert("Entered Mark Not greater than " + co_lenth);
                } else if (inputValue < 0) {
                    $(this).val('').css('background-color', 'red');
                    $(this).closest('tr').find('.total-td').text('');
                    $(this).closest('tr').find('.total-td').css('background-color', '');
                    alert('Entered Mark Not less than 0');
                } else {
                    $(this).css('background-color', '');
                }
            });

        });


        function verified(){
            $('#loading').show();
            $.ajax({
                url: "{{ route('admin.lab_verifiedStatus') }}",
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
                        if (response.data === 401) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Mark Entry Is Enabled',
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
