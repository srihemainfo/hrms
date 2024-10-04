<!DOCTYPE html>
<html>

<head>

</head>
<style>
    .body{
        font-family: "Times New Roman", Times, serif;
    }
</style>
<body>
            @php
            if (isset($getData->date) && $getData->date) {
                $given_date = $getData->date;
                $dateTime = DateTime::createFromFormat('Y-m-d', $given_date);
                $formattedDate = $dateTime->format('d-m-Y');
            } else {
                $formattedDate = '';
            }
        @endphp
    <div style="padding:10px;padding-top:220px;">
        <div style="text-align:right;font-size:1rem;"><b>Date : {{$formattedDate}}</b></div>
        <div style="text-align:center;padding-top:60px;font-size:1.2rem;"><b style="text-decoration: underline;">{{ isset($getData->certificate) ? $getData->certificate : '' }}  CERTIFICATE</b></div>
        <div style="text-indent: 25px; line-height: 1.8; padding-top:40px;font-size:1rem;">
            This is to certify that <b> {{ isset($getData->stu_front) ? $getData->stu_front : '' }}
                {{ isset($getData->name) ? $getData->name : '' }}.</b> Register No :
            <b>{{ isset($getData->register_no) ? $getData->register_no : '' }}</b>,
            <b>{{ isset($getData->gender) ? $getData->gender : '' }} Mr.
                {{ isset($getData->father_name) ? $getData->father_name : '' }}</b>, is
            a bonafide student of our college studying in {{ isset($getData->year) ? $getData->year : '' }}
            {{ isset($getData->degree) ? $getData->degree : '' }} Degree course in
            {{ isset($getData->course) ? $getData->course : '' }} during the academic year
            {{ isset($getData->ay) ? $getData->ay : '' }}.
        </div>
        <div style="padding-top:35px;text-align:left;font-size:1rem; line-height: 1.6;">
            <b>
                This Certificate is issued to enable {{ isset($getData->stu_gen) ? $getData->stu_gen : '' }} to
                apply for
                {{ isset($getData->purpose) ? $getData->purpose : '' }}. {{$getData->message ?? ''}}
            </b>
        </div>
        <div style="text-align:right;padding-top:150px;font-size:1.3rem;">
            <b style="padding-top:30px;">Principal</b>
        </div>
    </div>
</body>
</html>
