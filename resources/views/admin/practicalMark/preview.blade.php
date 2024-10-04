@extends('layouts.admin')
@section('content')
    <div class="card">
        @php
            if ($detail['data'] != null) {
                $count = count($detail['data']);
                $pageCount = ceil($count / 35);
                $data = $detail['data'];
            }
            $start = 0;
            if ($count > 35) {
                $stop = 35;
            } else {
                $stop = $count;
            }
        @endphp
        <div class="card-header text-right">
            <a class="enroll_generate_bn bg-success" href="{{ route('admin.practical-marks.print') }}">Print</a>
        </div>
        <div class="card-body">
            @for ($i = 0; $i < $pageCount; $i++)
                <div class="card">
                    <div class="card-body" style="position:relative">
                        <img src="{{ asset('adminlogo/school_menu_logo.png') }}" alt="Image Description"
                            style="position:absolute;width:200px;left:30px;top:30px;">

                        <div class="text-center">
                            <b>Demo College Of Engineering & Technology</b>
                        </div>
                        <div class="text-center">(An Autonomous Insitution and Affliated to Anna University, Chennai)</div>
                        <div class="text-center">Kuthambakkam - 600124</div>
                        <div class="mt-3 mb-1 text-center"><b>END SEMESTER PRACTICAL EXAMINATIONS -
                                {{ strtoupper($detail['exam_month']) }}
                                {{ $detail['exam_year'] }}</b></div>
                        <div class="row ">
                            <div class="col-6 pl-5">
                                <p><b>Branch : </b> {{ $detail['course']['name'] }}</p>
                            </div>
                            <div class="col-6 pl-5">
                                <p><b>Date & Time : </b> </p>
                            </div>
                            <div class="col-6 pl-5">
                                <p><b>Semester : </b> 0{{ $data[0]['subject_sem'] }}</p>
                            </div>
                            <div class="col-6 pl-5">
                                <p><b>Exam Type : </b> {{ $detail['exam_type'] }}</p>
                            </div>
                            <div class="col-6 pl-5">
                                <p><b>Subject Code & Name : </b> {{ $detail['subject']['subject_code'] }} &
                                    {{ $detail['subject']['name'] }}</p>
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Register Number</th>
                                        <th>Marks Awarded</th>
                                        <th>Mark in Words</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($j = $start; $j < $stop; $j++)
                                        <tr>
                                            <td>{{ $j + 1 }}</td>
                                            <td>{{ $data[$j]['student']['register_no'] }}</td>
                                            <td>{{ $data[$j]['mark'] == -1 ? 'Absent' : $data[$j]['mark'] }}</td>
                                            <td>{{ $data[$j]['mark_in_word'] }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                        <div class="row p-3 text-center">
                            <div class="col-6">Internal Examiner</div>
                            <div class="col-6">External Examiner</div>
                        </div>
                    </div>
                </div>
                @php
                    $start = $stop;
                    $calculate = $count - $stop;
                    if ($calculate >= 35) {
                        $stop += 35;
                    } else {
                        $stop += $calculate;
                    }

                @endphp
            @endfor
        </div>
    </div>
@endsection
