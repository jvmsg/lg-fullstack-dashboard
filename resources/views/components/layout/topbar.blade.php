<header class="lg-topbar">
    <button
        class="lg-topbar__toggle"
        type="button"
        data-sidebar-toggle
        aria-label="Toggle sidebar"
        aria-controls="lgSidebar"
    >
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="lg-topbar__titles">
        <p class="lg-topbar__eyebrow">LG Electronics - Planta A</p>
        <h2 class="lg-topbar__title">@yield('page_title', 'Dashboard')</h2>
        <p class="lg-topbar__subtitle">@yield('page_subtitle', 'Visao operacional de eficiencia')</p>
    </div>

    <div class="lg-topbar__meta">
        <span class="lg-topbar__meta-label">Atualizacao</span>
        <strong>{{ now()->format('d/m/Y') }}</strong>
    </div>
</header>
