
 @php
    if (auth()->user()->roles[0]->id == 11) {
        $extend = 'layouts.studentHome';
    } else {
        $extend = 'layouts.admin';
    }
@endphp
@extends($extend)
@section('content')
{{--
@php
        $uniqueCOKeys = [];
        $uniqueExamNames = [];
        $names = [];
        $co_values = [];
        $NO = [];
    @endphp
    @foreach ($examMarks as $id => $examMark)
    @php 
    
    @endphp
    @if(isset($examMark['exam_title']) &&  isset($examMark['co_val']))
    @php
    
            $exam_title = $examMark['exam_title'] ?? [];
            $co_val = $examMark['co_val'] ?? [];
            $co_total = array_sum($examMark['co_val']);
        @endphp
        @foreach ($exam_title as $key => $value)
            @if (!in_array($key, $uniqueExamNames))
                @php
                    $uniqueExamNames[] = $key;
                    $names[] = $value;
                @endphp
            @endif
        @endforeach
        @foreach ($co_val as $key => $value)
            @if (!in_array($key, $uniqueCOKeys))
                @php
                $co_values[$key]=$value;
                    $uniqueCOKeys[] = $key;
                    $no = explode('CO-', $key)[1];
                    $NO[] = $no;
                    $NOs = asort($NO);
                @endphp
            @endif
        @endforeach
        @endif
    @endforeach --}}
    <style>
        .null-cell {
            color: red;
        }

        .table-container {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
    <div class="card">
        <div class="card-header text-center">
            <strong>CAT Marks</strong>
        </div>
        <div class="card-body">
            @if (count($examMarks) > 0)
                <div class="table-responsive table-container">
                    <table class=" table table-bordered text-center table-striped table-hover ">
                        <thead>
                            <tr>
                                <th colspan='3'></th>
                                
                                @foreach ($names as $name)                              
                                    <th>{{ $name ?? '' }}</th>                              
                                @endforeach
                                <th></th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Faculty Name</th>
                                
                                @foreach ($co_values as $id => $co_value)
                                    <th>{{ $id ?? '' }} <br> ({{ $co_value ?? '' }} Marks)
                                    </th>
                               @endforeach
                                <th>Total <br> ({{ $co_total ?? '' }} Marks)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($examMarks as $id => $examMark)
                                <tr>
                                    <td>{{ $examMark['subject_code'] ?? '' }}</td>
                                    <td>{{ $examMark['subject_name'] ?? '' }}</td>
                                    <td>{{ $examMark['Staff'] ?? '' }}</td>
                                    @foreach ($NO as $id => $key)
                                        <td>{{ $examMark["co_mark$key"] ?? '' }}</td>
                                    @endforeach
                                    <td @if (isset($examMark['total']) && $examMark['total'] < ($co_total / 2)) class="bg-danger" @endif>{{ $examMark['total'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <center>NO Exam Result Available</center>
            @endif
        </div>
    </div>
@endsection
