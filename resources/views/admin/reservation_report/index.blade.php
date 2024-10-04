@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 5) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Reservation Report
        </div>
        <div class="card-body">
            <div class="row gutters">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                    <label for="from_date" class="required">From Date</label>
                    <input type="text" id="from_date" name="from_date" class="form-control date">
                    <span id="from_date_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                    <label for="to_date" class="required">To Date</label>
                    <input type="text" id="to_date" name="to_date" class="form-control date">
                    <span id="to_date_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div id="save_div">
                    <button type="button" id="save_btn" class="enroll_generate_bn" onclick="filterReservation()"
                        style="margin-top: 31px;">Save</button>
                </div>
                <div id="loading_div" style="display: none; margin-top: 31px;">
                    <span class="theLoader"></span>
                </div>
            </div>
        </div>
        <div class="secondLoader"></div>
    </div>
    <div class="card">
        <div class="card-header">
            Reservation Report
        </div>
        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ReservationReport text-center">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            ID
                        </th>
                        <th>
                            Member Name
                        </th>
                        <th>
                            Role
                        </th>
                        <th>
                            Book Name
                        </th>
                        <th>
                            Reservation Date
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
        <div class="secondLoader"></div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            $('#loading_div').hide();
            callAjax();
        });

        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(2, 2);
            dtButtons.splice(3, 3);

            if ($.fn.DataTable.isDataTable('.datatable-ReservationReport')) {
                $('.datatable-ReservationReport').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.reserve-report.reserveReport') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'sno',
                        name: 'sno'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'book_name',
                        name: 'book_name'
                    },
                    {
                        data: 'reserve_date',
                        name: 'reserve_date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-ReservationReport').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        };

        function filterReservation() {
            if ($('#from_date').val() == '') {
                $("#from_date_span").html(`From Date Is Required.`);
                $("#from_date_span").show();
                $("#to_date_span").hide();
            } else if ($('#to_date').val() == '') {
                $("#to_date_span").html(`To Date Is Required.`);
                $("#to_date_span").show();
                $("#from_date_span").hide();
            } else {
                $('#save_div').hide();
                $('#loading_div').show();
                $("#to_date_span").hide();
                $("#from_date_span").hide();
                $.ajax({
                    url: "{{ route('admin.reserve-report.search') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'from_date': $('#from_date').val(),
                        'to_date': $('#to_date').val()
                    },
                    success: function(response) {
                        let data = response.data
                        let status = response.status

                        if (status == true) {
                            let table = $('.datatable-ReservationReport').DataTable();
                            table.destroy();
                            let body = $('#tbody').empty()
                            let i = 0;
                            $.each(data, function(index, value) {
                                let row = $('<tr>')
                                row.append(`<td></td>`)
                                row.append(`<td>${i+=1}</td>`)
                                row.append(`<td>${value.name}</td>`)
                                row.append(`<td>${value.title}</td>`)
                                row.append(`<td>${value.book_name}</td>`)
                                row.append(`<td>${value.reserve_date}</td>`)
                                body.append(row)
                            })

                            table = $('.datatable-ReservationReport').DataTable();

                        } else {
                            Swal.fire('', data, 'error');
                        }

                        $('#save_div').show()
                        $('#loading_div').hide()
                    }
                })
            }
        }
    </script>
@endsection
