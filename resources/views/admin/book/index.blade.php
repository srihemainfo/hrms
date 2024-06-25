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
    @can('library_book_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Book Details
                </button>
                <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'BookModel',
                    'route' => 'admin.book-models.parseCsvImport',
                ])
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
            Books List
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
                            Book Name
                        </th>
                        <th>
                            ISBN
                        </th>
                        <th>
                            Genre
                        </th>
                        <th>
                            Author
                        </th>
                        <th>
                            Publication
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
    <div class="modal fade" id="bookModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="book" class="required">Book Name</label>
                            <input type="hidden" name="book_id" id="book_id" value="">
                            <input type="text" name="book" id="book" class="form-control"
                                style="text-transform: uppercase;">
                            <span id="book_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group book_code">
                            <label for="book_code" class="required">ISBN</label>
                            <input type="text" class="form-control" id="book_code" name="book_code" value=""
                                style="text-transform: uppercase;">
                            <span id="book_code_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group book_code">
                            <label for="genre" class="required">Genre</label>
                            <select id="genre" name="genre[]" class="form-control select2" multiple>
                                @foreach ($genre as $k => $item)
                                    <option value="{{ $k }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="genre_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group book_code">
                            <label for="author" class="required">Author</label>
                            <input type="text" class="form-control" id="author" name="author" value=""
                                style="text-transform: uppercase;">
                            <span id="author_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group book_code">
                            <label for="book_count" class="required">Book Count</label>
                            <input type="number" id="book_count" name="book_count" class="form-control">
                            <span id="book_count_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group book_code">
                            <label for="publication" class="required">Publication</label>
                            <input type="text" class="form-control" id="publication" name="publication" value=""
                                style="text-transform: uppercase;">
                            <span id="publication_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group image_store">
                            <label for="image">Book Image</label>
                            <input type="file" class="form-control-file" id="image" name="image"
                                value="">
                            <span id="image_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group image_view">
                            <label for="image_view">Book Image</label>
                            {{-- <input type="file" class="form-control-file" id="image" name="image" value=""> --}}
                            <img id="image_view" src="" alt="Image is Unavailable"
                                style="width: 100%; height: 100px; object-fit: contain">
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
            $('.image_store').show()
            $('.image_view').hide()
            $('#book_count').val('')
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('library_book_delete')
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
                                        url: "{{ route('admin.book.massDestroy') }}",
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
                ajax: "{{ route('admin.book.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'book_code',
                        name: 'book_code'
                    },
                    {
                        data: 'genre',
                        name: 'genre'
                    },
                    {
                        data: 'author',
                        name: 'author'
                    },
                    {
                        data: 'publication',
                        name: 'publication'
                    },
                    {
                        data: 'book_count',
                        name: 'book_count'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data, type, row) {
                            let link = "{{ url('admin/book/downloadQr') }}/" + row.id;
                            return data +=
                                `<a class="newEditBtn" title="Download QRCode" href="${link}" target="_blank"><i class="fa-fw nav-icon fas fa-download"></i></a>`;
                        }
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
            $('#book_count').val('')
            $('.image_store').show()
            $('.image_view').hide()
            $("#book_id").val('')
            $("#book").val('');
            $("#book_code").val('')
            $("#book_code").prop('disabled', false)
            $("#genre").val('').select2()
            $("#author").val('')
            $("#publication").val('')
            $("#image").val('')
            $("#book_span").hide();
            $("#book_code_span").hide();
            $("#genre_span").hide();
            $("#author_span").hide();
            $("#publication_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#bookModal").modal();
        }

        function saveBook() {
            $("#loading_div").hide();
            if ($("#book").val() == '') {
                $("#book_span").html(`Rack Is Required.`);
                $("#book_span").show();
                $("#book_code_span").hide();
                $("#genre_span").hide();
                $("#author_span").hide();
                $("#publication_span").hide();

            } else if ($("#book_code").val() == '') {
                $("#book_code_span").html(`Row Count Is Required.`);
                $("#book_code_span").show();
                $("#book_span").hide();
                $("#genre_span").hide();
                $("#author_span").hide();
                $("#publication_span").hide();

            } else if ($("#genre").val() == '') {
                $("#genre_span").html(`Row Count Is Required.`);
                $("#genre_span").show();
                $("#book_code_span").hide();
                $("#book_span").hide();
                $("#author_span").hide();
                $("#publication_span").hide();

            } else if ($("#author").val() == '') {
                $("#author_span").html(`Row Count Is Required.`);
                $("#author_span").show();
                $("#book_code_span").hide();
                $("#book_span").hide();
                $("#genre_span").hide();
                $("#publication_span").hide();

            } else if ($("#publication").val() == '') {
                $("#publication_span").html(`Row Count Is Required.`);
                $("#publication_span").show();
                $("#book_code_span").hide();
                $("#book_span").hide();
                $("#genre_span").hide();
                $("#author_span").hide();

            } else {
                $("#save_div").hide();
                $("#book_span").hide();
                $("#book_code_span").hide();
                $("#loading_div").show();
                var formData = new FormData();
                var imageFile = $('#image')[0].files[0];
                console.log(imageFile);
                formData.append('image', imageFile);
                formData.append('id', $("#book_id").val());
                formData.append('book', $('#book').val());
                formData.append('book_code', $('#book_code').val());
                formData.append('genre', $('#genre').val());
                formData.append('author', $('#author').val());
                formData.append('book_count', $('#book_count').val());
                formData.append('publication', $('#publication').val());

                $.ajax({
                    url: "{{ route('admin.book.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#bookModal").modal('hide');
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

        function viewBook(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.book.view') }}",
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
                            $("#book_id").val(data.id);
                            $("#book").val(data.name);
                            $("#book_code").val(data.isbn)
                            $("#book_code").prop('disabled', true)
                            $.each(data.got_genre, function(index, value) {
                                $("#genre option[value='" + index + "']").prop("selected", true);
                            })
                            $("#genre").select2();
                            $("#author").val(data.author)
                            $("#publication").val(data.publication)
                            $('#image_view').attr('src', '/uploads/' + data.image);
                            $('#book_count').val(data.book_count);
                            $('.image_store').hide()
                            $('.image_view').show()
                            $("#save_div").hide();
                            $("#book_span").hide();
                            $("#book_code_span").hide();
                            $("#loading_div").hide();
                            $("#bookModal").modal();
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

        function editBook(id) {
            let array = [id]
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.book.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': array
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            $("#book_id").val(data.id);
                            $("#book").val(data.name);
                            $("#book_code").val(data.isbn)
                            $("#book_code").prop('disabled', true)
                            $.each(data.got_genre, function(index, value) {
                                $("#genre option[value='" + index + "']").prop("selected", true);
                            })
                            $("#genre").select2();
                            $("#author").val(data.author)
                            $("#publication").val(data.publication)
                            $('#image_view').attr('src', '/uploads/' + data.image);
                            $('#book_count').val(data.book_count);
                            $('.image_store').show()
                            $('.image_view').show()
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#book_span").hide();
                            $("#book_code_span").hide();
                            $("#loading_div").hide();
                            $("#bookModal").modal();
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

        function deleteBook(id) {
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
                            url: "{{ route('admin.book.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire('', response.data, 'success');
                                } else {
                                    Swal.fire('', response.data, 'error');
                                }
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
