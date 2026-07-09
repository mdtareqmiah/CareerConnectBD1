@props(['title', 'description' => null, 'backRoute' => null, 'backLabel' => 'Back'])

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $title }}</h1>
        @if ($description)
            <p class="text-muted mb-0">{{ $description }}</p>
        @endif
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if ($backRoute)
            <a href="{{ $backRoute }}" class="btn btn-outline-secondary btn-sm">{{ $backLabel }}</a>
        @endif
        @if (!empty(trim($slot->toHtml())))
            {{ $slot }}
        @endif
    </div>
</div>
