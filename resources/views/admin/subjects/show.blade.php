@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.subject.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.subjects.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                {{ trans('cruds.subject.fields.id') }}
                            </th>
                            <td>
                                {{ $subject->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Department
                            </th>
                            <td>
                                {{ $subject->department->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Course
                            </th>
                            <td>
                                {{ $subject->course->short_form ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Semester
                            </th>
                            <td>
                                {{ $subject->semester->semester ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Subject Code
                            </th>
                            <td>
                                {{ $subject->subject_code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Subject Name
                            </th>
                            <td>
                                {{ $subject->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Regulation
                            </th>
                            <td>
                                {{ $subject->regulation->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Subject Type
                            </th>
                            <td>
                                {{ $subject->subject_type->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Subject Category
                            </th>
                            <td>
                                {{ $subject->subject_category->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Lecture
                            </th>
                            <td>
                                {{ $subject->lecture }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Tutorial
                            </th>
                            <td>
                                {{ $subject->tutorial }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Practical
                            </th>
                            <td>
                                {{ $subject->practical }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total Contact Periods
                            </th>
                            <td>
                                {{ $subject->contact_periods }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Credits
                            </th>
                            <td>
                                {{ $subject->credits }}
                            </td>
                        </tr>

                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.subjects.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let rejected_reason = document.getElementById('rejected_reason');

        let id = document.getElementById('id');
        let status = document.getElementById('status');

        function reject() {
            Swal.fire({
                title: 'Reject Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (username) => {

                    let data = {
                        'id': id.value,
                        // 'leave_type': leave_type.value,
                        'status': '2',
                        'rejected_reason': username
                    }
                    $.ajax({
                        url: '{{ route('admin.subjects.statusUpdate') }}',
                        type: 'POST',
                        data: {
                            'data': data,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire(
                                'Done!',
                                'You Rejected the Leave!',
                                'success'
                            )
                            location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status) {
                                if (jqXHR.status == 500) {
                                    Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                                } else {
                                    Swal.fire('', jqXHR.status, 'error');
                                }
                            } else if (textStatus) {
                                Swal.fire('', textStatus, 'error');
                            } else {
                                Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                    "error");
                            }
                        }
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            })

        }

        function approve() {
            let data = {
                'id': id.value,
                // 'status': status.value,
                'status': '1',
                'rejected_reason': null
            }
            $.ajax({
                url: '{{ route('admin.subjects.statusUpdate') }}',
                type: 'POST',
                data: {
                    'data': data,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    Swal.fire(
                        'Done!',
                        'You Approved the Leave!',
                        'success'
                    )
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                            "error");
                    }
                }
            });


        }
    </script>
@endsection
