<div class="container">

    @if (auth()->user()->id != $staff->user_name_id)
        <div class="row gutters">
            {{-- {{ dd($staff); }} --}}
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-header">

                        <h5 class="mb-2 text-primary">Promotion Details</h5>

                    </div>
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route('admin.promotion-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="current_designation">Current Designation</label>
                                        <input type="text" class="form-control" name=""
                                            value="{{ $staff_edit->current_designation }}" readonly>
                                        @foreach ($roles as $id => $entry)
                                            @if ($entry == $staff_edit->current_designation)
                                                <input type="hidden" name="current_designation"
                                                    value="{{ $id }}">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                @if ($staff_edit->promoted_designation == '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="promoted_designation">Promoted Designation</label>
                                            <select class="form-control select2 " name="promoted_designation"
                                                id="promoted_designation">
                                                @foreach ($staff_edit->designation as $id => $entry)
                                                    <option value="{{ $id }}">{{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="promoted_designation">Promoted Designation</label>
                                            <select class="form-control select2" name="promoted_designation"
                                                id="promoted_designation">
                                                @foreach ($staff_edit->designation as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('promoted_designation') ? old('promoted_designation') : $staff_edit->promoted_designation ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="promotion_date">Promotion Date</label>
                                        <input type="text" class="form-control date" name="promotion_date"
                                            placeholder="Enter Promotion Date"
                                            value="{{ $staff_edit->promotion_date }}">
                                    </div>
                                </div>

                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
                                        <button type="submit" id="submit" name="submit"
                                            class="btn btn-primary">{{ $staff_edit->add }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (count($list) > 0)
        <div class="row gutters mt-3 mb-3">
            <div class="col" style="padding:0;">
                <div class="card h-100">

                    <div class="card-body table-responsive">
                        <h5 class="mb-3 text-primary">Promotion Details List</h5>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Current Designation
                                    </th>
                                    <th>
                                        Promoted Designation
                                    </th>
                                    <th>
                                        Promotion Date
                                    </th>
                                    {{-- <th>
                                        Status
                                    </th> --}}
                                    {{-- @if (auth()->user()->id == $staff->user_name_id)
                                        <th>
                                            Action
                                        </th>
                                    @endif --}}
                                </tr>
                            </thead>
                            <tbody>

                                @for ($i = 0; $i < count($list); $i++)
                                    <tr>
                                        <td>
                                            @foreach ($roles as $id => $entry)
                                                @if ($id == $list[$i]->current_designation)
                                                    {{ $entry }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($list[$i]->promoted_designation != '' || $list[$i]->promoted_designation != null)
                                                @foreach ($list[$i]->designation as $id => $entry)
                                                    @if ($list[$i]->promoted_designation == $id)
                                                        {{ $entry }}
                                                    @endif
                                                @endforeach
                                            @else
                                            @endif
                                        </td>
                                        <td>{{ $list[$i]->promotion_date }}</td>
                                        {{-- <td>
                                            @if (auth()->user()->id == $list[$i]->user_name_id)
                                                @if ($list[$i]->status == 0)
                                                    <a class="btn btn-xs btn-warning">Pending</a>
                                                @elseif ($list[$i]->status == 1)
                                                    <button class="btn btn-xs btn-success">Approved</button>
                                                @elseif ($list[$i]->status == 2)
                                                    <button class="btn btn-xs btn-danger">Rejected</button>
                                                @endif
                                            @else
                                                @if ($list[$i]->status == 0)
                                                    <form method="POST"
                                                        action="{{ route('admin.promotion-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                        enctype="multipart/form-data"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                        @csrf
                                                        <button type="submit" name="accept" value="accept"
                                                            class="btn btn-xs btn-success">Accept</button>
                                                    </form>

                                                    <form method="POST"
                                                        action="{{ route('admin.promotion-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                        enctype="multipart/form-data"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                        @csrf
                                                        <button type="submit" name="reject" value="reject"
                                                            class="btn btn-xs btn-danger">Reject</button>
                                                    </form>
                                                @elseif ($list[$i]->status == 1)
                                                    <button class="btn btn-xs btn-success">Approved</button>
                                                @elseif ($list[$i]->status == 2)
                                                    <button class="btn btn-xs btn-danger">Rejected</button>
                                                @endif
                                            @endif
                                        </td> --}}

                                        {{-- @if (auth()->user()->id == $staff->user_name_id)
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('admin.promotion-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <button type="submit" id="updater" name="updater" value="updater"
                                                        class="btn btn-xs btn-info">Edit</button>
                                                </form>
                                                <form
                                                    action="{{ route('admin.promotion-details.destroy', $list[$i]->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                    style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger"
                                                        value="{{ trans('global.delete') }}">
                                                </form>
                                            </td>
                                        @endif --}}

                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row gutters" style="margin-top:1rem;">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-2 text-primary">Promotion Details List</h5>
                    </div>
                    <div class="card-body text-center">
                        No Data Available

                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
