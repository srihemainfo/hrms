@extends('layouts.teachingStaffHome')
@section('content')

<style>
    .select2-container {
        width: 100% !important;
    }

    .select-checkbox:before {
        content: none !important;
    }
</style>

<div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
<div class="card">
    <div class="card-header text-uppercase text-center"><strong> Day Subjects Attendance Summary.</strong>
    </div>


    <div class="card-body">
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="section" class="required">Class</label>
                    <select class="form-control select2" name="classes" id="classes">
                        <option value="">Select Class</option>
                        @foreach($subjects as $id => $sub)
                        <option value="{{$id}}">{{$sub}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="date" class="required">Date</label>
                    <input type="text"class="form-control date" id="search_date" name="search_date">
                </div>
            </div>

            <div class="col-sm-1 col-12 text-right">
                <div style='padding-top:32px;'>
                    <button class="btn btn-primary " id='submit' onclick="submit()" type="button">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card" id="lister" style="display:none;">
    <div class="card-header text-center text-capitalize"> <strong> Day Subjects Attendance Summary Details </strong>
    </div>
    <div class="card-body" style="max-width:100%;overflow-x:auto;">
        <table id="data_table" class="table table-bordered table-striped table-hover ajaxTable datatable datatable-AttendanceSummary text-center" style="min-width:700px;">
            <thead>
                <tr>
                    <th>Alloted Period</th>
                    <th>Taken Period</th>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Staff Name</th>
                    <th>No Of Students</th>
                    <th>Class / Batch Strength</th>
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

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;

        $("#search_date").val(today);

    }

    function submit() {

        var $date = $('#search_date').val();
        var $class = $('#classes').val();
        var $class_name = $('#classes option:selected').text();
        var $user_name = $('#user_id').val();

        if ($date != '') {
            $('#loading').show();

            let loading = `<tr><td colspan="7"> Loading...</td></tr>`;
            $("#tbody").html(loading);
            $("#lister").show();

            $.ajax({
                url: "{{ route('admin.staff_summary_student_attendance_summary.get_data')}}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'date': $date,
                    'class': $class,
                    'class_name': $class_name,

                },
                success: function(response) {

                    let data = response.status;
                    let check_data_type = typeof data;
                    let status, subject_code;
                    let rows = '';
                    if (check_data_type == 'string') {
                        Swal.fire('', data, 'warning');

                        rows += `<tr><td colspan="7"> No Data Available...</td></tr>`;
                        $('#loading').hide();
                    } else {
                        let data_len = data.length;

                        for (let a = 0; a < data_len; a++) {

                            let checky = typeof data[a]['status'];
                            if (checky == 'string') {

                                status = '<td style="background-color:#fff88f;color:black;">' + data[a][
                                    'status'
                                ] + '</td>';

                            } else {
                                if (data[a]['status'] === false) {
                                    status =
                                        '<td style="background-color:#ffccc7;color:black;">Not Yet Taken</td>';
                                } else if (data[a]['status'] === true) {
                                    status = `<td>${data[a]['attend_students']}</td>`;
                                }
                            }
                            if (data[a]['subject_code'] == null) {
                                subject_code = '';
                            } else {
                                subject_code = data[a]['subject_code'];
                            }

                            rows += `<tr>
                   <td>${data[a]['alloted_periods']}</td>
                   <td>${data[a]['period']}</td>
                   <td>${subject_code}</td>
                   <td>${data[a]['subject_name']}</td>
                   <td>${data[a]['staff_name']}  (${data[a]['staff_code']})</td>
                   ${status}
                   <td>${data[a]['total_students']}</td>
                </tr>`;
                        }

                    }
                    $("#tbody").html(rows);
                    $('.date-field').val('');
                    $('#loading').hide();

                }
            });
        }


    }
</script>
@endsection
