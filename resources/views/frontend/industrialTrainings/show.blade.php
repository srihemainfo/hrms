@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.industrialTraining.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.industrial-trainings.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $industrialTraining->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $industrialTraining->name->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.topic') }}
                                    </th>
                                    <td>
                                        {{ $industrialTraining->topic }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $industrialTraining->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $industrialTraining->remarks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.from_date') }}
                                    </th>
                                    <td>
                                        {{ $industrialTraining->from_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.industrialTraining.fields.to_date') }}
                                    </th>
                                    <td>
                                        {{ $industrialTraining->to_date }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.industrial-trainings.index') }}">
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