@extends('layouts.admin')
@section('content')

    <a class="btn btn-default" style="margin-bottom:17px;" href="{{ route('admin.subject-allotment.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card" id="open_form">
        <div class="card-header text-center">Semester Wise Subjects Allotment</div>
        <div class="card-header text-center">
            <div class="row gutters">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Regulation</label>
                        <select class="select2 form-control" name="regulation_id" id="regulation_id">
                            @foreach ($regulation as $id => $data)
                                <option value="{{ $id }}" {{ $reg_id == $id ? 'selected' : '' }}>
                                    {{ $data }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Department</label>
                        <select class="select2 form-control" name="department_id" id="department_id">
                            @foreach ($department as $id => $data)
                                <option value="{{ $id }}" {{ $dept_id == $id ? 'selected' : '' }}>
                                    {{ $data }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Course</label>
                        <select class="select2 form-control" name="course_id" id="course_id">
                            @foreach ($course as $id => $data)
                                <option value="{{ $id }}" {{ $course_id == $id ? 'selected' : '' }}>
                                    {{ $data }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">AY</label>
                        <select class="select2 form-control" name="academic_year" id="academic_year">
                            @foreach ($academic_years as $id => $data)
                                <option value="{{ $id }}" {{ $ay_id == $id ? 'selected' : '' }}>
                                    {{ $data }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Semester</label>
                        <select class="select2 form-control" name="semester_id" id="semester_id">
                            @foreach ($semester as $id => $data)
                                <option value="{{ $id }}" {{ $sem_id == $id ? 'selected' : '' }}>
                                    {{ $data }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Sem Type</label>
                        <select class="select2 form-control" name="semester_type" id="semester_type">
                            <option value="">Select Sem Type</option>
                            <option value="ODD" {{ $sem_type == 'ODD' ? 'selected' : '' }}>ODD</option>
                            <option value="EVEN" {{ $sem_type == 'EVEN' ? 'selected' : '' }}>EVEN</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($check_course == 13 || $check_course == '13')

                <form action="" id="regular_form">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Regular Subjects</div>
                                <div style="width:30%;text-align:center;">
                                    <div style="right:0;background-color:gray;" class="manual_bn">All Subjects Are Mandatory
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="regular-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($regular) > 0)
                                        @foreach ($regular as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="regular-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">
                                                    {{ $subject->subjects->subject_code }}</td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="regular" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="" id="pg_professional_form" class="mba">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Electives Human Resource</div>
                                <div style="width:20%;">
                                    {{-- <select class="form-control select2" name="professional_limit" id="professional_limit">
                                    <option value="">Select Limit</option>
                                    @if (count($professional) > 0)
                                        @for ($a = 1; $a <= count($professional); $a++)
                                            <option value="{{ $a }}"
                                                {{ $professional[0]->option_limits == $a ? 'selected' : '' }}>
                                                {{ $a }}</option>
                                        @endfor
                                    @endif
                                </select> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="professional-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($hr) > 0)
                                        @foreach ($hr as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="pg-professional-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">{{ $subject->subjects->subject_code }}
                                                </td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="professional" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="" id="pg_open_elec_form" class="mba">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Electives Finance</div>
                                <div style="width:20%;">
                                    {{-- <select class="form-control select2" name="open_limit" id="open_limit">
                                    <option value="">Select Limit</option>
                                    @if (count($open) > 0)
                                        @for ($a = 1; $a <= count($open); $a++)
                                            <option value="{{ $a }}"
                                                {{ $open[0]->option_limits == $a ? 'selected' : '' }}>
                                                {{ $a }}
                                            </option>
                                        @endfor
                                    @endif
                                </select> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="pg-open-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($finance) > 0)
                                        @foreach ($finance as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="pg-open-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">{{ $subject->subjects->subject_code }}
                                                </td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="pg-open" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="" id="pg_others_form" class="mba">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Elective Operations</div>
                                <div style="width:20%;">
                                    {{-- <select class="form-control select2" name="others_limit" id="others_limit">
                                    <option value="">Select Limit</option>
                                    @if (count($others) > 0)
                                        @for ($a = 1; $a <= count($others); $a++)
                                            <option value="{{ $a }}"
                                                {{ $others[0]->option_limits == $a ? 'selected' : '' }}>
                                                {{ $a }}
                                            </option>
                                        @endfor
                                    @endif
                                </select> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="pg-others-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($operations) > 0)
                                        @foreach ($operations as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="pg-others-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">{{ $subject->subjects->subject_code }}
                                                </td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="pg-others" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="" id="pg_logistics_form" class="mba">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Elective Logistics</div>
                                <div style="width:20%;">
                                    {{-- <select class="form-control select2" name="others_limit" id="others_limit">
                                    <option value="">Select Limit</option>
                                    @if (count($logistics) > 0)
                                        @for ($a = 1; $a <= count($logistics); $a++)
                                            <option value="{{ $a }}"
                                                {{ $logistics[0]->option_limits == $a ? 'selected' : '' }}>
                                                {{ $a }}
                                            </option>
                                        @endfor
                                    @endif
                                </select> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="pg-logistics-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($logistics) > 0)
                                        @foreach ($logistics as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="pg-logistics-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">{{ $subject->subjects->subject_code }}
                                                </td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="pg-logistics" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <form action="" id="regular_form">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Regular Subjects</div>
                                <div style="width:30%;text-align:center;">
                                    <div style="right:0;background-color:gray;" class="manual_bn">All Subjects Are
                                        Mandatory
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="regular-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($regular) > 0)
                                        @foreach ($regular as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="regular-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">
                                                    {{ $subject->subjects->subject_code }}</td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="regular" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="" id="professional_form" class="common">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Professional Electives</div>
                                <div style="width:20%;">
                                    <select class="form-control select2" name="professional_limit"
                                        id="professional_limit">
                                        <option value="">Select Limit</option>
                                        @if (count($professional) > 0)
                                            @for ($a = 1; $a <= count($professional); $a++)
                                                <option value="{{ $a }}"
                                                    {{ $professional[0]->option_limits == $a ? 'selected' : '' }}>
                                                    {{ $a }}</option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="professional-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($professional) > 0)
                                        @foreach ($professional as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="professional-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">{{ $subject->subjects->subject_code }}
                                                </td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="professional" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="" id="open_elec_form" class="common">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Open Electives</div>
                                <div style="width:20%;">
                                    <select class="form-control select2" name="open_limit" id="open_limit">
                                        <option value="">Select Limit</option>
                                        @if (count($open) > 0)
                                            @for ($a = 1; $a <= count($open); $a++)
                                                <option value="{{ $a }}"
                                                    {{ $open[0]->option_limits == $a ? 'selected' : '' }}>
                                                    {{ $a }}
                                                </option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="open-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($open) > 0)
                                        @foreach ($open as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="open-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">{{ $subject->subjects->subject_code }}
                                                </td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="open" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="" id="others_form" class="common">
                    <div class="card">
                        <div class="card-header">
                            <div style="display:flex;justify-content:space-between">
                                <div style="" class="manual_bn">Others</div>
                                <div style="width:20%;">
                                    <select class="form-control select2" name="others_limit" id="others_limit">
                                        <option value="">Select Limit</option>
                                        @if (count($others) > 0)
                                            @for ($a = 1; $a <= count($others); $a++)
                                                <option value="{{ $a }}"
                                                    {{ $others[0]->option_limits == $a ? 'selected' : '' }}>
                                                    {{ $a }}
                                                </option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="others-table">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if (count($others) > 0)
                                        @foreach ($others as $subject)
                                            <tr class="normal">
                                                <td>{{ $i }}</td>
                                                <td><input type="hidden" name="others-table{{ $i }}"
                                                        value="{{ $subject->subjects->id }}">{{ $subject->subjects->subject_code }}
                                                </td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                                <td>
                                                    <input type="checkbox" name="checkbox" value="{{ $subject->id }}"
                                                        style="width:18px;height:18px;accent-color:red;">
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div style="text-align:right;padding-top:15px;">
                                <i style="color:#007bff;font-size:1.5rem;cursor: pointer;" class="fa fa-plus-circle"
                                    id="others" onclick="get_subjects(this)">
                                </i>
                            </div>
                        </div>
                    </div>
                </form>
            @endif

            <div style="text-align:right;">
                <button class="enroll_generate_bn" onclick="submit()">Update</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary">Select Subject</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-2 col-lg-2 col-md-1 col-sm-1 col-12"></div>
                        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 col-12">
                            <div class="form-group">
                                <label for="subject" class="required">Subject</label>
                                <select class="form-control select2" name="subject" id="subject">

                                </select>
                                <input type="hidden" id="decider" name="decider" value="">
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-1 col-sm-1 col-12"></div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" onclick="save()">Add</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            $("select").select2();
        }

        function get_subjects(element) {
            // console.log(element.id);
            let id = element.id;

            let reg = $("#regulation_id").val();
            let dept = $("#department_id").val();
            let course = $("#course_id").val();
            let sem = $("#semester_id").val();
            let sem_type = $("#semester_type").val();
            let ay = $("#academic_year").val();

            let inputs = {
                'reg': reg,
                'dept': dept,
                'course': course,
                'sem': sem
            };
            // console.log(id)
            if (id != '') {
                $.ajax({
                    url: '{{ route('admin.subject-allotment.get_subjects') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: inputs,
                    success: function(response) {
                        // console.log(response);
                        if (response.subjects) {
                            let data = response.subjects;
                            let len = data.length;
                            let subjects = '';
                            if (len > 0) {
                                subject_array = data;
                                subjects = `<option value =''>Select Subject</option>`;
                                for (let i = 0; i < len; i++) {
                                    subjects +=
                                        `<option value ='${data[i].id}'>${data[i].name}(${data[i].subject_code})</option>`;
                                }
                            }
                            $("#decider").val(id);
                            $("#subject").html(subjects);
                            // $("#subject").select2();
                            $('#exampleModal').modal("show");
                            // console.log(len)
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                });
            }
        }

        function save() {

            let array_len = subject_array.length;
            let got_subject = $("#subject").val();
            let new_subject = '';
            let selector = $("#decider").val() + '_limit';
            let parent_table = $("#decider").val() + '-table';
            var added_subjects = $("#" + parent_table).children().length;
            // console.log($("#" + parent_table).children().length);
            if (got_subject != '') {
                for (let a = 0; a < array_len; a++) {
                    if (got_subject == subject_array[a].id) {
                        new_subject = subject_array[a];
                    }
                }
            }
            console.log(new_subject.subject_type);
            console.log(subject_array);
            if (new_subject != '') {
                let sub_type = null;
                if (new_subject.subject_type.name) {
                    sub_type = new_subject.subject_type.name;
                }
                let new_datas =
                    `<tr class="normal"><td>${added_subjects + 1}</td><td><input type="hidden" name="${parent_table}${added_subjects + 1}" value="${new_subject.id}">${new_subject.subject_code}</td><td>${new_subject.name}</td><td>${sub_type}</td><td>${new_subject.credits}</td><td></td></tr>`;

                let option = `<option value="${added_subjects + 1}">${added_subjects + 1}</option>`;

                $("#" + parent_table).append(new_datas);
                // console.log(option);
                $("#" + selector).append(option);
            }
            $('#exampleModal').modal("hide");
        }

        function submit() {
            let form_1 = '';
            let form_2 = '';
            let form_3 = '';
            let form_4 = '';
            let form_5 = '';
            if ($('#course_id').val() == 13 || $('#course_id').val() == '13') {
                form_1 = $("#regular_form").serializeArray();
                form_2 = $("#pg_professional_form").serializeArray();
                form_3 = $("#pg_open_elec_form").serializeArray();
                form_4 = $("#pg_others_form").serializeArray();
                form_5 = $("#pg_logistics_form").serializeArray();
            } else {
                console.log($('#department_id').val());
                form_1 = $("#regular_form").serializeArray();
                form_5 = '';
            }
            console.log($('#course_id').val());

            let reg = $("#regulation_id").val();
            let dept = $("#department_id").val();
            let course = $("#course_id").val();
            let sem = $("#semester_id").val();
            let sem_type = $("#semester_type").val();
            let ay = $("#academic_year").val();

            let inputs = {
                'reg': reg,
                'dept': dept,
                'course': course,
                'sem': sem,
                'sem_type': sem_type,
                'ay': ay
            };
            // console.log(form_1);
            // let data = {'regular':form_1,'professional':form_2,'open':form_3,'others':form_4,'inputs':inputs};
            $.ajax({
                url: '{{ route('admin.subject-allotment.updater') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'regular': form_1,
                    'professional': form_2,
                    'open': form_3,
                    'others': form_4,
                    'logistics': form_5,
                    'inputs': inputs
                },
                success: function(response) {
                    if (response.status) {
                        // alert('Subject Allotments Updated');
                        Swal.fire('', response.data, 'success');
                        window.location.href = "{{ route('admin.subject-allotment.index') }}";
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                            "error");
                    }
                }
            });

        }
    </script>
@endsection
