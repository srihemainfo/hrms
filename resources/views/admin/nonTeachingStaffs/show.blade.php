@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.nonTeachingStaff.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.non-teaching-staffs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.nonTeachingStaff.fields.id') }}
                        </th>
                        <td>
                            {{ $nonTeachingStaff->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.nonTeachingStaff.fields.name') }}
                        </th>
                        <td>
                            {{ $nonTeachingStaff->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.nonTeachingStaff.fields.working_as') }}
                        </th>
                        <td>
                            {{ $nonTeachingStaff->working_as->title ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.non-teaching-staffs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection