@extends('layouts.admin')
@section('content')
    <a class="btn btn-default" href="{{ route('admin.exam-fee.index') }}">
        Back To List
    </a>
    <div class="card mt-3">
        <div class="card-header">
            Exam Registration List
        </div>

        <div class="card-body" style="max-width:100%;min-width:100%;overflow-x: auto;">
            <table class=" table table-bordered table-striped table-hover text-center" style="overflow-x: auto;"
                id="examFee">
                <thead>
                    <tr>
                        <th>
                            Academic Year
                        </th>
                        <th>
                            Month and Year of Exam
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Semester
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $ay }}</td>
                        <td>{{ $exam_date }}</td>
                        <td>{{ $course }}</td>
                        <td>{{ $batch }}</td>
                        <td>{{ $semester }}</td>
                    </tr>
                    <tr>
                        <td>S.No</td>
                        <td><b>Register No</b></td>
                        <td><b>Student Name</b></td>
                        <td><b>Total Registered Subjects</b></td>
                        <td><b>Total Exam Fees</b></td>

                    </tr>
                    @foreach ($datas as $i => $data)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                {{ $data->register_no }}
                            </td>
                            <td>
                                {{ $data->name }}
                            </td>
                            <td>
                                {{ $data->subject_count }}
                            </td>
                            <td>
                                {{ $data->exam_fee_sum }}
                            </td>

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
            var elt = document.getElementById('examFee');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`examFees Report.` + (type || 'xlsx')));
        }
    </script>
@endsection
