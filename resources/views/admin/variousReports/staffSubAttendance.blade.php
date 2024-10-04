@extends('layouts.teachingStaffHome')
@section('content')
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
    <div class="card">
        <div class="card-header">
            <div class="text-center">Subject Attendance Report</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 col-sm-7 col-12">
                    <div class="form-group">
                        <label for="section" class="required">Subject</label>
                        <select class="form-control select2" name="subject" id="subject">
                            <option value="">Select Subject</option>
                            @foreach ($gotSubjects as $subjec)
                                @php
                                    $subject = $subjec->subjects ? $subjec->subjects->name . '  (' . $subjec->subjects->subject_code . ') ' : $subjec->subject;
                                @endphp
                                <option value="{{ $subjec->subject . '/' . $subjec->class_name }}">{{ $subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1 col-sm-2 col-12" style="text-align:right;">
                    <div class="form-group" style="padding-top: 32px;">
                        <button type="button"class="enroll_generate_bn" onclick="submit()">Go</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="report" style="display:none;max-width:100%;overflow-x:auto;z-index:0;">
        <div class="card-body" style="min-width:1100px;">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Register No</th>
                        <th>Student Name</th>
                        <th>No Of Periods Attend</th>
                        <th>Total No Of Periods</th>
                        <th>Attendance Percentage</th>
                        <th>View Attendance</th>
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
        window.onload = function() {
            let user_name_id = '';
            let main_class = '';
        }

        function submit() {

            let subject = $("#subject").val();

            if (subject == '') {
                Swal.fire('', 'Please Choose the Subject', 'warning');
            } else {
                rows = `<tr><td colspan="7">Loading...</td></tr>`;
                $("#tbody").html(rows);
                $("#report").show();
                $.ajax({
                    url: '{{ route('admin.subject-attendance-report.get_subject_report') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'subject': subject,
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        let data_len = data.length;
                        let rows = '';
                        let percentage;
                        let getSubject = subject.split('/');
                        let theSubject = getSubject[0];

                        if (status == true) {
                            if (data_len > 0) {
                                for (let k = 0; k < data_len; k++) {
                                    user_name_id = data[k]['user_name_id'];
                                    main_class = data[k]['class'];
                                    if (data[k]['percentage'] > 74) {
                                        percentage = `<td style="">${data[k]['percentage']}</td>`;
                                    } else {
                                        percentage =
                                            `<td style="background-color:#ffccc7;color:black;">${data[k]['percentage']}</td>`;
                                    }
                                    if (data[k]['registration'] == true) {

                                        rows += `<tr>
                                            <td>${k + 1}</td>
                                            <td>${data[k]['register_no']}</td>
                                            <td>${data[k]['name']}</td>
                                            <td>${data[k]['attend_hours']}</td>
                                            <td>${data[k]['total_hours']}</td>
                                           ${percentage}
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="{{ url('admin/subject-attendance-report/show/${user_name_id}/${main_class}/${theSubject}') }}" target="_blank">
                                                View
                                                </a>
                                             </td>
                                        </tr>`;
                                    } else {
                                        rows += `<tr>
                                            <td>${k + 1}</td>
                                            <td>${data[k]['register_no']}</td>
                                            <td>${data[k]['name']}</td>
                                            <td colspan="3">Not Registered</td>
                                            <td>
                                                <a class="btn btn-xs btn-primary" href="{{ url('admin/subject-attendance-report/show/${user_name_id}/${main_class}/${theSubject}') }}" target="_blank">
                                                View
                                                </a>
                                            </td>
                                        </tr>`;
                                    }
                                }
                            } else {
                                rows = `<tr><td colspan="7">No Data Available...</td></tr>`;
                            }
                        } else {
                            Swal.fire('', response.data, 'warning');
                            rows = `<tr><td colspan="7">No Data Available...</td></tr>`;
                        }
                        $("#tbody").html(rows);

                    }
                })
            }
        }
    </script>
@endsection
