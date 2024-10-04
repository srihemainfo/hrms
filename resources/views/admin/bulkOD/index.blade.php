@extends('layouts.admin')
@section('content')
    <style>
        .table.dataTable tbody td.select-checkbox:before {
            content: none !important;
        }
    </style>
    @can('bulk_od_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-2">
                <a class="btn btn-success" href="{{ route('admin.bulk-ods.create') }}">
                    Apply Institute OD
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">Institute OD Application</div>
        <div class="card-body">
            <form action="{{ route('admin.bulk-ods.index_page') }}" id="search_form" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label class="required" for="organized_by">Organized By</label>
                        <select class="form-control select2" name="organized_by" id="organized_by"
                            onchange="change_depts(this)" required>
                            <option value="" {{ $organized_by == '' ? 'selected' : '' }}>Please Select</option>
                            <option value="Placement" {{ $organized_by == 'Placement' ? 'selected' : '' }}>Placement
                            </option>
                            <option value="Training" {{ $organized_by == 'Training' ? 'selected' : '' }}>Training</option>
                            <option value="Centres" {{ $organized_by == 'Centres' ? 'selected' : '' }}>Centres</option>
                            <option value="Department" {{ $organized_by == 'Department' ? 'selected' : '' }}>Department
                            </option>
                            <option value="Clubs" {{ $organized_by == 'Clubs' ? 'selected' : '' }}>Clubs</option>
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <label class="required" for="dept_name">Name Of the Department</label>
                        <select class="form-control select2" name="dept_name" id="dept_name" required>
                            @if ($organized_by == 'Department')
                                @foreach ($deparments as $id => $entry)
                                    <option value="{{ $entry }}" {{ $entry == $dept_name ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            @else
                                <option value="{{ $dept_name }}" selected>{{ $dept_name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-5 col-sm-5 col-11">
                        <label class="" for="event_category">Event Category</label>
                        <select class="form-control select2" name="event_category" id="event_category">
                            <option value="" {{ $event_category == '' ? 'selected' : '' }}>All</option>
                            <option value="Internal" {{ $event_category == 'Internal' ? 'selected' : '' }}>Internal
                            </option>
                            <option value="External" {{ $event_category == 'External' ? 'selected' : '' }}>External
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-xl-6 col-lg-6 col-md-8 col-sm-8 col-12">
                        <label class="" for="event_date">Event Date (From & To)</label>
                        <div style="display:flex;">
                            <input type="text" class="p-1 date" style="width:40%;border: 1px solid #cfd1d8;"
                                name="from_date" id="from_date" placeholder="Enter From Date" value="{{ $from_date }}">
                            <input type="text" class="p-1 date"
                                style="margin-left:3%;width:40%;border: 1px solid #cfd1d8;" name="to_date" id="to_date"
                                placeholder="Enter To Date" value="{{ $to_date }}">
                        </div>
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-2 col-sm-2 col-12">
                        <div class="form-group" style="padding-top: 32px;">
                            <button type="submit" style="width:100%;" id="submit" name="submit"
                                class="enroll_generate_bn">Go</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Institue OD List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover datatable datatable-bulk-od text-center">
                <thead>
                    <tr>
                        <th>
                            S No
                        </th>
                        <th>
                            Organized By
                        </th>
                        <th>
                            Name of the Department
                        </th>
                        <th>
                            Incharge
                        </th>
                        <th>
                            Event Title
                        </th>
                        <th>
                            Event Category
                        </th>
                        <th>
                            External Event Venue
                        </th>
                        <th>
                            From Date
                        </th>
                        <th>
                            To Date
                        </th>
                        <th>
                            From Period
                        </th>
                        <th>
                            To Period
                        </th>
                        <th>
                            Total No of Students
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($get_data) > 0)
                        @foreach ($get_data as $id => $data)
                            <tr>
                                <td>{{ $id + 1 }}</td>
                                <td>{{ $data->organized_by }}</td>
                                <td>{{ $data->dept_name }}</td>
                                <td>{{ $data->tech_staff->name  }}  ({{ $data->tech_staff->StaffCode }})</td>
                                <td>{{ $data->event_title }}</td>
                                <td>{{ $data->event_category }}</td>
                                <td>{{ $data->ext_event_venue }}</td>
                                <td>{{ $data->from_date }}</td>
                                <td>{{ $data->to_date }}</td>
                                <td>{{ $data->from_period }}</td>
                                <td>{{ $data->to_period }}</td>
                                <td>{{ $data->count }}</td>
                                <td>
                                    @if ($data->status == 0)
                                        <span class="btn btn-xs btn-warning">Pending</span>
                                    @elseif ($data->status == 1)
                                        <span class="btn btn-xs btn-success">Approved</span>
                                    @elseif ($data->status == 2)
                                        <span class="btn btn-xs btn-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-info" href="{{ url('admin/bulk-ods/' . $data->id) }}">view</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11">No Data Available...</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.onload = function() {
            let select = ('student', 'organized_by', 'incharge', 'dept_name')
            $(select).select2();

        }

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            dtButtons.splice(0, 7);
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-bulk-od').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

        function change_depts(element) {
            if (element.value != '') {

                if (element.value == 'Placement') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    <option value="Placement" selected>Placement</option>`);
                }
                if (element.value == 'Training') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    <option value="Training" selected>Training</option>`);
                }
                if (element.value == 'Centres') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    <option value="Centres" selected>Centres</option>`);
                }
                if (element.value == 'Department') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    @foreach ($deparments as $id => $entry)
                       <option value="{{ $entry }}">{{ $entry }}</option>
                    @endforeach`);
                }
                if (element.value == 'Clubs') {
                    $("#dept_name").html(`
                    <option value="">Please Select</option>
                    <option value="Clubs" selected>Clubs</option>`);
                }
            }
        }
    </script>
@endsection
