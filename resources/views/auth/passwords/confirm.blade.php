@extends('layouts.app')

@section('title'){{ __('auth.Confirm Password') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\auth\login.css') }}" />
@endsection

@section('content')
    <div class="container standerd-direction">
        <div class="login-container">
            <header>{{ __('auth.Confirm Password') }}</header>

            <div class="login-body">
                <div class="alert-success">{{ __('auth.Please confirm your password before continuing') }}</div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div>
                        <label for="password">{{ __('auth.Password') }}</label>

                        <input id="password" type="password" class="login-input default-input transition @error('password') input-invalid @enderror" name="password" placeholder="{{ __('auth.Password') }}" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="login-footer">
                        <button type="submit" class="login-button">
                            {{ __('auth.Confirm Password') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('auth.Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
