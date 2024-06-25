@extends('layouts.admin')
@section('content')
    <style>
        .toggle {
            position: relative;
            width: 42%;
            margin: auto;
        }

        .toggle:before {
            content: '';
            position: absolute;
            border-bottom: 3px solid #fff;
            border-right: 3px solid #fff;
            width: 6px;
            height: 14px;
            z-index: 2;
            transform: rotate(45deg);
            top: 8px;
            left: 15px;
        }

        .toggle:after {
            content: '×';
            position: absolute;
            top: -6px;
            left: 49px;
            z-index: 2;
            line-height: 42px;
            font-size: 26px;
            color: #aaa;
        }

        .toggle input[type="checkbox"] {
            position: absolute;
            left: 0;
            top: 0;
            z-index: 10;
            width: 100%;
            height: 100%;
            cursor: pointer;
            opacity: 0;
        }

        .toggle label {
            position: relative;
            display: flex;
            align-items: center;
        }

        .toggle label:before {
            content: '';
            width: 70px;
            height: 30px;
            box-shadow: 0 0 1px 2px #0001;
            background: #eee;
            position: relative;
            display: inline-block;
            border-radius: 46px;
        }

        .toggle label:after {
            content: '';
            position: absolute;
            width: 31px;
            height: 29px;
            border-radius: 50%;
            left: 0;
            top: 0;
            z-index: 5;
            background: #fff;
            box-shadow: 0 0 5px #0002;
            transition: 0.2s ease-in;
        }

        .toggle input[type="checkbox"]:hover+label:after {
            box-shadow: 0 2px 15px 0 #0002, 0 3px 8px 0 #0001;
        }

        .toggle input[type="checkbox"]:checked+label:before {
            transition: 0.1s 0.2s ease-in;
            background: #4BD865;
        }

        .toggle input[type="checkbox"]:checked+label:after {
            left: 38px;
        }
    </style>
    <a class="btn btn-default mb-3" href="{{ route('admin.grade-master.index') }}">
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
    <div class="card" id="grade_master">
        <div class="card-body">
            <div id="grade_master_div">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>Grade Letter</th>
                            <th>Grade Point</th>
                            <th>Result</th>
                            <th>Include In Grade Sheet</th>
                            <th>Include In Grade Book</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody id="grade_master_table">
                        @if (count($edit) > 0)
                            @foreach ($edit as $i => $data)
                                <tr class="gradeRow">
                                    <td>{{ $data->grade_letter }}<input type="hidden" id="grade_letter_{{ $i }}"
                                            value="{{ $data->grade_letter }}"><input type="hidden"
                                            id="id_{{ $i }}" value="{{ $data->id }}"></td>
                                    <td>{{ $data->grade_point }}<input type="hidden" id="grade_point_{{ $i }}"
                                            value="{{ $data->grade_point }}"></td>
                                    <td>{{ $data->result }} <input type="hidden" id="result_{{ $i }}"
                                            value="{{ $data->result }}"></td>
                                    <td>
                                        <div class="toggle text-center"><input type="checkbox" class="toggleData"
                                                data-id="{{ $data->grade_sheet_show }}" onchange="attControl(this)"
                                                {{ $data->grade_sheet_show == 1 ? 'checked' : '' }}><label></label><input
                                                type="hidden" id="grade_sheet_show_{{ $i }}"
                                                value="{{ $data->grade_sheet_show }}"></div>
                                    </td>
                                    <td>
                                        <div class="toggle text-center"><input type="checkbox" class="toggleData"
                                                data-id="{{ $data->grade_book_show }}" onchange="attControl(this)"
                                                {{ $data->grade_book_show == 1 ? 'checked' : '' }}><label></label><input
                                                type="hidden" id="grade_book_show_{{ $i }}"
                                                value="{{ $data->grade_book_show }}"></div>
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
                                <td colspan="6"> No Data Available...</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="text-right">
                    <button type="button" class="btn btn-outline-primary" onclick="openGradeModel()">Add</button>
                    <button type="button" class="btn btn-outline-success ml-2" onclick="submit()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gradeMasterModel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-4 col-lg-4 col-md-10 col-sm-10 col-12 form-group">
                            <label for="grade_letter" class="required">Grade Letter</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="grade_letter"
                                name="grade_letter" value="">
                            <span id="grade_letter_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"> Grade Letter Is
                                Required</span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-10 col-sm-10 col-12 form-group">
                            <label for="grade_point" class="required">Grade Point</label>
                            <input type="number" class="form-control" id="grade_point" name="grade_point" value="">
                            <span id="grade_point_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"> Grade Point Is
                                Required</span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-10 col-sm-10 col-12 form-group">
                            <label for="result" class="required">Result</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="result"
                                name="result" value="">
                            <span id="result_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;">
                                Result Is Required</span>
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
        let rowInserted = $("#totalRow").val();
        let removableId = [];

        function openGradeModel() {
            $("#grade_letter").val('');
            $("#grade_point").val('');
            $("#result").val('');
            $("#gradeMasterModel").modal();
        }

        function save() {
            if ($("#grade_letter").val() == '') {
                $("#grade_letter_span").show();
                $("#grade_point_span").hide();
                $("#result_span").hide();
            } else if ($("#grade_point").val() == '') {
                $("#grade_letter_span").hide();
                $("#grade_point_span").show();
                $("#result_span").hide();
            } else if ($("#result").val() == '') {
                $("#grade_letter_span").hide();
                $("#grade_point_span").hide();
                $("#result_span").show();
            } else {
                let theLetter = $("#grade_letter").val();
                let gradeLetter = theLetter.toUpperCase();
                let gradePoint = $("#grade_point").val();
                let theResult = $("#result").val();
                let result = theResult.toUpperCase();
                let content = `<tr class="gradeRow">
                                  <td>${gradeLetter} <input type="hidden" id="grade_letter_${rowInserted}" value="${gradeLetter}"> <input type="hidden" id="id_${rowInserted}" value=""></td>
                                  <td>${gradePoint} <input type="hidden" id="grade_point_${rowInserted}" value="${gradePoint}"></td>
                                  <td>${result} <input type="hidden" id="result_${rowInserted}" value="${result}"></td>
                                  <td><div class="toggle text-center"><input type="checkbox" data-class="2004" data-id="0" onchange="attControl(this)"><label></label><input type="hidden" id="grade_sheet_show_${rowInserted}" value="0"></div></td>
                                  <td><div class="toggle text-center"><input type="checkbox" data-class="2004" data-id="0" onchange="attControl(this)"><label></label><input type="hidden" id="grade_book_show_${rowInserted}" value="0"></div></td>
                                  <td></td>
                              </tr>`;
                if (rowInserted == 0) {
                    $("#grade_master_table").html(content);
                } else {
                    $("#grade_master_table").append(content);
                }
                rowInserted += 1;
                $("#gradeMasterModel").modal('hide');
            }
        }

        function attControl(checkbox) {
            let db_id = $(checkbox).data('class');
            let currentStatus = $(checkbox).data('id');
            let status = currentStatus === 0 ? 1 : 0;
            $(checkbox).data('id', status);
            let nextElement = $(checkbox).next();
            let anotherNext = $(nextElement).next();
            $(anotherNext).val(status);
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
            let data_len = $(".gradeRow").length;
            if (data_len < 1) {
                Swal.fire('', 'Please Add Data', 'error');
                return false;
            } else {
                let regulation = $("#regulation").val();
                if (regulation != '') {
                    let formDataArray = [];
                    let grade_letter;
                    let grade_point;
                    let result;
                    let grade_sheet_show;
                    let grade_book_show;
                    let id;
                    let formData;
                    for (let i = 0; i < data_len; i++) {
                        grade_letter = 'grade_letter_' + i;
                        grade_point = 'grade_point_' + i;
                        result = 'result_' + i;
                        grade_sheet_show = 'grade_sheet_show_' + i;
                        grade_book_show = 'grade_book_show_' + i;
                        id = 'id_' + i;
                        formData = {
                            'id': $("#" + id).val(),
                            'grade_letter': $("#" + grade_letter).val(),
                            'grade_point': $("#" + grade_point).val(),
                            'result': $("#" + result).val(),
                            'grade_sheet_show': $("#" + grade_sheet_show).val(),
                            'grade_book_show': $("#" + grade_book_show).val(),
                        };
                        formDataArray.push(formData);
                    }
                    let formLen = formDataArray.length;
                    if (formLen > 0) {
                        $.ajax({
                            url: '{{ route('admin.grade-master.update') }}',
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
                                    // location.reload();
                                    window.location.href = '{{ route('admin.grade-master.index') }}';
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
