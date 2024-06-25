@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <a class="btn btn-default mb-3" href="{{ route('admin.examfee-master.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="regulation" class="required">Regulation</label>
                        <select name="regulation" id="regulation" class="form-control select2">
                            @foreach ($regulations as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" id="checkBtn" class="enroll_generate_bn bg-primary" style="margin-top:32px;"
                        onclick="checkRegulation()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="exam_fee_master" style="display:none;">
        <div class="card-body">
            <div id="exam_fee_master_div">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>Subject Type</th>
                            <th>Exam Fee</th>
                        </tr>
                    </thead>
                    <tbody id="exam_fee_master_table">
                        <tr>
                            <td colspan="5"> No Data Available...</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right">

                    <button type="button" class="btn btn-outline-primary" onclick="openExamFeeModel()">Add</button>
                    <button type="button" class="btn btn-outline-success ml-2" onclick="submit()">Submit</button>

                </div>
            </div>
            <div id="loading_div" class="text-primary text-center" style="display:none;">
                Loading...
            </div>
        </div>
    </div>

    <div class="modal fade" id="examFeeMasterModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group">
                            <label for="subject_type" class="required">Subject Type</label>
                            <select name="subject_type" id="subject_type" class="form-control select2">
                                <option value="">Select Subject Type</option>
                            </select>
                            <span id="subject_type_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;">Please Select Subject Type</span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group">
                            <label for="exam_fee" class="required">Exam Fee</label>
                            <input type="number" class="form-control" id="exam_fee" name="exam_fee" value="">
                            <span id="exam_fee_span" class="text-danger text-center" style="display:none;font-size:0.9rem;">
                                Exam Fee Is Required</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" onclick="save()">Save</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let rowInserted = 0;

        function checkRegulation() {
            // Swal.fire('','Under Development','info');
            // return false;

            $("#exam_fee_master").show();
            let regulation = $("#regulation").val();
            if (regulation != '') {
                $("#exam_fee_master_div").hide();
                $("#loading_div").show();
                $.ajax({
                    url: '{{ route('admin.examfee-master.checkRegulation') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': regulation
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            let data_len = data.length;
                            let options = `<option value="">Select Subject Type</option>`;
                            for (let a = 0; a < data_len; a++) {
                                options += `<option value="${data[a].id}">${data[a].name}</option>`;
                            }
                            $("#subject_type").html(options);
                            $("#loading_div").hide();
                            $("#regulation").attr('disabled', true);
                            $("#checkBtn").attr('disabled', true);
                            $("#exam_fee_master_div").show();
                            $("#subject_type").select2();
                        } else {
                            $("#exam_fee_master").hide();
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
                Swal.fire('', 'Please Select The Regulation', 'error');
            }
        }

        function openExamFeeModel() {
            $("#subject_type").val('');
            $("#exam_fee").val('');
            $("#examFeeMasterModel").modal();
            $("#subject_type").select2();
        }

        function save() {
            if ($("#subject_type").val() == '') {
                $("#subject_type_span").show();
                $("#exam_fee_span").hide();
            } else if ($("#exam_fee").val() == '') {
                $("#subject_type_span").hide();
                $("#exam_fee_span").show();
            } else {
                let subjectType = $("#subject_type").val();

                let subjectTypeText = $("#subject_type option:selected").text();

                let examFee = $("#exam_fee").val();

                let content = `<tr class="examFeeRow">
                                  <td>${subjectTypeText} <input type="hidden" id="subject_type_${rowInserted}" value="${subjectType}"></td>
                                  <td>${examFee} <input type="hidden" id="exam_fee_${rowInserted}" value="${examFee}"></td>
                              </tr>`;
                if (rowInserted == 0) {
                    $("#exam_fee_master_table").html(content);
                } else {
                    $("#exam_fee_master_table").append(content);
                }
                rowInserted += 1;
                $("#examFeeMasterModel").modal('hide');
            }
        }

        function submit() {
            let data_len = $(".examFeeRow").length;
            if (data_len < 1) {
                Swal.fire('', 'Please Add Data', 'error');
                return false;
            } else {
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
                                    location.reload();
                                } else {
                                    Swal.fire('', 'Exam Fee Master Not Created', 'error');
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
                    Swal.fire('', 'Please Choose Regulation', 'error');
                    return false;
                }
            }
        }
    </script>
@endsection
