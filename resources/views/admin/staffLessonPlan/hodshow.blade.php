@extends('layouts.admin')
@section('content')
    {{-- {{ dd($lessons) }} --}}
    <a class="btn btn-default" href="{{ route('admin.staff-lesson-plan.index', ['status' => 0]) }}">
        {{ trans('global.back_to_list') }}
    </a>
    @if (count($lessons) > 0)
        <div class="card" style="margin-top:1rem;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <label>Staff</label>
                        <span class="manual_bn">{{ $name }} ({{ $staff_code }})</span>
                    </div>
                    <div class="col-md-3 col-6">
                        <label>Class</label>
                        <span class="manual_bn">{{ $short_form }}</span>
                    </div>
                    <div class="col-md-6 col-6">
                        <label>Subject</label>
                        <span class="manual_bn">{{ $get_subject->name }} ({{ $get_subject->subject_code }})</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="lesson_plan">
            <div class="card-header">
                <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> Lesson Plans</h5>
            </div>
            <div class="card-body">
                @php
                    $no = 1;
                    $all_period_count = 0;
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
                                @php
                                    $period_count = 0;
                                @endphp
                                @foreach ($data as $datas)
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
                                                <div>Topic</div>
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
                                    @php
                                        $period_count++;
                                        $all_period_count++;
                                    @endphp
                                @endforeach
                                <hr style="margin-top:0;">
                                <div class="form-group">
                                    Proposed Dates :  <b>{{ $period_count }}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $no++;
                    @endphp
                @endforeach
                <div class="row">
                   <div class="col-md-6 col-12" style="padding-left:1.6rem;">
                    Total Proposed Dates :  <b>{{ $all_period_count }}</b>
                   </div>
                   <div class="col-md-6 col-12">
                     @foreach ($lessons as $data)
                         @if ($data[0]->status == 0)
                             <div style="text-align:right;">
                                 <input type="hidden" id="user_name_id" value="{{ $user_name_id }}">
                                 <input type="hidden" id="enroll" value="{{ $data[0]->class }}">
                                 <input type="hidden" id="subject" value="{{ $data[0]->subject }}">
                                 <span class="btn btn-info" onclick="reject()">
                                     Need Revision
                                 </span>
                                 <span class="btn btn-success" onclick="approve()">
                                     Approve
                                 </span>
                             </div>
                            @break
                         @elseif (auth()->user()->id == 1 && $data[0]->status == 1)
                            <div style="text-align:right;">
                                <input type="hidden" id="user_name_id" value="{{ $user_name_id }}">
                                <input type="hidden" id="enroll" value="{{ $data[0]->class }}">
                                <input type="hidden" id="subject" value="{{ $data[0]->subject }}">
                                <span class="btn btn-info" onclick="reject()">
                                    Need Revision
                                </span>
                            </div>
                            @break
                         @endif
                     @endforeach
                   </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
<script>
    function reject() {
        let user_name_id = $("#user_name_id").val();
        let class_name = $("#enroll").val();
        let subject = $("#subject").val();


        Swal.fire({
            title: 'Revision Reason',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: (username) => {

                let data = {
                    'class': class_name,
                    'subject': subject,
                    'status': 2,
                    'rejected_reason': username
                };
                $.ajax({
                    url: '{{ route('admin.staff-lesson-plan.action') }}',
                    type: 'POST',
                    data: {
                        'data': data,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        Swal.fire(
                            'Done!',
                            'The Lesson Plans Sent For Revision!',
                            'success'
                        )
                        window.location.href = "{{ url('admin/staff-lesson-plan/index/0') }}";
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr.responseText);
                    }
                });

            },
            allowOutsideClick: () => !Swal.isLoading()
        })

    }

    function approve() {
        let user_name_id = $("#user_name_id").val();
        let class_name = $("#enroll").val();
        let subject = $("#subject").val();

        let data = {
            'class': class_name,
            'subject': subject,
            'status': 1
        };

        $.ajax({
            url: '{{ route('admin.staff-lesson-plan.action') }}',
            type: 'POST',
            data: {
                'data': data,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                Swal.fire(
                    'Done!',
                    'You Approved the Lesson Plans!',
                    'success'
                )
                window.location.href = "{{ url('admin/staff-lesson-plan/index/0') }}";
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr.responseText);
            }
        });

    }
</script>
@endsection
