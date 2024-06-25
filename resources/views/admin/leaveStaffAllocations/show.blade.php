@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.leaveStaffAllocation.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.leave-staff-allocations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.leaveStaffAllocation.fields.id') }}
                        </th>
                        <td>
                            {{ $leaveStaffAllocation->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.leaveStaffAllocation.fields.user') }}
                        </th>
                        <td>
                            {{ $leaveStaffAllocation->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.leaveStaffAllocation.fields.academic_year') }}
                        </th>
                        <td>
                            {{ $leaveStaffAllocation->academic_year->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.leaveStaffAllocation.fields.no_of_leave') }}
                        </th>
                        <td>
                            {{ $leaveStaffAllocation->no_of_leave }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.leave-staff-allocations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection