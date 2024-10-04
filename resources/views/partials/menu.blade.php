<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" style="background-color: rgb(255, 255, 255)">
        <span class="brand-text font-weight-light">
            <img src="{{ asset('adminlogo/school_menu_logo.png') }}" alt="" width="100%">
        </span>
    </a>
    {{-- <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" /> --}}
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->

        <nav class="mt-2 mb-3">
            <style>
                .search-input-container {
                    position: relative;
                    overflow: auto;
                }

                /* D:\RIT_College\RIT_master - Copy */

                .search-input-container input[type="search"] {
                    padding: 7px 7px 7px 47px;
                    width: 100%;
                    background: #ededed url(https://static.tumblr.com/ftv85bp/MIXmud4tx/search-icon.png) no-repeat 9px center;
                    border: solid 1px #ccc;
                    border-bottom-left-radius: 25px;
                    border-top-left-radius: 25px;
                    transition: all .5s;
                }

                .search-input-container input[type="search"]:focus {
                    width: 100%;
                    background-color: #fff;
                    border-color: #007bff;
                    box-shadow: 0 0 5px rgba(109, 207, 246, .5);
                    outline: none;
                }
            </style>

            <div id="demo-2">
                <div class="search-input-container">
                    <input type="search" id="searchInput" placeholder="Search..." class="menu_searcher"
                        autocomplete="off" value="">
                </div>
            </div>


            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false" id="list" style="padding-bottom:50px;">
                <li>
                    {{-- <select class="searchable-field form-control">

                    </select> --}}



                </li>
                <li class="nav-item" style="margin-top:0.5rem;">
                    <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                        href="{{ route('admin.home') }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>

                @can('user_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }} {{ request()->is('admin/audit-logs*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/permissions*') ? 'active' : '' }} {{ request()->is('admin/roles*') ? 'active' : '' }} {{ request()->is('admin/users*') ? 'active' : '' }} {{ request()->is('admin/audit-logs*') ? 'active' : '' }}"
                            href="#">
                            <i class="fas nav-icon fas fa-address-card">

                            </i>
                            <p>
                                User Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">

                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}"
                                    class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-unlock-alt">

                                    </i>
                                    <p>
                                        {{ trans('cruds.permission.title') }}
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}"
                                    class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-briefcase">

                                    </i>
                                    <p>
                                        {{ trans('cruds.role.title') }}
                                    </p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-user">

                                    </i>
                                    <p>
                                        {{ trans('cruds.user.title') }}
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.audit-logs.index') }}"
                                    class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-file-alt">

                                    </i>
                                    <p>
                                        {{ trans('cruds.auditLog.title') }}
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan




                @can('staff_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/staff_details*') ? 'menu-open' : '' }} {{ request()->is('admin/Staff_status*') ? 'menu-open' : '' }} {{ request()->is('admin/inactive_staff*') ? 'menu-open' : '' }} {{ request()->is('admin/teaching-staffs*') ? 'menu-open' : '' }} {{ request()->is('admin/non-teaching-staffs*') ? 'menu-open' : '' }} {{ request()->is('admin/rd-staffs*') ? 'menu-open' : '' }} {{ request()->is('admin/staff-edge*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/staff_details*') ? 'active' : '' }} {{ request()->is('admin/Staff_status*') ? 'active' : '' }} {{ request()->is('admin/inactive_staff*') ? 'active' : '' }} {{ request()->is('admin/teaching-staffs*') ? 'active' : '' }} {{ request()->is('admin/non-teaching-staffs*') ? 'active' : '' }} {{ request()->is('admin/rd-staffs*') ? 'active' : '' }} {{ request()->is('admin/staff-edge*') ? 'active' : '' }}"
                            href="#">
                            <i class="fas nav-icon fas fa-user-tie">

                            </i>
                            <p>
                                Staff Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">

                            <li class="nav-item">
                                <a href="{{ route('admin.staffs.index') }}"
                                    class="nav-link {{ request()->is('admin/staffs') || request()->is('admin/staffs*') ? 'active' : '' }}">
                                    <i class="fas nav-icon fas fa-user-tie"></i>
                                    </i>
                                    <p>
                                        Staffs
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.teaching-staffs.index') }}"
                                    class="nav-link {{ request()->is('admin/teaching-staffs') || request()->is('admin/teaching-staffs*') ? 'active' : '' }}">
                                    <i class="fas nav-icon fa-chalkboard-teacher">
                                    </i>
                                    <p>
                                        {{ trans('cruds.teachingStaff.title') }}
                                    </p>
                                </a>
                            </li>
<<<<<<< HEAD


                            <li class="nav-item">
                                <a href="{{ route('admin.inactive_staff.index') }}"
                                    class="nav-link {{ request()->is('admin/inactive_staff') || request()->is('admin/inactive_staff/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-user-times"></i>
                                    <p>
                                        Inactive Staff List
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                @can('hrm_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/staff/*') ||request()->is('admin/staff') ||request()->is('admin/hrm-request-permissions*') ||request()->is('admin/staff_leave_report*') ||request()->is('admin/salary-statement*') ||request()->is('admin/permission-register*') ||request()->is('admin/PaySlip*') ||request()->is('admin/staff-attend-register*') ||request()->is('admin/employee-salary*') ||request()->is('admin/staff-biometrics*') ||request()->is('admin/hrm-request-leaves*') ||request()->is('admin/leave-staff-allocations*') ||request()->is('admin/od-masters*') ||request()->is('admin/take-attentance-students*') ||request()->is('admin/od-requests*') ||request()->is('admin/internship-requests*') ||request()->is('admin/hrm-request-permissions*') ||request()->is('admin/staff-transfer-infos*') ||request()->is('admin/hrm-request-leaves*') ||request()->is('admin/staff_leave_register*') ||request()->is('admin/Staff-Relieving-Report*') ||request()->is('admin/leave-implementation*') ||request()->is('admin/staff-daily-attendance*')? 'menu-open': '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/staff/*') || request()->is('admin/staff') || request()->is('admin/hrm-request-permissions*') || request()->is('admin/staff_leave_report*') || request()->is('admin/salary-statement*') || request()->is('admin/permission-register*') || request()->is('admin/staff-attend-register*') || request()->is('admin/PaySlip*') || request()->is('admin/employee-salary*') || request()->is('admin/staff-biometrics*') || request()->is('admin/hrm-request-leaves*') || request()->is('admin/leave-staff-allocations*') || request()->is('admin/od-masters*') || request()->is('admin/take-attentance-students*') || request()->is('admin/od-requests*') || request()->is('admin/internship-requests*') || request()->is('admin/hrm-request-permissions*') || request()->is('admin/staff-transfer-infos*') || request()->is('admin/staff_leave_register*') || request()->is('admin/Staff-Relieving-Report*') || request()->is('admin/leave-implementation*') || request()->is('admin/staff-daily-attendance*')? 'active': '' }}"
                            href="#">
                            <i class="fa nav-icon fas fa-dice-three">
                            </i>
                            <p>
                                {{ trans('cruds.hrm.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview " style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
=======
>>>>>>> 6563285674506c09c4794a263e688088e7e74606


<<<<<<< HEAD
                                        </i>
                                        <p>
                                            Staff Biometrics
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_biometric_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff.balance') }}"
                                        class="nav-link {{ request()->is('admin/staff') || request()->is('admin/staff/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon far fa-file-alt">

                                        </i>
                                        <p>
                                            Staff Balance Details
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_daily_att_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff-daily-attendance.index') }}"
                                        class="nav-link {{ request()->is('admin/staff-daily-attendance') || request()->is('admin/staff-daily-attendance/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-check">

                                        </i>
                                        <p>
                                            Staff Daily Attendance
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('hrm_request_leaf_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.hrm-request-leaves.index') }}"
                                        class="nav-link {{ request()->is('admin/hrm-request-leaves') || request()->is('admin/hrm-request-leaves/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-mail-bulk">

                                        </i>
                                        <p>
                                            Leave Requests
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('hrm_request_permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.hrm-request-permissions.index') }}"
                                        class="nav-link {{ request()->is('admin/hrm-request-permissions') || request()->is('admin/hrm-request-permissions/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-envelope">

                                        </i>
                                        <p>
                                            Permission Requests
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_leave_report_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff_leave_report.index') }}"
                                        class="nav-link {{ request()->is('admin/staff_leave_report') || request()->is('admin/staff_leave_report/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon far fa-file-alt">

                                        </i>
                                        <p>
                                            Leave Reports
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_leave_register_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff_leave_register.index') }}"
                                        class="nav-link {{ request()->is('admin/staff_leave_register') || request()->is('admin/staff_leave_register/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon far fa-address-book"></i>
                                        <p>
                                            Leave Register
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('permission_register_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permission-register.index') }}"
                                        class="nav-link {{ request()->is('admin/permission-register') || request()->is('admin/permission-register/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-address-book">

                                        </i>
                                        <p>
                                            Permission Register
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_attendance_register_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff-attend-register.index') }}"
                                        class="nav-link {{ request()->is('admin/staff-attend-register') || request()->is('admin/staff-attend-register/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-check">

                                        </i>
                                        <p>
                                            Attendance Register
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_relieving_report')
                                <li class="nav-item">
                                    <a href="{{ route('admin.Staff-Relieving-Report.index') }}"
                                        class="nav-link {{ request()->is('admin/Staff-Relieving-Report*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file"></i>
                                        <p>Staff Relieving Report</p>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('leave_implement_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.leave-implementation.index') }}"
                                        class="nav-link {{ request()->is('admin/leave-implementation*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-gavel"></i>
                                        <p>Leave Implementation</p>
                                    </a>
                                </li>
                            @endcan --}}
                            @can('employee_salary_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.employee-salary.index') }}"
                                        class="nav-link {{ request()->is('admin/employee-salary') || request()->is('admin/employee-salary/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-coins">

                                        </i>
                                        <p>
                                            Employee Salary
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('salary_statement')
                                <li class="nav-item">
                                    <a href="{{ route('admin.salary-statement.index') }}"
                                        class="nav-link {{ request()->is('admin/salary-statement') || request()->is('admin/salary-statement/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-receipt">

                                        </i>
                                        <p>
                                            Salary Statement
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('pay_slip_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.PaySlip.index') }}"
                                        class="nav-link {{ request()->is('admin/PaySlip') || request()->is('admin/PaySlip/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-invoice">

                                        </i>
                                        <p>
                                            PaySlip
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('leave_staff_allocation_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.leave-staff-allocations.index') }}"
                                        class="nav-link {{ request()->is('admin/leave-staff-allocations') || request()->is('admin/leave-staff-allocations/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-invoice-dollar">

                                        </i>
                                        <p>
                                            {{ trans('cruds.leaveStaffAllocation.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}
                            {{-- @can('od_master_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.od-masters.index') }}"
                                        class="nav-link {{ request()->is('admin/od-masters') || request()->is('admin/od-masters/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-signature">

                                        </i>
                                        <p>
                                            {{ trans('cruds.odMaster.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}
                            {{-- @can('take_attentance_student_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.take-attentance-students.index') }}"
                                        class="nav-link {{ request()->is('admin/take-attentance-students') || request()->is('admin/take-attentance-students/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-drafting-compass">

                                        </i>
                                        <p>
                                            {{ trans('cruds.takeAttentanceStudent.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}
                            {{-- @can('od_request_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.od-requests.index') }}"
                                        class="nav-link {{ request()->is('admin/od-requests') || request()->is('admin/od-requests/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-id-badge">

                                        </i>
                                        <p>
                                            {{ trans('cruds.odRequest.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}
                            {{-- @can('internship_request_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.internship-requests.index') }}"
                                        class="nav-link {{ request()->is('admin/internship-requests') || request()->is('admin/internship-requests/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-book-reader">

                                        </i>
                                        <p>
                                            {{ trans('cruds.internshipRequest.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}

                            {{-- @can('staff_transfer_info_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff-transfer-infos.index') }}"
                                        class="nav-link {{ request()->is('admin/staff-transfer-infos') || request()->is('admin/staff-transfer-infos/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-award">

                                        </i>
                                        <p>
                                            {{ trans('cruds.staffTransferInfo.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}
=======
                            <li class="nav-item">
                                <a href="{{ route('admin.inactive_staff.index') }}"
                                    class="nav-link {{ request()->is('admin/inactive_staff') || request()->is('admin/inactive_staff/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-user-times"></i>
                                    <p>
                                        Inactive Staff List
                                    </p>
                                </a>
                            </li>
>>>>>>> 6563285674506c09c4794a263e688088e7e74606

                        </ul>
                    </li>
                @endcan

                @can('master_tool_access')
<<<<<<< HEAD
                    <li class="nav-item">
                        <a href="{{ route('admin.designation.index') }}"
                            class="nav-link {{ request()->is('admin/master-tools') ||
                            request()->is('admin/master-tools/*') ||
                            request()->is('admin/tools*') ||
                            request()->is('admin/year*') ||
                            request()->is('admin/batches*') ||
                            request()->is('admin/academic-years*') ||
                            request()->is('admin/semesters*') ||
                            request()->is('admin/sections*') ||
                            request()->is('admin/course-enroll-masters*') ||
                            request()->is('admin/lab_title*') ||
                            request()->is('admin/nationalities*') ||
                            request()->is('admin/religions*') ||
                            request()->is('admin/state*') ||
                            request()->is('admin/blood-groups*') ||
                            request()->is('admin/communities*') ||
                            request()->is('admin/mother-tongues*') ||
                            request()->is('admin/education-boards*') ||
                            request()->is('admin/education-types*') ||
                            request()->is('admin/scholarships*') ||
                            request()->is('admin/mediumof-studieds*') ||
                            request()->is('admin/teaching-types*') ||
                            request()->is('admin/examstaffs*') ||
                            request()->is('admin/college-blocks*') ||
                            request()->is('admin/scholarships*') ||
                            request()->is('admin/shift*') ||
                            request()->is('admin/leave-statuses*') ||
                            request()->is('admin/class-rooms*') ||
                            request()->is('admin/class-batch*') ||
                            request()->is('admin/email-settings*') ||
                            request()->is('admin/sms-settings*') ||
                            request()->is('admin/sms-templates*') ||
                            request()->is('admin/email-templates*') ||
                            request()->is('admin/Shift/*') ||
                            request()->is('admin/Shift') ||
                            request()->is('admin/tool-lab') ||
                            request()->is('admin/tool-lab/*') ||
                            request()->is('admin/rooms') ||
                            request()->is('admin/rooms/*') ||
                            request()->is('admin/grade-master*') ||
                            request()->is('admin/examfee-master*') ||
                            request()->is('admin/credit-limit-master*') ||
                            request()->is('admin/internal-weightage/*') ||
                            request()->is('admin/paymentMode') ||
                            request()->is('admin/paymentMode/*') ||
                            request()->is('admin/fee-components*') ||
                            request()->is('admin/events*') ||
                            request()->is('admin/events/*') ||
                            request()->is('admin/leave-types*') ||
                            request()->is('admin/leave-types/*') ||
                            request()->is('admin/admission-mode*') ||
                            request()->is('admin/result-master*')
                                ? 'active'
                                : '' }}">
                            <i class="fa-fw nav-icon fas fa-tools">

                            </i>
                            <p>
                                Master Tools
                            </p>
                        </a>
                    </li>
                @endcan

                @can('office_calender_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.college-calenders.index') }}"
                            class="nav-link {{ request()->is('admin/college-calenders') || request()->is('admin/college-calenders/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Office Calendar
                            </p>
                        </a>
                    </li>
                @endcan


                {{-- <li class="nav-item">
=======
                <li class="nav-item">
                    <a href="{{ route('admin.designation.index') }}"
                        class="nav-link {{ request()->is('admin/master-tools') ||
                        request()->is('admin/master-tools/*') ||
                        request()->is('admin/tools*') ||
                        request()->is('admin/year*') ||
                        request()->is('admin/batches*') ||
                        request()->is('admin/academic-years*') ||
                        request()->is('admin/semesters*') ||
                        request()->is('admin/sections*') ||
                        request()->is('admin/course-enroll-masters*') ||
                        request()->is('admin/lab_title*') ||
                        request()->is('admin/nationalities*') ||
                        request()->is('admin/religions*') ||
                        request()->is('admin/state*') ||
                        request()->is('admin/blood-groups*') ||
                        request()->is('admin/communities*') ||
                        request()->is('admin/mother-tongues*') ||
                        request()->is('admin/education-boards*') ||
                        request()->is('admin/education-types*') ||
                        request()->is('admin/scholarships*') ||
                        request()->is('admin/mediumof-studieds*') ||
                        request()->is('admin/teaching-types*') ||
                        request()->is('admin/examstaffs*') ||
                        request()->is('admin/college-blocks*') ||
                        request()->is('admin/scholarships*') ||
                        request()->is('admin/shift*') ||
                        request()->is('admin/leave-statuses*') ||
                        request()->is('admin/class-rooms*') ||
                        request()->is('admin/class-batch*') ||
                        request()->is('admin/email-settings*') ||
                        request()->is('admin/sms-settings*') ||
                        request()->is('admin/sms-templates*') ||
                        request()->is('admin/email-templates*') ||
                        request()->is('admin/Shift/*') ||
                        request()->is('admin/Shift') ||
                        request()->is('admin/tool-lab') ||
                        request()->is('admin/tool-lab/*') ||
                        request()->is('admin/rooms') ||
                        request()->is('admin/rooms/*') ||
                        request()->is('admin/grade-master*') ||
                        request()->is('admin/examfee-master*') ||
                        request()->is('admin/credit-limit-master*') ||
                        request()->is('admin/internal-weightage/*') ||
                        request()->is('admin/paymentMode') ||
                        request()->is('admin/paymentMode/*') ||
                        request()->is('admin/fee-components*') ||
                        request()->is('admin/events*') ||
                        request()->is('admin/events/*') ||
                        request()->is('admin/leave-types*') ||
                        request()->is('admin/leave-types/*') ||
                        request()->is('admin/admission-mode*') ||
                        request()->is('admin/result-master*')
                            ? 'active'
                            : '' }}">
                        <i class="fa-fw nav-icon fas fa-tools">

                        </i>
                        <p>
                            Master Tools
                        </p>
                    </a>
                </li>
                @endcan


                <li class="nav-item">
>>>>>>> 6563285674506c09c4794a263e688088e7e74606
                    <a href="{{ route('admin.systemCalendar') }}"
                        class="nav-link {{ request()->is('admin/system-calendar') || request()->is('admin/system-calendar/*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-calendar nav-icon">

                        </i>
                        <p>
                            {{ trans('global.systemCalendar') }}
                        </p>
                    </a>
<<<<<<< HEAD
                </li> --}}
=======
                </li>
>>>>>>> 6563285674506c09c4794a263e688088e7e74606

                @php($unread = \App\Models\QaTopic::unreadCount())
                <li class="nav-item">
                    <a href="{{ route('admin.messenger.index') }}"
                        class="{{ request()->is('admin/messenger') || request()->is('admin/messenger/*') ? 'active' : '' }} nav-link">
                        <i class="fa-fw fa fa-envelope nav-icon">

                        </i>
                        <p>{{ trans('global.messages') }}</p>
                        @if ($unread > 0)
                            <strong>( {{ $unread }} )</strong>
                        @endif

                    </a>
                </li>
                @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}"
                                href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}"
                        class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
                        <i class="fa-fw nav-icon fas fa-cogs">

                        </i>
                        <p>
                            {{ trans('cruds.setting.title') }}
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>

</aside>
