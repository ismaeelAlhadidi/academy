@extends('mail.layouts.defaultMail')
@section('content')
    <h3 style="text-align: right; direction: rtl;">{{ $header }}</h3>
    <p style="text-align: right; direction: rtl;">{{ $msg }}</p>
@endsection