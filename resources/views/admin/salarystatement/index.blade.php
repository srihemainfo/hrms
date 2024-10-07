@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Salary Statement
        </div>

        <div class="card-body">
            {{-- <form method="" action="" enctype="multipart/form-data" id="search-form"> --}}
            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="" for="month">Month</label>
                        <select class="form-control select2" name="month" id="month">
                            <option value="">All Months</option>
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
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label class="required" for="year">Year</label>
                        <select class="form-control select2" name="year" id="year" required>
                            @php
                                $current_year = date('Y');
                            @endphp
                            @for ($i = 2010; $i <= $current_year; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group" style="padding-top:30px;">
                        <button id="searchButton" class="enroll_generate_bn">Get Report</button>
                    </div>
                </div>
            </div>
            {{-- </form> --}}
        </div>
    </div>
    <div style="width:100%;position: relative;">
        <div class="loader" id="loader" style="display:none;">
            <div class="spinner-border text-primary"></div>
        </div>
    </div>
    <div class="card" id="statement_table" style="display:none;">

        <div class="card-header">
            Salary Statement List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-salary_statement text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        {{-- <th>
                            {{ trans('cruds.hrmRequestPermission.fields.id') }}
                        </th> --}}
                        <th>
                            {{ 'Staff Name' }}
                        </th>
                        <th>
                            {{ 'Staff Code' }}
                        </th>
                        <th>
                            {{ 'Department ' }}
                        </th>
                        <th>
                            {{ 'DOJ ' }}
                        </th>
                        <th>
                            {{ 'Month ' }}
                        </th>
                        <th>Actual Gross Salary</th>
                        <th>Total Working Days</th>
                        <th>Total Payable Days</th>
                        <th>Total LOP Days</th>
                        <th>
                            {{ 'Basic pay' }}
                        </th>
                        <th>
                            {{ 'DA' }}
                        </th>
                        <th>
                            {{ 'HRA' }}
                        </th>
                        <th>
                            {{ 'AGP' }}
                        </th>
                        <th>
                            {{ 'Special Pay' }}
                        </th>
                        <th>
                            {{ 'Arrears' }}
                        </th>
                        <th>
                            {{ 'Other Allowances' }}
                        </th>
                        <th>
                            {{ 'ABI' }}
                        </th>
                        <th>
                            {{ 'Ph.D. Allowance' }}
                        </th>
                        <th>
                            {{ 'Earnings' }}
                        </th>
                        <th>
                            {{ 'IT' }}
                        </th>
                        <th>
                            {{ 'PT' }}
                        </th>
                        <th>
                            {{ 'Salary Advance' }}
                        </th>
                        <th>
                            {{ 'EPF' }}
                        </th>
                        <th>
                            {{ 'ESI' }}
                        </th>
                        <th>
                            {{ 'LOP' }}
                        </th>
                        <th>
                            {{ 'Other Deduction' }}
                        </th>
                        <th>
                            {{ 'Total Deduction' }}
                        </th>

                        <th>
                            {{ 'Net Pay' }}
                        </th>

                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $('#searchButton').click(function() {
            let table = $('.datatable-salary_statement').DataTable();
            table.clear().draw();
            $("#statement_table").hide();
            $("#loader").show();
            $.ajax({
                url: '{{ route('admin.salary-statement.get_report') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {
                    department: $("#department").val(),
                    year: $('#year').val(),
                    month: $('#month').val()
                },
                success: function(response) {

                    $("#statement_table").show();
                    $("#loader").hide();

                    // Destroy existing DataTable instance
                    let table = $('.datatable-salary_statement').DataTable();
                    table.destroy();

                    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


                    let generate = {
                        text: 'Pay Slip Generation',
                        url: "{{ route('admin.payslip.slip_generation') }}",
                        className: 'btn-success',
                        action: function(e, dt, node, config) {
                            var ids = $.map(dt.rows({
                                selected: true
                            }).data(), function(entry) {
                                return entry.id
                            });

                            // console.log(ids.length)

                            if (ids.length === 0) {
                                alert('{{ trans('global.datatables.zero_selected') }}')

                                return
                            }

                            if (confirm('{{ trans('global.areYouSure') }}')) {
                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: config.url,
                                        data: {
                                            ids: ids,
                                        }
                                    })
                                    .done(function(response) {

                                        alert(response.status)

                                    })
                            }
                        }
                    }

                    dtButtons.splice(2, 2);
                    dtButtons.push(generate);
                    // console.log(dtButtons)

                    let dtOverrideGlobals = {
                        buttons: dtButtons,
                        deferRender: true,
                        retrieve: true,
                        aaSorting: [],
                        data: response.data,
                        columns: [{
                                data: 'placeholder',
                                name: 'placeholder',
                                orderable: false,
                                searchable: false,

                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'staff_code',
                                name: 'staff_code'
                            },
                            {
                                data: 'department',
                                name: 'department'
                            },
                            {
                                data: 'doj',
                                name: 'doj'
                            },
                            {
                                data: 'month',
                                name: 'month'
                            },
                            {
                                data: 'gross_salary',
                                name: 'gross_salary'
                            },
                            {
                                data: 'total_working_days',
                                name: 'total_working_days'
                            },
                            {
                                data: 'total_payable_days',
                                name: 'total_payable_days'
                            },
                            {
                                data: 'total_lop_days',
                                name: 'total_lop_days'
                            },
                            {
                                data: 'basicpay',
                                name: 'basicpay'
                            },

                            {
                                data: 'da',
                                name: 'da'
                            },
                            {
                                data: 'hra',
                                name: 'hra'
                            },
                            {
                                data: 'agp',
                                name: 'agp'
                            },
                            {
                                data: 'specialpay',
                                name: 'specialpay'
                            },
                            {
                                data: 'arrears',
                                name: 'arrears'
                            },
                            {
                                data: 'otherall',
                                name: 'otherall'
                            },
                            {
                                data: 'abi',
                                name: 'abi'
                            },
                            {
                                data: 'phdallowance',
                                name: 'phdallowance'
                            },
                            {
                                data: 'earnings',
                                name: 'earnings'
                            },
                            {
                                data: 'it',
                                name: 'it'
                            },
                            {
                                data: 'pt',
                                name: 'pt'
                            },
                            {
                                data: 'salaryadvance',
                                name: 'salaryadvance'
                            },
                            {
                                data: 'epf',
                                name: 'epf'
                            },
                            {
                                data: 'esi',
                                name: 'esi'
                            },
                            {
                                data: 'lop',
                                name: 'lop'
                            },
                            {
                                data: 'otherdeduction',
                                name: 'otherdeduction'
                            },
                            {
                                data: 'totaldeductions',
                                name: 'totaldeductions'
                            },
                            {
                                data: 'netpay',
                                name: 'netpay'
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
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            // var total4 = api.column(4).data().reduce(function(a,
                            //     b) {
                            //  return a + b ;
                            // }, 0);
                            // var total5 = api.column(5).data().reduce(function(a,
                            //     b) {
                            //     return Math.round(a + b);
                            // }, 0);
                            var total6 = api.column(6).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total7 = api.column(7).data().reduce(function(a,
                                b) {
                                return '';
                            }, 0);
                            var total8 = api.column(8).data().reduce(function(a,
                                b) {
                                return '';
                            }, 0);
                            var total9 = api.column(9).data().reduce(function(a,
                                b) {
                                return '';
                            }, 0);
                            var total10 = api.column(10).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total11 = api.column(11).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total12 = api.column(12).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total13 = api.column(13).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total14 = api.column(14).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total15 = api.column(15).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total16 = api.column(16).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total17 = api.column(17).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total18 = api.column(18).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total19 = api.column(19).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total20 = api.column(20).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total21 = api.column(21).data().reduce(function(a,
                                b) {
                                return Math.round(a + b);
                            }, 0);
                            var total22 = api.column(22).data().reduce(function(a, b) {
                                return Math.round(a + b);
                            }, 0);
                            var total23 = api.column(23).data().reduce(function(a, b) {
                                return Math.round(a + b);
                            }, 0);
                            var total24 = api.column(24).data().reduce(function(a, b) {
                                return Math.round(a + b);
                            }, 0);
                            var total25 = api.column(25).data().reduce(function(a, b) {
                                return Math.round(a + b);
                            }, 0);
                            var total26 = api.column(26).data().reduce(function(a, b) {
                                return Math.round(a + b);
                            }, 0);
                            var total27 = api.column(27).data().reduce(function(a, b) {
                                return Math.round(a + b);
                            }, 0);
                            var total28 = api.column(28).data().reduce(function(a, b) {
                                return Math.round(a + b);
                            }, 0);



                            // Total over this page
                            var pageTotal = api.column(3, {
                                page: 'current'
                            }).data().reduce(function(a, b) {
                                return Math.round(a + b);
                            }, 0);


                            // Update footer
                            // console.log( $(api.table().footer()))
                            $(api.table().footer()).html(
                                '<tr>' +
                                '<td colspan="6"><strong>Total:</strong></td>' +
                                // '<td> ' + total3 + '  </td>' +
                                // '<td> ' + total4 + '  </td>' +
                                // '<td>' + total5 + '  </td>' +
                                '<td>' + total6 + '  </td>' +
                                '<td>' + total7 + '  </td>' +
                                '<td>' + total8 + '  </td>' +
                                '<td>' + total9 + '  </td>' +
                                '<td>' + total10 + ' </td>' +
                                '<td>' + total11 + ' </td>' +
                                '<td>' + total12 + '  </td>' +
                                '<td>' + total13 + '  </td>' +
                                '<td>' + total14 + '  </td>' +
                                '<td>' + total15 + '  </td>' +
                                '<td>' + total16 + '  </td>' +
                                '<td>' + total17 + '  </td>' +
                                '<td>' + total18 + '  </td>' +
                                '<td>' + total19 + '  </td>' +
                                '<td> ' + total20 + ' </td>' +
                                '<td> ' + total21 + ' </td>' +
                                '<td> ' + total22 + ' </td>' +
                                '<td> ' + total23 + ' </td>' +
                                '<td> ' + total24 + ' </td>' +
                                '<td> ' + total25 + ' </td>' +
                                '<td> ' + total26 + ' </td>' +
                                '<td> ' + total27 + ' </td>' +
                                '<td> ' + total28 + ' </td>' +
                                '<td>  </td>' +
                                '</tr>'
                            );
                        }
                    }

                    table = $('.datatable-salary_statement').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable()
                            .columns.adjust();
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            })


        })
    </script>
@endsection
