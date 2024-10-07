@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       Staff Biometrics
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.staff-biometrics.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                           Date
                        </th>
                        <td>
                            {{ $staffBiometric->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Employee Code
                        </th>
                        <td>
                            {{ $staffBiometric->employee_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Stsff Code
                        </th>
                        <td>
                            {{ $staffBiometric->staff_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Employee Name
                        </th>
                        <td>
                            {{ $staffBiometric->employee_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           In Time
                        </th>
                        <td>
                            {{ $staffBiometric->in_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Out tiime
                        </th>
                        <td>
                            {{ $staffBiometric->out_time }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.staff-biometrics.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
