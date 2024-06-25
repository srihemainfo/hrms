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
                    <select class="form-control select2" name="batch" id="batch" disabled>
                        @if (isset($show->Batch) && $show->Batch != null)
                            <option>{{ $show->Batch->name }}</option>
                        @endif
                    </select>
                </div>
                {{-- <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="academicyear">Academic year</label>
                    <select class="form-control select2" name="academicyear" id="academicyear" disabled>
                        @if (isset($show->AcademicYear) && $show->AcademicYear != null)
                            <option>{{ $show->AcademicYear->name }}</option>
                        @endif
                    </select>
                </div> --}}
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="department">Department</label>
                    <select class="form-control select2" name="department" id="department" disabled>
                        @if (isset($show->Department) && $show->Department != null)
                            <option>{{ $show->Department->name }}</option>
                        @endif
                    </select>
                </div>
                {{-- <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="course">Course</label>
                    <select class="form-control select2" name="course" id="course" disabled>
                        @if (isset($show->Course) && $show->Course != null)
                            <option>{{ $show->Course->short_form }}</option>
                        @endif
                    </select>
                </div> --}}
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="year">Year</label>
                    <select class="form-control select2" name="year" id="year" disabled>
                        @if (isset($show->year) && $show->year != null)
                            @if ($show->year == '4')
                                <option>Final Year</option>
                            @elseif ($show->year == '3')
                                <option>Third Year</option>
                            @elseif ($show->year == '2')
                                <option>Second Year</option>
                            @elseif ($show->year == '1')
                                <option>First Year</option>
                            @endif
                        @endif
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="name">Fee Name</label>
                    <select class="form-control select2" name="name" id="name" disabled>
                        @if (isset($show->name) && $show->name != null)
                            <option value="{{ $show->name }}">{{ $show->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="fee_structure">
        <div class="card-header">
            Fee Structure
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="mq_tuition_fee">Tuition Fee (MQ)</label>
                    <input type="number" class="form-control" id="mq_tuition_fee" name="mq_tuition_fee"
                        value="{{ isset($show->mq_tuition_fee) && $show->mq_tuition_fee != null ? $show->mq_tuition_fee : 0 }}">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="gq_tuition_fee">Tuition Fee (GQ)</label>
                    <input type="number" class="form-control" id="gq_tuition_fee" name="gq_tuition_fee"
                        value="{{ isset($show->gq_tuition_fee) && $show->gq_tuition_fee != null ? $show->gq_tuition_fee : 0 }}">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="hostel_fee">Hostel Fee</label>
                    <input type="number" class="form-control" id="hostel_fee" name="hostel_fee"
                        value="{{ isset($show->hostel_fee) && $show->hostel_fee != null ? $show->hostel_fee : 0 }}">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="others">Others</label>
                    <input type="number" class="form-control" id="others" name="others"
                        value="{{ isset($show->others) && $show->others != null ? $show->others : 0 }}">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (MQ) With Hostel</label>
                    <div id="mqh_total_div" style="font-weight:bold;width:100%;">
                        {{ isset($show->mqh_total_amt) && $show->mqh_total_amt != null ? $show->mqh_total_amt : 0 }}</div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (MQ) Without Hostel</label>
                    <div id="" style="font-weight:bold;width:100%;">
                        {{ isset($show->mq_total_amt) && $show->mq_total_amt != null ? $show->mq_total_amt : 0 }}</div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (GQ) With Hostel</label>
                    <div id="gq_total_div" style="font-weight:bold;width:100%;">
                        {{ isset($show->gq_total_amt) && $show->gqh_total_amt != null ? $show->gqh_total_amt : 0 }}</div>
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 text-center">
                    <label for="total">Total (GQ) Without Hostel</label>
                    <div id="" style="font-weight:bold;width:100%;">
                        {{ isset($show->gqh_total_amt) && $show->gq_total_amt != null ? $show->gq_total_amt : 0 }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
