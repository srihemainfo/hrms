@extends('layouts.admin')
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-outline-success" onclick="openModal()">
                Add Subject
            </button>
            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', [
                'model' => 'SubjectMaster',
                'route' => 'admin.subject-masters.parseCsvImport',
            ])
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Subjects List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Subject text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Regulation
                        </th>
                        {{-- <th>
                            Department
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            Subject Type
                        </th>
                        <th>
                            Subject Category
                        </th> --}}
                        <th>
                            Subject Code
                        </th>
                        <th>
                            Subject Name
                        </th>
                        <th>
                            Credit
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="secondLoader"></div>
    </div>

    <div class="modal fade" id="subjectModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="regulation" class="required">Regulation</label>
                            <input type="hidden" name="subject_id" id="subject_id" value="">
                            {{-- <input type="text" class="form-control" style="text-transform:uppercase"  value=""> --}}
                            <select id="regulation" name="regulation" class="form-control select2">
                                <option value="">Select Regulation</option>
                                @foreach ($regulation as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="regulation_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="dept" class="required">Department</label>
                            <select id="dept" name="dept" class="form-control select2">
                                <option value="">Select Department</option>
                                @foreach ($dept as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="dept_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="course" class="required">Course</label>
                            <select id="course" name="course" class="form-control select2">
                                <option value="">Select Course</option>
                            </select>
                            <span id="course_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="sem">Semester</label>
                            <select id="sem" name="sem" class="form-control select2">
                                <option value="">Select Semester</option>
                            </select>
                            <span id="sem_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="sub_type" class="required">Subject Type</label>
                            <select id="sub_type" name="sub_type" class="form-control select2">
                                <option value="">Select Subject Type</option>
                                @foreach ($sub_type as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="sub_type_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="sub_cat" class="required">Subject Category</label>
                            <select id="sub_cat" name="sub_cat" class="form-control select2">
                                <option value="">Select Subject Category</option>
                                @foreach ($sub_cat as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span id="sub_cat_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="subject" class="required">Subject Name</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="subject"
                                name="subject" value="">
                            <span id="subject_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                            <label for="subject_code" class="required">Subject Code</label>
                            <input type="text" class="form-control" style="text-transform:uppercase"
                                id="subject_code" name="subject_code" value="">
                            <span id="subject_code_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">

                            <label for="lecture" class="required">Lectures</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="lecture"
                                name="lecture" value="">
                            <span id="lecture_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">

                            <label for="tutorial" class="required">Tutorial</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="tutorial"
                                name="tutorial" value="">
                            <span id="tutorial_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">

                            <label for="practical" class="required">Practical</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="practical"
                                name="practical" value="">
                            <span id="practical_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">

                            <label for="credit" class="required">Credit</label>
                            <input type="text" class="form-control" style="text-transform:uppercase" id="credit"
                                name="credit" value="">
                            <span id="credit_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">

                            <label for="contact_periods" class="required">Contact Periods</label>
                            <input type="text" class="form-control" style="text-transform:uppercase"
                                id="contact_periods" name="contact_periods" value="">
                            <span id="contact_periods_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveSubject()">Save</button>
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
        $(function() {
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
                    }).then(function(credit) {
                        if (credit.value) {
                            $('.secondLoader').show()
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: "{{ route('admin.subjects.massDestroy') }}",
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

            if ($.fn.DataTable.isDataTable('.datatable-Subject')) {
                $('.datatable-Subject').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.subjects.index') }}",
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
                        data: 'subject_code',
                        name: 'subject_code'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'credit',
                        name: 'credit'
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
            let table = $('.datatable-Subject').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {
            $("#subject_id").val('');
            $("#subject").val('');
            $("#subject_code").val('');
            $("#credit").val('');
            $("#dept").val($("#target option:first").val());
            $("#dept").select2();
            $("#course").val($("#target option:first").val());
            $("#course").select2();
            $("#sem").val($("#target option:first").val());
            $("#sem").select2();
            $("#sub_type").val($("#target option:first").val());
            $("#sub_type").select2();
            $("#sub_cat").val($("#target option:first").val());
            $("#sub_cat").select2();
            $("#lecture").val('');
            $("#tutorial").val('');
            $("#practical").val('');
            $("#contact_periods").val('');
            $("#credits").val('');
            $("#regulation_span").hide();
            $("#subject_span").hide();
            $("#subject_code_span").hide();
            $("#credit_span").hide();
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#subjectModal").modal();
        }

        $('#dept').change(function() {
            if ($('#dept').val() != '') {
                deptChange()
            }
        })

        function deptChange() {

            return new Promise((resolve, reject) => {
                $('#course').html(`<option value="">Loading...</option>`)
                $.ajax({
                    url: "{{ route('admin.subjects.get_course') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'dept': $('#dept').val()
                    },
                }).done(function(response) {
                    let status = response.status;
                    let data = response.data;
                    $(".secondLoader").hide();
                    if (status == true) {
                        let course = $('#course').empty()
                        course.prepend(`<option value="">Select Course</option>`)
                        $.each(data, function(index, value) {
                            course.append(
                                `<option value="${value.id}" data-degree="${value.degree_type_id}">${value.name}</option>`
                            )
                        })
                        resolve();
                    } else {
                        Swal.fire('', response.data, 'error');
                        reject(new Error('Error in Designation'));
                    }
                }).fail(function(xhr, status, error) {
                    reject(new Error(error));
                });
            });

        }


        $('#course').change(function() {
            if ($('#course').val() != '') {
                courseChange()
            }
        })

        function courseChange() {

            return new Promise((resolve, reject) => {
                if ($('#course option:selected').data('degree') != '') {
                    if ($('#course option:selected').data('degree') == 1) {
                        $('#sem').empty()
                        $('#sem').prepend(`<option value="">Select Semester</option>`)
                        for (let i = 1; i <= 8; i++) {
                            $('#sem').append(`<option value="${i}">${i}</option>`)
                        }
                    } else {
                        $('#sem').empty()
                        $('#sem').prepend(`<option value="">Select Semester</option>`)
                        for (let i = 1; i <= 4; i++) {
                            $('#sem').append(`<option value="${i}">${i}</option>`)
                        }
                    }
                    console.log($('#course option:selected').data('degree'));
                    // dd()
                    resolve();
                } else {
                    reject(new Error(error));
                }

                console.log($('#course option:selected').data('degree'));
            });

        }


        function saveSubject() {
            $("#loading_div").hide();
            let code = $("#subject_code").val()
            let checkNumber = code.match(/[0-9]/g)
            let checkLetter = code.match(/[A-Z|a-z]/g)
            if ($("#regulation").val() == '') {
                $("#regulation_span").html(`Regulation Is Required.`);
                $("#regulation_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#dept").val() == '') {
                $("#dept_span").html(`Department Is Required.`);
                $("#dept_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#course").val() == '') {
                $("#course_span").html(`Course Is Required.`);
                $("#course_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#sem").val() == '') {
                $("#sem_span").html(`Semester Is Required.`);
                $("#sem_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#sub_type").val() == '') {
                $("#sub_type_span").html(`Subject Type Is Required.`);
                $("#sub_type_span").show();
                $("#sub_cat_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#sub_cat").val() == '') {
                $("#sub_cat_span").html(`Subject Category Is Required.`);
                $("#sub_cat_span").show();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#subject").val() == '') {
                $("#subject_span").html(`Subject Is Required.`);
                $("#subject_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if (!isNaN($("#subject").val())) {
                $("#subject_span").html(`It Is Not a Word.`);
                $("#subject_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#subject_code").val() == '') {
                $("#subject_code_span").html(`Subject Code Is Required.`);
                $("#subject_code_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if (checkNumber == null || checkLetter == null) {
                $("#subject_code_span").html(`Subject Code Is Invalid`);
                $("#subject_code_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#credit").val() == '') {
                $("#credit_span").html(`Credit Is Required.`);
                $("#credit_span").show();
                $("#course_span").hide();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if (isNaN($("#credit").val())) {
                $("#credit_span").html(`It Is Not a Number.`);
                $("#credit_span").show();
                $("#course_span").hide();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else if ($("#lecture").val() == '') {
                $("#lecture_span").html(`Lecture Is Required.`);
                $("#tutorial_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();

            } else if (isNaN($("#lecture").val())) {
                $("#lecture_span").html(`It Is Not a Number.`);
                $("#lecture_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#tutorial_span").hide();

            } else if ($("#tutorial").val() == '') {
                $("#tutorial_span").html(`Tutorial Is Required.`);
                $("#tutorial_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();


            } else if (isNaN($("#tutorial").val())) {
                $("#tutorial_span").html(`It Is Not a Number.`);
                $("#tutorial_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
            } else if ($("#practical").val() == '') {
                $("#practical_span").html(`Lecture Is Required.`);
                $("#practical_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();


            } else if (isNaN($("#practical").val())) {
                $("#practical_span").html(`It Is Not a Number.`);
                $("#practical_span").show();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();

            } else if ($("#contact_periods").val() == '') {
                $("#contact_periods_span").html(`Contact Periods Is Required.`);
                $("#contact_periods_span").show();
                $("#practical_span").hide();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();


            } else if (isNaN($("#contact_periods").val())) {
                $("#contact_periods_span").html(`It Is Not a Number.`);
                $("#contact_periods_span").show();
                $("#practical_span").hide();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
            } else {
                $("#save_div").hide();
                $("#sub_cat_span").hide();
                $("#sub_type_span").hide();
                $("#sem_span").hide();
                $("#course_span").hide();
                $("#dept_span").hide();
                $("#subject_span").hide();
                $("#credit_span").hide();
                $("#subject_code_span").hide();
                $("#regulation_span").hide();
                $("#practical_span").hide();
                $("#lecture_span").hide();
                $("#tutorial_span").hide();
                $("#loading_div").show();
                $("#contact_periods_span").hide();

                let id = $("#subject_id").val();
                let regulation = $("#regulation").val();
                let subject = $("#subject").val();
                let subject_code = $("#subject_code").val();
                let credit = $("#credit").val();
                $.ajax({
                    url: "{{ route('admin.subjects.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': regulation,
                        'subject': subject,
                        'subject_code': subject_code,
                        'dept': $("#dept").val(),
                        'course': $("#course").val(),
                        'sem': $("#sem").val(),
                        'sub_type': $("#sub_type").val(),
                        'sub_cat': $("#sub_cat").val(),
                        'lecture': $("#lecture").val(),
                        'tutorial': $("#tutorial").val(),
                        'practical': $("#practical").val(),
                        'contact_periods': $("#contact_periods").val(),
                        'credit': credit,
                        'id': id,
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#subjectModal").modal('hide');
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }

        function viewSubject(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.subjects.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            console.log(data);
                            var regulation = data.regulation != 0 ? data.regulation : '';
                            $("#regulation").val(data.regulation_id);
                            $("#regulation").select2();

                            $("#subject_id").val(data.id);
                            $("#subject").val(data.name);
                            $("#subject_code").val(data.subject_code);
                            $("#credit").val(data.credits);
                            $("#dept").val(data.department_id);
                            $("#dept").select2();
                            deptChange().then(() => {
                                $("#course").val(data.course_id);
                                $("#course").select2();
                                // courseChange()
                                courseChange().then(() => {
                                    $("#sem").val(data.semester_id);
                                    $("#sem").select2();
                                });
                            });


                            $("#sub_type").val(data.subject_type_id);
                            $("#sub_type").select2();
                            $("#sub_cat").val(data.subject_cat_id);
                            $("#sub_cat").select2();
                            $("#lecture").val(data.lecture);
                            $("#tutorial").val(data.tutorial);
                            $("#practical").val(data.practical);
                            $("#contact_periods").val(data.contact_periods);
                            $("#credits").val(data.credits);
                            $("#save_div").hide();
                            $("#subject_span").hide();
                            $("#subject_code_span").hide();
                            $("#credit_span").hide();
                            $("#loading_div").hide();
                            $("#subjectModal").modal();
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }

        function editSubject(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.subjects.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            $("#regulation").val(data.regulation_id);
                            $("#regulation").select2();
                            $("#subject_id").val(data.id);
                            $("#subject").val(data.name);
                            $("#subject_code").val(data.subject_code);
                            $("#credit").val(data.credits);
                            $("#dept").val(data.department_id);
                            $("#dept").select2();
                            deptChange().then(() => {
                                $("#course").val(data.course_id);
                                $("#course").select2();
                                courseChange().then(() => {
                                    $("#sem").val(data.semester_id);
                                    $("#sem").select2();
                                });
                            });


                            $("#sub_type").val(data.subject_type_id);
                            $("#sub_type").select2();
                            $("#sub_cat").val(data.subject_cat_id);
                            $("#sub_cat").select2();
                            $("#lecture").val(data.lecture);
                            $("#tutorial").val(data.tutorial);
                            $("#practical").val(data.practical);
                            $("#contact_periods").val(data.contact_periods);
                            $("#credits").val(data.credits);
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#subject_span").hide();
                            $("#loading_div").hide();
                            $("#subjectModal").modal();
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }

        function deleteSubject(id) {
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
                }).then(function(credit) {
                    if (credit.value) {
                        $('.secondLoader').show()
                        $.ajax({
                            url: "{{ route('admin.subjects.delete') }}",
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
    </script>
@endsection
