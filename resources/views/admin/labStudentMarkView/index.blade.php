
 @php
    if (auth()->user()->roles[0]->id == 11) {
        $extend = 'layouts.studentHome';
    } else {
        $extend = 'layouts.admin';
    }
@endphp
@extends($extend)
@section('content')

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
            <strong>LAB Marks</strong>
        </div>
        <div class="card-body">
            @if (count($examMarks) > 0)
                <div class="table-responsive table-container">
                    <table class=" table table-bordered text-center table-striped table-hover ">
                        <thead>
                            <tr>
                                <th colspan='3' class='text-right'> Exam Name:</th>
                                
                                @foreach ($names as $name)                              
                                    <th>{{ $name ? explode('/', $name)[0] : '' }}</th>                              
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
                                    <th>  <br> {{ $co_value ?? '' }} Marks
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
                                        <td>{{ $examMark["labMark-$key"] ?? '' }}</td>
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
