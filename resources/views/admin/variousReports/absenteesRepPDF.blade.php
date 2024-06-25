<!DOCTYPE html>
<html>

<head>

</head>
{{-- {{ dd($enrolls) }} --}}

<body>
    @php
        $strength = 0;
        $present = 0;
        $absent = 0;
        $leave = 0;
        $od = 0;
    @endphp
    @if (count($enrolls) > 0)
        <div style="text-align:center;">
            <h3>Demo Collage Of Engineering & Technology</h3>
            <h4>DEPARTMENT OF {{ $dept }}</h4>
            <h5>ABSENTEES SUMMARY</h5>
        </div>
        <div style="text-align:right;">
            <p> <b>Date : </b>{{ $date }}</p>
        </div>
        @foreach ($enrolls as $enroll)
            {{-- {{ dd($enroll) }} --}}
            <table
                style="font-size:0.6rem;width:100%;margin-bottom:0.8rem;border-collapse:collapse;border:1px solid rgba(0, 0, 0, 0.767);">
                <tr>
                    <th colspan="{{ count($enroll['data']) + 1 }}">{{ $enroll['year'] }}</th>
                </tr>
                <tr>
                    @foreach ($enroll['data'] as $sections)
                        <th style="min-width:70px;border:1px solid rgba(0, 0, 0, 0.767);">{{ $sections['name'] }}</th>
                    @endforeach
                    <th style="min-width:90px;border:1px solid rgba(0, 0, 0, 0.767);"></th>
                </tr>
                <tr>
                    @foreach ($enroll['data'] as $sectionRep)
                        @php
                            $strength += $sectionRep['students'];
                            $absent += count($sectionRep['absentlist']);
                            $leave += count($sectionRep['leavelist']);
                            $od += count($sectionRep['odlist']);
                        @endphp
                        <td style="border:1px solid rgba(0, 0, 0, 0.767);">
                            @if (count($sectionRep['absentlist']) > 0 || count($sectionRep['leavelist']) > 0 || count($sectionRep['odlist']) > 0)
                                <div>
                                    <p style="padding-left:5px;"><b>Absent </b></p>
                                    @if (count($sectionRep['absentlist']) > 0)
                                        <div style="padding-left:5px;">
                                            @php
                                                $absentlist = $sectionRep['absentlist'];
                                            @endphp
                                            @foreach ($absentlist as $students)
                                                {{ $students['student'] }},
                                            @endforeach
                                        </div>
                                    @endif
                                    <div style="height:1px;border-bottom:1px solid rgba(0, 0, 0, 0.767);"></div>
                                    <p style="padding-left:5px;"><b>Leave </b></p>
                                    @if (count($sectionRep['leavelist']) > 0)
                                        <div style="padding-left:5px;">
                                            @php
                                                $leavelist = $sectionRep['leavelist'];
                                            @endphp
                                            @foreach ($leavelist as $students)
                                                {{ $students['student'] }},
                                            @endforeach
                                        </div>
                                    @endif
                                    <div style="height:1px;border-bottom:1px solid rgba(0, 0, 0, 0.767);"></div>
                                    <p style="padding-left:5px;"><b>OD </b></p>
                                    @if (count($sectionRep['odlist']) > 0)
                                        <div style="padding-left:5px;">
                                            @php
                                                $odlist = $sectionRep['absentlist'];
                                            @endphp
                                            @foreach ($odlist as $students)
                                                {{ $students['student'] }},
                                            @endforeach
                                        </div>
                                    @endif
                                    <div style="height:1px;border-bottom:1px solid rgba(0, 0, 0, 0.767);"></div>
                                    <div style="padding-left:5px;"><strong>Total Leave & Absent  :  </strong> <span style="padding-left:5px;">{{ count($sectionRep['absentlist']) + count($sectionRep['leavelist']) }}</span></div>
                                    <div style="height:1px;border-bottom:1px solid rgba(0, 0, 0, 0.767);"></div>
                                    <div style="padding-left:5px;"><strong>Total OD  : </strong> <span style="padding-left:5px;"> {{ count($sectionRep['odlist']) }}</span></div>
                                    <div style="height:1px;border-bottom:1px solid rgba(0, 0, 0, 0.767);"></div>
                                    <p style="padding-left:5px;"><strong>Faculty Sign : </strong> </p>
                                </div>
                            @else
                                <div style="text-align:center;">Not Yet Taken</div>
                            @endif
                        </td>
                    @endforeach
                    @php
                        if ($absent > 0 || $od > 0 || $leave > 0) {
                            $present = $strength - ($absent + $od + $leave);
                        }
                    @endphp
                    <td style="border:1px solid rgba(0, 0, 0, 0.767);padding-left:5px;">
                        <p><strong>Strength : </strong> {{ $strength }}</p>
                        <p><strong>Present : </strong> {{ $present }}</p>
                        <p><strong>Leave : </strong> {{ $leave }}</p>
                        <p><strong>OD : </strong> {{ $od }}</p>
                        <p><strong>Absent : </strong> {{ $absent }}</p>
                    </td>
                </tr>
            </table>
            @php
                $strength = 0;
                $present = 0;
                $absent = 0;
                $leave = 0;
                $od = 0;
            @endphp
        @endforeach
        <div style="text-align:right;">
            <p style="padding-top:1rem;padding-right:5px;"> <b>HOD Sign</b></p>
        </div>
    @endif
</body>

</html>
