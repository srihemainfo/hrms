@extends('layouts.admin')
@section('content')
<style>
    .table.dataTable tbody td.select-checkbox:before {
        content: none !important;
    }
    @media screen and (max-width: 575px) {
            .select2 {
                width: 100% !important;
            }
        }
    @media screen and (max-width: 767px) {
            .select2 {
                width: 100% !important;
            }
        }
</style>
<div style="margin-bottom: 10px; display:flex;" class="row ">
    @can('examTimetable_create')
    <div class="pl-2">
        <a class="btn btn-success" href="{{ route('admin.examTimetable.create') }}">
            Create Exam Timetable
        </a>
    </div>
    @endcan


</div>
<div class="card">
    <div class="card-header text-center">
        <strong>Search</strong>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                <label class="required d-block" for="AcademicYear">Academic Year</label>
                <select class="form-control select2" name="AcademicYear" id="AcademicYear" required>
                    <option value="">Please Select</option>
                    @foreach ($AcademicYear as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                <label for="year" class="required d-block">Year</label>
                <select class="form-control select2" name="year" id="year">
                    <option value="">Select Year</option>
                    <option value="01">I</option>
                    <option value="02">II</option>
                    <option value="03">III</option>
                    <option value="04">IV</option>
                </select>
            </div>
            <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                <label class="required d-block" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                <select class="form-control select2" name="course" id="course" required>
                    <option value="">Please Select</option>
                    @foreach ($courses as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                <label for="semester" class="required d-block">Semester</label>
                <select class="form-control select2" name="semester" id="semester" required>
                    <option value="">Please Select</option>
                    @foreach ($semester as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                <label for="section" class="required d-block">Sections</label>
                <select class="form-control select2" name="section" id="section" required>
                    <option value="">Please Select</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                <label for="examename" class="required d-block">Exam Title</label>
                <select class="form-control select2" name="examename" id="examename" required>
                    <option value="">Please Select</option>
                    @foreach ($examNames as $id => $entry)
                    <option value="{{ $entry->exam_name }}">
                        {{ $entry->exam_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <!-- <div class="form-group col-12">
                <button class="manual_bn" onclick="search()">Filter</button>
            </div> -->
            <div class="form-group col-12" id='Search' style="padding-top: 32px;">
                    <button class="manual_bn" onclick="search()">Filter</button>
                </div>
                <div class="form-group" id='load' style="padding-top: 32px;display:none">
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...</button>
                </div>
        </div>

    </div>
</div>

<div class="card">
    <div class="card-header text-center text-bold">
        Exam Time Table {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-events text-center">
            <thead>
                <tr>

                    <th>
                        ID
                    </th>
                    <th>
                        Exam Title
                    </th>
                    <th>
                        Academic Year
                    </th>
                    <th>
                        Course
                    </th>
                    <th>
                        Year
                    </th>
                    <th>
                        Semester
                    </th>
                    <th>
                        Section
                    </th>

                    <th>
                        Start Date
                    </th>
                    <th>
                        End Date
                    </th>



                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
@parent
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success ') }}",
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: "{{ session('error ') }}",
    });
</script>
@endif
@endsection
@section('scripts')
@parent
<script>
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.examTimetable.massDestroy') }}",
            className: 'btn-danger',
            action: function(e, dt, node, config) {
                var ids = $.map(dt.rows({
                    selected: true
                }).data(), function(entry) {
                    return entry.id
                });

                if (ids.length === 0) {
                    alert("{{ trans('global.datatables.zero_selected ') }}")

                    return
                }

                if (confirm("{{ trans('global.areYouSure ') }}")) {
                    $.ajax({
                            headers: {
                                'x-csrf-token': _token
                            },
                            method: 'POST',
                            url: config.url,
                            data: {
                                ids: ids,
                                _method: 'DELETE'
                            }
                        })
                        .done(function() {
                            location.reload()
                        })
                }
            }
        }
        dtButtons.push(deleteButton)


        let dtOverrideGlobals = {
            buttons: dtButtons,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.Exam-time-table.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'exam_name',
                    name: 'exam_name'
                },
                {
                    data: 'accademicYear',
                    name: 'accademicYear'
                },
                {
                    data: 'course',
                    name: 'course'
                },
                {
                    data: 'year',
                    name: 'year'
                },
                {
                    data: 'semester',
                    name: 'semester'
                },

                {
                    data: 'sections',
                    name: 'sections'
                },
                {
                    data: 'start_time',
                    name: 'start_time',
                    render: function(data, type, full, meta) {
                        var parts = data.split('-');
                        if (parts.length === 3) {
                            var formattedDate = parts[2] + '-' + parts[1] + '-' +
                                parts[0];
                            return formattedDate;
                        }

                        return data;
                    }
                },
                {
                    data: 'end_time',
                    name: 'end_time',
                    render: function(data, type, full, meta) {
                        var parts = data.split('-');
                        if (parts.length === 3) {
                            var formattedDate = parts[2] + '-' + parts[1] + '-' +
                                parts[0];
                            return formattedDate;
                        }

                        return data;
                    }
                },

                {
                    data: 'actions',
                    name: "{{ trans('global.actions ') }}"
                }
            ],
            orderCellsTop: true,
            order: [
                [1, 'desc']
            ],
            pageLength: 10,
        };
        let table = $('.datatable-events').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    });


    $(document).ready(function() {



        const $year = $("#year");
        const semesterSelect = $("#semester");
        const $semesterType = $("#semesterType");

        let valuesAndText = getAllSelectValuesAndText(semesterSelect);

        if ($year.val() === "") {
            semesterSelect.html('<option value="">Please select a year first</option>');
        }

        $year.on("change", function() {
            const year = this.value;
            getSemester();
        });

        $semesterType.on("change", function() {
            const semesterType = this.value;
            getSemester();
        });
        // var previousCheckbox = '';



        function getSemester() {
            const year = $year.val();
            const semesterType = $semesterType.val();
            let $year_value = '';

            if (year !== "") {
                let start = 0;
                let end = 0;
                $year_value = $year.val();

                if ($year_value == '01') {
                    start = 1;
                    end = 2;
                } else if ($year_value == '02') {
                    start = 3;
                    end = 4;
                } else if ($year_value == '03') {
                    start = 5;
                    end = 6;
                } else if ($year_value == '04') {
                    start = 7;
                    end = 8;
                } else {
                    semesterSelect.html('<option value="">Please select a year first</option>');
                    return;
                }

                let sem = ' <option value="">Select Semester</option>';
                for (let i = start; i <= end; i++) {
                    const option = valuesAndText[i];
                    sem += `<option style="color:blue;" value="${option.id}">${option.text}</option>`;
                }
                semesterSelect.html(sem);
            }
        }

        function getAllSelectValuesAndText($select) {
            const valuesAndText = [];
            $select.find("option").each(function() {
                const id = $(this).val();
                const text = $(this).text();
                valuesAndText.push({
                    id,
                    text
                });
            });
            return valuesAndText;
        }


    });
    //  Search Function
    $(document).ready(function() {

        const $academicYear_id = $("#AcademicYear");
        const $year_id = $("#year");
        const $course_id = $("#course");
        const $semester_id = $("#semester");
        const $section = $("#section");
        const $spinner = $("#spinner");
        const $examename = $("#examename");


        let course_id = '';
        let year_id = '';
        let academicYear_id = '';
        let semester_id = '';
        let section = '';
        let examename = '';

        if ($course_id.val() === "") {
            $section.html('<option value=""> Select Course First</option>');
        }
        if ($academicYear_id.val() == '' || $year.val() == '' || $course_id.val() == '' || $semester_id.val() ==
            '' || $section.val() == '') {
            $examename.html('<option value=""> Fill  All Value</option>');
        }

        // let valuesAndText = getAllSelectValuesAndText($semester_id);

        $academicYear_id.on("change", function() {
            getSemester();
        });
        $year_id.on("change", function() {
            getSemester();
        });

        $course_id.on("change", function() {
            getSemester();
            course();
        });
        $semester_id.on("change", function() {
            getSemester();
        });
        $section.on("change", function() {
            getSemester();
        });
        $examename.on("change", function() {
            // getSemester();
        });

        function course() {
            course_id = $course_id.val();
            $spinner.show();
            $section.html(' <option value="">Loading Section..</option>')

            $.ajax({
                url: "{{ route('admin.examTimetable.section_exam_name_get') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'course_id': course_id,
                },
                success: function(response) {
                    newObj = [];
                    $spinner.hide();
                    if (response.status == 'fail') {
                        let sec = ' <option value="">No Available Section</option>';
                        $section.html(sec);
                        $spinner.hide();
                    } else {
                        let sec = '';
                        if (response.get_section.length > 0) {
                            sec = `<option value=""> Available Section</option>`;
                            let get_sections = response.get_section;
                            for (let i = 0; i < get_sections.length; i++) {
                                sec +=
                                    `<option style="color:blue;" value="${get_sections[i].section}"> ${get_sections[i].section}</option>`;
                            }
                            $("select").select2();
                            $section.html(sec);

                        }
                    }
                }
            });

        }




        function getSemester() {

            academicYear_id = $academicYear_id.val();
            year_id = $year_id.val();
            course_id = $course_id.val();
            semester_id = $semester_id.val();
            section = $section.val();
            examename = $examename.val();

            if (academicYear_id != '' && course_id != '' && semester_id != '' && section != '') {

                $spinner.show();
                $examename.html('<option value="">Loading..</option>');

                $.ajax({
                    url: "{{ route('admin.examTimetable.section_exam_name_get') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'academicYear_id': academicYear_id,
                        'course_id': course_id,
                        'semester_id': semester_id,
                        'section': section,
                    },
                    success: function(response) {
                        newObj = [];
                        $spinner.hide();
                        let exam_name = '';
                        if (response.status == 'exam_Name') {
                            exam_name += ' <option value="">No Available Exam Title</option>';
                            $examename.html(exam_name);
                        } else {
                            let exam_name = '';
                            if (response.exam_name.length > 0) {
                                exam_name = ' <option value="">Select Exam Name</option>';
                                let got_exam = response.exam_name;
                                for (let i = 0; i < got_exam.length; i++) {
                                    exam_name +=
                                        `<option style="color:blue;" value="${got_exam[i].exam_name}"> ${got_exam[i].exam_name}</option>`;
                                }
                            }
                            $examename.html(exam_name);
                            $("select").select2();
                        }
                    }
                })
            } else {

                var exam_name = ' <option value="">Fill All Value</option>';
                $examename.html(exam_name);
                // $("select").select2();

            }

        }
    });
 function search() {
    var academicYear_id = $('#AcademicYear').val();
        var year = $('#year').val();
        var course_id = $('#course').val();
        var semester_id = $('#semester').val();
        var section = $('#section').val();
        var examename = $('#examename').val();
        var token = $('meta[name="csrf-token"]').attr('content');
        $('#Search').hide();
        $('#load').show();

        var data = {};

        if (academicYear_id !== '') {
            data['academicYear_id'] = academicYear_id;
        }
        if (year !== '') {
            data['year'] = year;
        }

        if (course_id !== '') {
            data['course_id'] = course_id;
        }

        if (semester_id !== '') {
            data['semester_id'] = semester_id;
        }

        if (section !== '') {
            data['section'] = section;
        }

        if (examename !== '') {
            data['examename'] = examename;
        }
        if (token !== '') {
            data['_token'] = token;
        }

        // Check if requestData is empty
        if (Object.keys(data).length === 1) {
            alert("Please fill in at least one field.");
            $('#Search').show();
            $('#load').hide();
            return;
        }

                $.ajax({
                    url: "{{ route('admin.examTimetable.search') }}",
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        console.log(response);
                        let dtOverrideGlobals = {
                            // buttons: dtButtons,
                            // processing: true,
                            // serverSide: true,
                            deferRender: true,
                            retrieve: true,
                            aaSorting: [],
                            data: response.data,
                            columns: [{
                                    data: 'id',
                                    name: 'id'
                                },
                                {
                                    data: 'exam_name',
                                    name: 'exam_name'
                                },
                                {
                                    data: 'accademicYear',
                                    name: 'accademicYear'
                                },
                                {
                                    data: 'course',
                                    name: 'course'
                                },
                                {
                                    data: 'year',
                                    name: 'year'
                                },
                                {
                                    data: 'semester',
                                    name: 'semester'
                                },

                                {
                                    data: 'sections',
                                    name: 'sections'
                                },
                                {
                                    data: 'start_time',
                                    name: 'start_time',
                                    render: function(data, type, full, meta) {
                                        var parts = data.split('-');
                                        if (parts.length === 3) {
                                            var formattedDate = parts[2] + '-' + parts[1] + '-' +
                                                parts[0];
                                            return formattedDate;
                                        }

                                        return data;
                                    }
                                },
                                {
                                    data: 'end_time',
                                    name: 'end_time',
                                    render: function(data, type, full, meta) {
                                        var parts = data.split('-');
                                        if (parts.length === 3) {
                                            var formattedDate = parts[2] + '-' + parts[1] + '-' +
                                                parts[0];
                                            return formattedDate;
                                        }

                                        return data;
                                    }
                                },

                                {
                                    data: 'actions',
                                    name: "{{ trans('global.actions ') }}"
                                }
                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };
                        // let table = $('.datatable-events').DataTable(dtOverrideGlobals);
                        // $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        //     $($.fn.dataTable.tables(true)).DataTable()
                        //         .columns.adjust();
                        // });
                        let table = $('.datatable-events').DataTable(dtOverrideGlobals);
                        table.destroy();
                        table = $('.datatable-events').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                        $('#Search').show();
                        $('#load').hide();

                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.log('An error occurred: ' + error);
                        $('#Search').show();
                        $('#load').hide();
                    }
                });
            }



</script>
@endsection
