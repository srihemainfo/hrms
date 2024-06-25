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
    @can('book_allote_access')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Allote Books
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
            Book Allocation List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Book text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Rack No
                        </th>
                        <th>
                            Row No
                        </th>
                        <th>
                            Book Count
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
    <div class="modal fade" id="bookAllotModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="rack" class="required">Rack</label>
                            <input type="hidden" name="book_id" id="book_id" value="">
                            <select name="rack" id="rack" class="form-control select2" onchange="rack()">
                                <option value="">Select Rack</option>
                                @foreach ($rack as $item)
                                    <option value="{{ $item['rack_no'] }}">{{ $item['rack_no'] }}</option>
                                @endforeach
                            </select>
                            <span id="rack_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group rows">
                            <label for="rows" class="required">Row</label>
                            <select name="rows" id="rows" class="form-control select2" onchange="rowChange()">
                                <option value="">Select Row</option>
                            </select>
                            <span id="rows_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group rows">
                            <label for="genre" class="required">Genre</label>
                            <select id="genre" name="genre" class="form-control select2" onchange="genre()">
                                <option value="">Select Genre</option>
                                @foreach ($genre as $k => $item)
                                    <option value="{{ $k }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="genre_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group rows text-center">
                            <label for="count_book">Books Count In Row</label>
                            <p id="count_book" class="text-primary" style="font-size: large; font-weight: bold;">0</p>
                            <span id="genre_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group rows book-input">
                            <label for="book" class="required">Books</label>
                            <select id="book" name="book[]" class="form-control select2" multiple>
                                <option value="">Select Book</option>
                            </select>
                            <span id="book_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group rows t-table">
                            <label for="book" class="required">Books</label>
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Book Name</th>
                                        <th>Book Code</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveBookAllote()">Save</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            $('.book-input').show()
            $('.t-table').hide()
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('book_allote_delete')
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
                                        url: "{{ route('admin.book-allocate.massDestroy') }}",
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
            if ($.fn.DataTable.isDataTable('.datatable-Book')) {
                $('.datatable-Book').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.book-allocate.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'sno',
                        name: 'sno'
                    },
                    {
                        data: 'rack',
                        name: 'rack'
                    },
                    {
                        data: 'row',
                        name: 'row'
                    },
                    {
                        data: 'count',
                        name: 'count'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-Book').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#rack").prop('disabled', false)
            $("#rows").prop('disabled', false)
            $('.book-input').show()
            $('.t-table').hide()
            $("#book_id").val('')
            $("#rack").val('').select2()
            $("#rows").val('').select2()
            $("#genre").val('').select2()
            $("#book").empty()
            $("#book").val('').select2()
            $("#rack_span").hide();
            $("#book_code_span").hide();
            $("#genre_span").hide();
            $("#book_span").hide();
            $("#publication_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#bookAllotModal").modal();
        }

        function rack() {
            if ($('#rack').val() != '') {
                rackTrigger()
            }
        }

        function rackTrigger() {
            if ($('#rack').val() != '') {
                return new Promise((resolve, reject) => {
                    $("#rows").html(`<option value="">Loading</option>`)
                    $.ajax({
                        url: "{{ route('admin.book-allocate.fetchRow') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'rack': $('#rack').val(),
                        }
                    }).done(function(response) {
                        let status = response.status;
                        $(".secondLoader").hide();
                        if (status == true) {
                            let data = response.data;
                            let row = $("#rows").empty()
                            row.prepend(`<option value="">Select Row</option>`)
                            $.each(data, function(index, value) {
                                row.append(`<option value="${index}">${value}</option>`)
                            })
                            resolve();
                        } else {
                            Swal.fire('', response.data, 'error');
                            reject(new Error(
                                'Error in callDesignation'));
                        }
                    }).fail(function(xhr, status, error) {
                        reject(new Error(error));
                    });
                });
            }
        }

        function genre() {
            if ($('#genre').val() != '') {
                genreTrigger()
            }
        }

        function genreTrigger() {
            return new Promise((resolve, reject) => {
                $("#book").empty()
                $("#book").prepend(`<option value="" selected>Loading</option>`)
                $("#book").select2()
                $.ajax({
                    url: "{{ route('admin.book-allocate.fetchBook') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'genre': $('#genre').val(),
                    }
                }).done(function(response) {
                    let status = response.status;
                    $(".secondLoader").hide();
                    if (status == true) {
                        let data = response.data;
                        let book = $("#book").empty()
                        $.each(data, function(index, value) {
                            book.append(
                                `<option value="${value.id},${value.book_code}">${value.name} (${value.book_code})</option>`
                            )
                        })
                        resolve();
                    } else {
                        Swal.fire('', response.data, 'error');
                        reject(new Error('Error in callDesignation'));
                    }
                }).fail(function(xhr, status, error) {
                    reject(new Error(error));
                });
            });
        }

        function rowChange() {
            if ($('#rows').val() != '') {
                $.ajax({
                    url: "{{ route('admin.book-allocate.fetchCount') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'rows': $('#rows').val(),
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            let data = response.data;
                            let count_book = $("#count_book").empty()
                            count_book.text(data)
                        } else {
                            Swal.fire('', response.data, 'error');
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
                    }
                })
            }
        }

        // $('#book').change(function() {

        //     if ($('#book_id').val() != '') {
        //         let len = $('#book').val().length
        //         let value = $('#book').val()
        //         if (len > 1) {
        //             var book = $('#book').val()
        //             var remove = book.pop()
        //             $('#book').val(book)
        //         }
        //     }
        // })

        function saveBookAllote() {
            $("#loading_div").hide();
            if ($("#rack").val() == '') {
                $("#rack_span").html(`Rack Is Required.`);
                $("#rack_span").show();
                $("#rows_span").hide();
                $("#genre_span").hide();
                $("#book_span").hide();
                // $("#publication_span").hide();

            } else if ($("#rows").val() == '') {
                $("#rows_span").html(`Row Count Is Required.`);
                $("#rows_span").show();
                $("#rack_span").hide();
                $("#genre_span").hide();
                $("#book_span").hide();
                // $("#publication_span").hide();

            } else if ($("#genre").val() == '') {
                $("#genre_span").html(`Row Count Is Required.`);
                $("#genre_span").show();
                $("#rows_span").hide();
                $("#rack_span").hide();
                $("#book_span").hide();
                // $("#publication_span").hide();

            } else if ($("#book").val() == '') {
                $("#book_span").html(`Row Count Is Required.`);
                $("#book_span").show();
                $("#rows_span").hide();
                $("#rack_span").hide();
                $("#genre_span").hide();
                // $("#publication_span").hide();

            } else {
                $("#save_div").hide();
                $("#rack_span").hide();
                $("#book_code_span").hide();
                $("#loading_div").show();
                let id = $("#book_id").val();
                let rows = $("#rows").val();
                let genre = $("#genre").val();
                let book = $("#book").val();
                // let book = $("#book").val();
                // let publication = $("#publication").val()
                $.ajax({
                    url: "{{ route('admin.book-allocate.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'rows': rows,
                        'genre': genre,
                        'book': book,
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#bookAllotModal").modal('hide');
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

        function viewBookAllote(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.book-allocate.view') }}",
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
                            var allote = response.data;
                            $("#rack").prop('disabled', true)
                            $("#rows").prop('disabled', true)
                            $("#book_id").val(allote[0].row_id);
                            $("#rack").val(allote[0].rack_no).select2();
                            //After the execution of rackTrigger function the inside code will execute
                            rackTrigger().then(() => {
                                $("#rows").val(allote[0].row_id);
                                $("#rows").select2();
                                rowChange()
                            });
                            $("#genre").val(allote[0].genre_id)
                            $("#genre").select2();
                            //After the execution of genreTrigger function the inside code will execute
                            $('#tbody').empty()
                            $('#tbody').html(`<tr><td colspan="3">Loading...</td></tr>`)
                            $('.book-input').hide()
                            $('.t-table').show()
                            genreTrigger().then(() => {
                                let tbody = $('#tbody').empty()
                                $.each(allote, function(index, d) {
                                    // $("#book option[value='" + d.book_data_id + ',' + d
                                    //     .book_code + "']").prop(
                                    //     "selected",
                                    //     true);
                                    let row = $('<tr>')
                                    row.append(`<td>${index+=1}</td>`)
                                    row.append(`<td>${d.name}</td>`)
                                    row.append(`<td>${d.book_code}</td>`)
                                    tbody.append(row)
                                    $("#book").select2()
                                })
                            });
                            // // console.log();
                            // // $('#book').val(allote[0].book_data_id)
                            $("#save_div").hide();
                            $("#rack_span").hide();
                            $("#book_code_span").hide();
                            $("#loading_div").hide();
                            $("#bookAllotModal").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
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
                    }
                })

            }
        }

        function editBookAllote(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.book-allocate.edit') }}",
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
                            var allote = response.data;
                            $("#book_id").val(allote[0].row_id);
                            $("#rack").val(allote[0].rack_no).select2();
                            $("#rack").prop('disabled', true)
                            $("#rows").prop('disabled', true)
                            //After the execution of rackTrigger function the inside code will execute
                            rackTrigger().then(() => {
                                $("#rows").val(allote[0].row_id);
                                $("#rows").select2();
                                rowChange()
                            });
                            $("#genre").val(allote[0].genre_id)
                            $("#genre").select2();
                            //After the execution of genreTrigger function the inside code will execute
                            $('.book-input').show()
                            $('.t-table').hide()
                            genreTrigger().then(() => {
                                $.each(allote, function(index, d) {
                                    $("#book option[value='" + d.book_data_id + ',' + d
                                        .book_code + "']").prop(
                                        "selected",
                                        true);
                                    $("#book").select2()
                                })
                            });
                            $("#save_div").show();
                            $("#rack_span").hide();
                            $("#book_code_span").hide();
                            $("#loading_div").hide();
                            $("#bookAllotModal").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
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
                    }
                })
            }
        }

        function deleteBookAllote(id) {
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
                            url: "{{ route('admin.book-allocate.delete') }}",
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
    </script>
@endsection
