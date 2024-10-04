@extends('layouts.admin')
@section('content')
    {{-- <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.subject-wise-subject-registration.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div> --}}
    <div class="card">
        <div class="card-header">
            <h5 class="text-primary text-center">STUDENT SUBJECT REGISTRATION</h5>
            <h6 class="text-center">Subject Wise Students List</h6>
        </div>
        <div class="card-header text-center">
            <div style="display:flex;justify-content:space-between;font-size:0.90rem;">
                {{-- <div style="" class="manual_bn">Regulation : </div> --}}
                {{-- <div style="" class="manual_bn">Department : </div> --}}
                <div style="" class="manual_bn">Course :  {{  $course }}</div>
                <div style="" class="manual_bn">AY :  {{  $ay }}</div>
                <div style="" class="manual_bn">Semester :  {{  $semester }}</div>
                <div style="" class="manual_bn">Section :  {{  $section }}</div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Student Name</th>
                            <th>Register No</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($get_students) > 0)
                            @foreach ($get_students as $i => $student)
                                <tr>

                                    <td>
                                        {{ $i + 1 }}
                                    </td>

                                    <td>
                                        {{ $student->student_name }}
                                    </td>

                                    <td>
                                        {{ $student->register_no }}
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3">No Data Available...</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
