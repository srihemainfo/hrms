@extends('layouts.admin')
@section('content')
    <div class="row gutters" style="align-items: center;">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 mb-3">

            <a class="btn btn-primary" href="{{ route('admin.hrm-request-leaves.index') }}">
                {{ trans('global.back_to_list') }}
            </a>

        </div>
    </div>
    @if (count($details) > 0)
        <div class="card">
            <div class="card-body">
                <div class="row gutters">
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <span class="btn btn-secondary">Staff Name : {{ $name }}</span>
                    </div>
                    {{-- <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="margin-left: 4rem;">
                        <span class="btn btn-secondary">Department : {{ $dept }}</span>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" style="text-align: center;">
                    <thead>
                        <th>Leave Type</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        {{-- <th>Subject</th> --}}
                        <th>Total Days</th>
                        <th>Approved By</th>
                    </thead>
                    <tbody>
                        @foreach ($details as $data)
                            <tr>
                                <td>
                                    @foreach ($leave_types as $id => $entry)
                                        @if ($id == $data->leave_type)
                                            {{ $entry }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $data->from_date }}</td>
                                <td>{{ $data->to_date }}</td>
                                {{-- <td>{{ $data->subject }}</td> --}}
                                <td>{{ $data->total_days + ($data->total_days_nxt_mn != null ? $data->total_days_nxt_mn : 0) }}</td>
                                <td>{{ $data->approved_by }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card h-100">
            <div class="card-body">
                <div class="row gutters">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
                        style="text-align: center;font-size:1.5rem;">
                        No Data..

                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
