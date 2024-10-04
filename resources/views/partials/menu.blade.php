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
                            @can('permission_access')
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
                            @endcan
                            @can('role_access')
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
                            @endcan
                            @can('user_access')
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
                            @endcan
                            @can('audit_log_access')
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
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('Staff_personal_attendence')
                    <li class="nav-item">
                        <a href="{{ route('admin.Staff-Personal-Attendence.index') }}"
                            class="nav-link {{ request()->is('admin/Staff-Personal-Attendence') || request()->is('admin/Staff-Personal-Attendence/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-edit">

                            </i>
                            <p>
                                Staff Personal Attendence
                            </p>
                        </a>
                    </li>
                @endcan

                @can('user_management_access')
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
                            @can('teaching_staff_access')
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
                            @endcan
                            @can('non_teaching_staff_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.non-teaching-staffs.index') }}"
                                        class="nav-link {{ request()->is('admin/non-teaching-staffs') || request()->is('admin/non-teaching-staffs/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-friends">

                                        </i>
                                        <p>
                                            {{ trans('cruds.nonTeachingStaff.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('rd_staff_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.rd-staffs.index') }}"
                                        class="nav-link {{ request()->is('admin/rd-staffs') || request()->is('admin/rd-staffs*') ? 'active' : '' }}">
                                        <i class="fas nav-icon fas fa-user-tie"></i>
                                        </i>
                                        <p>
                                            R & D Staff
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_edge_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff-edge.index') }}"
                                        class="nav-link {{ request()->is('admin/staff-edge') || request()->is('admin/teaching-staff-edge/*') || request()->is('admin/staff-edge/*') || request()->is('admin/staff-edge-hr*') || request()->is('admin/non-teaching-staff-edge/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-id-card-alt">

                                        </i>
                                        <p>
                                            Staff Edge
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_details_download_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.Staff_details_get') }}"
                                        class="nav-link {{ request()->is('admin/staff_details/*') || request()->is('admin/staff_details') ? 'active' : '' }}">
                                        <i class="fas nav-icon fa-chalkboard-teacher">
                                        </i>
                                        <p>
                                            Staff Details Download
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('inactive_staff_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.inactive_staff.index') }}"
                                        class="nav-link {{ request()->is('admin/inactive_staff') || request()->is('admin/inactive_staff/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-times">


                                        </i>
                                        <p>
                                            Inactive Staff List
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_status_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.Staff_status.index') }}"
                                        class="nav-link {{ request()->is('admin/Staff_status') || request()->is('admin/Staff_status/*') ? 'active' : '' }}">

                                        <i class="fa-fw nav-icon fas fa-user-clock"></i>
                                        </i>
                                        <p>
                                            Staff Status
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('user_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/grade-book/*') || request()->is('admin/student-mandatory-details/*') || request()->is('admin/Attendence-Details*') || request()->is('admin/Batch-Wise-Strenth*') ? 'menu-open' : '' }}  {{ request()->is('admin/Batch-Wise-Strenth*') ? 'menu-open' : '' }} {{ request()->is('admin/student-mandatory-details*') ? 'menu-open' : '' }} {{ request()->is('admin/grade-book*') ? 'menu-open' : '' }} {{ request()->is('admin/student-edge*') ? 'menu-open' : '' }} {{ request()->is('admin/grade-book*') ? 'menu-open' : '' }} {{ request()->is('admin/certificate-provision*') ? 'menu-open' : '' }} {{ request()->is('admin/students*') ? 'menu-open' : '' }} {{ request()->is('admin/student-image*') ? 'menu-open' : '' }} {{ request()->is('admin/removed-students*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/grade-book/*') || request()->is('admin/student-mandatory-details/*') || request()->is('admin/Attendence-Details*') || request()->is('admin/Batch-Wise-Strenth*') ? 'active' : '' }}  {{ request()->is('admin/Batch-Wise-Strenth*') ? 'active' : '' }} {{ request()->is('admin/student-mandatory-details*') ? 'active' : '' }} {{ request()->is('admin/grade-book*') ? 'active' : '' }} {{ request()->is('admin/student-edge*') ? 'active' : '' }} {{ request()->is('admin/grade-book*') ? 'active' : '' }} {{ request()->is('admin/certificate-provision*') ? 'active' : '' }} {{ request()->is('admin/students*') ? 'active' : '' }} {{ request()->is('admin/student-image*') ? 'active' : '' }}  {{ request()->is('admin/removed-students*') ? 'active' : '' }}"
                            href="#">
                            <i class="fas nav-icon fas fa-user-graduate">
                            </i>
                            <p>
                                Student Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                            @can('student_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.students.index') }}"
                                        class="nav-link {{ request()->is('admin/students') || request()->is('admin/students/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-graduate">

                                        </i>
                                        <p>
                                            {{ trans('cruds.student.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('student_image_upload_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.student-image.index') }}"
                                        class="nav-link {{ request()->is('admin/student-image') || request()->is('admin/student-image*') ? 'active' : '' }}">
                                        <i class="fas nav-icon fa-user-circle">
                                        </i>
                                        <p>
                                            Student Images
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('student_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.removed-students.index') }}"
                                        class="nav-link {{ request()->is('admin/removed-students') || request()->is('admin/removed-students*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-times">

                                        </i>
                                        <p>
                                            Removed Students
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('student_edge_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.student-edge.index') }}"
                                        class="nav-link {{ request()->is('admin/student-edge') || request()->is('admin/student-edge/*') || request()->is('admin/students-edge/*') || request()->is('admin/student-personal-attendance/report') || request()->is('admin/student-cat-mark*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-id-card-alt">

                                        </i>
                                        <p>
                                            Student Edge
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('student_reports_access')
                                <li
                                    class="nav-item has-treeview  {{ request()->is('admin/grade-book/*') || request()->is('admin/student-mandatory-details/*') || request()->is('admin/Attendence-Details*') || request()->is('admin/Batch-Wise-Strenth*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/grade-book/*') || request()->is('admin/student-mandatory-details/*') || request()->is('admin/Attendence-Details*') || request()->is('admin/Batch-Wise-Strenth*') ? 'active' : '' }}"
                                        href="#">
                                        <i class="fa nav-icon fas fa-clipboard-list">
                                        </i>
                                        <p>
                                            Student Reports
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview "
                                        style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                                        @can('stu_sem_att_details_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.Attendence-Details.index') }}"
                                                    class="nav-link {{ request()->is('admin/Exam-time-table') || request()->is('admin/Attendence-Details/*') ? 'active' : '' }}">

                                                    <i class="fa-fw nav-icon fas fa-poll-h"></i>
                                                    </i>
                                                    <p>
                                                        Attendence Details
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Batch_wise_Student_Strength_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.batch_wise_strenth.index') }}"
                                                    class="nav-link {{ request()->is('admin/Batch-Wise-Strenth') || request()->is('admin/Batch-Wise-Strenth/*') ? 'active' : '' }}">

                                                    <i class="fa-fw nav-icon fas fa-user-tie"></i>
                                                    </i>
                                                    <p>
                                                        Batch-wise Student Strength
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        <li class="nav-item">
                                            <a href="{{ route('admin.student-mandatory-details.index') }}"
                                                class="nav-link {{ request()->is('admin/student-mandatory-details') || request()->is('admin/student-mandatory-details/*') ? 'active' : '' }}">
                                                <i class="fa-fw nav-icon fas fa-id-badge">
                                                </i>
                                                <p>
                                                    Mandatory Details
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.grade-book.index') }}"
                                                class="nav-link {{ request()->is('admin/grade-book') || request()->is('admin/grade-book/*') ? 'active' : '' }}">

                                                <i class="fa-fw nav-icon fas fa-book-open"></i>
                                                </i>
                                                <p>
                                                    Grade Book
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan

                            @can('certificate_provision')
                                <li class="nav-item">
                                    <a href="{{ route('admin.certificate-provision.index') }}"
                                        class="nav-link {{ request()->is('admin/certificate-provision*') || request()->is('admin/student-apply-certificate*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-address-card"></i>
                                        <p>Certificate Provision</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('user_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/subjectRegistration*') || request()->is('admin/subject-registration*') || request()->is('admin/degree-wise-subject-registration*') || request()->is('admin/subject-wise-subject-registration*') ? 'menu-open' : '' }} {{ request()->is('admin/subject_types*') ? 'menu-open' : '' }} {{ request()->is('admin/subject_category*') ? 'menu-open' : '' }} {{ request()->is('admin/subject-allotment*') ? 'menu-open' : '' }} {{ request()->is('admin/subjectRegistration*') ? 'menu-open' : '' }} {{ request()->is('admin/subject-registration*') ? 'menu-open' : '' }} {{ request()->is('admin/subjects*') ? 'menu-open' : '' }} {{ request()->is('admin/audit-logs*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/subjectRegistration*') || request()->is('admin/subject-registration*') || request()->is('admin/degree-wise-subject-registration*') || request()->is('admin/subject-wise-subject-registration*') ? 'active' : '' }} {{ request()->is('admin/subject_types*') ? 'active' : '' }} {{ request()->is('admin/subject_category*') ? 'active' : '' }} {{ request()->is('admin/subject-allotment*') ? 'active' : '' }} {{ request()->is('admin/subjectRegistration*') ? 'active' : '' }} {{ request()->is('admin/subject-registration*') ? 'active' : '' }} {{ request()->is('admin/subjects*') ? 'active' : '' }} {{ request()->is('admin/audit-logs*') ? 'active' : '' }}"
                            href="#">
                            <i class="fas nav-icon fas fa-scroll">
                            </i>
                            <p>
                                Subject Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                            @can('subject_category_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.subject_category.index') }}"
                                        class="nav-link {{ request()->is('admin/subject_category') || request()->is('admin/subject_category/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-book-open">

                                        </i>
                                        <p>
                                            Subject Category
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('subject_type_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.subject_types.index') }}"
                                        class="nav-link {{ request()->is('admin/subject_types') || request()->is('admin/subject_types/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-address-book">

                                        </i>
                                        <p>
                                            Subject Types
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('subject_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.subjects.index') }}"
                                        class="nav-link {{ request()->is('admin/subjects') || request()->is('admin/subjects/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-book-reader">

                                        </i>
                                        <p>
                                            {{ trans('cruds.subject.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('subject_allotment_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.subject-allotment.index') }}"
                                        class="nav-link {{ request()->is('admin/subject-allotment') || request()->is('admin/subject-allotment*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-clipboard">

                                        </i>
                                        <p>
                                            Subject Allotment
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('subject_registration_access')

                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/subjectRegistration*') || request()->is('admin/subject-registration*') || request()->is('admin/degree-wise-subject-registration*') || request()->is('admin/subject-wise-subject-registration*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/subjectRegistration*') || request()->is('admin/subject-registration*') || request()->is('admin/degree-wise-subject-registration*') || request()->is('admin/subject-wise-subject-registration*') ? 'active' : '' }}"
                                        href="#">
                                        <i class="fa nav-icon fas fa-book"></i>
                                        <p>
                                            Subject Register
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview"
                                        style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                        @can('degree_wise_sub_registration_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.degree-wise-subject-registration.index') }}"
                                                    class="nav-link {{ request()->is('admin/degree-wise-subject-registration*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-file-invoice"></i>
                                                    <p>Degree Wise Report</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('sub_wise_sub_registration_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.subject-wise-subject-registration.index') }}"
                                                    class="nav-link {{ request()->is('admin/subject-wise-subject-registration*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-file-alt"></i>
                                                    <p>Subject Wise Report</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('my_subject_registration_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.subjectRegistration.index') }}"
                                                    class="nav-link {{ request()->is('admin/subject-registration/index*') || request()->is('admin/subject-registrations/parse-csv-import*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-envelope-open"></i>
                                                    <p>Subject Reg Requests</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan


                @can('academy_activity_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/honor-subjects/show*') ||request()->is('admin/honor-subjects') ||request()->is('admin/honor-subjects-report') ||request()->is('admin/Faculty-WorkLoad*') ||request()->is('admin/class-time-table*') ||request()->is('admin/staff-lesson-plan*') ||request()->is('admin/Lesson-Plane-Report*') ||request()->is('admin/student-leave-requests*') ||request()->is('admin/student-att-modification*') ||request()->is('admin/bulk-ods*') ||request()->is('admin/staff-subjects/lesson-plan/hod*') ||request()->is('admin/student-Promotion*') ||request()->is('admin/student-attendance-summary*') ||request()->is('admin/subject-attendance-report*') ||request()->is('admin/Syllabus-Completion*') ||request()->is('admin/Staff-Alteration-Report*') ||request()->is('admin/class-attendance-report*') ||request()->is('admin/weekly-class-report*') ||request()->is('admin/absentees-summary-report*') ||request()->is('admin/student-apply-certificate*') ||request()->is('admin/faculty_staff-edge') ||request()->is('admin/faculty_staff-edge/*') ||request()->is('admin/faculty_time_table/*')? 'menu-open': '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/honor-subjects/show*') ||request()->is('admin/honor-subjects') ||request()->is('admin/honor-subjects-report') ||request()->is('admin/Faculty-WorkLoad*') ||request()->is('admin/class-time-table*') ||request()->is('admin/staff-lesson-plan*') ||request()->is('admin/Lesson-Plane-Report*') ||request()->is('admin/student-leave-requests*') ||request()->is('admin/student-att-modification*') ||request()->is('admin/bulk-ods*') ||request()->is('admin/staff-subjects/lesson-plan/hod*') ||request()->is('admin/student-Promotion*') ||request()->is('admin/student-attendance-summary*') ||request()->is('admin/Syllabus-Completion*') ||request()->is('admin/subject-attendance-report*') ||request()->is('admin/Staff-Alteration-Report*') ||request()->is('admin/class-attendance-report*') ||request()->is('admin/weekly-class-report*') ||request()->is('admin/absentees-summary-report*') ||request()->is('admin/student-apply-certificate*') ||request()->is('admin/faculty_staff-edge') ||request()->is('admin/faculty_staff-edge/*') ||request()->is('admin/faculty_time_table/*')? 'active': '' }}"
                            href="#">
                            <i class="fa nav-icon fas fa-university"></i>
                            <p>
                                Academic Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                            @can('class_time_table_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.class-time-table.index') }}"
                                        class="nav-link {{ request()->is('admin/class-time-table*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-table"></i>
                                        <p>Class Time Table</p>
                                    </a>
                                </li>
                            @endcan

                            @can('college_calender_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.college-calenders.index') }}"
                                        class="nav-link {{ request()->is('admin/college-calenders') || request()->is('admin/college-calenders/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-calendar-alt">

                                        </i>
                                        <p>
                                            Academic Calendar
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            {{-- @can('') --}}
                            <li
                                class="nav-item has-treeview {{ request()->is('admin/honor-subjects') || request()->is('admin/honor-subjects-report') || request()->is('admin/honor-subjects/show*') ? 'menu-open' : '' }}">
                                <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/honor-subjects') || request()->is('admin/honor-subjects-report') || request()->is('admin/honor-subjects/show*') ? 'active' : '' }}"
                                    href="#">
                                    <i class="fa nav-icon fas fa-graduation-cap"></i>
                                    <p>
                                        Honors Degree
                                        <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview"
                                    style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">


                                    <li class="nav-item">
                                        <a href="{{ route('admin.honor-subjects-report.index') }}"
                                            class="nav-link {{ request()->is('admin/honor-subjects-report') || request()->is('admin/honor-subjects/show*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fas fa-address-card"></i>
                                            <p>Class Wise Report</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('honor_subjects_access') --}}
                            <ul class="nav nav-treeview"
                                style="background-color: rgba(100, 100, 100, 0.473); color:#a7a3a3">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/honor-subjects') || request()->is('admin/honor-subjects-report') || request()->is('admin/honor-subjects/show*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/honor-subjects') || request()->is('admin/honor-subjects-report') || request()->is('admin/honor-subjects/show*') ? 'active' : '' }}"
                                        href="#">

                                        <i class="fa nav-icon fas fa-university"></i>
                                        <p>
                                            Honors Degree
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview"
                                        style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">

                                        <li class="nav-item">
                                            <a href="{{ route('admin.honor-subjects-report.index') }}"
                                                class="nav-link {{ request()->is('admin/honor-subjects-report') || request()->is('admin/honor-subjects/show*') ? 'active' : '' }}">
                                                <i class="fa-fw nav-icon fas fa-address-card"></i>
                                                <p>Class Wise Report</p>
                                            </a>
                                        </li>

                                        {{-- <li class="nav-item">
                                                           <a href="{{ route('admin.honor-subjects.index') }}"
                                                               class="nav-link {{ request()->is('admin/honor-subjects') ? 'active' : '' }}">
                                                               <i class="fa-fw nav-icon fas fa-address-card"></i>
                                                               <p>Subjects</p>
                                                           </a>
                                                </li> --}}

                                    </ul>
                                </li>
                            </ul>
                            {{-- @endcan --}}

                            {{-- @can('') --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.Faculty-WorkLoad.index') }}"
                                    class="nav-link {{ request()->is('admin/Faculty-WorkLoad*') ? 'active' : '' }}">

                                    <i class="fa-fw nav-icon fas fa-grip-horizontal"></i>
                                    <p>Faculty Workload</p>
                                </a>
                            </li>
                            {{-- @endcan --}}
                            @can('faculty_timetable_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.faculty_staff-edge.index') }}"
                                        class="nav-link {{ request()->is('admin/faculty_staff-edge') || request()->is('admin/faculty_staff-edge/*') || request()->is('admin/faculty_time_table/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-id-card-alt">

                                        </i>
                                        <p>
                                            Faculty Time Table
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_lesson_plan_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff-lesson-plan.index', ['status' => 0]) }}"
                                        class="nav-link {{ request()->is('admin/staff-lesson-plan*') || request()->is('admin/staff-subjects/lesson-plan/hod*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-chart-bar"></i>
                                        <p>Staff Lesson Plans</p>
                                    </a>
                                </li>
                            @endcan
                            @can('staff_alteration_report')
                                <li class="nav-item">
                                    <a href="{{ route('admin.Staff-Alteration-Report.index') }}"
                                        class="nav-link {{ request()->is('admin/Staff-Alteration-Report*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-clock"></i>
                                        <p>Staff Alteration Report</p>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('lesson_plane_report_acess')
                             <li class="nav-item">
                                <a href="{{ route('admin.lesson_plane_report.index') }}"
                                    class="nav-link {{ request()->is('admin/Lesson-Plane-Report*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-tools"></i>
                                    <p>Syllabus Completion Staff wise</p>
                                </a>
                            </li>
                        @endcan --}}
                            @can('student_leave_request')
                                <li class="nav-item">
                                    <a href="{{ route('admin.student-leave-requests.stu_index') }}"
                                        class="nav-link {{ request()->is('admin/student-leave-requests*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-envelope-open"></i>
                                        <p>Student Leave Request</p>
                                    </a>
                                </li>
                            @endcan
                            @can('stuent_att_request_Acces')
                                <li class="nav-item">
                                    <a href="{{ route('admin.student-att-modification.index', ['status' => 'Edit']) }}"
                                        class="nav-link {{ request()->is('admin/student-att-modification*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-edit"></i>
                                        <p>Attendance Modification</p>
                                    </a>
                                </li>
                            @endcan
                            @can('attendance_summary_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.student-attendance-summary.index') }}"
                                        class="nav-link {{ request()->is('admin/student-attendance-summary*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt"></i>
                                        <p>Day Attendance Summary</p>
                                    </a>
                                </li>
                            @endcan
                            @can('subject_attendance_report_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.subject-attendance-report.index') }}"
                                        class="nav-link {{ request()->is('admin/subject-attendance-report*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-invoice"></i>
                                        <p>Subject Attendance Report</p>
                                    </a>
                                </li>
                            @endcan
                            @can('class_attendance_report_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.class-attendance-report.index') }}"
                                        class="nav-link {{ request()->is('admin/class-attendance-report*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon far fa-file-alt"></i>
                                        <p>Class Attendance Report</p>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('class_attendance_report_access') --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.weekly-class-report.index') }}"
                                    class="nav-link {{ request()->is('admin/weekly-class-report*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-file-invoice"></i>
                                    <p>Weekly Class Report</p>
                                </a>
                            </li>
                            {{-- @endcan --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.absentees-summary-report.index') }}"
                                    class="nav-link {{ request()->is('admin/absentees-summary-report*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon far fa-file-alt"></i>
                                    <p>Absentees Report</p>
                                </a>
                            </li>
                            @can('student_promotion_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.student-Promotion.index') }}"
                                        class="nav-link {{ request()->is('admin/student-Promotion') || request()->is('admin/student-Promotion/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-cog">

                                        </i>
                                        <p>
                                            Student Promotion
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('bulk_od_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.bulk-ods.index') }}"
                                        class="nav-link {{ request()->is('admin/bulk-ods*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-portrait"></i>
                                        <p>Institute OD</p>
                                    </a>
                                </li>
                            @endcan
                            @can('syllabus_completion_dep_wise')
                                <li class="nav-item">
                                    <a href="{{ route('admin.Syllabus-Completion.index') }}"
                                        class="nav-link {{ request()->is('admin/Syllabus-Completion*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-archive"></i>
                                        <p>Syllabus Completion Report</p>
                                    </a>
                                </li>
                            @endcan


                        </ul>
                    </li>

                @endcan

                @can('coe_access')

                    <li
                        class="nav-item has-treeview {{ request()->is('admin/Exam-time-table*') ? 'menu-open' : '' }} {{ request()->is('admin/practical-mark*') ||request()->is('admin/grade-book-upload*') ||request()->is('admin/grade-books*') ||request()->is('admin/grade-sheet*') ||request()->is('admin/consolidated-statement*') ||request()->is('admin/transcript*') ||request()->is('admin/Exam-Cell-Coordinators*') ||request()->is('admin/internal-mark-generation*') ||request()->is('admin/internal-mark-report*') ||request()->is('admin/exam-enrollment*') ||request()->is('admin/exam-fee*') ||request()->is('admin/exam-result-publish*') ||request()->is('admin/result-publish*') ||request()->is('admin/exam-registration*') ||request()->is('admin/Exam-Cell-Coordinators*') ||request()->is('admin/Exam-time-table.*') ||request()->is('admin/Exam-Attendance/*') ||request()->is('admin/examTimetable.*') ||request()->is('admin/Exam-Mark-master/*') ||request()->is('admin/Exam-Attendance-summary-report*') ||request()->is('admin/Result_Analysis_Class_Wise*') ||request()->is('admin/Result_Analysis_Staff_Wise*') ||request()->is('admin/Result_Analysis_Abstract*') ||request()->is('admin/Result_Analysis_bar_chart*') ||request()->is('admin/lab-mark*') ||request()->is('admin/lab-mark*') ||request()->is('admin/lab_Exam_Attendance') ||request()->is('admin/lab_Exam_Attendance/*') ||request()->is('admin/lab_Exam-Attendance/*') ||request()->is('admin/lab_Exam_Mark_master/*') ||request()->is('admin/lab_Exam-Mark') ||request()->is('admin/lab_Exam-Mark/*') ||request()->is('admin/lab_Exam-Attendance-summary-report*') ||request()->is('admin/lab_Result_Analysis_Abstract') ||request()->is('admin/lab_Result_Analysis_Abstract/*') ||request()->is('admin/lab_Result_Analysis_Class_Wise*') ||request()->is('admin/lab_Result_Analysis_Staff_Wise*') ||request()->is('admin/lab_Result_Analysis_bar_chart*') ||request()->is('admin/exam-registrations*') ||request()->is('admin/assignment/*') ||request()->is('admin/assignment/*')? 'menu-open': '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/Exam-time-table*') ? 'active' : '' }} {{ request()->is('admin/practical-mark*') ||request()->is('admin/grade-book-upload*') ||request()->is('admin/grade-books*') ||request()->is('admin/grade-sheet*') ||request()->is('admin/consolidated-statement*') ||request()->is('admin/transcript*') ||request()->is('admin/internal-mark-generation*') ||request()->is('admin/internal-mark-report*') ||request()->is('admin/exam-enrollment*') ||request()->is('admin/exam-fee*') ||request()->is('admin/exam-result-publish*') ||request()->is('admin/result-publish*') ||request()->is('admin/exam-registration*') ||request()->is('admin/Exam-Cell-Coordinators*') ||request()->is('admin/Exam-time-table.*') ||request()->is('admin/Exam-Attendance/*') ||request()->is('admin/examTimetable.*') ||request()->is('admin/Exam-Mark-master/*') ||request()->is('admin/Exam-Attendance-summary-report*') ||request()->is('admin/Result_Analysis_Staff_Wise*') ||request()->is('admin/Result_Analysis_Abstract*') ||request()->is('admin/Result_Analysis_bar_chart*') ||request()->is('admin/lab-mark*') ||request()->is('admin/lab-mark*') ||request()->is('admin/lab_Exam_Attendance') ||request()->is('admin/lab_Exam_Attendance/*') ||request()->is('admin/lab_Exam_Mark_master/*') ||request()->is('admin/lab_Exam-Mark') ||request()->is('admin/lab_Exam-Mark/*') ||request()->is('admin/lab_Exam-Attendance-summary-report*') ||request()->is('admin/lab_Result_Analysis_Abstract') ||request()->is('admin/lab_Result_Analysis_Abstract/*') ||request()->is('admin/lab_Result_Analysis_Class_Wise*') ||request()->is('admin/lab_Result_Analysis_Staff_Wise*') ||request()->is('admin/lab_Result_Analysis_bar_chart*') ||request()->is('admin/exam-registrations*') ||request()->is('admin/assignment/*') ||request()->is('admin/assignment/*')? 'active': '' }}"
                            href="#">
                            <i class="fa nav-icon fas fa-newspaper"></i>
                            <p>
                                COE
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        @can('exam_cell_coordinators_acess')
                            <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                <li class="nav-item">
                                    <a href="{{ route('admin.exam_cell_coordinators.index') }}"
                                        class="nav-link {{ request()->is('admin/Exam-Cell-Coordinators*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-users-cog"></i>
                                        <p>Exam Cell Coordinators</p>
                                    </a>
                                </li>
                            </ul>
                        @endcan
                        @can('cat_mark_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(100, 100, 100, 0.473); color:#ffffff">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/Exam-time-table.*') || request()->is('admin/Exam-Attendance/*') || request()->is('admin/examTimetable.*') || request()->is('admin/Exam-Mark-master/*') || request()->is('admin/Exam-Attendance-summary-report*') || request()->is('admin/Result_Analysis_Class_Wise*') || request()->is('admin/Result_Analysis_Staff_Wise*') || request()->is('admin/Result_Analysis_Abstract*') || request()->is('admin/Result_Analysis_bar_chart*') || request()->is('admin/lab_Exam_Mark_master') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/Exam-time-table.*') || request()->is('admin/Exam-Attendance/*') || request()->is('admin/examTimetable.*') || request()->is('admin/Exam-Mark-master/*') || request()->is('admin/Exam-Attendance-summary-report*') || request()->is('admin/Result_Analysis_Staff_Wise*') || request()->is('admin/Result_Analysis_Abstract*') || request()->is('admin/Result_Analysis_bar_chart*') || request()->is('admin/lab_Exam_Mark_master') ? 'active' : '' }}"
                                        href="#">

                                        <i class="fa nav-icon fas far fa-address-card"></i>
                                        <p>
                                            CAT Marks
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview"
                                        style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                        @can('exam_time_table_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.Exam-time-table.index') }}"
                                                    class="nav-link {{ request()->is('admin/Exam-time-table') || request()->is('admin/Exam-time-table.*') || request()->is('admin/examTimetable.*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-clipboard-list">
                                                    </i>
                                                    <p>
                                                        CAT Schedule
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('exam_attendance_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.Exam-Attendance.attendance') }}"
                                                    class="nav-link {{ request()->is('admin/Exam-Attendance') || request()->is('admin/Exam-Attendance/*') || request()->is('admin/Exam-Attendance/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-user-edit">
                                                    </i>
                                                    <p>
                                                        CAT Attendance
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('exam_mark_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.Exam-Mark.index') }}"
                                                    class="nav-link {{ request()->is('admin/Exam-Mark-master') || request()->is('admin/Exam-Mark-master/*') || request()->is('admin/Exam-Mark-master/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-user-check">
                                                    </i>
                                                    <p>
                                                        CAT Marks Master
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('cat_report_access')
                                            <li
                                                class="nav-item has-treeview {{ request()->is('admin/Exam-Attendance-summary-report*') || request()->is('admin/Result_Analysis_Class_Wise*') || request()->is('admin/Result_Analysis_Staff_Wise*') || request()->is('admin/Result_Analysis_Abstract*') || request()->is('admin/Result_Analysis_bar_chart*') ? 'menu-open' : '' }}">
                                                <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/Exam-Attendance-summary-report*') || request()->is('admin/Result_Analysis_Class_Wise*') || request()->is('admin/Result_Analysis_Staff_Wise*') || request()->is('admin/Result_Analysis_Abstract*') || request()->is('admin/Result_Analysis_bar_chart*') ? 'active' : '' }}"
                                                    href="#">
                                                    <i class="fa nav-icon fas fa-file-invoice"></i>
                                                    <p>
                                                        CAT Reports
                                                        <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview"
                                                    style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                                    @can('cat_attendance_access')
                                                        <li class="nav-item">
                                                            <a href="{{ route('admin.Exam_attendance.summary.index') }}"
                                                                class="nav-link {{ request()->is('admin/Exam-Attendance-summary-report*') ? 'active' : '' }}">
                                                                <i class="fa-fw nav-icon fas fa-file-alt"></i>
                                                                <p> Absentees Summary</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('cat_result_analysis_abstract')
                                                        <li class="nav-item">
                                                            <a href="{{ route('admin.Result_Analysis_Abstract.Abstract') }}"
                                                                class="nav-link {{ request()->is('admin/Result_Analysis_Abstract*') ? 'active' : '' }}">
                                                                <i class="fa-fw nav-icon fas fa-business-time"></i>
                                                                <p>Result Analysis - Abstract</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('cat_result_analysis_class_Wise')
                                                        <li class="nav-item">
                                                            <a href="{{ route('admin.Result_Analysis_Class_Wise.index') }}"
                                                                class="nav-link {{ request()->is('admin/Result_Analysis_Class_Wise*') ? 'active' : '' }}">
                                                                <i class="fa-fw nav-icon fas fa-user-check"></i>
                                                                <p>Result Analysis-Class Wise</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('cat_result_analysis_staff_wise')
                                                        <li class="nav-item">
                                                            <a href="{{ route('admin.Result_Analysis_Staff_Wise.index') }}"
                                                                class="nav-link {{ request()->is('admin/Result_Analysis_Staff_Wise*') ? 'active' : '' }}">
                                                                <i class="fa-fw nav-icon fas fa-user-check"></i>
                                                                <p>Result Analysis-Staff Wise</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('cat_barchart_access')
                                                        <li class="nav-item">
                                                            <a href="{{ route('admin.Result_Analysis_bar_chart.chart') }}"
                                                                class="nav-link {{ request()->is('admin/Result_Analysis_bar_chart*') ? 'active' : '' }}">
                                                                <i class="fa-fw nav-icon fas fa-user-check"></i>
                                                                <p>Bar Chart Analysis </p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    {{-- @endcan --}}
                                                </ul>

                                            </li>
                                        @endcan
                                    </ul>


                                </li>

                            </ul>
                        @endcan
                        @can('lab_mark_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(100, 100, 100, 0.473); color:#a7a3a3">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/lab-mark*') || request()->is('admin/lab_Exam_Attendance') || request()->is('admin/lab_Exam_Attendance/*') || request()->is('admin/lab_Exam_Mark_master') || request()->is('admin/lab_Exam_Mark_master/*') || request()->is('admin/lab_Exam-Mark') || request()->is('admin/lab_Exam-Mark/*') || request()->is('admin/lab_Exam-Attendance-summary-report*') || request()->is('admin/lab_Result_Analysis_Abstract') || request()->is('admin/lab_Result_Analysis_Abstract/*') || request()->is('admin/lab_Result_Analysis_Class_Wise*') || request()->is('admin/lab_Result_Analysis_Staff_Wise*') || request()->is('admin/lab_Result_Analysis_bar_chart*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/lab-mark*') || request()->is('admin/lab_Exam_Attendance') || request()->is('admin/lab_Exam_Attendance/*') || request()->is('admin/lab_Exam_Mark_master') || request()->is('admin/lab_Exam_Mark_master/*') || request()->is('admin/lab_Exam-Mark') || request()->is('admin/lab_Exam-Mark/*') || request()->is('admin/lab_Exam-Attendance-summary-report*') || request()->is('admin/lab_Result_Analysis_Abstract') || request()->is('admin/lab_Result_Analysis_Abstract/*') || request()->is('admin/lab_Result_Analysis_Class_Wise*') || request()->is('admin/lab_Result_Analysis_Staff_Wise*') || request()->is('admin/lab_Result_Analysis_bar_chart*') ? 'active' : '' }}"
                                        href="#">

                                        <i class="fa nav-icon fas fa-flask"></i>
                                        <p>
                                            LAB Marks
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    @can('lab_schedule_access')
                                        <ul class="nav nav-treeview"
                                            style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                            <li class="nav-item">
                                                <a href="{{ route('admin.lab_mark.index') }}"
                                                    class="nav-link {{ request()->is('admin/lab-mark*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-users-cog"></i>
                                                    <p>Lab Marks Schedule</p>
                                                </a>
                                            </li>


                                            @can('exam_mark_access')
                                                <li class="nav-item">
                                                    <a href="{{ route('admin.lab_Exam-Mark.index') }}"
                                                        class="nav-link {{ request()->is('admin/lab_Exam_Mark_master') || request()->is('admin/lab_Exam_Mark_master/*') || request()->is('admin/lab_Exam-Mark') || request()->is('admin/lab_Exam-Mark/*') ? 'active' : '' }}">
                                                        <i class="fa-fw nav-icon fas fa-user-check">
                                                        </i>
                                                        <p>
                                                            LAB Marks Master
                                                        </p>
                                                    </a>
                                                </li>
                                            @endcan

                                            {{--
                                    <li
                                        class="nav-item has-treeview {{ request()->is('admin/lab_Exam-Attendance-summary-report*') || request()->is('admin/lab_Result_Analysis_Abstract') || request()->is('admin/lab_Result_Analysis_Abstract/*') || request()->is('admin/lab_Result_Analysis_Class_Wise*') || request()->is('admin/lab_Result_Analysis_Staff_Wise*') || request()->is('admin/lab_Result_Analysis_bar_chart*') ? 'menu-open' : '' }}">
                                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/lab_Exam-Attendance-summary-report*') || request()->is('admin/lab_Result_Analysis_Abstract') || request()->is('admin/lab_Result_Analysis_Abstract/*') || request()->is('admin/lab_Result_Analysis_Class_Wise*') || request()->is('admin/lab_Result_Analysis_Staff_Wise*') || request()->is('admin/lab_Result_Analysis_bar_chart*') ? 'active' : '' }}"
                                            href="#">
                                            <i class="fa nav-icon fas fa-file-invoice"></i>
                                            <p>
                                                LAB Reports
                                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview"
                                            style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                            <li class="nav-item">
                                                <a href="{{ route('admin.lab_Result_Analysis_Abstract') }}"
                                                    class="nav-link {{ request()->is('admin/lab_Result_Analysis_Abstract/*') || request()->is('admin/lab_Result_Analysis_Abstract') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-business-time"></i>
                                                    <p>LAB Result Analysis - Abstract</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.lab_Result_Analysis_Class_Wise.index') }}"
                                                    class="nav-link {{ request()->is('admin/lab_Result_Analysis_Class_Wise*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-user-check"></i>
                                                    <p>LAB Analysis-Class Wise</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.lab_Result_Analysis_Staff_Wise.index') }}"
                                                    class="nav-link {{ request()->is('admin/lab_Result_Analysis_Staff_Wise*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-user-check"></i>
                                                    <p>LAB Result Analysis-Staff Wise</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.lab_Result_Analysis_bar_chart.chart') }}"
                                                    class="nav-link {{ request()->is('admin/lab_Result_Analysis_bar_chart*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-user-check"></i>
                                                    <p> LAB Bar Chart Analysis </p>
                                                </a>
                                            </li>
                                        </ul>

                                    </li>

                                    --}}
                                        </ul>
                                    @endcan
                                </li>
                            </ul>
                        @endcan
                        @can('assignment_mark_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(100, 100, 100, 0.473); color:#a7a3a3">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/assignment/schedule*') || request()->is('admin/assignment/Mark/*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/assignment/schedule/*') || request()->is('admin/assignment/Mark/*') ? 'active' : '' }}"
                                        href="#">
                                        <i class="fa nav-icon fas fa-sticky-note"></i>
                                        <p>
                                            Assignment Marks
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview"
                                        style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                        @can('assignment_schedule_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.assignment.index') }}"
                                                    class="nav-link {{ request()->is('admin/assignment/schedule') || request()->is('admin/assignment/schedule/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-users-cog"></i>
                                                    <p>Assignment Marks Schedule</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('exam_mark_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.assignment_Exam_Mark.index') }}"
                                                    class="nav-link {{ request()->is('admin/assignment/Mark/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-user-check">
                                                    </i>
                                                    <p>
                                                        Assignment Marks Master
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                            </ul>
                        @endcan
                        @can('internal_mark_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(100, 100, 100, 0.473); color:#a7a3a3">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/internal-mark-generation*') || request()->is('admin/internal-mark-report*') || request()->is('admin/internal/*') || request()->is('admin/assignment/Mark/*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/internal-mark-generation*') || request()->is('admin/internal-mark-report*') || request()->is('admin/internal/*') || request()->is('admin/internal/Mark/*') ? 'active' : '' }}"
                                        href="#">

                                        <i class="fa nav-icon fas fa-money-check"></i>
                                        <p>
                                            Internal Marks
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview"
                                        style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                        @can('internal_generation_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.internalmark_generate') }}"
                                                    class="nav-link {{ request()->is('admin/internal-mark-generation*') || request()->is('admin/internal-mark-generation/index') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-users-cog"></i>
                                                    <p>Internal Mark Generation</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('internal_report_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.internalmark_report.index') }}"
                                                    class="nav-link {{ request()->is('admin/internal-mark-report*') || request()->is('admin/internal-mark-report/index') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-users-cog"></i>
                                                    <p>Internal Mark Report</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                            </ul>
                        @endcan
                        @can('end_exam_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(100, 100, 100, 0.473); color:#a7a3a3">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/practical-mark*') || request()->is('admin/exam-enrollment*') || request()->is('admin/exam-fee*') || request()->is('admin/exam-result-publish*') || request()->is('admin/exam-registration*') || request()->is('admin/result-publish*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/practical-mark*') || request()->is('admin/exam-enrollment*') || request()->is('admin/exam-fee*') || request()->is('admin/exam-result-publish*') || request()->is('admin/exam-registration*') || request()->is('admin/result-publish*') ? 'active' : '' }}"
                                        href="#">

                                        <i class="fa nav-icon fas fa-newspaper"></i>
                                        <p>
                                            End Exam
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview"
                                        style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                        @can('exam_registration_access')
                                            <li
                                                class="nav-item has-treeview {{ request()->is('admin/exam-enrollment*') ? 'menu-open' : '' }}">
                                                <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/exam-enrollment*') ? 'active' : '' }}"
                                                    href="#">
                                                    <i class="fa nav-icon fas fa-file-invoice"></i>
                                                    <p>
                                                        Exam Enrollment
                                                        <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview"
                                                    style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                                    @can('exam_registration_access')
                                                        <li class="nav-item">
                                                            <a href="{{ route('admin.exam-enrollment.classwise-index') }}"
                                                                class="nav-link {{ request()->is('admin/exam-enrollment/classwise*') ? 'active' : '' }}">
                                                                <i class="fa-fw nav-icon fas fa-address-card"></i>
                                                                <p>Class Wise Enrollment</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('exam_registration_access')
                                                        <li class="nav-item">
                                                            <a href="{{ route('admin.exam-enrollment.studentwise-index') }}"
                                                                class="nav-link {{ request()->is('admin/exam-enrollment/studentwise*') || request()->is('admin/exam-enrollment/*enrolled-student*') ? 'active' : '' }}">
                                                                <i class="fa-fw nav-icon fas fa-address-card"></i>
                                                                <p>Student Wise Enrollment</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan
                                        @can('practical_mark_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.practical-marks.index') }}"
                                                    class="nav-link {{ request()->is('admin/practical-marks*') ? 'active' : '' }}">
                                                    <i class="fa nav-icon fas fa-flask"></i>
                                                    <p>Practical Marks Entry</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('practical_mark_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.practical-mark-master.index') }}"
                                                    class="nav-link {{ request()->is('admin/practical-mark-master*') ? 'active' : '' }}">
                                                    <i class="fa nav-icon fas fa-flask"></i>
                                                    <p>Practical Marks Master</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('exam_registration_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.exam-registrations.index') }}"
                                                    class="nav-link {{ request()->is('admin/exam-registrations') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-address-card"></i>
                                                    <p>Exam Registration</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('exam_registration_preview_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.exam-registration-preview') }}"
                                                    class="nav-link {{ request()->is('admin/exam-registration-preview*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-spa"></i>
                                                    <p>Exam Registration Preview</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('exam_attendance_generation_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.exam-registrations-attendanceSheet.index') }}"
                                                    class="nav-link {{ request()->is('admin/exam-registrations-attendanceSheet*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fab fa-envira"></i>
                                                    <p>Attendance Sheet Generation</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('exam_hall_ticket_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.exam-registrations-hallticket.index') }}"
                                                    class="nav-link {{ request()->is('admin/exam-registrations-hallticket*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fab fa-codepen"></i>
                                                    <p>Hall Ticket Generation</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('result_publish_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.result-publish.index') }}"
                                                    class="nav-link {{ request()->is('admin/result-publish*') || request()->is('admin/exam-result-publish*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fab fa-readme"></i>
                                                    <p>Result Publish</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('exam_fee_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.exam-fee.index') }}"
                                                    class="nav-link {{ request()->is('admin/exam-fee*') || request()->is('admin/exam-fee*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-database"></i>
                                                    <p>Exam Fee</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            </ul>
                        @endcan
                        @can('grade_statements_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(100, 100, 100, 0.473); color:#a7a3a3">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/grade-book-upload*') || request()->is('admin/grade-books*') || request()->is('admin/grade-sheet*') || request()->is('admin/consolidated-statement*') || request()->is('admin/transcript*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/grade-book-upload*') || request()->is('admin/grade-books*') || request()->is('admin/grade-sheet*') || request()->is('admin/consolidated-statement*') || request()->is('admin/transcript*') ? 'active' : '' }}"
                                        href="#">

                                        <i class="fa-fw nav-icon fas fa-th-list"></i>
                                        <p>
                                            Grade Statements
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview"
                                        style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">

                                        @can('grade_book_upload_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.grade-book-upload.index') }}"
                                                    class="nav-link {{ request()->is('admin/grade-book-upload*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fab fa-accusoft"></i>
                                                    <p>Grade Book Upload</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('grade_sheet_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.grade-sheet.index') }}"
                                                    class="nav-link {{ request()->is('admin/grade-sheet*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-file-alt"></i>
                                                    <p>Grade Sheet</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('consolidated_statement_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.consolidated-statement.index') }}"
                                                    class="nav-link {{ request()->is('admin/consolidated-statement*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-clipboard"></i>
                                                    <p>Consolidated Statement</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('transcript_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.transcript.index') }}"
                                                    class="nav-link {{ request()->is('admin/transcript*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-table"></i>
                                                    <p>Transcript</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            </ul>
                        @endcan
                    </li>
                @endcan


                {{-- @can('exam_module_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/Exam-time-table*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/Exam-time-table*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa nav-icon fas fa-clipboard-list">
                            </i>
                            <p>
                                Exam
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview "
                            style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                            @can('exam_time_table_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.Exam-time-table.index') }}"
                                        class="nav-link {{ request()->is('admin/Exam-time-table') || request()->is('admin/Exam-time-table.*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-calendar-times">
                                        </i>
                                        <p>
                                            Exam Time Table
                                        </p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan --}}

                @can('fee_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/fee-management*') || request()->is('admin/fee-structure*') || request()->is('admin/fee-data-import*') || request()->is('admin/fee-details*') || request()->is('admin/fee-summary-report*') || request()->is('admin/fee-defaulters-report*') || request()->is('admin/fee-scholarship-report*') || request()->is('admin/fee-category-report*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/fee-management*') || request()->is('admin/fee-structure*') || request()->is('admin/fee-data-import*') || request()->is('admin/fee-details*') || request()->is('admin/fee-summary-report*') || request()->is('admin/fee-defaulters-report*') || request()->is('admin/fee-scholarship-report*') || request()->is('admin/fee-category-report*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa nav-icon fas fa-file-invoice-dollar"></i>
                            <p>
                                Fee Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                            @can('fee_structure_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.fee-structure.index') }}"
                                        class="nav-link {{ request()->is('admin/fee-structure*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-money-check-alt"></i>
                                        <p>Fee Structure Master</p>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('fee_payment_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.fee-data-import.index') }}"
                                        class="nav-link {{ request()->is('admin/fee-data-import*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-rupee-sign"></i>
                                        <p>Fee Data Import</p>
                                    </a>
                                </li>
                            @endcan --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.fee-details.index') }}"
                                    class="nav-link {{ request()->is('admin/fee-details*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-receipt"></i>
                                    <p>Fee Details</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.fee-collection.index') }}"
                                    class="nav-link {{ request()->is('admin/fee-collection*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-receipt"></i>
                                    <p>Fee Collection</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.feeScholarship.index') }}"
                                    class="nav-link {{ request()->is('admin/fee-scholarship*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-receipt"></i>
                                    <p>ScholarShip</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route('admin.fee.year-wise-report') }}"
                                    class="nav-link {{ request()->is('admin/fee/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-receipt"></i>
                                    <p>Year Wise Report</p>
                                </a>
                            </li> --}}
                        </ul>

                        <ul class="nav nav-treeview" style="background-color: rgba(100, 100, 100, 0.473); color:#a7a3a3">
                            <li
                                class="nav-item has-treeview {{ request()->is('admin/fee-summary-report*') || request()->is('admin/fee-defaulters-report*') || request()->is('admin/fee-scholarship-report*') || request()->is('admin/fee-category-report*') ? 'menu-open' : '' }}">
                                <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/fee-summary-report*') || request()->is('admin/fee-defaulters-report*') || request()->is('admin/fee-scholarship-report*') || request()->is('admin/fee-category-report*') ? 'active' : '' }}"
                                    href="#">

                                    <i class="fa nav-icon fas fa-newspaper"></i>
                                    <p>
                                        Fee Reports
                                        <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview"
                                    style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.fee-summary-report.index') }}"
                                            class="nav-link {{ request()->is('admin/fee-summary-report*') || request()->is('admin/fee-summary-report*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fas fa-wallet"></i>
                                            <p>Fee Summary</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.fee-defaulters-report.index') }}"
                                            class="nav-link {{ request()->is('admin/fee-defaulters-report*') || request()->is('admin/fee-defaulters-report*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fas fa-donate"></i>
                                            <p>Fee Defaulters</p>
                                        </a>
                                    </li>
                                    {{-- <li class="nav-item">
                                        <a href="{{ route('admin.fee-scholarship-report.index') }}"
                                            class="nav-link {{ request()->is('admin/fee-scholarship-report*') || request()->is('admin/fee-scholarship-report*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fas fa-address-card"></i>
                                            <p>Scholarship Students</p>
                                        </a>
                                    </li> --}}
                                    <li class="nav-item">
                                        <a href="{{ route('admin.fee-category-report.index') }}"
                                            class="nav-link {{ request()->is('admin/fee-category-report*') || request()->is('admin/fee-category-report*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fab fa-leanpub"></i>
                                            <p>Fee Category</p>
                                        </a>
                                    </li>
                                </ul>
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

                            @can('staff_biometric_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.staff-biometrics.index') }}"
                                        class="nav-link {{ request()->is('admin/staff-biometrics') || request()->is('admin/staff-biometrics/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-fingerprint">

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
                            @can('leave_implement_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.leave-implementation.index') }}"
                                        class="nav-link {{ request()->is('admin/leave-implementation*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-gavel"></i>
                                        <p>Leave Implementation</p>
                                    </a>
                                </li>
                            @endcan
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

                        </ul>
                    </li>
                @endcan
                @can('master_tool_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.tools-degree-types.index') }}"
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
                            request()->is('admin/feedback-type*') ||
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
                @can('hostel_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/hostel*') ? 'menu-open' : '' }} {{ request()->is('admin/hostelRoom*') ? 'menu-open' : '' }} {{ request()->is('admin/room-allot*') ? 'menu-open' : '' }} {{ request()->is('admin/hostel-attendance/*') ? 'menu-open' : '' }} {{ request()->is('admin/hostel-attendance-report/*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/hostel*') ? 'active' : '' }} {{ request()->is('admin/hostelRoom*') ? 'active' : '' }} {{ request()->is('admin/room-allot*') ? 'active' : '' }} {{ request()->is('admin/hostel-attendance/*') ? 'active' : '' }} {{ request()->is('admin/hostel-attendance-report/*') ? 'active' : '' }}"
                            href="#">
                            <i class="fas nav-icon fas fa-hotel">
                            </i>
                            <p>
                                Hostel Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                            @can('hostel_block_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.hostel.index') }}"
                                        class="nav-link {{ request()->is('admin/hostel') || request()->is('admin/hostel/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-h-square">

                                        </i>
                                        <p>
                                            Hostel
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('hostel_warden_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.hostel-warden.index') }}"
                                        class="nav-link {{ request()->is('admin/hostel-warden') || request()->is('admin/hostel-warden/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">
                                        </i>
                                        <p>
                                            Hostel Warden
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('hostel_room_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.hostelRoom.index') }}"
                                        class="nav-link {{ request()->is('admin/hostelRoom') || request()->is('admin/hostelRoom/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-door-open">

                                        </i>
                                        <p>
                                            Hostel Rooms
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('room_allot_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.room-allot.index') }}"
                                        class="nav-link {{ request()->is('admin/room-allot') || request()->is('admin/room-allot/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user"></i>
                                        <p>
                                            Room Allocation
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('hostel_attendance_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.hostel-attendance.index') }}"
                                        class="nav-link {{ request()->is('admin/hostel-attendance') || request()->is('admin/hostel-attendance/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt">

                                        </i>
                                        <p>
                                            Attendance
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('hostel_report_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.hostel-attendance.reportIndex') }}"
                                        class="nav-link {{ request()->is('admin/hostel-attendance-report') || request()->is('admin/hostel-attendance-report/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt">

                                        </i>
                                        <p>
                                            Attendance Report
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('transport_management_access')

                    <li
                        class="nav-item has-treeview {{ request()->is('admin/bus*') ? 'menu-open' : '' }} {{ request()->is('admin/bus-route*') ? 'menu-open' : '' }} {{ request()->is('admin/route-allot*') ? 'menu-open' : '' }} {{ request()->is('admin/driver*') ? 'menu-open' : '' }}  {{ request()->is('admin/transport-report*') ? 'menu-open' : '' }} {{ request()->is('admin/bus-student*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/bus*') ? 'active' : '' }} {{ request()->is('admin/bus-route*') ? 'active' : '' }} {{ request()->is('admin/route-allot*') ? 'active' : '' }} {{ request()->is('admin/driver*') ? 'active' : '' }} {{ request()->is('admin/transport-report*') ? 'active' : '' }} {{ request()->is('admin/bus-student*') ? 'active' : '' }}"
                            href="#">
                            <i class="fas nav-icon fas fa-bus-alt">
                            </i>
                            <p>
                                Transport Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                            @can('bus_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.bus.index') }}"
                                        class="nav-link {{ request()->is('admin/bus') || request()->is('admin/bus/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-bus">
                                        </i>
                                        <p>
                                            Bus
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('driver_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.driver.index') }}"
                                        class="nav-link {{ request()->is('admin/driver') || request()->is('admin/driver/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-male">
                                        </i>
                                        <p>
                                            Driver
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}
                            @can('bus_route_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.bus-route.index') }}"
                                        class="nav-link {{ request()->is('admin/bus-route') || request()->is('admin/bus-route/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-route">

                                        </i>
                                        <p>
                                            Routes
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('route_allot_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.route-allot.index') }}"
                                        class="nav-link {{ request()->is('admin/route-allot*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-walking"></i>
                                        <p>
                                            Bus Driver Allocation
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('bus_student_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.bus-student.index') }}"
                                        class="nav-link {{ request()->is('admin/bus-student*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user-graduate"></i>
                                        <p>
                                            Bus Students Allocation
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('transport_report_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.transport-report.reportIndex') }}"
                                        class="nav-link {{ request()->is('admin/transport-report*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-invoice"></i>
                                        <p>
                                            Transport Report
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan


                @can('library_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/inventory-report*') || request()->is('admin/departWise-report*') || request()->is('admin/rack*') || request()->is('admin/memberWise-report*') || request()->is('admin/book*') || request()->is('admin/book-allocate*') || request()->is('admin/driver*') || request()->is('admin/genre*') || request()->is('admin/reserve-report*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/inventory-report*') || request()->is('admin/departWise-report*') || request()->is('admin/rack*') || request()->is('admin/memberWise-report*') || request()->is('admin/book*') || request()->is('admin/book-allocate*') || request()->is('admin/driver*') || request()->is('admin/genre*') || request()->is('admin/reserve-report*') ? 'active' : '' }}"
                            href="#">
                            <i class="fas nav-icon fas fa-building">
                            </i>
                            <p>
                                Library Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        @can('library_management_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/rack*') || request()->is('admin/genre*') || request()->is('admin/book') || request()->is('admin/book-allocate*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/rack*') || request()->is('admin/genre*') || request()->is('admin/book') || request()->is('admin/book-allocate*') ? 'active' : '' }}"
                                        href="#">

                                        <i class="fa nav-icon fas far fa-address-card"></i>
                                        <p>
                                            Books
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="">
                                        @can('library_rack_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.rack.index') }}"
                                                    class="nav-link {{ request()->is('admin/rack') || request()->is('admin/rack/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-swatchbook">
                                                    </i>
                                                    <p>
                                                        Racks
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('genre_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.genre.index') }}"
                                                    class="nav-link {{ request()->is('admin/genre') || request()->is('admin/genre/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-holly-berry">
                                                    </i>
                                                    <p>
                                                        Genre
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('library_book_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.book.index') }}"
                                                    class="nav-link {{ request()->is('admin/book') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-book">
                                                    </i>
                                                    <p>
                                                        Books
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('book_allote_access')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.book-allocate.index') }}"
                                                    class="nav-link {{ request()->is('admin/book-allocate') || request()->is('admin/book-allocate/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-layer-group">
                                                    </i>
                                                    <p>
                                                        Books Allocation
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>


                                </li>

                            </ul>
                        @endcan

                        @can('book_issue_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                <li class="nav-item">
                                    <a href="{{ route('admin.book-issue.index') }}"
                                        class="nav-link {{ request()->is('admin/book-issue') || request()->is('admin/book-issue/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-server">
                                        </i>
                                        <p>
                                            Book Issue
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        @endcan

                        @can('library_report_access')
                            <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                <li
                                    class="nav-item has-treeview {{ request()->is('admin/inventory-report*') || request()->is('admin/reserve-report*') || request()->is('admin/departWise-report*') || request()->is('admin/memberWise-report*') ? 'menu-open' : '' }}">
                                    <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/inventory-report*') || request()->is('admin/departWise-report*') || request()->is('admin/reserve-report*') || request()->is('admin/memberWise-report*') ? 'active' : '' }}"
                                        href="#">

                                        <i class="fa nav-icon fas far fa-address-card"></i>
                                        <p>
                                            Library Reports
                                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="">
                                        @can('library_reservation_report')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.reserve-report.reserveReport') }}"
                                                    class="nav-link {{ request()->is('admin/reserve-report') || request()->is('admin/reserve-report/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-clipboard">
                                                    </i>
                                                    <p>
                                                        Reservation Report
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('library_member_wise_report')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.memberWise-report.memberWiseReport') }}"
                                                    class="nav-link {{ request()->is('admin/memberWise-report') || request()->is('admin/memberWise-report/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-user">
                                                    </i>
                                                    <p>
                                                        Member Wise Report
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('library_department_wise_report')
                                            <li class="nav-item">
                                                <a href="{{ url('admin/departWise-report/report/student') }}"
                                                    class="nav-link {{ request()->is('admin/departWise-report') || request()->is('admin/departWise-report/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-window-restore">
                                                    </i>
                                                    <p>
                                                        Department Wise Report
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('library_inventory_report')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.inventory-report.inventory') }}"
                                                    class="nav-link {{ request()->is('admin/inventory-report') || request()->is('admin/inventory-report/*') ? 'active' : '' }}">
                                                    <i class="fa-fw nav-icon fas fa-warehouse">
                                                    </i>
                                                    <p>
                                                        Inventory Report
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            </ul>
                        @endcan


                    </li>
                @endcan

                @can('feedback_management_access')

                    <li
                        class="nav-item has-treeview {{ request()->is('admin/configure-feedback*') ? 'menu-open' : '' }} {{ request()->is('admin/schedule-feedback*') ? 'menu-open' : '' }} {{ request()->is('admin/feedReport-training*') ? 'menu-open' : '' }} {{ request()->is('admin/feedReport-course*') ? 'menu-open' : '' }}  {{ request()->is('admin/feedReport-faculty*') ? 'menu-open' : '' }} {{ request()->is('admin/feedReport-external*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/configure-feedback*') ? 'active' : '' }} {{ request()->is('admin/schedule-feedback*') ? 'active' : '' }} {{ request()->is('admin/feedReport-training*') ? 'active' : '' }} {{ request()->is('admin/feedReport-course*') ? 'active' : '' }} {{ request()->is('admin/feedReport-faculty*') ? 'active' : '' }} {{ request()->is('admin/feedReport-external*') ? 'active' : '' }}"
                            href="#">
                            <i class="fas nav-icon fas fa-comment">
                            </i>
                            <p>
                                Feedback Management
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                            @can('configure_feedback_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.configure-feedback.index') }}"
                                        class="nav-link {{ request()->is('admin/configure-feedback') || request()->is('admin/configure-feedback/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-comments">
                                        </i>
                                        <p>
                                            Configure FeedBack
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('configure_feedback_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.schedule-feedback.index') }}"
                                        class="nav-link {{ request()->is('admin/schedule-feedback') || request()->is('admin/schedule-feedback/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-sticky-note">
                                        </i>
                                        <p>
                                            FeedBack Schedule
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            <li
                                class="nav-item has-treeview {{ request()->is('admin/feedReport-training*') || request()->is('admin/feedReport-faculty*') || request()->is('admin/feedReport-external*') || request()->is('admin/fee-category-report*') || request()->is('admin/feedReport-course*') ? 'menu-open' : '' }}">
                                <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/feedReport-training*') || request()->is('admin/feedReport-faculty*') || request()->is('admin/feedReport-external*') || request()->is('admin/fee-category-report*') || request()->is('admin/feedReport-course*') ? 'active' : '' }}"
                                    href="#">

                                    <i class="fa nav-icon fas fa-newspaper"></i>
                                    <p>
                                        Feedback Reports
                                        <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview"
                                    style="background-color: rgba(128, 128, 128, 0.473); color:#ffffff">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.feedReport-training.index') }}"
                                            class="nav-link {{ request()->is('admin/feedReport-training*') || request()->is('admin/feedReport-training*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fas fa-wallet"></i>
                                            <p>Student Training Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.feedReport-course.index') }}"
                                            class="nav-link {{ request()->is('admin/feedReport-course*') || request()->is('admin/feedReport-course*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fas fa-wallet"></i>
                                            <p>Student Course Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.feedReport-faculty.index') }}"
                                            class="nav-link {{ request()->is('admin/feedReport-faculty*') || request()->is('admin/feedReport-faculty*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fas fa-wallet"></i>
                                            <p>Faculty Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.feedReport-external.index') }}"
                                            class="nav-link {{ request()->is('admin/feedReport-external*') || request()->is('admin/feedReport-external*') ? 'active' : '' }}">
                                            <i class="fa-fw nav-icon fas fa-wallet"></i>
                                            <p>External Report</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- @can('feedback_access')
                    @php
                        $userId = auth()->user()->id;
                        $user = \App\Models\User::find($userId);
                        if ($user) {
                            $assignedRole = $user->roles->first();

                            if ($assignedRole) {
                                $roleTitle = $assignedRole->id;
                            }
                        }

                        if($roleTitle == 11){
                            $data='About Staff';
                        }else{
                            $data='';
                        }
                    @endphp
                    <li class="nav-item">
                        <a href="{{ route('admin.FeedBack.index') }}"
                            class="nav-link {{ request()->is('admin/FeedBack') || request()->is('admin/FeedBack/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-award">

                            </i>
                            <p>
                                Feed Back
                            </p>
                        </a>
                    </li>
                @endcan --}}
                {{-- @can('user_alert_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.user-alerts.index') }}"
                            class="nav-link {{ request()->is('admin/user-alerts') || request()->is('admin/user-alerts/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-bell">

                            </i>
                            <p>
                                {{ trans('cruds.userAlert.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}
                {{-- @can('asset_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/asset-categories*') ? 'menu-open' : '' }} {{ request()->is('admin/asset-locations*') ? 'menu-open' : '' }} {{ request()->is('admin/asset-statuses*') ? 'menu-open' : '' }} {{ request()->is('admin/assets*') ? 'menu-open' : '' }} {{ request()->is('admin/assets-histories*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/asset-categories*') ? 'active' : '' }} {{ request()->is('admin/asset-locations*') ? 'active' : '' }} {{ request()->is('admin/asset-statuses*') ? 'active' : '' }} {{ request()->is('admin/assets*') ? 'active' : '' }} {{ request()->is('admin/assets-histories*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-book">

                            </i>
                            <p>
                                {{ trans('cruds.assetManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                            @can('asset_category_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.asset-categories.index') }}"
                                        class="nav-link {{ request()->is('admin/asset-categories') || request()->is('admin/asset-categories/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-tags">

                                        </i>
                                        <p>
                                            {{ trans('cruds.assetCategory.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('asset_location_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.asset-locations.index') }}"
                                        class="nav-link {{ request()->is('admin/asset-locations') || request()->is('admin/asset-locations/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-map-marker">

                                        </i>
                                        <p>
                                            {{ trans('cruds.assetLocation.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('asset_status_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.asset-statuses.index') }}"
                                        class="nav-link {{ request()->is('admin/asset-statuses') || request()->is('admin/asset-statuses/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-server">

                                        </i>
                                        <p>
                                            {{ trans('cruds.assetStatus.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('asset_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.assets.index') }}"
                                        class="nav-link {{ request()->is('admin/assets') || request()->is('admin/assets/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-book">

                                        </i>
                                        <p>
                                            {{ trans('cruds.asset.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('assets_history_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.assets-histories.index') }}"
                                        class="nav-link {{ request()->is('admin/assets-histories') || request()->is('admin/assets-histories/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-th-list">

                                        </i>
                                        <p>
                                            {{ trans('cruds.assetsHistory.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan --}}
                {{-- @can('task_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/task-statuses*') ? 'menu-open' : '' }} {{ request()->is('admin/task-tags*') ? 'menu-open' : '' }} {{ request()->is('admin/tasks*') ? 'menu-open' : '' }} {{ request()->is('admin/tasks-calendars*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/task-statuses*') ? 'active' : '' }} {{ request()->is('admin/task-tags*') ? 'active' : '' }} {{ request()->is('admin/tasks*') ? 'active' : '' }} {{ request()->is('admin/tasks-calendars*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-list">

                            </i>
                            <p>
                                {{ trans('cruds.taskManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                            @can('task_status_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.task-statuses.index') }}"
                                        class="nav-link {{ request()->is('admin/task-statuses') || request()->is('admin/task-statuses/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-server">

                                        </i>
                                        <p>
                                            {{ trans('cruds.taskStatus.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('task_tag_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.task-tags.index') }}"
                                        class="nav-link {{ request()->is('admin/task-tags') || request()->is('admin/task-tags/*') ? 'active' : '' }}">

                                        <i class="fa-fw nav-icon fas fa-tag"></i>
                                        </i>
                                        <p>
                                            {{ trans('cruds.taskTag.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('task_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.tasks.index') }}"
                                        class="nav-link {{ request()->is('admin/tasks') || request()->is('admin/tasks/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.task.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('tasks_calendar_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.tasks-calendars.index') }}"
                                        class="nav-link {{ request()->is('admin/tasks-calendars') || request()->is('admin/tasks-calendars/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-calendar">

                                        </i>
                                        <p>
                                            {{ trans('cruds.tasksCalendar.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan --}}
                {{-- @can('faq_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/faq-categories*') ? 'menu-open' : '' }} {{ request()->is('admin/faq-questions*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/faq-categories*') ? 'active' : '' }} {{ request()->is('admin/faq-questions*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-question">

                            </i>
                            <p>
                                {{ trans('cruds.faqManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                            @can('faq_category_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.faq-categories.index') }}"
                                        class="nav-link {{ request()->is('admin/faq-categories') || request()->is('admin/faq-categories/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.faqCategory.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('faq_question_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.faq-questions.index') }}"
                                        class="nav-link {{ request()->is('admin/faq-questions') || request()->is('admin/faq-questions/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-question">

                                        </i>
                                        <p>
                                            {{ trans('cruds.faqQuestion.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan --}}
                {{-- @can('content_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/content-categories*') ? 'menu-open' : '' }} {{ request()->is('admin/content-tags*') ? 'menu-open' : '' }} {{ request()->is('admin/content-pages*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/content-categories*') ? 'active' : '' }} {{ request()->is('admin/content-tags*') ? 'active' : '' }} {{ request()->is('admin/content-pages*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-book">

                            </i>
                            <p>
                                {{ trans('cruds.contentManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                            @can('content_category_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.content-categories.index') }}"
                                        class="nav-link {{ request()->is('admin/content-categories') || request()->is('admin/content-categories/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-folder">

                                        </i>
                                        <p>
                                            {{ trans('cruds.contentCategory.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('content_tag_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.content-tags.index') }}"
                                        class="nav-link {{ request()->is('admin/content-tags') || request()->is('admin/content-tags/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-tags">

                                        </i>
                                        <p>
                                            {{ trans('cruds.contentTag.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('content_page_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.content-pages.index') }}"
                                        class="nav-link {{ request()->is('admin/content-pages') || request()->is('admin/content-pages/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file">

                                        </i>
                                        <p>
                                            {{ trans('cruds.contentPage.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan --}}
                {{-- @can('expense_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/expense-categories*') ? 'menu-open' : '' }} {{ request()->is('admin/income-categories*') ? 'menu-open' : '' }} {{ request()->is('admin/expenses*') ? 'menu-open' : '' }} {{ request()->is('admin/incomes*') ? 'menu-open' : '' }} {{ request()->is('admin/expense-reports*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/expense-categories*') ? 'active' : '' }} {{ request()->is('admin/income-categories*') ? 'active' : '' }} {{ request()->is('admin/expenses*') ? 'active' : '' }} {{ request()->is('admin/incomes*') ? 'active' : '' }} {{ request()->is('admin/expense-reports*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-money-bill">

                            </i>
                            <p>
                                {{ trans('cruds.expenseManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(128, 128, 128, 0.473); colour:#ffffff">
                            @can('expense_category_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.expense-categories.index') }}"
                                        class="nav-link {{ request()->is('admin/expense-categories') || request()->is('admin/expense-categories/*') ? 'active' : '' }}">

                                        <i class="fa-fw nav-icon fab fa-slack-hash"></i>
                                        </i>
                                        <p>
                                            {{ trans('cruds.expenseCategory.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('income_category_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.income-categories.index') }}"
                                        class="nav-link {{ request()->is('admin/income-categories') || request()->is('admin/income-categories/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-list">

                                        </i>
                                        <p>
                                            {{ trans('cruds.incomeCategory.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('expense_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.expenses.index') }}"
                                        class="nav-link {{ request()->is('admin/expenses') || request()->is('admin/expenses/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-arrow-circle-right">

                                        </i>
                                        <p>
                                            {{ trans('cruds.expense.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('income_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.incomes.index') }}"
                                        class="nav-link {{ request()->is('admin/incomes') || request()->is('admin/incomes/*') ? 'active' : '' }}">

                                        <i class="fa-fw nav-icon fas fa-ticket-alt"></i>
                                        </i>
                                        <p>
                                            {{ trans('cruds.income.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('expense_report_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.expense-reports.index') }}"
                                        class="nav-link {{ request()->is('admin/expense-reports') || request()->is('admin/expense-reports/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-chart-line">

                                        </i>
                                        <p>
                                            {{ trans('cruds.expenseReport.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan --}}


                {{-- @can('payment_gateway_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.payment-gateways.index') }}"
                            class="nav-link {{ request()->is('admin/payment-gateways') || request()->is('admin/payment-gateways/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-landmark">

                            </i>
                            <p>
                                {{ trans('cruds.paymentGateway.title') }}
                            </p>
                        </a>
                    </li>
                @endcan --}}

                <li class="nav-item">
                    <a href="{{ route('admin.systemCalendar') }}"
                        class="nav-link {{ request()->is('admin/system-calendar') || request()->is('admin/system-calendar/*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-calendar nav-icon">

                        </i>
                        <p>
                            {{ trans('global.systemCalendar') }}
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.erp-setting.index') }}"
                        class="nav-link {{ request()->is('admin/erp-setting') || request()->is('admin/erp-setting/*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-calendar nav-icon">

                        </i>
                        <p>
                            ERP Settings
                        </p>
                    </a>
                </li> --}}
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
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}"
                        class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
                        <i class="fa-fw nav-icon fas fa-cogs">

                        </i>
                        <p>
                            {{ trans('cruds.setting.title') }}
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>

</aside>
