<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<div class="container">
    @if (auth()->user()->id != $student->user_name_id)
        <div class="row gutters">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route('admin.academic-details.stu_update', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-2 text-primary">Academic Details</h6>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="register_number">Register Number</label>
                                        <input type="text" class="form-control" name="register_number"
                                            placeholder="Enter Register Number"
                                            value="{{ old('register_number', $student->register_number) }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="roll_no">Roll Number</label>
                                        <input type="text" class="form-control" name="roll_no"
                                            placeholder="Enter Roll Number"
                                            value="{{ old('roll_no', $student->roll_no) }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="emis_number">Emis Number</label>
                                        <input type="text" class="form-control" name="emis_number"
                                            placeholder="Enter Emis Number"
                                            value="{{ old('emis_number', $student->emis_number) }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="admitted_mode">Admitted Mode</label>
                                        <select name="admitted_mode" id="admitted_mode" class="form-control select2">
                                            <option value="GENERAL QUOTA"
                                                {{ $student->admitted_mode == 'GENERAL QUOTA' ? 'selected' : '' }}>
                                                GENERAL QUOTA</option>
                                            <option value="MANAGEMENT QUOTA"
                                                {{ $student->admitted_mode == 'MANAGEMENT QUOTA' ? 'selected' : '' }}>
                                                MANAGEMENT QUOTA</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="first_graduate">First Graduate</label>
                                        <select name="first_graduate" id="first_graduate" class="form-control select2">
                                            <option value="0"
                                                {{ $student->first_graduate == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1"
                                                {{ $student->first_graduate == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="gqg">GQ GOVT</label>
                                        <select name="gqg" id="gqg" class="form-control select2">
                                            <option value="0" {{ $student->gqg == '0' ? 'selected' : '' }}>No
                                            </option>
                                            <option value="1" {{ $student->gqg == '1' ? 'selected' : '' }}>Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="scholarship">Scholarship</label>
                                        <select name="scholarship" id="scholarship" class="form-control select2" onchange="checkScholar(this)">
                                            <option value="0"
                                                {{ $student->scholarship == '0' ? 'selected' : '' }}>No
                                            </option>
                                            <option value="1"
                                                {{ $student->scholarship == '1' ? 'selected' : '' }}>Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="scholarshipDiv" style="display:{{ $student->scholarship == '1' ? 'block':'none' }};">
                                    <div class="form-group">
                                        <label for="scholarship_name" class="required">Scholarship Name</label>
                                        <select name="scholarship_name" id="scholarship_name"
                                            class="form-control select2">
                                            <option value="">Select Scholarship</option>
                                            @foreach ($student->scholarships as $scholarship)
                                                <option value="{{ $scholarship->id }}" {{ $student->scholarship_name == $scholarship->id ? 'selected':'' }}>{{ $scholarship->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="hosteler">Hosteler</label>
                                        <select name="hosteler" id="hosteler" class="form-control select2">
                                            <option value="0" {{ $student->hosteler == '0' ? 'selected' : '' }}>No
                                            </option>
                                            <option value="1" {{ $student->hosteler == '1' ? 'selected' : '' }}>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="late_entry">Late Entry</label>
                                        <select name="late_entry" id="late_entry" class="form-control select2">
                                            <option value="0" {{ $student->late_entry == '0' ? 'selected' : '' }}>
                                                No
                                            </option>
                                            <option value="1" {{ $student->late_entry == '1' ? 'selected' : '' }}>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="admitted_course">Admitted Course</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('admitted_course') ? 'is-invalid' : '' }}"
                                            name="admitted_course" id="admitted_course">
                                            @foreach ($student->admitted_courses as $id => $entry)
                                                <option value="{{ $entry }}"
                                                    {{ $student->course_id == $entry ? 'selected' : '' }}>
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="batch">Batch</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('batch') ? 'is-invalid' : '' }}"
                                            name="batch" id="batch">
                                            @foreach ($student->batch as $id => $entry)
                                                <option value="{{ $entry }}"
                                                    {{ $student->batch_id == $entry ? 'selected' : '' }}>
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="accademicYear">Academic Year</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('accademicYear') ? 'is-invalid' : '' }}"
                                            name="accademicYear" id="accademicYear">
                                            @foreach ($student->accademicYear as $id => $entry)
                                                <option value="{{ $entry }}"
                                                    {{ $student->accademicYear_id == $entry ? 'selected' : '' }}>
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="semester">Semester</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}"
                                            name="semester" id="semester">
                                            @foreach ($student->semester as $id => $entry)
                                                <option value="{{ $entry }}"
                                                    {{ $student->semester_id == $entry ? 'selected' : '' }}>
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="section">Section</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('section') ? 'is-invalid' : '' }}"
                                            name="section" id="section">
                                            @foreach ($student->section as $id => $entry)
                                                <option value="{{ $entry }}"
                                                    {{ $student->section_id == $entry ? 'selected' : '' }}>
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
                                        {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
                                        <button type="submit" id="submit" name="submit"
                                            class="btn btn-primary">{{ $student->add }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->id == $student->user_name_id)
        <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
        <div class="row gutters">
            <div class="col" style="padding:0;">
                <div class="card">
                    <div class="card-header">
                        <div class="row gutters">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Academic Details
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive view_more">
                        <div class="row gutters">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="register_number">Register Number</label>
                                    <input type="text" class="form-control" name="register_number"
                                        value="{{ old('register_number', $student->register_number) }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="roll_no">Roll Number</label>
                                    <input type="text" class="form-control" name="roll_no"
                                        value="{{ old('roll_no', $student->roll_no) }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="emis_number">Emis Number</label>
                                    <input type="text" class="form-control" name="emis_number"
                                        value="{{ old('emis_number', $student->emis_number) }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="admitted_mode">Admitted Mode</label>
                                    <input type="text" class="form-control" name="admitted_mode"
                                        value="{{ old('admitted_mode', $student->admitted_mode) }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="first_graduate">First Graduate</label>
                                    <input type="text" class="form-control" name="first_graduate"
                                        value="{{ $student->first_graduate == '1' ? 'Yes' : 'No' }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="gqg">GQ GOVT</label>
                                    <input type="text" class="form-control" name="gqg"
                                        value="{{ $student->gqg == '1' ? 'Yes' : 'No' }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="scholarship">Scholarship</label>
                                    <input type="text" class="form-control" name="scholarship"
                                        value="{{ $student->scholarship == '1' ? 'Yes' : 'No' }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"
                                style="display:{{ $student->scholarship == '1' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label for="scholarship_name">Scholarship Name</label>
                                    <input type="text" class="form-control" name="scholarship_name"
                                        value="{{ $student->scholarDetail != null ? $student->scholarDetail->name : '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="hosteler">Hosteler</label>
                                    <input type="text" class="form-control" name="hosteler"
                                        value="{{ $student->hosteler == '1' ? 'Yes' : 'No' }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="late_entry">Late Entry</label>
                                    <input type="text" class="form-control" name="late_entry"
                                        value="{{ $student->late_entry == '1' ? 'Yes' : 'No' }}" readonly>
                                </div>
                            </div>
                            @if ($student->admitted_course != '')
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="admitted_course">Admitted Course</label>
                                        @foreach ($student->admitted_courses as $id => $entry)
                                            @if ($student->admitted_course == $id)
                                                <input type="text" class="form-control"
                                                    value="{{ $entry }}" readonly>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            @else
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="admitted_course">Admitted Course</label>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="batch">Batch</label>

                                    @foreach ($student->batch as $id => $entry)
                                        @if ($student->batch_id == $entry)
                                            <input class="form-control" type="text" value="{{ $entry }}"
                                                readonly>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="accademicYear">Academic Year</label>

                                    @foreach ($student->accademicYear as $id => $entry)
                                        @if ($student->accademicYear_id == $entry)
                                            <input class="form-control" type="text" value="{{ $entry }}"
                                                readonly>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="semester">Semester</label>

                                    @foreach ($student->semester as $id => $entry)
                                        @if ($student->semester_id == $entry)
                                            <input class="form-control" type="text" value="{{ $entry }}"
                                                readonly>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="section">Section</label>

                                    @foreach ($student->section as $id => $entry)
                                        @if ($student->section_id == $entry)
                                            <input class="form-control" type="text" value="{{ $entry }}"
                                                readonly>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
