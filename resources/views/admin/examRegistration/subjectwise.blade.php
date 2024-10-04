@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Exam Registration Subject Wise
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Batch</label>
                        <select name="batch" id="batch" class="form-control select2">
                            <option value="">Select batch</option>
                            @foreach ($batches as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
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
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Exam Type</label>
                        <select name="exam_type" id="exam_type" class="form-control select2">
                            <option value="">Select Exam Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Arrear">Arrear</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="search()">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function search() {
            if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select Batch', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else if ($("#exam_type").val() == '') {
                Swal.fire('', 'Please Select Exam Type', 'error');
                return false;
            } else {
                let course = $("#course").val();
                let semester = $("#semester").val();
                let exam_type = $("#exam_type").val();
                $.ajax({
                    url: '{{ route('admin.exam-registrations.subjectwise.search') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': course,
                        'semester': semester,
                        'exam_type': exam_type
                    },
                    success: function(response) {

                    }
                })
            }

        }
    </script>
@endsection
