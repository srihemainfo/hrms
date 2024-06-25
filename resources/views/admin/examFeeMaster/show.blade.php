@extends('layouts.admin')
@section('content')
    <a class="btn btn-default mb-3" href="{{ route('admin.examfee-master.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Regulation</th>
                        <th>Subject Type</th>
                        <th>Exam Fee</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($show as $i => $data)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                @if ($data->regulations != null)
                                    {{ $data->regulations->name }}
                                @endif
                            </td>
                            <td>
                                @if ($data->subject_types != null)
                                    {{ $data->subject_types->name }}
                                @endif
                            </td>
                            <td>{{ $data->fee }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
