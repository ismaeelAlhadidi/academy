@extends('layouts.app')

@section('title'){{ $user->first_name . ' ' . $user->last_name }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\default.css') }}" />
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\profile.css') }}" />
@endsection

@section('content')
    <div class="user-data-content section-borders">
        <section class="user-image no-select">
            <div>
                <img id="imageElement" src="{{ asset($user->image) }}"/>
                @if($haveThisProfile)
                <div id="editImageButton"><span>{{ __('masseges.update') }} <span class="fa"> &#xf03e;</span></span></div>
                <input id="inputFileElement" type="file" accept=".png,.jpg,.tif,.gif" style="display: none !important;"/>
                @endif
            </div>
        </section>
        <section class="user-data">
            <form id="userDataForm">
                <div><span class="no-select">{{ __('auth.first_name') }}</span><input id="firstNameInput" type="text" value="{{ $user->first_name }}" disabled /></div>
                <div><span class="no-select">{{ __('auth.second_name') }}</span><input id="secondNameInput" type="text"  value="{{ $user->second_name }}" disabled /></div>
                <div><span class="no-select">{{ __('auth.last_name') }}</span><input id="lastNameInput" type="text"  value="{{ $user->last_name }}" disabled /></div>
                <div><span class="no-select">{{ __('auth.E-Mail Address') }}</span><input id="emailInput" type="text" value="{{ $user->email }}" disabled /></div>
                @if($haveThisProfile)
                <footer class="no-select"><div><input id="editDataButton" type="button" value="{{ __('masseges.update') }}"/></div><div><a href="{{ route('password.request') }}">{{ __('passwords.Reset Password') }}</a></div></footer>
                @endif
            </form>
        </section>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js\authenticated\profile.js') }}"></script>
    <script type="text/javascript" lang="javascript">
        var lang = {
            'edit': '{{ __('masseges.update') }}',
            'save': '{{ __('masseges.save') }}',
            'MassegeOfErrorInSave': '{{ __('masseges.error-in-save-data') }}',
            'MassegeOfErrorInChangeImage': '{{ __('masseges.error-in-save-image') }}',
            'AlertOfBigSize': '{{ __('masseges.size-of-image-more-than-w-MB') }}'
            },
            editDataButton = document.getElementById('editDataButton'),
            editImageButton = document.getElementById('editImageButton'),
            imageElement = document.getElementById('imageElement'),
            inputFileElement = document.getElementById('inputFileElement'),
            TOKEN = '{{ csrf_token() }}',
            ImageSize = 2 * 1024 * 1024;
        
        if(editDataButton != null) editDataButton.onclick = function () {
            editUserData(editDataButton);
        };
        if(editImageButton != null && inputFileElement != null) editImageButton.onclick = function() {
            editUserImage(inputFileElement);
        };
        if(imageElement != null && inputFileElement != null) imageElement.onclick = function() {
            editUserImage(inputFileElement);
        };
        if(inputFileElement != null && imageElement != null) inputFileElement.onchange = function() {
            saveNewUserImage(inputFileElement, imageElement);
        };
    </script>
@endsection