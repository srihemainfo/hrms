@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <!-- {{ trans('cruds.tasksCalendar.title') }} -->
        Tools
    </div>

    <div class="card-body">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css" />
        <div id="tools" class="row">
<div class="col-4">
<ul class="nav nav-pills nav-sidebar flex-column">


@can('tools_degree_type_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.tools-degree-types.index") }}" class="nav-link {{ request()->is("admin/tools-degree-types") || request()->is("admin/tools-degree-types/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-graduation-cap">

                                        </i>
                                        <p>
                                            {{ trans('cruds.toolsDegreeType.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('batch_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.batches.index") }}" class="nav-link {{ request()->is("admin/batches") || request()->is("admin/batches/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-users">

                                        </i>
                                        <p>
                                            {{ trans('cruds.batch.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('academic_year_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.academic-years.index") }}" class="nav-link {{ request()->is("admin/academic-years") || request()->is("admin/academic-years/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.academicYear.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan

                           @can('tools_course_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.tools-courses.index") }}" class="nav-link {{ request()->is("admin/tools-courses") || request()->is("admin/tools-courses/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fab fa-accusoft">

                                        </i>
                                        <p>
                                            {{ trans('cruds.toolsCourse.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('tools_department_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.tools-departments.index") }}" class="nav-link {{ request()->is("admin/tools-departments") || request()->is("admin/tools-departments/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-building">

                                        </i>
                                        <p>
                                            {{ trans('cruds.toolsDepartment.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('semester_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.semesters.index") }}" class="nav-link {{ request()->is("admin/semesters") || request()->is("admin/semesters/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.semester.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('section_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.sections.index") }}" class="nav-link {{ request()->is("admin/sections") || request()->is("admin/sections/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-puzzle-piece">

                                        </i>
                                        <p>
                                            {{ trans('cruds.section.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('toolssyllabus_year_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.toolssyllabus-years.index") }}" class="nav-link {{ request()->is("admin/toolssyllabus-years") || request()->is("admin/toolssyllabus-years/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-book">

                                        </i>
                                        <p>
                                            {{ trans('cruds.toolssyllabusYear.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('course_enroll_master_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.course-enroll-masters.index") }}" class="nav-link {{ request()->is("admin/course-enroll-masters") || request()->is("admin/course-enroll-masters/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.courseEnrollMaster.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('nationality_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.nationalities.index") }}" class="nav-link {{ request()->is("admin/nationalities") || request()->is("admin/nationalities/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.nationality.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('religion_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.religions.index") }}" class="nav-link {{ request()->is("admin/religions") || request()->is("admin/religions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.religion.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('blood_group_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.blood-groups.index") }}" class="nav-link {{ request()->is("admin/blood-groups") || request()->is("admin/blood-groups/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.bloodGroup.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('community_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.communities.index") }}" class="nav-link {{ request()->is("admin/communities") || request()->is("admin/communities/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.community.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('mother_tongue_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.mother-tongues.index") }}" class="nav-link {{ request()->is("admin/mother-tongues") || request()->is("admin/mother-tongues/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.motherTongue.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('education_board_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.education-boards.index") }}" class="nav-link {{ request()->is("admin/education-boards") || request()->is("admin/education-boards/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.educationBoard.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('education_type_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.education-types.index") }}" class="nav-link {{ request()->is("admin/education-types") || request()->is("admin/education-types/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.educationType.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('scholarship_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.scholarships.index") }}" class="nav-link {{ request()->is("admin/scholarships") || request()->is("admin/scholarships/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.scholarship.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('subject_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.subjects.index") }}" class="nav-link {{ request()->is("admin/subjects") || request()->is("admin/subjects/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.subject.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('mediumof_studied_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.mediumof-studieds.index") }}" class="nav-link {{ request()->is("admin/mediumof-studieds") || request()->is("admin/mediumof-studieds/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.mediumofStudied.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('teaching_type_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.teaching-types.index") }}" class="nav-link {{ request()->is("admin/teaching-types") || request()->is("admin/teaching-types/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.teachingType.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('examstaff_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.examstaffs.index") }}" class="nav-link {{ request()->is("admin/examstaffs") || request()->is("admin/examstaffs/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.examstaff.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('college_block_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.college-blocks.index") }}" class="nav-link {{ request()->is("admin/college-blocks") || request()->is("admin/college-blocks/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.collegeBlock.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('foundation_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.foundations.index") }}" class="nav-link {{ request()->is("admin/foundations") || request()->is("admin/foundations/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            Foundations
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('leave_status_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.leave-statuses.index") }}" class="nav-link {{ request()->is("admin/leave-statuses") || request()->is("admin/leave-statuses/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.leaveStatus.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('class_room_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.class-rooms.index") }}" class="nav-link {{ request()->is("admin/class-rooms") || request()->is("admin/class-rooms/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.classRoom.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('email_setting_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.email-settings.index") }}" class="nav-link {{ request()->is("admin/email-settings") || request()->is("admin/email-settings/*") ? "active" : "" }}">
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
                                    <a href="{{ route("admin.sms-settings.index") }}" class="nav-link {{ request()->is("admin/sms-settings") || request()->is("admin/sms-settings/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.smsSetting.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('sms_template_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.sms-templates.index") }}" class="nav-link {{ request()->is("admin/sms-templates") || request()->is("admin/sms-templates/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.smsTemplate.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('email_template_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.email-templates.index") }}" class="nav-link {{ request()->is("admin/email-templates") || request()->is("admin/email-templates/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.emailTemplate.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>

</ul>





            </div>
<div class="col-8">bbbbbbbbbb</div>

        </div>

    </div>
</div>



@endsection

@section('scripts')
@parent
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>


@stop
