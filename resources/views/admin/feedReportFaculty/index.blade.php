@extends('layouts.admin')
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Student Training Feedback Report
        </div>
        <div class="card-body">
            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <label for="feedback" class="required">Feedback</label>
                    <select class="form-control select2" name="feedback" id="feedback">
                        <option value="">Select Feedback</option>
                        @foreach ($feedback as $item)
                            <option value="{{ $item->feedback->id }}">{{ $item->feedback->name }}</option>
                        @endforeach
                    </select>
                    <span id="feedback_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <label for="ay" class="required">Ay</label>
                    <select class="form-control select2" name="ay" id="ay">
                        <option value="">Select Ay</option>
                        @foreach ($ay as $id => $item)
                            <option value="{{ $id }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <span id="ay_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <label for="course" class="required">Course</label>
                    <select class="form-control select2" name="course" id="course">
                        <option value="">Select Course</option>
                        @foreach ($course as $id => $item)
                            <option value="{{ $id }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <span id="dept_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col">
                    <div id="save_div" class="float-right">
                        <button type="button" onclick="fetchReport()" id="save_btn" class="enroll_generate_bn"
                            style="margin-top: 31px;">Fetch
                            Report</button>
                    </div>
                    <div id="loading_div" style="display: none; margin-top: 31px;">
                        <span class="theLoader"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="secondLoader"></div>
    </div>
    <div class="card">
        <div class="card-header">
            Report
        </div>
        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-feedbackReport text-center">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Year
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Total Student
                        </th>
                        <th>
                            Total Submitted
                        </th>
                        <th>
                            Not Submitted
                        </th>
                        <th>
                            Action
                        </th>

                    </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>
        {{-- <div class="secondLoader"></div> --}}
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            if ($.fn.DataTable.isDataTable('.datatable-feedbackReport')) {
                $('.datatable-feedbackReport').DataTable().destroy();
            }

            let table = $('.datatable-feedbackReport').DataTable();
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function fetchReport() {
            if ($('#feedback').val() != '' && $('#ay').val() != '' && $('#course').val() != '') {
                $('.secondLoader').show();
                $.ajax({
                    url: "{{ route('admin.feedback-faculty.report') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'feedback': $('#feedback').val(),
                        'ay': $('#ay').val(),
                        'dept': $('#course').val()
                    },
                    success: function(response) {
                        let data = response.data
                        let status = response.status
                        if (status == true) {
                            let table = $('.datatable-feedbackReport').DataTable()
                            table.clear().destroy()
                            let body = $('#tbody').empty()
                            let i = 0;
                            $.each(data, function(index, value) {
                                let row = $('<tr>')
                                row.append(`<td></td>`)
                                row.append(`<td>${i+=1}</td>`)
                                row.append(`<td>${value.ay}</td>`)
                                row.append(`<td>${value.dept}</td>`)
                                row.append(`<td>${value.total_student}</td>`)
                                row.append(`<td>${value.submitted}</td>`)
                                row.append(`<td>${value.not_submitted}</td>`)
                                row.append(`<td>
                                    <form action="{{ route('admin.feedback-faculty.view') }}" method="post">
                                        @csrf
                                        <input name="feedback_id" type="hidden" value="${value.feedback_id}">
                                        <input name="dept" type="hidden" value="${value.dept_id}">
                                        <input name="total_student" type="hidden" value="${value.total_student}">
                                        <button type="submit" class="newEditBtn" title="View Report" onclick="viewReport()"><i class="fas fa-file-signature"></i></button>
                                    </form>
                                    <form action="{{ route('admin.feedback-faculty.download') }}" method="post">
                                        @csrf
                                        <input name="feedback_id" type="hidden" value="${value.feedback_id}">
                                        <input name="dept" type="hidden" value="${value.dept_id}">
                                        <input name="total_student" type="hidden" value="${value.total_student}">
                                        <input name="file_type" type="hidden" value="pdf">
                                        <button type="submit" class="newDeleteBtn" title="Download Pdf"><i class="fas fa-download"></i></button>
                                    </form>
                                    <form action="{{ route('admin.feedback-faculty.download') }}" method="post">
                                        @csrf
                                        <input name="feedback_id" type="hidden" value="${value.feedback_id}">
                                        <input name="dept" type="hidden" value="${value.dept_id}">
                                        <input name="total_student" type="hidden" value="${value.total_student}">
                                        <input name="file_type" type="hidden" value="excel">
                                        <button type="submit" class="newViewBtn" title="Download Excel"><i class="fas fa-file-excel"></i></button>
                                    </form>
                                
                                    </td>`)
                                body.append(row)
                            })
                            table = $('.datatable-feedbackReport').DataTable();
                        } else {
                            Swal.fire('', data, 'error');
                        }

                        $('#save_div').show()
                        $('#loading_div').hide()
                        $('.secondLoader').hide();
                    }
                })
            } else {
                Swal.fire('', 'Require All Fields.', 'error');
            }
        }
    </script>
@endsection
