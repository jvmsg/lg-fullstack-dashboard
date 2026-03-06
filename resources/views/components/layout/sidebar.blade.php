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
        @if(request()->routeIs('dashboard.index'))
            <span class="lg-sidebar__link is-active" aria-current="page">
                <span class="lg-sidebar__link-dot"></span>
                Dashboard geral
            </span>
        @else
            <a
                href="{{ route('dashboard.index') }}"
                class="lg-sidebar__link"
                data-sidebar-close
            >
                <span class="lg-sidebar__link-dot"></span>
                Dashboard geral
            </a>
        @endif

        <span class="lg-sidebar__link is-disabled" aria-disabled="true">
            <span class="lg-sidebar__link-dot"></span>
            <svg class="lg-sidebar__link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
            </svg>
            Linha especifica (em breve)
        </span>

        @if(request()->routeIs('welcome'))
            <span class="lg-sidebar__link is-active" aria-current="page">
                <span class="lg-sidebar__link-dot"></span>
                Sobre
            </span>
        @else
            <a
                href="{{ route('welcome') }}"
                class="lg-sidebar__link"
                data-sidebar-close
            >
                <span class="lg-sidebar__link-dot"></span>
                Sobre
            </a>
        @endif

    </nav>

    <div class="lg-sidebar__meta">
        <p class="lg-sidebar__meta-label">Periodo base</p>
        <strong>Jan/2026</strong>
    </div>
</aside>
