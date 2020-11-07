@extends('mail.layouts.defaultMail')
@section('content')
    <h1>{{ $header }}</h1>
    <h3>{{ $title }}</h3>
    <p>{{ $msg }}</p>
@endsection