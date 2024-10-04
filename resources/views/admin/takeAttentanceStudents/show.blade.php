@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.takeAttentanceStudent.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.take-attentance-students.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.takeAttentanceStudent.fields.id') }}
                        </th>
                        <td>
                            {{ $takeAttentanceStudent->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.takeAttentanceStudent.fields.enroll_master') }}
                        </th>
                        <td>
                            {{ $takeAttentanceStudent->enroll_master->enroll_master_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.takeAttentanceStudent.fields.period') }}
                        </th>
                        <td>
                            {{ $takeAttentanceStudent->period }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.takeAttentanceStudent.fields.taken_from') }}
                        </th>
                        <td>
                            {{ $takeAttentanceStudent->taken_from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.takeAttentanceStudent.fields.approved_by') }}
                        </th>
                        <td>
                            {{ $takeAttentanceStudent->approved_by }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.take-attentance-students.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection