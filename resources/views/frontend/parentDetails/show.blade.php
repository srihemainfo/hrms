@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.parentDetail.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.parent-details.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.father_name') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->father_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.father_mobile_no') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->father_mobile_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.fathers_occupation') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->fathers_occupation }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.mother_name') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->mother_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.mother_mobile_no') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->mother_mobile_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.mothers_occupation') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->mothers_occupation }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.guardian_name') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->guardian_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.guardian_mobile_no') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->guardian_mobile_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.parentDetail.fields.gaurdian_occupation') }}
                                    </th>
                                    <td>
                                        {{ $parentDetail->gaurdian_occupation }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.parent-details.index') }}">
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