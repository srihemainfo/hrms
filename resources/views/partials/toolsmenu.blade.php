<style>
    .search-input-container-1 {
        position: relative;
        overflow: auto;
    }

    .search-input-container-1 input[type="search"] {
        padding: 7px 7px 7px 47px;
        width: 100%;
        background: #ededed url(https://static.tumblr.com/ftv85bp/MIXmud4tx/search-icon.png) no-repeat 9px center;
        border: solid 1px #ccc;
        border-bottom-left-radius: 25px;
        border-top-left-radius: 25px;
        transition: all .5s;
        margin-bottom: 4px;
    }

    .search-input-container-1 input[type="search"]:focus {
        width: 100%;
        background-color: #fff;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(109, 207, 246, .5);
        outline: none;
    }
</style>
<div class="input-field" id="demo-1">
    <div class="search-input-container-1">
        <input type="search" id="searchInput-1" placeholder="Search..." class="menu_searcher-1 autocomplete"
            autocomplete="off" value="">
    </div>
</div>
<ul class="nav nav-pills nav-sidebar flex-column tools-menu" id="list-1">


    @can('blood_group_access')
        <li class="nav-item">
            <a href="{{ route('admin.blood-groups.index') }}"
                class="nav-link {{ request()->is('admin/blood-groups') || request()->is('admin/blood-groups/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-tint">

                </i>
                <p>
                    {{ trans('cruds.bloodGroup.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('community_access')
        <li class="nav-item">
            <a href="{{ route('admin.communities.index') }}"
                class="nav-link {{ request()->is('admin/communities') || request()->is('admin/communities/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-users">

                </i>
                <p>
                    {{ trans('cruds.community.title') }}
                </p>
            </a>
        </li>
    @endcan

<<<<<<< HEAD

    @can('designation_access')
=======

    @can('designation_access')
        <li class="nav-item">
            <a href="{{ route('admin.designation.index') }}"
                class="nav-link {{ request()->is('admin/designation') || request()->is('admin/designation/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-user"></i>
                <p>
                    Designation
                </p>
            </a>
        </li>
    @endcan

    @can('education_type_access')
>>>>>>> 6563285674506c09c4794a263e688088e7e74606
        <li class="nav-item">
            <a href="{{ route('admin.designation.index') }}"
                class="nav-link {{ request()->is('admin/designation') || request()->is('admin/designation/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-user"></i>
                <p>
                    Designation
                </p>
            </a>
        </li>
    @endcan

    @can('leave_type_access')
        <li class="nav-item">
            <a href="{{ route('admin.leave-types.index') }}"
                class="nav-link {{ request()->is('admin/leave-types') || request()->is('admin/leave-types/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-monument">
<<<<<<< HEAD
=======

                </i>
                <p>

                    {{ trans('cruds.leaveType.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('mother_tongue_access')
        <li class="nav-item">
            <a href="{{ route('admin.mother-tongues.index') }}"
                class="nav-link {{ request()->is('admin/mother-tongues') || request()->is('admin/mother-tongues/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-language">

                </i>
                <p>
                    {{ trans('cruds.motherTongue.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('mediumof_studied_access')
        <li class="nav-item">
            <a href="{{ route('admin.mediumof-studieds.index') }}"
                class="nav-link {{ request()->is('admin/mediumof-studieds') || request()->is('admin/mediumof-studieds/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-ticket-alt">

                </i>
                <p>
                    {{ trans('cruds.mediumofStudied.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('nationality_access')
        <li class="nav-item">
            <a href="{{ route('admin.nationalities.index') }}"
                class="nav-link {{ request()->is('admin/nationalities') || request()->is('admin/nationalities/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-globe-asia">

                </i>
                <p>
                    {{ trans('cruds.nationality.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('religion_access')
        <li class="nav-item">
            <a href="{{ route('admin.religions.index') }}"
                class="nav-link {{ request()->is('admin/religions') || request()->is('admin/religions/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-pray">

                </i>
                <p>
                    {{ trans('cruds.religion.title') }}
                </p>
            </a>
        </li>
    @endcan
>>>>>>> 6563285674506c09c4794a263e688088e7e74606

    @can('state_access')
        <li class="nav-item">
            <a href="{{ route('admin.state.index') }}"
                class="nav-link {{ request()->is('admin/state') || request()->is('admin/state/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-map"></i>
                <p>
                    State
                </p>
            </a>
        </li>
    @endcan
<<<<<<< HEAD

    @can('nationality_access')
        <li class="nav-item">
            <a href="{{ route('admin.nationalities.index') }}"
                class="nav-link {{ request()->is('admin/nationalities') || request()->is('admin/nationalities/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-globe-asia">

                </i>
                <p>
                    {{ trans('cruds.nationality.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('religion_access')
        <li class="nav-item">
            <a href="{{ route('admin.religions.index') }}"
                class="nav-link {{ request()->is('admin/religions') || request()->is('admin/religions/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-pray">

                </i>
                <p>
                    {{ trans('cruds.religion.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('state_access')
        <li class="nav-item">
            <a href="{{ route('admin.state.index') }}"
                class="nav-link {{ request()->is('admin/state') || request()->is('admin/state/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-map"></i>
                <p>
                    State
                </p>
            </a>
        </li>
    @endcan

=======

>>>>>>> 6563285674506c09c4794a263e688088e7e74606

    @can('email_setting_access')
        <li class="nav-item">
            <a href="{{ route('admin.email-settings.index') }}"
                class="nav-link {{ request()->is('admin/email-settings') || request()->is('admin/email-settings/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.emailSetting.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('sms_setting_access')
        <li class="nav-item">
            <a href="{{ route('admin.sms-settings.index') }}"
                class="nav-link {{ request()->is('admin/sms-settings') || request()->is('admin/sms-settings/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.smsSetting.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('email_template_access')
        <li class="nav-item">
            <a href="{{ route('admin.email-templates.index') }}"
                class="nav-link {{ request()->is('admin/email-templates') || request()->is('admin/email-templates/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon far fa-envelope">

                </i>
                <p>
                    {{ trans('cruds.emailTemplate.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('sms_template_access')
        <li class="nav-item">
            <a href="{{ route('admin.sms-templates.index') }}"
                class="nav-link {{ request()->is('admin/sms-templates') || request()->is('admin/sms-templates/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-envelope">

                </i>
                <p>
                    {{ trans('cruds.smsTemplate.title') }}
                </p>
            </a>
        </li>
    @endcan
</ul>

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#searchInput-1').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();

                $('#list-1 li').each(function() {
                    var listItemText = $(this).text().toLowerCase();

                    if (listItemText.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endsection
