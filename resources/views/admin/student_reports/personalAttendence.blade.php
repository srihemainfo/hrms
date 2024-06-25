@if (auth()->user()->roles[0]->id == 11)
    @php
        $key = 'layouts.studentHome';
    @endphp
@else
    @php
        $key = 'layouts.admin';
    @endphp
@endif
@extends($key)
@section('content')
    <div class="card">
        <div class="card-header text-center">
            <strong>Attendence Report</strong>
        </div>
        @php
            function toRoman($number)
            {
                $map = [
                    1000 => 'M',
                    900 => 'CM',
                    500 => 'D',
                    400 => 'CD',
                    100 => 'C',
                    90 => 'XC',
                    50 => 'L',
                    40 => 'XL',
                    10 => 'X',
                    9 => 'IX',
                    5 => 'V',
                    4 => 'IV',
                    1 => 'I',
                ];

                $roman = '';
                foreach ($map as $value => $symbol) {
                    while ($number >= $value) {
                        $roman .= $symbol;
                        $number -= $value;
                    }
                }
                return $roman;
            }
        @endphp
        <div class="card-body">
            <div class="row ">
                <div class="col-md-3 col-5 mb-2">
                    Academic Year : {{ $year ?? '' }}
                </div>
                <div class=" col-md-3 col-1"></div>
                <div class="col-md-3 col-1"></div>
                {{-- <div class="col-3 mb-2">
                    Semester : {{ $sem ?? '' }}
                </div> --}}
                <div class="col-md-3 col-5 mb-2">
                    Semester: <strong>{{ toRoman($sem) }}</strong>
                </div>
            </div>
            <div style="height: 2px; background-color: #dee2e6;" class="mb-4"></div>
            <div class="div">
                <table
                    class="table table-bordered table-response table-striped table-hover ajaxTable datatable datatable-TaskTag text-center"
                    id="studentAttendence">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sl/No</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Faculty Name</th>
                            <th>No Of Periods Attended</th>
                            <th>Total No Of Periods</th>
                            <th>Attendence Percentage</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($response as $index => $responses)
                            {{-- {{ dd($responses) }} --}}
                            <tr>
                                <td></td>
                                <td>{{ $index != '' ? $index + 1 : '' }}</td>
                                <td>{{ $responses->subject_code ?? '' }}</td>
                                <td>{{ $responses->name ?? '' }}</td>
                                <td>{{ $responses->classTeacher ?? '' }}</td>
                                <td>{{ $responses->totalAttended ?? '' }}</td>
                                <td>{{ $responses->totalHours ?? '' }}</td>
                                <td class=" {{ $responses->percentage < 75 ? 'bg-warning text-white' : '' }}">
                                    {{ $responses->percentage ?? '' }}</td>
                                <td>
                                    <a class="btn btn-xs btn-primary"
                                        href="{{ url('admin/subject-attendance-report/show/' . auth()->user()->id . '/' . $responses->enroll_master . '/' . $responses->subject_id) }}"
                                        target="_blank">
                                        view
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <p>
                    <strong>Important :</strong> <span class="text-primary">If you secured less than 75% attendence, not
                        eligible to write the
                        particular subject.</span>
                </p>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'asc']
                ],
                pageLength: 10,
            });
            let table = $('#studentAttendence').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
