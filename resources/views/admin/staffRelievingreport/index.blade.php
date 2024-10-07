@extends('layouts.admin')

@section('content')
    <div class="card" id="staff_status">
        <div class="card-body">

            <ul class="nav nav-tabs mb-3" style="font-size: 1.3rem;">
                <li class="nav-item">
                    <span class="text-primary nav-link " style="cursor:pointer;" id="Relieving"
                        onclick="search('Relieving')">Relieving</span>
                </li>
                <li class="nav-item">
                    <span class="text-primary nav-link" style="cursor:pointer;" id="Medical_Leave"
                        onclick="search('Medical_Leave')">Medical Leave</span>
                </li>
                <li class="nav-item">
                    <span class="text-primary nav-link" style="cursor:pointer;" id="Maternity_Leave"
                        onclick="search('Maternity_Leave')">Maternity Leave</span>
                </li>
                <li class="nav-item">
                    <span class="text-primary nav-link" style="cursor:pointer;" id="Break"
                        onclick="search('Break')">Break</span>
                </li>
            </ul>

            <div>
                <table class="table table-bordered table-striped table-hover datatable-examtimetable w-100 text-center"
                    id="stulist">
                    <thead>
                        <tr class="" id='details_head'>
                            <th class="text-center"></th>
                            <th class="text-center">Staff Name(Code)</th>
                            <th class="text-center">Date Of Joining</th>
                            <th class="text-center">Date Of Relived</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        {{-- {{ dd($datas) }} --}}
                        @foreach ($datas as $item)
                            <tr>
                                <td></td>
                                <td>{{ $item->name . ' ( ' . $item->employee_id . ' )' }}</td>
                                <td>{{ $item->DOJ }}</td>
                                <td>{{ $item->DOR }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        function search(element) {

            if ($.fn.DataTable.isDataTable('#datatable-examtimetable')) {
                $('#datatable-examtimetable').DataTable().destroy();
            }
            $("#tbody").html(`<tr class="text-center text-primary"><td colspan="9"> loading...</td></tr>`);
            let current_status;

            if (element == 'Medical_Leave') {
                current_status = 'Medical Leave';

            } else if (element == 'Maternity_Leave') {
                current_status = 'Maternity Leave';
            } else if (element == 'Break') {
                current_status = 'Break';
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
            $("#staff_status").show();

            $.ajax({
                url: "{{ route('admin.Staff-Relieving-Report.search') }}",
                method: 'POST',
                headers: {
                    'x-csrf-token': _token
                },
                data: {
                    'status': current_status,
                },
                success: function(response) {
                    // $("#hidden").show();

                    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
                    let dtOverrideGlobals = {
                        buttons: dtButtons,
                        deferRender: true,
                        retrieve: true,
                        aaSorting: [],
                        data: response.datas,
                        columns: [{
                                data: 'empty',
                                name: 'empty',
                                render: function(data, type, full, meta) {
                                    return '';
                                }
                            },
                            {
                                data: 'user_id_staff_code',
                                name: 'name',
                            },

                            {
                                data: 'DOJ',
                                name: 'DOJ',
                            },
                            {
                                data: 'DOR',
                                name: 'DOR',
                            }
                        ],
                        orderCellsTop: true,
                        order: [
                            [1, 'desc']
                        ],
                        pageLength: 10,
                    };

                    let table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
                    table.destroy();
                    table = $('.datatable-examtimetable').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).columns.adjust().DataTable();
                    });

                },

                error: function(xhr, status, error) {
                    console.log('An error occurred: ' + error);
                    $("#staff_status").hide();
                    // $("#hidden").hide();
                }
            });
        }
    </script>
@endsection
