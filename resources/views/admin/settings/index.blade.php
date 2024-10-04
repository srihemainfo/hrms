@extends('layouts.admin')
@section('content')
    @can('setting_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-warning" data-toggle="modal" data-target="#settingsModal">
                    PackUp Database
                </button>
            </div>
        </div>
    @endcan
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel">PackUp DB</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-12 form-group'>
                            <label for="ay" class="required">AY</label>
                            <select name="ay" id="ay" class="select2 form-control">
                                <option value="">Select AY</option>
                                @foreach ($ays as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12 form-group'>
                            <button class="btn btn-success" id="action" onclick="generatePackUp()">Generate
                                PackUp</button>
                            <span class="text-success" id="loading" style="display:none;">Processing...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            PackUp DB List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Setting">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>S.No</th>
                        <th>AY</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        function callAjax() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.settings.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    }, {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ay',
                        name: 'ay'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-Setting').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function generatePackUp() {
            $("#loading").show();
            $("#action").hide();
            $.ajax({
                url: "{{ route('admin.packup-db.generation') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'ay': $("#ay").val()
                },
                success: function(response) {

                    $("#loading").hide();
                    $("#action").show();
                    if (response.status == true) {
                        Swal.fire('', response.data, 'success');
                        callAjax();

                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error',
                                'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR
                            .statusText,
                            "error");
                    }
                    $("#loading").hide();
                    $("#action").show();
                }
            })
        }
    </script>
@endsection
