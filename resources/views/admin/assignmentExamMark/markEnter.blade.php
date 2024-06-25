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
                            <th colspan="4">Class Name :&nbsp; {{ $classname ?? '' }}</th>
                            <td colspan="4"><strong> Subject: &nbsp; {{ $examSubject ?? '' }}</strong></td>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td><strong>Student Name</strong></td>
                            <td><strong>Register No</strong></td>
                            <td><strong>Assignment-1 <br> (10 Marks)</strong></td>
                            <td><strong>Assignment-2 <br> (10 Marks)</strong></td>
                            <td><strong>Assignment-3 <br> (10 Marks)</strong></td>
                            <td><strong>Assignment-4 <br> (10 Marks)</strong></td>
                            <td><strong>Assignment-5 <br> (10 Marks)</strong></td>
                            <td colspan='1'><strong>Total <br> (50 Marks)</strong></td>

                        </tr>
                        <form action="{{ route('admin.assignment_Exam_Mark.markStore') }}" id="my-form" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @if (isset($assignmentMark))
                                <input type="hidden" id="assignmentTotal_mark" value="{{ $assignmentMark ?? 0 }}">
                            @endif
                            <input type="hidden" name="exam_name" value="{{ $examId ?? '' }}">
                            <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
                            <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">

                            @forelse ($exameData as $exameDatas)
                                <tr>
                                    <td>{{ $exameDatas->studentName ?? '' }}</td>
                                    <td>{{ $exameDatas->studentReg ?? '' }}</td>

                                    @if (isset($examName))
                                        @for ($i = 1; $i <= 5; $i++)
                                            <td class=" " colspan="1">
                                                <input type="number" min="0" max="{{ $assignmentMark - 40 ?? 0 }}"
                                                    class="mark-input {{ isset($assignmentMark) ? 'assignment_mark_' . $i : '' }} mark co_1a"
                                                    id="{{ isset($assignmentMark) ? 'assignment_mark_' . $i : '' }}"
                                                    name="assignment_mark_{{ $i }}[{{ $exameDatas->student_id }}]"
                                                    value="" />
                                            </td>
                                        @endfor
                                    @endif

                                    <td colspan="1" class="total-td"> </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No data Found</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="4">
                                    <button type="submit" id="btn_submit" class="btn btn-primary">Submit Mark</button>
                                </td>
                                <td colspan="4">
                                    <button type="submit" id='btn-submit' value='final_submit'
                                        class="btn btn-primary free_submit">Final Submit</button>
            </div>
            </td>
            </tr>
            </form>

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
                var maxPossibleValue = 50;
                var co_length;

                // Extract the assignment number from the class
                var assignmentNumber = $(this).attr('class').match(/assignment_mark_(\d+)/);

                if (assignmentNumber) {
                    // Construct the assignment selector dynamically
                    var assignmentSelector = '#assignment_mark_' + assignmentNumber[1];

                    if ($(assignmentSelector).length > 0) {
                        co_length = 10; // Set your dynamic length here

                        if (inputValue > co_length) {
                            $(this).val('').css('background-color', 'red');
                            alert('Total mark is ' + co_length);
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
                    }
                }
            });
        });


        $('#btn_submit').on('click', function(e) {
            $('#loading').show();
            var form = $('#my-form');
            form.submit();
        });





        $(document).ready(function() {
            $('#btn-submit').on('click', function(e) {
                let $button = $(this).val();
                e.preventDefault();
                var form = $('#my-form');
                //   var myClass= $('input[type="text"]').hasClass('co_1a');

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
                    confirmButtonText: $button,
                    showConfirmButton: true
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

                                // Get the value of the button
                                // var buttonValue = $('#btn-submit').val();

                                // Append the button value to the form data
                                if ($button == 'publish') {
                                    $('#my-form').append($('<input>', {
                                        type: 'hidden',
                                        name: 'publish',
                                        value: $button,
                                    }));
                                } else if ($button == 'final_submit') {
                                    $('#my-form').append($('<input>', {
                                        type: 'hidden',
                                        name: 'final_submit',
                                        value: $button,
                                    }));

                                }
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
