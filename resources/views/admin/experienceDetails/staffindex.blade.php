<div class="container">

    <div class="row gutters">
        {{-- {{ dd($staff); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-header">

                        <h5 class="mb-2 text-primary">Experience Details</h5>

                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.experience-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="designation">Designation</label>
                                    <input type="text" class="form-control" name="designation"
                                        placeholder="Enter Designation" value="{{ $staff_edit->designation }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <input type="text" class="form-control" name="department"
                                        placeholder="Enter Department" value="{{ $staff_edit->department }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="name_of_organisation">Organisation Name</label>
                                    <input type="text" class="form-control" name="name_of_organisation"
                                        placeholder="Enter Organisation Name"
                                        value="{{ $staff_edit->name_of_organisation }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="taken_subjects">Taken Subjects</label>
                                    <input type="text" class="form-control" name="taken_subjects"
                                        placeholder="Enter Taken Subjects" value="{{ $staff_edit->taken_subjects }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="doj">Date Of Joining</label>
                                    <input type="text" class="form-control date" name="doj"
                                        placeholder="Enter Date Of Joining" value="{{ $staff_edit->doj }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="dor">Date Of Leaving</label>
                                    <input type="text" class="form-control date" name="dor"
                                        placeholder="Enter Date Of Leaving" value="{{ $staff_edit->dor }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="last_drawn_salary">Last Drawn Salary</label>
                                    <input type="number" class="form-control" name="last_drawn_salary"
                                        placeholder="Enter Last Drawn Salary"
                                        value="{{ $staff_edit->last_drawn_salary }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="responsibilities">Responsibilities</label>
                                    <input type="text" class="form-control" name="responsibilities"
                                        placeholder="Enter Responsibilities"
                                        value="{{ $staff_edit->responsibilities }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="leaving_reason">Leaving Reason</label>
                                    <input type="text" class="form-control" name="leaving_reason"
                                        placeholder="Enter Leaving Reason" value="{{ $staff_edit->leaving_reason }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" name="address"
                                        placeholder="Enter Address" value="{{ $staff_edit->address }}">
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
                        <h6 class="mb-3 text-primary">Experience Details List</h6>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Designation
                                    </th>
                                    <th>
                                        Department
                                    </th>
                                    <th>
                                        Organisation Name
                                    </th>
                                    <th>
                                        Taken Subjects
                                    </th>
                                    <th>
                                        Date Of Joining
                                    </th>
                                    <th>
                                        Date Of Leaving
                                    </th>
                                    <th>
                                        Last Drawn Salary
                                    </th>
                                    <th>
                                        Responsibilities
                                    </th>
                                    <th>
                                        leave Reason
                                    </th>
                                    <th>
                                        Address
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @for ($i = 0; $i < count($list); $i++)
                                    <tr>
                                        <td>{{ $list[$i]->designation }}</td>
                                        <td>{{ $list[$i]->department }}</td>
                                        <td>{{ $list[$i]->name_of_organisation }}</td>
                                        <td>{{ $list[$i]->taken_subjects }}</td>
                                        <td>{{ $list[$i]->doj }}</td>
                                        <td>{{ $list[$i]->dor }}</td>
                                        <td>{{ $list[$i]->last_drawn_salary }}</td>
                                        <td>{{ $list[$i]->responsibilities }}</td>
                                        <td>{{ $list[$i]->leaving_reason }}</td>
                                        <td>{{ $list[$i]->address }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.experience-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn btn-xs btn-info">Edit</button>
                                            </form>
                                            <form
                                                action="{{ route('admin.experience-details.destroy', $list[$i]->id) }}"
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
