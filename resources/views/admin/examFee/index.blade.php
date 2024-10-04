@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Exam Fees
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Academic Year</label>
                        <select name="ay" id="ay" class="form-control select2">
                            <option value="">Select AY</option>
                            @foreach ($ays as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Month & Year Of Exam</label>
                        <select name="exam_month_year" id="exam_month_year" class="form-control select2">
                            <option value="">Select Month & Year Of Exam</option>
                            @foreach ($exam_month_year as $data)
                                <option value="{{ $data->exam_month . '|' . $data->exam_year }}">
                                    {{ $data->exam_month . ' ' . $data->exam_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Course</label>
                        <select name="course" id="course" class="form-control select2">
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Batch</label>
                        <select name="batch" id="batch" class="form-control select2">
                            <option value="">Select Batch</option>
                            @foreach ($batches as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Semester</label>
                        <select name="semester" id="semester" class="form-control select2" onchange="checkFields()">
                            <option value="">Select Semester</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Register No</label>
                        <select name="user_name_id" id="user_name_id" class="form-control select2"> </select>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="preview()">Generate</button>
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function reset() {
            $("#ay").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#batch").val($("#target option:first").val());
            $("#regulation").val($("#target option:first").val());
            $("#semester").val($("#target option:first").val());
            $("#exam_month_year").val($("#target option:first").val());
            $("#user_name_id").val($("#target option:first").val());
            $('select').select2();
            $('#user_name_id').html('');
        }
        let theData;

        function checkFields() {
            if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#exam_month_year").val() == '') {
                Swal.fire('', 'Please Select Exam Month & Year', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                $("#semester").val('');
                $("#semester").select2();
                return false;
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else {
                $("#user_name_id").html(`<option value="">Loading...</option>`);
                let ay = $("#ay").val();
                let batch = $("#batch").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                $.ajax({
                    url: '{{ route('admin.exam-registrations.getStudents') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'ay': ay,
                        'batch': batch,
                        'course': course,
                        'semester': semester,
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;

                        if (status == true) {
                            let data_len = data.length;
                            let options =
                                `<option value=""> Select Register No</option><option value="All">All Students</option>`;
                            for (let i = 0; i < data_len; i++) {
                                for (let j = 0; j < data[i].length; j++) {
                                    options +=
                                        `<option value="${data[i][j].user_name_id}">${data[i][j].name} (${data[i][j].register_no})</option>`;
                                }
                            }
                            $("#user_name_id").html(options);
                        } else {
                            $("#user_name_id").html('');
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
        }

        function preview() {
            if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#exam_month_year").val() == '') {
                Swal.fire('', 'Please Select Exam Month & Year', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else if ($("#user_name_id").val() == '') {
                Swal.fire('', 'Please Select Register No', 'error');
                return false;
            } else {

                let ay = $("#ay").val();
                let batch = $("#batch").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let user_name_id = $("#user_name_id").val();
                let exam_month_year = $("#exam_month_year").val();
                let split = exam_month_year.split('|');
                let exam_month = split[0];
                let exam_year = split[1];

                // window.location.href = ;
                window.open("{{ url('admin/exam-fee/generate') }}" + '/' + ay + '/' + batch + '/' + course + '/' +
                    semester + '/' + user_name_id + '/' + exam_month + '/' + exam_year, "_blank");
            }
        }
    </script>
@endsection
