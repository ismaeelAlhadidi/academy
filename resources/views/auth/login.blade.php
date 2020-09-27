@extends('layouts.app')

@section('title'){{ __('title.adminLogin') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\auth\login.css') }}" />
@endsection

@section('content')
<div class="container standerd-direction">
    <div class="login-container">
        <header>{{ __('title.adminLogin') }}</header>
        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="email">{{ __('auth.E-Mail Address') }}</label>

                    <input id="email" type="email" class="login-input default-input transition @error('email') input-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('auth.E-Mail Address') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div>
                    <label for="password">{{ __('auth.Password') }}</label>

                    <input id="password" type="password" class="login-input default-input transition @error('password') input-invalid @enderror" name="password" placeholder="{{ __('auth.Password') }}" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('auth.Remember Me') }}
                    </label>
                </div>

                <div class="login-footer">
                    <button type="submit" class="login-button">
                        {{ __('auth.Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            {{ __('auth.Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
