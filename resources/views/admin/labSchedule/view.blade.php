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
        <a class="btn btn-default " href="{{ route('admin.lab_mark.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header text-uppercase text-center">
            <p> <strong> View {{ $labMarkschedule->MarkType }} LAB Schedule </strong></p>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="" for="accademicYear">Academic Year</label>
                    <input class="form-control" value="{{ $labMarkschedule->accademicYear ?? '' }}" readonly>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="semesterType" class="">Semester Type</label>
                    <input class="form-control" value="{{ $labMarkschedule->semesterType ?? '' }}" readonly>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <input class="form-control" value="{{ $labMarkschedule->course_id ?? '' }}" readonly>

                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="">Year</label>
                    <input class="form-control" value="{{ $labMarkschedule->year ?? '' }}" readonly>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="semester" class="">Semester</label>
                    <input class="form-control" value="{{ $labMarkschedule->semester ?? '' }}" readonly>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label class="" for="examName">Title of the Exam</label>
                    <input class="form-control" type="text" value="{{ $labMarkschedule->exam_name }}" readonly>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="section" class="required">Sections</label>
                    <input class="form-control" value="{{ $labMarkschedule->section ?? '' }}" readonly>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <label for="due_date" class="">Due Date</label>
                    <input class="form-control" value="{{ $labMarkschedule->due_date }}" readonly>
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
                            <th colspan='4'>Lab schedule Subject Details</th>
                        </tr>

                    </thead>
                    <thead class='bg-primary'>
                        <tr>
                            <th>Subject Tittle</th>
                            <th>Subject code</th>
                        </tr>
                    </thead>
                    <tbody id="tabledata">

                        @foreach ($labMarkschedule->newsubject as $subjectA)
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
