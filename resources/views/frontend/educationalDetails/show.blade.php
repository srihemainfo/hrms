@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.educationalDetail.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.educational-details.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.education_type') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->education_type->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.institute_name') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->institute_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.institute_location') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->institute_location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.medium') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->medium->medium ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.board_or_university') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->board_or_university }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.marks') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->marks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.marks_in_percentage') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->marks_in_percentage }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_1') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->subject_1 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_1') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->mark_1 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_2') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->subject_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_2') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->mark_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_3') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->subject_3 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_3') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->mark_3 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_4') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->subject_4 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_4') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->mark_4 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_5') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->subject_5 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_5') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->mark_5 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_6') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->subject_6 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_6') }}
                                    </th>
                                    <td>
                                        {{ $educationalDetail->mark_6 }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.educational-details.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection