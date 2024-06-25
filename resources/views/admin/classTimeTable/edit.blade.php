@extends('layouts.admin')
@section('content')
    <style>
        span {
            color: black;
        }

        .staff_label {
            width: 98%;
            margin: auto;
            margin-bottom: 3px;
            border-radius: 5px;
            box-sizing: border-box;
            box-shadow: 0px 1px 3px grey;
            position: relative;
        }

        .inter {
            position: absolute;
            right: 4%;
            top: 6%;
            background-color: transparent;
            color: red;
            font-size: 0.8rem;
            cursor: pointer;
        }
    </style>

    <div class="" style="padding-bottom:1rem;">
        <a class="btn btn-default" href="{{ route('admin.class-time-table.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

    @if ($class != null)
        <div class="card" id="table-card" style="max-width:100%;overflow-x:auto;">
            <div class="card-header">
                @if ($class_name != null)
                    <span class="text-primary" style="font-weight:bold;"> Class : {{ $class_name }}</span>
                @endif
            </div>
            <div class="card-body" style="font-size:0.75rem;min-width:1100px;">
                <div class="table table-bordered text-center">
                    <div style="display:flex;width:100%;">

                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b>DAY</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b>MON DAY</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b>TUES DAY</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b>WEDNES DAY</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b>THURS DAY</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b>FRI DAY</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b>SATUR DAY</b>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;width:100%;">
                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b style="display:block;padding-top:10%;">1</b>
                                <input type="hidden" name="class_name" id="class_name" value="{{ $class[0]->class_name }}">
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="monday_one">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'MONDAY' && $data->period == 1)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'MONDAY' && $data->period == 1)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id" value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id" value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('monday_one')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="tuesday_one">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'TUESDAY' && $data->period == 1)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'TUESDAY' && $data->period == 1)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id" value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('tuesday_one')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="wednesday_one">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 1)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 1)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('wednesday_one')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="thursday_one">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'THURSDAY' && $data->period == 1)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'THURSDAY' && $data->period == 1)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('thursday_one')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="friday_one">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'FRIDAY' && $data->period == 1)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'FRIDAY' && $data->period == 1)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('friday_one')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="saturday_one">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'SATURDAY' && $data->period == 1)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'SATURDAY' && $data->period == 1)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('saturday_one')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;width:100%;">
                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b style="display:block;padding-top:10%;">2</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="monday_two">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'MONDAY' && $data->period == 2)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'MONDAY' && $data->period == 2)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('monday_two')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="tuesday_two">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'TUESDAY' && $data->period == 2)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'TUESDAY' && $data->period == 2)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('tuesday_two')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="wednesday_two">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 2)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 2)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('wednesday_two')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="thursday_two">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'THURSDAY' && $data->period == 2)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'THURSDAY' && $data->period == 2)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('thursday_two')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="friday_two">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'FRIDAY' && $data->period == 2)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'FRIDAY' && $data->period == 2)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('friday_two')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="saturday_two">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'SATURDAY' && $data->period == 2)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'SATURDAY' && $data->period == 2)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('saturday_two')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;width:100%;">
                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b style="display:block;padding-top:10%;">3</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="monday_three">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'MONDAY' && $data->period == 3)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'MONDAY' && $data->period == 3)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('monday_three')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="tuesday_three">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'TUESDAY' && $data->period == 3)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'TUESDAY' && $data->period == 3)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('tuesday_three')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="wednesday_three">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 3)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 3)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('wednesday_three')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="thursday_three">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'THURSDAY' && $data->period == 3)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'THURSDAY' && $data->period == 3)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('thursday_three')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="friday_three">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'FRIDAY' && $data->period == 3)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'FRIDAY' && $data->period == 3)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('friday_three')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="saturday_three">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'SATURDAY' && $data->period == 3)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'SATURDAY' && $data->period == 3)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>






                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('saturday_three')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;width:100%;">
                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b style="display:block;padding-top:10%;">4</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="monday_four">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'MONDAY' && $data->period == 4)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'MONDAY' && $data->period == 4)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('monday_four')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="tuesday_four">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'TUESDAY' && $data->period == 4)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'TUESDAY' && $data->period == 4)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('tuesday_four')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="wednesday_four">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 4)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 4)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('wednesday_four')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="thursday_four">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'THURSDAY' && $data->period == 4)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'THURSDAY' && $data->period == 4)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('thursday_four')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="friday_four">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'FRIDAY' && $data->period == 4)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'FRIDAY' && $data->period == 4)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('friday_four')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="saturday_four">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'SATURDAY' && $data->period == 5)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'SATURDAY' && $data->period == 4)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('saturday_four')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;width:100%;">
                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b style="display:block;padding-top:10%;">5</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="monday_five">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'MONDAY' && $data->period == 5)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'MONDAY' && $data->period == 5)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('monday_five')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="tuesday_five">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'TUESDAY' && $data->period == 5)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'TUESDAY' && $data->period == 5)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('tuesday_five')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="wednesday_five">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 5)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 5)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('wednesday_five')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="thursday_five">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'THURSDAY' && $data->period == 5)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'THURSDAY' && $data->period == 5)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('thursday_five')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="friday_five">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'FRIDAY' && $data->period == 5)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'FRIDAY' && $data->period == 5)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('friday_five')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="saturday_five">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'SATURDAY' && $data->period == 5)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'SATURDAY' && $data->period == 5)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('saturday_five')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;width:100%;">
                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b style="display:block;padding-top:10%;">6</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="monday_six">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'MONDAY' && $data->period == 6)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'MONDAY' && $data->period == 6)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('monday_six')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="tuesday_six">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'TUESDAY' && $data->period == 6)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'TUESDAY' && $data->period == 6)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('tuesday_six')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="wednesday_six">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 6)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 6)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('wednesday_six')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="thursday_six">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'THURSDAY' && $data->period == 6)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'THURSDAY' && $data->period == 6)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('thursday_six')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="friday_six">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'FRIDAY' && $data->period == 6)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'FRIDAY' && $data->period == 6)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('friday_six')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="saturday_six">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'SATURDAY' && $data->period == 6)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'SATURDAY' && $data->period == 6)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('saturday_six')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;width:100%;">
                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b style="display:block;padding-top:10%;">7</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="monday_seven">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'MONDAY' && $data->period == 7)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'MONDAY' && $data->period == 7)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('monday_seven')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="tuesday_seven">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'TUESDAY' && $data->period == 7)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'TUESDAY' && $data->period == 7)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('tuesday_seven')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="wednesday_seven">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 7)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 7)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('wednesday_seven')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="thursday_seven">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'THURSDAY' && $data->period == 7)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'THURSDAY' && $data->period == 7)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('thursday_seven')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="friday_seven">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'FRIDAY' && $data->period == 7)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'FRIDAY' && $data->period == 7)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('friday_seven')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="saturday_seven">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'SATURDAY' && $data->period == 7)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'SATURDAY' && $data->period == 7)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('saturday_seven')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;width:100%;">
                        <div style="padding-top:1rem;width:5%;" class="table-bordered">
                            <div class="form-group text-center">
                                <b style="display:block;padding-top:10%;">8</b>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="monday_eight">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'MONDAY' && $data->period == 8)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'MONDAY' && $data->period == 8)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('monday_eight')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="tuesday_eight">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'TUESDAY' && $data->period == 8)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'TUESDAY' && $data->period == 8)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('tuesday_eight')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="wednesday_eight">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 8)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'WEDNESDAY' && $data->period == 8)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('wednesday_eight')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="thursday_eight">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'THURSDAY' && $data->period == 8)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'THURSDAY' && $data->period == 8)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('thursday_eight')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="friday_eight">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'FRIDAY' && $data->period == 8)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'FRIDAY' && $data->period == 8)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('friday_eight')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                        <div style="padding-top:1rem;width:19%;" class="table-bordered">
                            <div class="form-group" id="saturday_eight">
                                @if (count($class) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($class as $data)
                                        @if ($data->day == 'SATURDAY' && $data->period == 8)
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @foreach ($class as $data)
                                        @php
                                            $rand = rand();
                                            $rand_id = 'A' . $rand . 'K';
                                        @endphp
                                        @if ($data->day == 'SATURDAY' && $data->period == 8)
                                            <div class="staff_label">
                                                <i class="fa fa-times inter" onclick="remove(this)"
                                                    id="{{ $data->id }}"></i>
                                                <form class="period_form" id="{{ $rand_id }}">
                                                    @if ($data->subjects != null)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                    <input type="hidden" name="day"
                                                        value="{{ strtolower($data->day) }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $data->id }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
                                                    <b class="text-primary">
                                                        @if ($data->subjects != null)
                                                            {{ $data->subjects->name }}
                                                            ({{ $data->subjects->subject_code }})
                                                        @endif

                                                    </b>
                                                    <div><b>Staff</b></div>
                                                    <b class="text-primary">
                                                        @if ($data->staffs != null)
                                                            {{ $data->staffs->name }}
                                                            ({{ $data->staffs->StaffCode }})
                                                        @elseif ($data->non_tech_staffs != null)
                                                            {{ $data->non_tech_staffs->name }}
                                                            ({{ $data->non_tech_staffs->StaffCode }})
                                                        @endif

                                                    </b>
                                                </form>





                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="btn btn-xs btn-primary" onclick="add('saturday_eight')">ADD <i
                                        style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                        class="fa fa-plus-circle">
                                    </i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gutters">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="text-right" style="padding-top:1rem;">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary"
                                onclick="submit()">Update</button>
                            <span id="submit_span" style="display:none;font-weight:bold;font-size:1rem;"
                                class="text-success">Processing...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="p-2 card text-center">No Data Available...</div>
    @endif

    <div class="modal fade" id="periodModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary">Select Subject & Staff</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row" id="selector_div">
                                <div class='col-md-12'>
                                    <select class="form-control select2" style="width:100%;" name="selector"
                                        id="selector" onchange="open_divs(this)">
                                        <option value="">Select Program</option>
                                        <option value="lecture">Lecture</option>
                                        <option value="library">Library</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row' id="lecture">
                        <div class='col-md-12'>
                            <div class="form-group">
                                <input type="hidden" value="" name="period" id="period">
                                <input type="hidden" value="" name="column" id="column">
                                <input type="hidden" value="" name="day" id="day">
                                <input type="hidden" value="" name="selected_subject" id="selected_subject">
                                <input type="hidden" value="" name="selected_staff" id="selected_staff">
                                <b for="" style="display:block;">Select Subject</b>
                                <select style="width:100%;"class="form-control select2 subject" name="subject_selecter"
                                    id="subject_selecter">
                                    <option value="">Select Subject</option>
                                    @if (count($get_subjects) > 0)
                                        @foreach ($get_subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}
                                                ({{ $subject->subject_code }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="text-center">
                                    <span id="subject_span"
                                        style="font-weight:bold;display:block;color:rgb(255, 0, 13);">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <b for="" style="display:block;">Select Staff</b>
                                <select style="width:100%;" class="form-control select2" name="staff_selecter"
                                    id="staff_selecter" onchange="check_staff(this)">
                                    <option value="">Select Staff</option>
                                    @foreach ($teaching_staffs as $staff)
                                        <option value="{{ $staff->user_name_id }}">{{ $staff->name }}
                                            ({{ $staff->StaffCode }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-center">
                                    <span id="staff_span" style="display:block;">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="library">
                        <div class='col-md-12'>
                            <div class="form-group">
                                <label for="library_staff">Select Staff</label>
                                <select class="form-control select2" name="library_staff" id="library_staff"
                                    onchange="check_staff(this)">
                                    <option value="">Select Staff</option>
                                    @foreach ($teaching_staffs as $staff)
                                        <option value="{{ $staff->user_name_id }}">{{ $staff->name }}
                                            ({{ $staff->StaffCode }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-center">
                                    <span id="library_span" style="display:block;">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="program_saver" class="btn btn-success" onclick="save()">Add</button>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('scripts')
    <script>
        window.onload = function() {
            $("#staff_selecter").select2();
            $("#subject_selecter").select2();
        }

        let remove_ids = [];
        let allot_div;

        function remove(element) {

            let id = $(element).attr('id');

            if (id != 'added') {
                remove_ids.push(id);
            }

            let parent = $(element).parent();

            $(parent).remove();

        }


        function add(element) {

            $("#column").val(element);
            $("#staff_selecter").val('');
            $("#subject_selecter").val('');
            $("#staff_selecter").select2();
            $("#subject_selecter").select2();
            let split = element.split("_");
            // console.log(split)

            $("#day").val(split[0]);

            let period;

            if (split[1] == 'one') {
                period = 1;
            } else if (split[1] == 'two') {
                period = 2;
            } else if (split[1] == 'three') {
                period = 3;
            } else if (split[1] == 'four') {
                period = 4;
            } else if (split[1] == 'five') {
                period = 5;
            } else if (split[1] == 'six') {
                period = 6;
            } else if (split[1] == 'seven') {
                period = 7;
            } else if (split[1] == 'eight') {
                period = 8;
            }

            $("#period").val(period);

            // console.log(period,split,split[0])
            $("#lecture").hide();
            $("#library").hide();
            $("#program_saver").hide();
            $("#selecter_div").show();
            $("#selector").val('');
            $("select").select2();
            $("#periodModal").modal();
        }

        function open_divs(element) {
            let div_id = element.value;
            $("#program_saver").hide();
            if (div_id == 'lecture') {
                $("#lecture").show();
                $("#library").hide();
                $("select").select2();
                $("#program_saver").show();
            }
            if (div_id == 'library') {
                $("#library").show();
                $("#lecture").hide();
                $("select").select2();
                $("#program_saver").show();
            }
        }

        function check_staff(element) {

            let selected_staff = element.value;
            let day = $("#day").val();
            let period = $("#period").val();
            let class_name = $("#class_name").val();
            let selecter = $("#selector").val();
            let get_id = $(element).attr('id');
            let span = `<span class="text-primary">Checking Availability...</span>`;
            if (get_id == 'library_staff') {
                $("#library_span").html(span)
            } else {
                $("#staff_span").html(span);
            }
            let subject = null;
            if (selecter == 'lecture') {
                subject = $("#subject_selecter").val();
            } else {
                if (selecter == 'library') {
                    subject = 'library';
                }
            }
            let data = {
                'selected_staff': selected_staff,
                'column': period,
                'day': day,
                'class_name': class_name,
                'subject': subject
            };

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.check-staff-period.check') }}',
                data: {
                    data: data
                },
                success: function(response) {

                    if (response.status == false) {
                        // alert('Choosen Staff Already Assigned');
                        span =
                            `<span style="font-weight:bold;color:rgb(255, 0, 13);">Choosen Staff Already Assigned</span>`;
                        if (get_id == 'library_staff') {
                            $("#library_span").html(span)
                        } else {
                            $("#staff_span").html(span);
                        }

                        element.innerHTML = `  <option value="">Select Staff</option>
                              @foreach ($teaching_staffs as $staff)
                                  <option value="{{ $staff->user_name_id }}">{{ $staff->name }} <span style="font-size:0.5rem;">({{ $staff->StaffCode }})</span></option>
                              @endforeach`;
                    } else {

                        if (get_id == 'library_staff') {
                            $("#library_span").html('')
                        } else {
                            $("#staff_span").html('');
                        }

                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (get_id == 'library_staff') {
                        $("#library_span").html('')
                    } else {
                        $("#staff_span").html('');
                    }
                    $("#loadig_spin").hide();
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }

            });

            // console.log(data)
        }

        function save() {

            let column = $("#column").val();
            let day_name = $("#day").val();
            let day = day_name.toUpperCase();
            let period = $("#period").val();
            let subject_selecter;
            let staff_selecter;

            let program_type = $("#selector").val();
            if (program_type == 'lecture') {
                subject_selecter = $("#subject_selecter").val();
                staff_selecter = $("#staff_selecter").val();
                $("#staff_span").html('');
                $("#subject_span").html('');

                if (subject_selecter == '') {
                    $("#subject_span").html("Please Choose the Subject");

                } else if (staff_selecter == '') {
                    $("#staff_span").html("Please Choose the Staff");

                }
            } else if (program_type == 'library') {
                subject_selecter = 'Library';
                staff_selecter = $("#library_staff").val();

                if (staff_selecter == '') {
                    $("#library_span").html("Please Choose the Library Staff");

                } else {
                    $("#library_span").html('');
                }

            }

            if (staff_selecter != '' && subject_selecter != '' && staff_selecter != null && subject_selecter !=
                null) {
                $("#staff_span").html('');
                $("#subject_span").html('');
                // console.log(staff_selecter, subject_selecter)
                $.ajax({
                    url: '{{ route('admin.class-time-table.get_staff_and_subject') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'staff': staff_selecter,
                        'subject': subject_selecter
                    },
                    success: function(response) {

                        if (response.staff != '' && response.subject != '') {
                            let staff = response.staff;
                            let subject = response.subject;

                            let staff_name = staff['name'];
                            let staff_code = staff['StaffCode'];
                            let user_name_id = staff['user_name_id'];

                            let subject_name;
                            let subject_code;
                            let subject_id;
                            let sub_label;

                            if (program_type == 'lecture') {
                                subject_name = subject['subjects']['name'];
                                subject_code = '(' + subject['subjects']['subject_code'] + ')';
                                subject_id = subject['subjects']['id'];
                                sub_label = 'Subject';
                            }

                            if (program_type == 'library') {
                                subject_name = '';
                                subject_code = '';
                                subject_id = subject;
                                sub_label = 'Library';
                            }
                            let rand = Math.floor(Math.random() * 1000000000);
                            let rand_id = 'I' + rand + 'D';

                            let div = `
                                <div class="staff_label"><i class="fa fa-times inter" onclick="remove(this)" id="added"></i>
                                    <form class='period_form' id="${rand_id}">
                                        <div><b>${sub_label}</b></div>
                                        <input type="hidden" name="day" value="${day}">
                                        <input type="hidden" name="id" value="">
                                        <input type="hidden" name="period" value="${period}">
                                        <input type="hidden" name="subject_id" value="${subject_id}">
                                        <input type="hidden" name="user_name_id" value="${user_name_id}">
                                        <b class="text-primary">${subject_name}  ${subject_code}</b>
                                        <div><b>Staff</b></div>
                                        <b class="text-primary">${staff_name} (${staff_code})</b>
                                    </form>
                                </div>`;


                            $("#" + column).append(div);

                            let parent = $("#" + column);
                            let len = $("#" + column).children().length;
                            // console.log(len)
                            let allot_element = parent.find(".allot_student");
                            let alloted_element = parent.find(".alloted_student");
                            if (len > 1) {
                                allot_element.show();
                            } else {
                                allot_element.hide();

                            }
                            alloted_element.hide();
                            $("#periodModal").modal('hide');

                        } else {
                            Swal.fire('','Technical Error','error');
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                        }
                    }
                })
            }

        }

        function submit() {
            let class_name = $("#class_name").val();
            let form_len = $('.period_form').length;
            let forms = $(".period_form");

            let form_data = [];

            for (let a = 0; a < form_len; a++) {
                let form = forms[a];
                let serialize = $(form).serializeArray();
                form_data.push(serialize);
            }
            $("#submit_span").show()
            $("#submit").hide()

            if (form_len > 0) {
                $.ajax({
                    url: '{{ route('admin.class-time-table.updater') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'class': class_name,
                        'delete': remove_ids,
                        'data': form_data
                    },
                    success: function(response) {
                        // console.log(response)
                        $("#submit_span").hide()
                        if (response.status) {
                            Swal.fire('','Class Time Table Updated For Approval','success');

                            window.location.href = "{{ route('admin.class-time-table.index') }}";
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                        }
                        $("#submit_span").hide()
                        $("#submit").show()
                    }
                })
            }

        }
    </script>
@endsection
