{{-- {{ dd($stu_edit) }} --}}
<div class="container mb-3">
    @php
        $array = [];
        if (count($list) > 0) {
            foreach ($list as $data) {
                array_push($array, $data->education_type_id);
            }
        }
        // dd($array);
    @endphp
    @if (auth()->user()->id != $student->user_name_id)
        <div class="row gutters">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route('admin.educational-details.stu_update', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $stu_edit->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-2 text-primary">Educational Details</h6>
                                </div>

                                @if ($stu_edit->education_type_id == '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="education_type_id">Education Type</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('education_type') ? 'is-invalid' : '' }}"
                                                name="education_type_id" id="education_type_id" onchange="check_exist()">
                                                @foreach ($stu_edit->education_types as $id => $entry)
                                                                <option value="{{ $id }}">
                                                                    {{ $entry }}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    {{-- {{ dd($stu_edit->education_type_id) }} --}}
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="education_type_id">Education Type</label>
                                            <select class="form-control select2" name="education_type_id"
                                                id="education_type">
                                                @foreach ($stu_edit->education_types as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('education_type_id') ? old('education_type_id') : $stu_edit->education_type_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="institute_name">Institute Name</label>
                                        <input type="text" class="form-control" name="institute_name"
                                            placeholder="Enter Institute Name" value="{{ $stu_edit->institute_name }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="institute_location">Institute Location</label>
                                        <input type="text" class="form-control" name="institute_location"
                                            placeholder="Enter Institute Location"
                                            value="{{ $stu_edit->institute_location }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="board_or_university">Board / University</label>
                                        <input type="text" class="form-control" name="board_or_university"
                                            placeholder="Enter Board/University"
                                            value="{{ $stu_edit->board_or_university }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="board_or_university">Register Number</label>
                                        <input type="text" class="form-control" name="register_number"
                                            placeholder="Enter Register Number"
                                            value="{{ $stu_edit->register_number }}">
                                    </div>
                                </div>
                                @if ($stu_edit->medium_id == '')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="medium_id">Medium</label>
                                            <select
                                                class="form-control select2 {{ $errors->has('medium') ? 'is-invalid' : '' }}"
                                                name="medium_id" id="medium_id">
                                                @foreach ($stu_edit->medium as $id => $entry)
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
                                                @foreach ($stu_edit->medium as $id => $entry)
                                                    <option value="{{ $id }}"
                                                        {{ (old('medium_id') ? old('medium_id') : $stu_edit->medium_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="passing_year">Year Of Passing</label>
                                        <input type="number" class="form-control" name="passing_year"
                                            placeholder="Enter Year Of Passing" value="{{ $stu_edit->passing_year }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="marks">Total Marks</label>
                                        <input type="text" class="form-control" name="marks"
                                            placeholder="Enter Total Marks" value="{{ $stu_edit->marks }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="cutoffmark">Cutoff Marks</label>
                                        <input type="text" class="form-control" name="cutoffmark"
                                            placeholder="Enter Cutoff Mark" value="{{ $stu_edit->cutoffmark }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="marks_in_percentage">Total Marks In Percentage</label>
                                        <input type="text" class="form-control" name="marks_in_percentage"
                                            placeholder="Enter Total Marks in Percentage"
                                            value="{{ $stu_edit->marks_in_percentage }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="subject_1">Subject - 1</label>
                                        <input type="text" class="form-control" name="subject_1"
                                            placeholder="Enter Subject - 1" value="{{ $stu_edit->subject_1 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mark_1">Mark in Subject - 1</label>
                                        <input type="text" class="form-control" name="mark_1"
                                            placeholder="Enter Mark in Subject - 1" value="{{ $stu_edit->mark_1 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="subject_2">Subject - 2</label>
                                        <input type="text" class="form-control" name="subject_2"
                                            placeholder="Enter Subject - 2" value="{{ $stu_edit->subject_2 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mark_2">Mark in Subject - 2</label>
                                        <input type="text" class="form-control" name="mark_2"
                                            placeholder="Enter Mark in Subject - 2" value="{{ $stu_edit->mark_2 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="subject_3">Subject - 3</label>
                                        <input type="text" class="form-control" name="subject_3"
                                            placeholder="Enter Subject - 3" value="{{ $stu_edit->subject_3 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mark_3">Mark in Subject - 3</label>
                                        <input type="text" class="form-control" name="mark_3"
                                            placeholder="Enter Mark in Subject - 3" value="{{ $stu_edit->mark_3 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="subject_4">Subject - 4</label>
                                        <input type="text" class="form-control" name="subject_4"
                                            placeholder="Enter Subject - 4" value="{{ $stu_edit->subject_4 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mark_4">Mark in Subject - 4</label>
                                        <input type="text" class="form-control" name="mark_4"
                                            placeholder="Enter Mark in Subject - 4" value="{{ $stu_edit->mark_4 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="subject_5">Subject - 5</label>
                                        <input type="text" class="form-control" name="subject_5"
                                            placeholder="Enter Subject - 5" value="{{ $stu_edit->subject_5 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mark_5">Mark in Subject - 5</label>
                                        <input type="text" class="form-control" name="mark_5"
                                            placeholder="Enter Mark in Subject - 5" value="{{ $stu_edit->mark_5 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="subject_6">Subject - 6</label>
                                        <input type="text" class="form-control" name="subject_6"
                                            placeholder="Enter Subject - 6" value="{{ $stu_edit->subject_6 }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="mark_6">Mark in Subject - 6</label>
                                        <input type="text" class="form-control" name="mark_6"
                                            placeholder="Enter Mark in Subject - 6" value="{{ $stu_edit->mark_6 }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="highestqualification" name="highestqualification">
                            <label class="form-check-label" for="highestqualification">
                              Highest Qualification
                            </label>
                          </div>
                        </div> --}}

                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
                                        {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
                                        <button type="submit" id="submit" name="submit"
                                            class="btn btn-primary">{{ $stu_edit->add }}</button>
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
        @if (auth()->user()->id != $student->user_name_id)
            <div class="row gutters mt-3 mb-3">
                <div class="col" style="padding:0;">
                    <div class="card h-100">

                        <div class="card-body table-responsive">
                            <h6 class="mb-3 text-primary">Educational Details List</h6>
                            <table class="list_table ">
                                <thead>
                                    <tr>
                                        <th>
                                            Education Type
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
                                            Register Number
                                        </th>
                                        <th>
                                            Total Marks
                                        </th>
                                        <th>
                                            Cutoff Mark
                                        </th>
                                        <th>
                                            Marks In Percentage
                                        </th>
                                        <th>
                                            Medium
                                        </th>
                                        <th>
                                            Subject 1
                                        </th>
                                        <th>
                                            Mark 1
                                        </th>
                                        <th>
                                            Subject 2
                                        </th>
                                        <th>
                                            Mark 2
                                        </th>
                                        <th>
                                            Subject 3
                                        </th>
                                        <th>
                                            Mark 3
                                        </th>
                                        <th>
                                            Subject 4
                                        </th>
                                        <th>
                                            Mark 4
                                        </th>
                                        <th>
                                            Subject 5
                                        </th>
                                        <th>
                                            Mark 5
                                        </th>
                                        <th>
                                            Subject 6
                                        </th>
                                        <th>
                                            Mark 6
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
                                                        <td>{{ $entry }} <span class="educate" style="display:none">{{ $id }}</span> </td>
                                                    @endif
                                                @endforeach
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $list[$i]->institute_name }}</td>
                                            <td>{{ $list[$i]->institute_location }}</td>
                                            <td>{{ $list[$i]->board_or_university }}</td>
                                            <td>{{ $list[$i]->register_number }}</td>
                                            <td>{{ $list[$i]->marks }}</td>
                                            <td>{{ $list[$i]->cutoffmark }}</td>
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
                                            <td>{{ $list[$i]->subject_1 }}</td>
                                            <td>{{ $list[$i]->mark_1 }}</td>
                                            <td>{{ $list[$i]->subject_2 }}</td>
                                            <td>{{ $list[$i]->mark_2 }}</td>
                                            <td>{{ $list[$i]->subject_3 }}</td>
                                            <td>{{ $list[$i]->mark_3 }}</td>
                                            <td>{{ $list[$i]->subject_4 }}</td>
                                            <td>{{ $list[$i]->mark_4 }}</td>
                                            <td>{{ $list[$i]->subject_5 }}</td>
                                            <td>{{ $list[$i]->mark_5 }}</td>
                                            <td>{{ $list[$i]->subject_6 }}</td>
                                            <td>{{ $list[$i]->mark_6 }}</td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('admin.educational-details.stu_updater', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $list[$i]->id]) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <button type="submit" id="updater" name="updater"
                                                        value="updater" class="btn btn-xs btn-info">Edit</button>
                                                </form>
                                                <form
                                                    action="{{ route('admin.educational-details.destroy', $list[$i]->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                    style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token"
                                                        value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger"
                                                        value="{{ trans('global.delete') }}">
                                                </form>
                                            </td>
                                        </tr>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->id == $student->user_name_id)
            <div class="row gutters mt-3 mb-3">
                <div class="col" style="padding:0;">
                    <div class="card h-100">

                        <div class="card-body table-responsive">
                            <h6 class="mb-3 text-primary">Educational Details List</h6>
                            <table class="list_table ">
                                <thead>
                                    <tr>
                                        <th>
                                            Education Type
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
                                            Register Number
                                        </th>
                                        <th>
                                            Total Marks
                                        </th>
                                        <th>
                                            Cutoff Mark
                                        </th>
                                        <th>
                                            Marks In Percentage
                                        </th>
                                        <th>
                                            Medium
                                        </th>
                                        <th>
                                            Subject 1
                                        </th>
                                        <th>
                                            Mark 1
                                        </th>
                                        <th>
                                            Subject 2
                                        </th>
                                        <th>
                                            Mark 2
                                        </th>
                                        <th>
                                            Subject 3
                                        </th>
                                        <th>
                                            Mark 3
                                        </th>
                                        <th>
                                            Subject 4
                                        </th>
                                        <th>
                                            Mark 4
                                        </th>
                                        <th>
                                            Subject 5
                                        </th>
                                        <th>
                                            Mark 5
                                        </th>
                                        <th>
                                            Subject 6
                                        </th>
                                        <th>
                                            Mark 6
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @for ($i = 0; $i < count($list); $i++)
                                        <tr>
                                            @if ($list[$i]->education_type_id != '' || $list[$i]->education_type_id != null)
                                                @foreach ($list[$i]->education_types as $id => $entry)
                                                    @if ($list[$i]->education_type_id == $id)
                                                        <td>{{ $entry }}</td>
                                                    @endif
                                                @endforeach
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $list[$i]->institute_name }}</td>
                                            <td>{{ $list[$i]->institute_location }}</td>
                                            <td>{{ $list[$i]->board_or_university }}</td>
                                            <td>{{ $list[$i]->register_number }}</td>
                                            <td>{{ $list[$i]->marks }}</td>
                                            <td>{{ $list[$i]->cutoffmark }}</td>
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
                                            <td>{{ $list[$i]->subject_1 }}</td>
                                            <td>{{ $list[$i]->mark_1 }}</td>
                                            <td>{{ $list[$i]->subject_2 }}</td>
                                            <td>{{ $list[$i]->mark_2 }}</td>
                                            <td>{{ $list[$i]->subject_3 }}</td>
                                            <td>{{ $list[$i]->mark_3 }}</td>
                                            <td>{{ $list[$i]->subject_4 }}</td>
                                            <td>{{ $list[$i]->mark_4 }}</td>
                                            <td>{{ $list[$i]->subject_5 }}</td>
                                            <td>{{ $list[$i]->mark_5 }}</td>
                                            <td>{{ $list[$i]->subject_6 }}</td>
                                            <td>{{ $list[$i]->mark_6 }}</td>

                                        </tr>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        @if (auth()->user()->id == $student->user_name_id)
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
    function check_exist(){

        // console.log(selector.value);
        for(let i = 0; i < length; i++){
            if(selector.value == table_data[i].innerHTML){
                alert("Already Data Added");
                location.reload();

            }
        }
    }


</script>
