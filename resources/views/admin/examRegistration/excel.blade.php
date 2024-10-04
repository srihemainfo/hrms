@extends('layouts.admin')
@section('content')
<a class="btn btn-default" href="{{ route('admin.exam-registrations.index') }}">
   Back To List
</a>
    <div class="card mt-3">
        <div class="card-header">
            Exam Registration List
        </div>

        <div class="card-body" style="max-width:100%;min-width:100%;overflow-x: auto;">
            <table class=" table table-bordered table-striped table-hover text-center" style="overflow-x: auto;" id="examRegistration">
                <thead>
                    <tr>
                        <th>
                            Regulation
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Academic Year
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            Register No
                        </th>
                        <th>
                            Student Name
                        </th>
                        <th>
                            Subject Code
                        </th>
                        <th>
                            Subject Name
                        </th>
                        <th>
                            Credits
                        </th>
                        <th>
                            Subject Type
                        </th>
                        <th>
                            Subject Semester
                        </th>
                        <th>
                            Exam Type
                        </th>
                        <th>
                            Exam Fee
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($get as $data)
                        <tr>
                            <td>
                                {{ $data->regulations->name }}
                            </td>
                            <td>
                                {{ $data->batches->name }}
                            </td>
                            <td>
                                {{ $data->ay->name }}
                            </td>
                            <td>
                                {{ $data->courses->short_form }}
                            </td>
                            <td>
                                {{ $data->semester }}
                            </td>
                            <td>
                                {{ $data->student->register_no ?? '' }}
                            </td>
                            <td>
                                {{ $data->student->name ?? ''}}
                            </td>
                            <td>
                                {{ $data->subject->subject_code ?? ''}}
                            </td>
                            <td>
                                {{ $data->subject->name ?? ''}}
                            </td>
                            <td>
                                {{-- {{ $data->subject->credits }} --}}
                                {{ $data->credits }}
                            </td>
                            <td>
                                {{-- {{ $data->subject->subject_type->name }} --}}
                                {{ $data->subject_type }}
                            </td>
                            <td>
                                {{ $data->subject_sem }}
                            </td>
                            <td>
                                {{ $data->exam_type }}
                            </td>
                            <td>
                                {{ $data->exam_fee }}
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
    window.onload = function(){
        ExportToExcel('xlsx');
    }
    function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('examRegistration');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`ExamRegistrations.` + (type || 'xlsx')));
        }
</script>
@endsection
