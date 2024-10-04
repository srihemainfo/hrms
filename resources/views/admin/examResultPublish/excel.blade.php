@extends('layouts.admin')
@section('content')
    <a class="btn btn-default" href="{{ route('admin.result-publish.index') }}">
        Back To List
    </a>
    <div class="card mt-3">
        <div class="card-header">
            Exam Result List
        </div>

        <div class="card-body" style="max-width:100%;min-width:100%;overflow-x: auto;">
            <table class=" table table-bordered table-striped table-hover text-center" style="overflow-x: auto;"
                id="examResult">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Academic Year</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>regulation</th>
                        <th>Exam Month</th>
                        <th>Exam Year</th>
                        <th>Result Type</th>
                        <th>Publish Date</th>
                        <th> </th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{ $data[0]['batch'] ?? '' }}
                        </td>
                        <td>
                            {{ $data[0]['academic_year'] ?? '' }}
                        </td>
                        <td>
                            {{ $data[0]['course'] ?? '' }}
                        </td>
                        <td>
                            {{ $data[0]['semester'] ?? '' }}
                        </td>
                        <td>
                            {{ $data[0]['regulation'] ?? '' }}
                        </td>
                        <td>
                            {{ $data[0]['exam_month'] ?? '' }}
                        </td>
                        <td>
                            {{ $data[0]['exam_year'] ?? '' }}
                        </td>
                        <td>
                            {{ $data[0]['result_type'] ?? '' }}
                        </td>
                        <td>
                            @php
                                $format_date = $data[0]['publish_date']
                                    ? date('d-m-Y', strtotime($data[0]['publish_date']))
                                    : '';

                            @endphp
                            {{ $format_date ?? '' }}

                        </td>
                        <td></td>
                        <td></td>

                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th>Register Number</th>
                        <th>Subject Code 1</th>
                        <th>Subject Code 2</th>
                        <th>Subject Code 3</th>
                        <th>Subject Code 4</th>
                        <th>Subject Code 5</th>
                        <th>Subject Code 6</th>
                        <th>Subject Code 7</th>
                        <th>Subject Code 8</th>
                        <th>Subject Code 9</th>
                        <th>Subject Code 10</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        @foreach ($theSubjects as $id => $code)
                            <td>{{ $code }}</td>
                        @endforeach
                    </tr>
                    @foreach ($data as $data)
                        <tr>
                            <td>{{ $data['register_no'] }}</td>
                            @foreach ($theSubjects as $id => $subject)
                                @if (array_key_exists($id, $data['subjects']))
                                    <td>{{ $data['subjects'][$id] }}</td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
    <script>
        window.onload = function() {
            ExportToExcel('xlsx');
        }

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('examResult');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`ExamResults.` + (type || 'xlsx')));
        }
    </script>
@endsection
