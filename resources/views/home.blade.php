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
            } else {
                $roleTitle = 0;
            }
        }
        // echo $roleTitle ;
    @endphp



    @if ($roleTitle == 15)
        <!--<div class="row gutters">-->
        <!--    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12">-->
        <!--        <div class="card">-->
        <!--            <div class="card-header">-->
        <!--                Dashboard-->
        <!--            </div>-->

        <!--            <div class="card-body">-->
        <!--                @if (session('status'))
    -->
        <!--                    <div class="alert alert-success" role="alert">-->
        <!--                        {{ session('status') }}-->
        <!--                    </div>-->
        <!--
    @endif-->
        <!--                <div class="row">-->

        <!--                    <div class="col-lg-3 col-6">-->
        <!--                        <div class="small-box bg-success">-->
        <!--                            <div class="inner" style="height: 114px;">-->
        <!--                                <h3 class="counter-value">0<sup style="font-size: 20px"></sup></h3>-->
        <!--                                <p>Faculty Attendance</p>-->
        <!--                            </div>-->
        <!--                            <div class="icon">-->
        <!--                                <i class="ion ion-stats-bars"></i>-->
        <!--                            </div>-->
        <!--                            <a href="#" class="small-box-footer">More info <i-->
        <!--                                    class="fas fa-arrow-circle-right"></i></a>-->
        <!--                        </div>-->
        <!--                    </div>-->

        <!--                    <div class="col-lg-3 col-6">-->

        <!--                        <div class="small-box bg-warning">-->
        <!--                            <div class="inner " style="height: 114px;">-->
        <!--                                <h3 class="counter-value">{{ $staff_leaves }}</h3>-->
        <!--                                <p>Faculty Leave Applications</p>-->
        <!--                            </div>-->
        <!--                            <div class="icon">-->
        <!--                                <i class="ion ion-person-add"></i>-->
        <!--                            </div>-->
        <!--                            <a href="#" class="small-box-footer">More info <i-->
        <!--                                    class="fas fa-arrow-circle-right"></i></a>-->
        <!--                        </div>-->
        <!--                    </div>-->

        <!--                    <div class="col-lg-3 col-6">-->

        <!--                        <div class="small-box bg-info">-->
        <!--                            <div class="inner" style="height: 114px;">-->
        <!--                                <h3 class="counter-value">{{ $staff_od }}</h3>-->
        <!--                                <p>Faculty Pending OD Approval</p>-->
        <!--                            </div>-->
        <!--                            <div class="icon">-->
        <!--                                <i class="ion ion-bag"></i>-->
        <!--                            </div>-->
        <!--                            <a href="#" class="small-box-footer">More info <i-->
        <!--                                    class="fas fa-arrow-circle-right"></i></a>-->
        <!--                        </div>-->
        <!--                    </div>-->

        <!--                    <div class="col-lg-3 col-6">-->

        <!--                        <div class="small-box bg-danger">-->
        <!--                            <div class="inner"style="height: 114px;">-->
        <!--                                <h3 class="counter-value">15</h3>-->
        <!--                                <p>Taken Leave</p>-->
        <!--                            </div>-->
        <!--                            <div class="icon">-->
        <!--                                <i class="ion ion-bag"></i>-->
        <!--                            </div>-->
        <!--                            <a href="#" class="small-box-footer">More info <i-->
        <!--                                    class="fas fa-arrow-circle-right"></i></a>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </div>-->

        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">-->
        <!--        <div class="card">-->
        <!--            <div class="card-header">-->
        <!--                Profile Image-->
        <!--            </div>-->
        <!--            <div class="card-body" style="height: 200px;">-->
        <!--                <div class="d-flex flex-column align-items-center text-center">-->
        <!--                    <img src="{{ asset('adminlogo/user-image.png') }}" alt="" class="rounded-circle"-->
        <!--                        width="150">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        </div>
        </div>
        <style>
            .color-box {
                width: 18px;
                height: 18px;
            }
        </style>
        <!--{{-- @can('calender_show_access')-->
        <!--    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12-->
                <!--<div class="card">-->
                <!--    <div style="padding: 10px" class="d-flex">-->
                <!--        <strong>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</strong>-->
                <!--        <strong style="padding-left: 5px">{{ $year }}</strong>-->
                <!--        <div class="d-flex" style="padding-left: 10px">-->
                            <!--<div class="d-flex">-->
                            <!--    <div class="color-box" style="background-color: #FFD5D6;"></div>-->
                            <!--    <div style="padding-left: 10px;">HoliDay</div>-->
                            <!--</div>-->
                            <!--<div class="d-flex" style="padding-left: 10px;">-->
                            <!--    <div class="color-box" style="background-color: #007bff7a;"></div>-->
                            <!--    <div style="padding-left: 10px;">No order Day</div>-->
                            <!--</div>-->
                            <!--<div class="d-flex" style="padding-left: 10px;">-->
                            <!--    <div class="color-box" style="background-color: #17a2b8;"></div>-->
                            <!--    <div style="padding-left: 10px;">Today</div>-->
                            <!--</div>-->
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
                        <div class="card-header"style="font-size:20px;">
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
    <div class="container"
            <div class="content">
               <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header "style="  font-size: 1.75rem;">
                                <h2>Dashboard</h2>
                                
                            </div>
                            <div class="content">
               <!--<div class="row">-->
        <!--     <div class="col-lg-12">-->
        <!--         <div class="card">-->
        <!--             <div class="card-header">-->
        <!--                 Dashboard-->
        <!--             </div>-->
        <!--             <div class="card-body">-->
        <!--<div class="row">-->
        <!--    <div class="col-lg-3 col-6">-->

        <!--        <div class="small-box bg-info">-->
        <!--            <div class="inner">-->
        <!--                <h3 class="counter-value">201</h3>-->
        <!--                <p>Teaching Staffs</p>-->
        <!--            </div>-->
        <!--            <div class="icon">-->
        <!--                <i class="ion ion-person"></i>-->
        <!--            </div>-->
        <!--            <a href="https://enggdemo.kalvierp.com/admin/teaching-staffs"-->
        <!--                class="small-box-footer">More info-->
        <!--                <i class="fas fa-arrow-circle-right"></i></a>-->
        <!--        </div>-->
        <!--    </div>-->

        <!--    <div class="col-lg-3 col-6">-->

        <!--        <div class="small-box bg-success">-->
        <!--            <div class="inner">-->
        <!--                <h3 class="counter-value">80<sup-->
        <!--                        style="font-size: 20px"></sup></h3>-->
        <!--                <p>Non Teaching Staffs</p>-->
        <!--            </div>-->
        <!--            <div class="icon">-->
        <!--                <i class="ion ion-person"></i>-->
        <!--            </div>-->
        <!--            <a href="https://enggdemo.kalvierp.com/admin/non-teaching-staffs"-->
        <!--                class="small-box-footer">More info-->
        <!--                <i class="fas fa-arrow-circle-right"></i></a>-->
        <!--        </div>-->
        <!--    </div>-->

        <!--    <div class="col-lg-3 col-6">-->

        <!--        <div class="small-box bg-warning">-->
        <!--            <div class="inner">-->
        <!--                <h3 class="counter-value">3075</h3>-->
        <!--                <p>User Registrations</p>-->
        <!--            </div>-->
        <!--            <div class="icon">-->
        <!--                <i class="ion ion-person-add"></i>-->
        <!--            </div>-->
        <!--            <a href="https://enggdemo.kalvierp.com/admin/users" class="small-box-footer">More info <i-->
        <!--                    class="fas fa-arrow-circle-right"></i></a>-->
        <!--        </div>-->
        <!--    </div>-->

        <!--    <div class="col-lg-3 col-6">-->

        <!--        <div class="small-box bg-danger">-->
        <!--            <div class="inner">-->
        <!--                <h3 class="counter-value">12</h3>-->
        <!--                <p>Blocked Users</p>-->
        <!--            </div>-->
        <!--            <div class="icon">-->
        <!--                <i class="ion ion-pie-graph"></i>-->
        <!--            </div>-->
        <!--            <a href="https://enggdemo.kalvierp.com/admin/users/block_list" class="small-box-footer">More info <i-->
        <!--                    class="fas fa-arrow-circle-right"></i></a>-->
        <!--        </div>-->
        <!--    </div>-->

        <!--fees dashborad design -->

        <div class="container">
            <div class="welcome">
                <h4 style="cursor: pointer;margin-bottom: 0;padding: 20px;">Welcome!</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="fees-report1">
                        <div class="row justify-content-around">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-4">
                                <div class="card text-left collection-box5">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fas fa-user icon-box mr-3"></i>
                                        <div>
                                            <span class="card-box-title">Users</span>
                                            <p class="card-box-text">(2973)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-4">
                                <div class="card text-left collection-box4">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher icon-box mr-3"></i>
                                        <div>
                                            <span class="card-box-title">Teachers</span>
                                            <p class="card-box-text">(150)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-4">
                                <div class="card text-left collection-box3 ">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fas fa-user-graduate icon-box mr-3"></i>
                                        <div>
                                            <span class="card-box-title">Students</span>
                                            <p class="card-box-text">(1560)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fees-report1">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="card collection-box1 p-3">
                                    <div class="collection-box1">
                                        <div>
                                            <div class="d-flex justify-content-between">

                                                <span style="font-weight:700;">August Fees Collection</span>

                                            </div>
                                            <div class="progress mt-2">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: 42%" aria-valuenow="42" aria-valuemin="0"
                                                    aria-valuemax="100">42%</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="card p-3 d-flex text-center collection-box2">
                                    <div style="font-weight:700;">Today Collection</div>
                                    <div style="font-weight:700;"class="">&#8377; 2834</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fees-report">
                        <h2 style="font-weight:700;">Overall Fees Report</h2>
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th>Total Fees</th>
                                    <td>Rs. 2041565</td>
                                </tr>
                                <tr>
                                    <th>Total Head Discount</th>
                                    <td>Rs. 17800</td>
                                </tr>
                                <tr>
                                    <th>Gross Total Fees</th>
                                    <td>Rs. 2023765</td>
                                </tr>
                                <tr>
                                    <th>Total Received Fees</th>
                                    <td>Rs. 669441</td>
                                </tr>
                                <tr>
                                    <th>Total Discount</th>
                                    <td>Rs. 6332</td>
                                </tr>
                                <tr>
                                    <th>Total Balance Fees</th>
                                    <td>Rs. 1347992</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card report-card mt-4">
                        <h5 style="font-weight:700;"class="card-header over">

                            Overall Students Report</h5>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>No. of students fees structure created:</span>
                                <span class="badge badge-primary badge-pill">100</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>No. of students fees structure not created:</span>
                                <span class="badge badge-primary badge-pill">34</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>No. of Students Paid Fees:</span>
                                <span class="badge badge-primary badge-pill">82</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>No. of Students Paid Nothing:</span>
                                <span class="badge badge-primary badge-pill">18</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Total Registered Students:</span>
                                <span class="badge badge-primary badge-pill">134</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>No. of Students Paid Nothing:</span>
                                <span class="badge badge-primary badge-pill">18</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mt-1 ">
                <div class="col-md-6">
                    <div class="card-attendance">
                        <div class="attendance-header ">
                            <h5>Daily Attendance</h5>
                            <div>
                                <select class="form-control d-inline-block" style="width: auto;">
                                    <option>23</option>
                                    <option>24</option>
                                    <option>25</option>
                                </select>
                                <select class="form-control d-inline-block ml-2" style="width: auto;">
                                    <option>Aug</option>
                                    <option>Sep</option>
                                    <option>Oct</option>
                                </select>
                            </div>
                        </div>
                        <div class="attendance-stats">
                            <span>Total Present: 129</span>
                            <span>Total Absent: 50</span>
                        </div>

                        <canvas id="attendanceChart12"
                            style="display: block; box-sizing: border-box; height: 203px; width: 489px;" width="489"
                            height="203"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-attendance">
                        <div class="attendance-header ">
                            <h5>Monthly Attendance</h5>
                            <!--<label for="unique-attendance-month attendData">Month:</label>-->
                            <select id="unique-attendance-month datattend" onchange="updateAttendanceChart()">
                                <option value="aug">Aug</option>

                            </select>
                            <!--<label for="unique-attendance-year attendData">Year:</label>-->
                            <select id="unique-attendance-year datattend" onchange="updateAttendanceChart()">
                                <option value="2023-2024">2023-2024</option>

                            </select>
                        </div>
                        <canvas id="unique-attendance-chart"
                            style="display: block; box-sizing: border-box; height: 240px; width: 481px;" width="481"
                            height="240"></canvas>
                    </div>
                </div>

            </div>


            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="custom-box">
                        <div class="custom-box-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Monthly Fees</h5>

                            </div>
                            <div>
                                <select class="form-select">
                                    <option value="Aug">All months</option>
                                </select>
                                <select class="form-control">
                                    <option value="2023-2024">2024 - 2025</option>
                                </select>
                            </div>
                        </div>
                        <div class="custom-box-body">
                            <div class="chart-cont">
                                <canvas id="waveremove"
                                    style="display: block; box-sizing: border-box; height: 245px; width: 490px;"
                                    width="490" height="245"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="custom-box">
                        <div class="custom-box-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Monthly Expense</h5>

                            </div>
                            <div>
                                <select class="form-select">
                                    <option value="Aug">All months</option>
                                </select>
                                <select class="form-control">
                                    <option value="2023-2024">2024 - 2025</option>
                                </select>
                            </div>
                        </div>
                        <div class="custom-box-body">
                            <div class="chart-num">
                                <canvas id="yChart" width="490" height="245"
                                    style="display: block; box-sizing: border-box; height: 245px; width: 490px;"></canvas>
                            </div>
                            <!--<p>Total Amount: Rs. 0</p>-->
                        </div>
                    </div>
                </div>

            </div>

            <div class="row ">
                <div class="col-md-6  mt-3">
                    <div class="announcement-box">
                        <div class="announcement-header">

                            <h5 class="mb-0">Announcement</h5>

                        </div>

                        <div class="announcement-body">
                            <p>Dear Students,<br>
                                Greetings! Semester fee payment is now open. Please ensure timely submission to avoid
                                penalties. Thank you.</p>

                            <p>Dear Students,<br>
                                Exciting news! Cultural events are coming soon! Stay tuned for registration details and get
                                ready to showcase your talents.</p>

                            <p>Dear Staff,<br><br>
                                Greetings! We would like to inform you about upcoming staff development workshops. Stay
                                tuned for further details and registration information.</p>

                            <p>Dear Students,<br>
                                Exciting news! A placement drive is scheduled for [Date]. Prepare your resumes and get ready
                                to meet potential employers.</p>

                            <p>Dear Students and Staff,<br>
                                Happy New Year! May the year ahead be filled with joy, success, and new opportunities.
                                Wishing you all a wonderful year ahead!</p>
                        </div>

                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="birthday-box">
                        <div class="birthday-header">
                            <div>
                                <h5 class="mb-0"><i class="fas fa-birthday-cake"></i> Birthday Announcement</h5>
                                <small>Happy Birthday to </small>
                            </div>


                        </div>

                        <div class="birthday-body">

                            <table>
                                <tr>
                                    <th style="border: 1px solid black;"class="bday-staff">Staff</th>
                                    <td style="border: 1px solid black;">Dr.Hema</td>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid black;">Student</th>
                                    <td style="border: 1px solid black;">Bhavya</td>

                                </tr>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div>

        </div>
        </div>
        </div>
        </div>
        </div>
        </div>

        </div>
        </div>
        <!--                              <div class="inner">-->
        <!--                                          <h3 class="counter-value">{{ $nonTeachingStaffs }}<sup-->
        <!--                                                  style="font-size: 20px"></sup></h3>-->
        <!--                                          <p>Non Teaching Staffs</p>-->
        <!--                                      </div>-->
        <!--                                      <div class="icon">-->
        <!--                                          <i class="ion ion-person"></i>-->
        <!--                                      </div>-->
        <!--                                      <a href="{{ route('admin.non-teaching-staffs.index') }}"-->
        <!--                                          class="small-box-footer">More info-->
        <!--                                          <i class="fas fa-arrow-circle-right"></i></a>-->
        <!--                                  </div>-->
        <!--                              </div>-->

        <!--                              <div class="col-lg-3 col-6">-->

        <!--                                  <div class="small-box bg-warning">-->
        <!--                                      <div class="inner">-->
        <!--                                          <h3 class="counter-value">{{ $userCounts }}</h3>-->
        <!--                                          <p>User Registrations</p>-->
        <!--                                      </div>-->
        <!--                                      <div class="icon">-->
        <!--                                          <i class="ion ion-person-add"></i>-->
        <!--                                      </div>-->
        <!--                                      <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i-->
        <!--                                              class="fas fa-arrow-circle-right"></i></a>-->
        <!--                                  </div>-->
        <!--                              </div>-->

        <!--                              <div class="col-lg-3 col-6">-->

        <!--                                  <div class="small-box bg-danger">-->
        <!--                                      <div class="inner">-->
        <!--                                          <h3 class="counter-value">12</h3>-->
        <!--                                          <p>Blocked Users</p>-->
        <!--                                      </div>-->
        <!--                                      <div class="icon">-->
        <!--                                          <i class="ion ion-pie-graph"></i>-->
        <!--                                      </div>-->
        <!--                                      <a href="{{ route('admin.users.block_list') }}" class="small-box-footer">More info <i-->
        <!--                                              class="fas fa-arrow-circle-right"></i></a>-->
        <!--                                  </div>-->
        <!--                              </div>-->
        <!--<div class="card-body">-->
        <!--                          @if (session('status'))
    -->
        <!--                              <div class="alert alert-success" role="alert">-->
        <!--                                  {{ session('status') }}-->
        <!--                              </div>-->
        <!--
    @endif-->
        <!--                          <div class="row">-->
        <!--                              <div class="col-lg-3 col-6">-->

        <!--                                  <div class="small-box bg-info">-->
        <!--                                      <div class="inner">-->
        <!--                                          <h3 class="counter-value">{{ $teachingStaffs }}</h3>-->
        <!--                                          <p>Teaching Staffs</p>-->
        <!--                                      </div>-->
        <!--                                      <div class="icon">-->
        <!--                                          <i class="ion ion-person"></i>-->
        <!--                                      </div>-->
        <!--                                      <a href="{{ route('admin.teaching-staffs.index') }}"-->
        <!--                                          class="small-box-footer">More info-->
        <!--                                          <i class="fas fa-arrow-circle-right"></i></a>-->
        <!--                                  </div>-->
        <!--                              </div>-->

        <!--                              <div class="col-lg-3 col-6">-->

        <!--                                  <div class="small-box bg-success">-->

        <!--                          </div>-->
        </div>
        </div>
        </div>
        </div>
        </div>
    @endif
    @can('calender_show_access')
        @if ($check != 'empty')
            <style>
                /*------dashboard new design-----*/
                .chart-cont {
                    width: 100%;
                    margin: 0 auto;
                }

                .card-box-title {
                    font-weight: 700;
                }

                .over {
                    background-color: #1ba5ad;
                    color: white;
                    text-align: center;
                }

                .birthday-body {
                    margin: 20px;
                }

                .card.text-left.collection-box5 {
                    background-color: #447fb3;
                    color: white;
                    /* font-size: 16px; */
                    padding: 0px 21px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: auto;
                }

                th,
                td {

                    padding: 10px;
                    text-align: left;
                }

                th {
                    background-color: #f2f2f2;
                }

                .bday-staff {
                    font-weight: bold;
                }

                /*monthly attendace chart*/
                .attendData {
                    margin-right: 10px;
                }

                .datattend {
                    margin: 7px 320px 14px 13px;
                }

                .fees-report1 {
                    background-color: white;
                    border-radius: 8px;
                    margin-top: 20px;

                    overflow: hidden;
                }

                .fees-report {
                    background-color: white;
                    border-radius: 8px;
                    /*border: 1px solid grey;*/
                    margin-top: 20px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }

                .fees-report h2 {
                    background-color: #153566;
                    color: white;
                    margin: 0;
                    padding: 16px;
                    font-size: 18px;
                    border-bottom: 1px solid #ddd;
                    text-align: center;
                }

                .fees-report th,
                .fees-report td {
                    padding: 12px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }

                .fees-report th {
                    background-color: #ccc7c7;
                    font-weight: normal;
                }

                .fees-report td {
                    color: #555;
                }

                .fees-report td:last-child {
                    text-align: start;
                    color: #333;
                }

                /*------fees card-----*/
                .icon-box {
                    font-size: 1.5rem;
                    color: #fff;
                }

                .card-body {
                    flex: 1 1 auto;
                    padding: 0.50rem;
                }

                .card {
                    border: none;
                    margin-bottom: 1.5rem;
                }

                .card.text-left.collection-box4 {
                    background-color: #2c8740;
                    color: white;
                    padding: 0px 10px;
                }

                .card.text-left.collection-box3 {
                    background-color: #dd940e;
                    color: white;
                    padding: 0px 12px;
                }

                .card.collection-box1.p-3 {
                    background-color: #c3586a;
                    color: white;
                }

                .card.p-3.d-flex.text-center.collection-box2 {
                    background-color: #c35944;
                    color: white;
                }

                /*-----attendance chart-----*/
                .card-attendance {
                    border-radius: 10px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    /* margin: 9px; */
                    margin-top: 0px;
                    height: 317px;
                    padding: 20px;
                }

                #attendanceChart12 {
                    display: block;
                    box-sizing: border-box;
                    height: 204px;
                    width: 468px;

                }

                .attendance-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .attendance-stats {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                }

                /*monthly attendance chart*/

                .date-card-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: none;
                    margin-top: 46px;
                }

                .date-card-header h5 {
                    margin: 0;
                }

                .date-card-body {
                    text-align: center;
                }

                .date-card-body p {
                    margin: 0;
                    font-weight: bold;
                }

                .date-card-body canvas {
                    margin-top: 20px;
                }

                @media (max-width: 576px) {
                    .date-card-header {
                        flex-direction: column;
                    }

                    .date-card-header select {
                        margin-top: 10px;

                    }
                }


                /*monthly fees*/
                .custom-box {
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    background-color: #e7eaed;
                    margin-bottom: 20px;
                }

                .custom-box-header {
                    background-color: #f8f9fa;
                    border-bottom: 1px solid #e9ecef;
                    border-radius: 10px 10px 0 0;
                    padding: 10px 15px;
                }

                .custom-box-body {
                    padding: 15px;
                    height: 266px;
                    background: white;
                }

                .form-select,
                .form-control {
                    width: auto;
                    display: inline-block;
                    margin-left: 5px;
                }

                /*annoncement box*/

                .announcement-header {
                    background-color: #007bff;
                    border-bottom: 1px solid #e9ecef;
                    border-radius: 10px 10px 0 0;
                    padding: 21px 15px;
                    overflow: hidden;
                    display: flex;
                    color: white;
                }

                .announcement-box {
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    background-color: #fff;
                    margin-bottom: 20px;
                    padding: 15px;
                    border: 1px solid #dee2e6;
                    height: 300px;
                    overflow-y: auto;
                }


                .announcement-body p {
                    margin-bottom: 20px;
                }

                /*birthday box*/
                .birthday-box {
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    background-color: #fff;
                    /*margin-bottom: 20px;*/
                    padding: 15px;
                    height: 300px;
                }

                .birthday-header {
                    background-color: #EC407A;
                    border-bottom: 1px solid #e9ecef;
                    border-radius: 10px 10px 0 0;
                    padding: 10px 15px;
                    display: flex;
                    color: white;
                    justify-content: space-between;
                    align-items: center;
                }

                .bday-textbox {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 10px;
                }

                .form-control {
                    border: 1px solid #cfd1d8;
                    -webkit-border-radius: 2px;
                    -moz-border-radius: 2px;
                    border-radius: 2px;
                    font-size: .825rem;
                    background: #ffffff;
                    color: #000000;
                }

                .card {
                    background: #ffffff;
                    -webkit-border-radius: 5px;
                    -moz-border-radius: 5px;
                    border-radius: 5px;
                    border: 0;
                    margin-bottom: 1rem;
                }

                .birthday-body {
                    margin: 20px;
                    margin-top: 50px;
                }

                .bday-box {

                    padding: 8px 42px 14px 35px;
                }

                margin: 33px 20px 20px 47px;
                }

                h4.bday-staff {
                    margin: 0;
                    color: #333;
                    border-bottom: 2px solid #ddd;
                    padding-bottom: 5px;
                    margin-bottom: 10px;
                    font-size: 18px;
                }

                .bday-name {
                    margin: 32px 10px 10px;
                    font-size: 16px;
                    color: #555;
                }

                .birthday-header h5 {
                    margin: 0;
                    display: flex;
                    align-items: center;
                }

                .birthday-header h5 i {
                    margin-right: 10px;
                }

                .card-box-text {
                    font-weight: 700;
                }

                @media (min-width: 1024px) {
                    .fees-report .card {

                        border: 1px solid #ccc;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }

                    /*.collection-box {*/
                    /*    width: 133px;*/
                    /*    height: 92px;*/
                    /*}*/

                    /*chart wave monthly fees*/
                    .chart-container1 {
                        width: 80%;
                        margin: 0 auto;
                    }

                    #waveChart2 {
                        background-color: #fff;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
            </style>
            <div class="content">
                <!--<div class="row">-->
                <!--<div class="col-12">-->
                <!--<div class="card">-->
                <!--    <div style="padding: 10px" class="d-flex flex-wrap justify-content-between align-items-center">-->
                <strong class="mb-2">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</strong>
                <strong class="mb-2">{{ $year }}</strong>
                <!--<div class="d-flex flex-wrap">-->
                <!--    <div class="d-flex align-items-center mr-3">-->
                <!--        <div class="color-box" style="background-color: #FFD5D6;"></div>-->
                <!--        <div class="ml-2">Holiday</div>-->
                <!--    </div>-->
                <!--    <div class="d-flex align-items-center mr-3">-->
                <!--        <div class="color-box" style="background-color: #007bff7a;"></div>-->
                <!--        <div class="ml-2">No order Day</div>-->
                <!--    </div>-->
                <!--    <div class="d-flex align-items-center">-->
                <!--        <div class="color-box" style="background-color: #17a2b8;"></div>-->
                <!--        <div class="ml-2">Today</div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>

            <!--<div class="table-responsive" style="padding: .5rem;">-->
            <!--    <table class="table table-bordered" style="margin-bottom: 0;">-->
            <!--        <thead>-->
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
                </tr>
                <tr>
        @endif

        <!--<td style="-->
                                    <!--    text-align: center;-->
                                    <!--    {{ $isCurrentDate ? 'background-color: #17a2b8;' : '' }}-->
                                    <!--    {{ $eventDayOrder == 0 && !$isCurrentDate ? 'background-color: ;' : '' }}-->
                                    <!--    {{ $eventDayOrder == 1 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}-->
                                    <!--    {{ $eventDayOrder == 2 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}-->
                                    <!--    {{ $eventDayOrder == 3 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}-->
                                    <!--    {{ $eventDayOrder == 4 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}-->
                                    <!--    {{ $eventDayOrder == 5 && !$isCurrentDate ? 'background-color: #007bff7a;' : '' }}">-->
        <!--    @if ($eventDayOrder == 0)
        -->
        <!--        <span style="color: rgb(5, 5, 5)">{{ $day }}</span>-->
        <!--
    @elseif ($eventDayOrder == 1 || $eventDayOrder == 2 || $eventDayOrder == 3)
        -->
        <!--        <span>{{ $day }}</span>-->
    <!--    @else-->
        <!--        {{ $day }}-->
        <!--
        @endif-->
        <!--</td>-->
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
    <!--daily attendance chart-->
    <script>
        var ctx = document.getElementById('attendanceChart12').getContext('2d');
        var attendanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['VLSI', 'CIVIL	 ', 'AI & ML', 'CSBS ', 'CCE', 'S & H', 'AI & DS', 'MECH ', 'ECE', ],
                datasets: [{
                    label: 'Present',
                    data: [12, 19, 3, 5, 2, 3, 10, 8, 5, ],
                    backgroundColor: '#4CAF50     '
                }, {
                    label: 'Absent',
                    data: [7, 8, 10, 4, 7, 2, 6, 7, 4, ],
                    backgroundColor: '   #303F9F      '
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20
                    }
                }
            }
        });
    </script>
    <!--monthy chart-->
    <script>
        const labels = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Monthly Fees',
                data: [300, 450, 400, 500, 450, 500, 550, 600, 700, 750, 800, 850],
                fill: true,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4
            }]
        };


        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };


        const waveChart = new Chart(
            document.getElementById('waveremove'),
            config
        );
    </script>
    <script>
        var ctx = document.getElementById('yChart').getContext('2d');
        var chart = new Chart(ctx, {

            type: 'line',


            data: {
                labels: ["Jun ", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"],

                datasets: [{
                    label: "Monthly Expense",
                    backgroundColor: 'lightblue',
                    borderColor: 'royalblue',
                    data: [26.4, 39.8, 66.8, 66.4, 40.6, 55.2, 77.4, 69.8, 57.8, 76, 110.8, 142.6],
                }]
            },


            options: {
                layout: {
                    padding: 10,
                },
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Precipitation in Toronto'
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Precipitation in mm'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Month of the Year'
                        }
                    }]
                }
            }
        });
    </script>
    <!--monthly expence-->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const ctx = document.getElementById('unique-attendance-chart').getContext('2d');

            const initialData = {
                aug: {
                    present: [200, 220, 190, 200, 180, 190, 200, 180, 10, ],
                    absent: [100, 120, 100, 100, 150, 90, 120, 131, 80, ]
                }
                // Add more data for other months
            };

            let currentData = initialData.aug;

            const attendanceData = {
                labels: Array.from({
                    length: 9
                }, (_, i) => i + 1),
                datasets: [{
                        label: 'Present',
                        data: currentData.present,
                        backgroundColor: ' #29B6F6  '
                    },
                    {
                        label: 'Absent',
                        data: currentData.absent,
                        backgroundColor: '#FFB74D '
                    }
                ]
            };

            const attendanceChart = new Chart(ctx, {
                type: 'bar',
                data: attendanceData,
                options: {
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true,
                            max: 250
                        }
                    }
                }
            });

            window.updateAttendanceChart = function() {
                const month = document.getElementById('unique-attendance-month').value;
                const year = document.getElementById('unique-attendance-year').value;

                // Logic to fetch new data based on the selected month and year
                // For example, updating `currentData` based on `month` and `year`
                if (initialData[month]) {
                    currentData = initialData[month];
                    attendanceChart.data.datasets[0].data = currentData.present;
                    attendanceChart.data.datasets[1].data = currentData.absent;
                    attendanceChart.update();
                }
            };
        });
    </script>
@endsection
