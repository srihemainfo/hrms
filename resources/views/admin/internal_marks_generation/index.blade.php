@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;

        }
    </style>
    <div class="card">
        <div class="card-header">
            <b>Internal Mark Generation</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class=" col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 form-group">
                    <label class=" required" for="regulation">Regulation</label>
                    <select class="form-control select2" name="regulation" id="regulation">
                        <option value="">Select Regulation</option>
                        @foreach ($reg as $i => $reg)
                            <option value="{{ $i }}">{{ $reg }}</option>
                        @endforeach
                    </select>
                </div>

                <div class=" col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 form-group ">
                    <label for="ay" class=" required">Academic Year</label>
                    <select class="form-control select2" name="ay" id="ay">
                        <option value="">Select AY</option>
                        @foreach ($ay as $i => $ay)
                            <option value="{{ $i }}">{{ $ay }}</option>
                        @endforeach
                    </select>
                </div>
                <div class=" col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 form-group ">
                    <label for="course" class=" required">Course</label>
                    <select class="form-control select2" name="course" id="course">
                        <option value="">Select Course</option>
                        @foreach ($course as $i => $course)
                            <option value="{{ $i }}">{{ $course }}</option>
                        @endforeach
                    </select>
                </div>

                <div class=" col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 form-group">
                    <label for="batch" class="required">Batch</label>
                    <select class="form-control select2" name="batch" id="batch">
                        <option value="">Select Batch</option>
                        @foreach ($batch as $i => $batch)
                            <option value="{{ $i }}">{{ $batch }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row">
                <div class="form-group  col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <label for="current_sem" class=" required">Current Semester</label>
                    <select class="form-control select2" name="current_sem" id="current_sem">
                        <option value="">Select Current Sem</option>
                        @foreach ($sem as $i => $sem)
                            <option value="{{ $i }}">{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group  col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <label for="internal" class=" required">Internal Weightage</label>
                    <select class="form-control select2" name="internal" id="internal">
                        <option value="">Select Weightage</option>
                        <option value="THEORY">THEORY</option>
                        <option value="LABORATORY">LABORATORY</option>
                        <option value="PROJECT">PROJECT</option>
                    </select>
                </div>

                <div class="form-group  col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                    <button class="enroll_generate_bn" style="margin-top:32px" id="fetch_sub"
                        onclick="fetchSubjects()">Fetch Subjects</button>
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px" id="reset">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="inter_weight">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>Internal Component</th>
                        <th>Weightage</th>

                    </tr>
                </thead>
                <tbody id="tbody2">

                </tbody>
                <tfoot id="tfoot">

                </tfoot>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Type</th>
                        {{-- <th>Remove Subject</th> --}}
                        <th>Status</th>
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
        $(document).ready(function() {
            $('#inter_weight').hide()
        })

        $("#reset").click(function() {

            $("#ay").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#regulation").val($("#target option:first").val());
            $("#current_sem").val($("#target option:first").val());
            $("#batch").val($("#target option:first").val());
            $("#internal").val($("#target option:first").val());
            $('select').select2();

            let tbody = $('#tbody')
            tbody.empty()
            let tbody2 = $('#tbody2')
            tbody2.empty()
            let tfoot = $('#tfoot')
            tfoot.empty()

        })
        // $('#regulation').change(function() {
        //     let reg = $(this).val()

        //     $.ajax({
        //         url: "{{ route('admin.internalmark_generate.weightage') }}",
        //         method: 'GET',
        //         data: {
        //             reg: reg
        //         },
        //         success: function(data) {
        //             let internal = $('#internal')
        //             internal.empty()
        //             $.each(data, function(index, value) {
        //                 internal.append(`<option value='${index}'>${value}</option>`)
        //             })
        //             internal.prepend(`<option>Please Select</option>`)

        //         }
        //     })
        // })

        function generate(data, element) {

            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Really Want To Generate !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    let next = $(element).next();
                    $(element).hide();
                    $(next).show();
                    let reg = $('#regulation').val()
                    let ay = $('#ay').val()
                    let course = $('#course').val()
                    let batch = $('#batch').val()
                    let sem = $('#current_sem').val()
                    let subject_type = $('#internal').val()
                    let sub_id = data

                    var exam_names = []
                    let theWeights = [];
                    var totals = []

                    $('#tbody2 tr').each(function(index, data) {
                        var exam_name = $(data).find('td:nth-child(1)').text();
                        var weightage = $(data).find('td:nth-child(2)').text();
                        var tempData = exam_name + '|' + weightage;
                        theWeights.push(tempData);
                        exam_names.push(exam_name);
                    })


                    $('#tfoot tr').each(function(index, data) {
                        var total = $(data).find('th:nth-child(2)').text()

                        totals.push(total)
                    });

                    $.ajax({
                        url: "{{ route('admin.internalmark_generate.generate') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'reg': reg,
                            'ay': ay,
                            'course': course,
                            'batch': batch,
                            'sem': sem,
                            'subject_type': subject_type,
                            'sub_id': sub_id,
                            'theWeights': JSON.stringify(theWeights),
                            'totals': JSON.stringify(totals),
                            'exam_names': JSON.stringify(exam_names)
                        },
                        success: function(response) {
                            let status = response.status;
                            let data = response.data;
                            $(element).show();
                            $(next).hide();
                            if (status == true) {
                                Swal.fire('', data, 'success');
                                fetchSubjects();
                            } else {
                                Swal.fire('', data, 'error');
                            }
                        }
                    })
                }
            })
        }

        function deleteData(id, element) {

            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Really Want To Delete !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    let next = $(element).next();
                    $(element).hide();
                    $(next).show();
                    $.ajax({
                        url: "{{ route('admin.internalmark_generate.delete') }}",
                        type: 'POST',
                        data: {
                            'sub_id': id,
                            'reg': $('#regulation').val(),
                            'ay': $('#ay').val(),
                            'course': $('#course').val(),
                            'batch': $('#batch').val(),
                            'sem': $('#current_sem').val(),
                            'subject_type': $('#internal').val()
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            let status = response.status;
                            $(element).show();
                            $(next).hide();
                            if (status == true) {
                                Swal.fire('', 'Data Deleted Successfully!', 'success');
                                fetchSubjects();
                            } else {
                                Swal.fire('', 'Technical Error', 'error');
                            }

                        }
                    });
                } else {
                    Swal.fire('', 'You Cancelled The Action!', 'info');
                }
            });
        }

        function fetchSubjects() {

            let tbody = $('#tbody')
            tbody.empty()
            let tbody2 = $('#tbody2')
            tbody2.empty()
            let tfoot = $('#tfoot')
            tfoot.empty()
            let row = $('<tr>')
            row.append(`<td colspan='4'>Loading...</td>`)
            tbody.append(row)

            let reg = $('#regulation').val()
            let ay = $('#ay').val()
            let course = $('#course').val()
            let batch = $('#batch').val()
            let sem = $('#current_sem').val()
            let internal = $('#internal').val()

            if ($('#regulation').val() == '') {
                Swal.fire('', 'Please Select Regulation', 'warning');
                return false;
            } else if ($('#ay').val() == '') {
                Swal.fire('', 'Please Select Ay', 'warning');
                return false;
            } else if ($('#course').val() == '') {
                Swal.fire('', 'Please Select Course', 'warning');
                return false;
            } else if ($('#batch').val() == '') {
                Swal.fire('', 'Please Select Batch', 'warning');
                return false;
            } else if ($('#current_sem').val() == '') {
                Swal.fire('', 'Please Select Semester', 'warning');
                return false;
            } else if ($('#internal').val() == '') {
                Swal.fire('', 'Please Select Internal Weightage', 'warning');
                return false;
            } else {

                $.ajax({
                    url: "{{ route('admin.internalmark_generate.fetch_subject') }}",
                    method: 'GET',
                    data: {
                        'reg': $('#regulation').val(),
                        'course': $('#course').val(),
                        'sem': $('#current_sem').val(),
                        'ay': $('#ay').val(),
                        'internal': $('#internal').val(),
                        'batch': $('#batch').val()
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            $('#inter_weight').show()
                            let sub = (response.subject)

                            let weight = (response.weightage)

                            let data = (response.weightageData)
                            let tbody = $('#tbody')
                            let tbody2 = $('#tbody2')
                            tbody.empty()
                            tbody2.empty()

                            $.each(sub, function(index, sub) {
                                // console.log(sub)
                                var subId = sub.id;
                                let row = $('<tr>')
                                row.append(`<td>${sub.subject_code}</td>`)
                                row.append(`<td>${sub.name}</td>`)
                                // row.append(`<td style="display: none"><input type="text" value="${sub.id}" name="sub_id" style="display: block"></td>`)
                                // row.append(`<td><input style="width:18px;height:18px;accent-color:red;" type="checkbox"></td>`)
                                if (sub.generated == true) {
                                    row.append(`<td class="text-success">Generated</td> `)
                                    row.append(
                                        `<td><a class="btn btn-success btn-xs" target="_blank" href="{{ url('admin/internal-mark-generation/download/') }}` +
                                        `/` + reg + `/` + batch + `/` + ay + `/` + course + `/` +
                                        sem + `/` + subId + `/` + internal +
                                        `">Download Excel</a><br><button class="btn btn-danger btn-xs" onclick="deleteData(${sub.id},this)">Delete</button><span style="display:none;" class="text-danger">Processing...</span></td>`
                                    )
                                } else {
                                    row.append(`<td class="text-danger">Not Generated</td> `)
                                    row.append(
                                        `<td><button class="btn btn-primary btn-xs btn-generate" onclick="generate(${sub.id},this)">Generate Marks</button> <span style="display:none;" class="text-primary">Generating...</span></td>`
                                    )
                                }
                                tbody.append(row)
                            })


                            $('#tfoot').empty()
                            $.each(data, function(index, data) {
                                let row = $('<tr>')
                                row.append(`<td>${data.exam_title}</td>`)
                                row.append(`<td>${data.internal_weightage}</td>`)

                                tbody2.append(row)
                            })
                            let row = $('<tr>')
                            row.append(`<th>TOTAL</th>`)
                            row.append(`<th>${weight.total}</th>`)
                            $('#tfoot').append(row)
                        } else {
                            $('#tbody').html(`<tr><td colspan="6">No Data Available...</td></tr>`);
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }
    </script>
@endsection
