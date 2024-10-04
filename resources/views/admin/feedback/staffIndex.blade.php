@extends('layouts.teachingStaffHome') @section('content') <div class="card">
        <div class="card-header">
            <h5>Feed Back Forms</h5>
        </div>
        @if ($data)
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>FeedBack</th>
                            <th>Click to Provide Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($data) > 0)
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->feedback->name }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.staff-feedback-form.survey') }}" method="POST"> @csrf
                                            <button type="submit" class="newEditBtn" title="Take Feedback"><i
                                                    class="fas fa-pen-alt"></i></button>
                                            <input type="hidden" name="feedback_id" id="feedback_id"
                                                value="{{ $item->feedback_id }}">
                                            <input type="hidden" name="datas" id="datas"
                                                value="{{ json_encode($item) }}">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="2"> Feedbacks not Available... </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body"> Feedbacks not Available... </div>
        @endif
    </div> @endsection @section('scripts')
    @parent
@endsection
