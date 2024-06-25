@extends('layouts.admin')
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
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="">Register No</label>
                        <select name="user_name_id" id="user_name_id" class="form-control select2">
                            <option value="">Select Register No</option>
                            @if (count($students) > 0)
                                @foreach ($students as $student)
                                    <option value="{{ $student->user_name_id }}">{{ $student->name }}
                                        ({{ $student->register_no }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required d-block " for="result_sem">Result Semester</label>
                        <select class="form-control select2" name="result_sem" id="result_sem" required>
                            <option value="">Select Result Semester</option>
                            <option value="All">All Semesters</option>
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
                    <div class="form-group text-left">
                        <button class="enroll_generate_bn bg-success" style="margin-top:32px;"
                            onclick="getGradeBook()">Submit</button>
                        <button class="enroll_generate_bn bg-warning" style="margin-top:32px;"
                            onclick="reset()">Reset</button>
                    </div>
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
            <div class="row" id="detailsDiv" style="display:none;">
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
        function reset() {
            $("#result_sem").val($("#target option:first").val());
            $("#user_name_id").val($("#target option:first").val());
            $('select').select2();
            $("#detailsDiv").hide()
        }

        function getGradeBook() {

            if ($("#user_name_id").val() == '') {
                Swal.fire('', 'Please Select Register No', 'error');
                return false;
            } else if ($("#result_sem").val() == '') {
                Swal.fire('', 'Please Select Result Semester', 'error');
                return false;
            } else {
                $("#detailsDiv").hide()
                let result_sem = $('#result_sem').val();
                let user_name_id = $('#user_name_id').val();

                if ($.fn.DataTable.isDataTable('#datatable-gradebook')) {
                    $('#datatable-gradebook').DataTable().destroy();
                }
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                dtButtons.splice(0, 8);
                let print = {
                    text: 'Print',
                    url: "{{ url('admin/grade-book/print-grades') }}" + result_sem + "/" + user_name_id,
                    className: "btn btn-default btn-sm",
                    id: 'printBtn',
                    action: function(e, dt, node, config) {
                        var tempUrl = "{{ url('admin/grade-book/print-grades') }}/" + result_sem + "/" + user_name_id;
                        window.open(tempUrl);
                    }
                }
                dtButtons.push(print);
                console.log(dtButtons)
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.grade-book.get-grades') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'result_sem': result_sem,
                            'user_name_id': user_name_id,
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
                $("#detailsDiv").show()
            }
        }
    </script>
@endsection
