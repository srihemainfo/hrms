<?php

use App\Http\Controllers\Admin\FeeDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use App\Http\Controllers\Admin\GoogleDriveController;

// Route::get('/auth', [GoogleDriveController::class, 'authenticate']);
// Route::get('/auth/callback', [GoogleDriveController::class, 'handleCallback']);
// Route::view('/', 'welcome');
Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/feedback/{token}', 'Admin\FeedbackController@feedbackForm');
Route::post('/feedback/survey', 'Admin\FeedbackController@feedbackStore')->name('feedback.store');

Route::get('/test', function () {
    return view('test');
});

Route::get('/student-login-from-api', function (Request $request) {
    return view('auth.studentLoginFromApi', compact('request'));
})->name('student-login-from-api');

Route::get('/staff-login-from-api', function (Request $request) {
    return view('auth.staffLoginFromApi', compact('request'));
})->name('staff-login-from-api');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Auth'], function () {
    Route::post('logout-rit', 'LogoutController@logoutRIT')->name('logout-rit');
    Route::get('student-logout', 'LogoutController@logoutRIT')->name('student-logout');
});
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin', 'active_user']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    // Php Artisan Cmds
    Route::get('schedule-clear-cache', 'ArtisanCmdController@ScheduleClearCache');

    Route::get('view-cache', 'ArtisanCmdController@ViewCache');
    Route::get('view-clear', 'ArtisanCmdController@ViewClear');

    Route::get('route-cache', 'ArtisanCmdController@RouteCache');
    Route::get('route-clear', 'ArtisanCmdController@RouteClear');

    Route::get('cache-clear', 'ArtisanCmdController@CacheClear');
    Route::get('cache-forget/{key}', 'ArtisanCmdController@CacheForget');

    Route::get('config-cache', 'ArtisanCmdController@ConfigCache');
    Route::get('config-clear', 'ArtisanCmdController@ConfigClear');
    Route::get('storage-link', 'ArtisanCmdController@StorageLink');

    // Staff Biometric modificationRun
    Route::get('biometric-modification', 'StaffBiometricController@modificationRun');

    Route::get('circle', 'ArtisanCmdController@circle');

    // Api Staff Biometric
    Route::get('api-biometric', 'ArtisanCmdController@ApiBiometric');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/parse-csv-import', 'UsersController@parseCsvImport')->name('users.parseCsvImport');
    Route::post('users/process-csv-import', 'UsersController@processCsvImport')->name('users.processCsvImport');
    Route::get('users/block', 'UsersController@block')->name('users.block');
    Route::get('users/fetch_users', 'UsersController@fetchUsers')->name('users.fetch_users');
    Route::get('users/fetch_roles', 'UsersController@fetchRoles')->name('users.fetch_roles');
    Route::get('users/unblock', 'UsersController@unblock')->name('users.unblock');
    Route::get('users/block_list', 'UsersController@block_list')->name('users.block_list');
    Route::post('users/block_user', 'UsersController@block_user')->name('users.block_user');
    Route::match(['get', 'post'], 'users/unblock_user', 'UsersController@unblock_user')->name('users.unblock_user');
    Route::post('users/fetch_role', 'UsersController@fetch_role')->name('users.fetch_role');
    Route::resource('users', 'UsersController');

    // Master Tool
    Route::get('master-tools', function () {
        return view('layouts.admin');
    })->name('master-tools');

    Route::get('mail', 'sampleMail@index')->name('mail.index');
    Route::post('mail/send', 'sampleMail@send')->name('mail.send');
    // Route::get('mail', 'sampleMail@index')->name('mail.index');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Tools Degree Type
    Route::delete('tools-degree-types/destroy', 'ToolsDegreeTypeController@massDestroy')->name('tools-degree-types.massDestroy');
    Route::resource('tools-degree-types', 'ToolsDegreeTypeController');

    Route::get('tools-degree-types', 'ToolsDegreeTypeController@index')->name('tools-degree-types.index');
    Route::post('tools-degree-types/view', 'ToolsDegreeTypeController@view')->name('tools-degree-types.view');
    Route::post('tools-degree-types/edit', 'ToolsDegreeTypeController@edit')->name('tools-degree-types.edit');
    Route::post('tools-degree-types/store', 'ToolsDegreeTypeController@store')->name('tools-degree-types.store');
    Route::post('tools-degree-types/delete', 'ToolsDegreeTypeController@destroy')->name('tools-degree-types.delete');
    Route::delete('tools-degree-types/destroy', 'ToolsDegreeTypeController@massDestroy')->name('tools-degree-types.massDestroy');

    // Year
    Route::get('year', 'YearController@index')->name('year.index');
    Route::post('year/view', 'YearController@view')->name('year.view');
    Route::post('year/edit', 'YearController@edit')->name('year.edit');
    Route::post('year/store', 'YearController@store')->name('year.store');
    Route::post('year/delete', 'YearController@destroy')->name('year.delete');
    Route::delete('year/destroy', 'YearController@massDestroy')->name('year.massDestroy');


    // FeeCyle
    Route::get('fee-cycle', 'feeCycleController@index')->name('fee-cycle.index');
    Route::post('fee-cycle/store', 'feeCycleController@store')->name('fee-cycle.store');


    // FeeComponents
    Route::get('fee-components', 'feeComponentsController@index')->name('fee-components.index');
    Route::post('fee-components/view', 'feeComponentsController@view')->name('fee-components.view');
    Route::post('fee-components/edit', 'feeComponentsController@edit')->name('fee-components.edit');
    Route::post('fee-components/store', 'feeComponentsController@store')->name('fee-components.store');

    Route::post('fee-components/delete', 'feeComponentsController@destroy')->name('fee-components.delete');
    Route::delete('fee-components/destroy', 'feeComponentsController@massDestroy')->name('fee-components.massDestroy');

    // Academic Year
    Route::get('academic-years', 'AcademicYearController@index')->name('academic-years.index');
    Route::post('academic-years/view', 'AcademicYearController@view')->name('academic-years.view');
    Route::post('academic-years/edit', 'AcademicYearController@edit')->name('academic-years.edit');
    Route::post('academic-years/store', 'AcademicYearController@store')->name('academic-years.store');
    Route::post('academic-years/delete', 'AcademicYearController@destroy')->name('academic-years.delete');
    Route::post('academic-years/check', 'AcademicYearController@check')->name('academic-years.check');
    Route::post('academic-years/change-status', 'AcademicYearController@changeStatus')->name('academic-years.change-status');
    Route::delete('academic-years/destroy', 'AcademicYearController@massDestroy')->name('academic-years.massDestroy');

    // Settings
    // Route::get('admin/master_settings','HomeController@master_settings')->name('admin.master_settings');

    // Batch
    Route::get('batches', 'BatchController@index')->name('batches.index');
    Route::post('batches/view', 'BatchController@view')->name('batches.view');
    Route::post('batches/edit', 'BatchController@edit')->name('batches.edit');
    Route::post('batches/store', 'BatchController@store')->name('batches.store');
    Route::post('batches/delete', 'BatchController@destroy')->name('batches.delete');
    Route::delete('batches/destroy', 'BatchController@massDestroy')->name('batches.massDestroy');

    // Tools Mainscreen
    // Route::delete('tools/destroy', 'ToolsController@massDestroy')->name('tools-courses.massDestroy');
    Route::resource('tools', 'ToolsController');

    // Tools Course
    Route::get('tools-courses', 'ToolsCourseController@index')->name('tools-courses.index');
    Route::post('tools-courses/view', 'ToolsCourseController@view')->name('tools-courses.view');
    Route::post('tools-courses/edit', 'ToolsCourseController@edit')->name('tools-courses.edit');
    Route::post('tools-courses/store', 'ToolsCourseController@store')->name('tools-courses.store');
    Route::post('tools-courses/delete', 'ToolsCourseController@destroy')->name('tools-courses.delete');
    Route::delete('tools-courses/destroy', 'ToolsCourseController@massDestroy')->name('tools-courses.massDestroy');

    // Tools Department
    // Route::delete('tools-departments/destroy', 'ToolsDepartmentController@massDestroy')->name('tools-departments.massDestroy');
    // Route::resource('tools-departments', 'ToolsDepartmentController');

    Route::get('tools-departments', 'ToolsDepartmentController@index')->name('tools-departments.index');
    Route::post('tools-departments/view', 'ToolsDepartmentController@view')->name('tools-departments.view');
    Route::post('tools-departments/edit', 'ToolsDepartmentController@edit')->name('tools-departments.edit');
    Route::post('tools-departments/store', 'ToolsDepartmentController@store')->name('tools-departments.store');
    Route::post('tools-departments/delete', 'ToolsDepartmentController@destroy')->name('tools-departments.delete');
    Route::delete('tools-departments/destroy', 'ToolsDepartmentController@massDestroy')->name('tools-departments.massDestroy');

    // Section
    Route::get('sections', 'SectionController@index')->name('sections.index');
    Route::post('sections/view', 'SectionController@view')->name('sections.view');
    Route::post('sections/edit', 'SectionController@edit')->name('sections.edit');
    Route::post('sections/store', 'SectionController@store')->name('sections.store');
    Route::post('sections/delete', 'SectionController@destroy')->name('sections.delete');
    Route::delete('sections/destroy', 'SectionController@massDestroy')->name('sections.massDestroy');

    // Semester
    Route::get('semesters', 'SemesterController@index')->name('semesters.index');
    Route::post('semesters/view', 'SemesterController@view')->name('semesters.view');
    Route::post('semesters/edit', 'SemesterController@edit')->name('semesters.edit');
    Route::post('semesters/store', 'SemesterController@store')->name('semesters.store');
    Route::post('semesters/delete', 'SemesterController@destroy')->name('semesters.delete');
    Route::post('semesters/change-status', 'SemesterController@changeStatus')->name('semesters.change-status');
    Route::delete('semesters/destroy', 'SemesterController@massDestroy')->name('semesters.massDestroy');

    // Course Enroll Master
    Route::post('course_enroll_masters/enroll_index', 'CourseEnrollMasterController@enroll_index')->name('course_enroll_masters.enroll_index');
    Route::get('course-enroll-masters', 'CourseEnrollMasterController@index')->name('course-enroll-masters.index');
    Route::post('course-enroll-masters/view', 'CourseEnrollMasterController@view')->name('course-enroll-masters.view');
    Route::post('course-enroll-masters/edit', 'CourseEnrollMasterController@edit')->name('course-enroll-masters.edit');
    Route::post('course-enroll-masters/store', 'CourseEnrollMasterController@store')->name('course-enroll-masters.store');
    Route::post('course-enroll-masters/delete', 'CourseEnrollMasterController@destroy')->name('course-enroll-masters.delete');
    Route::delete('course-enroll-masters/destroy', 'CourseEnrollMasterController@massDestroy')->name('course-enroll-masters.massDestroy');

    // Toolssyllabus Year
    Route::get('toolssyllabus-years', 'ToolssyllabusYearController@index')->name('toolssyllabus-years.index');
    Route::post('toolssyllabus-years/view', 'ToolssyllabusYearController@view')->name('toolssyllabus-years.view');
    Route::post('toolssyllabus-years/edit', 'ToolssyllabusYearController@edit')->name('toolssyllabus-years.edit');
    Route::post('toolssyllabus-years/store', 'ToolssyllabusYearController@store')->name('toolssyllabus-years.store');
    Route::post('toolssyllabus-years/delete', 'ToolssyllabusYearController@destroy')->name('toolssyllabus-years.delete');
    Route::delete('toolssyllabus-years/destroy', 'ToolssyllabusYearController@massDestroy')->name('toolssyllabus-years.massDestroy');

    // Menu geter
    Route::post('menu/geter', 'MenuController@geter')->name('menu.geter');

    // Subject Attendance
    Route::get('subject-attendance-report/index', 'AttendanceReportController@subjectAttendance')->name('subject-attendance-report.index');
    Route::get('subject-attendance-report/staff_index', 'AttendanceReportController@staffSubjectAttendance')->name('subject-attendance-report.staff_index');
    Route::get('subject-attendance-report/show/{user_name_id}/{class}/{subject}', 'AttendanceReportController@show')->name('subject-attendance-report.show');
    Route::post('subject-attendance-report/get_details', 'AttendanceReportController@getDetails')->name('subject-attendance-report.get_details');
    Route::post('subject-attendance-report/teacherSubject', 'AttendanceReportController@teacherSubject')->name('subject-attendance-report.teacherSubject');
    Route::post('subject-attendance-report/get_staff', 'AttendanceReportController@getStaff')->name('subject-attendance-report.get_staff');
    Route::post('subject-attendance-report/get_report', 'AttendanceReportController@getReport')->name('subject-attendance-report.get_report');
    Route::post('subject-attendance-report/get_subject_report', 'AttendanceReportController@getSubjectReport')->name('subject-attendance-report.get_subject_report');
    Route::post('subject-attendance-report/teacher', 'AttendanceReportController@teacher')->name('subject-attendance-report.teacher');
    Route::post('class-attendance-report/get_report', 'AttendanceReportController@getClassReport')->name('class-attendance-report.get_report');

    // Class Attendance
    Route::get('class-attendance-report/index', 'AttendanceReportController@classAttendance')->name('class-attendance-report.index');

    //My Subject Registration
    Route::get('subject-registration/student', 'SubjectRegistrationController@student')->name('subject-registration.student');
    Route::post('subject-registration/store', 'SubjectRegistrationController@store')->name('subjectRegistration.store');
    Route::post('subject-registration/update', 'SubjectRegistrationController@update')->name('subjectRegistration.update');
    Route::get('subject-registration/edit/{id}', 'SubjectRegistrationController@edit')->name('subjectRegistration.edit');
    Route::get('subject-registration/index', 'SubjectRegistrationController@index')->name('subjectRegistration.index');
    Route::get('subject-registration/show/{id}', 'SubjectRegistrationController@show')->name('subjectRegistration.show');
    Route::post('subject-registration/get_course_and_sem', 'SubjectRegistrationController@get_course_and_sem')->name('subject-registration.get_course_and_sem');
    Route::get('subject-registration/getData', 'SubjectRegistrationController@getData')->name('subjectRegistration.getData');
    Route::post('subject-registration/getDatas', 'SubjectRegistrationController@getDatas')->name('subjectRegistration.getDatas');
    Route::post('subject-registration/parseCsvRemovalSubjectReg', 'SubjectRegistrationController@parseCsvRemovalSubjectReg')->name('subjectRegistration.parseCsvRemovalSubjectReg');
    Route::post('subject-registration/parseCsvHonors', 'SubjectRegistrationController@parseCsvHonors')->name('subjectRegistration.parseCsvHonors');
    Route::post('subject-registrations/csvImportHonors', 'SubjectRegistrationController@processCsvHonors')->name('subject-registrations.csvImportHonors');
    Route::post('subject-registration/remove-process-csv-import-sub', 'SubjectRegistrationController@removeProcessCsvImportSub')->name('subject-registrations.removeProcessCsvImportSub');

    // Academic Details
    Route::delete('academic-details/destroy', 'AcademicDetailsController@massDestroy')->name('academic-details.massDestroy');
    Route::post('academic-details/parse-csv-import', 'AcademicDetailsController@parseCsvImport')->name('academic-details.parseCsvImport');
    Route::post('academic-details/process-csv-import', 'AcademicDetailsController@processCsvImport')->name('academic-details.processCsvImport');
    Route::resource('academic-details', 'AcademicDetailsController');
    Route::get('academic-details/stu_index/{user_name_id}/{name}', 'AcademicDetailsController@stu_index')->name('academic-details.stu_index');
    Route::get('academic-details/staff_index/{user_name_id}/{name}', 'AcademicDetailsController@staff_index')->name('academic-details.staff_index');
    Route::post('academic-details/stu_update', 'AcademicDetailsController@stu_update')->name('academic-details.stu_update');
    Route::post('academic-details/staff_update', 'AcademicDetailsController@staff_update')->name('academic-details.staff_update');

    // Personal Details
    Route::delete('personal-details/destroy', 'PersonalDetailsController@massDestroy')->name('personal-details.massDestroy');
    Route::post('personal-details/parse-csv-import', 'PersonalDetailsController@parseCsvImport')->name('personal-details.parseCsvImport');
    Route::post('personal-details/process-csv-import', 'PersonalDetailsController@processCsvImport')->name('personal-details.processCsvImport');
    Route::resource('personal-details', 'PersonalDetailsController');
    Route::get('personal-details/stu_index/{user_name_id}/{name}', 'PersonalDetailsController@stu_index')->name('personal-details.stu_index');
    Route::get('personal-details/staff_index/{user_name_id}/{name}', 'PersonalDetailsController@staff_index')->name('personal-details.staff_index');
    Route::post('personal-details/stu_update', 'PersonalDetailsController@stu_update')->name('personal-details.stu_update');
    Route::post('personal-details/staff_update', 'PersonalDetailsController@staff_update')->name('personal-details.staff_update');

    // Ph.D Details
    Route::delete('phd-details/{id}', 'PhdDetailController@destroy')->name('phd-details.destroy');
    Route::get('phd-details/staff_index/{user_name_id}/{name}', 'PhdDetailController@staff_index')->name('phd-details.staff_index');
    Route::post('phd-details/staff_update', 'PhdDetailController@staff_update')->name('phd-details.staff_update');
    Route::post('phd-details/staff_updater', 'PhdDetailController@staff_index')->name('phd-details.staff_updater');

    // Employment Details
    Route::get('employment-details/staff_index/{user_name_id}/{name}', 'EmploymentDetailsController@staff_index')->name('employment-details.staff_index');
    Route::post('employment-details/staff_update', 'EmploymentDetailsController@staff_update')->name('employment-details.staff_update');
    // Route::post('personal-details/index', 'PersonalDetailsController@index')->name('personal-details.index');

    // Educational Details
    Route::delete('educational-details/destroy', 'EducationalDetailsController@massDestroy')->name('educational-details.massDestroy');
    Route::post('educational-details/parse-csv-import', 'EducationalDetailsController@parseCsvImport')->name('educational-details.parseCsvImport');
    Route::post('educational-details/process-csv-import', 'EducationalDetailsController@processCsvImport')->name('educational-details.processCsvImport');
    Route::resource('educational-details', 'EducationalDetailsController');
    Route::get('educational-details/stu_index/{user_name_id}/{name}', 'EducationalDetailsController@stu_index')->name('educational-details.stu_index');
    Route::get('educational-details/staff_index/{user_name_id}/{name}', 'EducationalDetailsController@staff_index')->name('educational-details.staff_index');
    Route::post('educational-details/stu_update', 'EducationalDetailsController@stu_update')->name('educational-details.stu_update');
    Route::post('educational-details/staff_updater', 'EducationalDetailsController@staff_index')->name('educational-details.staff_updater');
    Route::post('educational-details/stu_updater', 'EducationalDetailsController@stu_index')->name('educational-details.stu_updater');
    Route::post('educational-details/staff_update', 'EducationalDetailsController@staff_update')->name('educational-details.staff_update');

    // Nationality
    Route::get('nationalities', 'NationalityController@index')->name('nationalities.index');
    Route::post('nationalities/view', 'NationalityController@view')->name('nationalities.view');
    Route::post('nationalities/edit', 'NationalityController@edit')->name('nationalities.edit');
    Route::post('nationalities/store', 'NationalityController@store')->name('nationalities.store');
    Route::post('nationalities/delete', 'NationalityController@destroy')->name('nationalities.delete');
    Route::post('nationalities/check', 'NationalityController@check')->name('nationalities.check');
    Route::post('nationalities/change-status', 'NationalityController@changeStatus')->name('nationalities.change-status');
    Route::delete('nationalities/destroy', 'NationalityController@massDestroy')->name('nationalities.massDestroy');

    //Fee Components

    //Payment Mode
    Route::get('paymentMode', 'PaymentModeController@index')->name('paymentMode.index');
    Route::post('paymentMode/view', 'PaymentModeController@view')->name('paymentMode.view');
    Route::post('paymentMode/edit', 'PaymentModeController@edit')->name('paymentMode.edit');
    Route::post('paymentMode/store', 'PaymentModeController@store')->name('paymentMode.store');
    Route::post('paymentMode/delete', 'PaymentModeController@destroy')->name('paymentMode.delete');
    Route::delete('paymentMode/destroy', 'PaymentModeController@massDestroy')->name('paymentMode.massDestroy');

    // Religion
    Route::get('religions', 'ReligionController@index')->name('religions.index');
    Route::post('religions/view', 'ReligionController@view')->name('religions.view');
    Route::post('religions/edit', 'ReligionController@edit')->name('religions.edit');
    Route::post('religions/store', 'ReligionController@store')->name('religions.store');
    Route::post('religions/delete', 'ReligionController@destroy')->name('religions.delete');
    Route::delete('religions/destroy', 'ReligionController@massDestroy')->name('religions.massDestroy');

    // Blood Group
    Route::get('blood-groups', 'BloodGroupController@index')->name('blood-groups.index');
    Route::post('blood-groups/view', 'BloodGroupController@view')->name('blood-groups.view');
    Route::post('blood-groups/edit', 'BloodGroupController@edit')->name('blood-groups.edit');
    Route::post('blood-groups/store', 'BloodGroupController@store')->name('blood-groups.store');
    Route::post('blood-groups/delete', 'BloodGroupController@destroy')->name('blood-groups.delete');
    Route::delete('blood-groups/destroy', 'BloodGroupController@massDestroy')->name('blood-groups.massDestroy');

    // Community
    Route::get('communities', 'CommunityController@index')->name('communities.index');
    Route::post('communities/view', 'CommunityController@view')->name('communities.view');
    Route::post('communities/edit', 'CommunityController@edit')->name('communities.edit');
    Route::post('communities/store', 'CommunityController@store')->name('communities.store');
    Route::post('communities/delete', 'CommunityController@destroy')->name('communities.delete');
    Route::delete('communities/destroy', 'CommunityController@massDestroy')->name('communities.massDestroy');

    // Mother Tongue
    Route::get('mother-tongues', 'MotherTongueController@index')->name('mother-tongues.index');
    Route::post('mother-tongues/view', 'MotherTongueController@view')->name('mother-tongues.view');
    Route::post('mother-tongues/edit', 'MotherTongueController@edit')->name('mother-tongues.edit');
    Route::post('mother-tongues/store', 'MotherTongueController@store')->name('mother-tongues.store');
    Route::post('mother-tongues/delete', 'MotherTongueController@destroy')->name('mother-tongues.delete');
    Route::delete('mother-tongues/destroy', 'MotherTongueController@massDestroy')->name('mother-tongues.massDestroy');

    // Education Board
    Route::get('education-boards', 'EducationBoardController@index')->name('education-boards.index');
    Route::post('education-boards/view', 'EducationBoardController@view')->name('education-boards.view');
    Route::post('education-boards/edit', 'EducationBoardController@edit')->name('education-boards.edit');
    Route::post('education-boards/store', 'EducationBoardController@store')->name('education-boards.store');
    Route::post('education-boards/delete', 'EducationBoardController@destroy')->name('education-boards.delete');
    Route::delete('education-boards/destroy', 'EducationBoardController@massDestroy')->name('education-boards.massDestroy');

    // Admission Mode
    Route::get('admission-mode', 'AdmissionModeController@index')->name('admission-mode.index');
    Route::post('admission-mode/view', 'AdmissionModeController@view')->name('admission-mode.view');
    Route::post('admission-mode/edit', 'AdmissionModeController@edit')->name('admission-mode.edit');
    Route::post('admission-mode/store', 'AdmissionModeController@store')->name('admission-mode.store');
    Route::post('admission-mode/delete', 'AdmissionModeController@destroy')->name('admission-mode.delete');
    Route::delete('admission-mode/destroy', 'AdmissionModeController@massDestroy')->name('admission-mode.massDestroy');

    // Subject Types
    Route::get('subject_types', 'SubjectTypeController@index')->name('subject_types.index');
    Route::post('subject_types/view', 'SubjectTypeController@view')->name('subject_types.view');
    Route::post('subject_types/edit', 'SubjectTypeController@edit')->name('subject_types.edit');
    Route::post('subject_types/store', 'SubjectTypeController@store')->name('subject_types.store');
    Route::post('subject_types/delete', 'SubjectTypeController@destroy')->name('subject_types.delete');
    Route::post('subject_types/check', 'SubjectTypeController@check')->name('subject_types.check');
    Route::delete('subject_types/destroy', 'SubjectTypeController@massDestroy')->name('subject_types.massDestroy');

    // Subject Category
    Route::get('subject_category', 'SubjectCategoryController@index')->name('subject_category.index');
    Route::post('subject_category/view', 'SubjectCategoryController@view')->name('subject_category.view');
    Route::post('subject_category/edit', 'SubjectCategoryController@edit')->name('subject_category.edit');
    Route::post('subject_category/store', 'SubjectCategoryController@store')->name('subject_category.store');
    Route::post('subject_category/delete', 'SubjectCategoryController@destroy')->name('subject_category.delete');
    Route::post('subject_category/check', 'SubjectCategoryController@check')->name('subject_category.check');
    Route::delete('subject_category/destroy', 'SubjectCategoryController@massDestroy')->name('subject_category.massDestroy');

    // Subject Registration
    Route::delete('subject-registrations/destroy', 'SubjectRegistrationController@massDestroy')->name('subject-registrations.massDestroy');
    Route::post('subject-registrations/parse-csv-import', 'SubjectRegistrationController@parseCsvImport')->name('subject-registrations.parseCsvImport');
    Route::post('subject-registrations/process-csv-import', 'SubjectRegistrationController@processCsvImport')->name('subject-registrations.processCsvImport');
    Route::resource('subject-registrations', 'SubjectRegistrationController');
    Route::get('degree-wise-subject-registration/index', 'SubjectRegistrationController@degreeWise')->name('degree-wise-subject-registration.index');
    Route::post('degree-wise-subject-registration/search', 'SubjectRegistrationController@degreeWise_search')->name('degree-wise-subject-registration.search');
    Route::post('subject-registration/get_sections', 'SubjectRegistrationController@getSections')->name('subject-registration.get_sections');
    Route::get('degree-wise-subject-registration/show/{enroll}', 'SubjectRegistrationController@degreeWise_show')->name('degree-wise-subject-registration.show');
    Route::get('subject-wise-subject-registration/show/{enroll}/{subject}', 'SubjectRegistrationController@subjectWise_show')->name('subject-wise-subject-registration.show');
    Route::get('subject-wise-subject-registration/index', 'SubjectRegistrationController@subjectWise')->name('subject-wise-subject-registration.index');
    Route::post('subject-wise-subject-registration/search', 'SubjectRegistrationController@subjectWise_search')->name('subject-wise-subject-registration.search');

    // Exam Cell Coordinators
    Route::get('Exam-Cell-Coordinators/index', 'ExamCellCoordinatorsController@index')->name('exam_cell_coordinators.index');
    Route::get('Exam-Cell-Coordinators/create', 'ExamCellCoordinatorsController@create')->name('exam_cell_coordinators.create');
    Route::post('Exam-Cell-Coordinators/create/store', 'ExamCellCoordinatorsController@store')->name('exam_cell_coordinators.store');
    Route::delete('Exam-Cell-Coordinators/remove/{id}', 'ExamCellCoordinatorsController@remove')->name('exam_cell_coordinators.remove');
    Route::get('Exam-Cell-Coordinators/show/{id}', 'ExamCellCoordinatorsController@show')->name('exam_cell_coordinators.show');

    //Lab mark
    Route::get('lab-mark/index', 'lab_markController@index')->name('lab_mark.index');
    Route::get('lab-mark/create', 'lab_markController@create')->name('lab_schedule.create');
    Route::post('lab-mark/store', 'lab_markController@store')->name('lab_schedule.store');
    Route::get('lab-mark/show/{id}', 'lab_markController@view')->name('lab-mark.show');
    Route::get('lab-mark.view/{id}', 'lab_markController@show')->name('lab-mark.view');
    Route::get('lab-mark.edit/{id}', 'lab_markController@edit')->name('lab-mark.edit');
    Route::DELETE('lab-mark.destroy/{ExamTimetableCreation}', 'lab_markController@destroy')->name('lab-mark.destroy');
    Route::post('lab-mark.update/{ExamTimetableCreation}', 'lab_markController@update')->name('lab_markController.update');
    Route::post('lab-mark/search', 'lab_markController@search')->name('lab_schedule.search');
    Route::post('lab_examTimetable.Subject_get', 'lab_markController@lab_subject_get')->name('lab_examTimetable.Subject_get');
    Route::post('lab_examTimetable.Subject_get_edit', 'lab_markController@lab_subject_get_edit')->name('lab_examTimetable_edit.Subject_get');

    //lab exam attendance menu
    Route::get('lab_Exam_Attendance/attendance', 'lab_markController@attendance')->name('lab_Exam_Attendance.attendance');
    Route::get('lab_Exam_Attendance/AttendanceEnter/{id}/{recordId}', 'lab_markController@attendanceEnter')->name('lab_exam_attendance.attendanceEnter');
    Route::post('lab_Exam_Attendance/Attendence_Store', 'lab_markController@attendencestore')->name('labExamAttendance.attendencestore');
    Route::get('lab_Exam-Attendance/editattendance/{id}/{recordId}', 'lab_markController@editattendance')->name('lab_exam_attendance.editattendance');
    Route::get('lab_Exam-Attendance/ViewAttendance/{id}/{recordId}', 'lab_markController@viewattendance')->name('lab_exam_attendance.viewattendance');
    Route::post('lab_Exam-Attendance/attendenceUpdate', 'lab_markController@attendenceUpdate')->name('lab_exam_attendance.attendenceUpdate');
    Route::post('lab_examTimetable.find', 'lab_markController@find')->name('lab_examTimetable.find');

    //LAB Mark Staff page
    Route::get('lab_Exam-Mark/staff', 'lab_markController@staff')->name('lab_Exam-Mark.staff');
    Route::get('lab_Exam-Mark-Result/index', 'lab_markController@indexStaff')->name('lab_Exam-Mark-Result.index');
    Route::get('lab_Exam-Mark-Result/view/{id}/{recordId}', 'lab_markController@resultview')->name('lab_Exam-Mark-Result.resultview');
    Route::get('lab_Exam-result-StaffWise-report/pdf/{classId}/{subjectId}/{pdf}', 'lab_markController@resultview')->name('lab_Exam-result-StaffWise-report');
    Route::post('lab_Exam-Mark-Result/get-past-records', 'lab_markController@getPastRecords')->name('lab_Exam-Mark-Result.get-past-records');

    //Lab Mark Student Page
    Route::get('student_lab_mark/report', 'StudentLabMarkStatementController@index')->name('student_lab_mark.statement');
    Route::get('student-lab-mark/report', 'StudentLabMarkStatementController@index')->name('student_lab_mark_2.statement');

    // LAB Exam Mark Master
    Route::get('lab_Exam_Mark_master/index', 'LabMarkMasterController@index')->name('lab_Exam-Mark.index');
    Route::get('lab_Exam-Mark/view/{id}/{recordId}', 'LabMarkMasterController@markview')->name('lab_Exam_Mark.markview');
    Route::get('lab_Exam-Mark/Mark/{id}/{recordId}', 'LabMarkMasterController@MarkEnter')->name('lab_Exam_Mark.markEnter');
    Route::post('lab_Exam_Mark/Store', 'LabMarkMasterController@MarkStore')->name('Lab_Exam_Mark.markStore');
    Route::post('lab_Exam-Mark/Edit_request', 'LabMarkMasterController@editMark_request')->name('lab_exam_edit_request');
    Route::post('lab_Exam_Mark/Status-Update', 'LabMarkMasterController@verifiedStatus')->name('lab_verifiedStatus');
    Route::get('lab_Exam-Mark/Edit/{id}/{recordId}', 'LabMarkMasterController@editMark')->name('lab_Exam_Mark.editMark');
    Route::post('lab_Exam_Mark/toggle_status', 'LabMarkMasterController@toggle_status')->name('lab_Exam-Mark.toggle_status');
    Route::post('lab_Exam_Mark/find', 'LabMarkMasterController@find')->name('lab_Exam_Mark.find');

    // lab Attendance Summary
    Route::get('lab_Exam-Attendance-summary-report', 'LabExamAttendanceSummaryController@index')->name('lab_Exam_attendance.summary.index');
    Route::post('lab_Exam_AttendanceSummary_search', 'LabExamAttendanceSummaryController@search')->name('lab_Exam_AttendanceSummary.search');
    Route::post('lab_Exam_AttendanceSummary_course_get', 'LabExamAttendanceSummaryController@course_get')->name('lab_Exam_AttendanceSummary.course_get');
    Route::post('lab_Exam_AttendanceSummary_course_get/getDate', 'LabExamAttendanceSummaryController@getDate')->name('lab_Exam_AttendanceSummary.getDate');
    Route::post('lab_Exam_AttendanceSummary_Subject_get', 'LabExamAttendanceSummaryController@subject_get')->name('lab_Exam_AttendanceSummary.Subject_get');
    Route::get('lab_Exam-absentees-report/pdf/{id}/{academicYear_id}/{year}/{semester_id}/{date}/{department}/{course_id}/{exameName}', 'LabExamAttendanceSummaryController@absenteesReportPDF')->name('lab_Exam-absentees-report.pdf');
    Route::get('lab_Result_Analysis_Abstract', 'LabExamAttendanceSummaryController@Abstract')->name('lab_Result_Analysis_Abstract');
    Route::post('lab_Result_Analysis_Abstract/Abstractget', 'LabExamAttendanceSummaryController@Abstractget')->name('lab_Result_Analysis_Abstract.Abstractget');
    Route::get('lab_Result_Analysis_Class_Wise', 'LabExamAttendanceSummaryController@Result_Analysis_Class_Wise')->name('lab_Result_Analysis_Class_Wise.index');
    Route::post('lab_Result_Analysis_Class_Wise/get', 'LabExamAttendanceSummaryController@get')->name('lab_Result_Analysis_Class_Wise.get');
    Route::get('lab_Exam-classWise-summary-report/pdf/{Ay}/{Ex}/{Sem}/{Course}/{Year}/{Sec}', 'LabExamAttendanceSummaryController@Exam_classWise_ReportPDF')->name('lab_Ex_classWise-summary-report.pdf');
    Route::get('lab_Result_Analysis_Staff_Wise', 'LabExamAttendanceSummaryController@Result_Analysis_Staff_Wise')->name('lab_Result_Analysis_Staff_Wise.index');
    Route::post('lab_Result_Analysis_Staff_Wise/staff_wise', 'LabExamAttendanceSummaryController@staff_wise')->name('lab_Result_Analysis_Staff_Wise.staff_wise');
    Route::post('lab_Exam/section_exam_name_get', 'LabExamAttendanceSummaryController@subject_get2')->name('lab_Exam.section_exam_name_get');
    Route::get('lab_Exam-StaffWise-report/pdf/{ay}/{year}/{sem}/{examname}/{course}/{section}', 'LabExamAttendanceSummaryController@staff_wisePDF')->name('lab_Exam_staff_wise_report.pdf');
    Route::get('lab_Result_Analysis_bar_chart', 'LabExamAttendanceSummaryController@chart')->name('lab_Result_Analysis_bar_chart.chart');
    Route::post('lab_Result_Analysis_bar_chart/Bar_chart', 'LabExamAttendanceSummaryController@showChart')->name('lab_Result_Analysis_bar_chart.showChart');

    // Route::resource('Exam-Cell-Coordinators', 'ExamCellCoordinatorsController');

    // Class Time Table
    Route::delete('class-time-table/destroy', 'ClassTimeTableController@massDestroy')->name('class-time-table.massDestroy');
    Route::post('class-time-table/search', 'ClassTimeTableController@search')->name('class-time-table.search');
    Route::get('class-time-table/version/{id}', 'ClassTimeTableController@version')->name('class-time-table.version');
    Route::post('class-time-table/versionShow', 'ClassTimeTableController@versionShow')->name('class-time-table.versionShow');
    Route::post('class-time-table/updater', 'ClassTimeTableController@updater')->name('class-time-table.updater');
    Route::post('class-time-table/status_update', 'ClassTimeTableController@status_update')->name('class-time-table.status_update');
    Route::get('class-time-table-two/live_show/{id}', 'ClassTimeTableController@live_show')->name('class-time-table-two.live_show');
    Route::resource('class-time-table', 'ClassTimeTableController');
    Route::get('student-time-table/{user_name_id}', 'ClassTimeTableController@get_myTimeTable')->name('student-time-table');
    Route::post('get-subjects/index', 'ClassTimeTableController@subjects')->name('get-subjects.index');
    Route::post('check-staff-period/check', 'ClassTimeTableController@check')->name('check-staff-period.check');
    Route::post('class-time-table/get_staff_and_subject', 'ClassTimeTableController@getStaffAndSubject')->name('class-time-table.get_staff_and_subject');
    Route::post('class-time-table/get_students', 'ClassTimeTableController@getStudents')->name('class-time-table.get_students');
    Route::post('class-time-table/store_allot_students', 'ClassTimeTableController@storeAllotStudents')->name('class-time-table.store_allot_students');
    Route::post('class-time-table/get_alloted_students', 'ClassTimeTableController@getAllotStudents')->name('class-time-table.get_alloted_students');
    Route::post('get_all_section', 'ClassTimeTableController@getSections')->name('get_all_section');
    Route::post('class-time-table/get-course', 'ClassTimeTableController@getCourse')->name('class-time-table.get-course');
    Route::get('remove-unwanted-allotedBatch', 'ClassTimeTableController@removeUnwantedAllot')->name('remove-unwanted-allotedBatch');

    // Staff Time Table
    Route::get('staff-time-table/index', 'StaffTimeTableController@index')->name('staff-time-table.index');

    //Faculty work Load Report
    Route::get('Faculty-WorkLoad/index', 'FacultyWorkLoadController@index')->name('Faculty-WorkLoad.index');
    Route::post('Faculty-WorkLoad/Report', 'FacultyWorkLoadController@show')->name('Faculty-WorkLoad.show');
    Route::get('Faculty-WorkLoad/index/{user_name_id}', 'FacultyWorkLoadController@view')->name('Faculty-WorkLoad.view');

    //Hostel Block
    Route::get('hostel', 'HostelBlockController@index')->name('hostel.index');
    Route::post('hostel/view', 'HostelBlockController@view')->name('hostel.view');
    Route::post('hostel/edit', 'HostelBlockController@edit')->name('hostel.edit');
    Route::post('hostel/store', 'HostelBlockController@store')->name('hostel.store');
    Route::post('hostel/delete', 'HostelBlockController@destroy')->name('hostel.delete');
    Route::delete('hostel/destroy', 'HostelBlockController@massDestroy')->name('hostel.massDestroy');

    // Hotsel Warden
    Route::get('hostel-warden', 'HostelWardenController@index')->name('hostel-warden.index');
    Route::post('hostel-warden/view', 'HostelWardenController@view')->name('hostel-warden.view');
    Route::post('hostel-warden/edit', 'HostelWardenController@edit')->name('hostel-warden.edit');
    Route::post('hostel-warden/store', 'HostelWardenController@store')->name('hostel-warden.store');
    Route::post('hostel-warden/delete', 'HostelWardenController@destroy')->name('hostel-warden.delete');
    Route::delete('hostel-warden/destroy', 'HostelWardenController@massDestroy')->name('hostel-warden.massDestroy');

    Route::get('hostelRoom', 'HostelBlockController@roomIndex')->name('hostelRoom.index');
    Route::post('hostelRoom/view', 'HostelBlockController@roomView')->name('hostelRoom.view');
    Route::post('hostelRoom/edit', 'HostelBlockController@roomEdit')->name('hostelRoom.edit');
    Route::post('hostelRoom/store', 'HostelBlockController@roomStore')->name('hostelRoom.store');
    Route::post('hostelRoom/delete', 'HostelBlockController@roomDestroy')->name('hostelRoom.delete');
    Route::get('hostelRoom/staffIndex', 'HostelBlockController@roomStaffIndex')->name('hostelRoom.roomStaffIndex');
    Route::delete('hostelRoom/destroy', 'HostelBlockController@roomMassDestroy')->name('hostelRoom.massDestroy');


    Route::get('room-allot', 'RoomAllocationController@index')->name('room-allot.index');
    Route::post('room-allot/view', 'RoomAllocationController@view')->name('room-allot.view');
    Route::post('room-allot/edit', 'RoomAllocationController@edit')->name('room-allot.edit');
    Route::post('room-allot/store', 'RoomAllocationController@store')->name('room-allot.store');
    Route::get('room-allot/staffIndex', 'RoomAllocationController@staffIndex')->name('room-allot.staffIndex');
    Route::post('room-allot/delete', 'RoomAllocationController@destroy')->name('room-allot.delete');
    Route::delete('room-allot/destroy', 'RoomAllocationController@massDestroy')->name('room-allot.massDestroy');
    Route::post('room-allot/checkRoom', 'RoomAllocationController@checkRoom')->name('room-allot.checkRoom');


    Route::get('hostel-attendance', 'HostelAttendanceController@index')->name('hostel-attendance.index');
    Route::post('hostel-attendance/view', 'HostelAttendanceController@view')->name('hostel-attendance.view');
    Route::post('hostel-attendance/edit', 'HostelAttendanceController@edit')->name('hostel-attendance.edit');
    Route::post('hostel-attendance/store', 'HostelAttendanceController@store')->name('hostel-attendance.store');
    Route::post('hostel-attendance/delete', 'HostelAttendanceController@destroy')->name('hostel-attendance.delete');
    Route::delete('hostel-attendance/destroy', 'HostelAttendanceController@massDestroy')->name('hostel-attendance.massDestroy');
    Route::post('hostel-attendance/get-student', 'HostelAttendanceController@get_student')->name('hostel-attendance.get-student');
    Route::post('hostel-attendance/view_attendance', 'HostelAttendanceController@view_attendance')->name('hostel-attendance.view_attendance');
    Route::post('hostel-attendance/edit_attendance', 'HostelAttendanceController@edit_attendance')->name('hostel-attendance.edit_attendance');
    Route::get('hostel-attendance-report/reportIndex', 'HostelAttendanceController@reportIndex')->name('hostel-attendance.reportIndex');
    Route::post('hostel-attendance-report/get_report', 'HostelAttendanceController@get_report')->name('hostel-attendance.get_report');

    //Hostel students
    Route::get('hostel-students', 'HostelStudentController@index')->name('hostel-students.index');
    Route::post('hostel-students/getRoom', 'HostelStudentController@getRoom')->name('hostel-students.getRoom');
    Route::post('hostel-students/search', 'HostelStudentController@search')->name('hostel-students.search');
    Route::get('hostel-students/take-attendance', 'HostelStudentController@takeAttendance')->name('hostel-students.take-attendance');
    // Route::post('hostel-students/edit', 'HostelStudentController@edit')->name('hostel-students.edit');
    // Route::get('hostel-students/staffIndex', 'HostelStudentController@staffIndex')->name('hostel-students.staffIndex');
    // Route::post('hostel-students/delete', 'HostelStudentController@destroy')->name('hostel-students.delete');

    Route::get('bus', 'BusController@index')->name('bus.index');
    Route::post('bus/edit', 'BusController@edit')->name('bus.edit');
    Route::post('bus/store', 'BusController@store')->name('bus.store');
    Route::post('bus/view', 'BusController@view')->name('bus.view');
    Route::post('bus/delete', 'BusController@destroy')->name('bus.delete');
    Route::delete('bus/destroy', 'BusController@massDestroy')->name('bus.massDestroy');

    Route::get('driver', 'DriverController@index')->name('driver.index');
    Route::get('driver/create', 'DriverController@create')->name('driver.create');
    Route::post('driver/store', 'DriverController@store')->name('driver.store');
    Route::put('driver/update', 'DriverController@update')->name('driver.update');
    Route::post('driver/delete', 'DriverController@destroy')->name('driver.destroy');
    Route::delete('driver/destroy', 'DriverController@massDestroy')->name('driver.massDestroy');
    Route::get('driver/Profile-edit/{id}', 'DriverController@edit')->name('driver.Profile-edit');
    Route::get('driver/Profile-view/{id}', 'DriverController@show')->name('driver.Profile-view');
    Route::get('driver/show/{id}', 'DriverController@show')->name('driver.show');
    Route::get('driver/edit/{id}', 'DriverController@edit')->name('driver.edit');
    Route::get('driver/staffIndex', 'DriverController@staffIndex')->name('driver.staffIndex');
    // Route::resource('driver', 'DriverController');


    Route::get('bus-route', 'BusRouteController@index')->name('bus-route.index');
    Route::post('bus-route/edit', 'BusRouteController@edit')->name('bus-route.edit');
    Route::post('bus-route/store', 'BusRouteController@store')->name('bus-route.store');
    Route::post('bus-route/view', 'BusRouteController@show')->name('bus-route.view');
    Route::post('bus-route/delete', 'BusRouteController@destroy')->name('bus-route.delete');
    Route::delete('bus-route/destroy', 'BusRouteController@massDestroy')->name('bus-route.massDestroy');
    // Route::post('bus/get-student', 'BusController@get_student')->name('bus.get-student');

    // Bus Route Allocation
    Route::get('route-allot', 'RouteAllocationController@index')->name('route-allot.index');
    Route::post('route-allot/edit', 'RouteAllocationController@edit')->name('route-allot.edit');
    Route::post('route-allot/store', 'RouteAllocationController@store')->name('route-allot.store');
    Route::post('route-allot/view', 'RouteAllocationController@view')->name('route-allot.view');
    Route::post('route-allot/delete', 'RouteAllocationController@destroy')->name('route-allot.delete');
    Route::delete('route-allot/destroy', 'RouteAllocationController@massDestroy')->name('route-allot.massDestroy');

    Route::get('bus-student', 'BusStudentController@index')->name('bus-student.index');
    Route::post('bus-student/edit', 'BusStudentController@edit')->name('bus-student.edit');
    Route::post('bus-student/store', 'BusStudentController@store')->name('bus-student.store');
    Route::post('bus-student/view', 'BusStudentController@view')->name('bus-student.view');
    Route::post('bus-student/delete', 'BusStudentController@destroy')->name('bus-student.delete');
    Route::delete('bus-student/destroy', 'BusStudentController@massDestroy')->name('bus-student.massDestroy');
    Route::post('bus-student/checkDesignation', 'BusStudentController@checkDesignation')->name('bus-student.checkDesignation');
    Route::get('transport-report/index', 'BusStudentController@reportIndex')->name('transport-report.reportIndex');
    Route::post('transport-report/report', 'BusStudentController@report')->name('transport-report.report');

    // Library Rack
    Route::get('rack', 'RackController@index')->name('rack.index');
    Route::post('rack/edit', 'RackController@edit')->name('rack.edit');
    Route::post('rack/store', 'RackController@store')->name('rack.store');
    Route::post('rack/view', 'RackController@view')->name('rack.view');
    Route::post('rack/delete', 'RackController@destroy')->name('rack.delete');
    Route::delete('rack/destroy', 'RackController@massDestroy')->name('rack.massDestroy');

    Route::get('genre', 'GenreController@index')->name('genre.index');
    Route::post('genre/edit', 'GenreController@edit')->name('genre.edit');
    Route::post('genre/store', 'GenreController@store')->name('genre.store');
    Route::post('genre/view', 'GenreController@view')->name('genre.view');
    Route::post('genre/delete', 'GenreController@destroy')->name('genre.delete');
    Route::delete('genre/destroy', 'GenreController@massDestroy')->name('genre.massDestroy');

    Route::get('book', 'BookController@index')->name('book.index');
    Route::post('book/edit', 'BookController@edit')->name('book.edit');
    Route::post('book/store', 'BookController@store')->name('book.store');
    Route::post('book/view', 'BookController@view')->name('book.view');
    Route::post('book/delete', 'BookController@destroy')->name('book.delete');
    Route::delete('book/destroy', 'BookController@massDestroy')->name('book.massDestroy');
    Route::get('book/downloadQr/{id}', 'BookController@downloadQr')->name('book.downloadQr');
    Route::get('download-pdf/{filename}', 'BookController@downloadPdf');
    Route::post('book/parse-csv-import', 'BookController@parseCsvImport')->name('book-models.parseCsvImport');
    Route::post('book/process-csv-import', 'BookController@processCsvImport')->name('book-models.processCsvImport');

    Route::get('book-allocate', 'BookAllocateController@index')->name('book-allocate.index');
    Route::post('book-allocate/edit', 'BookAllocateController@edit')->name('book-allocate.edit');
    Route::post('book-allocate/store', 'BookAllocateController@store')->name('book-allocate.store');
    Route::post('book-allocate/view', 'BookAllocateController@view')->name('book-allocate.view');
    Route::post('book-allocate/delete', 'BookAllocateController@destroy')->name('book-allocate.delete');
    Route::delete('book-allocate/destroy', 'BookAllocateController@massDestroy')->name('book-allocate.massDestroy');
    Route::post('book-allocate/fetchRow', 'BookAllocateController@fetchRow')->name('book-allocate.fetchRow');
    Route::post('book-allocate/fetchBook', 'BookAllocateController@fetchBook')->name('book-allocate.fetchBook');
    Route::post('book-allocate/fetchCount', 'BookAllocateController@fetchCount')->name('book-allocate.fetchCount');

    //Book Issue
    Route::get('book-issue', 'BookIssueController@index')->name('book-issue.index');
    Route::post('book-issue/edit', 'BookIssueController@edit')->name('book-issue.edit');
    Route::post('book-issue/store', 'BookIssueController@store')->name('book-issue.store');
    Route::post('book-issue/view', 'BookIssueController@view')->name('book-issue.view');
    Route::post('book-issue/delete', 'BookIssueController@destroy')->name('book-issue.delete');
    Route::delete('book-issue/destroy', 'BookIssueController@massDestroy')->name('book-issue.massDestroy');
    Route::post('book-issue/fetchBook', 'BookIssueController@fetchBook')->name('book-issue.fetchBook');
    Route::post('book-issue/fetchBookNo', 'BookIssueController@fetchBookNo')->name('book-issue.fetchBookNo');
    Route::post('book-issue/checkStudent', 'BookIssueController@checkStudent')->name('book-issue.checkStudent');
    Route::get('book-issue/get_record/{id}', 'BookIssueController@get_record')->name('book-issue.get_record');
    Route::get('book-issue/get-book-info/{id}', 'BookIssueController@get_book_info')->name('book.get_book_info');
    Route::post('book-issue/updater', 'BookIssueController@updater')->name('book-issue.updater');
    Route::post('book-issue/reservation', 'BookIssueController@reservation')->name('book-issue.reservation');
    Route::get('reserve-report/report', 'BookIssueController@reserveReport')->name('reserve-report.reserveReport');
    Route::get('memberWise-report/report', 'BookIssueController@memberWiseReport')->name('memberWise-report.memberWiseReport');
    Route::get('departWise-report/report/{who}', 'BookIssueController@departWise')->name('departWise-report.departWiseReport');
    Route::get('inventory-report/report', 'BookIssueController@inventory')->name('inventory-report.inventory');
    Route::post('reserve-report/search', 'BookIssueController@search')->name('reserve-report.search');

    // Feedback type Master
    Route::get('feedback-type', 'FeedbackTypeController@index')->name('feedback-type.index');
    Route::post('feedback-type/edit', 'FeedbackTypeController@edit')->name('feedback-type.edit');
    Route::post('feedback-type/store', 'FeedbackTypeController@store')->name('feedback-type.store');
    Route::post('feedback-type/view', 'FeedbackTypeController@view')->name('feedback-type.view');
    Route::post('feedback-type/delete', 'FeedbackTypeController@destroy')->name('feedback-type.delete');
    Route::delete('feedback-type/massDestroy', 'FeedbackTypeController@massDestroy')->name('feedback-type.massDestroy');
    
    
    //FeedBack Configure
    Route::get('configure-feedback', 'FeedbackController@configureIndex')->name('configure-feedback.index');
    Route::post('configure-feedback/edit', 'FeedbackController@configureEdit')->name('configure-feedback.edit');
    Route::post('configure-feedback/store', 'FeedbackController@configureStore')->name('configure-feedback.store');
    Route::post('configure-feedback/view', 'FeedbackController@configureView')->name('configure-feedback.view');
    Route::post('configure-feedback/delete', 'FeedbackController@configureDestroy')->name('configure-feedback.delete');
    
    
    
    //Feedback Schedule
    Route::get('schedule-feedback', 'FeedbackController@scheduleIndex')->name('schedule-feedback.index');
    Route::post('schedule-feedback/edit', 'FeedbackController@scheduleEdit')->name('schedule-feedback.edit');
    Route::post('schedule-feedback/store', 'FeedbackController@scheduleStore')->name('schedule-feedback.store');
    Route::post('schedule-feedback/view', 'FeedbackController@scheduleView')->name('schedule-feedback.view');
    Route::post('schedule-feedback/delete', 'FeedbackController@scheduleDestroy')->name('schedule-feedback.delete');
    Route::post('schedule-feedback/fetch-course', 'FeedbackController@fetchCourse')->name('schedule-feedback.fetch_course');
    Route::post('schedule-feedback/fetch-section', 'FeedbackController@fetchSection')->name('schedule-feedback.fetch_section');
    Route::post('schedule-feedback/change-status', 'FeedbackController@change_status')->name('schedule-feedback.change-status');
    
    //Student Feedback
    Route::get('feedback-forms', 'FeedbackController@studentIndex')->name('feedback-forms.index');
    Route::post('feedback-forms/survey', 'FeedbackController@studentFeedSurvey')->name('student-feedback-forms.survey');
    Route::post('feedback-forms/submit', 'FeedbackController@studentFeedStore')->name('student-feedback-forms.store');

    //Staff Feedback
    Route::get('feedback-form', 'FeedbackController@staffIndex')->name('feedback-form.index');
    Route::post('feedback-form/survey', 'FeedbackController@staffFeedSurvey')->name('staff-feedback-form.survey');
    Route::post('feedback-form/submit', 'FeedbackController@staffFeedStore')->name('staff-feedback-form.store');


    //Feedback Reports----------------------------------------

    //Student Others Feedback
    Route::get('feedReport-training', 'FeedbackReportController@trainingIndex')->name('feedReport-training.index');
    Route::post('feedback-training/report', 'FeedbackReportController@trainingReport')->name('feedback-training.report');
    Route::post('feedback-training/view', 'FeedbackReportController@trainingView')->name('feedback-training.view');
    Route::post('feedback-training/download', 'FeedbackReportController@trainingDownload')->name('feedback-training.download');

    //Student Course Feedback
    Route::get('feedReport-course', 'FeedbackReportController@courseIndex')->name('feedReport-course.index');
    Route::post('feedback-course/report', 'FeedbackReportController@courseReport')->name('feedback-course.report');
    Route::post('feedback-course/view', 'FeedbackReportController@courseView')->name('feedback-course.view');
    Route::post('feedback-course/download', 'FeedbackReportController@courseDownload')->name('feedback-course.download');
    
    //Faculty Feedback
    Route::get('feedReport-faculty', 'FeedbackReportController@facultyIndex')->name('feedReport-faculty.index');
    Route::post('feedback-faculty/report', 'FeedbackReportController@facultyReport')->name('feedback-faculty.report');
    Route::post('feedback-faculty/view', 'FeedbackReportController@facultyView')->name('feedback-faculty.view');
    Route::post('feedback-faculty/download', 'FeedbackReportController@facultyDownload')->name('feedback-faculty.download');
    
    //External Feedback
    Route::get('feedReport-external', 'FeedbackReportController@externalIndex')->name('feedReport-external.index');
    Route::post('feedback-external/report', 'FeedbackReportController@externalReport')->name('feedback-external.report');
    Route::post('feedback-external/view', 'FeedbackReportController@externalView')->name('feedback-external.view');
    Route::post('feedback-external/download', 'FeedbackReportController@externalDownload')->name('feedback-external.download');


    // Staff Subjects
    Route::get('staff-subjects/lesson-plan/download-pdf/{enroll}/{subject}/{status}', 'StaffSubjectsController@lessonPlanPdf')->name('staff-subjects.lesson-plan.download-pdf');
    Route::get('staff-subjects/lesson-plan', 'StaffSubjectsController@lesson_plan')->name('staff-subjects.lesson-plan');
    Route::get('staff-subjects/lesson-plan/view/{enroll}/{subject}/{status}', 'StaffSubjectsController@lessonPlanView')->name('staff-subjects.lesson-plan.view');
    Route::get('staff-subjects/lesson-plan/edit/{enroll}/{subject}/{status}', 'StaffSubjectsController@lessonPlanEdit')->name('staff-subjects.lesson-plan.edit');
    Route::get('staff-subjects/lesson-plan/complete/{enroll}/{subject}/{status}', 'StaffSubjectsController@lessonPlanComplete')->name('staff-subjects.lesson-plan.complete');
    Route::post('staff-subjects/lesson-plan/delete/{enroll}/{subject}/{status}', 'StaffSubjectsController@lessonPlanDelete')->name('staff-subjects.lesson-plan.delete');
    Route::get('staff-subjects/index', 'StaffSubjectsController@index')->name('staff-subjects.index');
    Route::get('staff-subjects/lesson-plan/add', 'StaffSubjectsController@lessonPlanAdd')->name('staff-subjects.lesson-plan.add');
    Route::post('staff-subjects/get-subjects', 'StaffSubjectsController@get_subjects')->name('staff-subjects.get-subjects');
    Route::post('staff-subjects/lesson-plan/submit', 'StaffSubjectsController@lessonPlanSubmit')->name('staff-subjects.lesson-plan.submit');
    Route::post('staff-subjects/lesson-plan/save', 'StaffSubjectsController@lessonPlanSave')->name('staff-subjects.lesson-plan.save');
    Route::post('staff-subjects/lesson-plan/update', 'StaffSubjectsController@lessonPlanUpdate')->name('staff-subjects.lesson-plan.update');
    Route::post('staff-subjects/get-past-records', 'StaffSubjectsController@getPastSubjectRecords')->name('staff-subjects.get-past-records');
    Route::post('staff-subjects/lesson-plan/get-past-records', 'StaffSubjectsController@getPastLessonPlanRecords')->name('staff-subjects.lesson-plan.get-past-records');

    // Staff Lesson Plan HOD Access
    Route::get('staff-subjects/lesson-plan/hod-view/{enroll}/{subject}/{status}', 'StaffSubjectsController@lessonPlanHODView')->name('staff-subjects.lesson-plan.hod-view');
    Route::get('staff-lesson-plan/index/{status}', 'StaffSubjectsController@lessonPlanHOD')->name('staff-lesson-plan.index');
    Route::post('staff-lesson-plan/action', 'StaffSubjectsController@lessonPlanAction')->name('staff-lesson-plan.action');

    //staff Alteration Report
    Route::get('Staff-Alteration-Report', 'staffAlterationReportController@index')->name('Staff-Alteration-Report.index');
    Route::resource('Staff-Alteration-Report', 'staffAlterationReportController');

    // Bulk OD
    Route::delete('bulk-ods/destroy', 'BulkODController@massDestroy')->name('bulk-ods.massDestroy');
    Route::post('bulk-ods/parse-csv-import', 'BulkODController@parseCsvImportOD')->name('bulk-ods.parseCsvImport');
    Route::post('bulk-ods/process-csv-import', 'BulkODController@processCsvImport')->name('bulk-o-ds.processCsvImport');
    Route::post('bulk-ods/action', 'BulkODController@action')->name('bulk-ods.action');
    Route::post('bulk-ods/index_page', 'BulkODController@index')->name('bulk-ods.index_page');
    Route::post('bulk-ods/save', 'BulkODController@save')->name('bulk-ods.save');
    Route::post('bulk-ods/check', 'BulkODController@check')->name('bulk-ods.check');
    Route::post('bulk-ods/documents', 'BulkODController@documents')->name('bulk-ods.documents');
    Route::resource('bulk-ods', 'BulkODController');

    //student-Promotion
    Route::get('student-Promotion/index', 'studentPromotionController@index')->name('student-Promotion.index');
    Route::post('student-Promotion/search', 'studentPromotionController@search')->name('student-Promotion.search');
    Route::post('student-Promotion/promote', 'studentPromotionController@promote')->name('student-Promotion.promote');
    Route::post('student-Promotion/getSections', 'studentPromotionController@getSections')->name('student-Promotion.getSections');

    //Syllabus Completion Staff wise
    Route::get('Lesson-Plane-Report/index', 'Lesson_plane_reportsController@index')->name('lesson_plane_report.index');
    Route::post('Lesson-Plane-Report/search', 'Lesson_plane_reportsController@search')->name('staffFilter.search');
    Route::post('Lesson-Plane-Report/showTable', 'Lesson_plane_reportsController@showTable')->name('staffFilter.showTable');

    // Syllabus Completion Report
    Route::get('Syllabus-Completion/index', 'SyllabusCompletionDeptWiseController@index')->name('Syllabus-Completion.index');
    Route::post('Syllabus-Completion/search', 'SyllabusCompletionDeptWiseController@search')->name('Syllabus-Completion.search');
    Route::post('Syllabus-Completion/get-past-records', 'SyllabusCompletionDeptWiseController@getPastRecords')->name('Syllabus-Completion.get-past-records');
    Route::get('Syllabus-Completion/view/{enroll}', 'SyllabusCompletionDeptWiseController@view')->name('staffFilter.view');
    Route::get('Syllabus-Completion/pdf/{enroll}', 'SyllabusCompletionDeptWiseController@pdf')->name('staffFilter.pdf');

    // Student Mark
    Route::get('student-marks/index', 'StudentMarkController@index')->name('student-marks.index');
    Route::get('student-marks/show', 'StudentMarkController@show')->name('student-marks.show');
    Route::post('get-students-for-mark/get_students', 'StudentMarkController@get_students')->name('get-students-for-mark.get_students');
    Route::post('save-students-mark/store', 'StudentMarkController@store')->name('save-students-mark.store');

    // Student Period Attendance
    Route::get('student-period-attendance/index', 'StudentPeriodAttendanceController@index')->name('student-period-attendance.index');
    Route::post('get-staff-day-period/get_day', 'StudentPeriodAttendanceController@get_day')->name('get-staff-day-period.get_day');
    Route::post('student-period-attendance/list', 'StudentPeriodAttendanceController@list')->name('student-period-attendance.list');
    Route::post('student-period-attendance/got_list', 'StudentPeriodAttendanceController@got_list')->name('student-period-attendance.got_list');
    Route::post('student-period-attendance/store', 'StudentPeriodAttendanceController@store')->name('student-period-attendance.store');
    Route::post('student-period-attendance/unitGet', 'StudentPeriodAttendanceController@unitGet')->name('student-period-attendance.unitGet');
    Route::post('student-period-attendance/edit_requesting', 'StudentPeriodAttendanceController@editRequesting')->name('student-period-attendance.edit_requesting');
    Route::post('student-period-attendance/edit_attendance', 'StudentPeriodAttendanceController@editAttendance')->name('student-period-attendance.edit_attendance');
    Route::post('student-period-attendance/delete_requesting', 'StudentPeriodAttendanceController@deleteRequesting')->name('student-period-attendance.delete_requesting');
    Route::post('student-period-attendance/take_periods', 'StudentPeriodAttendanceController@takePeriods')->name('student-period-attendance.take_periods');
    Route::post('student-period-attendance/taken_periods', 'StudentPeriodAttendanceController@takenPeriods')->name('student-period-attendance.taken_periods');
    Route::post('student-period-attendance/get_batch', 'StudentPeriodAttendanceController@getBatch')->name('student-period-attendance.get_batch');
    Route::post('student-period-attendance/check_period', 'StudentPeriodAttendanceController@checkPeriod')->name('student-period-attendance.check_period');
    Route::post('student-period-attendance/get-period', 'StudentPeriodAttendanceController@getPeriod')->name('student-period-attendance.get_period');
    Route::post('student-period-attendance/get-classes', 'StudentPeriodAttendanceController@getClasses')->name('student-period-attendance.get-classes');
    Route::post('student-period-attendance/attendance_log', 'StudentPeriodAttendanceController@attendanceLog')->name('student-period-attendance.attendance_log');
    Route::post('student-period-attendance/get-past-records', 'StudentPeriodAttendanceController@getPastRecords')->name('student-period-attendance.get-past-records');
    Route::get('Student_period_Attendance_report/pdf/{Subject}/{Class}', 'StudentPeriodAttendanceController@Student_period_Attendance_report')->name('Student_period_Attendance_report');
    Route::get('Student_period_Attendance_report/excel/{Subject}/{Class}/{excel}', 'StudentPeriodAttendanceController@Student_period_Attendance_report')->name('Student_period_Attendance_report_excel');

    // Student Attendance Summary
    Route::get('student-attendance-summary/index', 'StudentPeriodAttendanceController@attendanceSummary')->name('student-attendance-summary.index');
    Route::get('day_student-attendance-summary/index', 'StudentPeriodAttendanceController@staffattendanceSummary')->name('day_student_attendance_summary.index');
    Route::post('day_student-attendance-summary/index', 'StudentPeriodAttendanceController@staffattendanceSummary')->name('staff_attendance_course_get');
    Route::post('student-attendance-summary/get_courses', 'StudentPeriodAttendanceController@getCourses')->name('student-attendance-summary.get_courses');
    Route::post('student-attendance-summary/get_sections', 'StudentPeriodAttendanceController@getSections')->name('student-attendance-summary.get_sections');
    Route::post('student-attendance-summary/get_data', 'StudentPeriodAttendanceController@getData')->name('student-attendance-summary.get_data');
    Route::post('staffsummary_student-attendance-summary/get_data', 'StudentPeriodAttendanceController@staff_attendance')->name('staff_summary_student_attendance_summary.get_data');

    // Weekly Class Report
    Route::get('weekly-class-report/index', 'AttendanceReportController@weeklyReportIndex')->name('weekly-class-report.index');
    Route::post('weekly-class-report/weekly-report', 'AttendanceReportController@weeklyReport')->name('weekly-class-report.weekly-report');

    // Absentees Summary Report
    Route::get('absentees-summary-report/index', 'AttendanceReportController@absenteesReportIndex')->name('absentees-summary-report.index');
    Route::get('absentees-summary-report/pdf/{department}/{course}/{ay}/{sem_type}/{date}', 'AttendanceReportController@absenteesReportPDF')->name('absentees-summary-report.pdf');
    Route::post('absentees-summary-report/summary-report', 'AttendanceReportController@absenteesReport')->name('absentees-summary-report.summary-report');

    //ExamResult PDF ClassWise Report
    Route::get('Exam-classWise-summary-report/pdf/{Ay}/{Ex}/{Sem}/{Course}/{Year}/{Sec}', 'CatExamAttendanceSummaryController@Exam_classWise_ReportPDF')->name('Ex_classWise-summary-report.pdf');

    // Exam Create
    Route::get('Exam-time-table.index', 'ExamTimetableCreationController@index')->name('Exam-time-table.index');
    Route::get('Exam-Attendance/attendance', 'ExamTimetableCreationController@attendance')->name('Exam-Attendance.attendance');
    // Route::get('Exam-time-table.store', 'ExamTimetableCreationController@store')->name('Exam-time-table.store');
    Route::get('examTimetable.create', 'ExamTimetableCreationController@create')->name('examTimetable.create');
    Route::get('examTimetable/show/{id}', 'ExamTimetableCreationController@view')->name('examTimetable.show');
    Route::post('examTimetable.search', 'ExamTimetableCreationController@search')->name('examTimetable.search');
    Route::post('examTimetable.Subject_get', 'ExamTimetableCreationController@Subject_get')->name('examTimetable.Subject_get');
    Route::post('examTimetable.find', 'ExamTimetableCreationController@find')->name('examTimetable.find');
    Route::post('examTimetable.store', 'ExamTimetableCreationController@store')->name('examTimetable.store');
    Route::post('examTimetable.update/{ExamTimetableCreation}', 'ExamTimetableCreationController@update')->name('examTimetable.update');
    Route::post('examTimetable.massDestroy', 'ExamTimetableCreationController@massDestroy')->name('examTimetable.massDestroy');
    Route::get('examTimetable.edit/{id}', 'ExamTimetableCreationController@edit')->name('examTimetable.edit');
    Route::get('examTimetable.view/{id}', 'ExamTimetableCreationController@show')->name('examTimetable.view');
    Route::DELETE('examTimetable.destroy/{ExamTimetableCreation}', 'ExamTimetableCreationController@destroy')->name('examTimetable.destroy');
    // Route::put('examTimetable.update/{ExamTimetableCreation}', 'ExamTimetableCreationController@update')->name('examTimetable.update');
    // Route::resource('examTimetable.destroy/{ExamTimetableCreation}', 'ExamTimetableCreationController');
    Route::post('Exam-Timetable/Check', 'ExamTimetableCreationController@Check')->name('examTimetable.Check');
    Route::post('Exam-Timetable/examTimetable.section_exam_name_get', 'ExamTimetableCreationController@subject_get2')->name('examTimetable.section_exam_name_get');
    // Route::post('Exam-Timetable/Subject_get', 'ExamTimetableCreationController@filter_get')->name('examTimetable.filter_get');

    //Student personal Attendence
    Route::get('student-personal-attendance/report', 'StudentPersonalAttController@report')->name('student-personal-attendance.report');

    //Student Reports
    Route::get('Attendence-Details/index', 'student_attendence_report@index')->name('Attendence-Details.index');
    Route::post('Attendence-Details/search', 'student_attendence_report@search')->name('Attendence-Details.search');
    Route::post('Attendence-Details/studentGet', 'student_attendence_report@studentGet')->name('Attendence-Details.studentGet');
    Route::post('Attendence-Details/tableShow', 'student_attendence_report@tableShow')->name('Attendence-Details.tableShow');

    //Batch Wise student strenth report
    Route::get('Batch-Wise-Strenth/index', 'Batch_Wise_StrenthController@index')->name('batch_wise_strenth.index');
    // Route::post('Batch-Wise-Strenth/get', 'Batch_Wise_StrenthController@get')->name('batch_wise_strenth.get');
    // Route::post('Attendence-Details/search', 'student_attendence_report@search')->name('Attendence-Details.search');
    // Route::post('Attendence-Details/studentGet', 'student_attendence_report@studentGet')->name('Attendence-Details.studentGet');
    // Route::post('Attendence-Details/tableShow', 'student_attendence_report@tableShow')->name('Attendence-Details.tableShow');

    // Student Mandatory Details
    Route::get('student-mandatory-details/index', 'StudentDetailsController@index')->name('student-mandatory-details.index');
    Route::get('student-mandatory-details/generate/{batch}/{ay}/{course}/{semester}', 'StudentDetailsController@generate')->name('student-mandatory-details.generate');

    // Edge
    Route::get('staff-edge/index', 'EdgeController@staff')->name('staff-edge.index');
    Route::get('staff-edge-hr', 'EdgeController@staff_hr')->name('staff-edge-hr');
    Route::get('student-edge/index', 'EdgeController@student')->name('student-edge.index');
    Route::post('staff-edge', 'EdgeController@staff_edge')->name('staff-edge');
    Route::post('staff-edge/geter', 'EdgeController@staff_geter')->name('staff-edge.geter');
    Route::post('student-edge', 'EdgeController@student_edge')->name('student-edge');
    Route::post('student-edge/geter', 'EdgeController@student_geter')->name('student-edge.geter');
    // Route::get('get-staff-edge/{staff}','EdgeController@checker')->name('get-staff-edge');

    // Student
    Route::delete('students/destroy', 'StudentController@massDestroy')->name('students.massDestroy');
    Route::post('students/parse-csv-import', 'StudentController@parseCsvImport')->name('students.parseCsvImport');
    Route::post('students/process-csv-import', 'StudentController@processCsvImport')->name('students.processCsvImport');
    Route::get('students/{id}/Profile-view', 'StudentController@show')->name('students.Profile-view');
    Route::get('students/{id}/Profile-edit', 'StudentController@first_edit')->name('students.Profile-edit');
    Route::post('students/search', 'StudentController@search')->name('students.search');
    Route::resource('students', 'StudentController');
    Route::resource('students-edge', 'StudentController');

    // Removed Student
    Route::get('removed-students/index', 'StudentController@removedStudents')->name('removed-students.index');

    // Education Type
    Route::get('education-types', 'EducationTypeController@index')->name('education-types.index');
    Route::post('education-types/view', 'EducationTypeController@view')->name('education-types.view');
    Route::post('education-types/edit', 'EducationTypeController@edit')->name('education-types.edit');
    Route::post('education-types/store', 'EducationTypeController@store')->name('education-types.store');
    Route::post('education-types/delete', 'EducationTypeController@destroy')->name('education-types.delete');
    Route::delete('education-types/destroy', 'EducationTypeController@massDestroy')->name('education-types.massDestroy');


    //  Lab
    Route::delete('tool-lab/destroy', 'LabController@massDestroy')->name('tool-lab.massDestroy');
    Route::resource('tool-lab', 'LabController');

    // Scholarship
    Route::get('scholarships/index', 'ScholarshipController@index')->name('scholarships.index');
    Route::post('scholarships/view', 'ScholarshipController@view')->name('scholarships.view');
    Route::post('scholarships/edit', 'ScholarshipController@edit')->name('scholarships.edit');
    Route::post('scholarships/store', 'ScholarshipController@store')->name('scholarships.store');
    Route::post('scholarships/delete', 'ScholarshipController@destroy')->name('scholarships.delete');
    Route::delete('scholarships/destroy', 'ScholarshipController@massDestroy')->name('scholarships.massDestroy');

    // Fee Management

    // Route::get('fee-structure/structureIndex', 'FeeController@structureIndex')->name('fee-structure.structureIndex');
    // Route::get('fee-structure/create', 'FeeController@structureCreate')->name('fee-structure.create');
    // Route::get('fee-structure/show/{id}', 'FeeController@structureShow')->name('fee-structure.show');
    // Route::get('fee-structure/edit/{id}', 'FeeController@structureEdit')->name('fee-structure.edit');
    // Route::post('fee-structure/check', 'FeeController@structureCheck')->name('fee-structure.check');
    // Route::post('fee-structure/index', 'FeeController@index')->name('fee-structure.index');
    // Route::post('fee-structure/search', 'FeeController@structureSearch')->name('fee-structure.search');
    // Route::post('fee-structure/store', 'FeeController@structureStore')->name('fee-structure.store');
    // Route::post('fee-structure/update', 'FeeController@structureUpdate')->name('fee-structure.update');
    // Route::post('fee-structure/delete', 'FeeController@structureDelete')->name('fee-structure.delete');

    Route::get('fee-structure', 'FeeStructureController@index')->name('fee-structure.index');
    Route::post('fee-structure/get-course', 'FeeStructureController@getCourse')->name('fee-structure.get-course');
    // Route::post('fee-structure/fee-cycle', 'FeeStructureController@feecycle')->name('fee-structure.fee-cycle');
    Route::post('fee-structure/view', 'FeeStructureController@view')->name('fee-structure.view');
    Route::post('fee-structure/edit', 'FeeStructureController@edit')->name('fee-structure.edit');
    Route::post('fee-structure/store', 'FeeStructureController@store')->name('fee-structure.store');
    Route::post('fee-structure/delete', 'FeeStructureController@destroy')->name('fee-structure.delete');
    Route::post('fee-structure/generate-fee', 'FeeStructureController@generateFee')->name('fee-structure.generate-fee');
    Route::post('fee-structure/publish-fee', 'FeeStructureController@publishFee')->name('fee-structure.publish-fee');
    Route::delete('fee-structure/destroy', 'FeeStructureController@massDestroy')->name('fee-structure.massDestroy');
    Route::post('fee-components/getfeecomponents', 'FeeStructureController@getfeecomponents')->name('fee-structure.getfeecomponents');

    //Fee Collection
    Route::get('fee-collection', 'FeeCollectionController@index')->name('fee-collection.index');
    Route::post('student_rollnumber', 'FeeCollectionController@fetch_detils')->name('student-rollnumber.geter');
    Route::post('fee_payment', 'FeeCollectionController@fee_payment')->name('fee_payment');
    Route::post('fee_history', 'FeeCollectionController@fee_history')->name('fee_history');
    Route::post('fee_delete', 'FeeCollectionController@fee_delete')->name('fee_delete');
    Route::get('generate-pdf', 'FeeCollectionController@generatePDF')->name('generate-pdf');
    Route::post('student_alldetails', 'FeeCollectionController@getStudentData')->name('student-details.alldetails');


    //Fee ScholarShip
    Route::get('fee-scholarship', 'FeeScholarshipController@index')->name('feeScholarship.index');
    Route::post('fee-scholarship/geter', 'FeeScholarshipController@getScholarship')->name('fee-scholarship.getter');
    Route::post('fee-scholarship/store', 'FeeScholarshipController@store')->name('fee-scholarship.store');
    Route::post('fee-scholarship/view', 'FeeScholarshipController@view')->name('fee-scholarship.view');
    Route::post('fee-scholarship/edit', 'FeeScholarshipController@edit')->name('fee-scholarship.edit');
    Route::post('fee-scholarship/delete', 'FeeScholarshipController@destroy')->name('fee-scholarship.delete');
    Route::post('fee-scholarship/massdetroy', 'FeeScholarshipController@massDestroy')->name('fee-scholarship.massDestroy');





    // Fee Data Import
    Route::get('fee-data-import', 'FeeDataController@index')->name('fee-data-import.index');
    Route::post('fee-data-import/paid', 'FeeDataController@parseCsvImportPaid')->name('fee-data-import.paid');
    Route::post('fee-data-import/deductable', 'FeeDataController@parseCsvImportDeduct')->name('fee-data-import.deductable');
    Route::post('fee-data-import/import-paid', 'FeeDataController@processCsvImportPaid')->name('fee-data-import.import-paid');
    Route::post('fee-data-import/import-deduct', 'FeeDataController@processCsvImportDeduct')->name('fee-data-import.import-deduct');
    Route::post('academic-fee/parse-csv-import', 'FeeDataController@parseCsvImport')->name('academic-fee.parseCsvImport');
    Route::post('academic-fees/process-csv-import', 'FeeDataController@processCsvImport')->name('academic-fees.processCsvImport');

    // Fee Payment
    Route::get('fee-payment/collectIndex', 'FeePaymentController@collectIndex')->name('fee-payment.collectIndex');
    Route::get('fee-payment/show/{id}', 'FeePaymentController@collectShow')->name('fee-payment.show');
    Route::get('fee-payment/pdf/{id}', 'FeePaymentController@collectPDF')->name('fee-payment.pdf');
    Route::post('fee-payment/collectShow', 'FeePaymentController@collectShow')->name('fee-payment.collectShow');
    Route::post('fee-payment/getFee', 'FeePaymentController@getFee')->name('fee-payment.getFee');
    Route::post('fee-payment/store', 'FeePaymentController@collectStore')->name('fee-payment.store');
    Route::get('fee-payment/payment-history/{year}/{id}/{user_name_id}', 'FeePaymentController@paymentHistory')->name('fee-payment.payment-history');

    // Fee Reports
    Route::get('fee-details', 'FeeReportController@feeDetails')->name('fee-details.index');
    Route::post('fee-details/get-data', 'FeeReportController@getData')->name('fee-details.get-data');
    Route::get('fee-details/get-data', 'FeeReportController@getDataForStudent')->name('fee-details.get-data');
    Route::get('fee/year-wise-report', 'FeeReportController@yearWiseRep')->name('fee.year-wise-report');
    Route::post('fee/year-wise-report/data', 'FeeReportController@yearWiseRepData')->name('fee.year-wise-report.data');
    Route::get('fee/show/{id}', 'FeePaymentController@collectShow')->name('fee.collectShow');




    Route::get('fee-summary-report', 'FeeReportController@summaryReport')->name('fee-summary-report.index');
    Route::post('fee-summary-report/get-data', 'FeeReportController@summaryData')->name('fee-summary-report.get-data');
    Route::get('fee-defaulters-report', 'FeeReportController@defaultersReport')->name('fee-defaulters-report.index');
    Route::post('fee-defaulters-report/get-data', 'FeeReportController@defaultersData')->name('fee-defaulters-report.get-data');
    Route::get('fee-scholarship-report', 'FeeReportController@scholarshipReport')->name('fee-scholarship-report.index');
    Route::get('fee-category-report', 'FeeReportController@categoryReport')->name('fee-category-report.index');
    Route::post('fee-category-report/get-data', 'FeeReportController@categoryRepData')->name('fee-category-report.get-data');

    // Fee In Student Login
    Route::get('fee/stu_index', 'FeePaymentController@StudentIndex')->name('fee.stu_index');

    // Subject
    // Route::post('subject-masters/parse-csv-import', 'SubjectController@parseCsvImport')->name('subject-masters.parseCsvImport');
    // Route::post('subject-masters/process-csv-import', 'SubjectController@processCsvImport')->name('subject-masters.processCsvImport');
    // Route::delete('subjects/destroy', 'SubjectController@massDestroy')->name('subjects.massDestroy');
    // Route::get('subjects', 'SubjectController@index')->name('subjects.index');
    // Route::get('subjects/store', 'SubjectController@store')->name('subjects.store');
    // Route::get('subjects/view', 'SubjectController@view')->name('subjects.view');
    // Route::delete('subjects/delete', 'SubjectController@delete')->name('subjects.delete');
    // Route::get('subjects/edit', 'SubjectController@edit')->name('subjects.edit');

    Route::post('subject-masters/process-csv-import', 'SubjectController@processCsvImport')->name('subject-masters.processCsvImport');
    Route::post('subject-masters/parse-csv-import', 'SubjectController@parseCsvImport')->name('subject-masters.parseCsvImport');
    Route::get('subjects', 'SubjectController@index')->name('subjects.index');
    Route::post('subjects/view', 'SubjectController@view')->name('subjects.view');
    Route::post('subjects/show', 'SubjectController@show')->name('subjects.show');
    Route::get('subjects/create', 'SubjectController@create')->name('subjects.create');
    Route::post('subjects/edit', 'SubjectController@edit')->name('subjects.edit');
    Route::post('subjects/store', 'SubjectController@store')->name('subjects.store');
    Route::post('subjects/delete', 'SubjectController@destroy')->name('subjects.delete');
    Route::post('subjects/destroy', 'SubjectController@destroy')->name('subjects.destroy');
    Route::post('subjects/search', 'SubjectController@search')->name('subjects.search');
    Route::post('subjects/get_course', 'SubjectController@get_course')->name('subjects.get_course');
    Route::delete('subjects/destroy', 'SubjectController@massDestroy')->name('subjects.massDestroy');
    Route::post('subjects/statusUpdate', 'SubjectController@statusUpdate')->name('subjects.statusUpdate');
    Route::post('subjects/get_sub_categories', 'SubjectController@get_sub_categories')->name('subjects.get_sub_categories');

    // Route::resource('subjects', 'SubjectController');

    // Honors Degree
    Route::get('honor-subjects', 'SubjectController@honorsSubject')->name('honor-subjects.index');
    Route::get('honor-subjects-report', 'SubjectController@honorsSubjectReport')->name('honor-subjects-report.index');
    Route::post('honor-subjects/search', 'SubjectController@honorSearch')->name('honor-subjects.search');
    Route::post('honor-subjects-report/search', 'SubjectController@honorReportSearch')->name('honor-subjects-report.search');
    Route::get('honor-subjects/show/{class}', 'SubjectController@honorStudentsList')->name('honor-subjects.show');

    //Exam attendance
    Route::get('Exam-Attendance/AttendanceEnter/{id}/{recordId}', 'ExamTimetableCreationController@attendanceEnter')->name('exam_attendance.attendanceEnter');
    Route::get('Exam-Attendance/ViewAttendance/{id}/{recordId}', 'ExamTimetableCreationController@viewattendance')->name('exam_attendance.viewattendance');
    Route::get('Exam-Attendance/editattendance/{id}/{recordId}', 'ExamTimetableCreationController@editattendance')->name('exam_attendance.editattendance');
    Route::post('Exam-Attendance/attendenceUpdate', 'ExamTimetableCreationController@attendenceUpdate')->name('exam_attendance.attendenceUpdate');
    Route::post('Exam-Attendance/Attendence-Store', 'ExamTimetableCreationController@attendencestore')->name('examAttendance.attendencestore');

    //Exam Mark
    Route::get('Exam-Mark-master/index', 'ExamMarkcontroller@index')->name('Exam-Mark.index');
    Route::post('Exam-Mark/find', 'ExamMarkcontroller@find')->name('Exam-Mark.find');
    Route::post('Exam-Mark/toggle_status', 'ExamMarkcontroller@toggle_status')->name('Exam-Mark.toggle_status');
    Route::get('Exam-Mark/staff', 'ExamMarkcontroller@staff')->name('Exam-Mark.staff');
    // Route::get('Exam-Mark/index', 'StaffSubjectsController@index')->name('staff-subjects.index');
    Route::get('Exam-Mark/Mark/{id}/{recordId}', 'ExamMarkcontroller@MarkEnter')->name('Exam-Mark.markEnter');
    Route::post('Exam-Mark/Store', 'ExamMarkcontroller@MarkStore')->name('Exam-Mark.markStore');
    Route::get('Exam-Mark/view/{id}/{recordId}', 'ExamMarkcontroller@markview')->name('Exam-Mark.markview');
    Route::get('Exam-Mark/Mark-Edit/{id}/{recordId}', 'ExamMarkcontroller@editMark')->name('Exam-Mark.editMark');
    Route::post('Exam-Mark/Mark-Edit_request', 'ExamMarkcontroller@editMark_request')->name('cat_exam_edit_request');
    Route::post('Exam-Mark/Mark-Status-Update', 'ExamMarkcontroller@verifiedStatus')->name('verifiedStatus');
    Route::post('examattendance-data/parse-csv-import', 'ExamMarkcontroller@parseCsvImport')->name('examattendance-data.parseCsvImport');
    Route::post('examattendance-datas/process-csv-import', 'ExamMarkcontroller@processCsvImport')->name('examattendance-datas.processCsvImport');

    Route::get('Exam-Mark-Result/index', 'ExamMarkcontroller@indexStaff')->name('Exam-Mark-Result.index');
    Route::get('Exam-Mark-Result/view/{id}/{recordId}', 'ExamMarkcontroller@resultview')->name('Exam-Mark-Result.resultview');
    Route::get('Exam-result-StaffWise-report/pdf/{classId}/{subjectId}/{pdf}', 'ExamMarkcontroller@resultview')->name('Exam-result-StaffWise-report');
    Route::post('Exam-Mark-Result/get-past-records', 'ExamMarkcontroller@getPastRecords')->name('Exam-Mark-Result.get-past-records');

    // Subject Allotment
    Route::post('subject-allotments/parse-csv-import', 'SubjectAllotmentController@parseCsvImport')->name('subject-allotments.parseCsvImport');
    Route::post('subject-allotments/process-csv-import', 'SubjectAllotmentController@processCsvImport')->name('subject-allotments.processCsvImport');
    Route::get('subject-allotment/{regulation}/{department}/{course}/{academic_year}/{semester}/{semester_type}', 'SubjectAllotmentController@show');
    Route::get('subject-allotment/{regulation}/{department}/{course}/{academic_year}/{semester}/{semester_type}/edit', 'SubjectAllotmentController@edit');
    Route::post('subject-allotment/updater', 'SubjectAllotmentController@updater')->name('subject-allotment.updater');
    Route::resource('subject-allotment', 'SubjectAllotmentController');
    Route::post('subject-allotment/check', 'SubjectAllotmentController@check')->name('subject-allotment.check');
    // Route::post('subject-allotment/get-subjects', 'SubjectAllotmentController@getSubjects')->name('subject-allotment.get-subjects');
    Route::post('subject-allotment/get_subjects', 'SubjectAllotmentController@get_subjects')->name('subject-allotment.get_subjects');
    Route::post('subject-allotment/get-honor-subjects', 'SubjectAllotmentController@getHonorSubjects')->name('subject-allotment.get-honor-subjects');
    Route::post('subject-allotment/save', 'SubjectAllotmentController@save')->name('subject-allotment.save');
    Route::post('subject-allotment/search', 'SubjectAllotmentController@search')->name('subject-allotment.search');
    Route::post('subject-allotment/delete', 'SubjectAllotmentController@destroy')->name('subject-allotment.delete');

    // Mediumof Studied
    Route::get('mediumof-studieds', 'MediumofStudiedController@index')->name('mediumof-studieds.index');
    Route::post('mediumof-studieds/view', 'MediumofStudiedController@view')->name('mediumof-studieds.view');
    Route::post('mediumof-studieds/edit', 'MediumofStudiedController@edit')->name('mediumof-studieds.edit');
    Route::post('mediumof-studieds/store', 'MediumofStudiedController@store')->name('mediumof-studieds.store');
    Route::post('mediumof-studieds/delete', 'MediumofStudiedController@destroy')->name('mediumof-studieds.delete');
    Route::delete('mediumof-studieds/destroy', 'MediumofStudiedController@massDestroy')->name('mediumof-studieds.massDestroy');


    // Address
    Route::delete('addresses/destroy', 'AddressController@massDestroy')->name('addresses.massDestroy');
    Route::post('addresses/parse-csv-import', 'AddressController@parseCsvImport')->name('addresses.parseCsvImport');
    Route::post('addresses/process-csv-import', 'AddressController@processCsvImport')->name('addresses.processCsvImport');
    Route::resource('addresses', 'AddressController');
    Route::get('addresses/stu_index/{user_name_id}/{name}', 'AddressController@stu_index')->name('addresses.stu_index');
    Route::get('addresses/staff_index/{user_name_id}/{name}', 'AddressController@staff_index')->name('addresses.staff_index');
    Route::post('addresses/stu_update', 'AddressController@stu_update')->name('addresses.stu_update');
    Route::post('addresses/staff_updater', 'AddressController@staff_index')->name('addresses.staff_updater');
    Route::post('addresses/stu_updater', 'AddressController@stu_index')->name('addresses.stu_updater');
    Route::post('addresses/staff_update', 'AddressController@staff_update')->name('addresses.staff_update');

    // Parent Details
    Route::delete('parent-details/destroy', 'ParentDetailsController@massDestroy')->name('parent-details.massDestroy');
    Route::post('parent-details/parse-csv-import', 'ParentDetailsController@parseCsvImport')->name('parent-details.parseCsvImport');
    Route::post('parent-details/process-csv-import', 'ParentDetailsController@processCsvImport')->name('parent-details.processCsvImport');
    Route::resource('parent-details', 'ParentDetailsController');
    Route::get('parent-details/stu_index/{user_name_id}/{name}', 'ParentDetailsController@stu_index')->name('parent-details.stu_index');
    // Route::get('parent-details/stu_index/{user_name_id}/{name}', 'ParentDetailsController@stu_index')->name('parent-details.stu_index');
    Route::post('parent-details/stu_update', 'ParentDetailsController@stu_update')->name('parent-details.stu_update');

    // Bank Account Details
    Route::delete('bank-account-details/destroy', 'BankAccountDetailsController@massDestroy')->name('bank-account-details.massDestroy');
    Route::post('bank-account-details/parse-csv-import', 'BankAccountDetailsController@parseCsvImport')->name('bank-account-details.parseCsvImport');
    Route::post('bank-account-details/process-csv-import', 'BankAccountDetailsController@processCsvImport')->name('bank-account-details.processCsvImport');
    Route::resource('bank-account-details', 'BankAccountDetailsController');
    Route::get('bank-account-details/staff_index/{user_name_id}/{name}', 'BankAccountDetailsController@staff_index')->name('bank-account-details.staff_index');
    Route::post('bank-account-details/staff_updater', 'BankAccountDetailsController@staff_index')->name('bank-account-details.staff_updater');
    Route::post('bank-account-details/staff_update', 'BankAccountDetailsController@staff_update')->name('bank-account-details.staff_update');

    // Professional Activities
    Route::resource('professional_activities', 'ProfessionalActivitiesController');
    Route::get('professional_activities/stu_index/{user_name_id}/{name}', 'ProfessionalActivitiesController@stu_index')->name('professional_activities.stu_index');
    Route::post('professional_activities/stu_update', 'ProfessionalActivitiesController@stu_update')->name('professional_activities.stu_update');
    Route::post('professional_activities/stu_updater', 'ProfessionalActivitiesController@stu_index')->name('professional_activities.stu_updater');

    // Experience Details
    Route::delete('experience-details/destroy', 'ExperienceDetailsController@massDestroy')->name('experience-details.massDestroy');
    Route::post('experience-details/parse-csv-import', 'ExperienceDetailsController@parseCsvImport')->name('experience-details.parseCsvImport');
    Route::post('experience-details/process-csv-import', 'ExperienceDetailsController@processCsvImport')->name('experience-details.processCsvImport');
    Route::resource('experience-details', 'ExperienceDetailsController');
    Route::get('experience-details/staff_index/{user_name_id}/{name}', 'ExperienceDetailsController@staff_index')->name('experience-details.staff_index');
    Route::post('experience-details/staff_updater', 'ExperienceDetailsController@staff_index')->name('experience-details.staff_updater');
    Route::post('experience-details/staff_update', 'ExperienceDetailsController@staff_update')->name('experience-details.staff_update');

    // Promotion Details
    Route::delete('promotion-details/destroy', 'PromotionDetailsController@massDestroy')->name('promotion-details.massDestroy');
    Route::resource('promotion-details', 'PromotionDetailsController');
    Route::get('promotion-details/staff_index/{user_name_id}/{name}', 'PromotionDetailsController@staff_index')->name('promotion-details.staff_index');
    Route::post('promotion-details/staff_updater', 'PromotionDetailsController@staff_index')->name('promotion-details.staff_updater');
    Route::post('promotion-details/staff_update', 'PromotionDetailsController@staff_update')->name('promotion-details.staff_update');

    // Teaching Staff
    Route::get('teaching-staff/{id}/Profile-edit', 'TeachingStaffController@edit')->name('teaching-staff.Profile-edit');
    Route::get('teaching-staff/{id}/Profile-view', 'TeachingStaffController@show')->name('teaching-staff.Profile-view');
    Route::get('teaching-staffs/view', 'TeachingStaffController@show')->name('teaching-staffs.view');
    Route::delete('teaching-staffs/destroy', 'TeachingStaffController@massDestroy')->name('teaching-staffs.massDestroy');
    Route::post('teaching-staffs/parse-csv-import', 'TeachingStaffController@parseCsvImport')->name('teaching-staffs.parseCsvImport');
    Route::post('teaching-staffs/process-csv-import', 'TeachingStaffController@processCsvImport')->name('teaching-staffs.processCsvImport');
    Route::resource('teaching-staffs', 'TeachingStaffController');
    Route::resource('teaching-staff-edge', 'TeachingStaffController');
    Route::post('past_leave_apply_access', 'TeachingStaffController@past_leave_apply_access')->name('past_leave_apply_access');
    Route::post('Past_Leave_Access_check', 'TeachingStaffController@Past_Leave_Access_check')->name('Past_Leave_Access_check');

    // Route::get('/controllerMethod', 'TeachingStaffController@myControllerMethod')->name('controllerMethod.data');
    // Route::post('/updating', 'TeachingStaffController@updating')->name('updating.data');
    // Route::post('/editprofile', 'PersonalDetailsController@editpersonal')->name('editprofile.data');
    // Route::post('/personelDetailsedit', 'PersonalDetailsController@personelDetailsedit')->name('personelDetailsedit.data');

    // Non Teaching Staff
    Route::get('non-teaching-staff/{id}/Profile-edit', 'NonTeachingStaffController@edit')->name('non-teaching-staffs.Profile-edit');
    Route::get('non-teaching-staff/{id}/Profile-view', 'NonTeachingStaffController@show')->name('non-teaching-staff.Profile-view');
    Route::delete('non-teaching-staffs/destroy', 'NonTeachingStaffController@massDestroy')->name('non-teaching-staffs.massDestroy');
    Route::post('non-teaching-staffs/parse-csv-import', 'NonTeachingStaffController@parseCsvImport')->name('non-teaching-staffs.parseCsvImport');
    Route::post('non-teaching-staffs/process-csv-import', 'NonTeachingStaffController@processCsvImport')->name('non-teaching-staffs.processCsvImport');
    Route::resource('non-teaching-staffs', 'NonTeachingStaffController');
    Route::resource('non-teaching-staff-edge', 'NonTeachingStaffController');
    Route::post('past_leave_apply_Non_Teaching_access', 'NonTeachingStaffController@past_leave_apply_Non_Teaching_access')->name('past_leave_apply_Non_Teaching_access');
    Route::post('Past_Leave_Non_Teaching_Access__check', 'NonTeachingStaffController@Past_Leave_Non_Teaching_Access__check')->name('Past_Leave_Non_Teaching_Access__check');

    // R & D Staff
    Route::get('rd-staffs/index', 'TeachingStaffController@rdStaffIndex')->name('rd-staffs.index');
    Route::post('rd-staffs/store', 'TeachingStaffController@rdStaffStore')->name('rd-staffs.store');
    Route::post('rd-staffs/remove', 'TeachingStaffController@rdStaffRemove')->name('rd-staffs.remove');

    // Teaching Type
    Route::delete('teaching-types/destroy', 'TeachingTypeController@massDestroy')->name('teaching-types.massDestroy');
    Route::resource('teaching-types', 'TeachingTypeController');

    // Examstaff
    Route::delete('examstaffs/destroy', 'ExamstaffController@massDestroy')->name('examstaffs.massDestroy');
    Route::resource('examstaffs', 'ExamstaffController');

    // Event
    Route::get('events/index', 'EventController@index')->name('events.index');
    Route::post('events/view', 'EventController@view')->name('events.view');
    Route::get('events/create', 'EventController@create')->name('events.create');
    Route::post('events/edit', 'EventController@edit')->name('events.edit');
    Route::post('events/store', 'EventController@store')->name('events.store');
    Route::post('events/delete', 'EventController@destroy')->name('events.delete');
    Route::delete('events/destroy', 'EventController@massDestroy')->name('events.massDestroy');

    // Event Organized
    Route::resource('event-organized', 'EventOrganizedController');
    Route::get('event-organized/staff_index/{user_name_id}/{name}', 'EventOrganizedController@staff_index')->name('event-organized.staff_index');
    Route::post('event-organized/staff_update', 'EventOrganizedController@staff_update')->name('event-organized.staff_update');
    Route::post('event-organized/staff_updater', 'EventOrganizedController@staff_index')->name('event-organized.staff_updater');

    // Event Participation
    Route::resource('event-participation', 'EventParticipationController');
    Route::get('event-participation/staff_index/{user_name_id}/{name}', 'EventParticipationController@staff_index')->name('event-participation.staff_index');
    Route::post('event-participation/staff_update', 'EventParticipationController@staff_update')->name('event-participation.staff_update');
    Route::post('event-participation/staff_updater', 'EventParticipationController@staff_index')->name('event-participation.staff_updater');

    // Add Conference
    Route::delete('add-conferences/destroy', 'AddConferenceController@massDestroy')->name('add-conferences.massDestroy');
    Route::post('add-conferences/parse-csv-import', 'AddConferenceController@parseCsvImport')->name('add-conferences.parseCsvImport');
    Route::post('add-conferences/process-csv-import', 'AddConferenceController@processCsvImport')->name('add-conferences.processCsvImport');
    Route::resource('add-conferences', 'AddConferenceController');
    Route::get('add-conferences/staff_index/{user_name_id}/{name}', 'AddConferenceController@staff_index')->name('add-conferences.staff_index');
    Route::get('add-conferences/stu_index/{user_name_id}/{name}', 'AddConferenceController@stu_index')->name('add-conferences.stu_index');
    Route::post('add-conferences/staff_updater', 'AddConferenceController@staff_index')->name('add-conferences.staff_updater');
    Route::post('add-conferences/stu_updater', 'AddConferenceController@stu_index')->name('add-conferences.stu_updater');
    Route::post('add-conferences/staff_update', 'AddConferenceController@staff_update')->name('add-conferences.staff_update');
    Route::post('add-conferences/stu_update', 'AddConferenceController@stu_update')->name('add-conferences.stu_update');

    // Publications Details
    Route::get('staff-publications/staff_index/{user_name_id}/{name}', 'PublicationDetailController@staff_index')->name('staff-publications.staff_index');
    Route::post('staff-publications/staff_update', 'PublicationDetailController@staff_update')->name('staff-publications.staff_update');
    Route::post('staff-publications/staff_updater', 'PublicationDetailController@staff_index')->name('staff-publications.staff_updater');
    Route::resource('staff-publications', 'PublicationDetailController');

    //Permission Request

    Route::get('staff-permissionsreq/staff_index', 'PermissionrequestController@staff_index')->name('staff-permissionsreq.staff_index');
    Route::post('staff-permissionsreq/checkDate', 'PermissionrequestController@checkDate')->name('staff-permissionsreq.checkDate');
    Route::post('staff-permissionsreq/staff_update', 'PermissionrequestController@staff_update')->name('staff-permissionsreq.staff_update');
    Route::post('staff-permissionsreq/staff_updater', 'PermissionrequestController@staff_index')->name('staff-permissionsreq.staff_updater');
    Route::resource('staff-permissionsreq', 'PermissionrequestController');

    // Entrance Exams
    Route::delete('entrance-exams/destroy', 'EntranceExamsController@massDestroy')->name('entrance-exams.massDestroy');
    Route::post('entrance-exams/parse-csv-import', 'EntranceExamsController@parseCsvImport')->name('entrance-exams.parseCsvImport');
    Route::post('entrance-exams/process-csv-import', 'EntranceExamsController@processCsvImport')->name('entrance-exams.processCsvImport');
    Route::resource('entrance-exams', 'EntranceExamsController');
    Route::get('entrance-exams/staff_index/{user_name_id}/{name}', 'EntranceExamsController@staff_index')->name('entrance-exams.staff_index');
    Route::get('entrance-exams/stu_index/{user_name_id}/{name}', 'EntranceExamsController@stu_index')->name('entrance-exams.stu_index');
    Route::post('entrance-exams/staff_updater', 'EntranceExamsController@staff_index')->name('entrance-exams.staff_updater');
    Route::post('entrance-exams/staff_update', 'EntranceExamsController@staff_update')->name('entrance-exams.staff_update');

    // Guest Lecture
    Route::delete('guest-lectures/destroy', 'GuestLectureController@massDestroy')->name('guest-lectures.massDestroy');
    Route::post('guest-lectures/parse-csv-import', 'GuestLectureController@parseCsvImport')->name('guest-lectures.parseCsvImport');
    Route::post('guest-lectures/process-csv-import', 'GuestLectureController@processCsvImport')->name('guest-lectures.processCsvImport');
    Route::resource('guest-lectures', 'GuestLectureController');
    Route::get('guest-lectures/staff_index/{user_name_id}/{name}', 'GuestLectureController@staff_index')->name('guest-lectures.staff_index');
    Route::post('guest-lectures/staff_updater', 'GuestLectureController@staff_index')->name('guest-lectures.staff_updater');
    Route::post('guest-lectures/staff_update', 'GuestLectureController@staff_update')->name('guest-lectures.staff_update');

    // Industrial Training
    Route::delete('industrial-trainings/destroy', 'IndustrialTrainingController@massDestroy')->name('industrial-trainings.massDestroy');
    Route::post('industrial-trainings/parse-csv-import', 'IndustrialTrainingController@parseCsvImport')->name('industrial-trainings.parseCsvImport');
    Route::post('industrial-trainings/process-csv-import', 'IndustrialTrainingController@processCsvImport')->name('industrial-trainings.processCsvImport');
    Route::resource('industrial-trainings', 'IndustrialTrainingController');
    Route::get('industrial-trainings/staff_index/{user_name_id}/{name}', 'IndustrialTrainingController@staff_index')->name('industrial-trainings.staff_index');
    Route::get('industrial-trainings/stu_index/{user_name_id}/{name}', 'IndustrialTrainingController@stu_index')->name('industrial-trainings.stu_index');
    Route::post('industrial-trainings/staff_updater', 'IndustrialTrainingController@staff_index')->name('industrial-trainings.staff_updater');
    Route::post('industrial-trainings/stu_updater', 'IndustrialTrainingController@stu_index')->name('industrial-trainings.stu_updater');
    Route::post('industrial-trainings/staff_update', 'IndustrialTrainingController@staff_update')->name('industrial-trainings.staff_update');
    Route::post('industrial-trainings/stu_update', 'IndustrialTrainingController@stu_update')->name('industrial-trainings.stu_update');

    // Intern
    Route::delete('interns/destroy', 'InternController@massDestroy')->name('interns.massDestroy');
    Route::post('interns/parse-csv-import', 'InternController@parseCsvImport')->name('interns.parseCsvImport');
    Route::post('interns/process-csv-import', 'InternController@processCsvImport')->name('interns.processCsvImport');
    Route::resource('interns', 'InternController');
    Route::get('interns/staff_index/{user_name_id}/{name}', 'InternController@staff_index')->name('interns.staff_index');
    Route::get('interns/stu_index/{user_name_id}/{name}', 'InternController@stu_index')->name('interns.stu_index');
    Route::post('interns/staff_updater', 'InternController@staff_index')->name('interns.staff_updater');
    Route::post('interns/stu_updater', 'InternController@stu_index')->name('interns.stu_updater');
    Route::post('interns/staff_update', 'InternController@staff_update')->name('interns.staff_update');
    Route::post('interns/stu_update', 'InternController@stu_update')->name('interns.stu_update');

    // Industrial Experience
    Route::delete('industrial-experiences/destroy', 'IndustrialExperienceController@massDestroy')->name('industrial-experiences.massDestroy');
    Route::post('industrial-experiences/parse-csv-import', 'IndustrialExperienceController@parseCsvImport')->name('industrial-experiences.parseCsvImport');
    Route::post('industrial-experiences/process-csv-import', 'IndustrialExperienceController@processCsvImport')->name('industrial-experiences.processCsvImport');
    Route::resource('industrial-experiences', 'IndustrialExperienceController');
    Route::get('industrial-experiences/staff_index/{user_name_id}/{name}', 'IndustrialExperienceController@staff_index')->name('industrial-experiences.staff_index');
    Route::post('industrial-experiences/staff_updater', 'IndustrialExperienceController@staff_index')->name('industrial-experiences.staff_updater');
    Route::post('industrial-experiences/staff_update', 'IndustrialExperienceController@staff_update')->name('industrial-experiences.staff_update');

    // Iv
    Route::delete('ivs/destroy', 'IvController@massDestroy')->name('ivs.massDestroy');
    Route::resource('ivs', 'IvController');
    Route::get('ivs/staff_index/{user_name_id}/{name}', 'IvController@staff_index')->name('ivs.staff_index');
    Route::get('ivs/stu_index/{user_name_id}/{name}', 'IvController@stu_index')->name('ivs.stu_index');
    Route::post('ivs/staff_updater', 'IvController@staff_index')->name('ivs.staff_updater');
    Route::post('ivs/stu_updater', 'IvController@stu_index')->name('ivs.stu_updater');
    Route::post('ivs/staff_update', 'IvController@staff_update')->name('ivs.staff_update');
    Route::post('ivs/stu_update', 'IvController@stu_update')->name('ivs.stu_update');

    // Online Course
    Route::delete('online-courses/destroy', 'OnlineCourseController@massDestroy')->name('online-courses.massDestroy');
    Route::post('online-courses/parse-csv-import', 'OnlineCourseController@parseCsvImport')->name('online-courses.parseCsvImport');
    Route::post('online-courses/process-csv-import', 'OnlineCourseController@processCsvImport')->name('online-courses.processCsvImport');
    Route::resource('online-courses', 'OnlineCourseController');
    Route::get('online-courses/staff_index/{user_name_id}/{name}', 'OnlineCourseController@staff_index')->name('online-courses.staff_index');
    Route::post('online-courses/staff_updater', 'OnlineCourseController@staff_index')->name('online-courses.staff_updater');
    Route::post('online-courses/staff_update', 'OnlineCourseController@staff_update')->name('online-courses.staff_update');

    // Documents
    Route::delete('documents/destroy', 'DocumentsController@massDestroy')->name('documents.massDestroy');
    Route::post('documents/media', 'DocumentsController@storeMedia')->name('documents.storeMedia');
    Route::post('documents/ckmedia', 'DocumentsController@storeCKEditorImages')->name('documents.storeCKEditorImages');
    Route::post('documents/parse-csv-import', 'DocumentsController@parseCsvImport')->name('documents.parseCsvImport');
    Route::post('documents/process-csv-import', 'DocumentsController@processCsvImport')->name('documents.processCsvImport');
    Route::resource('documents', 'DocumentsController');
    Route::get('documents/staff_index/{user_name_id}/{name}', 'DocumentsController@staff_index')->name('documents.staff_index');
    Route::get('documents/stu_index/{user_name_id}/{name}', 'DocumentsController@stu_index')->name('documents.stu_index');
    Route::post('documents/staff_update', 'DocumentsController@staff_update')->name('documents.staff_update');
    Route::post('documents/stu_update', 'DocumentsController@stu_update')->name('documents.stu_update');
    Route::post('documents/newapprove', 'DocumentsController@newapprove')->name('documents.newapprove');

    // Seminar
    Route::delete('seminars/destroy', 'SeminarController@massDestroy')->name('seminars.massDestroy');
    Route::post('seminars/parse-csv-import', 'SeminarController@parseCsvImport')->name('seminars.parseCsvImport');
    Route::post('seminars/process-csv-import', 'SeminarController@processCsvImport')->name('seminars.processCsvImport');
    Route::resource('seminars', 'SeminarController');
    Route::get('seminars/staff_index/{user_name_id}/{name}', 'SeminarController@staff_index')->name('seminars.staff_index');
    Route::get('seminars/stu_index/{user_name_id}/{name}', 'SeminarController@stu_index')->name('seminars.stu_index');
    Route::post('seminars/staff_updater', 'SeminarController@staff_index')->name('seminars.staff_updater');
    Route::post('seminars/stu_updater', 'SeminarController@stu_index')->name('seminars.stu_updater');
    Route::post('seminars/staff_update', 'SeminarController@staff_update')->name('seminars.staff_update');
    Route::post('seminars/stu_update', 'SeminarController@stu_update')->name('seminars.stu_update');

    // Saboticals
    Route::delete('saboticals/destroy', 'SaboticalsController@massDestroy')->name('saboticals.massDestroy');
    Route::post('saboticals/parse-csv-import', 'SaboticalsController@parseCsvImport')->name('saboticals.parseCsvImport');
    Route::post('saboticals/process-csv-import', 'SaboticalsController@processCsvImport')->name('saboticals.processCsvImport');
    Route::resource('saboticals', 'SaboticalsController');
    Route::get('saboticals/staff_index/{user_name_id}/{name}', 'SaboticalsController@staff_index')->name('saboticals.staff_index');
    Route::post('saboticals/staff_updater', 'SaboticalsController@staff_index')->name('saboticals.staff_updater');
    Route::post('saboticals/staff_update', 'SaboticalsController@staff_update')->name('saboticals.staff_update');

    // Sponser
    Route::delete('sponsers/destroy', 'SponserController@massDestroy')->name('sponsers.massDestroy');
    Route::post('sponsers/parse-csv-import', 'SponserController@parseCsvImport')->name('sponsers.parseCsvImport');
    Route::post('sponsers/process-csv-import', 'SponserController@processCsvImport')->name('sponsers.processCsvImport');
    Route::resource('sponsers', 'SponserController');
    Route::get('sponsers/staff_index/{user_name_id}/{name}', 'SponserController@staff_index')->name('sponsers.staff_index');
    Route::post('sponsers/staff_updater', 'SponserController@staff_index')->name('sponsers.staff_updater');
    Route::post('sponsers/staff_update', 'SponserController@staff_update')->name('sponsers.staff_update');

    // Sttp
    Route::delete('sttps/destroy', 'SttpController@massDestroy')->name('sttps.massDestroy');
    Route::post('sttps/parse-csv-import', 'SttpController@parseCsvImport')->name('sttps.parseCsvImport');
    Route::post('sttps/process-csv-import', 'SttpController@processCsvImport')->name('sttps.processCsvImport');
    Route::resource('sttps', 'SttpController');
    Route::get('sttps/staff_index/{user_name_id}/{name}', 'SttpController@staff_index')->name('sttps.staff_index');
    Route::post('sttps/staff_updater', 'SttpController@staff_index')->name('sttps.staff_updater');
    Route::post('sttps/staff_update', 'SttpController@staff_update')->name('sttps.staff_update');

    // Workshop
    Route::delete('workshops/destroy', 'WorkshopController@massDestroy')->name('workshops.massDestroy');
    Route::post('workshops/parse-csv-import', 'WorkshopController@parseCsvImport')->name('workshops.parseCsvImport');
    Route::post('workshops/process-csv-import', 'WorkshopController@processCsvImport')->name('workshops.processCsvImport');
    Route::resource('workshops', 'WorkshopController');
    Route::get('workshops/staff_index/{user_name_id}/{name}', 'WorkshopController@staff_index')->name('workshops.staff_index');
    Route::post('workshops/staff_updater', 'WorkshopController@staff_index')->name('workshops.staff_updater');
    Route::post('workshops/staff_update', 'WorkshopController@staff_update')->name('workshops.staff_update');

    // Patents
    Route::delete('patents/destroy', 'PatentsController@massDestroy')->name('patents.massDestroy');
    Route::resource('patents', 'PatentsController');
    Route::get('patents/staff_index/{user_name_id}/{name}', 'PatentsController@staff_index')->name('patents.staff_index');
    Route::get('patents/stu_index/{user_name_id}/{name}', 'PatentsController@stu_index')->name('patents.stu_index');
    Route::post('patents/staff_updater', 'PatentsController@staff_index')->name('patents.staff_updater');
    Route::post('patents/stu_updater', 'PatentsController@stu_index')->name('patents.stu_updater');
    Route::post('patents/staff_update', 'PatentsController@staff_update')->name('patents.staff_update');
    Route::post('patents/stu_update', 'PatentsController@stu_update')->name('patents.stu_update');

    // Awards
    Route::delete('awards/destroy', 'AwardsController@massDestroy')->name('awards.massDestroy');
    Route::resource('awards', 'AwardsController');
    Route::get('awards/staff_index/{user_name_id}/{name}', 'AwardsController@staff_index')->name('awards.staff_index');
    Route::post('awards/staff_updater', 'AwardsController@staff_index')->name('awards.staff_updater');
    Route::post('awards/staff_update', 'AwardsController@staff_update')->name('awards.staff_update');

    //Staff Personal Attendence
    Route::get('Staff-Personal-Attendence', 'staff_personal_attendence@index')->name('Staff-Personal-Attendence.index');
    Route::post('Staff-Personal-Attendence/search', 'staff_personal_attendence@search')->name('Staff-Personal-Attendence.search');

    //Staff Relieving report
    Route::get('Staff-Relieving-Report', 'StaffRelievingreport@index')->name('Staff-Relieving-Report.index');
    Route::post('Staff-Relieving-Report/search', 'StaffRelievingreport@search')->name('Staff-Relieving-Report.search');

    // Salary Statement Auto Generation
    Route::get('salary-stmt-gen/{month}/{year}', 'EmployeeSalaryController@salary_stmt_gen')->name('salary-stmt-gen');

    // Asset Category
    Route::delete('asset-categories/destroy', 'AssetCategoryController@massDestroy')->name('asset-categories.massDestroy');
    Route::resource('asset-categories', 'AssetCategoryController');

    // Asset Location
    Route::delete('asset-locations/destroy', 'AssetLocationController@massDestroy')->name('asset-locations.massDestroy');
    Route::resource('asset-locations', 'AssetLocationController');

    // Asset Status
    Route::delete('asset-statuses/destroy', 'AssetStatusController@massDestroy')->name('asset-statuses.massDestroy');
    Route::resource('asset-statuses', 'AssetStatusController');

    // Asset
    Route::delete('assets/destroy', 'AssetController@massDestroy')->name('assets.massDestroy');
    Route::post('assets/media', 'AssetController@storeMedia')->name('assets.storeMedia');
    Route::post('assets/ckmedia', 'AssetController@storeCKEditorImages')->name('assets.storeCKEditorImages');
    Route::resource('assets', 'AssetController');

    // Assets History
    Route::resource('assets-histories', 'AssetsHistoryController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Task Status
    Route::delete('task-statuses/destroy', 'TaskStatusController@massDestroy')->name('task-statuses.massDestroy');
    Route::resource('task-statuses', 'TaskStatusController');

    // Task Tag
    Route::delete('task-tags/destroy', 'TaskTagController@massDestroy')->name('task-tags.massDestroy');
    Route::resource('task-tags', 'TaskTagController');

    // Task
    Route::delete('tasks/destroy', 'TaskController@massDestroy')->name('tasks.massDestroy');
    Route::post('tasks/media', 'TaskController@storeMedia')->name('tasks.storeMedia');
    Route::post('tasks/ckmedia', 'TaskController@storeCKEditorImages')->name('tasks.storeCKEditorImages');
    Route::post('tasks/get_users', 'TaskController@getUsers')->name('tasks.get_users');
    Route::resource('tasks', 'TaskController');

    // Tasks Calendar
    Route::resource('tasks-calendars', 'TasksCalendarController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Faq Category
    Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    Route::resource('faq-categories', 'FaqCategoryController');

    // Faq Question
    Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    Route::resource('faq-questions', 'FaqQuestionController');

    // Content Category
    Route::delete('content-categories/destroy', 'ContentCategoryController@massDestroy')->name('content-categories.massDestroy');
    Route::resource('content-categories', 'ContentCategoryController');

    // Content Tag
    Route::delete('content-tags/destroy', 'ContentTagController@massDestroy')->name('content-tags.massDestroy');
    Route::resource('content-tags', 'ContentTagController');

    // Content Page
    Route::delete('content-pages/destroy', 'ContentPageController@massDestroy')->name('content-pages.massDestroy');
    Route::post('content-pages/media', 'ContentPageController@storeMedia')->name('content-pages.storeMedia');
    Route::post('content-pages/ckmedia', 'ContentPageController@storeCKEditorImages')->name('content-pages.storeCKEditorImages');
    Route::resource('content-pages', 'ContentPageController');

    // Expense Category
    Route::delete('expense-categories/destroy', 'ExpenseCategoryController@massDestroy')->name('expense-categories.massDestroy');
    Route::resource('expense-categories', 'ExpenseCategoryController');

    // Income Category
    Route::delete('income-categories/destroy', 'IncomeCategoryController@massDestroy')->name('income-categories.massDestroy');
    Route::resource('income-categories', 'IncomeCategoryController');

    // Expense
    Route::delete('expenses/destroy', 'ExpenseController@massDestroy')->name('expenses.massDestroy');
    Route::resource('expenses', 'ExpenseController');

    // Income
    Route::delete('incomes/destroy', 'IncomeController@massDestroy')->name('incomes.massDestroy');
    Route::resource('incomes', 'IncomeController');

    // Expense Report
    Route::delete('expense-reports/destroy', 'ExpenseReportController@massDestroy')->name('expense-reports.massDestroy');
    Route::resource('expense-reports', 'ExpenseReportController');

    // Courses
    Route::delete('courses/destroy', 'CoursesController@massDestroy')->name('courses.massDestroy');
    Route::post('courses/media', 'CoursesController@storeMedia')->name('courses.storeMedia');
    Route::post('courses/ckmedia', 'CoursesController@storeCKEditorImages')->name('courses.storeCKEditorImages');
    Route::resource('courses', 'CoursesController');

    // Lessons
    Route::delete('lessons/destroy', 'LessonsController@massDestroy')->name('lessons.massDestroy');
    Route::post('lessons/media', 'LessonsController@storeMedia')->name('lessons.storeMedia');
    Route::post('lessons/ckmedia', 'LessonsController@storeCKEditorImages')->name('lessons.storeCKEditorImages');
    Route::resource('lessons', 'LessonsController');

    // Tests
    Route::delete('tests/destroy', 'TestsController@massDestroy')->name('tests.massDestroy');
    Route::resource('tests', 'TestsController');

    // Questions
    Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    Route::post('questions/media', 'QuestionsController@storeMedia')->name('questions.storeMedia');
    Route::post('questions/ckmedia', 'QuestionsController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    Route::resource('questions', 'QuestionsController');

    // Question Options
    Route::delete('question-options/destroy', 'QuestionOptionsController@massDestroy')->name('question-options.massDestroy');
    Route::resource('question-options', 'QuestionOptionsController');

    // Test Results
    Route::delete('test-results/destroy', 'TestResultsController@massDestroy')->name('test-results.massDestroy');
    Route::resource('test-results', 'TestResultsController');

    // Test Answers
    Route::delete('test-answers/destroy', 'TestAnswersController@massDestroy')->name('test-answers.massDestroy');
    Route::resource('test-answers', 'TestAnswersController');

    // Leave Type
    Route::get('leave-types', 'LeaveTypeController@index')->name('leave-types.index');
    Route::post('leave-types/view', 'LeaveTypeController@view')->name('leave-types.view');
    Route::post('leave-types/edit', 'LeaveTypeController@edit')->name('leave-types.edit');
    Route::post('leave-types/store', 'LeaveTypeController@store')->name('leave-types.store');
    Route::post('leave-types/delete', 'LeaveTypeController@destroy')->name('leave-types.delete');
    Route::delete('leave-types/destroy', 'LeaveTypeController@massDestroy')->name('leave-types.massDestroy');

    // Staff Daily Attendance
    Route::get('staff-daily-attendance/index', 'StaffAttendanceRegisterController@attendanceIndex')->name('staff-daily-attendance.index');
    Route::post('staff-daily-attendance/get-data', 'StaffAttendanceRegisterController@attendanceData')->name('staff-daily-attendance.get-data');

    // Staff Biometrics
    Route::delete('staff-biometrics/destroy', 'StaffBiometricController@massDestroy')->name('staff-biometrics.massDestroy');
    Route::post('staff-biometrics/parse-csv-import', 'StaffBiometricController@parseCsvImport')->name('staff-biometrics.parseCsvImport');
    Route::post('staff-biometrics/process-csv-import', 'StaffBiometricController@processCsvImport')->name('staff-biometrics.processCsvImport');
    Route::post('staff-biometrics/updater', 'StaffBiometricController@updater')->name('staff-biometrics.updater');
    Route::get('staff-biometrics/gen/{month}/{year}', 'StaffBiometricController@store')->name('staff-biometrics.gen');
    Route::resource('staff-biometrics', 'StaffBiometricController');
    Route::get('staff/balance', 'StaffBiometricController@balanceCl')->name('staff.balance');

    // Staff Attendance Report
    Route::get('staff-attend-register/index', 'StaffAttendanceRegisterController@index')->name('staff-attend-register.index');
    Route::post('staff-attend-register/search', 'StaffAttendanceRegisterController@search')->name('staff-attend-register.search');

    // permission  Report
    Route::get('permission-register/index', 'permissionRegister@index')->name('permission-register.index');
    Route::post('permission-register/search', 'permissionRegister@search')->name('permission-register.search');

    // PaySlip
    Route::get('payslips-pdf/{id}', 'paySlipcontroller@pdf');
    Route::post('payslip/store', 'paySlipcontroller@store')->name('payslip.store');
    Route::post('payslip/slip_generation', 'paySlipcontroller@slip_generation')->name('payslip.slip_generation');
    Route::get('payslip/bulk_pdf', 'paySlipcontroller@bulk_pdf')->name('payslip.bulk_pdf');
    Route::resource('PaySlip', 'paySlipcontroller');
    Route::get('payslip/index_rep', 'paySlipcontroller@index')->name('payslip.index_rep');
    Route::get('PaySlip/edit/{id}', 'paySlipcontroller@edit')->name('payslip.edit');

    // Staff Leave Reports
    Route::get('staff_leave_report/index', 'StaffLeaveReportController@index')->name('staff_leave_report.index');
    Route::post('staff_leave_report/index_rep', 'StaffLeaveReportController@index_rep')->name('staff_leave_report.index_rep');

    // Staff Leave Register
    Route::get('staff_leave_register/index', 'staff_leave_register@index')->name('staff_leave_register.index');
    Route::post('staff_leave_register/index_rep', 'staff_leave_register@index_rep')->name('staff_leave_register.index_rep');

    //Salary Statement
    Route::get('salary-statement/index', 'SalarystatementController@index')->name('salary-statement.index');
    Route::post('salary-statement/store', 'SalarystatementController@store')->name('salary-statement.store');
    Route::post('salary-statement/index_rep', 'SalarystatementController@index_rep')->name('salary-statement.index_rep');
    Route::post('salary-statement/get_report', 'SalarystatementController@get_report')->name('salary-statement.get_report');
    Route::get('salary-statement/salarystatement', 'SalarystatementController@salarystatement')->name('salary-statement.salarystatement');
    Route::resource('salary-statement', 'SalarystatementController');

    // Shift
    Route::any('Shift', 'shiftController@index')->name('Shift.index');
    Route::resource('Shift', 'shiftController');
    // Route::post('Shift/massDestroy', 'shiftController@massDestroy')->name('Shift.massDestroy');

    // Employee Salary
    Route::delete('employee-salary/destroy', 'EmployeeSalaryController@massDestroy')->name('employee-salary.massDestroy');
    Route::post('employee-salary/parse-csv-import', 'EmployeeSalaryController@parseCsvImport')->name('employee-salary.parseCsvImport');
    Route::post('employee-salary/process-csv-import', 'EmployeeSalaryController@processCsvImport')->name('employee-salary.processCsvImport');
    Route::post('employee-salary/search', 'EmployeeSalaryController@search')->name('employee-salary.search');
    Route::resource('employee-salary', 'EmployeeSalaryController');

    // Leave Staff Allocation
    Route::delete('leave-staff-allocations/destroy', 'LeaveStaffAllocationController@massDestroy')->name('leave-staff-allocations.massDestroy');
    Route::post('leave-staff-allocations/parse-csv-import', 'LeaveStaffAllocationController@parseCsvImport')->name('leave-staff-allocations.parseCsvImport');
    Route::post('leave-staff-allocations/process-csv-import', 'LeaveStaffAllocationController@processCsvImport')->name('leave-staff-allocations.processCsvImport');
    Route::resource('leave-staff-allocations', 'LeaveStaffAllocationController');

    // College Block
    Route::delete('college-blocks/destroy', 'CollegeBlockController@massDestroy')->name('college-blocks.massDestroy');
    Route::resource('college-blocks', 'CollegeBlockController');

    // Foundations
    Route::delete('foundations/destroy', 'FoundationController@massDestroy')->name('foundations.massDestroy');
    Route::post('foundations/parse-csv-import', 'FoundationController@parseCsvImport')->name('foundations.parseCsvImport');
    Route::post('foundations/process-csv-import', 'FoundationController@processCsvImport')->name('foundations.processCsvImport');
    Route::resource('foundations', 'FoundationController');

    // Leave Status
    Route::post('leave-statuses/parse-csv-import', 'LeaveStatusController@parseCsvImport')->name('leave-statuses.parseCsvImport');
    Route::post('leave-statuses/process-csv-import', 'LeaveStatusController@processCsvImport')->name('leave-statuses.processCsvImport');
    Route::resource('leave-statuses', 'LeaveStatusController', ['except' => ['destroy']]);

    // Od Master
    Route::delete('od-masters/destroy', 'OdMasterController@massDestroy')->name('od-masters.massDestroy');
    Route::post('od-masters/parse-csv-import', 'OdMasterController@parseCsvImport')->name('od-masters.parseCsvImport');
    Route::post('od-masters/process-csv-import', 'OdMasterController@processCsvImport')->name('od-masters.processCsvImport');
    Route::resource('od-masters', 'OdMasterController');

    Route::get('exam-attendance', 'Students_attendence_edit_request@exam_attdence')->name('exam_Attendance.index');
    // Route::get('exam-attendance/Student', 'Students_attendence_edit_request@exam_attdence')->name('exam_Attendance2.index');
    // Route::get('exam-attendance2', 'Students_attendence_edit_request@index')->name('exam_Attendance2.index');

    // Class Rooms
    Route::get('class-rooms', 'ClassRoomsController@index')->name('class-rooms.index');
    Route::post('class-rooms/view', 'ClassRoomsController@view')->name('class-rooms.view');
    Route::post('class-rooms/edit', 'ClassRoomsController@edit')->name('class-rooms.edit');
    Route::post('class-rooms/store', 'ClassRoomsController@store')->name('class-rooms.store');
    Route::post('class-rooms/delete', 'ClassRoomsController@destroy')->name('class-rooms.delete');
    Route::post('class-rooms/change-status', 'ClassRoomsController@changeStatus')->name('class-rooms.change-status');
    Route::post('class-rooms/getBatch', 'ClassRoomsController@getBatch')->name('class-rooms.getBatch');
    Route::delete('class-rooms/destroy', 'ClassRoomsController@massDestroy')->name('class-rooms.massDestroy');

    // Class Batch
    Route::get('class-batch', 'ClassBatchController@index')->name('class-batch.index');
    Route::post('class-batch/view', 'ClassBatchController@view')->name('class-batch.view');
    Route::post('class-batch/edit', 'ClassBatchController@edit')->name('class-batch.edit');
    Route::post('class-batch/store', 'ClassBatchController@store')->name('class-batch.store');
    Route::post('class-batch/delete', 'ClassBatchController@destroy')->name('class-batch.delete');
    Route::post('class-batch/get-students', 'ClassBatchController@getStudents')->name('class-batch.get-students');
    Route::post('class-batch/get-sections', 'ClassBatchController@getSections')->name('class-batch.get-sections');
    Route::delete('class-batch/destroy', 'ClassBatchController@massDestroy')->name('class-batch.massDestroy');

    // Rooms
    Route::post('rooms/updater/{id}', 'RoomsController@updater')->name('rooms.updater');
    Route::resource('rooms', 'RoomsController');
    Route::delete('rooms/destroy', 'RoomsController@massDestroy')->name('rooms.massDestroy');

    // Email Settings
    Route::delete('email-settings/destroy', 'EmailSettingsController@massDestroy')->name('email-settings.massDestroy');
    Route::post('email-settings/parse-csv-import', 'EmailSettingsController@parseCsvImport')->name('email-settings.parseCsvImport');
    Route::post('email-settings/process-csv-import', 'EmailSettingsController@processCsvImport')->name('email-settings.processCsvImport');
    Route::resource('email-settings', 'EmailSettingsController');

    // Sms Settings
    Route::delete('sms-settings/destroy', 'SmsSettingsController@massDestroy')->name('sms-settings.massDestroy');
    Route::post('sms-settings/parse-csv-import', 'SmsSettingsController@parseCsvImport')->name('sms-settings.parseCsvImport');
    Route::post('sms-settings/process-csv-import', 'SmsSettingsController@processCsvImport')->name('sms-settings.processCsvImport');
    Route::resource('sms-settings', 'SmsSettingsController');

    // Sms Templates
    Route::delete('sms-templates/destroy', 'SmsTemplatesController@massDestroy')->name('sms-templates.massDestroy');
    Route::post('sms-templates/parse-csv-import', 'SmsTemplatesController@parseCsvImport')->name('sms-templates.parseCsvImport');
    Route::post('sms-templates/process-csv-import', 'SmsTemplatesController@processCsvImport')->name('sms-templates.processCsvImport');
    Route::resource('sms-templates', 'SmsTemplatesController');

    // Settings
    Route::delete('settings/destroy', 'SettingsController@massDestroy')->name('settings.massDestroy');
    Route::post('settings/parse-csv-import', 'SettingsController@parseCsvImport')->name('settings.parseCsvImport');
    Route::post('settings/process-csv-import', 'SettingsController@processCsvImport')->name('settings.processCsvImport');
    Route::post('packup-db/generation', 'SettingsController@packUpDb')->name('packup-db.generation');
    Route::resource('settings', 'SettingsController');

    // Take Attentance Student
    Route::delete('take-attentance-students/destroy', 'TakeAttentanceStudentController@massDestroy')->name('take-attentance-students.massDestroy');
    Route::post('take-attentance-students/parse-csv-import', 'TakeAttentanceStudentController@parseCsvImport')->name('take-attentance-students.parseCsvImport');
    Route::post('take-attentance-students/process-csv-import', 'TakeAttentanceStudentController@processCsvImport')->name('take-attentance-students.processCsvImport');
    Route::resource('take-attentance-students', 'TakeAttentanceStudentController');

    // Email Templates
    Route::delete('email-templates/destroy', 'EmailTemplatesController@massDestroy')->name('email-templates.massDestroy');
    Route::post('email-templates/parse-csv-import', 'EmailTemplatesController@parseCsvImport')->name('email-templates.parseCsvImport');
    Route::post('email-templates/process-csv-import', 'EmailTemplatesController@processCsvImport')->name('email-templates.processCsvImport');
    Route::resource('email-templates', 'EmailTemplatesController');

    // Od Request
    Route::delete('od-requests/destroy', 'OdRequestController@massDestroy')->name('od-requests.massDestroy');
    Route::post('od-requests/parse-csv-import', 'OdRequestController@parseCsvImport')->name('od-requests.parseCsvImport');
    Route::post('od-requests/process-csv-import', 'OdRequestController@processCsvImport')->name('od-requests.processCsvImport');
    Route::resource('od-requests', 'OdRequestController');

    // Internship Request
    Route::delete('internship-requests/destroy', 'InternshipRequestController@massDestroy')->name('internship-requests.massDestroy');
    Route::post('internship-requests/parse-csv-import', 'InternshipRequestController@parseCsvImport')->name('internship-requests.parseCsvImport');
    Route::post('internship-requests/process-csv-import', 'InternshipRequestController@processCsvImport')->name('internship-requests.processCsvImport');
    Route::resource('internship-requests', 'InternshipRequestController');

    // College Calender
    Route::delete('college-calenders/destroy', 'CollegeCalenderController@massDestroy')->name('college-calenders.massDestroy');
    Route::post('college-calenders/parse-csv-import', 'CollegeCalenderController@parseCsvImport')->name('college-calenders.parseCsvImport');
    Route::post('college-calenders/process-csv-import', 'CollegeCalenderController@processCsvImport')->name('college-calenders.processCsvImport');
    Route::resource('college-calenders', 'CollegeCalenderController');
    Route::post('college-calenders/att_access', 'CollegeCalenderController@attAccess')->name('college-calenders.att_access');
    Route::post('/update-day', 'CollegeCalenderController@updateDay')->name('update-day');

    // Hrm Request Permission
    Route::delete('hrm-request-permissions/destroy', 'HrmRequestPermissionController@massDestroy')->name('hrm-request-permissions.massDestroy');
    Route::post('hrm-request-permissions/parse-csv-import', 'HrmRequestPermissionController@parseCsvImport')->name('hrm-request-permissions.parseCsvImport');
    Route::post('hrm-request-permissions/process-csv-import', 'HrmRequestPermissionController@processCsvImport')->name('hrm-request-permissions.processCsvImport');
    Route::resource('hrm-request-permissions', 'HrmRequestPermissionController');
    Route::post('hrm-request-permissions/update_hr', 'HrmRequestPermissionController@update_hr')->name('hrm-request-permissions.update_hr');
    Route::get('hrm-request-permissions/permission-list/{user_name_id}', 'HrmRequestPermissionController@list')->name('hrm-request-permissions.permission-list');

    // Add Leaves
    Route::post('staff-request-leaves/delete', 'HrmRequestLeaveController@delete')->name('staff-request-leaves.delete');
    Route::get('staff-request-leaves/staff_index', 'HrmRequestLeaveController@staff_index')->name('staff-request-leaves.staff_index');
    Route::post('staff-request-leaves/staff_update', 'HrmRequestLeaveController@staff_update')->name('staff-request-leaves.staff_update');
    Route::post('staff-request-leaves/staff_updater', 'HrmRequestLeaveController@staff_index')->name('staff-request-leaves.staff_updater');
    Route::post('staff-request-leaves/check', 'HrmRequestLeaveController@check')->name('staff-request-leaves.check');
    Route::post('staff-request-leaves/check_for_half', 'HrmRequestLeaveController@check_for_half')->name('staff-request-leaves.check_for_half');
    Route::post('staff-request-leaves/check_for_off', 'HrmRequestLeaveController@check_for_off')->name('staff-request-leaves.check_for_off');
    Route::post('staff-request-leaves/check_for_compo', 'HrmRequestLeaveController@check_for_compo')->name('staff-request-leaves.check_for_compo');
    Route::post('staff-request-leaves/NT_Teach_check_for_compo', 'HrmRequestLeaveController@NT_Staff_check_for_compo')->name('Non_Teach_staff-request-leaves.check_for_compo');
    Route::post('staff-request-leaves/checkStaff', 'HrmRequestLeaveController@checkStaff')->name('staff-request-leaves.checkStaff');
    Route::post('staff-request-leaves/approve', 'HrmRequestLeaveController@approve')->name('staff-request-leaves.approve');
    Route::post('staff-request-leaves/reject', 'HrmRequestLeaveController@reject')->name('staff-request-leaves.reject');
    Route::post('staff-request-leaves/alter_staff', 'HrmRequestLeaveController@alter_staff')->name('staff-request-leaves.alter_staff');

    // staff indudual Alteration Requests
    Route::get('staff-alteration-requests/index/{status2}/{status}', 'staffRequestsController@index')->name('staff-alteration-requests.index');
    Route::resource('staff-alteration-requests', 'staffRequestsController');

    //Student leave Apply
    Route::get('student-request-leaves/index/{user_name_id}/{name}', 'student_leave_apply@index')->name('student-request-leaves.index');
    Route::post('student-request-leaves/store', 'student_leave_apply@store')->name('student-request-leaves.store');
    Route::delete('student-request-leaves/delete', 'student_leave_apply@delete')->name('student-request-leaves.delete');
    Route::post('student-request-leaves/Edit', 'student_leave_apply@index')->name('student-request-leaves.Edit');

    //Student leave Request
    Route::get('student-leave-requests/stu_index', 'student_leave_apply@stu_index')->name('student-leave-requests.stu_index');
    Route::get('student-leave-requests/show/{id}', 'student_leave_apply@show')->name('student-leave-requests.show');

    //Student Attendence Edit Request
    Route::get('student-att-modification/index/{status}', 'Students_attendence_edit_request@index')->name('student-att-modification.index');
    Route::post('student-att-modification/approve', 'Students_attendence_edit_request@approve')->name('student-att-modification.approve');
    Route::post('student-att-modification/reject', 'Students_attendence_edit_request@reject')->name('student-att-modification.reject');

    // Student Certificate Apply
    Route::get('student-apply-certificate/stu_index', 'CertificateController@stuIndex')->name('student-apply-certificate.stu_index');
    Route::get('student-apply-certificate/show/{id}', 'CertificateController@show')->name('student-apply-certificate.show');
    Route::get('student-apply-certificate/edit/{id}', 'CertificateController@edit')->name('student-apply-certificate.edit');
    Route::get('student-apply-certificate/stu_create', 'CertificateController@stuCreate')->name('student-apply-certificate.stu_create');
    Route::post('student-apply-certificate/store', 'CertificateController@store')->name('student-apply-certificate.store');
    Route::post('student-apply-certificate/update', 'CertificateController@update')->name('student-apply-certificate.update');
    Route::post('student-apply-certificate/get_details', 'CertificateController@getDetails')->name('student-apply-certificate.get_details');
    Route::post('student-studentBonfideType', 'CertificateController@bonafideReson')->name('student_bonfide_reason.get');

    // Certificate Provision
    Route::match(['get', 'post'], 'certificate-provision/index', 'CertificateController@index')->name('certificate-provision.index');
    Route::get('certificate-provision/show/{id}', 'CertificateController@indexShow')->name('certificate-provision.show');
    Route::get('certificate-provision/edit/{id}', 'CertificateController@edit')->name('certificate-provision.edit');
    Route::get('certificate-provision/print/{id}', 'CertificateController@printCertificate')->name('certificate-provision.print');
    Route::post('certificate-provision/update-action', 'CertificateController@updateAction')->name('certificate-provision.update-action');
    Route::post('certificate-provision/update-purpose', 'CertificateController@updatePurpose')->name('certificate-provision.update-purpose');

    // Hrm Request Leave
    Route::post('hrm-request-leaves/update_hr', 'HrmRequestLeaveController@update_hr')->name('hrm-request-leaves.update_hr');
    Route::post('hrm-request-leaves/bulk-approve', 'HrmRequestLeaveController@bulkApprove')->name('hrm-request-leaves.bulk-approve');
    Route::delete('hrm-request-leaves/destroy', 'HrmRequestLeaveController@massDestroy')->name('hrm-request-leaves.massDestroy');
    Route::post('hrm-request-leaves/parse-csv-import', 'HrmRequestLeaveController@parseCsvImport')->name('hrm-request-leaves.parseCsvImport');
    Route::post('hrm-request-leaves/process-csv-import', 'HrmRequestLeaveController@processCsvImport')->name('hrm-request-leaves.processCsvImport');
    Route::get('staff-request-leaves/{id}/edit', 'HrmRequestLeaveController@edit')->name('staff-request-leaves.edit');
    Route::get('hrm-request-leaves/leave-list/{user_id}', 'HrmRequestLeaveController@list')->name('hrm-request-leaves.leave-list');
    Route::resource('hrm-request-leaves', 'HrmRequestLeaveController');

    // Leave Implementation
    Route::get('leave-implementation/index', 'LeaveImplementController@index')->name('leave-implementation.index');
    Route::post('leave-implementation/store', 'LeaveImplementController@store')->name('leave-implementation.store');
    Route::post('leave-implementation/destroy', 'LeaveImplementController@destroy')->name('leave-implementation.destroy');

    // Payment Gateway
    Route::delete('payment-gateways/destroy', 'PaymentGatewayController@massDestroy')->name('payment-gateways.massDestroy');
    Route::post('payment-gateways/parse-csv-import', 'PaymentGatewayController@parseCsvImport')->name('payment-gateways.parseCsvImport');
    Route::post('payment-gateways/process-csv-import', 'PaymentGatewayController@processCsvImport')->name('payment-gateways.processCsvImport');
    Route::resource('payment-gateways', 'PaymentGatewayController');

    // Staff Transfer Info
    Route::delete('staff-transfer-infos/destroy', 'StaffTransferInfoController@massDestroy')->name('staff-transfer-infos.massDestroy');
    Route::post('staff-transfer-infos/parse-csv-import', 'StaffTransferInfoController@parseCsvImport')->name('staff-transfer-infos.parseCsvImport');
    Route::post('staff-transfer-infos/process-csv-import', 'StaffTransferInfoController@processCsvImport')->name('staff-transfer-infos.processCsvImport');
    Route::resource('staff-transfer-infos', 'StaffTransferInfoController');

    //Feed back

    // Route::get('FeedBack/index', 'FeedbackController@index')->name('FeedBack.index');

    //Feed Back Questions
    // Route::get('FeedBack-Questions/index', 'FeedbackQuestionsController@index')->name('feedback_questions.index');
    // Route::get('FeedBack-Questions/create', 'FeedbackQuestionsController@create')->name('feedback_questions.create');
    // Route::post('FeedBack-Questions/store', 'FeedbackQuestionsController@store')->name('feedback_questions.store');
    // Route::post('FeedBack-Questions/update/{id}', 'FeedbackQuestionsController@update')->name('feedback_questions.update');
    // Route::get('FeedBack-Questions/edit/{id}', 'FeedbackQuestionsController@edit')->name('feedback_questions.edit');
    // Route::DELETE('FeedBack-Questions/destroy/{id}', 'FeedbackQuestionsController@destroy')->name('feedback_questions.destroy');

    // Staff Salary
    Route::delete('staff-salaries/destroy', 'StaffSalaryController@massDestroy')->name('staff-salaries.massDestroy');
    Route::post('staff-salaries/parse-csv-import', 'StaffSalaryController@parseCsvImport')->name('staff-salaries.parseCsvImport');
    Route::post('staff-salaries/process-csv-import', 'StaffSalaryController@processCsvImport')->name('staff-salaries.processCsvImport');
    Route::resource('staff-salaries', 'StaffSalaryController');
    Route::get('staff-salaries/staff_index/{user_name_id}/{name}', 'StaffSalaryController@staff_index')->name('staff-salaries.staff_index');
    Route::get('staff-salaries/stu_index/{user_name_id}/{name}', 'StaffSalaryController@stu_index')->name('staff-salaries.stu_index');
    Route::post('staff-salaries/staff_update', 'StaffSalaryController@staff_update')->name('staff-salaries.staff_update');
    Route::post('staff-salaries/stu_update', 'StaffSalaryController@stu_update')->name('staff-salaries.stu_update');
    Route::post('staff-salaries/staff_updater', 'StaffSalaryController@staff_index')->name('staff-salaries.staff_updater');

    // Fundingdetalis
    Route::delete('fundingdetalis/destroy', 'FundingdetalisController@massDestroy')->name('fundingdetalis.massDestroy');
    Route::resource('fundingdetalis', 'FundingdetalisController');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');

    // Cat Exam_Attendance Summary
    Route::get('Exam-Attendance-summary-report', 'CatExamAttendanceSummaryController@index')->name('Exam_attendance.summary.index');
    Route::post('Exam_AttendanceSummary_course_get', 'CatExamAttendanceSummaryController@course_get')->name('Exam_AttendanceSummary.course_get');
    Route::post('Exam_AttendanceSummary_Subject_get', 'CatExamAttendanceSummaryController@subject_get')->name('Exam_AttendanceSummary.Subject_get');
    Route::post('Exam_AttendanceSummary_search', 'CatExamAttendanceSummaryController@search')->name('Exam_AttendanceSummary.search');
    Route::delete('Exam_Attendance_massDestroy', 'CatExamAttendanceSummaryController@massDestroy')->name('Exam_AttendanceSummary.massDestroy');
    Route::get('Exam_AttendanceSummary.edit/{id}', 'CatExamAttendanceSummaryController@edit')->name('Exam_AttendanceSummary.edit');
    Route::get('Exam_AttendanceSummary.view/{id}', 'CatExamAttendanceSummaryController@show')->name('Exam_AttendanceSummary.view');
    Route::DELETE('Exam_AttendanceSummary.destroy/{ExamTimetableCreation}', 'CatExamAttendanceSummaryController@destroy')->name('Exam_AttendanceSummary.destroy');
    Route::post('Exam_Attendance_Summary_report', 'CatExamAttendanceSummaryController@absenteesReport')->name('Exam_AttendanceSummary.summary-report');
    Route::post('Exam_AttendanceSummary_course_get/getDate', 'CatExamAttendanceSummaryController@getDate')->name('Exam_AttendanceSummary.getDate');

    //Student CAT Mark Statement
    Route::get('student-cat-mark/report', 'StudentCatMarkStatementController@index')->name('student-cat-mark.statement');

    // Route::get('Exam-absentees-report/pdf/{id}', 'CatExamAttendanceSummaryController@absenteesReportPDF')->name('Exam-absentees-report.pdf');
    Route::get('Exam-absentees-report/pdf/{id}/{academicYear_id}/{year}/{semester_id}/{date}/{department}/{course_id}/{exameName}', 'CatExamAttendanceSummaryController@absenteesReportPDF')->name('Exam-absentees-report.pdf');
    // Route::get('Exam-absentees-report/pdf/{ay}/{year}/{sem}/{examname}/{date}/{dept}/{course}', 'CatExamAttendanceSummaryController@absenteesReportPDF')->name('Exam-absentees-report.pdf');
    // Route::get('Exam-StaffWise-report/pdf/{id}/{academicYear_id}/{year}/{semester_id}/{date}/{department}/{course_id}', 'CatExamAttendanceSummaryController@staff_wisePDF')->name('Exam_subject_wise_report.pdf');
    Route::get('Exam-StaffWise-report/pdf/{ay}/{year}/{sem}/{examname}/{course}/{section}', 'CatExamAttendanceSummaryController@staff_wisePDF')->name('Exam_staff_wise_report.pdf');
    //report exam
    Route::get('Result_Analysis_Class_Wise', 'CatExamAttendanceSummaryController@Result_Analysis_Class_Wise')->name('Result_Analysis_Class_Wise.index');
    Route::post('Result_Analysis_Class_Wise/get', 'CatExamAttendanceSummaryController@get')->name('Result_Analysis_Class_Wise.get');
    Route::get('Result_Analysis_Staff_Wise', 'CatExamAttendanceSummaryController@Result_Analysis_Staff_Wise')->name('Result_Analysis_Staff_Wise.index');
    Route::post('Result_Analysis_Staff_Wise/staff_wise', 'CatExamAttendanceSummaryController@staff_wise')->name('Result_Analysis_Staff_Wise.staff_wise');
    Route::get('Result_Analysis_Abstract', 'CatExamAttendanceSummaryController@Abstract')->name('Result_Analysis_Abstract.Abstract');
    Route::post('Result_Analysis_Abstract/Abstractget', 'CatExamAttendanceSummaryController@Abstractget')->name('Result_Analysis_Abstract.Abstractget');

    Route::get('Result_Analysis_bar_chart', 'CatExamAttendanceSummaryController@chart')->name('Result_Analysis_bar_chart.chart');
    Route::post('Result_Analysis_bar_chart/Bar_chart', 'CatExamAttendanceSummaryController@showChart')->name('Result_Analysis_bar_chart.showChart');

    Route::get('Exam-Attendance/ViewAttendance/pdf/{$class_id}/{$id}', 'ExamTimetableCreationController@ViewAttendancePdf')->name('Exam-Attendance-report.pdf');
    //Faculty Staff Timetable
    Route::get('faculty_staff-edge/index', 'FacultyTimeTableController@staff')->name('faculty_staff-edge.index');
    Route::post('faculty_edge/geter', 'FacultyTimeTableController@staff_geter')->name('faculty_edge.geter');
    Route::get('faculty_time_table/{staff_name}', 'FacultyTimeTableController@Facultytimetable')->name('faculty_time_table');

    // LabExamTitle
    Route::get('lab_title/index', 'ToolsLabTitleController@index')->name('lab_title.index');
    Route::post('lab_title/view', 'ToolsLabTitleController@view')->name('lab_title.view');
    Route::get('lab_title/create', 'ToolsLabTitleController@create')->name('lab_title.create');
    Route::post('lab_title/edit', 'ToolsLabTitleController@edit')->name('lab_title.edit');
    Route::post('lab_title/store', 'ToolsLabTitleController@store')->name('lab_title.store');
    Route::post('lab_title/delete', 'ToolsLabTitleController@destroy')->name('lab_title.delete');
    Route::delete('lab_title/destroy', 'ToolsLabTitleController@massDestroy')->name('lab_title.massDestroy');


    // Practical Marks
    Route::get('practical-marks/index', 'LabController@practicalIndex')->name('practical-marks.index');
    Route::get('practical-marks/preview', 'LabController@preview')->name('practical-marks.preview');
    Route::get('practical-marks/print', 'LabController@print')->name('practical-marks.print');
    Route::post('practical-marks/printing-data', 'LabController@printingData')->name('practical-marks.printing-data');
    Route::post('practical-marks/get-subjects', 'LabController@getSubjects')->name('practical-marks.get-subjects');
    Route::post('practical-marks/get-students', 'LabController@getStudents')->name('practical-marks.get-students');
    Route::post('practical-marks/store-students', 'LabController@storeStudents')->name('practical-marks.store-students');

    Route::get('practical-mark-master/index', 'LabController@practicalMasterIndex')->name('practical-mark-master.index');
    Route::get('practical-mark-master/excel/{batch}/{ay}/{course}/{semester}/{subject}/{subject_sem}/{exam_type}/{exam_month}/{exam_year}', 'LabController@downloadExcel')->name('practical-mark-master.excel');
    Route::get('practical-mark-master/pdf/{batch}/{ay}/{course}/{semester}/{subject}/{subject_sem}/{exam_type}/{exam_month}/{exam_year}', 'LabController@downloadPdf')->name('practical-mark-master.pdf');
    Route::get('practical-mark-master/edit/{batch}/{ay}/{course}/{semester}/{subject}/{subject_sem}/{exam_type}/{exam_month}/{exam_year}', 'LabController@masterEdit')->name('practical-mark-master.edit');
    Route::post('practical-mark-master/get-data', 'LabController@getData')->name('practical-mark-master.get-data');
    Route::post('practical-mark-master/update-students', 'LabController@updateStudents')->name('practical-mark-master.update-students');

    // Inactive Staff
    Route::resource('inactive_staff', 'InactiveStaffsListController');
    Route::delete('inactive_staff/destroy', 'InactiveStaffsListController@massDestroy')->name('inactive_staff.massDestroy');
    Route::post('inactive_teaching_or_nonteach', 'InactiveStaffsListController@inactive_teaching_or_nonteach')->name('inactive_teaching_or_nonteach');
    Route::resource('Staff_status', 'StaffStatusController');

    // Grade Master
    Route::get('grade-master/index', 'GradeMasterController@index')->name('grade-master.index');
    Route::post('grade-master/view', 'GradeMasterController@view')->name('grade-master.view');
    Route::get('grade-master/create', 'GradeMasterController@create')->name('grade-master.create');
    Route::post('grade-master/edit', 'GradeMasterController@edit')->name('grade-master.edit');
    Route::post('grade-master/store', 'GradeMasterController@store')->name('grade-master.store');
    Route::post('grade-master/delete', 'GradeMasterController@destroy')->name('grade-master.delete');
    Route::delete('grade-master/destroy', 'GradeMasterController@massDestroy')->name('grade-master.massDestroy');

    // Exam Fee Master
    Route::get('examfee-master/index', 'ExamFeeController@masterIndex')->name('examfee-master.index');
    Route::get('examfee-master/create', 'ExamFeeController@create')->name('examfee-master.create');
    Route::post('examfee-master/view', 'ExamFeeController@masterView')->name('examfee-master.view');
    Route::post('examfee-master/edit', 'ExamFeeController@masterEdit')->name('examfee-master.edit');
    Route::post('examfee-master/checkRegulation', 'ExamFeeController@checkRegulation')->name('examfee-master.checkRegulation');
    Route::post('examfee-master/store', 'ExamFeeController@masterStore')->name('examfee-master.store');
    Route::post('examfee-master/update', 'ExamFeeController@masterUpdate')->name('examfee-master.update');
    Route::post('examfee-master/destroy', 'ExamFeeController@masterDestroy')->name('examfee-master.delete');
    Route::delete('examfee-master/destroy', 'ExamFeeController@massDestroy')->name('examfee-master.massDestroy');

    //  Credit Limit Master
    Route::get('credit-limit-master/index', 'CreditLimitController@index')->name('credit-limit-master.index');
    Route::post('credit-limit-master/view', 'CreditLimitController@view')->name('credit-limit-master.view');
    Route::post('credit-limit-master/edit', 'CreditLimitController@edit')->name('credit-limit-master.edit');
    Route::post('credit-limit-master/store', 'CreditLimitController@store')->name('credit-limit-master.store');
    Route::post('credit-limit-master/delete', 'CreditLimitController@destroy')->name('credit-limit-master.delete');
    Route::delete('credit-limit-master/destroy', 'CreditLimitController@massDestroy')->name('credit-limit-master.massDestroy');

    //Staff Details Download
    Route::get('staff_details', 'StaffDetailsDownloadController@index')->name('Staff_details_get');
    Route::post('staff_details', 'StaffDetailsDownloadController@personal_details')->name('Staff_details_personal');

    //Assignment menu
    //Lab mark//
    Route::get('assignment/schedule/index', 'AssignmentExamController@index')->name('assignment.index');
    Route::get('assignment/schedule/create', 'AssignmentExamController@create')->name('assignment_schedule.create');
    Route::post('assignment/schedule/store', 'AssignmentExamController@store')->name('assignment_schedule.store');
    Route::get('assignment/schedule/show/{id}', 'AssignmentExamController@view')->name('assignment.show');
    Route::get('assignment/schedule/view/{id}', 'AssignmentExamController@show')->name('assignment.view');
    Route::get('assignment/schedule/edit/{id}', 'AssignmentExamController@edit')->name('assignment.edit');
    Route::DELETE('assignment.destroy/{ExamTimetableCreation}', 'AssignmentExamController@destroy')->name('assignment.destroy');
    Route::post('assignment.update/{ExamTimetableCreation}', 'AssignmentExamController@update')->name('assignment_schedule_update');
    Route::post('assignment/search', 'AssignmentExamController@search')->name('assignment_schedule.search');
    Route::post('assignment/examTimetable.Subject_get', 'AssignmentExamController@assignment_subject_get')->name('assignment_examTimetable.Subject_get');
    Route::post('assignment/examTimetable.Subject_get_edit', 'AssignmentExamController@assignment_subject_get_edit')->name('assignment_edit.Subject_get');
    Route::post('assignment/mark/search', 'AssignmentExamController@search')->name('assignment_schedule.search');

    Route::get('assignment/Mark/index', 'AssignmentMarkMasterController@index')->name('assignment_Exam_Mark.index');
    Route::get('assignment/Mark/view/{id}/{recordId}', 'AssignmentMarkMasterController@markview')->name('assignment_Exam_Mark.markview');
    Route::get('assignment/Mark/Enter/{id}/{recordId}', 'AssignmentMarkMasterController@MarkEnter')->name('assignment_Exam_Mark.markEnter');
    Route::post('assignment/Mark/Store', 'AssignmentMarkMasterController@MarkStore')->name('assignment_Exam_Mark.markStore');
    Route::post('assignment/Mark/Status-Update', 'AssignmentMarkMasterController@verifiedStatus')->name('assignment_verifiedStatus');
    Route::get('assignment/Mark/Edit/{id}/{recordId}', 'AssignmentMarkMasterController@editMark')->name('assignment_Exam_Mark.editMark');
    Route::post('assignment/Mark/toggle_status', 'AssignmentMarkMasterController@toggle_status')->name('assignment_Exam-Mark.toggle_status');
    Route::post('assignment/Mark/find', 'AssignmentMarkMasterController@find')->name('assignment_Exam_Mark.find');
    Route::post('assignment_examTimetable.find', 'AssignmentExamController@find')->name('assignment_examTimetable.find');

    //assignment Mark Staff page
    Route::get('assignment/MarkEntry/staff/index', 'AssignmentExamController@staff')->name('assignment_Exam_Mark_entry_staff.index');
    Route::get('assignment/MarkView/staff/Result/index', 'AssignmentExamController@indexStaff')->name('assignment_Exam_Mark_Result.index');
    Route::get('assignment/MarkView/staff/Result/view/{id}/{subjectId}', 'AssignmentExamController@resultview')->name('assignment_Exam_Mark_Result.resultview');
    // Route::get('assignment/Mark/staff/Result/StaffWise-report/pdf/{classId}/{subjectId}/{pdf}', 'AssignmentExamController@resultview')->name('assignment_Exam-result_StaffWise_report');
    Route::post('assignment/MarkView/staff/Result/get-past-records', 'AssignmentExamController@getPastRecords')->name('assignment_Exam_Mark_Result.get-past-records');

    //assignment Mark Student Page
    Route::get('assignment/student/mark/report', 'StudentassignmentMarkStatementController@index')->name('student_assignment_mark.statement');
    // Route::get('student-assignment-mark/report', 'StudentassignmentMarkStatementController@index')->name('student_assignment_mark_2.statement');

    // Student Image Upload
    Route::get('student-image/index', 'StudentController@imageIndex')->name('student-image.index');
    Route::post('student-image/upload', 'StudentController@imageUpload')->name('student-image.upload');

    // Result Master
    Route::get('result-master/index', 'ResultMasterController@index')->name('result-master.index');
    Route::post('result-master/view', 'ResultMasterController@view')->name('result-master.view');
    Route::get('result-master/create', 'ResultMasterController@create')->name('result-master.create');
    Route::post('result-master/edit', 'ResultMasterController@edit')->name('result-master.edit');
    Route::post('result-master/store', 'ResultMasterController@store')->name('result-master.store');
    Route::post('result-master/delete', 'ResultMasterController@destroy')->name('result-master.delete');
    Route::delete('result-master/destroy', 'ResultMasterController@massDestroy')->name('result-master.massDestroy');


    // Internal Weightage
    // Route::delete('internal-weightages/destroy', 'SemesterController@semTypeDelete')->name('semesters.destroy');
    Route::get('internal-weightage/index', 'InternalMarksController@weightageIndex')->name('internal-weightage.index');
    Route::post('internal-weightage/subject-types', 'InternalMarksController@subjectTypes')->name('internal-weightage.subject-types');
    Route::get('internal-weightage/create', 'InternalMarksController@weightageCreate')->name('internal-weightage.create');
    Route::get('internal-weightage/fetch-cat', 'InternalMarksController@fetchCat')->name('internal-weightage.fetch_cat');
    Route::get('internal-weightage/store', 'InternalMarksController@store')->name('internal-weightage.store');
    Route::get('internal-weightage/show/{id}', 'InternalMarksController@view')->name('internal-weightage.show');
    Route::get('internal-weightage/edit/{id}', 'InternalMarksController@edit')->name('internal-weightage.edit');
    Route::delete('internal-weightage/destroy', 'InternalMarksController@destroy')->name('internal-weightage.destroy');

    //Internal Mark generation
    Route::get('internal-mark-generation/index', 'InternalMarkGenerationController@index')->name('internalmark_generate');
    Route::get('internal-mark-generation/weightage', 'InternalMarkGenerationController@weightage')->name('internalmark_generate.weightage');
    Route::get('internal-mark-generation/subject', 'InternalMarkGenerationController@fetch_subject')->name('internalmark_generate.fetch_subject');
    Route::post('internal-mark-generation/generate', 'InternalMarkGenerationController@generate')->name('internalmark_generate.generate');
    Route::get('internal-mark-generation/get-data', 'InternalMarkGenerationController@getData')->name('internalmark_generate.get-data');
    Route::get('internal-mark-generation/download/{regulation}/{batch}/{ay}/{course}/{semester}/{subject}/{subject_type}', 'InternalMarkGenerationController@download')->name('internalmark_generate.download');
    Route::post('internal-mark-generation/delete', 'InternalMarkGenerationController@delete')->name('internalmark_generate.delete');

    //Internal Mark report
    Route::get('internal-mark-report/index', 'InternalMarkGenerationController@reportIndex')->name('internalmark_report.index');
    Route::get('internal-mark-report/report', 'InternalMarkGenerationController@report')->name('internalmark_report.report');

    // Exam Enrollment
    Route::get('exam-enrollment/classwise-index', 'ExamEnrollmentController@ClassIndex')->name('exam-enrollment.classwise-index');
    // Route::get('exam-enrollment/run','ExamEnrollmentController@run')->name('exam-enrollment.run');
    Route::get('exam-enrollment/studentwise-index', 'ExamEnrollmentController@StudentIndex')->name('exam-enrollment.studentwise-index');
    Route::post('exam-enrollment/checkMasters', 'ExamEnrollmentController@checkMasters')->name('exam-enrollment.checkMasters');
    Route::post('exam-enrollment/get-subjects-classwise', 'ExamEnrollmentController@getSubjectClasswise')->name('exam-enrollment.get-subjects-classwise');
    Route::post('exam-enrollment/get-details-classwise', 'ExamEnrollmentController@getDetailsClasswise')->name('exam-enrollment.get-details-classwise');
    Route::post('exam-enrollment/get-details-studentwise', 'ExamEnrollmentController@getDetailsStudentwise')->name('exam-enrollment.get-details-studentwise');
    Route::get('exam-enrollment/classwise-show-subjects/{regulation}/{ay}/{batch}/{course}/{semester}/{exam_month}/{exam_year}', 'ExamEnrollmentController@showSubjectClasswise')->name('exam-enrollment.classwise-show-subjects');
    Route::get('exam-enrollment/view-enrolled-student/{user_name_id}/{regulation}/{ay}/{batch}/{course}/{semester}/{exam_month}/{exam_year}', 'ExamEnrollmentController@viewStudent')->name('exam-enrollment.view-enrolled-student');
    Route::post('exam-enrollment/view-enrolled-each-student', 'ExamEnrollmentController@viewEachStudent')->name('exam-enrollment.view-enrolled-each-student');
    Route::get('exam-enrollment/edit-enrolled-student/{user_name_id}/{regulation}/{ay}/{batch}/{course}/{semester}/{exam_month}/{exam_year}/{enroll}', 'ExamEnrollmentController@editStudent')->name('exam-enrollment.edit-enrolled-student');
    Route::post('exam-enrollment/edit-enrolled-each-student', 'ExamEnrollmentController@editEachStudent')->name('exam-enrollment.edit-enrolled-each-student');
    Route::get('exam-enrollment/download-enrolled-student/{user_name_id}/{regulation}/{ay}/{batch}/{course}/{semester}/{exam_month}/{exam_year}', 'ExamEnrollmentController@downloadStudent')->name('exam-enrollment.download-enrolled-student');
    Route::post('exam-enrollment/enroll-classWise', 'ExamEnrollmentController@enrollClasswise')->name('exam-enrollment.enroll-classWise');
    Route::post('exam-enrollment/store-enrolled-student', 'ExamEnrollmentController@storeStudent')->name('exam-enrollment.store-enrolled-student');
    Route::post('exam-enrollment/enroll-studentWise', 'ExamEnrollmentController@enrollStudentwise')->name('exam-enrollment.enroll-studentWise');
    Route::post('exam-enrollment/get-spl-subjects', 'ExamEnrollmentController@getSplSubjects')->name('exam-enrollment.get-spl-subjects');

    // Exam Registration
    Route::delete('exam-registrations/destroy', 'ExamRegistrationController@massDestroy')->name('exam-registrations.massDestroy');
    Route::post('exam-registrations/parse-csv-import', 'ExamRegistrationController@parseCsvImport')->name('exam-registrations.parseCsvImport');
    Route::post('exam-registrations/process-csv-import', 'ExamRegistrationController@processCsvImport')->name('exam-registrations.processCsvImport');
    Route::post('exam-registrations/remove-parse-csv-import', 'ExamRegistrationController@parseCsvRemovalExamReg')->name('exam-registrations.removeParseCsvImport');
    Route::post('exam-registrations/remove-process-csv-import', 'ExamRegistrationController@removeProcessCsvImport')->name('exam-registrations.removeProcessCsvImport');
    Route::get('exam-registrations/download/{ay}/{batch}/{course}/{regulation}/{sem}/{exam_type}', 'ExamRegistrationController@download')->name('exam-registrations.download');
    Route::get('exam-registration-preview', 'ExamRegistrationController@preview')->name('exam-registration-preview');
    Route::get('exam-registration-preview/pdf/{batch}/{ay}/{course}/{semester}/{user_name_id}/{enroll}', 'ExamRegistrationController@previewPdf')->name('exam-registration-preview.pdf');
    Route::get('exam-registration-hallticketPreview/pdf/{batch}/{ay}/{course}/{semester}/{user_name_id}/{enroll}', 'ExamRegistrationController@hallticketPreviewPdf')->name('exam-registration-hallticketPreview.pdf');
    Route::post('exam-registrations/search', 'ExamRegistrationController@search')->name('exam-registrations.search');
    Route::post('exam-registrations/getStudents', 'ExamRegistrationController@getStudents')->name('exam-registrations.getStudents');
    Route::post('exam-registrations/getPreview', 'ExamRegistrationController@getPreview')->name('exam-registrations.getPreview');
    Route::post('exam-registrations/getHallTicketPreview', 'ExamRegistrationController@hallTicketPreview')->name('exam-registrations.getHallTicketPreview');
    Route::get('exam-registrations-subjectwise/index', 'ExamRegistrationController@subjectwise')->name('exam-registrations-subjectwise.index');
    Route::get('exam-registrations-attendanceSheet/index', 'ExamRegistrationController@attendanceSheet')->name('exam-registrations-attendanceSheet.index');
    Route::post('exam-registrations-subjectwise/getSubjects', 'ExamRegistrationController@getSubjects')->name('exam-registrations-subjectwise.getSubjects');
    Route::get('exam-registrations-subjectwise/download/{batch}/{ay}/{course}/{semester}/{exam_type}/{subject}/{subject_sem}', 'ExamRegistrationController@subjectwiseDownload')->name('exam-registrations-subjectwise.download');
    Route::get('exam-registrations-hallticket/index', 'ExamRegistrationController@hallTicket')->name('exam-registrations-hallticket.index');
    Route::resource('exam-registrations', 'ExamRegistrationController');
    Route::get('exam-registrations-result-entry/index', 'ExamRegistrationController@resultEntryIndex')->name('exam-registrations-result-entry.index');

    Route::post('exam-registrations-result-get/index', 'ExamRegistrationController@resultget')->name('exam-registrations-result-result.index');
    Route::post('exam-registrations-result-get/index', 'ExamRegistrationController@examDateGet')->name('exam-publish-exam-date');
    Route::post('exam-result-publish/parse-csv-import', 'ExamRegistrationController@parseCsvImport')->name('exam-result-publish.parseCsvImport');
    Route::post('exam-result-publishes/process-csv-import', 'ExamRegistrationController@processCsvImport')->name('exam-result-publishes.processCsvImport');

    // Result Publish
    Route::resource('result-publish', 'ResultPublishController');
    Route::get('result-publish/download-excel/{ay}/{batch}/{course}/{regulation}/{sem}/{result_type}/{publish}/{exam_month}/{exam_year}', 'ResultPublishController@downloadExcel')->name('result-publish.download-excel');
    Route::get('result-publish/download-pdf/{ay}/{batch}/{course}/{regulation}/{sem}/{result_type}/{publish}/{exam_month}/{exam_year}', 'ResultPublishController@downloadPDF')->name('result-publish.download-pdf');
    Route::get('result-publish/delete/{ay}/{batch}/{course}/{regulation}/{sem}/{result_type}/{publish}/{exam_month}/{exam_year}', 'ResultPublishController@deleteResult')->name('result-publish.delete-result');
    Route::post('result-publish/search', 'ResultPublishController@search')->name('result-publish.search');
    Route::post('result-publish/publish-action', 'ResultPublishController@publish')->name('result-publish.publish-action');
    Route::post('result-publish/check-data', 'ResultPublishController@checkData')->name('result-publish.check-data');

    // Grade Statements

    // Grade Book
    Route::get('grade-book-upload', 'GradeStatementsController@gradeBookIndex')->name('grade-book-upload.index');
    Route::post('grade-book-upload/parse-csv-import', 'GradeStatementsController@parseCsvImport')->name('grade-book-upload.parseCsvImport');
    Route::post('grade-books/process-csv-import', 'GradeStatementsController@processCsvImport')->name('grade-books.processCsvImport');

    // Grade Sheet
    Route::get('grade-sheet', 'GradeStatementsController@gradeSheetIndex')->name('grade-sheet.index');
    Route::get('grade-sheet/pdf/{batch}/{ay}/{course}/{regulation}/{exam_date}', 'GradeStatementsController@gradeSheetPDF')->name('grade-sheet.pdf');
    Route::post('grade-sheet/search', 'GradeStatementsController@searchGradeSheet')->name('grade-sheet.search');
    Route::post('grade-sheet/generation', 'GradeStatementsController@sheetGeneration')->name('grade-sheet.generation');
    Route::post('grade-sheet/check-generation', 'GradeStatementsController@checkSheetGeneration')->name('grade-sheet.check-generation');

    // Consolidated Statement
    Route::get('consolidated-statement', 'GradeStatementsController@statementIndex')->name('consolidated-statement.index');
    Route::post('consolidated-statement/check-generation', 'GradeStatementsController@checkStatementGeneration')->name('consolidated-statement.check-generation');
    Route::post('consolidated-statement/search', 'GradeStatementsController@searchStatement')->name('consolidated-statement.search');
    Route::post('consolidated-statement/generation', 'GradeStatementsController@statementGeneration')->name('consolidated-statement.generation');
    Route::get('consolidated-statement/pdf/{batch}/{course}/{regulation}', 'GradeStatementsController@statementPDF')->name('consolidated-statement.pdf');
    Route::get('consolidated-statement/print', 'GradeStatementsController@statePrint')->name('consolidated-statement.print');

    // Transcript
    Route::get('transcript', 'GradeStatementsController@transcriptIndex')->name('transcript.index');
    Route::post('transcript/check-generation', 'GradeStatementsController@checkScriptGeneration')->name('transcript.check-generation');
    Route::post('transcript/search', 'GradeStatementsController@searchTranscript')->name('transcript.search');
    Route::post('transcript/generation', 'GradeStatementsController@scriptGeneration')->name('transcript.generation');
    // test
    // Route::get('transcript/generation/{batch}/{course}/{regulation}', 'GradeStatementsController@testScriptGeneration')->name('transcript.generation');
    Route::get('transcript/pdf/{batch}/{course}/{regulation}', 'GradeStatementsController@tranScriptPDF')->name('transcript.pdf');
    Route::get('transcript/print', 'GradeStatementsController@scriptPrint')->name('transcript.print');

    Route::get('grade/student/mark/report', 'ResultPublishController@studentIndex')->name('student_grade_mark.statement');
    Route::post('grade/student/mark/get_marks', 'ResultPublishController@getGrade')->name('student_grade_mark.get_marks');

    Route::get('grade-book/index', 'ResultPublishController@gradeBookIndex')->name('grade-book.index');
    Route::post('grade-book/get-grades', 'ResultPublishController@getGradeBook')->name('grade-book.get-grades');
    Route::get('grade-book/print-grades/{result_sem}/{user_name_id}', 'ResultPublishController@printGrades')->name('grade-book.print-grades');

    Route::get('exam-fee/index', 'ExamFeeController@index')->name('exam-fee.index');
    Route::get('exam-fee/generate/{ay}/{batch}/{course}/{semester}/{user_name_id}/{exam_month}/{exam_year}', 'ExamFeeController@generate')->name('exam-fee.generate');

    Route::get('erp-setting', 'ErpSettingController@index')->name('erp-setting.index');
    Route::post('erp-setting/edit', 'ErpSettingController@edit')->name('erp-setting.edit');
    Route::post('erp-setting/store', 'ErpSettingController@store')->name('erp-setting.store');
    Route::post('erp-setting/view', 'ErpSettingController@view')->name('erp-setting.view');
    Route::post('erp-setting/delete', 'ErpSettingController@destroy')->name('erp-setting.delete');
    Route::delete('erp-setting/destroy', 'ErpSettingController@massDestroy')->name('erp-setting.massDestroy');



});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::get('password/Staff-Edit', 'ChangePasswordController@Staff_edit')->name('password.Staff_edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');


    Route::get('tools-degree-types', 'ToolsDegreeTypeController@index')->name('tools-degree-types.index');
    Route::post('tools-degree-types/view', 'ToolsDegreeTypeController@view')->name('tools-degree-types.view');
    Route::post('tools-degree-types/edit', 'ToolsDegreeTypeController@edit')->name('tools-degree-types.edit');
    Route::post('tools-degree-types/store', 'ToolsDegreeTypeController@store')->name('tools-degree-types.store');
    Route::post('tools-degree-types/delete', 'ToolsDegreeTypeController@destroy')->name('tools-degree-types.delete');
    Route::delete('tools-degree-types/destroy', 'ToolsDegreeTypeController@massDestroy')->name('tools-degree-types.massDestroy');

    // Year
    Route::get('year', 'YearController@index')->name('year.index');
    Route::post('year/view', 'YearController@view')->name('year.view');
    Route::post('year/edit', 'YearController@edit')->name('year.edit');
    Route::post('year/store', 'YearController@store')->name('year.store');
    Route::post('year/delete', 'YearController@destroy')->name('year.delete');
    Route::delete('year/destroy', 'YearController@massDestroy')->name('year.massDestroy');

    // Academic Year
    Route::get('academic-years', 'AcademicYearController@index')->name('academic-years.index');
    Route::post('academic-years/view', 'AcademicYearController@view')->name('academic-years.view');
    Route::post('academic-years/edit', 'AcademicYearController@edit')->name('academic-years.edit');
    Route::post('academic-years/store', 'AcademicYearController@store')->name('academic-years.store');
    Route::post('academic-years/delete', 'AcademicYearController@destroy')->name('academic-years.delete');
    Route::post('academic-years/check', 'AcademicYearController@check')->name('academic-years.check');
    Route::post('academic-years/change-status', 'AcademicYearController@changeStatus')->name('academic-years.change-status');
    Route::delete('academic-years/destroy', 'AcademicYearController@massDestroy')->name('academic-years.massDestroy');

    // Settings
    // Route::get('admin/master_settings','HomeController@master_settings')->name('admin.master_settings');

    // Batch
    Route::get('batches', 'BatchController@index')->name('batches.index');
    Route::post('batches/view', 'BatchController@view')->name('batches.view');
    Route::post('batches/edit', 'BatchController@edit')->name('batches.edit');
    Route::post('batches/store', 'BatchController@store')->name('batches.store');
    Route::post('batches/delete', 'BatchController@destroy')->name('batches.delete');
    Route::delete('batches/destroy', 'BatchController@massDestroy')->name('batches.massDestroy');

    // Tools Mainscreen
    // Route::delete('tools/destroy', 'ToolsController@massDestroy')->name('tools-courses.massDestroy');
    Route::resource('tools', 'ToolsController');

    // Tools Course
    Route::get('tools-courses', 'ToolsCourseController@index')->name('tools-courses.index');
    Route::post('tools-courses/view', 'ToolsCourseController@view')->name('tools-courses.view');
    Route::post('tools-courses/edit', 'ToolsCourseController@edit')->name('tools-courses.edit');
    Route::post('tools-courses/store', 'ToolsCourseController@store')->name('tools-courses.store');
    Route::post('tools-courses/delete', 'ToolsCourseController@destroy')->name('tools-courses.delete');
    Route::delete('tools-courses/destroy', 'ToolsCourseController@massDestroy')->name('tools-courses.massDestroy');

    // Tools Department
    // Route::delete('tools-departments/destroy', 'ToolsDepartmentController@massDestroy')->name('tools-departments.massDestroy');
    // Route::resource('tools-departments', 'ToolsDepartmentController');

    Route::get('tools-departments', 'ToolsDepartmentController@index')->name('tools-departments.index');
    Route::post('tools-departments/view', 'ToolsDepartmentController@view')->name('tools-departments.view');
    Route::post('tools-departments/edit', 'ToolsDepartmentController@edit')->name('tools-departments.edit');
    Route::post('tools-departments/store', 'ToolsDepartmentController@store')->name('tools-departments.store');
    Route::post('tools-departments/delete', 'ToolsDepartmentController@destroy')->name('tools-departments.delete');
    Route::delete('tools-departments/destroy', 'ToolsDepartmentController@massDestroy')->name('tools-departments.massDestroy');

    // Section
    Route::get('sections', 'SectionController@index')->name('sections.index');
    Route::post('sections/view', 'SectionController@view')->name('sections.view');
    Route::post('sections/edit', 'SectionController@edit')->name('sections.edit');
    Route::post('sections/store', 'SectionController@store')->name('sections.store');
    Route::post('sections/delete', 'SectionController@destroy')->name('sections.delete');
    Route::delete('sections/destroy', 'SectionController@massDestroy')->name('sections.massDestroy');

    // Semester
    Route::get('semesters', 'SemesterController@index')->name('semesters.index');
    Route::post('semesters/view', 'SemesterController@view')->name('semesters.view');
    Route::post('semesters/edit', 'SemesterController@edit')->name('semesters.edit');
    Route::post('semesters/store', 'SemesterController@store')->name('semesters.store');
    Route::post('semesters/delete', 'SemesterController@destroy')->name('semesters.delete');
    Route::post('semesters/change-status', 'SemesterController@changeStatus')->name('semesters.change-status');
    Route::delete('semesters/destroy', 'SemesterController@massDestroy')->name('semesters.massDestroy');

    // Course Enroll Master
    Route::post('course_enroll_masters/enroll_index', 'CourseEnrollMasterController@enroll_index')->name('course_enroll_masters.enroll_index');
    Route::get('course-enroll-masters', 'CourseEnrollMasterController@index')->name('course-enroll-masters.index');
    Route::post('course-enroll-masters/view', 'CourseEnrollMasterController@view')->name('course-enroll-masters.view');
    Route::post('course-enroll-masters/edit', 'CourseEnrollMasterController@edit')->name('course-enroll-masters.edit');
    Route::post('course-enroll-masters/store', 'CourseEnrollMasterController@store')->name('course-enroll-masters.store');
    Route::post('course-enroll-masters/delete', 'CourseEnrollMasterController@destroy')->name('course-enroll-masters.delete');
    Route::delete('course-enroll-masters/destroy', 'CourseEnrollMasterController@massDestroy')->name('course-enroll-masters.massDestroy');

    // Toolssyllabus Year
    Route::get('toolssyllabus-years', 'ToolssyllabusYearController@index')->name('toolssyllabus-years.index');
    Route::post('toolssyllabus-years/view', 'ToolssyllabusYearController@view')->name('toolssyllabus-years.view');
    Route::post('toolssyllabus-years/edit', 'ToolssyllabusYearController@edit')->name('toolssyllabus-years.edit');
    Route::post('toolssyllabus-years/store', 'ToolssyllabusYearController@store')->name('toolssyllabus-years.store');
    Route::post('toolssyllabus-years/delete', 'ToolssyllabusYearController@destroy')->name('toolssyllabus-years.delete');
    Route::delete('toolssyllabus-years/destroy', 'ToolssyllabusYearController@massDestroy')->name('toolssyllabus-years.massDestroy');

    // Personal Details
    Route::delete('personal-details/destroy', 'PersonalDetailsController@massDestroy')->name('personal-details.massDestroy');
    Route::resource('personal-details', 'PersonalDetailsController');

    // Educational Details
    Route::delete('educational-details/destroy', 'EducationalDetailsController@massDestroy')->name('educational-details.massDestroy');
    Route::resource('educational-details', 'EducationalDetailsController');

    // Nationality
    Route::get('nationalities', 'NationalityController@index')->name('nationalities.index');
    Route::post('nationalities/view', 'NationalityController@view')->name('nationalities.view');
    Route::post('nationalities/edit', 'NationalityController@edit')->name('nationalities.edit');
    Route::post('nationalities/store', 'NationalityController@store')->name('nationalities.store');
    Route::post('nationalities/delete', 'NationalityController@destroy')->name('nationalities.delete');
    Route::post('nationalities/check', 'NationalityController@check')->name('nationalities.check');
    Route::post('nationalities/change-status', 'NationalityController@changeStatus')->name('nationalities.change-status');
    Route::delete('nationalities/destroy', 'NationalityController@massDestroy')->name('nationalities.massDestroy');

    //Payment Mode
    Route::get('paymentMode', 'PaymentModeController@index')->name('paymentMode.index');
    Route::post('paymentMode/view', 'PaymentModeController@view')->name('paymentMode.view');
    Route::post('paymentMode/edit', 'PaymentModeController@edit')->name('paymentMode.edit');
    Route::post('paymentMode/store', 'PaymentModeController@store')->name('paymentMode.store');
    Route::post('paymentMode/delete', 'PaymentModeController@destroy')->name('paymentMode.delete');
    Route::delete('paymentMode/destroy', 'PaymentModeController@massDestroy')->name('paymentMode.massDestroy');

    // Religion
    Route::get('religions', 'ReligionController@index')->name('religions.index');
    Route::post('religions/view', 'ReligionController@view')->name('religions.view');
    Route::post('religions/edit', 'ReligionController@edit')->name('religions.edit');
    Route::post('religions/store', 'ReligionController@store')->name('religions.store');
    Route::post('religions/delete', 'ReligionController@destroy')->name('religions.delete');
    Route::delete('religions/destroy', 'ReligionController@massDestroy')->name('religions.massDestroy');

    // Blood Group
    Route::get('blood-groups', 'BloodGroupController@index')->name('blood-groups.index');
    Route::post('blood-groups/view', 'BloodGroupController@view')->name('blood-groups.view');
    Route::post('blood-groups/edit', 'BloodGroupController@edit')->name('blood-groups.edit');
    Route::post('blood-groups/store', 'BloodGroupController@store')->name('blood-groups.store');
    Route::post('blood-groups/delete', 'BloodGroupController@destroy')->name('blood-groups.delete');
    Route::delete('blood-groups/destroy', 'BloodGroupController@massDestroy')->name('blood-groups.massDestroy');

    // Community
    Route::get('communities', 'CommunityController@index')->name('communities.index');
    Route::post('communities/view', 'CommunityController@view')->name('communities.view');
    Route::post('communities/edit', 'CommunityController@edit')->name('communities.edit');
    Route::post('communities/store', 'CommunityController@store')->name('communities.store');
    Route::post('communities/delete', 'CommunityController@destroy')->name('communities.delete');
    Route::delete('communities/destroy', 'CommunityController@massDestroy')->name('communities.massDestroy');

    // Mother Tongue
    Route::get('mother-tongues', 'MotherTongueController@index')->name('mother-tongues.index');
    Route::post('mother-tongues/view', 'MotherTongueController@view')->name('mother-tongues.view');
    Route::post('mother-tongues/edit', 'MotherTongueController@edit')->name('mother-tongues.edit');
    Route::post('mother-tongues/store', 'MotherTongueController@store')->name('mother-tongues.store');
    Route::post('mother-tongues/delete', 'MotherTongueController@destroy')->name('mother-tongues.delete');
    Route::delete('mother-tongues/destroy', 'MotherTongueController@massDestroy')->name('mother-tongues.massDestroy');

    // Education Board
    Route::get('education-boards', 'EducationBoardController@index')->name('education-boards.index');
    Route::post('education-boards/view', 'EducationBoardController@view')->name('education-boards.view');
    Route::post('education-boards/edit', 'EducationBoardController@edit')->name('education-boards.edit');
    Route::post('education-boards/store', 'EducationBoardController@store')->name('education-boards.store');
    Route::post('education-boards/delete', 'EducationBoardController@destroy')->name('education-boards.delete');
    Route::delete('education-boards/destroy', 'EducationBoardController@massDestroy')->name('education-boards.massDestroy');

    // Subject Types
    Route::get('subject_types', 'SubjectTypeController@index')->name('subject_types.index');
    Route::post('subject_types/view', 'SubjectTypeController@view')->name('subject_types.view');
    Route::post('subject_types/edit', 'SubjectTypeController@edit')->name('subject_types.edit');
    Route::post('subject_types/store', 'SubjectTypeController@store')->name('subject_types.store');
    Route::post('subject_types/delete', 'SubjectTypeController@destroy')->name('subject_types.delete');
    Route::post('subject_types/check', 'SubjectTypeController@check')->name('subject_types.check');
    Route::delete('subject_types/destroy', 'SubjectTypeController@massDestroy')->name('subject_types.massDestroy');

    // Subject Category
    Route::get('subject_category', 'SubjectCategoryController@index')->name('subject_category.index');
    Route::post('subject_category/view', 'SubjectCategoryController@view')->name('subject_category.view');
    Route::post('subject_category/edit', 'SubjectCategoryController@edit')->name('subject_category.edit');
    Route::post('subject_category/store', 'SubjectCategoryController@store')->name('subject_category.store');
    Route::post('subject_category/delete', 'SubjectCategoryController@destroy')->name('subject_category.delete');
    Route::post('subject_category/check', 'SubjectCategoryController@check')->name('subject_category.check');
    Route::delete('subject_category/destroy', 'SubjectCategoryController@massDestroy')->name('subject_category.massDestroy');

    // Address
    Route::delete('addresses/destroy', 'AddressController@massDestroy')->name('addresses.massDestroy');
    Route::resource('addresses', 'AddressController');

    // Parent Details
    Route::delete('parent-details/destroy', 'ParentDetailsController@massDestroy')->name('parent-details.massDestroy');
    Route::resource('parent-details', 'ParentDetailsController');

    // Bank Account Details
    Route::delete('bank-account-details/destroy', 'BankAccountDetailsController@massDestroy')->name('bank-account-details.massDestroy');
    Route::resource('bank-account-details', 'BankAccountDetailsController');

    // Experience Details
    Route::delete('experience-details/destroy', 'ExperienceDetailsController@massDestroy')->name('experience-details.massDestroy');
    Route::resource('experience-details', 'ExperienceDetailsController');

    // Teaching Staff
    Route::delete('teaching-staffs/destroy', 'TeachingStaffController@massDestroy')->name('teaching-staffs.massDestroy');
    Route::resource('teaching-staffs', 'TeachingStaffController');

    // Non Teaching Staff
    Route::delete('non-teaching-staffs/destroy', 'NonTeachingStaffController@massDestroy')->name('non-teaching-staffs.massDestroy');
    Route::resource('non-teaching-staffs', 'NonTeachingStaffController');

    // Teaching Type
    Route::delete('teaching-types/destroy', 'TeachingTypeController@massDestroy')->name('teaching-types.massDestroy');
    Route::resource('teaching-types', 'TeachingTypeController');

    // Examstaff
    Route::delete('examstaffs/destroy', 'ExamstaffController@massDestroy')->name('examstaffs.massDestroy');
    Route::resource('examstaffs', 'ExamstaffController');

    // Add Conference
    Route::delete('add-conferences/destroy', 'AddConferenceController@massDestroy')->name('add-conferences.massDestroy');
    Route::resource('add-conferences', 'AddConferenceController');

    // Entrance Exams
    Route::delete('entrance-exams/destroy', 'EntranceExamsController@massDestroy')->name('entrance-exams.massDestroy');
    Route::resource('entrance-exams', 'EntranceExamsController');

    // Guest Lecture
    Route::delete('guest-lectures/destroy', 'GuestLectureController@massDestroy')->name('guest-lectures.massDestroy');
    Route::resource('guest-lectures', 'GuestLectureController');

    // Industrial Training
    Route::delete('industrial-trainings/destroy', 'IndustrialTrainingController@massDestroy')->name('industrial-trainings.massDestroy');
    Route::resource('industrial-trainings', 'IndustrialTrainingController');

    // Intern
    Route::delete('interns/destroy', 'InternController@massDestroy')->name('interns.massDestroy');
    Route::resource('interns', 'InternController');

    // Industrial Experience
    Route::delete('industrial-experiences/destroy', 'IndustrialExperienceController@massDestroy')->name('industrial-experiences.massDestroy');
    Route::resource('industrial-experiences', 'IndustrialExperienceController');

    // Iv
    Route::delete('ivs/destroy', 'IvController@massDestroy')->name('ivs.massDestroy');
    Route::resource('ivs', 'IvController');

    // Online Course
    Route::delete('online-courses/destroy', 'OnlineCourseController@massDestroy')->name('online-courses.massDestroy');
    Route::resource('online-courses', 'OnlineCourseController');

    // Documents
    Route::delete('documents/destroy', 'DocumentsController@massDestroy')->name('documents.massDestroy');
    Route::post('documents/media', 'DocumentsController@storeMedia')->name('documents.storeMedia');
    Route::post('documents/ckmedia', 'DocumentsController@storeCKEditorImages')->name('documents.storeCKEditorImages');
    Route::resource('documents', 'DocumentsController');

    // Seminar
    Route::delete('seminars/destroy', 'SeminarController@massDestroy')->name('seminars.massDestroy');
    Route::resource('seminars', 'SeminarController');

    // Saboticals
    Route::delete('saboticals/destroy', 'SaboticalsController@massDestroy')->name('saboticals.massDestroy');
    Route::resource('saboticals', 'SaboticalsController');

    // Sponser
    Route::delete('sponsers/destroy', 'SponserController@massDestroy')->name('sponsers.massDestroy');
    Route::resource('sponsers', 'SponserController');

    // Sttp
    Route::delete('sttps/destroy', 'SttpController@massDestroy')->name('sttps.massDestroy');
    Route::resource('sttps', 'SttpController');

    // Workshop
    Route::delete('workshops/destroy', 'WorkshopController@massDestroy')->name('workshops.massDestroy');
    Route::resource('workshops', 'WorkshopController');

    // Patents
    Route::delete('patents/destroy', 'PatentsController@massDestroy')->name('patents.massDestroy');
    Route::resource('patents', 'PatentsController');

    // Awards
    Route::delete('awards/destroy', 'AwardsController@massDestroy')->name('awards.massDestroy');
    Route::resource('awards', 'AwardsController');

    // Asset Category
    Route::delete('asset-categories/destroy', 'AssetCategoryController@massDestroy')->name('asset-categories.massDestroy');
    Route::resource('asset-categories', 'AssetCategoryController');

    // Asset Location
    Route::delete('asset-locations/destroy', 'AssetLocationController@massDestroy')->name('asset-locations.massDestroy');
    Route::resource('asset-locations', 'AssetLocationController');

    // Asset Status
    Route::delete('asset-statuses/destroy', 'AssetStatusController@massDestroy')->name('asset-statuses.massDestroy');
    Route::resource('asset-statuses', 'AssetStatusController');

    // Asset
    Route::delete('assets/destroy', 'AssetController@massDestroy')->name('assets.massDestroy');
    Route::post('assets/media', 'AssetController@storeMedia')->name('assets.storeMedia');
    Route::post('assets/ckmedia', 'AssetController@storeCKEditorImages')->name('assets.storeCKEditorImages');
    Route::resource('assets', 'AssetController');

    // Assets History
    Route::resource('assets-histories', 'AssetsHistoryController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Task Status
    Route::delete('task-statuses/destroy', 'TaskStatusController@massDestroy')->name('task-statuses.massDestroy');
    Route::resource('task-statuses', 'TaskStatusController');

    // Task Tag
    Route::delete('task-tags/destroy', 'TaskTagController@massDestroy')->name('task-tags.massDestroy');
    Route::resource('task-tags', 'TaskTagController');

    // Task
    Route::delete('tasks/destroy', 'TaskController@massDestroy')->name('tasks.massDestroy');
    Route::post('tasks/media', 'TaskController@storeMedia')->name('tasks.storeMedia');
    Route::post('tasks/ckmedia', 'TaskController@storeCKEditorImages')->name('tasks.storeCKEditorImages');
    Route::resource('tasks', 'TaskController');

    // Tasks Calendar
    // Route::resource('tasks-calendars', 'TasksCalendarController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Faq Category
    Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    Route::resource('faq-categories', 'FaqCategoryController');

    // Faq Question
    Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    Route::resource('faq-questions', 'FaqQuestionController');

    // Content Category
    Route::delete('content-categories/destroy', 'ContentCategoryController@massDestroy')->name('content-categories.massDestroy');
    Route::resource('content-categories', 'ContentCategoryController');

    // Content Tag
    Route::delete('content-tags/destroy', 'ContentTagController@massDestroy')->name('content-tags.massDestroy');
    Route::resource('content-tags', 'ContentTagController');

    // Content Page
    Route::delete('content-pages/destroy', 'ContentPageController@massDestroy')->name('content-pages.massDestroy');
    Route::post('content-pages/media', 'ContentPageController@storeMedia')->name('content-pages.storeMedia');
    Route::post('content-pages/ckmedia', 'ContentPageController@storeCKEditorImages')->name('content-pages.storeCKEditorImages');
    Route::resource('content-pages', 'ContentPageController');

    // Expense Category
    Route::delete('expense-categories/destroy', 'ExpenseCategoryController@massDestroy')->name('expense-categories.massDestroy');
    Route::resource('expense-categories', 'ExpenseCategoryController');

    // Income Category
    Route::delete('income-categories/destroy', 'IncomeCategoryController@massDestroy')->name('income-categories.massDestroy');
    Route::resource('income-categories', 'IncomeCategoryController');

    // Expense
    Route::delete('expenses/destroy', 'ExpenseController@massDestroy')->name('expenses.massDestroy');
    Route::resource('expenses', 'ExpenseController');

    // Income
    Route::delete('incomes/destroy', 'IncomeController@massDestroy')->name('incomes.massDestroy');
    Route::resource('incomes', 'IncomeController');

    // Courses
    Route::delete('courses/destroy', 'CoursesController@massDestroy')->name('courses.massDestroy');
    Route::post('courses/media', 'CoursesController@storeMedia')->name('courses.storeMedia');
    Route::post('courses/ckmedia', 'CoursesController@storeCKEditorImages')->name('courses.storeCKEditorImages');
    Route::resource('courses', 'CoursesController');

    // Lessons
    Route::delete('lessons/destroy', 'LessonsController@massDestroy')->name('lessons.massDestroy');
    Route::post('lessons/media', 'LessonsController@storeMedia')->name('lessons.storeMedia');
    Route::post('lessons/ckmedia', 'LessonsController@storeCKEditorImages')->name('lessons.storeCKEditorImages');
    Route::resource('lessons', 'LessonsController');

    // Tests
    Route::delete('tests/destroy', 'TestsController@massDestroy')->name('tests.massDestroy');
    Route::resource('tests', 'TestsController');

    // Questions
    Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    Route::post('questions/media', 'QuestionsController@storeMedia')->name('questions.storeMedia');
    Route::post('questions/ckmedia', 'QuestionsController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    Route::resource('questions', 'QuestionsController');

    // Question Options
    Route::delete('question-options/destroy', 'QuestionOptionsController@massDestroy')->name('question-options.massDestroy');
    Route::resource('question-options', 'QuestionOptionsController');

    // Test Results
    Route::delete('test-results/destroy', 'TestResultsController@massDestroy')->name('test-results.massDestroy');
    Route::resource('test-results', 'TestResultsController');

    // Test Answers
    Route::delete('test-answers/destroy', 'TestAnswersController@massDestroy')->name('test-answers.massDestroy');
    Route::resource('test-answers', 'TestAnswersController');

    // Leave Type
    Route::delete('leave-types/destroy', 'LeaveTypeController@massDestroy')->name('leave-types.massDestroy');
    Route::resource('leave-types', 'LeaveTypeController');

    // Staff Biometrics
    Route::delete('staff-biometrics/destroy', 'StaffBiometricController@massDestroy')->name('staff-biometrics.massDestroy');
    Route::resource('staff-biometrics', 'StaffBiometricController');

    // Leave Staff Allocation
    Route::delete('leave-staff-allocations/destroy', 'LeaveStaffAllocationController@massDestroy')->name('leave-staff-allocations.massDestroy');
    Route::resource('leave-staff-allocations', 'LeaveStaffAllocationController');

    // College Block
    Route::delete('college-blocks/destroy', 'CollegeBlockController@massDestroy')->name('college-blocks.massDestroy');
    Route::resource('college-blocks', 'CollegeBlockController');

    // Leave Status
    Route::resource('leave-statuses', 'LeaveStatusController', ['except' => ['destroy']]);

    // Od Master
    Route::delete('od-masters/destroy', 'OdMasterController@massDestroy')->name('od-masters.massDestroy');
    Route::resource('od-masters', 'OdMasterController');

    // Class Rooms
    Route::get('class-rooms', 'ClassRoomsController@index')->name('class-rooms.index');
    Route::post('class-rooms/view', 'ClassRoomsController@view')->name('class-rooms.view');
    Route::post('class-rooms/edit', 'ClassRoomsController@edit')->name('class-rooms.edit');
    Route::post('class-rooms/store', 'ClassRoomsController@store')->name('class-rooms.store');
    Route::post('class-rooms/delete', 'ClassRoomsController@destroy')->name('class-rooms.delete');
    Route::post('class-rooms/change-status', 'ClassRoomsController@changeStatus')->name('class-rooms.change-status');
    Route::post('class-rooms/getBatch', 'ClassRoomsController@getBatch')->name('class-rooms.getBatch');
    Route::delete('class-rooms/destroy', 'ClassRoomsController@massDestroy')->name('class-rooms.massDestroy');



    // Email Settings
    Route::delete('email-settings/destroy', 'EmailSettingsController@massDestroy')->name('email-settings.massDestroy');
    Route::resource('email-settings', 'EmailSettingsController');

    // Sms Settings
    Route::delete('sms-settings/destroy', 'SmsSettingsController@massDestroy')->name('sms-settings.massDestroy');
    Route::resource('sms-settings', 'SmsSettingsController');

    // Sms Templates
    Route::delete('sms-templates/destroy', 'SmsTemplatesController@massDestroy')->name('sms-templates.massDestroy');
    Route::resource('sms-templates', 'SmsTemplatesController');

    // Settings
    Route::delete('settings/destroy', 'SettingsController@massDestroy')->name('settings.massDestroy');
    Route::resource('settings', 'SettingsController');

    // Take Attentance Student
    Route::delete('take-attentance-students/destroy', 'TakeAttentanceStudentController@massDestroy')->name('take-attentance-students.massDestroy');
    Route::resource('take-attentance-students', 'TakeAttentanceStudentController');

    // Email Templates
    Route::delete('email-templates/destroy', 'EmailTemplatesController@massDestroy')->name('email-templates.massDestroy');
    Route::resource('email-templates', 'EmailTemplatesController');

    // Od Request
    Route::delete('od-requests/destroy', 'OdRequestController@massDestroy')->name('od-requests.massDestroy');
    Route::resource('od-requests', 'OdRequestController');

    // Internship Request
    Route::delete('internship-requests/destroy', 'InternshipRequestController@massDestroy')->name('internship-requests.massDestroy');
    Route::resource('internship-requests', 'InternshipRequestController');

    // College Calender
    Route::delete('college-calenders/destroy', 'CollegeCalenderController@massDestroy')->name('college-calenders.massDestroy');
    Route::resource('college-calenders', 'CollegeCalenderController');
    Route::post('/update-day', 'CollegeCalenderController@update')->name('update-day');

    // Hrm Request Permission
    Route::delete('hrm-request-permissions/destroy', 'HrmRequestPermissionController@massDestroy')->name('hrm-request-permissions.massDestroy');
    Route::resource('hrm-request-permissions', 'HrmRequestPermissionController');

    // Hrm Request Leave
    Route::delete('hrm-request-leaves/destroy', 'HrmRequestLeaveController@massDestroy')->name('hrm-request-leaves.massDestroy');
    Route::resource('hrm-request-leaves', 'HrmRequestLeaveController');

    // Payment Gateway
    Route::delete('payment-gateways/destroy', 'PaymentGatewayController@massDestroy')->name('payment-gateways.massDestroy');
    Route::resource('payment-gateways', 'PaymentGatewayController');

    // Staff Transfer Info
    Route::delete('staff-transfer-infos/destroy', 'StaffTransferInfoController@massDestroy')->name('staff-transfer-infos.massDestroy');
    Route::resource('staff-transfer-infos', 'StaffTransferInfoController');

    // Fundingdetalis
    Route::delete('fundingdetalis/destroy', 'FundingdetalisController@massDestroy')->name('fundingdetalis.massDestroy');
    Route::resource('fundingdetalis', 'FundingdetalisController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');

    Route::get('erp-setting', 'ErpSettingController@index')->name('erp-setting.index');
    Route::post('erp-setting/edit', 'ErpSettingController@edit')->name('erp-setting.edit');
    Route::post('erp-setting/store', 'ErpSettingController@store')->name('erp-setting.store');
    Route::post('erp-setting/view', 'ErpSettingController@view')->name('erp-setting.view');
    Route::post('erp-setting/delete', 'ErpSettingController@destroy')->name('erp-setting.delete');
    Route::delete('erp-setting/destroy', 'ErpSettingController@massDestroy')->name('erp-setting.massDestroy');


});
