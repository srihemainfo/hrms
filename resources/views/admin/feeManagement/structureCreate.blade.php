@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <a class="btn btn-default mb-3" href="{{ route('admin.fee-structure.structureIndex') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <label class="required" for="batch">Batch</label>
                    <select class="form-control select2" name="batch" id="batch">
                        <option value="">Select Batch</option>
                        @foreach ($batch as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label class="required" for="academicyear">Academic year</label>
                    <select class="form-control select2" name="academicyear" id="academicyear">
                        <option value="">Select AY</option>
                        @foreach ($academic_year as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="department">Department</label>
                    <select class="form-control select2" name="department" id="department">
                        <option value="">Select Department</option>
                        @foreach ($department as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                {{--<div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="course">Course</label>
                    <select class="form-control select2" name="course" id="course">
                        <option value="">Select Course</option>
                         @foreach ($course as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div> --}}
                {{-- <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label class="required" for="year">Year</label>
                    <select class="form-control select2" name="year" id="year">
                        <option value="">Select Year</option>
                        <option value="1">First Year</option>
                        <option value="2">Second Year</option>
                        <option value="3">Third Year</option>
                        <option value="4">Final Year</option>
                    </select>
                </div> --}}
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 text-right">
                    <div>
                        <button type="button" class="enroll_generate_bn bg-primary" style="margin-top:30px;"
                            onclick="openForm()">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="fee_structure" style="display:none;">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="mq_tuition_fee">Tuition Fee (MQ)</label>
                    <input type="number" class="form-control" id="mq_tuition_fee" name="mq_tuition_fee"
                        onchange="addTotal()">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="gq_tuition_fee">Tuition Fee (GQ)</label>
                    <input type="number" class="form-control" id="gq_tuition_fee" name="gq_tuition_fee"
                        onchange="addTotal()">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="hostel_fee">Hostel Fee</label>
                    <input type="number" class="form-control" id="hostel_fee" name="hostel_fee" onchange="addTotal()">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="others">Others</label>
                    <input type="number" class="form-control" id="others" name="others" onchange="addTotal()">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (MQ) With Hostel</label>
                    <input type="hidden" id="mqh_total" name="mqh_total" value="0">
                    <div id="mqh_total_div" style="font-weight:bold;width:100%;"></div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (MQ) Without Hostel</label>
                    <input type="hidden" id="mq_total" name="mq_total" value="0">
                    <div id="mq_total_div" style="font-weight:bold;width:100%;"></div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (GQ) With Hostel</label>
                    <input type="hidden" id="gqh_total" name="gqh_total" value="0">
                    <div id="gqh_total_div" style="font-weight:bold;width:100%;"></div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (GQ) Without Hostel</label>
                    <input type="hidden" id="gq_total" name="gq_total" value="0">
                    <div id="gq_total_div" style="font-weight:bold;width:100%;"></div>
                </div>
                <div class="form-group col-12 text-center">
                    <button type="button" class="enroll_generate_bn bg-success"
                        onclick="generateFee()">Generate
                        Fee</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            $("#gq_tuition_fee").val(0);
            $("#mq_tuition_fee").val(0);
            $("#special_fee").val(0);
            $("#hostel_fee").val(0);
            $("#university_fee").val(0);
            $("#lab_fee").val(0);
            $("#others").val(0);
            $("#mq_total").val(0);
            $("#mqh_total").val(0);
            $("#gq_total").val(0);
            $("#gqh_total").val(0);
            $("#mq_total_div").html(0);
            $("#mqh_total_div").html(0);
            $("#gq_total_div").html(0);
            $("#gqh_total_div").html(0);
            $("#fee_structure").hide();
        }

        function openForm() {
            $("#fee_structure").hide();
            if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select The Batch', 'warning');
                return false;
                // } else if ($("#academicyear").val() == '') {
                //     Swal.fire('', 'Please Select The AY', 'warning');
                //     return false;
            } else if ($("#department").val() == '') {
                Swal.fire('', 'Please Select The Department', 'warning');
                return false;
            // } else if ($("#course").val() == '') {
            //     Swal.fire('', 'Please Select The Course', 'warning');
            //     return false;
                // } else if ($("#year").val() == '') {
                //     Swal.fire('', 'Please Select The Year', 'warning');
                //     return false;
            } else {
                let batch = $("#batch").val();
                // let ay = $("#academicyear").val();
                let dept = $("#department").val();
                // let course = $("#course").val();
                // let year = $("#year").val();


                $.ajax({
                    url: '{{ route('admin.fee-structure.check') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'batch': batch,
                        // 'ay': ay,
                        'dept': dept,
                        // 'course': course,
                        // 'year': year,
                    },
                    success: function(response) {
                        // console.log(response)
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            $("#fee_structure").show();
                        } else {
                            Swal.fire('', data, 'info');
                        }
                    }
                })
            }
        }

        function addTotal() {
            let mq_tuition_fee = $("#mq_tuition_fee").val();
            let gq_tuition_fee = $("#gq_tuition_fee").val();
            let hostel_fee = $("#hostel_fee").val();
            let others = $("#others").val();

            let sum;

            if (mq_tuition_fee == '') {
                mq_tuition_fee = 0;
            }
            if (gq_tuition_fee == '') {
                gq_tuition_fee = 0;
            }
            if (hostel_fee == '') {
                hostel_fee = 0;
            }
            if (others == '') {
                others = 0;
            }
            mqh_sum = parseInt(mq_tuition_fee) + parseInt(hostel_fee) + parseInt(others);
            mq_sum = parseInt(mq_tuition_fee) +  parseInt(others);
            gqh_sum = parseInt(gq_tuition_fee) + parseInt(hostel_fee) + parseInt(others);
            gq_sum = parseInt(gq_tuition_fee) +  parseInt(others);

            $("#mqh_total_div").html(mqh_sum);
            $("#mq_total_div").html(mq_sum);
            $("#mqh_total").val(mqh_sum);
            $("#mq_total").val(mq_sum);

            $("#gq_total_div").html(gq_sum);
            $("#gqh_total_div").html(gqh_sum);
            $("#gq_total").val(gq_sum);
            $("#gqh_total").val(gqh_sum);

        }

        function generateFee() {
            let batch = $("#batch").val();
            let dept = $("#department").val();
            // let course = $("#course").val();

            let mq_tuition_fee = $("#mq_tuition_fee").val();
            let gq_tuition_fee = $("#gq_tuition_fee").val();
            let hostel_fee = $("#hostel_fee").val();
            let others = $("#others").val();

            let mq_sum = $("#mq_total").val();
            let mqh_sum = $("#mqh_total").val();
            let gq_sum = $("#gq_total").val();
            let gqh_sum = $("#gqh_total").val();

            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Want To Generate Fee Structure ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.fee-structure.store') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'batch': batch,
                            'dept': dept,
                            // 'course': course,
                            'mq_tuition_fee': mq_tuition_fee,
                            'gq_tuition_fee': gq_tuition_fee,
                            'hostel_fee': hostel_fee,
                            'others': others,
                            'mq_total': mq_sum,
                            'mqh_total': mqh_sum,
                            'gq_total': gq_sum,
                            'gqh_total': gqh_sum,
                        },
                        success: function(response) {
                            // console.log(response)
                            let status = response.status;
                            if (status == true) {
                                $("#fee_structure").show();
                                Swal.fire('', 'Fee Structure Created Successfully!', 'success');
                                location.reload();
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                        }
                    })
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Fee Structure Creation Cancelled",
                        "error"
                    )
                }
            });
        }
    </script>
@endsection
