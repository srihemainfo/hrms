@if (auth()->user()->id == $staff->user_name_id &&
        (auth()->user()->roles[0]->id != 14 && auth()->user()->roles[0]->id != 15))
    @php
        $one = 'layouts.non_techStaffHome';
    @endphp
@elseif (auth()->user()->id != $staff->user_name_id ||
        (auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 15))
    @php
        $one = 'layouts.admin';
    @endphp
@endif
@extends($one)
@section('content')
    {{-- {{ dd($check) }} --}}
    <div class="row">

        <div class="col-9" style="border-right: 1px solid #cecdcd;">

            <div class="bg-primary text-light student_label">
                @if (auth()->user()->id != $staff->user_name_id)
                    @if ($check == 'entry1')
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
                            <a href="{{ url('admin/non-teaching-staff/' . auth()->user()->id . '/Profile-edit') }}">
                                <i class="fa fa-chevron-left prev_page_bn"></i>
                            </a>
                        </div>
                    @endif
                @endif

                <div style="padding-right:2%;">STAFF NAME : {{ $staff->name }}</div>
            </div>

            @if ($check == 'entry1')
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
                                                <label for="">Staff Code</label>
                                                <input type="text" class="form-control" id=""
                                                    placeholder="Enter email ID" value="{{ $staff->StaffCode }}" readonly>
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
                @include('admin.experienceDetails.nt_staffindex')
            @elseif ($check == 'employee_details')
                @include('admin.employmentDetails.staffindex')
            @elseif ($check == 'educational_details')
                @include('admin.educationalDetails.staffindex')
            @elseif($check == 'address_details')
                @include('admin.addresses.staffindex')
            @elseif($check == 'document_details')
                @include('admin.documents.staffindex')
            @elseif($check == 'staff-salery')
                @include('admin.staffSalaries.staffindex')
            @elseif($check == 'bank_account_details')
                @include('admin.bankAccountDetails.staffindex')
            @elseif($check == 'leave_details')
                @include('admin.addLeave.nt_staffindex')
            @elseif($check == 'permissionrequest')
                @include('admin.permissionrequest.staffindex')
            @elseif($check == 'promotion_details')
                @include('admin.promotionDetails.staffindex')
            @endif


        </div>
        <div class="col-3">

            <ul class="nav nav-pills nav-sidebar flex-column">
                @can('personal_detail_access')
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
                @endcan
                {{-- @can('academic_detail_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.academic-details.staff_index', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            class="nav-link {{ request()->is('admin/academic-details') || request()->is('admin/academic-details/*') ? 'active' : '' }}">

                            <i class="fa-fw nav-icon fas fa-graduation-cap">

                            </i>
                            <p>
                                {{ trans('cruds.academicDetail.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}
                @can('employment_detail_access')
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
                @endcan
                @can('experience_detail_access')
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
                @endcan
                @can('staff_promotion_access')
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
                @endcan
                @can('educational_detail_access')
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
                @endcan
                @can('address_access')
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
                @endcan
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
                @can('bank_account_detail_access')
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
                @endcan
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
                @can('document_access')
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
                @endcan
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
