@php
    if (auth()->user()->roles[0]->id == 11) {
        $layout = 'layouts.studentHome';
    } elseif (auth()->user()->roles[0]->id == 27) {
        $layout = 'layouts.non_techStaffHome';
    } else {
        $layout = 'layouts.admin';
    }
@endphp
@extends($layout);
@section('content')
    @if (auth()->user()->roles[0]->id == 11)
        <a class="btn btn-default mb-3" href="{{ route('admin.student-apply-certificate.stu_index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    @else
        <a class="btn btn-default mb-3" href="{{ route('admin.certificate-provision.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    @endif
    @php
    if (isset($getData->date) && $getData->date) {
        $given_date = $getData->date;
        $dateTime = DateTime::createFromFormat('Y-m-d', $given_date);
        $formattedDate = $dateTime->format('d-m-Y');
    } else {
        $formattedDate = '';
    }
    @endphp
    <div class="card">
        <div class="card-body">
            <div class="row" style="padding:10px;border:2px solid #b3b3b3;border-radius:3px;">
                <div class="col-12 text-right"><b>Date : {{$formattedDate }}</b></div>
                <div class="col-12 text-center"><b style="font-size:1.5rem;text-decoration: underline;">{{ isset($getData->certificate) ? $getData->certificate : '' }}
                        CERTIFICATE</b></div>
                <div class="col-12" style="text-indent:25px;padding-top:30px; line-height: 1.6;">
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
                <div class="col-12 text-center" style="padding-top:30px;"><b>
                        This Certificate is issued to enable {{ isset($getData->stu_gen) ? $getData->stu_gen : '' }} to
                        apply for {{ isset($getData->purpose) ? $getData->purpose : '' }}. {{$getData->message}}</b>
                </div>
                <div class="col-12 text-right">
                    <b style="font-size:1.2rem;" style="padding-top:30px;">Principal</b>
                </div>
            </div>
        </div>
    </div>
@endsection
