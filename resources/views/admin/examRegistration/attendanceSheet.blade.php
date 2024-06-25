@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Attendance Sheet Download
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
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
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
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
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                    <div class="form-group">
                        <label class="required" for="">Course</label>
                        <select name="course" id="course" class="form-control select2" onchange="">
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <div class="form-group">
                        <label class="required" for=""> Current Semester</label>
                        <select name="semester" id="semester" class="form-control select2">
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
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <div class="form-group">
                        <label class="required" for="">Exam Type</label>
                        <select name="exam_type" id="exam_type" class="form-control select2" onchange="checkExamType()">
                            <option value="">Select Exam Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Arrear">Arrear</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12" id='arrear_semester' style='display:none'>
                    <div class="form-group">
                        <label class="required">Subject Semester</label>
                        <select name="subject_sem" id="subject_sem" class="form-control select2" onchange="checkData()">
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

                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                    <div class="form-group">
                        <label for="">Subject</label>
                        <select name="subject" id="subject" class="form-control select2">

                        </select>
                    </div>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12" id="reset_div1">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="download()">Generate</button>
                            <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                </div>
                <div class="form-group col-md-12 col-sm-8 col-12" id="reset_div2" style="display:none;">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-warning" onclick="reset()"  style="margin-top:32px;">Reset</button>
                        <button class="enroll_generate_bn bg-success" onclick="download()"  style="margin-top:32px;">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function reset() {
            $("#batch").val($("#target option:first").val());
            $("#ay").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#semester").val($("#target option:first").val());
            $("#exam_type").val($("#target option:first").val());
            $("#subject").val($("#target option:first").val());
            $("#subject_sem").val($("#target option:first").val());
            $('select').select2();
            $('#subject').html('');
        }

        function checkExamType() {
            if ($("#exam_type").val() == '') {
                Swal.fire('', 'Please Select Exam Type', 'error');
                return false;
                $("#arrear_semester").hide();
                $("#reset_div1").show();
                $("#reset_div2").hide();
                $('#subject').html('');
            } else if ($("#exam_type").val() == 'Regular') {
                $("#arrear_semester").hide();
                $("#reset_div1").show();
                $("#reset_div2").hide();
                $("#subject_sem").val($("#target option:first").val());
                $("#subject_sem").select2();
                $('#subject').html('');
                getSubjects(false);
            } else if ($("#exam_type").val() == 'Arrear') {
                $("#subject_sem").val($("#target option:first").val());
                $("#subject_sem").select2();
                $('#subject').html('');
                $("#arrear_semester").show();
                $("#reset_div1").hide();
                $("#reset_div2").show();
            }
        }

        function checkData() {
            if ($("#subject_sem").val() == '') {
                Swal.fire('', 'Please Select Subject Semester', 'error');
                return false;
            } else {
                getSubjects(true);
            }
        }

        function getSubjects(data) {
            console.log(data)
            if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select Ay', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else if ($("#exam_type").val() == '') {
                Swal.fire('', 'Please Select Exam Type', 'error');
                return false;
            } else {
                let batch = $("#batch").val();
                let ay = $("#ay").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let exam_type = $("#exam_type").val();
                let subject_sem = 'All';
                if (data === true) {
                    subject_sem = $("#subject_sem").val();
                }
                $.ajax({
                    url: '{{ route('admin.exam-registrations-subjectwise.getSubjects') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'batch': batch,
                        'ay': ay,
                        'course': course,
                        'semester': semester,
                        'exam_type': exam_type,
                        'subject_sem': subject_sem,
                    },
                    success: function(response) {

                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            let data_len = data.length;
                            let subjects = '';
                            if (data_len > 0) {
                                subjects += `<option value=""> Select Subject</option>`;
                                for (let i = 0; i < data_len; i++) {
                                    subjects +=
                                        `<option value="${data[i].subject_id}">${data[i].subject_name}  (${data[i].subject.subject_code})</option>`;
                                }
                            }
                            $("#subject").html(subjects);
                            $("#subject").select2();

                        } else {
                            Swal.fire('', data, 'error');
                        }
                    }
                })
            }
        }

        function download() {
            if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select Ay', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else if ($("#exam_type").val() == '') {
                Swal.fire('', 'Please Select Exam Type', 'error');
                return false;
            } else {
                let batch = $("#batch").val();
                let ay = $("#ay").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let exam_type = $("#exam_type").val();
                let subject = $("#subject").val();
                if (subject == '') {
                    subject = 'All';
                }
                let subject_sem = $("#subject_sem").val();
                if (subject_sem == '') {
                    subject_sem = 'All';
                }
                let url =
                    `/admin/exam-registrations-subjectwise/download/${batch}/${ay}/${course}/${semester}/${exam_type}/${subject}/${subject_sem}`;
                window.open(url, '_blank');
                // window.location.href = url;
            }

        }
    </script>
@endsection
