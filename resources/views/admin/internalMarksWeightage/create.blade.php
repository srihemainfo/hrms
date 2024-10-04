@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
            margin: auto;
        }
    </style>

    <div class="card">
        <div class="card-header">
            Internal Weightage
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required" for="regulation">Regulation</label>
                    <select class="form-control select2" name="regulation" id="regulation">
                        <option value="">Select Regulation</option>
                        @foreach ($regulations as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="ay">Academic Year</label>
                    <select class="form-control select2" name="ay" id="ay">
                        <option value="">Select Academic Year</option>
                        @foreach ($ays as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="subject_type" class="required">Subject Type</label>
                    <select class="form-control select2" name="subject_type" id="subject_type">
                        <option value="">Select Subject Type</option>
                        <option value="THEORY">THEORY</option>
                        <option value="LABORATORY">LABORATORY</option>
                        <option value="PROJECT">PROJECT</option>
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="sem" class="required">Semester</label>
                    <select class="form-control select2" name="sem" id="sem">
                        <option value="">Select Semester</option>
                        @foreach ($sem as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <span id="subject_span" class="text-danger text-center"
                        style="display:none; font-size:0.9rem;padding-left:10px;">All
                        fileds Required</span>
                </div>
                <div class="form-group col-xl-9 col-lg-9 col-md-4 col-sm-6 col-12">
                    <button class="btn btn-primary mb-2" id="additem" style="float: right;">Add Item</button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row ">
                        <div class="col">
                            <table class="table table-bordered table-striped table-hover text-center ">
                                <thead>
                                    <tr>
                                        <th>Internal Component</th>
                                        <th>Weightage</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                </tbody>
                                <tfoot id="tfoot">

                                </tfoot>
                            </table>
                            <button class="btn btn-success " id="submit" style="float: right;">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="internalweightage" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">

                        <div class="col-md-4 ms-auto form-group">
                            <label for="category" class="required">Category</label>
                            <select name="category" id="category" class="form-control select2">
                                <option value="">Select Category</option>
                                <option value="CAT">CAT</option>
                                <option value="LAB">LAB</option>
                                <option value="ASSIGNMENT">ASSIGNMENT</option>
                                <option value="PROJECT">PROJECT</option>
                            </select>
                            <span id="category_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;">Please Select Category</span>
                        </div>
                        <div id="exam_inp" class="col-md-4 ms-auto form-group">
                            <label for="exam_title" class="required">Exam Title</label>
                            <select name="exam_title" id="exam_title" class="form-control select2">
                                <option value="default">Select Exam Title</option>
                            </select>

                            <span id="exam_title_span" class="text-danger text-center"
                                style="display:none; font-size:0.9rem;">
                                Exam Title Is Required</span>
                        </div>
                        <div id="project_inp" class="col-md-4 ms-auto form-group" style="display: none;">
                            <label for="project" class="required">Exam Title</label>

                            <input type="text" class="form-control" name="project" id="project">
                            <span id="exam_title_span" class="text-danger text-center"
                                style="display:none; font-size:0.9rem;">
                                Project Is Required</span>
                        </div>
                        <div class="col-md-4 ms-auto form-group">
                            <label for="internal" class="required">Internal Weightage</label>
                            <input type="number" name="internal" id="internal" class="form-control">

                        </div>

                    </div>
                </div>
                <span id="internal_span" class="text-danger text-center" style="display:none; font-size:0.9rem;">
                    All fields is Required</span>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="save">Add</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let catTitle = '';
        let labTitle = '';
        let projectTitle = '';
        $('#additem').click(function() {

            if ($('#subject_type').val() != '' && $("#regulation").val() != '' && $("#ay").val() != '' && $('#sem')
                .val()) {
                $('#subject_span').hide()
                $('#internal_span').hide()
                $('#internal').val('')
                $("#project_inp").hide()
                $("#exam_inp").show()

                if ($('#subject_type').val() == 'THEORY') {
                    $("#category").html(
                        `<option value="CAT">CAT</option><option value="ASSIGNMENT">ASSIGNMENT</option>`);
                } else if ($('#subject_type').val() == 'LABORATORY') {
                    $("#category").html(`<option value="LAB">LAB</option>`);
                } else {
                    $("#category").html(`<option value="PROJECT">PROJECT</option>`);
                }


                if (($('#subject_type').val() == 'THEORY' && catTitle == '') || ($('#subject_type').val() ==
                        'LABORATORY' && labTitle == '') || ($('#subject_type').val() == 'PROJECT' && projectTitle ==
                        '')) {
                    if ($('#category').val() != '' && $("#regulation").val() != '' && $("#ay").val() !=
                        '') {
                        $('#exam_title').html(`<option>Loading...</option>`);
                        $.ajax({
                            url: "{{ route('admin.internal-weightage.fetch_cat') }}",
                            method: 'GET',
                            data: {
                                'regulation': $("#regulation").val(),
                                'category': $("#category").val(),
                                'sem': $("#sem").val(),
                                'ay': $("#ay").val(),
                                'subject_type': $("#subject_type").val()
                            },
                            success: function(response) {
                                let status = response.status;
                                if (status == true) {
                                    let category = response.category;
                                    let exam_title = response.exam_title;
                                    let theData = '';
                                    $.each(exam_title, function(index, d) {
                                        theData +=
                                            `<option value='${d.name}'>${d.name}</option>`;
                                    })
                                    if (category == 'LAB') {
                                        labTitle = theData;
                                    } else if (category == 'CAT') {
                                        catTitle = theData;
                                    } else {
                                        projectTitle = theData;
                                    }
                                    if ($('#subject_type').val() == 'THEORY') {
                                        $('#exam_title').html(catTitle)
                                    }
                                    if ($('#subject_type').val() == 'LABORATORY') {
                                        $('#exam_title').html(labTitle)
                                    }
                                    if ($('#subject_type').val() == 'PROJECT') {
                                        $('#exam_title').html(projectTitle)
                                    }
                                    $('#internalweightage').modal()
                                } else {
                                    Swal.fire('', 'The Internal Weightage Already Created.', 'warning');
                                }
                            }
                        })
                    }
                } else {
                    $('#internalweightage').modal()
                }

                if ($('#subject_type').val() == 'THEORY') {
                    $('#exam_title').html(catTitle)
                }
                if ($('#subject_type').val() == 'LABORATORY') {
                    $('#exam_title').html(labTitle)
                }
                if ($('#subject_type').val() == 'PROJECT') {
                    $('#exam_title').html(projectTitle)
                }

            } else {
                $('#subject_span').show()
            }
        })
        $('#category').change(function() {
            if ($('#subject_type').val() == 'THEORY') {

                if ($('#category').val() == 'ASSIGNMENT') {
                    $('#exam_title').html('')
                    $("#internal").val(50)
                    $("#exam_inp").hide()
                } else {
                    $('#exam_title').html(catTitle)
                    $("#internal").val('')
                    $("#exam_inp").show()
                }
            }
        })
        var total = 0;
        $('#save').click(function() {
            let cat = $('#category').val()
            let exam = $('#exam_title').val()
            var internal = $('#internal').val()

            if (cat != 'ASSIGNMENT' && internal != '' && exam != '') {
                total += parseInt(internal);
                let body = $('#tbody')
                let row = $('<tr>')
                let foot = $('#tfoot')
                row.append(`<td>${exam}</td>`)
                row.append(`<td>${internal}</td>`)
                body.append(row)
                foot.html(`<tr><th>Total Weightage</th><th>${total}</th></tr>`)

                $('#internalweightage').modal('hide')
            } else if (cat == 'ASSIGNMENT' && internal != '') {
                total += parseInt(internal);
                let body = $('#tbody')
                let row = $('<tr>')
                let foot = $('#tfoot')

                row.append(`<td>Assignment</td>`)
                row.append(`<td>${internal}</td>`)

                body.append(row)
                foot.html(`<tr><th>Total Weightage</th><th>${total}</th></tr>`)
                $('#internalweightage').modal('hide')
            } else {
                $('#internal_span').show()
            }


        })

        $('#submit').click(function() {
            let cat = $('#category').val()
            let reg = $('#regulation').val()
            let ay = $('#ay').val()
            let sem = $('#sem').val()
            let sub_type = $('#subject_type').val()
            var arr_internal = []
            if (reg != '' && sub_type != '' && sem != '' && ay != '') {

                $('#tbody tr').each(function(index, row) {
                    var exam = $(row).find('td:nth-child(1)').text()
                    var internal = $(row).find('td:nth-child(2)').text()

                    arr_internal.push({
                        exam_title: exam,
                        internal_weightage: internal
                    })
                })
                $('#tfoot tr').each(function(index, data) {
                    var total = $(data).find('th:nth-child(2)').text()

                })
                if (cat == 'ASSIGNMENT') {
                    cat = 'CAT';
                }
                $.ajax({
                    url: "{{ route('admin.internal-weightage.store') }}",
                    method: 'GET',
                    data: {
                        reg: reg,
                        cat: cat,
                        ay: ay,
                        sem: sem,
                        subject_type: sub_type,
                        weightage: JSON.stringify(arr_internal),
                        total: total
                    },
                    success: function(data) {

                        Swal.fire('', data, 'success')
                        total = 0
                        $('tbody').empty()
                        $('tfoot').empty()
                        window.location.href = `{{ route('admin.internal-weightage.index') }}`;
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })

            } else {
                Swal.fire('', 'No row available', 'error')
            }
        })
    </script>
@endsection
