@extends('layouts.app')

@section('title'){{ config('app.name') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\default.css') }}" />
@endsection


@section('content')
    <div class="container">
        <div class="document">
            this is privacy of web site
        </div>
    </div>
@endsection