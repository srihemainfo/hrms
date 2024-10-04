@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
            margin: auto;
        }
    </style>
    <a class="btn btn-default mb-2" href="{{ route('admin.internal-weightage.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card">
        <div class="card-header">
            Internal Weightage
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required" for="regulation">Regulation</label>
                    <input type="hidden" name="id" value="{{ $getData->id }}">
                    <select class="form-control select2" name="regulation" id="regulation">
                        @php
                            $reg = $getData->getRegulation ? $getData->getRegulation->id : '';
                        @endphp

                        @foreach ($regulations as $id => $entry)
                            @if ($reg == $id)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="ay">Academic Year</label>
                    <select class="form-control select2" name="ay" id="ay">
                        @php
                            $ay = $getData->getAy ? $getData->getAy->id : '';
                        @endphp
                        @foreach ($ays as $id => $entry)
                            @if ($ay == $id)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="subject_type" class="required">Subject Type</label>
                    <select class="form-control select2" name="subject_type" id="subject_type">
                        <option value="{{ $getData->subject_type }}">{{ $getData->subject_type }}</option>
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="category" class="required">Category</label>
                    <select name="category" id="category" class="form-control select2">
                        <option value="{{ $getData->category }}">{{ $getData->category }}</option>
                    </select>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row ">
                        <div class="col">
                            <button class="btn btn-primary mb-2" id="additem" style="float: right;">Add Item</button>
                            <table class="table table-bordered table-striped table-hover text-center ">
                                <thead>
                                    <tr>
                                        <th>Internal Component</th>
                                        <th>Weightage</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @php
                                        $total = 0;
                                    @endphp
                                    @if (count($weightage) > 0)
                                        @foreach ($weightage as $data)
                                            <tr>
                                                <td>{{ $data->exam_title }}</td>
                                                <td class="weight">{{ $data->internal_weightage }}</td>
                                            </tr>
                                            @php
                                                $total += (int) $data->internal_weightage;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot id="tfoot">
                                    <tr>
                                        <th>Total Weightage</th>
                                        <th>{{ $total }}</th>
                                    </tr>
                                </tfoot>
                            </table>
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
        window.onload = function() {
            fetchCat();
        }
        $('#additem').click(function() {

            if ($('#subject_type').val() != '' && $("#regulation").val() != '' && $("#ay").val() != '') {
                $('#subject_span').hide()
                $('#internal_span').hide()
                $('#internal').val('')
                $('#project').val('')
                $('#exam_title option:first').val()

                $('#internalweightage').modal()
            } else {
                $('#subject_span').show()
            }

        })

        function fetchCat() {

            if ($('#category').val() != '' && $("#regulation").val() != '' && $("#ay").val() !=
                '') {
                $('#exam_title').html(`<option>Loading...</option>`);
                $.ajax({
                    url: "{{ route('admin.internal-weightage.fetch_cat') }}",
                    method: 'GET',
                    data: {
                        'regulation': $("#regulation").val(),
                        'category': $("#category").val(),
                        'ay': $("#ay").val()
                    },
                    success: function(data) {

                        if (data.result == 'project') {
                            $('#exam_inp').hide();
                            $('#project_inp').show();
                        } else {
                            $('#project_inp').hide();
                            $('#exam_inp').show();

                            let select = $('#exam_title')
                            select.empty()
                            select.prepend(
                                `<option value="default">Select Exam Title</option>`
                            )
                            $.each(data, function(index, d) {
                                select.append(
                                    `<option value='${d.name}'>${d.name}</option>`
                                )
                            })
                        }


                    }
                })
            } else {
                $('#exam_title').empty()
            }
        }
        var total = 0;

        function calculateTotal() {
            let weights = $(".weight");
            let weights_len = weights.length;
            total = 0;
            for (let u = 0; u < weights_len; u++) {
                total += parseInt($(weights[u]).html());
            }
        }

        $('#save').click(function() {
            calculateTotal();
            let cat = $('#category').val()
            let exam = $('#exam_title').val()
            var internal = $('#internal').val()
            var project = $('#project').val()



            if (cat != 'PROJECT' && internal != '' && exam !== '' && project == '') {
                total += parseInt(internal);
                let body = $('#tbody')
                let row = $('<tr>')
                let foot = $('#tfoot')

                row.append(`<td>${exam}</td>`)
                row.append(`<td class="weight">${internal}</td>`)

                body.append(row)
                foot.html(`<tr><th>Total Weightage</th><th>${total}</th></tr>`)
                $('#category').prop('disabled', true)
                $('#internalweightage').modal('hide')
            } else if (cat == 'PROJECT' && internal != '' && project != '') {
                total += parseInt(internal);
                let body = $('#tbody')
                let row = $('<tr>')
                let foot = $('#tfoot')

                row.append(`<td>${project}</td>`)
                row.append(`<td  class="weight">${internal}</td>`)
                body.append(row)
                foot.html(`<tr><th>Total Weightage</th><th>${total}</th></tr>`)
                $('#category').prop('disabled', true)
                $('#internalweightage').modal('hide')
            } else {
                $('#internal_span').show()
            }


        })

        $('#submit').click(function() {
            let cat = $('#category').val()
            let reg = $('#regulation').val()
            let ay = $('#ay').val()
            let id = $('#id').val()
            let sub_type = $('#subject_type').val()
            var arr_internal = []
            if (reg !== '' && sub_type !== '') {

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

                $.ajax({
                    url: "{{ route('admin.internal-weightage.store') }}",
                    method: 'GET',
                    data: {
                        reg: reg,
                        cat: cat,
                        ay: ay,
                        id: id,
                        subject_type: sub_type,
                        weightage: JSON.stringify(arr_internal),
                        total: total
                    },
                    success: function(data) {

                        Swal.fire('', data, 'success')
                        $('#regulation').val('')
                        $('#regulation').select2()
                        $('#subject_type').empty().prepend('Select Subject Type')
                        $('#exam_title').empty().prepend('Select Subject Type')
                        $('#category').prop('disabled', false)
                        $('#category').val('')
                        $('#category').select2()
                        total = 0
                        $('tbody').empty()
                        $('tfoot').empty()
                    }
                })

            } else {
                Swal.fire('', 'No row available', 'error')
            }
        })
    </script>
@endsection
