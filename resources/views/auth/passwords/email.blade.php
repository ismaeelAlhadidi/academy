@extends('layouts.app')

@section('title'){{ __('passwords.Reset Password') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\auth\login.css') }}" />
@endsection

@section('style')
    <style type="text/css">
        .login-button {
            width: 120px;
        }
    </style>
@endsection

@section('content')
    <div class="container standerd-direction">
        <div class="login-container">
            <header>{{ __('passwords.Reset Password') }}</header>

            <div class="login-body">
                @if (session('status'))
                    <div class="alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div>
                        <label for="email">{{ __('auth.E-Mail Address') }}</label>

                        <input id="email" type="email" class="login-input default-input transition @error('password') input-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('auth.E-Mail Address') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="login-footer">
                        <button type="submit" class="login-button">
                            {{ __('passwords.Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
