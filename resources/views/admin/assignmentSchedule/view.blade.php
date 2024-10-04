@extends('layouts.admin')
@section('content')
    <style>
        .borderNone {
            border: none;
        }

        .form-control {
            background-color: #dee2e6 !important;
        }

        @media screen and (max-width: 575px) {
            .select2 {
                width: 100% !important;
            }
        }
    </style>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.assignment.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">

            <p class="text-center text-uppercase"> <strong> View Assignment Schedule</strong>
            </p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="d-block" for="academic_year">Academic Year</label>
                    <input class="form-control" value="{{ $assignmentModel->academic_year ?? '' }}" readonly>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="semester_type" class="d-block">Semester Type</label>
                    <input class="form-control" value="{{ $assignmentModel->semester_type ?? '' }}" readonly>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="d-block" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <input class="form-control" value="{{ $assignmentModel->course_id ?? '' }}" readonly>

                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="d-block">Year</label>
                    <input class="form-control" value="{{ $assignmentModel->year ?? '' }}" readonly>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="semester" class="d-block">Semester</label>
                    <input class="form-control" value="{{ $assignmentModel->semester ?? '' }}" readonly>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="d-block" for="examName">Title of the Exam</label>
                    <input class="form-control" type="text" value="{{ $assignmentModel->exam_name }}" readonly>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class='d-block' for="section">Sections</label>
                    <input class="form-control" value="{{ $assignmentModel->section ?? '' }}" readonly>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="due_date" class="d-block">Due Date</label>
                    <input class="form-control"
                        value="{{ $assignmentModel->due_date ? date('d-m-Y', strtotime($assignmentModel->due_date)) : '' }}"
                        readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center table-striped table-hover mt-3">
                    <thead>
                        <tr class='text-uppercase   '>
                            <th colspan='4'>Assignment schedule Subject Details</th>
                        </tr>

                    </thead>
                    <thead class='bg-primary'>
                        <tr>
                            <th>Subject Tittle</th>
                            <th>Subject code</th>
                        </tr>
                    </thead>
                    <tbody id="tabledata">

                        @foreach ($assignmentModel->newsubject as $subjectA)
                            <tr>
                                <td class="subject-code">{{ $subjectA['name'] }}</td>
                                <td>{{ $subjectA['code'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
