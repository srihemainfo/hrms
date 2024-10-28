@php
    $role_id = auth()->user()->roles[0]->id;
    if ($role_id == 1 || $role_id == 5) {
        $key = 'layouts.admin';
    } else {
        $key = 'layouts.staffs';
    }
@endphp
@extends($key)
@section('content')
    @can('bestperformance_award_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Award
                </button>
            </div>
        </div>
    @endcan
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>

    <div class="card">
        <div class="card-header">
            Award List
        </div>
        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-BestPerformanceAward text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Staff Name
                        </th>
                        <th>
                            Year
                        </th>
                        <th>
                            Month
                        </th>
                        <th>
                            Amount
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

    <div class="card">
        <div class="card-header">
            Best Performance Award Details
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="month_report" class="required">Month</label>
                        <select name="month_report" class="form-control select2" id="month_report">
                            <option value="">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                        <span id="month_report_span" class="text-danger text-center" style="font-size:0.9rem;"></span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year_report" class="required">Year</label>
                        <select name="year_report" class="form-control select2" id="year_report">
                            <option value="">Select Year</option>
                            @for ($year = date('Y'); $year >= 2023; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <span id="year_report_span" class="text-danger text-center" style="font-size:0.9rem;"></span>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group" style="text-align:right;padding-top:2.2rem;">
                        <button class="enroll_generate_bn" onclick="get_data()">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="report" style="display:none;max-width:fix-content;overflow-x:auto;z-index:0;">
            <div class="card-header text-center text-primary" id="card_header">Best Performance Monthly Wise Report</div>
            <div class="card-body" id="card-body">
            </div>
        </div>

        <div class="modal fade" id="BestPerformanceAward" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Best Performance Award</h6>
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <input type="hidden" name="id" id="id" value="">
                                <label for="staff_name" class="required">Staff</label>
                                <select name="staff_name" class="form-control select2" id="staff_name">
                                    <option value="">Select Staff</option>
                                    @foreach ($staffs as $staff)
                                        <option value="{{ $staff->name }}">{{ $staff->name }}</option>
                                    @endforeach
                                </select>
                                <span id="staff_name_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="year" class="required">Year</label>
                                <select name="year" class="form-control select2" id="year">
                                    <option value="">Select Year</option>
                                    @for ($year = date('Y'); $year >= 2023; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <span id="year_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="month" class="required">Month</label>
                                <select name="month" class="form-control select2" id="month">
                                    <option value="">Select Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                                <span id="month_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 form-group">
                                <label for="amount" class="required">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control">
                                <span id="amount_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="save_div">
                            <button type="button" id="save_btn" class="btn btn-outline-success"
                                onclick="saveSection()">Save</button>
                        </div>
                        <div id="loading_div">
                            <span class="theLoader"></span>
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
                                        url: "{{ route('admin.bestperformance_award.massDestroy') }}",
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        Swal.fire('', response.data, response.status);
                                        $('.secondLoader').hide()
                                        callAjax()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)

                if ($.fn.DataTable.isDataTable('.datatable-BestPerformanceAward')) {
                    $('.datatable-BestPerformanceAward').DataTable().destroy();
                }
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    ajax: "{{ route('admin.bestperformance_award.index') }}",
                    columns: [{
                            data: 'placeholder',
                            name: 'placeholder'
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'staff_name',
                            name: 'staff_name'
                        },
                        {
                            data: 'year',
                            name: 'year'
                        },
                        {
                            data: 'month',
                            name: 'month'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
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
                let table = $('.datatable-BestPerformanceAward').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });

            };

            function openModal() {
                $("#id").val('');
                $("#staff_name").val('').select2();
                $("#staff_name_span").hide();
                $("#year").val('').select2();
                $("#year_span").hide();
                $("#month").val('').select2();
                $("#month_span").hide();
                $("#amount").val('');
                $("#amount_span").hide();
                $("#loading_div").hide();
                $("#save_btn").html(`Save`);
                $("#save_div").show();
                $("#BestPerformanceAward").modal();
            }

            function saveSection() {
                $("#loading_div").hide();
                if ($("#staff_name").val() == '') {
                    $("#staff_name_span").html(`Please Select Staff Name`);
                    $("#staff_name_span").show();
                    $("#month_span").hide();
                    $("#year_span").hide();
                    $("#amount").val('');

                } else if ($("#year").val() == '') {
                    $("#year_span").html(`Please Select Year`);
                    $("#staff_name_span").hide();
                    $("#month_span").hide();
                    $("#year_span").show();
                    $("#amount_span").hide();

                } else if ($("#month").val() == '') {
                    $("#month_span").html(`Please Select Month`);
                    $("#staff_name_span").hide();
                    $("#month_span").show();
                    $("#year_span").hide();
                    $("#amount_span").hide();

                } else if ($("#amount").val() == '') {
                    $("#amount_span").html(`Please Enter Amount`);
                    $("#staff_name_span").hide();
                    $("#month_span").hide();
                    $("#year_span").hide();
                    $("#amount_span").show();

                } else {
                    $("#save_div").hide();
                    $("#state_span").hide();
                    $("#loading_div").show();
                    let id = $("#id").val();
                    let staff_name = $("#staff_name").val();
                    let year = $("#year").val();
                    let month = $("#month").val();
                    let amount = $("#amount").val();
                    $.ajax({
                        url: "{{ route('admin.bestperformance_award.store') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'id': id,
                            'staff_name': staff_name,
                            'year': year,
                            'month': month,
                            'amount': amount
                        },
                        success: function(response) {
                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', response.data, 'success');
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                            $("#BestPerformanceAward").modal('hide');
                            callAjax();
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

            function deletebestperformance_award(id) {
                if (id == undefined) {
                    Swal.fire('', 'ID Not Found', 'warning');
                } else {
                    Swal.fire({
                        title: "Are You Sure?",
                        text: "Do You Really Want To Delete!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.value) {
                            $('.secondLoader').show(); // Show loader only if confirmed
                            $.ajax({
                                url: "{{ route('admin.bestperformance_award.delete') }}",
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'id': id
                                },
                                success: function(response) {
                                    Swal.fire('', response.data, response.status);
                                    $('.secondLoader').hide(); // Hide loader on success
                                    callAjax();
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $('.secondLoader').hide(); // Hide loader on error
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
                        }
                    });
                }
            }


            function viewbestperformance_award(id) {
                if (id == undefined) {
                    Swal.fire('', 'ID Not Found', 'warning');
                } else {
                    $('.secondLoader').show()

                    $.ajax({
                        url: "{{ route('admin.bestperformance_award.view') }}",
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
                                $("#staff_name").val(data.staff_name).select2();
                                $("#year").val(data.year).select2();
                                $("#month").val(data.month).select2();
                                $("#amount").val(data.amount)
                                $("#save_div").hide();
                                $("#loading_div").hide();
                                $("#BestPerformanceAward").modal();
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

            function editbestperformance_award(id) {
                if (id == undefined) {
                    Swal.fire('', 'ID Not Found', 'warning');
                } else {
                    $('.secondLoader').show()
                    $.ajax({
                        url: "{{ route('admin.bestperformance_award.edit') }}",
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
                                $("#staff_name").val(data.staff_name).select2();
                                $("#year").val(data.year).select2();
                                $("#month").val(data.month).select2();
                                $("#id").val(data.id);
                                $("#amount").val(data.amount)
                                $("#save_div").show();
                                $("#loading_div").hide();
                                $("#BestPerformanceAward").modal();
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

            function get_data() {
                let month_report = $("#month_report").val();
                let year_report = $("#year_report").val();

                if (month_report == '') {
                    $("#month_report_span").text("Please Choose Month");
                    return false;
                } else {
                    $("#month_report_span").text("");
                }

                if (year_report == '') {
                    $("#year_report_span").text("Please Choose Year");
                    return false;
                } else {
                    $("#year_report_span").text("");
                }
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.bestperformance_award.get-data') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'year_report': year_report,
                        'month_report': month_report
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;
                            console.log(data)

                            if (data.length > 0) {
                                let staffTable = `
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Staff Name</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>`;

                                data.forEach((item, index) => {
                                    staffTable +=
                                        `<tr><td>${index + 1}</td>
                                        <td>${item.staff_name}</td>
                                        <td>${item.month}</td>
                                        <td>${item.year}</td>
                                        <td>${item.amount}</td>

                                        </tr>`;
                                });

                                staffTable += `</tbody></table>`;


                                $('#report').show();
                                $('#card_header').show(); // If you want the header to display too
                                $('#card-body').html(staffTable);
                            } else {
                                // Hide the card if there's no data
                                $('#report').hide();
                                $('#card_header').hide();
                            }



                        } else {
                            Swal.fire('', response.data, 'error');
                            $('#report').hide();
                            $('#card_header').hide();

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
        </script>
    @endsection
