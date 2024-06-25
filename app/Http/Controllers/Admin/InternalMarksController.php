<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ExamTimetableCreation;
use App\Models\InternalWeightage;
use App\Models\LabFirstmodel;
use App\Models\MarksData;
use App\Models\Semester;
use App\Models\SubjectType;
use App\Models\ToolssyllabusYear;
use Illuminate\Http\Request;

class InternalMarksController extends Controller
{
    public function weightageIndex(Request $request)
    {
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $sem = Semester::pluck('semester', 'id');
        $getData = InternalWeightage::with('getRegulation:id,name', 'getAy:id,name', 'getSubType:id,name')->get();


        foreach($getData as $data){
            $markdata= MarksData::where(['academic_year'=>$data->academic_year, 'semester'=>$data->semester, 'subject_type'=>$data->subject_type])->get();
            if(count($markdata) > 0){
                $data->status = 1;
            }else{
                $data->status = 0;
            }
        }
        return view('admin.internalMarksWeightage.index', compact('regulations', 'ays', 'getData', 'sem'));
    }

    public function subjectTypes(Request $request)
    {
        if (isset($request->regulation)) {

            $subjectTypes = SubjectType::where(['regulation_id' => $request->regulation])->select('name', 'id')->get();

            return response()->json(['status' => true, 'data' => $subjectTypes]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Data Not Found']);
        }
    }
    public function weightageCreate(Request $request)
    {
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $sem = Semester::pluck('semester', 'id');
        return view('admin.internalMarksWeightage.create', compact('regulations', 'ays', 'sem'));
    }
    public function fetchCat(Request $request)
    {
        $category = $request->category;
        $exam_title = [];
        if ($request->regulation != '' && $request->ay != '' && $request->sem != '') {
            $checkCount = InternalWeightage::where(['academic_year' => $request->ay, 'semester' => $request->sem, 'regulation' => $request->regulation, 'subject_type'=>$request->subject_type])->count();
            if ($checkCount <= 0) {
                if ($category == "CAT") {
                    $cat_title = ExamTimetableCreation::where(['accademicYear' => $request->ay, 'semester' => $request->sem])->distinct()->select('exam_name')->get();
                    foreach ($cat_title as $i => $cat) {
                        array_push($exam_title, ['name' => $cat->exam_name]);
                    }
                    return response()->json(['status' => true,'category' => 'CAT', 'exam_title' => $exam_title]);
                } elseif ($category == "LAB") {

                    $lab_title = LabFirstmodel::where(['accademicYear' => $request->ay, 'semester' => $request->sem])->where('exam_name','NOT LIKE',"%Review%")->distinct()->select('exam_name')->get();
                    foreach ($lab_title as $i => $lab) {
                        array_push($exam_title, ['name' => $lab->exam_name]);
                    }
                    return response()->json(['status' => true,'category' => 'LAB', 'exam_title' => $exam_title]);
                } elseif ($category == "PROJECT") {

                    $project_title = LabFirstmodel::where(['accademicYear' => $request->ay, 'semester' => $request->sem])->where('exam_name','LIKE',"Review%")->distinct()->select('exam_name')->get();
                    foreach ($project_title as $i => $lab) {
                        array_push($exam_title, ['name' => $lab->exam_name]);
                    }
                    return response()->json(['status' => true,'category' => 'PROJECT', 'exam_title' => $exam_title]);
                }
            } else {
                return response()->json(['status' => false,'category' => '', 'exam_title' => '']);
            }

        } else {
            return back();
        }

    }

    public function store(Request $request)
    {
        $cat = $request->input('cat');
        $reg = $request->input('reg');
        $ay = $request->input('ay');
        $sem = $request->sem;

        $subject_type = $request->input('subject_type');
        $total = $request->input('total');
        $internal = $request->input('weightage');

        if (isset($cat)) {
            $store = InternalWeightage::create([
                'regulation' => $reg,
                'academic_year' => $ay,
                'semester' => $sem,
                'subject_type' => $subject_type,
                'category' => $cat,
                'internal_weightage' => $internal,
                'total' => $total,
            ]);

            return response()->json('Internal Weightage Successfully Created');
        } else {
            return response()->json(['error' => 'Required Details Not Found']);
        }
    }

    public function view(Request $request)
    {
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $getData = InternalWeightage::with('getRegulation:id,name', 'getAy:id,name', 'getSubType:id,name')->where(['id' => $request->id])->first();
        if ($getData->internal_weightage != null) {
            $weightage = json_decode($getData->internal_weightage);
        } else {
            $weightage = [];
        }
        return view('admin.internalMarksWeightage.show', compact('getData', 'regulations', 'ays', 'weightage'));
    }

    public function edit(Request $request)
    {
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $subjectTypes = SubjectType::pluck('name', 'id');
        $getData = InternalWeightage::with('getRegulation:id,name', 'getAy:id,name', 'getSubType:id,name')->where(['id' => $request->id])->first();
        if ($getData->internal_weightage != null) {
            $weightage = json_decode($getData->internal_weightage);
        } else {
            $weightage = [];
        }
        return view('admin.internalMarksWeightage.edit', compact('getData', 'regulations', 'ays', 'subjectTypes', 'weightage'));
    }

    public function destroy(Request $request)
    {
        $delete = InternalWeightage::where(['id' => $request->id])->delete();
        return redirect()->route('admin.internal-weightage.index');

    }
}
