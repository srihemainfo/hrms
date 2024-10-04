@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.toolsCourse.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tools-courses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.toolsCourse.fields.id') }}
                        </th>
                        <td>
                            {{ $toolsCourse->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{-- {{ trans('cruds.toolsCourse.fields.department') }} --}}
                            {{ trans('department') }}
                        </th>
                        <td>
                            {{ $toolsCourse->department->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.toolsCourse.fields.name') }}
                        </th>
                        <td>
                            {{ $toolsCourse->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Course In Short Form
                        </th>
                        <td>
                            {{ $toolsCourse->short_form }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tools-courses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
