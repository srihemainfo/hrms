<div class="container">

    <div class="row gutters">
        {{-- {{ dd($staff); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.entrance-exams.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Entrance Exam Details</h5>
                            </div>
                            @if ($staff_edit->exam_type_id == '')
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="exam_type_id">Exam Type</label>
                                        <select class="form-control select2" name="exam_type_id" id="exam_type_id"
                                            required>
                                            @foreach ($staff_edit->exam_types as $id => $entry)
                                                <option value="{{ $id }}">
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="exam_type_id">Education Type</label>
                                        <select class="form-control select2" name="exam_type_id" id="exam_type_id">
                                            @foreach ($staff_edit->exam_types as $id => $entry)
                                                <option value="{{ $id }}"
                                                    {{ (old('exam_type_id') ? old('exam_type_id') : $staff_edit->exam_type_id ?? '') == $id ? 'selected' : '' }}>
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="passing_year">Passing Year</label>
                                    <input type="text" class="form-control date" name="passing_year"
                                        placeholder="YYYY-MM-DD" value="{{ $staff_edit->passing_year }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="scored_mark">Scored Mark</label>
                                    <input type="text" class="form-control" id="scored_mark" name="scored_mark"
                                        placeholder="Enter Scored Mark" value="{{ $staff_edit->scored_mark }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="total_mark">Total Mark</label>
                                    <input type="text" class="form-control" name="total_mark"
                                        placeholder="Enter Total Mark" value="{{ $staff_edit->total_mark }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="rank">Rank</label>
                                    <input type="text" class="form-control" name="rank" placeholder="Enter Rank"
                                        value="{{ $staff_edit->rank }}">
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
    {{-- {{ dd($list); }} --}}
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
                        <h5 class="mb-3 text-primary">Entrance Exams List</h5>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Exam Types
                                    </th>
                                    <th>
                                        Passing Year
                                    </th>
                                    <th>
                                        Scored Mark
                                    </th>
                                    <th>
                                        Total Mark
                                    </th>
                                    <th>
                                        Rank
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
                                    @if ($list[$i]->exam_type_id != '' || $list[$i]->exam_type_id != null)
                                        <tr>

                                            @foreach ($list[$i]->exam_types as $id => $entry)
                                                @if ($list[$i]->exam_type_id == $id)
                                                    <td>{{ $entry }}</td>
                                                @endif
                                            @endforeach

                                            <td>{{ $list[$i]->passing_year }}</td>
                                            <td>{{ $list[$i]->scored_mark }}</td>
                                            <td>{{ $list[$i]->total_mark }}</td>
                                            <td>{{ $list[$i]->rank }}</td>
                                            <td>
                                            @if ($userId == 1)
                                            @if ($list[$i]->status == '0')

                                                    <form method="POST"
                                                        action="{{ route('admin.entrance-exams.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                        enctype="multipart/form-data" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                        @csrf
                                                        <button type="submit" name="accept" value="accept"
                                                            class="btn btn-success  ">Accept</button>
                                                    </form>

                                                    <form
                                                        action="{{ route('admin.entrance-exams.destroy', $list[$i]->id) }}"
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
                                                    action="{{ route('admin.entrance-exams.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <button type="submit" id="updater" name="updater" value="updater"
                                                        class="btn  btn-info Edit">Edit</button>
                                                </form>
                                                <form
                                                    action="{{ route('admin.entrance-exams.destroy', $list[$i]->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                    style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn  btn-danger mt-2"
                                                        value="{{ trans('global.delete') }}">
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @endif
</div>
