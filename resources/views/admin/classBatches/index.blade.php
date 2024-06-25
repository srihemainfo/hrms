@extends('layouts.admin')
@section('content')
    @can('batch_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Allocate Batch
                </button>
            </div>
        </div>
    @endcan
    <style>
        .select2 {
            width: 100% !important;
        }

        .secondLoader {
            position: absolute;
            top: 32%;
            left: 45%;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Batch List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Batch text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Class
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Students
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="secondLoader text-primary" style="display:none;"></div>
    </div>
    <div class="modal fade" id="batchModel" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 form-group">
                            <label for="batch" class="required">Batch</label>
                            <select name="batch" id="batch" class="form-control select2">
                                <option value="">Select Batch</option>
                                @if (count($batches) > 0)
                                    @foreach ($batches as $id => $name)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span id="batch_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 form-group">
                            <label for="course" class="required">Course</label>
                            <select name="course" id="course" class="form-control select2" onchange="getSections(this)">
                                <option value="">Select Course</option>
                                @if (count($courses) > 0)
                                    @foreach ($courses as $id => $short_form)
                                        <option value="{{ $id }}">{{ $short_form }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span id="course_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 form-group">
                            <label for="ay" class="required">AY</label>
                            <select name="ay" id="ay" class="form-control select2">
                                <option value="">Select AY</option>
                                @if (count($ays) > 0)
                                    @foreach ($ays as $id => $name)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span id="ay_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-md-4 col-sm-12 col-12 form-group">
                            <label for="semester" class="required">Semester</label>
                            <select name="semester" id="semester" class="form-control select2">
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
                            <span id="semester_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 form-group">
                            <label for="section" class="required">Section</label>
                            <select name="section" id="section" class="form-control select2">
                                <option value="">Select Section</option>
                            </select>
                            <span id="section_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 form-group">
                            <label for="class" class="required">Batch Name</label>
                            <input type="text" name="batch_name" id="batch_name" class="form-control"
                                style="text-transform: uppercase;">
                            <input type="hidden" id="class" value="">
                            <span id="batch_name_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 form-group" style="text-align:right;">
                            <button type="button" id="getStudentsBtn" class="btn btn-outline-primary"
                                style="margin-top:32px;" onclick="getStudents(this)">Get
                                Students</button>
                            <div id="loading_div" style="margin-top:32px;">
                                <span class="theLoader"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="lister" style="display:none;">
                        <div class="card-header bg-primary">
                            <div class="row text-center">
                                <div class="col-1">S No</div>
                                <div class="col-5">Name</div>
                                <div class="col-4">Register No</div>
                                <div class="col-2">Allocate</div>
                            </div>
                        </div>
                        <div class="card-body" id="stu_list">

                        </div>
                        <div class="card-footer text-right">
                            <div id="save_div">
                                <button type="button" id="save_btn" class="btn btn-outline-success"
                                    onclick="saveBatch()"></button>
                            </div>
                            <div class="loading_div">
                                <span class="theLoader"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            if ($.fn.DataTable.isDataTable('.datatable-Batch')) {
                $('.datatable-Batch').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.class-batch.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'class',
                        name: 'class'
                    },
                    {
                        data: 'batch',
                        name: 'batch'
                    },
                    {
                        data: 'count',
                        name: 'count'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-Batch').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {

            $("#batch").val($("#target option:first").val())
            $("#course").val($("#target option:first").val())
            $("#ay").val($("#target option:first").val())
            $("#semester").val($("#target option:first").val())
            $("#section").html('')
            $("select").select2();
            $("select,input").prop('disabled', false);
            $("input").val('');
            $("#course_span").hide();
            $("#ay_span").hide();
            $("#semester_span").hide();
            $("#section_span").hide();
            $("#batch_span").hide();
            $("#batch_name_span").hide();
            $("#save_btn").html(`Save Batch`);
            $("#stu_list").html('')
            $("#batch_name").html('')
            $("#lister").hide()
            $("#loading_div").hide()
            $(".loading_div").hide()
            // $("#loading_div").hide()
            $("#save_div").show();
            $("#getStudentsBtn").show();
            $("#batchModel").modal();
        }

        function getStudents(element) {

            if ($("#batch").val() == '') {
                $("#batch_span").html(`Batch Is Required.`).show();
                $("#course_span").hide();
                $("#ay_span").hide();
                $("#semester_span").hide();
                $("#section_span").hide();
            } else if ($("#course").val() == '') {
                $("#course_span").html(`Course Is Required.`).show();
                $("#batch_span").hide();
                $("#ay_span").hide();
                $("#semester_span").hide();
                $("#section_span").hide();
            } else if ($("#ay").val() == '') {
                $("#ay_span").html(`AY Is Required.`).show();
                $("#course_span").hide();
                $("#batch_span").hide();
                $("#semester_span").hide();
                $("#section_span").hide();
            } else if ($("#semester").val() == '') {
                $("#semester_span").html(`Semester Is Required.`).show();
                $("#course_span").hide();
                $("#batch_span").hide();
                $("#ay_span").hide();
                $("#section_span").hide();
            } else if ($("#section").val() == '') {
                $("#section_span").html(`Section Is Required.`).show();
                $("#course_span").hide();
                $("#batch_span").hide();
                $("#ay_span").hide();
                $("#semester_span").hide();
            } else {
                $("#course_span").hide();
                $("#batch_span").hide();
                $("#ay_span").hide();
                $("#semester_span").hide();
                $("#section_span").hide();
                $("#loading_div").show();

                $(element).hide();
                $("#lister").hide();
                $.ajax({
                    url: "{{ route('admin.class-batch.get-students') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'batch': $("#batch").val(),
                        'course': $("#course").val(),
                        'ay': $("#ay").val(),
                        'semester': $("#semester").val(),
                        'section': $("#section").val(),
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            let data = response.data;
                            let student_len = data.length;
                            let list = '';
                            if (student_len > 0) {
                                for (let i = 0; i < student_len; i++) {

                                    let balance = student_len - i;

                                    if (balance <= 1) {
                                        list +=
                                            `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-5">${data[i].name}</div><div class="col-4">${data[i].register_no} <input type="hidden" name="${data[i].user_name_id}" value="${data[i].user_name_id}"></div><div class="col-2"><input type="checkbox" style="width:15px;height:15px;" name="student_${i}"></div></div></form>`;

                                    } else {
                                        list +=
                                            `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-5">${data[i].name}</div><div class="col-4">${data[i].register_no} <input type="hidden" name="${data[i].user_name_id}" value="${data[i].user_name_id}"></div><div class="col-2"><input type="checkbox" style="width:15px;height:15px;" name="student_${i}"></div></div></form><hr style="margin:0;">`;
                                    }
                                }

                            }
                            $("#stu_list").html(list)
                            $("#lister").show();
                            $("select").select2()
                            $("#loading_div").hide();
                            $("#class").val(response.class);
                            $('select').prop('disabled', true);
                            $(element).hide();
                        } else {
                            Swal.fire('', response.data, 'error');
                            $("#loading_div").hide();
                            $(element).show();

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
                        $("#loading_div").hide();
                        $(element).show();
                    }
                })
            }
        }

        function getSections(element) {
            if ($(element).val() == '') {
                Swal.fire('', 'Please Selece The Course', 'warning');
                return false;
            } else {
                $.ajax({
                    url: "{{ route('admin.class-batch.get-sections') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': $(element).val()
                    },
                    success: function(response) {
                        if (response.status) {
                            let sections = '<option value="">Select Section</option>';
                            let sectionData = response.data;
                            if (sectionData.length > 0) {
                                sectionData.forEach(function(value, index) {
                                    console.log(value)
                                    sections +=
                                        `<option value="${value.section}">${value.section}</option>`;
                                })
                            }
                            $("#section").html(sections)
                        } else {
                            Swal.fire('', response.data, 'error');
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

        function saveBatch() {
            // $("#loading_div").hide();
            if ($("#class").val() == '') {
                Swal.fire('', `Technical Error`, 'error');

            } else if ($("#batch_name").val() == '') {
                Swal.fire('', `Batch Name Is Required.`, 'error');

            } else {
                $("#save_div").hide();
                $(".secondLoader").show();
                $(".loading_div").show();
                let form = $(".stu_form");
                let form_len = form.length;
                let form_data = [];

                for (let k = 0; k < form_len; k++) {
                    let collect = $(form[k]).serializeArray();

                    let collect_len = collect.length;

                    if (collect_len > 1) {
                        form_data.push(collect);
                    }

                }
                let action = 'Update';
                if ($("#save_btn").html() == 'Save Batch') {
                    action = 'Save';
                }
                $.ajax({
                    url: "{{ route('admin.class-batch.store') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'batch': $("#batch_name").val(),
                        'class': $("#class").val(),
                        'action': action,
                        'form_data': form_data
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#batchModel").modal('hide');
                        $("#save_div").show();
                        $(".secondLoader").hide();
                        $(".loading_div").hide();
                        callAjax();
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

        function viewBatch(theClass, batch) {
            if (theClass == undefined) {
                Swal.fire('', 'Class Not Found', 'warning');
            } else if (batch == undefined) {
                Swal.fire('', 'Batch Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $("#getStudentsBtn").hide()
                $.ajax({
                    url: "{{ route('admin.class-batch.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'class': theClass,
                        'batch': batch
                    },
                    success: function(response) {
                        let status = response.status;
                        $(".secondLoader").hide();
                        if (status == true) {
                            var data = response.data;
                            $("#batch_name").val(response.batchName);
                            $("#batch").val(response.batch);
                            $("#course").val(response.course);
                            $("#ay").val(response.ay);
                            $("#semester").val(response.semester);
                            $("#section").html(`<option>${response.section}</option>`);
                            $("select").select2();
                            $("select,input").prop('disabled', true);
                            $("#save_div").hide();
                            $("#loading_div").hide();

                            let student_len = data.length;
                            let list = '';
                            if (student_len > 0) {
                                for (let i = 0; i < student_len; i++) {

                                    let balance = student_len - i;

                                    if (balance <= 1) {
                                        list +=
                                            `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-5">${data[i].user_name.name}</div><div class="col-4">${data[i].user_name.register_no} <input type="hidden" name="${data[i].user_name.user_name_id}" value="${data[i].user_name.user_name_id}"></div><div class="col-2"><input type="checkbox" style="width:15px;height:15px;" name="student_${i}" checked></div></div></form>`;
                                    } else {
                                        list +=
                                            `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-5">${data[i].user_name.name}</div><div class="col-4">${data[i].user_name.register_no} <input type="hidden" name="${data[i].user_name.user_name_id}" value="${data[i].user_name.user_name_id}"></div><div class="col-2"><input type="checkbox" style="width:15px;height:15px;" name="student_${i}" checked></div></div></form><hr style="margin:0;">`;
                                    }
                                }

                            }
                            $("#stu_list").html(list)
                            $("#lister").show();
                            $(".loading_div").hide();

                            $("#batchModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
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

        function editBatch(theClass, batch) {
            if (theClass == undefined) {
                Swal.fire('', 'Class Not Found', 'warning');
            } else if (batch == undefined) {
                Swal.fire('', 'Batch Not Found', 'warning');
            } else {
                $(".secondLoader").show();
                $("#getStudentsBtn").hide()
                $.ajax({
                    url: "{{ route('admin.class-batch.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'class': theClass,
                        'batch': batch
                    },
                    success: function(response) {
                        let status = response.status;
                        $(".secondLoader").hide();
                        if (status == true) {
                            var data = response.data;
                            $("#batch_name").val(response.batchName);
                            $("#batch").val(response.batch);
                            $("#course").val(response.course);
                            $("#ay").val(response.ay);
                            $("#semester").val(response.semester);
                            $("#section").html(`<option>${response.section}</option>`);
                            $("select").select2();
                            $("select,input").prop('disabled', true);
                            $("#class").val(response.class);
                            $("#save_div").show();
                            $("#save_btn").html('Update Batch')
                            $("#loading_div").hide();
                            $(".loading_div").hide();

                            let student_len = data.length;
                            let list = '';
                            var checkStatus = '';
                            let allotedList = response.allotedList;
                            if (student_len > 0) {
                                for (let i = 0; i < student_len; i++) {

                                    let balance = student_len - i;

                                    if (allotedList.includes(data[i].user_name_id)) {
                                        checkStatus = 'checked';
                                    }
                                    if (balance <= 1) {
                                        list +=
                                            `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-5">${data[i].name}</div><div class="col-4">${data[i].register_no} <input type="hidden" name="${data[i].user_name_id}" value="${data[i].user_name_id}"></div><div class="col-2"><input type="checkbox" style="width:15px;height:15px;" name="student_${i}" ${checkStatus}></div></div></form>`;
                                    } else {
                                        list +=
                                            `<form class="stu_form"><div class="row text-center p-1"><div class="col-1">${i + 1}</div><div class="col-5">${data[i].name}</div><div class="col-4">${data[i].register_no} <input type="hidden" name="${data[i].user_name_id}" value="${data[i].user_name_id}"></div><div class="col-2"><input type="checkbox" style="width:15px;height:15px;" name="student_${i}" ${checkStatus}></div></div></form><hr style="margin:0;">`;
                                    }
                                    checkStatus = '';
                                }

                            }
                            $("#stu_list").html(list)
                            $("#lister").show();

                            $("#batchModel").modal();
                        } else {
                            Swal.fire('', response.data, 'error');
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

        function deleteBatch(theClass, batch) {
            if (theClass == undefined) {
                Swal.fire('', 'Class Not Found', 'warning');
            } else if (batch == undefined) {
                Swal.fire('', 'Batch Not Found', 'warning');
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
                        $(".secondLoader").show();
                        $.ajax({
                            url: "{{ route('admin.class-batch.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'class': theClass,
                                'batch': batch
                            },
                            success: function(response) {
                                $(".secondLoader").hide();
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
    </script>
@endsection
