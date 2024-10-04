@extends('layouts.teachingStaffHome')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary text-capitalize text-center">LAB Exam Subjects
            </h5>
        </div>
        <div class="card-body" style="max-width:100%;overflow-x:auto;">
            @if (count($response) > 0)
                @foreach ($response as $examname => $data2)
                    @foreach ($data2 as $id => $data)
                        <table class="table table-bordered table-striped table-hover text-center mb-5"
                            style="min-width:700px;">
                            <thead>
                                <tr>
                                    <th colspan='4' class='text-left'> Exam Name: &nbsp; {{ $examname ?? '' }}</th>
                                    @php
                                        $parts = date('d-m-Y', strtotime(explode('|', $id)[0]));
                                    @endphp
                                    <th colspan='4' class='text-right'> Due Date: &nbsp; {{ $parts ?? '' }}</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Class Name</th>
                                    <th>Subject</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{ dd($data) }} --}}
                                @php
                                    $Sno = 1;
                                @endphp
                                @foreach ($data as $theData)
                                    <tr>
                                        <td>{{ $Sno ?? '' }}</td>
                                        <td>
                                            {{ $theData['classname'] }}
                                        </td>
                                        <td>
                                            @foreach ($subjects as $subject)
                                                @if ($subject['id'] == $theData['subject'])
                                                    {{ $subject['name'] }} ({{ $subject['subject_code'] }})
                                                @endif
                                            @endforeach
                                            {{-- @if ($data[0] == 'Library')
                                            Library
                                        @endif --}}
                                        </td>
                                        <td>
                                            {!! $theData['button'] ?? '' !!}
                                            {!! $theData['edit_buttons'] ?? '' !!}
                                        </td>
                                        <td
                                            class="{{ $theData['markstatus'] == 'Not Submitted' ? 'text-danger' : 'text-success' }}">
                                            {{ $theData['markstatus'] ?? '' }}
                                        </td>
                                    </tr>
                                    @php
                                        $Sno++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                @endforeach
            @else
                <table class="table table-bordered table-striped table-hover text-center">
                    <tbody>
                        <tr>
                            <td colspan="5">No Data Available</td>
                        </tr>
                    </tbody>
                </table>
            @endif


        </div>
    </div>

@endsection
