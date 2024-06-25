@extends('layouts.studentHome')
@section('content')
    <a class="enroll_generate_bn bg-success" href="{{ route('admin.student-apply-certificate.stu_create') }}">Certificate
        Application</a>
    <div class="card mt-3" style="overflow-x: auto;">
        <div class="card-header text-primary">
            Applied Certificates List
        </div>
        <div class="card-body">
            <table class="table table-bordered datatable text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Certificate Type</th>
                        <th>Reason For Applying</th>
                        <th>Status</th>
                        <th>Approved Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($list) > 0)
                        @foreach ($list as $i => $data)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $data->date }}</td>
                                <td>{{ $data->certificate }}</td>
                                <td>{{ $data->purpose }}</td>
                                <td>
                                    @if ($data->status == 0)
                                        <span class="btn-sm btn-warning">Submitted For AO Verification</span>
                                    @elseif ($data->status == 1)
                                        <span class="btn-sm btn-primary">Waiting for Principal Sign</span>
                                    @elseif ($data->status == 2)
                                        <span class="btn-sm btn-success">Approved and Signed</span>
                                    @elseif ($data->status == 3)
                                        <span class="btn-sm btn-info">Need Revision </span>
                                        <div>({{ $data->action_reason }})</div>
                                    @elseif ($data->status == 4)
                                        <span class="btn-sm btn-danger">Rejected</span>
                                        <div>({{ $data->action_reason }})</div>
                                    @endif

                                </td>
                                <td>{{ $data->approved_date }}</td>
                                <td>
                                    <a class="btn btn-xs btn-primary"
                                        href="{{ route('admin.student-apply-certificate.show', $data->id) }}" target="_blank">
                                        {{ trans('global.view') }}
                                    </a>
                                    @if ($data->status == 0 || $data->status == 3)
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.student-apply-certificate.edit', $data->id) }}"
                                            target="_blank">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
