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
    <div class="card" id="grade_master" style="display:none;">
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
                        </tr>
                    </thead>
                    <tbody id="grade_master_table">
                        <tr>
                            <td colspan="5"> No Data Available...</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right">

                    <button type="button" class="btn btn-outline-primary" onclick="openGradeModel()">Add</button>
                    <button type="button" class="btn btn-outline-success ml-2" onclick="submit()">Submit</button>

                </div>
            </div>
            <div id="loading_div" class="text-primary text-center" style="display:none;">
                Loading...
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
                            <span id="result_span" class="text-danger text-center" style="display:none;font-size:0.9rem;">
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
        let rowInserted = 0;

        function checkRegulation() {
            $("#grade_master").show();
            let regulation = $("#regulation").val();
            if (regulation != '') {
                $("#grade_master_div").hide();
                $("#loading_div").show();
                $.ajax({
                    url: '{{ route('admin.grade-master.checkRegulation') }}',
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
                            $("#loading_div").hide();
                            $("#regulation").attr('disabled', true);
                            $("#checkBtn").attr('disabled', true);
                            $("#grade_master_div").show();
                        } else {
                            $("#grade_master").hide();
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
                                  <td>${gradeLetter} <input type="hidden" id="grade_letter_${rowInserted}" value="${gradeLetter}"></td>
                                  <td>${gradePoint} <input type="hidden" id="grade_point_${rowInserted}" value="${gradePoint}"></td>
                                  <td>${result} <input type="hidden" id="result_${rowInserted}" value="${result}"></td>
                                  <td><div class="toggle text-center"><input type="checkbox" class="toggleData" data-id="0" onchange="attControl(this)"><label></label><input type="hidden" id="grade_sheet_show_${rowInserted}" value="0"></div></td>
                                  <td><div class="toggle text-center"><input type="checkbox" class="toggleData" data-id="0" onchange="attControl(this)"><label></label><input type="hidden" id="grade_book_show_${rowInserted}" value="0"></div></td>
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
                    let formData;
                    for (let i = 0; i < data_len; i++) {

                        grade_letter = 'grade_letter_' + i;
                        grade_point = 'grade_point_' + i;
                        result = 'result_' + i;
                        grade_sheet_show = 'grade_sheet_show_' + i;
                        grade_book_show = 'grade_book_show_' + i;
                        formData = {
                            'grade_letter': $("#" + grade_letter).val(),
                            'grade_point': $("#" + grade_point).val(),
                            'result': $("#" + result).val(),
                            'grade_sheet_show': $("#" + grade_sheet_show).val(),
                            'grade_book_show': $("#" + grade_book_show).val()
                        };
                        formDataArray.push(formData);
                    }
                    let formLen = formDataArray.length;
                    if (formLen > 0) {
                        $.ajax({
                            url: '{{ route('admin.grade-master.store') }}',
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
                                    Swal.fire('', 'Grade Master Created', 'success');
                                    location.reload();
                                } else {
                                    Swal.fire('', 'Grade Master Not Created', 'error');
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
