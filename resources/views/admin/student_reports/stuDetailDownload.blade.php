@extends('layouts.admin')
@section('content')
    <a class="btn btn-default" href="{{ route('admin.student-mandatory-details.index') }}">
        Back To List
    </a>
    <div class="card mt-3">
        <div class="card-header">
            Students Mandatory Details
        </div>

        <div class="card-body" style="max-width:100%;min-width:100%;overflow-x: auto;">
            <table class=" table table-bordered table-striped table-hover text-center" style="overflow-x: auto;"
                id="studentDetails">
                <thead>
                    <tr>
                        <th>
                            Academic Year
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
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $ay }}</td>
                        <td>{{ $course_short_form }}</td>
                        <td>{{ $batch }}</td>
                        <td>0{{ $semester }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>S.No</b></td>
                        <td><b>Register No</b></td>
                        <td><b>Student Name</b></td>
                        <td><b>Section</b></td>
                        <td><b>Gender</b></td>
                        <td><b>Father Name</b></td>
                        <td><b>Date of Birth</b></td>
                        <td><b>Mobile No</b></td>
                        <td><b>E-Mail ID</b></td>
                        <td><b>Photo</b></td>

                    </tr>
                    @foreach ($datas as $i => $data)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                {{ $data->register_no }}
                            </td>
                            <td>
                                {{ $data->name }}
                            </td>
                            <td>
                                {{ $data->section }}
                            </td>
                            <td>
                                {{ $data->personal_details != null ? $data->personal_details->gender : '' }}
                            </td>
                            <td>
                                {{ $data->parent_details != null ? $data->parent_details->father_name : '' }}
                            </td>
                            <td>
                                {{ $data->personal_details != null ? $data->personal_details->dob : '' }}
                            </td>
                            <td>
                                {{ $data->personal_details != null ? $data->personal_details->mobile_number : '' }}
                            </td>
                            <td>
                                {{ $data->personal_details != null ? $data->personal_details->email : '' }}
                            </td>
                            <td>
                                {{ $data->photo == true ? 'Yes' : 'No' }}
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
            var elt = document.getElementById('studentDetails');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`Student Details Report.` + (type || 'xlsx')));
        }
    </script>
@endsection
