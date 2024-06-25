@if (auth()->user()->id == $student->user_name_id)
    @php
        $one = 'layouts.studentHome';
    @endphp
@elseif (auth()->user()->id != $student->user_name_id)
    @php
        $one = 'layouts.admin';
    @endphp
@endif
@extends($one)
@section('content')
    <div class="row">
        <div class="col-9" style="border-right: 1px solid #cecdcd;overflow:hidden; ">

            <div class="bg-primary text-light student_label">
                @if (auth()->user()->id != $student->user_name_id)
                    @if ($check == 'entry')
                        <div style="padding-left:2%;"><a href="{{ url('admin/students') }}"><i
                                    class="fa fa-chevron-left prev_page_bn"></i></a></div>
                    @else
                        <div style="padding-left:2%;"><a
                                href="{{ url('admin/students/' . $student->user_name_id . '/edit') }}"><i
                                    class="fa fa-chevron-left prev_page_bn"></i></a></div>
                    @endif
                @else
                    @if ($check == 'entry')
                        <div style="padding-left:2%;">

                        </div>
                    @else
                        <div style="padding-left:2%;">
                            <a href="{{ url('admin/students/' . auth()->user()->id . '/Profile-edit') }}">
                                <i class="fa fa-chevron-left prev_page_bn"></i>
                            </a>
                        </div>
                    @endif
                @endif
                <div style="padding-right:2%;">STUDENT NAME : {{ $student->name }}</div>
            </div>
            @if ($check == 'entry')
                {{-- {{ dd($student) }} --}}
                <div class="container" style="padding:0;">
                    <div class="row gutters">
                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="account-settings">
                                        <div class="user-profile">
                                            <div class="user-avatar">
                                                @if (
                                                    (isset($student->filePath) ? $student->filePath : '') != '' ||
                                                        (isset($student->filePath) ? $student->filePath : '') != null)
                                                    <img class="uploaded_img" src="{{ asset($student->filePath) }}"
                                                        alt="image">
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
                                        {{-- <div class="about">
                                            <h5>About</h5>
                                            <p>I'm {{ $student->name }}. Full Stack Designer I enjoy creating user-centric, delightful and
                                                human experiences.</p>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12" style="padding-left:0;">
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
                                                <input type="text" class="form-control" id=""
                                                    value="{{ $student->student_phone_no }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($check == 'personal_details')
                @include('admin.personalDetails.studentindex')
            @elseif ($check == 'academic_details')
                @include('admin.academicDetails.studentindex')
            @elseif ($check == 'educational_details')
                @include('admin.educationalDetails.studentindex')
            @elseif($check == 'parent_details')
                @include('admin.parentDetails.studentindex')
            @elseif($check == 'address_details')
                @include('admin.addresses.studentindex')
            @elseif($check == 'conference_details')
                @include('admin.addConferences.studentindex')
            @elseif($check == 'industrial_training_details')
                @include('admin.industrialTrainings.studentindex')
            @elseif($check == 'intern_details')
                @include('admin.interns.studentindex')
            @elseif($check == 'iv_details')
                @include('admin.ivs.studentindex')
            @elseif($check == 'document_details')
                @include('admin.documents.studentindex')
            @elseif($check == 'seminar_details')
                @include('admin.seminars.studentindex')
            @elseif($check == 'patent_details')
                @include('admin.patents.studentindex')
            @elseif($check == 'professional_activities')
                @include('admin.professionalactivities.index')
            @elseif($check == 'student_leave_details')
                @include('admin.student_Leave_apply.index')
            @endif


        </div>
        <div class="col-3">

            <ul class="nav nav-pills nav-sidebar flex-column">
                @can('personal_detail_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.personal-details.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/personal-details') || request()->is('admin/personal-details/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-user-cog">

                            </i>
                            <p>
                                {{ trans('cruds.personalDetail.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('academic_detail_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.academic-details.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/academic-details') || request()->is('admin/academic-details/*') ? 'active' : '' }}">

                            <i class="fa-fw nav-icon fas fa-graduation-cap">

                            </i>
                            <p>
                                {{ trans('cruds.academicDetail.title') }}
                            </p>
                        </a>
                    </li>
                @endcan

                @can('educational_detail_access')
                    <li class="nav-item">

                        <a href="{{ route('admin.educational-details.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/educational-details') || request()->is('admin/educational-details/*') ? 'active' : '' }}">

                            <i class="fa-fw nav-icon fas fa-book-open">

                            </i>
                            <p>
                                {{ trans('cruds.educationalDetail.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('parent_detail_access')
                    <li class="nav-item">

                        <a href="{{ route('admin.parent-details.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/parent-details') || request()->is('admin/parent-details/*') ? 'active' : '' }}">

                            <i class="fa-fw nav-icon fas fa-users-cog">

                            </i>
                            <p>
                                {{ trans('cruds.parentDetail.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('address_access')
                    <li class="nav-item">

                        <a href="{{ route('admin.addresses.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/addresses') || request()->is('admin/addresses/*') ? 'active' : '' }}">

                            <i class="fa-fw nav-icon fas fa-location-arrow">

                            </i>
                            <p>
                                {{ trans('cruds.address.title') }}
                            </p>
                        </a>
                    </li>
                @endcan

                @can('add_conference_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.add-conferences.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/add-conferences') || request()->is('admin/add-conferences/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-award">

                            </i>
                            <p>
                                {{ trans('cruds.addConference.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('industrial_training_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.industrial-trainings.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/industrial-trainings') || request()->is('admin/industrial-trainings/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-industry">

                            </i>
                            <p>
                                {{ trans('cruds.industrialTraining.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('intern_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.interns.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/interns') || request()->is('admin/interns/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-business-time">

                            </i>
                            <p>
                                {{ trans('cruds.intern.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- @can('industrial_experience_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.industrial-experiences.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/industrial-experiences') || request()->is('admin/industrial-experiences/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.industrialExperience.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}
                @can('iv_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.ivs.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/ivs') || request()->is('admin/ivs/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-monument">

                            </i>
                            <p>
                                {{ trans('cruds.iv.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                {{--  @can('online_course_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.online-courses.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/online-courses') || request()->is('admin/online-courses/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.onlineCourse.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}
                @can('document_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.documents.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/documents') || request()->is('admin/documents/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-file-upload">

                            </i>
                            <p>
                                Upload Documents
                            </p>
                        </a>
                    </li>
                @endcan
                @can('seminar_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.seminars.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/seminars') || request()->is('admin/seminars/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-microphone-alt">

                            </i>
                            <p>
                                {{ trans('cruds.seminar.title') }}
                            </p>
                        </a>
                    </li>
                @endcan

                {{-- @can('workshop_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.workshops.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/workshops') || request()->is('admin/workshops/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.workshop.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}
                @can('patent_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.patents.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/patents') || request()->is('admin/patents/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cubes">

                            </i>
                            <p>
                                {{ trans('cruds.patent.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('professional_activities_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.professional_activities.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/professional_activities') || request()->is('admin/professional_activities/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-magic">

                            </i>
                            <p>
                                {{ 'Professional Activities' }}
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- @can('award_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.awards.stu_index', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            class="nav-link {{ request()->is('admin/awards') || request()->is('admin/awards/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.award.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}
                {{-- @if ($one == 'layouts.studentHome')
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
        // function modeChange(element) {
        //     if ($(element).val() == 'GENERAL QUOTA') {
        //         $("#admitted_category_div").show();
        //         if ($("#admitted_category").val() == 'Scholarships') {
        //             $("#scholarship_div").show();
        //         } else {
        //             $("#scholarship_div").hide();
        //         }
        //     } else {
        //         $("#admitted_category_div").hide();
        //         $("#scholarship_div").hide();
        //         $("#From_Gov_Fee").hide();
        //     }
        // }

        // function categoryChange(element) {
        //     if ($(element).val() == 'FG' || $(element).val() == 'Scholarship' || $(element).val() == 'GQG') {
        //         $("#scholarship_div").show();
        //     } else {
        //         $("#scholarship_div").hide();
        //     }
        //     if ($(element).val() == 'GQG') {
        //         $("#From_Gov_Fee").show();
        //         $("#Foun_Per_Fee").hide();
        //     } else {
        //         if ($(element).val() == 'Scholarship' || $(element).val() == 'FG') {
        //             $("#Foun_Per_Fee").hide();
        //         }
        //         $("#From_Gov_Fee").hide();

        //     }
        // }

        // function foundationValue(element) {
        //     if ($("#admitted_category").val() == 'Scholarship') {
        //         if ($(element).val() != '') {
        //             $("#Foun_Per_Fee").show();
        //         } else {
        //             $("#Foun_Per_Fee").hide();
        //         }
        //     }
        // }

        function checkScholar(element) {
            if ($(element).val() == '1') {
                $("#scholarshipDiv").show();
            } else {
                $("#Scholarship_name").val('').select2();
                $("#scholarshipDiv").hide();
            }
        }

        function checkNo(element) {
            if ($(element).val() < 0) {
                $(element).val(0)
            }
        }
    </script>
@endsection
