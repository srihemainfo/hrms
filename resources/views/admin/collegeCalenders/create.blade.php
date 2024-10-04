@extends('layouts.admin')
@section('content')
    <div class="pl-2 pb-2">
        <a class="btn btn-default" href="{{ route('admin.college-calenders.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header">
         Create Office Calendar
        </div>


        <div class="card-body">
            <form method="POST" action="{{ route('admin.college-calenders.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="academic_year" class="required">
                        {{ trans('cruds.courseEnrollMaster.fields.academic') }}</label>
                    <select class="form-control select2 {{ $errors->has('academic_year') ? 'is-invalid' : '' }}"
                        name="academic_year" id="academic_id">
                        <option value="">Select Academic Year</option>
                        @foreach ($academics as $id => $entry)
                            <option value="{{ $entry }}">
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('academic_year'))
                        <span class="text-danger">{{ $errors->first('academic_year') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.academic_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="from_date" class="required">{{ trans('cruds.collegeCalender.fields.from_date') }}</label>
                    <input type="text" class="form-control date" name="from_date" id="from_date" value="" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="to_date" class="required">{{ trans('cruds.collegeCalender.fields.to_date') }}</label>
                    <input type="text" class="form-control date" name="to_date" id="to_date" value="" autocomplete="off">
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="saturday" value="1" id="exampleCheckbox">
                    <label class="form-check-label" for="saturday">
                        All Saturday Holiday
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="sunday" value="1" id="exampleCheckbox">
                    <label class="form-check-label" for="sunday">
                        All Sunday Holiday
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="monday" value="1" id="exampleCheckbox">
                    <label class="form-check-label" for="monday">
                        All Monday Holiday
                    </label>
                </div>

                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $("#from_date").blur(function() {
                let ay = $("#academic_id").val();
                if(ay == ''){
                    alert('Please Choose Academic Year First..')
                    $("#from_date").val('');
                }else{
                    let from_date =  $("#from_date").val();
                    let ay_year = ay.split('-');
                    let from_year = from_date.split('-');
                    if(from_year[0] < ay_year[0] || from_year[0] > ay_year[1]){
                        alert('Please Choose the Date in Between the Academic Year')
                            $("#from_date").val('');
                    }
                    // else{
                    //     if(from_year[1] < '06'){
                    //         alert('The Academic Should Be Start From June')
                    //         $("#from_date").val('');
                    //     }
                    // }
                    console.log(from_date,ay_year)
                }
            });

            $("#to_date").blur(function() {
                let ay = $("#academic_id").val();
                if(ay == ''){
                    alert('Please Choose Academic Year First..')
                    $("#to_date").val('');
                }else{
                    let to_date =  $("#to_date").val();
                    let ay_year = ay.split('-');
                    let to_year = to_date.split('-');
                    console.log(ay_year,to_year)
                    if(to_year[0] < ay_year[0] || to_year[0] > ay_year[1]){
                        alert('Please Choose the Date in Between the Academic Year')
                            $("#to_date").val('');
                    }
                }
            });
        })
    </script>
@endsection
