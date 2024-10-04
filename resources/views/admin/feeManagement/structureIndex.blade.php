@extends('layouts.admin')
@section('content')
    <style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    @can('fee_structure_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-2">
                <a class="btn btn-success" href="{{ route('admin.fee-structure.create') }}">
                    Create Fee Structure
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="batch">Batch</label>
                    <select class="form-control select2" name="batch" id="batch">
                        <option value="">Select Batch</option>
                        @foreach ($batch as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="department">Department</label>
                    <select class="form-control select2" name="department" id="department">
                        <option value="">Select Department</option>
                        @foreach ($department as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="course">Course</label>
                    <select class="form-control select2" name="course" id="course">
                        <option value="">Select Course</option>
                         @foreach ($course as $id => $entry)
                            <option value="{{ $id }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 text-right">
                    <div>
                        <button type="button" class="enroll_generate_bn bg-primary" style="margin-top:30px;"
                            onclick="Search()">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Created Fee List
        </div>

        <div class="card-body">
            <table id="feeTable"
                class="table table-bordered table-striped table-hover datatable datatable-fee-list text-center">
                <thead>
                    <tr>
                        <th>
                            S.No
                        </th>
                        <th>
                            Batch
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Year
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Total Fee (MQ) Without Hostel
                        </th>
                        <th>
                            Total Fee (MQ) With Hostel
                        </th>
                        <th>
                            Total Fee (GQ) Without Hostel
                        </th>
                        <th>
                            Total Fee (GQ) With Hostel
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
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            Index();
        }

        function Index() {

            $("#tbody").html(`<tr><td colspan="10"> Loading...</td></tr>`);

            $.ajax({
                url: '{{ route('admin.fee-structure.index') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'data': true
                },
                success: function(response) {

                    let data = response.data;
                    let data_len = data.length;
                    let year;
                    let rows = '';
                    if (data_len > 0) {
                        for (let i = 0; i < data_len; i++) {
                            if (data[i].year == '1') {
                                year = 'First Year';
                            } else if (data[i].year == '2') {
                                year = 'Second Year';
                            } else if (data[i].year == '3') {
                                year = 'Third Year';
                            } else if (data[i].year == '4') {
                                year = 'Final Year';
                            } else {
                                year = '';
                            }
                            rows += `<tr>
                                  <td>${i + 1}</td>
                                  <td>${data[i].batch.name}</td>
                                  <td>${data[i].department.name}</td>
                                  <td>${year}</td>
                                  <td>${data[i].name}</td>
                                  <td>${data[i].mq_total_amt}</td>
                                  <td>${data[i].mqh_total_amt}</td>
                                  <td>${data[i].gq_total_amt}</td>
                                  <td>${data[i].gqh_total_amt}</td>
                                  <td>
                                    <a class="btn btn-xs btn-primary" href="{{ url('admin/fee-structure/show/${data[i].id}') }}" target="_blank">
                                      View
                                    </a>

                                    <a class="btn btn-xs btn-info" href="{{ url('admin/fee-structure/edit/${data[i].id}') }}" target="_blank">
                                        Edit
                                    </a>
                                    <a class="btn btn-xs btn-danger" style="color:white;" onclick="Delete(${data[i].id})">
                                      Delete
                                    </a>
                                  </td>
                                </tr>`;
                        }
                    } else {
                        rows += `<tr><td colspan="10"> No Data Available...</td></tr>`;
                    }
                    $("#tbody").html(rows);
                    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                    let dtOverrideGlobals = {
                        buttons: dtButtons,
                        retrieve: true,
                        aaSorting: [],
                        orderCellsTop: true,
                        order: [
                            [1, 'desc']
                        ],
                        pageLength: 10,
                    };
                    let table = $('.datatable-fee-list').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable()
                            .columns.adjust();
                    });

                }
            })

        }

        function Search() {
            if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select The Batch', 'warning');
                return false;
            } else if ($("#department").val() == '') {
                Swal.fire('', 'Please Select The Department', 'warning');
                return false;
            } else {

                let batch = $("#batch").val();
                let dept = $("#department").val();
                // let course = $("#course").val();
                if ($.fn.DataTable.isDataTable('#feeTable')) {
                    $('#feeTable').DataTable().destroy();
                }
                $("#tbody").html(`<tr><td colspan="11"> Loading...</td></tr>`);
                $.ajax({
                    url: '{{ route('admin.fee-structure.search') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'batch': batch,
                        'dept': dept,
                        // 'course': course,
                    },
                    success: function(response) {

                        let data = response.data;
                        let data_len = data.length;
                        let rows = '';

                        if (data_len > 0) {
                            for (let i = 0; i < data_len; i++) {

                                if (data[i].year == '1') {
                                    year = 'First Year';
                                } else if (data[i].year == '2') {
                                    year = 'Second Year';
                                } else if (data[i].year == '3') {
                                    year = 'Third Year';
                                } else if (data[i].year == '4') {
                                    year = 'Final Year';
                                } else {
                                    year = '';
                                }
                                rows += `<tr>
                                  <td>${i + 1}</td>
                                  <td>${data[i].batch.name}</td>
                                  <td>${data[i].department.name}</td>
                                  <td>${year}</td>
                                  <td>${data[i].name}</td>
                                  <td>${data[i].mq_total_amt}</td>
                                  <td>${data[i].mqh_total_amt}</td>
                                  <td>${data[i].gq_total_amt}</td>
                                  <td>${data[i].gqh_total_amt}</td>
                                  <td>
                                    <a class="btn btn-xs btn-primary" href="{{ url('admin/fee-structure/show/${data[i].id}') }}" target="_blank">
                                      View
                                    </a>

                                    <a class="btn btn-xs btn-info" href="{{ url('admin/fee-structure/edit/${data[i].id}') }}" target="_blank">
                                      Edit
                                    </a>
                                    <a class="btn btn-xs btn-danger" style="color:white;" onclick="Delete(${data[i].id})">
                                      Delete
                                    </a>
                                  </td>
                                </tr>`;
                            }
                        } else {
                            rows += '';
                        }
                        $("#tbody").html(rows);
                        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            retrieve: true,
                            aaSorting: [],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 10,
                        };
                        // let table = $('.datatable-fee-list').DataTable(dtOverrideGlobals);
                        // table.destroy();
                        let table = $('.datatable-fee-list').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                    }
                })
            }
        }

        function Delete(id) {
            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Want To Delete The Fee Structure ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {

                    $.ajax({
                        url: '{{ route('admin.fee-structure.delete') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'id': id
                        },
                        success: function(response) {
                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', 'Fee Structure Deleted Successfully!', 'success');
                            } else {
                                Swal.fire('', 'Fee Structure Delete Process Failed', 'error');
                            }
                            location.reload();
                        }
                    })
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Fee Structure Delete Cancelled",
                        "error"
                    )
                }
            });
        }
    </script>
@endsection
