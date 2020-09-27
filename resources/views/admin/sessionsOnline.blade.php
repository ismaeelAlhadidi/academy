@extends('admin\layouts\adminpanel')

@section('title'){{ __('headers.admin-navbar-sessions') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\offers.css') }}"/>
@endsection

@section('style')
    <style type="text/css">
        .main {
            padding-top: 0px;
        }
        .pagination {
            direction: rtl;
        }
        @media (max-width: 390px) {
            th:nth-of-type(2), td:nth-of-type(2) {
                display: none;
            }
        }
        @media (max-width: 568px) {
            .main {
                font-size: 12px;
            }
            .user-image-in-sessions-table {
                width: 30px;
                height: 30px;
            }
            .text-feild-in-sessions-table, .toggleAcceptButton-in-sessions-table {
                margin-top: 5px;
            }
            .toggleAcceptButton-in-sessions-table {
                padding: 4px 6px;
                font-size: 12px;
            }
        }
    </style>
@endsection


@section('content')
    <div>
        @if($sessions->count() > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('masseges.name') }}</th>
                        <th>{{ __('masseges.session-name') }}</th>
                        <th>{{ __('masseges.session-time') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        <tr>
                            <td><img class="user-image-in-sessions-table" src="{{ asset($session->user->image) }}"/></td>
                            <td><span class="text-feild-in-sessions-table">{{ $session->user->first_name }}</span></td>
                            <td><span class="text-feild-in-sessions-table">{{ $session->sessionOffer->name }}</span></td>
                            <td><span class="text-feild-in-sessions-table">{{ $session->time}}</span></td>
                            <td>
                                @if(! $session->taken)
                                    <button id="SessionOnlineAdmissionButton{{ $session->id }}" 
                                            class="toggleAcceptButton-in-sessions-table"
                                            onclick="RequestSetAdmission({{ $session->id }}, {{ $session->admission }});">
                                    {{ $session->admission ? __('masseges.session-reverse-admission') : __('masseges.session-set-admission') }}
                                    </button>
                                @else 
                                    <canvas class="correct-sign" width="40" height="40"></canvas>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $sessions-> links() }}
        @else
            <div class="empty">{{ __('masseges.no-sessions') }}</div>
        @endif
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js\admin\sessionOnline.js') }}"></script>
    <script type="text/javascript" lang="javascript">
        var lang = [
                /* 00 => */ '{{ __('masseges.general-error') }}',
                /* 01 => */ '{{ __('masseges.ok') }}',
                /* 02 => */ '{{ __('masseges.session-reverse-admission') }}',
                /* 03 => */ '{{ __('masseges.session-set-admission') }}',
                /* 04 => */ '{{ __('masseges.session-set-admission-ask') }}',
                /* 05 => */ '{{ __('masseges.session-reverse-admission-ask') }}',
            ],
            canvasOfTakenSessions = document.getElementsByClassName('correct-sign');
        

        if(typeof(drawCorrectSign) == "function") {
            if(canvasOfTakenSessions != null && typeof(canvasOfTakenSessions) == "object") {
                if(canvasOfTakenSessions.constructor = HTMLCollection) {
                   for(var i = 0; i < canvasOfTakenSessions.length; i++) {
                        canvasOfTakenSessions[i].width = '40';
                        canvasOfTakenSessions[i].height = '40';
                        drawCorrectSign(canvasOfTakenSessions[i], 'red', true);
                   }
                }
            }
        }
    </script>
@endsection
