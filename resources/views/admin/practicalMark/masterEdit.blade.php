@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Practical Marks
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Batch</label>
                        <select name="batch" id="batch" class="form-control select2">
                            @if ($details['batch'] != null)
                                <option value="{{ $details['batch']->id }}">{{ $details['batch']->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Academic Year</label>
                        <select name="ay" id="ay" class="form-control select2">
                            @if ($details['ay'] != null)
                                <option value="{{ $details['ay']->id }}">{{ $details['ay']->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <label class="required" for="exam_month">Exam Month </label>
                    <select class="form-control select2" name="exam_month" id="exam_month">
                        @if ($details['exam_month'] != null)
                            <option value="{{ $details['exam_month'] }}">{{ $details['exam_month'] }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <label for="exam_year" class="required">Exam Year</label>
                    <select class="form-control select2 " name="exam_year" id="exam_year">
                        @if ($details['exam_year'] != null)
                            <option value="{{ $details['exam_year'] }}">{{ $details['exam_year'] }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Course</label>
                        <select name="course" id="course" class="form-control select2">
                            @if ($details['course'] != null)
                                <option value="{{ $details['course']->id }}">{{ $details['course']->short_form }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Current Semester</label>
                        <select name="semester" id="semester" class="form-control select2" onchange="clearExamType()">
                            @if ($details['semester'] != null)
                                <option value="{{ $details['semester'] }}">{{ $details['semester'] }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Exam Type</label>
                        <select name="exam_type" id="exam_type" class="form-control select2">
                            @if ($details['exam_type'] != null)
                                <option value="{{ $details['exam_type'] }}">{{ $details['exam_type'] }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Subject</label>
                        <select name="subject" id="subject" class="form-control select2">
                            @if ($details['subject'] != null)
                                <option value="{{ $details['subject']->id }}">{{ $details['subject']->name }}
                                    ({{ $details['subject']->subject_code }})</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center" style="width:100%;">
                <thead>
                    <tr>
                        <th>
                            S.No
                        </th>
                        <th>
                            Register No
                        </th>
                        <th>
                            Student Name
                        </th>
                        <th>
                            Total Marks (100)<br>
                            <span style="font-size:0.8rem;"> For Absent : -1</span>
                        </th>
                        <th>
                            Mark in Words
                        </th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @forelse ($data as $i => $theData)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $theData->student != null ? $theData->student->register_no : '' }}</td>
                            <td>{{ $theData->student != null ? $theData->student->name : '' }}</td>
                            <td>
                                <input class="form-control marks" style="width:40%;margin:auto;border-radius:5px;" type="number" id="{{ $theData->user_name_id }}" onchange="checkMark(this,{{ $theData->user_name_id }})" value="{{ $theData->mark }}"/>
                            </td>
                            <td id="w{{ $theData->user_name_id }}">{{ $theData->mark_in_word }}</td>
                        </tr>
                    @empty
                    <tr><td colspan="5">No Data Available...</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="row text-right mt-3">
                <div class="col-12">
                    <button class="enroll_generate_bn bg-success" onclick="update()" id="submitBtnDiv">Update</button>
                    <div class="text-success" id="submitDiv" style="display:none;"><b>Processing...</b></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function checkMark(element, id) {

            if ($(element).val() != '') {
                let value = $(element).val();
                if (isNaN($(element).val()) || parseInt($(element).val()) < -1 || parseInt($(element).val()) > 100 || value
                    .length > 3) {
                    Swal.fire('', 'Mark Is Not Valid', 'error');
                    $(element).val('')
                    $("#w" + id).html('')

                } else {

                    let includes = value.includes('-');
                    if (includes && $(element).val() != -1) {
                        Swal.fire('', 'Mark Is Not Valid', 'error');
                        $(element).val('');
                        $("#w" + id).html('');

                    }
                    var inWords = 'Absent';

                    if ($(element).val() != -1) {
                        inWords = numberToWords($(element).val(), element);

                    }
                    $("#w" + id).html(inWords)
                }
            } else {
                $("#w" + id).html('')
            }
        }

        function numberToWords(number, element) {

            let givenNum = number;
            let numb = givenNum.toString();
            let markWord = '';
            var numbVal = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            if (numb.length > 2) {
                if (number != 100) {
                    let getIndex = numb.slice(1);
                    markWord = numbVal[getIndex.charAt(0)] + ' ' + numbVal[getIndex.charAt(1)];
                    $(element).val(getIndex)
                } else {
                    markWord = 'One Zero Zero';
                }
            } else if (numb.length == 1) {
                markWord = 'Zero ' + numbVal[numb.charAt(0)];
                $(element).val('0' + numb)
            } else {

                markWord = numbVal[numb.charAt(0)] + ' ' + numbVal[numb.charAt(1)];

            }
            return markWord;

        }

        function update() {
            $(".marks").css('border', '1px solid #cfd1d8');
            let datas = [];
            let theAction = true;
            $.each($(".marks"), function() {
                if ($(this).attr('id') == '') {
                    Swal.fire('', 'Technical Error', 'error');
                    theAction = false;

                }
                if ($(this).val() == '') {
                    Swal.fire('', "Marks Field Can't Be Empty", 'error');
                    $(this).css('border', '2px solid red');
                    theAction = false;

                }

                var id = $(this).attr('id');
                datas.push([$(this).attr('id'), $(this).val(), $('#w' + id).html()]);
            })

            if (theAction) {
                Swal.fire({
                    title: "Are You Sure?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        if (datas.length > 0) {
                            $("#submitDiv").show();
                            $("#submitBtnDiv").hide();
                            $.ajax({
                                url: "{{ route('admin.practical-mark-master.update-students') }}",
                                type: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'batch': $("#batch").val(),
                                    'ay': $("#ay").val(),
                                    'course': $("#course").val(),
                                    'exam_month': $("#exam_month").val(),
                                    'exam_year': $("#exam_year").val(),
                                    'semester': $("#semester").val(),
                                    'exam_type': $("#exam_type").val(),
                                    'subject': $("#subject").val(),
                                    'data': datas
                                },
                                success: function(response) {
                                    let status = response.status;
                                    let data = response.data;
                                    $("#submitDiv").hide();
                                    if (status) {
                                        Swal.fire('', data, 'success');
                                        location.reload();
                                    } else {
                                        Swal.fire('', data, 'error');
                                    }

                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $("#submitDiv").hide();
                                    if (jqXHR.status) {
                                        if (jqXHR.status == 500) {
                                            Swal.fire('', 'Request Timeout / Internal Server Error',
                                                'error');
                                        } else {
                                            Swal.fire('', jqXHR.status, 'error');
                                        }
                                    } else if (textStatus) {
                                        Swal.fire('', textStatus, 'error');
                                    } else {
                                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                            "error");
                                    }
                                }
                            })
                        } else {
                            Swal.fire('', 'Technical Error', 'error');

                        }
                    } else if (result.dismiss == "cancel") {
                        Swal.fire(
                            "Cancelled",
                            "Mark Submission Cancelled",
                            "error"
                        )
                    }
                });
            }
        }
    </script>
@endsection
