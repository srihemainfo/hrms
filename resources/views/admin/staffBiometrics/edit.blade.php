@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Edit Biometrics
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.staff-biometrics.update', [$staffBiometric->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                {{-- <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input class="form-control" type="text" name="date" id="date"
                                value="{{ old('date', $staffBiometric->date) }}">

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="employee_code">Employee Code</label>
                            <input class="form-control" type="text" name="employee_code" id="employee_code"
                                value="{{ old('employee_code', $staffBiometric->employee_code) }}">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="staff_code">Staff Code</label>
                            <input class="form-control" type="text" name="staff_code" id="staff_code"
                                value="{{ old('staff_code', $staffBiometric->staff_code) }}">

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="employee_name">Employee Name</label>
                            <input class="form-control" type="text" name="employee_name" id="employee_name"
                                value="{{ old('employee_name', $staffBiometric->employee_name) }}">

                        </div>
                    </div>
                </div> --}}
                <div class="form-group">
                    <label for="in_time">In Time</label>
                    <input class="form-control timepicker" type="text" name="in_time" id="in_time"
                        value="{{ old('in_time', $staffBiometric->in_time) }}">

                </div>

                <div class="form-group">
                    <label for="out_time">Out Time</label>
                    <input class="form-control timepicker" type="text" name="out_time" id="out_time"
                        value="{{ old('out_time', $staffBiometric->out_time) }}">

                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
