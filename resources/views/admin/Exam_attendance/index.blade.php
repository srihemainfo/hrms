@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        .null-cell {
            color: red;
        }
    </style>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="container">
        <div class="loader" id="loader" style="display:none;top:15%;">
            <div class="spinner-border text-primary"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-header text-center">
            <strong>CAT Attendance</strong>
        </div>
        <div class="card-body">

            <div id="spinner" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <div class="d-flex">
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label class="required" for="AcademicYear">Academic Year</label>
                    <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required>
                        <option value="">Please Select</option>
                        @foreach ($AcademicYear as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label for="year" class="required">Year</label>
                    <select class="form-control select2" name="year" id="year">
                        <option value="">Select Year</option>
                        <option value="01">I</option>
                        <option value="02">II</option>
                        <option value="03">III</option>
                        <option value="04">IV</option>

                    </select>

                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                    <select class="form-control select2 " name="course" id="course" required>
                        <option value="">Please Select</option>
                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label for="semester" class="required">Semester</label>
                    <select class="form-control select2" name="semester" id="semester" required>
                        <option value="">Please Select</option>
                        @foreach ($semester as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label for="section" class="required">Sections</label>
                    <select class="form-control select2" name="section" id="section" required>
                        <option value="">Please Select</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>


                    </select>
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <label for="examename" class="required">Exam Title</label>
                    <select class="form-control select2" name="examename" id="examename" required>
                        <option value="">Please Select</option>
                        @foreach ($examNames as $id => $entry)
                            <option value="{{ $entry->exam_name }}">
                                {{ $entry->exam_name }}</option>
                        @endforeach
                    </select>
                    <div class="form-group" style="padding-top: 32px;">
                        <button class="manual_bn" onclick="search()">Filter</button>
                    </div>
                </div>


            </div>
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-examtimetable">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        {{-- <th>Module Code</th> --}}
                        <th>Class </th>
                        <th>Title of the Exam</th>
                        <th>Course</th>
                        <th>Exam Date</th>
                        <th>Subject Code</th>
                        {{-- <th>AN/FN</th> --}}
                        <th>Subject Title</th>
                        <th>Total No of Students</th>
                        <th>No of Students Present</th>
                        <th>No of Students Absent</th>
                        <th>Attendance Entered Date</th>
                        <th>Attendance Entered By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $exameAttCollection = collect($exameAtt);
                    @endphp
                    @if ($exameAttCollection->count() > 0)
                        @foreach ($exameAttCollection as $exameAtts)
                            <tr>
                                <td></td>
                                <td> {{ $exameAtts->class ?? '' }}</td>
                                <td> {{ $exameAtts->examename ?? '' }}</td>
                                <td> {{ $exameAtts->course ?? '' }}</td>
                                <td> {{ $exameAtts->date ?? '' }}</td>
                                <td>{{ $exameAtts->subject_code ?? '' }}</td>
                                <td> {{ $exameAtts->subject ?? '' }}</td>
                                <td> {{ $exameAtts->totalstudent ?? '' }}</td>
                                <td>

                                    @if (($exameAtts->total_present ?? '') == null)
                                        <span class="null-cell">Attendance Not Entered</span>
                                    @else
                                        {{ $exameAtts->total_present ?? '' }}
                                    @endif
                                </td>
                                <td> {{ $exameAtts->total_abscent ?? '' }}</td>
                                <td>{{ $exameAtts->date_entered ?? '' }}</td>
                                <td>{{ $exameAtts->attenteredBY ?? '' }}</td>
                                <td>{!! $exameAtts->actions ?? '' !!}</td>

                            </tr>
                        @endforeach
                    @endif


                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                // processing:true,
                // serverSide:true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        // function search() {
        //     var ids = $("#AcademicYear, #year, #course, #semester, #section");
        //     var isEmpty = false;

        //     ids.each(function() {
        //         var value = $(this).val();
        //         if (!value || value.trim() === "") {
        //             isEmpty = true;
        //             return false;
        //         }
        //     });
        //     var token = $('meta[name="csrf-token"]').attr('content');

        //     var data = {
        //         ay: $('#AcademicYear').val(),
        //         course: $('#course').val(),
        //         semester: $('#semester').val(),
        //         year: $('#year').val(),
        //         section: $('#section').val(),
        //         examename: $('#examename').val(),
        //         _token: token
        //     };
        //     if (isEmpty) {
        //         alert("Please fill in all the required fields.");
        //     } else {
        //         $.ajax({
        //             url: "{{ route('admin.examTimetable.find') }}",
        //             method: 'POST',
        //             data: data,
        //             success: function(response) {
        //                 console.log(response);
        //                 let dtOverrideGlobals = {
        //                     // buttons: dtButtons,
        //                     // processing: true,
        //                     // serverSide: true,
        //                     deferRender: true,
        //                     retrieve: true,
        //                     aaSorting: [],
        //                     data: response.data,
        //                     columns: [{
        //                             data: 'empty',
        //                             name: 'empty',
        //                             render: function(data, type, full, meta) {
        //                                 // Add static data here
        //                                 return ' ';
        //                             }
        //                         },
        //                         {
        //                             data: 'examename',
        //                             name: 'examename'
        //                         },

        //                         {
        //                             data: 'date',
        //                             name: 'date',
        //                             render: function(data, type, full, meta) {
        //                                 var parts = data.split('-');
        //                                 if (parts.length === 3) {
        //                                     var formattedDate = parts[2] + '-' + parts[1] + '-' +
        //                                         parts[0];
        //                                     return formattedDate;
        //                                 }

        //                                 return data;
        //                             }
        //                         },
        //                         {
        //                             data: 'subject_code',
        //                             name: 'subject_code'
        //                         },

        //                         // {
        //                         //     data: 'time_period',
        //                         //     name: 'time_period'
        //                         // },
        //                         {
        //                             data: 'subject',
        //                             name: 'subject'
        //                         },
        //                         {
        //                             data: 'totalstudent',
        //                             name: 'totalstudent'
        //                         },
        //                         {
        //                             data: 'total_present',
        //                             name: 'total_present',
        //                             render: function(data, type, full, meta) {
        //                                 if (data === null) {
        //                                     return '<span class="null-cell">Attendance Not Entered</span>';
        //                                 }
        //                                 return data;
        //                             }
        //                         },
        //                         {
        //                             data: 'total_abscent',
        //                             name: 'total_abscent',
        //                             render: function(data, type, full, meta) {
        //                                 if (data === null) {
        //                                     return '<span class="null-cell">-</span>';
        //                                 }
        //                                 return data;
        //                             }
        //                         },
        //                         {
        //                             data: 'date_entered',
        //                             name: 'date_entered',
        //                             render: function(data, type, full, meta) {
        //                                 if (data === null) {
        //                                     return '<span class="null-cell">-</span>';
        //                                 }
        //                                 return data;
        //                             }
        //                         },
        //                         {
        //                             data: 'attenteredBY',
        //                             name: 'attenteredBY',
        //                             render: function(data, type, full, meta) {
        //                                 if (data == '') {
        //                                     return '<span class="null-cell">-</span>';
        //                                 }
        //                                 return data;
        //                             }
        //                         },
        //                         {
        //                             data: 'actions',
        //                             name: 'actions'
        //                         },


        //                     ],
        //                     orderCellsTop: true,
        //                     order: [
        //                         [1, 'desc']
        //                     ],
        //                     pageLength: 10,
        //                 };
        //                 let table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
        //                 table.destroy();
        //                 table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
        //                 $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
        //                     $($.fn.dataTable.tables(true)).DataTable()
        //                         .columns.adjust();
        //                 });

        //             },
        //             error: function(xhr, status, error) {
        //                 // Handle errors
        //                 console.log('An error occurred: ' + error);
        //             }
        //         });
        //     }
        // }
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
                                exam_name += ' <option value="">No Available Exam Name</option>';
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
                } else {

                    var exam_name = ' <option value="">Fill All Value</option>';
                    $examename.html(exam_name);
                    // $("select").select2();

                }

            }


        });

        function search() {

            $('#loading').show();
            var academicYear_id = $('#AcademicYear').val();
            var year = $('#year').val();
            var course_id = $('#course').val();
            var semester_id = $('#semester').val();
            var section = $('#section').val();
            var examename = $('#examename').val();
            var token = $('meta[name="csrf-token"]').attr('content');

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
                $('#loading').hide();
                alert("Please fill in at least one field.");
                return;
            }
            $.ajax({
                url: "{{ route('admin.examTimetable.find') }}",
                method: 'POST',
                data: data,
                success: function(response) {
                    // console.log(response);
                    let dtOverrideGlobals = {
                        // buttons: dtButtons,
                        // processing: true,
                        // serverSide: true,
                        deferRender: true,
                        retrieve: true,
                        aaSorting: [],
                        data: response.data,
                        columns: [{
                                data: 'empty',
                                name: 'empty',
                                render: function(data, type, full, meta) {
                                    // Add static data here
                                    return ' ';
                                }
                            },
                            {
                                data: 'class',
                                name: 'examename'
                            },
                            {
                                data: 'examename',
                                name: 'examename'
                            },
                            {
                                data: 'course',
                                name: 'course'
                            },

                            {
                                data: 'date',
                                name: 'date',
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
                                data: 'subject_code',
                                name: 'subject_code'
                            },

                            // {
                            //     data: 'time_period',
                            //     name: 'time_period'
                            // },
                            {
                                data: 'subject.name',
                                name: 'subject'
                            },
                            {
                                data: 'totalstudent',
                                name: 'totalstudent'
                            },
                            {
                                data: 'total_present',
                                name: 'total_present',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">Attendance Not Entered</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'total_abscent',
                                name: 'total_abscent',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">-</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'date_entered',
                                name: 'date_entered',
                                render: function(data, type, full, meta) {
                                    if (data === null) {
                                        return '<span class="null-cell">-</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'attenteredBY',
                                name: 'attenteredBY',
                                render: function(data, type, full, meta) {
                                    if (data == '') {
                                        return '<span class="null-cell">-</span>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'actions',
                                name: 'actions'
                            },


                        ],
                        orderCellsTop: true,
                        order: [
                            [1, 'desc']
                        ],
                        pageLength: 10,
                    };
                    let table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
                    table.destroy();
                    table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable()
                            .columns.adjust();
                    });
                    $('#loading').hide();

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loading').hide();
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
            });
        }
    </script>
@endsection
