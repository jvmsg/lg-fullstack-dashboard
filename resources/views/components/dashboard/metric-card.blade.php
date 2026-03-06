<div class="col-12 col-md-6 col-xl-3 mb-4">
    <article class="lg-metric card h-100">
        <div class="card-body">
            <p class="lg-metric__label">{{ $line }}</p>
            <p class="lg-metric__value">{{ number_format($efficiency, 2) }}%</p>
            @if ($trend)
                <p class="lg-metric__delta {{ $trendClass }}">{{ $trend }}</p>
            @endif

            <div class="lg-metric__stats">
                <span>Produzidas: <strong>{{ number_format($produced, 0, ',', '.') }}</strong></span>
                <span>Defeituosas: <strong>{{ number_format($defective, 0, ',', '.') }}</strong></span>
            </div>
        </div>
    </article>
</div>
