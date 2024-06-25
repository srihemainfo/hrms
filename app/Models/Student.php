<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'students';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'Name',
        'Roll_No',
        'Register_No',
        'Admitted_Mode',
        'Batch',
        'Admitted_Course',
        'Academic_Year',
        'Current_Semester',
        'Section',
        'Emis_Number',
        'Student_Phone_No',
        'Student_Email',
        'Aadhar_Card_No',
        'Whatsapp_No',
        'Date_Of_Birth',
        'Dayscholar_or_Hosteler',
        'Gender',
        'Blood_Group',
        'Mother_Tongue',
        'Religion',
        'Caste',
        'Community',
        'State',
        'Nationality',
        'Later_Entry',
        'First_Graduate',
        'Different_Abled_Person',
        'Father_Name',
        'Father_Mobile_No',
        'Father_Email',
        'Father_Occupation',
        'Father_Off_Address',
        'Mother_Name',
        'Mother_Mobile_No',
        'Mother_Email',
        'Mother_Occupation',
        'Mother_Off_Address',
        'Guardian_Name',
        'Guardian_Mobile_No',
        'Guardian_Email',
        'Guardian_Occupation',
        'Guardian_Off_Address',
        'Annual_Income',
        'Education_Type(SSLC)',
        'Institute_Name(SSLC)',
        'Institute_Location(SSLC)',
        'Board(SSLC)',
        'Register_Number(SSLC)',
        'Medium_Of_Studied(SSLC)',
        'Passing_Year(SSLC)',
        'Total_Marks(SSLC)',
        'Total_Marks_In_Percentage(SSLC)',
        'Cutoff_Mark(SSLC)',
        'Subject_1(SSLC)',
        'Mark_1(SSLC)',
        'Subject_2(SSLC)',
        'Mark_2(SSLC)',
        'Subject_3(SSLC)',
        'Mark_3(SSLC)',
        'Subject_4(SSLC)',
        'Mark_4(SSLC)',
        'Subject_5(SSLC)',
        'Mark_5(SSLC)',
        'Education_Type(HSC)',
        'Institute_Name(HSC)',
        'Institute_Location(HSC)',
        'Board(HSC)',
        'Register_Number(HSC)',
        'Medium_Of_Studied(HSC)',
        'Passing_Year(HSC)',
        'Total_Marks(HSC)',
        'Total_Marks_In_Percentage(HSC)',
        'Cutoff_Mark(HSC)',
        'Subject_1(HSC)',
        'Mark_1(HSC)',
        'Subject_2(HSC)',
        'Mark_2(HSC)',
        'Subject_3(HSC)',
        'Mark_3(HSC)',
        'Subject_4(HSC)',
        'Mark_4(HSC)',
        'Subject_5(HSC)',
        'Mark_5(HSC)',
        'Subject_6(HSC)',
        'Mark_6(HSC)',
        'Education_Type(DIPLOMA)',
        'Institute_Name(DIPLOMA)',
        'Institute_Location(DIPLOMA)',
        'Board_or_University(DIPLOMA)',
        'Register_Number(DIPLOMA)',
        'Medium_Of_Studied(DIPLOMA)',
        'Passing_Year(DIPLOMA)',
        'Total_Marks(DIPLOMA)',
        'Total_Marks_In_Percentage(DIPLOMA)',
        'Cutoff_Mark(DIPLOMA)',
        'Address_Type(Permanent)',
        'Room_No_and_Street(Permanent)',
        'Area_Name(Permanent)',
        'District(Permanent)',
        'Pincode(Permanent)',
        'State(Permanent)',
        'Country(Permanent)',
        'Address_Type(Temporary)',
        'Room_No_and_Street(Temporary)',
        'Area_Name(Temporary)',
        'District(Temporary)',
        'Pincode(Temporary)',
        'State(Temporary)',
        'Country(Temporary)',
        'enroll_master_id',
        'student_batch',
        'student_email_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'student_batch',
        'Foundation_Name',
        // 'Scholarship',
        // 'Hosteler',
        // 'GQG',
        // 'MQ',
        // 'admitted_mode',
        // 'qualifying_examination',
        // 'later_entry_(y/n)',
        // 'day_scholar/hosteler',
        // 'gender',
        // 'father_name',
        // 'mother_name',
        // 'guardian_name_(if_applicable)',
        // 'occupation_of_parent_or_guardian',
        // 'annual_income',
        // 'communication_address(permanent)',
        // 'communication_address(temp)',
        // 'city/town/village_name(temp)',
        // 'city/town/village_name',
        // 'district',
        // 'pincode',
        // 'state',
        // 'district(temp)',
        // 'pincode(temp)',
        // 'state(temp)',
        // 'father_phone_no',
        // 'mother_phone_no',
        // 'parent_email_id',
        // 'date_of_birth',
        // 'nationality',
        // 'religion',
        // 'community',
        // 'blood_group',
        // 'mother_tongue',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function enroll_master()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master_id');
    }

    public function subjectRegistrations()
    {
        return $this->belongsTo(SubjectRegistration::class, 'user_name_id');
    }
    public function attendances()
    {
        return $this->belongsTo(AttendenceTable::class, 'student');
    }
    public function personal_details()
    {
        return $this->belongsTo(PersonalDetail::class, 'user_name_id', 'user_name_id');
    }
    public function documents()
    {
        return $this->belongsTo(Document::class, 'user_name_id', 'nameofuser_id');
    }
    public function parent_details()
    {
        return $this->belongsTo(ParentDetail::class, 'user_name_id', 'user_name_id');
    }
}
