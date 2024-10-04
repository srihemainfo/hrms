@php
   $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    }else{
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        input[type="file"] {
            /* background-color: #f2f2f2; */
            border: none;
            /* color: #555; */
            cursor: pointer;
            font-size: 16px;
            /* padding: 10px; */
        }

        input[type="file"]:focus {
            outline: none;
        }
    </style>
    <div class="container">
        {{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}

        <div class="row gutters">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-2 text-primary">Leave Form</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route('admin.student-request-leaves.store', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $leave_apply->id]) }}"
                            enctype="multipart/form-data" onsubmit=" return checkData()">
                            @csrf
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="leave_type">Leave Type</label>
                                        <select class="form-control select2" name="leave_type" id="leave_type">
                                            @if ($leave_apply)
                                                <option value="">Select Leave Type</option>
                                                <option value="Leave"
                                                    {{ $leave_apply->leave_type == 'Leave' ? 'selected' : '' }}>Leave
                                                </option>
                                                <option value="OD"
                                                    {{ $leave_apply->leave_type == 'OD' ? 'selected' : '' }}>OD</option>
                                            @else
                                                <option value="">No data available</option>
                                            @endif
                                        </select>
                                    </div>

                                </div>
                                {{-- @endif --}}
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="from_date_div">
                                    <div class="form-group">
                                        <label for="from_date">From Date</label>
                                        <input type="text" class="form-control date" name="from_date" id="from_date"
                                            placeholder="Enter From Date" autocomplete="off"
                                            value="{{ $leave_apply->from_date }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="to_date_div">
                                    <div class="form-group">
                                        <label for="to_date">To Date</label>
                                        <input type="text" class="form-control date" name="to_date" id="to_date"
                                            placeholder="Enter To Date" value="{{ $leave_apply->to_date }}"
                                            onblur="check_fromDate(this)" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="subject">Reason</label>
                                        <input type="text" class="form-control" name="reason" id="reason"
                                            placeholder="Enter Reason" value="{{ $leave_apply->reason }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="certificate">Documents</label>
                                        <input type="file" class="form-control" name="certificate" value="">
                                        <span class="text-primary">file should be in 2MB. PNG, JPG ,JPEG Formats
                                            Only</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
                                        <button type="submit" id="submit" name="submit"
                                            class="btn btn-primary Edit">{{ $leave_apply->add }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (count($list) > 0)
            <div class="row gutters mt-3 mb-3">
                <div class="col" style="padding:0;">
                    <div class="card h-100">

                        <div class="card-body table-responsive">
                            <h5 class="mb-3 text-primary">Requested Leave Details</h5>
                            <div class="table-responsive">
                                <table class="list_table table table-bordered table-striped table-hover text-center"
                                    style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>
                                                Leave Type
                                            </th>
                                            <th>
                                                From Date
                                            </th>
                                            <th>
                                                To Date
                                            </th>
                                            <th>
                                                Reason
                                            </th>
                                            <th>
                                                Document
                                            </th>
                                            <th>
                                                Rejected reason
                                            </th>
                                            <th>
                                                Status
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @for ($i = 0; $i < count($list); $i++)
                                            @if ($list[$i]->leave_type != '' || $list[$i]->leave_type != null)
                                                <tr>

                                                    <td>{{ $list[$i]->leave_type }}</td>

                                                    <td>{{ $list[$i]->from_date }}</td>
                                                    <td>{{ $list[$i]->to_date }}</td>
                                                    <td>{{ $list[$i]->reason }}</td>
                                                    <td>
                                                        @if ($list[$i]->certificate_path)
                                                            <img class="uploaded_img"
                                                                src="{{ asset($list[$i]->certificate_path) }}"
                                                                alt="image">
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($list[$i]->status == '2')
                                                            <p>{{ $list[$i]->rejected_reason }}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @switch($list[$i]->status)
                                                            @case(0)
                                                                <div class="p-2 Pending">Pending</div>
                                                            @break

                                                            @case(1)
                                                                <div class="p-2 Pending">Approved By class Incharge</div>
                                                            @break

                                                            @case(3)
                                                                <div class="p-2 Approved">Approved By Hod</div>
                                                            @break

                                                            @case(2)
                                                                <div class="btn mt-2 btn-danger">Rejected</div>
                                                            @break

                                                            @default
                                                        @endswitch



                                                    </td>
                                                    <td>
                                                        @if ($list[$i]->status == '0')
                                                            <form method="POST"
                                                                action="{{ route('admin.student-request-leaves.Edit', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $list[$i]->id]) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <button type="submit" id="updater" name="updater"
                                                                    value="updater"
                                                                    class="btn btn-xs btn-info">Edit</button>
                                                            </form>

                                                            <form
                                                                action="{{ route('admin.student-request-leaves.delete', ['id' => $list[$i]->id]) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                                style="display: inline-block;">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token"
                                                                    value="{{ csrf_token() }}">
                                                                <input type="submit" class="btn btn-xs btn-danger mt-2"
                                                                    value="{{ trans('global.delete') }}">
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        let fromdate = document.getElementById("from_date");
        let to_date = document.getElementById("to_date");

        function check_fromDate(element) {
            let from_date = new Date(fromdate.value);
            let selected_date = new Date(element.value);

            if (fromdate.value !== '') {
                if (selected_date.getTime() < from_date.getTime()) {
                    Swal.fire('', "It's Not A Valid Date", 'error');
                    element.value = '';
                } else {
                    let differenceInTime = selected_date.getTime() - from_date.getTime();
                    let differenceInDays = differenceInTime / (1000 * 3600 * 12);

                    if (differenceInDays > 2) {
                        Swal.fire('', "Only Two days Permission Is Allowed", 'error');
                        element.value = '';
                    }
                }
            } else {
                Swal.fire('', "Please Choose The From Date.", 'error');
            }
        }

        function checkData(element) {

            let leaveType = $("#leave_type").val();
            let fromDate = $("#from_date").val();
            let toDate = $("#to_date").val();
            let reason = $("#reason").val();


            if (leaveType == '') {
                Swal.fire('', 'Please Choose The Leave Type', 'error');
                return false;
            } else if (fromDate == '') {
                Swal.fire('', 'Please Choose The From Date', 'error');
                return false;
            } else if (toDate == '') {
                Swal.fire('', 'Please Choose The To Date', 'error');
                return false;
            } else if (reason == '') {
                Swal.fire('', 'Please Fill The Reason', 'error');
                return false;
            } else {
                return true;
            }
        }
    </script>
@endsection
