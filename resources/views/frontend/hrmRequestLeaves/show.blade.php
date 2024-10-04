@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.hrmRequestLeaf.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.hrm-request-leaves.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestLeaf->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestLeaf->user->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.from_date') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestLeaf->from_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.to_date') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestLeaf->to_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.reason') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestLeaf->reason }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestLeaf.fields.approved_by') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestLeaf->approved_by }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.hrm-request-leaves.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection