@extends('layouts.admin')
@section('content')
    <a class="btn btn-default" href="{{ route('admin.internalmark_generate') }}">
        Back To List
    </a>
    <div class="card mt-3">
        <div class="card-header">
            Internal Marks
        </div>

        <div class="card-body" style="max-width:100%;min-width:100%;overflow-x: auto;">
            <table class=" table table-bordered table-striped table-hover text-center" style="overflow-x: auto;"
                id="internalMarks">
                <thead>
                    <tr>
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
                            Subject Name
                        </th>
                        <th>
                            Subject Code
                        </th>

                        <th>Subject Type</th>


                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $theBatch }}</td>
                        <td>{{ $theAy }}</td>
                        <td>{{ $theCourse }}</td>
                        <td>{{ $theSemester }}</td>
                        <td>{{ $sub->name }}</td>
                        <td>{{ $sub->subject_code }}</td>

                        <td>{{ $subject_type }}</td>

                    </tr>
                    @if ($subject_type == 'THEORY')
                        <tr>
                            <td>
                                <b>Register No</b>
                            </td>
                            <td>
                                <b>Student Name</b>
                            </td>
                            <td><b>CAT-1</b></td>
                            <td><b>CAT-2</b></td>
                            <td><b>CAT-3</b></td>
                            <td><b>ASSIGNMENT</b></td>
                            <td><b>Total</b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><b>{{ $co1 }}</b></td>
                            <td><b>{{ $co2 }}</b></td>
                            <td><b>{{ $co3 }}</b></td>
                            <td><b>{{ $as }}</b></td>
                            <td><b>{{ $theTotal }}</b></td>
                        </tr>
                        @foreach ($stuArray as $data)
                            <tr>
                                <td>
                                    {{ $data['register_no'] }}
                                </td>
                                <td>
                                    {{ $data['name'] }}
                                </td>
                                <td>
                                    {{ $data['co1'] }}
                                </td>
                                <td>
                                    {{ $data['co2'] }}
                                </td>
                                <td>
                                    {{ $data['co3'] }}
                                </td>
                                <td>
                                    {{ $data['as'] }}
                                </td>
                                <td>
                                    {{ $data['total'] }}
                                </td>
                            </tr>
                        @endforeach
                    @elseif ($subject_type == 'PROJECT')
                        <tr>
                            <td>
                                <b>Register No</b>
                            </td>
                            <td>
                                <b>Student Name</b>
                            </td>
                            <td><b>Review-1</b></td>
                            <td><b>Review-2</b></td>
                            <td><b>Review-3</b></td>
                            <td><b>Total</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><b>{{ $p_1 }}</b></td>
                            <td><b>{{ $p_2 }}</b></td>
                            <td><b>{{ $p_3 }}</b></td>
                            <td><b>{{ $theTotal }}</b></td>
                            <td></td>
                        </tr>
                        @foreach ($stuArray as $data)
                            <tr>
                                <td>
                                    {{ $data['register_no'] }}
                                </td>
                                <td>
                                    {{ $data['name'] }}
                                </td>
                                <td>
                                    {{ $data['p1'] }}
                                </td>
                                <td>
                                    {{ $data['p2'] }}
                                </td>
                                <td>
                                    {{ $data['p3'] }}
                                </td>
                                <td>
                                    {{ $data['total'] }}
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                    @elseif ($subject_type == 'LABORATORY')
                        <tr>
                            <td>
                                <b>Register No</b>
                            </td>
                            <td>
                                <b>Student Name</b>
                            </td>
                            <td><b>Cycle-1</b></td>
                            <td><b>Cycle-2</b></td>
                            <td><b>Model-1</b></td>
                            <td><b>Total</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><b>{{ $cy1 }}</b></td>
                            <td><b>{{ $cy2 }}</b></td>
                            <td><b>{{ $mod }}</b></td>
                            <td><b>{{ $theTotal }}</b></td>
                            <td></td>
                        </tr>
                        @foreach ($stuArray as $data)
                            <tr>
                                <td>
                                    {{ $data['register_no'] }}
                                </td>
                                <td>
                                    {{ $data['name'] }}
                                </td>
                                <td>
                                    {{ $data['cy1'] }}
                                </td>
                                <td>
                                    {{ $data['cy2'] }}
                                </td>
                                <td>
                                    {{ $data['mod'] }}
                                </td>
                                <td>
                                    {{ $data['total'] }}
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
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
            var elt = document.getElementById('internalMarks');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`InternalMarks.` + (type || 'xlsx')));
        }
    </script>
@endsection
