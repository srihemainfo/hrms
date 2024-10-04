@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.intern.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.interns.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.intern.fields.id') }}
                        </th>
                        <td>
                            {{ $intern->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.intern.fields.name') }}
                        </th>
                        <td>
                            {{ $intern->name->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.intern.fields.topic') }}
                        </th>
                        <td>
                            {{ $intern->topic }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.intern.fields.from_date') }}
                        </th>
                        <td>
                            {{ $intern->from_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.intern.fields.to_date') }}
                        </th>
                        <td>
                            {{ $intern->to_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.intern.fields.progress_report') }}
                        </th>
                        <td>
                            {{ $intern->progress_report }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.interns.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection