<div class="container">

    <div class="row gutters">
        {{-- {{ dd($list); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.guest-lectures.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Guest Lecture Details</h6>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="topic">Topic</label>
                                    <input type="text" class="form-control" name="topic" placeholder="Enter Topic"
                                        value="{{ $staff_edit->topic }}">
                                </div>
                            </div>


                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" name="location"
                                        placeholder="Enter Location" value="{{ $staff_edit->location }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="from_date_and_time">From (Date And Time)</label>
                                    <input type="text" class="form-control datetime" name="from_date_and_time"
                                        placeholder="Enter Date And Time" value="{{ $staff_edit->from_date_and_time }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="to_date_and_time">To (Date And Time)</label>
                                    <input type="text" class="form-control datetime" name="to_date_and_time"
                                        placeholder="Enter Date And Time" value="{{ $staff_edit->to_date_and_time }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" class="form-control" name="remarks"
                                        placeholder="Enter Remarks" value="{{ $staff_edit->remarks }}">

                                </div>
                            </div>
                        </div>

                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
                                    <button type="submit" id="submit" name="submit" value="submit"
                                        class="btn btn-primary">{{ $staff_edit->add }}</button>
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

                    <div class="card-body table-responsive">
                        <h6 class="mb-3 text-primary">Guest Lectures Details</h6>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Topic
                                    </th>
                                    <th>
                                        From
                                    </th>
                                    <th>
                                        To
                                    </th>
                                    <th>
                                        Location
                                    </th>
                                    <th>
                                        Remarks
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($list); $i++)
                                    <tr>
                                        <td>{{ $list[$i]->topic }}</td>
                                        <td>{{ $list[$i]->from_date_and_time }}</td>
                                        <td>{{ $list[$i]->to_date_and_time }}</td>
                                        <td>{{ $list[$i]->location }}</td>
                                        <td>{{ $list[$i]->remarks }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.guest-lectures.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn btn-xs btn-info">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.guest-lectures.destroy', $list[$i]->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger"
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
