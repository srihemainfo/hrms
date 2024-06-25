<!DOCTYPE html>
<html>

<body>
    <table style="width:100%;margin-bottom:10px;">
        <tr style="text-align:center;">
            <th colspan="3">SYLLABUS COMPLETION REPORT</th>
        </tr>
        <tr>
            <td><strong>Academic Year :</strong> {{ $newObj->accademicYear ?? '' }}</td>
            <td><strong>Department :</strong> {{ $newObj->department ?? '' }}</td>
            <td><strong>Course :</strong> {{ $newObj->course ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Year :</strong> {{ $newObj->Year ?? '' }}</td>
            <td><strong>Semester :</strong> {{ $newObj->sem ?? '' }}</td>
            <td><strong>Section :</strong> {{ $newObj->section ?? '' }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>Subject Faculty :</strong> {{ $newObj->name ?? '' }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>Subject :</strong> {{ $newObj->subName ?? '' }}</td>

        </tr>
    </table>
    <table border="1" style="text-align:center;width:100%;margin-bottom:10px;">
        @foreach ($lessonplan as $lessonplanes)
            <tr>
                <th colspan="5">

                    Unit : {{ $lessonplanes->unit_no ?? '' }}
                    <span style="margin-left: 15px"> Title :
                        {{ $lessonplanes->unit ?? '' }}</span>
                </th>
            </tr>
            <tr>
                <th><strong>Proposed Date</strong></th>
                <th><strong>Topic</strong></th>
                <th><strong>Text/Ref</strong></th>
                <th><strong>Delivery</strong></th>
                <th><strong>Handled Date</strong></th>
            </tr>
            @foreach ($lessonplanes->lesTopic as $topic)
                <tr>
                    <td>{{ isset($topic->proposed_date) ? $topic->proposed_date : '' }} </td>
                    <td>{{ isset($topic->topic) ? $topic->topic : '' }}</td>
                    <td>{{ isset($topic->text_book) ? $topic->text_book : '' }} </td>
                    <td>{{ isset($topic->delivery_method) ? $topic->delivery_method : '' }} </td>
                    <td> {{ isset($topic->attendedperiod) ? $topic->attendedperiod : '' }} </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" rowspan="2"> Unit :
                    {{ $lessonplanes->unit_no ?? '' }} Total</td>
                <td><strong>Proposed Periods</strong></td>
                <td><strong>Handled Periods</strong></td>
            </tr>
            <tr>
                <td>{{ $topic->unitPeriods ?? '' }}</td>
                <td>{{ $topic->conducted ?? '' }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2"><strong> Total Proposed Periods </strong></th>
            <th colspan="2"><strong>Total Handled Periods</strong></th>
            <th colspan="1"><strong>Completion Percentage</strong></th>
        </tr>
        <tr>
            <td colspan="2">{{ $totalProposed ?? '0' }}</td>
            <td colspan="2">{{ $totalConducted ?? '0' }}</td>
            <td colspan="1">{{ $totalPercentage ?? '0' }}</td>
        </tr>

    </table>
    <table border="1" style="text-align:center;width:100%;">
        <thead>
            <tr>
                <th colspan="4">
                    <strong>Others</strong>
                </th>
            </tr>
            <tr>
                <th><strong>S.No</strong></th>
                <th><strong>Date</strong></th>
                <th><strong>Topic Name</strong></th>
                <th><strong>Remarks</strong></th>

            </tr>
        </thead>
        <tbody>
            @if (count($getOthers) > 0)
                @foreach ($getOthers as $i => $other)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $other->actual_date }}</td>
                        <td>{{ $other->unit }}</td>
                        <td>{{ $other->topic }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">No Data Available...</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
