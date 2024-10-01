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

                @can('master_tool_access')
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
                    <a href="{{ route('admin.systemCalendar') }}"
                        class="nav-link {{ request()->is('admin/system-calendar') || request()->is('admin/system-calendar/*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-calendar nav-icon">

                        </i>
                        <p>
                            {{ trans('global.systemCalendar') }}
                        </p>
                    </a>
                </li>

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
