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
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="batch">Batch</label>
                    <input type="hidden" id="db_id" value="{{ $edit->id }}">
                    <select class="form-control select2" name="batch" id="batch" disabled>
                        @if (isset($edit->Batch) && $edit->Batch != null)
                            <option value="{{ $edit->Batch->id }}">{{ $edit->Batch->name }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="department">Department</label>
                    <select class="form-control select2" name="department" id="department" disabled>
                        @if (isset($edit->Department) && $edit->Department != null)
                            <option value="{{ $edit->Department->id }}">{{ $edit->Department->name }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="year">Year</label>
                    <select class="form-control select2" name="year" id="year" disabled>
                        @if (isset($edit->year) && $edit->year != null)
                            @if ($edit->year == '4')
                                <option value="4">Final Year</option>
                            @elseif ($edit->year == '3')
                                <option value="3">Third Year</option>
                            @elseif ($edit->year == '2')
                                <option value="2">Second Year</option>
                            @elseif ($edit->year == '1')
                                <option value="1">First Year</option>
                            @endif
                        @endif
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="name">Fee Name</label>
                    <select class="form-control select2" name="name" id="name" disabled>
                        @if (isset($edit->name) && $edit->name != null)
                            <option value="{{ $edit->name }}">{{ $edit->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="fee_structure">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="mq_tuition_fee">Tuition Fee (MQ)</label>
                    <input type="number" class="form-control" id="mq_tuition_fee" name="mq_tuition_fee"
                        value="{{ isset($edit->mq_tuition_fee) && $edit->mq_tuition_fee != null ? $edit->mq_tuition_fee : 0 }}"
                        onchange="addTotal()">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="gq_tuition_fee">Tuition Fee (GQ)</label>
                    <input type="number" class="form-control" id="gq_tuition_fee" name="gq_tuition_fee"
                        value="{{ isset($edit->gq_tuition_fee) && $edit->gq_tuition_fee != null ? $edit->gq_tuition_fee : 0 }}"
                        onchange="addTotal()">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="hostel_fee">Hostel Fee</label>
                    <input type="number" class="form-control" id="hostel_fee" name="hostel_fee"
                        value="{{ isset($edit->hostel_fee) && $edit->hostel_fee != null ? $edit->hostel_fee : 0 }}"
                        onchange="addTotal()">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="others">Others</label>
                    <input type="number" class="form-control" id="others" name="others"
                        value="{{ isset($edit->others) && $edit->others != null ? $edit->others : 0 }}"
                        onchange="addTotal()">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (MQ) With Hostel</label>
                    <input type="hidden" id="mqh_total" name="mqh_total"
                        value="{{ isset($edit->mqh_total_amt) && $edit->mqh_total_amt != null ? $edit->mqh_total_amt : 0 }}">
                    <div id="mqh_total_div" style="font-weight:bold;width:100%;">
                        {{ isset($edit->mqh_total_amt) && $edit->mqh_total_amt != null ? $edit->mqh_total_amt : 0 }}</div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (MQ) Without Hostel</label>
                    <input type="hidden" id="mq_total" name="mq_total"
                        value="{{ isset($edit->mq_total_amt) && $edit->mq_total_amt != null ? $edit->mq_total_amt : 0 }}">
                    <div id="mq_total_div" style="font-weight:bold;width:100%;">
                        {{ isset($edit->mq_total_amt) && $edit->mq_total_amt != null ? $edit->mq_total_amt : 0 }}</div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (GQ) With Hostel</label>
                    <input type="hidden" id="gqh_total" name="gqh_total"
                        value="{{ isset($edit->gqh_total_amt) && $edit->gqh_total_amt != null ? $edit->gqh_total_amt : 0 }}">
                    <div id="gqh_total_div" style="font-weight:bold;width:100%;">
                        {{ isset($edit->gqh_total_amt) && $edit->gqh_total_amt != null ? $edit->gqh_total_amt : 0 }}</div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (GQ) Without Hostel</label>
                    <input type="hidden" id="gq_total" name="gq_total"
                        value="{{ isset($edit->gq_total_amt) && $edit->gq_total_amt != null ? $edit->gq_total_amt : 0 }}">
                    <div id="gq_total_div" style="font-weight:bold;width:100%;">
                        {{ isset($edit->gq_total_amt) && $edit->gq_total_amt != null ? $edit->gq_total_amt : 0 }}</div>
                </div>

                <div class="form-group col-12 text-center">
                    <button type="button" class="enroll_generate_bn bg-success"
                        onclick="updateFee()">Update
                        Fee</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

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

        function updateFee() {

            let id = $("#db_id").val();
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
                text: "Do You Want To Update Fee Structure ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.fee-structure.update') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'id': id,
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

                                Swal.fire('', 'Fee Structure Updated Successfully!', 'success');
                                window.location.href = '{{ route('admin.fee-structure.structureIndex') }}';
                            } else {
                                Swal.fire('', 'Fee Structure Update Failed', 'error');
                            }
                        }
                    })
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Fee Structure Update Cancelled",
                        "error"
                    )
                }
            });
        }
    </script>
@endsection
