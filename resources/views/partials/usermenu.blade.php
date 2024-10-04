<ul class="nav nav-pills nav-sidebar flex-column">

    @can('academic_detail_access')
        <li class="nav-item">
            <a href="{{ route('admin.academic-details.index') }}"
                class="nav-link {{ request()->is('admin/academic-details') || request()->is('admin/academic-details/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-graduation-cap">

                </i>
                <p>
                    {{ trans('cruds.academicDetail.title') }}
                </p>
            </a>
        </li>
    @endcan



    @can('personal_detail_access')
        <li class="nav-item">
            <button href="" id="personaldetails" class="nav-link ">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.personalDetail.title') }}
                </p>
            </button>
        </li>
    @endcan


    @can('educational_detail_access')
        <li class="nav-item">
            <a href="{{ route('admin.educational-details.index') }}"
                class="nav-link {{ request()->is('admin/educational-details') || request()->is('admin/educational-details/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.educationalDetail.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('parent_detail_access')
        <li class="nav-item">
            <a href="{{ route('admin.parent-details.index') }}"
                class="nav-link {{ request()->is('admin/parent-details') || request()->is('admin/parent-details/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.parentDetail.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('address_access')
        <li class="nav-item">
            <a href="{{ route('admin.addresses.index') }}"
                class="nav-link {{ request()->is('admin/addresses') || request()->is('admin/addresses/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.address.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('bank_account_detail_access')
        <li class="nav-item">
            <a href="{{ route('admin.bank-account-details.index') }}"
                class="nav-link {{ request()->is('admin/bank-account-details') || request()->is('admin/bank-account-details/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.bankAccountDetail.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('experience_detail_access')
        <li class="nav-item">
            <a href="{{ route('admin.experience-details.index') }}"
                class="nav-link {{ request()->is('admin/experience-details') || request()->is('admin/experience-details/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.experienceDetail.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('teaching_staff_access')
        <li class="nav-item">
            <a href="{{ route('admin.teaching-staffs.index') }}"
                class="nav-link {{ request()->is('admin/teaching-staffs') || request()->is('admin/teaching-staffs/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

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
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.nonTeachingStaff.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('add_conference_access')
        <li class="nav-item">
            <a href="{{ route('admin.add-conferences.index') }}"
                class="nav-link {{ request()->is('admin/add-conferences') || request()->is('admin/add-conferences/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.addConference.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('entrance_exam_access')
        <li class="nav-item">
            <a href="{{ route('admin.entrance-exams.index') }}"
                class="nav-link {{ request()->is('admin/entrance-exams') || request()->is('admin/entrance-exams/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.entranceExam.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('guest_lecture_access')
        <li class="nav-item">
            <a href="{{ route('admin.guest-lectures.index') }}"
                class="nav-link {{ request()->is('admin/guest-lectures') || request()->is('admin/guest-lectures/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.guestLecture.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('industrial_training_access')
        <li class="nav-item">
            <a href="{{ route('admin.industrial-trainings.index') }}"
                class="nav-link {{ request()->is('admin/industrial-trainings') || request()->is('admin/industrial-trainings/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.industrialTraining.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('intern_access')
        <li class="nav-item">
            <a href="{{ route('admin.interns.index') }}"
                class="nav-link {{ request()->is('admin/interns') || request()->is('admin/interns/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.intern.title') }}
                </p>
            </a>
        </li>
    @endcan


    @can('industrial_experience_access')
        <li class="nav-item">
            <a href="{{ route('admin.industrial-experiences.index') }}"
                class="nav-link {{ request()->is('admin/industrial-experiences') || request()->is('admin/industrial-experiences/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.industrialExperience.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('iv_access')
        <li class="nav-item">
            <a href="{{ route('admin.ivs.index') }}"
                class="nav-link {{ request()->is('admin/ivs') || request()->is('admin/ivs/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.iv.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('online_course_access')
        <li class="nav-item">
            <a href="{{ route('admin.online-courses.index') }}"
                class="nav-link {{ request()->is('admin/online-courses') || request()->is('admin/online-courses/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.onlineCourse.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('document_access')
        <li class="nav-item">
            <a href="{{ route('admin.documents.index') }}"
                class="nav-link {{ request()->is('admin/documents') || request()->is('admin/documents/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.document.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('seminar_access')
        <li class="nav-item">
            <a href="{{ route('admin.seminars.index') }}"
                class="nav-link {{ request()->is('admin/seminars') || request()->is('admin/seminars/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.seminar.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('sabotical_access')
        <li class="nav-item">
            <a href="{{ route('admin.saboticals.index') }}"
                class="nav-link {{ request()->is('admin/saboticals') || request()->is('admin/saboticals/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.sabotical.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('sponser_access')
        <li class="nav-item">
            <a href="{{ route('admin.sponsers.index') }}"
                class="nav-link {{ request()->is('admin/sponsers') || request()->is('admin/sponsers/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.sponser.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('sttp_access')
        <li class="nav-item">
            <a href="{{ route('admin.sttps.index') }}"
                class="nav-link {{ request()->is('admin/sttps') || request()->is('admin/sttps/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.sttp.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('workshop_access')
        <li class="nav-item">
            <a href="{{ route('admin.workshops.index') }}"
                class="nav-link {{ request()->is('admin/workshops') || request()->is('admin/workshops/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.workshop.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('patent_access')
        <li class="nav-item">
            <a href="{{ route('admin.patents.index') }}"
                class="nav-link {{ request()->is('admin/patents') || request()->is('admin/patents/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.patent.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('award_access')
        <li class="nav-item">
            <a href="{{ route('admin.awards.index') }}"
                class="nav-link {{ request()->is('admin/awards') || request()->is('admin/awards/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.award.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('fundingdetali_access')
        <li class="nav-item">
            <a href="{{ route('admin.fundingdetalis.index') }}"
                class="nav-link {{ request()->is('admin/fundingdetalis') || request()->is('admin/fundingdetalis/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.fundingdetali.title') }}
                </p>
            </a>
        </li>
    @endcan
</ul>
