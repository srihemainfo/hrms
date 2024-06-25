@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.address.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.addresses.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $address->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.address_type') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Address::ADDRESS_TYPE_SELECT[$address->address_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $address->name->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.room_no_and_street') }}
                                    </th>
                                    <td>
                                        {{ $address->room_no_and_street }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.area_name') }}
                                    </th>
                                    <td>
                                        {{ $address->area_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.district') }}
                                    </th>
                                    <td>
                                        {{ $address->district }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.pincode') }}
                                    </th>
                                    <td>
                                        {{ $address->pincode }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.state') }}
                                    </th>
                                    <td>
                                        {{ $address->state }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.address.fields.country') }}
                                    </th>
                                    <td>
                                        {{ $address->country }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.addresses.index') }}">
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