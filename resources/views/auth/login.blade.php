@extends('layouts.app')
@section('content')
    <style>
        .form-box {
            height: 420px;
            width: 380px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 2px 12px #e90c0c;
        }

        .form-btn {
            border: none;
            text-align: center;
            background: linear-gradient(246deg, rgb(89, 219, 236), rgb(243, 78, 49) 100%);
            color: white;
        }

        .input-box-1,
        .input-box-2 {
            border-radius: 10px;
            border: 1px solid black;
            padding-right: 20px;
            padding-left: 20px;
            outline: none;
            width: 240px;
        }

        .form-box-inside {
            margin-top: 40px;
        }

        body {
            background: linear-gradient(45deg, #ff7908, #0986c8) !important;
        }
    </style>

    <div class="container mt-5">
        @if (session('error'))
            <div class="alert alert-danger" style="position:absolute;left:30%;top:-20%;z-index:99;">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
            <div class="col d-flex justify-content-center align-items-center mt-5">
                <div class="form-box">
                    <div class="form-box-inside">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-center mt-2">
                                    <a href="{{ route('admin.home') }}">
                                        <img src="{{ asset('adminlogo/school_menu_logo-removebg-preview.png') }}"
                                            alt="" width="250px;" style="margin-left: 40px;">
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if (session()->has('message'))
                            <p class="alert alert-info">
                                {{ session()->get('message') }}
                            </p>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-center text-muted mt-3">
                                        <b style="color:black; font-size: 20px !important;">Human Resource Management</b>
                                    </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex justify-content-center mt-4">
                                        <input id="email" type="text"
                                            class="form-control text-center input-box-1 py-1{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            required autocomplete="email" autofocus placeholder="User ID or Email" name="email"
                                            value="{{ old('email', null) }}">
                                    </div>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback d-block text-center mt-2">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <div class="d-flex justify-content-center mt-4">
                                        <input id="password" type="password"
                                            class="form-control text-center input-box-2 py-1 {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            name="password" required placeholder="{{ trans('global.login_password') }}">


                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex justify-content-center mt-4">
                                        <button class="px-4 pb-2 btn btn-primary" style="border-radius: 10px">
                                            {{ trans('global.login') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
