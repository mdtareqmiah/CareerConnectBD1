@props(['title', 'description', 'actionLabel' => 'Get Started', 'actionRoute' => '#', 'icon' => '✨'])

<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <div class="display-6 mb-3">{{ $icon }}</div>
        <h2 class="h5 mb-2">{{ $title }}</h2>
        <p class="text-muted mb-4">{{ $description }}</p>
        <a href="{{ $actionRoute }}" class="btn btn-primary">{{ $actionLabel }}</a>
    </div>
</div>
