@php
    $role_id = auth()->user()->roles[0]->id;
    $type_id = auth()->user()->roles[0]->type_id;
    if ($role_id == 11) {
        $key = 'layouts.studentHome';
    } elseif ($type_id == 1 || $type_id == 3) {
        $key = 'layouts.teachingStaffHome';
    } elseif ($type_id == 2 || $type_id == 4 || $type_id == 5) {
        $key = 'layouts.non_techStaffHome';
    }else{
        $key = 'layouts.admin';
    }
@endphp
@extends($key)

@section('content')
    <div class="content">
        <div class="row">
            <p class="col-lg-12">
                @yield('title')
            </p>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <p>
                    <a href="{{ route('admin.messenger.createTopic') }}" class="btn btn-primary btn-block">
                        {{ trans('global.new_message') }}
                    </a>
                </p>
                <div class="list-group">
                    <a href="{{ route('admin.messenger.index') }}" class="list-group-item">
                        {{ trans('global.all_messages') }}
                    </a>
                    <a href="{{ route('admin.messenger.showInbox') }}" class="list-group-item">
                        @if ($unreads['inbox'] > 0)
                            <strong>
                                {{ trans('global.inbox') }}
                                ({{ $unreads['inbox'] }})
                            </strong>
                        @else
                            {{ trans('global.inbox') }}
                        @endif
                    </a>
                    <a href="{{ route('admin.messenger.showOutbox') }}" class="list-group-item">
                        @if ($unreads['outbox'] > 0)
                            <strong>
                                {{ trans('global.outbox') }}
                                ({{ $unreads['outbox'] }})
                            </strong>
                        @else
                            {{ trans('global.outbox') }}
                        @endif
                    </a>
                </div>
            </div>
            <div class="col-lg-9">
                @yield('messenger-content')
            </div>
        </div>
    </div>
@stop
