<div class="container">
    <div class="row gutters">
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.addresses.stu_update', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Address Details</h5>

                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="address_type">Address Type</label>




                                    <select class="form-control select2" name="address_type">
                                        <option value="Permanent"
                                            {{ old('address_type', $stu_edit->address_type) == 'Permanent' ? 'selected' : '' }}>
                                            Permanent</option>
                                        <option value="Temporary"
                                            {{ old('address_type', $stu_edit->address_type) == 'Temporary' ? 'selected' : '' }}>
                                            Temporary</option>
                                    </select>


                                </div>
                            </div>


                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="room_no_and_street">Room No & Street</label>
                                    <input type="text" class="form-control" name="room_no_and_street"
                                        placeholder="Enter Room No & Street"
                                        value="{{ $stu_edit->room_no_and_street }}">
                                </div>
                            </div>


                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="area_name">Area</label>
                                    <input type="text" class="form-control" name="area_name" placeholder="Enter Area"
                                        value="{{ $stu_edit->area_name }}">
                                </div>
                            </div>


                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="district">District</label>
                                    <input type="text" class="form-control" name="district"
                                        placeholder="Enter District" value="{{ $stu_edit->district }}">
                                </div>
                            </div>


                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="pincode">Pincode</label>
                                    <input type="text" class="form-control" name="pincode"
                                        placeholder="Enter Pincode" value="{{ $stu_edit->pincode }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" name="state" placeholder="Enter State"
                                        value="{{ $stu_edit->state }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" class="form-control" name="country"
                                        placeholder="Enter Country" value="{{ $stu_edit->country }}">
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
                                        class="btn btn-primary Edit">{{ $stu_edit->add }}</button>
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
                        <h5 class="mb-3 text-primary">Address List</h5>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Address Type
                                    </th>
                                    <th>
                                        Room No & Street
                                    </th>
                                    <th>
                                        Area
                                    </th>
                                    <th>
                                        District
                                    </th>
                                    <th>
                                        Pincode
                                    </th>
                                    <th>
                                        State
                                    </th>
                                    <th>
                                        Country
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @for ($i = 0; $i < count($list); $i++)
                                    @if ($list[$i]->address_type != '' || $list[$i]->address_type != null)
                                        <tr>
                                            <td>{{ $list[$i]->address_type }}</td>
                                            <td>{{ $list[$i]->room_no_and_street }}</td>
                                            <td>{{ $list[$i]->area_name }}</td>
                                            <td>{{ $list[$i]->district }}</td>
                                            <td>{{ $list[$i]->pincode }}</td>
                                            <td>{{ $list[$i]->state }}</td>
                                            <td>{{ $list[$i]->country }}</td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('admin.addresses.stu_updater', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $list[$i]->id]) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <button type="submit" id="updater" name="updater"
                                                        value="{{ $list[$i]->address_type }}"
                                                        class="btn Edit btn-info">Edit</button>
                                                </form>
                                                <form action="{{ route('admin.addresses.destroy', $list[$i]->id) }}"
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
