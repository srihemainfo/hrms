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
                                style="margin:0;padding-left:0.50rem;"
                                placeholder="Enter Student Name  (Student Register No)" class="autocomplete"
                                autocomplete="off"
                                @if ($name != '') value="{{ $name }}" @else value="" @endif required
                                onchange="run(this)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($name != '')
            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <div class="card">
                            <a style="color: transparent;" onclick="attendance({{ $student->user_name_id ?? '' }})">
                                <div class="" id="attendance" style="cursor:pointer;">
                                    <div class="card-body text-center" style="padding:13px 0px;">
                                        {{-- <div> --}}
                                        <h5 style="margin:0;">Attendance</h5>
                                        {{-- </div> --}}
                                        {{-- <div style="font-size:1.5rem;">
                                        {{ $theAttPercentage }} <span style="font-size:1rem;">%</span>
                                    </div> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <div class="card rounded-3">
                            <a style="color: transparent;" onclick="catmark({{ $student->user_name_id ?? '' }})">
                                <div class="" id="cat" style="cursor:pointer;">
                                    <div class="card-body text-center" style="padding:13px 0px;">
                                        {{-- <div> --}}
                                        <h5 style="margin:0;">Cat Mark</h5>
                                        {{-- </div> --}}
                                        {{-- <div style="font-size:1.5rem;">
                                        {{ $theAttPercentage }} <span style="font-size:1rem;">%</span>
                                    </div> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <div class="card rounded-3">
                            <a style="color: transparent;" onclick="labmark({{ $student->user_name_id ?? '' }})">
                                <div class="" id="cat" style="cursor:pointer;">
                                    <div class="card-body text-center" style="padding:13px 0px;">
                                        {{-- <div> --}}
                                        <h5 style="margin:0;">LAB Mark</h5>
                                        {{-- </div> --}}
                                        {{-- <div style="font-size:1.5rem;">
                                        {{ $theAttPercentage }} <span style="font-size:1rem;">%</span>
                                    </div> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>



                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <div class="card" id="grade" style="cursor:pointer;">
                            <div class="card-body text-center" style="padding:13px 0px;">
                                {{-- <div> --}}
                                <h5 style="margin:0;">Grade Book</h5>
                                {{-- </div>
                            <div style="font-size:1.5rem;">-<span style="font-size:1rem;"></span>
                            </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <style>
        .card {
            margin-bottom: 7.5px !important;
        }

        #attendance {
            /* background-color: whitesmoke; */
            color: #007bff;
        }

        #cat {
            /* background-color: #28a745; */
            color: #28a745;
        }

        #grade {
            /* background-color: #ffc107; */
            color: #ffc107;
        }

        #attendance:hover {
            background-color: #007bff;
            color: whitesmoke;
            border-radius: 5px;
        }

        #cat:hover {
            background-color: #28a745;
            color: whitesmoke;
            border-radius: 5px;
        }

        #grade:hover {
            background-color: #ffc107;
            color: black;
            border-radius: 5px;
        }
    </style>
    @if ($name != '')
        <div class="pb-3" style="overflow:hidden;">

            <div class="bg-primary text-light student_label">
                {{-- <div style="padding-left:2%;"><i class="fa fa-chevron-left prev_page_bn" onclick="history.go(-1)"></i></div> --}}
                <div style="padding-right:2%;"> <span style="margin-right: 10px;"> STUDENT NAME :
                        {{ $student->name }}</span>

                </div>
            </div>

            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
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
                                                <img src="{{ asset('adminlogo/male.png') }}" alt="">
                                            @elseif($student->gender == 'FEMALE' || $student->gender == 'Female')
                                                <img src="{{ asset('adminlogo/female.png') }}" alt="">
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
                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12" style="padding-left:0;">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h5 class="mb-2 text-primary">Student Info</h5>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" class="form-control" id="fullName"
                                            value="{{ $student->name }}" readonly>
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



            <!-- Student_attendance View Page  Start-->

            <div class="card mt-2" id = 'Attendance_card' style='display:none'>
                <div class="card-header text-center">
                    <strong>Attendence Report</strong>
                </div>

                <div class="card-body">
                    <div class="row ">
                        <div class="col-md-3 col-5 mb-2" id='Academic_year'>

                        </div>
                        <div class=" col-md-3 col-1"></div>
                        <div class="col-md-3 col-1"></div>
                        {{-- <div class="col-3 mb-2">
                    Semester : {{ $sem ?? '' }}
                </div> --}}
                        <div class="col-md-3 col-5 mb-2">
                            Semester: <strong id='Semester'> </strong>
                        </div>
                    </div>
                    <div style="height: 2px; background-color: #dee2e6;" class="mb-4"></div>
                    <div class="div">
                        <table
                            class="table table-bordered table-response table-striped table-hover ajaxTable datatable datatable-TaskTag text-center"
                            id="studentAttendence">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Sl/No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Faculty Name</th>
                                    <th>No Of Periods Attended</th>
                                    <th>Total No Of Periods</th>
                                    <th>Attendence Percentage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id='Student_attendace_details'>


                            </tbody>
                        </table>
                        <p>
                            <strong>Important :</strong> <span class="text-primary">If you secured less than 75%
                                attendence, not
                                eligible to write the
                                particular subject.</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Student_attendance View Page End -->
            <!-- Student Cat Mark Start -->

            <style>
                .null-cell {
                    color: red;
                }

                .table-container {
                    max-height: 500px;
                    overflow-y: auto;
                }
            </style>
            <div class="card mt-2" id='cat_mark_view' style='display:none'>
                <div class="card-header text-center">
                    <strong id='head'>CAT Marks</strong>
                </div>
                <div class="card-body">

                    <div class="table-responsive table-container">
                        <table class=" table table-bordered text-center table-striped table-hover ">
                            <thead id='title'>

                            </thead>
                            <thead id='mark_details'>

                            </thead>
                            <tbody id = 'student_mark_details'>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Student Cat Mark End -->
            <!-- Error_message for NO Data Start -->
            <div class="card mt-2" id='error_messages' style='display:none'>
                <div class="card-body">
                    <p class="text-center">NO Data Available</p>
                </div>
            </div>
            <!-- Error_message for NO Data end -->

            <div class="row gutters" style="margin:15px 0px;">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                    <div class="card">
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
                                            <label for="emis_number">Emis Number</label>
                                            <input type="text" class="form-control" name="emis_number"
                                                value="{{ old('emis_number', $academic_list->emis_number) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="admitted_mode">Admitted Mode</label>
                                            <input type="text" class="form-control" name="admitted_mode"
                                                value="{{ old('admitted_mode', $academic_list->admitted_mode) }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="first_graduate">First Graduate</label>
                                            <input type="text" class="form-control" name="first_graduate"
                                                value="{{ $academic_list->first_graduate == '1' ? 'Yes' : 'No' }}"
                                                readonly>
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
                                                value="{{ $academic_list->scholarDetail != null ? $academic_list->scholarDetail->name : '' }}" readonly>
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
                                                <input type="text" class="form-control"
                                                    value="{{ $academic_list->sem }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="enroll_master_number_id">Section</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $academic_list->section }}" readonly>
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
                                    @if ($parent->father_off_address != '')
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="father_off_address">Father's Office Address</label>
                                                <textarea name="father_off_address" class="form-control" readonly>{{ $parent->father_off_address }}</textarea>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="father_off_address">Father's Office Address</label>
                                                <input type="text" class="form-control" value="" readonly>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($parent->mother_off_address != '')
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="mother_off_address">Mother's Office Address</label>
                                                <textarea name="mother_off_address" class="form-control" readonly>{{ $parent->mother_off_address }}</textarea>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="mother_off_address">Mother's Office Address</label>
                                                <input type="text" class="form-control" value="" readonly>
                                            </div>
                                        </div>
                                    @endif
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
                                                value="{{ $parent->gaurdian_occupation }}" readonly>
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
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Educational
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
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Conference
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
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Industrial
                                            Training
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
        </div>
    @endif
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"
        integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const student = [];

        let loader = document.getElementById("loader");

        let given_data = document.getElementById("given_data");

        let input = document.getElementById("autocomplete-input");

        window.onload = function() {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.student-edge.geter') }}',
                type: 'POST',
                data: {
                    'data': 'geter'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                    let details = data.student;
                    let student = {};
                    // console.log(details)
                    for (let i = 0; i < details.length; i++) {
                        student[details[i]] = null;
                    }
                    // console.log(student)
                    $('input.autocomplete').autocomplete({
                        data: student,
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
                window.location.href = "{{ url('admin/students') }}/" + a;
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

        function attendance(id) {
            $('#loading').show();
            $('#cat_mark_view').hide();
            $('#Attendance_card').hide();
            $('#error_messages').hide();
            $.ajax({

                url: '{{ route('admin.student-personal-attendance.report') }}',
                type: 'GET',
                data: {
                    user_name_id: id,
                    attendance_isset: 'attendance',

                },
                success: function(response) {

                    if (response.year != '' && response.sem != '' && response.userid != '' && response.response
                        .length > 0) {

                        if ($.fn.DataTable.isDataTable('#studentAttendence')) {
                            $('#studentAttendence').DataTable().destroy();
                        }
                        var year = response.year;
                        var sem = response.sem;
                        var userid = response.userid;
                        var response_data = response.response;
                        var response_data_length = response.response.length;
                        var content = '';
                        for (var i = 0; i < response_data.length; i++) {
                            var href =
                                `/admin/subject-attendance-report/show/${userid}/${response_data[i].enroll_master}/${response_data[i].subject_id}`;
                            content += '<tr>' +
                                '<td></td>' +
                                '<td>' + (i + 1) + '</td>' +
                                '<td>' + response_data[i].subject_code + '</td>' +
                                '<td>' + response_data[i].name + '</td>' +
                                '<td>' + response_data[i].classTeacher + '</td>' +
                                '<td>' + response_data[i].totalAttended + '</td>' +
                                '<td>' + response_data[i].totalHours + '</td>' +
                                '<td class="' + (response_data[i].percentage < 75 ? 'bg-warning text-white' :
                                    '') + '">' + response_data[i].percentage + '</td>' +
                                '<td><a class="btn btn-xs btn-primary" target="_blank" href="' + href +
                                '">View</a></td>' +
                                '</tr>';
                        }

                        $('#Academic_year').html(`<p>${year}</p>`);
                        $('#Semester').html(`<p>${sem}</p>`);
                        $('#Student_attendace_details').html(content);
                        $('#error_messages').hide();
                        $('#Attendance_card').show();

                        initializeDataTable();

                        $('#Attendance_card').show();
                        $('#loading').hide();
                    } else {
                        $('#error_messages').show();
                        $('#loading').hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
            });
        }

        function catmark(id) {
            $('#loading').show();
            $('#Attendance_card').hide();
            $('#cat_mark_view').hide();
            $('#error_messages').hide();
            const $head = $('#head').val('');

            $.ajax({
                url: '{{ route('admin.student-cat-mark.statement') }}',
                type: 'GET',
                data: {
                    user_name_id: id,
                    cat_mark: 'cat_mark',
                },
                success: function(response) {

                    if (response.examMarks.length > 0 && response.NOs.length > 0 && response.co_total != '' &&
                        Object.keys(response.co_values).length > 0 && response.names.length > 0) {
                        const examMarks = response.examMarks;
                        const exam_title_length = examMarks.length;
                        const NOs = response.NOs;
                        const co_total = response.co_total; // Assuming you have 'co_total' in the response
                        const co_values = response.co_values;
                        const names = response.names;
                        $head.html('CAT Mark');

                        let Exam_name = '<tr><th colspan="3"></th>';
                        names.forEach(function(index) {
                            Exam_name += `<th> ${ index || ''} </th>`;
                        });

                        Exam_name += '<th></th></tr>';
                        $('#title').html(Exam_name);

                        let co_details = '<tr><th>Subject Code</th><th>Subject Name</th><th>Faculty Name</th>';
                        for (const key in co_values) {
                            if (co_values.hasOwnProperty(key)) {
                                co_details += `<th>${key} <br> (${co_values[key] || ''} Marks)</th>`;
                            }
                        }

                        co_details += `<th> Total <br> ( ${examMarks[0]['co_total'] }  Marks) </th></tr>`;
                        $('#mark_details').html(co_details);
                        let student_mark_details = '';
                        for (let i = 0; i < exam_title_length; i++) {
                            student_mark_details += `<tr><td>${examMarks[i].subject_code || ''}</td>
                              <td>${examMarks[i].subject_name || ''}</td>
                              <td>${examMarks[i].Staff || ''}</td>`;
                            NOs.forEach(function(key) {
                                student_mark_details +=
                                    `<td>${examMarks[i]['co_mark' + key] || ''}</td>`;
                            });
                            student_mark_details +=
                                `<td class="${typeof examMarks[i].total !== 'undefined' && examMarks[i].total < co_total / 2 ? 'bg-danger' : ''}">${examMarks[i].total || ''}</td></tr>`;

                        }
                        $('#student_mark_details').html(student_mark_details);
                        $('#cat_mark_view').show();
                        $('#error_messages').hide();
                        $('#loading').hide();
                    } else {
                        $('#error_messages').show();
                        $('#loading').hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#cat_mark_view').hide();
                    $('#loading').hide();
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
            });


        }

        function labmark(id) {
            $('#loading').show();
            $('#Attendance_card').hide();
            $('#cat_mark_view').hide();
            $('#error_messages').hide();
            const $head = $('#head').val('');

            $.ajax({
                url: '{{ route('admin.student_lab_mark_2.statement') }}',
                type: 'GET',
                data: {
                    user_name_id: id,
                    lab_mark: 'lab_mark',
                },
                success: function(response) {

                    if (response.examMarks.length > 0 && response.NOs.length > 0 && response.co_total != '' &&
                        Object.keys(response.co_values).length > 0 && response.names.length > 0) {
                        const examMarks = response.examMarks;
                        const exam_title_length = examMarks.length;
                        const NOs = response.NOs;
                        const co_total = response.co_total; // Assuming you have 'co_total' in the response
                        const co_values = response.co_values;
                        const names = response.names;
                        $head.html('LAB Mark')

                        let Exam_name = '<tr><th colspan="3"></th>';
                        names.forEach(function(index) {
                            Exam_name += `<th> ${ index || ''} </th>`;
                        });

                        Exam_name += '<th></th></tr>';
                        $('#title').html(Exam_name);

                        let co_details = '<tr><th>Subject Code</th><th>Subject Name</th><th>Faculty Name</th>';
                        for (const key in co_values) {
                            if (co_values.hasOwnProperty(key)) {
                                co_details += `<th>${key} <br> (${co_values[key] || ''} Marks)</th>`;
                            }
                        }

                        co_details += `<th> Total <br> ( ${examMarks[0]['co_total'] }  Marks) </th></tr>`;
                        $('#mark_details').html(co_details);
                        let student_mark_details = '';
                        for (let i = 0; i < exam_title_length; i++) {
                            student_mark_details += `<tr><td>${examMarks[i].subject_code || ''}</td>
                              <td>${examMarks[i].subject_name || ''}</td>
                              <td>${examMarks[i].Staff || ''}</td>`;
                            NOs.forEach(function(key) {
                                student_mark_details +=
                                    `<td>${examMarks[i]['labMark-' + key] || ''}</td>`;
                            });
                            student_mark_details +=
                                `<td class="${typeof examMarks[i].total !== 'undefined' && examMarks[i].total < co_total / 2 ? 'bg-danger' : ''}">${examMarks[i].total || ''}</td></tr>`;

                        }
                        $('#student_mark_details').html(student_mark_details);
                        $('#cat_mark_view').show();
                        $('#error_messages').hide();
                        $('#loading').hide();
                    } else {
                        $('#error_messages').show();
                        $('#loading').hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#cat_mark_view').hide();
                    $('#loading').hide();
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
            });


        }

        function initializeDataTable() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'asc']
                ],
                pageLength: 10,
            });
            let table = $('#studentAttendence').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        }
    </script>
@endsection
