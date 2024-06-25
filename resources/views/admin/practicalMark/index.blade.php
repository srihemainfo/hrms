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
                            <option value="">Select Batch</option>
                            @foreach ($batches as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Academic Year</label>
                        <select name="ay" id="ay" class="form-control select2">
                            <option value="">Select AY</option>
                            @foreach ($ays as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <label class="required" for="exam_month">Exam Month </label>
                    <select class="form-control select2" name="exam_month" id="exam_month">
                        <option value="">Select Exam Month</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <label for="exam_year" class="required">Exam Year</label>
                    <select class="form-control select2 " name="exam_year" id="exam_year">
                        <option value="">Select Exam Year</option>
                        @foreach ($years as $id => $entry)
                            <option value="{{ $entry }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Course</label>
                        <select name="course" id="course" class="form-control select2">
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Current Semester</label>
                        <select name="semester" id="semester" class="form-control select2" onchange="clearExamType()">
                            <option value="">Select Semester</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Exam Type</label>
                        <select name="exam_type" id="exam_type" class="form-control select2" onchange="getSubjects()">
                            <option value="">Select Exam Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Arrear">Arrear</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Subject</label>
                        <select name="subject" id="subject" class="form-control select2">
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;" onclick="getStudents()">Fetch
                            Students</button>
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
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
                </tbody>
            </table>
            <div class="row text-center mt-3" id="buttonsDiv" style="display:none;">
                <div class="col-4">
                    <button class="enroll_generate_bn bg-success" onclick="save()" id="saveBtnDiv">Save & Exit</button>
                    <div class="text-success" id="saveDiv" style="display:none;"><b>Saving...</b></div>
                </div>
                <div class="col-4"><button class="enroll_generate_bn bg-info" style="display:none;" id="previewBtn" onclick="preview()">Preview &
                        Print</button>
                </div>
                <div class="col-4">
                    <button class="enroll_generate_bn bg-success" onclick="submit()" id="submitBtnDiv">Final
                        Submit</button>
                    <div class="text-success" id="submitDiv" style="display:none;"><b>Processing...</b></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function reset() {
            let reset = ['batch', 'ay', 'course', 'exam_month', 'exam_year', 'semester', 'exam_type', 'subject'];
            $.each(reset, function(index, value) {
                $("#" + value).val($("#target option:first").val()).select2();
                $("#" + value).attr('disabled', false);
            })
            $("#tbody").html('');
            $("#buttonsDiv").hide()
            $("#previewBtn").hide()
        }

        function clearExamType() {
            $("#exam_type").val($("#target option:first").val()).select2();
        }

        function getSubjects() {
            $("#buttonsDiv").hide()
            $("#tbody").html('<tr><td colspan="5">No Data Available...</td></tr>');
            let check = [{
                'element': 'batch',
                'reason': 'Please Select Batch'
            }, {
                'element': 'ay',
                'reason': 'Please Select AY'
            }, {
                'element': 'course',
                'reason': 'Please Select Course'
            }, {
                'element': 'exam_month',
                'reason': 'Please Select Exam Month'
            }, {
                'element': 'exam_year',
                'reason': 'Please Select Exam Year'
            }, {
                'element': 'semester',
                'reason': 'Please Select Semester'
            }, {
                'element': 'exam_type',
                'reason': 'Please Select Exam Type'
            }];
            let forward = true;
            $.each(check, function(index, value) {

                if ($("#" + value.element).val() == '') {
                    Swal.fire('', value.reason, 'error');
                    forward = false;
                    return false;
                }
            })
            if (forward) {
                $("#subject").html('<option value="">Loading...</option>');
                $.ajax({
                    url: "{{ route('admin.practical-marks.get-subjects') }}",
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
                        'exam_type': $("#exam_type").val()
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            let data_len = data.length;
                            let subjects = '';
                            if (data_len > 0) {
                                subjects += `<option value=""> Select Subject</option>`;
                                for (let i = 0; i < data_len; i++) {
                                    subjects +=
                                        `<option value="${data[i].subject_id}">${data[i].subject_name}  (${data[i].subject.subject_code})</option>`;
                                }
                            }
                            $("#subject").html(subjects);
                            $("#subject").select2();

                        } else {
                            Swal.fire('', data, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                        }
                    }
                })
            }
        }

        function getStudents() {
            $("#buttonsDiv").hide()
            $("#previewBtn").hide()
            let check = [{
                'element': 'batch',
                'reason': 'Please Select Batch'
            }, {
                'element': 'ay',
                'reason': 'Please Select AY'
            }, {
                'element': 'course',
                'reason': 'Please Select Course'
            }, {
                'element': 'exam_month',
                'reason': 'Please Select Exam Month'
            }, {
                'element': 'exam_year',
                'reason': 'Please Select Exam Year'
            }, {
                'element': 'semester',
                'reason': 'Please Select Semester'
            }, {
                'element': 'exam_type',
                'reason': 'Please Select Exam Type'
            }, {
                'element': 'subject',
                'reason': 'Please Select Subject'
            }];
            let forward = true;
            $.each(check, function(index, value) {

                if ($("#" + value.element).val() == '') {
                    Swal.fire('', value.reason, 'error');
                    forward = false;
                    return false;
                }
            })
            if (forward) {
                $("#tbody").html('<tr><td colspan="5">Loading...</td></tr>');
                $("select").attr('disabled', true);
                $.ajax({
                    url: "{{ route('admin.practical-marks.get-students') }}",
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
                        'subject': $("#subject").val()
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        let rows = '';
                        if (status == true) {
                            $("#buttonsDiv").show()
                            let datalen = data.length;
                            if (response.exist) {
                                $("#saveBtnDiv").show()
                                if (data[0].action == 1) {
                                    $("#submitBtnDiv").hide()
                                    $("#saveBtnDiv").hide()
                                    Swal.fire('', 'Marks Already Submitted', 'info');
                                }
                                $.each(data, function(index, value) {
                                    rows +=
                                        `<tr><td>${index + 1}</td><td>${value.student != undefined ? value.student.register_no : value.register_no}</td><td>${value.student != undefined ? value.student.name : value.name}</td><td><input class="form-control marks" style="width:40%;margin:auto;border-radius:5px;${value.action != 0 ? 'background-color: #efefef;':''} " type="number" id="${value.user_name_id}" onchange="checkMark(this,${value.user_name_id})" value="${value.mark}" ${value.action != 0 ? "disabled":""} /></td><td id="w${value.user_name_id}">${value.mark_in_word != null ? value.mark_in_word : ''}</td></tr>`;
                                });
                                $("#previewBtn").show();
                            } else {
                                $.each(data, function(index, value) {
                                    rows +=
                                        `<tr><td>${index + 1}</td><td>${value.student != undefined ? value.student.register_no : value.register_no}</td><td>${value.student != undefined ? value.student.name : value.name}</td><td><input class="form-control marks" style="width:40%;margin:auto;border-radius:5px;" type="number" id="${value.user_name_id}" onchange="checkMark(this,${value.user_name_id})"/></td><td id="w${value.user_name_id}"></td></tr>`;
                                });
                            }
                        } else {
                            $("#buttonsDiv").hide()
                            rows = '<tr><td colspan="5">No Data Available...</td></tr>';
                            Swal.fire('', data, 'error');
                            $("select").attr('disabled', false);
                        }
                        $("#tbody").html(rows)

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#buttonsDiv").hide()
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                        }
                        $("#tbody").html('<tr><td colspan="5">No Data Available...</td></tr>');
                        $("select").attr('disabled', false);
                    }
                })
            }
        }

        function checkMark(element, id) {

            if ($(element).val() != '') {
                let value = $(element).val();
                if (isNaN($(element).val()) || parseInt($(element).val()) < -1 || parseInt($(element).val()) > 100 || value
                    .length > 3) {
                    Swal.fire('', 'Mark Is Not Valid', 'error');
                    $(element).val('')
                    $("#w" + id).html('')
                    return false;
                } else {

                    let includes = value.includes('-');
                    if (includes && $(element).val() != -1) {
                        Swal.fire('', 'Mark Is Not Valid', 'error');
                        $(element).val('');
                        $("#w" + id).html('');
                        return false;
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
            console.log(givenNum)
            console.log(numb)
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

        function save() {
            let datas = [];
            $.each($(".marks"), function() {
                if ($(this).attr('id') == '') {
                    Swal.fire('', 'Technical Error', 'error');
                    return false;
                }
                var id = $(this).attr('id');
                datas.push([$(this).attr('id'), $(this).val(), $('#w' + id).html()]);
            })
            if (datas.length > 0) {
                $("#saveDiv").show();
                $("#saveBtnDiv").hide();
                $.ajax({
                    url: "{{ route('admin.practical-marks.store-students') }}",
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
                        'action': 0,
                        'data': datas
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        $("#saveDiv").hide();

                        if (status) {
                            Swal.fire('', data, 'success');
                            $("#previewBtn").show()
                        } else {
                            Swal.fire('', data, 'error');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#saveDiv").hide();
                        $("#saveBtnDiv").show();
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                        }
                    }
                })
            } else {
                Swal.fire('', 'Technical Error', 'error');
                return false;
            }
        }

        function submit() {
            $(".marks").css('border', '1px solid #cfd1d8');
            let datas = [];
            let theAction = true;
            $.each($(".marks"), function() {
                if ($(this).attr('id') == '') {
                    Swal.fire('', 'Technical Error', 'error');
                    theAction = false;
                    return false;
                }
                if ($(this).val() == '') {
                    Swal.fire('', "Marks Field Can't Be Empty", 'error');
                    $(this).css('border', '2px solid red');
                    theAction = false;
                    return false;
                }

                var id = $(this).attr('id');
                datas.push([$(this).attr('id'), $(this).val(), $('#w' + id).html()]);
            })

            if (theAction) {
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Once the marks are submitted, it couldn’t edit later ?",
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
                                url: "{{ route('admin.practical-marks.store-students') }}",
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
                                    'action': 1,
                                    'data': datas
                                },
                                success: function(response) {
                                    let status = response.status;
                                    let data = response.data;
                                    $("#submitDiv").hide();
                                    $("#saveBtnDiv").hide()
                                    $("#previewBtn").show()
                                    if (status) {
                                        Swal.fire('', data, 'success');

                                    } else {
                                        Swal.fire('', data, 'error');
                                    }

                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $("#saveDiv").hide();
                                    $("#saveBtnDiv").show();
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
                            return false;
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

        function preview() {
            let datas = [];
            $.each($(".marks"), function() {
                if ($(this).attr('id') == '') {
                    Swal.fire('', 'Technical Error', 'error');
                    return false;
                }
                var id = $(this).attr('id');
                datas.push([$(this).attr('id'), $(this).val(), $('#w' + id).html()]);
            })
            if (datas.length > 0) {

                $.ajax({
                    url: "{{ route('admin.practical-marks.printing-data') }}",
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
                        'subject': $("#subject").val()
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status) {
                            window.open("{{ route('admin.practical-marks.preview') }}", '_blank');
                        } else {
                            Swal.fire('', data, 'error');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                        }
                    }
                })

            } else {
                Swal.fire('', 'Technical Error', 'error');
                return false;
            }
        }
    </script>
@endsection
