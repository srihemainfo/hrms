@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.emailSetting.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.email-settings.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.host_name') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->host_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.user_name') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->user_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.password') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->password }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.smtp_secure') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->smtp_secure }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.port_no') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->port_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.from') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->from }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.to') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.cc') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->cc }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.emailSetting.fields.bcc') }}
                                    </th>
                                    <td>
                                        {{ $emailSetting->bcc }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.email-settings.index') }}">
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