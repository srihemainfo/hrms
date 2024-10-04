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
<div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header text-center">
            <strong>Enter Attendance</strong>
        </div>
        <div class="card-body">
            <div class="card" id="stu_list_card" style="display: block">
                <div class="card-header bg-primary mb-4">
                    <div class="row text-center">
                        <div class="col-2">Class :{{ $class_name ?? '' }}</div>
                        <div class="col-5">Subject : {{ $subject_data ?? '' }}</div>
                        <div class="col-5">Exame date : {{ $date ?? '' }}</div>
                        {{-- <div class="col-2"></div> --}}
                    </div>
                </div>
            <div class="card" id="stu_list_card" style="display: block">

                <div class="card-header bg-primary">
                    <div class="row text-center">
                        <div class="col-1">S.No</div>
                        <div class="col-4">Name</div>
                        <div class="col-3">Register No</div>
                        <div class="col-2">Present</div>
                        <div class="col-2">Absent</div>
                    </div>
                </div>

                <div class="card-body text-center" id="stu_list">
                    <form method="POST" action="{{ route('admin.labExamAttendance.attendencestore') }}" enctype="multipart/form-data"
                        id="myForm">
                        @csrf
                        @foreach ($studentList as $id => $Student)
                            <div class="row text-center p-1">
                                <div class="col-1">{{ $id + 1 }}</div>
                                <div class="col-4">{{ $Student->name ?? '' }}</div>
                                <div class="col-3">
                                    {{ $Student->register_no ?? '' }}
                                    <input type="hidden" name="user_ids[]" value="{{ $Student->user_name_id ?? '' }}" />
                                    <input type="hidden" name="enroll_id" value="{{ $Student->enroll_master_id ?? '' }}" />
                                    <input type="hidden" name="exameid" value="{{ $studentList->exameid ?? '' }}" />
                                </div>
                                <div class="col-2">
                                    <input type="radio" class="attend_present"
                                        name="attendance[{{ $Student->user_name_id }}]" value="Present" checked />
                                </div>
                                <div class="col-2">
                                    <input type="radio" class="attend_absent"
                                        name="attendance[{{ $Student->user_name_id }}]" value="Absent" />
                                </div>
                            </div>
                        @endforeach
                        <button type="submit" id='btn-submit' class="btn btn-primary mt-2">Save</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    @parent
    <script>


        $(document).ready(function() {
            $('#btn-submit').on('click', function(e) {
                $('#btn-submit').hide();
                $('#loading').show();
                var form = $('#myForm');
                form.submit();
            });
        });
    </script>
@endsection
