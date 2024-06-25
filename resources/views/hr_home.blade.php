 @extends('layouts.admin')
@section('content')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />

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
                                    <a href="{{ route('admin.teaching-staffs.index') }}" class="small-box-footer">More info
                                        <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 class="counter-value">{{ $nonTeachingStaffs }}<sup style="font-size: 20px"></sup></h3>
                                        <p>Non Teaching Staffs</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person"></i>
                                    </div>
                                    <a href="{{ route('admin.non-teaching-staffs.index') }}" class="small-box-footer">More info
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
                                        <h3 class="counter-value">2</h3>
                                        <p>Unique Visitors</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-pie-graph"></i>
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
    </div>
@endsection
@section('scripts')
    @parent

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

