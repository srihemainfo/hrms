@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
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
    @if ($role_id == 40 || $role_id == 1)
    <div class="form-group pl-3 col-6">

        <a class="btn btn-default" href="{{ route('admin.assignment_Exam_Mark.index') }}">
            Back
        </a>
    </div>
    @else
    <div class="form-group pl-3 col-6">

        <a class="btn btn-default" href="{{ route('admin.assignment_Exam_Mark_entry_staff.index') }}">
            Back
        </a>
    </div>
    @endif
    @if ($role_id == 40 || $role_id == 1)

    <div class="col-6 text-center">

        <div class="form-group text-center">
            <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>
        </div>
    </div>

    @else
          @if ( $status == 2)
          <div class="col-6 text-center">

              <div class="form-group text-center">
                  <button class="enroll_generate_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>
              </div>
          </div>
          @endif
    @endif
</div>

<div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
<div class="card">
    <div class="card-header text-center">
        <strong>View Assignment Mark</strong>
    </div>
    <div class="card-body">
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
                                {{ $exam_value ??  '' }}
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
        <div class="d-none">
            <input type="hidden" id='Exam_name_edit' value="{{ $examName ?? '' }}">
            <input type="hidden" id='class_name_edit' value="{{ $classname ?? '' }}">
            <input type="hidden" id='class_subject_edit' value="{{ $subjectId ?? '' }}">
            <input type="hidden" id='Exam_date_edit' value="{{ $examDate ?? '' }}">
            <input type="hidden" id='Exam_id_edit' value="{{ $examId ?? '' }}">
        </div>

        <div class="row text-right">
            <div class="col">
            </div>

            <div class="col">
                @if($role_id == 40 || $role_id == 1 || $examCellCo == 'yes')
                @if ( $status == 2 || $status == 1)
                <div class="form-group" style='    margin-right: 30px;margin-top: 1rem;'>
                    <span class="px-3">Entered Marks are Verified and no errors :</span>
                    <button type="button" class="enroll_generate_bn bg-success" style="margin-left:0.4rem;">Verified</button>
                </div>
                @elseif ( $status != 1 || $status == 0 || $status == 3)
                <div class="form-group" style='    margin-right: 30px;margin-top: 1rem;'>
                    <span class="px-3">Entered Marks are Verified and no errors :</span>
                    <button type="button" class="enroll_generate_bn " style="margin-left:0.4rem;background-color: rgb(68, 207, 68); margin-left: 0.4rem;" onclick="verified()">Verify</button>
                </div>
                @endif
                @endif
            </div>
        </div>
        <hr style="height: 2px; width: 100%; background-color: darkgrey;">

        <div class="row ">
            <div class="col-6 text-center">
                @if($role_id == 40 || $role_id == 1 || $examCellCo == 'yes')
                <div class="form-group text-center">
                    <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>
                </div>

                @endif
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


    function verified() {
        $('#loading').show();
        $.ajax({
            url: "{{ route('admin.assignment_verifiedStatus') }}",
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
                $('#loading').hide();
                location.reload();


            },
            error: function(jqXHR, textStatus, errorThrown) {
                    $('#loading').hide();
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
</script>
@endsection
