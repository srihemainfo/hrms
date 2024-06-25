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
            Practical Marks Master
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
                        <select name="exam_type" id="exam_type" class="form-control select2">
                            <option value="">Select Exam Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Arrear">Arrear</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="getData()">Submit</button>
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable text-center datatable datatable-practicalIndex"
                style="width:100%;">
                <thead>
                    <tr>
                        <th>AY</th>
                        <th>
                            Month & Year Of Exam
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Current Semester
                        </th>
                        <th>
                            Subject
                        </th>
                        <th>Subject Sem</th>
                        <th>Exam Type</th>
                        <th>
                            Total No of Students
                        </th>
                        <th>
                            Total No of Students Present
                        </th>
                        <th>
                            Total No of Students Absent
                        </th>
                        <th>
                            Mark Submitted Date
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(0, 4);
            dtButtons.splice(2, 3);

            if ($.fn.DataTable.isDataTable('.datatable-practicalIndex')) {
                $('.datatable-practicalIndex').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                deferRender: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.practical-mark-master.index') }}",
                columns: [{
                        data: 'ay',
                        name: 'ay'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'course',
                        name: 'course'
                    },
                    {
                        data: 'semester',
                        name: 'semester'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'subject_sem',
                        name: 'subject_sem'
                    },
                    {
                        data: 'exam_type',
                        name: 'exam_type'
                    },
                    {
                        data: 'total_students',
                        name: 'total_students'
                    },
                    {
                        data: 'total_present',
                        name: 'total_present'
                    },
                    {
                        data: 'total_absent',
                        name: 'total_absent'
                    },
                    {
                        data: 'submitted_date',
                        name: 'submitted_date'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data, type, full, meta) {
                            var tempData = JSON.parse(data);
                            return `<div>
                                            <a class="btn btn-xs btn-success" href="{{ url('admin/practical-mark-master/excel') }}/` +
                                tempData.batch + `/` + tempData.ay + `/` + tempData.course + `/` + tempData
                                .semester + `/` +
                                tempData.subject + `/` + tempData.subject_sem + `/` + tempData.exam_type + `/` +
                                tempData
                                .exam_month + `/` + tempData.exam_year +
                                `" target="_blank">Download Excel</a>
                                            <a class="btn btn-xs btn-danger" href="{{ url('admin/practical-mark-master/pdf') }}/` +
                                tempData.batch + `/` + tempData
                                .ay + `/` + tempData.course + `/` + tempData.semester + `/` + tempData.subject +
                                `/` + tempData
                                .subject_sem + `/` + tempData.exam_type + `/` + tempData.exam_month + `/` +
                                tempData
                                .exam_year +
                                `" target="_blank">Download PDF</a>
                                            <a class="btn btn-xs btn-info" href="{{ url('admin/practical-mark-master/edit') }}/` +
                                tempData.batch + `/` + tempData.ay + `/` + tempData.course + `/` + tempData
                                .semester + `/` +
                                tempData.subject + `/` + tempData.subject_sem + `/` + tempData.exam_type + `/` +
                                tempData
                                .exam_month + `/` + tempData.exam_year + `" target="_blank">Edit</a>
                                        </div>`;
                        }
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-practicalIndex').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function reset() {
            let reset = ['batch', 'ay', 'course', 'exam_month', 'exam_year', 'semester', 'exam_type'];
            $.each(reset, function(index, value) {
                $("#" + value).val($("#target option:first").val()).select2();
            })
            $("#tbody").html('');
        }

        function clearExamType() {
            $("#exam_type").val($("#target option:first").val()).select2();
        }

        function getData() {
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
                $("#tbody").html('<tr><td colspan="12">Loading...</td></tr>');
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                dtButtons.splice(0, 4);
                dtButtons.splice(2, 3);

                $.ajax({
                    url: "{{ route('admin.practical-mark-master.get-data') }}",
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
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        let rows = '';
                        if ($.fn.DataTable.isDataTable('.datatable-practicalIndex')) {
                            $('.datatable-practicalIndex').DataTable().destroy();
                        }
                        if (status == true) {
                            $.each(data, function(index, value) {
                                var action =
                                    `<div>
                                            <a class="btn btn-xs btn-success" href="{{ url('admin/practical-mark-master/excel') }}/` +
                                    value.batch_id + `/` + value.ay_id + `/` + value.course_id + `/` +
                                    value.semester + `/` +
                                    value.subject_id + `/` + value.subject_sem + `/` + value
                                    .exam_type + `/` + value
                                    .exam_month + `/` + value.exam_year +
                                    `" target="_blank">Download Excel</a>
                                            <a class="btn btn-xs btn-danger" href="{{ url('admin/practical-mark-master/pdf') }}/` +
                                    value.batch_id + `/` + value.ay_id + `/` + value.course_id + `/` +
                                    value.semester + `/` +
                                    value.subject_id + `/` + value.subject_sem + `/` + value
                                    .exam_type + `/` + value
                                    .exam_month + `/` + value.exam_year +
                                    `" target="_blank">Download PDF</a>
                                            <a class="btn btn-xs btn-info" href="{{ url('admin/practical-mark-master/edit') }}/` +
                                    value.batch_id + `/` + value.ay_id + `/` + value.course_id + `/` +
                                    value.semester + `/` +
                                    value.subject_id + `/` + value.subject_sem + `/` + value
                                    .exam_type + `/` + value
                                    .exam_month + `/` + value.exam_year + `" target="_blank">Edit</a>
                                        </div>`;
                                rows +=
                                    `<tr><td>${value.ay}</td><td>${value.month}</td><td>${value.course}</td><td>${value.semester}</td><td>${value.subject}</td><td>${value.subject_sem}</td><td>${value.exam_type}</td><td>${value.total_students}</td><td>${value.total_present}</td><td>${value.total_absent}</td><td>${value.submitted_date}</td><td>${action}</td></tr>`;
                            });

                        } else {

                            rows = '<tr><td colspan="12">No Data Available...</td></tr>';
                            Swal.fire('', data, 'error');
                        }
                        $("#tbody").html(rows)
                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            retrieve: true,
                            aaSorting: [],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };
                        let table = $('.datatable-practicalIndex').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if ($.fn.DataTable.isDataTable('.datatable-practicalIndex')) {
                            $('.datatable-practicalIndex').DataTable().destroy();
                        }
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
                        $("#tbody").html('<tr><td colspan="12">No Data Available...</td></tr>');
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
                        $("#saveBtnDiv").show();
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
                                    $("#submitBtnDiv").show();
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
