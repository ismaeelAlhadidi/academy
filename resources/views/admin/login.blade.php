@extends('admin\layouts\adminpanel')

@section('title') 
    {{ __('title.adminLogin') }}
@stop
@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\login.css') }}"/>
@stop
@section('content')
    <div class="contianer">
        <form method="post" class="login-form" action="{{ route('admin.login') }}" >
            @csrf
            @method('POST')
            <header> {{ __('headers.admin-login-form') }} </header>
            <section><img src="{{ asset('images/static/adminLogin.png') }}" /></section>
            <section>
                <input id="usernameInput"  type="text" {{ session()->has('invaild') ? 'class=input-invalid' :  '' }} name="email" value="{{ old('email') }}" placeholder="{{ __('input.email') }}" required />
                <input id="passwordInput" type="password" {{ session()->has('invaild') ? 'class=input-invalid' :  '' }} name="password" placeholder=" {{ __('input.password') }}" required />
                <input type="submit" formmethod="post" value="{{ __('input.loginButton') }}" />
            </section>
        </form>
    </div>
    @if(session()->has('invaild')) @php session()->remove('invaild') @endphp @endif
@endsection
@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js\admin\login.js') }}"></script>
@endsection