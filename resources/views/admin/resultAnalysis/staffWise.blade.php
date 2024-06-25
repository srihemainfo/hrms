@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    }else{
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
            .select2-container {
            width: 100% !important;
        }
    @media screen and (max-width: 575px) {
            .select2 {
                width: 100% !important;
            }
        }
    @media screen and (max-width: 991px) {
            .btn-padding{
                padding: 0 !important;
                text-align:right;
            }
        }

        @media screen and (max-width: 767px) {
            .select2 {
                width: 100% !important;
            }
        }

        @media screen and (max-width: 1366px) {
            .select2 {
                width: 100% !important;
            }
        }
    </style>
    {{-- {{dd(phpinfo())}} --}}

    @php
        ini_set('memory_limit', '256M');
    @endphp
    {{-- ini_set('memory_limit', '256M'); --}}



    <div class="card">
        <div class="card-header text-center">
            <strong>Result Analysis Report - Staff Wise</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.Result_Analysis_Staff_Wise.staff_wise') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div id="spinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
                <div class="row">
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label class="required d-block" for="AcademicYear">Academic Year</label>
                        <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required>
                            <option value="">Please Select</option>
                            @foreach ($AcademicYear as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label for="year" class="required d-block">Year</label>
                        <select class="form-control select2" name="year" id="year">
                            <option value="">Select Year</option>
                            <option value="01">I</option>
                            <option value="02">II</option>
                            <option value="03">III</option>
                            <option value="04">IV</option>

                        </select>

                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label class="required d-block" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course" required>
                            <option value="">Please Select</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label for="semester" class="required d-block">Semester</label>
                        <select class="form-control select2" name="semester" id="semester" required>
                            <option value="">Please Select</option>
                            @foreach ($semester as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label for="section" class="required d-block">Sections</label>
                        <select class="form-control select2" name="section" id="section" required>
                            <option value="">Please Select</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>


                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <label for="examename" class="required d-block">Exam Title</label>
                        <select class="form-control select2" name="examename" id="examename" required>
                            <option value="">Please Select</option>
                            @foreach ($examNames as $id => $entry)
                                <option value="{{ $entry->exam_name }}">
                                    {{ $entry->exam_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 btn-padding" style="padding-top: 32px;">
                        <button class="btn manual_bn">Filter</button>
                    </div>


                </div>
            </form>
        </div>
    </div>
    @if (isset($response))
    @if ( count($response) > 0)
        <div class="card">

            <div class="card-header text-center">
                <strong>Staff - Wise Result Analysis</strong>
            </div>

            <div class="card-body" >
                <div class="row card-header ">
                    <div class=" col-xl-6 "></div>

                    <div class=" col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 mb-xl-0 mb-lg-0 mb-md-0 mb-sm-2 mb-2">
                    <a href="{{ URL::to('admin/Exam-StaffWise-report/pdf', ['ay'=> $newData[0], 'year'=> $newData[1], 'sem' => $newData[2], 'examname'=> base64_encode($newData[3]), 'course'=> $newData[4],'section' => $newData[5]]) }}" target="_blank" class="btn btn-primary" id="download_btn">Download PDF File</a>

                    </div>
                    <div class=" col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>

                    </div>
                </div>

                <div class="table-responsive">


                <table class="table table-bordered" id="tbl_exporttable_to_xls">
                    <thead>
                        <tr  class="text-center">
                            <td colspan="9">
                               <strong id="heading"> {{  $response[0]->className ?? '' }}</strong>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td> <strong> S.No</strong></td>
                            <td> <strong>  Sub Code & Subject Name</strong></td>
                            <td> <strong> Faculty Name</strong></td>
                            <td> <strong> Total students</strong></td>
                            <td> <strong> Absent</strong></td>
                            <td> <strong> Present</strong></td>
                            <td> <strong> Failed</strong></td>
                            <td> <strong> Passed</strong></td>
                            <td> <strong> Pass %</strong></td>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($response as $index => $responses)
                        {{-- @php
                            if(isset($responses->totalMark){
                                if($responses->totalMark ){

                                }
                            })

                        @endphp --}}
                        <tr>
                            <td>{{  $index + 1 }}</td>
                            <td>{{  $responses->subjectName ?? '' }}</td>
                            <td>{{  $responses->staffName ?? '' }}</td>
                            <td>{{  $responses->totalstudent ?? '' }}</td>
                            <td>{{  $responses->total_abscent ?? '' }}</td>
                            <td>{{  $responses->total_present ?? '' }}</td>
                            <td>{{  $responses->studentFail  ?? '' }}</td>
                            <td>{{  $responses->studentPass ?? '' }}</td>
                            <td>{{  $responses->subPassper ?? '' }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                    {{-- <tFoot class="text-center">
                        <tr class="text-center"><td  colspan="9" > <strong>Summary of Failures Count</strong></td></tr>
                        <tr>
                            <td colspan="2" class="text-center">Total Number of Students </td>
                            <td>{{  $response[0]->totalstudent ?? '' }}</td>
                            <td colspan="4">Arrear in one subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>

                            <td colspan="2" class="text-center">Number of students Passed </td>
                            <td>{{ $response[0]->studentallPass ?? '' }}</td>
                            <td colspan="4">Arrear in two subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>

                            <td colspan="2" class="text-center">Over All Pass Percentage </td>
                            <td></td>
                            <td colspan="4">Arrear in three subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="4">Arrear in four subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="4">Arrear in five subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>
                            <td colspan="3"> </td>
                            <td colspan="4">Arrear in six subject</td>
                            <td colspan="2"></td>

                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><strong> AUTHORITY</strong></td>
                            <td colspan="3" class="text-center"><strong>NAME</strong></td>
                            <td colspan="3" class="text-center"><strong>SIGNATURE</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><strong>PREPARED BY</strong></td>
                            <td colspan="3"></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><strong>HOD</strong></td>

                            <td colspan="3"></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center"><strong>PRINCIPAL</strong></td>

                            <td colspan="3"></td>
                            <td colspan="3"></td>
                        </tr>
                    </tFoot> --}}
                </table>
            </div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body">
                <p class="text-center">All Subject is Not Published</p>
            </div>
        </div>
    @endif
    @endif
@endsection
@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
    <script>
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`Result_Analysis_Report_Staff_Wise_{{{ $response[0]->className ?? '' }}}.` + (type || 'xlsx')));
        }



        // $(function() {
        //     let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        //     let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        //     let deleteButton = {
        //         text: deleteButtonTrans,
        //         url: "{{ route('admin.examTimetable.massDestroy') }}",
        //         className: 'btn-danger',
        //         action: function(e, dt, node, config) {
        //             var ids = $.map(dt.rows({
        //                 selected: true
        //             }).data(), function(entry) {
        //                 return entry.id
        //             });

        //             if (ids.length === 0) {
        //                 alert("{{ trans('global.datatables.zero_selected ') }}")

        //                 return
        //             }

        //             if (confirm("{{ trans('global.areYouSure ') }}")) {
        //                 $.ajax({
        //                         headers: {
        //                             'x-csrf-token': _token
        //                         },
        //                         method: 'POST',
        //                         url: config.url,
        //                         data: {
        //                             ids: ids,
        //                             _method: 'DELETE'
        //                         }
        //                     })
        //                     .done(function() {
        //                         location.reload()
        //                     })
        //             }
        //         }
        //     }
        //     dtButtons.push(deleteButton)


        //     let dtOverrideGlobals = {
        //         buttons: dtButtons,
        //         processing: true,
        //         serverSide: true,
        //         retrieve: true,
        //         aaSorting: [],
        //         ajax: "{{ route('admin.Exam_attendance.summary.index') }}",
        //         columns: [{
        //                 data: 'id',
        //                 name: 'id'
        //             },
        //             {
        //                 data: 'exam_name',
        //                 name: 'exam_name'
        //             },
        //             {
        //                 data: 'accademicYear',
        //                 name: 'accademicYear'
        //             },
        //             {
        //                 data: 'course',
        //                 name: 'course'
        //             },
        //             {
        //                 data: 'year',
        //                 name: 'year'
        //             },
        //             {
        //                 data: 'semester',
        //                 name: 'semester'
        //             },

        //             {
        //                 data: 'sections',
        //                 name: 'sections'
        //             },
        //             {
        //                 data: 'start_time',
        //                 name: 'start_time',
        //                 render: function(data, type, full, meta) {
        //                     var parts = data.split('-');
        //                     if (parts.length === 3) {
        //                         var formattedDate = parts[2] + '-' + parts[1] + '-' +
        //                             parts[0];
        //                         return formattedDate;
        //                     }

        //                     return data;
        //                 }
        //             },
        //             {
        //                 data: 'end_time',
        //                 name: 'end_time',
        //                 render: function(data, type, full, meta) {
        //                     var parts = data.split('-');
        //                     if (parts.length === 3) {
        //                         var formattedDate = parts[2] + '-' + parts[1] + '-' +
        //                             parts[0];
        //                         return formattedDate;
        //                     }

        //                     return data;
        //                 }
        //             },

        //             {
        //                 data: 'actions',
        //                 name: "{{ trans('global.actions ') }}"
        //             }
        //         ],
        //         orderCellsTop: true,
        //         order: [
        //             [1, 'desc']
        //         ],
        //         pageLength: 10,
        //     };
        //     let table = $('.datatable-events').DataTable(dtOverrideGlobals);
        //     $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
        //         $($.fn.dataTable.tables(true)).DataTable()
        //             .columns.adjust();
        //     });

        // });



        $(document).ready(function() {



            const $year = $("#year");
            const semesterSelect = $("#semester");
            const $semesterType = $("#semesterType");
            const $department = $("#department");
            const $course = $("#course");
            const $search_date = $("#search_date");

            let valuesAndText = getAllSelectValuesAndText(semesterSelect);

            if ($year.val() === "") {
                semesterSelect.html('<option value=""> select a year first</option>');
            }
            if ($department.val() === "") {
                $course.html('<option value=""> select a Department first</option>');
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
                        semesterSelect.html('<option value="">Select year first</option>');
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
                        }
                    })
                } else {
                    var exam_name = ' <option value="">Fill All Value</option>';
                    $examename.html(exam_name);
                    // $("select").select2();
                }

            }

        });
    </script>
@endsection
