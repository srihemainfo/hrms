@extends('layouts.admin')
@section('content')
    <style>
        .new-div-hidden {
            /* opacity: 0; */
            /* transition: opacity 0.5s ease; */
            display: none;
        }

        .new-div-show {
            /* opacity: 1; */
            /* transition: opacity 1.0s ease; */
            display: block;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    {{-- {{ dd($created_time_tables) }} --}}

    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.student-Promotion.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            Student Promotion
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data"class="pt-4">
                @csrf
                <div class="d-flex">

                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <label class="required" for="batch">Batch</label>
                        <select class="form-control select2 " name="batch" id="batch">
                            <option value="">Please Select</option>
                            @foreach ($batch as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course" onchange="getSections(this)">
                            <option value="">Please Select</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <label class="required" for="accademicyear	">Academic year</label>
                        <select class="form-control select2 " name="accademicyear" id="accademicyear">
                            <option value="">Please Select</option>
                            @foreach ($AcademicYear as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <label for="semester" class="required">Semester</label>
                        <select class="form-control select2" name="semester" id="semester">
                            <option value="">Please Select</option>
                            @foreach ($semester as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <label for="section" class="required">Section</label>
                        <select class="form-control select2" name="section" id="section">
                            <option value="">Select Section</option>

                        </select>
                    </div>
                    <div id="newDiv" class="">
                        <div>
                            <button type="button" class="btn btn-primary"
                                style="margin-top: 29px;
                        margin-left: 15px;
                        width: 155px;"
                                id="search">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <input type="hidden" name="" id="batch1" value="">
            <input type="hidden" name="" id="course1" value="">
            <input type="hidden" name="" id="accademicyear1" value="">
            <input type="hidden" name="" id="semester1" value="">
            <input type="hidden" name="" id="section1" value="">
            <div style="display: none" id="hidenDiv">
                <div class="d-flex">

                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <label for="accYearUp" class="required">New Academic year</label>
                        <input type="text" class="form-control" name="accYearUp" id="accYearUp" readonly>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <label for="semester" class="required">New Semester</label>
                        <input type="text" class="form-control" name="semester" id="semesterUp" readonly>
                    </div>

                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <label for="section" class="required"> New Section</label>
                        <select class="form-control select2" name="section" id="sectionUP">
                            <option value="">Please New Section</option>
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                        <button type="button" class="btn btn-info"style="margin-top: 31px;margin-left: 14px;"
                            id="promote">Promote</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="loader" id="loader" style="display:none;left: 37%;
        z-index:9999">
            <div class="spinner-border text-primary"></div>
        </div>

        <div class="card-body">

            <table
                class="table table-bordered table-striped table-hover text-center ajaxTable datatable datatable-attend_rep"
                id='report_table'>
                <thead>
                    <tr>
                        <th width="10">
                            Select
                        </th>
                        <th class="text-center">
                            Name
                        </th>
                        <th class="text-center">
                            Academic Year
                        </th>
                        <th class="text-center">
                            Course
                        </th>
                        <th class="text-center">
                            Department
                        </th>
                        <th class="text-center">
                            Semester
                        </th>
                        <th class="text-center">
                            Section
                        </th>
                        <th class="text-center">
                            Register Number
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);


            $('#promote').click(function() {
                $("#loader").show();

                var selectedRows = $('.datatable-attend_rep').DataTable().rows({
                    selected: true
                }).nodes();
                let selectedValues = [];
                $(selectedRows).each(function() {
                    let rowData = $(this).find('td').map(function() {
                        return $(this).text();
                    }).get();
                    selectedValues.push(rowData);
                });

                let data = {
                    'batch': $('#batch1').val(),
                    'course': $('#course1').val(),
                    'accademicyear': $('#accademicyear1').val(),
                    'semester': $('#semester1').val(),
                    'section': $('#section1').val(),
                    'semesterUp': $('#semesterUp').val(),
                    'sectionUP': $('#sectionUP').val(),
                    'selectedVal': selectedValues,
                    'accYearUp': $('#accYearUp').val(),
                }
                const isValid = validateData(data);
                if (isValid === true) {
                    if (!data.selectedVal || data.selectedVal.length === 0) {
                        select();
                    } else {
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{ route('admin.student-Promotion.promote') }}',
                            data: {
                                data: data,
                            },
                            success: function(response) {
                                $("#loader").hide();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                });
                                // $('#search').trigger('click');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                                $("#loader").hide();
                                if (jqXHR.status === 400 || jqXHR.status === 404) {
                                    var errorMessage = jqXHR.responseJSON.errors;
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: errorMessage,
                                    });
                                }
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please Fill All Details',
                    });
                }
            });

            function validateData(data) {
                if (
                    !data.batch ||
                    !data.course ||
                    !data.accademicyear ||
                    !data.semester ||
                    !data.section ||
                    !data.semesterUp ||
                    !data.sectionUP ||
                    !data.accYearUp
                ) {
                    return false;
                }
                return true;
            }

            function select() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please choose the student(s) to promote',
                });

            }


            $('#search').click(function() {
                $("#loader").show();
                let data = {
                    'batch': $('#batch').val(),
                    'course': $('#course').val(),
                    'accademicyear': $('#accademicyear').val(),
                    'semester': $('#semester').val(),
                    'section': $('#section').val(),
                }
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('admin.student-Promotion.search') }}',
                    data: {
                        data: data,
                    },
                    success: function(response) {
                        var currentSemester = parseInt(response.data[0].current_semester);
                        var nextSemester = currentSemester + 1;

                        var accYear = response.accYear;
                        var accYearParts = accYear.split('-');
                        var startYear = parseInt(accYearParts[0]);
                        var endYear = parseInt(accYearParts[1]);
                        var upgradedAccYear = accYear;
                        $('#batch1').val(response.batch);
                        $('#course1').val(response.course);
                        $('#accademicyear1').val(response.accYear);
                        $('#semester1').val(response.semester);
                        $('#section1').val(response.section);
                        if (nextSemester === 3 || nextSemester === 5 || nextSemester === 7) {
                            upgradedAccYear = (startYear + 1) + '-' + (endYear + 1);
                        }

                        $('#accYearUp').val(upgradedAccYear);

                        if (nextSemester === 9) {
                            $('#semesterUp').val('');
                        } else {
                            $('#semesterUp').val(nextSemester);
                        }
                        $("#sectionUP").val('');
                        $("#sectionUP").select2();

                        $("#loader").hide();

                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            deferRender: true,
                            retrieve: true,
                            aaSorting: [],
                            data: response.data,
                            columns: [{
                                    data: null,
                                    name: 'empty',
                                    render: function(data, type, full, meta) {
                                        return ' ';
                                    }
                                },
                                {
                                    data: 'name',
                                    name: 'name'
                                },
                                {
                                    data: 'Academic_Year',
                                    name: 'Academic_Year'
                                },
                                {
                                    data: 'admitted_course',
                                    name: 'admitted_course'
                                },
                                {
                                    data: 'dept',
                                    name: 'dept'
                                },
                                {
                                    data: 'current_semester',
                                    name: 'current_semester'
                                },
                                {
                                    data: 'section',
                                    name: 'section'
                                },
                                {
                                    data: 'register_no',
                                    name: 'register_no'
                                }
                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            // pageLength: 10
                        };


                        var table = $('.datatable-attend_rep');

                        if ($.fn.DataTable.isDataTable('.datatable-attend_rep')) {
                            table.DataTable().destroy();
                        }

                        table.DataTable(dtOverrideGlobals);

                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $.fn.dataTable
                                .tables({
                                    visible: true,
                                    api: true
                                })
                                .columns.adjust();
                        });
                        $('#hidenDiv').show();

                    },


                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        $("#loader").hide();
                        if (jqXHR.status === 400 || jqXHR.status === 404) {
                            var errorMessage = jqXHR.responseJSON.errors;

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                            });
                        }
                    }

                });
            });
        });

        function getSections(element) {
            if ($(element).val() != '') {
                $.ajax({
                    url: '{{ route('admin.student-Promotion.getSections') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        course: $(element).val(),
                    },
                    success: function(response) {
                        if (response.status == true) {

                            let data = response.data;
                            let options = '<option value="">Please Section</option>';
                            data.map((sections) => {
                                options +=
                                    `<option value="${sections.section}">${sections.section}</option>`;
                            })
                            $("#section").html(options);
                            $("#sectionUP").html(options);
                        } else {
                            $("#section").html('');
                            $("#sectionUP").html('');
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            } else {
                $("#section").html('');
                $("#sectionUP").html('');
            }
            $("#section").select2();
            $("#sectionUP").select2();
        }
    </script>
@endsection
