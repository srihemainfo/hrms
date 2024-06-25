@extends('layouts.teachingStaffHome')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> My Subjects</h5>
        </div>
        <div class="card-body" style="max-width:100%;overflow-x:auto;">
            <table class="table table-bordered table-striped table-hover text-center" style="min-width:700px;">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Class Name</th>
                        <th>Subject</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($got_subjects) > 0)
                        @foreach ($got_subjects as $id => $data)
                            <tr>
                                <td>{{ $id + 1 }}</td>
                                <td>
                                    {{ $data[1] }}
                                </td>
                                <td>
                                    @foreach ($subjects as $subject)
                                        @if ($subject->id == $data[0])
                                            {{ $subject->name }} ({{ $subject->subject_code }})
                                        @endif
                                    @endforeach
                                    @if ($data[0] == 'Library')
                                        Library
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">No Date Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> Archived Taken Subjects</h5>
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
        <div class="card-body" style="max-width:100%;overflow-x:auto;">
            <table class="table table-bordered table-striped table-hover text-center" style="min-width:1000px;">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Class Name</th>
                        <th>Subject</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <tr>
                        <td colspan="3">No Data Available...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
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
                    url: '{{ route('admin.staff-subjects.get-past-records') }}',
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
                            let rows = '';
                            $.map(data, function(value, index) {
                                console.log(value)
                                if (value[1] != 'Library') {
                                    rows +=
                                        `<tr><td>${index + 1}</td><td>${value[0]}</td><td>${value[1]} (${value[2]})</td></tr>`;
                                } else {
                                    rows +=
                                        `<tr><td>${index + 1}</td><td>${value[0]}</td><td>${value[1]}</td></tr>`;
                                }
                            });
                            $("#tbody").html(rows);
                        } else {
                            Swal.fire('', data, 'error');
                            $("#tbody").html(`<tr><td colspan="4">No Data Available...</td></tr>`);
                        }
                    }
                })
            }
        }
    </script>
@endsection
