@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.fundingdetali.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.fundingdetalis.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.fundingdetali.fields.id') }}
                        </th>
                        <td>
                            {{ $fundingdetali->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fundingdetali.fields.user_name') }}
                        </th>
                        <td>
                            {{ $fundingdetali->user_name->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fundingdetali.fields.topic') }}
                        </th>
                        <td>
                            {{ $fundingdetali->topic }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fundingdetali.fields.remark') }}
                        </th>
                        <td>
                            {{ $fundingdetali->remark }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.fundingdetalis.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection