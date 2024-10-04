@extends('layouts.admin')
@section('content')
<style>
    .select2-container {
        width: 100% !important;

    }
</style>
<div class="card">
    <div class="card-header">
        <b>Internal Mark Report</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class=" col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 form-group">
                <label class=" required" for="regulation">Regulation</label>
                <select class="form-control select2" name="regulation" id="regulation">
                    <option value="">Select Regulation</option>
                    @foreach ($reg as $i => $reg)
                    <option value="{{ $i }}">{{ $reg }}</option>
                    @endforeach
                </select>
            </div>

            <div class=" col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 form-group ">
                <label for="ay" class=" required">Academic Year</label>
                <select class="form-control select2" name="ay" id="ay">
                    <option value="">Select AY</option>
                    @foreach ($ay as $i => $ay)
                    <option value="{{ $i }}">{{ $ay }}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 form-group ">
                <label for="course" class=" required">Course</label>
                <select class="form-control select2" name="course" id="course">
                    <option value="">Select Course</option>
                    @foreach ($course as $i => $course)
                    <option value="{{ $i }}">{{ $course }}</option>
                    @endforeach
                </select>
            </div>

            <div class=" col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 form-group">
                <label for="batch" class="required">Batch</label>
                <select class="form-control select2" name="batch" id="batch">
                    <option value="">Select Batch</option>
                    @foreach ($batch as $i => $batch)
                    <option value="{{ $i }}">{{ $batch }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="row">
            <div class="form-group  col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                <label for="sem" class=" required">Semester</label>
                <select class="form-control select2" name="sem" id="sem">
                    <option value="">Select Semester</option>
                    @foreach ($sem as $i => $sem)
                    <option value="{{ $i }}">{{ $sem }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group  col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                <label for="sub_type">Subject Type</label>
                <select class="form-control select2" name="sub_type" id="sub_type">
                    <option value="">Select Subject Type</option>
                    <option value="THEORY">THEORY</option>
                    <option value="LABORATORY">LABORATORY</option>
                    <option value="PROJECT">PROJECT</option>
                </select>
            </div>

            <div class="form-group  col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                <button class="enroll_generate_bn" style="margin-top:32px" id="submit">Submit</button>
                <button class="enroll_generate_bn bg-warning" style="margin-top:32px" id="reset">Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Batch</th>
                    <th>Subject Type</th>
                    <th>Subject Code</th>
                    <th>Subject Title</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tbody">

            </tbody>
        </table>
    </div>
</div>

@endsection
@section('scripts')

<script>
    $("#reset").click(function() {

        $("#ay").val($("#target option:first").val());
        $("#course").val($("#target option:first").val());
        $("#regulation").val($("#target option:first").val());
        $("#sem").val($("#target option:first").val());
        $("#batch").val($("#target option:first").val());
        $("#sub_type").val($("#target option:first").val());
        $('select').select2();
        let body = $('#tbody')
        body.empty()

    })

    $('#submit').click(function() {

        let sub_type = $('#sub_type').val();
        let reg = $("#regulation").val();
        let course = $('#course').val();
        let ay = $("#ay").val();
        let sem = $('#sem').val();
        let batch = $('#batch').val();

        let body = $('#tbody')
        body.empty()
        let row = $('<tr>')
        row.append(`<td colspan=6>Loading...</td>`)
        body.append(row)
        if ($("#regulation").val() == '') {
            Swal.fire('', 'Enter Regulation', 'warning');
            return false;
        } else if ($("#ay").val() == '') {
            Swal.fire('', 'Enter Academic Year', 'warning');
            return false;
        } else if ($("#course").val() == '') {
            Swal.fire('', 'Enter Course', 'warning');
            return false;
        } else if ($("#batch").val() == '') {
            Swal.fire('', 'Enter Batch', 'warning');
            return false;
        } else if ($("#sem").val() == '') {
            Swal.fire('', 'Enter Semester', 'warning');
            return false;

        } else {

            $.ajax({
                url: "{{route('admin.internalmark_report.report')}}",
                method: 'GET',
                data: {
                    sub_type: $('#sub_type').val(),
                    reg: $("#regulation").val(),
                    ay: $("#ay").val(),
                    course: $("#course").val(),
                    batch: $("#batch").val(),
                    sem: $("#sem").val(),

                },
                success: function(data) {
                    let status = data.status;
                    if (status == true) {
                        let body = $('#tbody')
                        body.empty()

                        $.each(data, function(index, da) {

                            for (i = 0; da.length > i; i++) {
                                // console.log(d)
                                let row = $('<tr>')
                                row.append(`<td>${da[i][4]}</td>`)
                                row.append(`<td>${da[i][3]}</td>`)
                                row.append(`<td>${da[i][2]}</td>`)
                                row.append(`<td>${da[i][0]}</td>`)
                                row.append(`<td>${da[i][1]}</td>`)
                                if (da[i][6] == 1) {
                                    row.append(`<td><a class="btn btn-success btn-xs" target="_blank" href="{{ url('admin/internal-mark-generation/download/')}}` + `/` + reg + `/` + batch + `/` + ay + `/` + course + `/` + sem + `/` + da[i][5] + `/` + da[i][2] + `">Download Excel</a></td>`);

                                } else if (da[i][6] == 0) {
                                    row.append(`<td style="color:red">Not Generated</td>`)
                                }

                                body.append(row)
                            }

                        })
                    } else {
                        $('#tbody').html(`<tr><td colspan="6">No Data Available...</td></tr>`);
                        Swal.fire('', data.data, 'error');
                    }

                }
            })
        }
    })
</script>

@endsection
