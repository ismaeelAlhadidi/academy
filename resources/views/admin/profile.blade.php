@extends('admin\layouts\adminpanel')

@section('title'){{ auth('admin')->user()->username }}@endsection

@section('style')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\profile.css') }}"/>
@endsection

@section('content')
    <div class="container">
        <header><h3>{{ __('masseges.admins') }}</h3><button id="openAddTemplateButton">{{ __('masseges.admin-add') }}</button></header>
        <table class="table table-striped">    
            <thead>
                <tr>
                    <th>{{ __('input.username') }}</th>
                    <th>{{ __('input.email') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($admins as $admin)
                <tr id="record{{ $admin->id }}">
                    <td>{{ $admin->username }}</td>
                    <td>{{ $admin->email }}</td>
                    <td><button class="default-button" onclick="openUpdateTemplate('{{ $admin->email }}','{{ $admin->username }}')">{{ __('masseges.update') }}</button><button class="default-button" onclick="deleteAdmin('{{ $admin->id }}')">{{ __('masseges.delete') }}</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="templateAddOrUpdateAdminData" class="pop-up-template add-admin-template no-select" style="display: none;">
        <div class="center">
            <header><div><canvas id="exitButtonCanvasOfTemplateAddOrUpdateAdminData"  class="center" width="25" height="25"></canvas></div></header>
            <div>
                <div><span>{{ __('input.email') }}</span><input type="text" id="inputAdminEmail" class="default-input" placeholder="{{ __('input.email') }}" /></div>
                <div><span>{{ __('input.username') }}</span><input type="text" id="inputAdminUserName" class="default-input" placeholder="{{ __('input.username') }}" /></div>
                <div><span>{{ __('input.password') }}</span><input type="password" id="inputAdminPassword" class="default-input" placeholder="{{ __('input.password') }}" /></div>
                <div><span>{{ __('input.password') }}</span><input type="password" id="repeatAdminPassword" class="default-input" placeholder="{{ __('input.password') }}" /></div>
                <section id="alertInTemplateAddOrUpdateAdminData"></section>
                <footer><button id="addAdminButton" class="default-button">{{ __('masseges.add') }}</button><button id="updateAdminButton" class="default-button" style="display: none;">{{ __('masseges.update') }}</button></footer>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript">
        var lang = {
                'generalError': '{{ __("masseges.general-error") }}',
                'passwordLessThanMin': '{{ __("masseges.password-more-than-8") }}',
                'passwordDeferent': '{{ __("masseges.password-deferent") }}',
                'addOk': '{{ __("masseges.add-ok") }}',
                'updateOk': '{{ __("masseges.update-ok") }}',
                'askToDeleteMassege': '{{ __("masseges.ask-to-delete-admin") }}',
                'ok': '{{ __("masseges.ok") }}',
                'deleteOk': '{{ __("masseges.delete-ok") }}',
                'update': '{{ __("masseges.update") }}',
                'delete': '{{ __("masseges.delete") }}',
            },
            templateAddOrUpdateAdminData = document.getElementById('templateAddOrUpdateAdminData')
            exitButtonCanvasOfTemplateAddOrUpdateAdminData = document.getElementById('exitButtonCanvasOfTemplateAddOrUpdateAdminData'),
            inputAdminEmail = document.getElementById('inputAdminEmail'),
            inputAdminUserName = document.getElementById('inputAdminUserName'),
            inputAdminPassword = document.getElementById('inputAdminPassword'),
            repeatAdminPassword = document.getElementById('repeatAdminPassword'),
            alertInTemplateAddOrUpdateAdminData = document.getElementById('alertInTemplateAddOrUpdateAdminData'),
            TOKEN = "{{ csrf_token() }}",
            AddAdminUrl = "{{ route('admin.add') }}",
            UpdateAdminUrl = "{{ route('admin.update') }}",
            DeleteAdminUrl = "{{ route('admin.delete') }}",
            tableBody = document.getElementById('tableBody');

        @if(session()->has('msg'))
            showPopUpMassage("{{ session()->get('msg')}}");
            {{ session()->forget('msg') }}
        @endif
    </script>
    <script type="text/javascript" lang="javascript" src="{{ asset('js\admin\profile.js') }}"></script>
@endsection