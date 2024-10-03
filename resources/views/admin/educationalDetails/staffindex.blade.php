{{-- {{ dd($staff_edit) }} --}}
<?php

?>
<div class="container mb-3">
    @if (auth()->user()->id != $staff->user_name_id)
        <div class="row gutters">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route('admin.educational-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-2 text-primary">Educational Details</h6>
                                </div>

                                {{-- @if ($staff_edit->education_type_id == '')
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label for="education_type_id" class="required">Education Type</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('education_type') ? 'is-invalid' : '' }}"
                                                name="education_type_id" id="education_type_id"
                                                onchange="check_exist()">
                                                @foreach ($staff_edit->education_types as $id => $entry)
                                                    <option value="{{ $id }}">
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else --}}
                                    {{-- {{ dd($staff_edit->education_type_id) }} --}}
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label for="education_type_id" class="required">Education Type</label>
                                            <select class="form-control select2" name="education_type_id"
                                                id="education_type_id">
                                                @foreach ($staff_edit->education_types as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('education_type_id') ? old('education_type_id') : $staff_edit->education_type_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                {{-- @endif --}}
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="qualification">Qualification</label>
                                        <input type="text" class="form-control" name="qualification"
                                            placeholder="Enter Qualification" value="{{ $staff_edit->qualification }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="course_duration">Course Duration</label>
                                        <input type="number" class="form-control" name="course_duration"
                                            placeholder="Enter Course Duration"
                                            value="{{ $staff_edit->course_duration }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="institute_name">Institute Name</label>
                                        <input type="text" class="form-control" name="institute_name"
                                            placeholder="Enter Institute Name"
                                            value="{{ $staff_edit->institute_name }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="institute_location">Institute Location</label>
                                        <input type="text" class="form-control" name="institute_location"
                                            placeholder="Enter Institute Location"
                                            value="{{ $staff_edit->institute_location }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="board_or_university">Board / University</label>
                                        <input type="text" class="form-control" name="board_or_university"
                                            placeholder="Enter Board/University"
                                            value="{{ $staff_edit->board_or_university }}">
                                    </div>
                                </div>
                                @if ($staff_edit->medium_id == '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="medium_id">Medium</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('medium') ? 'is-invalid' : '' }}"
                                                name="medium_id" id="medium_id">
                                                @foreach ($staff_edit->medium as $id => $entry)
                                                    <option value="{{ $id }}">
                                                        {{ $entry }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="medium_id">Medium</label>
                                            <select class="form-control select2" name="medium_id" id="medium_id">
                                                @foreach ($staff_edit->medium as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('medium_id') ? old('medium_id') : $staff_edit->medium_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="month_value" class="required">Month/Year Of Passing</label>
                                        @php
                                            $date_as = $staff_edit->month_value;
                                            $time_stamp = strtotime($date_as);
                                            $month = date('Y-m', $time_stamp);
                                        @endphp
                                        <input type="month" class="form-control" name="month_value"
                                            value="{{ $staff_edit->month_value == '' ? '' : $month }}" required>
                                    </div>

                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="marks_in_percentage">Total Marks In Percentage / CGPA</label>
                                        <input type="text" class="form-control" name="marks_in_percentage"
                                            placeholder="Enter Total Marks in Percentage"
                                            value="{{ $staff_edit->marks_in_percentage }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="study_mode">Mode Of Study</label>
                                        <select class="form-control select2" name="study_mode" id="study_mode">
                                            <option value=""
                                                {{ $staff_edit->study_mode == '' || $staff_edit->study_mode == null ? 'selected' : '' }}>
                                                Please Select</option>
                                            <option value="Full Time"
                                                {{ $staff_edit->study_mode == 'Full Time' ? 'selected' : '' }}>Full
                                                Time
                                            </option>
                                            <option value="Part Time"
                                                {{ $staff_edit->study_mode == 'Part Time' ? 'selected' : '' }}>Part
                                                Time
                                            </option>
                                            <option value="Distance Education"
                                                {{ $staff_edit->study_mode == 'Distance Education' ? 'selected' : '' }}>
                                                Distance Education</option>
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
    @endif
    {{-- {{ dd($list); }} --}}
    @if (count($list) > 0)
        <div class="row gutters mt-3 mb-3">
            <div class="col" style="padding:0;">
                <div class="card h-100">

                    <div class="card-body table-responsive">
                        <h6 class="mb-3 text-primary">Educational Details List</h6>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Education Type
                                    </th>
                                    <th>
                                        Qualification
                                    </th>
                                    <th>
                                        Course Duration
                                    </th>
                                    <th>
                                        Institute Name
                                    </th>
                                    <th>
                                        Institute Location
                                    </th>
                                    <th>
                                        Board / University
                                    </th>
                                    <th>
                                        Marks In Percentage / CGPA
                                    </th>
                                    <th>
                                        Medium
                                    </th>
                                    <th>
                                        Month/Year
                                    </th>
                                    <th>
                                        Mode Of Study
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @for ($i = 0; $i < count($list); $i++)
                                    <tr>
                                        @if ($list[$i]->education_type_id != '' || $list[$i]->education_type_id != null)
                                            @foreach ($list[$i]->education_types as $id => $entry)
                                                @if ($list[$i]->education_type_id == $id)
                                                    <td>{{ $entry }} <span class="educate"
                                                            style="display:none">{{ $id }}</span> </td>
                                                @endif
                                            @endforeach
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $list[$i]->qualification }}</td>
                                        <td>{{ $list[$i]->course_duration }}</td>
                                        <td>{{ $list[$i]->institute_name }}</td>
                                        <td>{{ $list[$i]->institute_location }}</td>
                                        <td>{{ $list[$i]->board_or_university }}</td>
                                        <td>{{ $list[$i]->marks_in_percentage }}</td>


                                        @if ($list[$i]->medium_id != '' || $list[$i]->medium_id != null)
                                            @foreach ($list[$i]->medium as $id => $entry)
                                                @if ($list[$i]->medium_id == $id)
                                                    <td>{{ $entry }}</td>
                                                @endif
                                            @endforeach
                                        @else
                                            <td></td>
                                        @endif
                                        <?php
                                        $date = $list[$i]->month_value;
                                        $timestamp = strtotime($date);
                                        $month_valuee = date('Y-m', $timestamp);

                                        ?>
                                        <td>{{ $month_valuee }}</td>
                                        <td>{{ $list[$i]->study_mode }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.educational-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn btn-xs btn-info">Edit</button>
                                            </form>
                                            <form
                                                action="{{ route('admin.educational-details.destroy', $list[$i]->id) }}"
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
    @else
        @if (auth()->user()->id == $staff->user_name_id)
            <div class="row gutters mt-3 mb-3">
                <div class="col" style="padding:0;">
                    <div class="card h-100">
                        <div class="card-body">
                            <div style="text-align: center;padding-top:15px;"> Not Added Yet..!</div>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
<script>
    let table_data = document.getElementsByClassName("educate");

    let selector = document.getElementById("education_type_id");


    let length = table_data.length;
    // console.log(selected_value);
    function check_exist() {

        // console.log(selector.value);
        for (let i = 0; i < length; i++) {
            if (selector.value == 1 || selector.value == 2) {
                if (selector.value == table_data[i].innerHTML) {
                    alert("Already Data Added");
                    location.reload();

                }
            }

        }
    }
</script>
