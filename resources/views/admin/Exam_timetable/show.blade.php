@extends('layouts.admin')
@section('content')
    <div class="container"
        style="
        background-color: #fefefe;
        box-shadow: -2px 3px 12px 4px #c6c0c0a8;
        border-radius: 5px;">
        <div class="form-group" style="padding-top: 20px;padding-left:20px;">
            <a class="btn btn-default" href="{{ route('admin.Exam-time-table.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div style="height: 1px; background-color:black;"></div>

        <form method="POST" action="{{ route('admin.examTimetable.search') }}" enctype="multipart/form-data"class="pt-4">
            @csrf
            <div>
                <div class="d-flex">
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label class="required" for="AcademicYear">Academic Year</label>
                        <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required>
                            <option value="">Please Select</option>
                            @foreach ($AcademicYear as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label for="year" class="required">Year</label>
                        <select class="form-control select2" name="year"
                            id="year">
                            <option value="">Select Year</option>
                            <option value="01" >First Year</option>
                            <option value="02" >Second Year</option>
                            <option value="03" >Third Year</option>
                            <option value="04" >Fourth Year</option>

                        </select>

                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course" required>
                            <option value="">Please Select</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label for="semester" class="required">Semester</label>
                        <select class="form-control select2" name="semester" id="semester" required>
                            <option value="">Please Select</option>
                            @foreach ($semester as $id => $entry)
                                <option value="{{ $id }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                        <label for="section" class="required">Sections</label>
                        <select class="form-control select2" name="section" id="section" required>
                            <option value="">Please Select</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>
        </form>
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-examtimetable">
            <thead>
                <tr>
                    <th width="10">
                    </th>
                    {{-- <th>Module Code</th> --}}
                    <th>Title of the exam</th>
                    {{-- <th>Department</th> --}}
                    <th>Sections</th>
                    <th>Exam Date</th>
                    <th>AN/FN</th>
                    <th>Subject</th>
                    <th>semester</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
        <br />
    </div>
    <div class="footer">
        <div class="container">
            <h6 class="pull-right pb-4">
                Note: First <a href="{{ route('admin.examTimetable.create') }}">Create the Exam time table</a>
            </h6>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        window.onload = function() {
            // Select all select elements by their IDs
            var $selects = $('#AcademicYear,#course, #semester,#year,#section');

            $selects.change(function() {
                var allFilled = true;
                $selects.each(function() {
                    if ($(this).val() === '') {
                        allFilled = false;
                        return false; // Exit the loop if any select box is empty
                    }
                });

                var token = $('meta[name="csrf-token"]').attr('content');

                var data = {
                    ay: $('#AcademicYear').val(),
                    course: $('#course').val(),
                    semester: $('#semester').val(),
                    year: $('#year').val(),
                    section: $('#section').val(),
                    _token: token
                };
                if (allFilled) {

                    $.ajax({
                        url: "{{ route('admin.examTimetable.search') }}",
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            console.log(response);
                            let dtOverrideGlobals = {
                                // buttons: dtButtons,
                                // processing: true,
                                // serverSide: true,
                                deferRender: true,
                                retrieve: true,
                                aaSorting: [],
                                data: response.data,
                                columns: [{
                                        data: 'empty',
                                        name: 'empty',
                                        render: function(data, type, full, meta) {
                                            // Add static data here
                                            return ' ';
                                        }
                                    },
                                    {
                                        data: 'exam_name',
                                        name: 'exam_name'
                                    },
                                    // {
                                    //     data: 'department',
                                    //     name: 'department'
                                    // },
                                    {
                                        data: 'sections',
                                        name: 'sections'
                                    },
                                    {
                                        data: 'date',
                                        name: 'date'
                                    },
                                    {
                                        data: 'time_period',
                                        name: 'time_period'
                                    },
                                    {
                                        data: 'subject',
                                        name: 'subject'
                                    },
                                    {
                                        data: 'semester',
                                        name: 'semester'
                                    },
                                    {
                                        data: 'Duration',
                                        name: 'Duration'
                                    },
                                    {
                                        data: 'actions',
                                        name: 'actions'
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
                            // Handle errors
                            console.log('An error occurred: ' + error);
                        }
                    });
                }
            });

        };
    </script>
@endsection