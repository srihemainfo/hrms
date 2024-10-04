@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header text-center">
            <strong>FACULTY WORKLOAD - REPORTS</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.Faculty-WorkLoad.show') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                        <label class="required" for="AcademicYear">Academic Year</label>
                        <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required>
                            <option value="">Please Select</option>
                            @foreach ($AcademicYear as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        $role_id = auth()->user()->roles[0]->id;
                        if ($role_id == 14) {
                            $dept = auth()->user()->dept;
                            if ($dept == 'S & H') {
                                $semester = [1, 2];
                            } else {
                                $semester = [3, 4, 5, 6, 7, 8];
                            }
                        } else {
                            $semester = [1, 2, 3, 4, 5, 6, 7, 8];
                        }
                    @endphp
                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="department" class="required">Department</label>
                            <select class="form-control select2" name="department" id="department"
                                {{ isset($dept) ? 'readonly' : '' }}>
                                @foreach ($departments as $id => $entry)
                                    @if ($id != '10' && $id != '9' && isset($dept) && $dept == $entry)
                                        <option value="{{ $id }}">{{ $entry }}
                                        </option>
                                    @elseif ($id != '10' && $id != '9' && !isset($dept))
                                        <option value="{{ $id }}">{{ $entry }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                        <label for="semester">Semester</label>
                        <select class="form-control select2" name="semester" id="semester">
                            <option value="">Please Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                        <div class="form-group" style="padding-top: 32px;">
                            <button class=" btn manual_bn">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (isset($staffData))
        <div class="card">
            <div class="card-header text-center">
                <span> <strong>FACULTY WORKLOAD </strong></span>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center  datatable-reports ">
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                Staff ID
                            </th>
                            <th>
                                Faculty Name
                            </th>
                            <th>
                                Subject -1
                            </th>
                            <th>
                                Subject -2
                            </th>
                            <th>
                                Subject -3
                            </th>
                            <th>
                                Subject -4
                            </th>
                            <th>
                                Subject -5
                            </th>
                            <th>
                                Subject -6
                            </th>
                            <th>
                                Allotted Hours Per Week
                            </th>
                            <th>
                                View Time Table
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staffData as $data)
                            <tr>
                                <td width='10'>

                                </td>
                                <td>
                                    {{ $data['staff']->StaffCode ?? '' }}
                                </td>
                                <td>
                                    {{ $data['staff']->name ?? '' }}
                                </td>
                                <td style="font-size:0.8rem;">
                                    @if (isset($data['time_table'][0]))
                                    <div class="text-primary">{{ $data['time_table'][0]->subjects != null ? $data['time_table'][0]->subjects->name : '' }}</div>
                                        ({{ $data['time_table'][0]->class_name }})
                                    @endif
                                </td>
                                <td style="font-size:0.8rem;">
                                    @if (isset($data['time_table'][1]))
                                    <div class="text-primary">{{ $data['time_table'][1]->subjects != null ? $data['time_table'][1]->subjects->name : '' }}</div>
                                        ({{ $data['time_table'][1]->class_name }})
                                    @endif
                                </td>
                                <td style="font-size:0.8rem;">
                                    @if (isset($data['time_table'][2]))
                                        <div class="text-primary">{{ $data['time_table'][2]->subjects != null ? $data['time_table'][2]->subjects->name : '' }}</div>
                                        ({{ $data['time_table'][2]->class_name }})
                                    @endif
                                </td>
                                <td style="font-size:0.8rem;">
                                    @if (isset($data['time_table'][3]))
                                    <div class="text-primary">{{ $data['time_table'][3]->subjects != null ? $data['time_table'][3]->subjects->name : '' }}</div>
                                        ({{ $data['time_table'][3]->class_name }})
                                    @endif
                                </td>
                                <td style="font-size:0.8rem;">
                                    @if (isset($data['time_table'][4]))
                                    <div class="text-primary">{{ $data['time_table'][4]->subjects != null ? $data['time_table'][4]->subjects->name : '' }}</div>
                                        ({{ $data['time_table'][4]->class_name }})
                                    @endif
                                </td>
                                <td style="font-size:0.8rem;">
                                    @if (isset($data['time_table'][5]))
                                    <div class="text-primary">{{ $data['time_table'][5]->subjects != null ? $data['time_table'][5]->subjects->name : '' }}</div>
                                        ({{ $data['time_table'][5]->class_name }})
                                    @endif
                                </td>
                                <td>
                                    {{ $data['count'] }}
                                </td>
                                <td>
                                    @if (isset($data['staff']) && isset($data['staff']->user_name_id) && $data['staff']->user_name_id != null)
                                        <a target="_blank" class="badge badge-primary"
                                            href="{{ route('admin.Faculty-WorkLoad.view', [($dataID = $data['staff']->user_name_id)]) }}">View</a>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <td colspan="5">No Data Found</td>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
@section('scripts')
    @parent
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
            });
        </script>
    @endif

    <script>
        $(function() {
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
            let table = $('.datatable-reports').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
