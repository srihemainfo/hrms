@extends('layouts.admin')
@section('content')
{{-- {{ dd($room) }} --}}
    <div class="card">
        <div class="card-header">
            Edit Room
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.rooms.updater', ['id' => $room->id]) }}"
                enctype="multipart/form-data">
               
                @csrf
                <div class="form-group">
                    <label for="block_id">Block</label>
                    <select class="form-control select2 {{ $errors->has('block') ? 'is-invalid' : '' }}" name="block_id"
                        id="block_id">
                        @foreach ($blocks as $id => $entry)
                            <option value="{{ $id }}"
                                {{ (old('block_id') ? old('block_id') : $room->block->id ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('block'))
                        <span class="text-danger">{{ $errors->first('block') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="room_no">Room No</label>
                    <input class="form-control {{ $errors->has('room_no') ? 'is-invalid' : '' }}" type="text"
                        name="room_no" id="room_no" value="{{ old('room_no', $room->room_no) }}" step="1">
                    @if ($errors->has('room_no'))
                        <span class="text-danger">{{ $errors->first('room_no') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="no_of_class_seats">No Of Seats(Class)</label>
                    <input class="form-control {{ $errors->has('no_of_class_seats') ? 'is-invalid' : '' }}" type="number"
                        name="no_of_class_seats" id="no_of_class_seats" value="{{ old('no_of_class_seats', $room->no_of_class_seats) }}"
                        step="1">
                    @if ($errors->has('no_of_class_seats'))
                        <span class="text-danger">{{ $errors->first('no_of_class_seats') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="no_of_exam_seats">No Of Seats(Exam)</label>
                    <input class="form-control {{ $errors->has('no_of_exam_seats') ? 'is-invalid' : '' }}" type="number"
                        name="no_of_exam_seats" id="no_of_exam_seats" value="{{ old('no_of_exam_seats', $room->no_of_exam_seats) }}"
                        step="1">
                    @if ($errors->has('no_of_exam_seats'))
                        <span class="text-danger">{{ $errors->first('no_of_exam_seats') }}</span>
                    @endif
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
