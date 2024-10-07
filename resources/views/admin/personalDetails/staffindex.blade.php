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
</style>
@php
    // Define an array of all the languages
    $languages = [
        'tamil' => 'Tamil',
        'malayalam' => 'Malayalam',
        'telugu' => 'Telugu',
        'kannada' => 'Kannada',
        'hindi' => 'Hindi',
        'urdu' => 'Urdu',
        'bengali' => 'Bengali',
        'marathi' => 'Marathi',
        'punjabi' => 'Punjabi',
        'english' => 'English',
        'spanish' => 'Spanish',
        'french' => 'French',
        'german' => 'German',
        'chinese' => 'Chinese',
        'japanese' => 'Japanese',
        'korean' => 'Korean',
        'arabic' => 'Arabic',
        'portuguese' => 'Portuguese',
        'russian' => 'Russian',
        'italian' => 'Italian',
    ];
@endphp
<div class="container">
    @if (auth()->user()->id != $staff->user_name_id)
        <div class="row gutters">
            {{-- {{ dd($staff) }} --}}
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route('admin.personal-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-2 text-primary">Personal Details</h6>
                                </div>
                            </div>


                            @if (
                                (isset($staff->filePath) ? $staff->filePath : '') != '' ||
                                    (isset($staff->filePath) ? $staff->filePath : '') != null)
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <img class="uploaded_img" src="{{ asset($staff->filePath) }}" alt="image">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="filePath">Change Profile Image</label>
                                            <input type="hidden" name="fileName" value="Profile">
                                            <input type="file" class="form-control" name="filePath" value="">
                                        </div>

                                        <div class="form-group">
                                            <label for="StaffCode">Staff Code</label>
                                            <input type="text" class="form-control" id="StaffCode" name="StaffCode"
                                                placeholder="Enter Staff Code" value="{{ $staff->StaffCode }}">
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
                                            <label for="employee_id">Employee ID</label>
                                            <input type="text" class="form-control" id="employee_id"
                                                name="employee_id" placeholder="Enter Employee ID"
                                                value="{{ $staff->employee_id }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <span>
                                <strong class="text-primary">Supported Formats :</strong> JPG, PNG, JPEG (Max Size: 2MB)
                            </span>
                            <div class="row gutters">

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="name">First Name</label>
                                        <input type="text" style="text-transform:uppercase;" class="form-control"
                                            name="name" placeholder="Enter First Name"
                                            value="{{ $staff->first_name }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter email ID" value="{{ $staff->email }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone_number">Mobile</label>
                                        <input type="number" class="form-control" id="phone_number" name="phone_number"
                                            placeholder="Enter Phone Number" value="{{ $staff->phone_number }}"
                                            maxlength="10">
                                    </div>
                                </div>
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Department</label>
                                        <input type="text" class="form-control" id="phone" placeholder=""
                                            value="{{ $staff->Dept }}" readonly>
                                    </div>
                                </div> --}}
                                {{-- @if ($staff->department != '')
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="department_id">Department</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('department') ? 'is-invalid' : '' }}"
                                            name="department_id" id="department_id">
                                            @foreach ($staff->departmentss as $id => $entry)
                                                <option value="{{ $id }}"

                                                    {{ (old('department') ? old('department') : $staff->department ?? '') == $id ? 'selected' : '' }}>
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="department_id">Department</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('department') ? 'is-invalid' : '' }}"
                                            name="department_id" id="department_id">
                                            @foreach ($staff->departmentss as $id => $entry)
                                                <option value="{{ $id }}"

                                                    {{ old('department_id') == $id ? 'selected' : '' }}>
                                                    {{ $entry }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif --}}
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="aadhar_number">Aadhar Number</label>
                                        <input type="text" class="form-control" name="aadhar_number"
                                            placeholder="Enter Aadhar Number" value="{{ $staff->aadhar_number }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="emergency_contact_no">Emergency Contact Number</label>
                                        <input type="text" class="form-control" name="emergency_contact_no"
                                            placeholder="Enter Emergency Contact Number"
                                            value="{{ $staff->emergency_contact_no }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_experience">Total Experience</label>
                                        <input type="text" class="form-control" name="total_experience"
                                            placeholder="Enter Total Experience"
                                            value="{{ $staff->total_experience }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="pan_number">Pan No</label>
                                        <input type="text" class="form-control" name="pan_number"
                                            placeholder="Enter Pan No" value="{{ $staff->pan_number }}">
                                    </div>
                                </div>
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
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
                            </div> --}}

                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="au_card_no">Anna University Code</label>
                                    <input type="text" class="form-control" name="au_card_no"
                                        placeholder="Enter Anna University Code" value="{{ $staff->au_card_no }}">
                                </div>
                            </div> --}}
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="COECode">COE Code</label>
                                        <input type="text" class="form-control" name="COECode"
                                            placeholder="Enter COE Code" value="{{ $staff->COECode }}">
                                    </div>
                                </div> --}}
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="PassportNo">Passport No</label>
                                        <input type="text" class="form-control" name="PassportNo"
                                            placeholder="Enter Passport No" value="{{ $staff->PassportNo }}">
                                    </div>
                                </div> --}}
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="father_name">Father Name</label>
                                        <input type="text" class="form-control" name="father_name"
                                            placeholder="Enter Father Name" value="{{ $staff->father_name }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="spouse_name">Spouse Name</label>
                                        <input type="text" class="form-control" name="spouse_name"
                                            placeholder="Enter Spouse Name" value="{{ $staff->spouse_name }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input class="form-control date" type="text" name="dob"
                                            id="dob" placeholder="YYYY-MM-DD" value="{{ $staff->dob }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="text" class="form-control" name="age"
                                            placeholder="Enter Age" value="{{ $staff->age }}">
                                    </div>
                                </div>
                                @if ($staff->blood_group != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="blood_group_id">Blood Group</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('blood_group') ? 'is-invalid' : '' }}"
                                                name="blood_group_id" id="blood_group_id">
                                                @foreach ($staff->blood_group as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('blood_group_id') ? old('blood_group_id') : $staff->blood_group_id ?? '') == $id ? 'selected' : '' }}>
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
                                                @foreach ($staff->blood_group_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('blood_group_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($staff->mother_tongue != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mother_tongue_id">Mother Tongue</label>
                                            <select
                                                class="form-control select2 kk {{ $errors->has('mother_tongue') ? 'is-invalid' : '' }}"
                                                name="mother_tongue_id" id="mother_tongue_id">
                                                @foreach ($staff->mother_tongue as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('mother_tongue_id') ? old('mother_tongue_id') : $staff->mother_tongue_id ?? '') == $id ? 'selected' : '' }}>
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
                                                @foreach ($staff->mother_tongue_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('mother_tongue_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($staff->religion != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="religion_id">Religion</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}"
                                                name="religion_id" id="religion_id">
                                                @foreach ($staff->religion as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('religion_id') ? old('religion_id') : $staff->religion_id ?? '') == $id ? 'selected' : '' }}>
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
                                                @foreach ($staff->religion_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('religion_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($staff->community != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="community_id">Community</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('community') ? 'is-invalid' : '' }}"
                                                name="community_id" id="community_id">
                                                @foreach ($staff->community as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('community_id') ? old('community_id') : $staff->community_id ?? '') == $id ? 'selected' : '' }}>
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
                                                @foreach ($staff->community_id as $id => $entry)
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
                                        <label for="gender">GENDER</label>
                                        <select class="form-control select2 " name="gender">
                                            <option value="" {{ $staff->gender == '' ? 'selected' : '' }}>Please
                                                Select</option>
                                            <option value="MALE" {{ $staff->gender == 'MALE' ? 'selected' : '' }}>
                                                MALE</option>
                                            <option value="FEMALE" {{ $staff->gender == 'FEMALE' ? 'selected' : '' }}>
                                                FEMALE</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="marital_status">Marital Status</label>
                                        <select class="form-control select2" name="marital_status"
                                            id="marital_status">
                                            <option value=""
                                                {{ $staff->marital_status == '' ? 'selected' : '' }}>
                                                Please Select</option>
                                            <option value="MARRIED"
                                                {{ $staff->marital_status == 'MARRIED' ? 'selected' : '' }}>MARRIED
                                            </option>
                                            <option value="UNMARRIED"
                                                {{ $staff->marital_status == 'UNMARRIED' ? 'selected' : '' }}>UNMARRIED
                                            </option>
                                            <option value="DIVORCE"
                                                {{ $staff->marital_status == 'DIVORCE' ? 'selected' : '' }}>DIVORCE
                                            </option>
                                            <option value="WIDOW"
                                                {{ $staff->marital_status == 'WIDOW' ? 'selected' : '' }}>WIDOW
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                @if ($staff->state != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="state_id">State</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('state') ? 'is-invalid' : '' }}"
                                                name="state_id" id="state_id">
                                                @foreach ($staff->state as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('state_id') ? old('state_id') : $staff->state_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="state_id">State</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('state') ? 'is-invalid' : '' }}"
                                                name="state_id" id="state_id">
                                                @foreach ($staff->state_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('state_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($staff->nationality != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="nationality_id">Nationality</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('nationality') ? 'is-invalid' : '' }}"
                                                name="nationality_id" id="nationality_id">
                                                @foreach ($staff->nationality as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('nationality_id') ? old('nationality_id') : $staff->nationality_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="nationality_id">Nationality</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('nationality') ? 'is-invalid' : '' }}"
                                                name="nationality_id" id="nationality_id">
                                                @foreach ($staff->nationality_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('nationality_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="country">Nationality</label>
                                        <input type="text" class="form-control" name="country"
                                            placeholder="Enter Country" value="{{ $staff->country }}">
                                    </div>
                                </div> --}}

                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="known_languages">Languages Known</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('known_languages') ? 'is-invalid' : '' }}"
                                            name="known_languages[]" id="known_languages" multiple>

                                            @if ($staff->known_languages != '')
                                                @foreach ($languages as $key => $language)
                                                    <option value="{{ $key }}"
                                                        {{ in_array($key, $staff->known_languages) ? 'selected' : '' }}>
                                                        {{ $language }}
                                                    </option>
                                                @endforeach
                                            @elseif($staff->known_languages == '')
                                                @foreach ($languages as $key => $language)
                                                    <option value="{{ $key }}">
                                                        {{ $language }}
                                                    </option>
                                                @endforeach
                                            @endif


                                        </select>
                                    </div>
                                </div> --}}


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
    @elseif(auth()->user()->id == $staff->user_name_id)
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
                        <form method="POST"
                            action="{{ route('admin.personal-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                            enctype="multipart/form-data">
                            @csrf
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
                                        <label for="employee_id">Employee ID</label>
                                        <input type="text" class="form-control" id="employee_id"
                                            name="employee_id" placeholder="Enter Employee ID"
                                            value="{{ $staff->employee_id }} " readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="name">First Name</label>
                                        <input type="text" style="text-transform:uppercase;" class="form-control"
                                            name="name" placeholder="Enter First Name"
                                            value="{{ $staff->first_name }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter email ID" value="{{ $staff->email }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone_number">Mobile</label>
                                        <input type="number" class="form-control" id="phone_number"
                                            name="phone_number" placeholder="Enter Phone Number"
                                            value="{{ $staff->phone_number }}" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="aadhar_number">Aadhar Number</label>
                                        <input type="text" class="form-control" name="aadhar_number"
                                            placeholder="Enter Aadhar Number" value="{{ $staff->aadhar_number }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="emergency_contact_no">Emergency Contact Number</label>
                                        <input type="text" class="form-control" name="emergency_contact_no"
                                            value="{{ $staff->emergency_contact_no }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="total_experience">Total Experience</label>
                                        <input type="text" class="form-control" name="total_experience"
                                            value="{{ $staff->total_experience }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="pan_number">Pan No</label>
                                        <input type="text" class="form-control" name="pan_number"
                                            value="{{ $staff->pan_number }}">
                                    </div>
                                </div>
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="DOJ">Date Of Joining</label>
                                        <input type="text" class="form-control date" name="DOJ"
                                            value="{{ $staff->DOJ }}">
                                    </div>
                                </div> --}}
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="DOR">Date Of Relieving</label>
                                        <input type="text" class="form-control date" name="DOR"
                                            value="{{ $staff->DOR }}">
                                    </div>
                                </div> --}}

                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="au_card_no">Anna University Code</label>
                                        <input type="text" class="form-control" name="au_card_no"
                                            value="{{ $staff->au_card_no }}">
                                    </div>
                                </div> --}}
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="COECode">COE Code</label>
                                        <input type="text" class="form-control" name="COECode"
                                            value="{{ $staff->COECode }}" readonly>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="PassportNo">Passport No</label>
                                        <input type="text" class="form-control" name="PassportNo"
                                            value="{{ $staff->PassportNo }}" readonly>
                                    </div>
                                </div> --}}
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="father_name">Father Name</label>
                                        <input type="text" class="form-control" name="father_name"
                                            value="{{ $staff->father_name }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="spouse_name">Spouse Name</label>
                                        <input type="text" class="form-control" name="spouse_name"
                                            value="{{ $staff->spouse_name }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input class="form-control date" type="text" name="dob"
                                            id="dob" value="{{ $staff->dob }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="text" class="form-control" name="age"
                                            value="{{ $staff->age }}">
                                    </div>
                                </div>
                                @if ($staff->blood_group != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="blood_group_id">Blood Group</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('blood_group') ? 'is-invalid' : '' }}"
                                                name="blood_group_id" id="blood_group_id">
                                                @foreach ($staff->blood_group as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('blood_group_id') ? old('blood_group_id') : $staff->blood_group_id ?? '') == $id ? 'selected' : '' }}>
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
                                                @foreach ($staff->blood_group_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('blood_group_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($staff->mother_tongue != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mother_tongue_id">Mother Tongue</label>
                                            <select
                                                class="form-control select2 kk {{ $errors->has('mother_tongue') ? 'is-invalid' : '' }}"
                                                name="mother_tongue_id" id="mother_tongue_id">
                                                @foreach ($staff->mother_tongue as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('mother_tongue_id') ? old('mother_tongue_id') : $staff->mother_tongue_id ?? '') == $id ? 'selected' : '' }}>
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
                                                @foreach ($staff->mother_tongue_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('mother_tongue_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($staff->religion != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="religion_id">Religion</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}"
                                                name="religion_id" id="religion_id">
                                                @foreach ($staff->religion as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('religion_id') ? old('religion_id') : $staff->religion_id ?? '') == $id ? 'selected' : '' }}>
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
                                                @foreach ($staff->religion_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('religion_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($staff->community != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="community_id">Community</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('community') ? 'is-invalid' : '' }}"
                                                name="community_id" id="community_id">
                                                @foreach ($staff->community as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('community_id') ? old('community_id') : $staff->community_id ?? '') == $id ? 'selected' : '' }}>
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
                                                @foreach ($staff->community_id as $id => $entry)
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
                                        <label for="gender">GENDER</label>
                                        <select class="form-control select2 " name="gender">
                                            <option value="" {{ $staff->gender == '' ? 'selected' : '' }}>
                                                Please
                                                Select</option>
                                            <option value="MALE" {{ $staff->gender == 'MALE' ? 'selected' : '' }}>
                                                MALE</option>
                                            <option value="FEMALE"
                                                {{ $staff->gender == 'FEMALE' ? 'selected' : '' }}>
                                                FEMALE</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($staff->state != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="state_id">State</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('state') ? 'is-invalid' : '' }}"
                                                name="state_id" id="state_id">
                                                @foreach ($staff->state as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('state_id') ? old('state_id') : $staff->state_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="state_id">State</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('state') ? 'is-invalid' : '' }}"
                                                name="state_id" id="state_id">
                                                @foreach ($staff->state_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('state_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="marital_status">Marital Status</label>
                                        <select class="form-control select2" name="marital_status"
                                            id="marital_status">
                                            <option value=""
                                                {{ $staff->marital_status == '' ? 'selected' : '' }}>
                                                Please Select</option>
                                            <option value="MARRIED"
                                                {{ $staff->marital_status == 'MARRIED' ? 'selected' : '' }}>MARRIED
                                            </option>
                                            <option value="UNMARRIED"
                                                {{ $staff->marital_status == 'UNMARRIED' ? 'selected' : '' }}>
                                                UNMARRIED
                                            </option>
                                            <option value="DIVORCE"
                                                {{ $staff->marital_status == 'DIVORCE' ? 'selected' : '' }}>DIVORCE
                                            </option>
                                            <option value="WIDOW"
                                                {{ $staff->marital_status == 'WIDOW' ? 'selected' : '' }}>WIDOW
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                @if ($staff->nationality != '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="nationality_id">Nationality</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('nationality') ? 'is-invalid' : '' }}"
                                                name="nationality_id" id="nationality_id">
                                                @foreach ($staff->nationality as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('nationality_id') ? old('nationality_id') : $staff->nationality_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="nationality_id">Nationality</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('nationality') ? 'is-invalid' : '' }}"
                                                name="nationality_id" id="nationality_id">
                                                @foreach ($staff->nationality_id as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ old('nationality_id') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Department</label>
                                        <input type="text" class="form-control" id="phone" placeholder=""
                                            value="{{ $staff->Dept }}" readonly>
                                    </div>
                                </div> --}}
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
    @endif
</div>
