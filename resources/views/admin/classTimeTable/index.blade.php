@extends('layouts.admin')
@section('content')
    <style>
        .new-div-hidden {
            /* opacity: 0; */
            /* transition: opacity 0.5s ease; */
            display: none;
        }

        .new-div-show {
            /* opacity: 1; */
            /* transition: opacity 1.0s ease; */
            display: block;
        }
    </style>
    {{-- {{ dd($created_time_tables) }} --}}
    @can('create_class_time_table_access')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.class-time-table.create') }}">
                    Create Class Time Table
                </a>
            </div>
        </div>
    @endcan

    @if (auth()->user()->roles[0]->id == '1' || auth()->user()->roles[0]->id == '15' || auth()->user()->roles[0]->id == '14')
        <div class="card">
            <div class="card-header">
                Search
            </div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data"class="pt-4">
                    @csrf
                    <div class="row">
                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                            <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                            <select class="form-control select2 " name="course" id="course" required>
                                <option value="">Please Select</option>
                                @foreach ($courses as $id => $entry)
                                    <option value="{{ $id }}">
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                            <label class="required" for="accademicyear	">Academic year</label>
                            <select class="form-control select2 " name="accademicyear" id="accademicyear" required>
                                <option value="">Please Select</option>
                                @foreach ($AcademicYear as $id => $entry)
                                    <option value="{{ $entry }}">
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                            <label for="semesterType" class="required">Semester Type</label>
                            <select class="form-control select2" name="" id="semesterType" required>
                                <option value=""> Select SemType</option>
                                <option value="ODD">ODD</option>
                                <option value="Even">EVEN</option>

                            </select>
                        </div>
                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                            <label for="semester" class="required">Semester</label>
                            <select class="form-control select2" name="semester" id="semester" required>
                                <option value="0">Select Semester</option>
                                @foreach ($semester as $id => $entry)
                                    <option value="{{ $entry }}">
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                            <label for="section" class="required">Section</label>
                            <select class="form-control select2" name="section" id="section" required>
                                <option value="">Select Section</option>
                                @foreach ($section as $id => $entry)
                                    <option value="{{ $entry }}">
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6 new-div-hidden" id="newDiv">
                            <div>
                                <button type="button" class="btn btn-primary"
                                    style="margin-top: 29px;
                            margin-left: 15px;
                            max-width: 155px;">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    @endif


    <div class="card">
        <div class="card-header">
            Class Time Table {{ trans('global.list') }}
        </div>

        <div class="card-body" style="max-width:100%;min-width:100%;overflow-x: auto;">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Class Name</th>
                        <th>Shift</th>
                        <th>Created By</th>
                        <th>With Effect From</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @if (count($created_time_tables) > 0)
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($created_time_tables as $data)
                            @php
                                $i++;
                                $user = \App\Models\User::find($data[0]->created_by);
                                $version = DB::table('timetable_versions')
                                    ->where('class_id', $data[0]->class_name)
                                    ->latest('updated_at')
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                    @foreach ($class_name as $id => $entry)
                                        {{-- {{ dd($data[0]->class_name) }} --}}
                                        @if ($id == $data[0]->class_name)
                                            {{ $entry }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    {{ $data[0]->shift ? $data[0]->shift->Name : '' }}
                                    
                                </td>
                                <td>
                                    @if ($user)
                                        {{ $user->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($version)
                                        {{ $version->updated_at }}
                                    @else
                                        {{ $data[0]['created_at']->format('d-m-Y') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($version)
                                        <a href="#" class="dropDown" onclick="dropdown(this)"
                                            data-classId="{{ $data[0]->class_name }}"
                                            data-attr="{{ $version->version }}">{{ $version->version }}</a>
                                    @endif
                                </td>

                                <td>
                                    {{-- {{ dd($data[0]->status) }} --}}
                                    @if ($data[0]->status == 0)
                                        @if (auth()->user()->roles[0]->id == 15 || auth()->user()->roles[0]->id == 1)
                                            <div style="display:flex;justify-content:space-around;">
                                                <div>
                                                    <button type="submit" name="accept" value="{{ $data[0]->id }}"
                                                        class="btn btn-xs btn-success"
                                                        onclick="accept(this)">Accept</button>
                                                </div>
                                                <div>
                                                    <button type="submit" name="reject" value="{{ $data[0]->id }}"
                                                        class="btn btn-xs btn-danger" onclick="reject(this)">Reject</button>
                                                </div>
                                            </div>
                                        @else
                                            <span class="btn btn-xs btn-warning">Pending</span>
                                        @endif
                                    @elseif($data[0]->status == 1)
                                        <span class="btn btn-xs btn-success">Approved</span>
                                    @elseif($data[0]->status == 2)
                                        <span class="btn btn-xs btn-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-primary"
                                        href="{{ route('admin.class-time-table.show', $data[0]->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                    <a class="btn btn-xs btn-warning"
                                        href="{{ route('admin.class-time-table-two.live_show', $data[0]->id) }}">
                                        Live View
                                    </a>
                                    @can('edit_class_time_table_access')
                                        {{-- @if ($data[0]->status == 2) --}}
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.class-time-table.edit', $data[0]->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                        {{-- @endif --}}
                                    @endcan
                                    @can('delete_class_time_table_access')
                                        @if ($data[0]->status != 1)
                                            <form action="{{ route('admin.class-time-table.destroy', $data[0]->id) }}"
                                                method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger"
                                                    value="{{ trans('global.delete') }}">
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"> No Data Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Time Table Versions</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th class="text-center">S No</th>
                                <th class="text-center">Class Name</th>
                                <th class="text-center">Version </th>
                                <th class="text-center">Created / modified Date </th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="model-Body">

                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let rejected_reason = document.getElementById('rejected_reason');

        let id = document.getElementById('id');

        function reject(element) {
            Swal.fire({
                title: 'Reject Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (username) => {

                    let data = {
                        'id': element.value,
                        'status': 'Rejected',
                        'rejected_reason': username
                    }
                    $.ajax({
                        url: '{{ route('admin.class-time-table.status_update') }}',
                        type: 'POST',
                        data: {
                            'data': data,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire(
                                'Done!',
                                'You Rejected the Request!',
                                'success'
                            )
                            location.reload();
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log(xhr.responseText);
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            })

        }

        function accept(element) {


            let data = {
                'id': element.value,
                'status': 'Approved',
                'rejected_reason': null
            }
            $.ajax({
                url: '{{ route('admin.class-time-table.status_update') }}',
                type: 'POST',
                data: {
                    'data': data,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    Swal.fire(
                        'Done!',
                        'You Approved the Request!',
                        'success'
                    )
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                }
            });


        }
        var $selects = $('#course, #accademicyear, #semester, #section');

        function reset() {
            $selects.select2();
            $('#semesterType').select2();
            $('#semesterType').val('');

            var newDiv = document.getElementById("newDiv");
            newDiv.classList.add("new-div-hidden");
            newDiv.classList.remove("new-div-show");
            newDiv.removeAttribute("style");
            var divs = document.querySelectorAll(
                ".form-group[class*='col-xl-'][class*='col-lg-'][class*='col-md-'][class*='col-sm-'][class*='col-']");

            for (var i = 0; i < divs.length; i++) {

                divs[i].classList.add("form-group", "col-xl-3", "col-lg-3", "col-md-3", "col-sm-3", "col-12");
                divs[i].classList.remove("form-group", "col-xl-2", "col-lg-2", "col-md-2", "col-sm-2", "col-6");
            }



        }


        window.addEventListener('DOMContentLoaded', function() {
            // Select all select elements by their IDs
            var $selects = $('#course, #accademicyear, #semester, #section');
            var divs = '';
            var newDiv = '';
            $selects.change(function() {
                var allFilled = true;
                $selects.each(function() {
                    if ($(this).val() === '') {
                        allFilled = false;
                        return false; // Exit the loop if any select box is empty
                    }
                });

                if (allFilled) {
                    $selects.select2();
                    $('#semesterType').select2();
                    $('#semesterType').val('');

                    divs = document.querySelectorAll(
                        ".form-group[class*='col-xl-'][class*='col-lg-'][class*='col-md-'][class*='col-sm-'][class*='col-']"
                    );
                    for (var i = 0; i < divs.length; i++) {
                        divs[i].classList.add("form-group", "col-xl-2", "col-lg-2", "col-md-2", "col-sm-2",
                            "col-6");
                        divs[i].classList.remove("form-group", "col-xl-3", "col-lg-3", "col-md-3",
                            "col-sm-3", "col-12");
                    }

                    // Show the new div with animation
                    newDiv = document.getElementById("newDiv");
                    newDiv.classList.add("new-div-show");
                    newDiv.classList.remove("new-div-hidden");
                }

            });

            var $submit = $('#newDiv');

            $submit.on('click', function() {
                var token = $('meta[name="csrf-token"]').attr('content');

                var data = {
                    course: $('#course').val(),
                    semester: $('#semester').val(),
                    section: $('#section').val(),
                    accademicyear: $('#accademicyear').val(),
                    _token: token
                };

                $.ajax({
                    url: "{{ route('admin.class-time-table.search') }}",
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        var checkTablesArray = Object.values(response.check_tables);
                        console.log(checkTablesArray.length);

                        var tableBody = $('#table-body');
                        tableBody.empty(); // Clear the existing table body content

                        // Generate table rows dynamically using the response data
                        if (checkTablesArray.length > 0) {
                            $.each(checkTablesArray, function(index, data) {
                                var newRow = $('<tr></tr>');

                                // Generate each table cell dynamically based on the data
                                var countCell = $('<td></td>').text(index + 1);
                                var classCell = $('<td></td>').text(data.class_name ||
                                    "");
                                var userCell = $('<td></td>').text(data.user ? data.user
                                    .name : "");
                                var updatedAtCell = $('<td></td>').text(data.version ?
                                    data.version.updated_at : "");
                                var versionCell = $('<td></td>').append(data.version ?
                                    $('<a class="dropDown"></a>').text(data.version
                                        .version).attr({
                                        'href': '#',
                                        'data-attr': data.version.version,
                                        'data-classId': data.classId ? data
                                            .classId : '',
                                    }).click(function() {
                                        dropdown(this);
                                    }) : "");



                                var statusCell = $('<td></td>');
                                var actionCell = $('<td></td>');

                                // Generate buttons and status based on the data
                                if (data.status == 0) {
                                    if (authUserRoleId == 15) {
                                        var acceptBtn = $('<button></button>')
                                            .attr('type', 'submit')
                                            .attr('name', 'accept')
                                            .val(data.id)
                                            .addClass(
                                                'btn btn-xs btn-success accept-btn')
                                            .text('Accept');
                                        var rejectBtn = $('<button></button>')
                                            .attr('type', 'submit')
                                            .attr('name', 'reject')
                                            .val(data.id)
                                            .addClass(
                                                'btn btn-xs btn-danger reject-btn')
                                            .text('Reject');
                                        var buttonDiv = $('<div></div>')
                                            .css('display', 'flex')
                                            .css('justify-content', 'space-around')
                                            .append($('<div></div>').append(acceptBtn))
                                            .append($('<div></div>').append(rejectBtn));
                                        statusCell.append(buttonDiv);
                                    } else {
                                        var statusSpan = $('<span></span>')
                                            .addClass('btn btn-xs btn-warning')
                                            .text('Pending');
                                        statusCell.append(statusSpan);
                                    }
                                } else if (data.status == 1) {
                                    var statusSpan = $('<span></span>')
                                        .addClass('btn btn-xs btn-success')
                                        .text('Approved');
                                    statusCell.append(statusSpan);
                                } else if (data.status == 2) {
                                    var statusSpan = $('<span></span>')
                                        .addClass('btn btn-xs btn-danger')
                                        .text('Rejected');
                                    statusCell.append(statusSpan);
                                }

                                var viewBtn = $('<a></a>')
                                    .attr('href', '/admin/class-time-table/' + data.id)
                                    .addClass('btn btn-xs btn-primary')
                                    .text('View');
                                actionCell.append(viewBtn);
                                var liveViewBtn = $('<a></a>')
                                    .attr('href',
                                        '/admin/class-time-table-two/live_show/' + data
                                        .id)
                                    .addClass('btn btn-xs btn-warning')
                                    .text('Live View');
                                actionCell.append(liveViewBtn);
                                @if (Auth::user()->can('edit_class_time_table_access'))
                                    var editBtn = $('<a></a>')
                                        .attr('href', '/admin/class-time-table/' + data
                                            .id + '/edit')
                                        .addClass('btn btn-xs btn-info')
                                        .text('Edit');
                                    actionCell.append(editBtn);
                                @endif

                                @if (Auth::user()->can('delete_class_time_table_access'))
                                    var deleteForm = $('<form></form>')
                                        .attr('action', '/admin/class-time-table/' +
                                            data.id)
                                        .attr('method', 'POST')
                                        .attr('onsubmit',
                                            'return confirm("Are you sure?");')
                                        .css('display', 'inline-block');

                                    deleteForm.append($('<input>')
                                        .attr('type', 'hidden')
                                        .attr('name', '_method')
                                        .val('DELETE'));

                                    deleteForm.append($('<input>')
                                        .attr('type', 'hidden')
                                        .attr('name', '_token')
                                        .val('{{ csrf_token() }}'));

                                    deleteForm.append($('<input>')
                                        .attr('type', 'submit')
                                        .addClass('btn btn-xs btn-danger')
                                        .val('Delete'));

                                    actionCell.append(deleteForm);
                                @endif

                                newRow.append(countCell, classCell, userCell,
                                    updatedAtCell,
                                    versionCell, statusCell, actionCell);
                                tableBody.append(newRow);
                            });
                        } else {
                            // Handle case when no data is returned
                            var emptyRow = $('<tr></tr>').append($('<td colspan="7"></td>')
                                .text('No data available'));
                            tableBody.append(emptyRow);
                            alert('No Data For This Search')
                            // location.reload();

                        }
                        $selects.val('');
                        $('#semesterType').select2();
                        $('#semesterType').val('');
                        $('#semester').empty().append(
                            '<option value="">Please Select</option>');

                        $selects.select2();
                        // divs.val(' ');
                        reset();
                        allFilled = false;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error',
                                    'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                });
            });
        });




        function dropdown(element) {
            var versionNumber = $(element).data('attr');
            var classId = $(element).data('classid');
            console.log(versionNumber);
            console.log(classId);

            if (classId != '') {
                versionShow(versionNumber, classId);

            }
            $('#myModal').modal('show');
        };

        function versionShow(versionNumber, classId) {
            // console.log(versionNumber);
            // console.log(classId);


            var token = $('meta[name="csrf-token"]').attr('content');

            var data = {
                versionNumber: versionNumber,
                classId: classId,
                // section: $('#section').val(),
                // accademicyear: $('#accademicyear').val(),
                _token: token
            };
            $.ajax({
                url: "{{ route('admin.class-time-table.versionShow') }}",
                method: 'POST',
                data: data,
                success: function(response) {
                    var length = response.versionShowing.length;
                    // console.log(response.versionShowing);
                    // You can iterate over the array and access each object
                    // response.versionShowing.forEach(function(version) {
                    //     console.log(version); // Print each version object
                    // });
                    // console.log(response);
                    var tableBody = $('#model-Body');
                    tableBody.empty();
                    if (length > 0) {
                        // alert('gweubu')
                        $.each(response.versionShowing, function(index, data) {
                            console.log(data.all);

                            var versionRow = $('<tr></tr>');
                            var slCell = $('<td class="text-center"></td>').text(index + 1 || "");
                            var classnameCell = $('<td class="text-center"></td>').text(data
                                .class_name || "");
                            var versionCell = $('<td class="text-center"></td>').text(data.all && data
                                .all.version ? data
                                .all.version : "");
                            var ModifiedDate = $('<td class="text-center"></td>').text(data.all && data
                                .all.created_at ? data
                                .all.created_at : "");
                            var actionCell = $('<td class="text-center"></td>');
                            if (typeof data.all.id !== 'undefined' && data.all.id !== '') {
                                var viewBtn = $('<a class="text-center"></a>')
                                    .attr('href', '/admin/class-time-table/version/' + data.all.id)
                                    .attr('target',
                                        '_blank') // Add target="_blank" to open in a new window or tab
                                    .addClass('btn btn-xs btn-primary')
                                    .text('View');

                                actionCell.append(viewBtn);
                            }


                            // Append the cells to the row
                            versionRow.append(slCell, classnameCell, versionCell, ModifiedDate,
                                actionCell);

                            // Append the row to the table
                            $('#model-Body').append(versionRow);
                        });

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
            });
        }
        $('#semesterType').change(function() {
            var selectedSection = $(this).val();
            var semesterSelect = $('#semester');
            semesterSelect.empty();

            if (selectedSection === 'ODD') {
                for (var i = 1; i <= 8; i += 2) {
                    semesterSelect.append($('<option>', {
                        value: i,
                        text: i
                    }));
                }
            } else if (selectedSection === 'Even') {
                for (var i = 2; i <= 8; i += 2) {
                    semesterSelect.append($('<option>', {
                        value: i,
                        text: i
                    }));
                }
            }
        });
    </script>
@endsection
