<?php

namespace App\Http\Controllers\Admin;

use App\Models\professional_activities;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ProfessionalActivitiesController extends Controller
{

    public function stu_index(Request $request)
    {
        // dd($request->user_name_id);
        if (isset($request->accept)) {
            // dd($request);
            professional_activities::where('id', $request->id)->update(['status' => 1]);
        }
        $check = 'professional_activities';
        if (!$request->updater) {
            $query = professional_activities::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->winning_in_competitions = '';
                $query->participation_in_competitions = '';
                $query->participation_in_extra_curricular_activates = '';
                $query->participation_in_co_curricular_activates = '';
                $query->leader_board_score = '';
                $query->add = 'Add';

                $student = $query;
                $stu_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $student = $query[0];

                $list = $query;

                $stu_edit = new professional_activities;
                $stu_edit->add = 'Add';
                $stu_edit->id = '';
                $stu_edit->winning_in_competitions = '';
                $stu_edit->participation_in_competitions = '';
                $stu_edit->participation_in_extra_curricular_activates = '';
                $stu_edit->participation_in_co_curricular_activates = '';
                $stu_edit->leader_board_score = '';

            }

        } else {

            // dd($request);

            $query_one = professional_activities::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = professional_activities::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $student = $query_one[0];

                $list = $query_one;
                // dd($staff);
                $stu_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        return view('admin.StudentProfile.student', compact('student', 'check', 'list', 'stu_edit'));
    }
    public function stu_update(professional_activities $professional_activities, Request $request)
    {
        // dd($request);
        if (!$request->id == 0 || $request->id != '') {

            $seminars = $professional_activities->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update(request()->except(['_token', 'submit', 'id', 'name', 'user_name_id']));

        } else {
            $seminars = false;
        }

        if ($seminars) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $stu_semi = new professional_activities;

            $stu_semi->winning_in_competitions = $request->winning_in_competitions;
            $stu_semi->participation_in_competitions = $request->participation_in_competitions;
            $stu_semi->participation_in_extra_curricular_activates = $request->participation_in_extra_curricular_activates;
            $stu_semi->participation_in_co_curricular_activates = $request->participation_in_co_curricular_activates;
            $stu_semi->leader_board_score = $request->leader_board_score;
            $stu_semi->user_name_id = $request->user_name_id;
            $stu_semi->status='0';
            $stu_semi->save();

            if ($stu_semi) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

// dd($student);
        return redirect()->route('admin.professional_activities.stu_index', $student);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $seminar = professional_activities::create($request->all());

        return redirect()->route('admin.professional_activities.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\professional_activities  $professional_activities
     * @return \Illuminate\Http\Response
     */
    public function show(professional_activities $professional_activities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\professional_activities  $professional_activities
     * @return \Illuminate\Http\Response
     */
    public function edit(professional_activities $professional_activities)
    {
        //
    }


    public function update(Request $request, professional_activities $professional_activities)
    {
        dd($request);
    }


    public function destroy($professional_activities)
    {
        // dd($professional_activities);
        $professional_activity = professional_activities::find($professional_activities);
        $professional_activity->delete();

        return back();
    }
}
