@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.exam_cell_coordinators.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
<div class="card ">

    <div class="card-header text-center">
        <strong>Add Exam Cell Coordinators</strong>
    </div>
    <div class="card-body" style="min-height: 75vh">
<div class=" col-12 ">
    <form method="POST" action="{{ route("admin.exam_cell_coordinators.store") }}" enctype="multipart/form-data">
        @csrf

    <div class="form-group">
        <label for="mother_tongue_id">Select Staff</label>
        <select class="form-control select2 "
            name="staffName[]" id="staffName" multiple>
            <option value="">Please Select</option>
            @foreach ($combinedData as $id => $entry)
                <option value="{{ $entry->user_name_id ?? '' }}">{{ $entry->name ?? ''}}({{ $entry->StaffCode ?? '' }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="text-right" style="padding-top:5.5rem;">
            <button class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </div>
</form>
</div>
</div>
</div>
@endsection

