@extends('layouts.teachingStaffHome')
@section('content')
    <div class="card">
        <div class="card-header">
            <p class="text-center"><strong>Assignment Marks</strong></p>
        </div>
        <div class="card-body">


            @if (count($response) > 0)
                @foreach ($response as $examname => $data2)
                    @php
                        $condition = true;
                        $Sno = 1;
                    @endphp
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-5">
                        @foreach ($data2 as $id => $data)
                            @if ($condition)
                               
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Class Name</th>
                                        <th>Subject</th>
                                        <th>Action</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @php
                                    $condition = false;
                                @endphp
                            @endif
                            <tbody>
                                {{-- {{ dd($data) }} --}}
                                <tr>
                                    <td>{{ $id+1 ??'' }}</td>
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
                                        {!! $data->button ?? '' !!}
                                        {!! $data->edit_buttons ?? '' !!}
                                    </td>
                                    <td>{{ $data->due_date ?? ''}}</td>
                                    <td class='{{ 
                                        ($data->markstatus == 'Not Submitted') || ($data->markstatus == 'Not Final Submitted') ? 'text-danger' : 'text-success'
                                    }}'>    
                                    
                                        {{ $data->markstatus ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                    </div>
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
