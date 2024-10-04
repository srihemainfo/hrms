@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Driver
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.driver.index') }}">
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
                            {{ $driver->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.nonTeachingStaff.fields.name') }}
                        </th>
                        <td>
                            {{ $driver->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.nonTeachingStaff.fields.working_as') }}
                        </th>
                        <td>
                            {{ $driver->working_as->title ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.driver.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection