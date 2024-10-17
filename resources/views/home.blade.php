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
        // echo $roleTitle;
        // dd('hii');
    @endphp

    @if ($roleTitle == 1)
        <div class="row">
            <div class="col-lg-12">
                <p id="welcome">Welcome Admin!</p>
                <p id="dashboard">Dashboard</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="employee_count" class="counts">{{ $staffsCount }}</p>
                            <p id="employee">SHI Staffs</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-cubes"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="project_count" class="counts">{{ $projectCount }}</p>
                            <p id="projects">Projects</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">

                </div>
            </div>
            <div class="col-md-3">
                <div class="card">

                </div>
            </div>
        </div>

        <style>
            .select2 {
                width: 100% !important;
            }

            #welcome {
                margin-top: -10px;
                font-size: 28px;
                font-weight: bold;
            }

            #dashboard {
                margin-top: -10px;
                color: gray;
                font-size: 20px;
            }

            .card {
                height: 110px;
                border-bottom: 5px solid #007bff;
            }

            .icon-container {
                background-color: #d3d8df;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 20px 0 0 20px;
            }

            .icon-container i {
                color: #007bff;
                font-size: 26px;
            }

            .counts {
                margin-right: 35px;
                font-size: 26px;
                font-weight: bold;
                margin-top: 20px;
            }

            #employee {
                margin-right: 35px;
                margin-top: -10px;
                font-size: 18px;
            }

            #projects {
                margin-right: 35px;
                margin-top: -10px;
                font-size: 18px;
            }
        </style>
    @endif
@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <script src="your-js-file.js"></script>
@endsection
