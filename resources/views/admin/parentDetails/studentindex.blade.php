<div class="container">
    <div class="row gutters">
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.parent-details.stu_update', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Parent Details</h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="father_name">Father's Name</label>
                                    <input type="text" class="form-control" name="father_name"
                                        placeholder="Enter Father's Name" value="{{ $student->father_name }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="mother_name">Mother's Name</label>
                                    <input type="text" class="form-control" name="mother_name"
                                        placeholder="Enter Mother's Name" value="{{$student->mother_name}}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="father_mobile_no">Father's Mobile No</label>
                                    <input type="text" class="form-control" name="father_mobile_no"
                                        placeholder="Enter Father's Mobile No" value="{{$student->father_mobile_no}}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="mother_mobile_no">Mother's Mobile No</label>
                                    <input type="text" class="form-control" name="mother_mobile_no"
                                        placeholder="Enter Mother's Mobile No" value="{{$student->mother_mobile_no}}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="father_email">Father's Email</label>
                                    <input type="text" class="form-control" name="father_email"
                                        placeholder="Enter Father's Email" value="{{$student->father_email}}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="mother_email">Mother's Email</label>
                                    <input type="text" class="form-control" name="mother_email"
                                        placeholder="Enter Mother's Email" value="{{$student->mother_email}}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="fathers_occupation">Father's Occupation</label>
                                    <input type="text" class="form-control" name="fathers_occupation"
                                        placeholder="Enter Father's Occupation" value="{{$student->fathers_occupation}}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="mothers_occupation">Mother's Occupation</label>
                                    <input type="text" class="form-control" name="mothers_occupation"
                                        placeholder="Enter Mother's Occupation" value="{{$student->mothers_occupation}}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="father_off_address">Father's Office Address</label>
                                    <textarea type="text" class="form-control" id="father_off_address" name="father_off_address" placeholder="Enter Father's Office Address"
                                    value="{{ $student->father_off_address }}">{{ $student->father_off_address }}</textarea>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="mother_off_address">Mother's Office Address</label>
                                    <textarea type="text" class="form-control" id="mother_off_address" name="mother_off_address" placeholder="Enter Mother's Office Address"
                                    value="{{ $student->mother_off_address }}">{{ $student->mother_off_address }}</textarea>

                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="guardian_name">Guardian's Name(If)</label>
                                    <input type="text" class="form-control" name="guardian_name"
                                        placeholder="Enter Guardian's Name" value="{{$student->guardian_name}}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="guardian_mobile_no">Guardian's Mobile No</label>
                                    <input type="text" class="form-control" name="guardian_mobile_no"
                                        placeholder="Enter Guardian's Mobile No" value="{{$student->guardian_mobile_no}}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="guardian_email">Guardian's Email</label>
                                    <input type="text" class="form-control" name="guardian_email"
                                        placeholder="Enter Guardian's Email" value="{{$student->guardian_email}}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="gaurdian_occupation">Guardian's Occupation</label>
                                    <input type="text" class="form-control" name="gaurdian_occupation"
                                        placeholder="Enter Guardian's Occupation" value="{{$student->gaurdian_occupation}}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="guardian_off_address">Guardian's Office Address</label>
                                    <textarea type="text" class="form-control" id="guardian_off_address" name="guardian_off_address" placeholder="Enter Guardian's Office Address"
                                    value="{{ $student->guardian_off_address }}">{{ $student->guardian_off_address }}</textarea>

                                </div>
                            </div>

                        </div>

                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
                                    <button type="submit" id="submit" name="submit"
                                        class="btn btn-primary">{{ $student->add }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

