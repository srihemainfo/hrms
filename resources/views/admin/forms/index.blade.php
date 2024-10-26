@php
    $role_id = auth()->user()->roles[0]->id;
    if ($role_id == 1 || $role_id == 5) {
        $key = 'layouts.admin';
    } else {
        $key = 'layouts.staffs';
    }
@endphp
@extends($key)
