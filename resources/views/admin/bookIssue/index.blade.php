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
    </style>
    @can('library_rack_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    New Book Issue
                </button>
            </div>
        </div>
    @endcan
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Book Issue
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-BookIssue text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Sno
                        </th>
                        <th>
                            Members Name
                        </th>
                        <th>
                            Role
                        </th>
                        <th>
                            Book Name
                        </th>
                        <th>
                            Issued Date
                        </th>
                        <th>
                            Due Date
                        </th>
                        <th>
                            Return Date
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div>
    <div class="modal fade" id="bookIssueModal" role="dialog" data-backdrop='static'>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="secondLoader" style="z-index: 99"></div>

                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                            <input type="hidden" name="book_id" id="book_id" value="">
                            <label for="student" class="required">Member Name</label>
                            <select name="student" id="student" class="form-control select2">
                                <option value="">Select Member</option>
                                @foreach ($student as $k => $item)
                                    <option value="{{ $k }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="student_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 form-group book_no">
                            <label for="book_name" class="required">Book Name</label>
                            <select class="form-control select2" id="book_name" name="book_name">
                                <option value="">Select Book</option>
                                @foreach ($book as $k => $item)
                                    <option value="{{ $k }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="book_name" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                            <label for="book_no" class="required">Book No</label>
                            <select class="form-control select2" id="book_no" name="book_no">
                                <option value="">Select Book No</option>
                            </select>
                            <span id="book_no_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group book_no ">
                            <label for="issue_date" class="required">Date Of Issue</label>
                            <input type="text" id="issue_date" name="issue_date" class="form-control date">
                            <span id="issue_date_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group book_no ">
                            <label for="due_date" class="required">End Of Due Date</label>
                            <input type="text" id="due_date" name="due_date" class="form-control date" readonly>
                            <span id="due_date_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group image_view text-center">
                            <label for="image_view">Book Image</label>
                            <img id="image_view" src="" alt="Image is Unavailable"
                                style="width: 100%; height: 100px; object-fit: contain;">
                            <span id="image_view_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveBook()">Save</button>
                    </div>
                    <div id="reservation">
                        <button type="button" id="reserve_btn" class="btn btn-outline-success"
                            onclick="reserveBook()">Reserve</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bookIssueModal2" role="dialog" data-backdrop='static'>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Book Information</h1>
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="book_issue_id" id="book_issue_id" value="">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Member Name</strong>
                            <span id="user_name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Member's Role</strong>
                            <span id="title"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Book Name</strong>
                            <span id="books_name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Book ISBN Code</strong>
                            <span id="isbn"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Book Status</strong>
                            <span class="fw-bolder" id="status"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Issued Date</strong>
                            <span id="issued_Date"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Due Date</strong>
                            <span id="due_Date"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Return Date</strong>
                            <span id="return_Date"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Fine Amount</strong>
                            <span id="fine"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Renewed Count</strong>
                            <span id="renew_count"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    {{-- <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveBook()">Save</button>
                    </div>
                    <div id="reservation">
                        <button type="button" id="reserve_btn" class="btn btn-outline-success"
                            onclick="reserveBook()">Reserve</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader"></span>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            $('.image_store').show()
            $('.image_view').hide()
            $('#availability').val('')
            // $('.reservation').hide()
            $('#reservation').hide()
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('library_rack_delete')
                let deleteButton = {
                    text: 'Delete Selected',
                    className: 'btn-outline-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            Swal.fire('', 'No Rows Selected', 'warning');

                            return
                        }

                        Swal.fire({
                            title: "Are You Sure?",
                            text: "Do You Really Want To Delete !",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            reverseButtons: true
                        }).then(function(result) {
                            if (result.value) {
                                $('.secondLoader').show()
                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: "{{ route('admin.book-issue.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        Swal.fire('', response.data, response.status);
                                        $('.secondLoader').hide()
                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-BookIssue')) {
                $('.datatable-BookIssue').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.book-issue.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'sno',
                        name: 'sno'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'book_name',
                        name: 'book_name'
                    },
                    {
                        data: 'issue_date',
                        name: 'issue_date'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'return_date',
                        name: 'return_date'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, full, meta) {
                            if (data == 'OverDue') {
                                return data ? '<span class="overDueLabel">' + data + '</span>' : '';
                            } else {
                                return data ? '<span class="roleLabel">' + data + '</span>' : '';
                            }
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data, type, row) {
                            if (row.status == 'Return') {
                                return data;
                            } else {
                                return data +
                                    '<button class="newEditBtn" onclick="returnFun(' + row.id +
                                    ')" title="Return Or Renew"><i class="fa-fw nav-icon fas fa-exchange-alt"></i></button>';

                            }
                        }
                    }

                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-BookIssue').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };


        $('#book_name').change(function() {
            if ($('#book_name').val() != '') {
                $('#book_no').html(`<option value="">Loading</option>`)
                $.ajax({
                    url: "{{ route('admin.book-issue.fetchBook') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#book_name').val(),
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            let book_image = response.book_image;
                            let books = response.books;
                            if (book_image != '') {
                                $('#image_view').attr('src', '/uploads/' + book_image.image);
                            }

                            if (books != '') {
                                let book_no = $('#book_no').empty()
                                book_no.prepend(`<option value="">Select Book No</option>`)
                                $.each(books, function(index, value) {
                                    book_no.append(`<option value="${index}">${value}</option>`)
                                })
                            }
                            $('.image_view').show()
                            $('#save_div').show()
                            $('#reservation').hide()
                        } else {
                            $('#book_no').html(`<option value="">No Books Available</option>`)
                            let book_image = response.book_image;
                            if (book_image != '') {
                                $('#image_view').attr('src', '/uploads/' + book_image
                                    .image);
                            }
                            $('.image_view').show()
                            $('#save_div').hide()
                            $('#reservation').show()
                            // $('.reservation').show()

                        }
                        // $("#bookIssueModal").modal('hide');
                        // callAjax();
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

        function openModal() {
            // $('#availability').val('')
            $('.image_view').hide()
            $("#book_id").val('')
            $("#student").val('').select2();
            $("#book_no").val('').select2()
            $("#book_name").val('').select2()
            $("#issue_date").val('')
            $("#due_date").val('')
            $("#image").val('')
            $("#student_span").hide();
            $("#book_no_span").hide();
            $("#issue_date_span").hide();
            $("#book_name").hide();
            $("#publication_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#bookIssueModal").modal();
        }

        function reserveBook() {

            if ($('#student').val() == '') {
                $("#student_span").html(`Student Is Required.`);
                $("#student_span").show();
                $("#book_no_span").hide();
            } else if ($('#book_name').val() == '') {
                $("#book_no_span").html(`Book Name Is Required.`);
                $("#book_no_span").show();
                $("#student_span").hide();
            } else {
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You Really Want To Reserve The Book !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $('#reservation').hide()
                        $("#loading_div").show();
                        $.ajax({
                            url: "{{ route('admin.book-issue.reservation') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'user': $('#student').val(),
                                'book_id': $('#book_name').val(),
                            },
                            success: function(response) {
                                let status = response.status;
                                if (status == true) {
                                    Swal.fire('', response.data, 'success');
                                } else {
                                    Swal.fire('', response.data, 'error');
                                }
                                $("#bookIssueModal").modal('hide');
                                callAjax();
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
            }

        }

        $('#student').change(function() {
            // secondLoader
            if ($('#student').val() != '') {
                $('.row.gutters .secondLoader').show();

                $.ajax({
                    url: "{{ route('admin.book-issue.checkStudent') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#student').val()
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {

                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $('.row.gutters .secondLoader').hide();
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

        function openModal2() {
            // $('#availability').val('')
            // $('.image_view').hide()
            // $("#book_id").val('')
            // $("#student").val('').select2();
            // $("#book_no").val('').select2()
            // $("#book_name").val('').select2()
            // $("#issue_date").val('')
            // $("#due_date").val('')
            // $("#image").val('')
            // $("#student_span").hide();
            // $("#book_no_span").hide();
            // $("#issue_date_span").hide();
            // $("#book_name").hide();
            // $("#publication_span").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#loading_div").hide();
            $("#bookIssueModal2").modal();
        }

        $('#issue_date').blur(function() {
            let issue = new Date($('#issue_date').val())
            let due = new Date(issue)
            due.setDate(due.getDate() + 15);
            var formattedDueDate = due.toISOString().split('T')[0];
            $('#due_date').val(formattedDueDate);
        })

        function saveBook() {
            $("#loading_div").hide();
            if ($("#student").val() == '') {
                $("#student_span").html(`Student Is Required.`);
                $("#student_span").show();
                $("#book_no_span").hide();
                $("#issue_date_span").hide();
                $("#book_name").hide();
                $("#publication_span").hide();

            } else if ($("#book_name").val() == '') {
                $("#book_name").html(`Book Name Is Required.`);
                $("#book_name").show();
                $("#book_no_span").hide();
                $("#student_span").hide();
                $("#issue_date_span").hide();
                $("#publication_span").hide();

            } else if ($("#book_no").val() == '') {
                $("#book_no_span").html(`Book Number Is Required.`);
                $("#book_no_span").show();
                $("#student_span").hide();
                $("#issue_date_span").hide();
                $("#book_name").hide();
                $("#publication_span").hide();

            } else if ($("#issue_date").val() == '') {
                $("#issue_date_span").html(`Date Is Required.`);
                $("#issue_date_span").show();
                $("#book_no_span").hide();
                $("#student_span").hide();
                $("#book_name").hide();
                $("#publication_span").hide();

            } else {
                $("#save_div").hide();
                $("#student_span").hide();
                $("#book_no_span").hide();
                $("#loading_div").show();
                let id = $("#book_id").val()
                let user = $('#student').val()
                let book_id = $('#book_name').val()
                let book_no = $('#book_no').val()
                let issue_date = $('#issue_date').val()
                let due_date = $('#due_date').val()

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
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#bookIssueModal").modal('hide');
                        callAjax();
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

        function viewBookIssue(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.book-issue.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            console.log(data);
                            $('#user_name').text(data.user_name)
                            $('#title').text(data.title)
                            $('#books_name').text(data.book_name)
                            $('#isbn').text(data.isbn)
                            if (data.status == 'On Loan') {
                                $('#status').text(data.status).css('color', 'red');
                            } else {
                                $('#status').text(data.status).css('color', 'green');
                            }
                            $('#issued_Date').text(data.issued_date)
                            $('#due_Date').text(data.due_date)
                            $('#return_Date').text(data.return_date != '' ? data.return_date : 0)
                            $('#fine').text(data.fine != '' ? data.fine : 0)
                            $('#renew_count').text(data.renew_count != '' ? data.renew_count : 0)
                            $("#save_div").hide()
                            $("#student_span").hide()
                            $("#book_no_span").hide()
                            $("#loading_div").hide()
                            // $("#bookIssueModal").modal()
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $('#bookIssueModal2').modal()
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

        // function editBookIssue(id) {
        //     let array = [id]
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         $('.secondLoader').show()
        //         $.ajax({
        //             url: "{{ route('admin.book-issue.edit') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'id': array
        //             },
        //             success: function(response) {
        //                 $('.secondLoader').hide()
        //                 let status = response.status;
        //                 if (status == true) {
        //                     var data = response.data;
        //                     $("#book_id").val(data.id);
        //                     $("#student").val(data.name);
        //                     $("#book_no").val(data.isbn)
        //                     $.each(data.got_genre, function(index, value) {
        //                         $("#issue_date option[value='" + index + "']").prop("selected",
        //                             true);
        //                     })
        //                     $("#book_name").val(data.book_name)
        //                     $("#publication").val(data.publication)
        //                     $('#image_view').attr('src', '/uploads/' + data.image);
        //                     $('#availability').val(data.availability);
        //                     $('.image_store').show()
        //                     $('.image_view').show()
        //                     $("#save_btn").html(`Update`);
        //                     $("#save_div").show();
        //                     $("#student_span").hide();
        //                     $("#book_no_span").hide();
        //                     $("#loading_div").hide();
        //                     $("#bookIssueModal").modal();
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //             }
        //         })
        //     }
        // }

        function deleteBookIssue(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You Really Want To Delete !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('admin.book-issue.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                Swal.fire('', response.data, response.status);
                                $('.secondLoader').hide()
                                callAjax();
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
            }
        }

        function returnFun(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                // $('.secondLoader').show()
                window.location.href = "{{ url('admin/book-issue/get_record') }}/" + id;
            }
        }
    </script>
@endsection
