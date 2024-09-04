@extends('layouts.admin')
@section('content')
    @can('scholarship_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-outline-secondary" href="{{ url('admin/fee-scholarship') }}">
                    Back to List
                </a>
            </div>
        </div>
    @endcan

    <style>
        .select2-container {
            width: 100% !important;
        }

        #loading {
            z-index: 99999;
        }
    </style>


    <div class="card">
        <div class="card-header text-center">
            Assign ScholarShip
        </div>
        <div class="card-body">
            <div class="row gutters">

                <input type="hidden" name="feescholarship_id" id="feescholarship_id">

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 form-group" id="scholarship_div">
                    <label for="schoalrship" class="required">Select Scholarship</label>
                    <select name="schoalrship" id="scholarship" class="form-control select2" style="font-size: 18px;"
                        onchange="getscholarship()">
                        <option value="">Select Scholarship</option>
                        @foreach ($getScholarship as $id => $scholarship)
                            <option value="{{ $id }}">{{ $scholarship }}</option>
                        @endforeach
                    </select>
                    <span id="schoalrship_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 form-group" id="amt_per_edit_box"
                    style="display: none;">
                    <label for="amt_per_edit" class="required">Scholarship Details</label>
                    <input type="text" id="amt_per_edit" class="form-control">
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 form-group" id="amt_percentage_box"
                    style="display: none;">
                    <label for="amt_percentage" class="required">Scholarship Details</label>
                    <input type="text" id="amt_percentage" class="form-control" readonly>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 form-group" id="filteration_div">
                    <label for="filteration" class="required">Select Type</label>
                    <select name="filteration" id="filteration" class="form-control select2" style="font-size: 18px;"
                        onchange="test()">
                        <option value="">Select Type</option>
                        <option value="for_all">For All Students</option>
                        <option value="department_wise">Department Wise</option>
                    </select>
                    <span id="filteration_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 form-group" id="applied_batch_div">
                    <label for="result" class="required">Applicable Batch</label>
                    <select class="form-control select2" id="applied_batch" name="applied_batch">
                        <option value="">Select Batch</option>
                        @foreach ($batch as $id => $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                    <span id="applied_batch_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 form-group" id="course_div">
                    <label for="result" class="required">Course</label>
                    <input type="hidden" id="feeStructure_id" value="">
                    <select class="form-control select2" style="text-transform:uppercase" id="course" name="course"
                        value="">
                        <option value="">Select Course</option>
                        @foreach ($course as $id => $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                    <span id="course_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 form-group" id="batch_filter_std_div">
                    <label for="feeBatch" class="required">Select Students</label>
                    <select class="form-control select2" id="batch_filter_std" name="batch_filter_std" multiple>
                        <option value="">Select Students</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->register_no }}">{{ $student->name }} ({{ $student->register_no }})
                            </option>
                        @endforeach
                    </select>
                    <span id="batch_filter_std_span" class="text-danger text-center"
                        style="display:none; font-size:0.9rem;"></span>
                </div>

            </div>

            <div id="save_div" class="text-center">
                <button type="button" id="save_btn" class="btn btn-outline-success"
                    onclick="saveScholarship()">Save</button>
                <div id="loading_div" style="display:none;">
                </div>

            </div>
        </div>
        <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    @endsection
    @section('scripts')
        <script>
            $("#applied_batch_div").hide();
            $("#course_div").hide();
            $("#batch_filter_std_div").hide();
            $('#save_btn').hide();

            function test() {

                var filteration = $("#filteration").val();

                if (filteration == 'for_all') {

                    $("#save_btn").show();
                    $("#applied_batch_div").hide();
                    $("#course_div").hide();
                    $("#scholarship_div").show();
                    $("#batch_filter_std_div").show();


                } else if (filteration == 'department_wise') {

                    $('#batch_filter_std').empty();
                    $("#applied_batch_div").show();
                    $("#course_div").show();
                    $("#save_btn").show();
                    $("#scholarship_div").show();
                    $("#batch_filter_std_div").show();

                } else {
                    $("#save_btn").hide();
                    $("#applied_batch_div").hide();
                    $("#course_div").hide();
                    $("#batch_filter_std_div").hide();

                }

            }

            function getscholarship() {

                $('#loading').show();

                var scholarship = $("#scholarship").val();

                $.ajax({
                    url: '{{ route('admin.fee-scholarship.getter') }}',
                    type: 'POST',
                    data: {
                        'scholarship': scholarship
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        let status = response.status;
                        if (status) {
                            $("#amt_percentage_box").show();
                            $("#amt_percentage").val(response.value);

                        } else {
                            Swal.fire('', response.data, 'error');
                            $("#amt_percentage_box").hide();
                        }

                        $('#loading').hide();


                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        let errorMessage = textStatus || errorThrown || 'Request Failed';
                        Swal.fire('', errorMessage, 'error');

                        $('#loading').hide();
                        $("#amt_percentage_box").hide();

                    }
                });
            }

            $('#applied_batch, #course').on('change', function() {

                var batch = $('#applied_batch').val();
                var course = $('#course').val();

                if (batch !== '' && course !== '') {
                    initiateFunction(batch, course);
                } else {
                    console.log("Please select both Batch and Course.");
                }

            })

            function initiateFunction(batch, course) {

                var batch = $('#applied_batch').val();
                var course = $('#course').val();

                $('#loading').show();

                $.ajax({

                    url: '{{ route('admin.fee-scholarship.filter_student') }}',
                    type: 'POST',
                    data: {
                        'batch': batch,
                        'course': course
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        let status = response.status;
                        if (status) {
                            if (status == true) {
                                console.log(response);
                                let students = response.data;
                                $('#batch_filter_std').empty();
                                $('#batch_filter_std').append('<option value="">Select Students</option>');

                                $.each(students, function(register_number, name) {
                                    $('#batch_filter_std').append(
                                        `<option value="${register_number}">${name} (${register_number})</option>`
                                    );
                                });

                                $('#batch_filter_std').trigger('change.select2');

                            } else {
                                Swal.fire('', response.data, 'error');
                            }

                        } else {
                            Swal.fire('', response.data, 'error');

                        }
                        $('#loading').hide();


                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        let errorMessage = textStatus || errorThrown || 'Request Failed';
                        Swal.fire('', errorMessage, 'error');

                        $('#loading').hide();

                    }

                })


            }

            function saveScholarship() {


                $("#loading_div").hide();
                $("#schoalrship_span").hide();
                $("#student_span").hide();

                if ($("#scholarship").val() == '') {
                    $("#schoalrship_span").html('Scholarship Is Required');
                    $("#schoalrship_span").show();
                    $("#student_span").hide();
                } else if ($("#sutdent").val() == '') {
                    $("#student_span").html('Plese Select Student');
                    $("#schoalrship_span").hide();
                    $("#student_span").show();
                } else {

                    var scholarship = $("#scholarship").val();
                    var student = $("#student").val();
                    var amt_percentage = $("#amt_percentage").val();
                    var id = $("#feescholarship_id").val();
                    var amt_per_edit = $("#amt_per_edit").val();
                    var batch_filter_std = $("#batch_filter_std").val();

                    $('#loading').show();

                    $.ajax({
                        url: '{{ route('admin.fee-scholarship.store') }}',
                        type: 'POST',
                        data: {
                            'id': id,
                            'scholarship': scholarship,
                            'amt_percentage': amt_percentage,
                            'student': student,
                            'amt_per_edit': amt_per_edit,
                            'batch_filter_std': batch_filter_std

                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                            let status = response.status;
                            if (status) {
                                if (status == true) {
                                    Swal.fire('', response.data, 'success');
                                } else {
                                    Swal.fire('', response.data, 'error');
                                }
                                $('#loading').show();


                            } else {
                                Swal.fire('', response.data, 'error');

                            }
                            $('#loading').hide();

                            location.reload();

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            let errorMessage = textStatus || errorThrown || 'Request Failed';
                            Swal.fire('', errorMessage, 'error');

                            $('#loading').hide();

                        }
                    });

                }

            }
        </script>
    @endsection
