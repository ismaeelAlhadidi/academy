@extends('admin\layouts\adminpanel')

@section('title'){{ __('headers.admin-navbar-app-settings') }}@endsection

@section('style')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\settings.css') }}"/>
@endsection
@section('content')
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div>
                <section class="welcome-about-coach-image no-select">
                    <div>
                        <img id="imageElement" src="{{ (( isset($appInfos['about_coach_image']) ? asset($appInfos['about_coach_image']) : '')) }}"/>
                        <div id="editImageButton"><span>{{ __('masseges.update') }}<!--  <span class="material-icons"> image</span> --></span></div>
                        <input id="inputFileElement" name="about_coach_image" type="file" accept=".png,.jpg,.tif,.gif" style="display: none !important;"/>
                        <footer>{{ __('masseges.the-image-of-welcome-page') }}</footer>
                    </div>
                </section>
                <section class="welcome-about-coach-data">
                    <div><input name="first_statment" type="text" placeholder="{{ __('masseges.first-statment') }}" value="{{ ( ( isset($appInfos['first_statment']) ? $appInfos['first_statment'] : '') ) }}" /></div>
                    <div><input name="about_cach_title" type="text" placeholder="{{ __('masseges.title-of-site-descrption') }}" value="{{ ( ( isset($appInfos['about_cach_title']) ? $appInfos['about_cach_title'] : '') ) }}" /></div>
                    <div><textarea name="about_cach_desc" placeholder="{{ __('masseges.site-descrption') }}">{{ ( isset($appInfos['about_cach_desc']) ? $appInfos['about_cach_desc'] : '' ) }}</textarea></div>
                    <footer><div><button type="submit">{{ __('masseges.save') }}</button></div></footer>
                </section>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript">
        var lang = {
            'selectImage': "{{ __('masseges.select-only-image') }}",
            'yourBrowserNotSupport': "{{ __('masseges.your-browser-not-support-show-images') }}",
        };
        @if(session()->has('msg'))
            showPopUpMassage("{{ session()->get('msg')}}");
            {{ session()->forget('msg') }}
        @endif
    </script>
    <script type="text/javascript" lang="javascript" src="{{ asset('js\admin\settings.js') }}"></script>
@endsection
