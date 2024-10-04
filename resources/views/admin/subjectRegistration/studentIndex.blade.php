@extends('layouts.studentHome')
@section('content')

    @php
        $professional_limits = $open_limits = $others_limits = 0;
       
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
    <a class="btn btn-default" style="margin-bottom:17px;" href="">
        {{ trans('global.back_to_list') }}
    </a>
    <form action="{{ route('admin.subjectRegistration.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card" id="open_form">
            <div class="card-header text-center text-primary"><strong class="fs-1">My Subjects</strong></div>
            <div class="card-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-5 col-lg-4 text-center">
                                <div class="manual_bn ">Regular Subjects</div>
                            </div>
                            <div class="col-12 col-md-1 col-lg-2"></div>
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="text-center">
                                    <div class="manual_bn" style="background-color:gray;">All Subjects Are Mandatory</div>
                                </div>
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

                                                <input type="hidden" name="selectedSubjects[]" value="{{ $subject->id }}">

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
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit :
                                {{ $professional_limits }}
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Select</th>
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
                                                <td>
                                                    @if (isset($professional_limits) && $professional_limits != 0)
                                                        <input type="checkbox" class="subject-checkbox"
                                                            name="selectedProfessional[]" value="{{ $subject->id }}"
                                                            data-limit="{{ $professional_limits }}">
                                                    @endif
                                                </td>

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
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit : {{ $open_limits }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Select</th>
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
                                                <td>
                                                    @if (isset($open_limits) && $open_limits != 0)
                                                        <input type="checkbox" class="subject-checkbox-2"
                                                            name="selectedOpen[]" value="{{ $subject->id }}"
                                                            data-limit="{{ $open_limits }}">
                                                    @endif

                                                </td>
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
                            <div style="right:0;background-color:gray;" class="manual_bn">Limit :
                                {{ $others_limits }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Select</th>
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
                                                <td>
                                                    @if (isset($others_limits) && $others_limits != 0)
                                                        <input type="checkbox" class="subject-checkbox-3"
                                                            name="selectedOthers[]" value="{{ $subject->id }}"
                                                            data-limit="{{ $others_limits }}">
                                                    @endif

                                                </td>
                                                <td>{{ $index }}</td>
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

                @if ($statusCheck == 'ok')
                    <div class="card-header text-center text-primary">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                @endif

            </div>
        </div>
    </form>
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.subject-checkbox').on('change', function() {
                var limit = parseInt($(this).data('limit'));
                var checkedCount = $('.subject-checkbox:checked').length;
                $('.subject-checkbox').not(':checked').prop('disabled', checkedCount >= limit);
            });
            $('.subject-checkbox-2').on('change', function() {
                var limit = parseInt($(this).data('limit'));
                var checkedCount = $('.subject-checkbox-2:checked').length;
                $('.subject-checkbox-2').not(':checked').prop('disabled', checkedCount >= limit);
            });
            $('.subject-checkbox-3').on('change', function() {
                var limit = parseInt($(this).data('limit'));
                var checkedCount = $('.subject-checkbox-3:checked').length;
                $('.subject-checkbox-3').not(':checked').prop('disabled', checkedCount >= limit);
            });
        });
    </script>
@endsection
