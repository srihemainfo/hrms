@extends('layouts.admin')
@section('content')

@php
    ini_set('memory_limit', '-1');
@endphp
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
               CSV Import For Registration
            </button>
            @include('csvImport.modal', [
                'model' => 'ExamRegistration',
                'route' => 'admin.exam-registrations.parseCsvImport',
            ])
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <button class="btn btn-info" data-toggle="modal" data-target="#csvRemoveModal">
               CSV Import For Remove Registration
            </button>
        </div>
    </div>
    <div class="modal fade" id="csvRemoveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel">@lang('global.app_csvImport')</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-12'>

                            <form class="form-horizontal" method="POST" action="{{ route('admin.exam-registrations.removeParseCsvImport', ['model' => 'ExamRegistration']) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                                    <label for="csv_file" class="col-md-4 control-label">@lang('global.app_csv_file_to_import')</label>

                                    <div class="col-md-6">
                                        <input id="csv_file" type="file" class="form-control-file" name="csv_file" required>

                                        @if($errors->has('csv_file'))
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
    <div class="card">
        <div class="card-header">
            Exam Registration List
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Regulation</label>
                        <select name="regulation" id="regulation" class="form-control select2">
                            <option value="">Select Regulation</option>
                            @foreach ($regulations as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Academic Year</label>
                        <select name="ay" id="ay" class="form-control select2">
                            <option value="">Select AY</option>
                            @foreach ($ays as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Course</label>
                        <select name="course" id="course" class="form-control select2">
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Semester</label>
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
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Exam Type</label>
                        <select name="exam_type" id="exam_type" class="form-control select2">
                            <option value="">Select Exam Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Arrear">Arrear</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group text-right">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="search()">Search</button>
                            <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ExamRegistration text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Regulation
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Academic Year
                        </th>
                        <th>
                            Exam month & Year
                        </th>
                        <th>
                            Course
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            Exam Type
                        </th>
                        {{-- <th>
                            Uploaded Date
                        </th> --}}
                        <th>
                            Action
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.exam-registrations.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'regulation',
                        name: 'regulations'
                    },
                    {
                        data: 'batch',
                        name: 'batch'
                    },
                    {
                        data: 'academic_year',
                        name: 'academic_year'
                    },
                    {
                        data: 'exam_date',
                        name: 'exam_date'
                    },
                    {
                        data: 'course',
                        name: 'course'
                    },
                    {
                        data: 'semester',
                        name: 'semester'
                    },
                    {
                        data: 'exam_type',
                        name: 'exam_type'
                    },
                    // {
                    //     data: 'uploaded_date',
                    //     name: 'uploaded_date'
                    // },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data) {
                            return `@can('download_exam_reg_access')<div><a class="btn btn-xs btn-success" target="_blank" href="{{ url('admin/exam-registrations/download/${data}') }}">Download Excel</span></div>@endcan`;
                        },
                        type: 'html',
                        className: 'text-center'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-ExamRegistration').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function reset() {
            $("#ay").val($("#target option:first").val());
            $("#course").val($("#target option:first").val());
            $("#regulation").val($("#target option:first").val());
            $("#semester").val($("#target option:first").val());
            $("#exam_type").val($("#target option:first").val());
            $('select').select2();
        }

        function search() {
            if ($("#regulation").val() == '') {
                Swal.fire('', 'Please Select Regulation', 'error');
                return false;
            } else if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#course").val() == '') {
                Swal.fire('', 'Please Select Course', 'error');
                return false;
            } else if ($("#semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else if ($("#exam_type").val() == '') {
                Swal.fire('', 'Please Select Exam Type', 'error');
                return false;
            } else {
                let regulation = $("#regulation").val();
                let ay = $("#ay").val();
                let course = $("#course").val();
                let semester = $("#semester").val();
                let exam_type = $("#exam_type").val();
                $.ajax({
                    url: '{{ route('admin.exam-registrations.search') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'regulation': regulation,
                        'ay': ay,
                        'course': course,
                        'semester': semester,
                        'exam_type': exam_type
                    },
                    success: function(response) {
                        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            retrieve: true,
                            data: response.data,
                            aaSorting: [],
                            columns: [{
                                    data: 'placeholder',
                                    name: 'placeholder'
                                },
                                {
                                    data: 'regulation',
                                    name: 'regulations'
                                },
                                {
                                    data: 'batch',
                                    name: 'batch'
                                },
                                {
                                    data: 'academic_year',
                                    name: 'academic_year'
                                },
                                {
                                    data: 'exam_date',
                                    name: 'exam_date'
                                },
                                {
                                    data: 'course',
                                    name: 'course'
                                },
                                {
                                    data: 'semester',
                                    name: 'semester'
                                },
                                {
                                    data: 'exam_type',
                                    name: 'exam_type'
                                },
                                // {
                                //     data: 'uploaded_date',
                                //     name: 'uploaded_date'
                                // },
                                {
                                    data: 'actions',
                                    name: 'actions',
                                    render: function(data) {
                                        return `@can('download_exam_reg_access')<div><a class="btn btn-xs btn-success" target="_blank" href="{{ url('admin/exam-registrations/download/${data}') }}">Download Excel</span></div>@endcan`;
                                    },
                                    type: 'html',
                                    className: 'text-center'
                                }
                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };
                        let table = $('.datatable-ExamRegistration').DataTable(dtOverrideGlobals);
                        table.destroy();
                        table = $('.datatable-ExamRegistration').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                    }
                })
            }

        }
    </script>
@endsection
