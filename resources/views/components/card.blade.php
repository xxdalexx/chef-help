<div class="card {{ $cardClass ?? '' }}">
    <h5 class="card-header {{ $headerClass ?? '' }}">{{ $title }}</h5>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
