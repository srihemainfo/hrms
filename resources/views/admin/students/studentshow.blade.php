@php
    if (auth()->user()->id == $student->user_name_id) {
        $one = 'layouts.studentHome';
    } elseif (auth()->user()->id != $student->user_name_id) {
        $one = 'layouts.admin';
    }
@endphp
@extends($one)
@section('content')
    <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
    <div class="col pb-3" style="overflow:hidden;">
        <div class="bg-primary text-light student_label">
            <div style="padding-left:2%;"><i class="fa fa-chevron-left prev_page_bn" onclick="history.go(-1)"></i></div>
            <div style="padding-right:2%;"> <span style="margin-right: 10px;"> STUDENT NAME : {{ $student->name }}</span>
                @if (auth()->user()->id == $student->user_name_id && session('appUser') == false)
                    <a href="{{ url('admin/students/' . auth()->user()->id . '/Profile-edit') }}" style="margin-left:10px;"
                        title="Edit Profile"><i class="fas fa-edit"></i></a>
                @endif
            </div>
        </div>

        <div class="row gutters">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12" style="margin:0px 0px 7.5px 0px;">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="account-settings">
                            <div class="user-profile">
                                <div class="user-avatar">

                                    @if (
                                        (isset($student->filePath) ? $student->filePath : '') != '' ||
                                            (isset($student->filePath) ? $student->filePath : '') != null)
                                        <img class="uploaded_img" src="{{ asset($student->filePath) }}" alt="image">
                                    @else
                                        @if ($student->gender == 'MALE' || $student->gender == 'Male')
                                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png"
                                                alt="Maxwell Admin">
                                        @elseif($student->gender == 'FEMALE' || $student->gender == 'Female')
                                            <img src="https://bootdey.com/img/Content/avatar/avatar8.png"
                                                alt="Maxwell Admin">
                                        @else
                                            <img src="{{ asset('adminlogo/user-image.png') }}" alt="">
                                        @endif
                                    @endif

                                </div>
                                <h5 class="user-name">{{ $student->name }}</h5>
                                <h6 class="user-email">
                                    {{ $student->enroll_master_id != '' ? $student->enroll_master['enroll_master_number'] : '' }}
                                </h6>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12" style="margin:0px 0px 7.5px 0px;">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Student Info</h5>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="fullName">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" value="{{ $student->name }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="">Register Number</label>
                                    <input type="text" class="form-control" id=""
                                        value="{{ $student->register_no }}" readonly>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id=""
                                        value="{{ $student->student_email_id }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone"
                                        value="{{ $student->student_phone_no }}" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                <div class="card" style="margin-top: 16px;">
                    <div class="card-header">
                        <div class="row gutters">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Personal Details</h5>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                        style="font-size:1.5em;"></i></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body view_more" style="display:none;">
                        <form method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $detail->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $detail->email == '' ? $student->student_email_id : $detail->email }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile</label>
                                        <input type="text" class="form-control" id="mobile_number"
                                            name="mobile_number" value="{{ $detail->mobile_number }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="aadhar_number">Aadhar Number</label>
                                        <input type="text" class="form-control" name="aadhar_number"
                                            value="{{ $detail->aadhar_number }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input type="text" class="form-control date" name="dob"
                                            value="{{ $detail->dob }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="text" class="form-control" name="age"
                                            value="{{ $detail->age }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="gender">GENDER</label>
                                        <input type="text" class="form-control" name="gender"
                                            value="{{ $detail->gender }}"readonly>
                                    </div>
                                </div>
                                @if ($detail->blood_group_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="blood_group_id">Blood Group</label>
                                            @php
                                                $blood_group = null;
                                            @endphp
                                            @foreach ($detail->blood_group as $id => $entry)
                                                @if ($detail->blood_group_id == $id)
                                                    @php
                                                        $blood_group = $entry;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            <input type="text" class="form-control"
                                                value="{{ $blood_group }}"readonly>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="blood_group_id">Blood Group</label>
                                            <input type="text" class="form-control"readonly>
                                        </div>
                                    </div>
                                @endif
                                @if ($detail->mother_tongue_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mother_tongue_id">Mother Tongue</label>
                                            @php
                                                $mother_tongue = null;
                                            @endphp
                                            @foreach ($detail->mother_tongue as $id => $entry)
                                                @if ($detail->mother_tongue_id == $id)
                                                    @php
                                                        $mother_tongue = $entry;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            <input type="text" class="form-control"
                                                value="{{ $mother_tongue }}"readonly>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mother_tongue_id">Mother Tongue</label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endif
                                @if ($detail->religion_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="religion_id">Religion</label>
                                            @php
                                                $religion = null;
                                            @endphp
                                            @foreach ($detail->religion as $id => $entry)
                                                @if ($detail->religion_id == $id)
                                                    @php
                                                        $religion = $entry;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            <input type="text" class="form-control" value="{{ $religion }}"
                                                readonly>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="religion_id">Religion</label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endif
                                @if ($detail->community_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="community_id">Community</label>
                                            @php
                                                $community = null;
                                            @endphp
                                            @foreach ($detail->community as $id => $entry)
                                                @if ($detail->community_id == $id)
                                                    @php
                                                        $community = $entry;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            <input type="text" class="form-control" value="{{ $community }}"
                                                readonly>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="community_id">Community</label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" name="state"
                                            value="{{ $detail->state }}"readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" class="form-control" name="country"
                                            value="{{ $detail->country }}" readonly>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        @if (!empty($academic_list))
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Academic Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="register_number">Register Number</label>
                                        <input type="text" class="form-control" name="register_number"
                                            value="{{ old('register_number', $academic_list->register_number) }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="roll_no">Roll Number</label>
                                        <input type="text" class="form-control" name="roll_no"
                                            value="{{ old('roll_no', $academic_list->roll_no) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="shift">Shift</label>
                                        <input type="text" class="form-control" name="shift"
                                            value="{{ old('shift', $academic_list->shift ? $academic_list->shift->Name : '') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="emis_number">Emis Number</label>
                                        <input type="text" class="form-control" name="emis_number"
                                            value="{{ old('emis_number', $academic_list->emis_number) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="admitted_mode">Admitted Mode</label>
                                        <input type="text" class="form-control" name="admitted_mode"
                                            value="{{ old('admitted_mode', $academic_list->admitted_mode) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="first_graduate">First Graduate</label>
                                        <input type="text" class="form-control" name="first_graduate"
                                            value="{{ $academic_list->first_graduate == '1' ? 'Yes' : 'No' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="gqg">GQ GOVT</label>
                                        <input type="text" class="form-control" name="gqg"
                                            value="{{ $academic_list->gqg == '1' ? 'Yes' : 'No' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="scholarship">Scholarship</label>
                                        <input type="text" class="form-control" name="scholarship"
                                            value="{{ $academic_list->scholarship == '1' ? 'Yes' : 'No' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"
                                    style="display:{{ $academic_list->scholarship == '1' ? 'block' : 'none' }};">
                                    <div class="form-group">
                                        <label for="scholarship_name">Scholarship Name</label>
                                        <input type="text" class="form-control" name="scholarship_name"
                                            value="{{ $academic_list->scholarDetail != null ? $academic_list->scholarDetail->name : '' }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="hosteler">Hosteler</label>
                                        <input type="text" class="form-control" name="hosteler"
                                            value="{{ $academic_list->hosteler == '1' ? 'Yes' : 'No' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="late_entry">Late Entry</label>
                                        <input type="text" class="form-control" name="late_entry"
                                            value="{{ $academic_list->late_entry == '1' ? 'Yes' : 'No' }}" readonly>
                                    </div>
                                </div>
                                @if ($academic_list->enroll_master_number_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="enroll_master_number_id">Course</label>
                                            <input type="text" class="form-control"
                                                value="{{ $academic_list->coursE }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="enroll_master_number_id">Batch</label>
                                            <input type="text" class="form-control"
                                                value="{{ $academic_list->Batch }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="enroll_master_number_id">Academic Year</label>
                                            <input type="text" class="form-control"
                                                value="{{ $academic_list->accademicYear }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="enroll_master_number_id">Semester</label>
                                            <input type="text" class="form-control" value="{{ $academic_list->sem }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="enroll_master_number_id">Section</label>
                                            <input type="text" class="form-control"
                                                value="{{ $academic_list->section }}" readonly>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="enroll_master_number_id">Student Details</label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!empty($parent))
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Parent Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="father_name">Father Name</label>
                                        <input type="text" class="form-control" name="father_name"
                                            value="{{ $parent->father_name }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mother_name">Mother Name</label>
                                        <input type="text" class="form-control" name="mother_name"
                                            value="{{ $parent->mother_name }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="father_mobile_no">Father Mobile No</label>
                                        <input type="text" class="form-control" name="father_mobile_no"
                                            value="{{ $parent->father_mobile_no }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mother_mobile_no">Mother Mobile No</label>
                                        <input type="text" class="form-control" name="mother_mobile_no"
                                            value="{{ $parent->mother_mobile_no }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="fathers_occupation">Father's Occupation</label>
                                        <input type="text" class="form-control" name="fathers_occupation"
                                            value="{{ $parent->fathers_occupation }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mothers_occupation">Mother's Occupation</label>
                                        <input type="text" class="form-control" name="mothers_occupation"
                                            value="{{ $parent->mothers_occupation }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="father_off_address">Father's Office Address</label>
                                        <input type="text" class="form-control"
                                            value="{{ $parent->father_off_address }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mother_off_address">Mother's Office Address</label>
                                        <input type="text" class="form-control"
                                            value="{{ $parent->mother_off_address }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="guardian_name">Guardian Name(If)</label>
                                        <input type="text" class="form-control" name="guardian_name"
                                            value="{{ $parent->guardian_name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="guardian_mobile_no">Guardian Mobile No</label>
                                        <input type="text" class="form-control" name="guardian_mobile_no"
                                            value="{{ $parent->guardian_mobile_no }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="gaurdian_occupation">Guardian's Occupation</label>
                                        <input type="text" class="form-control" name="gaurdian_occupation"
                                            value="{{ $parent->gaurdian_occupation }}"readonly>
                                    </div>
                                </div>
                                @if ($parent->guardian_off_address != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="guardian_off_address">Guardian's Office Address</label>
                                            <textarea name="guardian_off_address" class="form-control" readonly>{{ $parent->guardian_off_address }}</textarea>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="guardian_off_address">Guardian's Office Address</label>
                                            <input type="text" class="form-control" value="" readonly>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($education_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Educational Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Education Type
                                        </th>
                                        <th>
                                            Institute Name
                                        </th>
                                        <th>
                                            Institute Location
                                        </th>
                                        <th>
                                            Board / University
                                        </th>
                                        <th>
                                            Register Number
                                        </th>
                                        <th>
                                            Total Marks
                                        </th>
                                        <th>
                                            Cutoff Mark
                                        </th>
                                        <th>
                                            Marks In Percentage / CGPA
                                        </th>
                                        <th>
                                            Medium
                                        </th>
                                        <th>
                                            Subject 1
                                        </th>
                                        <th>
                                            Mark 1
                                        </th>
                                        <th>
                                            Subject 2
                                        </th>
                                        <th>
                                            Mark 2
                                        </th>
                                        <th>
                                            Subject 3
                                        </th>
                                        <th>
                                            Mark 3
                                        </th>
                                        <th>
                                            Subject 4
                                        </th>
                                        <th>
                                            Mark 4
                                        </th>
                                        <th>
                                            Subject 5
                                        </th>
                                        <th>
                                            Mark 5
                                        </th>
                                        <th>
                                            Subject 6
                                        </th>
                                        <th>
                                            Mark 6
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @for ($i = 0; $i < count($education_list); $i++)
                                        <tr>
                                            @if ($education_list[$i]->education_type_id != '' || $education_list[$i]->education_type_id != null)
                                                @foreach ($education_list[$i]->education_types as $id => $entry)
                                                    @if ($education_list[$i]->education_type_id == $id)
                                                        <td>{{ $entry }}</td>
                                                    @endif
                                                @endforeach
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{ $education_list[$i]->institute_name }}</td>
                                            <td>{{ $education_list[$i]->institute_location }}</td>
                                            <td>{{ $education_list[$i]->board_or_university }}</td>
                                            <td>{{ $education_list[$i]->register_number }}</td>
                                            <td>{{ $education_list[$i]->marks }}</td>
                                            <td>{{ $education_list[$i]->cutoffmark }}</td>
                                            <td>{{ $education_list[$i]->marks_in_percentage }}</td>
                                            @if ($education_list[$i]->medium_id != '' || $education_list[$i]->medium_id != null)
                                                @foreach ($education_list[$i]->medium as $id => $entry)
                                                    @if ($education_list[$i]->medium_id == $id)
                                                        <td>{{ $entry }}</td>
                                                    @endif
                                                @endforeach
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{ $education_list[$i]->subject_1 }}</td>
                                            <td>{{ $education_list[$i]->mark_1 }}</td>
                                            <td>{{ $education_list[$i]->subject_2 }}</td>
                                            <td>{{ $education_list[$i]->mark_2 }}</td>
                                            <td>{{ $education_list[$i]->subject_3 }}</td>
                                            <td>{{ $education_list[$i]->mark_3 }}</td>
                                            <td>{{ $education_list[$i]->subject_4 }}</td>
                                            <td>{{ $education_list[$i]->mark_4 }}</td>
                                            <td>{{ $education_list[$i]->subject_5 }}</td>
                                            <td>{{ $education_list[$i]->mark_5 }}</td>
                                            <td>{{ $education_list[$i]->subject_6 }}</td>
                                            <td>{{ $education_list[$i]->mark_6 }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($address_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Address Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">
                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Address Type
                                        </th>
                                        <th>
                                            Room No & Street
                                        </th>
                                        <th>
                                            Area
                                        </th>
                                        <th>
                                            District
                                        </th>
                                        <th>
                                            Pincode
                                        </th>
                                        <th>
                                            State
                                        </th>
                                        <th>
                                            Country
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($address_list); $i++)
                                        @if ($address_list[$i]->address_type != '' || $address_list[$i]->address_type != null)
                                            <tr>
                                                <td>{{ $address_list[$i]->address_type }}</td>
                                                <td>{{ $address_list[$i]->room_no_and_street }}</td>
                                                <td>{{ $address_list[$i]->area_name }}</td>
                                                <td>{{ $address_list[$i]->district }}</td>
                                                <td>{{ $address_list[$i]->pincode }}</td>
                                                <td>{{ $address_list[$i]->state }}</td>
                                                <td>{{ $address_list[$i]->country }}</td>
                                            </tr>
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($conference_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Conference Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Topic Name
                                        </th>
                                        <th>
                                            Location
                                        </th>
                                        <th>
                                            Project Name
                                        </th>
                                        <th>
                                            Conference Date
                                        </th>
                                        <th>
                                            Contribution
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($conference_list); $i++)
                                        <tr>
                                            <td>{{ $conference_list[$i]->topic_name }}</td>
                                            <td>{{ $conference_list[$i]->location }}</td>
                                            <td>{{ $conference_list[$i]->project_name }}</td>
                                            <td>{{ $conference_list[$i]->conference_date }}</td>
                                            <td>{{ $conference_list[$i]->contribution_of_conference }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($industrial_training_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Industrial Training
                                        Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Topic
                                        </th>
                                        <th>
                                            From
                                        </th>
                                        <th>
                                            To
                                        </th>
                                        <th>
                                            Location
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($industrial_training_list); $i++)
                                        <tr>
                                            <td>{{ $industrial_training_list[$i]->topic }}</td>
                                            <td>{{ $industrial_training_list[$i]->from_date }}</td>
                                            <td>{{ $industrial_training_list[$i]->to_date }}</td>
                                            <td>{{ $industrial_training_list[$i]->location }}</td>
                                            <td>{{ $industrial_training_list[$i]->remarks }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($intern_details_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Interns Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Topic
                                        </th>
                                        <th>
                                            From
                                        </th>
                                        <th>
                                            To
                                        </th>
                                        <th>
                                            Progress Report
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($intern_details_list); $i++)
                                        <tr>
                                            <td>{{ $intern_details_list[$i]->topic }}</td>
                                            <td>{{ $intern_details_list[$i]->from_date }}</td>
                                            <td>{{ $intern_details_list[$i]->to_date }}</td>
                                            <td>{{ $intern_details_list[$i]->progress_report }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($iv_details_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">IV Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Topic
                                        </th>
                                        <th>
                                            From
                                        </th>
                                        <th>
                                            To
                                        </th>
                                        <th>
                                            Location
                                        </th>
                                        <th>
                                            Remarks
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($iv_details_list); $i++)
                                        <tr>
                                            <td>{{ $iv_details_list[$i]->topic }}</td>
                                            <td>{{ $iv_details_list[$i]->from_date }}</td>
                                            <td>{{ $iv_details_list[$i]->to_date }}</td>
                                            <td>{{ $iv_details_list[$i]->location }}</td>
                                            <td>{{ $iv_details_list[$i]->remarks }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($document_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Document Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>File</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($document_list as $row)
                                        <tr>
                                            @if (($row->fileName != '' || $row->fileName != null) && $row->fileName != 'Profile')
                                                <td>{{ $row->fileName }}</td>
                                                <td>
                                                    <img class="uploaded_img" src="{{ asset($row->filePath) }}"
                                                        alt="image">
                                                </td>
                                            @endif

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($seminar_details_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Seminars Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">

                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Topic
                                        </th>
                                        <th>
                                            Seminar Date
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($seminar_details_list); $i++)
                                        <tr>
                                            <td>{{ $seminar_details_list[$i]->topic }}</td>
                                            <td>{{ $seminar_details_list[$i]->seminar_date }}</td>
                                            <td>{{ $seminar_details_list[$i]->remark }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($patent_details_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Patent Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">
                            {{-- <h5 class="mb-3 text-primary">Patent Details</h5> --}}
                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Topic
                                        </th>
                                        <th>
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($patent_details_list); $i++)
                                        <tr>
                                            <td>{{ $patent_details_list[$i]->topic }}</td>
                                            <td>{{ $patent_details_list[$i]->remark }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($professional_activities_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Professional
                                        Activities
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">
                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Winning In Competition
                                        </th>
                                        <th>
                                            Participation In Competitions
                                        </th>
                                        <th>
                                            Co-Curricular Activates
                                        </th>
                                        <th>
                                            Extra Curricular Activates
                                        </th>
                                        <th>
                                            Leader Board Score
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($professional_activities_list); $i++)
                                        <tr>
                                            <td>{{ $professional_activities_list[$i]->winning_in_competitions }}</td>
                                            <td>{{ $professional_activities_list[$i]->participation_in_competitions }}
                                            </td>
                                            <td>{{ $professional_activities_list[$i]->participation_in_co_curricular_activates }}
                                            </td>
                                            <td>{{ $professional_activities_list[$i]->participation_in_extra_curricular_activates }}
                                            </td>
                                            <td>{{ $professional_activities_list[$i]->leader_board_score }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (count($student_leave_apply_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Student leave apply
                                        list

                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive view_more" style="display:none;">
                            <table class="list_table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            Leave Type
                                        </th>
                                        <th>
                                            From Date
                                        </th>
                                        <th>
                                            To Date
                                        </th>
                                        <th>
                                            Reason
                                        </th>
                                        <th>
                                            Document
                                        </th>
                                        <th>
                                            Rejected reason
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($student_leave_apply_list); $i++)
                                        @if ($student_leave_apply_list[$i]->leave_type != '' || $student_leave_apply_list[$i]->leave_type != null)
                                            <tr>

                                                <td>{{ $student_leave_apply_list[$i]->leave_type }}</td>

                                                <td>{{ $student_leave_apply_list[$i]->from_date }}</td>
                                                <td>{{ $student_leave_apply_list[$i]->to_date }}</td>
                                                <td>{{ $student_leave_apply_list[$i]->reason }}</td>
                                                <td>
                                                    @if ($student_leave_apply_list[$i]->certificate_path)
                                                        <img class="uploaded_img"
                                                            src="{{ asset($student_leave_apply_list[$i]->certificate_path) }}"
                                                            alt="image">
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($student_leave_apply_list[$i]->status == '2')
                                                        <p>{{ $student_leave_apply_list[$i]->rejected_reason }}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @switch($student_leave_apply_list[$i]->status)
                                                        @case(0)
                                                            @if ($student_leave_apply_list[$i]->level == 0)
                                                                <div class="p-2 Pending">Waiting For Class Adviser Approval</div>
                                                            @elseif ($student_leave_apply_list[$i]->level == 1)
                                                                <div class="p-2 Pending">Waiting For Hod Approval</div>
                                                            @else
                                                                <div class="p-2 Pending">Pending</div>
                                                            @endif
                                                        @break

                                                        @case(1)
                                                            <div class="p-2 Approved">Approved</div>
                                                        @break

                                                        @case(2)
                                                            <div class="btn mt-2 btn-danger">Rejected</div>
                                                        @break

                                                        @default
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.add_plus').each(function(index) {
                $(this).click(function() {
                    $(this).toggleClass('rotated');
                    $('.view_more').eq(index).toggle();
                });
            });
        });
    </script>
@endsection
