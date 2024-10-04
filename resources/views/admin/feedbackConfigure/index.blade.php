@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    @can('nationality_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Create FeedBack
                </button>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            FeedBack Lists
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
                            Created By
                        </th>
                        <th>
                            Created At
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="modal fade" id="configureFeedbackModel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">FeedBack Name</label>
                                <input type="hidden" name="feedback_id" id="feedback_id" value="">
                                <input type="text" class="form-control" style="text-transform:uppercase" id="feedback"
                                    name="feedback" value="">
                                <span id="feedback_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            {{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">FeedBack Type</label>
                                <select class="form-control select2" name="feedback_type" id="feedback_type">
                                    <option value="">Select Type</option>
                                    <option value="Academic">Academic</option>
                                    <option value="Event">Event</option>
                                    <option value="Others">Others</option>
                                </select>
                                <span id="feedback_type_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div> --}}
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">Rating Scale</label>
                                <select class="form-control select2" name="rate" id="rate">
                                    <option value="">Select Scale</option>
                                    <option value="3">3 Scale
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Good/Fair/Poor)</option>
                                    <option value="4">4 Scale
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Very Good/Good/Fair/Poor)</option>
                                    <option value="5">5 Scale
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Excellent/Very Good/Good/Fair/Poor)
                                    </option>
                                </select>
                                <span id="rating_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group questions">
                                <label for="Questions">Questions</label>
                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label for="Questions" class="control-label">1.</label>
                                    <input type="text" class="form-control ques_inp" style="text-transform:capitalize"
                                        id="ques_inp_1" name="ques_inp[]" value="">
                                    <span id="ques_inp_1_span" class="text-danger text-center"
                                        style="display:none;font-size:0.9rem;"></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group tbl">
                                <table class="table table-bordered table-striped" style="text-transform: capitalize">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Question</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group buttons">
                                <button onclick="addQuestion(this)" class="btn btn-sx btn-outline-success float-left"><i
                                        class="far fa-question-circle"></i> Add Question</button>
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
                ajax: "{{ route('admin.configure-feedback.index') }}",
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
                        data: 'createdBy',
                        name: 'createdBy'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
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
            let table = $('.datatable-FeedBack').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#feedback").val('')
            $("#feedback_id").val('')
            $('.tbl').hide()
            $('.questions').show()
            $('.buttons').show()
            $('#rate').val('').select2().prop('disabled', false);
            $("#fee_components_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#configureFeedbackModel").modal()
            $('.questions').empty()
            let add = `<label for="Questions">Questions</label><div class="form-group" style="margin-bottom: 1rem;"><label for="result" class="control-label">1.</label>
                            <input type="text" class="form-control ques_inp" style="text-transform:capitalize;" id="ques_inp_1" name="ques_inp" value="">
                                <span id="ques_inp_1_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                       </div>`;

            $('.questions').append(add);
        }

        function addQuestion(e) {
            let inp = $('.ques_inp').length;
            if (inp) {
                let add = `<div class="form-group" style="margin-bottom: 1rem;"><label for="result" class="control-label">${inp+1}.</label>
                                <input type="text" class="form-control ques_inp" style="text-transform:capitalize" id="ques_inp_${inp+1}"
                                    name="ques_inp" value="">
                                <span id="ques_inp_${inp+1}_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                           </div>`;

                $('.questions').append(add)
            }
        }


        function saveFeedback() {
            if ($('#feedback').val() == '') {
                $("#feedback_span").html(`Feedback Name Is Required.`);
                $("#feedback_span").show();
                $("#rating_span").hide();
            } else if ($('#rate').val() == '') {
                $("#rating_span").html(`Rating Scale Is Required.`);
                $("#rating_span").show();
                $("#feedback_span").hide();
            } else {
                let ques_inp = $('.ques_inp').length
                let question = [];
                if (ques_inp) {
                    for (let i = 1; i <= ques_inp; i++) {
                        let q = `ques_inp_${i}`
                        question.push($('#' + q).val());
                    }
                }
                $("#save_div").hide();
                $("#loading_div").show();
                $("#rating_span").hide();
                $("#feedback_span").hide();
                $.ajax({
                    url: '{{ route('admin.configure-feedback.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#feedback_id').val(),
                        'name': $('#feedback').val(),
                        'rate': $('#rate').val(),
                        'question': question
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#configureFeedbackModel").modal('hide');
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
                    url: "{{ route('admin.configure-feedback.view') }}",
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
                            console.log(data)
                            $("#feedback_id").val(data.id);
                            $("#feedback").val(data.name);
                            $("#rate").val(data.rating).select2().prop('disabled', true);
                            let question = JSON.parse(data.question)
                            let body = $('#tbody').empty()
                            $.each(question, function(index, value) {
                                let row = `<tr><td>${index+1}</td><td>${value}</td></tr>`
                                body.append(row)
                            })

                            $('.tbl').show()
                            $('.questions').hide()
                            $('.buttons').hide()
                            $("#save_div").hide();
                            $("#fee_components_span").hide();
                            $("#loading_div").hide();
                            $("#configureFeedbackModel").modal();
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
                    url: "{{ route('admin.configure-feedback.edit') }}",
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
                            $("#feedback").val(data.name);
                            $("#rate").val(data.rating).select2().prop('disabled', false);
                            let question = JSON.parse(data.question)
                            let body = $('#tbody').empty()
                            let inp = $('.questions').empty();
                            $.each(question, function(index, value) {
                                let add = `<div class="form-group" style="margin-bottom: 1rem;"><label for="result" class="control-label">${index+1}.</label>
                                <input type="text" class="form-control ques_inp" style="text-transform:capitalize" id="ques_inp_${index+1}"
                                    name="ques_inp" value="${value}">
                                <span id="ques_inp_${index+1}_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span></div>`;

                                $('.questions').append(add)
                            })
                            $('.questions').prepend(`<label for="Questions">Questions</label>`)
                            $('.tbl').hide()
                            $('.questions').show()
                            $('.buttons').show()
                            $("#save_div").show();
                            $("#save_btn").html('Update');
                            $("#fee_components_span").hide();
                            $("#loading_div").hide();
                            $("#configureFeedbackModel").modal();
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
                            url: "{{ route('admin.configure-feedback.delete') }}",
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
