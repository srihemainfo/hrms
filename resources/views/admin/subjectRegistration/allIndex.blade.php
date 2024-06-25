@php
    $type_id = auth()->user()->roles[0]->type_id;
    $key = 'layouts.admin';
    if ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    }

@endphp
@extends($key)
@section('content')
    @can('sub_registration_import_access')
        <div style="margin-bottom: 10px;" class="row text-center">
            <div class="col-lg-3 col-sm-6 col-12">
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    CSV Import For Registration
                </button>
                @include('csvImport.modal', [
                    'model' => 'SubjectRegistration',
                    'route' => 'admin.subject-registrations.parseCsvImport',
                ])
            </div>


            <div class="col-lg-3 col-sm-6 col-12">
                <button class="btn btn-info" data-toggle="modal" data-target="#csvRemoveModal">
                    CSV Import For Remove Registration
                </button>
            </div>
            @can('honor_subjects_access')
                <div class="col-lg-3 col-sm-6 col-12">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#csvHonors">
                        CSV Import For Honors Degree
                    </button>
                </div>
            @endcan
        </div>

        <div class="modal fade" id="csvRemoveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="myModalLabel">@lang('global.app_csvImport')</span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class='row'>
                            <div class='col-md-12'>

                                <form class="form-horizontal" method="POST"
                                    action="{{ route('admin.subjectRegistration.parseCsvRemovalSubjectReg', ['model' => 'SubjectRegistration']) }}"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                                        <label for="csv_file" class="col-md-4 control-label">@lang('global.app_csv_file_to_import')</label>

                                        <div class="col-md-6">
                                            <input id="csv_file" type="file" class="form-control-file" name="csv_file"
                                                required>

                                            @if ($errors->has('csv_file'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('csv_file') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="header" checked> @lang('global.app_file_contains_header_row')
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                @lang('global.app_parse_csv')
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="csvHonors" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="myModalLabel">@lang('global.app_csvImport')</span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class='row'>
                            <div class='col-md-12'>

                                <form class="form-horizontal" method="POST"
                                    action="{{ route('admin.subjectRegistration.parseCsvHonors', ['model' => 'SubjectRegistration']) }}"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                                        <label for="csv_file" class="col-md-4 control-label">@lang('global.app_csv_file_to_import')</label>

                                        <div class="col-md-6">
                                            <input id="csv_file" type="file" class="form-control-file" name="csv_file"
                                                required>

                                            @if ($errors->has('csv_file'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('csv_file') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="header" checked> @lang('global.app_file_contains_header_row')
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                @lang('global.app_parse_csv')
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .select2-container {
                width: 100% !important;
            }
        </style>
    @endcan
    <style>
        .select2-container {
            width: 100% !important;

        }
    </style>
    @php
        if ($role_type_id == 1 || $role_type_id == 3) {
            $style = 'display:none;';
        } else {
            $style = '';
        }
    @endphp
    <div class="card" style="{{ $style }}">
        <div class="card-header">
            <b>Subject Registrations</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 form-group">
                    <label class=" required" for="regulation">Regulation</label>
                    <select class="form-control select2" name="regulation" id="regulation">
                        <option value="">Select Regulation</option>
                        @foreach ($regulations as $i => $reg)
                            <option value="{{ $i }}">{{ $reg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 form-group">
                    <label for="batch" class="required">Batch</label>
                    <select class="form-control select2" name="batch" id="batch">
                        <option value="">Select Batch</option>
                        @foreach ($batches as $i => $batch)
                            <option value="{{ $batch }}">{{ $batch }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 form-group">
                    <label for="ay" class=" required">Academic Year</label>
                    <select class="form-control select2" name="ay" id="ay">
                        <option value="">Select AY</option>
                        @foreach ($ays as $i => $ay)
                            <option value="{{ $ay }}">{{ $ay }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 form-group">
                    <label for="course" class=" required">Course</label>
                    <select class="form-control select2" name="course" id="course">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->name }}">{{ $course->short_form }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 form-group">
                    <label for="sem" class=" required">Semester</label>
                    <select class="form-control select2" name="sem" id="sem">
                        <option value="">Select Semester</option>
                        @foreach ($semesters as $sem)
                            <option value="{{ $sem }}">{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 form-group">
                    <button class="enroll_generate_bn" style="margin-top:32px" id="fetch_sub">Fetch Subjects</button>
                    <button class="enroll_generate_bn bg-warning" style="margin-top:32px" id="reset">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card text-primary text-center" style="display:none;" id="processing">
        <div class="card-body">
            Processing...
        </div>

    </div>

    <div class="card" id="card_2">
        <div class="card-header">
            Subject Registration Requests
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover datatable text-center datatable-SubjectRegistration">
                <thead>
                    <tr>
                        <th></th>
                        <th>S.No</th>
                        <th>Student Name</th>
                        <th>Register No</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            // if ($("#role_type").val() == 1 || $("#role_type").val() == 3) {
            $("#card_2").show();
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.subjectRegistration.getData') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: null,
                        name: 'id',
                        render: function(data, type, row, meta) {
                            var rowId = meta.row + 1;
                            return rowId;
                        }
                    },

                    {
                        data: 'student_name',
                        name: 'students.name'
                    },
                    {
                        data: 'register_no',
                        name: 'students.register_no'
                    },
                    {
                        data: 'enroll_master',
                        name: 'enroll_master'
                    },

                    {
                        data: null,
                        name: 'view',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            var viewUrl = "{{ route('admin.subjectRegistration.show', ':id') }}"
                                .replace(':id', row
                                    .id);
                            var viewButton = '<a class="badge badge-primary" href="' + viewUrl +
                                '">View</a>';
                            // console.log(row.id);
                            return viewButton;
                        }
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };

            let table = $('.datatable-SubjectRegistration').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
            // }
        });

        $("#reset").click(function() {

            $("#ay").val($("#target option:first").val());
            $("#regulation").val($("#target option:first").val());
            $("#sem").val($("#target option:first").val());
            $("#batch").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $('select').select2();

        })


        $('#fetch_sub').click(function() {

            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Enter Regulation', 'warning');
                return false;
            } else if ($("#batch").val() == '') {
                Swal.fire('', 'Please Enter Batch', 'warning');
                return false;
            } else if ($("#ay").val() == '') {
                Swal.fire('', 'Please Enter Academic Year', 'warning');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Enter Course', 'warning');
                return false;
            } else if ($("#sem").val() == '') {
                Swal.fire('', 'Please Enter Semester', 'warning');
                return false;

            } else {

                $.ajax({
                    url: "{{ route('admin.subjectRegistration.getDatas') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': $("#regulation").val(),
                        'batch': $("#batch").val(),
                        'ay': $("#ay").val(),
                        'course': $("#course").val(),
                        'sem': $("#sem").val()
                    },
                    success: function(response) {
                        // $("#processing").hide();
                        // $('#card_2').show();

                        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            retrieve: true,
                            aaSorting: [],
                            data: response.data,
                            columns: [{
                                    data: 'placeholder',
                                    name: 'placeholder'
                                },
                                {
                                    data: 'id',
                                    name: 'id',
                                    render: function(data, type, row, meta) {
                                        var rowId = meta.row + 1;
                                        return rowId;
                                    }
                                },

                                {
                                    data: 'student_name',
                                    name: 'students.name'
                                },
                                {
                                    data: 'register_no',
                                    name: 'register_no'
                                },
                                {
                                    data: 'enroll_master',
                                    name: 'enroll_master'
                                },

                                {
                                    data: null,
                                    name: 'actions',
                                    orderable: false,
                                    searchable: false,
                                    render: function(data, type, row, meta) {
                                        console.log(data)
                                        console.log(row)
                                        var viewUrl =
                                            "{{ route('admin.subjectRegistration.show', ':id') }}"
                                            .replace(':id', data.id);
                                        var viewButton =
                                            '<a class="badge badge-primary" href="' +
                                            viewUrl +
                                            '">View</a>';
                                        // console.log(row.id);
                                        return viewButton;
                                    }
                                }
                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };

                        let table = $('.datatable-SubjectRegistration').DataTable(dtOverrideGlobals);

                        table.destroy();
                        table = $('.datatable-SubjectRegistration').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });

                    }

                })
            }
        })
    </script>
@endsection
