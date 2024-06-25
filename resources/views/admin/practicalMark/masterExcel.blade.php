@extends('layouts.admin')
@section('content')
    @if ($status)
        <div class="card mt-3">
            <div class="card-header">
                Practical Marks
            </div>

            <div class="card-body" style="max-width:100%;min-width:100%;overflow-x: auto;">
                <table class=" table table-bordered table-striped table-hover text-center" style="overflow-x: auto;"
                    id="practicalMark">
                    <thead>
                        <tr>
                            <th>Academic Year</th>
                            <th>Branch</th>
                            <th>Semester</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Exam Type</th>
                            <th>Exam Month</th>
                            <th>Exam Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $ay != null ? $ay->name : '' }}</td>
                            <td>{{ $course != null ? $course->short_form : '' }}</td>
                            <td>{{ $data[0]->subject_sem }}</td>
                            <td>{{ $subject != null ? $subject->name : '' }}</td>
                            <td>{{ $subject != null ? $subject->subject_code : '' }}</td>
                            <td>{{ $exam_type }}</td>
                            <td>{{ $exam_month }}</td>
                            <td>{{ $exam_year }}</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th>Register No</th>
                            <th>Mark Awarded</th>
                            <th>Mark in Words</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $detail)
                        <tr>
                        <td>{{ $detail->student != null ? $detail->student->register_no : '' }}</td>
                        <td>{{ $detail->mark != -1 ? $detail->mark : 'Absent' }}</td>
                        <td>{{ $detail->mark_in_word }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">No Data Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">{{ $data }}</div>
        </div>
    @endif
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
    <script>
        window.onload = function() {
            ExportToExcel('xlsx');
        }

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('practicalMark');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`PracticalMarks.` + (type || 'xlsx')));
        }
    </script>
@endsection
