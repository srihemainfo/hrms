@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.iv.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.ivs.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.iv.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $iv->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.iv.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $iv->name->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.iv.fields.topic') }}
                                    </th>
                                    <td>
                                        {{ $iv->topic }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.iv.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $iv->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.iv.fields.from_date') }}
                                    </th>
                                    <td>
                                        {{ $iv->from_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.iv.fields.to_date') }}
                                    </th>
                                    <td>
                                        {{ $iv->to_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.iv.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $iv->remarks }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.ivs.index') }}">
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