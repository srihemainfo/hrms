@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <div class="text-center">Fee Defaulters Report</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="course">Course</label>
                        <select class="form-control select2" name="course" id="course">
                            <option value="">Select Course</option>
                            @foreach ($courses as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="ay" class="required">AY</label>
                        <select class="form-control select2" name="ay" id="ay">
                            <option value="">Select Academic Year</option>
                            @foreach ($ays as $id => $data)
                                <option value="{{ $id }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="year">Year</label>
                        <select class="form-control select2" name="year" id="year">
                            <option value="">Select Year</option>
                            <option value="1">First Year</option>
                            <option value="2">Second Year</option>
                            <option value="3">Third Year</option>
                            <option value="4">Forth Year</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3 col-12" style="text-align:center;">
                    <div class="form-group" style="padding-top: 32px;">
                        <button type="button" style="width:100%;" class="enroll_generate_bn"
                            onclick="submit()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="defaultersReport" style="display:none;">
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-defaultersRep text-center"
                style="width:100%;">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Student Name</th>
                        {{-- <th>Total Fee Amount</th>
                        <th>Scholarship</th>
                        <th>GQG</th>
                        <th>FG</th>
                        <th>Total Fee Paid</th> --}}
                        <th>Total Balance</th>
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
        function submit() {
            // if ($("#course").val() == '') {
            //     Swal.fire('', 'Please Select The Course', 'warning');
            //     $("#defaultersReport").hide();
            //     return false;
            // } else
             if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select The AY', 'warning');
                $("#defaultersReport").hide();
                return false;
            } else {
                $("#defaultersReport").show();
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
                dtButtons.splice(0, 2);
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    orderCellsTop: true,
                    order: [
                        [1, 'asc']
                    ],
                    pageLength: 25,
                    columnDefs: [{
                        targets: 0,
                        orderable: true
                    }]
                };
                $("#tbody").html('<tr><td colspan="3">Loading...</td></tr>')
                $.ajax({
                    url: '{{ route('admin.fee-defaulters-report.get-data') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': $("#course").val(),
                        'ay': $("#ay").val(),
                        'year': $("#year").val()
                    },
                    success: function(response) {
                        if ($.fn.DataTable.isDataTable('.datatable-defaultersRep')) {
                            $('.datatable-defaultersRep').DataTable().destroy();
                        }

                        if (response.status) {
                            let data = response.data;
                            if (data.length > 0) {
                                let rows = $("#tbody");
                                rows.empty()
                                $.each(data, function(index, value) {
                                    rows.append(
                                        `<tr><td>${index+1}</td><td>${value.name}</td><td>${value.balance_fee}</td></tr>`
                                    )
                                })
                            } else {
                                $("#tbody").html('<tr><td></td><td></td><td></td></tr>')
                            }

                        } else {
                            $("#tbody").html('<tr><td></td><td></td><td></td></tr>')
                            Swal.fire('', response.data, 'error');
                        }

                        let table = $('.datatable-defaultersRep').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if ($.fn.DataTable.isDataTable('.datatable-defaultersRep')) {
                            $('.datatable-defaultersRep').DataTable().destroy();
                        }
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
                        $("#tbody").html('<tr><td></td><td></td><td></td></tr>')
                        let table = $('.datatable-defaultersRep').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                    }

                })
            }
        }
    </script>
@endsection
