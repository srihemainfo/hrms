@extends('layouts.admin')
@section('content')
    @can('rooms_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-outline-success" href="{{ route('admin.rooms.create') }}">
                    Create Room
                </a>

            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Rooms List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-room">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>ID</th>

                        <th>
                            {{ trans('cruds.classRoom.fields.block') }}
                        </th>
                        <th>Room No</th>
                        <th>
                            No Of Seats(Class)
                        </th>
                        <th>No Of Seats(Exam)</th>

                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.rooms.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'block_name',
                        name: 'block.name'
                    },
                    {
                        data: 'room_no',
                        name: 'room_no'
                    },
                    {
                        data: 'no_of_class_seats',
                        name: 'no_of_class_seats'
                    },
                    {
                        data: 'no_of_exam_seats',
                        name: 'no_of_class_seats'
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
            };
            let table = $('.datatable-room').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
