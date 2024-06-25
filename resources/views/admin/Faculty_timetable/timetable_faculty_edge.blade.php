@php
    $type_id = auth()->user()->roles[0]->type_id;
    if ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')

    <div class="row gutters">
        <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
            <div class="card">

                <div class="row">
                    <div class="col-11">
                        <div class="input-field" style="padding-left: 0.50rem;">
                            <input type="text" name="name" id="autocomplete-input"
                                style="margin:0;padding-left:0.50rem;" placeholder="Enter Staff Name   ( Staff Code )"
                                class="autocomplete" autocomplete="off"
                                @if ($name != '') value="{{ $name }}" @else value="" @endif required
                                onchange="run()">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


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
        }
    </style>
    @if (isset($timetable))
        @if ($timetable != null)
            <div class="card" id="table-card">
                <div class="card-body" style="max-width:100%;overflow-x:auto;">
                    <div class="table table-bordered text-center" style="min-width:1100px;font-size:0.75rem;">
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
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'MONDAY' && $data->period == 1)
                                                <div class="staff_label">
                                                    <form class="period_form">
                                                        <input type="hidden" name="day"
                                                            value="{{ strtolower($data->day) }}">
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                                        <input type="hidden" name="period" value="{{ $data->period }}">
                                                        <input type="hidden" name="subject_id"
                                                            value="{{ $data->subject }}">
                                                        <input type="hidden" name="user_name_id"
                                                            value="{{ $data->staff }}">
                                                        <input type="hidden" name="class_name" value="{{ $data->class }}">
                                                        <input type="hidden" name="class_name" value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'TUESDAY' && $data->period == 1)
                                                <div class="staff_label">
                                                    <form class="period_form">
                                                        <input type="hidden" name="day"
                                                            value="{{ strtolower($data->day) }}">
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                                        <input type="hidden" name="period" value="{{ $data->period }}">
                                                        <input type="hidden" name="subject_id"
                                                            value="{{ $data->subject }}">
                                                        <input type="hidden" name="user_name_id"
                                                            value="{{ $data->staff }}">
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>
                                                    
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'WEDNESDAY' && $data->period == 1)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'THURSDAY' && $data->period == 1)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'FRIDAY' && $data->period == 1)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'SATURDAY' && $data->period == 1)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'MONDAY' && $data->period == 2)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'TUESDAY' && $data->period == 2)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'WEDNESDAY' && $data->period == 2)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'THURSDAY' && $data->period == 2)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'FRIDAY' && $data->period == 2)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'SATURDAY' && $data->period == 2)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'MONDAY' && $data->period == 3)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'TUESDAY' && $data->period == 3)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'WEDNESDAY' && $data->period == 3)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'THURSDAY' && $data->period == 3)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'FRIDAY' && $data->period == 3)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'SATURDAY' && $data->period == 3)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'MONDAY' && $data->period == 4)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'TUESDAY' && $data->period == 4)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'WEDNESDAY' && $data->period == 4)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'THURSDAY' && $data->period == 4)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'FRIDAY' && $data->period == 4)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'SATURDAY' && $data->period == 4)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'MONDAY' && $data->period == 5)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'TUESDAY' && $data->period == 5)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'WEDNESDAY' && $data->period == 5)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'THURSDAY' && $data->period == 5)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'FRIDAY' && $data->period == 5)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'SATURDAY' && $data->period == 5)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'MONDAY' && $data->period == 6)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'TUESDAY' && $data->period == 6)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'WEDNESDAY' && $data->period == 6)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'THURSDAY' && $data->period == 6)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'FRIDAY' && $data->period == 6)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'SATURDAY' && $data->period == 6)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'MONDAY' && $data->period == 7)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'TUESDAY' && $data->period == 7)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'WEDNESDAY' && $data->period == 7)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'THURSDAY' && $data->period == 7)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'FRIDAY' && $data->period == 7)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div style="padding-top:1rem;width:19%;" class="table-bordered">
                                <div class="form-group">
                                    @if (count($timetable) > 0)
                                        @foreach ($timetable as $data)
                                            @if ($data->day == 'SATURDAY' && $data->period == 7)
                                                <div class="staff_label">
                                                    <form class="period_form">
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
                                                        <input type="hidden" name="class_name"
                                                            value="{{ $data->class }}">
                                                        @if ($data->subjects != null)
                                                            <div><b>Subject</b></div>
                                                        @else
                                                            <div><b>Library</b></div>
                                                        @endif
                                                        <b class="text-primary">
                                                            @if ($data->subjects != null)
                                                                {{ $data->subjects->name }}
                                                                ({{ $data->subjects->subject_code }})
                                                            @endif
                                                        </b>
                                                        <div><b>Class</b></div>
                                                        <div><b class="text-primary">{{ $data->class_name }}</b></div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="p-2 card text-center">No Data Available...</div>
        @endif
    @endif

    {{-- <div class=" modal  fade bootstrap-modal" id="studentModal" role="dialog" id="" >
        <div class="modal-dialog  modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Student List</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header bg-primary" id="list_header">

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-primary">
                            <div class="row text-center">
                                <div class="col-1">S No</div>
                                <div class="col-6">Name</div>
                                <div class="col-5">Register No</div>

                            </div>
                        </div>
                        <div class="card-body" id="stu_list">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}
    <div id="studentModal" class="modal ">
        <div class="modal-content" style="max-height: 70vh; overflow-y: auto; max-width: 800px; margin:auto">
            <div class="row" style="width:96%;margin:auto;">
                <div class="col-11 p-0">
                    <h5 class="blue-text text-darken-2 p-2">Students List</h5>
                </div>
                <div class="col-1 p-0 text-center">
                    <div style="padding:10px 0px 0px 0px;">
                        <i class="fas fa-times modal-close" style="cursor:pointer"></i>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header blue">
                    <div id="list_header" style="color:white;">
                        <!-- Content for list_header -->
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header blue">
                    <div class="row text-center" style="color:white;">
                        <div class="col-1">S No</div>
                        <div class="col-6">Name</div>
                        <div class="col-5">Register No</div>
                    </div>
                </div>
                <div class="card-body" id="stu_list">
                    <!-- Content for stu_list -->
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script>
        const staff = [];

        let loader = document.getElementById("loader");

        let given_data = document.getElementById("given_data");

        let input = document.getElementById("autocomplete-input");




        window.onload = function() {
            $.ajax({
                url: '{{ route('admin.faculty_edge.geter') }}',
                type: 'POST',
                data: {
                    'data': 'geter'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // console.log(data);
                    let details = data.staff;
                    let staff = {};
                    // console.log(details)
                    for (let i = 0; i < details.length; i++) {
                        staff[details[i]] = null;
                    }
                    // console.log(staff)
                    $('input.autocomplete').autocomplete({
                        data: staff,
                    });

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                }
            });

        }

        function run() {
            let staff_name = input.value;
            // console.log(a)
            if (staff_name != '' && staff_name.length > 2) {

                // window.location.href = "{{ url('admin/teaching-staff-edge') }}/" + a;
                window.location.href = "{{ url('admin/faculty_time_table') }}/" + staff_name;
                // faculty_time_table/{a}
            }
        }
    </script>
    <script>
        function get_back(element) {
            console.log(element);
            let get_form = $(element).prev();
            let get_form_data = get_form.serializeArray();
            // if (get_form_data.length > 4) {
            //     get_form_data.splice(1, 1);
            // }
            let class_name = get_form_data[5].value;
            if (class_name != '') {
                $.ajax({
                    url: '{{ route('admin.class-time-table.get_alloted_students') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'class': class_name,
                        'form_data': get_form_data
                    },
                    success: function(response) {
                        // console.log(response.students);
                        if (response.students) {
                            let students = response.students;
                            let got_students = response.got_students;
                            let got_stu_len = got_students.length;
                            let className = response.class;
                            let student_len = students.length;
                            let header =
                                `<div class="row text-center"><div class="col-3">Class  :  ${className}</div><div class="col-7 p-0">Subject  :  ${response.subject}</div><div class="col-2 p-0">Period  :  ${get_form_data[2].value}</div></div>`;

                            let list = `<form id="main_form">
                            <input type="hidden" name="class_name" value="${class_name}">
                            <input type="hidden" name="day" value="${get_form_data[0].value}">
                            <input type="hidden" name="period" value="${get_form_data[2].value}">
                            <input type="hidden" name="subject" value="${get_form_data[3].value}">
                            <input type="hidden" name="staff" value="${get_form_data[4].value}">
                            </form>`;

                            if (student_len > 0) {
                                for (let i = 0; i < student_len; i++) {
                                    for (let j = 0; j < got_stu_len; j++) {
                                        let balance = student_len - j;
                                        if (got_students[j].student == students[i].user_name_id) {

                                            list += `<form class="stu_form">
                                             <div class="row text-center">
                                                 <div class="col-1">${j + 1}</div>
                                                 <div class="col-6">${students[i].name}</div>
                                                 <div class="col-5">${students[i].register_no}</div>
                                             </div>
                                            </form>`;

                                            if (balance > 1) {
                                                list += '<hr style="margin:0;">';
                                            }
                                        }
                                    }
                                }
                            }



                            $("#list_header").html(header);
                            console.log($("#list_header").html());
                            $("#stu_list").html(list);
                            $(".modal").modal();
                            // document.addEventListener('DOMContentLoaded', function() {
                            //     var modalElems = document.querySelectorAll('.modal');
                            //     var modalInstances = M.Modal.init(modalElems);
                            // });
                            var modal = document.querySelector('.modal');
                            var instance = M.Modal.init(modal);
                            instance.open();
                        }
                    }
                })
            } else {
                alert("Couldn't Get the Class");
            }
        }
    </script>
@endsection
