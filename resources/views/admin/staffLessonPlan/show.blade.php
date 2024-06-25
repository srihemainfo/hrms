@extends('layouts.teachingStaffHome')
@section('content')
    {{-- {{ dd($lessons) }} --}}
    <a class="btn btn-default"
        href="{{ route('admin.staff-subjects.lesson-plan', ['user_name_id' => $user_name_id, 'name' => $name, 'status' => 0]) }}">
        {{ trans('global.back_to_list') }}
    </a>
    @if (count($lessons) > 0)
        <div class="card" style="margin-top:1rem;">
            <div class="card-body row text-center">
                <div class="col-md-3 col-12">
                    <label>Class</label><br>
                    <span class="manual_bn">{{ $short_form }}</span>
                </div>
                <div class="col-md-7 col-12">
                    <label>Subject</label><br>
                    <span class="manual_bn">{{ $get_subject->name }} ({{ $get_subject->subject_code }})</span>
                </div>
                <div class="col-md-2 col-12">
                    <label>Download As PDF</label><br>
                    <a class="manual_bn bg-info" target="blank" href="{{ URL::to('admin/staff-subjects/lesson-plan/download-pdf/' . $enroll.'/'.$subject.'/'.$status) }}">
                       Download
                    </a>
                </div>
            </div>
            <div class="card" id="lesson_plan">
                <div class="card-header">
                    <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> Lesson Plans</h5>
                </div>
                <div class="card-body">
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($lessons as $data)
                        <div id="units">
                            <div class="card add_color" id="">
                                <div class="card-body">
                                    <div class="row gutters">
                                        <div class="col-md-10 col-9">
                                            <div class="form-group">
                                                <span>Unit : </span>
                                                <b>{{ $data[0]->unit }}</b>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-3"> <span class="manual_bn">Unit No :
                                                {{ $no }}</span></div>
                                    </div>
                                    @foreach ($data as $i => $datas)
                                        <hr style="margin-top:0;">
                                        <div class="row gutters">
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <div>Proposed Date</div>
                                                    <label>{{ $datas->proposed_date }}</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div>Topic - {{ $i +1 }}</div>
                                                    <label>{{ $datas->topic }}</label>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <div>Text Books / Reference</div>
                                                    <label>{{ $datas->text_book }}</label>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <div>Delivery Methods</div>
                                                    <label>{{ $datas->delivery_method }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                </div>
            </div>
    @endif
@endsection
