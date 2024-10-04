@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 5) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        .list-group-item {
            border-color: rgba(104, 101, 101, 0.125);
            /* Set the border color to a light shade */
        }

        #card {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Adjust as needed */
        }
    </style>
    @if ($data != null)
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <span>Member Information</span>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="book_issue_id" id="book_issue_id" value="{{ $data->id }}">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Member Name</strong>
                                <span>{{ $data->user_name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Member's Role</strong>
                                <span>{{ $data->title }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Book Name</strong>
                                <span>{{ $data->book_name }}( {{ $data->book_code }})</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Book ISBN Code</strong>
                                <span>{{ $data->isbn }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Book Status</strong>
                                @if ($data->status == 'Issued')
                                    <span class="text-success fw-bolder" style="font-weight: 800">{{ $data->status }}</span>
                                @else
                                    <span class="text-danger fw-bolder">{{ $data->status }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Issued Date</strong>
                                <span id="issued_date">{{ $data->issued_date }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Due Date</strong>
                                <span id="due_date">{{ $data->due_date }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Return Date</strong>
                                <span>{{ $data->return_date }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Fine Amount</strong>
                                <span>{{ $data->fine != '' ? $data->fine : '0' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Renewed Count</strong>
                                <span>{{ $data->renew_count != '' ? $data->renew_count : '0' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Return / Renew</h5>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="renew" class="required">Action</label>
                            <select name="renew" id="renew" class="form-control select2">
                                <option value="">Select Option</option>
                                <option value="Return">Return</option>
                                <option value="Renew">Renew</option>
                            </select>
                            <span id="renew_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="fine">Fine Amount</label>
                            <input type="text" name="fine" id="fine" class="form-control"
                                placeholder="Enter fine amount" value="">
                            <span id="fine_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group renew_group ">
                            <label for="issue_date" class="required">Date Of Issue</label>
                            <input type="text" id="issue_date" name="issue_date" class="form-control date">
                            <span id="issued_date_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group renew_group ">
                            <label for="end_date" class="required">Due Date</label>
                            <input type="text" id="end_date" name="end_date" class="form-control date" readonly>
                            <span id="end_date_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="remark">Remark</label>
                            <textarea name="remark" id="remark" cols="30" rows="10" class="form-control"></textarea>
                            <span id="remark_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div id="save_div">
                            <button type="button" id="save_btn" class="btn btn-primary btn-block"
                                onclick="saveBook()">Submit</button>
                        </div>
                        <div id="loading_div" style="display: none;">
                            <span class="theLoader"></span>
                        </div>
                        {{-- <button type="button" class="btn btn-primary btn-block">Submit</button> --}}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div id="card">
            <div class="card shadow col-12">
                <div class="card-body text-center">
                    <h2>This Book Not Issued...</h2>
                    <p>Please return the book to the library.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            $('#loading_div').hide()
            $('.renew_group').hide()
        })

        $('#renew').change(function() {
            if ($('#renew').val() == 'Return') {
                $('.renew_group').hide()

                let issue = $('#issued_date').text()
                let due = $('#due_date').text()
                const date = new Date();
                const formattedDate = date.toISOString().slice(0, 10);
                let issueDate = new Date(issue);
                let dueDate = new Date(due);
                let current = new Date(formattedDate);

                if (current <= dueDate) {
                    let fine = 0;
                    $('#fine').val(fine)
                } else {
                    let fine = 1;
                    let value = Math.abs(current - dueDate)
                    let differenceDays = value / (1000 * 60 * 60 * 24);

                    $('#fine').val(fine * differenceDays)
                }
            } else {
                let issue = $('#issued_date').text()
                let due = $('#due_date').text()
                const date = new Date();
                const formattedDate = date.toISOString().slice(0, 10);
                let issueDate = new Date(issue);
                let dueDate = new Date(due);
                let current = new Date(formattedDate);

                if (current <= dueDate) {
                    let fine = 0;
                    $('#fine').val(fine)
                } else {
                    let fine = 1;
                    let value = Math.abs(current - dueDate)
                    let differenceDays = value / (1000 * 60 * 60 * 24);

                    $('#fine').val(fine * differenceDays)
                }

                $('.renew_group').show()

            }
        })

        $('#issue_date').blur(function() {
            let issue = new Date($('#issue_date').val())
            let due = new Date(issue)
            due.setDate(due.getDate() + 15);
            var formattedDueDate = due.toISOString().split('T')[0];
            $('#end_date').val(formattedDueDate);
            console.log(issue);
            console.log(formattedDueDate);
        })

        function saveBook() {

            if ($('#renew').val() == '') {
                $("#renew_span").html(`Action Field Is Required.`);
                $("#renew_span").show();
                $("#fine_span").hide();
                $("#remark_span").show();

            } else if ($('#fine').val() == '') {
                $("#fine_span").html(`Fine Is Required.`);
                $("#fine_span").show();
                $("#renew_span").hide();
                $("#remark_span").show();

            } else {
                if ($('#renew').val() == 'Renew') {
                    if ($('#issue_date').val() == '') {
                        $("#issued_date_span").html(`Due date Is Required.`);
                        $("#issued_date_span").show();
                        $("#end_date_span").hide();
                        $("#renew_span").hide();
                        $("#fine_span").hide();
                        return false;
                    } else if ($('#end_date').val() == '') {
                        $("#end_date_span").html(`Due date Is Required.`);
                        $("#end_date_span").show();
                        $("#renew_span").hide();
                        $("#fine_span").hide();
                        $("#issued_date_span").hide();
                        return false;
                    }
                }
                $("#issued_date_span").hide();
                $("#end_date_span").hide();
                $("#renew_span").hide();
                $("#fine_span").hide();
                $("#remark_span").show();

                $.ajax({
                    url: "{{ route('admin.book-issue.updater') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#book_issue_id').val(),
                        'fine': $('#fine').val(),
                        'remark': $('#remark').val(),
                        'action': $('#renew').val(),
                        'issue_date': $('#issue_date').val(),
                        'due_date': $('#end_date').val()
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                            window.location.href = "{{ url('admin/book-issue') }}"
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        // $("#bookIssueModal").modal('hide');
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
                    }
                })

            }
        }
    </script>
@endsection
