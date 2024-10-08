@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.experienceDetail.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.experience-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.experienceDetail.fields.id') }}
                        </th>
                        <td>
                            {{ $experienceDetail->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.experienceDetail.fields.designation') }}
                        </th>
                        <td>
                            {{ $experienceDetail->designation }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.experienceDetail.fields.years_of_experience') }}
                        </th>
                        <td>
                            {{ $experienceDetail->years_of_experience }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.experienceDetail.fields.worked_place') }}
                        </th>
                        <td>
                            {{ $experienceDetail->worked_place }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.experienceDetail.fields.taken_subjects') }}
                        </th>
                        <td>
                            {{ $experienceDetail->taken_subjects }}
                        </td>
                    </tr> --}}
                    <tr>
                        <th>
                            {{ trans('cruds.experienceDetail.fields.from_date') }}
                        </th>
                        <td>
                            {{ $experienceDetail->from_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.experienceDetail.fields.to_date') }}
                        </th>
                        <td>
                            {{ $experienceDetail->to_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.experienceDetail.fields.name') }}
                        </th>
                        <td>
                            {{ $experienceDetail->name->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.experience-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
