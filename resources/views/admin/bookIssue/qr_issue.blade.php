@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 5) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5 || ($type_id == 6 && $role_id == 9)) {
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
    @if ($book != null)
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <span>Book Information</span>
                    </div>
                    <div class="card-body">
                        {{-- <input type="hidden" name="book_issue_id" id="book_issue_id" value="{{ $book->id }}"> --}}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Book Name</strong>
                                <span>{{ $book->book_name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>ISBN</strong>
                                <span>{{ $book->isbn }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Book Code</strong>
                                <span id="book_id" data-book_id="{{ $book->book_id }}">{{ $book->book_name }}(
                                    {{ $book->book_code }})</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Book Status</strong>
                                @if ($book->availability == 'Yes')
                                    <span class="text-success fw-bolder" style="font-weight: 800">Availability</span>
                                @else
                                    <span class="text-danger fw-bolder">Issued</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Book Image</strong>
                                <span id="issued_date"><img src="{{ asset('uploads/' . $book->image) }}"
                                        alt="Image Not Available" width="100px" height="130px"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Issue Information</h5> <br>
                        <div class="secondLoader" style="z-index: 99"></div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="member">Member Name</label>
                            <select name="member" id="member" class="form-control select2">
                                <option value="">Select Member</option>
                                @foreach ($student as $k => $item)
                                    <option value="{{ $k }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="member_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="book_name">Book Name</label>
                            <input type="text" name="book_name" id="book_name" class="form-control"
                                value="{{ $book->book_name . '(' . $book->book_code . ')' }}"
                                data-book_data_id="{{ $book->book_data_id }}">
                            <span id="book_name_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="issue_date" class="required">Date Of Issue</label>
                            <input type="text" id="issue_date" name="issue_date" class="form-control date">
                            <span id="issued_date_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="end_date" class="required">Due Date</label>
                            <input type="text" id="end_date" name="end_date" class="form-control date">
                            <span id="end_date_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div id="save_div">
                            <button type="button" id="save_btn" class="btn btn-primary btn-block"
                                onclick="saveBook()">Submit</button>
                        </div>
                        <div id="loading_div" style="display: none;">
                            <span class="theLoader float-right"></span>
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
            $('#save_div').show()
            $('#loading_div').hide()
            $('.renew_group').hide()
        })

        $('#member').change(function() {
            // secondLoader
            if ($('#member').val() != '') {
                $('.card-body .secondLoader').show();

                $.ajax({
                    url: "{{ route('admin.book-issue.checkStudent') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#member').val()
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {

                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $('.card-body .secondLoader').hide();
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
            $("#loading_div").hide();
            if ($("#member").val() == '') {
                $("#member_span").html(`Member Is Required.`);
                $("#member_span").show();
                $("#issue_date_span").hide();
                $("#book_name_sapn").hide();
                $("#end_date_span").hide();

            } else if ($("#book_name").val() == '') {
                $("#book_name_span").html(`Book Name Is Required.`);
                $("#book_name_span").show();
                $("#member_span").hide();
                $("#issue_date_span").hide();
                $("#end_date_span").hide();

            } else if ($("#issue_date").val() == '') {
                $("#issue_date_span").html(`Date Is Required.`);
                $("#issue_date_span").show();
                $("#book_no_span").hide();
                $("#student_span").hide();
                $("#book_name_span").hide();
                $("#end_date_span").hide();

            } else if ($("#end_date").val() == '') {
                $("#end_date_span").html(`Date Is Required.`);
                $("#end_date_span").show();
                $("#issue_date_span").hide();
                $("#member_span").hide();
                $("#book_name_span").hide();
            } else {
                $("#save_div").hide();
                $("#student_span").hide();
                $("#book_no_span").hide();
                $("#loading_div").show();
                let id = ''
                let user = $('#member').val()
                let book_id = $('#book_id').data('book_id')
                let book_no = $('#book_name').data('book_data_id')
                let issue_date = $('#issue_date').val()
                let due_date = $('#end_date').val()

                $.ajax({
                    url: "{{ route('admin.book-issue.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'user': user,
                        'book_id': book_id,
                        'book_no': book_no,
                        'issue_date': issue_date,
                        'due_date': due_date
                    },
                    success: function(response) {
                        let status = response.status;
                        $('#save_div').show()
                        $('#loading_div').hide()
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        // $("#bookIssueModal").modal('hide');
                        window.location.href = "{{ url('admin/book-issue') }}";
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
