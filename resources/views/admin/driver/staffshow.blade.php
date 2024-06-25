@if (auth()->user()->id == $staff->user_name_id &&
        (auth()->user()->roles[0]->id != 4 && auth()->user()->roles[0]->id != 3))
    @php
        $one = 'layouts.non_techStaffHome';
    @endphp
@elseif (auth()->user()->id != $staff->user_name_id ||
        (auth()->user()->roles[0]->id == 4 || auth()->user()->roles[0]->id == 3))
    @php
        $one = 'layouts.admin';
    @endphp
@endif
@extends($one)
@section('content')
    <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
    <div class="col pb-3" style="overflow:hidden;">
        <div class="bg-primary text-light student_label">
            <div style="padding-left:2%;"><i class="fa fa-chevron-left prev_page_bn" onclick="history.go(-1)"></i></div>
            <div style="padding-right:2%;"> <span style="margin-right: 10px;"> STAFF NAME : {{ $staff->name }}</span>
                @if (auth()->user()->id == $staff->user_name_id)
                    <a href="{{ url('admin/driver/' . auth()->user()->id . '/Profile-edit') }}"
                        style="margin-left:10px;" title="Edit Profile"><i class="fas fa-edit"></i></a>
                @endif
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
                                            <img src="{{ asset('adminlogo/male.png') }}">
                                        @elseif($staff->gender == 'FEMALE' || $staff->gender == 'Female')
                                            <img src="{{ asset('adminlogo/female.png') }}">
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
                                    <input type="text" class="form-control" id="fullName" value="{{ $staff->name }}"
                                        readonly>
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
                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Department</label>
                                    <input type="text" class="form-control" id="phone" value="{{ $staff->Dept }}"
                                        readonly>
                                </div>
                            </div> --}}
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Email</label>
                                    <input type="text" class="form-control" id="phone"
                                        value="{{ $staff->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone"
                                        value="{{ $staff->phone }}" readonly>
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

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="PanNo">Pan No</label>
                                        <input type="text" class="form-control" name="PanNo"
                                            value="{{ $detail->PanNo }}" readonly>
                                    </div>
                                </div>

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
                                        <input class="form-control date" type="text" name="dob" id="dob"
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
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
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
                                        <th>
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @for ($i = 0; $i < count($promotiondetails_list); $i++)
                                        <tr>
                                            <td>{{ $promotiondetails_list[$i]->current_designation }}</td>
                                            <td>
                                                @if (($promotiondetails_list[$i]->promoted_designation != '' || $promotiondetails_list[$i]->promoted_designation != null) && ($promotiondetails_list[$i]->designation != null))
                                                    @foreach ($promotiondetails_list[$i]->designation as $id => $entry)
                                                        @if ($promotiondetails_list[$i]->promoted_designation == $id)
                                                            {{ $entry }}
                                                        @endif
                                                    @endforeach
                                                @else
                                                @endif
                                            </td>
                                            <td>{{ $promotiondetails_list[$i]->promotion_date }}</td>
                                            <td>
                                                <button class="btn btn-xs btn-outline-success">Approved</button>
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
                                                            $staffName = \App\Models\TeachingStaff::where('user_name_id', $leave_list[$i]->assigning_staff)->first();
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
                                                        <div class="btn mt-2 btn-outline-danger">Rejected</div>
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

        @if (count($permissionrequest_list) > 0)
            <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                <div class="col" style="padding:0;">
                    <div class="card ">
                        <div class="card-header">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Permission Request
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
                                                    <div class="btn mt-2 btn-outline-danger">
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
