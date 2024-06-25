@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header text-center">
            Class Wise Exam Enrollment
        </div>
        <div class="card-body">
            <div class="card" id="classWiseEnroller">
                <div class="card-body">
                    <div class="row">

                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label class="required" for="regulation">Regulation</label>
                            <select class="form-control select2" name="regulation" id="regulation" disabled>

                                @foreach ($regulations as $id => $entry)
                                    @if ($regulation == $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="academic_year" class="required">Academic Year</label>
                            <select class="form-control select2" name="academic_year" id="academic_year" disabled>

                                @foreach ($ays as $id => $entry)
                                    @if ($ay == $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label class="required" for="exam_month">Exam Month </label>
                            <select class="form-control select2" name="exam_month" id="exam_month" disabled>
                                <option value="{{ $exam_month }}">{{ $exam_month }}</option>
                            </select>
                        </div>
                        <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="exam_year" class="required">Exam Year</label>
                            <select class="form-control select2 " name="exam_year" id="exam_year" disabled>

                                @foreach ($years as $id => $entry)
                                    @if ($exam_year == $entry)
                                        <option value="{{ $entry }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="course" class="required">Course</label>
                            <select class="form-control select2" name="course" id="course" disabled>

                                @foreach ($courses as $id => $entry)
                                    @if ($course == $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="batch" class="required">Batch</label>
                            <select class="form-control select2" name="batch" id="batch" disabled>

                                @foreach ($batches as $id => $entry)
                                    @if ($batch == $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label for="semester" class="required">Current Semester</label>
                            <select class="form-control select2" name="semester" id="semester" disabled>
                                <option value="{{ $semester }}">0{{ $semester }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="classWiseList">
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center" id="regularTableClassWise">
                        <thead>
                            <tr>
                                <th colspan="3"> Regular Subjects</th>
                            </tr>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Total No of Students</th>
                            </tr>
                        </thead>
                        <tbody id="regularTableBody">
                            @if (count($regularData) > 0)
                                @foreach ($regularData as $data)
                                    <tr>
                                        <td>{{ $data['subject_code'] }}</td>
                                        <td>{{ $data['subject_name'] }}</td>
                                        <td>{{ $data['count'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3"> No Data Available...</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <table class="table table-bordered table-striped text-center mt-4" id="arrearTableClassWise">
                        <thead>
                            <tr>
                                <th colspan="4"> Arrear Subjects</th>
                            </tr>
                            <tr>
                                <th>Subject Semester</th>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Total No of Students</th>
                            </tr>
                        </thead>
                        <tbody id="arrearTableBody">
                            @if (count($arrearData) > 0)
                                @foreach ($arrearData as $data)
                                    <tr>
                                        <td>{{ $data['subject_sem'] }}</td>
                                        <td>{{ $data['subject_code'] }}</td>
                                        <td>{{ $data['subject_name'] }}</td>
                                        <td>{{ $data['count'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4"> No Data Available...</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
