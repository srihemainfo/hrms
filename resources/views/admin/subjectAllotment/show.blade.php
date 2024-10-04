@extends('layouts.admin')
@section('content')
    @php
        $regular_limits = $professional_limits = $open_limits = $others_limits = 0;
        if (count($regular) > 0) {
            $regular_limits = $regular[0]->option_limits == null ? 0 : $regular[0]->option_limits;
        }
        if (count($professional) > 0) {
            $professional_limits = $professional[0]->option_limits == null ? 0 : $professional[0]->option_limits;
        }
        if (count($open) > 0) {
            $open_limits = $open[0]->option_limits == null ? 0 : $open[0]->option_limits;
        }
        if (count($others) > 0) {
            $others_limits = $others[0]->option_limits == null ? 0 : $others[0]->option_limits;
        }
    @endphp
    <a class="btn btn-default" style="margin-bottom:17px;" href="{{ route('admin.subject-allotment.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card" id="open_form">
        <div class="card-header text-center text-primary">Semester Wise Subjects Allotment</div>
        <div class="card-header text-center">
            <div style="display:flex;justify-content:space-between;font-size:0.75rem;">
                <div style="" class="manual_bn">Regulation : {{ $reg }}</div>
                <div style="" class="manual_bn">Department : {{ $dept }}</div>
                <div style="" class="manual_bn">Course : {{ $course }}</div>
                <div style="" class="manual_bn">AY : {{ $ay }}</div>
                <div style="" class="manual_bn">Semester : {{ $sem }}</div>
                <div style="" class="manual_bn">Sem Type : {{ $sem_type }}</div>
            </div>
        </div>
        <div class="card-body">
            @if ($check_course == 13 || $check_course == '13')
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Regular Subjects</div>
                            <div style="width:30%;text-align:center;">
                                <div style="right:0;background-color:gray;" class="manual_bn">All Subjects Are Mandatory
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="regular-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($regular) > 0)
                                    @foreach ($regular as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Electives Human Resource</div>
                            {{-- <div style="right:0;background-color:gray;" class="manual_bn">Limit :
                                {{ $professional_limits }}
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="pg-professional-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($hr) > 0)
                                    @foreach ($hr as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Electives Finance</div>
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit : {{ $open_limits }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="pg-open-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($finance) > 0)
                                    @foreach ($finance as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Elective Operations</div>
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit : {{ $others_limits }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="pg-others-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($operations) > 0)
                                    @foreach ($operations as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Elective Logistics</div>
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit : {{ $others_limits }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="pg-others-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($logistics) > 0)
                                    @foreach ($logistics as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Regular Subjects</div>
                            <div style="width:30%;text-align:center;">
                                <div style="right:0;background-color:gray;" class="manual_bn">All Subjects Are Mandatory
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="regular-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($regular) > 0)
                                    @foreach ($regular as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Professional Electives</div>
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit :
                                {{ $professional_limits }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="professional-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($professional) > 0)
                                    @foreach ($professional as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Open Electives</div>
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit : {{ $open_limits }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="open-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($open) > 0)
                                    @foreach ($open as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Others</div>
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit : {{ $others_limits }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Subject Type</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody id="others-table">
                                @php
                                    $i = 1;
                                @endphp
                                @if (count($others) > 0)
                                    @foreach ($others as $subject)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $subject->subjects->subject_code }}</td>
                                            <td>{{ $subject->subjects->name }}</td>
                                            <td>{{ $subject->subjects->subject_type_id }}</td>
                                            <td>{{ $subject->subjects->credits }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Data Available..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div> --}}
            @endif

        </div>
    </div>
@endsection
