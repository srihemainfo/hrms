@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.foundations.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            Create Foundation
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.foundations.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Foundation Name</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', '') }}">
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
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
