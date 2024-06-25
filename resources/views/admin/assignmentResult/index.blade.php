@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <div class="card" style="max-width:100%;overflow-x:auto;">
        <div class="card-header" style="min-width:700px;">
            <p class="text-center"><strong>Assignment Subjects</strong></p>
        </div>
        <div class="card-body" style="min-width:700px;">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        {{-- <th>Exam Name</th> --}}
                        <th>Class Name</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($response) > 0)
                        @foreach ($response as $id => $data)
                            {{-- {{ dd($data) }} --}}
                            <tr>
                                <td>{{ $id + 1 }}</td>
                                {{-- <td>
                                {{ $data->examename }}
                            </td> --}}
                                <td>
                                    {{ $data->classname }}
                                </td>
                                <td>
                                    {{ $data->subject_name }}

                                </td>
                                <td>
                                    {!! $data->button !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Data Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @if ($type_id == 1 || $type_id == 3)
        <div class="card">
            <div class="card-header">
                <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Archived Assignment Subjects</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4 form-group">
                        <label for="past_ay" class="required">Select Academic Year</label>
                        <select class="select2 form-control" name="past_ay" id="past_ay">
                            <option value="">Select AY</option>
                            @foreach ($getAys as $id => $ay)
                                <option value="{{ $ay }}">{{ $ay }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 form-group">
                        <label for="past_semester" class="required">Select Semester </label>
                        <select class="select2 form-control" name="past_semester" id="past_semester">
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
                    <div class="col-4 form-group">
                        <button class="enroll_generate_bn" style="margin-top:32px;" onclick="getPastRecords()">Get
                            Details</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Class Name</th>
                            <th>Subject</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr>
                            <td colspan="4">No Data Available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
    <script>
        function getPastRecords() {
            if ($("#past_ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#past_semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else {
                $("#tbody").html(`<tr><td colspan="4">Loading...</td></tr>`);
                $.ajax({
                    url: '{{ route('admin.assignment_Exam_Mark_Result.get-past-records') }}',
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'past_ay': $("#past_ay").val(),
                        'past_semester': $("#past_semester").val()
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            console.log(data)
                            let rows = '';
                            $.map(data, function(value, index) {
                                rows +=
                                    `<tr><td>${index + 1}</td><td>${value.classname}</td><td>${value.subject_name}</td><td>${value.button}</td></tr>`;
                            });
                            $("#tbody").html(rows);
                        } else {
                            Swal.fire('', data, 'error');
                            $("#tbody").html(`<tr><td colspan="4">No Data Available...</td></tr>`);
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
                })
            }
        }
    </script>
@endsection
