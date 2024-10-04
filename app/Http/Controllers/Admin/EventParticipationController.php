<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEventParticipationRequest;
use App\Models\EventParticipation;
use App\Models\Events;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventParticipationController extends Controller
{

    public function staff_index(Request $request)
    {
        // dd($request);
        abort_if(Gate::denies('event_participation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (isset($request->accept)) {

            EventParticipation::where('id', $request->id)->update(['status' => 1]);
    }
        $event = Events::pluck('event', 'id')->prepend(trans('global.pleaseSelect'), '');

        if (!$request->updater) {
            $query = EventParticipation::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->event = $event;
                $query->event_category = '';
                $query->event_type = '';
                $query->title = '';
                $query->organized_by = '';
                $query->event_location = '';
                $query->event_duration = '';
                $query->start_date = '';
                $query->end_date = '';
                $query->certificate = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                for ($i = 0; $i < count($query); $i++) {
                    $query[$i]->event = $event;
                }

                $staff = $query[0];

                $list = $query;

                $staff_edit = new EventParticipation;
                $staff_edit->add = 'Add';
                $staff_edit->event = $event;
                $staff_edit->event_category = '';
                $staff_edit->event_type = '';
                $staff_edit->title = '';
                $staff_edit->organized_by = '';
                $staff_edit->event_location = '';
                $staff_edit->event_duration = '';
                $staff_edit->start_date = '';
                $staff_edit->end_date = '';
                $staff_edit->certificate = '';

            }

        } else {

            // dd($request);

            $query_one = EventParticipation::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = EventParticipation::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['event'] = $event;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                for ($i = 0; $i < count($query_one); $i++) {

                    $query_one[$i]->event = $event;

                }

                $list = $query_one;
                // dd($staff);
                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $check = 'event_participation_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));

    }

    public function staff_update(UpdateEventParticipationRequest $request, EventParticipation $eventParticipation)
    {
if ($request->hasFile('certificate')) {
    $request->validate([
        'certificate' => 'required|image|mimes:jpg,JPG,jpeg,png,PNG,JPEG|max:2048',
    ]);

    $file = $request->file('certificate');
    $extension = $file->getClientOriginalExtension();
    $fileName = time() . '.' . $extension;

    $destinationPath = public_path('uploads');
    $file->move($destinationPath, $fileName);

    $path = 'uploads/' . $fileName;
} else {
    $path = null;
}

        if (!$request->id == 0 || $request->id != '') {

            $event_participate = $eventParticipation->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->first();

            if ($event_participate) {

                $file_exist = $event_participate->certificate;
                $filePath = public_path($event_participate->certificate);

                $event_participate->event_category = $request->event_category;
                $event_participate->event_type = $request->event_type;
                $event_participate->title = $request->title;
                $event_participate->organized_by = $request->organized_by;
                $event_participate->event_location = $request->event_location;
                $event_participate->event_duration = $request->event_duration;
                $event_participate->start_date = $request->start_date;
                $event_participate->end_date = $request->end_date;
                $event_participate->user_name_id = $request->user_name_id;
                $event_participate->status='0';
                if ($path != '') {
                    $event_participate->certificate = $path;
                }

                $event_participate->save();

                // Delete the old file from the disk
                if ($path != '') {
                    if ($file_exist != '' || $file_exist != null) {
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }

            } else {
                $event_participate = false;
            }
        } else {
            $event_participate = false;
        }
        if ($event_participate) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $event_participation = new EventParticipation;
            $event_participation->event_category = $request->event_category;
            $event_participation->event_type = $request->event_type;
            $event_participation->title = $request->title;
            $event_participation->organized_by = $request->organized_by;
            $event_participation->event_location = $request->event_location;
            $event_participation->event_duration = $request->event_duration;
            $event_participation->start_date = $request->start_date;
            $event_participation->end_date = $request->end_date;
            $event_participation->user_name_id = $request->user_name_id;
            $event_participation->status='0';
            if ($path != '') {
                $event_participation->certificate = $path;
            }

            $event_participation->save();

            if ($event_participation) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        return redirect()->route('admin.event-participation.staff_index', $staff);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventParticipation $eventParticipation)
    {
        $eventParticipation->delete();

        return back();
    }
}
