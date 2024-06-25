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
    {{-- {{ dd($permissionrequest) }} --}}
    <div class="card">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card-header">

                <h5 class="mb-2 text-primary">Student Leave Request</h5>
            </div>
        </div>

        <div class="card-body">

            {{-- <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.hrm-request-leaves.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div> --}}
            <div class="row gutters">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="Permission_div">
                    <div class="form-group">
                        <label for="off_date">Leave Type</label>
                        <input type="text" class="form-control" name="Permission" id="Permission"
                            value="{{ $data->leave_type }}" readonly>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="from_date_div">
                    <div class="form-group">
                        <label for="from_date">From Date</label>
                        <input type="text" class="form-control" name="from_date" id="from_time"
                            value="{{ $data->from_date }}" readonly>

                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="to_date_div">
                    <div class="form-group">
                        <label for="to_time">To Date</label>
                        <input type="text" class="form-control" name="to_date" id="to_time"
                            value="{{ $data->to_date }}" readonly>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12" id="off_date_div"
                    style="display: flex; justify-content: center; padding-top: 6px;">
                    <div class="form-group">
                        <h4 style="font-size: 18px;"><strong>Certificate</strong></h4>
                        @if (isset($data->certificate_path) && !empty($data->certificate_path))
                            <img class="uploaded_img" src="{{ asset($data->certificate_path) }}" alt="image">
                        @endif


                    </div>
                </div>




                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12" id="alter_date_div">
                    <div class="form-group">
                        <label for="alter_date">Reason</label>
                        <textarea type="text" class="form-control" id="reason" name="reason" value="{{ $data->reason }}" readonly>{{ $data->reason }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row gutters" style="align-items: center;">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">

                    <a class="btn btn-primary" href="{{ route('admin.student-leave-requests.stu_index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>

                </div>
                <div class="col-7"></div>


                @php

                    $type_id = auth()->user()->roles[0]->type_id;
                   
                @endphp
                @if ($type_id != 1 && $type_id != 3)
                 {{-- {{ dd($data) }} --}}

                    {{-- {{ dd($data) }} --}}

                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1">
                        @if ($data->status == 1 && !empty($data->approved_by))
                            <form id="rejectForm_{{ $data->id }}" method="POST"
                                action="{{ route('admin.student-request-leaves.store', ['id' => $data->id, 'status' => 'Rejected-HOD']) }}"
                                enctype="multipart/form-data">
                                @csrf

                                <button type="button" id="rejectButton_{{ $data->id }}" class="btn btn-danger"
                                    onclick="reject('{{ $data->id }}')">Reject</button>
                            </form>
                        @elseif ($data->status == 3)
                            <span class="btn badge-success">Approved</span>
                        @elseif ($data->status == 2)
                            <span class="btn badge-danger">Rejected</span>
                            @elseif ($data->status == 0)
                            <span class="btn badge-danger">Not verified by staff</span>
                        @endif
                        {{-- <span class="btn badge-danger">Not verified by staff</span> --}}

                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1">
                        @if ($data->status == 1 && !empty($data->approved_by))
                            <form method="POST"
                                action="{{ route('admin.student-request-leaves.store', ['id' => $data->id, 'status' => 'Approved-HOD']) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <button type="submit" id="updater" name="updater" value="updater"
                                    class="btn btn-success">Approve</button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1" style="margin-left: -51px;">
                        @if ($data->status == 0 && empty($data->approved_by))
                            <form id="rejectForm_{{ $data->id }}" method="POST"
                                action="{{ route('admin.student-request-leaves.store', ['id' => $data->id, 'status' => 'Rejected']) }}"
                                enctype="multipart/form-data">
                                @csrf

                                <button type="button" id="rejectButton_{{ $data->id }}" class="btn btn-danger"
                                    onclick="reject('{{ $data->id }}')">Reject</button>
                            </form>
                        @elseif ($data->status == 1)
                            <span class="btn badge-success">Forwarded</span>
                        @elseif ($data->status == 2)
                            <span class="btn badge-danger">Rejected</span>
                        @endif
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1">
                        @if ($data->status == 0 && empty($data->approved_by))
                            <form method="POST"
                                action="{{ route('admin.student-request-leaves.store', ['id' => $data->id, 'status' => 'Approved']) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <button type="submit" id="updater" name="updater" value="updater"
                                    class="btn btn-success">Forward to HOD</button>
                            </form>
                        @endif
                    </div>
                @endif


            </div>
        </div>
    </div>
    </div>
@endsection
@if (session('success'))
    @section('scripts')
        @parent
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
            }).then(function() {
                window.location.href = "{{ route('admin.student-leave-requests.stu_index') }}";
            });
        </script>
    @endsection
@endif
@section('scripts')
    @parent
    <script>
        function reject(formId) {
            // alert('sxs');
            var form = $('#rejectForm_' + formId);

            Swal.fire({
                title: 'Enter Rejection Reason',
                input: 'text',
                inputPlaceholder: 'Rejection Reason',
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
                preConfirm: function(reason) {
                    if (reason) {
                        form.append('<input type="hidden" name="rejected_reason" value="' + reason + '">');
                        form.submit();
                    } else {
                        Swal.showValidationMessage('Please enter a rejection reason');
                    }
                }
            });
        }
    </script>
@endsection
