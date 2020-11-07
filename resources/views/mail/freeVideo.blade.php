@extends('mail.layouts.defaultMail')
@section('content')
    <h1>{{ __('mail.welcome') . ' ' . $name }}</h1>
    <p>{{ $description }}</p>
    <video src="{{ $videoSrc }}"></video>
@endsection