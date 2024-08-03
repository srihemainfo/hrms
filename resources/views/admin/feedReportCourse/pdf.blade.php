<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feedback Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #495057;
            margin: auto;
            border: 1px solid black;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            max-width: 1000px;
            margin: auto;
        }

        .card-header {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            font-size: 1.5em;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .details {
            margin-bottom: 20px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
        }

        .details .row {
            margin-bottom: 10px;
        }

        .details span {
            font-size: 1em;
            color: #495057;
        }

        .details strong {
            color: #343a40;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ced4da;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            font-size: 0.9em;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #e9ecef;
        }

        tr:hover {
            background-color: #d6d6d6;
        }

        .secondLoader {
            text-align: center;
            margin-top: 20px;
        }

        .secondLoader::after {
            content: '';
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 4px solid #007bff;
            border-top: 4px solid transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .container {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container .row {
            margin-bottom: 15px;
        }

        .container .col-xl-6,
        .container .col-lg-6 {
            padding: 0 15px;
        }

        .logo_div {
            position: relative;
            height: 100px;
            margin-top: 20px;
        }

        .logo_div img {
            position: absolute;
            left: 20px;
        }

        .logo_div span {
            position: absolute;
            top: 35px;
            right: 8%;
        }
    </style>
</head>

<body>

    <div class="section">
        <div class="logo_section">
            <div class="logo_div">
                <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt=""
                    style="width: 150px; height: 70px;">
                <span class="collage-title">Demo College OF ENGINNERING AND TECHNOLOGY</span>
            </div>
            <h5 style="text-align: center;">Report</h5>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Student Training Feedback Report
        </div>
        <div class="card-body">
            <div class="container">
                <div class="details">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <span><strong>Class:</strong> {{ $get_feed[0]->enroll ?? '' }}</span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <span><strong>Participant:</strong> {{ $get_feed[0]->feedback_participant }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <span><strong>Type:</strong> {{ $get_feed[0]->feedback_type }}</span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <span><strong>Feedback Name:</strong> {{ $get_feed[0]->feedback->name }}</span>
                        </div>
                    </div>
                    @php
                        $decode = json_decode($get_feed[0]->feedback_schedule->training);
                    @endphp
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <span><strong>Training Type:</strong> {{ $decode->type_training }}</span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <span><strong>Title :</strong> {{ $decode->title_training }}</span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <span><strong>Person :</strong> {{ $decode->person_training }}</span>
                        </div>
                    </div>
                </div>
                <table
                    class="table table-bordered table-striped table-hover ajaxTable datatable datatable-feedbackReport text-center">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>S.No</th>
                            <th>Question</th>
                            <th>Submitted Count</th>
                            <th>5 Scale (%)</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @foreach ($get_feed as $id => $item)
                            <tr>
                                <td></td>
                                <td>{{ $id + 1 }}</td>
                                <td style="text-transform: uppercase;">{{ $item->question_name }}</td>
                                <td>{{ $item->submitted }}</td>
                                <td>{{ $item->star_percent }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <div class="secondLoader"></div> --}}
    </div>
</body>

</html>
