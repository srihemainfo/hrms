<div class="container">
    <div class="row gutters">
        {{-- {{ dd($staff) }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.employment-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Employment Details</h6>
                            </div>
                        </div>
                        <div class="row gutters">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="BiometricID">Biometric ID</label>
                                    <input type="number" class="form-control" id="BiometricID" name="BiometricID"
                                        placeholder="Enter Biometric ID" value="{{ $staff->BiometricID }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="AICTE">AICTE</label>
                                    <input type="text" class="form-control" name="AICTE" placeholder="Enter AICTE"
                                        value="{{ $staff->AICTE }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="DOJ">Date Of Joining</label>
                                    <input type="text" class="form-control date" name="DOJ"
                                        placeholder="Enter Date Of Joining" value="{{ $staff->DOJ }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="DOR">Date Of Relieving</label>
                                    <input type="text" class="form-control date" name="DOR"
                                        placeholder="Enter Date Of Relieving" value="{{ $staff->DOR }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="au_card_no">Anna University Code</label>
                                    <input type="text" class="form-control" name="au_card_no"
                                        placeholder="Enter Anna University Code" value="{{ $staff->au_card_no }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="employment_type">Employment Type</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('employment_type') ? 'is-invalid' : '' }}"
                                        name="employment_type" id="employment_type">
                                        <option value="" {{ $staff->employment_type == '' ? 'selected' : '' }}>
                                            Please
                                            Select</option>
                                        <option value="Permanent"
                                            {{ $staff->employment_type == 'Permanent' ? 'selected' : '' }}>Permanent
                                        </option>
                                        <option value="Adjunct"
                                            {{ $staff->employment_type == 'Adjunct' ? 'selected' : '' }}>Adjunct
                                        </option>
                                        <option value="Visiting"
                                            {{ $staff->employment_type == 'Visiting' ? 'selected' : '' }}>Visiting
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="employment_status">Employment Status</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('employment_status') ? 'is-invalid' : '' }}"
                                        name="employment_status" id="employment_status">
                                        <option value="" {{ $staff->employment_status == '' ? 'selected' : '' }}>
                                            Please Select</option>
                                        <option value="Active"
                                            {{ $staff->employment_status == 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Medical Leave"
                                            {{ $staff->employment_status == 'Medical Leave' ? 'selected' : '' }}>
                                            Medical
                                            Leave
                                        </option>
                                        <option value="Break"
                                            {{ $staff->employment_status == 'Break' ? 'selected' : '' }}>Break
                                        </option>
                                        <option value="Maternity Leave"
                                            {{ $staff->employment_status == 'Maternity Leave' ? 'selected' : '' }}>
                                            Maternity
                                            Leave
                                        </option>
                                        <option value="Relieving"
                                            {{ $staff->employment_status == 'Relieving' ? 'selected' : '' }}>
                                            Relieving
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="rit_club_incharge">RIT Club Incharge</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('rit_club_incharge') ? 'is-invalid' : '' }}"
                                        name="rit_club_incharge" id="rit_club_incharge">
                                        <option value="1"
                                            {{ $staff->rit_club_incharge == true ? 'selected' : '' }}>
                                            YES</option>
                                        <option value="0"
                                            {{ $staff->rit_club_incharge == false || $staff->rit_club_incharge == null ? 'selected' : '' }}>
                                            NO
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="future_tech_membership">Future Tech Centre Membership</label>
                                    <input type="text" class="form-control" name="future_tech_membership"
                                        placeholder="" value="{{ $staff->future_tech_membership }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="future_tech_membership_type">Future Tech Centre Membership Type</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('future_tech_membership_type') ? 'is-invalid' : '' }}"
                                        name="future_tech_membership_type" id="future_tech_membership_type">
                                        <option value=""
                                            {{ $staff->future_tech_membership_type == '' ? 'selected' : '' }}>
                                            Please Select</option>
                                        <option value="In-Charge"
                                            {{ $staff->future_tech_membership_type == 'In-Charge' ? 'selected' : '' }}>
                                            In-Charge
                                        </option>
                                        <option value="Member"
                                            {{ $staff->future_tech_membership_type == 'Member' ? 'selected' : '' }}>
                                            Member </option>
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
                                        class="btn btn-primary">{{ $staff->add }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
