@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">Institute OD Application</div>
        <div class="card-body">
            <form action="" id="search_form">
                <div class="row">
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label class="required" for="organized_by">Organized By</label>
                        <select class="form-control select2" style="width:100%;" name="organized_by" id="organized_by"
                            onchange="change_depts(this)">
                            <option value="">Please Select</option>
                            <option value="Placement">Placement</option>
                            <option value="Training">Training</option>
                            <option value="Centres">Centres</option>
                            <option value="Department">Department</option>
                            <option value="Clubs">Clubs</option>
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label class="required" for="dept_name">Name Of the Department</label>
                        <select class="form-control select2" style="width:100%;" name="dept_name" id="dept_name">
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label class="required" for="incharge">Faculty Incharge</label>
                        <select class="form-control select2" style="width:100%;" name="incharge" id="incharge">
                            <option value="">Select Staff</option>
                            @foreach ($teaching_staff as $staff)
                                <option value="{{ $staff->user_name_id }}">
                                    {{ $staff->name }} ({{ $staff->StaffCode }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label class="required" for="event_title">Event Title</label>
                        <input class="form-control" type="text" name="event_title" id="event_title"
                            placeholder="Enter Event Title">

                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                        <label class="required" for="event_category">Event Category</label>
                        <select class="form-control select2" style="width:100%;" name="event_category" id="event_category"
                            onchange="check_eventCat(this)">
                            <option value="Internal">Internal</option>
                            <option value="External">External</option>
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12" style="display:none;"
                        id="ext_div">
                        <label class="required" for="ext_event_venue">External Event Venue</label>
                        <input class="form-control" type="text" name="ext_event_venue" id="ext_event_venue"
                            value="" placeholder="Enter External Event Venue">
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                        <label class="required" for="duration">Duration</label>
                        <div class="row text-center">
                            <div class="col-5">
                                <input type="radio" style="width:18px;height:18px;" name="duration" id="period_duration"
                                    value="" onchange="check_duration(this)"> <b
                                    style="padding-left:0.2rem;">Periods</b>
                            </div>
                            <div class="col-4">
                                <input type="radio" style="width:18px;height:18px;" name="duration" id="day_duration"
                                    value="" onchange="check_duration(this)"> <b style="padding-left:0.2rem;">Days</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                        <label class="required" for="event_date">Event Period (From & To)</label>
                        <div style="display:flex;justify-content:space-around;">
                            <select class="form-control select2" style="width:40%;" name="from_period" id="from_period"
                                onchange="check_period(this)">
                                <option value="">Select Period</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                            </select>
                            <select class="form-control select2" style="width:40%;" name="to_period" id="to_period">

                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                        <label class="required" for="event_date">Event Date (From & To)</label>
                        <div style="display:flex;justify-content:space-around;">
                            <input type="text" class="date"
                                style="width:40%;padding-left:10px;border: 1px solid #cfd1d8;" name="from_date"
                                id="from_date" placeholder="Enter From Date">
                            <input type="text" class="date"
                                style="width:40%;padding-left:10px;border: 1px solid #cfd1d8;" name="to_date"
                                id="to_date" placeholder="Enter To Date">
                        </div>
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-2 col-sm-2 col-12">
                        <div class="form-group" style="padding-top: 32px;">
                            <button type="button" style="width:100%;" id="submit" name="submit"
                                onclick="open_card()" class="enroll_generate_bn">Go</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card" id="open" style="display:none;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 col-sm-4 col-12">
                    <button class="bg-warning enroll_generate_bn" data-toggle="modal" data-target="#odImportModal">
                        {{ trans('global.app_csvImport') }}
                    </button>
                </div>
                <div class="col-md-6 col-sm-8 col-12" id="add_stu_div">
                    <select class="form-control select2" name="student" id="student" style="width:70%;">
                        <option value="">Select Student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->user_name_id }}">{{ $student->name }}
                                ({{ $student->register_no }})
                            </option>
                        @endforeach
                    </select>
                    <div style="display:inline-block;">
                        <button type="button" class="bg-success enroll_generate_bn" style="padding:4.3px 15px;"
                            onclick="save()">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="" id="student_form">
                <table class=" table table-bordered table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>
                                S.No
                            </th>
                            <th>
                                Register No
                            </th>
                            <th>
                                Student Name
                            </th>
                            <th>
                                Department
                            </th>
                            <th>
                                Course
                            </th>
                            <th>
                                Semester
                            </th>
                            <th>
                                Section
                            </th>
                            <th>
                                Remove
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
                <style>
                    .image-container {
                        display: inline-block;
                        position: relative;
                        margin-right: 10px;
                    }

                    .remove-icon {
                        position: absolute;
                        /* top: 5px; */
                        left: 1px;
                        padding: 0px 8px 0px 8px;
                        background-color: rgb(0 123 255);
                        color: #f2f2f2;
                        font-size: 20px;
                        border-radius: 50%;
                        cursor: pointer;
                        display: none;
                    }
                </style>
                <div id="uploaded-files" style="padding:20px;display:flex"></div>

                <div style="display: flex;
                justify-content: space-between;">
                    <span class='file file--upload'>
                        <label for='input-file' class="enroll_generate_bn">
                            File Upload
                        </label>
                        {{-- onclick="remove(this)" --}}
                        <input id='input-file' type='file' style="display:none;" />
                    </span>
                    <span class="enroll_generate_bn" style="font-size: 1.3rem;" onclick="submit()">Submit</span>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="odImportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel">@lang('global.app_csvImport')</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-12'>
                            <form class="form-horizontal" id="import_form">

                                <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                                    <label for="csv_file"
                                        class="col-md-4 control-label required">@lang('global.app_csv_file_to_import')</label>

                                    <div class="col-md-6">
                                        <input id="csv_file" type="file" class="form-control-file" name="csv_file"
                                            required>

                                        @if ($errors->has('csv_file'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('csv_file') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="header" id="checker" checked>
                                                @lang('global.app_file_contains_header_row')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="text-primary text-center" style="display:none;" id="loading_tag_one">
                                Processing...
                            </div>
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button class="btn btn-primary" onclick="importCSV()">
                                        @lang('global.app_parse_csv')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="odImportListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel">Import List</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="importlistbody">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#upload-button').click(function() {
                let input = $('#input-file')[0];
                if (input.files && input.files.length > 0) {
                    uploadFile(input.files[0]);
                } else {
                    alert('Please select a file to upload.');
                }
            });

            $('#input-file').change(function() {
                handleFileUpload(this.files);
            });
        });

        function uploadFile(file) {
            let formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: '{{ route('admin.bulk-ods.documents') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    displayUploadedFile(response.path);
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
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
            });
        }

        function handleFileUpload(files) {
            let formData = new FormData();

            for (let i = 0; i < files.length; i++) {
                formData.append('file', files[i]);

                $.ajax({
                    url: '{{ route('admin.bulk-ods.documents') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        displayUploadedFile(response.path);
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
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
                });
            }
        }
        let uploadedFiles = [];

        function displayUploadedFile(file) {
            var assetUrl = '{{ asset('') }}';

            var uploadedFilesDiv = $('#uploaded-files');
            var fileContainer = $('<div>', {
                class: 'image-container'
            });

            var isImage = /\.(jpeg|jpg|png|gif)$/i.test(file);

            if (isImage) {
                var image = $('<img>', {
                    src: assetUrl + file,
                    width: 100,
                    height: 100,
                    css: {
                        'border-radius': '8px',
                        'margin-right': '14px'
                    }
                });
            } else { // Handle non-image files (e.g., PDF)
                var fileLink = $('<a>', {
                    href: assetUrl + file,
                    text: 'Click to open ',
                    target: '_blank',
                    css: {
                        'margin': '7px'
                    }
                });
            }

            var removeIcon = $('<span>', {
                class: 'remove-icon',
                html: '&times;',
                css: {
                    display: 'none'
                },
                click: function() {
                    var index = uploadedFiles.indexOf(file);

                    if (index !== -1) {
                        uploadedFiles.splice(index, 1);
                        fileContainer.remove();
                    }
                }
            });

            if (isImage) {
                fileContainer.append(image);
            } else {
                fileContainer.append(fileLink);
            }

            fileContainer.append(removeIcon);

            fileContainer.hover(
                function() {
                    removeIcon.show();
                },
                function() {
                    removeIcon.hide();
                }
            );

            uploadedFilesDiv.append(fileContainer);
            uploadedFiles.push(file);
        }

        window.onload = function() {
            let select = ('student', 'organized_by', 'incharge', 'dept_name')
            $(select).select2();
            $("#open").hide();
        }

        function change_depts(element) {
            if (element.value != '') {

                if (element.value == 'Placement') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    <option value="Placement" selected>Placement</option>`);
                }
                if (element.value == 'Training') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    <option value="Training" selected>Training</option>`);
                }
                if (element.value == 'Centres') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    <option value="Centres" selected>Centres</option>`);
                }
                if (element.value == 'Department') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    @foreach ($deparments as $id => $entry)
                       <option value="{{ $entry }}">{{ $entry }}</option>
                    @endforeach`);
                }
                if (element.value == 'Clubs') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    <option value="Clubs" selected>Clubs</option>`);
                }
            }
        }

        function open_card() {
            $("#open").hide();
            if ($("#organized_by").val() == '') {
                Swal.fire('', 'Please Select the Organizer', 'info');
            } else if ($("#dept_name").val() == '') {
                Swal.fire('', 'Please Select the Department', 'info');
            } else if ($("#incharge").val() == '') {
                Swal.fire('', 'Please Select the Incharge', 'info');
            } else if ($("#event_title").val() == '') {
                Swal.fire('', 'Please Fill the Event Title', 'info');
            } else if ($("#event_category").val() == '') {
                Swal.fire('', 'Please Select the Event Category', 'info');
            } else if ($("#event_category").val() == 'External') {
                if ($("#ext_event_venue").val() == '') {
                    Swal.fire('', 'Please Fill the External Event Venue', 'info');
                    return false;
                }
            } else if ($("#day_duration").val() == '' && $("#period_duration").val() == '') {
                Swal.fire('', 'Please Select the Duration', 'info');
                return false;
            } else if ($("#from_period").val() == '') {
                Swal.fire('', 'Please Choose the From Period', 'info');
            } else if ($("#to_period").val() == '') {
                Swal.fire('', 'Please Choose the To Period', 'info');
            } else if ($("#from_date").val() == '') {
                Swal.fire('', 'Please Choose the From Date', 'info');
            } else if ($("#to_date").val() == '') {
                Swal.fire('', 'Please Choose the To Date', 'info');
            }
            if ($("#organized_by").val() != '' && $("#dept_name").val() != '' && $("#incharge").val() != '' && $(
                    "#event_title").val() != '' && $("#event_category").val() != '' && $("#from_period").val() != '' && $(
                    "#to_period").val() != '' && $("#from_date").val() != '' &&
                $("#to_date").val() != '') {

                let search_form = $("#search_form").serializeArray();

                $.ajax({
                    url: '{{ route('admin.bulk-ods.check') }}',
                    type: 'POST',
                    data: {
                        'search_form': search_form
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response.status == true) {
                            Swal.fire('', 'Already Requested For This OD', 'info');

                            $("#organized_by").val('');
                            $("#incharge").val('');
                            $("#dept_name").val('');
                            $("#event_title").val('');
                            $("#from_date").val('');
                            $("#to_date").val('');

                            $("select").select2();


                        } else {
                            $("#tbody").html('');
                            $("#open").show();
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
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
                });
            }
        }

        function check_eventCat(element) {
            let event_cat = element.value;

            if (event_cat != '') {
                if (event_cat == 'External') {
                    $("#ext_div").show();
                } else {
                    $("#ext_div").hide();
                }
            } else {
                $("#ext_div").hide();
            }

        }

        function check_duration(element) {
            let get_id = $(element).attr('id');
            if (get_id == 'period_duration') {
                $(element).val('period');

                let today_date = new Date();
                let get_year = today_date.getFullYear();
                let get_month = today_date.getMonth() + 1;
                let get_date = today_date.getDate();

                let current_date;
                let current_month;

                let month = get_month.toString();
                let tdate = get_date.toString();
                let month_len = month.length;
                let date_len = tdate.length;

                if (month_len < 2) {
                    current_month = '0' + month;
                } else {
                    current_month = month;
                }
                if (date_len < 2) {
                    current_date = '0' + tdate;
                } else {
                    current_date = tdate;
                }
                let make_date = get_year + '-' + current_month + '-' + current_date;
                $("#from_date").val(make_date)
                $("#to_date").val(make_date)
                $("#from_date").attr('readonly', true);
                $("#to_date").attr('readonly', true);

            } else if (get_id == 'day_duration') {

                $(element).val('day');
                $("#from_date").removeAttr('readonly');
                $("#to_date").removeAttr('readonly');

                let from_period = `<option value='1' selected>1</option>`;
                let to_period = `<option value='7' selected>7</option>`;
                $("#from_period").html(from_period);
                $("#to_period").html(to_period);
                $("select").select2()


            }

        }

        function check_period(element) {

            let period = element.value;
            let to_period = '';
            if (period != '') {
                var got_period = parseInt(period);
                for (let a = got_period; a < 8; a++) {
                    to_period += `<option value="${a}">${a}</option>`;
                }
            }
            $("#to_period").html(to_period);
            $("select").select2();

        }

        function save() {
            let student = $("#student").val();
            if (student != '') {
                $.ajax({
                    url: '{{ route('admin.bulk-ods.save') }}',
                    type: 'POST',
                    data: {
                        'data': student
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // console.log(response);
                        let table_len = $("#tbody").children().length;
                        // console.log(table_len)

                        // if (!response.check) {
                        if (response.student != '') {
                            let student = response.student;
                            let insert = true;
                            if (table_len > 0) {
                                let form_data = $("#student_form").serializeArray();
                                for (let a = 0; a < table_len; a++) {
                                    if (form_data[a].value == student.user_name_id) {
                                        insert = false;
                                    }
                                }
                            } else {
                                insert = true;
                            }
                            // console.log(insert)
                            if (insert) {
                                let len = $("#tbody").children().length + 1;
                                let new_row =
                                    `<tr><td id="s_no">${len}</td><td><input type="hidden" id="user_name_id" name="user_name_id" value="${student.user_name_id}">${student.register_no}</td><td>${student.name}</td><td>${student.dept}</td><td>${student.course}</td><td>${student.semester}</td><td>${student.section}</td><td>   <span class="btn btn-xs btn-danger" onclick="remove(this)">Remove</span> </td></tr>`;
                                $("#tbody").append(new_row);
                            } else {
                                Swal.fire('', 'The Student Already Added in List', 'info');
                            }
                            $('#exampleModal').modal("hide");
                            $("#student").val('');
                            $("#student").select2();
                        }
                        // }
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
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
                });
            }
        }

        function importCSV() {
            let check_file = $("#csv_file")[0].files.length;

            if (check_file > 0) {
                var data;
                let got_file = new FormData();
                let inputFile = $("#csv_file")[0].files[0];

                got_file.append('csv_file', inputFile);


                let checker = $("#checker").is(":checked");
                if (checker == true) {
                    got_file.append('header', 'on');
                } else {
                    got_file.append('header', 'on');
                }
                $("#loading_tag_one").show();

                $.ajax({
                    url: "{{ route('admin.bulk-ods.parseCsvImport', ['model' => 'BulkOD']) }}",
                    type: 'POST',
                    data: got_file,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // console.log(response);
                        let routeName = response.routeName;
                        let filename = response.filename;
                        let hasHeader = response.hasHeader;
                        let modelName = response.modelName;
                        let redirect = response.redirect;
                        let headers = response.headers;
                        let lines = response.lines;
                        let fillables = response.fillables;

                        let list_lines = '';
                        let len;

                        if (typeof lines != undefined && lines.length > 0) {
                            if (lines.length > 3) {
                                len = 3;
                            } else {
                                len = lines.length;
                            }
                            for (let a = 0; a < len; a++) {

                                list_lines += `<tr><td>${lines[a][0]}</td></tr>`;


                            }

                        }



                        let list_content = `
                                   <form class="form-horizontal">
                                        <input type="hidden" id="i_filename"  name="filename" value="${filename}" />
                                        <input type="hidden" id="i_hasHeader"  name="hasHeader" value="${hasHeader}" />
                                        <input type="hidden" id="i_modelName"  name="modelName" value="${modelName}" />
                                        <input type="hidden" id="i_redirect"  name="redirect" value="${redirect}" />

                                        <table class="table">
                                           <tr>
                                               <th> register_no</th>
                                            </tr>
                                            ${list_lines}
                                            <tr>
                                                <td>
                                                    <select id="i_fields">
                                                            <option value="register_no" selected>register_no</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <div class="text-primary text-center" style="display:none;" id="loading_tag">Loading...</div>
                                    <button class="btn btn-primary" onclick="ImportListMake()">
                                        @lang('global.app_import_data')
                                    </button>`;

                        $("#importlistbody").html(list_content);
                        $("#loading_tag_one").hide();
                        $("#odImportModal").modal('hide');
                        $("#odImportListModal").modal();
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
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
                });


                // for (let pair of got_file.entries()) {
                //     console.log(pair[0] + ', ' + pair[1]);
                // }
            } else {
                Swal.fire('', 'Please Choose the File..', 'warning');
            }

        }

        function ImportListMake() {

            let filename = $("#i_filename").val();
            let hasHeader = $("#i_hasHeader").val();
            let modelName = $("#i_modelName").val();
            let redirect = $("#i_redirect").val();
            let register_no = $("#i_fields").val();

            let data = {
                'filename': filename,
                'hasHeader': hasHeader,
                'modelName': modelName,
                'redirect': redirect,
                'fields': ['register_no']
            };
            $("#loading_tag").show();
            // console.log(data);
            $.ajax({
                url: '{{ route('admin.bulk-o-ds.processCsvImport') }}',
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // console.log(response)
                    let student = response.students;
                    let stu_len = student.length;
                    let len = $("#tbody").children().length + 1;
                    let new_row = '';
                    if (stu_len > 0) {
                        for (let a = 0; a < stu_len; a++) {
                            new_row +=
                                `<tr><td id="s_no">${len}</td><td><input type="hidden" id="user_name_id" name="user_name_id" value="${student[a].user_name_id}">${student[a].register_no}</td><td>${student[a].name}</td><td>${student[a].dept}</td><td>${student[a].course}</td><td>${student[a].semester}</td><td>${student[a].section}</td><td>   <span class="btn btn-xs btn-danger" onclick="remove(this)">Remove</span> </td></tr>`;
                        }
                    }

                    $("#tbody").append(new_row);
                    $("#loading_tag").hide();
                    $("#odImportListModal").modal('hide');
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
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
            });

        }

        function remove(element) {
            if (confirm('{{ trans('global.areYouSure') }}')) {
                $(element).closest('tr').remove();
                let len = $("#tbody").children().length;
                let child = $("#tbody").children();
                if (len > 0) {
                    for (let a = 0; a < len; a++) {
                        let td = child.eq(a).find('td#s_no');
                        td.html(a + 1);
                    }
                }

            }
        }

        function submit() {
            let student_form = $("#student_form").serializeArray();
            let search_form = $("#search_form").serializeArray();
            // console.log(search_form.length);
            if (student_form.length > 0) {
                $.ajax({
                    url: '{{ route('admin.bulk-ods.store') }}',
                    type: 'POST',
                    data: {
                        'student_form': student_form,
                        'search_form': search_form,
                        'document': uploadedFiles
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == true) {
                            Swal.fire('', 'Institute OD Applied Successfully.!', 'success');

                            $("#organized_by").val('');
                            $("#incharge").val('');
                            $("#event_title").val('');
                            $("#ext_event_venue").val('');
                            $("#dept_name").val('');
                            $("#from_date").val('');
                            $("#to_date").val('');


                            // $("select").select2();

                            $("#tbody").html('');

                            window.location.href = '{{ route('admin.bulk-ods.index') }}';
                        }

                        if (response.status == false) {
                            Swal.fire('', 'No Student Selected', 'error');
                        }
                        $("#open").hide();
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
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
                });
            } else {
                Swal.fire('', 'No Students Were Added', 'warning');
            }
        }
    </script>
@endsection
