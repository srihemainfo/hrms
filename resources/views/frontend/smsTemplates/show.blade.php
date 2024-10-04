@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.smsTemplate.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.sms-templates.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.smsTemplate.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $smsTemplate->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.smsTemplate.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $smsTemplate->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.smsTemplate.fields.sender') }}
                                    </th>
                                    <td>
                                        {{ $smsTemplate->sender }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.smsTemplate.fields.template') }}
                                    </th>
                                    <td>
                                        {{ $smsTemplate->template }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.smsTemplate.fields.type') }}
                                    </th>
                                    <td>
                                        {{ $smsTemplate->type }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.smsTemplate.fields.content') }}
                                    </th>
                                    <td>
                                        {{ $smsTemplate->content }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.sms-templates.index') }}">
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