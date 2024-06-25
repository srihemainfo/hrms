<?php

namespace App\Http\Controllers\Traits;

use App\Models\AcademicYear;
use App\Models\CourseEnrollMaster;
use App\Models\Semester;
use Illuminate\Support\Facades\Session;

trait CurrentAySemTrait
{
    public function getCurrent_Ay_Sem()
    {
        $getAys = AcademicYear::where(['status' => 1])->select('id', 'name')->get();
        $Ays = $getAys->toArray();
        $getSem = Semester::where(['status' => 1])->select('id', 'semester')->get();
        $Sems = $getSem->toArray();
        $currentEnrolls = [];
        if (count($Ays) > 0 && count($Sems) > 0) {
            foreach ($Ays as $ay) {
                foreach ($Sems as $sem) {
                    $make_enroll = '%/%/' . $ay['name'] . '/' . $sem['semester'] . '/%';
                    array_push($currentEnrolls, $make_enroll);
                }
            }
        }
        $theClass = [];
        if (count($currentEnrolls) > 0) {
            foreach ($currentEnrolls as $enroll) {
                $getClass = CourseEnrollMaster::where('enroll_master_number',"LIKE", $enroll)->select('id')->get();
                if(count($getClass) > 0){
                    foreach($getClass as $enrolledClass){
                        array_push($theClass, $enrolledClass->id);
                    }
                }
            }
        }
        $store = Session::put('currentClasses', $theClass);
        return true;
    }
}
