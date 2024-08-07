@extends('layouts.studentHome')
@section('content')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karla" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />


    <style>
        table {
            table-layout: fixed;
            width: 100%;
            font-size: 18px;
            font-weight: 300;
        }

        #first-box {
            background-color: #447fb3;
            color: white;

        }

        #second-box {
            color: white;
            background-color: #2c8740;


        }

        #third-box {
            color: white;
            background-color: #dd940e;
        }

        .Course-name {
            font-size: 16px;
            font-weight: 600;
            margin-left: 13px;
            /* margin-top: 13px; */
            padding-bottom: 0px;
            padding-top: 17px;
            margin-bottom: 0px;
        }
        }

        .small-box p {
            font-size: 20px;
            font-weight: 600;

            padding-left: 22px;

        }

        .small-box .icon {
            transition: all 0.3s linear;
            position: absolute;
            top: -9px;
            right: 42px;
            z-index: 0;
            font-size: 80px;
            color: rgba(0, 0, 0, 0.15);
        }

        .small-box>.inner {
            padding: 8px;
        }

        .small-box:hover .icon {
            font-size: 70px;
        }

        @media (min-width: 320px) {
            .small-box .icon {
                transition: all 0.3s linear;
                position: absolute;
                top: 8px;
                display: block;
                right: 30px;
                z-index: 0;
                font-size: 58px;
                color: rgba(0, 0, 0, 0.15);
            }

            .small-box p {
                font-size: 20px;
                font-weight: 600;
                padding-left: -27px;
                padding-right: 89px;
                margin-top: 2px;
            }
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

        /*.firstnav {*/
        /*    height: 300px;*/
        /*    background: rgb(177, 180, 181);*/
        /*    background: linear-gradient(0deg, rgba(177, 180, 181, 0.7203256302521008) 0%, rgba(255, 255, 255, 1) 100%);*/
        /*    margin: -90px;*/
        /* z-index: -9999; */
        /*}*/

        .style {
            background-color: rgb(190, 190, 228);
        }

        .color-box {
            width: 18px;
            height: 18px;
        }

        /*Timetable style*/

        .timetable-container {
            width: 100%;
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
        }


        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            margin-top: 20px;
        }

        .timetable-table td {
            border: 1px solid #ddd;
            padding: 8px;
            color: black;
        }

        .timetable-table th {
            padding: 8px;
        }

        .timetable-table th {
            background-color: #0986c8;
            ;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .timetable-table td {
            background-color: #ffffff;
        }

        .timetable-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .timetable-table tr:hover {
            background-color: #f1f1f1;
        }

        .timetable-table td p {
            margin: 5px 0;
        }

        .header-row {
            background-color: #4A90E2;
            /* Medium blue */
            color: white;
            /* White text for contrast */
        }

        .row-1 {
            background-color: #E1F5FE;
        }

        .row-2 {
            background-color: #ffffff;
        }

        .but {
            background-color: #4A90E2;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .but:hover {
            background-color: #357ABD;
        }

        .icon {
            color: white;
        }

        /*.but {*/
        /*    background-color: #4ca1af;*/
        /*    color: white;*/
        /*    border: none;*/
        /*    padding: 7px 16px;*/
        /*    text-align: center;*/
        /*    text-decoration: none;*/
        /*    display: inline-block;*/
        /*    font-size: 16px;*/
        /*    margin: 4px 2px;*/
        /*    cursor: pointer;*/
        /*    border-radius: 4px;*/
        /*}*/

        /*timetable style end*/
        /*Announcement box*/

        .hidden {
            display: none;
        }

        .events-list,
        .announcements-list {
            width: 45%;
            margin: 0 2%;
        }

        .announcement {
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
            height: 314px;
            overflow: scroll;
        }

        @media (max-width: 388px) {

            .nav-tabs .nav-link.active,
            .nav-tabs .nav-link {
                font-size: 10px !important;
            }

        }

        .nav-tabs .nav-link.active {
            color: #ffffff;
            font-size: 15px;
            background-color: #f47810;
            border-color: #dedede #dee2e6 #fff;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;

        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            font-size: 15px;
        }

        /*Calender start*/
        .calender {
            background: #26a0fc;
            width: 100%;
            height: 386px;
            box-shadow: rgba(0, 0, 0, 0.125) 0px 0px 1px, rgba(0, 0, 0, 0.2) 0px 1px 3px;
            /* box-shadow: 3px 3px 4px #ccc; */
            padding: 9px;
        }

        .calender .majorDate {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 20px 0 0 0;
        }

        .calender .majorDate .getdate {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 23px;
            color: #fff;
            cursor: default;
        }

        .calender .majorDate .getdate p {
            font-size: 21px;
            font-weight: bold;
            padding: 3px;
            cursor: default;
        }

        .calender .majorDate .fas {
            font-size: 25px;
            color: #fff;
            cursor: pointer;
        }

        .calender .dateContainer {
            padding: 0 16px;
        }

        .calender .box {
            position: relative;
            width: calc(100%/7);
            height: 33px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 3;
            color: #fff;
            padding: 16px;
        }

        .calender .dateContainer .days {
            display: flex;
        }

        .calender .dateContainer .dayCount {
            display: flex;
            flex-wrap: wrap;
        }

        .calender .dateContainer .dayCount span {
            z-index: 3;
        }

        .calender .active {
            box-shadow: 1px 1px 3px #5c5757;
            background: #fff;
            display: block;
            width: 40px;
            height: 40px;
            position: absolute;
            border-radius: 50%;
            z-index: 0;
        }

        .tab-content>.active {
            display: block;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 10px;
        }

        @media (max-width: 768px) {
            .but {
                background-color: #4ca1af;
                color: white;
                border: none;
                padding: 4px 4px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                border-radius: 4px;
            }
        }

        @media (min-width: 768px) {
            .calender .box {
                position: relative;
                width: calc(100% / 7);
                height: 33px;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 3;
                color: #fff;
                padding: 22px;
            }
        }

        @media (max-width: 768px) {
            .but {
                background-color: #4ca1af;
                color: white;
                border: none;
                padding: 4px 4px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                border-radius: 4px;
            }
        }

        /*chart*/
        #chart {
            min-height: 365px;
            box-shadow: rgba(0, 0, 0, 0.125) 0px 0px 1px, rgba(0, 0, 0, 0.2) 0px 1px 3px;
        }
    </style>
    <div class="container">
        <div class="firstnav1">
            <nav aria-label="breadcrumb">

                <h2>Dashboard</h2>
            </nav>
            <h4 class="content-title mb-2 ms-5">Hi, welcome back!</h4>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="small-box "id="first-box">
                                <div class="inner">
                                    <p class="box-content">Academic Grade</p>
                                    <!--<h3 class="counter-value">80</h3>-->
                                    <p class="value">80</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-percent"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i-->
                                <!--        class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">

                            <div class="small-box"id="second-box">
                                <div class="inner">
                                    <p class="box-content">Arrears In Hand</p>
                                    <!--<h3 class="counter-value">2</h3>-->
                                    <p class="value">02</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i-->
                                <!--        class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">

                            <div class="small-box" id="third-box">
                                <div class="inner">
                                    <p class="box-content">Academic Fee</p>
                                    <!--<h3 class="counter-value">1,000</h3>-->
                                    <p class="value">12,900</p>

                                </div>
                                <div class="icon">
                                    <i class="fa fa-inr" aria-hidden="true"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i-->
                                <!--        class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>


                    </div>

                    <!--Timetable start-->
                    <div class="row mt-0">
                        <div class="col-md-6">

                            <div class="timetable-container">
                                <table class="timetable-table"
                                    style="width: 100%; border-collapse: collapse; text-align: center;">
                                    <thead>
                                        <tr class="header-row">
                                            <th colspan="7">Time Table</th>
                                        </tr>
                                        <tr class="header-row">

                                            <th colspan="2">Monday</th>
                                            <th>
                                                <button class="but" onclick="goToPrevious()">
                                                    <i class="fas fa-arrow-left icon"></i>
                                                </button>
                                            </th>
                                            <th colspan="3">Subjects</th>
                                            <th>
                                                <button class="but" onclick="goToNext()">
                                                    <i class="fas fa-arrow-right icon"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="row-1">
                                            <td colspan="2">1<sup>st</sup>&nbsp;Hour <br>10.00 to 11.00</td>
                                            <td colspan="5">
                                                <p>Heat and Mass Transfer</p>
                                            </td>
                                        </tr>
                                        <tr class="row-2">
                                            <td colspan="2">2<sup>nd</sup>&nbsp;Hour<br>11.00 to 12.00</td>
                                            <td colspan="5">
                                                <p>CAD/CAM Laboratory</p>
                                            </td>
                                        </tr>
                                        <tr class="row-1">
                                            <td colspan="2">3<sup>rd</sup>&nbsp;Hour<br>12.00 to 01.00</td>
                                            <td colspan="5">
                                                <p>Heat and Mass Transfer</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>



                        <!--Timetable end-->
                        <!--Announcement box-->
                        <div class="col-md-6">

                            <div class="announcement container mt-4">
                                <ul class="nav nav-tabs row" role="tablist">
                                    <li class="nav-item col-6">
                                        <a class="nav-link active" data-toggle="tab" href="#tabs-1"
                                            role="tab">Announcement
                                            &nbsp;&nbsp;<i class="fa-solid fa-bullhorn"></i></a>
                                    </li>
                                    <li class="nav-item col-6">
                                        <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Events &nbsp; <i
                                                class="fa-solid fa-calendar-days"></i></a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="tabs-1" role="tabpanel">
                                        <p>Dear Students,<br>
                                            Greetings! Semester fee payment is now open. Please ensure timely submission to
                                            avoid penalties. Thank you.</p>
                                        <p>Dear Students,<br>
                                            Exciting news! Cultural events are coming soon! Stay tuned for registration
                                            details
                                            and get ready to showcase your talents.</p>
                                        <p>Dear Staff,<br>
                                            Greetings! We would like to inform you about upcoming staff development
                                            workshops.
                                            Stay tuned for further details and registration information.</p>
                                    </div>
                                    <div class="tab-pane fade" id="tabs-2" role="tabpanel">
                                        <p>Dear Students,<br>
                                            Greetings! Semester fee payment is now open. Please ensure timely submission to
                                            avoid penalties. Thank you.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"style="margin-top:20px;">
                            <div class="calender" id="calen">
                                <div class="majorDate" id="majorDate">
                                    <i class="fas fa-angle-left left"></i>
                                    <div class="getdate">
                                        <div class="month">
                                            <p id="month">MAY</p>
                                        </div>
                                        <div class="year">
                                            <p id="year">2018</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-angle-right right"></i>
                                </div>
                                <div class="dateContainer">
                                    <div class="days">
                                        <p class="day box">SUN</p>
                                        <p class="day box">MON</p>
                                        <p class="day box">TUE</p>
                                        <p class="day box">WED</p>
                                        <p class="day box">THU</p>
                                        <p class="day box">FRI</p>
                                        <p class="day box">SAT</p>
                                    </div>
                                    <div class="dayCount" id="dayCount">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div style="margin-top: 20px;" id="chart">
                                <h6 class="Course-name">Course completion status</h6>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
    <!--time table-->
    <script>
        function goToPrevious() {

            alert("Going to previous set");
        }

        function goToNext() {

            alert("Going to next set");
        }
    </script>
    <!--announcement-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-btn');
            const announcementsList = document.getElementById('announcements-list');
            const eventsList = document.getElementById('events-list');


            announcementsList.classList.remove('hidden');

            toggleButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const target = button.getAttribute('data-toggle');

                    if (target === 'events') {

                        announcementsList.classList.add('hidden');
                        eventsList.classList.remove('hidden');
                    } else if (target === 'announcements') {

                        eventsList.classList.add('hidden');
                        announcementsList.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
    <!--Calender-->
    <script>
        var calender = document.getElementById('calen'),
            month = document.getElementById('month'),
            day = document.getElementById('day'),
            year = document.getElementById('year'),
            date = new Date(),
            yearNumber = date.getFullYear(),
            monthNumber = date.getMonth(),
            activeMonth = date.getMonth(),
            activeYear = date.getFullYear(),
            dayNumber = date.getDay(),
            monthOfYear = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMPER', 'OCTOBER',
                'NOVEMBER', 'DECEMBER'
            ],
            days = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'],
            dayCount = document.getElementById('dayCount'),
            firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay(),
            dayInMonth = date.getDate(),
            i = 1,
            x = 1,
            z = 1,
            l = 1,
            k = 1,
            dayCountNumber,
            emptyBlock,
            monthStart = new Date(yearNumber, monthNumber, 1);
        year.innerHTML = yearNumber;
        month.innerHTML = monthOfYear[monthNumber];
        console.log(dayNumber);
        $('.dayCount .box').eq(6).addClass('active');
        if (monthNumber === 0 || monthNumber === 2 || monthNumber === 4 || monthNumber === 6 || monthNumber === 7 ||
            monthNumber === 9 || monthNumber === 11) {
            dayCountNumber = 31;
        } else if (monthNumber === 1) {
            if (yearNumber % 4 === 0) {
                dayCountNumber = 29;
            } else {
                dayCountNumber = 28;
            }
        } else {
            dayCountNumber = 30;
        }
        for (i; i < firstDay + 1; i++) {
            $('.dayCount').append('<p class="box" id="dayBox">' + '<p>');
        }
        for (x; x < dayCountNumber + 1; x++) {
            $('.dayCount').append('<p class="box" id="dayBox">' + '<span>' + x + '</span>' + '<p>');
        }
        emptyBlock = 42 - (dayCountNumber + firstDay);
        for (z; z < emptyBlock; z++) {
            $('.dayCount').append('<p class="box empty" id="dayBox"/>');
        }
        $('.dayCount .box').eq(firstDay + dayInMonth - 1).addClass('activeParent');
        $('.dayCount .activeParent').append('<div class="active"/>');
        $('.dayCount .activeParent').css({
            color: '#000'
        });
        $('.left').click(function() {
            if (monthNumber > 0) {
                $(this).each(function() {
                    monthNumber = monthNumber - 1;
                    month.innerHTML = monthOfYear[monthNumber];
                });
            } else {
                monthNumber = 11;
                yearNumber = yearNumber - 1;
                year.innerHTML = yearNumber;
                month.innerHTML = monthOfYear[monthNumber];
            }
            $('.dayCount').empty();
            i = 1;
            x = 1;
            z = 1;
            firstDay = new Date(yearNumber, monthNumber, 1).getDay();
            emptyBlock = 35 - (dayCountNumber + firstDay)
            if (monthNumber === 0 || monthNumber === 2 || monthNumber === 4 || monthNumber === 6 || monthNumber ===
                7 || monthNumber === 9 || monthNumber === 11) {
                dayCountNumber = 31;
            } else if (monthNumber === 1) {
                if (yearNumber % 4 === 0) {
                    dayCountNumber = 29;
                } else {
                    dayCountNumber = 28;
                }
            } else {
                dayCountNumber = 30;
            }
            for (i; i < firstDay + 1; i++) {
                $('.dayCount').append('<p class="box" id="dayBox">' + '<p>');
            }
            for (x; x < dayCountNumber + 1; x++) {
                $('.dayCount').append('<p class="box" id="dayBox">' + '<span>' + x + '</span>' + '<p>');
            }
            emptyBlock = 42 - (dayCountNumber + firstDay);
            for (z; z < emptyBlock; z++) {
                $('.dayCount').append('<p class="box" id="dayBox"><p>');
            }
            if (activeMonth === monthNumber && activeYear == yearNumber) {
                $('.dayCount .box').eq(firstDay + dayInMonth - 1).addClass('activeParent');
                $('.dayCount .activeParent').append('<div class="active"/>');
                $('.dayCount .activeParent').css({
                    color: '#000'
                });
            }
        });
        $('document').on('click', () => {

        })
        $('.right').click(function() {

            if (monthNumber < 11) {
                $(this).each(function() {
                    monthNumber = monthNumber + 1;
                    month.innerHTML = monthOfYear[monthNumber];
                });
            } else {
                monthNumber = 0;
                yearNumber = yearNumber + 1;
                year.innerHTML = yearNumber;
                month.innerHTML = monthOfYear[monthNumber];
            }
            $('.dayCount').empty();
            i = 1;
            x = 1;
            z = 1;
            firstDay = new Date(yearNumber, monthNumber, 1).getDay();
            emptyBlock = 35 - (dayCountNumber + firstDay)
            if (monthNumber === 0 || monthNumber === 2 || monthNumber === 4 || monthNumber === 6 || monthNumber ===
                7 || monthNumber === 9 || monthNumber === 11) {
                dayCountNumber = 31;
            } else if (monthNumber === 1) {
                if (yearNumber % 4 === 0) {
                    dayCountNumber = 29;
                } else {
                    dayCountNumber = 28;
                }
            } else {
                dayCountNumber = 30;
            }
            for (i; i < firstDay + 1; i++) {
                $('.dayCount').append('<p class="box" id="dayBox">' + '<p>');
            }
            for (x; x < dayCountNumber + 1; x++) {
                $('.dayCount').append('<p class="box" id="dayBox">' + '<span>' + x + '</span>' + '<p>');
            }
            emptyBlock = 42 - (dayCountNumber + firstDay);
            for (z; z < emptyBlock; z++) {
                $('.dayCount').append('<p class="box" id="dayBox"><p>');
            }
            if (activeMonth === monthNumber && activeYear == yearNumber) {
                $('.dayCount .box').eq(firstDay + dayInMonth - 1).addClass('activeParent');
                $('.dayCount .activeParent').append('<div class="active"/>');
                $('.dayCount .activeParent').css({
                    color: '#000'
                });
            }
        });
    </script>
    <!--chart-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.50.0/apexcharts.min.js"></script>
    <script>
        $(document).ready(function() {
            var options = {
                series: [{
                    data: [10, 20, 30, 40, 50, 60, 70]
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        borderRadiusApplication: 'end',
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: ['ME3691', 'OCS352', ' OCS342', 'ME9832', 'DE8752', 'PO0987'],
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
    <script src="https://enggdemo.kalvierp.com/css/apexcharts.js"></script>
@endsection
