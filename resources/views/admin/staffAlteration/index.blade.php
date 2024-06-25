@extends('layouts.teachingStaffHome')
@section('content')
    <div class="card">
        <div class="card-header text-center">
            <h5 class="text-primary">Staff Alteration Requests</h5>
        </div>
        <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
            <li class="nav-item">
                <a class="nav-link{{ $status2 == 'Sent' ? ' active' : '' }}"
                    href="{{ route('admin.staff-alteration-requests.index', ['status2' => 'Sent','status' => 'Pending']) }}">Sent</a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ $status2 == 'Received' ? ' active' : '' }}"
                    href="{{ route('admin.staff-alteration-requests.index', ['status2' => 'Received','status' => 'Pending']) }}">Received</a>
            </li>
        </ul>
        <div class="card-body" style="max-width:100%;overflow-x:auto;">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <a class="nav-link{{ $status == 'Pending' ? ' active' : '' }}"
                        href="{{ route('admin.staff-alteration-requests.index', ['status2' => $status2,'status' => 'Pending']) }}">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status == 'Approved' ? ' active' : '' }}"
                        href="{{ route('admin.staff-alteration-requests.index', ['status2' => $status2,'status' => 'Approved']) }}">Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ $status == 'Rejected' ? ' active' : '' }}"
                        href="{{ route('admin.staff-alteration-requests.index', ['status2' => $status2,'status' => 'Rejected']) }}">Rejected</a>
                </li>
            </ul>
            <table class="table list_table" id="Requesting" style="min-width:700px;">
                <thead>
                    <tr>
                        <th>S NO</th>
                        <th>Staff Name</th>
                        <th>Period</th>
                        <th>Day</th>
                        <th>Time </th>
                        <th>Class</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($checking) > 0)
                        @foreach ($checking as $index => $checkings)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $checkings->name }} ({{ $checkings->staff_code }})</td>
                                <td>{{ $checkings->period }}</td>
                                <td>{{ $checkings->day }}</td>
                                <td>{{ $checkings->timeDeff }}</td>
                                <td>{{ $checkings->classname }}</td>
                                <td>
                                    @if($checkings->status == 0 && $checkings->from_id != auth()->user()->id )
                                    <form action="{{ route('admin.staff-request-leaves.approve') }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $checkings->id }}">
                                        <input type="hidden" name="to_id" value="{{ $checkings->to_id }}">
                                        <input type="hidden" name="period" value="{{ $checkings->class_period }}">
                                        <input type="hidden" name="day" value="{{ $checkings->day }}">
                                        <input type="hidden" name="classname" value="{{ $checkings->classID }}">
                                        <button class="btn btn btn-xs btn-primary" type="submit">Accept</button>
                                    </form>
                                    <form action="{{ route('admin.staff-request-leaves.reject') }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $checkings->id }}">
                                        <button class="btn btn btn-xs btn-danger" type="submit">Reject</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">
                                No Request Found
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>

    </div>
@endsection
