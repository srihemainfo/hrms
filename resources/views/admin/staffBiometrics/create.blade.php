@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Create Biometrics
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.staff-biometrics.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row gutters">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="month">Month</label>
                            <select class="form-control select2" name="month" id="month" required>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="required" for="year">Year</label>
                            <select class="form-control select2" name="year" id="year" required>
                                @php
                                    $current_year = date('Y');
                                    $next_year = $current_year + 1;

                                @endphp
                                <option value="{{ $current_year }}" selected>{{ $current_year }}</option>
                                <option value="{{ $next_year }}">{{ $next_year }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group" style="padding-top: 30px;">
                            <button type="submit" id="submit" name="submit" class="enroll_generate_bn">Generate</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
