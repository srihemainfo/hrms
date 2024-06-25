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
                <div class="col-2">
                    <div class="form-group">
                        <label for="regulation_name" class="required">Regulation</label>
                        <input type="text" class="form-control"
                            value="{{ $edit[0] ? ($edit[0]['regulations'] ? $edit[0]['regulations']['name'] : '') : '' }}"
                            id="regulation_name" disabled>
                        <input type="hidden" id="regulation" value="{{ $edit[0] ? $edit[0]['regulation_id'] : '' }}">
                        <input type="hidden" id="totalRow" value="{{ count($edit) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="examfee_master">
        <div class="card-body">
            <div id="examfee_master_div">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>Subject Type</th>
                            <th>Exam Fee</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody id="exam_fee_master_table">
                        @if (count($edit) > 0)
                            @foreach ($edit as $i => $data)
                                <tr class="examFeeRow">
                                    <td>
                                        @if ($data->subject_types != null)
                                            {{ $data->subject_types->name ?? '' }}
                                        @endif
                                        <input type="hidden" id="subject_type_{{ $i }}"
                                            value="{{ $data->subject_type_id }}">
                                        <input type="hidden" id="id_{{ $i }}" value="{{ $data->id }}">
                                    </td>
                                    <td>
                                        {{ $data->fee }}
                                        <input type="hidden" id="exam_fee_{{ $i }}"
                                            value="{{ $data->fee }}">
                                    </td>
                                    <td>
                                        <input type="checkbox" id="checkbox_{{ $i }}"
                                            value="{{ $data->id }}" style="width:18px;height:18px;accent-color:red;"
                                            onchange="checkCheckBox(this)">
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3"> No Data Available...</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="text-right">
                    <button type="button" class="btn btn-outline-primary" onclick="openExamFeeModel()">Add</button>
                    <button type="button" class="btn btn-outline-success ml-2" onclick="submit()">Update</button>
                </div>
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
                            <select name="subject_type" id="subject_type">
                                <option value="">Select Subject Type</option>
                                @foreach ($subjectTypes as $subjectType)
                                    <option value="{{ $subjectType->id }}">{{ $subjectType->name }}</option>
                                @endforeach
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
        let rowInserted = parseInt($("#totalRow").val());
        let removableId = [];

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
                  <td>${subjectTypeText} <input type="hidden" id="subject_type_${rowInserted}" value="${subjectType}"> <input type="hidden" id="id_${rowInserted}" value=""></td>
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

        function checkCheckBox(element) {
            let value = $(element).val();

            if ($(element).prop("checked")) {
                $(element).removeAttr('checked');
                removableId.push(value);

            } else {
                $(element).attr('checked', true);
                const index = removableId.indexOf(value);
                if (index > -1) {
                    removableId.splice(index, 1);
                }
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
