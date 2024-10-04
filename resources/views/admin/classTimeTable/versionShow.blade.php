@extends('layouts.admin')
@section('content')
    <style>
        .staff_label {
            width: 98%;
            margin: auto;
            margin-bottom: 3px;
            border-radius: 5px;
            box-sizing: border-box;
            box-shadow: 0px 1px 3px grey;
        }
    </style>
    <div class="" style="padding-bottom:1rem;">
        <a class="btn btn-default" href="{{ route('admin.class-time-table.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card" id="table-card" style="overflow-x:auto;">
        <div class="card-header">
            <button class="manual_bn">Class Name : {{ $class_name }}</button>
            <input type="hidden" id="class_name" value="{{ $class_id }}">
        </div>
        <div class="card-body" style="font-size:0.75rem;">
            <div class="table table-bordered text-center">
                <div style="display:flex;width:100%;">

                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b>DAY</b>
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'TUESDAY' && $data['period'] == 1)
                                        <div class="staff_label">
                                            <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'WEDNESDAY' && $data['period'] == 1)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'THURSDAY' && $data['period'] == 1)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'FRIDAY' && $data['period'] == 1)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'SATURDAY' && $data['period'] == 1)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'TUESDAY' && $data['period'] == 2)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'WEDNESDAY' && $data['period'] == 2)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'THURSDAY' && $data['period'] == 2)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'FRIDAY' && $data['period'] == 2)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'SATURDAY' && $data['period'] == 2)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'TUESDAY' && $data['period'] == 3)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'WEDNESDAY' && $data['period'] == 3)
                                        <div class="staff_label">
                                            <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'THURSDAY' && $data['period'] == 3)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'FRIDAY' && $data['period'] == 3)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'SATURDAY' && $data['period'] == 3)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'TUESDAY' && $data['period'] == 4)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'WEDNESDAY' && $data['period'] == 4)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'THURSDAY' && $data['period'] == 4)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'FRIDAY' && $data['period'] == 4)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'SATURDAY' && $data['period'] == 4)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'TUESDAY' && $data['period'] == 5)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'WEDNESDAY' && $data['period'] == 5)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'THURSDAY' && $data['period'] == 5)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'FRIDAY' && $data['period'] == 5)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'SATURDAY' && $data['period'] == 5)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'TUESDAY' && $data['period'] == 6)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'WEDNESDAY' && $data['period'] == 6)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'THURSDAY' && $data['period'] == 6)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'FRIDAY' && $data['period'] == 6)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'SATURDAY' && $data['period'] == 6)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'TUESDAY' && $data['period'] == 7)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'WEDNESDAY' && $data['period'] == 7)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'THURSDAY' && $data['period'] == 7)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'FRIDAY' && $data['period'] == 7)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
                                            </form>

                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data['day'] == 'SATURDAY' && $data['period'] == 7)
                                        <div class="staff_label">
                                           <form class="period_form">

                                                @if ($data['status'] != true)
                                                        <div><b>Subject</b></div>
                                                    @else
                                                        <div><b>Library</b></div>
                                                    @endif
                                                <b class="text-primary">
                                                        {{ $data['subject_name'] }}
                                                        ({{ $data['subject_code'] }})
                                                </b>
                                                <div><b>Staff</b></div>
                                                <b class="text-primary">
                                                        {{ $data['staff_name'] }}
                                                        ({{ $data['staff_code'] }})
                                                </b>
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
                            <b style="display:block;padding-top:10%;">8</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group">
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data->day == 'MONDAY' && $data->period == 8)
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data->day == 'TUESDAY' && $data->period == 8)
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data->day == 'WEDNESDAY' && $data->period == 8)
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data->day == 'THURSDAY' && $data->period == 8)
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data->day == 'FRIDAY' && $data->period == 8)
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
                            @if (count($class) > 0)
                                @foreach ($class as $data)
                                    @if ($data->day == 'SATURDAY' && $data->period == 8)
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
@endsection
