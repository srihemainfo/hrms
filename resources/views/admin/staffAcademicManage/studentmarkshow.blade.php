@extends('layouts.teachingStaffHome')
@section('content')
    <div class="" style="padding-bottom:1rem;">
        <a class="btn btn-default" href="{{ route('admin.student-marks.index',['user_name_id' => auth()->user()->id,'name' => auth()->user()->name]) }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Mark List</h5>
        </div>
        <div class="card-header">
            <div class="row text-center">
                <div class="col-4">Class : {{ $class }}</div>
                <div class="col-4">Subject : {{ $subject }}</div>
                <div class="col-4">Exam : {{ $exam }}</div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($marks) > 0)
                        @foreach ($marks as $data)
                            {{-- {{ dd($data) }} --}}
                            <tr>
                                <td>
                                    {{ $data['name'] }}
                                </td>
                                <td>
                                    {{ $data['roll_no'] }}
                                </td>
                                <td>
                                    {{ $data['mark'] }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">Mark Didn't Updated Yet...</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
