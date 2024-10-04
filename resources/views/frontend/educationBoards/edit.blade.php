@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.educationBoard.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.education-boards.update", [$educationBoard->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="education_board">{{ trans('cruds.educationBoard.fields.education_board') }}</label>
                            <input class="form-control" type="text" name="education_board" id="education_board" value="{{ old('education_board', $educationBoard->education_board) }}">
                            @if($errors->has('education_board'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('education_board') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationBoard.fields.education_board_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection