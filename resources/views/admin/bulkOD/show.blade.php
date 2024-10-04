@extends('layouts.admin')
@section('content')
    <a class="btn btn-default" style="margin-bottom:1rem;" href="{{ route('admin.bulk-ods.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">Organized By : {{ $organized_by }}</div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">Department Name : {{ $dept_name }}</div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">Incharge : {{ $incharge }}</div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">Event Title : {{ $event_title }}</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">Event Category : {{ $event_category }}</div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">External Event Venue : {{ $ext_event_venue }}</div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">From Date : {{ $from_date }}</div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">To Date : {{ $to_date }}</div>
                </div>
            </div>
            <div class="row">
                @if (!empty($document))
                    <div>
                        {{-- {{ dd($document) }} --}}
                        @foreach ($document as $documentPath)
                            @php
                                $extension = pathinfo($documentPath, PATHINFO_EXTENSION);
                            @endphp

                            @if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif')
                                <a href="{{ asset($documentPath) }}" target="_blank">
                                    <img src="{{ asset($documentPath) }}"
                                        style="border-radius: 10px; padding: 5px; margin: 5px;" width="100px"
                                        height="100px" alt="">
                                </a>
                            @else
                                <a href="{{ asset($documentPath) }}" target="_blank"><strong>File</strong></a>
                            @endif
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">From Period : {{ $from_period }}</div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="manual_bn">To Period : {{ $to_period }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Student List
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover datatable text-center">
                <thead>
                    <tr>
                        <th>
                            S.No
                        </th>
                        <th>
                            Register No
                        </th>
                        <th>
                            Student Name
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Semester
                        </th>
                        <th>
                            Section
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($students) > 0)
                        @foreach ($students as $id => $student)
                            <tr>
                                <td>{{ $id + 1 }}</td>
                                <td>{{ $student->register_no }}</td>
                                <td>{{ $student->student != null ? $student->student->name : '' }}</td>
                                <td>{{ $student->dept_name }}</td>
                                <td>{{ $student->semester }}</td>
                                <td>{{ $student->section }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if (auth()->user()->roles[0]->id == 15 || auth()->user()->roles[0]->id == 1)
                @if ($status == 0)
                    <div style="text-align:right; padding-top:1rem;">
                        <input type="hidden" id="id" value="{{ $Id }}">
                        <span class="btn btn-danger" onclick="reject()">
                            Reject
                        </span>
                        <span class="btn btn-success" onclick="approve()">
                            Approve
                        </span>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function reject() {

            let id = $("#id").val();

            Swal.fire({
                title: 'Rejection Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (username) => {

                    $.ajax({
                        url: '{{ route('admin.bulk-ods.action') }}',
                        type: 'POST',
                        data: {
                            'id': id,
                            'action': 'reject',
                            'rejected_reason': username
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire(
                                'Done!',
                                'The Institue OD Rejected!',
                                'success'
                            )
                            location.reload();
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log(xhr.responseText);
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            })

        }

        function approve() {
            let id = $("#id").val();

            $.ajax({
                url: '{{ route('admin.bulk-ods.action') }}',
                type: 'POST',
                data: {
                    'id': id,
                    'action': 'approve'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    Swal.fire(
                        'Done!',
                        'You Approved the Institue OD!',
                        'success'
                    )
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert(xhr.responseText);
                }
            });

        }
    </script>
@endsection
