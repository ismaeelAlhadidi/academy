<div id="notifcation" class="notifcation notifcation-on-aside-template no-select" style="display: none;">
	<header></header>
	<div>
		@if(session()->has('notifcations'))
            @forelse(session()->get('notifcations') as $notifcation)
                <div class="transition{{ $notifcation['readed'] ? '' : ' not-readed'}}" 
                    id="notifcation{{ $notifcation['type'] . $notifcation['id'] }}"   
                    @auth('admin')
                        onclick="showNotifcation({{ $notifcation['id'] }},'{{ $notifcation['type'] }}',
                        [
                            /* 00 => */ '{{ __('masseges.general-error') }}',
                            /* 01 => */ '{{ __('masseges.hidden')}}',
                            /* 02 => */ '{{ __('masseges.visible')}}',
                            /* 03 => */ '{{ __('masseges.session-reverse-admission') }}',
                            /* 04 => */ '{{ __('masseges.session-set-admission') }}',
                            /* 05 => */ '{{ __('masseges.session-set-admission-ask') }}',
                            /* 06 => */ '{{ __('masseges.session-reverse-admission-ask') }}',
                            /* 07 => */ '{{ __('masseges.ok') }}',
                        ]);"
                    @else 
                        onclick="showUserNotifcation({{ $notifcation['id'] }},'{{ $notifcation['type'] }}', {
                            'generalError': '{{ __('masseges.general-error') }}',
                            'goToThisReplay': '{{ __('notifcations.go-to-the-replay') }}',
                            'ok': '{{ __('masseges.ok') }}',
                            'date': '{{ __('masseges.date') }}',
                            'time': '{{ __('masseges.time') }}',
                            'SessionNotForUseAlert': '{{ __('masseges.session-deleted') }}',
                            'TOKEN': '{{ csrf_token() }}'
                        });"
                    @endauth > <!-- this lang array of needed statement from server to use it with javascript -->
                    <img src="{{ $notifcation['image'] }}" />
                    <span>{{ Date('F j, Y, g:i a',strtotime($notifcation['time'])) }}</span>
                    <span class="text">{{ $notifcation['content'] }}</span>
                </div>
            @empty
				<section>{{ __('notifcations.no-new-notifcations') }}</section>
            @endforelse
            {{ session()->forget('notifcations') }}
        @else
			<section>{{ __('notifcations.no-new-notifcations') }}</section>
		@endif
	</div>
</div>
<div id="notifcationTemplate" class="pop-up-template template-of-this-playlist notifcations-template" style="display: none;">
    <header><div><canvas id="exitButtonCanvasOfNotifcationTemplate" width="25" height="25"></canvas></div></header>
    <div id="notifcationTemplateDiv">
    </div>
</div>