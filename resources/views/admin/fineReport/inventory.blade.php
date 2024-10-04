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
    {{-- <div class="card float-right"
        style="width: fit-content; background-color: #2276cf; color: white;     box-shadow: 0 0 0 0;">
        <div class="card-body" style="padding: 1rem; ">
            <div class="div"><b style="font-weight: initial;">Total Fine Amount :
                </b>&#x20B9;{{ $totalFine->total_fine }}.00</div>
        </div>
    </div> --}}
    <div class="card">
        <div class="card-header">
            Inventory Management Report
        </div>
        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FineReport text-center">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            SNo
                        </th>
                        <th>
                            Book Title
                        </th>
                        <th>
                            Author(s)
                        </th>
                        <th>
                            ISBN
                        </th>
                        <th>
                            Publisher
                        </th>
                        <th>
                            Total Quantity
                        </th>
                        <th>
                            Available Quantity
                        </th>
                        {{-- <th>
                            Reserved Quantity
                        </th> --}}
                        <th>
                            Loaned Quantity
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
            dtButtons.splice(1, 3);
            dtButtons.splice(4, 4);

            if ($.fn.DataTable.isDataTable('.datatable-FineReport')) {
                $('.datatable-FineReport').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.inventory-report.inventory') }}",
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
                        data: 'author',
                        name: 'author',
                    },
                    {
                        data: 'isbn',
                        name: 'isbn'
                    },
                    {
                        data: 'publication',
                        name: 'publication'
                    },
                    {
                        data: 'book_count',
                        name: 'book_count'
                    },
                    {
                        data: 'available',
                        name: 'available'
                    },
                    {
                        data: 'loaned',
                        name: 'loaned'
                    }

                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-FineReport').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        };

        // function filterReservation() {
        //     if ($('#from_date').val() == '') {
        //         $("#from_date_span").html(`From Date Is Required.`);
        //         $("#from_date_span").show();
        //         $("#to_date_span").hide();
        //     } else if ($('#to_date').val() == '') {
        //         $("#to_date_span").html(`To Date Is Required.`);
        //         $("#to_date_span").show();
        //         $("#from_date_span").hide();
        //     } else {
        //         $('#save_div').hide();
        //         $('#loading_div').show();
        //         $("#to_date_span").hide();
        //         $("#from_date_span").hide();
        //         $.ajax({
        //             url: "{{ route('admin.reserve-report.search') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: {
        //                 'from_date': $('#from_date').val(),
        //                 'to_date': $('#to_date').val()
        //             },
        //             success: function(response) {
        //                 let data = response.data
        //                 let status = response.status

        //                 if (status == true) {
        //                     let table = $('.datatable-FineReport').DataTable();
        //                     table.destroy();
        //                     let body = $('#tbody').empty()
        //                     let i = 0;
        //                     $.each(data, function(index, value) {
        //                         let row = $('<tr>')
        //                         row.append(`<td></td>`)
        //                         row.append(`<td>${i+=1}</td>`)
        //                         row.append(`<td>${value.name}</td>`)
        //                         row.append(`<td>${value.title}</td>`)
        //                         row.append(`<td>${value.book_name}</td>`)
        //                         row.append(`<td>${value.reserve_date}</td>`)
        //                         body.append(row)
        //                     })

        //                     table = $('.datatable-FineReport').DataTable();

        //                 } else {
        //                     Swal.fire('', data, 'error');
        //                 }

        //                 $('#save_div').show()
        //                 $('#loading_div').hide()
        //             }
        //         })
        //     }
        // }
    </script>
@endsection
