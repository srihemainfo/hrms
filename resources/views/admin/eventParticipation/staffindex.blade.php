<style>
    input[type="file"] {
        /* background-color: #f2f2f2; */
        border: none;
        /* color: #555; */
        cursor: pointer;
        font-size: 16px;
        /* padding: 10px; */
    }


    input[type="file"]:focus {
        outline: none;
    }
</style>
<div class="container">
    {{-- @if (auth()->user()->id != $staff->user_name_id) --}}
    <div class="row gutters">
        {{-- {{ dd($staff) }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.event-participation.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Event Participation Details</h5>
                            </div>
                        </div>
                        <div class="row gutters">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="event_category" class="required">Event Category</label>
                                    <select class="form-control select2" name="event_category" id="event_category">
                                        <option value="" {{ $staff_edit->event_category == ''?'selected':'' }}>Please Select</option>
                                        <option value="Internal" {{ $staff_edit->event_category == 'Internal'?'selected':'' }}>Internal</option>
                                        <option value="External" {{ $staff_edit->event_category == 'External'?'selected':'' }}>External</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="event_type" class="required">Event Type</label>
                                    <select name="event_type" id="event_type" class="form-control select2" required>
                                        @foreach ($staff_edit->event as $id => $entry)
                                            <option value="{{ $id }}"
                                                {{ (old('event_type') ? old('event_type') : $staff_edit->event_type ?? '') == $id ? 'selected' : '' }}>
                                                {{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="title" class="required">Event Title</label>
                                    <input type="text" class="form-control" name="title"
                                        placeholder="Enter Event Title" value="{{ $staff_edit->title }}" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="organized_by" class="required">Organized By</label>
                                    <input type="text" class="form-control" name="organized_by"
                                        placeholder="Enter Organized By"
                                        value="{{ $staff_edit->organized_by }}" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="event_location">Event Location</label>
                                    <input type="text" class="form-control" name="event_location"
                                        placeholder="Enter Event Location" value="{{ $staff_edit->event_location }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="event_duration">Event Duration</label>
                                    <input type="number" class="form-control" name="event_duration"
                                        placeholder="Enter Event Duration" value="{{ $staff_edit->event_duration }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="start_date" class="required">Start Date</label>
                                    <input type="text" class="form-control date" name="start_date"
                                        placeholder="Enter Start Date" value="{{ $staff_edit->start_date }}" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="end_date" class="required">End Date</label>
                                    <input type="text" class="form-control date" name="end_date"
                                        placeholder="Enter End Date" value="{{ $staff_edit->end_date }}" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="certificate">Upload the Certificate/Approval Letter</label>
                                    <input type="file" name="certificate" class="form-control" id="certificate">
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="status" value="0">

                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    <button type="submit" id="submit" name="submit"
                                        class="btn btn-primary Edit">{{ $staff_edit->add }}</button>
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
                        <h5 class="mb-3 text-primary">Event Participation  List</h5>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Event Category
                                    </th>
                                    <th>
                                        Event Type
                                    </th>
                                    <th>
                                        Event Title
                                    </th>
                                    <th>
                                        Organized By
                                    </th>
                                    <th>
                                        Event Location
                                    </th>
                                    <th>
                                        Event Duration
                                    </th>
                                    <th>
                                        Start Date
                                    </th>
                                    <th>
                                        End Date
                                    </th>
                                    <th>
                                        Certificate
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
                                        <td>{{ $list[$i]->event_category }}</td>
                                        @if ($list[$i]->event_type != '' || $list[$i]->event_type != null)
                                            @foreach ($list[$i]->event as $id => $entry)
                                                @if ($id == $list[$i]->event_type)
                                                    <td>{{ $entry }}</td>
                                                @endif
                                            @endforeach
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $list[$i]->title }}</td>
                                        <td>{{ $list[$i]->organized_by }}</td>
                                        <td>{{ $list[$i]->event_location }}</td>
                                        <td>{{ $list[$i]->event_duration }}</td>
                                        <td>{{ $list[$i]->start_date }}</td>
                                        <td>{{ $list[$i]->end_date }}</td>
                                        @if ($list[$i]->certificate != '' && $list[$i]->certificate != null)
                                            <td>
                                                <img class="uploaded_img" src="{{ asset($list[$i]->certificate) }}"
                                                    alt="image">
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>
                                            @if ($userId == 1)
                                                @if ($list[$i]->status == '0')

                                                        <form method="POST"
                                                            action="{{ route('admin.event-participation.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                            enctype="multipart/form-data" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                            @csrf
                                                            <button type="submit" name="accept" value="accept"
                                                                class="btn btn-success  ">Accept</button>
                                                        </form>

                                                        <form
                                                            action="{{ route('admin.event-participation.destroy', $list[$i]->id) }}"
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

                                                        <div class="p-2 Approved"
                                                            >
                                                            Approved </div>

                                                @endif
                                            @endif

                                            @if ($userId == $staff->user_name_id)
                                                @if ($list[$i]->status == '0')

                                                        <div class="p-2 Pending"
                                                            >
                                                            Pending </div>

                                                @elseif ($list[$i]->status == '1')

                                                    <div class="p-2 Approved"
                                                        >
                                                        Approved</div>

                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.event-participation.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn Edit btn-info">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.event-participation.destroy', $list[$i]->id) }}"
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

