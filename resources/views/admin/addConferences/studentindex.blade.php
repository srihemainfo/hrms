<div class="container">

    <div class="row gutters">
        {{-- {{ dd($student); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.add-conferences.stu_update', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $stu_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Conference Details</h5>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="topic_name">Topic Name</label>
                                    <input type="text" class="form-control" name="topic_name"
                                        placeholder="Enter Topic Name" value="{{ $stu_edit->topic_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                        placeholder="Enter Location" value="{{ $stu_edit->location }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="project_name">Project Name</label>
                                    <input type="text" class="form-control" name="project_name"
                                        placeholder="Enter Project Name" value="{{ $stu_edit->project_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="conference_date">Conference Date</label>
                                    <input type="text" class="form-control date" id="conference_date"
                                        name="conference_date" placeholder="YYYY-MM-DD"
                                        value="{{ $stu_edit->conference_date }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="contribution_of_conference">Contribution</label>
                                    <input type="text" class="form-control" name="contribution_of_conference"
                                        placeholder="Enter Your Contribution"
                                        value="{{ $stu_edit->contribution_of_conference }}">
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="status" value="0">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
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
                        <h5 class="mb-3 text-primary">Conference List</h5>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Topic Name
                                    </th>
                                    <th>
                                        Location
                                    </th>
                                    <th>
                                        Project Name
                                    </th>
                                    <th>
                                        Conference Date
                                    </th>
                                    <th>
                                        Contribution
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
                                        <td>{{ $list[$i]->topic_name }}</td>
                                        <td>{{ $list[$i]->location }}</td>
                                        <td>{{ $list[$i]->project_name }}</td>
                                        <td>{{ $list[$i]->conference_date }}</td>
                                        <td>{{ $list[$i]->contribution_of_conference }}</td>
                                        <td>
                                            @if ($userId == 1)
                                                @if ($list[$i]->status == '0')
                                                    <form method="POST"
                                                        action="{{ route('admin.add-conferences.stu_updater', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $list[$i]->id]) }}"
                                                        enctype="multipart/form-data"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                        @csrf
                                                        <button type="submit" name="accept" value="accept"
                                                            class="btn btn-success  ">Accept</button>
                                                    </form>

                                                    <form
                                                        action="{{ route('admin.add-conferences.destroy', $list[$i]->id) }}"
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
                                                action="{{ route('admin.add-conferences.stu_updater', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn Edit btn-info">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.add-conferences.destroy', $list[$i]->id) }}"
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
