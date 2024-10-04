<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.home') }}">
                                    {{ __('Dashboard') }}
                                </a>
                            </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if(Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('frontend.profile.index') }}">{{ __('My profile') }}</a>

                                    @can('user_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.userManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('permission_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.permissions.index') }}">
                                            {{ trans('cruds.permission.title') }}
                                        </a>
                                    @endcan
                                    @can('role_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.roles.index') }}">
                                            {{ trans('cruds.role.title') }}
                                        </a>
                                    @endcan
                                    @can('user_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.users.index') }}">
                                            {{ trans('cruds.user.title') }}
                                        </a>
                                    @endcan
                                    @can('tool_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.tool.title') }}
                                        </a>
                                    @endcan
                                    @can('tools_degree_type_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tools-degree-types.index') }}">
                                            {{ trans('cruds.toolsDegreeType.title') }}
                                        </a>
                                    @endcan
                                    @can('batch_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.batches.index') }}">
                                            {{ trans('cruds.batch.title') }}
                                        </a>
                                    @endcan
                                    @can('academic_year_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.academic-years.index') }}">
                                            {{ trans('cruds.academicYear.title') }}
                                        </a>
                                    @endcan
                                    @can('tools_course_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tools-courses.index') }}">
                                            {{ trans('cruds.toolsCourse.title') }}
                                        </a>
                                    @endcan
                                    @can('tools_department_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tools-departments.index') }}">
                                            {{ trans('cruds.toolsDepartment.title') }}
                                        </a>
                                    @endcan
                                    @can('semester_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.semesters.index') }}">
                                            {{ trans('cruds.semester.title') }}
                                        </a>
                                    @endcan
                                    @can('section_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.sections.index') }}">
                                            {{ trans('cruds.section.title') }}
                                        </a>
                                    @endcan
                                    @can('toolssyllabus_year_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.toolssyllabus-years.index') }}">
                                            {{ trans('cruds.toolssyllabusYear.title') }}
                                        </a>
                                    @endcan
                                    @can('course_enroll_master_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.course-enroll-masters.index') }}">
                                            {{ trans('cruds.courseEnrollMaster.title') }}
                                        </a>
                                    @endcan
                                    @can('nationality_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.nationalities.index') }}">
                                            {{ trans('cruds.nationality.title') }}
                                        </a>
                                    @endcan
                                    @can('religion_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.religions.index') }}">
                                            {{ trans('cruds.religion.title') }}
                                        </a>
                                    @endcan
                                    @can('blood_group_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.blood-groups.index') }}">
                                            {{ trans('cruds.bloodGroup.title') }}
                                        </a>
                                    @endcan
                                    @can('community_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.communities.index') }}">
                                            {{ trans('cruds.community.title') }}
                                        </a>
                                    @endcan
                                    @can('mother_tongue_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.mother-tongues.index') }}">
                                            {{ trans('cruds.motherTongue.title') }}
                                        </a>
                                    @endcan
                                    @can('education_board_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.education-boards.index') }}">
                                            {{ trans('cruds.educationBoard.title') }}
                                        </a>
                                    @endcan
                                    @can('education_type_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.education-types.index') }}">
                                            {{ trans('cruds.educationType.title') }}
                                        </a>
                                    @endcan
                                    @can('scholarship_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.scholarships.index') }}">
                                            {{ trans('cruds.scholarship.title') }}
                                        </a>
                                    @endcan
                                    @can('subject_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.subjects.index') }}">
                                            {{ trans('cruds.subject.title') }}
                                        </a>
                                    @endcan
                                    @can('mediumof_studied_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.mediumof-studieds.index') }}">
                                            {{ trans('cruds.mediumofStudied.title') }}
                                        </a>
                                    @endcan
                                    @can('teaching_type_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.teaching-types.index') }}">
                                            {{ trans('cruds.teachingType.title') }}
                                        </a>
                                    @endcan
                                    @can('examstaff_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.examstaffs.index') }}">
                                            {{ trans('cruds.examstaff.title') }}
                                        </a>
                                    @endcan
                                    @can('college_block_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.college-blocks.index') }}">
                                            {{ trans('cruds.collegeBlock.title') }}
                                        </a>
                                    @endcan
                                    @can('scholorship_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.scholorships.index') }}">
                                            {{ trans('cruds.scholorship.title') }}
                                        </a>
                                    @endcan
                                    @can('leave_status_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.leave-statuses.index') }}">
                                            {{ trans('cruds.leaveStatus.title') }}
                                        </a>
                                    @endcan
                                    @can('class_room_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.class-rooms.index') }}">
                                            {{ trans('cruds.classRoom.title') }}
                                        </a>
                                    @endcan
                                    @can('email_setting_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.email-settings.index') }}">
                                            {{ trans('cruds.emailSetting.title') }}
                                        </a>
                                    @endcan
                                    @can('sms_setting_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.sms-settings.index') }}">
                                            {{ trans('cruds.smsSetting.title') }}
                                        </a>
                                    @endcan
                                    @can('sms_template_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.sms-templates.index') }}">
                                            {{ trans('cruds.smsTemplate.title') }}
                                        </a>
                                    @endcan
                                    @can('email_template_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.email-templates.index') }}">
                                            {{ trans('cruds.emailTemplate.title') }}
                                        </a>
                                    @endcan
                                    @can('academic_detail_access')
                                        <a class="dropdown-item" href="{{ route('frontend.academic-details.index') }}">
                                            {{ trans('cruds.academicDetail.title') }}
                                        </a>
                                    @endcan
                                    @can('personal_detail_access')
                                        <a class="dropdown-item" href="{{ route('frontend.personal-details.index') }}">
                                            {{ trans('cruds.personalDetail.title') }}
                                        </a>
                                    @endcan
                                    @can('educational_detail_access')
                                        <a class="dropdown-item" href="{{ route('frontend.educational-details.index') }}">
                                            {{ trans('cruds.educationalDetail.title') }}
                                        </a>
                                    @endcan
                                    @can('parent_detail_access')
                                        <a class="dropdown-item" href="{{ route('frontend.parent-details.index') }}">
                                            {{ trans('cruds.parentDetail.title') }}
                                        </a>
                                    @endcan
                                    @can('student_access')
                                        <a class="dropdown-item" href="{{ route('frontend.students.index') }}">
                                            {{ trans('cruds.student.title') }}
                                        </a>
                                    @endcan
                                    @can('address_access')
                                        <a class="dropdown-item" href="{{ route('frontend.addresses.index') }}">
                                            {{ trans('cruds.address.title') }}
                                        </a>
                                    @endcan
                                    @can('bank_account_detail_access')
                                        <a class="dropdown-item" href="{{ route('frontend.bank-account-details.index') }}">
                                            {{ trans('cruds.bankAccountDetail.title') }}
                                        </a>
                                    @endcan
                                    @can('experience_detail_access')
                                        <a class="dropdown-item" href="{{ route('frontend.experience-details.index') }}">
                                            {{ trans('cruds.experienceDetail.title') }}
                                        </a>
                                    @endcan
                                    @can('teaching_staff_access')
                                        <a class="dropdown-item" href="{{ route('frontend.teaching-staffs.index') }}">
                                            {{ trans('cruds.teachingStaff.title') }}
                                        </a>
                                    @endcan
                                    @can('non_teaching_staff_access')
                                        <a class="dropdown-item" href="{{ route('frontend.non-teaching-staffs.index') }}">
                                            {{ trans('cruds.nonTeachingStaff.title') }}
                                        </a>
                                    @endcan
                                    @can('add_conference_access')
                                        <a class="dropdown-item" href="{{ route('frontend.add-conferences.index') }}">
                                            {{ trans('cruds.addConference.title') }}
                                        </a>
                                    @endcan
                                    @can('entrance_exam_access')
                                        <a class="dropdown-item" href="{{ route('frontend.entrance-exams.index') }}">
                                            {{ trans('cruds.entranceExam.title') }}
                                        </a>
                                    @endcan
                                    @can('guest_lecture_access')
                                        <a class="dropdown-item" href="{{ route('frontend.guest-lectures.index') }}">
                                            {{ trans('cruds.guestLecture.title') }}
                                        </a>
                                    @endcan
                                    @can('industrial_training_access')
                                        <a class="dropdown-item" href="{{ route('frontend.industrial-trainings.index') }}">
                                            {{ trans('cruds.industrialTraining.title') }}
                                        </a>
                                    @endcan
                                    @can('intern_access')
                                        <a class="dropdown-item" href="{{ route('frontend.interns.index') }}">
                                            {{ trans('cruds.intern.title') }}
                                        </a>
                                    @endcan
                                    @can('industrial_experience_access')
                                        <a class="dropdown-item" href="{{ route('frontend.industrial-experiences.index') }}">
                                            {{ trans('cruds.industrialExperience.title') }}
                                        </a>
                                    @endcan
                                    @can('iv_access')
                                        <a class="dropdown-item" href="{{ route('frontend.ivs.index') }}">
                                            {{ trans('cruds.iv.title') }}
                                        </a>
                                    @endcan
                                    @can('online_course_access')
                                        <a class="dropdown-item" href="{{ route('frontend.online-courses.index') }}">
                                            {{ trans('cruds.onlineCourse.title') }}
                                        </a>
                                    @endcan
                                    @can('document_access')
                                        <a class="dropdown-item" href="{{ route('frontend.documents.index') }}">
                                            {{ trans('cruds.document.title') }}
                                        </a>
                                    @endcan
                                    @can('seminar_access')
                                        <a class="dropdown-item" href="{{ route('frontend.seminars.index') }}">
                                            {{ trans('cruds.seminar.title') }}
                                        </a>
                                    @endcan
                                    @can('sabotical_access')
                                        <a class="dropdown-item" href="{{ route('frontend.saboticals.index') }}">
                                            {{ trans('cruds.sabotical.title') }}
                                        </a>
                                    @endcan
                                    @can('sponser_access')
                                        <a class="dropdown-item" href="{{ route('frontend.sponsers.index') }}">
                                            {{ trans('cruds.sponser.title') }}
                                        </a>
                                    @endcan
                                    @can('sttp_access')
                                        <a class="dropdown-item" href="{{ route('frontend.sttps.index') }}">
                                            {{ trans('cruds.sttp.title') }}
                                        </a>
                                    @endcan
                                    @can('workshop_access')
                                        <a class="dropdown-item" href="{{ route('frontend.workshops.index') }}">
                                            {{ trans('cruds.workshop.title') }}
                                        </a>
                                    @endcan
                                    @can('patent_access')
                                        <a class="dropdown-item" href="{{ route('frontend.patents.index') }}">
                                            {{ trans('cruds.patent.title') }}
                                        </a>
                                    @endcan
                                    @can('award_access')
                                        <a class="dropdown-item" href="{{ route('frontend.awards.index') }}">
                                            {{ trans('cruds.award.title') }}
                                        </a>
                                    @endcan
                                    @can('user_alert_access')
                                        <a class="dropdown-item" href="{{ route('frontend.user-alerts.index') }}">
                                            {{ trans('cruds.userAlert.title') }}
                                        </a>
                                    @endcan
                                    @can('course_access')
                                        <a class="dropdown-item" href="{{ route('frontend.courses.index') }}">
                                            {{ trans('cruds.course.title') }}
                                        </a>
                                    @endcan
                                    @can('lesson_access')
                                        <a class="dropdown-item" href="{{ route('frontend.lessons.index') }}">
                                            {{ trans('cruds.lesson.title') }}
                                        </a>
                                    @endcan
                                    @can('test_access')
                                        <a class="dropdown-item" href="{{ route('frontend.tests.index') }}">
                                            {{ trans('cruds.test.title') }}
                                        </a>
                                    @endcan
                                    @can('question_access')
                                        <a class="dropdown-item" href="{{ route('frontend.questions.index') }}">
                                            {{ trans('cruds.question.title') }}
                                        </a>
                                    @endcan
                                    @can('question_option_access')
                                        <a class="dropdown-item" href="{{ route('frontend.question-options.index') }}">
                                            {{ trans('cruds.questionOption.title') }}
                                        </a>
                                    @endcan
                                    @can('test_result_access')
                                        <a class="dropdown-item" href="{{ route('frontend.test-results.index') }}">
                                            {{ trans('cruds.testResult.title') }}
                                        </a>
                                    @endcan
                                    @can('test_answer_access')
                                        <a class="dropdown-item" href="{{ route('frontend.test-answers.index') }}">
                                            {{ trans('cruds.testAnswer.title') }}
                                        </a>
                                    @endcan
                                    @can('fundingdetali_access')
                                        <a class="dropdown-item" href="{{ route('frontend.fundingdetalis.index') }}">
                                            {{ trans('cruds.fundingdetali.title') }}
                                        </a>
                                    @endcan
                                    @can('asset_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.assetManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('asset_category_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.asset-categories.index') }}">
                                            {{ trans('cruds.assetCategory.title') }}
                                        </a>
                                    @endcan
                                    @can('asset_location_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.asset-locations.index') }}">
                                            {{ trans('cruds.assetLocation.title') }}
                                        </a>
                                    @endcan
                                    @can('asset_status_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.asset-statuses.index') }}">
                                            {{ trans('cruds.assetStatus.title') }}
                                        </a>
                                    @endcan
                                    @can('asset_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.assets.index') }}">
                                            {{ trans('cruds.asset.title') }}
                                        </a>
                                    @endcan
                                    @can('assets_history_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.assets-histories.index') }}">
                                            {{ trans('cruds.assetsHistory.title') }}
                                        </a>
                                    @endcan
                                    @can('task_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.taskManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('task_status_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.task-statuses.index') }}">
                                            {{ trans('cruds.taskStatus.title') }}
                                        </a>
                                    @endcan
                                    @can('task_tag_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.task-tags.index') }}">
                                            {{ trans('cruds.taskTag.title') }}
                                        </a>
                                    @endcan
                                    @can('task_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tasks.index') }}">
                                            {{ trans('cruds.task.title') }}
                                        </a>
                                    @endcan
                                    @can('faq_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.faqManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('faq_category_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.faq-categories.index') }}">
                                            {{ trans('cruds.faqCategory.title') }}
                                        </a>
                                    @endcan
                                    @can('faq_question_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.faq-questions.index') }}">
                                            {{ trans('cruds.faqQuestion.title') }}
                                        </a>
                                    @endcan
                                    @can('content_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.contentManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('content_category_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.content-categories.index') }}">
                                            {{ trans('cruds.contentCategory.title') }}
                                        </a>
                                    @endcan
                                    @can('content_tag_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.content-tags.index') }}">
                                            {{ trans('cruds.contentTag.title') }}
                                        </a>
                                    @endcan
                                    @can('content_page_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.content-pages.index') }}">
                                            {{ trans('cruds.contentPage.title') }}
                                        </a>
                                    @endcan
                                    @can('expense_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.expenseManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('expense_category_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.expense-categories.index') }}">
                                            {{ trans('cruds.expenseCategory.title') }}
                                        </a>
                                    @endcan
                                    @can('income_category_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.income-categories.index') }}">
                                            {{ trans('cruds.incomeCategory.title') }}
                                        </a>
                                    @endcan
                                    @can('expense_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.expenses.index') }}">
                                            {{ trans('cruds.expense.title') }}
                                        </a>
                                    @endcan
                                    @can('income_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.incomes.index') }}">
                                            {{ trans('cruds.income.title') }}
                                        </a>
                                    @endcan
                                    @can('hrm_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.hrm.title') }}
                                        </a>
                                    @endcan
                                    @can('leave_type_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.leave-types.index') }}">
                                            {{ trans('cruds.leaveType.title') }}
                                        </a>
                                    @endcan
                                    @can('leave_staff_allocation_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.leave-staff-allocations.index') }}">
                                            {{ trans('cruds.leaveStaffAllocation.title') }}
                                        </a>
                                    @endcan
                                    @can('od_master_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.od-masters.index') }}">
                                            {{ trans('cruds.odMaster.title') }}
                                        </a>
                                    @endcan
                                    @can('setting_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.settings.index') }}">
                                            {{ trans('cruds.setting.title') }}
                                        </a>
                                    @endcan
                                    @can('take_attentance_student_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.take-attentance-students.index') }}">
                                            {{ trans('cruds.takeAttentanceStudent.title') }}
                                        </a>
                                    @endcan
                                    @can('od_request_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.od-requests.index') }}">
                                            {{ trans('cruds.odRequest.title') }}
                                        </a>
                                    @endcan
                                    @can('internship_request_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.internship-requests.index') }}">
                                            {{ trans('cruds.internshipRequest.title') }}
                                        </a>
                                    @endcan
                                    @can('hrm_request_permission_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.hrm-request-permissions.index') }}">
                                            {{ trans('cruds.hrmRequestPermission.title') }}
                                        </a>
                                    @endcan
                                    @can('staff_transfer_info_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.staff-transfer-infos.index') }}">
                                            {{ trans('cruds.staffTransferInfo.title') }}
                                        </a>
                                    @endcan
                                    @can('hrm_request_leaf_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.hrm-request-leaves.index') }}">
                                            {{ trans('cruds.hrmRequestLeaf.title') }}
                                        </a>
                                    @endcan
                                    @can('college_calender_access')
                                        <a class="dropdown-item" href="{{ route('frontend.college-calenders.index') }}">
                                            {{ trans('cruds.collegeCalender.title') }}
                                        </a>
                                    @endcan
                                    @can('payment_gateway_access')
                                        <a class="dropdown-item" href="{{ route('frontend.payment-gateways.index') }}">
                                            {{ trans('cruds.paymentGateway.title') }}
                                        </a>
                                    @endcan

                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('message'))
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                </div>
            @endif
            @if($errors->count() > 0)
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul class="list-unstyled mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')

</html>
