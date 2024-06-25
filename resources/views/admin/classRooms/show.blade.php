@extends('layouts.admin')
@section('content')
    {{-- {{ dd($classRoom) }} --}}
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.classRoom.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.class-rooms.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                {{ trans('cruds.classRoom.fields.id') }}
                            </th>
                            <td>
                                {{ $classRoom->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Class Name
                            </th>
                            <td>

                                {{ $classRoom->enroll_master->enroll_master_number }}

                            </td>
                        </tr>
                        <tr>
                            <th>
                                Class Incharge
                            </th>
                            <td>

                                {{ $classRoom->teaching_staff->name }}  ({{ $classRoom->teaching_staff->employID }})

                            </td>
                        </tr>
                        <tr>
                            <th>
                                Class In Short Form
                            </th>
                            <td>
                                {{ $classRoom->short_form }}
                            </td>
                        </tr>
                        {{-- <tr>
                            <th>
                                {{ trans('cruds.classRoom.fields.block') }}
                            </th>
                            <td>
                                {{ $classRoom->block->name ?? '' }}
                            </td>
                        </tr> --}}
                        {{-- <tr>
                            <th>
                                {{ trans('cruds.classRoom.fields.type') }}
                            </th>
                            <td>
                                {{ $classRoom->type }}
                            </td>
                        </tr> --}}
                        {{-- <tr>
                            <th>
                                {{ trans('cruds.classRoom.fields.room_no') }}
                            </th>
                            <td>
                                {{ $classRoom->room_no }}
                            </td>
                        </tr> --}}
                        {{-- @if (count($get_roomSeats) > 0)
                            @foreach ($get_roomSeats as $seats)
                                <tr>
                                    <th>
                                        No Of Seats (Class)
                                    </th>
                                    <td>
                                        {{ $seats->no_of_class_seats }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                       No Of Seats (Exam)
                                    </th>
                                    <td>
                                        {{ $seats->no_of_exam_seats }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif --}}
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.class-rooms.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
