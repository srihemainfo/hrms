@extends('layouts.admin')
@section('content')
    <style>
        .select2 {
            min-width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Create Subject Allotment For Semester
        </div>

        <div class="card-body">
            <div class="row gutters">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="regulation_id" class="required">Regulation</label>
                        <select class="form-control select2" name="regulation_id" id="regulation_id">
                            @foreach ($regulation as $id => $entry)
                                <option value="{{ $id }}" {{ old('regulation_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="department_id" class="required">Department</label>
                        <input type="hidden" name="degree_type" id="degree_type" value="">
                        <select class="form-control select2" name="department_id" id="department_id"
                            onchange="check_dept(this)">
                            @foreach ($department as $id => $entry)
                                <option value="{{ $id }}" {{ old('department_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="course_id" class="required">Course</label>
                        <select class="form-control select2" name="course_id" id="course_id" onchange="check_course(this)">
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="academic_year" class="required">Academic Year</label>
                        <select class="form-control select2" name="academic_year" id="academic_year">
                            @foreach ($academic_years as $id => $entry)
                                <option value="{{ $id }}" {{ old('academic_year') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="semester_type" class="required">Semester Type</label>
                        <select class="form-control select2" name="semester_type" id="semester_type">
                            <option value="">Select Semester Type</option>
                            <option value="ODD">ODD</option>
                            <option value="EVEN">EVEN</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="semester_id" class="required">Semester</label>
                        <select class="form-control select2" name="semester_id" id="semester_id"
                            onchange="check_sem_type(this)">

                        </select>
                    </div>
                </div>
            </div>


            <div class="form-group" style="text-align:right;">
                <button class="btn btn-outline-success" type="button" onclick="open_form()">
                    Create Subject Allotment
                </button>
            </div>
        </div>
    </div>
    <div class="card" id="open_form" style="display:none;">
        <div class="card-header text-center">Semester Wise Subjects Allotment</div>
        <div class="card-body">
            <form action="" id="regular_form">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Regular Subjects</div>
                            <div style="width:30%;text-align:center;">
                                <div style="right:0;background-color:gray;" class="manual_bn">All Subjects Are Mandatory
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="regular-table">

                            </tbody>
                        </table>
                        <div style="text-align:right;padding-top:15px;">
                            <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                id="regular" onclick="get_subjects(this)">
                            </i>
                        </div>
                    </div>
                </div>
            </form>
            <form action="" id="professional_form" class="common" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Professional Electives</div>
                            <div style="width:20%;">
                                <select class="form-control select2" name="professional_limit" id="professional_limit">
                                    <option value="">Select Limit</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="professional-table">

                            </tbody>
                        </table>
                        <div style="text-align:right;padding-top:15px;">
                            <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                id="professional" onclick="get_subjects(this)">
                            </i>
                        </div>
                    </div>
                </div>
            </form>
            <form action="" id="open_elec_form" class="common" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Open Electives</div>
                            <div style="width:20%;">
                                <select class="form-control select2" name="open_limit" id="open_limit">
                                    <option value="">Select Limit</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="open-table">

                            </tbody>
                        </table>
                        <div style="text-align:right;padding-top:15px;">
                            <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                id="open" onclick="get_subjects(this)">
                            </i>
                        </div>
                    </div>
                </div>
            </form>
            <form action="" id="others_form" class="common" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Others</div>
                            <div style="width:20%;">
                                <select class="form-control select2" name="others_limit" id="others_limit">
                                    <option value="">Select Limit</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="others-table">

                            </tbody>
                        </table>
                        <div style="text-align:right;padding-top:15px;">
                            <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                id="others" onclick="get_subjects(this)">
                            </i>
                        </div>
                    </div>
                </div>
            </form>

            <form action="" id="pg_professional_form" class="mba" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Electives Human Resource</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="pg-professional-table">

                            </tbody>
                        </table>
                        <div style="text-align:right;padding-top:15px;">
                            <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                id="pg-professional" onclick="get_subjects(this)">
                            </i>
                        </div>
                    </div>
                </div>
            </form>
            <form action="" id="pg_open_elec_form" class="mba" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Electives Finance</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="pg-open-table">

                            </tbody>
                        </table>
                        <div style="text-align:right;padding-top:15px;">
                            <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                id="pg-open" onclick="get_subjects(this)">
                            </i>
                        </div>
                    </div>
                </div>
            </form>
            <form action="" id="pg_others_form" class="mba" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Elective Operation</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="pg-others-table">

                            </tbody>
                        </table>
                        <div style="text-align:right;padding-top:15px;">
                            <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                id="pg-others" onclick="get_subjects(this)">
                            </i>
                        </div>
                    </div>
                </div>
            </form>
            <form action="" id="pg_logistics_form" class="mba" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Elective Logistics</div>

                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="pg-logistics-table">

                            </tbody>
                        </table>
                        <div style="text-align:right;padding-top:15px;">
                            <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                id="pg-logistics" onclick="get_subjects(this)">
                            </i>
                        </div>
                    </div>
                </div>
            </form>


            <div style="text-align:right;">
                <button class="enroll_generate_bn" onclick="submit()">Submit</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary">Select Subject</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12"></div>
                        <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-12">
                            <div class="form-group">
                                <label for="subject" class="required">Subject</label>
                                <select class="form-control select2" name="subject" id="subject">

                                </select>
                                <input type="hidden" id="decider" name="decider" value="">
                            </div>
                        </div>
                        <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" onclick="save()">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            $("#regulation_id").select2();
            $("#department_id").select2();
            $("#course_id").select2();
            $("#semester_id").select2();
            $("#semester_type").select2();
            $("#academic_year").select2();
        }

        function check_dept(element) {
            if (element.value != '') {
                let dept = element.value;

                $.ajax({
                    url: '{{ route('admin.subjects.get_course') }}',
                    type: 'POST',
                    data: {
                        'dept': dept
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response.course != '') {
                            let course = response.course;
                            let course_len = course.length;

                            let got_course = `<option value="">Select Course</option>`;
                            for (let a = 0; a < course_len; a++) {
                                got_course +=
                                    `<option value="${course[a].id}">${course[a].name}</option>`;
                            }
                            $('#degree_type').val(course[0].degree_type_id)
                            $("#course_id").html(got_course);
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




            }
        }

        let subject_array = [];

        function check_course(element) {
            $("#open_form").hide();
            if (element.value != '') {
                // console.log(element.value, );
                let course = element.value;
                let dept = $("#department_id").val();
                let degree_type = $('#degree_type').val();
                let semester = '';
                if (dept == '8' && degree_type == 1) {
                    $("#semester_id").empty()
                    let semester = `
                            @foreach ($semester as $id => $entry)
                            @if ($id < 3)
                            <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                                @endif
                                @endforeach`;
                    $("#semester_id").html(semester);
                } else if (degree_type == 2 && dept != '8') {
                    $("#semester_id").empty()
                    let semester = `
                        @foreach ($semester as $id => $entry)
                        @if ($id < 5)
                        <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>
                            {{ $entry }}</option>
                            @endif
                            @endforeach`;
                    $("#semester_id").html(semester);

                } else if (degree_type == 1 && dept != '8') {
                    $("#semester_id").empty()
                    let semester = `
                    @foreach ($semester as $id => $entry)
                    @if ($id > 2)
                    <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>
                        {{ $entry }}</option>
                        @endif
                        @endforeach`;
                    $("#semester_id").html(semester);
                }
            }
        }

        function check_sem_type(element) {
            $("#open_form").hide();
            if (element.value != '') {
                let get_sem = $("#semester_type").val();
                if (get_sem == '') {
                    Swal.fire('', 'Please Select the Semester Type..', 'error');

                    element.value = '';
                    $("#semester_id").select2();
                }
                if (get_sem == 'ODD') {
                    if (element.value != 1 && element.value != 3 && element.value != 5 && element.value != 7) {
                        Swal.fire('', 'Choosen Semester Not Valid..', 'error');

                        element.value = '';
                        $("#semester_id").select2();
                    }
                }
                if (get_sem == 'EVEN') {
                    if (element.value != 2 && element.value != 4 && element.value != 6 && element.value != 8) {
                        Swal.fire('', 'Choosen Semester Not Valid..', 'error');

                        element.value = '';
                        $("#semester_id").select2();
                    }
                }
            }
        }

        function open_form() {
            $("#open_form").hide();
            let reg = $("#regulation_id").val();
            let dept = $("#department_id").val();
            let course = $("#course_id").val();
            let sem = $("#semester_id").val();
            let sem_type = $("#semester_type").val();
            let ay = $("#academic_year").val();
            if (reg == '') {
                Swal.fire('', 'Please Select Department', 'error');

                return false;
            }
            if (dept == '') {
                Swal.fire('', 'Please Select Department', 'error');

                return false;
            }
            if (course == '') {
                Swal.fire('', 'Please Select Course', 'error');

                return false;
            }
            if (ay == '') {
                Swal.fire('', 'Please Select Academic Year', 'error');

                return false;
            }
            if (sem_type == '') {
                Swal.fire('', 'Please Select Semester Type', 'error');

                return false;
            }
            if (sem == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            }

            if (reg != '' && dept != '' && course != '' && ay != '' && sem_type != '' && sem != '') {

                let inputs = {
                    'reg': reg,
                    'dept': dept,
                    'course': course,
                    'ay': ay,
                    'sem': sem,
                    'sem_type': sem_type
                };
                $.ajax({
                    url: '{{ route('admin.subject-allotment.check') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: inputs,
                    success: function(response) {
                        if (response.status == false) {
                            $("#open_form").fadeIn();
                            if ($('#course_id').val() == '13' || $('#course_id').val() == 13) {
                                $('.common').hide()
                                $('.mba').show()
                            } else {
                                $('.mba').hide()
                                $('.common').show()
                            }
                        } else {
                            Swal.fire('', 'Already Subjects Alloted', 'error');
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

            }
        }

        function get_subjects(element) {
            // console.log(element.id);
            let id = element.id;

            let reg = $("#regulation_id").val();
            let dept = $("#department_id").val();
            let course = $("#course_id").val();
            let sem = $("#semester_id").val();
            let sem_type = $("#semester_type").val();
            let ay = $("#academic_year").val();

            let inputs = {
                'reg': reg,
                'dept': dept,
                'course': course,
                'sem': sem
            };

            if (id != '') {
                $.ajax({
                    url: '{{ route('admin.subject-allotment.get_subjects') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: inputs,
                    success: function(response) {
                        // console.log(response);
                        if (response.subjects) {
                            let data = response.subjects;
                            let len = data.length;
                            let subjects = '';
                            if (len > 0) {
                                subject_array = data;
                                subjects = `<option value =''>Select Subject</option>`;
                                for (let i = 0; i < len; i++) {
                                    subjects +=
                                        `<option value ='${data[i].id}'>${data[i].subject_code}` + '   ' +
                                        `(${data[i].name})</option>`;
                                }
                            }


                            $("#decider").val(id);
                            $("#subject").html(subjects);
                            // $("#subject").select2();
                            $('#exampleModal').modal("show");
                            // console.log(len)
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
            }
        }

        function save() {

            let array_len = subject_array.length;
            let got_subject = $("#subject").val();
            let new_subject = '';
            let selector = $("#decider").val() + '_limit';
            let parent_table = $("#decider").val() + '-table';
            var added_subjects = $("#" + parent_table).children().length;
            // console.log($("#" + parent_table).children().length);
            if (got_subject != '') {
                for (let a = 0; a < array_len; a++) {
                    if (got_subject == subject_array[a].id) {
                        new_subject = subject_array[a];
                    }
                }
            }
            if (new_subject != '') {
                let sub_type = null;
                if (new_subject.subject_type.name) {
                    sub_type = new_subject.subject_type.name;
                }
                let new_datas =
                    `<tr><td>${added_subjects + 1}</td><td><input type="hidden" name="${parent_table}${added_subjects + 1}" value="${new_subject.id}">${new_subject.subject_code}</td><td>${new_subject.name}</td><td>${sub_type}</td><td>${new_subject.credits}</td></tr>`;

                let option = `<option value="${added_subjects + 1}">${added_subjects + 1}</option>`;

                $("#" + parent_table).append(new_datas);
                console.log(selector);
                console.log(option);
                $("#" + selector).append(option);
            }
            $('#exampleModal').modal("hide");
        }

        function submit() {
            let form_1 = '';
            let form_2 = '';
            let form_3 = '';
            let form_4 = '';
            let form_5 = '';
            if ($('#course_id').val() == 13 || $('#course_id').val() == '13') {
                form_1 = $("#regular_form").serializeArray();
                form_2 = $("#pg_professional_form").serializeArray();
                console.log(form_2);
                form_3 = $("#pg_open_elec_form").serializeArray();
                console.log(form_3);
                form_4 = $("#pg_others_form").serializeArray();
                console.log(form_4);
                form_5 = $("#pg_logistics_form").serializeArray();
                console.log(form_5);
            } else {
                console.log($('#department_id').val());
                form_1 = $("#regular_form").serializeArray();
                form_2 = $("#professional_form").serializeArray();
                form_3 = $("#open_elec_form").serializeArray();
                form_4 = $("#others_form").serializeArray();
                form_5 = '';
            }

            let reg = $("#regulation_id").val();
            let dept = $("#department_id").val();
            let course = $("#course_id").val();
            let sem = $("#semester_id").val();
            let sem_type = $("#semester_type").val();
            let ay = $("#academic_year").val();

            let inputs = {
                'reg': reg,
                'dept': dept,
                'course': course,
                'sem': sem,
                'sem_type': sem_type,
                'ay': ay
            };
            // let data = {'regular':form_1,'professional':form_2,'open':form_3,'others':form_4,'inputs':inputs};
            $.ajax({
                url: '{{ route('admin.subject-allotment.store') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'regular': form_1,
                    'professional': form_2,
                    'open': form_3,
                    'others': form_4,
                    'logistics': form_5,
                    'inputs': inputs
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire('', 'Subject Allotments Saved', 'success');

                        location.reload();
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

        }
    </script>
@endsection
