@extends('layouts.teachingStaffHome')
@section('content')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />

    <style>
        .firstnav {
            height: 300px;
            background: rgb(177, 180, 181);
            background: linear-gradient(0deg, rgba(177, 180, 181, 0.7203256302521008) 0%, rgba(255, 255, 255, 1) 100%);
            margin: -90px;
            /* z-index: -9999; */
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

    <div class="row gutters">
        <div class=" col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner"style="height: 114px;">
                                    <h3 class="counter-value">00</h3>
                                    <p>Current Year Leader Board Score</p>
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
                                <div class="inner"style="height: 114px;">
                                    <h3 class="counter-value">44</h3>
                                    <p>Life Time Leader Board Score</p>
                                </div>
                                <div class="icon"style="height: 114px;">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner"style="height: 114px;">
                                    <h3 class="counter-value">150</h3>
                                    <p>Life Time Cafe Score</p>
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

    </div>
    <div class="row gutters-sm">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body" style="height: 351px;">
                    <div class="d-flex flex-column align-items-center text-center">
                        @if (
                            (isset($document->filePath) ? $document->filePath : '') != '' ||
                                (isset($document->filePath) ? $document->filePath : '') != null)
                            <img class="uploaded_img rounded-circle" src="{{ asset($document->filePath) }}" alt="image">
                        @else
                            @if ($personal_details->gender == 'MALE' || $personal_details->gender == 'Male')
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin"
                                    class="rounded-circle" width="150">
                            @elseif($personal_details->gender == 'FEMALE' || $personal_details->gender == 'Female')
                                <img src="https://bootdey.com/img/Content/avatar/avatar8.png" alt="Admin"
                                    class="rounded-circle" width="150">
                            @else
                                <img src="{{ asset('adminlogo/user-image.png') }}" alt="Admin" class="rounded-circle"
                                    width="150">
                            @endif
                        @endif

                        <div class="mt-3">
                            <h4>{{ isset($personal_details->name) ? $personal_details->name : '' }}
                                {{ isset($personal_details->last_name) ? $personal_details->last_name : '' }}
                            </h4>
                            <p class="text-secondary mb-1">
                                {{ isset($teaching_staffs->Designation) ? $teaching_staffs->Designation : '' }}
                            </p>
                            <p class="text-secondary mb-1">Address :</p>
                            <div class="text-muted font-size-sm">
                                {{ isset($Address->room_no_and_street) ? $Address->room_no_and_street : '' }},
                                {{ isset($Address->area_name) ? $Address->area_name : '' }},
                                {{ isset($Address->district) ? $Address->district : '' }},
                                {{ isset($Address->state) ? $Address->state : '' }},
                                {{ isset($Address->country) ? $Address->country : '' }}</div>
                            <div class="text-muted font-size-sm"> PIN Code :
                                {{ isset($Address->pincode) ? $Address->pincode : '' }}</div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3 ">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-globe mr-2 icon-inline">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path
                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                </path>
                            </svg>Website</h6>
                        <span class="text-secondary">https://bootdey.com</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-github mr-2 icon-inline">
                                <path
                                    d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">
                                </path>
                            </svg>Github</h6>
                        <span class="text-secondary">bootdey</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-twitter mr-2 icon-inline text-info">
                                <path
                                    d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                </path>
                            </svg>Twitter</h6>
                        <span class="text-secondary">@bootdey</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-instagram mr-2 icon-inline text-danger">
                                <rect x="2" y="2" width="20" height="20" rx="5"
                                    ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>Instagram</h6>
                        <span class="text-secondary">bootdey</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-facebook mr-2 icon-inline text-primary">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z">
                                </path>
                            </svg>Facebook</h6>
                        <span class="text-secondary">bootdey</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" class="bi bi-youtube" viewBox="0 0 24 24">
                                <path
                                    d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                            </svg><span class="ml-2">YouTube</span></h6>
                        <span class="text-secondary">bootdey</span>
                    </li>

                </ul>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card ">
                <div class="card-body" style="">
                    <div class="row about-list">
                        <div class="col-md-6">
                            <div class="media">
                                <label>Birthday :&nbsp;</label>
                                <p>{{ isset($personal_details->dob) ? date('jS F Y', strtotime($personal_details->dob)) : '' }}
                                </p>

                            </div>
                            <div class="media">
                                <label>Age :&nbsp;</label>
                                <p>{{ isset($personal_details->age) ? $personal_details->age : '' }}</p>
                            </div>
                            <div class="media">
                                <label>Staff Code :&nbsp;</label>
                                <p>{{ isset($personal_details->StaffCode) ? $personal_details->StaffCode : '' }}
                                </p>
                            </div>
                            <div class="media">
                                <label>Department :&nbsp;</label>
                                <p>{{ isset($personal_details->Dept) ? $personal_details->Dept : '' }}</p>
                            </div>
                            <div class="media">
                                <label>Date Of Joining :&nbsp;</label>
                                <p>{{ isset($personal_details->DOJ) ? $personal_details->DOJ : '' }}</< /p>
                            </div>
                            <div class="media">
                                <label>Total Experience :&nbsp;</label>
                                <p>{{ isset($personal_details->TotalExperience) ? $personal_details->TotalExperience : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="media">
                                <label>E-mail :&nbsp;</label>
                                <p>{{ isset($personal_details->email) ? $personal_details->email : '' }}</p>
                            </div>
                            <div class="media">
                                <label>Phone :&nbsp;</label>
                                <p>{{ isset($personal_details->mobile_number) ? $personal_details->mobile_number : '' }}
                                </p>
                            </div>
                            <div class="media">
                                <label>Religion :&nbsp;</label>
                                <p>{{ isset($personal_details->Religion->name) ? $personal_details->Religion->name : '' }}
                                </p>
                            </div>
                            <div class="media">
                                <label>Highest Degree :&nbsp;</label>
                                <p>{{ isset($personal_details->HighestDegree) ? $personal_details->HighestDegree : '' }}
                                </p>
                            </div>
                            <div class="media">
                                <label>BiometricID :&nbsp;</label>
                                <p>{{ isset($personal_details->BiometricID) ? $personal_details->BiometricID : '' }}
                                </p>
                            </div>

                        </div>

                    </div>
                </div>

            </div>


            <div class="row gutters-sm">
                <div class="col-sm-6 mb-3">
                    <div class="card">


                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color: white;">
                                        <tr>

                                            <td style="background-color: white;">Event Participation</td>
                                            <td style="background-color: white;">
                                                <span>{{ $eventparticipation }}</span>
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>Awards</td>
                                            <td><span>{{ $Awards }}</span></td>

                                        </tr>
                                        <tr>

                                            <td style="background-color: white;">Patents</td>
                                            <td style="background-color: white;">
                                                <span>{{ $Patent }}</span>
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>Online Courses</td>
                                            <td><span>{{ $OnlineCourse }}</span></td>

                                        </tr>
                                        <tr>

                                            <td style="background-color: white;"> IV </td>
                                            <td style="background-color: white;">
                                                <span>{{ $Iv }}</span>
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>Industrial Experience</td>
                                            <td><span>{{ $IndustrialExperience }}</span></td>

                                        </tr>
                                        <tr>

                                            <td style="background-color: white;">Interns</td>
                                            <td style="background-color: white;">
                                                <span>{{ $Intern }}</span>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="col-sm-6 mb-3">

                    <div class="card">


                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                            <td style="background-color: white;">Industrial Training</td>
                                            <td style="background-color: white;">
                                                <span>{{ $IndustrialTraining }}</span>
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>Event Organized</td>
                                            <td><span>{{ $EventOrganized }}</span></td>

                                        </tr>
                                        <tr>

                                            <td style="background-color: white;">Journal</td>
                                            <td style="background-color: white;">
                                                <span>{{ $Journal }}</span>
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>Conference</td>
                                            <td><span>{{ $Conference }}</span></td>

                                        </tr>
                                        <tr>

                                            <td style="background-color: white;">Text Book</td>
                                            <td style="background-color: white;">
                                                <span>{{ $Text_Book }}</span>
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>Book Chapter</td>
                                            <td><span>{{ $Book_Chapter }}</span></td>

                                        </tr>
                                        <tr>

                                            <td style="background-color: white;">Ph.D</td>
                                            <td style="background-color: white;">
                                                <span>{{ $PhdDetail }}</span>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
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
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <script>
        $(document).ready(function() {
            // page is now ready, initialize the calendar...
            events = {!! json_encode($events) !!};
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                events: events,


            })
            //Script for tables
            $("table").addClass("table table-striped basic");
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
@stop
