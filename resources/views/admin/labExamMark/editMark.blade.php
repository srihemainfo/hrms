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
            <strong>Edit Mark</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered  text-center table-hover ajaxTable datatable datatable-exameMark">


                    <thead>
                        <tr>
                            <th colspan="2">Exam Name : {{ $examName ?? '' }}</th>
                            <th colspan="2">Class Name : {{ $classname ?? '' }}</th>


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
                        <form action="{{ route('admin.Lab_Exam_Mark.markStore') }}" method="POST"
                            enctype="multipart/form-data" id ='my-form'>
                            @if (isset($coMarks))
                                <input type="hidden" id="co_1" value="{{ $coMarks ?? 0 }}">
                            @endif
                            @csrf
                            <input type="hidden" name="exame_name" value="{{ $examId ?? '' }}">
                            <input type="hidden" name="class_name" value="{{ $classId ?? '' }}">
                            <input type="hidden" name="subject" value="{{ $subjectId ?? '' }}">
                            @php
                                $array = [];
                            @endphp
                            @forelse ($exameData as $exameDatas)
                                @if ($exameDatas->studentName != '')
                                    <tr>
                                        <td>{{ $exameDatas->studentName ?? '' }}</td>
                                        <td>{{ $exameDatas->studentReg ?? '' }}</td>
                                        @if (isset($examName))
                                            <td colspan="1">
                                                <input type="number" min="0"
                                                    max="{{ isset($coMarks) ? $coMarks : '' }}"
                                                    class="mark-input mark {{ isset($coMarks) ? 'co_1a' : '' }}"
                                                    name="CO_1[{{ $exameDatas->student_id }}]"
                                                    value="{{ $exameDatas->cycle_mark ?? '' }}" />
                                            </td>
                                        @endif
                                        <td style="{{ $exameDatas->cycle_mark < 50 || $exameDatas->cycle_mark == 999 ? 'background-color: red;color:white;' : '' }}"
                                            class='total-td' colspan="1">
                                            {{ $exameDatas->cycle_mark ?? '' }}
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4">No data Found</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="4">
                                    @if ($role_id == 40 || $role_id == 1)
                                        <div class="row">
                                            <div class="col">
                                                @if ($status != 2)
                                                    <button type="submit" value='Save & Exit'
                                                        class="btn btn-primary submit ">Save & Exit</button>
                                                @endif
                                            </div>

                                            <div class="col">
                                                <button type="submit" name="publish" value="publish" id='btn-submit'
                                                    class="btn btn-success submit ">Save & Publish</button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            @if ($status != 2 || $status != 1)
                                                <div class="col">
                                                    <button type="submit" id='submit1' value='Save & Exit'
                                                        class="btn btn-primary submit ">Save &
                                                        Exit</button>
                                                </div>
                                            @elseif($status == 1)
                                                <div class="col">
                                                    <button type="submit" class="btn btn-warning">Exam Mark Edit request
                                                        Pending</button>
                                                </div>
                                            @elseif ($status == 2)
                                                <div class="col">
                                                    <button type="submit" class="btn btn-warning">Exam Mark
                                                        Published</button>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </form>
                        <tr>
                            <td colspan="4"><strong>Summary</strong></td>

                        </tr>
                        <tr>
                            <td colspan="1"><strong>Total No Of Students</strong> </td>
                            <td colspan="1"><strong>Total No Of Students Pass</strong></td>
                            <td colspan="1"><strong>No Of Students Fail</strong></td>
                            <td colspan="1"><strong>Pass percentage</strong></td>
                        </tr>
                        <tr>
                            <td colspan="1">{{ $totalStudent ?? '' }}</td>
                            <td colspan="1">{{ $exameData->pass ?? '' }}</td>
                            <td colspan="1">{{ $exameData->fail ?? '' }}</td>
                            <td colspan="1">
                                {{ $exameData->passPercentage ? number_format($exameData->passPercentage, 2) : '' }}</td>
                        </tr>

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
            // $('#Publish').on('click', function(e) {
            //     $('#loading').show();
            //     var form = $('#my-form');
            //     form.submit();
            // });

            // $('#submit').on('click', function(e) {
            //     $('#loading').show();
            //     var form = $('#my-form');
            //     form.submit();
            // });

            $('#my-form').on('focus', 'input[type="number"]', function(e) {
                $(this).on('wheel.disableScroll', function(e) {
                    e.preventDefault();
                });
            });

            $('#my-form').on('blur', 'input[type="number"]', function(e) {
                $(this).off('wheel.disableScroll');
            });



            // mark check

            var passCount = 0;
            var failCount = 0;
            var percentageArray = [];
            co_lenth = '';

            $('.mark-input').on('keyup', function() {
                var inputValue = parseFloat($(this).val()) || 0;
                // var inputValue = $(this).val() || 0;

                if ($(this).hasClass('mark-input') && $(this).hasClass('co_1a')) {
                    if ($('#co_1').length > 0) {
                        co_lenth = $('#co_1').val();
                    }
                }
                if (inputValue == 0) {
                    $(this).closest('tr').find('.total-td').html(0);
                    $(this).closest('tr').find('.total-td').css('background-color', 'red');
                    $(this).closest('tr').find('.total-td').css('color', 'white');
                } else if (inputValue < {{ $coMarks / 2 }}) {
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
            });

        });

        // $(document).ready(function() {

        //     $('#submit').on('click', function(e) {
        //         // alert('Button clicked!');
        //         e.preventDefault();
        //         $('input.co_1a').each(function() {
        //             var value = $(this).val().trim();
        //             if (value === null || value === '') {
        //                 $(this).css('background-color', 'red');
        //             }
        //         })

        //         $('input.co_1a').each(function() {
        //             hasNullValue = false;
        //             var value = $(this).val().trim();
        //             if (value === null || value === '') {
        //                 hasNullValue = true;
        //                 return false; // Exit the loop if a null value is found
        //             }

        //         })


        //         Swal.fire({
        //             title: "Are you sure?",
        //             html: `
    //         <p>Marks submitted by you cannot be edited. Are you sure to submit marks?</p>
    //         <label>
    //             <input type="checkbox" id="verification-checkbox">
    //             I hereby declare that I entered the marks correctly and verified.
    //         </label>
    //     `,
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonColor: "#007bff",
        //             confirmButtonText: "Save & Publish",
        //             allowOutsideClick: false
        //         }).then(function(result) {
        //             if (result.value) {

        //                 var verificationCheckbox = $('#verification-checkbox');
        //                 if (hasNullValue) {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Please Fill All fields',
        //                         text: 'Please check the marks fields',
        //                     });
        //                 } else {
        //                     if (verificationCheckbox.prop('checked')) {



        //                         // Get the value of the button
        //                         var buttonValue = $('#submit').val();

        //                         // Append the button value to the form data
        //                         $('#my-form').append($('<input>', {
        //                             type: 'hidden',
        //                             name: 'buttonValue',
        //                             value: buttonValue
        //                         }));

        //                         console.log(buttonValue);
        //                         $('#my-form').submit();

        //                         $('#loading').show();
        //                     } else {
        //                         Swal.fire({
        //                             icon: 'error',
        //                             title: 'Verification Required',
        //                             text: 'Please check the verification checkbox before submitting.',
        //                         });
        //                     }
        //                 }

        //             }
        //         });
        //     });
        // });

        $(document).ready(function() {
            $('.submit').on('click', function(e) {
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
