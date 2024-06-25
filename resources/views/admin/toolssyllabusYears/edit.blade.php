@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Edit Regulation
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.toolssyllabus-years.update', [$toolssyllabusYear->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">Regulation Name</label>
                <select class="form-control select2  {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="name" value="{{ old('name', $toolssyllabusYear->name) }}" required>
                    <option value="">Choose the Year</option>
                    @foreach ($year as $id => $name)
                    <option value="{{ $name }}" {{ $name == $toolssyllabusYear->name ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                    <!-- <option value="{{ $name }}"> {{ $name }} </option> -->
                    @endforeach
                </select>
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <!-- <div class="form-group">
                    <label class="required" for="name">Regulation Name</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', $toolssyllabusYear->name) }}" required>
                </div> -->

            <div class="form-group">
                <label class="required" for="year">Effective From</label>
                <input type="month" class="form-control" name="year" value="{{ $toolssyllabusYear->year }}" required>
            </div>
            <div class="form-group">
                <label class="required" for="frame_by">Framed By</label>
                <select class="form-control select2" name="frame_by" id="frame_by" required>
                    <option value="" {{ $toolssyllabusYear->frame_by == '' ? 'selected':'' }}>Please Select</option>
                    <option value="SVCET" {{ $toolssyllabusYear->frame_by == 'SVCET' ? 'selected':'' }}>SVCET</option>
                    <option value="Anna University" {{ $toolssyllabusYear->frame_by == 'Anna University' ? 'selected':'' }}>Anna University</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    window.onload = function() {
        $("#frame_by").select2();
    }
</script>
@endsection
