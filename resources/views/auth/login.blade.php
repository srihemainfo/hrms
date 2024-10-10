@extends('layouts.app')
@section('content')
<STYle>
    /* * * * * General CSS * * * * */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        font-weight: 400;
        color: #666666;
        background: #eaeff4;
    }

    .wrapper {
        margin: 0 auto;
        width: 100%;
        /* max-width: 1140px; */
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .container {
        position: relative;
        border-radius: 4px;
        width: 100%;
        max-width: 600px;
        height: auto;
        display: flex;
        background: #ffffff;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    }

    .credit {
        position: relative;
        margin: 25px auto 0 auto;
        width: 100%;
        text-align: center;
        color: #666666;
        font-size: 16px;
        font-weight: 400;
    }

    .credit a {
        color: #222222;
        font-size: 16px;
        font-weight: 600;
    }

    /* * * * * Login Form CSS * * * * */
    h2 {
        /* margin: 0 0 15px 0; */
        font-size: 30px;
        font-weight: 700;
    }

    h2 img {
        width: 120px;
    }

    p {
        margin: 0 0 20px 0;
        font-size: 16px;
        font-weight: 500;
        line-height: 22px;
    }

    .btn {
        display: inline-block;
        padding: 7px 20px;
        font-size: 16px;
        letter-spacing: 1px;
        text-decoration: none;
        border-radius: 4px;
        color: #ffffff;
        outline: none;
        border: 1px solid #ffffff;
        transition: .3s;
        -webkit-transition: .3s;
    }

    .btn:hover {
        color: #2a5792;
        background: #ffffff;
    }

    .col-left,
    .col-right {
        width: 60%;
        padding: 45px 35px;
        display: flex;
    }

    .col-left {
        width: 60%;
        background: linear-gradient(to right, #9facbd, #4b87d2);
        -webkit-clip-path: polygon(98% 17%, 100% 34%, 98% 51%, 100% 68%, 98% 84%, 100% 100%, 0 100%, 0 0, 100% 0);
        clip-path: polygon(98% 17%, 100% 34%, 98% 51%, 100% 68%, 98% 84%, 100% 100%, 0 100%, 0 0, 100% 0);
        /* filter: drop-shadow(-1px 6px 3px rgba(50, 50, 0, 0.5)); */
        /* box-shadow: -1px 6px 3px rgba(50, 50, 0, 0.5); */


    }


    @media(max-width: 575.98px) {
        .container {
            flex-direction: column;
            box-shadow: none;
        }

        .col-left,
        .col-right {
            width: 100%;
            margin: 0;
            padding: 60px;
            -webkit-clip-path: none;
            clip-path: none;
        }

        .wrapper {
            position: relative;
            background-color: white;
            min-height: 100vh;

        }
    }

    .login-text {
        position: relative;
        width: 100%;
        color: #ffffff;
        text-align: center;
    }

    .login-form {
        position: relative;
        width: 100%;
        color: #666666;
    }

    .login-form p:last-child {
        margin: 0;
    }

    .login-form p a {
        color: #2a5792;
        font-size: 14px;
        text-decoration: none;
    }

    .login-form p:last-child a:last-child {
        float: right;
    }

    .login-form label {
        display: block;
        width: 100%;
        margin-bottom: 2px;
        letter-spacing: .5px;
    }

    .login-form p:last-child label {
        width: 60%;
        float: left;
    }

    .login-form label span {
        color: #ff574e;
        padding-left: 2px;
    }

    .login-form input {
        display: block;
        width: 100%;
        height: 40px;
        padding: 0 10px;
        font-size: 16px;
        letter-spacing: 1px;
        outline: none;
        border: 1px solid #cccccc;
        border-radius: 4px;
    }

    .login-form input:focus {
        border-color: #ff574e;
    }

    .login-form input.btn {
        color: #ffffff;
        background: #2a5792;
        border-color: #2a5792;
        outline: none;
        cursor: pointer;
    }

    .login-form input.btn:hover {
        color: #2a5792;
        background: #ffffff;
    }
</STYle>
<div class="wrapper">
    <div class="container">
        @if (session('error'))
        <div class="alert alert-danger" style="position:absolute;left:30%;top:-20%;z-index:99;">
            {{ session('error') }}
        </div>
        @endif
        <div class="col-left">
            <div class="login-text -MB2">
                <a href="{{ route('admin.home') }}">
                    <img src="{{ asset('adminlogo/school_menu_logo-removebg-preview.png') }}" alt="" height="65%" width="100%">
                </a>
                <p><br>Human Resource Management Software</p>
            </div>
        </div>
        <div class="col-right">
            <div class="login-form ">
                <h2>Login</h2>
                <div class="card-body login-card-body">

                    @if (session()->has('message'))
                    <p class="alert alert-info">
                        {{ session()->get('message') }}
                    </p>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder=" User ID" name="email" value="{{ old('email', null) }}">

                            @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group" id="parent">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ trans('global.login_password') }}">

                            <span class="">
                                <i class="fa fa-eye" id="togglePassword"></i>
                            </span>

                            @if ($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                            @endif
                        </div>

                        <style>
                            #parent {
                                position: relative;
                            }

                            #togglePassword {
                                position: absolute;
                                right: 5px;
                                top: 13px;

                            }
                        </style>


                        <div class="row">

                            <button type="submit" class="btn btn-primary btn-block btn-flat">
                                {{ trans('global.login') }}
                            </button>
                        </div>
                    </form>



                    <p class="mb-1">

                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        let passwordField = $('#password');
        let togglePassword = $('#togglePassword');

        togglePassword.click(function() {
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                togglePassword.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                togglePassword.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>ஃ
@endsection
