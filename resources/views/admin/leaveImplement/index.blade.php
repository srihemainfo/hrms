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
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Date</label>
                        <input type="text" name="date" id="date" class="form-control date"
                            placeholder="Select Date">
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Staff Type</label>
                        <select name="staff_type" id="staff_type" class="form-control select2" multiple>
                            @foreach ($type as $i => $data)
                                <option value="{{ $i }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Leave Type</label>
                        <select name="leave_type" id="leave_type" class="form-control select2">
                            <option value="Holiday">HoliDay</option>
                        </select>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="" class="required">Half Day</label><br>
                        <input id="half_day" name="half_day" type="checkbox" style="width:18px;height:18px;">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-12" id="day_type_div" style="display: none;">
                    <div class="form-group">
                        <label for="" class="required">Day type</label>
                        <select name="day_type" id="day_type" class="form-control select2">
                            <option value="">Select Day type</option>
                            <option value="Fore Noon">Fore Noon</option>
                            <option value="After Noon">After Noon</option>
                        </select>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
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
                        <th style="width: 100px">Staff Type</th>
                        <th>Leave Type</th>
                        <th>Half Day (FN / AN)</th>
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
                        data: 'staff_type',
                        name: 'staff_type'
                    },
                    {
                        data: 'leave_type',
                        name: 'leave_type'
                    },
                    {
                        data: 'half_day',
                        name: 'half_day'
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
                columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return `<div style="word-wrap: break-word; width: 100%"; >${data}</div>`;
                    },
                    targets: 3
                }
             ],
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
                            // $('#process').hide()
                            // $('#btn-save').show()
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
            } else if ($("#staff_type").val() == '') {
                Swal.fire('', 'Please Choose The Staff Type!', 'warning');
                return false;
            } else if ($("#leave_type").val() == '') {
                Swal.fire('', 'Please Choose The Leave Type!', 'warning');
                return false;

            } else if ($('#half_day').is(':checked') == true && $('#day_type').val() == '') {

                Swal.fire('', 'Please Fill The Day type', 'warning');
                return false;

            } else if ($("#reason").val() == '') {
                Swal.fire('', 'Please Fill The Remark', 'warning');
                return false;

            } else {
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
                        let staff_type = $("#staff_type").val();
                        let leave_type = $("#leave_type").val();
                        let day_type = $("#day_type").val();
                        let reason = $("#reason").val();
                        $('#process').show()
                        $('#btn-save').hide()
                        $.ajax({
                            url: "{{ route('admin.leave-implementation.store') }}",
                            type: 'POST',
                            data: {
                                'date': date,
                                'staff_type': staff_type,
                                'leave_type': leave_type,
                                'day_type': day_type,
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
