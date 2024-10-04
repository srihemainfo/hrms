@php
     $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    }elseif ($type_id == 1 || $type_id == 3) {
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
            <strong>Enter Mark</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
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
                <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark">
                    <thead>
                        {{-- @php
                            $firstTd=$available;
                            $totalFirsttd=(8 - $firstTd);
                            $result1=($totalFirsttd/2);
                            $result1=floor($result1);
                            dd($result1);
                       @endphp --}}
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
                            @if (isset($coMarks['CO-1']))
                                <td colspan="{{ $colSpan }}"><strong>CO-1 <br> ({{ $coMarks['CO-1'] ?? '' }}-Marks)
                                    </strong></td>
                            @endif
                            @if (isset($coMarks['CO-2']))
                                <td colspan="{{ $colSpan }}"><strong>CO-2 <br>
                                        ({{ $coMarks['CO-2'] ?? '' }}-Marks)</strong></td>
                            @endif
                            @if (isset($coMarks['CO-3']))
                                <td colspan="{{ $colSpan }}"><strong>CO-3 <br>
                                        ({{ $coMarks['CO-3'] ?? '' }}-Marks)</strong></td>
                            @endif
                            @if (isset($coMarks['CO-4']))
                                <td colspan="{{ $colSpan }}"><strong>CO-4 <br>
                                        ({{ $coMarks['CO-4'] ?? '' }}-Marks)</strong></td>
                            @endif
                            @if (isset($coMarks['CO-5']))
                                <td colspan="{{ $colSpan }}"><strong>CO-5 <br>
                                        ({{ $coMarks['CO-5'] ?? '' }}-Marks)</strong></td>
                            @endif
                            <td colspan="{{ $colSpan }}"><strong>Total <br> ({{ $coMarks['count'] ?? '' }}-Marks)
                                </strong></td>

                        </tr>

                        <form action="{{ route('admin.Exam-Mark.markStore') }}" id="my-form" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @if (isset($coMarks['CO-1']))
                                <input type="hidden" id="co_1" value="{{ $coMarks['CO-1'] ?? 0 }}">
                            @endif
                            @if (isset($coMarks['CO-2']))
                                <input type="hidden" id="co_2" value="{{ $coMarks['CO-2'] ?? 0 }}">
                            @endif
                            @if (isset($coMarks['CO-3']))
                                <input type="hidden" id="co_3" value="{{ $coMarks['CO-3'] ?? 0 }}">
                            @endif
                            @if (isset($coMarks['CO-4']))
                                <input type="hidden" id="co_4" value="{{ $coMarks['CO-4'] ?? 0 }}">
                            @endif
                            @if (isset($coMarks['CO-5']))
                                <input type="hidden" id="co_5" value="{{ $coMarks['CO-5'] ?? 0 }}">
                            @endif




                            <input type="hidden" name="exame_name" value="{{ $examId ?? '' }}">
                            <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
                            <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">

                            @forelse ($exameData as $exameDatas)
                                <tr>
                                    <td>{{ $exameDatas->name ?? '' }}</td>
                                    <td>{{ $exameDatas->register_no ?? '' }}</td>
                                    @if (isset($coMarks['CO-1']))
                                        <td colspan="{{ $colSpan }}"><input
                                                type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-1']) ? 'hidden' : 'number' }}"
                                                min="0"
                                                max-value="{{ isset($coMarks['CO-1']) ? $coMarks['CO-1'] : '' }}"
                                                class="mark-input mark {{ isset($coMarks['CO-1']) ? 'co_1a' : '' }}"
                                                name="CO_1[{{ $exameDatas->student_id }}]"
                                                value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-1']) ? '999' : '' }}"
                                                {{ $exameDatas->attendance == 'Present' && isset($coMarks['CO-1']) ? '' : 'readonly' }} />
                                            {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-1']) ? 'Absent' : '' }}
                                        </td>
                                    @endif
                                    @if (isset($coMarks['CO-2']))
                                        <td class="" colspan="{{ $colSpan }}"><input
                                                type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-2']) ? 'hidden' : 'number' }}"
                                                min="0" max="{{ isset($coMarks['CO-2']) ? $coMarks['CO-2'] : '' }}"
                                                class=" mark-input {{ isset($coMarks['CO-2']) ? 'co_2a' : '' }} mark"
                                                name="CO_2[{{ $exameDatas->student_id }}]"
                                                value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-2']) ? '999' : '' }}"
                                                {{ $exameDatas->attendance == 'Present' && isset($coMarks['CO-2']) ? '' : 'readonly' }} />
                                            {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-2']) ? 'Absent' : '' }}
                                        </td>
                                    @endif
                                    @if (isset($coMarks['CO-3']))
                                        <td class="" colspan="{{ $colSpan }}"><input
                                                type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-3']) ? 'hidden' : 'number' }}"
                                                min="0" max="{{ isset($coMarks['CO-3']) ? $coMarks['CO-3'] : '' }}"
                                                class="mark-input {{ isset($coMarks['CO-3']) ? 'co_3a' : '' }} mark"
                                                name="CO_3[{{ $exameDatas->student_id }}]"
                                                value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-3']) ? '999' : '' }}"
                                                {{ $exameDatas->attendance == 'Present' && isset($coMarks['CO-3']) ? '' : 'readonly' }} />
                                            {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-3']) ? 'Absent' : '' }}
                                        </td>
                                    @endif
                                    @if (isset($coMarks['CO-4']))
                                        <td class="" colspan="{{ $colSpan }}"><input
                                                type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-4']) ? 'hidden' : 'number' }}"
                                                min="0" max="{{ isset($coMarks['CO-4']) ? $coMarks['CO-4'] : '' }}"
                                                class=" mark-input {{ isset($coMarks['CO-4']) ? 'co_4a' : '' }} mark"
                                                name="CO_4[{{ $exameDatas->student_id }}]"
                                                value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-4']) ? '999' : '' }}"
                                                {{ $exameDatas->attendance == 'Present' && isset($coMarks['CO-4']) ? '' : 'readonly' }} />
                                            {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-4']) ? 'Absent' : '' }}
                                        </td>
                                    @endif
                                    @if (isset($coMarks['CO-5']))
                                        <td class=" " colspan="{{ $colSpan }}"><input
                                                type="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-5']) ? 'hidden' : 'number' }}"
                                                min="0" max="{{ isset($coMarks['CO-5']) ? $coMarks['CO-5'] : '' }}"
                                                class=" mark-input {{ isset($coMarks['CO-5']) ? 'co_5a' : '' }} mark"
                                                name="CO_5[{{ $exameDatas->student_id }}]"
                                                value="{{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-5']) ? '999' : '' }}"
                                                {{ $exameDatas->attendance == 'Present' && isset($coMarks['CO-5']) ? '' : 'readonly' }} />
                                            {{ $exameDatas->attendance == 'Absent' && isset($coMarks['CO-5']) ? 'Absent' : '' }}
                                        </td>
                                    @endif

                                    <td colspan="{{ $colSpan }}" class="total-td"> </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No data Found</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="8">
                                    <button type="submit" id="btn-submit" class="btn btn-primary">Submit Mark</button>
                                </td>
                            </tr>
                        </form>
                        <tr>
                            <td colspan="8"><strong>Summary</strong></td>

                        </tr>
                        <tr>
                            <td colspan="3"><strong>Total No Of Students</strong> </td>
                            <td colspan="3"><strong>Total No Of Students Present</strong></td>
                            <td colspan="2"><strong> Total No Of Students Absent</strong></td>
                            {{-- <td colspan="1"><strong>Total No Of Students Pass</strong></td>
                            <td colspan="1"><strong>No Of Students Fail</strong></td>
                            <td colspan="1"><strong>Pass percentage</strong></td> --}}
                        </tr>
                        {{-- @for ($i = 0; $i < 3; $i++) --}}
                        <tr>
                            <td colspan="3">{{ ($totalPres ?? '') + ($totalAbs ?? '') }}</td>
                            <td colspan="3">{{ $totalPres ?? '' }}</td>
                            <td colspan="2">{{ $totalAbs ?? '' }}</td>
                            {{-- <td colspan="1" id="pass-count"></td>
                            <td colspan="1" id="fail-count"></td>
                            <td colspan="1"></td> --}}


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
            $('#my-form').on('focus', 'input[type="number"]', function(e) {
                $(this).on('wheel.disableScroll', function(e) {
                    e.preventDefault();
                });
            });

            $('#my-form').on('blur', 'input[type="number"]', function(e) {
                $(this).off('wheel.disableScroll');
            });
        });

        $(document).ready(function() {

            var passCount = 0;
            var failCount = 0;
            var percentageArray = [];

            $('.mark-input').on('keyup', function() {
                var inputValue = parseFloat($(this).val()) || 0;
                var maxPossibleValue = @json($coMarks['count']);
                var co_lenth;
                if ($(this).hasClass('mark-input') && $(this).hasClass('co_1a')) {
                    if ($('#co_1').length > 0) {
                        co_lenth = $('#co_1').val();
                    }
                }
                if ($(this).hasClass('mark-input') && $(this).hasClass('co_2a')) {

                    if ($('#co_2').length > 0) {
                        co_lenth = $('#co_2').val();
                    }
                }
                if ($(this).hasClass('mark-input') && $(this).hasClass('co_3a')) {

                    if ($('#co_3').length > 0) {
                        co_lenth = $('#co_3').val();
                    }
                }
                if ($(this).hasClass('mark-input') && $(this).hasClass('co_4a')) {

                    if ($('#co_4').length > 0) {
                        co_lenth = $('#co_4').val();
                    }
                }
                if ($(this).hasClass('mark-input') && $(this).hasClass('co_5a')) {

                    if ($('#co_5').length > 0) {
                        co_lenth = $('#co_5').val();
                    }
                }

                if (inputValue > co_lenth) {
                    $(this).val('0').css('background-color', 'red');
                    alert('Total mark is '+co_lenth);
                } else {
                    $(this).css('background-color', '');
                }

                var total = 0;

                $(this).closest('tr').find('.mark-input').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });

                var percentage = (total / maxPossibleValue) * 100;
                percentage = parseFloat(percentage.toFixed(2));

                var totalTd = $(this).closest('tr').find('.total-td');
                totalTd.text(total);

                if (percentage >= 50) {
                    totalTd.css('background-color', '');
                } else {
                    totalTd.css('background-color', 'red');
                }
            });

        });





        $(document).ready(function() {
            $('#btn-submit').on('click', function(e) {
                e.preventDefault();
                var form = $('#my-form');
                //   var myClass= $('input[type="text"]').hasClass('co_1a');
                $('input.co_1a, input.co_2a, input.co_3a, input.co_4a, input.co_5a').each(function() {
                    hasNullValue = false;
                    var value = $(this).val().trim();
                    console.log(value);
                    if (value === null || value === '') {
                        hasNullValue = true;
                        return false; // Exit the loop if a null value is found
                    }

                })
                Swal.fire({
                    title: "Are you sure?",
                    html: `
                <p>Marks submitted by you cannot be edited. Are you sure to submit marks?</p>
                <label>
                    <input type="checkbox" id="verification-checkbox">
                    I hereby declare that I entered the marks correctly and verified.
                </label>
            `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#007bff",
                    confirmButtonText: "Submit Marks",
                    closeOnConfirm: false
                }).then(function(result) {
                    if (result.value) {
                        var verificationCheckbox = $('#verification-checkbox');
                        if (hasNullValue) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Please Fill All fields',
                                text: 'Please check the marks fields',
                            });
                        } else {
                            if (verificationCheckbox.prop('checked')) {
                                $('#loading').show();
                                form.submit();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Verification Required',
                                    text: 'Please check the verification checkbox before submitting.',
                                });
                            }
                        }

                    }
                });
            });
        });
    </script>
@endsection
