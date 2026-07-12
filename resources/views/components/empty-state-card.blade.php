@props(['title', 'description', 'actionLabel' => 'Get Started', 'actionRoute' => '#', 'icon' => '✨'])

<div class="card border-0 shadow-soft">
    <div class="card-body text-center py-5">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 56px; height: 56px;">
            <span class="display-6">{{ $icon }}</span>
        </div>
        <h2 class="h5 mb-2">{{ $title }}</h2>
        <p class="text-muted mb-4">{{ $description }}</p>
        <a href="{{ $actionRoute }}" class="btn btn-primary">{{ $actionLabel }}</a>
    </div>
</div>
