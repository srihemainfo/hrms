<div class="container">
    <div class="row gutters">
        {{-- {{ dd($student); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.professional_activities.stu_update', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $stu_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Professional Activities</h6>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="topic">Winning In Competitions</label>
                                    <input type="text" class="form-control" name="winning_in_competitions" placeholder="Enter Winning In Competitions"
                                        value="{{ $stu_edit->winning_in_competitions }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="seminar_date">Participation In Competitions</label>
                                    <input type="text" class="form-control" id="seminar_date"
                                        name="participation_in_competitions" placeholder="Enter Participation In Competitions"
                                        value="{{ $stu_edit->participation_in_competitions }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="seminar_date">Participation In Co-Curricular Activates</label>
                                    <input type="text" class="form-control" id="seminar_date"
                                        name="participation_in_co_curricular_activates" placeholder="Enter Participation In Co-Curricular Activates"
                                        value="{{ $stu_edit->participation_in_co_curricular_activates }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="seminar_date">Participation In Extra Curricular Activates</label>
                                    <input type="text" class="form-control" id="seminar_date"
                                        name="participation_in_extra_curricular_activates" placeholder="Enter Participation In Extra Curricular Activates"
                                        value="{{ $stu_edit->participation_in_extra_curricular_activates }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="seminar_date">Leader Board Score</label>
                                    <input type="text" class="form-control" id="seminar_date"
                                        name="leader_board_score" placeholder="Enter Leader Board Score"
                                        value="{{ $stu_edit->leader_board_score }}">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="0">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    <button type="submit" id="submit" name="submit"
                                        class="btn btn-primary">{{ $stu_edit->add }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (count($list) > 0)
        <div class="row gutters mt-3 mb-3">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    @php
                        $user = auth()->user();

                        if ($user) {
                            // Get the user's ID
                            $userId = $user->id;
                            // dd($userId);
                            // You can also use the following equivalent syntax:
                            // $userId = auth()->id();

                            // $userId now contains the ID of the authenticated user
                        } else {
                            // User is not authenticated
                            // Handle accordingly
                        }
                    @endphp
                    <div class="card-body table-responsive">
                        <h6 class="mb-3 text-primary">Professional Activities List</h6>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Winning In Competition
                                    </th>
                                    <th>
                                        Participation In Competitions
                                    </th>
                                    <th>
                                        Co-Curricular Activates
                                    </th>
                                    <th>
                                        Extra Curricular Activates
                                    </th>
                                    <th>
                                        Leader Board Score
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($list); $i++)
                                    <tr>
                                        <td>{{ $list[$i]->winning_in_competitions }}</td>
                                        <td>{{ $list[$i]->participation_in_competitions }}</td>
                                        <td>{{ $list[$i]->participation_in_co_curricular_activates }}</td>
                                        <td>{{ $list[$i]->participation_in_extra_curricular_activates }}</td>
                                        <td>{{ $list[$i]->leader_board_score }}</td>
                                        <td>
                                            @if ($userId == 1)
                                                @if ($list[$i]->status == '0')
                                                    <form method="POST"
                                                        action="{{ route('admin.professional_activities.stu_updater', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $list[$i]->id]) }}"
                                                        enctype="multipart/form-data"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                        @csrf
                                                        <button type="submit" name="accept" value="accept"
                                                            class="btn btn-success  ">Accept</button>
                                                    </form>

                                                    <form
                                                        action="{{ route('admin.professional_activities.destroy', $list[$i]->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token"
                                                            value="{{ csrf_token() }}">
                                                        <input type="submit" class="btn  btn-danger mt-2"
                                                            value="{{ 'Reject' }}">
                                                    </form>
                                                @endif

                                                @if ($list[$i]->status == '1')
                                                    <div class="p-2 Approved">
                                                        Approved </div>
                                                @endif
                                            @endif

                                            @if ($userId == $student->user_name_id)
                                                @if ($list[$i]->status == '0')
                                                    <div class="p-2 Pending">
                                                        Pending </div>
                                                @elseif ($list[$i]->status == '0')
                                                    <div class="p-2 Approved">
                                                        Approved</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.professional_activities.stu_updater', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn Edit btn-info">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.professional_activities.destroy', $list[$i]->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn mt-2 btn-danger"
                                                    value="{{ trans('global.delete') }}">
                                            </form>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @endif
</div>

