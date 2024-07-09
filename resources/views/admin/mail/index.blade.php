@extends('layouts.admin')
@section('content')
    <div class="mail">
        
        <form action="{{ route('admin.mail.send') }}" method="POST">
            @csrf
            <input type="email" name="email" id="email">
            <textarea name="message" id="message" cols="30" rows="3"></textarea>
            <input type="submit" name="submit" id="" value="Submit">
        </form>
    </div>
@endsection
