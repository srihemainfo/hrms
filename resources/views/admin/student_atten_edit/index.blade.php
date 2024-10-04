@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Student Attendence Edit Request
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'Edit' ? ' active' : '' }}"
                        href="{{ route('admin.student-att-modification.index', ['status' => 'Edit']) }}">Edit Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status === 'Delete' ? ' active' : '' }}"
                        href="{{ route('admin.student-att-modification.index', ['status' => 'Delete']) }}">Delete Requests</a>
                </li>
            </ul>
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Sttp text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Class</th>
                        <th>From</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Period</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($studentAtt as $index => $studentAtts)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $studentAtts->enroll_master }}</td>
                            <td>{{ $studentAtts->staff }}</td>
                            <td>{{ $studentAtts->reason }}</td>
                            <td>{{ $studentAtts->actual_date }}</td>
                            <td>{{ $studentAtts->period }}</td>
                            <td>{{ $studentAtts->subject }}</td>
                            <td>
                                <form action="{{ route('admin.student-att-modification.approve') }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $studentAtts->id }}">
                                    <input type="hidden" name="action" value="{{ $status }}">
                                    <button class="btn btn-primary" type="submit">Approve</button>
                                </form>
                                {{-- <form action="{{ route('admin.student-att-modification.reject') }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $studentAtts->id }}">
                                    <button class="btn btn-danger" type="submit">Reject</button>
                                </form> --}}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No data Found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>
@endsection
