@php
   $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    }else{
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    {{-- {{ dd($newObj) }} --}}
    <div class="card">
        <div class="card-header text-center">
            <strong>SYLLABUS COMPLETION REPORT</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4"><strong>Academic Year :</strong> {{ $newObj->accademicYear ?? '' }}
                </div>
                <div class="col-12 col-sm-6 col-md-4"><strong>Department :</strong> {{ $newObj->department ?? '' }}</div>
                <div class="col-12 col-sm-6 col-md-4"><strong>Course :</strong> {{ $newObj->course ?? '' }}</div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4"><strong>Year :</strong> {{ $newObj->Year ?? '' }}</div>
                <div class="col-12 col-sm-6 col-md-4"><strong>Semester :</strong> {{ $newObj->sem ?? '' }}</div>
                <div class="col-12 col-sm-6 col-md-4"><strong>Section :</strong> {{ $newObj->section ?? '' }}</div>
            </div>
            <div class="" style="margin-left: -8px">
                <div class="col-12 col-sm-6"><strong>Subject Faculty :</strong> {{ $newObj->name ?? '' }}</div>
                <div class="col-12 col-sm-6"><strong>Subject :</strong> {{ $newObj->subName ?? '' }}</div>
            </div>
        </div>

    </div>
    <div class="card ">
        <div class="card-body">

            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div style="max-height: 500px; overflow-y: auto;">

                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        @foreach ($lessonplane as $lessonplanes)
                                            <thead>
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
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($lessonplanes->lesTopic as $topic)
                                                    <tr>
                                                        <td>{{ isset($topic->proposed_date) ? $topic->proposed_date : '' }}
                                                        </td>
                                                        <td>{{ isset($topic->topic) ? $topic->topic : '' }}</td>
                                                        <td>{{ isset($topic->text_book) ? $topic->text_book : '' }}</td>
                                                        <td>{{ isset($topic->delivery_method) ? $topic->delivery_method : '' }}
                                                        </td>
                                                        <td> {{ isset($topic->attendedperiod) ? $topic->attendedperiod : '' }}
                                                        </td>
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
                                            <th colspan="2"><strong>Completion Percentage</strong></th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">{{ $totalProposed ?? '0' }}</td>
                                            <td colspan="2">{{ $totalConducted ?? '0' }}</td>
                                            <td colspan="2">{{ $totalPercentage ?? '0' }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div style="max-height: 500px; overflow-y: auto;">

                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">

                                        <thead>
                                            <tr>
                                                <th colspan="5">
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
                                        <tbody class="text-center">
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
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
