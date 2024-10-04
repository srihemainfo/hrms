@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.teachingStaff.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.teaching-staffs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.teachingStaff.fields.id') }}
                        </th>
                        <td>
                            {{ $teachingStaff->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.teachingStaff.fields.name') }}
                        </th>
                        <td>
                            {{ $teachingStaff->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.teachingStaff.fields.subject') }}
                        </th>
                        <td>
                            {{ $teachingStaff->subject->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.teachingStaff.fields.enroll_master') }}
                        </th>
                        <td>
                            {{ $teachingStaff->enroll_master->enroll_master_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.teachingStaff.fields.working_as') }}
                        </th>
                        <td>
                            {{ $teachingStaff->working_as->title ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.teaching-staffs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection