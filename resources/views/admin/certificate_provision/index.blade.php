@php
    if (auth()->user()->roles[0]->id == 27) {
        $layout = 'layouts.non_techStaffHome';
    } else {
        $layout = 'layouts.admin';
    }
@endphp
@extends($layout)
@section('content')
    <style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }

        .select2-container {
            width: 100% !important;
        }

        span {
            cursor: pointer;
        }

        /* .nav-span {
            color: #007bff !important;
        }

        .active {
            color: #495057 !important;
        } */
    </style>
    <div class="card">
        <div class="card-header">
            <span class="text-primary" style="font-size:1.2rem">Certificate Applications Search</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="ay" class="required">AY</label>
                        <select class="form-control select2" name="ay" id="ay">
                            <option value="">Select AY</option>
                            @foreach ($academic_years as $id => $entry)
                                <option value="{{ $entry }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="semester" class="required">Semester</label>
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
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="department" class="">Department</label>
                        <select class="form-control select2" name="department" id="department" onchange="check_dept(this)">
                            <option value="">Select Department</option>
                            @foreach ($departments as $id => $entry)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="course" class="">Course</label>
                        <select class="form-control select2" name="course" id="course">
                            <option value="">Select Course</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="from_date" class="">From Date</label>
                        <input type="text" class="form-control date" id="from_date" name="from_date">
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="form-group">
                        <label for="to_date" class="">To Date</label>
                        <input type="text" class="form-control date" id="to_date" name="to_date">
                    </div>
                </div>
            </div>
            <div style="text-align:right;">
                <button class="enroll_generate_bn" onclick="get_data(0,'main')">Submit</button>
            </div>
        </div>
    </div>
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <span class="nav-link nav-span" id="0" onclick="get_data(0,'dumm')">Pending</span>
                </li>
                <li class="nav-item">
                    <span class="nav-link nav-span" id="1" onclick="get_data(1,'dumm')">Waiting for
                        Principal
                        Sign</span>
                </li>
                <li class="nav-item">
                    <span class="nav-link nav-span" id="2" onclick="get_data(2,'dumm')">Approved and
                        Signed</span>
                </li>
                <li class="nav-item">
                    <span class="nav-link nav-span" id="3" onclick="get_data(3,'dumm')">Need
                        Revision</span>
                </li>
                <li class="nav-item">
                    <span class="nav-link nav-span" id="4" onclick="get_data(4,'dumm')">Rejected</span>
                </li>
            </ul>
            <table id="certificateProvision"
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-certificateProvision text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Student Name</th>
                        <th> Class </th>
                        <th>Applied Date</th>
                        <th>Certificate Type</th>
                        <th>Reason For Applying</th>
                        {{-- <th>Status</th> --}}
                        <th>Approved Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            // $(".nav-item").removeClass('activer');
            $("#0").addClass('active');
            let dtOverrideGlobals = {
                buttons: dtButtons,
                // processing: true,
                // serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: {
                    url: "{{ route('admin.certificate-provision.index') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.ay = '';
                        d.semester = '';
                        d.dept = '';
                        d.course = '';
                        d.from_date = '';
                        d.to_date = '';
                        d.status = 0;
                    },
                },
                columns: [{
                        data: 'sn',
                        name: 'sn'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'class',
                        name: 'class'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'certificate',
                        name: 'certificate'
                    },
                    {
                        data: 'purpose',
                        name: 'purpose'
                    },
                    // {
                    //     data: 'status',
                    //     name: 'status'
                    // },
                    {
                        data: 'approved_date',
                        name: 'approved_date'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-certificateProvision').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        };

        function check_dept(element) {
            let dept = element.value;
            let courses;
            if (dept == '') {
                Swal.fire('', 'Please Select the Department', 'warning');
            } else {
                $.ajax({
                    url: '{{ route('admin.student-attendance-summary.get_courses') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'department': dept
                    },
                    success: function(response) {
                        // console.log(response)
                        let courses = response.courses;

                        let courses_len = courses.length;


                        let got_courses = '<option value="">Select Course</option>';


                        if (courses_len > 0) {
                            for (let i = 0; i < courses_len; i++) {
                                got_courses +=
                                    `<option value="${courses[i].id}">${courses[i].short_form}</option>`;
                            }
                        }
                        $("#course").html(got_courses);
                        $("select").select2();

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
                })
            }

        }

        function get_data(element, second) {
            let state = false;
            if (second == 'main') {
                if ($("#ay").val() == '') {
                    Swal.fire('', 'Please Choose The AY', 'warning');
                    return false;
                } else if ($("#semester").val() == '') {
                    Swal.fire('', 'Please Choose The Semester', 'warning');
                    return false;
                } else {
                    state = true;
                }
            } else {
                state = true;
            }
            if (state == true) {
                if ($.fn.DataTable.isDataTable('#certificateProvision')) {
                    $('#certificateProvision').DataTable().destroy();
                }
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                $("span").removeClass('active');
                $("#" + element).addClass('active');
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    // processing: true,
                    // serverSide: true,
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.certificate-provision.index') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(d) {
                            d.ay = $("#ay").val();
                            d.semester = $("#semester").val();
                            d.dept = $("#department").val();
                            d.course = $("#course").val();
                            d.from_date = $("#from_date").val();
                            d.to_date = $("#to_date").val();
                            d.status = element;
                        },
                    },
                    columns: [{
                            data: 'sn',
                            name: 'sn'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'class',
                            name: 'class'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'certificate',
                            name: 'certificate'
                        },
                        {
                            data: 'purpose',
                            name: 'purpose'
                        },
                        // {
                        //     data: 'status',
                        //     name: 'status'
                        // },
                        {
                            data: 'approved_date',
                            name: 'approved_date'
                        },
                        {
                            data: 'actions',
                            name: '{{ trans('global.actions') }}'
                        }
                    ],
                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 10,
                };
                let table = $('.datatable-certificateProvision').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
            }
        }

        function certificateReady(id) {

            Swal.fire({
                title: "Are You Sure?",
                text: "Is The Certificate Ready ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.certificate-provision.update-action') }}',
                        type: 'POST',
                        data: {
                            'id': id,
                            'status': 2,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                Swal.fire(
                                    'Done!',
                                    'The Certificate Status Sent To The Student!',
                                    'success'
                                )

                            } else {
                                Swal.fire(
                                    '',
                                    response.data,
                                    'error'
                                )
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
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Certificate Not Ready",
                        "error"
                    )
                }
            });
        }
    </script>
@endsection
