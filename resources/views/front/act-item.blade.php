<button command="show-modal" commandfor="act-{{ $act['id'] }}" aria-controls="act-{{ $act['id'] }}"
        class="act-item{{ $act['image'] ? ' has-image' : '' }}{{ isset($act['profileContent']) ? ' has-profile' : '' }}"
        onclick="trackEvent('dialog_open', { type: 'act', act: '{{ $act['slug'] }}'})">
    @include('front.act-image', ['act' => $act, 'size' => 'full'])
    <span class="act-item-text">
        {{ $act['name'] }}
        @if($act['subtitle'])
            <small>{{ $act['subtitle'] }}</small>
        @endif
    </span>
</button>

@if(isset($act['profileContent']))
    <dialog id="act-{{ $act['id'] }}" class="dialog act-dialog">
        <button class="dialog-close" command="close" commandfor="act-{{ $act['id'] }}"
                aria-controls="act-{{ $act['id'] }}"
                title="Close">
            <i class="fa-solid fa-close"></i>
        </button>

        <div
            class="act-profile-layout">
            <div class="act-profile-image">
                @include('front.act-image', ['act' => $act, 'size' => 'full'])
            </div>
            <div class="act-profile-content">
                <h2 class="dialog-title">
                    {{ $act['name'] }}
                    @if($act['subtitle'])
                        <small>{{ $act['subtitle'] }}</small>
                    @endif
                </h2>

                @if(count($act['genres']))
                    <ul class="act-profile-genres">
                        @foreach($act['genres'] as $genre)
                            <li>{{ $genre }}</li>
                        @endforeach
                    </ul>
                @endif
                @if (count($act['accolades']['wins']) || count($act['accolades']['buzzers']))
                    <ul class="act-profile-accolades">
                        @foreach($act['accolades']['wins'] as $win)
                            <li>
                                @if($win['is_winner'])
                                    <i class="fa-solid fa-trophy text-gold"></i>
                                @else
                                    <i class="fa-solid fa-ribbon text-primary"></i>
                                @endif
                                {{ $win['text'] }}
                            </li>
                        @endforeach
                        @foreach($act['accolades']['buzzers'] as $buzzer)
                            <li>
                                <i class="fa-solid fa-star text-gold"></i>
                                {{ $buzzer }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="content act-profile-desc">
                    {!! $act['profileContent']['description'] !!}
                </div>
            </div>
        </div>
    </dialog>
@endif
