@extends('layouts.admin')
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            Transport Report
        </div>

        <div class="card-body">
            <div class="row gutters" id="gutters">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 form-group">
                    <label for="designation" class="required">Designation</label>
                    <input type="hidden" name="bus_stu_id" id="bus_stu_id" value="">
                    <select class="form-control select2" name="designation" id="designation" onclick="changeDesignation()">
                        <option value="">Select Designation</option>
                        @foreach ($designation as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <span id="designation_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <div id="save_div" style="margin-top: 32px; ">
                        <span id="error_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                        <button type="button" class="enroll_generate_bn" onclick="reportFun()">Save</button>
                    </div>
                    <div id="loading_div" style="margin-top: 32px; display: none;">
                        <span class="theLoader"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="secondLoader"></div>
    </div>

    <div class="card">
        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TransportReport text-center">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>
                            ID
                        </th>
                        <th>
                            Bus No
                        </th>
                        <th>
                            Stop Name
                        </th>
                        <th>
                            Student
                        </th>
                        <th>
                            Roll Number
                        </th>
                        <th>
                            Enrollment
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

            if ($.fn.DataTable.isDataTable('.datatable-TransportReport')) {
                $('.datatable-TransportReport').DataTable().destroy();
            }

            let table = $('.datatable-TransportReport').DataTable();
        };

        function openModal() {
            $('.tbody1').hide()
            $('.student').show()
            $("#bus_stu_id").val('')
            $("#designation").val('').select2()
            $("#seats").val('')
            $("#driver").val('')
            $("#bus").val('')
            $("#student").val('').select2()
            $("#stops").empty()
            $("#stops").append(`<option value="">Select Stop</option>`).select2()
            $("#designation_span").hide();
            $("#seats_span").hide();
            $("#loading_div").hide();
            $(".save_div").show();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#transReportModel").modal();
        }


        function callDesignation() {
            return new Promise((resolve, reject) => {
                $('#stops').empty()

                $('#seats').val('Loading...')
                $('#driver').val('Loading...')
                $('#bus').val('Loading...')
                $('#stops').html(`<option value="">Loading...</option>`)

                $.ajax({
                    url: "#",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $('#designation').val()
                    }
                }).done(function(response) {
                    let status = response.status;
                    $(".secondLoader").hide();
                    if (status == true) {
                        var data = response.data;
                        $('#seats').val(data[0].total_seats)
                        $('#driver').val(data[0].name)
                        $('#bus').val(data[0].bus_no)
                        let get_stops = JSON.parse(data[0].stops)
                        let stops = [];
                        $.each(get_stops, function(index, value) {
                            $.each(value, function(key, getValue) {
                                stops[index] = key + '(' + getValue + ' Km)';
                            })
                        })
                        let select = $('#stops').empty()
                        if (stops.length > 0) {
                            select.append(`<option value="">Select Stop</option>`)
                            $.each(stops, function(index, value) {
                                if (value != undefined) {
                                    select.append(`<option value="${index}">${value}</option>`)
                                }
                            })
                            select.select2()
                        }

                        $("#save_div").show();
                        $("#degree_span").hide();
                        $("#from_span").hide();
                        $("#to_span").hide();
                        $("#loading_div").hide();
                        resolve(); // Resolve the promise when everything is done
                    } else {
                        Swal.fire('', response.data, 'error');
                        reject(new Error(
                            'Error in callDesignation')); // Reject the promise if there's an error
                    }
                }).fail(function(xhr, status, error) {
                    // Handle AJAX error
                    reject(new Error(error)); // Reject the promise with the error object
                });
            });
        }

        function reportFun() {
            if ($('#designation').val() != '') {
                $('#save_div').hide()
                $('#loading_div').show()
                $.ajax({
                    url: "{{ route('admin.transport-report.report') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'designation': $('#designation').val()
                    },
                    success: function(response) {
                        let data = response.data
                        let status = response.status
                        
                        if (status == true) {
                            let table = $('.datatable-TransportReport').DataTable();
                            table.destroy();
                            let body = $('#tbody').empty()
                            let i = 0;
                            $.each(data, function(index, value) {
                                let student = value.student
                                $.each(student, function(subIndex, student) {
                                    let row = $('<tr>')
                                    row.append(`<td></td>`)
                                    row.append(`<td>${i+=1}</td>`)
                                    row.append(`<td>${value.bus_no}</td>`)
                                    row.append(`<td>${value.stop_name}</td>`)
                                    row.append(`<td>${student[0]}</td>`)
                                    row.append(`<td>${student[2]}</td>`)
                                    row.append(`<td>${student[1]}</td>`)
                                    body.append(row)
                                })
                            })

                            table = $('.datatable-TransportReport').DataTable();

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
