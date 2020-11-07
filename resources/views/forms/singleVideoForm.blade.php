@extends('layouts.app')

@section('title'){{ config('app.name') }}@endsection

@section('links')
    @auth 
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\default.css') }}" />
    @endauth
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\auth\login.css') }}" />
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\auth\register.css') }}" />
@endsection

@section('style')
    @auth
        <style type="text/css">
            .container {
                margin-top: unset;
                margin-bottom: unset;
                margin: 20% 0 5%;
                text-align: center;
                width: unset;
            }
            @media (min-width: 576px) {
                .container {
                    margin: 80px 10% 20px;
                }
            }
            @media (min-width: 768px) {
                .container {
                    margin: 90px 10% 20px;
                }
            }
            @media (min-width: 992px) {
                .container {
                    margin: 90px 10% 20px;
                }
            }
            @media (min-width:1200px) {
                .container {
                    margin: 90px 10% 20px;
                }
            }
        </style>
    @endauth
@endsection

@section('content')
    <div class="container standerd-direction">
        <div class="login-container  no-select">
            <header class="header-in-public-form">{{ __('title.Register-of-single-video') . ' ' . $video->pre_title }}</header>
            <p class="note-in-public-form">{{ __('masseges.video-is-free-and-when-published-we-well-send-it-in-email') }}</p>
            <div class="login-body">
                <form id="registerForm" method="POST" action="{{ route('save.public.form', $video->form_key) }}">
                    @csrf

                    <div>
                        <label for="first_name">{{ __('auth.first_name') }}</label>

                        <input id="first_name" type="text" class="login-input default-input transition @error('first_name') input-invalid @enderror" name="first_name" value="{{ ( auth('web')->check() ? auth()->user()->first_name : old('first_name') ) }}" placeholder="{{ __('auth.first_name') }}" required autocomplete="name" autofocus>

                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name">{{ __('auth.last_name') }}</label>

                        <input id="last_name" type="text" class="login-input default-input transition @error('last_name') input-invalid @enderror" name="last_name" value="{{ ( auth('web')->check() ? auth()->user()->last_name : old('last_name') ) }}" placeholder="{{ __('auth.last_name') }}" required autocomplete="name" >

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label for="email">{{ __('auth.E-Mail Address') }}</label>

                        <input id="email" type="email" class="login-input default-input transition @error('email') input-invalid @enderror" name="email" value="{{ ( auth('web')->check() ? auth()->user()->email : old('email') )  }}" placeholder="{{ __('auth.E-Mail Address') }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    @guest
                    <div class="form-check">
                        <input type="checkbox" name="privacy" id="privacy" {{ old('privacy') ? 'checked' : '' }}>

                        <label id="privacyLabel" for="privacy">
                            {{ __('auth.i-am-admition-on') }} <a>{{ __('auth.privacy') }}</a> {{ __('auth.and') }} <a>{{ __('auth.term') }}</a>
                        </label>
                    </div>
                   @endguest

                    <div class="login-footer">
                        <button type="submit" class="login-button">
                            {{ __('input.Register-in-public-form') }}
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
            privacyLabel = document.getElementById("privacyLabel");

        if(privacy != null && registerForm != null) {
            registerForm.onsubmit = function (e) {
                if(! privacy.checked) {
                    e.preventDefault();
                    privacyLabel.setAttribute('style', 'color: red; font-size: 18px;');
                }
            };
        }
    </script>
@endsection