@extends('layouts.admin')
@section('content')
    <style>
        .num-r {
            background: #eee;
            width: 60px;
            text-align: center;
            height: 60px;
            padding: 13px 0;
            border-radius: 50px;
            font-size: 22px;
            font-weight: 800;
            color: #989393;
        }
    </style>
    <div class="container"
        style="
    background-color: #fefefe;
    box-shadow: -2px 3px 12px 4px #c6c0c0a8;
    border-radius: 5px;">
        <div class="">
            <div class="card-header">
                <div>Syllabus Completion Report</div>
            </div>
            <div>
                <div class="d-flex">
                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <label for="department" class="required">Department</label>
                        <select class="form-control select2" name="department" id="department" required>
                            <option value="">please Select</option>
                            @foreach ($department as $entry)
                                <option value="{{ $entry->name }}">
                                    {{ $entry->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="" for="fromdate">From Date</label>
                            <input type="text" class=" form-control date" id="fromdate"
                                placeholder="Enter The From Date" name="fromdate">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="" for="todate">To Date</label>
                            <input type="text" class=" form-control date" placeholder="Enter The To Date" id="todate"
                                name="todate">
                        </div>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <label for="staffname" class="required">Staff Name </label>
                        <select class="form-control select2" name="staffname" id="staffname" required>
                            <option value="">please Select</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="card mt-4" id="tableShow"
        style="display:none;box-shadow: -2px 3px 12px 4px #c6c0c0a8;
    border-radius: 5px; display: none;">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-examtimetable"
                id="dataTable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th class="text-center">Unit NO</th>
                        <th class="text-center">Topic Discussed</th>
                        <th class="text-center">Porposed Date</th>
                        {{-- <th class="text-center">Porposed Period</th> --}}
                        <th class="text-center">Actual Date</th>
                        <th class="text-center">Actual Period</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent

    <script>
        window.onload = function() {
            var $select = $('#department');
            var staffName = $('#staffname');

            $select.change(function() {
                if ($(this).val() !== '') {
                    var token = $('meta[name="csrf-token"]').attr('content');

                    var data = {
                        department: $(this).val(),
                        _token: token
                    };

                    $.ajax({
                        url: '{{ route('admin.staffFilter.search') }}',
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            staffName.empty(); // Clear existing options
                            staffName.append('<option value="">Please select</option>');
                            $.each(response.response, function(index, user) {
                                var option = $('<option>').text(user.name).val(user
                                    .user_name_id);
                                staffName.append(option);
                            });
                        },
                        error: function(xhr, status, error) {

                        }
                    });
                }
            });

            var $staffname = $('#staffname');
            var $fromdate = $('#fromdate');
            var $todate = $('#todate');

            $staffname.add($fromdate).add($todate).change(function() {
                var allFilled = true;
                var fromDateValue = $fromdate.val();
                var toDateValue = $todate.val();

                if ($staffname.val() === '' || fromDateValue === '' || toDateValue === '') {
                    allFilled = false;
                }

                if (allFilled) {
                    var token = $('meta[name="csrf-token"]').attr('content');

                    var data = {
                        staff_name: $staffname.val(),
                        fromdate: fromDateValue,
                        todate: toDateValue,
                        _token: token
                    };

                    $.ajax({
                        url: '{{ route('admin.staffFilter.showTable') }}',
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            $('#tableShow').show();
                            let dtOverrideGlobals = {
                                deferRender: true,
                                retrieve: true,
                                aaSorting: [],
                                data: response,
                                columns: [{
                                        data: null,
                                        name: 'empty',
                                        render: function(data, type, full, meta) {
                                            return ' ';
                                        }
                                    },
                                    {
                                        data: null,
                                        name: '',
                                        render: function(data, type, full, meta) {
                                            if (data.lessonPlanes != null) {
                                                var unit = data.lessonPlanes.unit;
                                            } else {
                                                var unit = '';
                                            }
                                            return unit;
                                        }
                                    },
                                    {
                                        data: null,
                                        name: '',
                                        render: function(data, type, full, meta) {
                                            if (data.lessonPlanes != null) {
                                                var topic = data.lessonPlanes.topic;
                                            } else {
                                                var topic = '';
                                            }
                                            return topic;
                                        }
                                    },
                                    {
                                        data: null,
                                        name: '',
                                        render: function(data, type, full, meta) {
                                            if (data.lessonPlanes != null) {
                                                var proposed_date = data.lessonPlanes
                                                    .proposed_date;
                                            } else {
                                                var proposed_date = '';
                                            }
                                            return proposed_date;
                                        }
                                    },
                                    {
                                        data: null,
                                        name: '',
                                        render: function(data, type, full, meta) {
                                            if (data.attendence != null) {
                                                var date = data.attendence.date;
                                            } else {
                                                var date = '';
                                            }
                                            return date;
                                        }
                                    },
                                    {
                                        data: null,
                                        name: '',
                                        render: function(data, type, full, meta) {
                                            if (data.attendence != null) {
                                                var period_id = data.attendence
                                                    .period_id;
                                            } else {
                                                var period_id = '';
                                            }
                                            return period_id;
                                        }
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
                        },
                        error: function(xhr, status, error) {
                        }
                    });
                }
            });
        }
    </script>
@endsection
