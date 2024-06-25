@php
    session()->start();
    session(['appUser' => true]);
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body style="width:100%;height:100vh;background-color:#007bff;">
    <form action="{{ route('login') }}" method="POST" id="autoLogin">
        @csrf
        <input id="email" type="hidden" name="email" value="{{ $request->register_no }}">
        <input id="password" type="hidden" name="password" value="{{ $request->password }}">
    </form>

    <script>
        $(document).ready(function() {
            var myForm = $('#autoLogin');
            myForm.submit();
        });
    </script>
</body>

</html>
