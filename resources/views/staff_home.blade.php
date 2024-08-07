@extends('layouts.teachingStaffHome')
@section('content')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.49.2/apexcharts.min.css"
        integrity="sha512-YEwcgX5JXVXKtpXI4oXqJ7GN9BMIWq1rFa+VWra73CVrKds7s+KcOfHz5mKzddIOLKWtuDr0FzlTe7LWZ3MTXw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        .firstnav {
            height: 300px;
            background: rgb(177, 180, 181);
            background: linear-gradient(0deg, rgba(177, 180, 181, 0.7203256302521008) 0%, rgba(255, 255, 255, 1) 100%);
            margin: -90px;
            /* z-index: -9999; */
        }

        .row-1 {
            background-color: #E1F5FE;
        }

        .row-2 {
            background-color: #ffffff;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            line-height: 42px;
            text-align: center;
            color: white;
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
            /*margin-top: 20px;*/
            background-color: white;
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
            height: 420px;
            border-radius: 5px;
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
            padding: 10px;
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            font-size: 15px;
        }

        .calendar-container {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            height: 420px;

            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .calendar-container header {
            display: flex;
            align-items: baseline;
            padding: 4px 20px;
            justify-content: space-between;
        }

        header .calendar-navigation {
            display: flex;
        }

        header .calendar-navigation span {
            height: 38px;
            width: 38px;
            margin: 0 1px;
            cursor: pointer;
            text-align: center;
            line-height: 38px;
            border-radius: 50%;
            user-select: none;
            color: #aeabab;
            font-size: 1.9rem;
        }

        .container {
            background-color: white;
        }

        h2 {
            font-size: 2rem;
            /* margin-top: 10px; */
            padding-top: 18px;
            padding-left: 18px;
        }

        .calendar-navigation span:last-child {
            margin-right: -10px;
        }

        header .calendar-navigation span:hover {
            background: #f2f2f2;
        }

        header .calendar-current-date {
            font-weight: 500;
            font-size: 1.45rem;
        }

        /*.calendar-body {*/
        /*    padding: 20px;*/
        /*}*/

        .calendar-body ul {
            list-style: none;
            flex-wrap: wrap;
            margin-bottom: -1rem;
            display: flex;
            margin-right: 28px;
            text-align: center;
        }

        ul.calendar-weekdays {
            margin-bottom: 8px;
        }

        /*.calendar-body .calendar-dates {*/
        /*    margin-bottom: 20px;*/
        /*}*/

        .calendar-body li {
            width: calc(100% / 7);
            font-size: 1.07rem;
            color: #414141;
        }

        .calendar-body .calendar-weekdays li {
            cursor: default;
            font-weight: 500;
        }

        .calendar-body .calendar-dates li {
            margin-top: 20px;
            position: relative;
            z-index: 1;
            cursor: pointer;
        }

        .calendar-dates li.inactive {
            color: #aaa;
        }

        .calendar-dates li.active {
            color: #fff;
        }

        .calendar-dates li::before {
            position: absolute;
            content: "";
            z-index: -1;
            top: 50%;
            left: 50%;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        .calendar-dates li.active::before {
            background: #6332c5;
        }

        .calendar-dates li:not(.active):hover::before {
            background: #e4e1e1;
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

        .table td,
        .table th {
            padding: 0.75rem;
            vertical-align: top;
            border: 1px solid #dee2e6;
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

        @media (max-width: 320px) {
            .card {
                font-size: 10px;
                margin-left: -8px;
            }
        }

        @media (max-width: 375px) {
            .card {
                font-size: 10px;
                margin-left: -8px;
            }
        }

        @media (max-width: 320px) {
            .calendar-body ul {
                list-style: none;
                flex-wrap: wrap;
                display: flex;
                text-align: center;
                margin-left: -39px;
                /* margin-right: 13px; */
            }
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

        .table-bordered thead th {
            border-bottom-width: 2px;
            line-height: 42px;
        }

        .timetable-container {
            width: 100%;
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
            height: 320px;
            border-radius: 5px;
        }


        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            margin-top: 3px;
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
            background-color: #d85568;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table-bordered thead th {
            border-bottom-width: 2px;
            line-height: 42px;
            text-align: center;
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

        @media (max-width: 767px) {
            .calendar-container {
                margin-top: 30px;
            }
        }

        @media (max-width: 328px) {
            .timetable-container {
                height: 388px;
            }
        }
    </style>
    <div class="container">
        <div class="firstnav1">
            <nav aria-label="breadcrumb">

                <h2>Dashboard</h2>
            </nav>


            <h4 class="content-title "style="padding-left: 18px;">Hi, welcome back!</h4>




            <div class="row" style="margin-top: 10px;">
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
                                    <p class="box-content">Total Student</p>
                                    <!--<h3 class="counter-value">80</h3>-->
                                    <p class="value">80</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-graduate"></i>

                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i-->
                                <!--        class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">

                            <div class="small-box"id="second-box">
                                <div class="inner">
                                    <p class="box-content">Class</p>
                                    <!--<h3 class="counter-value">2</h3>-->
                                    <p class="value">06</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-graduation-cap"></i>

                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i-->
                                <!--        class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">

                            <div class="small-box" id="third-box">
                                <div class="inner">
                                    <p class="box-content">Leave Taken</p>
                                    <!--<h3 class="counter-value">1,000</h3>-->
                                    <p class="value">11</p>

                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i-->
                                <!--        class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>


                    </div>

                    <!--<div class="col-lg-3 col-6">-->

                    <!--    <div class="small-box bg-danger">-->
                    <!--        <div class="inner"style="height: 114px;">-->
                    <!--            <h3 class="counter-value">15</h3>-->
                    <!--            <p>Taken Leave</p>-->
                    <!--        </div>-->
                    <!--        <div class="icon">-->
                    <!--            <i class="ion ion-bag"></i>-->
                    <!--        </div>-->
                    <!--        <a href="#" class="small-box-footer">More info <i-->
                    <!--                class="fas fa-arrow-circle-right"></i></a>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>

            </div>
        </div>





        <div class="row mt-3">
            <div class="col-md-6  mb-3">

                <div class="timetable-container p-3">
                    <table class="timetable-table" style="width: 100%; border-collapse: collapse; text-align: center;">
                        <thead>
                            <tr class="header-row">
                                <th colspan="7">Time Table</th>
                            </tr>
                            <tr class="header-row">

                                <th colspan="2">Period Hrs</th>

                                <th colspan="3">Class Enrollment</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr class="row-1">
                                <td colspan="2">1<sup>st</sup>&nbsp;Hour <br>10.00 to 11.00</td>
                                <td colspan="5">
                                    <p>B.Tech CS & BS / 4 / B</p>
                                </td>
                            </tr>
                            <tr class="row-2">
                                <td colspan="2">2<sup>nd</sup>&nbsp;Hour<br>11.00 to 12.00</td>
                                <td colspan="5">
                                    <p>M.E. CSE-S1 / 4 / A</p>
                                </td>
                            </tr>
                            <tr class="row-1">
                                <td colspan="2">3<sup>rd</sup>&nbsp;Hour<br>12.00 to 01.00</td>
                                <td colspan="5">
                                    <p>B.E. MECH / 4 / A</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>


            <div class="col-md-6 mb-3">
                <div class="card p-2">
                    <h2 class="heading-text ">Hours Spent</h2>
                    <div id="hours-spent"></div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card p-2 ">
                    <h2 class="heading-text">Lesson Plan</h2>
                    <table class="table">
                        <thead>
                            <tr style="background-color:#ea730e;">
                                <th style="width:10px;">S.No</th>
                                <th>Enroll No</th>
                                <th>Topics</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="std-th">1</th>
                                <td>410U9802</td>
                                <td>Python Programming</td>
                            </tr>
                            <tr>
                                <th scope="row" class="std-th">2</th>
                                <td>410U1820</td>
                                <td>Engineering Chemistry</td>
                            </tr>
                            <tr>
                                <th scope="row" class="std-th">3</th>
                                <td>410U8928</td>
                                <td>Matrices and Calculus</td>
                            </tr>
                            <tr>
                                <th scope="row" class="std-th">4</th>
                                <td>410U1867</td>
                                <td>Professional English - I</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-2 leave-request-table">
                    <h2 class="heading-text">Student Leave Request</h2>
                    <table class="leave-request-table table table-bordered ajaxTable datatable datatable-coeIndex">
                        <thead>
                            <tr style="background-color: #2020bc;">
                                <th scope="col" class="std-th" style="width:10px;">S.No</th>
                                <th scope="col" class="std-th">Student Name</th>
                                <th scope="col" class="std-th">Date</th>
                                <th scope="col" class="std-th">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="std-th">1</th>
                                <td>Meera</td>
                                <td>08.07.2024</td>
                                <td>Fever</td>
                            </tr>
                            <tr>
                                <th scope="row" class="std-th">2</th>
                                <td>Lily</td>
                                <td>03.08.2024</td>
                                <td>Cold</td>
                            </tr>
                            <tr>
                                <th scope="row" class="std-th">3</th>
                                <td>Aaradhya</td>
                                <td>22.07.2024</td>
                                <td>Health issue</td>
                            </tr>
                            <tr>
                                <th scope="row" class="std-th">4</th>
                                <td>Kavya</td>
                                <td>14.09.2024</td>
                                <td>Fever</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card p-4">
                    <h2 class="heading-text">Syllabus Completion</h2>
                    <div id="syllabus-comp"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">


                <div class="calendar-container">

                    <header class="calendar-header">
                        <p class="calendar-current-date"></p>
                        <h2 class="heading-text">Calendar</h2>
                        <div class="calendar-navigation">
                            <span id="calendar-prev" class="material-symbols-rounded">
                                chevron_left
                            </span>
                            <span id="calendar-next" class="material-symbols-rounded">
                                chevron_right
                            </span>
                        </div>
                    </header>

                    <div class="calendar-body">
                        <ul class="calendar-weekdays">
                            <li>Sun</li>
                            <li>Mon</li>
                            <li>Tue</li>
                            <li>Wed</li>
                            <li>Thu</li>
                            <li>Fri</li>
                            <li>Sat</li>
                        </ul>
                        <ul class="calendar-dates"></ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">

                <div class="announcement container ">
                    <ul class="nav nav-tabs row" role="tablist">
                        <li class="nav-item col-6">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Announcement
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


        <!--<div class="card mt-3 ">-->
        <!--    <ul class="list-group list-group-flush">-->
        <!--        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
        <!--            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"-->
        <!--                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"-->
        <!--                    stroke-linecap="round" stroke-linejoin="round"-->
        <!--                    class="feather feather-globe mr-2 icon-inline">-->
        <!--                    <circle cx="12" cy="12" r="10"></circle>-->
        <!--                    <line x1="2" y1="12" x2="22" y2="12"></line>-->
        <!--                    <path-->
        <!--                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">-->
        <!--                    </path>-->
        <!--                </svg>Website</h6>-->
        <!--            <span class="text-secondary">https://bootdey.com</span>-->
        <!--        </li>-->
        <!--        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
        <!--            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"-->
        <!--                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"-->
        <!--                    stroke-linecap="round" stroke-linejoin="round"-->
        <!--                    class="feather feather-github mr-2 icon-inline">-->
        <!--                    <path-->
        <!--                        d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">-->
        <!--                    </path>-->
        <!--                </svg>Github</h6>-->
        <!--            <span class="text-secondary">bootdey</span>-->
        <!--        </li>-->
        <!--        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
        <!--            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"-->
        <!--                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"-->
        <!--                    stroke-linecap="round" stroke-linejoin="round"-->
        <!--                    class="feather feather-twitter mr-2 icon-inline text-info">-->
        <!--                    <path-->
        <!--                        d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">-->
        <!--                    </path>-->
        <!--                </svg>Twitter</h6>-->
        <!--            <span class="text-secondary">@bootdey</span>-->
        <!--        </li>-->
        <!--        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
        <!--            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"-->
        <!--                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"-->
        <!--                    stroke-linecap="round" stroke-linejoin="round"-->
        <!--                    class="feather feather-instagram mr-2 icon-inline text-danger">-->
        <!--                    <rect x="2" y="2" width="20" height="20" rx="5"-->
        <!--                        ry="5"></rect>-->
        <!--                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>-->
        <!--                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>-->
        <!--                </svg>Instagram</h6>-->
        <!--            <span class="text-secondary">bootdey</span>-->
        <!--        </li>-->
        <!--        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
        <!--            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"-->
        <!--                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"-->
        <!--                    stroke-linecap="round" stroke-linejoin="round"-->
        <!--                    class="feather feather-facebook mr-2 icon-inline text-primary">-->
        <!--                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z">-->
        <!--                    </path>-->
        <!--                </svg>Facebook</h6>-->
        <!--            <span class="text-secondary">bootdey</span>-->
        <!--        </li>-->
        <!--        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">-->
        <!--            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"-->
        <!--                    fill="currentColor" class="bi bi-youtube" viewBox="0 0 24 24">-->
        <!--                    <path-->
        <!--                        d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />-->
        <!--                </svg><span class="ml-2">YouTube</span></h6>-->
        <!--            <span class="text-secondary">bootdey</span>-->
        <!--        </li>-->

        <!--    </ul>-->
        <!--</div>-->
        <!--</div>-->
        <!--<div class="col-md-8">-->
        <!--    <div class="card ">-->
        <!--        <div class="card-body" style="">-->
        <!--            <div class="row about-list">-->
        <!--                <div class="col-md-6">-->
        <!--                    <div class="media">-->
        <!--                        <label>Birthday :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->dob) ? date('jS F Y', strtotime($personal_details->dob)) : '' }}-->
        <!--                        </p>-->

        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>Age :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->age) ? $personal_details->age : '' }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>Staff Code :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->StaffCode) ? $personal_details->StaffCode : '' }}-->
        <!--                        </p>-->
        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>Department :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->Dept) ? $personal_details->Dept : '' }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>Date Of Joining :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->DOJ) ? $personal_details->DOJ : '' }}</< /p>-->
        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>Total Experience :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->TotalExperience) ? $personal_details->TotalExperience : '' }}-->
        <!--                        </p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="col-md-6">-->
        <!--                    <div class="media">-->
        <!--                        <label>E-mail :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->email) ? $personal_details->email : '' }}</p>-->
        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>Phone :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->mobile_number) ? $personal_details->mobile_number : '' }}-->
        <!--                        </p>-->
        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>Religion :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->Religion->name) ? $personal_details->Religion->name : '' }}-->
        <!--                        </p>-->
        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>Highest Degree :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->HighestDegree) ? $personal_details->HighestDegree : '' }}-->
        <!--                        </p>-->
        <!--                    </div>-->
        <!--                    <div class="media">-->
        <!--                        <label>BiometricID :&nbsp;</label>-->
        <!--                        <p>{{ isset($personal_details->BiometricID) ? $personal_details->BiometricID : '' }}-->
        <!--                        </p>-->
        <!--                    </div>-->

        <!--                </div>-->

        <!--            </div>-->
        <!--        </div>-->

        <!--    </div>-->


        <!--    <div class="row gutters-sm">-->
        <!--        <div class="col-sm-6 mb-3">-->
        <!--            <div class="card">-->


        <!--                <div class="card-body p-0">-->
        <!--                    <div class="table-responsive">-->
        <!--                        <table class="table m-0">-->
        <!--                            <thead>-->
        <!--                                <tr>-->
        <!--                                    <th>Subject</th>-->
        <!--                                    <th>Status</th>-->
        <!--                                </tr>-->
        <!--                            </thead>-->
        <!--                            <tbody style="background-color: white;">-->
        <!--                                <tr>-->

        <!--                                    <td style="background-color: white;">Event Participation</td>-->
        <!--                                    <td style="background-color: white;">-->
        <!--                                        <span>{{ $eventparticipation }}</span>-->
        <!--                                    </td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td>Awards</td>-->
        <!--                                    <td><span>{{ $Awards }}</span></td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td style="background-color: white;">Patents</td>-->
        <!--                                    <td style="background-color: white;">-->
        <!--                                        <span>{{ $Patent }}</span>-->
        <!--                                    </td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td>Online Courses</td>-->
        <!--                                    <td><span>{{ $OnlineCourse }}</span></td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td style="background-color: white;"> IV </td>-->
        <!--                                    <td style="background-color: white;">-->
        <!--                                        <span>{{ $Iv }}</span>-->
        <!--                                    </td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td>Industrial Experience</td>-->
        <!--                                    <td><span>{{ $IndustrialExperience }}</span></td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td style="background-color: white;">Interns</td>-->
        <!--                                    <td style="background-color: white;">-->
        <!--                                        <span>{{ $Intern }}</span>-->
        <!--                                    </td>-->

        <!--                                </tr>-->
        <!--                            </tbody>-->
        <!--                        </table>-->
        <!--                    </div>-->

        <!--                </div>-->


        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="col-sm-6 mb-3">-->

        <!--            <div class="card">-->


        <!--                <div class="card-body p-0">-->
        <!--                    <div class="table-responsive">-->
        <!--                        <table class="table m-0">-->
        <!--                            <thead>-->
        <!--                                <tr>-->
        <!--                                    <th>Subject</th>-->
        <!--                                    <th>Status</th>-->
        <!--                                </tr>-->
        <!--                            </thead>-->
        <!--                            <tbody>-->
        <!--                                <tr>-->

        <!--                                    <td style="background-color: white;">Industrial Training</td>-->
        <!--                                    <td style="background-color: white;">-->
        <!--                                        <span>{{ $IndustrialTraining }}</span>-->
        <!--                                    </td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td>Event Organized</td>-->
        <!--                                    <td><span>{{ $EventOrganized }}</span></td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td style="background-color: white;">Journal</td>-->
        <!--                                    <td style="background-color: white;">-->
        <!--                                        <span>{{ $Journal }}</span>-->
        <!--                                    </td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td>Conference</td>-->
        <!--                                    <td><span>{{ $Conference }}</span></td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td style="background-color: white;">Text Book</td>-->
        <!--                                    <td style="background-color: white;">-->
        <!--                                        <span>{{ $Text_Book }}</span>-->
        <!--                                    </td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td>Book Chapter</td>-->
        <!--                                    <td><span>{{ $Book_Chapter }}</span></td>-->

        <!--                                </tr>-->
        <!--                                <tr>-->

        <!--                                    <td style="background-color: white;">Ph.D</td>-->
        <!--                                    <td style="background-color: white;">-->
        <!--                                        <span>{{ $PhdDetail }}</span>-->
        <!--                                    </td>-->

        <!--                                </tr>-->
        <!--                            </tbody>-->
        <!--                        </table>-->
        <!--                    </div>-->

        <!--                </div>-->


        <!--            </div>-->

        <!--        </div>-->
        <!--    </div>-->



        <!--</div>-->
    </div>

@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.49.2/apexcharts.min.js"
        integrity="sha512-3BIgFs7OIA76S6nx4QMAiSPlGXgCN+eITFIY6q0q0sFPxkuVzVXy0Vp/yQfXP3wyf+DmRpHRzEw3fQc/yrhk4w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            // page is now ready, initialize the calendar...
            events = {!! json_encode($events) !!};
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                events: events,


            })
            //Script for tables
            // $("table").addClass("table table-striped basic");
            $("th").not(":nth-child(1)").addClass("time");


            $('table tr').each(function() {
                var $t = $(this).closest('table').find('.time');
                $('td', this).not(':first-child').each(function(i) {
                    $(this).attr('data-before-content', $t.eq(i).text());
                });
            });



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

    <!--Hours Spent-->

    <script>
        var options = {
            series: [44, 55, 41, 17, 15],
            chart: {
                type: 'donut',
            },
            responsive: [{
                    breakpoint: 300,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                },
                {
                    breakpoint: 2000,
                    options: {
                        chart: {
                            width: 400
                        },
                        legend: {
                            position: 'right'
                        }
                    }
                }
            ],
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        };

        var chart = new ApexCharts(document.querySelector("#hours-spent"), options);
        chart.render();
    </script>


    <!--Syllabus Completion-->

    <script>
        var options = {
            series: [{
                name: 'Python Programming',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'Engineering Chemistry',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z",
                    "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z",
                    "2018-09-19T06:30:00.000Z"
                ]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#syllabus-comp"), options);
        chart.render();
    </script>

    <!--Calendar JS-->

    <script>
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth();

        const day = document.querySelector(".calendar-dates");

        const currdate = document
            .querySelector(".calendar-current-date");

        const prenexIcons = document
            .querySelectorAll(".calendar-navigation span");

        // Array of month names
        const months = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];

        // Function to generate the calendar
        const manipulate = () => {

            // Get the first day of the month
            let dayone = new Date(year, month, 1).getDay();

            // Get the last date of the month
            let lastdate = new Date(year, month + 1, 0).getDate();

            // Get the day of the last date of the month
            let dayend = new Date(year, month, lastdate).getDay();

            // Get the last date of the previous month
            let monthlastdate = new Date(year, month, 0).getDate();

            // Variable to store the generated calendar HTML
            let lit = "";

            // Loop to add the last dates of the previous month
            for (let i = dayone; i > 0; i--) {
                lit +=
                    `<li class="inactive">${monthlastdate - i + 1}</li>`;
            }

            // Loop to add the dates of the current month
            for (let i = 1; i <= lastdate; i++) {

                // Check if the current date is today
                let isToday = i === date.getDate() &&
                    month === new Date().getMonth() &&
                    year === new Date().getFullYear() ?
                    "active" :
                    "";
                lit += `<li class="${isToday}">${i}</li>`;
            }

            // Loop to add the first dates of the next month
            for (let i = dayend; i < 6; i++) {
                lit += `<li class="inactive">${i - dayend + 1}</li>`
            }

            // Update the text of the current date element 
            // with the formatted current month and year
            currdate.innerText = `${months[month]} ${year}`;

            // update the HTML of the dates element 
            // with the generated calendar
            day.innerHTML = lit;
        }

        manipulate();

        // Attach a click event listener to each icon
        prenexIcons.forEach(icon => {

            // When an icon is clicked
            icon.addEventListener("click", () => {

                // Check if the icon is "calendar-prev"
                // or "calendar-next"
                month = icon.id === "calendar-prev" ? month - 1 : month + 1;

                // Check if the month is out of range
                if (month < 0 || month > 11) {

                    // Set the date to the first day of the 
                    // month with the new year
                    date = new Date(year, month, new Date().getDate());

                    // Set the year to the new year
                    year = date.getFullYear();

                    // Set the month to the new month
                    month = date.getMonth();
                } else {

                    // Set the date to the current date
                    date = new Date();
                }

                // Call the manipulate function to 
                // update the calendar display
                manipulate();
            });
        });
    </script>

@stop
