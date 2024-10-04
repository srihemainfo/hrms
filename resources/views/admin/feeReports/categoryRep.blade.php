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
            <div class="text-center">Admission Mode Based Report</div>
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
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="admitted_mode">Admitted Mode</label>
                        <select class="form-control select2" name="admitted_mode" id="admitted_mode">
                            <option value="">Select Admitted Mode</option>
                            <option value="GENERAL QUOTA">GENERAL QUOTA</option>
                            <option value="MANAGEMENT QUOTA">MANAGEMENT QUOTA</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12 text-center">
                    <div class="form-group">
                        <label for="scholarship">Scholarship</label>
                        <div><input type="checkbox" id="scholarship" style="width:18px;height:18px;"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12 text-center">
                    <div class="form-group">
                        <label for="fg">FG</label>
                        <div><input type="checkbox" id="fg" style="width:18px;height:18px;"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12 text-center">
                    <div class="form-group">
                        <label for="gqg">GQG</label>
                        <div><input type="checkbox" id="gqg" style="width:18px;height:18px;"></div>
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

    <div class="card" id="admissionReport" style="display:none;">
        <div class="card-body">
            <table
                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-admissionRep text-center"
                style="width:100%;">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Student Name</th>
                        <th>Tuition Fee</th>
                        <th>Other Fee</th>
                        <th>Hostel Fee</th>
                        <th>Total Fee</th>
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
            if ($("#ay").val() == '') {
                Swal.fire('', 'Please Select The AY', 'warning');
                $("#admissionReport").hide();
                return false;
            } else {
                $("#admissionReport").show();
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
                var scholarship = '';
                var fg = '';
                var gqg = '';

                if ($("#scholarship").prop('checked')) {
                    scholarship = '1';
                }
                if ($("#fg").prop('checked')) {
                    fg = '1';
                }
                if ($("#gqg").prop('checked')) {
                    gqg = '1';
                }
                $("#tbody").html('<tr><td colspan="6">Loading...</td></tr>')
                $.ajax({
                    url: '{{ route('admin.fee-category-report.get-data') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course': $("#course").val(),
                        'ay': $("#ay").val(),
                        'year': $("#year").val(),
                        'admitted_mode': $("#admitted_mode").val(),
                        'scholarship': scholarship,
                        'fg': fg,
                        'gqg': gqg
                    },
                    success: function(response) {
                        if ($.fn.DataTable.isDataTable('.datatable-admissionRep')) {
                            $('.datatable-admissionRep').DataTable().destroy();
                        }

                        if (response.status) {
                            let data = response.data;
                            if (data.length > 0) {
                                let rows = $("#tbody");
                                rows.empty()
                                $.each(data, function(index, value) {
                                    rows.append(
                                        `<tr><td>${index+1}</td><td>${value.name}</td><td>${value.tuition_fee}</td><td>${value.other_fee}</td><td>${value.hostel_fee}</td><td>${value.total_fee}</td></tr>`
                                    )
                                })
                            } else {
                                $("#tbody").html(
                                    '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>')
                            }

                        } else {
                            $("#tbody").html('<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>')
                            Swal.fire('', response.data, 'error');
                        }

                        let table = $('.datatable-admissionRep').DataTable(dtOverrideGlobals);
                        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if ($.fn.DataTable.isDataTable('.datatable-admissionRep')) {
                            $('.datatable-admissionRep').DataTable().destroy();
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
                        $("#tbody").html('<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>')
                        let table = $('.datatable-admissionRep').DataTable(dtOverrideGlobals);
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
