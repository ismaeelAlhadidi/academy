@extends('layouts.app')

@section('title'){{ __('auth.Verify Your Email Address') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\auth\login.css') }}" />
@endsection

@section('style')
    <style type="text/css">
        .login-button {
            width: unset;
            padding-left: 5px;
            padding-right: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container standerd-direction">
        <div class="login-container">
            <header>{{ __('auth.Verify Your Email Address') }}</header>

            <div class="login-body">
                @if (session('resent'))
                    <div class="alert-success" role="alert">
                        {{ __('auth.A fresh verification link has been sent to your email address.') }}
                    </div>
                    <br/><br/>
                @endif

                <div>{{ __('auth.Before proceeding, please check your email for a verification link.') }}</div>
                <br/>
                <div>{{ __('auth.If you did not receive the email') }}</div>
                <form class="login-footer" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="login-button">{{ __('auth.click here to request another') }}</button>.
                </form>
            </div>
        </div>
    </div>
@endsection
