@props(['service'])

<article class="service-card">
    <h3>{{ $service->name }}</h3>
    <p>{{ $service->description }}</p>
    <div class="service-card__meta">
        <span class="meta">⏱ {{ minutes($service->duration_min) }}</span>
        <span class="service-card__price">{{ dram($service->price) }}</span>
    </div>
    <a href="{{ route('services.show', $service) }}" class="btn btn--outline btn--sm">
        Ընտրել ժամ<span class="visually-hidden">՝ {{ $service->name }}</span>
    </a>
</article>
