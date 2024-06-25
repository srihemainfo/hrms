@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    } else {
        $key = 'layouts.admin';
    }
@endphp
@extends($key)
@section('content')

    @if ($role_id != 11)
        <a class="btn btn-default" style="margin-bottom:17px;" href="{{ route('admin.subjectRegistration.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    @endif
    <form action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card" id="open_form">
            <div class="card-header text-center text-primary"><strong class="fs-1">My Subjects</strong></div>
            <div class="card-body">
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div style="" class="manual_bn">Regular Subjects</div>
                            <div style="width:30%;text-align:center;">
                                {{-- <div style="right:0;background-color:gray;" class="manual_bn">All Subjects Are Mandatory
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        {{-- <th>Select</th> --}}
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                    </tr>
                                </thead>
                                <tbody id="regular-table">

                                    @if (count($regular) > 0)
                                        @foreach ($regular as $index => $subject)
                                            <tr>


                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $subject->subjects->subject_code }}</td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                            </tr>
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
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Professional Electives</div>
                            {{-- <div style="right:0;background-color:gray;" class="manual_bn">Limit : {{ $professional_limits }}
                            </div> --}}

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        {{-- <th>Select</th> --}}
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                    </tr>
                                </thead>
                                <tbody id="professional-table">
                                    @if (count($professional) > 0)
                                        @foreach ($professional as $index => $subject)
                                            <tr>
                                                {{-- <td>
                                                <input type="checkbox" class="subject-checkbox"
                                                    name="selectedProfessional[]" value="{{ $subject->id }}"
                                                    data-limit="{{ $professional_limits }}">
                                            </td> --}}
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $subject->subjects->subject_code }}</td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No Data Available..</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Open Electives</div>
                            {{-- <div style="right:0;background-color:gray;" class="manual_bn">Limit : {{ $open_limits }}</div> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        {{-- <th>Select</th> --}}
                                        <th>S.No</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Credits</th>
                                    </tr>
                                </thead>
                                <tbody id="open-table">


                                    @if (count($open) > 0)
                                        @foreach ($open as $index => $subject)
                                            <tr>
                                                {{-- <td>
                                                <input type="checkbox" class="subject-checkbox-2" name="selectedOpen[]"
                                                    value="{{ $subject->id }}" data-limit="{{ $open_limits }}">
                                            </td> --}}
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $subject->subjects->subject_code }}</td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No Data Available..</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Others</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
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

                                    @if (count($others) > 0)
                                        @foreach ($others as $index => $subject)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $subject->subjects->subject_code }}</td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No Data Available..</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display:flex;justify-content:space-between">
                            <div class="manual_bn">Honors Degree</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
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
                                <tbody id="honors-table">

                                    @if (count($honors) > 0)
                                        @foreach ($honors as $index => $subject)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $subject->subjects->subject_code }}</td>
                                                <td>{{ $subject->subjects->name }}</td>
                                                <td>{{ $subject->subjects->subject_type_id }}</td>
                                                <td>{{ $subject->subjects->credits }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No Data Available..</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($role_id == 11)
                    <div class="d-flex">
                        @if ($status == '0')
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6"
                                style="margin-bottom: 18px;margin-left: 25px;">
                                <span class="btn btn-danger">Waiting For Class Incharge to verify </span>
                            </div>
                        @endif

                        @if ($status == '1')
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6"
                                style="margin-bottom: 18px;margin-left: 25px;">
                                <span class="btn btn-info">Waiting For HOD to Approve</span>
                            </div>
                        @endif

                        @if ($status == '2')
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6"
                                style="margin-bottom: 18px;margin-left: 25px;">
                                <span class="btn btn-success">Approved By HOD</span>
                            </div>
                        @endif
                    </div>
                @endif
                @if (auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 1 || auth()->user()->roles[0]->id == 13)
                    <div class="d-flex">
                        @if (count($regular) > 0)
                            @if ($status == '0')
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6"
                                    style="margin-bottom: 18px;margin-left: 25px;">
                                    <span class="btn btn-warning">Class Incharge Not verified</span>
                                </div>
                            @endif
                            @if ($status == '1')
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1"
                                    style="margin-bottom: 18px; margin-left: 25px;">
                                    <button type="button" id="updater" name="updater" value="updater"
                                        class="btn btn-success" onclick="approve()">Approve</button>
                                </div>
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1"
                                    style="margin-bottom: 18px;margin-left: 25px;">
                                    <button type="button" id="editButton"
                                        onclick="window.location.href='{{ route('admin.subjectRegistration.edit', ['id' => $user_name_id]) }}'"
                                        class="btn btn-danger">Edit Preferences</button>
                                </div>
                            @endif
                            @if ($status == '2')
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1"
                                    style="margin-bottom: 18px;margin-left: 25px;">
                                    <button type="button" id="editButton"
                                        onclick="window.location.href='{{ route('admin.subjectRegistration.edit', ['id' => $user_name_id]) }}'"
                                        class="btn btn-danger">Edit Preferences</button>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
                @if ($type_id == 1 || $type_id == 3)
                    <div class="d-flex">
                        @if (count($regular) > 0)
                            @if ($status == '0')
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1"
                                    style="margin-bottom: 18px; margin-left: 25px;">

                                    <button type="button" id="updater" name="updater" value="updater"
                                        class="btn btn-success" onclick="approve()">Approve</button>
                                </div>
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1"
                                    style="margin-bottom: 18px;margin-left: 25px;">
                                    <button type="button" id="editButton"
                                        onclick="window.location.href='{{ route('admin.subjectRegistration.edit', ['id' => $user_name_id]) }}'"
                                        class="btn btn-danger">Edit Preferences</button>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    @parent
    <script>
        function approve() {
            let data = {
                'user_name_id': $('#user_name_id').val(),
                'status': 'Approved',
                // 'rejected_reason': null
            }
            $.ajax({
                url: '{{ route('admin.subjectRegistration.update') }}',
                type: 'POST',
                data: {
                    'data': data,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
@endsection
