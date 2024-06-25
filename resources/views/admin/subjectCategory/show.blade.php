@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Show Subject Category
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.subject_category.index') }}">
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
                                {{ $subjectCategory->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Regulation
                            </th>
                            <td>
                                {{ $subjectCategory->regulation->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Subject Category
                            </th>
                            <td>
                                {{ $subjectCategory->name }}
                            </td>
                        </tr>

                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.subject_category.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
