@extends('layouts.teachingStaffHome')
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
        }
    </style>
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
                                                    <input type="hidden" name="subject_id" value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id" value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="subject_id" value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id" value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $data->subject }}">
                                                    <input type="hidden" name="user_name_id"
                                                        value="{{ $data->staff }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="hidden" name="period" value="{{ $data->period }}">
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
                                                <div class="btn btn-success btn-xs alloted_student"
                                                    style="margin-bottom:5px;display:{{ isset($data->allocated_students) ? ($data->allocated_students != null ? (count($data->allocated_students) > 0 ? '' : 'none') : 'none') : 'none' }};"
                                                    onclick="get_back(this)">Batch
                                                    Allocated
                                                </div>
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
    <div class="modal fade" id="studentModal" role="dialog">
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
    </div>
@endsection
@section('scripts')
    <script>
        function get_back(element) {

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

                        if (response.students) {
                            let students = response.students;
                            let got_students = response.got_students;
                            let got_stu_len = got_students.length;
                            let className = response.class;
                            let student_len = students.length;
                            let header =
                                `<div class="row"><div class="col-3">Class  :  ${className}</div><div class="col-7">Subject  :  ${response.subject}</div><div class="col-2">Period  :  ${get_form_data[2].value}</div></div>`;

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
                                             <div class="row text-center p-1">
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
                            $("#stu_list").html(list);
                            $("#studentModal").modal();
                        }
                    }
                })
            } else {
                alert("Couldn't Get the Class");
            }
        }
    </script>
@endsection
