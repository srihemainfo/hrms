<?php

namespace App\Http\Controllers\Traits;

use App\Models\AcademicDetail;
use App\Models\AcademicFee;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\Examattendance;
use App\Models\ExamattendanceData;
use App\Models\ExamRegistration;
use App\Models\ExamResultPublish;
use App\Models\ExamTimetableCreation;
use App\Models\GradeBook;
use App\Models\GradeMaster;
use App\Models\NonTeachingStaff;
use App\Models\PermissionRequest;
use App\Models\PersonalDetail;
use App\Models\Role;
use App\Models\Scholarship;
use App\Models\Semester;
use App\Models\StaffBiometric;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectAllotment;
use App\Models\SubjectCategory;
use App\Models\SubjectRegistration;
use App\Models\SubjectType;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\ToolssyllabusYear;
use App\Models\User;
use App\Models\UserAlert;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SpreadsheetReader;

ini_set('max_execution_time', 3600);
trait CsvImportTrait
{
    public function processCsvImport(Request $request)
    {
        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            if (!$reader) {
                return redirect($request->input('redirect'))->with('error', 'Sheet Not Readable, Please Try Again.');
            }
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == "App\Models\Student") {
                // dd($row);
                foreach ($for_insert[0] as $insert) {
                    if ($insert['Foundation_Name'] != null && $insert['Foundation_Name'] != '') {
                        $check = Student::where(['register_no' => $insert['Register_No']])->select('user_name_id')->first();
                        if ($check != '') {
                            $theName = strtoupper($insert['Foundation_Name']);
                            $foundation = Scholarship::where('name', 'LIKE', "%{$theName}")->value('id');

                            AcademicDetail::where(['user_name_id' => $check->user_name_id])->update([
                                // 'admitted_mode' => $insert['MQ'] == 'MQ' ? 'MANAGEMENT QUOTA' : 'GENERAL QUOTA',
                                // 'first_graduate' => $insert['First_Graduate'] == 'Yes' ? 1 : 0,
                                // 'hosteler' => $insert['Hosteler'] == 'Yes' ? 1 : 0,
                                // 'gqg' => $insert['GQG'] == 'Yes' ? 1 : 0,
                                // 'scholarship' => $insert['Scholarship'] == 'Yes' ? 1 : 0,
                                'scholarship_name' => $foundation != '' ? $foundation : null,
                            ]);

                        }
                    }
                }
                // foreach ($for_insert[0] as $insert) {
                //     if ($insert['Date_Of_Birth'] != null && $insert['Date_Of_Birth'] != '') {
                //         $check = Student::where(['register_no' => $insert['Register_No']])->select('user_name_id')->first();
                //         if ($check != '') {
                //             $given_date = $insert['Date_Of_Birth'];
                //             $formattedDob = $detectedFormat = $formattedDate = $age = null;

                //             $formats = [
                //                 'd-m-Y',
                //                 'm/d/Y',
                //                 'd/m/Y',
                //             ];

                //             for ($i = 0; $i < count($formats); $i++) {
                //                 $dateTime = DateTime::createFromFormat($formats[$i], $given_date);
                //                 if ($dateTime != false) {
                //                     $formattedDob = $dateTime->format('Y-m-d');
                //                     $presentDate = new DateTime();
                //                     break;
                //                 }
                //             }
                //             $update = PersonalDetail::where(['user_name_id' => $check->user_name_id])->update([
                //                 'dob' => $formattedDob,
                //             ]);
                //         }
                //     }
                // }
                $import_status = null;
                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['Name'] != '' && $insert['Register_No'] != '' && $insert['Student_Email'] != '') {
                        $email_validate = DB::select("SELECT * FROM users WHERE email = :email", ['email' => $insert['Student_Email']]);
                        //    dd($email_validate);
                        $user_name = null;

                        if (count($email_validate) > 0) {
                            $user_name = $email_validate[0]->name;
                        }

                        if (count($email_validate) < 1) {
                            // $check_user = User::where(['register_no' => $insert['Register_No']])->first();
                            $reg_validate = DB::select("SELECT * FROM users WHERE register_no = :register_no", ['register_no' => $insert['Register_No']]);
                            //    dd($email_validate);
                            if (count($reg_validate) > 0) {
                                $user_name = $reg_validate[0]->name;
                            }
                            if (empty($reg_validate)) {

                                // dd('stop');

                                $user = new User;
                                $user->name = $insert['Name'];
                                $user->register_no = $insert['Register_No'];
                                $user->email = $insert['Student_Email'];
                                $user->password = bcrypt($insert['Student_Phone_No']);
                                $user->save();

                                $admin = Role::select('id')
                                    ->where('title', 'Student')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                                // $rols

                                $get_short_form = $insert['Admitted_Course'] ?? null;

                                if ($get_short_form != null) {
                                    $get_course = ToolsCourse::where('short_form', 'LIKE', "%{$get_short_form}")->first();
                                } else {
                                    $get_course = '';
                                }

                                if ($get_course != '') {
                                    $course = $get_course->name ?? null;
                                } else {
                                    $course = null;
                                }

                                $batch = $insert['Batch'] ?? null;
                                $accademicYear = $insert['Academic_Year'] ?? null;
                                $semester = $insert['Current_Semester'] ?? null;
                                $section = $insert['Section'] ?? null;

                                $enrollMaster = $batch . '/' . $course . '/' . $accademicYear . '/' . $semester . '/' . $section;
                                $id = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMaster}%")->latest()
                                    ->first();

                                $enroll = null;

                                if (!empty($id)) {
                                    $enroll = $id->id;
                                }

                                $studentcreate = new Student;
                                $studentcreate->name = $insert['Name'];
                                // $studentcreate->roll_no = $insert['Roll_No'] ?? null;
                                $studentcreate->register_no = $insert['Register_No'] ?? null;
                                $studentcreate->student_phone_no = $insert['Student_Phone_No'] ?? null;
                                $studentcreate->student_email_id = $insert['Student_Email'] ?? null;
                                $studentcreate->student_batch = $batch ?? null;
                                $studentcreate->admitted_course = $course ?? null;
                                $studentcreate->enroll_master_id = $enroll;
                                $studentcreate->user_name_id = $user->id;
                                $studentcreate->save();

                                $age = $formattedDob = $blood_group = $mother_tongue = $religion = $community = null;

                                if ($insert['Date_Of_Birth'] != null && $insert['Date_Of_Birth'] != '') {
                                    $given_date = $insert['Date_Of_Birth'];
                                    $formattedDate = null;

                                    $formats = [
                                        'd-m-Y',
                                        'd/m/Y',
                                    ];

                                    for ($i = 0; $i < count($formats); $i++) {
                                        $dateTime = DateTime::createFromFormat($formats[$i], $given_date);
                                        if ($dateTime != false) {
                                            $formattedDob = $dateTime->format('Y-m-d');
                                            break;
                                        }
                                    }
                                }
                                if ($insert['Blood_Group'] != null && $insert['Blood_Group'] != '') {
                                    $blood = $insert['Blood_Group'];
                                    $get_blood_id = BloodGroup::where('name', 'like', "%{$blood}%")->first();
                                    if ($get_blood_id != '') {
                                        $blood_group = $get_blood_id->id;
                                    }
                                }

                                if ($insert['Mother_Tongue'] != null && $insert['Mother_Tongue'] != '') {
                                    $mt = $insert['Mother_Tongue'];
                                    $get_mother_tongue_id = MotherTongue::where('mother_tongue', 'like', "%{$mt}%")->first();
                                    if ($get_mother_tongue_id != '') {
                                        $mother_tongue = $get_mother_tongue_id->id;
                                    }
                                }

                                if ($insert['Religion'] != null && $insert['Religion'] != '') {
                                    $relig = $insert['Religion'];
                                    $get_religion_id = Religion::where('name', 'like', "%{$relig}%")->first();
                                    if ($get_religion_id != '') {
                                        $religion = $get_religion_id->id;
                                    }
                                }

                                if ($insert['Community'] != null && $insert['Community'] != '') {
                                    $commu = $insert['Community'];
                                    $get_community_id = Community::where('name', 'like', "%{$commu}%")->first();
                                    if ($get_community_id != '') {
                                        $community = $get_community_id->id;
                                    }
                                }

                                $personalDetails = new PersonalDetail;
                                $personalDetails->user_name_id = $user->id;
                                $personalDetails->name = $insert['Name'];
                                $personalDetails->mobile_number = $insert['Student_Phone_No'] ?? null;
                                $personalDetails->aadhar_number = $insert['Aadhar_Card_No'] ?? null;
                                $personalDetails->email = $insert['Student_Email'] ?? null;
                                $personalDetails->dob = $formattedDob;
                                $personalDetails->age = $age;
                                $personalDetails->caste = $insert['Caste'] ?? null;
                                $personalDetails->different_abled_person = $insert['Different_Abled_Person'] ?? '0';
                                $personalDetails->gender = $insert['Gender'] ?? null;
                                $personalDetails->blood_group_id = $blood_group;
                                $personalDetails->mother_tongue_id = $mother_tongue;
                                $personalDetails->religion_id = $religion;
                                $personalDetails->community_id = $community;
                                $personalDetails->state = $insert['State'] ?? null;
                                $personalDetails->country = $insert['Nationality'] ?? null;
                                $personalDetails->whatsapp_no = $insert['Whatsapp_No'] ?? null;
                                $personalDetails->annual_income = $insert['Annual_Income'] ?? null;
                                $personalDetails->save();

                                $academicDetails = new AcademicDetail;
                                $academicDetails->register_number = $insert['Register_No'] ?? null;
                                $academicDetails->emis_number = $insert['Emis_Number'] ?? null;
                                $personalDetails->late_entry = $insert['Later_Entry'] ?? '0';
                                $personalDetails->hosteler = $insert['Dayscholar_or_Hosteler'] ?? '0';
                                $personalDetails->first_graduate = $insert['First_Graduate'] ?? '0';
                                $personalDetails->scholarship = $insert['Scholarship'] ?? '0';
                                $personalDetails->gqg = $insert['GQG'] ?? '0';
                                $academicDetails->admitted_course = $get_course->id ?? null;
                                $academicDetails->admitted_mode = $insert['Admitted_Mode'] ?? null;
                                $academicDetails->enroll_master_number_id = $enroll;
                                $academicDetails->user_name_id = $user->id;
                                $academicDetails->save();

                                $medium = MediumofStudied::get();

                                $sslc_medium = $hsc_medium = $dip_medium = $medium_sslc = $medium_hsc = $medium_dip = null;

                                if ($insert['Medium_Of_Studied(SSLC)'] != '') {
                                    $sslc_medium = $insert['Medium_Of_Studied(SSLC)'];
                                }
                                if ($insert['Medium_Of_Studied(HSC)'] != '') {
                                    $hsc_medium = $insert['Medium_Of_Studied(HSC)'];
                                }
                                if ($insert['Medium_Of_Studied(DIPLOMA)'] != '') {
                                    $dip_medium = $insert['Medium_Of_Studied(DIPLOMA)'];
                                }
                                foreach ($medium as $data) {
                                    if ($sslc_medium == $data->medium) {
                                        $medium_sslc = $data->id;
                                    }
                                    if ($hsc_medium == $data->medium) {
                                        $medium_hsc = $data->id;
                                    }
                                    if ($dip_medium == $data->medium) {
                                        $medium_dip = $data->id;
                                    }
                                }

                                if (isset($insert['Education_Type(SSLC)'])) {
                                    $stu_education_1 = EducationalDetail::create([
                                        'education_type_id' => 2,
                                        'user_name_id' => $user->id,
                                        'institute_name' => $insert['Institute_Name(SSLC)'],
                                        'institute_location' => $insert['Institute_Location(SSLC)'],
                                        'board_or_university' => $insert['Board(SSLC)'],
                                        'medium_id' => $medium_sslc,
                                        'register_number' => $insert['Register_Number(SSLC)'],
                                        'marks' => $insert['Total_Marks(SSLC)'],
                                        'cutoffmark' => $insert['Cutoff_Mark(SSLC)'],
                                        'marks_in_percentage' => $insert['Total_Marks_In_Percentage(SSLC)'],
                                        'passing_year' => $insert['Passing_Year(SSLC)'],
                                        'subject_1' => $insert['Subject_1(SSLC)'],
                                        'mark_1' => $insert['Mark_1(SSLC)'],
                                        'subject_2' => $insert['Subject_2(SSLC)'],
                                        'mark_2' => $insert['Mark_2(SSLC)'],
                                        'subject_3' => $insert['Subject_3(SSLC)'],
                                        'mark_3' => $insert['Mark_3(SSLC)'],
                                        'subject_4' => $insert['Subject_4(SSLC)'],
                                        'mark_4' => $insert['Mark_4(SSLC)'],
                                        'subject_5' => $insert['Subject_5(SSLC)'],
                                        'mark_5' => $insert['Mark_5(SSLC)'],
                                    ]);
                                }

                                if (isset($insert['Education_Type(HSC)'])) {
                                    $stu_education_2 = EducationalDetail::create([
                                        'education_type_id' => 1,
                                        'user_name_id' => $user->id,
                                        'institute_name' => $insert['Institute_Name(HSC)'],
                                        'institute_location' => $insert['Institute_Location(HSC)'],
                                        'board_or_university' => $insert['Board(HSC)'],
                                        'medium_id' => $medium_hsc,
                                        'register_number' => $insert['Register_Number(HSC)'],
                                        'marks' => $insert['Total_Marks(HSC)'],
                                        'cutoffmark' => $insert['Cutoff_Mark(HSC)'],
                                        'marks_in_percentage' => $insert['Total_Marks_In_Percentage(HSC)'],
                                        'passing_year' => $insert['Passing_Year(HSC)'],
                                        'subject_1' => $insert['Subject_1(HSC)'],
                                        'mark_1' => $insert['Mark_1(HSC)'],
                                        'subject_2' => $insert['Subject_2(HSC)'],
                                        'mark_2' => $insert['Mark_2(HSC)'],
                                        'subject_3' => $insert['Subject_3(HSC)'],
                                        'mark_3' => $insert['Mark_3(HSC)'],
                                        'subject_4' => $insert['Subject_4(HSC)'],
                                        'mark_4' => $insert['Mark_4(HSC)'],
                                        'subject_5' => $insert['Subject_5(HSC)'],
                                        'mark_5' => $insert['Mark_5(HSC)'],
                                        'subject_6' => $insert['Subject_6(HSC)'],
                                        'mark_6' => $insert['Mark_6(HSC)'],
                                    ]);
                                }

                                if (isset($insert['Education_Type(DIPLOMA)'])) {
                                    $stu_education_3 = EducationalDetail::create([
                                        'education_type_id' => 5,
                                        'user_name_id' => $user->id,
                                        'institute_name' => $insert['Institute_Name(DIPLOMA)'],
                                        'institute_location' => $insert['Institute_Location(DIPLOMA)'],
                                        'board_or_university' => $insert['Board_or_University(DIPLOMA)'],
                                        'medium_id' => $medium_dip,
                                        'register_number' => $insert['Register_Number(DIPLOMA)'],
                                        'marks' => $insert['Total_Marks(DIPLOMA)'],
                                        'cutoffmark' => $insert['Cutoff_Mark(DIPLOMA)'],
                                        'marks_in_percentage' => $insert['Total_Marks_In_Percentage(DIPLOMA)'],
                                        'passing_year' => $insert['Passing_Year(DIPLOMA)'],
                                    ]);
                                }

                                $stu_parent = new ParentDetail;
                                $stu_parent->father_name = $insert['Father_Name'] ?? null;
                                $stu_parent->mother_name = $insert['Mother_Name'] ?? null;
                                $stu_parent->guardian_name = $insert['Guardian_Name'] ?? null;
                                $stu_parent->father_mobile_no = $insert['Father_Mobile_No'] ?? null;
                                $stu_parent->father_email = $insert['Father_Email'] ?? null;
                                $stu_parent->mother_mobile_no = $insert['Mother_Mobile_No'] ?? null;
                                $stu_parent->mother_email = $insert['Mother_Email'] ?? null;
                                $stu_parent->guardian_mobile_no = $insert['Guardian_Mobile_No'] ?? null;
                                $stu_parent->guardian_email = $insert['Guardian_Email'] ?? null;
                                $stu_parent->fathers_occupation = $insert['Father_Occupation'] ?? null;
                                $stu_parent->mothers_occupation = $insert['Mother_Occupation'] ?? null;
                                $stu_parent->father_off_address = $insert['Father_Off_Address'] ?? null;
                                $stu_parent->mother_off_address = $insert['Mother_Off_Address'] ?? null;
                                $stu_parent->gaurdian_occupation = $insert['Guardian_Occupation'] ?? null;
                                $stu_parent->guardian_off_address = $insert['Guardian_Off_Address'] ?? null;
                                $stu_parent->user_name_id = $user->id;
                                $stu_parent->save();

                                if (isset($insert['Address_Type(Permanent)'])) {
                                    $stu_address = new Address;
                                    $stu_address->address_type = 'Permanent';
                                    $stu_address->room_no_and_street = $insert['Room_No_and_Street(Permanent)'] ?? null;
                                    $stu_address->area_name = $insert['Area_Name(Permanent)'] ?? null;
                                    $stu_address->district = $insert['District(Permanent)'] ?? null;
                                    $stu_address->pincode = $insert['Pincode(Permanent)'] ?? null;
                                    $stu_address->state = $insert['State(Permanent)'] ?? null;
                                    $stu_address->country = $insert['Country(Permanent)'] ?? null;
                                    $stu_address->name_id = $user->id;
                                    $stu_address->save();
                                }

                                if (isset($insert['Address_Type(Temporary)'])) {
                                    $stu_address = new Address;
                                    $stu_address->address_type = 'Temporary';
                                    $stu_address->room_no_and_street = $insert['Room_No_and_Street(Temporary)'] ?? null;
                                    $stu_address->area_name = $insert['Area_Name(Temporary)'] ?? null;
                                    $stu_address->district = $insert['District(Temporary)'] ?? null;
                                    $stu_address->pincode = $insert['Pincode(Temporary)'] ?? null;
                                    $stu_address->state = $insert['State(Temporary)'] ?? null;
                                    $stu_address->country = $insert['Country(Temporary)'] ?? null;
                                    $stu_address->name_id = $user->id;
                                    $stu_address->save();
                                }
                                $balance_row--;
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                $import_status = 'Error';
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Register No : ' . $insert['Register_No'] . ' Already Registered For  ' . $user_name);
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            $import_status = 'Error';
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Email : ' . $insert['Student_Email'] . ' Already Registered For  ' . $user_name);
                        }
                        // dd($balance_row,$inserted_rows);
                    }
                }
                $inserted_rows = $rows - $balance_row;
                if ($import_status == null) {
                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                }
            } elseif ($request->modelName == 'TeachingStaff') {

                $import_status = null;
                $balance_row = $rows;
                $inserted_rows = $rows - $balance_row;

                foreach ($for_insert[0] as $insert) {

                    $check_user = User::where(['employID' => $insert['StaffCode']])->first();

                    $get_Dept = ToolsDepartment::where('name', 'LIKE', $insert['Dept'])->get();

                    if (count($get_Dept) > 0) {

                        if (empty($check_user)) {

                            $balance_row--;

                            $user = new User;
                            $user->name = $insert['name'] . ' ' . $insert['last_name'];
                            $user->email = $insert['EmailIDOffical'];
                            $user->employID = $insert['StaffCode'];
                            $user->password = bcrypt($insert['ContactNo']);
                            $user->save();

                            if ($insert['Designation'] == 'Assistant Professor') {

                                $admin = Role::select('id')
                                    ->where('title', 'Assistant Professor')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Professor') {
                                $admin = Role::select('id')
                                    ->where('title', 'Professor')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Sr. Associate Professor') {
                                $admin = Role::select('id')
                                    ->where('title', 'Sr. Associate Professor')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Associate Professor') {
                                $admin = Role::select('id')
                                    ->where('title', 'Associate Professor')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Assistant Professor (SS)') {
                                $admin = Role::select('id')
                                    ->where('title', 'Assistant Professor (SS)')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Professor & Dean - Academics') {
                                $admin = Role::select('id')
                                    ->where('title', 'Professor & Dean - Academics')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Assistant Professor (SG)') {
                                $admin = Role::select('id')
                                    ->where('title', 'Assistant Professor (SG)')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Associate Professor & Head') {
                                $admin = Role::select('id')
                                    ->where('title', 'Associate Professor & Head')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Director') {
                                $admin = Role::select('id')
                                    ->where('title', 'Director')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'HOD') {
                                $admin = Role::select('id')
                                    ->where('title', 'HOD')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Principal') {
                                $admin = Role::select('id')
                                    ->where('title', 'Principal')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            } elseif ($insert['Designation'] == 'Dean') {
                                $admin = Role::select('id')
                                    ->where('title', 'Dean')
                                    ->latest()
                                    ->first();
                                $role_id = $admin->id;
                                $user->roles()->sync($request->input('roles', $role_id));
                            }

                            $staffCreate = new TeachingStaff;
                            $staffCreate->name = $insert['name'] . ' ' . $insert['last_name'];
                            $staffCreate->StaffCode = $insert['StaffCode'];
                            $staffCreate->Designation = $insert['Designation'];
                            $staffCreate->Dept = $insert['Dept'];
                            $staffCreate->ContactNo = $insert['ContactNo'];
                            $staffCreate->EmailIDOffical = $insert['EmailIDOffical'];
                            $staffCreate->user_name_id = $user->id;
                            $staffCreate->save();

                            $personalDetails = new PersonalDetail();
                            $personalDetails->name = $insert['name'];
                            $personalDetails->last_name = $insert['last_name'];
                            $personalDetails->email = $insert['EmailIDOffical'];
                            $personalDetails->mobile_number = $insert['ContactNo'];
                            $personalDetails->StaffCode = $insert['StaffCode'];
                            // $personalDetails->BiometricID = $insert['BiometricID'];
                            $personalDetails->user_name_id = $user->id;
                            $personalDetails->save();
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            $import_status = 'Error';
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Staff Code : ' . $insert['StaffCode'] . ' Already Registered For ' . $check_user->name);
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        $import_status = 'Error';
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', $insert['Dept'] . 'Not Found In Deparments For ' . $check_user->name);
                    }
                }
                $inserted_rows = $rows - $balance_row;
                if ($import_status == null) {
                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                }
            } elseif ($request->modelName == 'StaffBiometric') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ((isset($insert['date']) && isset($insert['staff_code'])) && ($insert['date'] != '' && $insert['staff_code'])) {
                        $staff_code = preg_replace('/\s+/', '', $insert['staff_code']);
                        $user = User::where(['employID' => $staff_code])->value('id');
                        if ($user != null) {
                            $in_time = '00:00:00';
                            $out_time = '00:00:00';

                            if(isset($insert['in_time'])){
                                $insert['in_time'] = str_replace(' ', '', $insert['in_time']);
                                $in_time = $insert['in_time'] != '' ? $insert['in_time'] :'00:00:00' ;
                            }
                            if(isset($insert['out_time'])){
                                $insert['out_time'] = str_replace(' ', '', $insert['out_time']);
                                $out_time = $insert['out_time'] != '' ? $insert['out_time'] :'00:00:00' ;
                            }

                            $permission = '';
                            $details = '';
                            if ($in_time != '00:00:00' && $out_time != '00:00:00') {
                                $in = strtotime($in_time);
                                $out = strtotime($out_time);

                                $duration_seconds = $out - $in;

                                $total_hours = gmdate('H:i:s', $duration_seconds);

                                if (strtotime($in_time) > strtotime('08:00:00') && strtotime($in_time) <= strtotime('08:15:00')) {
                                    if ($details == '') {
                                        $details .= 'Late';
                                    } else {
                                        $details .= ',Late';
                                    }
                                } else if (strtotime($in_time) > strtotime('08:15:00')) {
                                    if ($details == '') {
                                        $details .= 'Too Late';
                                    } else {
                                        $details .= ',Too Late';
                                    }

                                }
                                $status = 'Present';
                            } else {
                                $total_hours = '00:00:00';
                                $status = 'Absent';
                            }

                            $day_punches = $insert['day_punches'] == '' ? null : $insert['day_punches'];

                            $given_date = $insert['date'];
                            $formattedDate = null;

                            $formats = [
                                'd-m-y',
                                'd-m-Y',
                                'd/m/y',
                                'd/m/Y',
                            ];

                            foreach ($formats as $i => $format) {
                                try {
                                    $the_date = Carbon::createFromFormat($format, $given_date);

                                    // Extract only the date part
                                    $dateOnly = $the_date->format('Y-m-d');
                                    //   echo 'no: '.$i;
                                    if ($dateOnly != '') {
                                        $formattedDate = $dateOnly;
                                        break;
                                    }
                                } catch (Exception $e) {
                                    // Do nothing, just continue to the next format
                                }
                            }

                            if ($formattedDate != null) {

                                $staff_biometric = StaffBiometric::where(['date' => $formattedDate, 'user_name_id' => $user])->select('id', 'details', 'update_status', 'shift', 'status', 'in_time', 'out_time', 'total_hours', 'day_punches', 'updated_at', 'permission', 'import')->first();

                                if ($staff_biometric != '') {
                                    if ($staff_biometric->import != 1) {
                                        // if (strpos($staff_biometric->details, 'Sunday') === false && $staff_biometric->details != 'Holiday') {
                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Sunday,Admin OD' && $staff_biometric->details != 'Sunday,Exam OD' && $staff_biometric->details != 'Sunday,Training OD' && $staff_biometric->details != 'Admin OD,Sunday' && $staff_biometric->details != 'Exam OD,Sunday' && $staff_biometric->details != 'Training OD,Sunday' && $staff_biometric->details != 'Holiday' && $staff_biometric->details != 'Holiday,Admin OD' && $staff_biometric->details != 'Holiday,Exam OD' && $staff_biometric->details != 'Holiday,Training OD' && $staff_biometric->details != 'Admin OD,Holiday' && $staff_biometric->details != 'Exam OD,Holiday' && $staff_biometric->details != 'Training OD,Holiday') {
                                            $get = PermissionRequest::where(['user_name_id' => $user, 'date' => $formattedDate, 'Permission' => 'On Duty', 'status' => 2])->select('from_time', 'to_time')->first();
                                            if ($get == '' && $staff_biometric->permission != 'OD Permission') {
                                                if ($staff_biometric->permission == 'FN Permission') {
                                                    $details = '';
                                                } else if ($staff_biometric->permission != 'AN Permission') {

                                                    if ($staff_biometric->shift == 1) {
                                                        if ($out_time != '00:00:00') {
                                                            if (strtotime($out_time) < strtotime('16:00:00')) {
                                                                if ($details == '') {
                                                                    if ($staff_biometric->details != null && strpos($staff_biometric->details, 'After Noon') === false) {
                                                                        $status = 'Absent';
                                                                        $details = 'Early Out';
                                                                    } else if ($staff_biometric->details == null) {
                                                                        $status = 'Absent';
                                                                        $details = 'Early Out';
                                                                    }
                                                                } else {
                                                                    if ($staff_biometric->details != null && strpos($staff_biometric->details, 'After Noon') === false) {
                                                                        $status = 'Absent';
                                                                        $details .= ',Early Out';
                                                                    } else if ($staff_biometric->details == null) {
                                                                        $status = 'Absent';
                                                                        $details .= ',Early Out';
                                                                    }
                                                                }

                                                            }
                                                        }
                                                    } else if ($staff_biometric->shift == 2) {
                                                        if ($out_time != '00:00:00') {
                                                            if (strtotime($out_time) < strtotime('17:00:00')) {
                                                                if ($details == '') {
                                                                    if ($staff_biometric->details != null && strpos($staff_biometric->details, 'After Noon') === false) {
                                                                        $status = 'Absent';
                                                                        $details = 'Early Out';
                                                                    } else if ($staff_biometric->details == null) {
                                                                        $status = 'Absent';
                                                                        $details = 'Early Out';
                                                                    }
                                                                } else {
                                                                    if ($staff_biometric->details != null && strpos($staff_biometric->details, 'After Noon') === false) {
                                                                        $status = 'Absent';
                                                                        $details .= ',Early Out';
                                                                    } else if ($staff_biometric->details == null) {
                                                                        $status = 'Absent';
                                                                        $details .= ',Early Out';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    if ($staff_biometric->details != null && strpos($staff_biometric->details, 'Fore Noon') !== false) {
                                                        $details = '';
                                                    }
                                                }
                                            } else {
                                                if (($details == 'Late' || $details == 'Too Late') && (strtotime($in_time) >= strtotime($get->from_time) && strtotime($in_time) <= strtotime($get->to_time))) {
                                                    $details = '';
                                                } else if (strtotime($out_time) <= strtotime($get->to_time)) {
                                                    $details = '';
                                                }
                                            }
                                            if ($staff_biometric->details != null) {
                                                if ($details != '') {
                                                    $tempDetail = $staff_biometric->details . ',' . $details;
                                                } else {
                                                    $tempDetail = $staff_biometric->details;
                                                }
                                            } else {
                                                $tempDetail = $details != '' ? $details : null;
                                            }
                                        } else {
                                            $tempDetail = $staff_biometric->details;
                                        }

                                        $staff_biometric->in_time = $in_time;
                                        $staff_biometric->out_time = $out_time;
                                        $staff_biometric->total_hours = $total_hours;
                                        $staff_biometric->status = $status;
                                        $staff_biometric->details = $tempDetail;
                                        $staff_biometric->day_punches = $day_punches;
                                        $staff_biometric->import = 1;
                                        $staff_biometric->updated_at = Carbon::now();
                                        $staff_biometric->save();
                                    }
                                    $balance_row--;
                                    $details = '';
                                } else {
                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => 'Staff Biometric']));
                                    return redirect($request->input('redirect'))->with('error', 'Staff Biometric Not Found For ' . $insert['staff_code'] . ' On ' . $insert['date']);
                                }
                                $formattedDate = null;
                            }
                        }

                        //  else {
                        //     $inserted_rows = $rows - $balance_row;
                        //     session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => 'Subject Master']));
                        //     return redirect($request->input('redirect'))->with('error', 'Staff Not Found By ' . $insert['staff_code']);
                        // }
                    }

                }

                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\SubjectMaster") {

                $balance_row = $rows;
                foreach ($for_insert[0] as $insert) {
                    if ($insert['Regulation'] != '' && $insert['Department'] != '' && $insert['Course'] != '' && $insert['Subject_Code'] != '' && $insert['Subject_Name'] != '') {

                        $semester = $course = $dept = $regulation = $subject_type = $subject_cat = null;

                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['Regulation']}%")->first();
                        $get_dept = ToolsDepartment::where('name', 'like', "%{$insert['Department']}%")->first();
                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['Course']}")->first();

                        if ($get_dept != '') {
                            $dept = $get_dept->id;
                        }

                        if ($get_course != '') {
                            $course = $get_course->id;
                        }

                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                            // dd($regulation);
                            $check_subject = Subject::where(['regulation_id' => $regulation, 'subject_code' => $insert['Subject_Code']])->get();
                            if (count($check_subject) > 0) {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => 'Subject Master']));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code : ' . $insert['Subject_Code'] . ' Already Registered For ' . $check_subject[0]->name);
                            }
                            if ($insert['Subject_Type'] != '') {
                                $get_subject_type = SubjectType::where('name', 'like', "%{$insert['Subject_Type']}%")->where(['regulation_id' => $regulation])->first();
                                if ($get_subject_type != '') {
                                    $subject_type = $get_subject_type->id;
                                }
                            }
                        }

                        if ($insert['Subject_Category'] != '') {
                            $get_subject_cat = SubjectCategory::where('name', 'like', "%{$insert['Subject_Category']}%")->first();
                            if ($get_subject_cat != '') {
                                $subject_cat = $get_subject_cat->id;
                            }
                        }

                        if ($insert['Semester'] != '') {
                            $get_semester = Semester::where('semester', $insert['Semester'])->first();
                            if ($get_semester != '') {
                                $semester = $get_semester->id;
                            }
                        }

                        $get_subject = Subject::where(['subject_code' => $insert['Subject_Code'], 'regulation_id' => $regulation])->first();

                        if ($get_subject == '') {

                            $subject = new Subject;
                            $subject->subject_code = $insert['Subject_Code'] ?? null;
                            $subject->name = $insert['Subject_Name'] ?? null;
                            $subject->regulation_id = $regulation;
                            $subject->department_id = $dept;
                            $subject->course_id = $course;
                            $subject->semester_id = $semester;
                            $subject->subject_type_id = $subject_type;
                            $subject->subject_cat_id = $subject_cat;
                            $subject->lecture = $insert['Lecture'] ?? null;
                            $subject->tutorial = $insert['Tutorial'] ?? null;
                            $subject->practical = $insert['Practical'] ?? null;
                            $subject->credits = $insert['Credits'] ?? null;
                            $subject->contact_periods = $insert['Contact_Periods'] ?? null;
                            $subject->created_at = Carbon::now();
                            $subject->save();

                            $balance_row--;
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == 'App\Models\SubjectAllotment') {
                $balance_row = $rows;
                // dd($for_insert[0]);
                foreach ($for_insert[0] as $insert) {

                    if ($insert['regulation'] != '' && $insert['department'] != '' && $insert['course'] != '' && $insert['academic_year'] != '' && $insert['semester'] != '' && $insert['category'] != '' && $insert['subject_code'] != '' && $insert['subject_name'] != '' && $insert['subject_type'] != '') {

                        $semester = $course = $dept = $regulation = $subject_type = $ay = null;

                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        $get_dept = ToolsDepartment::where('name', 'like', "%{$insert['department']}%")->first();
                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_subject_type = SubjectType::where('name', 'like', "%{$insert['subject_type']}%")->first();
                        $get_semester = Semester::where('semester', $insert['semester'])->first();
                        $get_ay = AcademicYear::where('name', 'like', "%{$insert['academic_year']}%")->first();

                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        }

                        if ($get_dept != '') {
                            $dept = $get_dept->id;
                        }

                        if ($get_course != '') {
                            $course = $get_course->id;
                        }

                        if ($get_subject_type != '') {
                            $subject_type = $get_subject_type->id;
                        }

                        if ($get_semester != '') {
                            $semester = $get_semester->id;
                        }

                        if ($get_ay != '') {
                            $ay = $get_ay->id;
                        }

                        if ($insert['category'] == 'Regular Subject' || $insert['category'] == 'Honor Degree') {
                            $limit = 0;
                        } else {
                            $limit = $insert['option_limits'];
                        }
                        $subCode = trim($insert['subject_code'], ' ');
                        $get_subject = Subject::where(['subject_code' => $subCode, 'regulation_id' => $regulation])->first();

                        if ($get_subject != '') {
                            $checkAllot = SubjectAllotment::where(['regulation' => $regulation, 'department' => $dept, 'course' => $course, 'academic_year' => $ay, 'semester' => $semester, 'subject_id' => $get_subject->id])->select('id')->get();
                            if (count($checkAllot) <= 0) {
                                $subject_allotment = new SubjectAllotment;
                                $subject_allotment->regulation = $regulation;
                                $subject_allotment->department = $dept;
                                $subject_allotment->course = $course;
                                $subject_allotment->academic_year = $ay;
                                $subject_allotment->semester_type = $insert['semester_type'];
                                $subject_allotment->semester = $semester;
                                $subject_allotment->category = $insert['category'];
                                $subject_allotment->subject_id = $get_subject->id;
                                $subject_allotment->credits = $get_subject->credits;
                                $subject_allotment->option_limits = $limit == '' ? null : $limit;
                                $subject_allotment->save();
                                $balance_row--;
                            }
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\SubjectRegistration") {
                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['student_name'] != '' && $insert['register_no'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['academic_year'] != '' && $insert['semester'] != '' && $insert['section'] != '' && $insert['category'] != '' && $insert['subject_code'] != '' && $insert['regulation'] != '') {
                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        } else {
                            $regulation = null;
                        }

                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_ay = AcademicYear::where('name', 'like', "%{$insert['academic_year']}")->first();
                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->first();

                        $course = null;

                        if ($get_course != '') {
                            $course = $get_course->name;
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Course Not Found.');
                        }
                        if ($get_ay != '' && $regulation != null) {
                            $checkAllotment = SubjectAllotment::where(['course' => $get_course->id, 'semester' => $insert['semester'], 'academic_year' => $get_ay->id, 'regulation' => $regulation, 'subject_id' => $get_subject->id])->count();
                            if ($checkAllotment <= 0) {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Allotment Not Found For ' . $insert['subject_code']);
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'AY Not Found.');
                        }
                        $enrollMaster = $insert['batch'] . '/' . $course . '/' . $insert['academic_year'] . '/' . $insert['semester'] . '/' . $insert['section'];

                        $enroll_master = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMaster}%")->select('id')->first();

                        if ($enroll_master != '') {
                            $get_student = Student::where(['register_no' => $insert['register_no'], 'enroll_master_id' => $enroll_master->id])->select('name', 'register_no', 'user_name_id')->first();
                            if ($get_student != '') {
                                if ($get_subject != '') {
                                    $check_registration = SubjectRegistration::where(['register_no' => $get_student->register_no, 'enroll_master' => $enroll_master->id, 'subject_id' => $get_subject->id])->get();

                                    if (count($check_registration) <= 0) {
                                        $registration = new SubjectRegistration;
                                        $registration->student_name = $get_student->name;
                                        $registration->register_no = $get_student->register_no;
                                        $registration->regulation = $regulation;
                                        $registration->user_name_id = $get_student->user_name_id;
                                        $registration->enroll_master = $enroll_master->id;
                                        $registration->category = $insert['category'];
                                        $registration->subject_id = $get_subject->id;
                                        $registration->status = 2;
                                        $registration->save();

                                        $userAlert = new UserAlert;
                                        $userAlert->alert_text = 'Your Subject Registration Done!';
                                        $userAlert->alert_link = url('admin/subject-registration/student');
                                        $userAlert->save();
                                        $userAlert->users()->sync($get_student->user_name_id);

                                        $balance_row--;
                                    }
                                } else {
                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                    return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found.');
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Class Couldn\'t Found For ' . $insert['student_name'] . '.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\BulkOD") {
                $balance_row = $rows;

                $students = [];
                foreach ($for_insert[0] as $insert) {
                    if ($insert['register_no'] != '') {

                        $get_student = Student::where(['register_no' => $insert['register_no']])->first();
                        if ($get_student != '') {
                            $user_name_id = $get_student->user_name_id;
                            $enroll_master = $get_student->enroll_master_id;

                            $get_enroll = CourseEnrollMaster::where(['id' => $enroll_master])->first();
                            if ($get_enroll != '') {
                                $explode = explode('/', $get_enroll->enroll_master_number);
                                $get_course = $explode[1];
                                $get_semester = $explode[3];
                                $get_section = $explode[4];
                                // dd($explode);
                                $get_dept = ToolsCourse::where(['name' => $get_course])->first();
                                if ($get_dept != '') {
                                    $dept = ToolsDepartment::where(['id' => $get_dept->department_id])->first();
                                    if ($dept != '') {
                                        $get_Dept = $dept->name;
                                    } else {
                                        $get_Dept = '';
                                    }
                                } else {
                                    $get_Dept = '';
                                }

                                array_push($students, ['name' => $get_student->name, 'user_name_id' => $get_student->user_name_id, 'register_no' => $get_student->register_no, 'dept' => $get_Dept, 'course' => $get_course, 'semester' => $get_semester, 'section' => $get_section]);
                            }
                        }

                        $balance_row--;
                    }
                }
                $inserted_rows = $rows - $balance_row;

                // $data = ['students' => json_encode($students), 'row_count' => $inserted_rows];
                return response()->json(['students' => $students, 'rows' => $inserted_rows]);
            } elseif ($model == "App\Models\NonTeachingStaff") {
                // dd($request);

                $import_status = null;
                $balance_row = $rows;
                $inserted_rows = $rows - $balance_row;

                foreach ($for_insert[0] as $insert) {
                    $check_user = User::where(['employID' => $insert['StaffCode']])->first();
                    if (empty($check_user)) {
                        $balance_row--;

                        $user = new User;
                        $user->name = ($insert['name'] != '' ? $insert['name'] : '') . ' ' . ($insert['last_name'] != '' ? $insert['last_name'] : '');
                        $user->email = $insert['email'] ?? null;
                        $user->employID = $insert['StaffCode'];
                        $user->password = bcrypt($insert['phone']);
                        $user->save();

                        if ($insert['Designation'] == 'Admin. Executive') {

                            $admin = Role::select('id')
                                ->where('title', 'Admin. Executive')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'System Administrator') {
                            $admin = Role::select('id')
                                ->where('title', 'System Administrator')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Senior Manager - HR') {
                            $admin = Role::select('id')
                                ->where('title', 'Senior Manager - HR')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Admin. Assistant') {
                            $admin = Role::select('id')
                                ->where('title', 'Admin. Assistant')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Librarian') {
                            $admin = Role::select('id')
                                ->where('title', 'Librarian')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'System Administrator') {
                            $admin = Role::select('id')
                                ->where('title', 'System Administrator')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Accountant') {
                            $admin = Role::select('id')
                                ->where('title', 'Accountant')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Accounts Executive') {
                            $admin = Role::select('id')
                                ->where('title', 'Accounts Executive')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Telecaller') {
                            $admin = Role::select('id')
                                ->where('title', 'Telecaller')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Administrative Officer') {
                            $admin = Role::select('id')
                                ->where('title', 'Administrative Officer')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Accounts Assistant') {
                            $admin = Role::select('id')
                                ->where('title', 'Accounts Assistant')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Assistant Librarian') {
                            $admin = Role::select('id')
                                ->where('title', 'Assistant Librarian')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Hostel Warden') {
                            $admin = Role::select('id')
                                ->where('title', 'Hostel Warden')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Mess Supervisor') {
                            $admin = Role::select('id')
                                ->where('title', 'Mess Supervisor')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Office Attender') {
                            $admin = Role::select('id')
                                ->where('title', 'Office Attender')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Physical Director') {
                            $admin = Role::select('id')
                                ->where('title', 'Physical Director')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Lab Assistant') {
                            $admin = Role::select('id')
                                ->where('title', 'Lab Assistant')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Lab Instructor') {
                            $admin = Role::select('id')
                                ->where('title', 'Lab Instructor')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Site Engineer') {
                            $admin = Role::select('id')
                                ->where('title', 'Site Engineer')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Housekeeping In-Charge') {
                            $admin = Role::select('id')
                                ->where('title', 'Housekeeping In-Charge')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Electrician') {
                            $admin = Role::select('id')
                                ->where('title', 'Electrician')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Garden Supervisor') {
                            $admin = Role::select('id')
                                ->where('title', 'Garden Supervisor')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        } elseif ($insert['Designation'] == 'Carpenter') {
                            $admin = Role::select('id')
                                ->where('title', 'Carpenter')
                                ->latest()
                                ->first();
                            $role_id = $admin->id;
                            $user->roles()->sync($request->input('roles', $role_id));
                        }

                        $staffCreate = new NonTeachingStaff;
                        $staffCreate->name = $insert['name'] ?? null;
                        $staffCreate->last_name = $insert['last_name'] ?? null;
                        $staffCreate->StaffCode = $insert['StaffCode'] ?? null;
                        $staffCreate->Designation = $insert['Designation'] ?? null;
                        $staffCreate->Dept = $insert['Dept'] ?? null;
                        $staffCreate->phone = $insert['phone'] ?? null;
                        $staffCreate->email = $insert['email'] ?? null;
                        $staffCreate->user_name_id = $user->id;
                        $staffCreate->save();

                        $personalDetails = new PersonalDetail();
                        $personalDetails->name = $insert['name'] ?? null;
                        $personalDetails->last_name = $insert['last_name'] ?? null;
                        $personalDetails->email = $insert['email'] ?? null;
                        $personalDetails->mobile_number = $insert['phone'] ?? null;
                        $personalDetails->StaffCode = $insert['StaffCode'] ?? null;
                        $personalDetails->user_name_id = $user->id;
                        $personalDetails->save();
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        $import_status = 'Error';
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Staff Code : ' . $insert['StaffCode'] . ' Already Registered For ' . $check_user->name);
                    }
                }
                $inserted_rows = $rows - $balance_row;
                if ($import_status == null) {
                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                }
            } elseif ($model == 'App\Models\ExamRegistration') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['regulation'] != '' && $insert['academic_year'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['semester'] != '' && $insert['register_no'] != '' && $insert['subject_code'] != '' && $insert['subject_name'] != '' && $insert['credits'] != '' && $insert['subject_type'] != '' && $insert['subject_sem'] != '' && $insert['exam_type'] != '' && $insert['exam_fee'] != '' && $insert['exam_month'] != '' && $insert['exam_year'] != '') {

                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        } else {
                            $regulation = null;
                        }
                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->select('id')->first();
                        $get_ay = AcademicYear::where(['name' => $insert['academic_year']])->select('id')->first();
                        $get_batch = Batch::where(['name' => $insert['batch']])->select('id')->first();
                        $course = null;
                        $ay = null;
                        $batch = null;
                        if ($get_ay != '') {
                            $ay = $get_ay->id;
                        }

                        if ($get_course != '') {
                            $course = $get_course->id;
                        }
                        if ($get_batch != '') {
                            $batch = $get_batch->id;
                        }

                        $get_student = Student::where(['register_no' => $insert['register_no']])->select('user_name_id')->first();

                        if ($get_student != '' && $get_student != null) {
                            if ($get_subject != '' && $get_subject != null) {
                                // $check_subRegistration = SubjectRegistration::where(['user_name_id' => $get_student->user_name_id,'register_no' => $insert['register_no'], 'subject_id' => $get_subject->id,'regulation' => $regulation])->select('id')->get();
                                // $check_subRegistration = SubjectRegistration::where(['user_name_id' => $get_student->user_name_id,'register_no' => $insert['register_no'], 'subject_id' => $get_subject->id])->select('id')->get();
                                // if (count($check_subRegistration) > 0) {

                                $check_examRegistration = ExamRegistration::where(['subject_id' => $get_subject->id, 'user_name_id' => $get_student->user_name_id])->select('id')->get();
                                // if ($i == 9) {
                                //     dd($get_subject, $get_student, $check_examRegistration);
                                // }

                                if (count($check_examRegistration) <= 0) {

                                    $registration = new ExamRegistration;
                                    $registration->regulation = $regulation;
                                    $registration->academic_year = $ay;
                                    $registration->batch = $batch;
                                    $registration->course = $course;
                                    $registration->semester = $insert['semester'];
                                    $registration->user_name_id = $get_student->user_name_id;
                                    $registration->subject_id = $get_subject->id;
                                    $registration->subject_name = $insert['subject_name'];
                                    $registration->subject_type = $insert['subject_type'];
                                    $registration->subject_sem = $insert['subject_sem'];
                                    $registration->credits = $insert['credits'];
                                    $registration->exam_type = $insert['exam_type'];
                                    $registration->exam_fee = $insert['exam_fee'];
                                    $registration->exam_month = $insert['exam_month'];
                                    $registration->exam_year = $insert['exam_year'];
                                    $registration->uploaded_date = Carbon::now()->format('Y-m-d');
                                    $registration->save();

                                    $balance_row--;
                                } else {

                                    $update = ExamRegistration::where(['id' => $check_examRegistration[0]->id])->update([
                                        'regulation' => $regulation,
                                        'academic_year' => $ay,
                                        'batch' => $batch,
                                        'course' => $course,
                                        'semester' => $insert['semester'],
                                        'subject_id' => $get_subject->id,
                                        'subject_name' => $insert['subject_name'],
                                        'subject_type' => $insert['subject_type'],
                                        'subject_sem' => $insert['subject_sem'],
                                        'credits' => $insert['credits'],
                                        'exam_type' => $insert['exam_type'],
                                        'exam_fee' => $insert['exam_fee'],
                                        'exam_month' => $insert['exam_month'],
                                        'exam_year' => $insert['exam_year'],
                                    ]);

                                    $balance_row--;
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Missing.');
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == 'App\Models\ExamResultPublish') {

                $balance_row = $rows;

                $course = null;
                $regulation = null;
                $ay = null;
                $batch = null;
                $formattedDate = null;
                $sub_1 = null;
                $sub_2 = null;
                $sub_3 = null;
                $sub_4 = null;
                $sub_5 = null;
                $sub_6 = null;
                $sub_7 = null;
                $sub_8 = null;
                $sub_9 = null;
                $sub_10 = null;
                $semester = null;
                $exam_month = null;
                $exam_year = null;
                $result_type = null;
                $gradeMaster = [];

                foreach ($for_insert[0] as $i => $insert) {

                    if ($i == 0) {
                        if ($insert['regulation'] != '' && $insert['academic_year'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['semester'] != '' && $insert['exam_month'] != '' && $insert['exam_year'] != '' && $insert['result_type'] != '' && $insert['publish_date'] != '') {
                            $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                            if ($get_regulation != '') {
                                $regulation = $get_regulation->id;
                                $getGrades = GradeMaster::where(['regulation_id' => $regulation])->pluck('id', 'grade_letter');
                                $gradeMaster = $getGrades->toArray();
                            } else {
                                $regulation = null;
                            }
                            $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                            $get_ay = AcademicYear::where(['name' => $insert['academic_year']])->select('id')->first();
                            $get_batch = Batch::where(['name' => $insert['batch']])->select('id')->first();

                            if ($get_ay != '') {
                                $ay = $get_ay->id;
                            }

                            if ($get_course != '') {
                                $course = $get_course->id;
                            }
                            if ($get_batch != '') {
                                $batch = $get_batch->id;
                            }

                            $given_date = $insert['publish_date'];

                            $formats = [
                                'd-m-y',
                                'd-m-Y',
                                'd/m/y',
                                'd/m/Y',
                            ];

                            foreach ($formats as $i => $format) {
                                try {
                                    $the_date = Carbon::createFromFormat($format, $given_date);

                                    $dateOnly = $the_date->format('Y-m-d');
                                    if ($dateOnly != '') {
                                        $formattedDate = $dateOnly;
                                        break;
                                    }
                                } catch (Exception $e) {
                                }
                            }
                            $semester = $insert['semester'];
                            $exam_month = $insert['exam_month'];
                            $exam_year = $insert['exam_year'];
                            $result_type = $insert['result_type'];
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Required Details Missing.');
                        }
                    } elseif ($i == 2) {
                        $subject_code_1 = $insert['academic_year'];
                        $subject_code_2 = $insert['course'];
                        $subject_code_3 = $insert['semester'];
                        $subject_code_4 = $insert['regulation'];
                        $subject_code_5 = $insert['exam_month'];
                        $subject_code_6 = $insert['exam_year'];
                        $subject_code_7 = $insert['result_type'];
                        $subject_code_8 = $insert['publish_date'];
                        $subject_code_9 = $insert['subjectcode_9'];
                        $subject_code_10 = $insert['subjectcode_10'];

                        if ($subject_code_1 != '') {
                            $get_subject_1 = Subject::where(['subject_code' => $subject_code_1, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_1 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_1 . ') Couldn\'t Found For.');
                            } else {
                                $sub_1 = $get_subject_1->id;
                            }
                        }

                        if ($subject_code_2 != '') {
                            $get_subject_2 = Subject::where(['subject_code' => $subject_code_2, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_2 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_2 . ') Couldn\'t Found.');
                            } else {
                                $sub_2 = $get_subject_2->id;
                            }
                        }

                        if ($subject_code_3 != '') {
                            $get_subject_3 = Subject::where(['subject_code' => $subject_code_3, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_3 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_3 . ') Couldn\'t Found.');
                            } else {
                                $sub_3 = $get_subject_3->id;
                            }
                        }

                        if ($subject_code_4 != '') {
                            $get_subject_4 = Subject::where(['subject_code' => $subject_code_4, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_4 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_4 . ') Couldn\'t Found.');
                            } else {
                                $sub_4 = $get_subject_4->id;
                            }
                        }

                        if ($subject_code_5 != '') {
                            $get_subject_5 = Subject::where(['subject_code' => $subject_code_5, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_5 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_5 . ') Couldn\'t Found.');
                            } else {
                                $sub_5 = $get_subject_5->id;
                            }
                        }

                        if ($subject_code_6 != '') {
                            $get_subject_6 = Subject::where(['subject_code' => $subject_code_6, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_6 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_6 . ') Couldn\'t Found.');
                            } else {
                                $sub_6 = $get_subject_6->id;
                            }
                        }

                        if ($subject_code_7 != '') {
                            $get_subject_7 = Subject::where(['subject_code' => $subject_code_7, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_7 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_7 . ') Couldn\'t Found.');
                            } else {
                                $sub_7 = $get_subject_7->id;
                            }
                        }

                        if ($subject_code_8 != '') {
                            $get_subject_8 = Subject::where(['subject_code' => $subject_code_8, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_8 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_8 . ') Couldn\'t Found.');
                            } else {
                                $sub_8 = $get_subject_8->id;
                            }
                        }

                        if ($subject_code_9 != '') {
                            $get_subject_9 = Subject::where(['subject_code' => $subject_code_9, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_9 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_9 . ') Couldn\'t Found.');
                            } else {
                                $sub_9 = $get_subject_9->id;
                            }
                        }

                        if ($subject_code_10 != '') {
                            $get_subject_10 = Subject::where(['subject_code' => $subject_code_10, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_10 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_10 . ') Couldn\'t Found.');
                            } else {
                                $sub_10 = $get_subject_10->id;
                            }
                        }
                    } elseif ($i > 2) {

                        if ($course != null && $regulation != null && $batch != null && $ay != null && $formattedDate != null && $semester != null && $exam_month != null && $exam_year != null && $result_type != null) {
                            $get_student = Student::where(['register_no' => $insert['batch']])->select('user_name_id')->first();
                            $gradeStatus = true;
                            if (count($gradeMaster) > 0) {
                                if ($insert['academic_year'] != '') {
                                    if (array_key_exists($insert['academic_year'], $gradeMaster)) {
                                        $grade_1 = $gradeMaster[$insert['academic_year']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_1 = null;
                                }
                                if ($insert['course'] != '') {
                                    if (array_key_exists($insert['course'], $gradeMaster)) {
                                        $grade_2 = $gradeMaster[$insert['course']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_2 = null;
                                }

                                if ($insert['semester'] != '') {
                                    if (array_key_exists($insert['semester'], $gradeMaster)) {
                                        $grade_3 = $gradeMaster[$insert['semester']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_3 = null;
                                }

                                if ($insert['regulation'] != '') {
                                    if (array_key_exists($insert['regulation'], $gradeMaster)) {
                                        $grade_4 = $gradeMaster[$insert['regulation']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_4 = null;
                                }

                                if ($insert['exam_month'] != '') {
                                    if (array_key_exists($insert['exam_month'], $gradeMaster)) {
                                        $grade_5 = $gradeMaster[$insert['exam_month']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_5 = null;
                                }

                                if ($insert['exam_year'] != '') {
                                    if (array_key_exists($insert['exam_year'], $gradeMaster)) {
                                        $grade_6 = $gradeMaster[$insert['exam_year']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_6 = null;
                                }

                                if ($insert['result_type'] != '') {
                                    if (array_key_exists($insert['result_type'], $gradeMaster)) {
                                        $grade_7 = $gradeMaster[$insert['result_type']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_7 = null;
                                }

                                if ($insert['publish_date'] != '') {
                                    if (array_key_exists($insert['publish_date'], $gradeMaster)) {
                                        $grade_8 = $gradeMaster[$insert['publish_date']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_8 = null;
                                }

                                if ($insert['subjectcode_9'] != '') {
                                    if (array_key_exists($insert['subjectcode_9'], $gradeMaster)) {
                                        $grade_9 = $gradeMaster[$insert['subjectcode_9']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_9 = null;
                                }

                                if ($insert['subjectcode_10'] != '') {
                                    if (array_key_exists($insert['subjectcode_10'], $gradeMaster)) {
                                        $grade_10 = $gradeMaster[$insert['subjectcode_10']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_10 = null;
                                }

                                if ($gradeStatus == false) {
                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                    return redirect($request->input('redirect'))->with('error', 'Grade Not Found.');
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Grade Master Not Found.');
                            }

                            if ($get_student != '' && $get_student != null) {

                                $check_resultPublish = ExamResultPublish::where(['user_name_id' => $get_student->user_name_id, 'result_type' => $result_type, 'academic_year' => $ay, 'regulation' => $regulation, 'batch' => $batch, 'course' => $course, 'semester' => $semester, 'exam_month' => $exam_month, 'exam_year' => $exam_year])->select('id')->get();

                                if (count($check_resultPublish) <= 0) {

                                    $resultPublish = new ExamResultPublish;
                                    $resultPublish->regulation = $regulation;
                                    $resultPublish->academic_year = $ay;
                                    $resultPublish->batch = $batch;
                                    $resultPublish->course = $course;
                                    $resultPublish->semester = $semester;
                                    $resultPublish->register_no = $insert['batch'];
                                    $resultPublish->user_name_id = $get_student->user_name_id;
                                    $resultPublish->subject_1 = $sub_1;
                                    $resultPublish->grade_1 = $grade_1;
                                    $resultPublish->subject_2 = $sub_2;
                                    $resultPublish->grade_2 = $grade_2;
                                    $resultPublish->subject_3 = $sub_3;
                                    $resultPublish->grade_3 = $grade_3;
                                    $resultPublish->subject_4 = $sub_4;
                                    $resultPublish->grade_4 = $grade_4;
                                    $resultPublish->subject_5 = $sub_5;
                                    $resultPublish->grade_5 = $grade_5;
                                    $resultPublish->subject_6 = $sub_6;
                                    $resultPublish->grade_6 = $grade_6;
                                    $resultPublish->subject_7 = $sub_7;
                                    $resultPublish->grade_7 = $grade_7;
                                    $resultPublish->subject_8 = $sub_8;
                                    $resultPublish->grade_8 = $grade_8;
                                    $resultPublish->subject_9 = $sub_9;
                                    $resultPublish->grade_9 = $grade_9;
                                    $resultPublish->subject_10 = $sub_10;
                                    $resultPublish->grade_10 = $grade_10;
                                    $resultPublish->result_type = $result_type;
                                    $resultPublish->exam_month = $exam_month;
                                    $resultPublish->exam_year = $exam_year;
                                    $resultPublish->publish_date = $formattedDate;
                                    $resultPublish->uploaded_date = Carbon::now()->format('Y-m-d');
                                    $resultPublish->uploaded_by = auth()->user()->id;

                                    $resultPublish->save();

                                    $balance_row--;
                                } else {

                                    $update = ExamResultPublish::where(['id' => $check_resultPublish[0]->id])->update([
                                        'subject_1' => $sub_1,
                                        'grade_1' => $grade_1,
                                        'subject_2' => $sub_2,
                                        'grade_2' => $grade_2,
                                        'subject_3' => $sub_3,
                                        'grade_3' => $grade_3,
                                        'subject_4' => $sub_4,
                                        'grade_4' => $grade_4,
                                        'subject_5' => $sub_5,
                                        'grade_5' => $grade_5,
                                        'subject_6' => $sub_6,
                                        'grade_6' => $grade_6,
                                        'subject_7' => $sub_7,
                                        'grade_7' => $grade_7,
                                        'subject_8' => $sub_8,
                                        'grade_8' => $grade_8,
                                        'subject_9' => $sub_9,
                                        'grade_9' => $grade_9,
                                        'subject_10' => $sub_10,
                                        'grade_10' => $grade_10,
                                        'publish_date' => $formattedDate,
                                        'publish' => 0,
                                        'uploaded_date' => Carbon::now()->format('Y-m-d'),
                                        'updated_at' => Carbon::now(),
                                        'uploaded_by' => auth()->user()->id,
                                    ]);

                                    $balance_row--;
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student (' . $insert['batch'] . ') Not Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\ExamattendanceData") {
                $balance_row = $rows;
                $course = null;
                $ay = null;
                $batch = null;
                $semester = null;
                $section = null;
                $exam_id = null;
                $exam_name = null;
                $subject_id = null;
                $examDate = null;

                foreach ($for_insert[0] as $i => $insert) {

                    if ($i == 0) {
                        try {
                            if ($insert['ay'] == '') {
                                throw new Exception("AY Can't Be Empty");
                            } else if ($insert['batch'] == '') {
                                throw new Exception("Batch Can't Be Empty");
                            } else if ($insert['course'] == '') {
                                throw new Exception("Course Can't Be Empty");
                            } else if ($insert['semester'] == '') {
                                throw new Exception("Semester Can't Be Empty");
                            } else if ($insert['section'] == '') {
                                throw new Exception("Section Can't Be Empty");
                            }
                            $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                            $get_ay = AcademicYear::where(['name' => $insert['ay']])->select('id')->first();
                            $get_batch = Batch::where(['name' => $insert['batch']])->select('id')->first();

                            if ($get_ay != '') {
                                $ay = $get_ay->id;
                            }
                            if ($get_course != '') {
                                $course = $get_course->id;
                            }
                            if ($get_batch != '') {
                                $batch = $get_batch->id;
                            }

                            $semester = $insert['semester'];
                            $section = $insert['section'];
                        } catch (Exception $e) {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', $e->getMessage());
                        }
                    } elseif ($i == 1) {
                        if ($insert['ay'] != '' && $insert['semester'] != '' && $insert['section'] != '') {

                            $getSubject = Subject::where(['subject_code' => $insert['section']])->select('id')->first(); //->where('name','LIKE',"%{$insert['semester']}%")

                            if ($getSubject != '') {
                                $subject_id = $getSubject->id;
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Not Found.');
                            }
                            $getTimeTable = ExamTimetableCreation::where(['exam_name' => $insert['ay'], 'course' => $course, 'semester' => $semester, 'accademicYear' => $ay, 'sections' => $section])->select('id', 'subject')->first();
                            if ($getTimeTable != '') {
                                $exam_id = $getTimeTable->id;
                                $exam_name = $insert['ay'];

                                $examDates = unserialize($getTimeTable->subject);
                                foreach ($examDates as $dat) {
                                    if (array_key_exists($subject_id, $dat)) {
                                        $examDate = $dat[$subject_id];
                                    }
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Exam Time Table Not Found.');
                            }

                            $getExamAtt = Examattendance::where(['examename' => $insert['ay'], 'exame_id' => $exam_id, 'course' => $course, 'sem' => $semester, 'acyear' => $ay, 'section' => $section, 'subject' => $subject_id])->select('id', 'mark_entereby')->first();
                            if ($getExamAtt != '') {
                                $theExamId = $getExamAtt->id;
                                if ($getExamAtt->mark_entereby == null) {
                                    $updateExamAtt = Examattendance::where(['id' => $getExamAtt->id])->update([
                                        'mark_date' => Carbon::now(),
                                        'mark_entereby' => auth()->user()->id,
                                    ]);
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Exam Attendance Not Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Exam Name / Subject Details Missing.');
                        }
                    } elseif ($i > 1) {

                        if ($course != null && $batch != null && $ay != null && $semester != null && $theExamId != null && $subject_id != null && $examDate != null && $insert['co_4'] != null && $insert['co_5'] != null) {

                            $get_student = Student::where(['register_no' => $insert['course']])->select('user_name_id', 'enroll_master_id')->first();

                            if ($get_student != '' && $get_student != null) {

                                $checkData = ExamattendanceData::where(['student_id' => $get_student->user_name_id, 'examename' => $theExamId, 'subject' => $subject_id, 'class_id' => $get_student->enroll_master_id])->select('id', 'attendance')->get();

                                if (count($checkData) <= 0) {
                                    $store = ExamattendanceData::create([
                                        'date' => Carbon::now(),
                                        'enteredby' => 1,
                                        'class_id' => $get_student->enroll_master_id,
                                        'subject' => $subject_id,
                                        'attendance' => 'Present',
                                        'examename' => $theExamId,
                                        'student_id' => $get_student->user_name_id,
                                        'exame_date' => $examDate,
                                        'co_4' => $insert['co_4'],
                                        'co_5' => $insert['co_5'],
                                    ]);
                                    $balance_row--;
                                } else {
                                    $temp_attendance = 'Present';
                                    $tempco4 = $insert['co_4'];
                                    $tempco5 = $insert['co_5'];
                                    if ($checkData[0]->attendance == 'Absent') {
                                        $temp_attendance = 'Absent';
                                        $tempco4 = 999;
                                        $tempco5 = 999;
                                    }

                                    $update = ExamattendanceData::where(['student_id' => $get_student->user_name_id, 'examename' => $theExamId, 'subject' => $subject_id, 'class_id' => $get_student->enroll_master_id])->update([
                                        'attendance' => $temp_attendance,
                                        'co_4' => $tempco4,
                                        'co_5' => $tempco5,
                                    ]);
                                    $balance_row--;
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student (' . $insert['course'] . ') Not Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\AcademicFee") {
                $balance_row = $rows;
                foreach ($for_insert[0] as $insert) {

                    if ($insert['Register_No'] != '' && $insert['Academic_Year'] != '') {
                        $theAy = AcademicYear::where(['name' => $insert['Academic_Year']])->value('id');
                        if ($theAy != null) {
                            $theStudent = Student::where(['register_no' => $insert['Register_No']])->value('user_name_id');
                            if ($theStudent != null) {
                                $update = [];

                                if (isset($insert['tuition_fee']) && $insert['tuition_fee'] != '') {
                                    $update['tuition_fee'] = $insert['tuition_fee'];
                                }

                                if (isset($insert['hostel_fee']) && $insert['hostel_fee'] != '') {
                                    $update['hostel_fee'] = $insert['hostel_fee'];
                                }

                                if (isset($insert['other_fee']) && $insert['other_fee'] != '') {
                                    $update['other_fee'] = $insert['other_fee'];
                                }

                                if (isset($insert['fine']) && $insert['fine'] != '') {
                                    $update['fine'] = $insert['fine'];
                                }

                                $updateAct = AcademicFee::where([
                                    'user_name_id' => $theStudent,
                                    'ay' => $theAy,
                                ])->update($update);
                                if (!$updateAct) {
                                    $update['user_name_id'] = $theStudent;
                                    $update['ay'] = $theAy;
                                    AcademicFee::create($update);
                                }
                                $balance_row--;
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student Not Found For ' . $insert['Register_No']);
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Academic Year Not Found For ' . $insert['Register_No']);
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }

                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\GradeBook") {
                $balance_row = $rows;

                $gradeArray = [];
                $getAy = AcademicYear::select('name', 'id')->get();
                $getBatch = Batch::select('name', 'id')->get();
                $getRegulation = ToolssyllabusYear::select('name', 'id')->get();
                $getCourse = ToolsCourse::select('short_form', 'id')->get();
                $theAy = [];
                $theBatch = [];
                $theRegulation = [];
                $theCourse = [];
                $subjectArray = [];
                $gradeArray = [];

                foreach ($getAy as $ay) {

                    $theAy[$ay->name] = $ay->id;
                }
                foreach ($getBatch as $bat) {

                    $theBatch[$bat->name] = $bat->id;
                }
                foreach ($getRegulation as $regu) {

                    $theRegulation[$regu->name] = $regu->id;
                }
                foreach ($getCourse as $cour) {

                    $theCourse[$cour->short_form] = $cour->id;
                }
                $subjects = Subject::select('id', 'subject_code', 'regulation_id')->get();
                foreach ($subjects as $subject) {
                    $tempReg = array_search($subject->regulation_id, $theRegulation);
                    $subjectArray[$tempReg][$subject->subject_code] = $subject->id;
                }
                $grades = GradeMaster::select('id', 'grade_letter', 'regulation_id')->get();
                foreach ($grades as $grade) {
                    $tempReg = array_search($grade->regulation_id, $theRegulation);
                    $gradeArray[$tempReg][$grade->grade_letter] = $grade->id;
                }

                foreach ($for_insert[0] as $i => $insert) {

                    if ($insert['batch'] != '' && $insert['academic_year'] != '' && $insert['regulation'] != '' && $insert['semester'] != '' && $insert['result_type'] != '' && $insert['course'] != '' && $insert['exam_month'] != '' && $insert['published_date'] != '' && $insert['exam_year'] != '') {

                        $Ay = $theAy[$insert['academic_year']];
                        $Batch = $theBatch[$insert['batch']];
                        $Regulation = $theRegulation[$insert['regulation']];
                        $Course = $theCourse[$insert['course']];
                        $published_date = null;
                        $given_date = $insert['published_date'];
                        $semester = $insert['semester'];
                        $exam_date = $insert['exam_month'] . ' ' . $insert['exam_year'];
                        $exam_month = $insert['exam_month'];
                        $exam_year = $insert['exam_year'];
                        $result_type = $insert['result_type'];
                        $formats = [
                            'd-m-y',
                            'd-m-Y',
                            'd/m/y',
                            'd/m/Y',
                        ];

                        foreach ($formats as $i => $format) {
                            try {
                                $the_date = Carbon::createFromFormat($format, $given_date);

                                $dateOnly = $the_date->format('Y-m-d');
                                if ($dateOnly != '') {
                                    $published_date = $dateOnly;
                                    break;
                                }
                            } catch (Exception $e) {
                            }
                        }

                        try {
                            if ($published_date != null) {
                                if (count($subjectArray) > 0 && array_key_exists($insert['regulation'], $subjectArray) && array_key_exists($insert['subject_code'], $subjectArray[$insert['regulation']])) {
                                    if (array_key_exists($insert['regulation'], $gradeArray) && array_key_exists($insert['grade'], $gradeArray[$insert['regulation']])) {
                                        $theStudent = Student::where(['register_no' => $insert['register_no']])->value('user_name_id');
                                        if ($theStudent != null) {

                                            // $checkGradeBook = GradeBook::where(['user_name_id' => $theStudent, 'batch' => $Batch, 'academic_year' => $Ay, 'regulation' => $Regulation, 'semester' => $semester, 'subject' => $subjectArray[$insert['regulation']][$insert['subject_code']], 'course' => $Course])->select('id', 'exam_date')->first();
                                            $checkGradeBook = GradeBook::where(['user_name_id' => $theStudent, 'batch' => $Batch, 'academic_year' => $Ay, 'regulation' => $Regulation, 'semester' => $semester, 'subject' => $subjectArray[$insert['regulation']][$insert['subject_code']], 'course' => $Course])->count();
                                            // if ($checkGradeBook != null && $checkGradeBook != '') {
                                            //     $getExamDate = explode(' ', $checkGradeBook->exam_date);

                                            //     $examMonthInNum = date('m', strtotime($exam_month));
                                            //     $theGotExMonInNum = date('m', strtotime($getExamDate[0]));
                                            //     if ($examMonthInNum > $theGotExMonInNum && ((int) $exam_year >= (int) $getExamDate[1])) {
                                            //         $updateGradeBook = GradeBook::where(['id' => $checkGradeBook->id])->update([
                                            //             'grade' => $gradeArray[$insert['regulation']][$insert['grade']],
                                            //             'result_type' => $result_type,
                                            //             'exam_date' => $exam_date,
                                            //             'published_date' => $published_date,
                                            //             'import' => 1,
                                            //         ]);
                                            //     }
                                            //     $balance_row--;
                                            // } else
                                            if($checkGradeBook == 0) {

                                                $store = GradeBook::create([
                                                    'user_name_id' => $theStudent,
                                                    'course' => $Course,
                                                    'batch' => $Batch,
                                                    'academic_year' => $Ay,
                                                    'regulation' => $Regulation,
                                                    'semester' => $semester,
                                                    'result_type' => $result_type,
                                                    'exam_date' => $exam_date,
                                                    'published_date' => $published_date,
                                                    'subject' => $subjectArray[$insert['regulation']][$insert['subject_code']],
                                                    'grade' => $gradeArray[$insert['regulation']][$insert['grade']],
                                                    'import' => 1,
                                                ]);
                                                $balance_row--;
                                            }

                                        } else {
                                            throw new Exception('Student Not Found For ' . $insert['batch']);
                                        }
                                    } else {
                                        throw new Exception('Grade Not Found');
                                    }
                                } else {
                                    throw new Exception("Subject (".$insert['subject_code'].") Not Found For This Regulation (".$insert['regulation'].") On Row No " . ($rows - $balance_row) + 1);
                                }
                            } else {
                                throw new Exception("Not a Valid Date On Row No " . ($rows - $balance_row) + 1);
                            }

                        } catch (Exception $e) {

                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => 'Grade Book']));
                            return redirect($request->input('redirect'))->with('error', $e->getMessage());
                        }

                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => 'Grade Book ']));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }

                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => 'Grade Book ']));
            } else {

                $balance_row = $rows;

                foreach ($for_insert as $insert_item) {
                    $balance_row--;
                    $model::insert($insert_item);
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            }
            return redirect($request->input('redirect'));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseCsvImport(Request $request)
    {

        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.processCsvImport';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    public function removeProcessCsvImport(Request $request)
    {

        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == 'App\Models\ExamRegistration') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['regulation'] != '' && $insert['academic_year'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['semester'] != '' && $insert['register_no'] != '' && $insert['subject_code'] != '' && $insert['subject_name'] != '' && $insert['credits'] != '' && $insert['subject_type'] != '' && $insert['subject_sem'] != '' && $insert['exam_type'] != '' && $insert['exam_fee'] != '') {

                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                            return redirect($request->input('redirect'))->with('error', 'Regulation Couldn\'t Found.');
                        }

                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->select('id')->first();

                        $get_student = Student::where(['register_no' => $insert['register_no']])->select('user_name_id')->first();

                        if ($get_student != '' && $get_student != null) {
                            if ($get_subject != '' && $get_subject != null) {

                                $check_examRegistration = ExamRegistration::where(['subject_id' => $get_subject->id, 'user_name_id' => $get_student->user_name_id])->select('id')->get();

                                if (count($check_examRegistration) > 0) {

                                    $update = ExamRegistration::where(['id' => $check_examRegistration[0]->id])->delete();
                                    $balance_row--;
                                } else {

                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                                    return redirect($request->input('redirect'))->with('error', 'Exam Registration Couldn\'t Found.');
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                                return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                            return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Missing.');
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                return redirect($request->input('redirect'));
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseCsvImportOD(Request $request)
    {
        // phpinfo();
        // dd($request);
        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.processCsvImport';

        return response()->json(['headers' => $headers, 'filename' => $filename, 'fillables' => $fillables, 'hasHeader' => $hasHeader, 'modelName' => $modelName, 'lines' => $lines, 'redirect' => $redirect, 'routeName' => $routeName]);
    }

    public function parseCsvRemovalExamReg(Request $request)
    {
        // phpinfo();
        // dd($request);
        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.removeProcessCsvImport';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    public function parseCsvRemovalSubjectReg(Request $request)
    {

        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.removeProcessCsvImportSub';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    public function removeProcessCsvImportSub(Request $request)
    {
        // dd($request);
        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == 'App\Models\SubjectRegistration') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['student_name'] != '' && $insert['register_no'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['academic_year'] != '' && $insert['semester'] != '' && $insert['section'] != '' && $insert['category'] != '' && $insert['subject_code'] != '' && $insert['regulation'] != '') {
                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;

                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                            return redirect($request->input('redirect'))->with('error', 'Regulation Couldn\'t Found.');
                        }

                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->first();

                        $course = null;

                        if ($get_course != '') {
                            $course = $get_course->name;
                        }
                        $enrollMaster = $insert['batch'] . '/' . $course . '/' . $insert['academic_year'] . '/' . $insert['semester'] . '/' . $insert['section'];

                        $enroll_master = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMaster}%")->select('id')->first();

                        if ($enroll_master != '') {
                            $get_student = Student::where(['register_no' => $insert['register_no'], 'enroll_master_id' => $enroll_master->id])->select('name', 'register_no', 'user_name_id')->first();

                            if ($get_student != '') {
                                if ($get_subject != '') {
                                    $check_registration = SubjectRegistration::where(['register_no' => $get_student->register_no, 'enroll_master' => $enroll_master->id, 'subject_id' => $get_subject->id])->get();

                                    if (count($check_registration) > 0) {

                                        SubjectRegistration::where(['register_no' => $get_student->register_no, 'enroll_master' => $enroll_master->id, 'subject_id' => $get_subject->id])->update([
                                            'deleted_at' => Carbon::now(),
                                        ]);

                                        $userAlert = new UserAlert;
                                        $userAlert->alert_text = 'Your Subject Registration Removed ';
                                        $userAlert->alert_link = url('admin/subject-registration/student');
                                        $userAlert->save();
                                        $userAlert->users()->sync($get_student->user_name_id);

                                        $balance_row--;
                                    }
                                } else {
                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                                    return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found.');
                                }
                            } else {

                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                                return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                            }
                        } else {

                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                            return redirect($request->input('redirect'))->with('error', 'Class Couldn\'t Found For ' . $insert['student_name'] . '.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                return redirect($request->input('redirect'));
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseCsvHonors(Request $request)
    {
        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.csvImportHonors';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    public function processCsvHonors(Request $request)
    {

        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == 'App\Models\SubjectRegistration') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['student_name'] != '' && $insert['register_no'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['academic_year'] != '' && $insert['semester'] != '' && $insert['section'] != '' && $insert['category'] != '' && $insert['subject_code'] != '' && $insert['regulation'] != '') {
                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->value('id');
                        if ($get_regulation != '') {
                            $regulation = $get_regulation;
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Regulation Not Found.');
                        }

                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_ay = AcademicYear::where('name', 'like', "%{$insert['academic_year']}")->first();
                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->first();

                        $course = null;

                        if ($get_course != '') {
                            $course = $get_course->name;
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Course Not Found.');
                        }
                        if ($get_ay == '') {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'AY Not Found.');
                        }
                        $enrollMaster = $insert['batch'] . '/' . $course . '/' . $insert['academic_year'] . '/' . $insert['semester'] . '/' . $insert['section'];

                        $enroll_master = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMaster}%")->value('id');

                        if ($enroll_master != '') {
                            $get_student = Student::where(['register_no' => $insert['register_no'], 'enroll_master_id' => $enroll_master])->select('name', 'register_no', 'user_name_id')->first();
                            if ($get_student != '') {
                                if ($get_subject != '') {
                                    $check_registration = SubjectRegistration::where(['register_no' => $get_student->register_no, 'enroll_master' => $enroll_master, 'subject_id' => $get_subject->id])->get();

                                    if (count($check_registration) <= 0) {
                                        $registration = new SubjectRegistration;
                                        $registration->student_name = $get_student->name;
                                        $registration->register_no = $get_student->register_no;
                                        $registration->regulation = $regulation;
                                        $registration->user_name_id = $get_student->user_name_id;
                                        $registration->enroll_master = $enroll_master;
                                        $registration->category = $insert['category'];
                                        $registration->subject_id = $get_subject->id;
                                        $registration->status = 2;   
                                        $registration->save();

                                        $userAlert = new UserAlert;
                                        $userAlert->alert_text = 'Your Subject Registration Done!';
                                        $userAlert->alert_link = url('admin/subject-registration/student');
                                        $userAlert->save();
                                        $userAlert->users()->sync($get_student->user_name_id);

                                        $balance_row--;
                                    }
                                } else {
                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                    return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found For ' . $insert['subject_code']);
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Class Couldn\'t Found For ' . $insert['student_name'] . '.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }
                $inserted_rows = $rows - $balance_row;

                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                return redirect($request->input('redirect'));
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseCsvImportPaid(Request $request)
    {

        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.fee-data-import.import-paid';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    public function parseCsvImportDeduct(Request $request)
    {

        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.fee-data-import.import-deduct';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    public function processCsvImportDeduct(Request $request)
    {

        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == 'App\Models\AcademicFee') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['Register_No'] != '' && $insert['Academic_Year'] != '' && $insert['Scholarship'] != '' && $insert['GQG'] != '' && $insert['FG'] != '') {
                        $theAy = AcademicYear::where(['name' => $insert['Academic_Year']])->value('id');
                        if ($theAy != null) {
                            $theStudent = Student::where(['register_no' => $insert['Register_No']])->value('user_name_id');
                            if ($theStudent != null) {
                                AcademicFee::where([
                                    'user_name_id' => $theStudent,
                                    'ay' => $theAy,
                                ])->update(['scholarship_amt' => (int) $insert['Scholarship'], 'gqg_amt' => (int) $insert['GQG'], 'fg_amt' => (int) $insert['FG']]);
                                $balance_row--;
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student Not Found For ' . $insert['Register_No']);
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Academic Year Not Found For ' . $insert['Register_No']);
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }

                $inserted_rows = $rows - $balance_row;

                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Academic Fee']));
                return redirect($request->input('redirect'));
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function processCsvImportPaid(Request $request)
    {
        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == 'App\Models\AcademicFee') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['Register_No'] != '' && $insert['Academic_Year'] != '' && $insert['Paid_Amount'] != '' && $insert['paid_date'] != '') {
                        $theAy = AcademicYear::where(['name' => $insert['Academic_Year']])->value('id');
                        if ($theAy != null) {
                            $theStudent = Student::where(['register_no' => $insert['Register_No']])->value('user_name_id');
                            if ($theStudent != null) {
                                $given_date = $insert['paid_date'];
                                $formattedDate = null;

                                $formats = [
                                    'd-m-y',
                                    'd-m-Y',
                                    'd/m/y',
                                    'd/m/Y',
                                ];

                                foreach ($formats as $i => $format) {
                                    try {
                                        $the_date = Carbon::createFromFormat($format, $given_date);

                                        // Extract only the date part
                                        $dateOnly = $the_date->format('Y-m-d');
                                        //   echo 'no: '.$i;
                                        if ($dateOnly != '') {
                                            $formattedDate = $dateOnly;
                                            break;
                                        }
                                    } catch (Exception $e) {
                                        // Do nothing, just continue to the next format
                                    }
                                }
                                $getPaidAmt = AcademicFee::where(['user_name_id' => $theStudent, 'ay' => $theAy])->value('paid_amt');
                                AcademicFee::where([
                                    'user_name_id' => $theStudent,
                                    'ay' => $theAy,
                                ])->update(['paid_amt' => ((int) $getPaidAmt + (int) $insert['Paid_Amount']), 'paid_date' => $formattedDate]);
                                $balance_row--;
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student Not Found For ' . $insert['Register_No']);
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Academic Year Not Found For ' . $insert['Register_No']);
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }

                $inserted_rows = $rows - $balance_row;

                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Academic Fee']));
                return redirect($request->input('redirect'));
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
