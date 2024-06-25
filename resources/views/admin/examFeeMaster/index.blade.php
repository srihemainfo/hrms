@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <button class="btn btn-outline-success" onclick="openModal()">
        Create Exam Fee Master
    </button>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-ExamFees text-center">
                <thead>
                    <tr>
                        <th></th>
                        <th>S.No</th>
                        <th>Regulation</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="secondLoader"></div>

    </div>

    <div class="modal fade" id="sub_typeModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="regulation" class="required">Regulation</label>
                            <input type="hidden" name="reg_id" id="reg_id" value="">
                            <select class="form-control select2" style="text-transform:uppercase" id="regulation"
                                name="regulation" value="" onchange="checkRegulation()">
                                <option value="">Select Regulation</option>
                                @foreach ($reg as $id => $r)
                                    <option value="{{ $id }}">{{ $r }}</option>
                                @endforeach
                            </select>
                            <span id="regulation_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group tbl-fees">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <th>Subject Type</th>
                                    <th>Exam Fees</th>
                                </thead>
                                <tbody id="tbody">
                                    <tr>

                                        <td colspan="2">No Data Available</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group tbl-fees2"
                            style="display: none">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <th>Subject Type</th>
                                    <th>Exam Fees</th>
                                    <th>Remove</th>
                                </thead>
                                <tbody id="tbody2">
                                    <tr>
                                        <td colspan="3">No Data Available</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 form-group tbl-fees tbl-view">
                            <label for="regulation" class="required">Subject Type</label>
                            <select class="form-control select2" style="text-transform:uppercase" id="subject_type"
                                name="subject_type" value="">
                                <option value="">Select Subject Type</option>
                            </select>
                            <span id="subject_type_span"
                                class="text-danger text-center"style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 form-group tbl-fees tbl-view">
                            <label for="regulation" class="required">Exam Fees</label>
                            <input type="text" class="form-control" name="exam_fees" id="exam_fees" value="">
                            <span id="exam_fees_span" class="text-danger text-center"
                                style="display:none; font-size:0.9rem;"></span>

                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 form-group tbl-fees text-center tbl-view">
                            <button onclick="addFees()" id="addFees" class="newViewBtn"
                                style="font-size: 1.7rem; padding-top: 30px !important;" title="Add Fee"><i
                                    class="far fa-plus-square"></i></button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveExamfee()">Save</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        var rowInserted = 0;
        var count = 0
        let count1 = 0
        $(function() {
            // $('.tbl-fees').hide()
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            let deleteButton = {
                text: 'Delete Selected',
                className: 'btn-outline-danger',
                action: function(e, dt, node, config) {
                    var ids = $.map(dt.rows({
                        selected: true
                    }).data(), function(entry) {
                        return entry.id
                    });

                    if (ids.length === 0) {
                        Swal.fire('', 'No Rows Selected', 'warning');
                        return
                    }

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
                            $('.secondLoader').show()
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: "{{ route('admin.examfee-master.massDestroy') }}",
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function(response) {
                                    $('.secondLoader').hide()
                                    Swal.fire('', response.data, response.status);
                                    callAjax()
                                })
                        }
                    })
                }
            }
            dtButtons.push(deleteButton)

            if ($.fn.DataTable.isDataTable('.datatable-ExamFees')) {
                $('.datatable-ExamFees').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.examfee-master.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'regulation',
                        name: 'regulation'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-ExamFees').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#regulation").val('').prop('disabled', false)
            $('.tbl-fees').hide()
            $('.tbl-fees2').hide()
            $('#tbody').empty().append(`<tr><td colspan="2" class="text-center">No Data Available</td></tr>`)
            $('#tbody2').empty().append(`<tr><td colspan="3" class="text-center">No Data Available</td></tr>`).hide()
            $("#regulation").select2();
            $("#sub_type").val('')
            $("#sub_type_id").val('')
            $("#regulation_span").hide();
            $('#exam_fees_span').hide()
            $('#subject_type_span').hide()
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#sub_typeModal").modal();
            rowInserted = 0
        }

        function checkRegulation() {
            if ($("#regulation").val() == '') {
                $("#regulation_span").html(`Regulation Is Required.`);
                $("#regulation_span").show();
            } else {
                $.ajax({
                    url: "{{ route('admin.examfee-master.checkRegulation') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': $("#regulation").val()
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            $("#regulation").prop('disabled', true);
                            $('.tbl-fees').show()
                            $('.tbl-fees2').hide()
                            $("#tbl-view").show();
                            $("#addFees").show();
                            let select = $('#subject_type').empty()
                            $.each(data, function(index, d) {
                                select.append(`<option value="${d.id}">${d.name}</option>`)
                            })
                            select.prepend(`<option value="" selected>Select Subject Type</option>`)

                        } else {
                            $("#sub_typeModal").modal('hide');
                            Swal.fire('', data, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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
            }
        }


        function addFees() {
            console.log(rowInserted);
            if ((rowInserted == 0 || rowInserted != 0) && count == 0) {
                if ($('#subject_type').val() == '') {
                    console.log('hii');
                    $("#subject_type_span").html(`Subject Type Required.`);
                    $('#subject_type_span').show()
                    $('#exam_fees_span').hide()
                } else if ($('#exam_fees').val() == '') {
                    $("#exam_fees_span").html(`Exam Fees Required.`);
                    $('#exam_fees_span').show()
                    $('#subject_type_span').hide()
                } else {
                    $('#exam_fees_span').hide()
                    $('#subject_type_span').hide()
                    let body = $('#tbody')
                    row = `<tr class="examFeeRow">
                                  <td>${$('#subject_type option:selected').text()}<input type="hidden" id="subject_type_${rowInserted}" value="${$('#subject_type').val()}"></td>
                                  <td>${$('#exam_fees').val()}<input type="hidden" id="exam_fee_${rowInserted}" value="${$('#exam_fees').val()}"></td>
                              </tr>`;


                    if (rowInserted == 0) {
                        $("#tbody").html(row);
                    } else {
                        $("#tbody").append(row);
                    }
                    rowInserted += 1;

                    $('#subject_type').val('')
                    $('#subject_type').select2()
                    $('#exam_fees').val('')

                }
            }
            if (count) {
                console.log($('#subject_type option:selected').text(), $('#exam_fees').val());
                let body2 = $('#tbody2')
                let reg_id = $('#reg_id').val()
                row = `<tr class="examFeeRow2">
                                  <td>${$('#subject_type option:selected').text()}<input type="hidden" id="subject_type2_${count1}" value="${$('#subject_type').val()}"> <input type="hidden" id="id2_${count1}" value="${$('#subject_type').val()}"></td>
                                  <td>${$('#exam_fees').val()}<input type="hidden" id="exam_fee2_${count1}" value="${$('#exam_fees').val()}"></td>
                              </tr>`;


                if (count == 0) {
                    body2.html(row);
                } else {
                    body2.append(row);
                }
                count1 += 1;

                $('#subject_type').val('')
                $('#subject_type').select2()
                $('#exam_fees').val('')
            }

        }

        function saveExamfee() {

            if ($('#reg_id').val() == 0 || $('#reg_id').val() == '' || $('#reg_id').val() == null) {
                let data_len = $(".examFeeRow").length;
                if (data_len < 1) {
                    Swal.fire('', 'Please Add Data', 'error');
                    return false;
                } else {
                    $('#loading_div').show()
                    $('#save_div').hide()
                    let regulation = $("#regulation").val();
                    if (regulation != '') {
                        let formDataArray = [];
                        let subject_type;
                        let exam_fee;
                        let formData;

                        for (let i = 0; i < data_len; i++) {

                            subject_type = 'subject_type_' + i;
                            exam_fee = 'exam_fee_' + i;

                            formData = {
                                'subject_type': $("#" + subject_type).val(),
                                'exam_fee': $("#" + exam_fee).val(),
                            };
                            formDataArray.push(formData);
                        }
                        let formLen = formDataArray.length;
                        if (formLen > 0) {
                            $.ajax({
                                url: '{{ route('admin.examfee-master.store') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'regulation': regulation,
                                    'data': formDataArray
                                },
                                success: function(response) {
                                    let status = response.status;
                                    if (status == true) {
                                        Swal.fire('', 'Exam Fee Master Created', 'success');

                                    } else {
                                        Swal.fire('', 'Exam Fee Master Not Created', 'error');
                                    }
                                    $('#loading_div').hide()
                                    $("#sub_typeModal").modal('hide');
                                    callAjax();
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
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
                            Swal.fire('', 'Please Add Data', 'error');
                            return false;
                        }
                    } else {
                        Swal.fire('', 'Please Choose Regulation', 'error');
                        return false;
                    }
                }
            } else if ($('#reg_id').val() != 0) {
                let data_len2 = $(".examFeeRow2").length;
                console.log(data_len2)
                if (data_len2 < 1) {
                    Swal.fire('', 'Please Add Data', 'error');
                    return false;
                } else {
                    let regulation2 = $("#regulation").val();
                    if (regulation2 != '') {
                        let formDataArray2 = [];
                        let subject_type2;
                        let exam_fee2;
                        let id2;
                        let formData2;

                        for (let i = 0; i < data_len2; i++) {

                            subject_type2 = 'subject_type2_' + i;
                            exam_fee2 = 'exam_fee2_' + i;
                            id2 = 'id2_' + i;
                            formData2 = {
                                'subject_type': $("#" + subject_type2).val(),
                                'exam_fee': $("#" + exam_fee2).val(),
                                'id': $("#" + id2).val(),
                            };
                            formDataArray2.push(formData2);
                        }
                        console.log(formDataArray2);
                        let formLen2 = formDataArray2.length;
                        if (formLen2 > 0) {
                            $('#loading_div').show()
                            $('#save_div').hide()
                            $.ajax({
                                url: '{{ route('admin.examfee-master.update') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'regulation': regulation2,
                                    'data': formDataArray2,
                                    'removable_id': removableId2
                                },
                                success: function(response) {
                                    let status = response.status;
                                    let data = response.data;
                                    if (status == true) {
                                        Swal.fire('', data, 'success');
                                    } else {
                                        Swal.fire('', data, 'error');
                                    }
                                    $('#loading_div').hide()
                                    $('#save_div').show()
                                    $('#sub_typeModal').modal('hide')
                                    callAjax()
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
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
                            Swal.fire('', 'Please Add Data', 'error');
                            return false;
                        }
                    } else {
                        Swal.fire('', 'Regulation Not Found', 'error');
                        return false;
                    }
                }
            }

        }

        function viewExamfee(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.examfee-master.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        $("#sub_typeModal").modal();
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            $("#sub_type_id").val(data[0].regulations.id);
                            $("#regulation").val(data[0].regulations.id).prop('disabled', true);
                            $("#regulation").select2();
                            $(".tbl-fees2").hide();
                            $(".tbl-fees").show();
                            $(".tbl-view").hide();
                            $("#exam_fee_span").hide();
                            $("#save_div").hide();
                            $("#regulation_span").hide();
                            $("#subject_type_span").hide();
                            $("#exam_fee_span").hide();
                            $("#loading_div").hide();

                            let body = $('#tbody').empty()
                            $.each(data, function(index, d) {
                                row = `<tr class="examFeeRow">
                                        <td>${d.subject_types.name}</td>
                                        <td>${d.fee}</td>
                                    </tr>`;
                                body.append(row)
                            })
                            $("#sub_typeModal").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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
            }
        }

        // let rowInserted = parseInt($("#totalRow").val());
        let removableId2 = [];

        function editExamfee(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.examfee-master.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        $("#sub_typeModal").modal();
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            var subject = response.sub;
                            let rowInserted2 = data.length;
                            $("#sub_type_id").val(data[0].regulations.id);
                            $("#regulation").val(data[0].regulations.id).prop('disabled', true);
                            $("#regulation").select2()
                            let body = $('#tbody2').empty()
                            let i = 0;
                            $.each(data, function(index, d) {
                                row = `<tr class="examFeeRow">
                                        <td>
                                            ${d.subject_types.name}
                                            <input type="hidden" id="subject_type_"+i
                                            value="${ d.subject_type_id }">
                                            <input type="hidden" id="id_"+i value="${d.id}">
                                        </td>
                                        <td>
                                            ${d.fee}  <input type="hidden" id="exam_fee_"+i
                                            value="${d.fee}">
                                        </td>
                                        <td>
                                            <input type="checkbox" id="checkbox_"+i
                                            value="${d.id}" style="width:18px;height:18px;accent-color:red;"
                                            onchange="checkCheckBox(this)">
                                        </td>
                                    </tr>`;
                                body.append(row)
                                i += 1;
                            })

                            let subject_type = $('#subject_type').empty().append(
                                `<option value="">Select Subject Type</option>`)
                            $.each(subject, function(index, sub) {
                                subject_type.append(`<option value="${index}">${sub}</option>`)
                            })
                            count = data.length
                            $('#reg_id').val(data[0].regulation_id)
                            $('.tbl-fees').hide()
                            $('.tbl-fees2').show()
                            $(".tbl-view").show();
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#regulation_span").hide();
                            $("#loading_div").hide();
                            $("#sub_typeModal").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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
            }
        }

        function deleteExamfee(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
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
                        $('.secondLoader').show()
                        $.ajax({
                            url: "{{ route('admin.examfee-master.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $('.secondLoader').hide()
                                Swal.fire('', response.data, response.status);
                                callAjax();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
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
                    }
                })
            }
        }

        function checkCheckBox(element) {
            let value = $(element).val();

            if ($(element).prop("checked")) {
                $(element).removeAttr('checked');
                removableId2.push(value);

            } else {
                $(element).attr('checked', true);
                const index = removableId2.indexOf(value);
                if (index > -1) {
                    removableId2.splice(index, 1);
                }
            }
        }

        function submit() {
            let data_len = $(".examFeeRow2").length;
            if (data_len < 1) {
                Swal.fire('', 'Please Add Data', 'error');
                return false;
            } else {
                let regulation = $("#regulation").val();
                if (regulation != '') {
                    let formDataArray = [];
                    let subject_type;
                    let exam_fee;
                    let id;
                    let formData;

                    for (let i = 0; i < data_len; i++) {

                        subject_type = 'subject_type_' + i;
                        exam_fee = 'exam_fee_' + i;
                        id = 'id_' + i;
                        formData = {
                            'subject_type': $("#" + subject_type).val(),
                            'exam_fee': $("#" + exam_fee).val(),
                            'id': $("#" + id).val(),
                        };
                        formDataArray.push(formData);
                    }
                    let formLen = formDataArray.length;
                    if (formLen > 0) {
                        $.ajax({
                            url: '{{ route('admin.examfee-master.update') }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'regulation': regulation,
                                'data': formDataArray,
                                'removable_id': removableId
                            },
                            success: function(response) {
                                let status = response.status;
                                let data = response.data;
                                if (status == true) {
                                    Swal.fire('', data, 'success');
                                    window.location.href = '{{ route('admin.examfee-master.index') }}';
                                } else {
                                    Swal.fire('', data, 'error');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
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
                        Swal.fire('', 'Please Add Data', 'error');
                        return false;
                    }
                } else {
                    Swal.fire('', 'Regulation Not Found', 'error');
                    return false;
                }
            }
        }
    </script>
@endsection
