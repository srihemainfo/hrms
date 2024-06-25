@extends('layouts.studentHome')
@section('content')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <style>
        table {
            table-layout: fixed;
            width: 100%;
            font-size: 18px;
            font-weight: 300;
        }

        .caption {
            text-align: center;
            font-size: 1.5em;
            background: #FAFAFA;
            /* padding: 0.35em; */
        }

        table tr {
            height: 1.85em;
        }

        table td,
        table th {
            text-align: center;
            background: #FAFAFA;
        }

        table th {
            font-weight: 400;
        }

        .firstnav {
            height: 300px;
            background: rgb(177, 180, 181);
            background: linear-gradient(0deg, rgba(177, 180, 181, 0.7203256302521008) 0%, rgba(255, 255, 255, 1) 100%);
            margin: -90px;
            /* z-index: -9999; */
        }

        .style {
            background-color: rgb(190, 190, 228);
        }

        .color-box {
            width: 18px;
            height: 18px;
        }
    </style>
    <div class="firstnav">
        <div style="padding-top: 100px;padding-left:100px;">
            <h4 class="content-title mb-2">Hi, welcome back!</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 class="counter-value">0</h3>
                            <p>CGPA</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 class="counter-value">0</h3>
                            <p>Arrears In Hand</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="counter-value">0</h3>
                            <p>Average Attendance</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 class="counter-value">0</h3>
                            <p>Taken Leave</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card" style="width:100%;">
                <div class="card-header">
                    <h5 class="card-title">Announcements</h5>
                </div>
                <div class="card-body" style="overflow-y: auto;">
                    <ul>
                        <li>No Announcements</li>
                    </ul>
                    <p style="text-align: right">
                        More..
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card" style="width:100%;">
                <div class="card-header">
                    <h5 class="card-title">Placement / Events Schedule</h5>
                </div>
                <div class="card-body" style="overflow-y: auto;">
                    <ul>
                        <li>No Events</li>
                    </ul>
                    <p style="text-align: right">
                        More..
                    </p>
                </div>
            </div>
        </div>

        @can('calender_show_access')
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row pb-1">
                            <div class="col-3"><strong>{{ DateTime::createFromFormat('!m', $month)->format('F') }} &nbsp;&nbsp;
                                    {{ $year }}</strong></div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-md-2 col-12">
                                        <div class="color-box" style="background-color: #FFD5D6;"></div>
                                    </div>
                                    <div class="col-md-10 col-12 p-0">HoliDay</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-md-2 col-12">
                                        <div class="color-box" style="background-color: #007bff7a;"></div>
                                    </div>
                                    <div class="col-md-10 col-12 p-0">No order Day</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-md-2 col-12">
                                        <div class="color-box" style="background-color: #17a2b8;"></div>
                                    </div>
                                    <div class="col-md-10 col-12 p-0">&nbsp;Today</div>
                                </div>
                            </div>
                        </div>
                        <table style="height: 400px" class="table-bordered">
                            <thead>
                                <tr>
                                    @foreach ($weekdays as $weekday)
                                        <th style="text-align: center; height: 60px;" class="table-primary">
                                            {{ $weekday }}
                                        </th>
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
                                        style="text-align: center;{{ $isCurrentDate ? 'background-color: #17a2b8;' : '' }}{{ $eventDayOrder == 0 && !$isCurrentDate ? 'background-color: ;' : '' }}{{ $eventDayOrder == 1 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}{{ $eventDayOrder == 2 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}{{ $eventDayOrder == 3 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}{{ $eventDayOrder == 4 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}{{ $eventDayOrder == 5 && !$isCurrentDate ? 'background-color: #007bff7a;' : '' }}">
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
            </div>
        @endcan
    </div>
@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script>
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
