@extends('layouts.admin')
@section('content')
    <style>
        .toggle {
            position: relative;
            width: 60%;
            margin: auto;
        }

        .toggle:before {
            content: '';
            position: absolute;
            border-bottom: 3px solid #fff;
            border-right: 3px solid #fff;
            width: 6px;
            height: 14px;
            z-index: 2;
            transform: rotate(45deg);
            top: 8px;
            left: 15px;
        }

        .toggle:after {
            content: '×';
            position: absolute;
            top: -6px;
            left: 35px;
            z-index: 2;
            line-height: 42px;
            font-size: 26px;
            color: #aaa;
        }

        .toggle input[type="checkbox"] {
            position: absolute;
            left: 0;
            top: 0;
            z-index: 10;
            width: 100%;
            height: 100%;
            cursor: pointer;
            opacity: 0;
        }

        .toggle label {
            position: relative;
            display: flex;
            align-items: center;
        }

        .toggle label:before {
            content: '';
            width: 70px;
            height: 30px;
            box-shadow: 0 0 1px 2px #0001;
            background: #eee;
            position: relative;
            display: inline-block;
            border-radius: 46px;
        }

        .toggle label:after {
            content: '';
            position: absolute;
            width: 31px;
            height: 29px;
            border-radius: 50%;
            left: 0;
            top: 0;
            z-index: 5;
            background: #fff;
            box-shadow: 0 0 5px #0002;
            transition: 0.2s ease-in;
        }

        .toggle input[type="checkbox"]:hover+label:after {
            box-shadow: 0 2px 15px 0 #0002, 0 3px 8px 0 #0001;
        }

        .toggle input[type="checkbox"]:checked+label:before {
            transition: 0.1s 0.2s ease-in;
            background: #4BD865;
        }

        .toggle input[type="checkbox"]:checked+label:after {
            left: 30px;
        }

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
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FeedBack text-center"
                style="text-transform: capitalize;">
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
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span id="feedback_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">Feedback Participant</label>
                                <select name="participant" id="participant" class="form-control select2"
                                    onchange="participant(this)">
                                    <option value="">Select Type</option>
                                    <option value="student">Student</option>
                                    <option value="faculty">Faculty</option>
                                    <option value="external">External</option>
                                </select>
                                <span id="participant_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group type">
                                <label for="result" class="required">Feedback Type</label>
                                <select style="text-transform: capitalize;" name="type" id="type"
                                    class="form-control select2">
                                    <option value="">Select Type</option>
                                    @foreach ($feed_type as $i => $item)
                                        <option value="{{ $item->feedback_type }}"
                                            data-type="{{ $item->feedback_participant }}"
                                            style="text-transform: capitalize;">
                                            {{ ucwords($item->feedback_type) }}
                                        </option>
                                    @endforeach
                                </select>


                                <span id="type_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group training">
                                <label for="type_training" class="required">Type of Training</label>
                                <select style="text-transform: capitalize;" name="type_training" id="type_training"
                                    class="form-control select2">
                                    <option value="">Select Training</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Others">Others</option>
                                </select>
                                <span id="type_training_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group others_training">
                                <label for="others_training" class="required">Others</label>
                                <input type="text" name="others_training" id="others_training" class="form-control">
                                <span id="others_training_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group training">
                                <label for="title_training" class="required">Title</label>
                                <input type="text" name="title_training" id="title_training" class="form-control">
                                <span id="title_training_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group training">
                                <label for="from_time" class="required">From Time</label>
                                <input type="time" name="from" id="from_time" class="form-control"
                                    placeholder="">
                                <span id="from_time_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group training">
                                <label for="to_time" class="required">To Time</label>
                                <input type="time" name="to_time" id="to_time" class="form-control"
                                    placeholder="">
                                <span id="to_time_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group training">
                                <label for="person_training" class="required">Resource Person</label>
                                <input type="text" name="person_training" id="person_training" class="form-control">
                                <span id="person_training_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                                <label for="start_date" class="required">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                                <span id="start_date_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                                <label for="expiry_date" class="required">Expire Date</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                    onblur="getDays(this)">
                                <span id="expiry_date_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            {{-- <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result" class="required">Status</label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Disabled">Disabled</option>
                                </select>
                            </div> --}}
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group filter">
                                <label for="result">Degree</label>
                                <select name="degree" id="degree" class="form-control select2">
                                    <option value="">Select Degree</option>
                                    <option value="All">All</option>
                                    @foreach ($degree as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group filter course">
                                <label for="course">Course</label>
                                <select name="course[]" id="course" class="form-control select2">
                                    <option value="">Select Course</option>
                                    @foreach ($course as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group filter">
                                <label for="batch">Batch</label>
                                <select name="batch" id="batch" class="form-control select2">
                                    <option value="">Select Batch</option>
                                    @foreach ($batch as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group filter">
                                <label for="ay">Academic Year</label>
                                <select name="ay" id="ay" class="form-control select2">
                                    <option value="">Select Academic Year</option>
                                    @foreach ($ay as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group filter">
                                <label for="sem">Semester</label>
                                <select name="sem" id="sem" class="form-control select2">
                                    <option value="">Select Semester</option>
                                    <option value="All">All</option>
                                    @foreach ($sem as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group filter">
                                <label for="sec">Section</label>
                                <select name="sec" id="sec" class="form-control select2">
                                    <option value="">Select Section</option>
                                    <option value="All">All</option>
                                    @foreach ($sec as $id => $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group course">
                                <label for="dept">Department</label>
                                <select name="dept[]" id="dept" class="form-control select2" multiple>
                                    <option value="All">All</option>
                                    @foreach ($dept as $id => $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                                <label for="result">No. of Days</label>
                                <input type="text" name="days" id="days" class="form-control" disabled>
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

        const tool_course = `@foreach ($course as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach`;

        const tool_feedType = `@foreach ($feed_type as $i => $item)
                                <option value="{{ $item->feedback_type }}"
                                    data-type="{{ $item->feedback_participant }}"
                                    style="text-transform: capitalize;">
                                    {{ ucwords($item->feedback_type) }}
                                </option>
                            @endforeach`;

        const tool_section = `@foreach ($sec as $id => $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach`;


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
                        name: 'status',
                        render: function(data, type, row) {
                            // Create the HTML for the toggle switch
                            return '<div class="toggle text-center">' +
                                '<input onchange="checkStatus(this)" type="checkbox" data-id="' + row.id +
                                '" class="toggleData" ' + (row
                                    .status ==
                                    "1" ? 'checked' : '') + '/>' +
                                '<label></label>' +
                                '</div>';
                        }
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

        $('#type').change(function() {
            let value = $('#type option:selected').val();
            console.log(value);
            $('#others_training').val('')
            $('.others_training').hide()
            if (value == 'faculty feedback') {
                $('.filter').hide();
                $('.course').show();
                $('.training').hide();
            } else if (value == 'training feedback') {
                $('.training').show();
                $('.filter').show();
                $('.course').show();
            } else if (value == 'course feedback') {
                $('.training').hide();
                $('.type').show();
                $('.course').hide();
                $('.filter').show();
            }
        })

        function participant(e) {
            let participate = $('#participant').val();
            $('#others_training').val('')
            $('#type').html(tool_feedType);
            $('#type option').each(function() {
                let optionType = $(this).data('type');
                if (optionType != participate && optionType != undefined) {
                    $(this).remove();
                }
            });

            if (participate == 'external') {
                $(".filter").hide();
                $(".course").show();
                $('.type').hide();
            } else {
                $('.type').show();
                $('.filter').show();
            }
            $('#type').append($('#type option'));
            $('#type').prepend(`<option value="" selected>Select Type</option>`).select2();
        }
        $('#participant').change(function() {

        })

        function getDays(e) {
            let start_date = new Date($('#start_date').val());
            let expiry = new Date($('#expiry_date').val());
            let diffInTime = expiry.getTime() - start_date.getTime();
            let diffInDays = Math.ceil(diffInTime / (1000 * 3600 * 24));
            if (diffInDays >= 0) {
                $('#days').val((diffInDays == 0 ? 1 : diffInDays) + ' Days')
            } else {
                Swal.fire('', 'Invalid Dates', 'error');
            }
        }

        $('#degree').change(function() {
            let course = $('#course').html(`<option value="">Loading...</option>`)
            $.ajax({
                url: '{{ route('admin.schedule-feedback.fetch_course') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': $('#degree').val(),
                },
                success: function(response) {
                    let status = response.status;
                    let data = response.data;
                    if (status == true) {
                        course = $('#course').empty()
                        $.each(data, function(index, value) {
                            course.append(
                                `<option value="${index}">${value}</option>`)
                        })
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
                    $("#save_div").show();
                    $("#loading_div").hide();
                }
            })
        })

        function cpyLink(e) {
            var decode = atob($(e).data('link'));
            var tempInput = $('<input>').val(decode).appendTo('body').select();
            document.execCommand('copy');
            tempInput.remove();
            Swal.fire('', 'Link Copied...', 'success');
        }

        function checkStatus(e) {
            let status = 0;
            $(".secondLoader").show();
            if ($(e).is(":checked")) {
                status = 1;
            } else {
                status = 0;
            }
            let id = $(e).data('id')
            $.ajax({
                url: '{{ route('admin.schedule-feedback.change-status') }}',
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                    'status': status
                },
                success: function(response) {
                    $(".secondLoader").hide();
                    let status = response.status;
                    let data = response.data;
                    if (status == true) {
                        Swal.fire('', data, 'success');
                    } else {
                        Swal.fire('', data, 'error');
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

        function openModal() {
            $("#feedback_id").val('');
            $("#feedback").val('').select2();
            $("#participant").val('').select2();
            $('#others_training').val('')
            $("#participant").prop('disabled', false);
            $('#course').prop('disabled', false);
            $("#type").val('').prop("disabled", false).select2();
            $("#start_date").val('');
            $("#expiry_date").val('');
            $("#type_training").val('').select2();
            $("#title_training").val('');
            $("#from_time").val('');
            $("#to_time").val('');
            $('.others_training').hide()
            $("#person_training").val('');
            $("#dept").val('').select2();
            $("#type").empty();
            $("#type").html(tool_feedType);
            $("#type").prepend(`<option value="">Select Type</option>`);
            $("#type").val('').select2();
            $("#type").select2();
            $("#sem").val('').select2();
            $("#ay").val('').select2();
            $("#batch").val('').select2();
            $("#degree").val('').select2();
            $("#sec").val('').select2();
            $("#course").val('').select2();
            $('#days').val('')
            $("#loading_div").hide();
            $(".training").show();
            $(".course").show();
            $(".type").show();
            $(".training").hide();
            $(".filter").show();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#scheduleFeedbackModel").modal();
        }

        // function getSection(e) {
        //     console.log($('#course').val());
        //     console.log($('#course').val().length);
        //     $('#course').prop('disabled', false);

        //     if ($('#course').val().length == 1 && $('#course').val() != 'All' && $('#course').val() != '') {
        //         let section = $('#sec').html(`<option value="">Loading...</option>`)
        //         $('#course option[value="All"]').prop('selected', false);
        //         $('#course').prop('disabled', true);
        //         $.ajax({
        //             url: '{{ route('admin.schedule-feedback.fetch_section') }}',
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'id': $('#course').val(),
        //             },
        //             success: function(response) {
        //                 let status = response.status;
        //                 let data = response.data;
        //                 if (status == true) {
        //                     section = $('#sec').empty()
        //                     section.prepend(
        //                         `<option value="All">All</option>`)
        //                     $.each(data, function(index, value) {
        //                         section.append(
        //                             `<option value="${value}">${value}</option>`)
        //                     })
        //                 } else {
        //                     Swal.fire('', response.data, 'error');
        //                 }
        //                 $('#course').prop('disabled', false);

        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 if (jqXHR.status) {
        //                     if (jqXHR.status == 500) {
        //                         Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
        //                     } else {
        //                         Swal.fire('', jqXHR.status, 'error');
        //                     }
        //                 } else if (textStatus) {
        //                     Swal.fire('', textStatus, 'error');
        //                 } else {
        //                     Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
        //                         "error");
        //                 }
        //                 $('#course').prop('disabled', false);

        //                 $("#save_div").show();
        //                 $("#loading_div").hide();
        //             }
        //         })



        //     } else if ($('#course').val().length > 1 || $('#course').val() == 'All') {
        //         $('#course').prop('disabled', false);
        //         let value = $('#course').val();
        //         console.log(value[0] == 'All' && $('#course').val().length > 1);

        //         if (value[0] == 'All' && $('#course').val().length > 1) {
        //             var course = $('#course').val()
        //             let len = $('#course').val().length;
        //             console.log(len);
        //             for (let index = 0; index < len - 1; index++) {
        //                 var remove = course.pop();
        //             }
        //             $('#course').val(course).select2()
        //         }
        //         let section = $('#sec').empty();
        //         section.append(`<option value="All">All</option>`)
        //     }
        // }


        $('#type_training').change(function() {
            if ($('#type_training').val() == 'Others') {
                $('.others_training').show();
            } else {
                $('.others_training').hide();
                $('#others_training').val('');
            }
        })


        function saveFeedback() {
            if ($('#feedback').val() == '') {
                $("#feedback_span").html(`Fees Components Is Required.`);
                $("#feedback_span").show();
                $("#participant_span").hide();
                $("#expiry_date_span").hide();
                $("#start_date_span").hide();
            } else if ($('#participant').val() == '') {
                $("#participant_span").html(`Participant Is Required.`);
                $("#participant_span").show();
                $("#feedback_span").hide();
                $("#expiry_date_span").hide();
                $("#start_date_span").hide();
            } else if ($('#start_date').val() == '') {
                $("#start_date_span").html(`Start Date Is Required.`);
                $("#start_date_span").show();
                $("#feedback_span").hide();
                $("#expiry_date_span").hide();
                $("#participant_span").hide();
            } else if ($('#expiry_date').val() == '') {
                $("#expiry_date_span").html(`Expiry Date Is Required.`);
                $("#expiry_date_span").show();
                $("#participant_span").hide();
                $("#start_date_span").hide();
                $("#feedback_span").hide();
            } else {
                $("#save_div").hide();
                $("#loading_div").show();
                if ($('#type_training').val() == 'Others') {
                    type_training = $('#others_training').val()
                } else {
                    type_training = $('#type_training').val()
                }
                $.ajax({
                    url: '{{ route('admin.schedule-feedback.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#feedback_id').val(),
                        'name': $('#feedback').val(),
                        'participant': $('#participant').val(),
                        'type': $('#type').val(),
                        'type_training': type_training,
                        'title_training': $('#title_training').val(),
                        'from_time': $('#from_time').val(),
                        'to_time': $('#to_time').val(),
                        'person_training': $('#person_training').val(),
                        'start': $('#start_date').val(),
                        'expiry': $('#expiry_date').val(),
                        'status': $('#status').val(),
                        'degree': $('#degree').val(),
                        'course': $('#course').val(),
                        'batch': $('#batch').val(),
                        'ay': $('#ay').val(),
                        'sem': $('#sem').val(),
                        'sec': $('#sec').val(),
                        'dept': $('#dept').val(),
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
                            $('.others_training').hide()
                            $("#feedback_id").val(data.id);
                            $("#feedback").val(data.feedback_id).select2();
                            $("#participant").val(data.feedback_participant).select2();
                            $("#participant").prop('disabled', true);

                            participant(data.feedback_participant)
                            if (data.feedback_type == 'training feedback' && data.feedback_type != null) {
                                let training_details = JSON.parse(data.training);
                                console.log(training_details);
                                $("#type").val(data.feedback_type).prop("disabled", true).select2();
                                $("#from_time").val(training_details.from_time);
                                $("#to_time").val(training_details.to_time);
                                $("#person_training").val(training_details.person_training);
                                $("#title_training").val(training_details.title_training);
                                if (training_details.type_training != 'Seminar' && training_details
                                    .type_training != 'Workshop') {
                                    $("#type_training").val('Others').select2();
                                    $(".others_training").show();
                                    $("#others_training").val(training_details.type_training);

                                } else {
                                    $("#type_training").val(training_details.type_training).select2();
                                }

                                $(".training").show();
                                $(".type").show();
                                $(".course").hide();
                                $(".filter").show();

                            } else if (data.feedback_type == 'course feedback' && data.feedback_type != null) {
                                $("#type").val(data.feedback_type).prop("disabled", true).select2();
                                $(".type").show();
                                $(".training").hide();
                                $(".course").hide();
                                $(".filter").show();
                            } else if (data.feedback_type == 'faculty feedback' && data.feedback_type != null) {
                                $("#type").val(data.feedback_type).prop("disabled", true).select2();
                                $("#course").val(data.course_id).prop("selected", true);
                                $(".filter").hide();
                                $(".course").show();
                                $(".type").show();
                                $(".training").hide();
                            } else {
                                $(".training").hide();
                                $(".type").hide();
                                $(".others_training").hide();
                                $(".course").show();
                                $(".filter").hide();
                            }

                            if (data.feedback_participant != 'external') {
                                $("#degree").val(data.degree_id).select2();
                                $("#batch").val(data.batch_id).select2();
                                $("#sem").val(data.semester).select2();
                                $("#ay").val(data.academic_id).select2();
                                $("#sec").val(data.section).select2();
                                $("#course").val('');
                                $("#course").val(data.course_id).prop("selected", true);
                                $("#course").select2();
                            } else {
                                $("#type").val(data.feedback_type).prop("disabled", true).select2();
                                $("#course").val(data.course_id).prop("selected", true);
                                $("#course").select2();
                                $(".type").hide();
                                $(".filter").hide();
                                $(".course").show();
                                $(".training").hide();
                            }

                            $("#expiry_date").val(data.expiry_date);
                            $("#start_date").val(data.start_date);
                            $("#status").val(data.status).select2();
                            getDays(data.expiry_date)
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
                            $("#participant").val(data.feedback_participant).select2();
                            $("#participant").prop('disabled', true);
                            $('.others_training').hide()
                            participant(data.feedback_participant)
                            if (data.feedback_type == 'training feedback' && data.feedback_type != null) {
                                let training_details = JSON.parse(data.training);
                                $("#type").val(data.feedback_type).prop("disabled", true).select2();
                                $("#from_time").val(training_details.from_time);
                                $("#to_time").val(training_details.to_time);
                                $("#person_training").val(training_details.person_training);
                                $("#title_training").val(training_details.title_training);
                                if (training_details.type_training != 'Seminar' && training_details
                                    .type_training != 'Workshop') {
                                    $("#type_training").val('Others').select2();
                                    $(".others_training").show();
                                    $("#others_training").val(training_details.type_training);

                                } else {
                                    $("#type_training").val(training_details.type_training).select2();
                                }
                                $(".training").show();
                                $(".type").show();
                                $(".course").hide();
                                $(".filter").show();

                            } else if (data.feedback_type == 'course feedback' && data.feedback_type != null) {

                                $("#type").val(data.feedback_type).prop("disabled", true).select2();
                                $(".type").show();
                                $(".training").hide();
                                $(".course").hide();
                                $(".filter").show();

                            } else if (data.feedback_type == 'faculty feedback' && data.feedback_type != null) {
                                $("#type").val(data.feedback_type).prop("disabled", true).select2();
                                $("#course").val(data.course_id).prop("selected", true);
                                $("#course").select2();
                                $(".filter").hide();
                                $(".course").show();
                                $(".type").show();
                                $(".training").hide();

                            } else {
                                $(".training").hide();
                                $(".type").hide();
                                $(".others_training").hide();
                                $(".course").show();
                                $(".filter").hide();
                            }

                            if (data.feedback_participant != 'external') {
                                $("#degree").val(data.degree_id).select2();
                                $("#batch").val(data.batch_id).select2();
                                $("#sem").val(data.semester).select2();
                                $("#ay").val(data.academic_id).select2();
                                $("#sec").val(data.section).select2();
                                console.log(data.course_id);
                                $("#course").val(data.course_id).prop("selected", true);
                                $("#course").select2();
                            } else {
                                $("#type").val(data.feedback_type).prop("disabled", true).select2();
                                $("#course").val(data.course_id).prop("selected", true);
                                $("#course").select2();
                                $(".type").hide();
                                $(".filter").hide();
                                $(".course").show();
                                $(".training").hide();
                            }

                            $("#expiry_date").val(data.expiry_date);
                            $("#start_date").val(data.start_date);
                            $("#status").val(data.status).select2();
                            getDays(data.expiry_date)
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
