@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Edit Subject Type
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.subject_types.update', [$subjectType->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="regulation_id">Regulation</label>
                    <select class="form-control select2" name="regulation_id" id="regulation_id" required>

                        @foreach ($regulations as $id => $entry)

                                <option value="{{ $id }}" {{ $subjectType->regulation_id == $id ? 'selected':'' }}>{{ $entry }}</option>

                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="required" for="name">Subject Type</label>
                    <input style="text-transform:uppercase;" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', $subjectType->name) }}" oninput="this.value = this.value.toUpperCase()" required>
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

