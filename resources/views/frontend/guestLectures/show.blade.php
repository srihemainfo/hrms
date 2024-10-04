@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.guestLecture.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.guest-lectures.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.user_name') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->user_name->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.topic') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->topic }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->remarks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.from_date_and_time') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->from_date_and_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.to_date_and_time') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->to_date_and_time }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.guest-lectures.index') }}">
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