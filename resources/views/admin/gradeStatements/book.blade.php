@extends('layouts.admin')
@section('content')
    {{-- @can('fee_import_access') --}}
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-md-12">
            <button class="btn btn-warning" data-toggle="modal" data-target="#bookImp">
                Import Grade Book
            </button>
        </div>
    </div>
    {{-- @endcan --}}
    <div class="modal fade" id="bookImp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel">Import Grade Book</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-12'>

                            <form class="form-horizontal" method="POST"
                                action="{{ route('admin.grade-book-upload.parseCsvImport', ['model' => 'GradeBook']) }}"
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
         .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-GBList text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>AY</th>
                        <th>Course</th>
                        <th>Exam Month</th>
                        <th>Exam Year</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
            dtButtons.splice(0, 8);

            if ($.fn.DataTable.isDataTable('.datatable-GBList')) {
                $('.datatable-GBList').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.grade-book-upload.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'academic_year',
                        name: 'academic_year'
                    },
                    {
                        data: 'course',
                        name: 'course'
                    },
                    {
                        data: 'exam_month',
                        name: 'exam_month'
                    },
                    {
                        data: 'exam_year',
                        name: 'exam_year'
                    },
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-GBList').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };
    </script>
@endsection
