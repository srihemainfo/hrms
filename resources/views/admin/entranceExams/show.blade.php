@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.entranceExam.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entrance-exams.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.entranceExam.fields.id') }}
                        </th>
                        <td>
                            {{ $entranceExam->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entranceExam.fields.name') }}
                        </th>
                        <td>
                            {{ $entranceExam->name->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entranceExam.fields.exam_type') }}
                        </th>
                        <td>
                            {{ $entranceExam->exam_type->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entranceExam.fields.passing_year') }}
                        </th>
                        <td>
                            {{ $entranceExam->passing_year }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entranceExam.fields.scored_mark') }}
                        </th>
                        <td>
                            {{ $entranceExam->scored_mark }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entranceExam.fields.total_mark') }}
                        </th>
                        <td>
                            {{ $entranceExam->total_mark }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entranceExam.fields.rank') }}
                        </th>
                        <td>
                            {{ $entranceExam->rank }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entrance-exams.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection