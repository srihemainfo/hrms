@php
     ini_set('max_execution_time', 360);
    ini_set('memory_limit', '-1');
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>
        {{ $data2['theSubject'] }} {{ $data2['report'] }}
    </title>

</head>

<body>

    <table border="1">
        <thead>
            <tr>
                <td>
                    <div>
                        <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" alt="Image Description"
                            style="width: 150px; height: 70px; margin-top: 20px;">
                    </div>
                </td>
                <td style='text-align:center;'>
                    <h3 style='margin-right:90px'> {{ $data2['course_name'] }} </h3>
                    <h3 style='margin-right:100px'> {{ $data2['theSubject'] }} </h3>
                    <h3 style='margin-right:70px'> {{ $data2['class_shor_form'] }} </h3>
                    <h3 style='margin-right:70px'> {{ $data2['report'] }} </h3>
                </td>
            </tr>
        </thead>
    </table>
    @php
    $true = true;
    $i = 1;
@endphp

    <table border="1" style="text-align:center;width:100%;">
        @foreach($days as $id => $value)
            @foreach($value as $day)
                @php
                    $parts = explode('|', $day)[1];
                    $date = explode('|', $day)[0];
                    $date = str_replace("|","",$date);
                    $true = true;
                @endphp
                @if ($true)
                    <tr>
                        <td colspan="4" style="padding:5px;"></td>
                    </tr>
                    <tr>
                    <th colspan="2"> PERIOD: &nbsp; {{$parts}}</th>
                    <th colspan='2'>DATE:
                        &nbsp;{{ date('d-m-Y', strtotime($date)) }}</th>
                    </tr>
                    <tr>
                        <th>S.NO</th>
                        <th>Student Name</th>
                        <th>Register Number </th>
                        <th>Status</th>
                    </tr>

                    @php
                        $true = false;
                    @endphp
                @endif

                    @php
                    $i = 1;
                    @endphp
                @foreach ($data as $ids => $value)
                    <tr style="font-size:0.8rem;">
                        <td>{{ $i++ }}</td>
                        <td>{{ $value['details'][0] }}</td>
                        <td>{{ $value['details'][1] }}</td>
                            @if( isset($value['day'][$day]) && array_key_exists($day,$value['day']))
                            <td>{{ $value['present_details'][$day] }}</td>
                            @else
                            <td></td>
                            @endif
                    </tr>
                @endforeach

            @endforeach
        @endforeach
    </table>
</body>
</html>
