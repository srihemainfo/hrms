@extends('layouts.teachingStaffHome')
@section('content')
    {{-- <a class="btn  btn-primary" href="{{ route('admin.staff-subjects.lesson-plan.add') }}">
        Create Lesson Plan
    </a> --}}
    <style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card" style="margin-top:1rem;">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> My Lesson Plans</h5>
        </div>
        <div class="card-body" style="max-width:100%;overflow-x:auto;">
            <table class="table table-bordered table-striped table-hover datatable datatable-lesson_plan text-center"
                style="min-width:700px;">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- {{ dd($get_lessons) }} --}}
                    @if (count($get_lessons) > 0)
                        @foreach ($get_lessons as $data)
                            <tr>
                                <td>
                                    {{ $data['short_form'] }}
                                </td>
                                <td>
                                    {{ $data['subject_name'] }} ({{ $data['subject_code'] }})
                                </td>
                                <td>
                                    @if ($data['status'] == 99)
                                        <span class="btn btn-xs btn-info" style="margin-left:3px;">
                                            Incomplete
                                        </span>
                                    @endif
                                    @if ($data['status'] == 0)
                                        <span class="btn btn-xs btn-warning" style="margin-left:3px;">
                                            Pending
                                        </span>
                                    @endif
                                    @if ($data['status'] == 1)
                                        <span class="btn btn-xs btn-success" style="margin-left:3px;">
                                            Approved
                                        </span>
                                    @endif
                                    @if ($data['status'] == 2)
                                        <span class="btn btn-xs btn-danger" style="margin-left:3px;">
                                            Need Revision
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($data['status'] == '')
                                        <a class="btn btn-xs btn-warning"
                                            href="{{ route('admin.staff-subjects.lesson-plan.add', ['class' => $data['class_name'], 'short_form' => $data['short_form'], 'subject_name' => $data['subject_name'], 'subject_code' => $data['subject_code'], 'subject' => $data['subject_id']]) }}">
                                            Create Lesson Plan
                                        </a>
                                    @endif
                                    @if ($data['status'] == 99)
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.staff-subjects.lesson-plan.complete', ['enroll' => $data['class_name'], 'subject' => $data['subject_id'], 'status' => $data['status']]) }}">
                                            Complete Lesson Plan
                                        </a>
                                    @endif
                                    @if ($data['status'] != '')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.staff-subjects.lesson-plan.view', ['enroll' => $data['class_name'], 'subject' => $data['subject_id'], 'status' => $data['status']]) }}">
                                            View
                                        </a>
                                    @endif
                                    @if ($data['status'] == 2)
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.staff-subjects.lesson-plan.edit', ['enroll' => $data['class_name'], 'subject' => $data['subject_id'], 'status' => $data['status']]) }}">
                                            Edit
                                        </a>
                                    @endif
                                    @if ($data['status'] != 1 && $data['status'] != 2 && $data['status'] != '')
                                        <form
                                            action="{{ route('admin.staff-subjects.lesson-plan.delete', ['enroll' => $data['class_name'], 'subject' => $data['subject_id'], 'status' => $data['status']]) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Date Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> Archived Lesson Plans</h5>
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
        <div class="card-body" style="max-width:100%;overflow-x:auto;">
            <table class="table table-bordered table-striped table-hover text-center" style="min-width:1000px;">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <tr>
                        <td colspan="4">No Data Available...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(0, 2);
            // console.log(dtButtons)
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            };
            //  console.log(dtOverrideGlobals)
            let table = $('.datatable-lesson_plan').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function getPastRecords() {
            if ($("#past_ay").val() == '') {
                Swal.fire('', 'Please Select AY', 'error');
                return false;
            } else if ($("#past_semester").val() == '') {
                Swal.fire('', 'Please Select Semester', 'error');
                return false;
            } else {
                $("#tbody").html(`<tr><td colspan="4">Loading...</td></tr>`);
                $.ajax({
                    url: '{{ route('admin.staff-subjects.lesson-plan.get-past-records') }}',
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'past_ay': $("#past_ay").val(),
                        'past_semester': $("#past_semester").val()
                    },
                    success: function(response) {
                        let status = response.status;
                        let data = response.data;
                        if (status == true) {
                            let rows = '';
                            $.map(data, function(value, index) {
                                console.log(value)
                                var lessonStatus = '';
                                var lessonAction = '';
                                if (value['status'] != '') {
                                    lessonAction = `<a class="btn btn-xs btn-primary" href="{{ url('admin/staff-subjects/lesson-plan/view') }}`+`/`+value['class_name']+`/`+value['subject_id']+`/`+value['status']+`"> View </a>`;
                                }
                                if (value['status'] == 99) {
                                    lessonStatus =
                                        `<span class="btn btn-xs btn-info" style="margin-left:3px;">Incomplete</span>`;
                                } else if (value['status'] == 0) {
                                    lessonStatus =
                                        `<span class="btn btn-xs btn-warning" style="margin-left:3px;">Pending</span>`;
                                } else if (value['status'] == 1) {
                                    lessonStatus =
                                        `<span class="btn btn-xs btn-success" style="margin-left:3px;">Approved</span>`;
                                } else if (value['status'] == 2) {
                                    lessonStatus =
                                        `<span class="btn btn-xs btn-danger" style="margin-left:3px;">Need Revision</span>`;
                                }
                                rows +=
                                    `<tr><td>${value['short_form']}</td><td>${value['subject_name']} (${value['subject_code']})</td><td>${lessonStatus}</td><td>${lessonAction}</td></tr>`;

                            });
                            $("#tbody").html(rows);
                        } else {
                            Swal.fire('', data, 'error');
                            $("#tbody").html(`<tr><td colspan="4">No Data Available...</td></tr>`);
                        }
                    }
                })
            }
        }
    </script>
@endsection
