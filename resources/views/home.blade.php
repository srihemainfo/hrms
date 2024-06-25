@extends('layouts.admin')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    @php
        $userId = auth()->user()->id;
        $user = \App\Models\User::find($userId);
        if ($user) {
            $assignedRole = $user->roles->first();

            if ($assignedRole) {
                $roleTitle = $assignedRole->id;
            }else{
                $roleTitle = 0;
            }
        }
        // echo $roleTitle ;
    @endphp



    @if ($roleTitle == 15)
        <div class="row gutters">
            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12">
                <div class="card">
                    <div class="card-header">
                        Dashboard
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner" style="height: 114px;">
                                        <h3 class="counter-value">0<sup style="font-size: 20px"></sup></h3>
                                        <p>Faculty Attendance</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-warning">
                                    <div class="inner " style="height: 114px;">
                                        <h3 class="counter-value">{{ $staff_leaves }}</h3>
                                        <p>Faculty Leave Applications</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-info">
                                    <div class="inner" style="height: 114px;">
                                        <h3 class="counter-value">{{ $staff_od }}</h3>
                                        <p>Faculty Pending OD Approval</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-danger">
                                    <div class="inner"style="height: 114px;">
                                        <h3 class="counter-value">15</h3>
                                        <p>Taken Leave</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                <div class="card">
                    <div class="card-header">
                        Profile Image
                    </div>
                    <div class="card-body" style="height: 200px;">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="{{ asset('adminlogo/user-image.png') }}" alt="" class="rounded-circle"
                                width="150">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .color-box {
                width: 18px;
                height: 18px;
            }
        </style>
        {{-- @can('calender_show_access')
            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12">
                <div class="card">
                    <div style="padding: 10px" class="d-flex">
                        <strong>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</strong>
                        <strong style="padding-left: 5px">{{ $year }}</strong>
                        <div class="d-flex" style="padding-left: 10px">
                            <div class="d-flex">
                                <div class="color-box" style="background-color: #FFD5D6;"></div>
                                <div style="padding-left: 10px;">HoliDay</div>
                            </div>
                            <div class="d-flex" style="padding-left: 10px;">
                                <div class="color-box" style="background-color: #007bff7a;"></div>
                                <div style="padding-left: 10px;">No order Day</div>
                            </div>
                            <div class="d-flex" style="padding-left: 10px;">
                                <div class="color-box" style="background-color: #17a2b8;"></div>
                                <div style="padding-left: 10px;">Today</div>
                            </div>
                        </div>

                    </div>

                    <table style="height: 400px" class="table-bordered">
                        <thead>
                            <tr>
                                @foreach ($weekdays as $weekday)
                                    <th style="text-align: center; height: 30px;">{{ $weekday }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                                    <td></td>
                                @endfor

                                @for ($day = 1; $day <= $numDays; $day++)
                                    @php
                                        $currentDate = DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day);
                                        $isCurrentDate = $currentDate->format('Y-m-d') === date('Y-m-d');
                                        $eventDayOrder = null;
                                    @endphp

                                    @foreach ($events as $event)
                                        @php
                                            $eventDate = new DateTime($event->date);

                                            if ($currentDate->format('Y-m-d') === $eventDate->format('Y-m-d')) {
                                                $eventDayOrder = $event->dayorder;
                                                break;
                                            }
                                        @endphp
                                    @endforeach

                                    @if (($day + $firstDayOfWeek - 1) % 7 === 0)
                            </tr>
                            <tr>
        @endif

        <td
            style="text-align: center;{{ $isCurrentDate ? 'background-color: #17a2b8;' : '' }}{{ $eventDayOrder == 0 ? 'background-color: ;' : '' }}{{ $eventDayOrder == 1 ? 'background-color: #FFD5D6;' : '' }}{{ $eventDayOrder == 2 ? 'background-color: #FFD5D6;' : '' }}{{ $eventDayOrder == 3 ? 'background-color: #FFD5D6;' : '' }}{{ $eventDayOrder == 4 ? 'background-color: #FFD5D6;' : '' }}{{ $eventDayOrder == 5 ? 'background-color: #007bff7a;' : '' }}">
            @if ($eventDayOrder == 0)
                <span style="color: rgb(5, 5, 5)">{{ $day }}</span>
            @elseif ($eventDayOrder == 1 || $eventDayOrder == 2 || $eventDayOrder == 3)
                <span style="">{{ $day }}</span>
            @else
                {{ $day }}
            @endif
        </td>
        @endfor

        @while (($day + $firstDayOfWeek - 1) % 7 !== 0)
            <td></td>
            @php $day++; @endphp
        @endwhile
        </tr>
        </tbody>
        </table>
        </div>



        </div>
    @endcan --}}
    @elseif ($roleTitle == 14)
        <div class="row gutters">
            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12">
                <div class="card">
                    <div class="card-header">
                        Dashboard
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner" style="height: 114px;">
                                        <h3 class="counter-value">00<sup style="font-size: 20px"></sup></h3>
                                        <p>Faculty Attendance</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-warning">
                                    <div class="inner" style="height: 114px;">
                                        <h3 class="counter-value">{{ $staff_leaves }}</h3>
                                        <p>Faculty Leave Applications</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-info">
                                    <div class="inner" style="height: 114px;">
                                        <h3 class="counter-value">{{ $staff_od }}</h3>
                                        <p>Faculty Pending OD Approval</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-danger">
                                    <div class="inner" style="height: 114px;">
                                        <h3 class="counter-value">00</h3>
                                        <p>Faculty Attendance</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                <div class="card">
                    <div class="card-header">
                        Profile Image
                    </div>
                    <div class="card-body" style="height: 200px;">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="{{ asset('adminlogo/user-image.png') }}" alt="" class="rounded-circle"
                                width="150">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="content">
           <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Dashboard
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-3 col-6">

                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $teachingStaffs }}</h3>
                                            <p>Teaching Staffs</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person"></i>
                                        </div>
                                        <a href="{{ route('admin.teaching-staffs.index') }}"
                                            class="small-box-footer">More info
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">

                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $nonTeachingStaffs }}<sup
                                                    style="font-size: 20px"></sup></h3>
                                            <p>Non Teaching Staffs</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person"></i>
                                        </div>
                                        <a href="{{ route('admin.non-teaching-staffs.index') }}"
                                            class="small-box-footer">More info
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">

                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $userCounts }}</h3>
                                            <p>User Registrations</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person-add"></i>
                                        </div>
                                        <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">

                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3 class="counter-value">12</h3>
                                            <p>Blocked Users</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-pie-graph"></i>
                                        </div>
                                        <a href="{{ route('admin.users.block_list') }}" class="small-box-footer">More info <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @can('calender_show_access')
    @if ($check != 'empty')
        <style>
            .color-box {
                width: 18px;
                height: 18px;
            }
        </style>
        <div class="content">
           <div class="row">
        <div class="col-12">
            <div class="card">
                <div style="padding: 10px" class="d-flex flex-wrap justify-content-between align-items-center">
                    <strong class="mb-2">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</strong>
                    <strong class="mb-2">{{ $year }}</strong>
                    <div class="d-flex flex-wrap">
                        <div class="d-flex align-items-center mr-3">
                            <div class="color-box" style="background-color: #FFD5D6;"></div>
                            <div class="ml-2">Holiday</div>
                        </div>
                        <div class="d-flex align-items-center mr-3">
                            <div class="color-box" style="background-color: #007bff7a;"></div>
                            <div class="ml-2">No order Day</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="color-box" style="background-color: #17a2b8;"></div>
                            <div class="ml-2">Today</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="padding: .5rem;">
                    <table class="table table-bordered" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                @foreach ($weekdays as $weekday)
                                    <th class="text-center table-primary">{{ $weekday }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                                    <td></td>
                                @endfor

                                @for ($day = 1; $day <= $numDays; $day++)
                                    @php
                                        $currentDate = DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day);
                                        $isCurrentDate = $currentDate->format('Y-m-d') === date('Y-m-d');
                                        $eventDayOrder = null;
                                    @endphp

                                    @foreach ($events as $event)
                                        @php
                                            $eventDate = new DateTime($event->date);

                                            if ($currentDate->format('Y-m-d') === $eventDate->format('Y-m-d')) {
                                                $eventDayOrder = $event->dayorder;
                                                break;
                                            }
                                        @endphp
                                    @endforeach

                                    @if (($day + $firstDayOfWeek - 1) % 7 === 0)
                                        </tr><tr>
                                    @endif

                                    <td style="
                                        text-align: center;
                                        {{ $isCurrentDate ? 'background-color: #17a2b8;' : '' }}
                                        {{ $eventDayOrder == 0 && !$isCurrentDate ? 'background-color: ;' : '' }}
                                        {{ $eventDayOrder == 1 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}
                                        {{ $eventDayOrder == 2 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}
                                        {{ $eventDayOrder == 3 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}
                                        {{ $eventDayOrder == 4 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}
                                        {{ $eventDayOrder == 5 && !$isCurrentDate ? 'background-color: #007bff7a;' : '' }}">
                                        @if ($eventDayOrder == 0)
                                            <span style="color: rgb(5, 5, 5)">{{ $day }}</span>
                                        @elseif ($eventDayOrder == 1 || $eventDayOrder == 2 || $eventDayOrder == 3)
                                            <span>{{ $day }}</span>
                                        @else
                                            {{ $day }}
                                        @endif
                                    </td>
                                @endfor

                                @while (($day + $firstDayOfWeek - 1) % 7 !== 0)
                                    <td></td>
                                    @php $day++; @endphp
                                @endwhile
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        </div>
    @endif
@endcan

@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    {{-- <script>
        $(document).ready(function() {
            // page is now ready, initialize the calendar...
            events = {!! json_encode($events) !!};
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                events: events,
                eventBackgroundColor: '#4fc3f7'
            })
        });
    </script> --}}
    <script src="your-js-file.js"></script>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        $(document).ready(function() {
            $('.counter-value').each(function() {
               $(this).prop('Counter', 0).animate({
                  Counter: $(this).text()
               }, {
                  duration: 3500,
                  easing: 'swing',
                  step: function(now) {
                     $(this).text(Math.ceil(now));
                  }
               });
            });
         });
    </script>
@endsection
