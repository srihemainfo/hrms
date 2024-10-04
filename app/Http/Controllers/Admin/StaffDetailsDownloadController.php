<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTeachingStaffRequest;
use App\Http\Requests\StoreTeachingStaffRequest;
use App\Http\Requests\UpdateTeachingStaffRequest;
use App\Models\Address;
use App\Models\Award;
use App\Models\BankAccountDetail;
use App\Models\BloodGroup;
use App\Models\Community;
use App\Models\Document;
use App\Models\EducationalDetail;
use App\Models\EducationType;
use App\Models\EntranceExam;
use App\Models\EventOrganized;
use App\Models\EventParticipation;
use App\Models\Events;
use App\Models\Examstaff;
use App\Models\ExperienceDetail;
use App\Models\HrmRequestLeaf;
use App\Models\IndustrialExperience;
use App\Models\IndustrialTraining;
use App\Models\Intern;
use App\Models\Iv;
use App\Models\LeaveType;
use App\Models\MediumofStudied;
use App\Models\MotherTongue;
use App\Models\NonTeachingStaff;
use App\Models\OnlineCourse;
use App\Models\Patent;
use App\Models\PermissionRequest;
use App\Models\PersonalDetail;
use App\Models\PhdDetail;
use App\Models\PromotionDetails;
use App\Models\PublicationDetail;
use App\Models\Religion;
use App\Models\Role;
use App\Models\Sabotical;
use App\Models\Sponser;
use App\Models\StaffSalary;
use App\Models\Sttp;
use App\Models\TeachingStaff;
use App\Models\ToolsDepartment;
use App\Models\User;
use App\Models\Workshop;
use App\Models\StaffDetailsDownload;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class StaffDetailsDownloadController extends Controller
{
    public function index()
    {


        $title = StaffDetailsDownload::pluck('name', 'id');
        return view('admin.staffDetailsDownload.index', compact('title'));
    }
    public function personal_details(Request $request)
    {

        // abort_if(Gate::denies('personal_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $download_id = $request->personal;
        // if ($request->ajax()) {
        if ($download_id == 1) {
            $columns = ['user_name_id', 'name', 'last_name', 'StaffCode', 'department', 'email', 'mobile_number', 'aadhar_number', 'emergency_contact_no', 'total_experience', 'PanNo', 'COECode', 'PassportNo', 'father_name', 'spouse_name', 'dob', 'age', 'blood_group_id', 'mother_tongue_id', 'religion_id', 'community_id', 'gender', 'marital_status', 'state', 'country', 'known_languages'];
        } elseif ($download_id == 2) {

            $columns = ['user_name_id', 'name', 'last_name', 'BiometricID', 'AICTE', 'DOJ', 'DOR', 'au_card_no', 'employment_type', 'employment_status', 'rit_club_incharge', 'future_tech_membership', 'future_tech_membership_type'];
        } elseif ($download_id == 3) {

            $columns = ['experience_details.user_name_id', 'personal_details.name', 'personal_details.StaffCode', 'experience_details.designation', 'experience_details.department', 'experience_details.name_of_organisation', 'experience_details.taken_subjects', 'experience_details.doj', 'personal_details.DOR', 'experience_details.last_drawn_salary', 'experience_details.responsibilities', 'experience_details.leaving_reason', 'experience_details.address'];
        } elseif ($download_id == 4) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'promotion_details.current_designation', 'promotion_details.promoted_designation'];
        } elseif ($download_id == 6) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'phd_details.institute_name', 'phd_details.university_name', 'phd_details.thesis_title', 'phd_details.research_area', 'phd_details.supervisor_name', 'phd_details.supervisor_details', 'phd_details.status', 'phd_details.registration_year', 'phd_details.viva_date', 'phd_details.total_years', 'phd_details.mode'];
        } elseif ($download_id == 7) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'addresses.address_type', 'addresses.room_no_and_street', 'addresses.district', 'addresses.pincode', 'addresses.state', 'addresses.country'];
        } elseif ($download_id == 9) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'bank_account_details.account_type', 'bank_account_details.account_no', 'bank_account_details.ifsc_code', 'bank_account_details.bank_name', 'bank_account_details.branch_name', 'bank_account_details.bank_location'];
        } elseif ($download_id == 10) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'publication_details.publication_type', 'publication_details.paper_title', 'publication_details.journal_name', 'publication_details.book_series_title', 'publication_details.publisher', 'publication_details.organized_by', 'publication_details.doi'];
        } elseif ($download_id == 11) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'event_organized.event_type', 'event_organized.title', 'event_organized.funding_support', 'event_organized.coordinated_sjfc', 'event_organized.audience_category', 'event_organized.participants', 'event_organized.start_date', 'event_organized.end_date', 'event_organized.chiefguest_information', 'event_organized.total_participants'];
        } elseif ($download_id == 12) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'event_participations.event_category', 'event_participations.event_type', 'event_participations.title', 'event_participations.organized_by', 'event_participations.event_location', 'event_participations.event_duration', 'event_participations.start_date', 'event_participations.end_date'];
        } elseif ($download_id == 14) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'industrial_experiences.work_experience', 'industrial_experiences.designation', 'industrial_experiences.from', 'industrial_experiences.to', 'industrial_experiences.work_type'];
        } elseif ($download_id == 15) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'online_courses.course_name', 'online_courses.remark', 'online_courses.from_date', 'online_courses.to_date'];
        } elseif ($download_id == 17) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'patents.title', 'patents.application_no', 'patents.application_date', 'patents.application_status'];
        } elseif ($download_id == 18) {

            $columns = ['personal_details.user_name_id', 'personal_details.name', 'awards.title', 'awards.organizer_name', 'awards.awarded_date', 'awards.venue'];
        } else {
            $columns = [];
        }


        $sortedColumns = [];
        $count = count($columns);


        foreach ($columns as $column) {

            $sortedName = str_replace(' ', '_', preg_replace('/\s+/', ' ', trim($column)));
            if ($download_id == 3 || $download_id == 4 || $download_id == 6 || $download_id == 7 || $download_id == 9 ||  $download_id == 10 ||  $download_id == 11 || $download_id == 12 || $download_id == 14 || $download_id == 15 || $download_id == 17 || $download_id == 18) {
                $sortedName = explode('.', $sortedName)[1];
            }

            $data = [
                'data' => $sortedName,
                'name' => $sortedName,
            ];

            $my_string = $data['name'];
            $regex = preg_match('[@_!#$%^&*()<>?/|}{~:]', $my_string);
            if (!$regex)
                $data['name'] =  str_replace("_", " ", $my_string);
            $data['data'] = $data['data'] == 'user_name_id' ? 'id' : $data['data'];
            $sortedColumns[] = $data;
        }






        if ($count > 0) {
            // $query = PersonalDetail::whereNotNull('StaffCode')->select('id','name','last_name','StaffCode','department','email','mobile_number','aadhar_number','emergency_contact_no','total_experience','PanNo','COECode','PassportNo','father_name','spouse_name','dob','age','blood_group_id','mother_tongue_id','religion_id','community_id','gender','marital_status','state','country','known_languages')->get();
            if ($download_id == 1 || $download_id == 2) {
                $query = PersonalDetail::whereNotNull('StaffCode')->select($columns)->get();
            } elseif ($download_id == 3) {

                $query = ExperienceDetail::join('personal_details', 'experience_details.user_name_id', '=', 'personal_details.user_name_id')->select($columns)->get();
            } elseif ($download_id == 4) {

                $query = PromotionDetails::join('personal_details', 'promotion_details.user_name_id', '=', 'personal_details.user_name_id')->select($columns)->get();
            } elseif ($download_id == 6) {

                $query = PhdDetail::join('personal_details', 'phd_details.user_name_id', '=', 'personal_details.user_name_id')->select($columns)->get();
            } elseif ($download_id == 7) {

                $query = Address::where('address_type', 'Permanent')->join('personal_details', 'addresses.name_id', '=', 'personal_details.user_name_id')->select($columns)->get();
            } elseif ($download_id == 9) {

                $query = BankAccountDetail::join('personal_details', 'personal_details.user_name_id', '=', 'bank_account_details.user_name_id')->select($columns)->get();
            } elseif ($download_id == 10) {

                $query = PublicationDetail::join('personal_details', 'personal_details.user_name_id', '=', 'publication_details.user_name_id')->select($columns)->get();
            } elseif ($download_id == 11) {

                $query = EventOrganized::join('personal_details', 'personal_details.user_name_id', '=', 'event_organized.user_name_id')->select($columns)->get();
            } elseif ($download_id == 12) {

                $query = EventParticipation::join('personal_details', 'personal_details.user_name_id', '=', 'event_participations.user_name_id')->select($columns)->get();
            } elseif ($download_id == 14) {

                $query = IndustrialExperience::join('personal_details', 'personal_details.user_name_id', '=', 'industrial_experiences.user_name_id')->select($columns)->get();
            } elseif ($download_id == 15) {

                $query = OnlineCourse::join('personal_details', 'personal_details.user_name_id', '=', 'online_courses.user_name_id')->select($columns)->get();
            } elseif ($download_id == 17) {

                $query = Patent::join('personal_details', 'personal_details.user_name_id', '=', 'patents.name_id')->select($columns)->get();
            } elseif ($download_id == 18) {

                $query = Award::join('personal_details', 'personal_details.user_name_id', '=', 'awards.user_name_id')->select($columns)->get();
            }


            if (count($query) > 0) {
                $table = DataTables::of($query);
                $i = 0;
                $table->editColumn('id', function ($row) use (&$i) {
                    $i++;
                    return $i;
                });

                $table->editColumn('current_designation', function ($row) {

                    $designation = $row->current_designation;
                    if ($designation) {

                        $designation_name = Role::where('id', $designation)->select('title')->first()->title;
                    } else {
                        $designation_name = '';
                    }
                    return $designation_name;
                });


                $table->editColumn('promoted_designation', function ($row) {
                    $designation = $row->promoted_designation;
                    if ($designation) {

                        $designation_name = Role::where('id', $designation)->select('title')->first()->title;
                    } else {
                        $designation_name = '';
                    }
                    return $designation_name;
                });


                $table->editColumn('department', function ($row) {
                    if ($row->department != '') {
                        $department_id = $row->department;
                        $department = ToolsDepartment::where('id', $department_id)->select('name')->first()->name;
                    } else {
                        $department = '';
                    }

                    return $department;
                });

                $table->editColumn('dob', function ($row) {

                    if ($row->dob != '') {
                        $dob =  date('d-m-Y', strtotime($row->dob));
                    } else {
                        $dob = '';
                    }

                    return $dob;
                });

                $table->editColumn('blood_group_id', function ($row) {

                    $blood_group_id = $row->blood_group_id;


                    if ($blood_group_id != '') {

                        $blood_group = BloodGroup::where('id', $row->blood_group_id)->select('name')->first()->name;
                    } else {
                        $blood_group = '';
                    }

                    return $blood_group;
                });

                $table->editColumn('mother_tongue_id', function ($row) {

                    if ($row->mother_tongue_id != '') {
                        $mother_tongue = $row->mother_tongue_id;
                        $mother_tongue_name =  MotherTongue::where('id', $mother_tongue)->select('mother_tongue')->first()->mother_tongue;
                    } else {
                        $mother_tongue_name = '';
                    }
                    return $mother_tongue_name;
                });

                $table->editColumn('religion_id', function ($row) {

                    if ($row->religion_id != '') {
                        $religion_id = $row->religion_id;
                        $religion =  Religion::where('id', $religion_id)->select('name')->first()->name;
                    } else {
                        $religion = '';
                    }

                    return $religion;
                });
                $table->editColumn('community_id', function ($row) {

                    if ($row->community_id != '') {
                        $community_id = $row->community_id;
                        $Community = Community::where('id', $community_id)->select('name')->first()->name;
                    } else {
                        $Community = '';
                    }
                    return $Community;
                });
                $table->editColumn('known_languages', function ($row) {

                    if ($row->known_languages != '') {

                        $language_is_array = $row->known_languages;
                        if (is_string($language_is_array)) {
                            $language_unserialize =  unserialize($language_is_array);
                            if (is_array($language_unserialize)) {
                                $language = strtoupper(implode(',', $language_unserialize));
                            } else {
                                $language = '';
                            }
                        } else {
                            $language = $language_is_array;
                        }
                    } else {
                        $language = '';
                    }
                    return $language;
                });

                $table->editColumn('DOJ', function ($row) {

                    if ($row->DOJ != '') {
                        $DOJ = date('d-m-Y', strtotime($row->DOJ));
                    } else {
                        $DOJ = '';
                    }
                    return $DOJ;
                });

                $table->editColumn('dor', function ($row) {

                    if ($row->dor != '') {
                        $dor = date('d-m-Y', strtotime($row->dor));
                    } else {
                        $dor = '';
                    }
                    return $dor;
                });



                $table->editColumn('DOR', function ($row) {

                    if ($row->DOR != '') {
                        $DOR = date('d-m-Y', strtotime($row->DOR));
                    } else {
                        $DOR = '';
                    }
                    return $DOR;
                });

                $table->editColumn('viva_date', function ($row) {

                    if ($row->viva_date != '') {
                        $viva_date = date('d-m-Y', strtotime($row->viva_date));
                    } else {
                        $viva_date = '';
                    }
                    return $viva_date;
                });
                $table->editColumn('registration_year', function ($row) {

                    if ($row->registration_year != '') {
                        $registration_year = date('d-m-Y', strtotime($row->registration_year));
                    } else {
                        $registration_year = '';
                    }
                    return $registration_year;
                });

                if ($download_id == 11 || $download_id == 12) {

                    $table->editColumn('event_type', function ($row) {

                        if ($row->event_type != '') {
                            $id = $row->event_type;

                            $eventName = Events::where('id', $id)->select('event')->first()->event;
                        } else {
                            $eventName = '';
                        }
                        return $eventName;
                    });

                    $table->editColumn('start_date', function ($row) {

                        if ($row->start_date != '') {
                            $start_date = date('d-m-Y', strtotime($row->start_date));
                        } else {
                            $start_date = '';
                        }
                        return $start_date;
                    });

                    $table->editColumn('end_date', function ($row) {

                        if ($row->end_date != '') {
                            $end_date = date('d-m-Y', strtotime($row->end_date));
                        } else {
                            $end_date = '';
                        }
                        return $end_date;
                    });
                }

                if ($download_id == 14) {

                    $table->editColumn('from', function ($row) {

                        if ($row->from != '') {
                            $from = date('d-m-Y', strtotime($row->from));
                        } else {
                            $from = '';
                        }
                        return $from;
                    });
                    $table->editColumn('to', function ($row) {

                        if ($row->to != '') {
                            $to = date('d-m-Y', strtotime($row->to));
                        } else {
                            $to = '';
                        }
                        return $to;
                    });
                }

                if ($download_id == 15) {

                    $table->editColumn('from_date', function ($row) {

                        if ($row->from_date != '') {
                            $from_date = date('d-m-Y', strtotime($row->from_date));
                        } else {
                            $from_date = '';
                        }
                        return $from_date;
                    });

                    $table->editColumn('to_date', function ($row) {

                        if ($row->to_date != '') {
                            $to_date = date('d-m-Y', strtotime($row->to_date));
                        } else {
                            $to_date = '';
                        }
                        return $to_date;
                    });
                }

                if ($download_id == 17) {

                    $table->editColumn('application_date', function ($row) {

                        if ($row->application_date != '') {
                            $application_date = date('d-m-Y', strtotime($row->application_date));
                        } else {
                            $application_date = '';
                        }
                        return $application_date;
                    });
                }
                if ($download_id == 18) {

                    $table->editColumn('awarded_date', function ($row) {

                        if ($row->awarded_date != '') {
                            $awarded_date = date('d-m-Y', strtotime($row->awarded_date));
                        } else {
                            $awarded_date = '';
                        }
                        return $awarded_date;
                    });
                }
                $table->rawColumns(['placeholder']);
                $table->make(true);
                $data = $table;
                // dd($data);
                return response()->json(['status' => 200, 'data' => $data, 'sortedColumns' => $sortedColumns]);
            } else {
                return response()->json(['status' => false, 'data' => $data]);
            }
            return response()->json(['status' => 200, 'data' => $data, 'sortedColumns' => $sortedColumns]);
        } else {

            $data = [];
            return response()->json(['status' => false, 'data' => $data]);
        }
        // }



    }
}
