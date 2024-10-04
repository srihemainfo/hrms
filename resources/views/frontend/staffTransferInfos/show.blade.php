@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.staffTransferInfo.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.staff-transfer-infos.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $staffTransferInfo->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.enroll_master') }}
                                    </th>
                                    <td>
                                        {{ $staffTransferInfo->enroll_master->enroll_master_number ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.period') }}
                                    </th>
                                    <td>
                                        {{ $staffTransferInfo->period }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.from_user') }}
                                    </th>
                                    <td>
                                        {{ $staffTransferInfo->from_user }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.to_user') }}
                                    </th>
                                    <td>
                                        {{ $staffTransferInfo->to_user }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.transfer_date') }}
                                    </th>
                                    <td>
                                        {{ $staffTransferInfo->transfer_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.staffTransferInfo.fields.approved_by_user') }}
                                    </th>
                                    <td>
                                        {{ $staffTransferInfo->approved_by_user }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.staff-transfer-infos.index') }}">
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