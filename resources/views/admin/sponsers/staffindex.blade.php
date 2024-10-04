<div class="container">

    <div class="row gutters">
        {{-- {{ dd($staff); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.sponsers.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Sponser Details</h5>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="sponser_name">Sponser Name</label>
                                    <input type="text" class="form-control" id="sponser_name" name="sponser_name"
                                        placeholder="Enter Sponser Name" value="{{ $staff_edit->sponser_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="project_title">Project Title</label>
                                    <input type="text" class="form-control" id="project_title" name="project_title"
                                        placeholder="Enter Project Title" value="{{ $staff_edit->project_title }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="project_duration">Project Duration</label>
                                    <input type="text" class="form-control" id="project_duration"
                                        name="project_duration" placeholder="Enter Project Duration"
                                        value="{{ $staff_edit->project_duration }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="application_date">Application Date</label>
                                    <input type="text" class="form-control date" name="application_date"
                                        placeholder="Enter Application Date"
                                        value="{{ $staff_edit->application_date }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="application_status">Application Status</label>
                                    <select class="form-control select2" name="application_status"
                                        id="application_status">
                                        <option value=""
                                            {{ $staff_edit->application_status == '' ? 'selected' : '' }}>Please Select
                                        </option>
                                        <option value="Applied"
                                            {{ $staff_edit->application_status == 'Applied' ? 'selected' : '' }}>Applied
                                        </option>
                                        <option value="Granted"
                                            {{ $staff_edit->application_status == 'Granted' ? 'selected' : '' }}>Granted
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="investigator_level">Investigator Level</label>
                                    <select class="form-control select2" name="investigator_level"
                                        id="investigator_level">
                                        <option value=""
                                            {{ $staff_edit->investigator_level == '' ? 'selected' : '' }}>Please Select
                                        </option>
                                        <option value="PI"
                                            {{ $staff_edit->investigator_level == 'PI' ? 'selected' : '' }}>PI</option>
                                        <option value="CO-PI"
                                            {{ $staff_edit->investigator_level == 'CO-PI' ? 'selected' : '' }}>CO-PI
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="funding_amount">Sponsered Amount</label>
                                    <input type="text" class="form-control" id="funding_amount" name="funding_amount"
                                        placeholder="Enter Sponsered Amount" value="{{ $staff_edit->funding_amount }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="received_date">Amount Received Date</label>
                                    <input type="text" class="form-control date" id="received_date"
                                        name="received_date" placeholder="Enter Fund Received Date"
                                        value="{{ $staff_edit->received_date }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="sanctioned_letter">Upload Sanctioned Letter</label>
                                    <input type="file" name="sanctioned_letter" id="sanctioned_letter">
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
                        <h5 class="mb-3 text-primary">Sponser List</h5>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Sponser Name
                                    </th>
                                    <th>
                                        Project Title
                                    </th>
                                    <th>
                                        Project Duration
                                    </th>
                                    <th>
                                        Application Date
                                    </th>
                                    <th>
                                        Application Status
                                    </th>
                                    <th>
                                        Investigator Level
                                    </th>
                                    <th>
                                        Sponsered Amount
                                    </th>
                                    <th>
                                        Amount Received Date
                                    </th>
                                    <th>
                                        Sanctioned Letter
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
                                        <td>{{ $list[$i]->sponser_name }}</td>
                                        <td>{{ $list[$i]->project_title }}</td>
                                        <td>{{ $list[$i]->project_duration }}</td>
                                        <td>{{ $list[$i]->application_date }}</td>
                                        <td>{{ $list[$i]->application_status }}</td>
                                        <td>{{ $list[$i]->investigator_level }}</td>
                                        <td>{{ $list[$i]->funding_amount }}</td>
                                        <td>{{ $list[$i]->received_date }}</td>
                                        @if ($list[$i]->sanctioned_letter != '' && $list[$i]->sanctioned_letter != null)
                                            <td>
                                                <img class="uploaded_img"
                                                    src="{{ asset($list[$i]->sanctioned_letter) }}" alt="image">
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>
                                            @if ($userId == 1)
                                                @if ($list[$i]->status == '0')

                                                        <form method="POST"
                                                            action="{{ route('admin.sponsers.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                            enctype="multipart/form-data" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                            @csrf
                                                            <button type="submit" name="accept" value="accept"
                                                                class="btn btn-success  ">Accept</button>
                                                        </form>

                                                        <form
                                                            action="{{ route('admin.sponsers.destroy', $list[$i]->id) }}"
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
                                                action="{{ route('admin.sponsers.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn Edit btn-info">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.sponsers.destroy', $list[$i]->id) }}"
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
