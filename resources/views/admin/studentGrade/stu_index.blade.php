@extends('layouts.studentHome')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <span class="text-center" style="font-size:1.2rem;"> <b>Grade Book </b></span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 ">
                    <label class="required d-block " for="semester">Semester</label>
                    <select class="form-control select2" name="semester" id="semester" required>
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

                <div class="from-group col-xl-3 col-lg-2 col-md-2 col-sm-2 col-12" style="padding-top: 32px;">
                    <button id='gradeMark' class="enroll_generate_bn">Submit</button>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center table-hover ajaxTable datatable"
                    id="datatable-gradebook">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="text-align:center;">Academic Year</th>
                            <th style="text-align:center;">Semester</th>
                            <th style="text-align:center;">Subject Code</th>
                            <th style="text-align:center;">Subject Title</th>
                            <th style="text-align:center;">Grade</th>
                            <th style="text-align:center;">Result</th>
                            <th style="text-align:center;">Exam Month and Exam Year</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-3"><b>RA</b> – Reappearance is required</div>
                <div class="col-3"><b>RA*</b> - Absent for End Exam</div>
                <div class="col-3"><b>W/WD</b> – Withdrawal</div>
                <div class="col-3"><b>SA</b> – Shortage of Attendance</div>
                <div class="col-3"><b>SE</b> – Sports Exemption</div>
                <div class="col-3"><b>WH1</b> – Suspected Malpractice</div>
                <div class="col-3"><b>WH2</b> – Contact COE office</div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: {
                    url: "{{ route('admin.student_grade_mark.get_marks') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'semester': 'All',
                    },
                },
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'ay',
                        name: 'ay'
                    },
                    {
                        data: 'semester',
                        name: 'semester'
                    },
                    {
                        data: 'subject_code',
                        name: 'subject_code'
                    },
                    {
                        data: 'subject_name',
                        name: 'subject_name'
                    },
                    {
                        data: 'grade_letter',
                        name: 'grade_letter'
                    },
                    {
                        data: 'result',
                        name: 'result'
                    },
                    {
                        data: 'exam_date',
                        name: 'exam_date'
                    },

                ],
                orderCellsTop: true,
                pageLength: 10,
            };

            let table = $('#datatable-gradebook').DataTable(dtOverrideGlobals);

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

        $('#gradeMark').on('click', function(e) {
            let semester = $('#semester').val();
            if (semester != '') {
                if ($.fn.DataTable.isDataTable('#datatable-gradebook')) {
                    $('#datatable-gradebook').DataTable().destroy();
                }
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.student_grade_mark.get_marks') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'semester': semester,
                        },
                    },
                    columns: [{
                            data: 'placeholder',
                            name: 'placeholder'
                        },
                        {
                            data: 'ay',
                            name: 'ay'
                        },
                        {
                            data: 'semester',
                            name: 'semester'
                        },
                        {
                            data: 'subject_code',
                            name: 'subject_code'
                        },
                        {
                            data: 'subject_name',
                            name: 'subject_name'
                        },
                        {
                            data: 'grade_letter',
                            name: 'grade_letter'
                        },
                        {
                            data: 'result',
                            name: 'result'
                        },
                        {
                            data: 'exam_date',
                            name: 'exam_date'
                        },

                    ],
                    orderCellsTop: true,
                    pageLength: 10,
                };

                let table = $('#datatable-gradebook').DataTable(dtOverrideGlobals);

                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
            } else {
                Swal.fire('', 'Please Select The Semester', 'error');
                return false;
            }
        });
    </script>
@endsection
