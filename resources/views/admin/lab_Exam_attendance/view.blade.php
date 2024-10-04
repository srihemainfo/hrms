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
<div class="card">
    <div class="card-header text-center">
        <strong>View Attendance</strong>
    </div>
    <div class="card-body">
        <div class="card" id="stu_list_card" style="display: block">
            <div class="card-header bg-primary mb-4">
                <div class="row text-center">
                    <div class="col-2">Class :{{ $class_name }}</div>
                    <div class="col-5">Subject: {{ $subject_data ?? '' }}</div>
                    <div class="col-5">Exame date : {{ $date ?? '' }}</div>
                    {{-- <div class="col-2"></div> --}}
                </div>
            </div>
            {{-- <div class="card-body text-center" id="stu_list"> --}}

            <div class="card" id="stu_list_card" style="display: block">

                <div class="card-header bg-primary">
                    <div class="row text-center">
                        <div class="col-1">S.No</div>
                        <div class="col-4">Name</div>
                        <div class="col-3">Register No</div>
                        <div class="col-4">Attendance</div>
                    </div>
                </div>

                <div class="card-body text-center" id="stu_list">
                    <form method="POST" action="" enctype="multipart/form-data" id="myForm">
                        @csrf
                        @foreach ($studentList as $id => $Student)
                        <div class="row text-center p-1">
                            <div class="col-1">{{ $id + 1 }}</div>
                            <div class="col-4">{{ $Student->name ?? '' }}</div>
                            <div class="col-3">{{ $Student->register_no ?? '' }}</div>
                            <div class="col-4 ">
                                <span class="{{ isset($Student->attendance) && $Student->attendance != 'Present'  ? 'text-danger' : '' }}">{{$Student->attendance ?? ''}}</span>
                            </div>
                            {{-- <div class="col-2">
                                    <input type="radio" class="attend_absent" name="attendance" value="Absent"
                                        {{ isset($Student->attendance) && $Student->attendance == 'Absent' ? 'checked' : '' }}>
                                </div> --}}
                            </div>
                        @endforeach
                    </form>


                </div>

                <table class=" table table-bordered text-center table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th> Total Students</th>
                            <th> Total Presents</th>
                            <th> Total Absents</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$summary[0] ?? ''}}</td>
                            <td>{{$summary[1] ?? ''}}</td>
                            <td>{{$summary[2] ?? ''}}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="row p-3">
                {{--
                    <div class="col-xl-3">
                        <div class="form-group"> <a href="" class="btn btn-success" id="download_pdf" onclick="download_pdf()"> Download Excel File</a> </div>

                    </div>
                    <div class="col-xl-3">
                        <div class="form-group">
                        <input type="text" id='class_master_id' value="{{$summary[3]??  ''}}">
                        <input type="text"  id='examename_id' value="{{$summary[4]??  ''}}">
                            <a href="{{ URL::to('admin/Exam-Attendance/ViewAttendance/pdf/',['class_id' => $summary[3],'id'=> $summary[4]]) }}" class="btn btn-primary" id="download_btn">Download PDF File</a>



                        </div>
                    </div>

                    <div class="col-xl-3"></div> --}}
                    {{-- <div class="col-xl-3">
                        <div class="form-group">
                            <a class="btn btn-primary " href="{{ route('admin.Exam-Attendance.attendance') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>

                </div>
                <div class="col-xl-3">
                    <div class="form-group">
                    <input type="text" id='class_master_id' value="{{$summary[3]??  ''}}">
                    <input type="text"  id='examename_id' value="{{$summary[4]??  ''}}">
                        <a href="{{ URL::to('admin/Exam-Attendance/ViewAttendance/pdf/',['class_id' => $summary[3],'id'=> $summary[4]]) }}" class="btn btn-primary" id="download_btn">Download PDF File</a>



                    </div>
                </div>

                <div class="col-xl-3">
                    </div>  --}}
                <div class="col-xl-3">
                    <div class="form-group">
                        <a class="btn btn-primary " href="{{ route('admin.lab_Exam_Attendance.attendance') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


{{-- </div> --}}
</div>
@endsection
@section('scripts')
@parent
<script>
</script>
@endsection
