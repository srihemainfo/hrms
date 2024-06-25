@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 5) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5 || ($type_id == 6 && $role_id == 9)) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Get Attendance Report
        </div>
        <div class="card-body">
            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <label for="date">Date</label>
                    <input type="text" class="form-control date" name="date" id="date">
                    <span id="date_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <label for="day_type">Day Type</label>
                    <select name="day_type" id="day_type" class="form-control select2">
                        <option value="">Select Day Type</option>
                        <option value="Morning">Morning</option>
                        <option value="Evening">Evening</option>
                    </select>
                    <span id="day_type_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <label for="day_type">Day Type</label>
                    <select name="hostel" id="hostel" class="form-control select2">
                        <option value="">Select Day Type</option>
                        @foreach ($hostel as $k => $item)
                            <option value="{{ $k }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <span id="hostel_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group mt-4">
                    <div id="save_div" style="margin-top: 8px;">
                        <button type="button" id="save_btn" class="enroll_generate_bn">Get Report</button>
                    </div>
                    {{-- <div id="loading_div">
                        <span class="theLoader"></span>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="secondLoader"></div>
    </div>
    <div class="card">
        <div class="card-header">
            Attendance Report
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>
                            SNO
                        </th>
                        <th>
                            Hostel
                        </th>
                        <th>
                            Room No
                        </th>
                        <th>
                            Student Name
                        </th>
                        <th>
                            Attendance
                        </th>
                    </tr>
                </thead>

                <tbody id="tbody">
                    <tr>
                        <td colspan="5">No Data</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $('#save_btn').click(function() {
            if ($('#date').val() == '') {
                $("#date_span").html(`Student Is Required.`);
                $("#date_span").show();
                $("#day_type_span").hide();
                $("#hostel_span").hide();

            } else if ($('#day_type').val() == '') {
                $("#day_type_span").html(`Day Type Is Required.`);
                $("#day_type_span").show();
                $("#date_span").hide();
                $("#hostel_span").hide();
            } else if ($('#hostel').val() == '') {
                $("#hostel_span").html(`Hostel Is Required.`);
                $("#hostel_span").show();
                $("#date_span").hide();
                $("#day_type_span").hide();
            } else {

                $('#secondLoader').show()
                body = $('#tbody').empty()
                body.append(`<tr><td colspan="5">Searching...</td></tr>`)
                $.ajax({
                    url: "{{ route('admin.hostel-attendance.get_report') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'day': $('#day_type').val(),
                        'date': $('#date').val(),
                        'hostel_id': $('#hostel').val()
                    },
                    success: function(response) {
                        let status = response.status
                        let data = response.data
                        if (status == true) {
                            $('#secondLoader').hide()
                            body = $('#tbody').empty()
                            console.log(data.length);
                            if (data.length > 0) {
                                $.each(data, function(index, value) {
                                    let row = $('<tr>')
                                    row.append(`<td>${index+=1}</td>`)
                                    row.append(`<td>${value.hostel_name}</td>`)
                                    row.append(`<td>${value.room_no}</td>`)
                                    row.append(
                                        `<td>${value.name+'('+value.register_no+')'}</td>`)
                                    row.append(`<td>${value.attendance}</td>`)
                                    body.append(row)
                                })
                            } else {
                                body = $('#tbody').empty()
                                body.append(`<tr><td colspan="5">No Data Available...</td></tr>`)
                            }

                        } else {
                            Swal.fire('', data, 'error')
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
        })
    </script>
@endsection
