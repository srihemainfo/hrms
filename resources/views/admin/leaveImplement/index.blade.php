@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
            margin: auto;
        }
    </style>

    <div class="card" style="position: relative;">
        <div class="card-header text-primary">
            Leave Implementation (HoliDay)
        </div>
        <div class="card-body">
            <div class="row gutters">

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Date</label>
                        <input type="text" name="date" id="date" class="form-control date"
                            placeholder="Select Date">
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="start_time" style="display:block;" class="required">Start Time</label>
                        <input type="time" class="form-control" name="start_time" id="start_time">
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="end_time" style="display:block;" class="required">End Time</label>
                        <input type="time" class="form-control" name="end_time" id="end_time">
                    </div>
                </div>

            </div>
            <div class="row gutters">

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">

                    <div class="form-group">
                        <label for="" class="required">Remark</label>
                        <input type="input" name="reason" id="reason" class="form-control "
                            placeholder=" Fill The Remark..">
                    </div>
                </div>


                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <button id="btn-save" class="enroll_generate_bn bg-primary" style="margin-top:1.9rem;"
                            onclick="save()">
                            Save
                        </button>
                        <p style="display:none; margin-top:1.9rem; padding-top: 10px;" id="process" class="text-success">
                            Processing...</p>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="card" style="position: relative;">
        <div class="card-header text-primary">Leave Implementation History</div>
        {{-- <div class="loader" id="loader" style="display:none;top:15%;">
                <div class="spinner-border text-primary"></div>
            </div> --}}
        <div class="card-body">
            <table class="table table-striped table-hover ajaxTable datatable datatable-LeaveImplementation text-center">
                <thead>
                    <tr>
                        <th width="10"> </th>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.leave-implementation.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'start_time',
                        name: 'start_time'
                    },
                    {
                        data: 'end_time',
                        name: 'end_time'
                    },
                    {
                        data: 'remark',
                        name: 'remark'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}',
                        render: function(data, type, full, meta) {
                            var datas = JSON.parse(data);
                            if (data != null) {
                                return `<button class="btn btn-xs btn-danger del_btn"
                                            onclick="del_btn(${datas.id}, this)">Delete</button>
                                            <div class="loader" id="loader" style="display:none; top:12px; padding: 0">
                                                <div class="spinner-border text-primary"></div>
                                            </div>`;
                            }
                            return '';
                        }
                    }
                ],
                columnDefs: [{
                    render: function(data, type, full, meta) {
                        return `<div style="word-wrap: break-word; width: 100%"; >${data}</div>`;
                    },
                    targets: 3
                }],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-LeaveImplementation').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function del_btn(id, element) {
            // console.log(element);
            Swal.fire({
                title: "Are You Sure?",
                text: "You Want To Delete?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $(element).hide();
                    $(element).next().show();
                    $.ajax({
                        url: "{{ route('admin.leave-implementation.destroy') }}",
                        type: 'POST',
                        data: {
                            'id': id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', 'Leave Implemented Deleted Successfully', 'success');

                            } else {
                                Swal.fire('', 'Technical Error', 'error');
                            }
                            $("#loader").hide();
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('', 'You Cancelled The Action!', 'info');
                }
            });
        }

        function save() {

            if ($("#date").val() == '') {
                Swal.fire('', 'Please Choose The Date!', 'warning');
                return false;
            }

            else if ($("#start_time").val() == '') {
                Swal.fire('', 'Please Fill The Start Time', 'warning');
                return false;
            }

            else if ($("#end_time").val() == '') {
                Swal.fire('', 'Please Fill The End Time', 'warning');
                return false;
            }

            else if ($("#reason").val() == '') {
                Swal.fire('', 'Please Fill The Remark', 'warning');
                return false;

            }


            else {
                $('#process').hide()
                $('#loader').hide()
                Swal.fire({
                    title: "Are You Sure?",
                    text: "You Will Not Be Able To Recover This Day!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        let date = $("#date").val();
                        let start_time = $("#start_time").val();
                        let end_time = $("#end_time").val();
                        let reason = $("#reason").val();
                        $('#process').show()
                        $('#btn-save').hide()
                        $.ajax({
                            url: "{{ route('admin.leave-implementation.store') }}",
                            type: 'POST',
                            data: {
                                'date': date,
                                'start_time': start_time,
                                'end_time': end_time,
                                'reason': reason,
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#process').hide()
                                $('#btn-save').show()
                                let status = response.status;
                                if (status == true) {
                                    Swal.fire('', 'Leave Implemented!', 'success');

                                } else {
                                    Swal.fire('', 'Technical Error', 'error');
                                }
                                $("#loader").hide();
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire('', 'You Cancelled The Action!', 'info');
                    }
                });

            }
        }

        $('#half_day').click(function() {

            $('#day_type_div').toggle()
        })
    </script>
@endsection
