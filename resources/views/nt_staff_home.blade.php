@extends('layouts.non_techStaffHome')
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
