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
    <style>
          input.mark {
            background: unset;
            border: 0;
            width: 100px;
        }

        input.mark:focus-visible {
            border: 0;
            outline: 0;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header text-center">
            <strong>Edit Mark</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark">

                    @php
                    $totalCount = count($coMarks) - 1;
                    $available = 5 - $totalCount;
                    $colSpan = 8 / ($available == 0 ? 1 : $available);

                    if ($colSpan <= 2) {
                        $colSpan = 3;
                    }
                    if ($colSpan >= 4) {
                        $colSpan = 2;
                    }
                    // dd($colSpan);
                @endphp
                    <thead>
                        <tr>
                            <th colspan="4">Exam Name : {{ $examName ?? '' }}</th>
                            <th colspan="4">Class Name : {{ $classname ?? '' }}</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Subject : </strong></td>
                            <td colspan="4"><strong>{{ $examSubject ?? '' }}</strong></td>
                            <td colspan="2"><strong>Exam Date : {{ $examDate ?? '' }}</strong></td>
                        </tr>

                        <tr>
                            <td><strong>Student Name</strong></td>
                            <td><strong>Register No</strong></td>
                             @if(isset($coMarks['CO-1'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-1 <br> ({{ $coMarks['CO-1'] }}-Marks)</span> </strong></td>@endif
                             @if(isset($coMarks['CO-2'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-2 <br> ({{ $coMarks['CO-2'] }}-Marks)</span> </strong></td>@endif
                             @if(isset($coMarks['CO-3'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-3 <br> ({{ $coMarks['CO-3'] }}-Marks)</span> </strong></td>@endif
                             @if(isset($coMarks['CO-4'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-4 <br> ({{ $coMarks['CO-4'] }}-Marks)</span> </strong></td>@endif
                             @if(isset($coMarks['CO-5'])) <td colspan="{{ $colSpan }}"><strong> <span> CO-5 <br>({{ $coMarks['CO-5'] }}-Marks)</span> </strong></td>@endif
                            <td colspan="{{ $colSpan }}"><strong>Total <br> @if(isset($coMarks['count'])) <span>({{ $coMarks['count'] }}-Marks)</span> @endif</strong></td>

                        </tr>
                        <form action="{{ route('admin.Exam-Mark.markStore') }}" method="post"
                            enctype="multipart/form-data" id ='my-form'>
                            @csrf
                            <input type="hidden" name="exame_name" value="{{ $examId ?? '' }}">
                            <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
                            <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">
                            @php
                              $array=[];
                            @endphp
                            @forelse ($exameData as $exameDatas)
                                <tr>
                                    <td>{{ $exameDatas->name ?? '' }}</td>
                                    <td>{{ $exameDatas->register_no ?? '' }}</td>
                                    @if (isset($coMarks['CO-1']))
                                    <td colspan="{{ $colSpan }}"><input
                                            type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-1']) ? 'hidden' : 'number' }}"
                                            min="0" max="{{ isset($coMarks['CO-1']) ? $coMarks['CO-1'] : '' }}" step=".01"
                                            class="mark-input mark {{ isset($coMarks['CO-1']) ? 'co_1a' : '' }}"
                                            name="CO_1[{{ $exameDatas->student_id }}]"
                                            value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-1']) ? '999' : $exameDatas->co_1 ?? '' }}"
                                          />
                                        {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-1']) ? 'Absent' : '' }}
                                    </td>
                                    @endif
                                    @if (isset($coMarks['CO-2']))

                                    <td class="" colspan="{{ $colSpan }}"><input
                                            type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-2']) ? 'hidden' : 'number' }}"
                                            min="0" max="{{ isset($coMarks['CO-2']) ? $coMarks['CO-2'] : '' }}" step=".01"
                                            class=" mark-input {{ isset($coMarks['CO-2']) ? 'co_2a' : '' }} mark"
                                            name="CO_2[{{ $exameDatas->student_id }}]"
                                            value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-2']) ? '999' : $exameDatas->co_2 ?? '' }}"
                                            />
                                        {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-2']) ? 'Absent' : '' }}
                                    </td>
                                    @endif
                                    @if (isset($coMarks['CO-3']))

                                    <td class="" colspan="{{ $colSpan }}"><input
                                            type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-3']) ? 'hidden' : 'number' }}"
                                            min="0" max="{{ isset($coMarks['CO-3']) ? $coMarks['CO-3'] : '' }}" step=".01"
                                            class="mark-input {{ isset($coMarks['CO-3']) ? 'co_3a' : '' }} mark"
                                            name="CO_3[{{ $exameDatas->student_id }}]"
                                            value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-3']) ? '999' : $exameDatas->co_3 ?? '' }}"
                                       />
                                        {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-3']) ? 'Absent' : '' }}
                                    </td>
                                    {{-- {{ $exameDatas->attendance == 'Present' && isset($coMarks['CO-3']) ? '' : 'readonly' }} --}}
                                   @endif
                                   @if (isset($coMarks['CO-4']))

                                    <td class="" colspan="{{ $colSpan }}"><input
                                            type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-4']) ? 'hidden' : 'number' }}"
                                            min="0" max="{{ isset($coMarks['CO-4']) ? $coMarks['CO-4'] : '' }}" step=".01"
                                            class=" mark-input {{ isset($coMarks['CO-4']) ? 'co_4a' : '' }} mark"
                                            name="CO_4[{{ $exameDatas->student_id }}]"
                                            value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-4']) ? '999' : $exameDatas->co_4 ?? '' }}"/>
                                        {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-4']) ? 'Absent' : '' }}
                                    </td>
                                    @endif
                                    @if (isset($coMarks['CO-5']))
                                    <td class=" " colspan="{{ $colSpan }}"><input
                                            type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-5']) ? 'hidden' : 'number' }}"
                                            min="0" max="{{ isset($coMarks['CO-5']) ? $coMarks['CO-5'] : '' }}" step=".01"
                                            class=" mark-input {{ isset($coMarks['CO-5']) ? 'co_5a' : '' }} mark"
                                            name="CO_5[{{ $exameDatas->student_id }}]"
                                            value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-5']) ? '999' : $exameDatas->co_5 ?? '' }}"
                                            />
                                        {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-5']) ? 'Absent' : '' }}
                                    </td>
                                    @endif
                                    @php
                                    $singleTotal = number_format(
                                        (100 * (
                                            ($exameDatas->co_1 ?? 0) +
                                            ($exameDatas->co_2 ?? 0) +
                                            ($exameDatas->co_3 ?? 0) +
                                            ($exameDatas->co_4 ?? 0) +
                                            ($exameDatas->co_5 ?? 0)
                                        )) / ($coMarks['count'] ?? 0), 2
                                    );
                                    $single=($exameDatas->co_1 ?? 0) +
                                        ($exameDatas->co_2 ?? 0) +
                                        ($exameDatas->co_3 ?? 0) +
                                        ($exameDatas->co_4 ?? 0) +
                                        ($exameDatas->co_5 ?? 0);
                                    @endphp
                                    <td colspan="{{ $colSpan }}" style="{{ ($singleTotal < 50) ? 'background-color: red;' : '' }}">
                                        @if (
                                            ($exameDatas->co_1 ?? 0) +
                                            ($exameDatas->co_2 ?? 0) +
                                            ($exameDatas->co_3 ?? 0) +
                                            ($exameDatas->co_4 ?? 0) +
                                            ($exameDatas->co_5 ?? 0) >= 999
                                        )
                                            Absent
                                        @else
                                            {{
                                                $single
                                            }}

                                            @php
                                                array_push($array, $singleTotal);
                                            @endphp
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No data Found</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="8">
                                    <div class="row">
                                        <div class="col">
                                            @if ($status!=2)
                                    <button type="submit" id='submit' class="btn btn-primary">Save & Exit</button>

                                            @endif
                                </div>
                                <div class="col">
                                    <button type="submit" id="Publish" name="publish" value="publish" class="btn btn-success">Save & Publish</button>
                                </div>
                                </div>
                                </td>
                            </tr>
                        </form>
                        <tr>
                            <td colspan="8"><strong>Summary</strong></td>

                        </tr>
                        <tr>
                            <td colspan="2"><strong>Total No Of Students</strong> </td>
                            <td colspan="2"><strong>Total No Of Students Present</strong></td>
                            <td colspan="1"><strong> Total No Of Students Absent</strong></td>
                            <td colspan="1"><strong>Total No Of Students Pass</strong></td>
                            <td colspan="1"><strong>No Of Students Fail</strong></td>
                            <td colspan="1"><strong>Pass percentage</strong></td>
                        </tr>
                        {{-- @for ($i = 0; $i < 3; $i++) --}}
                        <tr>
                            <td colspan="2">{{ ($totalPres ?? '') + ($totalAbs ?? '') }}</td>
                            <td colspan="2">{{ $totalPres ?? '' }}</td>
                            <td colspan="1">{{ $totalAbs ?? '' }}</td>
                            <td colspan="1">
                                @if (!empty($array))
                                <?php
                                $sum = 0;
                                $Pass = 0;
                                $Fail = 0;
                                // $Fail=$Fail+($totalAbs ?? 0);
                                foreach ($array as $value) {
                                    if($value >= 50 ){
                                        $Pass++;
                                    }
                                    if($value < 50 ){
                                        $Fail++;
                                    }
                                    $sum += $value;

                                }
                                $passPercentage = ($Pass / (($totalPres ?? 0))) * 100;
                                ?>

                            @endif {{ $Pass  ?? '' }}</td>
                            <td colspan="1">{{ $Fail ?? '' }}</td>
                            <td colspan="1">{{ number_format($passPercentage, 2) }}%</td>


                        </tr>
                        {{-- @endfor --}}

                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>

        document.addEventListener("wheel", function(event) {
            if (document.activeElement.type === "number") {
                document.activeElement.blur();
            }
        });

        $(document).ready(function() {
            $('#Publish').on('click', function(e) {
                $('#loading').show();
                var form = $('#my-form');
                form.submit();
            });
        });

        $(document).ready(function() {
            $('#submit').on('click', function(e) {
                $('#loading').show();
                var form = $('#my-form');
                form.submit();
            });
        });
    </script>
@endsection
