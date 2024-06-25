@extends('layouts.teachingStaffHome')
@section('content')
@php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 360);
@endphp

<head>
    <title>
        {{ $data2['theSubject'] }} {{ $data2['report'] }}
    </title>

</head>

<style>
    .null-cell {
        color: red;
    }

    .table-container {
        max-height: 500px;
        overflow-y: auto;
    }
</style>

<div class="card">
    <div class="text-right">
        <div class="card-header text-right" id="card_header">
            <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>
        </div>
    </div>
    <div class="card-header">
        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary text-center text-uppercase"> Student Daily Attendance</h5>
    </div>
    <div class="card-body">


        <div class="table-responsive table-container">
            <table class=" table table-bordered text-center table-striped table-hover " id='tbl_exporttable_to_xls'>
                @php
                $title = true;
                $sno = 0;
                @endphp
                @foreach ($days as $id => $value)
                @php
                $count = count($value) + 3;
                @endphp
                @endforeach

                <thead>
                    <tr>
                        <th colspan="{{$count}}" class='text-center text-uppercase'> {{ $data2['course_name'] ?? '' }} </th>
                    </tr>
                    <tr>
                        <th colspan="{{ $count }}" class='text-center text-uppercase'> {{ $data2['report'] ?? '' }} </th>
                    </tr>
                    <tr>
                        <th colspan="{{$count /2}}" class='text-center text-uppercase'> Class: &nbsp; {{ $data2['class_shor_form'] ?? '' }} </th>
                        <th colspan="{{$count /2}}" class='text-center text-uppercase'> Subject: &nbsp;{{ $data2['theSubject']  ?? ''}} </th>
                    </tr>
                </thead>

                @foreach ($days as $id => $value)
                <thead>
                    <tr>
                        <th> </th>
                        <th> </th>
                        <th> Period</th>
                        @foreach($value as $day)
                        @php
                        $parts = explode('|', $day)[1];
                        @endphp
                        <th> {{ $parts ?? '' }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th> S.NO </th>
                        <th> Student Name </th>
                        <th> Register Number </th>
                        @foreach($value as $day)
                        @php
                        $part = explode('|', $day)[0];
                        $explode = explode('-',$part);
                        $date = $explode[2].'/'.$explode[1].'/'.$explode[0];
                        @endphp

                        <th>{{ $date }}</th>
                        @endforeach

                    </tr>
                </thead>
                @endforeach
                @foreach($data as $ids => $value)
                @php
                $name = true;
                @endphp
                <tbody>
                    <tr>
                        @if($name)
                        <td> {{$sno+1}} </td>
                        <td> {{$value['details'][0] ?? '' }}</td>
                        <td>{{ $value['details'][1] ?? '' }}</td>
                        @endif
                        @foreach ($days['day'] as $id => $value2)
                        @php
                        $status = true;
                        @endphp
                        <th>
                            @foreach($value['present_details'] as $date => $present)
                            @if($status)
                            @if($date == $value2)
                            {{$present ?? ''}}
                            @php
                            $status = false;
                            @endphp
                            @endif
                            @endif
                            @endforeach
                        </th>
                        @endforeach
                    </tr>
                </tbody>
                @php
                $sno++;
                @endphp
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('tbl_exporttable_to_xls');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        //     var ws = wb.Sheets["sheet1"];
        // var style = {
        //     font: {
        //         color: { rgb: "FF0000" }, // Red font color
        //         bold: true, // Bold text
        //     },
        //     fill: {
        //         fgColor: { rgb: "FFFF00" }, // Yellow fill color
        //     },
        // };

        // ws["A1"].s = style; // Apply style to cell A1
        // ws["B2:C2"].s = style;
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || (`AttendanceReport_ {{{  $data2['theSubject'] ?? '' }}}.` + (type || 'xlsx')));
    }
</script>
@endsection
