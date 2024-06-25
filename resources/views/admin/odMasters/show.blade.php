@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.odMaster.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.od-masters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.odMaster.fields.id') }}
                        </th>
                        <td>
                            {{ $odMaster->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odMaster.fields.name') }}
                        </th>
                        <td>
                            {{ $odMaster->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odMaster.fields.level_1_role') }}
                        </th>
                        <td>
                            {{ $odMaster->level_1_role }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odMaster.fields.level_2_role') }}
                        </th>
                        <td>
                            {{ $odMaster->level_2_role }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odMaster.fields.level_3_role') }}
                        </th>
                        <td>
                            {{ $odMaster->level_3_role }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odMaster.fields.approved_by') }}
                        </th>
                        <td>
                            {{ $odMaster->approved_by }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.od-masters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection