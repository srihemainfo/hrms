@php
    $role_id = auth()->user()->roles[0]->id;
    if ($role_id == 1) {
        $key = 'layouts.admin';
    } else {
        $key = 'layouts.staffs';
    }
@endphp
@extends($key)
@section('content')
    {{-- {{ dd($check) }} --}}
    <div class="row">
        {{-- @php
            $route = Illuminate\Support\Facades\Route::getCurrentRoute();

            if ($route) {
                $action = $route->getAction();

                // Retrieve the controller and method names
                $controller = class_basename($action['controller']);
                $method = $action['uses'];

                // Split the method to get only the method name
                $method = explode('@', $method)[1];

                echo "Controller: $controller<br>";
                echo "Method: $method";
            }
        @endphp --}}
        <div class="col-9" style="border-right: 1px solid #cecdcd;">

            <div class="bg-primary text-light student_label">
                @if (auth()->user()->id != $staff->user_name_id)
                    @if ($check == 'entry')
                        <div style="padding-left:2%;"><a href="{{ url('admin/teaching-staffs') }}"><i
                                    class="fa fa-chevron-left prev_page_bn"></i></a></div>
                    @elseif ($check == 'entry1')
                        <div style="padding-left:2%;"><a href="{{ url('admin/non-teaching-staffs') }}"><i
                                    class="fa fa-chevron-left prev_page_bn"></i></a></div>
                    @else
                        <div style="padding-left:2%;"><a onclick="history.go(-1)"><i
                                    class="fa fa-chevron-left prev_page_bn"></i></a></div>
                    @endif
                @else
                    @if ($check == 'entry')
                        <div style="padding-left:2%;">

                        </div>
                    @else
                        <div style="padding-left:2%;">
                            <a href="{{ url('admin/teaching-staff/' . auth()->user()->id . '/Profile-edit') }}">
                                <i class="fa fa-chevron-left prev_page_bn"></i>
                            </a>
                        </div>
                    @endif
                @endif

                <div style="padding-right:2%;">STAFF NAME : {{ $staff->name }}</div>
            </div>
            @if ($check == 'entry')
                {{-- {{ dd($staff) }} --}}
                <div class="container" style="padding:0;">
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
                                                    <img class="uploaded_img" src="{{ asset($staff->filePath) }}"
                                                        alt="image">
                                                @else
                                                    @if ($staff->Gender == 'MALE' || $staff->Gender == 'Male')
                                                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png"
                                                            alt="Maxwell Admin">
                                                    @elseif($staff->Gender == 'FEMALE' || $staff->Gender == 'Female')
                                                        <img src="https://bootdey.com/img/Content/avatar/avatar8.png"
                                                            alt="Maxwell Admin">
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
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h5 class="mb-2 text-primary">Staff Info</h5>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="fullName">Full Name</label>
                                                <input type="text" class="form-control" id="fullName"
                                                    placeholder="Enter full name" value="{{ $staff->name }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="">Employee ID</label>
                                                <input type="text" class="form-control" id=""
                                                    value="{{ $staff->employee_id }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="designation_id">Designation</label>
                                                <select class="form-control select2" id="designation_id"
                                                    name="designation_id" disabled>
                                                    <option value="">Select Designation</option>
                                                    @foreach ($designations as $id => $designation)
                                                        <option value="{{ $id }}"
                                                            {{ $staff->designation_id == $id ? 'selected' : '' }}>
                                                            {{ $designation }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="role_id">Role</label>
                                                <select class="form-control select2" id="role_id" name="role_id" disabled>
                                                    <option value="">Select Role</option>
                                                    @foreach ($roles as $id => $role)
                                                        <option value="{{ $id }}"
                                                            {{ $staff->role_id == $id ? 'selected' : '' }}>
                                                            {{ $role }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="phone">Department</label>
                                                <input type="text" class="form-control" id="phone" placeholder=""
                                                    value="{{ $staff->Dept }}" readonly>
                                            </div>
                                        </div> --}}
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
                                                    value="{{ $staff->ContactNo ? $staff->ContactNo : $staff->phone_number }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($check == 'entry1')
                {{-- {{ dd($staff) }} --}}
                <div class="container" style="padding:0;">
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
                                                    <img class="uploaded_img" src="{{ asset($staff->filePath) }}"
                                                        alt="image">
                                                @else
                                                    @if ($staff->Gender == 'MALE' || $staff->Gender == 'Male')
                                                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png"
                                                            alt="Maxwell Admin">
                                                    @elseif($staff->Gender == 'FEMALE' || $staff->Gender == 'Female')
                                                        <img src="https://bootdey.com/img/Content/avatar/avatar8.png"
                                                            alt="Maxwell Admin">
                                                    @else
                                                        <img src="{{ asset('adminlogo/user-image.png') }}"
                                                            alt="">
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
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h5 class="mb-2 text-primary">Staff Info</h5>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="fullName">Full Name</label>
                                                <input type="text" class="form-control" id="fullName"
                                                    placeholder="Enter full name" value="{{ $staff->name }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="">Staff Code</label>
                                                <input type="text" class="form-control" id=""
                                                    placeholder="Enter email ID" value="{{ $staff->StaffCode }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="phone">Designation</label>
                                                <input type="text" class="form-control" id="phone" placeholder=""
                                                    value="{{ $staff->Designation }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="phone">Department</label>
                                                <input type="text" class="form-control" id="phone" placeholder=""
                                                    value="{{ $staff->Dept }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="phone">Email</label>
                                                <input type="text" class="form-control" id="phone" placeholder=""
                                                    value="{{ $staff->email }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" class="form-control" id="phone" placeholder=""
                                                    value="{{ $staff->phone }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($check == 'personal_details')
                @include('admin.personalDetails.staffindex')
            @elseif ($check == 'academic_details')
                @include('admin.academicDetails.staffindex')
            @elseif ($check == 'phd_details')
                @include('admin.phdDetails.staffindex')
            @elseif($check == 'experience_details')
                @include('admin.experienceDetails.staffindex')
            @elseif ($check == 'employee_details')
                @include('admin.employmentDetails.staffindex')
            @elseif ($check == 'educational_details')
                @include('admin.educationalDetails.staffindex')
            @elseif($check == 'address_details')
                @include('admin.addresses.staffindex')
            @elseif($check == 'conference_details')
                @include('admin.addConferences.staffindex')
            @elseif ($check == 'publication_details')
                @include('admin.publications.staffindex')
            @elseif($check == 'entrance_exam_details')
                @include('admin.entranceExams.staffindex')
            @elseif($check == 'guest_lecture_details')
                @include('admin.guestLectures.staffindex')
            @elseif($check == 'industrial_training_details')
                @include('admin.industrialTrainings.staffindex')
            @elseif($check == 'intern_details')
                @include('admin.interns.staffindex')
            @elseif($check == 'industrial_experience_details')
                @include('admin.industrialExperiences.staffindex')
            @elseif($check == 'iv_details')
                @include('admin.ivs.staffindex')
            @elseif($check == 'event_organized_details')
                @include('admin.eventOrganized.staffindex')
            @elseif($check == 'event_participation_details')
                @include('admin.eventParticipation.staffindex')
            @elseif($check == 'online_course_details')
                @include('admin.onlineCourses.staffindex')
            @elseif($check == 'document_details')
                @include('admin.documents.staffindex')
            @elseif($check == 'seminar_details')
                @include('admin.seminars.staffindex')
            @elseif($check == 'sabotical_details')
                @include('admin.saboticals.staffindex')
            @elseif($check == 'sponser_details')
                @include('admin.sponsers.staffindex')
            @elseif($check == 'sttp_details')
                @include('admin.sttps.staffindex')
            @elseif($check == 'workshop_details')
                @include('admin.workshops.staffindex')
            @elseif($check == 'patent_details')
                @include('admin.patents.staffindex')
            @elseif($check == 'award_details')
                @include('admin.awards.staffindex')
            @elseif($check == 'staff-salery')
                @include('admin.staffSalaries.staffindex')
            @elseif($check == 'bank_account_details')
                @include('admin.bankAccountDetails.staffindex')
            @elseif($check == 'leave_details')
                @include('admin.addLeave.staffindex')
            @elseif($check == 'permissionrequest')
                @include('admin.permissionrequest.staffindex')
            @elseif($check == 'promotion_details')
                @include('admin.promotionDetails.staffindex')
            @endif


        </div>
        <div class="col-3">

            <ul class="nav nav-pills nav-sidebar flex-column">
                {{-- @can('personal_detail_access') --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.personal-details.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/personal-details') || request()->is('admin/personal-details/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-user-cog">

                            </i>
                            <p>
                                {{ trans('cruds.personalDetail.title') }}
                            </p>
                        </a>
                    </li>
                {{-- @endcan --}}

                {{-- @can('employment_detail_access') --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.employment-details.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/employment-details') || request()->is('admin/employment-details/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-user-tie">

                            </i>
                            <p>
                                Employment Details
                            </p>
                        </a>
                    </li>
                {{-- @endcan --}}
                {{-- @can('experience_detail_access') --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.experience-details.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/experience-details') || request()->is('admin/experience-details/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-poll">

                            </i>
                            <p>
                                {{ trans('cruds.experienceDetail.title') }}
                            </p>
                        </a>
                    </li>
                {{-- @endcan --}}
                {{-- @can('staff_promotion_access') --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.promotion-details.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/promotion-details') || request()->is('admin/promotion-details/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-star">

                            </i>
                            <p>
                                Promotion Details
                            </p>
                        </a>
                    </li>
                {{-- @endcan --}}
                {{-- @can('educational_detail_access') --}}
                    <li class="nav-item">

                        <a href="{{ route('admin.educational-details.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/educational-details') || request()->is('admin/educational-details/*') ? 'active' : '' }}">

                            <i class="fa-fw nav-icon fas fa-book-open">

                            </i>
                            <p>
                                {{ trans('cruds.educationalDetail.title') }}
                            </p>
                        </a>
                    </li>
                {{-- @endcan --}}
                @can('phd_detail_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.phd-details.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/phd-details') || request()->is('admin/phd-details/*') ? 'active' : '' }}">

                            <i class="fa-fw nav-icon fas fa-award">

                            </i>
                            <p>
                                Ph.D / PDF Details
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- @can('address_access') --}}
                    <li class="nav-item">

                        <a href="{{ route('admin.addresses.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/addresses') || request()->is('admin/addresses/*') ? 'active' : '' }}">

                            <i class="fa-fw nav-icon fas fa-location-arrow">

                            </i>
                            <p>
                                {{ trans('cruds.address.title') }}
                            </p>
                        </a>
                    </li>
                {{-- @endcan --}}
                @can('staff_salary_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.staff-salaries.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/staff-salaries') || request()->is('admin/staff-salaries/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-money-bill">

                            </i>
                            <p>
                                Staff Salary
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- @can('bank_account_detail_access') --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.bank-account-details.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/bank-account-details') || request()->is('admin/bank-account-details/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-landmark">

                            </i>
                            <p>
                                Bank Account Details
                            </p>
                        </a>
                    </li>
                {{-- @endcan --}}
                {{-- @can('add_leave_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.staff-request-leaves.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/staff-request-leaves') || request()->is('admin/staff-request-leaves/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                Apply Leave / OD
                            </p>
                        </a>
                    </li>
                @endcan --}}
                {{-- @can('permission_request')
                    <li class="nav-item">
                        <a href="{{ route('admin.staff-permissionsreq.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/staff-permissionsreq') || request()->is('admin/staff-permissionsreq/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                Apply Permission
                            </p>
                        </a>
                    </li>
                @endcan --}}
                @can('staff_publication_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.staff-publications.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/staff-publications') || request()->is('admin/staff-publications/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-file-alt">


                            </i>
                            <p>
                                Publications
                            </p>
                        </a>
                    </li>
                @endcan
                @can('event_organized_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.event-organized.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/event-organized') || request()->is('admin/event-organized/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-magic">

                            </i>
                            <p>
                                Event Organized
                            </p>
                        </a>
                    </li>
                @endcan
                @can('event_participation_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.event-participation.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/event-participation') || request()->is('admin/event-participation/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-microphone-alt">

                            </i>
                            <p>
                                Event Participation
                            </p>
                        </a>
                    </li>
                @endcan
                @can('industrial_training_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.industrial-trainings.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/industrial-trainings') || request()->is('admin/industrial-trainings/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-business-time">

                            </i>
                            <p>
                                {{ trans('cruds.industrialTraining.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- @can('intern_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.interns.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/interns') || request()->is('admin/interns/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.intern.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}
                @can('industrial_experience_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.industrial-experiences.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/industrial-experiences') || request()->is('admin/industrial-experiences/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-industry">

                            </i>
                            <p>
                                {{ trans('cruds.industrialExperience.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- @can('iv_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.ivs.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/ivs') || request()->is('admin/ivs/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.iv.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}
                @can('online_course_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.online-courses.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/online-courses') || request()->is('admin/online-courses/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-laptop">

                            </i>
                            <p>
                                {{ trans('cruds.onlineCourse.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- @can('document_access') --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.documents.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/documents') || request()->is('admin/documents/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-file-pdf">

                            </i>
                            <p>
                                Certificates
                            </p>
                        </a>
                    </li>
                {{-- @endcan --}}
                @can('patent_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.patents.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/patents') || request()->is('admin/patents/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cubes">

                            </i>
                            <p>
                                {{ trans('cruds.patent.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('award_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.awards.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/awards') || request()->is('admin/awards/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-trophy">

                            </i>
                            <p>
                                {{ trans('cruds.award.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- @if ($one == 'layouts.teachingStaffHome')
                    <li class="nav-item">
                        <a href="#" class="nav-link"
                            onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                            <p>
                                <i class="fas fa-fw fa-sign-out-alt nav-icon">

                                </i>
                                <p>{{ trans('global.logout') }}</p>
                            </p>
                        </a>
                    </li>
                @endif --}}
            </ul>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function check_type() {
            const mySelect = document.getElementById('leave_type');

            let selected_value = mySelect.value;

            if (selected_value == 5) {
                $("#from_date_div").hide();
                $("#to_date_div").hide();
                $("#off_date_div").show();
                $("#alter_date_div").show();
                $("#from_date").prop('required', false);
                $("#to_date").prop('required', false);
                $("#off_date").prop('required', true);
                $("#alter_date").prop('required', true);
            } else {
                $("#from_date_div").show();
                $("#to_date_div").show();
                $("#off_date_div").hide();
                $("#alter_date_div").hide();
                $("#from_date").prop('required', true);
                $("#to_date").prop('required', true);
                $("#off_date").prop('required', false);
                $("#alter_date").prop('required', false);
            }
            // console.log(mySelect.value)
        }

        document.getElementById("myNumberInput1").addEventListener("input", function() {
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
        document.getElementById("myNumberInput2").addEventListener("input", function() {
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
        document.getElementById("myNumberInput3").addEventListener("input", function() {
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
        document.getElementById("myNumberInput4").addEventListener("input", function() {
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
        document.getElementById("myNumberInput5").addEventListener("input", function() {
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
    </script>
@endsection
