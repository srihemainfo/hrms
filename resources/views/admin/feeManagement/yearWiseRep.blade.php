@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .nav-link:hover {
            cursor: pointer;
        }

        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>

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
                            @if ($id != 9 && $id != 10 && $id != 5)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="year">Year</label>
                    <select class="form-control select2" name="year" id="year">
                        <option value="">Select Year</option>
                        <option value="1">First Year</option>
                        <option value="2">Second Year</option>
                        <option value="3">Third Year</option>
                        <option value="4">Final Year</option>
                    </select>
                </div>

                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 text-right">
                    <div>
                        <button type="button" class="enroll_generate_bn bg-primary" style="margin-top:30px;"
                            onclick="getData('PAID',this)">Go</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="stulistCard" style="display:none;">
        <div class="card-body">
            {{-- <div class="row"> --}}
            {{-- <div class="col-md-6 col-sm-12"> --}}
            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <span class="text-primary nav-link " id="PAID" onclick="getData('PAID')">PAID</span>
                </li>
                <li class="nav-item">
                    <span class="text-primary nav-link" id="HALF" onclick="getData('HALF')">HALF PAID</span>
                </li>
                {{-- <li class="nav-item">
                    <span class="text-primary nav-link" id="UNPAID" onclick="getData('UNPAID')">UNPAID</span>
                </li> --}}
            </ul>
            <table class="table table-bordered table-striped table-hover datatable datatable-stulist text-center"
                id="stulist">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Student Name</th>
                        <th>Date</th>
                        <th>Total Fee</th>
                        <th>Total Paid Fee</th>
                        <th>Total Paying Fee</th>
                        <th>Total Balance Fee</th>
                        <th>Payment Mode</th>
                        <th>Details</th>
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


        function getData(element) {
            if ($("#batch").val() == '') {
                Swal.fire('', 'Please Select The Batch', 'warning');
                return false;
            } else if ($("#department").val() == '') {
                Swal.fire('', 'Please Select The Department', 'warning');
                return false;
            } else if ($("#year").val() == '') {
                Swal.fire('', 'Please Select The Year', 'warning');
                return false;
            } else {
                if ($.fn.DataTable.isDataTable('#stulist')) {
                    $('#stulist').DataTable().destroy();
                }
                $("#tbody").html(`<tr class="text-center text-primary"><td colspan="9"> loading...</td></tr>`);
                let current_status;
                if (element == 'HALF') {
                    current_status = 'HALF PAID';
                } else {
                    current_status = element;
                }
                const buttons = document.querySelectorAll('span');
                buttons.forEach(function(button) {
                    button.classList.remove('active');
                    button.classList.remove('text-secondary');
                });

                $("#" + element).addClass('active');
                $("#" + element).addClass('text-secondary');
                let batch = $("#batch").val();
                let year = $("#year").val();
                let dept = $("#department").val();


                $("#stulistCard").show();
                $.ajax({
                    url: '{{ route('admin.fee.year-wise-report.data') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'batch': batch,
                        'dept': dept,
                        'year': year,
                        'status': current_status,
                    },
                    success: function(response) {
                        console.log(response)
                        let status = response.status;
                        let data = response.data;
                        let data_len = data.length;
                        let stu_name;
                        let rows = ``;
                        if (status == true) {

                                for (let i = 0; i < data_len; i++) {

                                    if (data[i].student != null) {
                                        stu_name = data[i].student.name + ' ( ' + data[i].student.register_no +
                                            ' )';
                                    } else {
                                        stu_name = '';
                                    }

                                    var given_date = data[i].date;
                                    var parts = given_date.split('-');
                                    var formattedDate = given_date;
                                    if (parts.length == 3) {
                                        formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                                    }
                                    rows += `<tr>
                                                 <td>${i + 1}</td>
                                                 <td>${stu_name}</td>
                                                 <td>${formattedDate}</td>
                                                 <td>${data[i].total_fee}</td>
                                                 <td>${data[i].total_paid}</td>
                                                 <td>${data[i].total_paying}</td>
                                                 <td>${data[i].total_balance}</td>
                                                 <td>${data[i].payment_mode}</td>
                                                 <td>
                                                    <a class="p-1 btn-xs bg-primary" href="{{ url('admin/fee/show/${data[i].id}') }}" target="_blank">
                                                      View
                                                    </a>
                                                 </td>
                                              </tr>`;
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
                            let table = $('.datatable-stulist').DataTable(dtOverrideGlobals);
                            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                                $($.fn.dataTable.tables(true)).DataTable()
                                    .columns.adjust();
                            });
                        } else {
                            $("#stulistCard").hide();
                            Swal.fire('', response.data, 'error');
                        }
                    }
                })
            }
        }
    </script>
@endsection
