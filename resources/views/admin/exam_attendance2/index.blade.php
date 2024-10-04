@extends('layouts.admin')
@section('content')


<style>
    .modal-content {
    max-height: 400px; /* Adjust the height as needed */
    overflow-y: auto;
    
}
.modal-lg {
        
    width:1000px;
    }
</style>

<!-- Button to open the outer modal -->
<button class="btn btn-primary show-outer-modal-button" data-toggle="modal" data-target="#outerModal">Open Outer Modal</button>

<!-- Outer Modal -->
<div class="modal fade" id="outerModal" tabindex="-1" role="dialog" aria-labelledby="outerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="outerModalLabel">Outer Modal Title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>This is the content of the outer modal.</p>
                <!-- Button to open the inner modal -->
                <button class="btn btn-secondary show-inner-modal-button" data-toggle="modal" data-target="#innerModal">Open Inner Modal</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Inner Modal -->
<div class="modal fade" id="innerModal" tabindex="-1" role="dialog" aria-labelledby="innerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal-width" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="innerModalLabel">Inner Modal Title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="scrollable-content">
                    <div class="container m-auto">
                        <div class="row justify-content-center">
                            <div class="col-4">
                          <h4>  {{ $class->short_form ?? '' }}</h4> 
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                    <div class="card" id="stu_list_card" style="display:block;">
                        <div class="card-header bg-primary">
                            <div class="row text-center">
                                <div class="col-1">S.No</div>
                                <div class="col-4">Name</div>
                                <div class="col-3">Register No</div>
                                <div class="col-2">Present</div>
                                <div class="col-2">Absent</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="stu_list">
                        @php
                        $i = 0; // Initialize $i here
                        @endphp

                        @foreach ($studentList as $id => $Student)
                        <form class="stu_form">
                            <div class="row text-center p-1">
                                <div class="col-1">{{ $i + 1 }}</div>
                                <div class="col-4">{{ $Student->name ?? '' }}</div>
                                <div class="col-3">{{ $Student->register_no ?? '' }}
                                    <input type="hidden" name="{{ $Student->user_name_id ?? '' }}" value="{{ $Student->user_name_id ?? '' }}">
                                </div>
                                <div class="col-2">
                                    <input type="radio" class="attend_present" name="attendance_{{ $i }}" value="Present" checked>
                                </div>
                                <div class="col-2">
                                    <input type="radio" class="attend_absent" name="attendance_{{ $i }}" value="Absent">
                                </div>
                            </div>
                        </form>

                        @php
                        $i++; // Increment $i within the loop
                        @endphp

                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
   

</div>





<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // When the button with class 'show-outer-modal-button' is clicked
        $('.show-outer-modal-button').click(function() {
            // Show the outer modal
            $('#outerModal').modal('show');
        });

        // When the button with class 'show-inner-modal-button' is clicked
        $('.show-inner-modal-button').click(function() {
            $('#outerModal').modal('hide');
            // Show the inner modal
            $('#innerModal').modal('show');
        });
    });
</script>







<!-- //// -->
{{-- <div class="card" id="stu_list_card" style="display:block;">
    <div class="card-header bg-primary">
        <div class="row text-center">
            <div class="col-1">S.No</div>
            <div class="col-4">Name</div>
            <div class="col-3">Register No</div>
            <div class="col-2">Present</div>
            <div class="col-2">Absent</div>
        </div>
    </div>
   
    <div class="card-body" id="stu_list">
        @php
        $i = 0; // Initialize $i here
        @endphp

        @foreach ($studentList as $id => $Student)
        <form class="stu_form">
            <div class="row text-center p-1">
                <div class="col-1">{{ $i + 1 }}
</div>
<div class="col-4">{{ $Student->name ?? '' }}</div>
<div class="col-3">{{ $Student->register_no ?? '' }}
    <input type="hidden" name="{{ $Student->user_name_id ?? '' }}" value="{{ $Student->user_name_id ?? '' }}">
</div>
<div class="col-2">
    <input type="radio" class="attend_present" name="attendance_{{ $i }}" value="Present" checked>
</div>
<div class="col-2">
    <input type="radio" class="attend_absent" name="attendance_{{ $i }}" value="Absent">
</div>
</div>
</form>

@php
$i++; // Increment $i within the loop
@endphp

@endforeach
</div>
</div>--}}
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // When the button with class 'show-modal-button' is clicked
        $('.show-modal-button').click(function() {
            // Show the modal
            $('#myModal').modal('show');
        });
    });
</script>