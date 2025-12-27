@if (! isset($custom))
    <div class="card">
        @if (isset($title))
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">{{ $title }}</h5>
                    </div>
                    @if (isset($actions))
                        <div>{{ $actions }}</div>
                    @endif
                </div>
            </div>
        @endif

        <div class="card-body">
            {{ $slot }}
        </div>

        @if (isset($footer))
            <div class="card-footer">
                {{ $footer }}
            </div>
        @endif
    </div>
@else
    {{ $custom }}
@endif
