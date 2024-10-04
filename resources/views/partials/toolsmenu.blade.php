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




    @can('tools_degree_type_access')
        <li class="nav-item">
            <a href="{{ route('admin.tools-degree-types.index') }}"
                class="nav-link {{ request()->is('admin/tools-degree-types') || request()->is('admin/tools-degree-types/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-graduation-cap">

                </i>
                <p>
                    {{ trans('cruds.toolsDegreeType.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('year_access')
        <li class="nav-item">
            <a href="{{ route('admin.year.index') }}"
                class="nav-link {{ request()->is('admin/year') || request()->is('admin/year/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-calendar-week">

                </i>
                <p>
                    {{ trans('cruds.year.title') }}
                </p>
            </a>
        </li>
    @endcan

    @can('year_access')
        <li class="nav-item">
            <a href="{{ route('admin.fee-cycle.index') }}"
                class="nav-link {{ request()->is('admin/fee-cycle') || request()->is('admin/fee-cycle/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-calendar-alt"></i>
                <p>
                    {{ trans('Fee Cycle') }}
                </p>
            </a>
        </li>
    @endcan

    @can('year_access')
        <li class="nav-item">
            <a href="{{ route('admin.fee-components.index') }}"
                class="nav-link {{ request()->is('admin/fee-components') || request()->is('admin/fee-components/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-dollar-sign"></i>
                <p>
                    {{ trans('Fee Components') }}
                </p>
            </a>
        </li>
    @endcan

    @can('batch_access')
        <li class="nav-item">
            <a href="{{ route('admin.batches.index') }}"
                class="nav-link {{ request()->is('admin/batches') || request()->is('admin/batches/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-calendar-alt">

                </i>
                <p>
                    {{ trans('cruds.batch.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('academic_year_access')
        <li class="nav-item">
            <a href="{{ route('admin.academic-years.index') }}"
                class="nav-link {{ request()->is('admin/academic-years') || request()->is('admin/academic-years/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-landmark">

                </i>
                <p>
                    {{ trans('cruds.academicYear.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('shift_access')
        <li class="nav-item">
            <a href="{{ route('admin.Shift.index') }}"
                class="nav-link {{ request()->is('admin/Shift') || request()->is('admin/Shift/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">
                </i>
                <p>
                    {{ 'Shift' }}
                </p>
            </a>
        </li>
    @endcan
    @can('tools_department_access')
        <li class="nav-item">
            <a href="{{ route('admin.tools-departments.index') }}"
                class="nav-link {{ request()->is('admin/tools-departments') || request()->is('admin/tools-departments/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-building">

                </i>
                <p>
                    {{ trans('cruds.toolsDepartment.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('tools_course_access')
        <li class="nav-item">
            <a href="{{ route('admin.tools-courses.index') }}"
                class="nav-link {{ request()->is('admin/tools-courses') || request()->is('admin/tools-courses/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fab fa-accusoft">

                </i>
                <p>
                    {{ trans('cruds.toolsCourse.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('semester_access')
        <li class="nav-item">
            <a href="{{ route('admin.semesters.index') }}"
                class="nav-link {{ request()->is('admin/semesters') || request()->is('admin/semesters/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-file-alt">

                </i>
                <p>
                    {{ trans('cruds.semester.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('section_access')
        <li class="nav-item">
            <a href="{{ route('admin.sections.index') }}"
                class="nav-link {{ request()->is('admin/sections') || request()->is('admin/sections/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-puzzle-piece">

                </i>
                <p>
                    {{ trans('cruds.section.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('course_enroll_master_access')
        <li class="nav-item">
            <a href="{{ route('admin.course-enroll-masters.index') }}"
                class="nav-link {{ request()->is('admin/course-enroll-masters') || request()->is('admin/course-enroll-masters/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cog">

                </i>
                <p>
                    {{ trans('cruds.courseEnrollMaster.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('lab_title_access')
        <li class="nav-item">
            <a href="{{ route('admin.lab_title.index') }}"
                class="nav-link {{ request()->is('admin/lab_title') || request()->is('admin/lab_title/*') ? 'active' : '' }}">

                <i class="fa-fw nav-icon fas fa-flask"></i>
                </i>
                <p>
                    {{ trans('cruds.labExamTitle.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('toolssyllabus_year_access')
        <li class="nav-item">
            <a href="{{ route('admin.toolssyllabus-years.index') }}"
                class="nav-link {{ request()->is('admin/toolssyllabus-years') || request()->is('admin/toolssyllabus-years/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-book">

                </i>
                <p>
                    Regulations
                </p>
            </a>
        </li>
    @endcan
    {{-- @can('subject_category_access')
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
    @endcan --}}
    @can('class_room_access')
        <li class="nav-item">
            <a href="{{ route('admin.class-rooms.index') }}"
                class="nav-link {{ request()->is('admin/class-rooms') || request()->is('admin/class-rooms/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-user-check">
                </i>
                <p>
                    {{ trans('cruds.classRoom.title') }}
                </p>
            </a>
        </li>
    @endcan
    {{-- @can('class_batch_access') --}}
    <li class="nav-item">
        <a href="{{ route('admin.class-batch.index') }}"
            class="nav-link {{ request()->is('admin/class-batch') || request()->is('admin/class-batch/*') ? 'active' : '' }}">
            <i class="fa-fw nav-icon fas fa-users">
            </i>
            <p>
                Class Batch
            </p>
        </a>
    </li>
    {{-- @endcan --}}
    @can('grade_master_access')
        <li class="nav-item">
            <a href="{{ route('admin.grade-master.index') }}"
                class="nav-link {{ request()->is('admin/grade-master*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-table"></i>
                <p>Grade Master</p>
            </a>
        </li>
    @endcan
    @can('grade_master_access')
        <li class="nav-item">
            <a href="{{ route('admin.examfee-master.index') }}"
                class="nav-link {{ request()->is('admin/examfee-master*') ? 'active' : '' }}">

                <i class="fa-fw nav-icon fas fa-wallet"></i>
                <p>Exam Fee Master</p>
            </a>
        </li>
    @endcan
    @can('grade_master_access')
        <li class="nav-item">
            <a href="{{ route('admin.credit-limit-master.index') }}"
                class="nav-link {{ request()->is('admin/credit-limit-master*') ? 'active' : '' }}">

                <i class="fa-fw nav-icon fas fa-th-list"></i>
                <p>Credit Limit Master</p>
            </a>
        </li>
    @endcan
    @can('result_master_access')
        <li class="nav-item">
            <a href="{{ route('admin.result-master.index') }}"
                class="nav-link {{ request()->is('admin/result-master') || request()->is('admin/result-master/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-book">
                </i>
                <p>
                    Result Master
                </p>
            </a>
        </li>
    @endcan
    @can('result_master_access')
        <li class="nav-item">
            <a href="{{ route('admin.feedback-type.index') }}"
                class="nav-link {{ request()->is('admin/feedback-type') || request()->is('admin/feedback-type/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-book">
                </i>
                <p>
                    Feedback Type
                </p>
            </a>
        </li>
    @endcan
    @can('result_master_access')
        <li class="nav-item">
            <a href="{{ route('admin.internal-weightage.index') }}"
                class="nav-link {{ request()->is('admin/internal-weightage/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-file-alt"></i>
                <p>
                    Internal Weightage
                </p>
            </a>
        </li>
    @endcan
    @can('result_master_access')
        <li class="nav-item">
            <a href="{{ route('admin.admission-mode.index') }}"
                class="nav-link {{ request()->is('admin/admission-mode') || request()->is('admin/admission-mode/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-file-alt"></i>
                <p>
                    Admission Mode
                </p>
            </a>
        </li>
    @endcan
    @can('foundation_access')
        <li class="nav-item">
            <a href="{{ route('admin.scholarships.index') }}"
                class="nav-link {{ request()->is('admin/scholarships') || request()->is('admin/scholarships/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-hand-holding-usd">

                </i>
                <p>
                    Scholarship
                </p>
            </a>
        </li>
    @endcan
    @can('foundation_access')
        <li class="nav-item">
            <a href="{{ route('admin.paymentMode.index') }}"
                class="nav-link {{ request()->is('admin/paymentMode') || request()->is('admin/paymentMode/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-money-check-alt"></i>
                <p>
                    Payment Mode
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
    @can('education_board_access')
        <li class="nav-item">
            <a href="{{ route('admin.education-boards.index') }}"
                class="nav-link {{ request()->is('admin/education-boards') || request()->is('admin/education-boards/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-school">

                </i>
                <p>
                    {{ trans('cruds.educationBoard.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('education_type_access')
        <li class="nav-item">
            <a href="{{ route('admin.education-types.index') }}"
                class="nav-link {{ request()->is('admin/education-types') || request()->is('admin/education-types/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-tasks">

                </i>
                <p>
                    {{ trans('cruds.educationType.title') }}
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
    {{-- @can('teaching_type_access')
        <li class="nav-item">
            <a href="{{ route('admin.teaching-types.index') }}"
                class="nav-link {{ request()->is('admin/teaching-types') || request()->is('admin/teaching-types/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.teachingType.title') }}
                </p>
            </a>
        </li>
    @endcan --}}
    @can('examstaff_access')
        <li class="nav-item">
            <a href="{{ route('admin.examstaffs.index') }}"
                class="nav-link {{ request()->is('admin/examstaffs') || request()->is('admin/examstaffs/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-stream">

                </i>
                <p>
                    Exam Type
                </p>
            </a>
        </li>
    @endcan
    {{-- @can('feedback_questions_access')
        <li class="nav-item">
            <a href="{{ route('admin.feedback_questions.index') }}"
                class="nav-link {{ request()->is('admin/FeedBack-Questions') || request()->is('admin/FeedBack-Questions/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">
                </i>
                <p>
                    Feed Back Questions
                </p>
            </a>
        </li>
    @endcan --}}
    @can('events_access')
        <li class="nav-item">
            <a href="{{ route('admin.events.index') }}"
                class="nav-link {{ request()->is('admin/events') || request()->is('admin/events/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-magic">

                </i>
                <p>
                    Events
                </p>
            </a>
        </li>
    @endcan
    @can('leave_type_access')
        <li class="nav-item">
            <a href="{{ route('admin.leave-types.index') }}"
                class="nav-link {{ request()->is('admin/leave-types') || request()->is('admin/leave-types/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-monument">

                </i>
                <p>
                    {{ trans('cruds.leaveType.title') }}
                </p>
            </a>
        </li>
    @endcan
    {{-- @can('leave_status_access')
        <li class="nav-item">
            <a href="{{ route('admin.leave-statuses.index') }}"
                class="nav-link {{ request()->is('admin/leave-statuses') || request()->is('admin/leave-statuses/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-cogs">

                </i>
                <p>
                    {{ trans('cruds.leaveStatus.title') }}
                </p>
            </a>
        </li>
    @endcan --}}
    @can('college_block_access')
        <li class="nav-item">
            <a href="{{ route('admin.college-blocks.index') }}"
                class="nav-link {{ request()->is('admin/college-blocks') || request()->is('admin/college-blocks/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-building">

                </i>
                <p>
                    {{ trans('cruds.collegeBlock.title') }}
                </p>
            </a>
        </li>
    @endcan
    @can('rooms_access')
        <li class="nav-item">
            <a href="{{ route('admin.rooms.index') }}"
                class="nav-link {{ request()->is('admin/rooms') || request()->is('admin/rooms/*') ? 'active' : '' }}">
                <i class="fa-fw nav-icon fas fa-hotel"></i>
                <p>
                    Rooms
                </p>
            </a>
        </li>
    @endcan
    {{-- @can('tool_lab_access')
    <li class="nav-item">
        <a href="{{ route('admin.tool-lab.index') }}"
            class="nav-link {{ request()->is('admin/tool-lab') || request()->is('admin/tool-lab/*') ? 'active' : '' }}">
            <i class="fa-fw nav-icon fas fa-cogs">

            </i>
            <p>
                Lab
            </p>
        </a>
    </li> --}}

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
