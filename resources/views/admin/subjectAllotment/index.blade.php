@extends('layouts.admin')
@section('content')
    @can('subject_allotment_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-outline-success" href="{{ route('admin.subject-allotment.create') }}">
                    Allot Subjects For Semester
                </a>
                <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'SubjectAllotment',
                    'route' => 'admin.subject-allotments.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Search subject
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-xl-11 col-lg-11 col-md-12 col-sm-12 col-12">
                        <div class="row">
                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                <label for="regulation" class="required">Regulation</label>
                                <select class="form-control select2" name="regulation" id="regulation" required>
                                    <option value="">Select Regulation</option>
                                    @foreach ($regulation as $id => $entry)
                                        <option value="{{ $id }}"
                                            {{ old('regulation') == $id ? 'selected' : '' }}>
                                            {{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                <label class="required" for="department	">Department</label>
                                <select class="form-control select2 " name="department" id="department" required
                                    onchange="check_dept(this)">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $id => $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                                <select class="form-control select2 " name="course" id="course" required
                                    onchange="check_course(this)">
                                    <option value="">Select Course</option>
                                    @foreach ($courses as $id => $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                <label for="semester" class="">Semester</label>
                                <select class="form-control select2" name="semester" id="semester">
                                    <option value="">Select Semester</option>
                                    @foreach ($semester as $id => $entry)
                                        <option value="{{ $id }}">
                                            {{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12">
                        <div class="form-group" style="padding-top: 32px;">
                            <button type="button" id="submit" name="submit" onclick="get_data()"
                                class="enroll_generate_bn">Go</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Alloted Subjects List (Semester)
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover text-center datatable datatable-SubjectAllotment">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>ID</th>
                        <th>Regulation</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Academic Year</th>
                        <th>Semester</th>
                        <th>Semester Type</th>
                        <th>Created Date</th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @if (count($query) > 0)
                        @foreach ($query as $id => $data)
                            <tr>
                                <td></td>
                                <td>{{ $id + 1 }}</td>
                                <td>
                                    @if ($data->regulations != null)
                                        {{ $data->regulations->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($data->departments != null)
                                        {{ $data->departments->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($data->courses != null)
                                        {{ $data->courses->short_form }}
                                    @endif
                                </td>
                                <td>
                                    @if ($data->academic_years != null)
                                        {{ $data->academic_years->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($data->semesters != null)
                                        {{ $data->semesters->semester }}
                                    @endif
                                </td>
                                <td>{{ $data->semester_type }}</td>
                                <td>{{ $data->date }}</td>
                                <td>
                                    <a class="newViewBtn"
                                        href="{{ route('admin.subject-allotment.show', $data->regulation . '/' . $data->department . '/' . $data->course . '/' . $data->academic_year . '/' . $data->semester . '/' . $data->semester_type) }}"
                                        title="View">
                                        <i class="fa-fw nav-icon far fa-eye"></i>
                                    </a>
                                    <a class="newEditBtn"
                                        href="{{ route('admin.subject-allotment.edit', $data->regulation . '/' . $data->department . '/' . $data->course . '/' . $data->academic_year . '/' . $data->semester . '/' . $data->semester_type) }}"
                                        title="Edit">
                                        <i class="fa-fw nav-icon far fa-edit"></i>
                                    </a>
                                    <form
                                        action="{{ route('admin.subject-allotment.destroy', $data->regulation . '/' . $data->department . '/' . $data->course . '/' . $data->academic_year . '/' . $data->semester . '/' . $data->semester_type) }}"
                                        method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                        style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="newDeleteBtn" type="submit" title="Delete">
                                            <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            let dtOverrideGlobals = {
                buttons: dtButtons,
                deferRender: true,
                retrieve: true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };

            let table = $('.datatable-SubjectAllotment').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        });

        window.onload = function() {
            $("#regulation").select2();
            $("#department").select2();
            $("#course").select2();
            $("#semester").select2();
        }

        function check_dept(element) {
            if (element.value != '') {
                let dept = element.value;

                $.ajax({
                    url: '{{ route('admin.subjects.get_course') }}',
                    type: 'POST',
                    data: {
                        'dept': dept
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response.course != '') {
                            let course = response.course;
                            let course_len = course.length;

                            let got_course = `<option value="">Select Course</option>`;
                            for (let a = 0; a < course_len; a++) {
                                got_course +=
                                    `<option value="${course[a].id}">${course[a].name}</option>`;
                            }
                            $("#course").html(got_course);
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

                });




            }
        }

        function check_course(element) {
            if (element.value != '') {

                let course = element.value;
                let dept = $("#department").val();
                if (dept != '' && course != '') {
                    let semester = `<option value =''>Select Semester</option>
                @foreach ($semester as $id => $entry)
                @if ($id > 2)
                            <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                                @endif
                        @endforeach`;

                    if (dept == 5) {
                        semester = `<option value =''>Select Semester</option>
                        @foreach ($semester as $id => $entry)
                          @if ($id < 3)
                            <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                                @endif
                        @endforeach`;
                    }

                    $("#semester").html(semester);
                }
            }
        }

        function get_data() {

            if ($("#regulation").val() != '' && $('#department').val() != '' && $('#course').val() != '') {
                var data = {
                    regulation: $("#regulation").val(),
                    department: $('#department').val(),
                    course: $('#course').val(),
                    semester: $('#semester').val(),

                };

                // console.log(data)
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                $.ajax({
                    url: '{{ route('admin.subject-allotment.search') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    success: function(response) {
                        let query = response.data;
                        let len = query.length;
                        let data = '';

                        if (len > 0) {
                            let i = 0;
                            query.forEach(element => {
                                i++;
                                let created_date = new Date(element.created_at);
                                let final_Date = created_date.toLocaleDateString("es-CL");
                                data += `
                                <tr>
                                   <td></td>
                                   <td>${i}</td>
                                   <td>${element.regulations.name}</td>
                                   <td>${element.departments.name}</td>
                                   <td>${element.courses.short_form}</td>
                                   <td>${element.academic_years.name}</td>
                                   <td>${element.semesters.semester}</td>
                                   <td>${element.semester_type}</td>
                                   <td>${final_Date}</td>
                                   <td>
                                    <a class="btn btn-xs btn-outline-primary"
               href="/admin/subject-allotment/${element.regulation}/${element.department}/${element.course}/${element.academic_year}/${element.semester}/${element.semester_type}">
                {{ trans('global.view') }}
            </a>
            <a class="btn btn-xs btn-info"
               href="/admin/subject-allotment/${element.regulation}/${element.department}/${element.course}/${element.academic_year}/${element.semester}/${element.semester_type}/edit">
                {{ trans('global.edit') }}
            </a>
            <form action="/admin/subject-allotment/${element.regulation}/${element.department}/${element.course}/${element.academic_year}/${element.semester}/${element.semester_type}"
                  method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                  style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-xs btn-outline-danger" value="{{ trans('global.delete') }}">
            </form>
                                   </td>
                               </tr>`;
                            });

                        };
                        // console.log(data);
                        $("#tbody").html(data);
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
                });
            } else {
                // alert('Please Provide the Required Fields..');
                Swal.fire('', 'Please Provide the Required Fields..', 'error');
            }
        }
    </script>
@endsection
