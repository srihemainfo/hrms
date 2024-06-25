<?php
use App\Http\Controllers\Api\V1\Admin\AuthController;
use Illuminate\Http\Request;

Route::post('staff-biometrics-data','Api\V1\Admin\StaffBiometricApiController@check');
// Route::get('/user', [UserController::class, 'index']);
// Route::post('/auth/register', [AuthController::class, 'createUser']);
// Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::post('student-login','Api\Login\LoginTestController@checkStudent');
Route::post('staff-login','Api\Login\LoginTestController@checkStaff');
Route::get('student-logout','Auth\LogoutController@logoutRIT');


Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Tools Degree Type
    Route::apiResource('tools-degree-types', 'ToolsDegreeTypeApiController');

    // Academic Year
    Route::apiResource('academic-years', 'AcademicYearApiController');

    // Batch
    Route::apiResource('batches', 'BatchApiController');

    // Tools Course
    Route::apiResource('tools-courses', 'ToolsCourseApiController');

    // Tools Department
    Route::apiResource('tools-departments', 'ToolsDepartmentApiController');

    // Section
    Route::apiResource('sections', 'SectionApiController');

    // Semester
    Route::apiResource('semesters', 'SemesterApiController');

    // Course Enroll Master
    Route::apiResource('course-enroll-masters', 'CourseEnrollMasterApiController');

    // Toolssyllabus Year
    Route::apiResource('toolssyllabus-years', 'ToolssyllabusYearApiController');

    // Academic Details
    Route::apiResource('academic-details', 'AcademicDetailsApiController');

    // Personal Details
    Route::apiResource('personal-details', 'PersonalDetailsApiController');

    // Educational Details
    Route::apiResource('educational-details', 'EducationalDetailsApiController');

    // Nationality
    Route::apiResource('nationalities', 'NationalityApiController');

    // Religion
    Route::apiResource('religions', 'ReligionApiController');

    // Blood Group
    Route::apiResource('blood-groups', 'BloodGroupApiController');

    // Community
    Route::apiResource('communities', 'CommunityApiController');

    // Mother Tongue
    Route::apiResource('mother-tongues', 'MotherTongueApiController');

    // Education Board
    Route::apiResource('education-boards', 'EducationBoardApiController');

    // Student
    Route::apiResource('students', 'StudentApiController');

    // Education Type
    Route::apiResource('education-types', 'EducationTypeApiController');

    // Scholarship
    Route::apiResource('scholarships', 'ScholarshipApiController');

    // Subject
    Route::apiResource('subjects', 'SubjectApiController');

    // Mediumof Studied
    Route::apiResource('mediumof-studieds', 'MediumofStudiedApiController');

    // Address
    Route::apiResource('addresses', 'AddressApiController');

    // Parent Details
    Route::apiResource('parent-details', 'ParentDetailsApiController');

    // Bank Account Details
    Route::apiResource('bank-account-details', 'BankAccountDetailsApiController');

    // Experience Details
    Route::apiResource('experience-details', 'ExperienceDetailsApiController');

    // Teaching Staff
    Route::apiResource('teaching-staffs', 'TeachingStaffApiController');

    // Non Teaching Staff
    Route::apiResource('non-teaching-staffs', 'NonTeachingStaffApiController');

    // Teaching Type
    Route::apiResource('teaching-types', 'TeachingTypeApiController');

    // Examstaff
    Route::apiResource('examstaffs', 'ExamstaffApiController');

    // Add Conference
    Route::apiResource('add-conferences', 'AddConferenceApiController');

    // Entrance Exams
    Route::apiResource('entrance-exams', 'EntranceExamsApiController');

    // Guest Lecture
    Route::apiResource('guest-lectures', 'GuestLectureApiController');

    // Industrial Training
    Route::apiResource('industrial-trainings', 'IndustrialTrainingApiController');

    // Intern
    Route::apiResource('interns', 'InternApiController');

    // Industrial Experience
    Route::apiResource('industrial-experiences', 'IndustrialExperienceApiController');

    // Iv
    Route::apiResource('ivs', 'IvApiController');

    // Online Course
    Route::apiResource('online-courses', 'OnlineCourseApiController');

    // Documents
    Route::post('documents/media', 'DocumentsApiController@storeMedia')->name('documents.storeMedia');
    Route::apiResource('documents', 'DocumentsApiController');

    // Seminar
    Route::apiResource('seminars', 'SeminarApiController');

    // Saboticals
    Route::apiResource('saboticals', 'SaboticalsApiController');

    // Sponser
    Route::apiResource('sponsers', 'SponserApiController');

    // Sttp
    Route::apiResource('sttps', 'SttpApiController');

    // Workshop
    Route::apiResource('workshops', 'WorkshopApiController');

    // Patents
    Route::apiResource('patents', 'PatentsApiController');

    // Assets History
    Route::apiResource('assets-histories', 'AssetsHistoryApiController', ['except' => ['store', 'show', 'update', 'destroy']]);

    // Leave Type
    Route::apiResource('leave-types', 'LeaveTypeApiController');

    // Leave Staff Allocation
    Route::apiResource('leave-staff-allocations', 'LeaveStaffAllocationApiController');

    // College Block
    Route::apiResource('college-blocks', 'CollegeBlockApiController');

    // Scholorship
    Route::apiResource('scholorships', 'ScholorshipApiController');

    // Leave Status
    Route::apiResource('leave-statuses', 'LeaveStatusApiController', ['except' => ['destroy']]);

    // Od Master
    Route::apiResource('od-masters', 'OdMasterApiController');

    // Class Rooms
    Route::apiResource('class-rooms', 'ClassRoomsApiController');

    // Email Settings
    Route::apiResource('email-settings', 'EmailSettingsApiController');

    // Sms Settings
    Route::apiResource('sms-settings', 'SmsSettingsApiController');

    // Sms Templates
    Route::apiResource('sms-templates', 'SmsTemplatesApiController');

    // Settings
    Route::apiResource('settings', 'SettingsApiController');

    // Take Attentance Student
    Route::apiResource('take-attentance-students', 'TakeAttentanceStudentApiController');

    // Email Templates
    Route::apiResource('email-templates', 'EmailTemplatesApiController');

    // Od Request
    Route::apiResource('od-requests', 'OdRequestApiController');

    // Internship Request
    Route::apiResource('internship-requests', 'InternshipRequestApiController');

    // College Calender
    Route::apiResource('college-calenders', 'CollegeCalenderApiController');

    // Hrm Request Permission
    Route::apiResource('hrm-request-permissions', 'HrmRequestPermissionApiController');

    // Hrm Request Leave
    Route::apiResource('hrm-request-leaves', 'HrmRequestLeaveApiController');

    // Payment Gateway
    Route::apiResource('payment-gateways', 'PaymentGatewayApiController');

    // Staff Transfer Info
    Route::apiResource('staff-transfer-infos', 'StaffTransferInfoApiController');

     // Staff Biometric Data
     Route::apiResource('staff-biometric-data', 'StaffBiometricApiController');
});
