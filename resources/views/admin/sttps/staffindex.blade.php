<div class="container">

    <div class="row gutters">
        {{-- {{ dd($staff); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.sttps.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">STTPS Details</h5>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="topic">Topic</label>
                                    <input type="text" class="form-control" name="topic" placeholder="Enter Topic"
                                        value="{{ $staff_edit->topic }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="from_date">From Date</label>
                                    <input type="text" class="form-control date" name="from"
                                        placeholder="Enter from_date" value="{{ $staff_edit->from }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="to_date">To Date</label>
                                    <input type="text" class="form-control date" name="to"
                                        placeholder="Enter to_date" value="{{ $staff_edit->to }}">
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="remark">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter Remarks" rows="1"
                                        value="{{ $staff_edit->remarks }}">{{ $staff_edit->remarks }}</textarea>

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
                        <h5 class="mb-3 text-primary">STTPS  List</h5>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Topic
                                    </th>
                                    <th>
                                        Remarks
                                    </th>
                                    <th>
                                        From Date
                                    </th>
                                    <th>
                                        To Date
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
                                        <td>{{ $list[$i]->topic }}</td>
                                        <td>{{ $list[$i]->remarks}}</td>
                                        <td>{{ $list[$i]->from }}</td>
                                        <td>{{ $list[$i]->to }}</td>
                                        <td>
                                            @if ($userId == 1)
                                                @if ($list[$i]->status == '0')

                                                        <form method="POST"
                                                            action="{{ route('admin.sttps.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                            enctype="multipart/form-data" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                            @csrf
                                                            <button type="submit" name="accept" value="accept"
                                                                class="btn btn-success  ">Accept</button>
                                                        </form>

                                                        <form
                                                            action="{{ route('admin.sttps.destroy', $list[$i]->id) }}"
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
                                                action="{{ route('admin.sttps.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn  btn-info Edit">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.sttps.destroy', $list[$i]->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-danger mt-2" value="{{ trans('global.delete') }}">
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
