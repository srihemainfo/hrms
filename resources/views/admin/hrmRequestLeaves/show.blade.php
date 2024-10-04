@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">

            <h5 class="mb-2 text-primary">Leave Request</h5>

        </div>
        {{-- {{dd($hrmRequestLeaf->status)}} --}}
        {{-- @if ($hrmRequestLeaf->level == 0 && $hrmRequestLeaf->status == 'Pending')
            @if (count($staffs) > 0)
                <div class="card-header">
                    <div class="row text-center">
                        @foreach ($staffs as $staff)
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2 text-center">
                                <div
                                    class="manual_bn {{ $staff['status'] == 0 || $staff['status'] == 2 ? 'bg-danger' : 'bg-success' }}">
                                    @if ($staff['status'] == 0)
                                        {{ $staff['staff_name'] }} ({{ $staff['staff_code'] }}) Not Responded to the
                                        Alteration
                                        Request
                                    @elseif ($staff['status'] == 1)
                                        {{ $staff['staff_name'] }} ({{ $staff['staff_code'] }}) Accepted the Alteration
                                        Request
                                    @elseif ($staff['status'] == 2)
                                        {{ $staff['staff_name'] }} ({{ $staff['staff_code'] }}) Rejected the Alteration
                                        Request
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
        @endif --}}

        <div class="card-body">

            {{-- {{ dd($hrmRequestLeaf->user) }} --}}
            <div class="row gutters">
                <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="">Staff Name</label>
                        <input type="text" class="form-control"
                            value="{{ $hrmRequestLeaf->user->name }} ({{ $hrmRequestLeaf->user->employID }})"readonly>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-4 col-sm-4 col-10">
                    <div class="form-group">
                        <label for="leave_type">Leave Types</label>
                        @if ($hrmRequestLeaf->leave_type != '')
                            @foreach ($leave_types as $id => $entry)
                                @if ($hrmRequestLeaf->leave_type == $id)
                                    <input type="text" class="form-control" value="{{ $entry }}"readonly>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-center">
                    <div class="form-group">
                        <label for="leave_type" class="required">Half Day</label>
                        <div style="padding-top:5px;">
                            <input type="checkbox" name="half_day" id="half_day" value=""
                                style="width:18px;height:18px;accent-color:#007bff;"
                                {{ $hrmRequestLeaf->half_day_leave != null && $hrmRequestLeaf->half_day_leave != '' ? 'checked' : '' }}
                                disabled>
                        </div>
                    </div>
                </div>
                @if ($hrmRequestLeaf->leave_type == 5 && $hrmRequestLeaf->half_day_leave == null)
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="off_date">Off Date</label>
                            <input type="text" class="form-control date" name="off_date" id="off_date"
                                value="{{ $hrmRequestLeaf->off_date }}" readonly>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="alter_date">Alter Working Date</label>
                            <input type="text" class="form-control date" name="alter_date" id="alter_date"
                                value="{{ $hrmRequestLeaf->alter_date }}" readonly>
                        </div>
                    </div>
                @elseif ($hrmRequestLeaf->half_day_leave != null)
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="half_day_leave">Date</label>
                            <input type="text" class="form-control date" name="half_day_leave"
                                value="{{ $hrmRequestLeaf->half_day_leave }}" readonly>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="noon">FN / AN</label>
                            <input type="text" class="form-control" name="noon" value="{{ $hrmRequestLeaf->noon }}"
                                readonly>
                        </div>
                    </div>
                @else
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="text" class="form-control date" name="from_date"
                                value="{{ $hrmRequestLeaf->from_date }}" readonly>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="text" class="form-control date" name="to_date"
                                value="{{ $hrmRequestLeaf->to_date }}" readonly>
                        </div>
                    </div>
                @endif
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="subject">Reason</label>
                        <input type="text" class="form-control" name="subject" value="{{ $hrmRequestLeaf->subject }}"
                            readonly>
                    </div>
                </div>
                @if ($hrmRequestLeaf->certificate != null)
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="doc">Document</label>
                            <div>
                                <img style="cursor:pointer;" class="uploaded_img"
                                    src="{{ asset($hrmRequestLeaf->certificate) }}" alt="image" onclick="imgShower()">
                            </div>
                        </div>
                    </div>
                @endif
                @if ($hrmRequestLeaf->status == 'NeedClarification')
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="clarifcation">Need Clarification Reason</label>
                            <input type="text" class="form-control" name="clarifcation"
                                value="{{ $hrmRequestLeaf->clarification_reason }}" readonly>
                        </div>
                    </div>
                @elseif ($hrmRequestLeaf->status == 'Rejected')
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="clarifcation">Rejected Reason</label>
                            <input type="text" class="form-control" name="clarifcation"
                                value="{{ $hrmRequestLeaf->rejected_reason }}" readonly>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row gutters" style="align-items: center;">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                    <a class="btn btn-primary" href="{{ route('admin.hrm-request-leaves.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <div class="{{ $hrmRequestLeaf->status == 'NeedClarification' ? 'col-5' : 'col-5' }}"></div>
                @if (
                    (auth()->user()->roles[0]->id == 1 || auth()->user()->roles[0]->id == 2 || auth()->user()->roles[0]->id == 3) &&
                        $hrmRequestLeaf->level == 0 &&
                        $hrmRequestLeaf->status == 'Pending')
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                        <input type="hidden" name="id" id="id" value="{{ $hrmRequestLeaf->id }}">
                        <input type="hidden" name="leave_type" id="leave_type"
                            value="{{ $hrmRequestLeaf->leave_type }}">
                        <button type="submit" id="rejecter" name="updater" value="updater" class="btn btn-warning"
                            onclick="needClarification(this)">Need clarification</button>
                        <span id="clarification_span" class="text-success"
                            style="display:none;font-weight:bold;">Processing...</span>
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1">
                        <input type="hidden" name="id" id="id" value="{{ $hrmRequestLeaf->id }}">
                        <input type="hidden" name="leave_type" id="leave_type"
                            value="{{ $hrmRequestLeaf->leave_type }}">
                        <button type="submit" id="rejecter" name="updater" value="updater" class="btn btn-danger"
                            onclick="reject(this)">Reject</button>
                        <span id="reject_span" class="text-success"
                            style="display:none;font-weight:bold;">Processing...</span>

                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1" style="padding: 0;">
                        <input type="hidden" name="id" id="id" value="{{ $hrmRequestLeaf->id }}">
                        <input type="hidden" name="leave_type" id="leave_type"
                            value="{{ $hrmRequestLeaf->leave_type }}">
                        <button type="submit" id="updater" name="updater" value="updater" class="btn btn-success"
                            onclick="approve(this)">Approve</button>
                        <span id="approve_span" class="text-success"
                            style="display:none;font-weight:bold;">Processing...</span>
                    </div>
                    {{-- auth()->user()->roles[0]->id == 1 && $hrmRequestLeaf->status == 'Pending') || --}}
                @else
                    <div class="col-2"></div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="imgShower" role="dialog">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Document
                </div>
                <div class="modal-body" id="">
                    @if ($hrmRequestLeaf->certificate != null)
                        <img src="{{ asset($hrmRequestLeaf->certificate) }}" alt="image" width="100%;">
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
        let leave_type = document.getElementById('leave_type');

        function reject(element) {
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
                    $(element).hide();
                    $("#reject_span").show();
                    let data = {
                        'id': id.value,
                        'leave_type': leave_type.value,
                        'status': 'Rejected',
                        'rejected_reason': username
                    }
                    $.ajax({
                        url: '{{ route('admin.hrm-request-leaves.update_hr') }}',
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
                                    'You Rejected the Leave!',
                                    'success'
                                )
                                $(element).hide();
                                $("#reject_span").hide();
                                location.href = "{{ route('admin.hrm-request-leaves.index') }}";
                            } else {
                                alert(data.status)
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status) {
                                if (jqXHR.status == 500) {
                                    Swal.fire('', 'Request Timeout / Internal Server Error',
                                        'error');
                                } else {
                                    Swal.fire('', jqXHR.status, 'error');
                                }
                            } else if (textStatus) {
                                Swal.fire('', textStatus, 'error');
                            } else {
                                Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                    "error");
                            }
                            $(element).show();
                            $("#reject_span").hide();
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            })

        }

        function needClarification(element) {
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
                    $(element).hide();
                    $("#clarification_span").show();
                    let data = {
                        'id': id.value,
                        'leave_type': leave_type.value,
                        'status': 'NeedClarification',
                        'clarification_reason': username
                    }
                    $.ajax({
                        url: '{{ route('admin.hrm-request-leaves.update_hr') }}',
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
                                $(element).hide();
                                $("#clarification_span").hide();
                                location.href = "{{ route('admin.hrm-request-leaves.index') }}";
                            } else {
                                alert(data.status)
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status) {
                                if (jqXHR.status == 500) {
                                    Swal.fire('', 'Request Timeout / Internal Server Error',
                                        'error');
                                } else {
                                    Swal.fire('', jqXHR.status, 'error');
                                }
                            } else if (textStatus) {
                                Swal.fire('', textStatus, 'error');
                            } else {
                                Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                    "error");
                            }
                            $(element).show();
                            $("#clarification_span").hide();
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            })

        }

        function approve(element) {


            let data = {
                'id': id.value,
                'leave_type': leave_type.value,
                'status': 'Approved',
                'rejected_reason': null
            }
            $(element).hide();
            $("#approve_span").show();
            $.ajax({
                url: '{{ route('admin.hrm-request-leaves.update_hr') }}',
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
                        'You Approved the Leave!',
                        'success'
                    )
                    $(element).hide();
                    $("#approve_span").hide();
                    location.href = "{{ route('admin.hrm-request-leaves.index') }}";
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                            "error");
                    }
                    $(element).hide();
                    $("#approve_span").show();
                }
            });


        }

        function imgShower() {
            $("#imgShower").modal();
        }
    </script>
@endsection
