@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Create Subject Type
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.subject_types.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="regulation_id">Regulation</label>
                    <select class="form-control select2" name="regulation_id" id="regulation_id" required>
                      <option value="">Select Regulation</option>
                        @foreach ($regulations as $id => $entry)
                        <option value="{{ $id }}">{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="required" for="name">Subject Type</label>
                    <input style="text-transform:uppercase;" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', '') }}" oninput="this.value = this.value.toUpperCase()" required placeholder="Enter the Subject Type">
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

