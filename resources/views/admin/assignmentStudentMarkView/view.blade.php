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
    @if (isset($examMark['exam_title']) && isset($examMark['co_val']))
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
            <strong>Assignment Marks</strong>
        </div>
        <div class="card-body">
            @if (count($examMarks) > 0)
                <div class="table-responsive table-container">
                    <table class=" table table-bordered text-center table-striped table-hover ">
                        @php
                            $condition = true;
                            $conditionTitle = true;
                        @endphp
                        @foreach ($examMarks as $id => $examMark)
                            <thead>
                                @if ($examMark->exam_name != null)
                                    @if ($condition)
                                        <tr>
                                            <th colspan='9' class='text-center'>{{ $exam_name ?? '' }}</th>

                                        </tr>
                                        @php
                                            $condition = false;
                                        @endphp
                                    @endif
                                @endif
                            </thead>
                        @endforeach
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Faculty Name</th>
                                @foreach ($examMarks as $id => $examMark)
                                    @if ($conditionTitle)
                                        @for ($si = 1; $si <= 5; $si++)
                                            @php
                                                $examTitle = 'Assignment_Mark_Title_' . $si;
                                            @endphp <th>{{ $examMark[$examTitle]  ?? '' }} <br> (10-Marks)
                                            </th>
                                        @endfor
                                        @php
                                        $conditionTitle=false;
                                            @endphp
                                    @endif
                                @endforeach
                                <th>Total <br> (50 -Marks)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($examMarks as $id => $examMark)
                                <tr>
                                    <td>{{ $examMark['subjectName'] ?? '' }}</td>
                                    <td>{{ $examMark['subjectCode'] ?? '' }}</td>
                                    <td>{{ $examMark['StaffName'] ?? '' }}</td>
                                    @for ($si = 1; $si <= 5; $si++)
                                        <td>{{ $examMark["assignment_mark_$si"] ?? '' }}</td>
                                    @endfor
                                    <td>{{ $examMark['total'] ?? '' }}</td>
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
