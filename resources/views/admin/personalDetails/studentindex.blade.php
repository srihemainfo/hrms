<style>
    input[type="file"] {
        /* background-color: #f2f2f2; */
        border: none;
        /* color: #555; */
        cursor: pointer;
        font-size: 16px;
        /* padding: 10px; */
    }


    input[type="file"]:focus {
        outline: none;
    }

    .relative_div {
        position: relative;
    }

    .checky_box {
        width: 18px;
        height: 18px;
        position: absolute;
        top: 18%;
        right: 1%;
    }
</style>
<div class="container">
    @if (auth()->user()->id != $student->user_name_id)
        <div class="row gutters">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route('admin.personal-details.stu_update', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-2 text-primary">Personal Details</h6>
                                </div>
                            </div>
                            @if (
                                (isset($student->filePath) ? $student->filePath : '') != '' ||
                                    (isset($student->filePath) ? $student->filePath : '') != null)
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <img class="uploaded_img" src="{{ asset($student->filePath) }}" alt="image">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="filePath">Change Profile Image</label>
                                            <input type="hidden" name="fileName" value="Profile">
                                            <input type="file" class="form-control" name="filePath" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" style="text-transform:uppercase;" class="form-control"
                                                name="name" placeholder="Enter full name"
                                                value="{{ $student->name }}">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="filePath">Upload Profile Image</label>
                                            <input type="hidden" name="fileName" value="Profile">
                                            <input type="file" class="form-control" name="filePath" value="">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Enter full name" value="{{ $student->name }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter email ID" value="{{ $student->email }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile</label>
                                        <input type="number" class="form-control" id="mobile_number"
                                            name="mobile_number" placeholder="Enter phone number"
                                            value="{{ $student->mobile_number }}" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="aadhar_number">Aadhar Number</label>
                                        <input type="text" class="form-control" name="aadhar_number"
                                            placeholder="Enter Aadhar Number" value="{{ $student->aadhar_number }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input type="text" class="form-control date" name="dob"
                                            placeholder="YYYY-MM-DD" value="{{ $student->dob }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="text" class="form-control" name="age"
                                            placeholder="Enter Age" value="{{ $student->age }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="whatsapp_no">WhatsApp Number</label>
                                        <input type="number" class="form-control" name="whatsapp_no"
                                            placeholder="Enter WhatsApp Number" value="{{ $student->whatsapp_no }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="annual_income">Annual Income</label>
                                        <input type="number" class="form-control" name="annual_income"
                                            placeholder="Enter Annual Income" value="{{ $student->annual_income }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="gender">GENDER</label>
                                        <select class="form-control select2 " name="gender">
                                            <option value="" {{ $student->gender == '' ? 'selected' : '' }}>
                                                Please Select</option>
                                            <option value="MALE" {{ $student->gender == 'MALE' ? 'selected' : '' }}>
                                                MALE</option>
                                            <option value="FEMALE"
                                                {{ $student->gender == 'FEMALE' ? 'selected' : '' }}>FEMALE</option>
                                        </select>
                                    </div>
                                </div>

                                @if ($student->blood_group != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="blood_group_id">Blood Group</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('blood_group') ? 'is-invalid' : '' }}"
                                                name="blood_group_id" id="blood_group_id">
                                                @foreach ($student->blood_group as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('blood_group_id') ? old('blood_group_id') : $student->blood_group_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="blood_group_id">Blood Group</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('blood_group') ? 'is-invalid' : '' }}"
                                                name="blood_group_id" id="blood_group_id">
                                                @foreach ($student->blood_group_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('blood_group_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($student->mother_tongue != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mother_tongue_id">Mother Tongue</label>
                                            <select
                                                class="form-control select2 kk {{ $errors->has('mother_tongue') ? 'is-invalid' : '' }}"
                                                name="mother_tongue_id" id="mother_tongue_id">
                                                @foreach ($student->mother_tongue as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('mother_tongue_id') ? old('mother_tongue_id') : $student->mother_tongue_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mother_tongue_id">Mother Tongue</label>
                                            <select
                                                class="form-control select2 ll {{ $errors->has('mother_tongue') ? 'is-invalid' : '' }}"
                                                name="mother_tongue_id" id="mother_tongue_id">
                                                @foreach ($student->mother_tongue_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('mother_tongue_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($student->religion != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="religion_id">Religion</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}"
                                                name="religion_id" id="religion_id">
                                                @foreach ($student->religion as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('religion_id') ? old('religion_id') : $student->religion_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="religion_id">Religion</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}"
                                                name="religion_id" id="religion_id">
                                                @foreach ($student->religion_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('religion_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($student->community != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="community_id">Community</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('community') ? 'is-invalid' : '' }}"
                                                name="community_id" id="community_id">
                                                @foreach ($student->community as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('community_id') ? old('community_id') : $student->community_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="community_id">Community</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('community') ? 'is-invalid' : '' }}"
                                                name="community_id" id="community_id">
                                                @foreach ($student->community_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('community_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="caste">Caste</label>
                                        <input type="text" style="text-transform:uppercase;" class="form-control"
                                            name="caste" placeholder=" Enter Caste" value="{{ $student->caste }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" name="state"
                                            placeholder="Enter State" value="{{ $student->state }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="country">Nationality</label>
                                        <input type="text" class="form-control" name="country"
                                            placeholder="Enter Country" value="{{ $student->country }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="different_abled_person">Different Abled Person</label>
                                        <select name="different_abled_person" id="different_abled_person"
                                            class="form-control select2">
                                            <option value="0"
                                                {{ $student->different_abled_person == '0' ? 'selected' : '' }}>
                                                No
                                            </option>
                                            <option value="1"
                                                {{ $student->different_abled_person == '1' ? 'selected' : '' }}>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="text-right" style="padding-top:5.5rem;">
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
    @elseif(auth()->user()->id == $student->user_name_id)
        <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                <div class="card" style="margin-top: 16px;">
                    <div class="card-header">
                        <div class="row gutters">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Personal Details</h5>
                            </div>
                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
                                <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus"
                                        style="font-size:1.5em;"></i></h5>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body view_more">
                        <form method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $student->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $student->email == '' ? $student->student_email_id : $student->email }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile</label>
                                        <input type="text" class="form-control" id="mobile_number"
                                            name="mobile_number" value="{{ $student->mobile_number }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="aadhar_number">Aadhar Number</label>
                                        <input type="text" class="form-control" name="aadhar_number"
                                            value="{{ $student->aadhar_number }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input type="text" class="form-control date" name="dob"
                                            value="{{ $student->dob }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="text" class="form-control" name="age"
                                            value="{{ $student->age }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="gender">GENDER</label>
                                        <input type="text" class="form-control" name="gender"
                                            value="{{ $student->gender }}"readonly>
                                    </div>
                                </div>
                                @if ($student->blood_group_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="blood_group_id">Blood Group</label>
                                            @foreach ($student->blood_group as $id => $entry)
                                                @if ($student->blood_group_id == $id)
                                                    <input type="text" class="form-control"
                                                        value="{{ $entry }}"readonly>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="blood_group_id">Blood Group</label>
                                            <input type="text" class="form-control"readonly>
                                        </div>
                                    </div>
                                @endif
                                @if ($student->mother_tongue_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mother_tongue_id">Mother Tongue</label>
                                            @foreach ($student->mother_tongue as $id => $entry)
                                                @if ($student->mother_tongue_id == $id)
                                                    <input type="text" class="form-control"
                                                        value="{{ $entry }}"readonly>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mother_tongue_id">Mother Tongue</label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endif
                                @if ($student->religion_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="religion_id">Religion</label>
                                            @foreach ($student->religion as $id => $entry)
                                                @if ($student->religion_id == $id)
                                                    <input type="text" class="form-control"
                                                        value="{{ $entry }}" readonly>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="religion_id">Religion</label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endif
                                @if ($student->community_id != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="community_id">Community</label>

                                            @foreach ($student->community as $id => $entry)
                                                @if ($student->community_id == $id)
                                                    <input type="text" class="form-control"
                                                        value="{{ $entry }}" readonly>
                                                @endif
                                            @endforeach

                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="community_id">Community</label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" name="state"
                                            value="{{ $student->state }}"readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" class="form-control" name="country"
                                            value="{{ $student->country }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
