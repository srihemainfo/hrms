<!DOCTYPE html>
<html>

<head>
    <style>

    </style>
</head>

<body>
    @if (count($lessons) > 0)
        <div style="margin-top:1rem;">
            <div>
                <p style="margin-top:3px;"> Lesson Plans</p>
                <div style="margin-bottom:3px;">
                    <span>Class : {{ $short_form }}</span>

                </div>
                <div style="margin-bottom:3px;">
                    <span>Subject : {{ $get_subject->name }} ({{ $get_subject->subject_code }})</span>
                </div>
            </div>
            <div id="lesson_plan">
                @php
                    $no = 1;
                @endphp
                @foreach ($lessons as $data)
                    <div style="padding-top:20px;">
                        <div style="margin-bottom:3px;">
                            <span>Unit No : {{ $no }}</span>
                        </div>
                        <div style="margin-bottom:3px;">
                            <span>Unit : {{ $data[0]->unit }}</span>
                        </div>
                    </div>
                    @foreach ($data as $i => $datas)
                        <hr style="margin-top:0;">
                        <table style="padding-left:1rem;margin-top:-5px;" class="table_one">
                            <tr>
                                <td>Proposed Date</td>
                                <td style="padding-left:1.5rem;"><span
                                        style="padding-right:1rem;">:</span>{{ $datas->proposed_date }}
                                </td>
                            </tr>
                            <tr>
                                <td>Topic - {{ $i + 1 }}</td>
                                <td style="padding-left:1.5rem;"><span
                                        style="padding-right:1rem;">:</span>{{ $datas->topic }}
                                </td>
                            </tr>
                            <tr>
                                <td>Text Books / Reference</td>
                                <td style="padding-left:1.5rem;"><span
                                        style="padding-right:1rem;">:</span>{{ $datas->text_book }}
                                </td>
                            </tr>
                            <tr>
                                <td>Delivery Methods</td>
                                <td style="padding-left:1.5rem;"><span
                                        style="padding-right:1rem;">:</span>{{ $datas->delivery_method }}</td>
                            </tr>
                        </table>
                    @endforeach
                    <hr style="margin-top:0;">
                    @php
                        $no++;
                    @endphp
                @endforeach
            </div>
        </div>
    @endif
</body>

</html>
