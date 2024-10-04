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

        .details {
            margin-bottom: 20px;
            /* border-bottom: 2px solid #dee2e6; */
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
            padding: 5px;
            text-align: center;
            font-size: 0.9em;
        }

        th:nth-child(even) {
            max-width: 90px; 
            font-weight: bold;
        }

        td:nth-child(even) {
            max-width: 90px; 
            word-wrap: break-word;
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

        /* .container .row {
            margin-bottom: 15px;
        }

        .container .col-xl-6,
        .container .col-lg-6 {
            padding: 0 15px;
        } */

        .logo_div {
            position: relative;
            height: 70px;
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
            <h5 style="text-align: center;">{{$get_feed[0]->feedback->name}}</h5>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="details">
                    <div class="row">
                        <div style="position: relative;">
                            <span style="position: absolute; left: 50px;"><strong>Department:</strong> {{ $get_feed[0]->dept ?? '' }}</span>
                            <span style="position: absolute; right: 50px;"><strong>Participant:</strong> {{ $get_feed[0]->feedback_participant }}</span>
                        </div>
                    </div>
                </div>
                <table
                    class="table table-bordered table-striped table-hover ajaxTable datatable datatable-feedbackReport text-center">
                    <thead>
                        @php
                            $rate = ['Excelent', 'Very good', 'Good', 'Fair', 'Poor'];
                            if ($get_feed[0]->rating_scale != 5) {
                                $slice_count = count($rate) - $get_feed[0]->rating_scale;
                                $rate = array_slice($rate, $slice_count);
                            }
                        @endphp
                        <tr>
                            <th>S.No</th>
                            <th>Question</th>
                            @for ($i = 0; $i < $get_feed[0]->rating_scale; $i++)
                                <th>{{ $rate[$i] }}</th>
                            @endfor
                            <th>Total Weightage</th>
                            <th>Percentage (%)</th>
                            <th>{{ $get_feed[0]->rating_scale }} Scale</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @foreach ($get_feed as $id => $item)
                            <tr>
                                <td>{{ $id + 1 }}</td>
                                <td style="text-transform: capitalize;">{{ $item->question_name }}</td>
                                <td>{{ $item->five_star }}</td>
                                <td>{{ $item->four_star }}</td>
                                <td>{{ $item->three_star }}</td>
                                <td>{{ $item->two_star }}</td>
                                <td>{{ $item->one_star }}</td>
                                <td>{{ $item->star_percent }} %</td>
                                <td>{{ $item->scale }}</td>
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
