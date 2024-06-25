@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    }elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    }else{
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    @if ($type_id == 1 || $type_id == 3)
        <script>
            function onLoadFunction() {
                $('#top_card').hide();
                $('#rep_label').show();
                search();
            }
            window.onload = onLoadFunction;
        </script>
    @endif
    <div class="card" id="top_card">
        <div class="card-header text-center">
            <strong>Syllabus Completion Report</strong>
        </div>
        <div class="card-body">
            <div class="row" id="search-body">
                <div class="col-md-2 col-12">
                    <div class="form-group">
                        <label for="academicyear" class="required">Academic Year</label>
                        <select class="form-control select2" name="academicyear" id="academicyear" required>
                            <option value="">Select AY</option>
                            @foreach ($AcademicYear as $id => $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="course" class="required">Course</label>
                        <select class="form-control select2" name="course" id="course" required>
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $data }}">
                                    {{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- @endif --}}

                <div class="col-md-2 col-12">
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control select2" name="semester" id="semester">
                            <option value="">Select Semester</option>
                            @foreach ($semester as $id => $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <div class="form-group">
                        <label for="section">Section</label>
                        <select class="form-control select2" name="section" id="section">
                            <option value="">Select Section</option>
                            @foreach ($Section as $id => $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <button class="btn btn-primary" style="margin-top:30px" onclick="search()">Search</button>
                </div>
            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-header" id="rep_label" style="display:none;">
            Syllabus Completion Report
        </div>
        <div class="card-body">
            <table class="table table-bordered table-response table-striped table-hover ajaxTable datatable  text-center"
                id="syllabusCompletion">
                <thead>
                    <tr>
                        <th></th>
                        <th>Year</th>
                        <th>Section</th>
                        <th>Semester</th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Faculty Name</th>
                        <th>Faculty Code</th>
                        <th>Proposed Periods</th>
                        <th>Handled Periods</th>
                        <th>Completion Percentage</th>
                        <th>Other Periods</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @if ($type_id == 1 || $type_id == 3)
        <div class="card">
            <div class="card-header">
                Archived Syllabus Completion Report
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4 form-group">
                        <label for="past_ay" class="required">Select Academic Year</label>
                        <select class="select2 form-control" name="past_ay" id="past_ay">
                            <option value="">Select AY</option>
                            @foreach ($getAys as $id => $ay)
                                <option value="{{ $ay }}">{{ $ay }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 form-group">
                        <label for="past_semester" class="required">Select Semester </label>
                        <select class="select2 form-control" name="past_semester" id="past_semester">
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
                    <div class="col-4 form-group">
                        <button class="enroll_generate_bn" style="margin-top:32px;" onclick="getPastRecords()">Get
                            Details</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table
                    class="table table-bordered table-response table-striped table-hover ajaxTable datatable  text-center"
                    id="pastSyllabusCompletion">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Year</th>
                            <th>Section</th>
                            <th>Semester</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Faculty Name</th>
                            <th>Faculty Code</th>
                            <th>Proposed Periods</th>
                            <th>Handled Periods</th>
                            <th>Completion Percentage</th>
                            <th>Other Periods</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
    @parent
    <script>
        function search() {
            var token = $('meta[name="csrf-token"]').attr('content');
            let data = {
                academicYear: $('#academicyear').val(),
                course: $('#course').val(),
                semester: $('#semester').val(),
                section: $('#section').val(),
                _token: token,

            };
            $.ajax({
                url: '{{ route('admin.Syllabus-Completion.search') }}',
                method: 'POST',
                data: data,
                success: function(response) {
                    // console.log(response.data);
                    let dtOverrideGlobals = {
                        deferRender: true,
                        retrieve: true,
                        aaSorting: [],
                        data: response.data,
                        columns: [{
                                data: null,
                                name: 'empty',
                                render: function(data, type, full, meta) {
                                    // console.log(data);
                                    return ' ';
                                }
                            },
                            {
                                data: 'year',
                                name: 'year',
                                // render: function(data, type, full, meta) {
                                //     // console.log(data.attendence);
                                //     if (data.lessonPlanes != null) {
                                //         var unit = data.lessonPlanes.unit;
                                //     } else {
                                //         var unit = '';
                                //     }
                                //     return unit;
                                // }
                            },
                            {
                                data: 'section',
                                name: 'section',

                            },
                            {
                                data: 'Semester',
                                name: 'Semester',

                            },
                            {
                                data: 'subjectCode',
                                name: 'subjectCode',

                                // timeTAble.subjectCode
                                //
                                // render: function(data, type, full, meta) {
                                //     console.log(data);
                                //     // if (data.lessonPlanes != null) {
                                //     //     var unit = data.lessonPlanes.unit;
                                //     // } else {
                                //     //     var unit = '';
                                //     // }
                                //     // return unit;
                                // }

                            },
                            {
                                data: 'subjectName',
                                name: 'subjectName',


                            },
                            {
                                data: 'staffName',
                                name: 'staffName',

                            },
                            {
                                data: 'staffCode',
                                name: 'staffCode',

                            },
                            {
                                data: 'proposedPeriods',
                                name: 'proposedPeriods',

                            },
                            {
                                data: 'handledPeriods',
                                name: 'handledPeriods',

                            },
                            {
                                data: 'percentage',
                                name: 'percentage',

                            },
                            {
                                data: 'others',
                                name: 'others',

                            },
                            {
                                data: 'button',
                                name: 'button',

                            },


                        ],
                        orderCellsTop: true,
                        order: [
                            [1, 'desc']
                        ],
                        pageLength: 10,


                    };

                    let table = $('#syllabusCompletion').DataTable(dtOverrideGlobals);
                    table.destroy();
                    table = $('#syllabusCompletion').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable()
                            .columns.adjust();
                    });
                },
                error: function(xhr, status, error) {
                    // Handle the AJAX error
                }
            });
        }

        function getPastRecords() {
            if ($("#past_ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#past_semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else {

                $.ajax({
                    url: '{{ route('admin.Syllabus-Completion.get-past-records') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {'past_ay': $("#past_ay").val(),'past_semester': $("#past_semester").val()},
                    success: function(response) {
                        // console.log(response.data);
                        let dtOverrideGlobals = {
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
                                    data: 'year',
                                    name: 'year',
                                },
                                {
                                    data: 'section',
                                    name: 'section',

                                },
                                {
                                    data: 'Semester',
                                    name: 'Semester',
                                },
                                {
                                    data: 'subjectCode',
                                    name: 'subjectCode',
                                },
                                {
                                    data: 'subjectName',
                                    name: 'subjectName',
                                },
                                {
                                    data: 'staffName',
                                    name: 'staffName',

                                },
                                {
                                    data: 'staffCode',
                                    name: 'staffCode',
                                },
                                {
                                    data: 'proposedPeriods',
                                    name: 'proposedPeriods',
                                },
                                {
                                    data: 'handledPeriods',
                                    name: 'handledPeriods',
                                },
                                {
                                    data: 'percentage',
                                    name: 'percentage',

                                },
                                {
                                    data: 'others',
                                    name: 'others',

                                },
                                {
                                    data: 'button',
                                    name: 'button',

                                },


                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,


                        };

                        let table = $('#pastSyllabusCompletion').DataTable(dtOverrideGlobals);
                        table.destroy();
                        table = $('#pastSyllabusCompletion').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle the AJAX error
                    }
                });
            }
        }
    </script>
@endsection
