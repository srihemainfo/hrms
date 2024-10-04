@php
    $type_id = auth()->user()->roles[0]->type_id;
    if ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="row gutters">
        <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
            <div class="card">

                <div class="row">
                    <div class="col-11">
                        <div class="input-field" style="padding-left: 0.50rem;">
                            <input type="text" name="name" id="autocomplete-input"
                                style="margin:0;padding-left:0.50rem;" placeholder="Enter Staff Name   ( Staff Code )"
                                class="autocomplete" autocomplete="off"
                                @if ($name != '') value="{{ $name }}" @else value="" @endif required
                                onchange="run(this)">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if ($first_entry != '')
        <div class="pb-3" style="overflow:hidden;">
            <div class="bg-primary text-light student_label">
                {{-- <div style="padding-left:2%;"><i class="fa fa-chevron-left prev_page_bn" onclick="history.go(-1)"></i></div> --}}
                <div style="padding-right:2%;"> <span style="margin-right: 10px;"> STAFF NAME : {{ $staff->name }}</span>

                </div>
            </div>

            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="account-settings">
                                <div class="user-profile">
                                    <div class="user-avatar">
                                        @if (
                                            (isset($staff->filePath) ? $staff->filePath : '') != '' ||
                                                (isset($staff->filePath) ? $staff->filePath : '') != null)
                                            <img class="uploaded_img" src="{{ asset($staff->filePath) }}" alt="image">
                                        @else
                                            @if ($staff->gender == 'MALE' || $staff->gender == 'Male')
                                                <img src="{{ asset('adminlogo/male.png') }}" alt="Maxwell Admin">
                                            @elseif($staff->gender == 'FEMALE' || $staff->gender == 'Female')
                                                <img src="{{ asset('adminlogo/female.png') }}" alt="Maxwell Admin">
                                            @else
                                                <img src="{{ asset('adminlogo/user-image.png') }}" alt="">
                                            @endif
                                        @endif
                                    </div>
                                    <h5 class="user-name">{{ $staff->name }}</h5>
                                    <h6 class="user-email">{{ $staff->Designation }}</h6>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12" style="padding-left:0;">
                    <div class="card h-100 ">
                        <div class="card-body">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h5 class="mb-2 text-primary">Staff Info</h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" class="form-control" id="fullName"
                                            value="{{ $staff->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="">Staff Code</label>
                                        <input type="text" class="form-control" id=""
                                            value="{{ $staff->StaffCode }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Designation</label>
                                        <input type="text" class="form-control" id="phone"
                                            value="{{ $staff->Designation }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Department</label>
                                        <input type="text" class="form-control" id="phone"
                                            value="{{ $staff->Dept }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Email</label>
                                        <input type="text" class="form-control" id="phone"
                                            value="{{ $staff->EmailIDOffical ? $staff->EmailIDOffical : $staff->email }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone"
                                            value="{{ $staff->ContactNo ? $staff->ContactNo : $staff->phone }}" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @can('staff_edge_full_view')
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                        <div class="card" style="margin-top: 16px;">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Personal Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                <label for="name">First Name</label>
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ $detail->first_name }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" class="form-control" name="last_name"
                                                    value="{{ $detail->last_name }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ $detail->email }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="StaffCode">Staff Code</label>
                                                <input type="text" class="form-control" id="StaffCode" name="StaffCode"
                                                    value="{{ $detail->StaffCode }}" readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="BiometricID">Biometric ID</label>
                                        <input type="text" class="form-control" id="BiometricID"
                                            name="BiometricID" value="{{ $detail->BiometricID }}">
                                    </div>
                                </div> --}}
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
                                                <label for="emergency_contact_no">Emergency Contact Number</label>
                                                <input type="text" class="form-control" name="emergency_contact_no"
                                                    value="{{ $staff->emergency_contact_no }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="total_experience">Total Experience</label>
                                                <input type="text" class="form-control" name="total_experience"
                                                    value="{{ $detail->total_experience }}" readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="AICTE">AICTE</label>
                                        <input type="text" class="form-control" name="AICTE"
                                            value="{{ $detail->AICTE }}">
                                    </div>
                                </div> --}}
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="PanNo">Pan No</label>
                                                <input type="text" class="form-control" name="PanNo"
                                                    value="{{ $detail->PanNo }}" readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="DOJ">Date Of Joining</label>
                                        <input type="text" class="form-control date" name="DOJ"
                                            value="{{ $detail->DOJ }}">
                                    </div>
                                </div> --}}
                                        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="DOR">Date Of Relieving</label>
                                        <input type="text" class="form-control date" name="DOR"
                                            value="{{ $detail->DOR }}">
                                    </div>
                                </div> --}}

                                        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="au_card_no">Anna University Code</label>
                                        <input type="text" class="form-control" name="au_card_no"
                                            value="{{ $detail->au_card_no }}">
                                    </div>
                                </div> --}}
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="COECode">COE Code</label>
                                                <input type="text" class="form-control" name="COECode"
                                                    value="{{ $detail->COECode }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="PassportNo">Passport No</label>
                                                <input type="text" class="form-control" name="PassportNo"
                                                    value="{{ $detail->PassportNo }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="father_name">Father Name</label>
                                                <input type="text" class="form-control" name="father_name"
                                                    value="{{ $detail->father_name }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="marital_status">Marital Status</label>
                                                <input type="text" class="form-control" name="marital_status"
                                                    value="{{ $detail->marital_status }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="spouse_name">Spouse Name</label>
                                                <input type="text" class="form-control" name="spouse_name"
                                                    value="{{ $detail->spouse_name }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="dob">Date of Birth</label>
                                                <input class="form-control date" type="text" name="dob"
                                                    id="dob" value="{{ $detail->dob }}" readonly>
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
                                                    value="{{ $detail->gender }}" readonly>
                                            </div>
                                        </div>
                                        @if ($detail->blood_group_id != '')
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label for="blood_group_id">Blood Group</label>
                                                    @foreach ($detail->blood_group as $id => $entry)
                                                        @if ($detail->blood_group_id == $id)
                                                            <input type="text" class="form-control"
                                                                value="{{ $entry }}" readonly>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label for="blood_group_id">Blood Group</label>
                                                    <input type="text" class="form-control" readonly>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($detail->mother_tongue_id != '')
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label for="mother_tongue_id">Mother Tongue</label>
                                                    @foreach ($detail->mother_tongue as $id => $entry)
                                                        @if ($detail->mother_tongue_id == $id)
                                                            <input type="text" class="form-control"
                                                                value="{{ $entry }}" readonly>
                                                        @endif
                                                    @endforeach
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
                                                    @foreach ($detail->religion as $id => $entry)
                                                        @if ($detail->religion_id == $id)
                                                            <input type="text" class="form-control"
                                                                value="{{ $entry }}" readonly>
                                                        @endif
                                                    @endforeach
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

                                                    @foreach ($detail->community as $id => $entry)
                                                        @if ($detail->community_id == $id)
                                                            <input type="text" class="form-control"
                                                                value="{{ $entry }}" readonly>
                                                        @endif
                                                    @endforeach

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
                                                    value="{{ $detail->state }}" readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="known_languages">Languages Known</label>

                                                @if ($detail->known_languages != '')
                                                    @php
                                                        $languages = '';

                                                        foreach ($detail->known_languages as $key => $value) {
                                                            $languages .= ucfirst($value) . ',';
                                                        }
                                                    @endphp
                                                    <input type="text" class="form-control" value="{{ $languages }}"
                                                        readonly>
                                                @elseif($detail->known_languages == '')
                                                    <input type="text" class="form-control" readonly>
                                                @endif

                                            </div> --}}
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

            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                    <div class="card ">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Employment Details
                                    </h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
                                    <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                            style="font-size:1.5em;"></i></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body view_more" style="display:none;">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="BiometricID">Biometric ID</label>
                                        <input type="text" class="form-control" id="BiometricID" name="BiometricID"
                                            value="{{ $detail->BiometricID }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="AICTE">AICTE</label>
                                        <input type="text" class="form-control" name="AICTE"
                                            value="{{ $detail->AICTE }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="DOJ">Date Of Joining</label>
                                        <input type="text" class="form-control date" name="DOJ"
                                            value="{{ $detail->DOJ }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="DOR">Date Of Relieving</label>
                                        <input type="text" class="form-control date" name="DOR"
                                            value="{{ $detail->DOR }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="au_card_no">Anna University Code</label>
                                        <input type="text" class="form-control" name="au_card_no"
                                            value="{{ $detail->au_card_no }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="employment_type">Employment Type</label>
                                        <input type="text" class="form-control" name="employment_type"
                                            value="{{ $detail->employment_type }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="employment_status">Employment Status</label>
                                        <input type="text" class="form-control" name="employment_status"
                                            value="{{ $detail->employment_status }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="rit_club_incharge">RIT Club Incharge</label>
                                        @if ($detail->rit_club_incharge == true)
                                            <input type="text" class="form-control" name="rit_club_incharge"
                                                value="YES" readonly>
                                        @elseif($detail->rit_club_incharge == false)
                                            <input type="text" class="form-control" name="rit_club_incharge"
                                                value="NO" readonly>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="future_tech_membership">Future Tech Centre Membership</label>
                                        <input type="text" class="form-control" name="future_tech_membership"
                                            value="{{ $detail->future_tech_membership }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="future_tech_membership_type">Future Tech Centre Membership Type</label>
                                        <input type="text" class="form-control" name="future_tech_membership_type"
                                            value="{{ $detail->future_tech_membership_type }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (count($phd_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Ph.D
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Institution/Research Center Name
                                            </th>
                                            <th>
                                                University Name
                                            </th>
                                            <th>
                                                Title of the Thesis
                                            </th>
                                            <th>
                                                Area of the Research
                                            </th>
                                            <th>
                                                Supervisor Name
                                            </th>
                                            <th>
                                                Supervisor Details
                                            </th>
                                            <th>
                                                Status
                                            </th>
                                            <th>
                                                Month and Year of Registration
                                            </th>
                                            <th>
                                                Viva Voce Date
                                            </th>
                                            <th>
                                                Total No of Year
                                            </th>
                                            <th>
                                                Mode
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @for ($i = 0; $i < count($phd_list); $i++)
                                            <tr>

                                                <td>{{ $phd_list[$i]->institute_name }}</td>
                                                <td>{{ $phd_list[$i]->university_name }}</td>
                                                <td>{{ $phd_list[$i]->thesis_title }}</td>
                                                <td>{{ $phd_list[$i]->research_area }}</td>
                                                <td>{{ $phd_list[$i]->supervisor_name }}</td>
                                                <td>{{ $phd_list[$i]->supervisor_details }}</td>
                                                <td>{{ $phd_list[$i]->status }}</td>

                                                @php
                                                    $date = $phd_list[$i]->registration_year;
                                                    $timestamp = strtotime($date);
                                                    $registration_year = date('Y-m', $timestamp);
                                                @endphp
                                                <td>{{ $registration_year }}</td>
                                                <td>{{ $phd_list[$i]->viva_date }}</td>
                                                <td>{{ $phd_list[$i]->total_years }}</td>
                                                <td>{{ $phd_list[$i]->mode }}</td>

                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($experience_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Experience
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Designation
                                            </th>
                                            <th>
                                                Department
                                            </th>
                                            <th>
                                                Organisation Name
                                            </th>
                                            <th>
                                                Taken Subjects
                                            </th>
                                            <th>
                                                Date Of Joining
                                            </th>
                                            <th>
                                                Date Of Leaving
                                            </th>
                                            <th>
                                                Last Drawn Salary
                                            </th>
                                            <th>
                                                Responsibilities
                                            </th>
                                            <th>
                                                leave Reason
                                            </th>
                                            <th>
                                                Address
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @for ($i = 0; $i < count($experience_list); $i++)
                                            <tr>
                                                <td>{{ $experience_list[$i]->designation }}</td>
                                                <td>{{ $experience_list[$i]->department }}</td>
                                                <td>{{ $experience_list[$i]->name_of_organisation }}</td>
                                                <td>{{ $experience_list[$i]->taken_subjects }}</td>
                                                <td>{{ $experience_list[$i]->doj }}</td>
                                                <td>{{ $experience_list[$i]->dor }}</td>
                                                <td>{{ $experience_list[$i]->last_drawn_salary }}</td>
                                                <td>{{ $experience_list[$i]->responsibilities }}</td>
                                                <td>{{ $experience_list[$i]->leaving_reason }}</td>
                                                <td>{{ $experience_list[$i]->address }}</td>

                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($promotiondetails_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Promotion
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
                                        <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                                style="font-size:1.5em;"></i></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive view_more" style="display:none;">
                                <table class="list_table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Current Designation
                                            </th>
                                            <th>
                                                Promoted Designation
                                            </th>
                                            <th>
                                                Promotion Date
                                            </th>
                                            {{-- <th>
                                                Status
                                            </th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @for ($i = 0; $i < count($promotiondetails_list); $i++)
                                            <tr>
                                                <td>
                                                    @foreach ($roles as $id => $entry)
                                                        @if ($id == $promotiondetails_list[$i]->current_designation)
                                                            {{ $entry }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($roles as $id => $entry)
                                                        @if ($id == $promotiondetails_list[$i]->promoted_designation)
                                                            {{ $entry }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $promotiondetails_list[$i]->promotion_date }}</td>
                                                {{-- <td>
                                                    <button class="btn btn-xs btn-success">Approved</button>
                                                </td> --}}
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($education_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Educational
                                            Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Qualification
                                            </th>
                                            <th>
                                                Course Duration
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
                                                Marks In Percentage / CGPA
                                            </th>
                                            <th>
                                                Medium
                                            </th>
                                            <th>
                                                Month/Year
                                            </th>
                                            <th>
                                                Mode Of Study
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
                                                <td>{{ $education_list[$i]->qualification }}</td>
                                                <td>{{ $education_list[$i]->course_duration }}</td>
                                                <td>{{ $education_list[$i]->institute_name }}</td>
                                                <td>{{ $education_list[$i]->institute_location }}</td>
                                                <td>{{ $education_list[$i]->board_or_university }}</td>
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
                                                <?php
                                                $date = $education_list[$i]->month_value;
                                                $timestamp = strtotime($date);
                                                $month_valuee = date('Y-m', $timestamp);

                                                ?>
                                                <td>{{ $month_valuee }}</td>
                                                <td>{{ $education_list[$i]->study_mode }}</td>
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
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Address Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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

            @if (count($bank_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Bank Account
                                            Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Account Type
                                            </th>
                                            <th>
                                                Account No
                                            </th>
                                            <th>
                                                IFSC Code
                                            </th>
                                            <th>
                                                Bank Name
                                            </th>
                                            <th>
                                                Branch Name
                                            </th>
                                            <th>
                                                Bank Location
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($bank_list); $i++)
                                            <tr>
                                                <td>{{ $bank_list[$i]->account_type }}</td>
                                                <td>{{ $bank_list[$i]->account_no }}</td>
                                                <td>{{ $bank_list[$i]->ifsc_code }}</td>
                                                <td>{{ $bank_list[$i]->bank_name }}</td>
                                                <td>{{ $bank_list[$i]->branch_name }}</td>
                                                <td>{{ $bank_list[$i]->bank_location }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @can('staff_salary_access')
                @if (count($salary_list) > 0)
                    <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                        <div class="col" style="padding:0;">
                            <div class="card ">
                                <div class="card-header">
                                    <div class="row gutters">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Salary Details
                                            </h5>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                    Salary Type
                                                </th>
                                                <th>
                                                    Basic Pay
                                                </th>
                                                <th>
                                                    Ph.D Allowance
                                                </th>
                                                <th>
                                                    AGP
                                                </th>
                                                <th>
                                                    Special Pay
                                                </th>
                                                <th>
                                                    HRA
                                                </th>
                                                {{-- <th>
                                                    DA
                                                </th> --}}
                                                <th>
                                                    Other Allowence
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @for ($i = 0; $i < count($salary_list); $i++)
                                                <tr>
                                                    @if ($salary_list[$i] == $salary_list[0])
                                                        <td>Default Salary</td>
                                                    @else
                                                        <td>Increment</td>
                                                    @endif
                                                    <td>{{ $salary_list[$i]->basic_pay }}</td>
                                                    <td>{{ $salary_list[$i]->phd_allowance }}</td>
                                                    <td>{{ $salary_list[$i]->agp }}</td>
                                                    <td>{{ $salary_list[$i]->special_pay }}</td>
                                                    <td>{{ $salary_list[$i]->hra }}</td>
                                                    {{-- <td>{{ $salary_list[$i]->da }}</td> --}}
                                                    <td>{{ $salary_list[$i]->other_allowances }}</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

            @if (count($leave_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Requested Leave
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Subject
                                            </th>
                                            <th>
                                                Rejected Reason
                                            </th>
                                            <th>
                                                Altered Staff
                                            </th>
                                            <th>
                                                Status
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @for ($i = 0; $i < count($leave_list); $i++)
                                            @if ($leave_list[$i]->leave_type != '' || $leave_list[$i]->leave_type != null)
                                                <tr>
                                                    @foreach ($leave_list[$i]->leave_types as $id => $entry)
                                                        @if ($leave_list[$i]->leave_type == $id)
                                                            <td>{{ $entry }}</td>
                                                        @endif
                                                    @endforeach
                                                    <td>{{ $leave_list[$i]->from_date }}</td>
                                                    <td>{{ $leave_list[$i]->to_date }}</td>
                                                    <td>{{ $leave_list[$i]->subject }}</td>
                                                    <td>{{ $leave_list[$i]->rejected_reason }}</td>
                                                    <td>
                                                        @if ($leave_list[$i]->assigning_staff)
                                                            @php
                                                                $staffName = \App\Models\TeachingStaff::where(
                                                                    'user_name_id',
                                                                    $leave_list[$i]->assigning_staff,
                                                                )->first();
                                                            @endphp
                                                            {{ isset($staffName->name) ? $staffName->name : '' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($leave_list[$i]->status == 'Pending')
                                                            <div class="p-2 Pending">Pending</div>
                                                        @elseif($leave_list[$i]->status == 'Approved')
                                                            <div class="p-2 Approved">Approved</div>
                                                        @elseif($leave_list[$i]->status == 'Rejected')
                                                            <div class="btn mt-2 btn-danger">Rejected</div>
                                                        @else
                                                        @endif
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

            @if (count($conference_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Conference
                                            Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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

            @if (count($exam_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Entrance Exams
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Exam Types
                                            </th>
                                            <th>
                                                Passing Year
                                            </th>
                                            <th>
                                                Scored Mark
                                            </th>
                                            <th>
                                                Total Mark
                                            </th>
                                            <th>
                                                Rank
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @for ($i = 0; $i < count($exam_list); $i++)
                                            @if ($exam_list[$i]->exam_type_id != '' || $exam_list[$i]->exam_type_id != null)
                                                <tr>

                                                    @foreach ($exam_list[$i]->exam_types as $id => $entry)
                                                        @if ($exam_list[$i]->exam_type_id == $id)
                                                            <td>{{ $entry }}</td>
                                                        @endif
                                                    @endforeach

                                                    <td>{{ $exam_list[$i]->passing_year }}</td>
                                                    <td>{{ $exam_list[$i]->scored_mark }}</td>
                                                    <td>{{ $exam_list[$i]->total_mark }}</td>
                                                    <td>{{ $exam_list[$i]->rank }}</td>

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

            @if (count($guest_lecture_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Guest Lectures
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                        @for ($i = 0; $i < count($guest_lecture_list); $i++)
                                            <tr>
                                                <td>{{ $guest_lecture_list[$i]->topic }}</td>
                                                <td>{{ $guest_lecture_list[$i]->from_date_and_time }}</td>
                                                <td>{{ $guest_lecture_list[$i]->to_date_and_time }}</td>
                                                <td>{{ $guest_lecture_list[$i]->location }}</td>
                                                <td>{{ $guest_lecture_list[$i]->remarks }}</td>
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
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Industrial
                                            Training
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Interns Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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

            @if (count($indus_exp_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Industrial
                                            Experience
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Work Experience
                                            </th>
                                            <th>
                                                Designation
                                            </th>
                                            <th>
                                                From
                                            </th>
                                            <th>
                                                To
                                            </th>
                                            <th>
                                                Work Type
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($indus_exp_list); $i++)
                                            <tr>
                                                <td>{{ $indus_exp_list[$i]->work_experience }}</td>
                                                <td>{{ $indus_exp_list[$i]->designation }}</td>
                                                <td>{{ $indus_exp_list[$i]->from }}</td>
                                                <td>{{ $indus_exp_list[$i]->to }}</td>
                                                <td>{{ $indus_exp_list[$i]->work_type }}</td>
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
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Iv Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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

            @if (count($event_organized_details_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Event Organized
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Event Type
                                            </th>
                                            <th>
                                                Event Title
                                            </th>
                                            <th>
                                                Funding Support
                                            </th>
                                            <th>
                                                Event Audience Category
                                            </th>
                                            <th>
                                                Coordinated SJFC
                                            </th>
                                            <th>
                                                Participations
                                            </th>
                                            <th>
                                                Event Duration
                                            </th>
                                            <th>
                                                Start Date
                                            </th>
                                            <th>
                                                End Date
                                            </th>
                                            <th>
                                                Total Participants
                                            </th>
                                            <th>
                                                Chief Guest(s) Information
                                            </th>
                                            <th>
                                                Certificate/Approval Letter
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($event_organized_details_list); $i++)
                                            <tr>
                                                @if ($event_organized_details_list[$i]->event_type != '' || $event_organized_details_list[$i]->event_type != null)
                                                    @foreach ($event_organized_details_list[$i]->event as $id => $entry)
                                                        @if ($id == $event_organized_details_list[$i]->event_type)
                                                            <td>{{ $entry }}</td>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>{{ $event_organized_details_list[$i]->title }}</td>
                                                <td>{{ $event_organized_details_list[$i]->funding_support }}</td>
                                                <td>{{ $event_organized_details_list[$i]->coordinated_sjfc }}</td>
                                                <td>{{ $event_organized_details_list[$i]->audience_category }}</td>
                                                <td>{{ $event_organized_details_list[$i]->participants }}</td>
                                                <td>{{ $event_organized_details_list[$i]->event_duration }}</td>
                                                <td>{{ $event_organized_details_list[$i]->start_date }}</td>
                                                <td>{{ $event_organized_details_list[$i]->end_date }}</td>
                                                <td>{{ $event_organized_details_list[$i]->total_participants }}</td>
                                                <td>{{ $event_organized_details_list[$i]->chiefguest_information }}</td>
                                                @if ($event_organized_details_list[$i]->certificate != '' && $event_organized_details_list[$i]->certificate != null)
                                                    <td>
                                                        <img class="uploaded_img"
                                                            src="{{ asset($event_organized_details_list[$i]->certificate) }}"
                                                            alt="image">
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($event_participation_details_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Event
                                            Participation
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Event Category
                                            </th>
                                            <th>
                                                Event Type
                                            </th>
                                            <th>
                                                Event Title
                                            </th>
                                            <th>
                                                Organized By
                                            </th>
                                            <th>
                                                Event Location
                                            </th>
                                            <th>
                                                Event Duration
                                            </th>
                                            <th>
                                                Start Date
                                            </th>
                                            <th>
                                                End Date
                                            </th>
                                            <th>
                                                Certificate
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($event_participation_details_list); $i++)
                                            <tr>
                                                <td>{{ $event_participation_details_list[$i]->event_category }}</td>
                                                @if (
                                                    $event_participation_details_list[$i]->event_type != '' ||
                                                        $event_participation_details_list[$i]->event_type != null)
                                                    @foreach ($event_participation_details_list[$i]->event as $id => $entry)
                                                        @if ($id == $event_participation_details_list[$i]->event_type)
                                                            <td>{{ $entry }}</td>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>{{ $event_participation_details_list[$i]->title }}</td>
                                                <td>{{ $event_participation_details_list[$i]->organized_by }}</td>
                                                <td>{{ $event_participation_details_list[$i]->event_location }}</td>
                                                <td>{{ $event_participation_details_list[$i]->event_duration }}</td>
                                                <td>{{ $event_participation_details_list[$i]->start_date }}</td>
                                                <td>{{ $event_participation_details_list[$i]->end_date }}</td>
                                                @if (
                                                    $event_participation_details_list[$i]->certificate != '' &&
                                                        $event_participation_details_list[$i]->certificate != null)
                                                    <td>
                                                        <img class="uploaded_img"
                                                            src="{{ asset($event_participation_details_list[$i]->certificate) }}"
                                                            alt="image">
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif

                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($online_course_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Online Courses
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Course Name
                                            </th>
                                            <th>
                                                From
                                            </th>
                                            <th>
                                                To
                                            </th>
                                            <th>
                                                Remarks
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($online_course_list); $i++)
                                            <tr>
                                                <td>{{ $online_course_list[$i]->course_name }}</td>
                                                <td>{{ $online_course_list[$i]->from_date }}</td>
                                                <td>{{ $online_course_list[$i]->to_date }}</td>
                                                <td>{{ $online_course_list[$i]->remark }}</td>
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
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Document Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Seminars Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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

            @if (count($sabotical_details_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Saboticals
                                            Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($sabotical_details_list); $i++)
                                            <tr>
                                                <td>{{ $sabotical_details_list[$i]->topic }}</td>
                                                <td>{{ $sabotical_details_list[$i]->from }}</td>
                                                <td>{{ $sabotical_details_list[$i]->to }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($sponser_details_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Sponser Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Sponser Name
                                            </th>
                                            <th>
                                                Project Title
                                            </th>
                                            <th>
                                                Project Duration
                                            </th>
                                            <th>
                                                Application Date
                                            </th>
                                            <th>
                                                Application Status
                                            </th>
                                            <th>
                                                Investigator Level
                                            </th>
                                            <th>
                                                Sponsered Amount
                                            </th>
                                            <th>
                                                Amount Received Date
                                            </th>
                                            <th>
                                                Sanctioned Letter
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($sponser_details_list); $i++)
                                            <tr>
                                                <td>{{ $sponser_details_list[$i]->sponser_name }}</td>
                                                <td>{{ $sponser_details_list[$i]->project_title }}</td>
                                                <td>{{ $sponser_details_list[$i]->project_duration }}</td>
                                                <td>{{ $sponser_details_list[$i]->application_date }}</td>
                                                <td>{{ $sponser_details_list[$i]->application_status }}</td>
                                                <td>{{ $sponser_details_list[$i]->investigator_level }}</td>
                                                <td>{{ $sponser_details_list[$i]->funding_amount }}</td>
                                                <td>{{ $sponser_details_list[$i]->received_date }}</td>
                                                @if ($sponser_details_list[$i]->sanctioned_letter != '' && $sponser_details_list[$i]->sanctioned_letter != null)
                                                    <td>
                                                        <img class="uploaded_img"
                                                            src="{{ asset($sponser_details_list[$i]->sanctioned_letter) }}"
                                                            alt="image">
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($sttp_details_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">STTP Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                From Date
                                            </th>
                                            <th>
                                                To Date
                                            </th>
                                            <th>
                                                Remarks
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($sttp_details_list); $i++)
                                            <tr>
                                                <td>{{ $sttp_details_list[$i]->topic }}</td>
                                                <td>{{ $sttp_details_list[$i]->from }}</td>
                                                <td>{{ $sttp_details_list[$i]->to }}</td>
                                                <td>{{ $sttp_details_list[$i]->remark }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($workshop_details_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Workshop Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                From Date
                                            </th>
                                            <th>
                                                To Date
                                            </th>
                                            <th>
                                                Remarks
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($workshop_details_list); $i++)
                                            <tr>
                                                <td>{{ $workshop_details_list[$i]->topic }}</td>
                                                <td>{{ $workshop_details_list[$i]->from_date }}</td>
                                                <td>{{ $workshop_details_list[$i]->to_date }}</td>
                                                <td>{{ $workshop_details_list[$i]->remarks }}</td>
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
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Patent Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Title
                                            </th>
                                            <th>
                                                Application No
                                            </th>
                                            <th>
                                                Date Of Application
                                            </th>
                                            <th>
                                                Application Status
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($patent_details_list); $i++)
                                            <tr>
                                                <td>{{ $patent_details_list[$i]->title }}</td>
                                                <td>{{ $patent_details_list[$i]->application_no }}</td>
                                                <td>{{ $patent_details_list[$i]->application_date }}</td>
                                                <td>{{ $patent_details_list[$i]->application_status }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($award_details_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Award Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Name of the Award
                                            </th>
                                            <th>
                                                Organizer Name
                                            </th>
                                            <th>
                                                Awarded Date
                                            </th>
                                            <th>
                                                Venue Details
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($award_details_list); $i++)
                                            <tr>
                                                <td>{{ $award_details_list[$i]->title }}</td>
                                                <td>{{ $award_details_list[$i]->organizer_name }}</td>
                                                <td>{{ $award_details_list[$i]->awarded_date }}</td>
                                                <td>{{ $award_details_list[$i]->venue }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($permissionrequest_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Permission
                                            Request
                                            Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                From Time
                                            </th>
                                            <th>
                                                To Time
                                            </th>
                                            <th>
                                                Date
                                            </th>
                                            <th>
                                                Reason
                                            </th>
                                            <th>
                                                Status
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($permissionrequest_list); $i++)
                                            <tr>
                                                <td>{{ $permissionrequest_list[$i]->from_time }}</td>
                                                <td>{{ $permissionrequest_list[$i]->to_time }}</td>
                                                <td>{{ $permissionrequest_list[$i]->date }}</td>
                                                <td>{{ $permissionrequest_list[$i]->reason }}</td>
                                                <td>
                                                    @if ($permissionrequest_list[$i]->status == '0')
                                                        <div class="p-2 Pending">
                                                            Pending
                                                        </div>
                                                    @elseif ($permissionrequest_list[$i]->status == '1')
                                                        <div class="p-2 Approved">
                                                            Approved
                                                        </div>
                                                    @elseif ($permissionrequest_list[$i]->status == '2')
                                                        <div class="btn mt-2 btn-danger">
                                                            Rejected
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($publication_details_list) > 0)
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col" style="padding:0;">
                        <div class="card ">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Publication
                                            Details
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
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
                                                Publication Type
                                            </th>
                                            <th id="Title_1" class="box Title_1">
                                                Title
                                            </th>
                                            <th id="Journal_Name_1" class="box Journal_Name_1">
                                                Journal Name
                                            </th>
                                            <th id="Title_of_Book_Series_1" class="box">
                                                Title of Book Series
                                            </th>
                                            <th id="Publisher_1" class="box">
                                                Publisher
                                            </th>
                                            <th id="Organized_By_1" class="box">
                                                Organized By
                                            </th>
                                            <th id="ISBN_No_1" class="box">
                                                ISBN/ISSN No
                                            </th>
                                            <th id="DOI_1" class="box">
                                                DOI
                                            </th>
                                            <th id="Proceedings_Name_1" class="box">
                                                Proceedings Name
                                            </th>
                                            <th id="Volume_No_1" class="box">
                                                Volume No
                                            </th>
                                            <th id="Issue_1" class="box">
                                                Issue
                                            </th>
                                            <th id="Pages_1" class="box">
                                                Pages
                                            </th>
                                            <th id="Scopus_1" class="box">
                                                Scopus
                                            </th>
                                            <th id="SCIE_1" class="box">
                                                SCIE(WOS)
                                            </th>
                                            <th id="ESCI_1" class="box">
                                                ESCI(WOS)
                                            </th>
                                            <th id="AHCI_1" class="box">
                                                AHCI(WOS)
                                            </th>
                                            <th id="UGC_1" class="box">
                                                UGC
                                            </th>
                                            <th id="Others_1" class="box">
                                                Others
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($publication_details_list); $i++)
                                            <tr>
                                                <td class="box col1-data" id="publication_type_2" class="box">
                                                    {{ $publication_details_list[$i]->publication_type }}</td>
                                                <td class="box col2-data" id="paper_title_2">
                                                    {{ $publication_details_list[$i]->paper_title }}
                                                </td>
                                                <td class="box col3-data" id="journal_name_2">
                                                    {{ $publication_details_list[$i]->journal_name }}
                                                </td>
                                                <td class="box col4-data" id="book_series_title_2">
                                                    {{ $publication_details_list[$i]->book_series_title }}</td>
                                                <td class="box col5-data" id="publisher_2">
                                                    {{ $publication_details_list[$i]->publisher }}</td>
                                                <td class="box col6-data" id="organized_by_2">
                                                    {{ $publication_details_list[$i]->organized_by }}
                                                </td>
                                                <td class="box col7-data" id="issn_no_2">
                                                    {{ $publication_details_list[$i]->issn_no }}</td>
                                                <td class="box col8-data" id="doi_2">
                                                    {{ $publication_details_list[$i]->doi }}</td>
                                                <td class="box col9-data" id="proceeding_name_2">
                                                    {{ $publication_details_list[$i]->proceeding_name }}
                                                </td>
                                                <td class="box col10-data" id="volume_no_2">
                                                    {{ $publication_details_list[$i]->volume_no }}</td>
                                                <td class="box col11-data" id="issue_2">
                                                    {{ $publication_details_list[$i]->issue }}</td>
                                                <td class="box col12-data" id="pages_2">
                                                    {{ $publication_details_list[$i]->pages }}</td>
                                                <td class="box col13-data" id="scopus_2">
                                                    {{ $publication_details_list[$i]->scopus }}</td>
                                                <td class="box col14-data" id="scie_2">
                                                    {{ $publication_details_list[$i]->scie }}</td>
                                                <td class="box col15-data" id="esci_2">
                                                    {{ $publication_details_list[$i]->esci }}</td>
                                                <td class="box col16-data" id="ahci_2">
                                                    {{ $publication_details_list[$i]->ahci }}</td>
                                                <td class="box col17-data" id="ugc_2">
                                                    {{ $publication_details_list[$i]->ugc }}</td>
                                                <td class="box col18-data" id="others_2">
                                                    {{ $publication_details_list[$i]->others }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endcan
        </div>
    @endif
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"
        integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const staff = [];

        let loader = document.getElementById("loader");

        let given_data = document.getElementById("given_data");

        let input = document.getElementById("autocomplete-input");



        window.onload = function() {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.staff-edge.geter') }}',
                type: 'POST',
                data: {
                    'data': 'geter'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // console.log(data);
                    let details = data.staff;
                    let staff = {};
                    // console.log(details)
                    for (let i = 0; i < details.length; i++) {
                        staff[details[i]] = null;
                    }
                    // console.log(staff)
                    $('input.autocomplete').autocomplete({
                        data: staff,
                    });
                    $('#loading').hide();

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                    $('#loading').hide();
                }
            });

        }

        function run(element) {
            if (/[0-9]/.test($(element).val()) && /[a-zA-Z]/.test($(element).val())) {
                var a = $(element).val();
                window.location.href = "{{ url('admin/teaching-staff-edge') }}/" + a;
            }
        }
    </script>
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
