@extends('layouts.admin')
@section('content')
    <a class="btn btn-default mb-3" href="{{ route('admin.honor-subjects-report.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <div class="card">
        <div class="card-header">
            Honor Subject Students List
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th> ID </th>
                        <th>Student Name</th>
                        <th>Register No</th>
                        <th>Subject</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($getData) > 0)
                        @foreach ($getData as $i => $data)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $data->students ? $data->students->name : '' }}</td>
                                <td>{{ $data->students ? $data->students->register_no : '' }}</td>
                                <td>{{ $data->subjects ? $data->subjects->name . ' (' . $data->subjects->subject_code . ')' : '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
