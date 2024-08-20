@extends('layouts.admin')
@section('content')
    @can('scholarship_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Assign Scholarship
                </button>
            </div>
        </div>
    @endcan
    <style>
        .select2-container {
            width: 100% !important;
        }

        #loading {
            z-index: 99999;
        }
    </style>

    <div class="card">
        <div class="card-header">
            ScholarStudents List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Scholarship text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Register Number
                        </th>
                        <th>
                            Student Name
                        </th>
                        <th>
                            Foundation Name
                        </th>
                        <th>
                            Amount or Percentage
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="secondLoader"></div>
    </div>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="modal fade" id="scholarshipModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Scholarship</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <input type="hidden" name="feescholarship_id" id="feescholarship_id">

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group" id="filteration_div">
                            <label for="filteration" class="required">Select Type</label>
                            <select name="filteration" id="filteration" class="form-control select2"
                                style="font-size: 18px;" onchange="test()">
                                <option value="">Select Type</option>
                                <option value="for_all">For All Students</option>
                                <option value="department_wise">Filter Based Enrollment Wise</option>
                            </select>
                            <span id="filteration_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="scholarship_div">
                            <label for="schoalrship" class="required">Select Scholarship</label>
                            <select name="schoalrship" id="scholarship" class="form-control select2"
                                style="font-size: 18px;" onchange="getscholarship()">
                                <option value="">Select Scholarship</option>
                                @foreach ($getScholarship as $id => $scholarship)
                                    <option value="{{ $id }}">{{ $scholarship }}</option>
                                @endforeach
                            </select>
                            <span id="schoalrship_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group" id="amt_per_edit_box"
                            style="display: none;">
                            <label for="amt_per_edit" class="required">Scholarship Details</label>
                            <input type="text" id="amt_per_edit" class="form-control">
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group" id="amt_percentage_box"
                            style="display: none;">
                            <label for="amt_percentage" class="required">Scholarship Details</label>
                            <input type="text" id="amt_percentage" class="form-control" readonly>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="enroll_div">
                            <label for="enroll_id" class="required">Select Enroll Master</label>
                            <select class="form-control select2" id="enroll_id" name="enroll_id"
                                onchange="filter_students()">
                                <option value="">Select Enroll Master</option>
                                @foreach ($CourseEnrollMaster as $id => $b)
                                    <option value="{{ $id }}">{{ $b }}</option>
                                @endforeach
                            </select>
                            <span id="enroll_id_span" class="text-danger text-center"
                                style="display:none; font-size:0.9rem;"></span>
                        </div>





                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="batch_filter_std_div">
                            <label for="feeBatch" class="required">Select Students</label>
                            <select class="form-control select2" id="batch_filter_std" name="batch_filter_std" multiple>
                                <option value="">Select Students</option>

                            </select>
                            <span id="batch_filter_std_span" class="text-danger text-center"
                                style="display:none; font-size:0.9rem;"></span>
                        </div>




                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="student_div">
                            <label for="started_batch" class="required">Select Student</label>
                            <select name="student" id="student" class="form-control select2" style="font-size: 18px;"
                                multiple>
                                <option value="">Select Student</option>
                                @foreach ($getStudents as $student)
                                    <option value="{{ $student->register_no }}">{{ $student->name }}
                                        ({{ $student->register_no }})
                                    </option>
                                @endforeach
                            </select>

                            <span id="student_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>




                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save_btn" class="btn btn-outline-success"
                        onclick="saveScholarship()">Save</button>
                    <div id="loading_div" style="display:none;">
                        <span class="theLoader"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="scholarshipModalviewedit" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row gutters">
                        <input type="hidden" name="feescholarship_id" id="feescholarship_id">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                            <label for="reg_number">Register Number</label>
                            <input type="text" id="reg_number" class="form-control" readonly>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                            <label for="scholarship_details">Scholarship Details</label>
                            <input type="text" id="scholarship_details" class="form-control" readonly>
                            <span id="scholarship_details_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="foundation_name">Foundation Name</label>
                            <input type="text" id="foundation_name" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveScholarship()">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);
            @can('scholarship_delete')
                let deleteButton = {
                    text: 'Delete Selected',
                    className: 'btn-outline-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            Swal.fire('', 'No Rows Selected', 'warning');

                            return
                        }

                        Swal.fire({
                            title: "Are You Sure?",
                            text: "Do You Really Want To Delete !",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            reverseButtons: true
                        }).then(function(result) {
                            if (result.value) {
                                $('.secondLoader').show()

                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: "{{ route('admin.fee-scholarship.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        $('.secondLoader').hide()

                                        Swal.fire('', response.data, response.status);
                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            if ($.fn.DataTable.isDataTable('.datatable-Scholarship')) {
                $('.datatable-Scholarship').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.feeScholarship.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'stu_reg_no',
                        name: 'stu_reg_no'
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'scholar_id',
                        name: 'scholar_id'
                    },
                    {
                        data: 'scholar_details',
                        name: 'scholar_details'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-Scholarship').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {

            $("#batch_filter_std_div").hide();

            $("#save_btn").hide();
            $("#scholarship_div").hide();
            $("#student_div").hide();
            $("#enroll_div").hide();


            $("#filteration").val('').select2();
            $("#enroll_id").val('').select2();

            $("select").prop('disabled', false).select2();

            $("#scholarshipModal").modal();
            $("#feescholarship_id").val('')
            $("#scholarship").val('').select2();
            // $("#student").val('').select2();
            var $studentDropdown = $("#student");
            if ($studentDropdown.val().length) {
                $studentDropdown.val([]).trigger('change');
            }
            $("#amt_percentage_box").hide();
            $("#amt_per_edit_box").hide();



        }


        function getscholarship() {

            $('#loading').show();

            var scholarship = $("#scholarship").val();

            $.ajax({
                url: '{{ route('admin.fee-scholarship.getter') }}',
                type: 'POST',
                data: {
                    'scholarship': scholarship
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    let status = response.status;
                    if (status) {
                        $("#amt_percentage_box").show();
                        $("#amt_percentage").val(response.value);

                    } else {
                        Swal.fire('', response.data, 'error');
                        $("#amt_percentage_box").hide();
                    }

                    $('#loading').hide();


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = textStatus || errorThrown || 'Request Failed';
                    Swal.fire('', errorMessage, 'error');

                    $('#loading').hide();
                    $("#amt_percentage_box").hide();

                }
            });
        }

        function test() {

            var filteration = $("#filteration").val();

            if (filteration == 'for_all') {

                $("#save_btn").show();
                $("#scholarship_div").show();
                $("#student_div").show();
                $("#enroll_div").hide();
                $("#batch_filter_std_div").hide();


            } else if (filteration == 'department_wise') {

                $("#enroll_div").show();
                $("#save_btn").show();
                $("#scholarship_div").show();
                $("#student_div").hide();
                $("#batch_filter_std_div").show();





            } else {
                $("#save_btn").hide();
                $("#scholarship_div").hide();
                $("#student_div").hide();
                $("#enroll_div").hide();
                $("#batch_filter_std_div").hide();


            }

        }

        function filter_students() {
            var enroll_id = $("#enroll_id").val();
            // alert(batch_name);
            $('#loading').show();

            $.ajax({

                url: '{{ route('admin.fee-scholarship.filter_student') }}',
                type: 'POST',
                data: {
                    'enroll_id': enroll_id,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    let status = response.status;
                    if (status) {
                        if (status == true) {
                            console.log(response);
                            let students = response.data;
                            $('#batch_filter_std').empty();
                            $('#batch_filter_std').append('<option value="">Select Students</option>');

                            $.each(students, function(register_number, name) {
                                $('#batch_filter_std').append(
                                    `<option value="${register_number}">${name} (${register_number})</option>`
                                );
                            });

                            $('#batch_filter_std').trigger('change.select2');

                        } else {
                            Swal.fire('', response.data, 'error');
                        }

                    } else {
                        Swal.fire('', response.data, 'error');

                    }
                    $('#loading').hide();


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = textStatus || errorThrown || 'Request Failed';
                    Swal.fire('', errorMessage, 'error');

                    $('#loading').hide();


                }

            })
        }

        function saveScholarship() {



            $("#loading_div").hide();
            $("#schoalrship_span").hide();
            $("#student_span").hide();

            if ($("#scholarship").val() == '') {
                $("#schoalrship_span").html('Scholarship Is Required');
                $("#schoalrship_span").show();
                $("#student_span").hide();
            } else if ($("#sutdent").val() == '') {
                $("#student_span").html('Plese Select Student');
                $("#schoalrship_span").hide();
                $("#student_span").show();
            } else {

                var scholarship = $("#scholarship").val();
                var student = $("#student").val();
                var amt_percentage = $("#amt_percentage").val();
                var id = $("#feescholarship_id").val();
                var amt_per_edit = $("#amt_per_edit").val();
                var batch_filter_std = $("#batch_filter_std").val();

                $("#loading_div").show();

                $.ajax({
                    url: '{{ route('admin.fee-scholarship.store') }}',
                    type: 'POST',
                    data: {
                        'id': id,
                        'scholarship': scholarship,
                        'amt_percentage': amt_percentage,
                        'student': student,
                        'amt_per_edit': amt_per_edit,
                        'batch_filter_std': batch_filter_std

                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        let status = response.status;
                        if (status) {
                            if (status == true) {
                                Swal.fire('', response.data, 'success');
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                            $("#scholarshipModal").modal('hide');
                            $("#loading_div").hide();

                        } else {
                            Swal.fire('', response.data, 'error');

                        }
                        $("#loading_div").hide();
                        callAjax();


                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        let errorMessage = textStatus || errorThrown || 'Request Failed';
                        Swal.fire('', errorMessage, 'error');

                        $("#loading_div").hide();

                    }
                });




            }

        }

        function viewfeeScholarship(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.fee-scholarship.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            var foundation_name = response.foundation_name;
                            // console.log(foundation_name);
                            // console.log(data);

                            $("#feescholarship_id").val(data.id);
                            $("#scholarshipModalviewedit").modal();
                            $("#reg_number").val(data.stu_reg_no);
                            $("#scholarship_details").val(data.scholar_details);
                            $("#foundation_name").val(foundation_name.foundation_name);
                            $("#save_div").hide();

                        } else {
                            Swal.fire('', response.data, 'error');
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }



        function editfeeScholarship(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.fee-scholarship.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            console.log(data);
                            var foundation_name = response.foundation_name;

                            $("#scholarshipModal").modal();
                            $("#amt_percentage_box").hide();
                            $("#amt_per_edit_box").show();
                            $("#amt_per_edit").val(data.scholar_details);

                            var names = $("#amt_per_edit").val();
                            var lastChar = names.trim().slice(-1);

                            // Remove previous event handlers to avoid multiple attachments
                            $("#amt_per_edit").off('input');

                            // Check if the last character is '%'
                            if (lastChar === '%') {
                                // alert("The value ends with a '%' symbol.");

                                // Add event handler for input
                                $("#amt_per_edit").on('input', function() {
                                    var value = $(this).val();
                                    value = value.replace('%', '');
                                    var number = parseFloat(value);
                                    if (isNaN(number)) {
                                        number = 0;
                                    } else if (number > 100) {
                                        number = 100;
                                    } else if (number < 0) {
                                        number = 0;
                                    }
                                    $(this).val(number + '%');
                                });

                            } else {
                                // alert('none');

                                // Add event handler for input
                                $("#amt_per_edit").on('input', function() {
                                    var value = $(this).val();
                                    var number = parseFloat(value);
                                    if (isNaN(number)) {
                                        number = '';
                                    } else if (number < 0) {
                                        number = 0;
                                    }
                                    $(this).val(number);
                                });
                            }

                            $("#student").val(data.stu_reg_no).select2();
                            $("#scholarship").val(data.scholar_id).select2();
                            $("select").prop('disabled', true).select2()
                            $("#feescholarship_id").val(data.id);

                            // callAjax();



                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('.secondLoader').hide()

                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
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
                })
            }
        }

        function deletefeeScholarship(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You Really Want To Delete !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $('.secondLoader').show()
                        $.ajax({
                            url: "{{ route('admin.fee-scholarship.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $('.secondLoader').hide()
                                Swal.fire('', response.data, response.status);
                                callAjax();
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
                        })
                    }
                })
            }
        }
    </script>
@endsection
