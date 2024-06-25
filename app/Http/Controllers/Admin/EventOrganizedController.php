<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEventOrganizedRequest;
use App\Models\EventOrganized;
use App\Models\Events;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventOrganizedController extends Controller
{

    public function staff_index(Request $request)
    {
        // dd($request);
        abort_if(Gate::denies('event_organized_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (isset($request->accept)) {

            EventOrganized::where('id', $request->id)->update(['status' => 1]);
    }
        $event = Events::pluck('event', 'id')->prepend(trans('global.pleaseSelect'), '');

        if (!$request->updater) {
            $query = EventOrganized::where(['user_name_id' => $request->user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->event = $event;
                $query->event_type = '';
                $query->title = '';
                $query->funding_support = '';
                $query->coordinated_sjfc = '';
                $query->audience_category = '';
                $query->participants = '';
                $query->event_duration = '';
                $query->start_date = '';
                $query->end_date = '';
                $query->chiefguest_information = '';
                $query->total_participants = '';
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

                $staff_edit = new EventOrganized;
                $staff_edit->add = 'Add';
                $staff_edit->event = $event;
                $staff_edit->event_type = '';
                $staff_edit->title = '';
                $staff_edit->funding_support = '';
                $staff_edit->coordinated_sjfc = '';
                $staff_edit->audience_category = '';
                $staff_edit->participants = '';
                $staff_edit->event_duration = '';
                $staff_edit->start_date = '';
                $staff_edit->end_date = '';
                $staff_edit->chiefguest_information = '';
                $staff_edit->total_participants = '';
                $staff_edit->certificate = '';

            }

        } else {

            // dd($request);

            $query_one = EventOrganized::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = EventOrganized::where(['id' => $request->id])->get();

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

        $check = 'event_organized_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));

    }

    public function staff_update(UpdateEventOrganizedRequest $request, EventOrganized $eventOrganized)
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

            $event_organize = $eventOrganized->where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->first();

            if ($event_organize) {

                $file_exist = $event_organize->certificate;
                $filePath = public_path($event_organize->certificate);

                $event_organize->event_type = $request->event_type;
                $event_organize->title = $request->title;
                $event_organize->funding_support = $request->funding_support;
                $event_organize->coordinated_sjfc = $request->coordinated_sjfc;
                $event_organize->audience_category = $request->audience_category;
                $event_organize->participants = $request->participants;
                $event_organize->event_duration = $request->event_duration;
                $event_organize->start_date = $request->start_date;
                $event_organize->end_date = $request->end_date;
                $event_organize->chiefguest_information = $request->chiefguest_information;
                $event_organize->total_participants = $request->total_participants;
                $event_organize->user_name_id = $request->user_name_id;
                $event_organize->status='0';
                if ($path != '') {
                    $event_organize->certificate = $path;
                }

                $event_organize->save();

                // Delete the old file from the disk
                if ($path != '') {
                    if ($file_exist != '' || $file_exist != null) {
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }

            } else {
                $event_organize = false;
            }
        } else {
            $event_organize = false;
        }
        if ($event_organize) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $staff_event = new EventOrganized;
            $staff_event->event_type = $request->event_type;
            $staff_event->title = $request->title;
            $staff_event->funding_support = $request->funding_support;
            $staff_event->coordinated_sjfc = $request->coordinated_sjfc;
            $staff_event->audience_category = $request->audience_category;
            $staff_event->participants = $request->participants;
            $staff_event->event_duration = $request->event_duration;
            $staff_event->start_date = $request->start_date;
            $staff_event->end_date = $request->end_date;
            $staff_event->chiefguest_information = $request->chiefguest_information;
            $staff_event->total_participants = $request->total_participants;
            $staff_event->user_name_id = $request->user_name_id;
            $staff_event->status='0';

            if ($path != '') {
                $staff_event->certificate = $path;
            }

            $staff_event->save();

            if ($staff_event) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        return redirect()->route('admin.event-organized.staff_index', $staff);
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
    public function destroy(EventOrganized $eventOrganized)
    {
        $eventOrganized->delete();

        return back();
    }
}
