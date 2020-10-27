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
            <header class="header-in-public-form">{{ $massege }}</header>
            <p class="note-in-public-form">{{ __('masseges.to-watch-more-go-to-our-website') }}</p>
            <div class="login-body">
                <div class="login-footer">
                        <button type="button" class="login-button" onclick="window.location.href = window.location.origin;">
                            {{ __('masseges.go-to-our-website') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection