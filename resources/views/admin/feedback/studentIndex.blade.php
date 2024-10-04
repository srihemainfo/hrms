@extends('layouts.studentHome')
@section('content')
    @if ($subject)
        <div class="card">
            <div class="card-header">
                <h6><strong>Course FeedBack Forms</strong></h6>
            </div>
            @if (count($training) > 0 || count($subject) > 0)
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center" style="text-transform: capitalize;">
                        <thead>
                            <tr>
                                <th>FeedBack Name</th>
                                <th>Subject</th>
                                <th>Subject Staff</th>
                                <th>Due Date</th>
                                <th>Click To Provide Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subject as $item)
                                <tr>
                                    <td class="text-left">{{ $item['feedback_name'] }}</td>
                                    <td class="text-left">{{ $item['name'] }}</td>
                                    <td class="text-left">{{ $item['staff'] }}</td>
                                    <td>{{ $item['expiry_date'] }}</td>
                                    <td>
                                        <form action="{{ route('admin.student-feedback-forms.survey') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="newEditBtn" title="Take Feedback"><i
                                                    class="fas fa-pen-alt"></i></button>
                                            <input type="hidden" name="feedback_id" id="feedback_id"
                                                value="{{ $item['feedback_id'] }}">
                                            <input type="hidden" name="datas" id="datas"
                                                value="{{ json_encode($item) }}">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h6><strong>Course FeedBack Forms</strong></h6>
            </div>
            <div class="card-body text-center">
                Feedbacks not Available...
            </div>
        </div>
    @endif

    @if ($training)
        <div class="card">
            <div class="card-header">
                <h6><strong>Training FeedBack Forms</strong></h6>
            </div>
            @if (count($training) > 0 || count($subject) > 0)
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>FeedBack Name</th>
                                <th>Due Date</th>
                                <th>Click to provide feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($training as $item)
                                <tr>
                                    <td class="text-left">{{ $item['feedback_name'] }}</td>
                                    <td>{{ $item['expiry_date'] }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.student-feedback-forms.survey') }}" method="POST">
                                            @csrf
                                            <button class="newEditBtn" title="Take Feedback"
                                                data-id="{{ $item['feedback_id'] }}"><i
                                                    class="fas fa-pen-alt"></i></button>
                                            <input type="hidden" name="feedback_id" id="feedback_id"
                                                value="{{ $item['feedback_id'] }}">
                                            <input type="hidden" name="datas" id="datas"
                                                value="{{ json_encode($item) }}">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h6><strong>Training FeedBack Forms</strong></h6>
            </div>
            <div class="card-body text-center">
                Feedbacks not Available...
            </div>
        </div>
    @endif
@endsection
@section('scripts')
    @parent
    <script>
        function callAjax() {
            $.ajax({
                url: "{{ route('admin.student-feedback-forms.survey') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'reg': reg,
                    'ay': ay,
                    'course': course,
                    'batch': batch,
                    'sem': sem,
                    'subject_type': subject_type,
                    'sub_id': sub_id,
                    'theWeights': JSON.stringify(theWeights),
                    'totals': JSON.stringify(totals),
                    'exam_names': JSON.stringify(exam_names)
                },
                success: function(response) {
                    let status = response.status;
                    let data = response.data;
                    $(element).show();
                    $(next).hide();
                    if (status == true) {
                        Swal.fire('', data, 'success');
                        fetchSubjects();
                    } else {
                        Swal.fire('', data, 'error');
                    }
                }
            })
        }
    </script>
@endsection
