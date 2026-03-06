<aside class="lg-sidebar" id="lgSidebar" aria-label="Main navigation">
    <div class="lg-sidebar__brand">
        <span class="lg-sidebar__logo">
            <img src="{{ asset('logo-lg-100-44.svg') }}" alt="LG" class="lg-sidebar__logo-image">
        </span>
        <div>
            <p class="lg-sidebar__brand-label">Plant A</p>
            <h1 class="lg-sidebar__brand-name">Ops Dashboard</h1>
        </div>
    </div>

    <nav class="lg-sidebar__nav">
        <a
            href="{{ route('dashboard.index') }}"
            class="lg-sidebar__link {{ request()->routeIs('dashboard.index') ? 'is-active' : '' }}"
            data-sidebar-close
        >
            <span class="lg-sidebar__link-dot"></span>
            Dashboard geral
        </a>

        <span class="lg-sidebar__link is-disabled" aria-disabled="true">
            <span class="lg-sidebar__link-dot"></span>
            Linha especifica (em breve)
        </span>

        <a
            href="{{ route('welcome') }}"
            class="lg-sidebar__link {{ request()->routeIs('welcome') ? 'is-active' : '' }}"
            data-sidebar-close
        >
            <span class="lg-sidebar__link-dot"></span>
            Sobre
        </a>

    </nav>

    <div class="lg-sidebar__meta">
        <p class="lg-sidebar__meta-label">Periodo base</p>
        <strong>Jan/2026</strong>
    </div>
</aside>
