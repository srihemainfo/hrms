@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} Room
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.rooms.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                ID
                            </th>
                            <td>
                                {{ $room->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Block
                            </th>
                            <td>
                                {{ $room->block->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Room No
                            </th>
                            <td>
                                {{ $room->room_no }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                No Of Seats (Class)
                            </th>
                            <td>
                                {{ $room->no_of_class_seats }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                No Of Seats (Exam)
                            </th>
                            <td>
                                {{ $room->no_of_exam_seats }}
                            </td>
                        </tr>

                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.rooms.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
