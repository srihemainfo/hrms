@extends('layouts.non_techStaffHome')
@section('content')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />


    <style>
        .row {
            display: flex;
            justify-content: space-around;
            width: 100%;
            /* max-width: 1200px; */
        }

        .card {
            margin-top: 30px;
            width: 300px;
            height: 100px;
            border-radius: 20px;
            position: relative;
            /* overflow: hidden; */
            box-shadow: 0px 8px 24px rgba(17, 17, 26, 0.1);
            transition: transform 0.3s ease;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .show-card {
            position: absolute;
            top: -20px;
            left: 20px;
            width: 65px;
            height: 60px;
            color: white;
            background: linear-gradient(to bottom right, #00c6ff, #0072ff);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 8px 24px rgba(17, 17, 26, 0.1);
        }

        .show-card i {
            font-size: 36px;
            color: #fff;
        }

        .inner {
            display: flex;
            align-items: center;
        }

        .inner i {
            font-size: 36px;
            color: #ffffff;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 16px 48px rgba(17, 17, 26, 0.15);
        }

        .foot{
            /* border-radius: 20px; */
            /* margin-top: 20px; */
        }
        .card-body{
            padding-top: 30px;
            text-align: right;
            position: absolute;
            right: 0;
        }
        span{
            font-size: 20px;
            /* background: linear-gradient(to bottom right, #00c6ff, #0072ff);
            box-shadow: 0px 16px 48px rgba(17, 17, 26, 0.15);
            border-radius: 10px;
            padding: 5px; */
        }
    </style>
    </style>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="show-card" style="background: #081f37; ">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="card-body">
                    <h5>Rooms</h5>
                    <div class="foot text-right">
                        <span>10</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="show-card" style="background: #5fc9f3">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="card-body">
                    <h5>Student Count</h5>
                    <div class="foot text-right">
                        <span>10</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="show-card" style="background: #2e79ba">
                    <i class="fas fa-check"></i>
                </div>
                <div class="card-body">
                    <h5>Today Present</h5>
                    <div class="foot text-right">
                        <span>65</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="show-card" style="background: #1e549f">
                    <i class="fas fa-times"></i>
                </div>
                <div class="card-body">
                    <h5>Today Absent</h5>
                    <div class="foot text-right">
                        <span>5</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

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
