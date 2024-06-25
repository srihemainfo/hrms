@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.personalDetail.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.personal-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.id') }}
                        </th>
                        <td>
                            {{ $personalDetail->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.user_name') }}
                        </th>
                        <td>
                            {{ $personalDetail->user_name->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.age') }}
                        </th>
                        <td>
                            {{ $personalDetail->age }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.dob') }}
                        </th>
                        <td>
                            {{ $personalDetail->dob }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.email') }}
                        </th>
                        <td>
                            {{ $personalDetail->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.mobile_number') }}
                        </th>
                        <td>
                            {{ $personalDetail->mobile_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.aadhar_number') }}
                        </th>
                        <td>
                            {{ $personalDetail->aadhar_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.blood_group') }}
                        </th>
                        <td>
                            {{ $personalDetail->blood_group->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.mother_tongue') }}
                        </th>
                        <td>
                            {{ $personalDetail->mother_tongue->mother_tongue ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.religion') }}
                        </th>
                        <td>
                            {{ $personalDetail->religion->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.community') }}
                        </th>
                        <td>
                            {{ $personalDetail->community->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.state') }}
                        </th>
                        <td>
                            {{ $personalDetail->state }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.personalDetail.fields.country') }}
                        </th>
                        <td>
                            {{ $personalDetail->country }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.personal-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection