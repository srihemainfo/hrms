@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Lab
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tool-lab.update",[$lab->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label> Department </label>
                <input type="hidden" name="id" value="{{ $lab->id }}">
                <select class="form-control select2 {{ $errors->has('dept') ? 'is-invalid' : '' }}" name="dept" id="dept">
                    @foreach($departments as $id => $entry)
                    <option value="{{ $id }}" {{ (old('dept') ? old('dept') : $lab->department->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('dept'))
                    <span class="text-danger">{{ $errors->first('dept') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="lab_name">Lab Name</label>
                <input class="form-control {{ $errors->has('lab_name') ? 'is-invalid' : '' }}" type="text" name="lab_name" id="lab_name" value="{{ $lab->lab_name }}">
                @if($errors->has('lab_name'))
                    <span class="text-danger">{{ $errors->first('lab_name') }}</span>
                @endif
            </div>
            {{-- <div class="form-group">
                 <label> Lab Incharge </label>
                <select class="form-control select2 {{ $errors->has('lab_incharge') ? 'is-invalid' : '' }}" name="lab_incharge" id="lab_incharge">
                    @foreach($teaching_staffs as $id => $entry)
                        <option value="{{ $id }}" {{ old('lab_incharge') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('lab_incharge'))
                    <span class="text-danger">{{ $errors->first('lab_incharge') }}</span>
                @endif
            </div> --}}
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
