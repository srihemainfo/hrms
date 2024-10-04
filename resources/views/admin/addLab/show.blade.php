@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Lab
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tool-lab.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                           ID
                        </th>
                        <td>
                            {{ $lab->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Department
                        </th>
                        <td>
                            {{ $lab->department['name'] }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Lab Name
                        </th>
                        <td>
                            {{ $lab->lab_name }}
                        </td>
                    </tr>

                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tool-lab.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
