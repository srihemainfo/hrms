@php
    if (auth()->user()->roles[0]->id == 27) {
        $layout = 'layouts.non_techStaffHome';
    } else {
        $layout = 'layouts.admin';
    }
@endphp
@extends($layout)
@section('content')
    <a class="btn btn-default mb-3" href="{{ route('admin.certificate-provision.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    @php
        if (isset($getData->date) && $getData->date) {
            $given_date = $getData->date;
            $dateTime = DateTime::createFromFormat('Y-m-d', $given_date);
            $formattedDate = $dateTime->format('d-m-Y');
        } else {
            $formattedDate = '';
        }
    @endphp
    <div class="card">
        <div class="card-header text-right">
            <a href="{{ url('admin/student-apply-certificate/show/' . $getData->id) }}" target="_blank"
                class="enroll_generate_bn bg-success" style="margin-top:1.9rem;">
                Preview Certificate</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <b for="">Student Name</b>
                        <p>{{ isset($getData->name) ? $getData->name : '' }}
                            {{ isset($getData->register_no) ? '(' . $getData->register_no . ')' : '' }}</p>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <b for="">Class</b>
                        <p>{{ isset($getData->course) ? $getData->course : '' }}</p>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <b for="">Date</b>
                        <p>{{ $formattedDate }}</p>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <b for="">Certificate</b>
                        <p>{{ isset($getData->certificate) ? $getData->certificate : '' }}</p>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <b for="">Purpose</b>
                        <p id="purpose_tag">{{ isset($getData->purpose) ? $getData->purpose : '' }}</p>
                    </div>
                </div>
                @if (isset($getData->action_reason) && $getData->action_reason != null)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <b for="">Revision / Rejection Reason</b>
                            <p>{{ $getData->action_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="text-right">
                @if ($getData->status == 0)
                    <button class="enroll_generate_bn bg-warning" style="margin-top:1.9rem;"
                        onclick="editContent(0,{{ $getData->id }})">
                        Edit
                    </button>
                    <button class="enroll_generate_bn bg-success" style="margin-top:1.9rem;"
                        onclick="takeAction(1,{{ $getData->id }})">
                        Verify
                    </button>
                @endif
                @if ($getData->status != 3 && $getData->status != 4 && $getData->status != 2)
                    <button class="enroll_generate_bn bg-info" style="margin-top:1.9rem;"
                        onclick="takeAction(3,{{ $getData->id }})">
                        Need Revision
                    </button>
                @endif
                @if ($getData->status != 4 && $getData->status != 2)
                    <button class="enroll_generate_bn bg-danger" style="margin-top:1.9rem;"
                        onclick="takeAction(4,{{ $getData->id }})">
                        Reject
                    </button>
                @endif
            </div>
        </div>
        <div class="modal fade" id="myModal" style="margin:auto;width:100%;" role="dialog" data-backdrop='static'>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Edit Purpose</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal_body">
                        <div class="form-group">
                            <label for="">Purpose</label>
                            <textarea name="purpose" class="form-control" id="purpose" cols="30" rows="1"></textarea>
                            <input type="hidden" name="application_id" id="application_id" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary Edit" id="save" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true" onclick="update()">Update</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script>
            function takeAction(status, id) {
                if (status == 3) {
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

                            $.ajax({
                                url: '{{ route('admin.certificate-provision.update-action') }}',
                                type: 'POST',
                                data: {
                                    'id': id,
                                    'status': status,
                                    'action_reason': username
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    if (response.status == true) {
                                        Swal.fire(
                                            'Done!',
                                            'Revision Request Sent !',
                                            'success'
                                        )
                                        location.reload();
                                    } else {
                                        Swal.fire(
                                            '',
                                            response.data,
                                            'error'
                                        )
                                    }

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
                                }
                            });

                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    })
                } else if (status == 4) {
                    Swal.fire({
                        title: 'Rejection Reason',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        showLoaderOnConfirm: true,
                        preConfirm: (username) => {

                            $.ajax({
                                url: '{{ route('admin.certificate-provision.update-action') }}',
                                type: 'POST',
                                data: {
                                    'id': id,
                                    'status': status,
                                    'action_reason': username
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    if (response.status == true) {
                                        Swal.fire(
                                            'Done!',
                                            'You Rejected The Application!',
                                            'success'
                                        )
                                        location.reload();
                                    } else {
                                        Swal.fire(
                                            '',
                                            response.data,
                                            'error'
                                        )
                                    }

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
                                }
                            });

                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    })
                } else if (status == 1) {

                    Swal.fire({
                        title: "Are You Sure?",
                        text: "Are Your Verified The Application ?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: '{{ route('admin.certificate-provision.update-action') }}',
                                type: 'POST',
                                data: {
                                    'id': id,
                                    'status': status,
                                    'action_reason': ''
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    if (response.status == true) {
                                        Swal.fire(
                                            'Done!',
                                            'You Verified The Application!',
                                            'success'
                                        )
                                        location.reload();
                                    } else {
                                        Swal.fire(
                                            '',
                                            response.data,
                                            'error'
                                        )
                                    }
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
                                }
                            });
                        } else if (result.dismiss == "cancel") {
                            Swal.fire(
                                "Cancelled",
                                "Application Verification Cancelled",
                                "error"
                            )
                        }
                    });
                }
            }

            function editContent(status, id) {
                if (status == 0) {
                    let purpose = $("#purpose_tag").html();
                    $("#purpose").val(purpose);
                    $("#application_id").val(id);
                    $("#myModal").modal();
                }
            }

            function update() {
                let purpose = $("#purpose").val();
                let id = $("#application_id").val();

                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You Want To Update The Purpose ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('admin.certificate-provision.update-purpose') }}',
                            type: 'POST',
                            data: {
                                'id': id,
                                'purpose': purpose,
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status == true) {
                                    Swal.fire(
                                        'Done!',
                                        'You Updated The Application\'s Purpose !',
                                        'success'
                                    )
                                    location.reload();
                                } else {
                                    Swal.fire(
                                        '',
                                        response.data,
                                        'error'
                                    )
                                }
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
                            }
                        });
                    } else if (result.dismiss == "cancel") {
                        Swal.fire(
                            "Cancelled",
                            "Purpose Updation Cancelled",
                            "error"
                        )
                    }
                });
            }
        </script>
    @endsection
