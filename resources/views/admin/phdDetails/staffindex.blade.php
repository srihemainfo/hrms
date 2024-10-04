<div class="container">
    <div class="row gutters">
        {{-- {{ dd($staff) }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.phd-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Ph.D Details</h6>
                            </div>
                        </div>
                        <div class="row gutters">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="institute_name">Institution/Research Center Name</label>
                                    <input type="text" class="form-control" name="institute_name"
                                        placeholder="Enter Institution/Research Center Name"
                                        value="{{ $staff_edit->institute_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="university_name">University Name</label>
                                    <input type="text" class="form-control" name="university_name"
                                        placeholder="Enter University Name" value="{{ $staff_edit->university_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="thesis_title">Title of the Thesis</label>
                                    <input type="text" class="form-control" name="thesis_title"
                                        placeholder="Enter Title of the Thesis" value="{{ $staff_edit->thesis_title }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="research_area">Area of the Research</label>
                                    <input type="text" class="form-control" name="research_area"
                                        placeholder="Enter Area of the Research"
                                        value="{{ $staff_edit->research_area }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="supervisor_name">Supervisor Name</label>
                                    <input type="text" class="form-control" name="supervisor_name"
                                        placeholder="Enter Supervisor Name" value="{{ $staff_edit->supervisor_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="supervisor_details">Supervisor Details</label>
                                    <input type="text" class="form-control" name="supervisor_details"
                                        placeholder="Enter Supervisor Details"
                                        value="{{ $staff_edit->supervisor_details }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="" {{ $staff_edit->status == '' ? 'selected' : '' }}>Please
                                            Select</option>
                                        <option value="Completed"
                                            {{ $staff_edit->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Pursuing" {{ $staff_edit->status == 'Pursuing' ? 'selected' : '' }}>
                                            Pursuing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="registration_year">Month and Year of Registration</label>
                                    @php
                                        $date_as = $staff_edit->registration_year;
                                        $time_stamp = strtotime($date_as);
                                        $month = date('Y-m', $time_stamp);
                                    @endphp
                                    <input type="month" class="form-control" name="registration_year"
                                        value="{{ $staff_edit->registration_year == '' ? '' : $month }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="viva_date">Viva Voce Date</label>
                                    <input type="text" class="form-control date" name="viva_date"
                                        placeholder="Enter Viva Voce Date" value="{{ $staff_edit->viva_date }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="total_years">Total No of Year</label>
                                    <input type="number" class="form-control" name="total_years"
                                        placeholder="Enter Total No of Year" value="{{ $staff_edit->total_years }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="mode">Mode</label>
                                    <select class="form-control select2" name="mode" id="mode" required>
                                        <option value="" {{ $staff_edit->mode == '' ? 'selected' : '' }}>Please
                                            Select</option>
                                        <option value="FULL TIME"
                                            {{ $staff_edit->mode == 'FULL TIME' ? 'selected' : '' }}>FULL TIME</option>
                                        <option value="PART TIME" {{ $staff_edit->mode == 'PART TIME' ? 'selected' : '' }}>
                                            PART TIME</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
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
    @if (count($list) > 0)
        <div class="row gutters mt-3 mb-3">
            <div class="col" style="padding:0;">
                <div class="card h-100">

                    <div class="card-body table-responsive">
                        <h6 class="mb-3 text-primary">Ph.D Details List</h6>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Institution/Research Center Name
                                    </th>
                                    <th>
                                        University Name
                                    </th>
                                    <th>
                                        Title of the Thesis
                                    </th>
                                    <th>
                                        Area of the Research
                                    </th>
                                    <th>
                                        Supervisor Name
                                    </th>
                                    <th>
                                        Supervisor Details
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Month and Year of Registration
                                    </th>
                                    <th>
                                        Viva Voce Date
                                    </th>
                                    <th>
                                        Total No of Year
                                    </th>
                                    <th>
                                        Mode
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @for ($i = 0; $i < count($list); $i++)
                                    <tr>

                                        <td>{{ $list[$i]->institute_name }}</td>
                                        <td>{{ $list[$i]->university_name }}</td>
                                        <td>{{ $list[$i]->thesis_title }}</td>
                                        <td>{{ $list[$i]->research_area }}</td>
                                        <td>{{ $list[$i]->supervisor_name }}</td>
                                        <td>{{ $list[$i]->supervisor_details }}</td>
                                        <td>{{ $list[$i]->status }}</td>

                                        @php
                                        $date = $list[$i]->registration_year;
                                        $timestamp = strtotime($date);
                                        $registration_year = date('Y-m', $timestamp);
                                        @endphp
                                        <td>{{ $registration_year }}</td>
                                        <td>{{ $list[$i]->viva_date }}</td>
                                        <td>{{ $list[$i]->total_years }}</td>
                                        <td>{{ $list[$i]->mode }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.phd-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn btn-xs btn-info">Edit</button>
                                            </form>
                                            <form
                                                action="{{ route('admin.phd-details.destroy', $list[$i]->id) }}"
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
