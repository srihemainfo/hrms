@extends('layouts.admin')
@section('content')
    {{-- {{ dd($permissionrequest) }} --}}
    <div class="card">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card-header">

                <h5 class="mb-2 text-primary">Permission Request</h5>
            </div>
        </div>

        <div class="card-body">


            <div class="row gutters">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="off_date"> Staff Name</label>
                        <input type="text" class="form-control" name="staff" id="staff"
                            value="{{ $permissionrequest->name }} ({{ $permissionrequest->staff_code }})" readonly>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="Permission_div">
                    <div class="form-group">
                        <label for="off_date"> Permission Type</label>
                        <input type="text" class="form-control" name="Permission" id="Permission"
                            value="{{ $permissionrequest->Permission }}" readonly>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="off_date_div">
                    <div class="form-group">
                        <label for="off_date"> Date</label>
                        <input type="date" class="form-control" name="date" id="date"
                            value="{{ $permissionrequest->date }}" readonly>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="from_date_div">
                    <div class="form-group">
                        <label for="from_date">From Time</label>
                        <input type="text" class="form-control" name="from_time" id="from_time"
                            value="{{ $permissionrequest->from_time }}" readonly>

                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="to_date_div">
                    <div class="form-group">
                        <label for="to_time">To Time</label>
                        <input type="text" class="form-control" name="to_time" id="to_time"
                            value="{{ $permissionrequest->to_time }}" readonly>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="alter_date_div">
                    <div class="form-group">
                        <label for="alter_date">Reason</label>
                        <input type="text" class="form-control" name="reason" id="reason"
                            value="{{ $permissionrequest->reason }}" readonly>
                        {{-- <textarea type="text" class="form-control" id="reason" name="reason" value="{{ $permissionrequest->reason }}"
                            readonly>{{ $permissionrequest->reason }}</textarea> --}}
                    </div>
                </div>
            </div>
            <div class="row gutters" style="align-items: center;">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">

                    <a class="btn btn-primary" href="{{ route('admin.hrm-request-permissions.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>

                </div>
                <div class="col-6"></div>

                @if (
                    (auth()->user()->roles[0]->id == 13 && $permissionrequest->status == 0)||(auth()->user()->roles[0]->id == 13 && $permissionrequest->status == 1)  ||
                        (auth()->user()->roles[0]->id == 14 && $permissionrequest->status == 0) || (auth()->user()->roles[0]->id == 42 && $permissionrequest->status == 0) || (auth()->user()->roles[0]->id == 1 && $permissionrequest->status == 0))

                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 ">
                        <input type="hidden" name="id" id="id" value="{{ $permissionrequest->id }}">
                        <button type="submit" id="rejecter" name="updater" value="updater" class="btn btn-primary"
                            onclick="needClarification()">Need Clarification</button>

                    </div>

                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1" style="padding: 0;">
                        <input type="hidden" name="id" id="id" value="{{ $permissionrequest->id }}">
                        <button type="submit" id="updater" name="updater" value="updater" class="btn btn-success"
                            onclick="approve()">Approve</button>
                    </div>
                @elseif (
                    (auth()->user()->roles[0]->id == 13 && $permissionrequest->status == 4 )||(auth()->user()->roles[0]->id == 42 && $permissionrequest->status == 4)  ||
                        (auth()->user()->roles[0]->id == 14 && $permissionrequest->status == 4) || (auth()->user()->roles[0]->id == 1 && $permissionrequest->status == 4))
                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1">
                        <input type="hidden" name="id" id="id" value="{{ $permissionrequest->id }}">
                        <button type="submit" id="rejecter" name="updater" value="updater" class="btn btn-danger"
                            onclick="reject()">Reject</button>
                    </div>

                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1" style="padding: 0;">
                        <input type="hidden" name="id" id="id" value="{{ $permissionrequest->id }}">
                        <button type="submit" id="updater" name="updater" value="updater" class="btn btn-success"
                            onclick="approve()">Approve</button>
                    </div>
                @else
                    <div class="col-2"></div>
                @endif





            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script>
        let rejected_reason = document.getElementById('rejected_reason');

        let id = document.getElementById('id');
        // let leave_type = document.getElementById('leave_type');

        function reject() {
            Swal.fire({
                title: 'Reject Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (username) => {

                    let data = {
                        'id': id.value,
                        'status': 3,
                        'rejected_reason': username
                    }
                    $.ajax({
                        url: '{{ route('admin.hrm-request-permissions.update_hr') }}',
                        type: 'POST',
                        data: {
                            'data': data,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire(
                                'Done!',
                                'You Rejected the Permission!',
                                'success'
                            )
                            location.reload();
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log(xhr.responseText);
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            })

        }

        function approve() {


            let data = {
                'id': id.value,
                'status': 1,
                'rejected_reason': null
            }
            $.ajax({
                url: '{{ route('admin.hrm-request-permissions.update_hr') }}',
                type: 'POST',
                data: {
                    'data': data,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    Swal.fire(
                        'Done!',
                        'You Approved the Permission!',
                        'success'
                    )
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                }
            });




        }

        function needClarification() {
            Swal.fire({
                title: 'Raise a Question',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (username) => {

                    let data = {
                        'id': id.value,
                        'status': 4,
                        'rejected_reason': username
                    }
                    $.ajax({
                        url: '{{ route('admin.hrm-request-permissions.update_hr') }}',
                        type: 'POST',
                        data: {
                            'data': data,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data.status == 'ok') {
                                Swal.fire(
                                    'Done!',
                                    'You Requested Clarification',
                                    'success'
                                )
                                // location.reload();
                                location.href = "{{ route('admin.hrm-request-permissions.index') }}";
                            } else {
                                alert(data.status)
                            }

                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log(xhr.responseText);
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            })

        }
    </script>
@endsection
