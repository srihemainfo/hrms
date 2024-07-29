@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .rating {
            display: flex;
        }

        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
            margin-left: 50px;
        }

        .rate:not(:checked)>input {
            position: absolute;
            top: -9999px;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ccc;
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #ffc700;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #c59b08;
        }
    </style>
    @can('nationality_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Create Schedule
                </button>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            FeedBack Schedule Lists
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FeedBack text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            S.No
                        </th>
                        <th>
                            FeedBack Name
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Expiry Date
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Created By
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="modal fade" id="scheduleFeedbackModel" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">FeedBack Name</label>
                                <input type="hidden" name="feedback_id" id="feedback_id" value="">
                                <select name="feedback" id="feedback" class="form-control select2">
                                    <option value="">Select Feedback Name</option>
                                    @foreach ($feedback as $id => $item)
                                        <option value="{{ $item->id }}" data-type="{{ $item->feedback_type }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span id="feedback_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group sub" style="display: none;">
                                <label for="result" class="required">Subject</label>
                                <select name="subject" id="subject" class="form-control select2" multiple>
                                    {{-- <option value="">Select Subject</option> --}}
                                    <option value="All">All</option>
                                    @foreach ($subject as $id => $item)
                                        <option value="{{ $item->subject_id }}">{{ $item->subjects->name }}</option>
                                    @endforeach
                                </select>
                                <span id="subject_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">Type</label>
                                <select name="type" id="type" class="form-control select2">
                                    <option value="">Select Type</option>
                                    <option value="Internal">Internal</option>
                                    <option value="External">External</option>
                                </select>
                                <span id="type_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="start_date" class="required">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                                <span id="start_date_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="expiry_date" class="required">Expire Date</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                    onblur="getDays(this)">
                                <span id="expiry_date_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result">No. of Days</label>
                                <input type="text" name="days" id="days" class="form-control" disabled>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">Status</label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Disabled">Disabled</option>
                                </select>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result">Degree</label>
                                <select name="degree" id="degree" class="form-control select2">
                                    <option value="">Select Degree</option>
                                    <option value="All">All</option>
                                    @foreach ($degree as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="course">Course</label>
                                <select name="course[]" id="course" class="form-control select2" multiple>
                                    <option value="">Select Course</option>
                                    <option value="All">All</option>
                                    @foreach ($course as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                                <label for="ay">Ay</label>
                                <select name="ay" id="ay" class="form-control select2">
                                    <option value="">Select Academic Year</option>
                                    @foreach ($ay as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                                <label for="sem">Semester</label>
                                <select name="sem" id="sem" class="form-control select2">
                                    <option value="">Select Semester</option>
                                    <option value="All">All</option>
                                    @foreach ($sem as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                                <label for="sec">Section</label>
                                <select name="sec" id="sec" class="form-control select2">
                                    <option value="">Select Section</option>
                                    <option value="All">All</option>
                                    @foreach ($sec as $id => $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="save_div">
                            <button type="button" id="save_btn" class="btn btn-outline-success"
                                onclick="saveFeedback()">Save</button>
                        </div>
                        <div id="loading_div">
                            <span class="theLoader"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="secondLoader"></div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            callAjax();

            function cpyLink(e) {
                var decode = atob($(e).data('link'));
                var tempInput = $('<input>').val(decode).appendTo('body').select();
                document.execCommand('copy');
                tempInput.remove();
                Swal.fire('', 'Link Copied...', 'success');
            }
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            if ($.fn.DataTable.isDataTable('.datatable-FeedBack')) {
                $('.datatable-FeedBack').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.schedule-feedback.index') }}",
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
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'expiry',
                        name: 'expiry'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'createdBy',
                        name: 'createdBy'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data, type, row) {
                            let link = row.token_link;
                            if (link != null) {
                                return data +=
                                    `<button class="newCopyBtn" data-link="${link}" onclick="cpyLink(this)" title="Copy Link"><i class="fa-fw nav-icon fas fa-copy"></i></button>`;
                            } else {
                                return data;
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
            let table = $('.datatable-FeedBack').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        $('#feedback').change(function() {
            let value = $('#feedback option:selected').data('type');
            if (value == 'Academic') {
                $('.sub').show()
            } else {
                $('.sub').hide()
            }
        })

        function cpyLink(e) {
            var decode = atob($(e).data('link'));
            var tempInput = $('<input>').val(decode).appendTo('body').select();
            document.execCommand('copy');
            tempInput.remove();
            Swal.fire('', 'Link Copied...', 'success');
        }

        function openModal() {
            $("#feedback_id").val('');
            $("#feedback").val('');
            $("#type").val('').select2();
            $("#subject").val('').select2();
            $("#start_date").val('');
            $("#expiry_date").val('');
            $("#status").val('').select2();
            $("#sem").val('').select2();
            $("#ay").val('').select2();
            $("#degree").val('').select2();
            $("#sec").val('').select2();
            $("#course").val('').select2();
            $('#days').val('')
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#scheduleFeedbackModel").modal();
        }



        function getDays(e) {

            let start_date = new Date($('#start_date').val());
            let expiry = new Date($('#expiry_date').val());
            let diffInTime = expiry.getTime() - start_date.getTime();
            let diffInDays = Math.ceil(diffInTime / (1000 * 3600 * 24));

            if (diffInDays) {
                $('#days').val(diffInDays + ' Days')
            }


        }


        function saveFeedback() {
            if ($('#feedback').val() == '') {
                $("#feedback_span").html(`Fees Components Is Required.`);
                $("#feedback_span").show();
            } else if ($('#start_date').val() == '') {
                $("#start_date_span").html(`Start Date Is Required.`);
                $("#start_date_span").show();
            } else if ($('#expiry_date').val() == '') {
                $("#expiry_date_span").html(`Expiry Date Is Required.`);
                $("#expiry_date_span").show();
            } else {
                $("#save_div").hide();
                $("#loading_div").show();
                $.ajax({
                    url: '{{ route('admin.schedule-feedback.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#feedback_id').val(),
                        'name': $('#feedback').val(),
                        'type': $('#type').val(),
                        'start': $('#start_date').val(),
                        'expiry': $('#expiry_date').val(),
                        'status': $('#status').val(),
                        'ay': $('#ay').val(),
                        'subject': $('#subject').val(),
                        'course': $('#course').val(),
                        'degree': $('#degree').val(),
                        'sem': $('#sem').val(),
                        'sec': $('#sec').val(),
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#scheduleFeedbackModel").modal('hide');
                        $("#save_div").show();
                        $("#loading_div").hide();
                        callAjax();
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
                        $("#save_div").show();
                        $("#loading_div").hide();
                    }
                })
            }
        }

        function viewfeedback(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.schedule-feedback.view') }}",
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
                            $("#feedback_id").val(data.id);
                            $("#feedback").val(data.feedback_id).select2();
                            $("#type").val(data.feedback_for).select2();
                            $("#expiry_date").val(data.expiry_date);
                            $("#start_date").val(data.start_date);
                            $("#status").val(data.status).select2();
                            $("#sem").val(data.semester).select2();
                            $("#ay").val(data.academic_id).select2();
                            $("#degree").val(data.degree_id).select2();
                            $("#sec").val(data.section).select2();
                            // console.log(data.subject_ids);
                            if (data.subject_ids) {
                                let subject = JSON.parse(data.subject_ids)
                                $.each(subject, function(index, value) {
                                    $("#subject option[value='" + value + "']").prop("selected", true);
                                })

                                $('.sub').show();
                            } else {
                                $('.sub').hide();
                            }
                            getDays(data.expiry_date)
                            let decode = JSON.parse(data.course_id)
                            $.each(decode, function(index, value) {
                                $("#course option[value='" + value + "']")
                                    .prop("selected", true);
                            })
                            $("#course").select2();
                            $("#subject").select2();

                            $('.tbl').show();
                            $('.questions').hide();
                            $('.buttons').hide();
                            $("#save_div").hide();
                            $("#fee_components_span").hide();
                            $("#loading_div").hide();
                            $("#scheduleFeedbackModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
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
                })
            }
        }

        function editfeedback(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.schedule-feedback.edit') }}",
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
                            $("#feedback_id").val(data.id);
                            $("#feedback").val(data.feedback_id).select2();
                            $("#type").val(data.feedback_for).select2();
                            $("#expiry_date").val(data.expiry_date);
                            $("#start_date").val(data.start_date);
                            $("#status").val(data.status).select2();
                            getDays(data.expiry_date)
                            $("#degree").val(data.degree_id).select2();
                            $("#ay").val(data.academic_id).select2();
                            $("#sem").val(data.semester).select2();
                            $("#sec").val(data.section).select2();
                            if (data.subject_ids) {
                                let subject = JSON.parse(data.subject_ids)
                                $.each(subject, function(index, value) {
                                    $("#subject option[value='" + value + "']").prop("selected", true);
                                })

                                $('.sub').show();
                            } else {
                                $('.sub').hide();
                            }
                            if (data.course_id) {
                                let decode = JSON.parse(data.course_id)
                                $.each(decode, function(index, value) {
                                    $("#course option[value='" + value + "']")
                                        .prop("selected", true);
                                })
                            }
                            $("#course").select2();
                            $("#subject").select2();
                            $('.tbl').hide()
                            $('.questions').show()
                            $('.buttons').show()
                            $("#save_div").show();
                            $("#save_btn").html(`Update`);
                            $("#fee_components_span").hide();
                            $("#loading_div").hide();
                            $("#scheduleFeedbackModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
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
                })
            }
        }

        function deletefeedback(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
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
                            url: "{{ route('admin.schedule-feedback.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $('.secondLoader').hide()
                                Swal.fire('', response.data, response.status);
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
