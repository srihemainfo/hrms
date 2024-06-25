@extends('layouts.teachingStaffHome')
@section('content')
    <div class="card" style="max-width:100%;overflow-x:auto;">
        <div class="card-header" style="min-width:700px;">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Exam Subjects</h5>
        </div>
        <div class="card-body" style="min-width:700px;">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Exam Name</th>
                        <th>Class Name</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($response) > 0)
                        @foreach ($response as $id => $data)
                            {{-- {{ dd($data) }} --}}
                            <tr>
                                <td>{{ $id + 1 }}</td>
                                <td>
                                    {{ $data->examename }}
                                </td>
                                <td>
                                    {{ $data->classname }}
                                </td>
                                <td>
                                    @foreach ($subjects as $subject)
                                        @if ($subject->id == $data->subject)
                                            {{ $subject->name }} ({{ $subject->subject_code }})
                                        @endif
                                    @endforeach
                                    {{-- @if ($data[0] == 'Library')
                                        Library
                                    @endif --}}
                                </td>
                                <td>
                                    {!!  $data->button  !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No Data Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
