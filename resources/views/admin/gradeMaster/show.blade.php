@extends('layouts.admin')
@section('content')
    <a class="btn btn-default mb-3" href="{{ route('admin.grade-master.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Regulation</th>
                        <th>Grade Letter</th>
                        <th>Grade Point</th>
                        <th>Result</th>
                        <th>Include In Grade Sheet</th>
                        <th>Include In Grade Book</th>
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
                            <td>{{ $data->grade_letter }}</td>
                            <td>{{ $data->grade_point }}</td>
                            <td>{{ $data->result }}</td>
                            <td>
                                @if ($data->grade_sheet_show == 1)
                                    YES
                                @else
                                    NO
                                @endif
                            </td>
                            <td>
                                @if ($data->grade_book_show == 1)
                                    YES
                                @else
                                    NO
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
