@php
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 360);
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>
        Hall Tickets
    </title>
</head>

<body>

    @foreach ($datas as $data)
        @php

            $subjectCount = count($data);
            $rightSide = false;
            $rightSideCount = 0;
            $theleft = 0;
        @endphp
        <div style="height:1025px;">
            <div id="hollticket_div" style="position:relative;">
                <div>
                    @php
                        if ($data[0]['personal_details']['dob'] != null) {
                            $date = new DateTime($data[0]['personal_details']['dob']);

                            $formattedDate = $date->format('d-m-Y');
                        } else {
                            $formattedDate = '';
                        }

                        if ($data[0]['profile'] != null) {
                            $image = public_path($data[0]['profile']['filePath']);
                        } else {
                            $image = '';
                        }
                    @endphp
                    <div style="z-index:99;">
                        <table style="width:100%;border:1px solid black;border-collapse:collapse;font-size:0.8rem;">
                            <tr>
                                <td style="width:20%;border-right:1px solid black;">
                                    <img src="{{ public_path('adminlogo/logoForPdf.png') }}"
                                        style="width:100%;height:80px;" alt="Institute Logo">
                                </td>
                                <td style="width:60%;border-right:1px solid black;font-size:0.7rem;text-align:center;">
                                    <div> <b style="font-size:0.9rem;"> Demo College Of Engineering & Technology </b></div>
                                    <div> <b> (An AUTONOMOUS Institution, Affiliated to ANNA UNIVERSITY, Chennai.) </b>
                                    </div>
                                    <div> <b> KUTHAMBAKKAM, CHENNAI – 600124. </b></div>
                                    <div> <b> End Semester Examinations – November / {{ $data[0]['exam_month'] }}
                                            {{ $data[0]['exam_year'] }}</b></div>
                                    <div> <b> HALL TICKET (Page 1/1) </b></div>
                                </td>
                                <td rowspan="2" colspan="2" style="width:20%;">
                                    <div style="position:relative;">
                                        <div style="text-align:center;position:absolute;top:2%;left:7%">Photo of the
                                            Candidate</div>
                                        <img src="{{ $image }}"
                                            style="margin-left:15%;margin-top:40px;width:70%;height:90px;"
                                            alt="Student image">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-top:1px solid black;border-right:1px solid black;">
                                    <table style="width:100%;border-collapse:collapse;font-size:0.7rem;">
                                        <tr>
                                            <td
                                                style="padding-left:5px;width:20%;border-right:1px solid black;border-bottom:1px solid black;">
                                                Register Number</td>
                                            <td
                                                style="padding-left:5px;width:40%;border-right:1px solid black;border-bottom:1px solid black;">
                                                {{ $data[0]['student']['register_no'] }}</td>
                                            <td
                                                style="padding-left:5px;width:20%;border-right:1px solid black;border-bottom:1px solid black;">
                                                Current Semester</td>
                                            <td style="padding-left:5px;width:20%;border-bottom:1px solid black;">
                                                0{{ $sem }}</td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="padding-left:5px;width:20%;border-right:1px solid black;border-bottom:1px solid black;">
                                                Name</td>
                                            <td
                                                style="padding-left:5px;width:40%;border-right:1px solid black;border-bottom:1px solid black;">
                                                {{ strtoupper($data[0]['student']['name']) }}</td>
                                            <td
                                                style="padding-left:5px;width:20%;border-right:1px solid black;border-bottom:1px solid black;">
                                                DOB</td>
                                            <td style="padding-left:5px;width:20%;border-bottom:1px solid black;">
                                                {{ $formattedDate }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1"
                                                style="padding-left:5px;width:20%;border-right:1px solid black;border-bottom:1px solid black;">
                                                Degree & Branch</td>
                                            <td colspan="3" style="padding-left:5px;border-bottom:1px solid black;">
                                                {{ $data[0]['courses']['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1"
                                                style="padding-left:5px;width:20%;border-right:1px solid black;">
                                                Examination Centre</td>
                                            <td colspan="3" style="padding-left:5px;">2117 : Demo INSTITUTE OF
                                                TECHNOLOGY</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="border:1px solid black;border-bottom:0px solid black;">
                                    <table style="width:100%;border-collapse:collapse;">
                                        <tr>
                                            <td style="width:50%;border-right:1px solid black;">
                                                <table style="width:100%;border-collapse:collapse;">
                                                    <tr>
                                                        <td
                                                            style="width:10%;padding-left:5px;border-bottom:1px solid black;text-align:center;">
                                                            Sem
                                                        </td>
                                                        <td
                                                            style="width:30%;padding-left:5px;border-bottom:1px solid black;text-align:center;">
                                                            Subject Code
                                                        </td>
                                                        <td
                                                            style="width:60%;padding-left:5px;border-bottom:1px solid black;">
                                                            Subject Name
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    <td colspan="3" style="height:600px;position:relative;" >
                                                            <table style="width:100%;position:absolute;top:0;">
                                                            @php
                                                            if(count($data) > 15){
                                                                $count = 15;
                                                            }else{
                                                                $count = count($data);
                                                            }
                                                            @endphp
                                                        
                                                            @for ($i = 0; $i < $count; $i++)
                                                                @if ($i < 15)
                                                                        <tr>
                                                                            <td
                                                                                style="width:10%;padding-left:5px;height:30px;text-align:center;">
                                                                                {{ $data[$i]['subject_sem'] }}</td>
                                                                            <td
                                                                                style="width:30%;padding-left:5px;text-align:center;">
                                                                                {{ $data[$i]['subject']['subject_code'] }}
                                                                            </td>
                                                                            <td style="width:60%;padding-left:5px;">
                                                                                {{ $data[$i]['subject']['name'] }}
                                                                            </td>
                                                                        </tr>
                                                                @else
                                                                    @php
                                                                    break;
                                                                    @endphp
                                                                @endif
                                                            @endfor
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="width:50%;">
                                                <table style="width:100%;border-collapse:collapse;">
                                                    <tr>
                                                        <td
                                                            style="height:15px;width:10%;padding-left:5px;border-bottom:1px solid black;text-align:center;">
                                                            Sem
                                                        </td>
                                                        <td
                                                            style="height:15px;width:30%;padding-left:5px;border-bottom:1px solid black;text-align:center;">
                                                            Subject Code
                                                        </td>
                                                        <td
                                                            style="height:15px;width:60%;padding-left:5px;border-bottom:1px solid black;">
                                                            Subject Name
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="height:600px;position:relative;" >
                                                            <table style="width:100%;position:absolute;top:0;">
                                                                
                                                                    @for ($j = 15; $j < count($data); $j++)
                                                                    <tr>
                                                                            <td
                                                                                style="width:10%;padding-left:5px;height:30px;text-align:center;">
                                                                                {{ $data[$j]['subject_sem'] }}</td>
                                                                            <td
                                                                                style="width:30%;padding-left:5px;text-align:center;">
                                                                                {{ $data[$j]['subject']['subject_code'] }}
                                                                            </td>
                                                                            <td style="width:60%;padding-left:5px;">
                                                                                {{ $data[$j]['subject']['name'] }}
                                                                            </td>
                                                                        </tr>
                                                                    @endfor
                                                                
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr style="font-size:0.8rem;">
                                <td colspan="4" style="border:1px solid black;padding-left:10px;">
                                    <b>No of Subjects Registered: <span>{{ $subjectCount }}</span> </b>
                                    (Page 1/1 Hall Ticket contain per Page Maximum 30 Subjects only)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="border:1px solid black;">
                                    <div>
                                        <div style="padding:4px;">NOTE : </div>

                                        <div style="padding:4px;">
                                            1. Correction in the Name / Date of Birth and missing of Photograph or
                                            incorrect Photograph, if any is to be updated in the IMS Portal when it is
                                            opened for correction.
                                        </div>
                                        <div style="padding:4px;">2. Instructions printed overleaf are to be followed
                                            strictly.</div>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="font-size:0.8rem;">
                                    <table style="width:100%;">
                                        <tr>
                                            <td style="padding-top:100px;padding-left:5px;">Signature of the Candidate
                                            </td>
                                            <td style="padding-top:100px;text-align:center;">Signature of the Principal
                                                with seal</td>
                                            <td style="padding-top:100px;padding-right:5px;text-align:right;">Controller
                                                of Examinations</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <img src="{{ public_path('adminlogo/logoForPdf.png') }}"
                    style="margin-left: 35%;width: 30%;height: 100px;position: absolute;top: 40%;z-index: 0;opacity:0.1;"
                    alt="Institute Logo">
            </div>
            <div style="text-align:right;font-size:0.8rem;">Page(1/1)</div>
        </div>
        <div style="height:1025px;font-size:0.8rem;position: relative;">
            <div style="padding:3px;height:98%;border:1px solid black;position: relative;">
                <div style="text-align:center;padding-bottom:10px;font-size:0.9rem;text-decoration:underline;">
                    INSTRUCTIONS TO THE CANDIDATE</div>
                <div style="padding-bottom:8px;">1. Admission to the Examination is provisional.</div>
                <div style="padding-bottom:8px;">2. The Hall Ticket is issued subject to the candidate satisfying the
                    Attendance and other requirements as
                    per Rules, Regulations and Instructions by the Demo College Of Engineering & Technology from time to
                    time. If
                    later, it is found that the candidate fails to comply with the above requirements, the examinations
                    written by the candidate will be treated as cancelled.</div>
                <div style="padding-bottom:8px;">3. A seat marked with Register number will be provided to each
                    candidate. Candidate will occupy the
                    allotted seat at least 10 minutes before the commencement of the examination. In no case,candidate
                    shall
                    be allowed to occupy a seat other than the seat allotted to him.</div>
                <div style="padding-bottom:8px;">4. Normally the Candidate will not be permitted to enter the hall after
                    the commencement of the
                    examination. Only on extraordinary circumstances, the candidates will be permitted during the first
                    thirty minutes of the examination after obtaining the written permission from the Principal/COE.
                    Under
                    any circumstances the Candidate shall not be permitted to enter the hall after the expiry of first
                    thirty minutes.</div>
                <div style="padding-bottom:8px;">5. Candidate shall not be allowed to leave the examination hall before
                    the expiry of 45 minutes from the
                    commencement of examination. The candidate who leaves the examination hall during the period
                    allotted
                    for a paper will not be allowed to re-enter the hall within that period.</div>
                <div style="padding-bottom:8px;">6. Candidate who is suffering from infectious diseases of any kind
                    shall
                    not be admitted to the
                    examination hall.</div>
                <div style="padding-bottom:8px;">7. Candidate is strictly prohibited from smoking inside the examination
                    hall.</div>
                <div style="padding-bottom:8px;">8. Strict silence should be maintained in the examination hall.</div>
                <div style="padding-bottom:8px;">9. Candidate is required to bring his/her own pens, pencils and
                    erasers.
                    Candidate should use only blue
                    or black ink while answering his/her papers.</div>
                <div style="padding-bottom:8px;">10. Before proceeding to answer the paper, the candidate should write
                    his/her register number, semester,
                    subject and date of the examination at the appropriate space provided in the first page of the
                    answer
                    book and nowhere else in the answer book or in any additional attachment like drawing sheet, smith
                    chart
                    etc.</div>
                <div style="padding-bottom:8px;">11. If a candidate writes his/her register number on any part of the
                    answer book/sheets other than the
                    one provided for or puts any special mark or writes anything which may disclose, in any way, the
                    identity of the Candidate/College, he/she will render himself/herself liable for disciplinary
                    action.</div>
                <div style="padding-bottom:8px;">12. Writing of wrong register number in the answer book will entail
                    rejection of the answer book.</div>
                <div style="padding-bottom:8px;">13. Candidate is not allowed to exceed the prescribed time assigned to
                    each paper.</div>
                <div style="padding-bottom:8px;">14. Candidate shall not talk/ask questions of any kind during the
                    examination.</div>
                <div style="padding-bottom:8px;">15. Candidate shall not carry any written/printed matter, any paper
                    material, cell phone, smart watch, pen drive,
                    ipad, programmable calculator, any unauthorized data sheet/table into the examination hall and if
                    anything is found in his/her possession his/her shall be liable for disciplinary action.</div>
                <div style="padding-bottom:8px;">16. No Candidate shall pass any part or whole of answer papers or
                    question papers to any other candidate.
                    No candidate shall allow another candidate to copy from his/her answer paper or copy from the answer
                    paper of another candidate. If found committing such malpractice, the involved candidates shall be
                    liable for disciplinary action.</div>
                <div style="padding-bottom:8px;">17. Candidate found guilty of using unfair means of any nature shall
                    be
                    liable for disciplinary action.</div>
                <div style="padding-bottom:8px;">16. Candidate will have to hand over the answer book to the
                    Invigilator
                    before leaving the examination
                    hall.</div>
                <div style="padding-bottom:8px;">19. Candidate should produce the hall ticket on demand by the
                    Invigilator/COE/Representatives/ Squad
                    members.</div>
                <div style="padding-bottom:8px;">20. Candidate shall not write anything in the Hall Ticket.</div>

                <div style="padding-bottom:8px;">21. Candidate shall write only the Register Number in the space
                    provided in the Question Paper. Any
                    other
                    writings in the Question Paper are prohibited and punishable.</div>
                <div
                    style="padding-top:10px;padding-right:10px;text-align:right;position: absolute;bottom:10px;right:10px;">
                    CONTROLLER OF EXAMINATIONS </div>
            </div>
            <img src="{{ public_path('adminlogo/logoForPdf.png') }}"
                style="margin-left: 35%;width: 30%;height: 100px;position: absolute;top: 40%;z-index: 0;opacity:0.1;"
                alt="Institute Logo">
            <div style="text-align:right;">Page(1/2)</div>
        </div>
    @endforeach
</body>

</html>
