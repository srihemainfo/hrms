@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.workshop.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.workshops.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.workshop.fields.id') }}
                        </th>
                        <td>
                            {{ $workshop->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workshop.fields.user_name') }}
                        </th>
                        <td>
                            {{ $workshop->user_name->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workshop.fields.topic') }}
                        </th>
                        <td>
                            {{ $workshop->topic }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workshop.fields.remarks') }}
                        </th>
                        <td>
                            {{ $workshop->remarks }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workshop.fields.from_date') }}
                        </th>
                        <td>
                            {{ $workshop->from_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.workshop.fields.to_date') }}
                        </th>
                        <td>
                            {{ $workshop->to_date }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.workshops.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection