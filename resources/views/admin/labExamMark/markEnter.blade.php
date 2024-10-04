@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
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

                <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark">
                    <thead>

                        <tr>
                            <th colspan="2">Exam Name : &nbsp; {{ $examName ?? '' }}</th>
                            <th colspan="2">Class Name :&nbsp; {{ $classname ?? '' }}</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4"><strong> Subject: &nbsp; {{ $examSubject ?? '' }}</strong></td>
                        </tr>

                        <tr>
                            <td><strong>Student Name</strong></td>
                            <td><strong>Register No</strong></td>
                            <td colspan='1'><strong> {{ $examName ? explode('/', $examName)[0] : '' }}
                                    <br>({{ $coMarks ?? '' }}-Marks)</strong></td>
                            <td colspan='1'><strong>Total <br> ({{ $coMarks ?? '' }}-Marks)</strong></td>

                        </tr>
                        <form action="{{ route('admin.Lab_Exam_Mark.markStore') }}" id="my-form" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @if (isset($coMarks))
                                <input type="hidden" id="co_1" value="{{ $coMarks ?? 0 }}">
                            @endif
                            <input type="hidden" name="exame_name" value="{{ $examId ?? '' }}">
                            <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
                            <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">

                            @forelse ($exameData as $exameDatas)
                                @if ($exameDatas->studentName != '')
                                    <tr>
                                        <td>{{ $exameDatas->studentName ?? '' }}</td>
                                        <td>{{ $exameDatas->studentReg ?? '' }}</td>

                                        @if (isset($examName))
                                            <td class=" " colspan="1">
                                                <input type="number" min="0" max="{{ $coMarks ?? 100 }}"
                                                    class=" mark-input {{ isset($coMarks) ? 'co_1a' : '' }} mark"
                                                    name="CO_1[{{ $exameDatas->student_id }}]" value="" />
                                            </td>
                                        @endif

                                        <td colspan="1" class="total-td"> </td>
                                    </tr>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="4">No data Found</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="4">
                                    <button type="submit" id="btn-submit" class="btn btn-primary">Submit Mark</button>
                                </td>
                            </tr>
                        </form>

                        {{--
                        <tr>
                            <td colspan="4"><strong>Summary</strong></td>

                        </tr>
                        <tr>
                            <td colspan="1"><strong>Total No Of Students</strong> </td>
                            <td colspan="1"><strong>Total No Of Students Present</strong></td>
                            <td colspan="1"><strong> Total No Of Students Absent</strong></td>
                        </tr>
                        <tr>
                            <td colspan="1">{{ ($totalPres ?? '') + ($totalAbs ?? '') }}</td>
                            <td colspan="1">{{ $totalPres ?? '' }}</td>
                            <td colspan="1">{{ $totalAbs ?? '' }}</td>
                        </tr>

                        --}}
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
            co_lenth = '';

            $('.mark-input').on('keyup', function() {
                var inputValue = parseFloat($(this).val()) || 0;
                {{--  // var maxPossibleValue = @json($coMarks['count']);
                // var co_lenth;
                --}}
                if ($(this).hasClass('mark-input') && $(this).hasClass('co_1a')) {
                    if ($('#co_1').length > 0) {
                        co_lenth = $('#co_1').val();
                    }
                }
                {{--
                // if ($(this).hasClass('mark-input') && $(this).hasClass('co_1a')) {
                //     if ($('#co_1').length > 0) {
                //         co_lenth = $('#co_1').val();
                //     }
                // }
                // if ($(this).hasClass('mark-input') && $(this).hasClass('co_2a')) {

                //     if ($('#co_2').length > 0) {
                //         co_lenth = $('#co_2').val();
                //     }
                // }
                // if ($(this).hasClass('mark-input') && $(this).hasClass('co_3a')) {

                //     if ($('#co_3').length > 0) {
                //         co_lenth = $('#co_3').val();
                //     }
                // }
                // if ($(this).hasClass('mark-input') && $(this).hasClass('co_4a')) {

                //     if ($('#co_4').length > 0) {
                //         co_lenth = $('#co_4').val();
                //     }
                // }
                // if ($(this).hasClass('mark-input') && $(this).hasClass('co_5a')) {

                //     if ($('#co_5').length > 0) {
                //         co_lenth = $('#co_5').val();
                //     }
                // }

                --}}

                if (inputValue < {{ $coMarks / 2 }}) {
                    $(this).closest('tr').find('.total-td').html(inputValue);
                    $(this).closest('tr').find('.total-td').css('background-color', 'red');
                    $(this).closest('tr').find('.total-td').css('color', 'white');
                    // $(this).val(inputValue).css('background-color', 'red');
                } else {
                    $(this).closest('tr').find('.total-td').html(inputValue);
                    $(this).closest('tr').find('.total-td').css('background-color', '');
                    $(this).closest('tr').find('.total-td').css('color', 'black');
                    // $(this).val(inputValue).css('background-color', 'red');
                }

                if (inputValue > co_lenth) {
                    $(this).val('').css('background-color', 'red');
                    $(this).closest('tr').find('.total-td').text('');
                    $(this).closest('tr').find('.total-td').css('background-color', '');
                    alert("Entered Mark Not greater than " + co_lenth);
                } else if (inputValue < 0) {
                    $(this).val('').css('background-color', 'red');
                    $(this).closest('tr').find('.total-td').text('');
                    $(this).closest('tr').find('.total-td').css('background-color', '');
                    alert('Entered Mark Not less than 0');
                } else {
                    $(this).css('background-color', '');
                }


                // var total = 0;

                // $(this).closest('tr').find('.mark-input').each(function() {
                //     total += parseFloat($(this).val()) || 0;
                // });

                // var percentage = (total) * 100;
                // percentage = parseFloat(percentage.toFixed(2));

                // var totalTd = $(this).closest('tr').find('.total-td');
                // totalTd.text(total);

                // if (percentage >= 50) {
                //     totalTd.css('background-color', '');
                // } else {
                //     totalTd.css('background-color', 'red');
                // }


            });

        });


        $(document).ready(function() {
            $('#btn-submit').on('click', function(e) {
                e.preventDefault();
                var form = $('#my-form');

                $('input.co_1a').each(function() {
                    var value = $(this).val().trim();
                    if (value === null || value === '') {
                        $(this).css('background-color', 'red');
                    }
                })

                $('input.co_1a').each(function() {
                    hasNullValue = false;
                    var value = $(this).val().trim();
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
                    allowOutsideClick: false
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
