@extends('layouts.studentHome')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Feed Back Forms</h5>
        </div>
        @if (count($training) > 0 || count($subject) > 0)
            <div class="card-body">

                <table class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>FeedBack</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subject as $item)
                            <tr>
                                <td>{{ $item['name'] }} - {{ $item['staff'] }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.student-feedback-forms.survey') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-primary">Take
                                            Feedback</button>
                                        <input type="hidden" name="feedback_id" id="feedback_id"
                                            value="{{ $item['feedback_id'] }}">
                                        <input type="hidden" name="datas" id="datas"
                                            value="{{ json_encode($item) }}">
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($training as $item)
                            <tr>
                                <td>{{ $item['feedback_name'] }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.student-feedback-forms.survey') }}" method="POST">
                                        @csrf
                                        <button class="btn btn-xs btn-primary" data-id="{{ $item['feedback_id'] }}">Take
                                            Feedback</button>
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
        @else
            <div class="card-body">
                Feedbacks not Available...
            </div>
        @endif

    </div>
@endsection
@section('scripts')
    @parent
@endsection
