@extends('layouts.app')

@section('title'){{ __('title.Register') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\auth\login.css') }}" />
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\auth\register.css') }}" />
@endsection

@section('content')
<div class="container standerd-direction">
    <div class="login-container  no-select">
        <header>{{ __('title.Register') }}</header>

        <div class="login-body">
            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    <label for="first_name">{{ __('auth.first_name') }}</label>

                    <input id="first_name" type="text" class="login-input default-input transition @error('first_name') input-invalid @enderror" name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('auth.first_name') }}" required autocomplete="name" autofocus>

                    @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <label for="second_name">{{ __('auth.second_name') }}</label>

                    <input id="second_name" type="text" class="login-input default-input transition @error('second_name') input-invalid @enderror" name="second_name" value="{{ old('second_name') }}" placeholder="{{ __('auth.second_name') }}" required autocomplete="name" >

                    @error('second_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <label for="last_name">{{ __('auth.last_name') }}</label>

                    <input id="last_name" type="text" class="login-input default-input transition @error('last_name') input-invalid @enderror" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('auth.last_name') }}" required autocomplete="name" >

                    @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <label for="email">{{ __('auth.E-Mail Address') }}</label>

                    <input id="email" type="email" class="login-input default-input transition @error('email') input-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('auth.E-Mail Address') }}" required autocomplete="email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <label for="password">{{ __('auth.Password') }}</label>

                    <input id="password" type="password" class="login-input default-input transition @error('password') input-invalid @enderror" name="password" placeholder="{{ __('auth.Password') }}" required autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <label for="password-confirm">{{ __('auth.Confirm Password') }}</label>

                    <input id="password-confirm" type="password" class="login-input default-input transition" name="password_confirmation" placeholder="{{ __('auth.Confirm Password') }}" required autocomplete="new-password">
                </div>
                
                <div class="form-check">
                    <input type="checkbox" name="privacy" id="privacy" {{ old('privacy') ? 'checked' : '' }}>

                    <label id="privacyLabel" for="privacy">
                        {{ __('auth.i-am-admition-on') }} <a>{{ __('auth.privacy') }}</a> {{ __('auth.and') }} <a>{{ __('auth.term') }}</a>
                    </label>
                </div>
                
                <div class="login-footer">
                    <button type="submit" class="login-button">
                        {{ __('auth.Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript">
       var registerForm = document.getElementById("registerForm"),
            privacy = document.getElementById("privacy"),
            privacyLabel = document.getElementById("privacyLabel"),
            password = document.getElementById("password"),
            passwordConfirm = document.getElementById("password-confirm");
        if(privacy != null && registerForm != null) {
            registerForm.onsubmit = function (e) {
                if(! privacy.checked) {
                    e.preventDefault();
                    privacyLabel.setAttribute('style', 'color: red; font-size: 18px;');
                }
                if(! isValid()) e.preventDefault();
            };
        }
        function isValid() {
            if(password.value != passwordConfirm.value){
                password.setAttribute('class', 'login-input default-input transition input-invalid');
                passwordConfirm.setAttribute('class', 'login-input default-input transition input-invalid');
                return false;
            }
            return true;
        }
    </script>
@endsection